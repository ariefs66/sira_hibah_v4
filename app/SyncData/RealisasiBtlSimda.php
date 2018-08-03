<?php

namespace App\SyncData;

use App\Model\RealisasiBtl as MRealisasi;
use App\Model\SKPD;
use Log;
use DB;

class RealisasiBtlSimda
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

            foreach ($listSkpd as $key => $skpd) {
                $realisasiSimda = $this->getFromSimda($skpd->SKPD_KODE);
                $blSira = $this->getFromSira($skpd->SKPD_KODE);
                $mappingRealisasi = $this->mappingRealisasiSimda($realisasiSimda, $blSira);
                $result = $this->processSync($mappingRealisasi);
                $result["SKPD_KODE"] = $skpd->SKPD_KODE;
                $result["SKPD_ID"] = $skpd->SKPD_ID;
                $resultFinal[] = $result;
            }
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
        $url="https://mantra.bandung.go.id/mantra/json/bpka/realisasi/belanja_tidak_langsung";
        $accesskey="ylchtxqdb6";
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
    public function getFromSira($kode_skpd)
    {
        $query = '
        SELECT
            "BELANJA"."PAGU",
            "BELANJA"."BTL_ID",
            "BELANJA"."REKENING_ID",
            "BELANJA"."KODE_REK_BTL",
            "BELANJA"."REKENING_NAMA"
            FROM (
                SELECT
                  SUM("SUB_BELANJA"."BTL_TOTAL") AS "PAGU",
                  "SUB_BELANJA"."BTL_ID",
                  "SUB_BELANJA"."REKENING_ID",
                  "SUB_BELANJA"."KODE_REK_BTL",
                  "SUB_BELANJA"."REKENING_NAMA"
                FROM
                  (
                    select
                      "BTL"."BTL_ID",
                      "BTL"."REKENING_ID",
                      "REK"."REKENING_NAMA",
                      concat(
                        \'0.00.\',
                        "SKPD"."SKPD_KODE",
                        \'.\',
                        "SUB"."SUB_KODE",
                        \'.00.000.\',
                        "REK"."REKENING_KODE"
                      ) as "KODE_REK_BTL",
                      "BTL"."BTL_TOTAL"
                    from
                      "BUDGETING"."DAT_BTL_PERUBAHAN" as "BTL"
                      join "REFERENSI"."REF_REKENING" as "REK" on "BTL"."REKENING_ID" = "REK"."REKENING_ID"
                      join "REFERENSI"."REF_SKPD" as "SKPD" on "BTL"."SKPD_ID" = "SKPD"."SKPD_ID"
                      join "REFERENSI"."REF_SUB_UNIT" as "SUB" on "BTL"."SUB_ID" = "SUB"."SUB_ID"
                    WHERE
                      "BTL"."BTL_TAHUN" = '.$this->tahun.'
                      and "SKPD"."SKPD_KODE" =  \''.$kode_skpd.'\'
                  ) as "SUB_BELANJA"
                group by
                  "SUB_BELANJA"."BTL_ID",
                  "SUB_BELANJA"."REKENING_ID",
                  "SUB_BELANJA"."REKENING_NAMA",
                  "SUB_BELANJA"."KODE_REK_BTL"
            ) AS "BELANJA"
        ';
        $data = DB::connection($this->connection)->select($query);
        return $data;
    }


    private function mappingRealisasiSimda($realisasiSimda, $blSira)
    {
        $data = array();

        foreach ($blSira as $key => $item) {
            $blRealisasi = $item;
            if (isset($realisasiSimda[$blRealisasi->KODE_REK_BTL])) {
                $realisasi = $realisasiSimda[$blRealisasi->KODE_REK_BTL];
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
            $realisasi = MRealisasi::where('BTL_ID', $item->BTL_ID)
                ->where('REKENING_ID', $item->REKENING_ID)
                ->first();
            if ($realisasi) {
                if ($realisasi->REALISASI_TOTAL != $item->REALISASI) {
                    MRealisasi::where('BTL_ID', $item->BTL_ID)
                    ->where('REKENING_ID', $item->REKENING_ID)
                    ->update(['REALISASI_TOTAL' => $item->REALISASI]);
                    $countUpdate+=1;
                }
            } else {
                $dataInsert[] = array(
                    'BTL_ID' => $item->BTL_ID,
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
}
