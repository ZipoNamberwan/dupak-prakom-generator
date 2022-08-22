<?php

namespace Database\Seeders;

use App\Models\ButirKegiatan;
use App\Models\InfraType;
use App\Models\Location;
use App\Models\Room;
use App\Models\SubUnsur;
use App\Models\Supervisor;
use App\Models\Unsur;
use App\Models\UserData;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserData::create(
            [
                'name' => 'Wahyu Razi Indrawan, SST',
                'nip' => '19930402 201602 1 001',
                'grade' => 'Penata Muda Tk1/IIIB',
                'pos' => 'Pranata Komputer Ahli Pertama'
            ]
        );

        Supervisor::create([
            'name' => 'Syaiful Rahman, S.E, M.T',
            'nip' => '19640621 198802 1 001',
            'pos' => 'Kepala BPS Kabupaten Probolinggo',
            'is_preference' => true
        ]);

        Location::create([
            'name' => 'BPS Kabupaten Probolinggo',
        ]);

        Room::create([
            'name' => 'IPDS',
        ]);

        Room::create([
            'name' => 'Sosial',
        ]);

        Room::create([
            'name' => 'Nerwilis/Distribusi',
        ]);

        Room::create([
            'name' => 'Produksi',
        ]);

        Room::create([
            'name' => 'PST',
        ]);

        Room::create([
            'name' => 'Belakang/KSK',
        ]);

        Room::create([
            'name' => 'Lobby',
        ]);

        $unsur = Unsur::create([
            'name' => 'Infrastruktur Teknologi Informasi',
            'code' => 'II'
        ]);

        $subunsur = SubUnsur::create([
            'name' => 'Manajemen Infrastruktur TI',
            'code' => 'II.B',
            'unsur_id' => $unsur->id
        ]);

        ButirKegiatan::create([
            'name' => 'Melakukan Deteksi Dan Atau Perbaikan Terhadap Permasalahan Infrastruktur TI',
            'code' => 'II.B.12',
            'subunsur_id' => $subunsur->id,
            'credit' => 0.02
        ]);

        InfraType::create(
            ['name' => 'Komputer']
        );
        InfraType::create(
            ['name' => 'Printer']
        );
        InfraType::create(
            ['name' => 'Laptop']
        );
        InfraType::create(
            ['name' => 'CCTV']
        );
        InfraType::create(
            ['name' => 'Router']
        );
        InfraType::create(
            ['name' => 'Tipe Lainnya']
        );
    }
}
