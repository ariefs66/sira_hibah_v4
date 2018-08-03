<?php

namespace App\Console\Commands\Integrasi;

use Illuminate\Console\Command;
use App\SyncData\RealisasiSimda;
use Log;

class GetRealisasiSimda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integrasi:sync-realisasi-simda {--tahun=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync realisasi dari simda';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tahun = $this->option('tahun');
        if ($tahun < 2016) {
            echo "Option tahun tidak valid\n";
            die();
        }
        $realisasi = new RealisasiSimda($tahun);
        $data = $realisasi->sync();
        Log::info($data);
    }
}
