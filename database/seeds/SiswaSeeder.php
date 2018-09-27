<?php

use Illuminate\Database\Seeder;
use App\siswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siswa = Siswa::create(['nama'=>'mega','kelas'=>'12 rpl','jk'=>'perempuan','alamat'=>'sayuran','eskul'=>'voli']);
    }
}