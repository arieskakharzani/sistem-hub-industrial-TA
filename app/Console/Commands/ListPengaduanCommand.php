<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pengaduan;

class ListPengaduanCommand extends Command
{
    protected $signature = 'pengaduan:list';
    protected $description = 'List semua pengaduan';

    public function handle()
    {
        $pengaduans = Pengaduan::select('pengaduan_id', 'nomor_pengaduan', 'status')->get();

        $this->info('Daftar Pengaduan:');
        $this->info('================');

        foreach ($pengaduans as $pengaduan) {
            $this->line($pengaduan->pengaduan_id . ' - ' . $pengaduan->nomor_pengaduan . ' (' . $pengaduan->status . ')');
        }

        return 0;
    }
}
