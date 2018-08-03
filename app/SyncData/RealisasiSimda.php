<?php

namespace App\SyncData;

use App\Model\Realisasi as MRealisasi;
use App\Model\RealisasiHistoryAgregation as MRealisasiHistoryAgregation;
use App\Model\RealisasiHistory as MRealisasiHistory;
use DB;
use App\Model\SKPD;
use Log;

class RealisasiSimda
{
    public $connection;
    public $tahun;
    public $kode_skpd;
    public $now;
    public $date;

    public function __construct($tahun)
    {
        $this->connection = "pgsql";
        $this->tahun = $tahun;
        $this->now = date('Y-m-d H:i:s');
        $this->date = date('Y-m-d');
    }
    /*
        fungsi untuk sinkronisasi data realisasi simda
        yang didapatkan dari database simda melalui mantra
    */
    public function sync()
    {
        DB::beginTransaction();
        try {
            $listSkpd = SKPD::select('SKPD_ID', 'SKPD_KODE', 'SKPD_NAMA')->where('SKPD_TAHUN', $this->tahun)->get();
            $resultFinal = array();
            $realisasiHistory = MRealisasiHistory::first();
            if (!$realisasiHistory) {
                $this->insertHistory(true);
            }

            foreach ($listSkpd as $key => $skpd) {
                $realisasiSimda = $this->getFromSimda($skpd->SKPD_KODE);
                $blSira = $this->getFromSira($skpd->SKPD_KODE);
                $mappingRealisasi = $this->mappingRealisasiSimda($realisasiSimda, $blSira);
                $result = $this->processSync($mappingRealisasi);
                $result["SKPD_KODE"] = $skpd->SKPD_KODE;
                $result["SKPD_ID"] = $skpd->SKPD_ID;
                $resultFinal[] = $result;
            }
            $this->insertHistory(false);
            $this->insertResultFinal($resultFinal);
            DB::commit();
            $data = array(
                'http_code' => 200,
                'message' => 'Sinkronisasi data realisasi berhasil dilakukan.',
                'data' => $resultFinal
            );
        } catch (\Exception $e) {
            DB::rollback();
            $data = array(
                'http_code' => 500,
                'message' => 'Sinkronisasi data realisasi gagal dilakukan. '.$e->getMessage()
            );
        }
        return $data;
    }

    /*
        fungsi untuk mengambil data realisasi dari Api realisasi mantra
    */
    public function getFromSimda($kode_skpd)
    {
        $url="https://mantra.bandung.go.id/mantra/json/bpka/realisasi/belanja";
        $accesskey="14h1re58js";
        $pardata=array(
            "tahun" => urlencode($this->tahun),
            "kode_skpd" => urlencode($kode_skpd)
        );
        $par="/".http_build_query($pardata);

        $options=array('http'=>
            array(
                'method'=>"GET",
                'header'=>"User-Agent:MANTRA\r\nAccessKey:$accesskey"
            )
        );
        $context = stream_context_create($options);
        $content = file_get_contents($url.$par, false, $context);
        $content = json_decode($content);
        $data = $content->response->data->data;
        $tmp = array();
        if ($data) {
            foreach ($data as $key => $itemData) {
                $tmp[$itemData->kode_rekening_belanja] = $itemData->realisasi;
            }
            $data = $tmp;
        }
        return $data;
    }
    /*
        fungsi untuk mengambil data realisasi sira / lokal
    */
    public function getFromSira($kode_skpd, $isForView = false)
    {
        if ($isForView) {
            $query = '
            SELECT 
            coalesce("REAL"."REALISASI_TOTAL", 0) AS "REALISASI_TOTAL",
            "BELANJA"."PAGU",
            "BELANJA"."BL_ID",
            "BELANJA"."REKENING_ID",
            "BELANJA"."KODE_REK_BL",
            "BELANJA"."KEGIATAN_NAMA",
            "BELANJA"."PROGRAM_NAMA",
            "BELANJA"."REKENING_NAMA",
            "BELANJA"."KODE_KEGIATAN",
            "BELANJA"."KODE_PROGRAM",
            "BELANJA"."PAGU"-coalesce("REAL"."REALISASI_TOTAL", 0) AS "SELISIH"
            FROM (
                SELECT
                  SUM("SUB_BELANJA"."RINCIAN_TOTAL") AS "PAGU",
                  "SUB_BELANJA"."BL_ID",
                  "SUB_BELANJA"."REKENING_ID",
                  "SUB_BELANJA"."KODE_REK_BL",
                  "SUB_BELANJA"."KEGIATAN_NAMA",
                  "SUB_BELANJA"."PROGRAM_NAMA",
                  "SUB_BELANJA"."REKENING_NAMA",
                  "SUB_BELANJA"."KODE_KEGIATAN",
                  "SUB_BELANJA"."KODE_PROGRAM"
                FROM
                  (
                    select
                      "BL"."BL_ID",
                      "RINCIAN"."REKENING_ID",
                      "REK"."REKENING_NAMA",
                      concat(
                        "URU"."URUSAN_KODE",
                        \'.\',
                        "SKPD"."SKPD_KODE",
                        \'.\',
                        "SUB"."SUB_KODE",
                        \'.\',
                        "PROG"."PROGRAM_KODE",
                        \'.\',
                        "KEG"."KEGIATAN_KODE",
                        \'.\',
                        "REK"."REKENING_KODE"
                      ) as "KODE_REK_BL",
                      concat(
                        "SKPD"."SKPD_KODE",
                        \'.\',
                        "SUB"."SUB_KODE",
                        \'.\',
                        "PROG"."PROGRAM_KODE",
                        \'.\',
                        "KEG"."KEGIATAN_KODE"
                      ) as "KODE_KEGIATAN",
                      concat(
                        "SKPD"."SKPD_KODE",
                        \'.\',
                        "SUB"."SUB_KODE",
                        \'.\',
                        "PROG"."PROGRAM_KODE"
                      ) as "KODE_PROGRAM",
                      "RINCIAN"."RINCIAN_TOTAL",
                      "KEG"."KEGIATAN_NAMA",
                      "PROG"."PROGRAM_NAMA"
                    from
                      "BUDGETING"."DAT_BL_PERUBAHAN" as "BL"
                      join "BUDGETING"."DAT_RINCIAN_PERUBAHAN" as "RINCIAN" on "BL"."BL_ID" = "RINCIAN"."BL_ID"
                      join "REFERENSI"."REF_REKENING" as "REK" on "RINCIAN"."REKENING_ID" = "REK"."REKENING_ID"
                      join "REFERENSI"."REF_SKPD" as "SKPD" on "BL"."SKPD_ID" = "SKPD"."SKPD_ID"
                      join "REFERENSI"."REF_SUB_UNIT" as "SUB" on "BL"."SUB_ID" = "SUB"."SUB_ID"
                      join "REFERENSI"."REF_KEGIATAN" as "KEG" on "BL"."KEGIATAN_ID" = "KEG"."KEGIATAN_ID"
                      join "REFERENSI"."REF_PROGRAM" as "PROG" on "KEG"."PROGRAM_ID" = "PROG"."PROGRAM_ID"
                      join "REFERENSI"."REF_URUSAN" as "URU" on "PROG"."URUSAN_ID" = "URU"."URUSAN_ID"
                    WHERE
                      "BL"."BL_TAHUN" = '.$this->tahun.'
                      and "BL"."BL_DELETED" = 0
                      and "BL"."BL_VALIDASI" = 1
                      and "SKPD"."SKPD_KODE" = \''.$kode_skpd.'\'
                  ) as "SUB_BELANJA"
                group by
                  "SUB_BELANJA"."BL_ID",
                  "SUB_BELANJA"."REKENING_ID",
                  "SUB_BELANJA"."REKENING_NAMA",
                  "SUB_BELANJA"."KODE_REK_BL",
                  "SUB_BELANJA"."KEGIATAN_NAMA",
                  "SUB_BELANJA"."PROGRAM_NAMA",
                  "SUB_BELANJA"."KODE_KEGIATAN",
                  "SUB_BELANJA"."KODE_PROGRAM"
            ) AS "BELANJA"
            LEFT JOIN "BUDGETING"."DAT_BL_REALISASI" as "REAL" on "BELANJA"."BL_ID"  = "REAL". "BL_ID"and "BELANJA"."REKENING_ID"  = "REAL". "REKENING_ID"
            ORDER BY "BELANJA"."KODE_REK_BL" ASC
            ';
        } else {
            $query = '
                select
                  "BL_ID",
                  "REKENING_ID",
                  "KODE_REK_BL"
                from
                  (
                    select
                      "BL"."BL_ID",
                      "RINCIAN"."REKENING_ID",
                      concat(
                        "URU"."URUSAN_KODE",
                        \'.\',
                        "SKPD"."SKPD_KODE",
                        \'.\',
                        "SUB"."SUB_KODE",
                        \'.\',
                        "PROG"."PROGRAM_KODE",
                        \'.\',
                        "KEG"."KEGIATAN_KODE",
                        \'.\',
                        "REK"."REKENING_KODE"
                      ) as "KODE_REK_BL",
                      "RINCIAN"."RINCIAN_TOTAL"
                    from
                      "BUDGETING"."DAT_BL_PERUBAHAN" as "BL"
                      join "BUDGETING"."DAT_RINCIAN_PERUBAHAN" as "RINCIAN" on "BL"."BL_ID" = "RINCIAN"."BL_ID"
                      join "REFERENSI"."REF_REKENING" as "REK" on "RINCIAN"."REKENING_ID" = "REK"."REKENING_ID"
                      join "REFERENSI"."REF_SKPD" as "SKPD" on "BL"."SKPD_ID" = "SKPD"."SKPD_ID"
                      join "REFERENSI"."REF_SUB_UNIT" as "SUB" on "BL"."SUB_ID" = "SUB"."SUB_ID"
                      join "REFERENSI"."REF_KEGIATAN" as "KEG" on "BL"."KEGIATAN_ID" = "KEG"."KEGIATAN_ID"
                      join "REFERENSI"."REF_PROGRAM" as "PROG" on "KEG"."PROGRAM_ID" = "PROG"."PROGRAM_ID"
                      join "REFERENSI"."REF_URUSAN" as "URU" on "PROG"."URUSAN_ID" = "URU"."URUSAN_ID"
                    WHERE
                      "BL"."BL_TAHUN" = '.$this->tahun.'
                      and "BL"."BL_DELETED" = 0
                      and "BL"."BL_VALIDASI" = 1
                      and "SKPD"."SKPD_KODE" = \''.$kode_skpd.'\'
                  ) as "BL_SUB_QUERY"
                GROUP BY
                  "BL_ID",
                  "REKENING_ID",
                  "KODE_REK_BL"
            ';
        }
        $data = DB::connection($this->connection)->select($query);
        return $data;
    }


    private function mappingRealisasiSimda($realisasiSimda, $blSira)
    {
        $data = array();

        foreach ($blSira as $key => $item) {
            $blRealisasi = $item;
            if (isset($realisasiSimda[$blRealisasi->KODE_REK_BL])) {
                $realisasi = $realisasiSimda[$blRealisasi->KODE_REK_BL];
                $blRealisasi->REALISASI = $realisasi;
                $data[] = $blRealisasi;
            }
        }
        return $data;
    }

    private function processSync($data)
    {
        $countUpdate = 0;
        $countInsert = 0;
        $dataInsert = array();
        $countTotal = count($data);
        foreach ($data as $key => $item) {
            $realisasi = MRealisasi::where('BL_ID', $item->BL_ID)
                ->where('REKENING_ID', $item->REKENING_ID)
                ->first();
            if ($realisasi) {
                if ($realisasi->REALISASI_TOTAL != $item->REALISASI) {
                    //$tmp[]= $realisasi->REALISASI_ID."--".$realisasi->REALISASI_TOTAL." --- data baru : ".$item->REALISASI;
                    MRealisasi::where('BL_ID', $item->BL_ID)
                    ->where('REKENING_ID', $item->REKENING_ID)
                    ->update(['REALISASI_TOTAL' => $item->REALISASI]);
                    $countUpdate+=1;
                }
            } else {
                $dataInsert[] = array(
                    'BL_ID' => $item->BL_ID,
                    'REKENING_ID' => $item->REKENING_ID,
                    'REALISASI_TOTAL' => $item->REALISASI
                );
                $countInsert+=1;
            }
        }
        if ($dataInsert) {
            MRealisasi::insert($dataInsert);
        }

        $data = array(
            "count_update" => $countUpdate,
            "count_insert" => $countInsert,
            "count_total" => $countTotal
        );
        return $data;
    }

    private function insertResultFinal($data)
    {
        $dataInsert = array();
        foreach ($data as $key => $item) {
            $history = MRealisasiHistoryAgregation::where('TAHUN', $this->tahun)
            ->where('SKPD_ID', $item['SKPD_ID'])
            ->whereDate('TANGGAL_GET_REALISASI', $this->date)
            ->first();

            if ($history) {
                MRealisasiHistoryAgregation::where('REALISASI_HISTORY_AGREGATION_ID', $history->REALISASI_HISTORY_AGREGATION_ID)
                ->update([
                    'COUNT_UPDATE' => $item['count_update'],
                    'COUNT_INSERT' => $item['count_insert'],
                    'COUNT_TOTAL' => $item['count_total'],
                    'updated_at' => $this->now
                ]);
            } else {
                $dataInsert[] = array(
                    'TAHUN' => $this->tahun,
                    'TANGGAL_GET_REALISASI' => $this->now,
                    'SKPD_ID' => $item['SKPD_ID'],
                    'COUNT_UPDATE' => $item['count_update'],
                    'COUNT_INSERT' => $item['count_insert'],
                    'COUNT_TOTAL' => $item['count_total'],
                    'created_at' => $this->now,
                    'updated_at' => $this->now
                );
            }
        }
        if ($dataInsert) {
            MRealisasiHistoryAgregation::insert($dataInsert);
        }
    }

    private function insertHistory($isFirst)
    {
        if ($isFirst) {
            $realisasi = MRealisasi::select('REALISASI_ID', 'BL_ID', 'REKENING_ID', 'REALISASI_TOTAL')->get()->toArray();
            MRealisasiHistory::insert($realisasi);
        } else {
            $dataInsert = array();
            $existHistory = MRealisasiHistory::whereDate('TANGGAL_GET_REALISASI', $this->date)->get();
            if (!$existHistory->isEmpty()) {
                $realisasi = MRealisasi::select('REALISASI_ID', 'BL_ID', 'REKENING_ID', 'REALISASI_TOTAL')->get();
                $dataInsert=array();
                foreach ($realisasi as $key => $item) {
                    $history = MRealisasiHistory::whereDate('TANGGAL_GET_REALISASI', $this->date)->where('REALISASI_ID', $item->REALISASI_ID)->first();
                    if ($history) {
                        MRealisasiHistory::where('REALISASI_HISTORY_ID', $history->REALISASI_HISTORY_ID)->update(['REALISASI_TOTAL' => $item->REALISASI, 'updated_at' => $this->now]);
                    } else {
                        $dataInsert[] = array(
                            'REALISASI_ID' => $item->REALISASI_ID,
                            'BL_ID' => $item->BL_ID,
                            'REKENING_ID' => $item->REKENING_ID,
                            'REALISASI_TOTAL' => $item->REALISASI_TOTAL,
                            'TANGGAL_GET_REALISASI' => $this->now
                        );
                    }
                }

                if ($dataInsert) {
                    MRealisasiHistory::insert($dataInsert);
                }
            } else {
                $realisasi = MRealisasi::select('REALISASI_ID', 'BL_ID', 'REKENING_ID', 'REALISASI_TOTAL')->get()->toArray();
                foreach ($realisasi as $key => $item) {
                    $realisasi[$key]['TANGGAL_GET_REALISASI'] = $this->now;
                    /*$realisasi[$key]['created_at'] = $this->now;
                    $realisasi[$key]['updated_at'] = $this->now;*/
                }
                MRealisasiHistory::insert($realisasi);
            }
        }
    }
}
