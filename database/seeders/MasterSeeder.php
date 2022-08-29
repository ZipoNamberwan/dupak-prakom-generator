<?php

namespace Database\Seeders;

use App\Models\ButirKegiatan;
use App\Models\InfraType;
use App\Models\Location;
use App\Models\Room;
use App\Models\ServiceMedia;
use App\Models\ServiceType;
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

        $iii = Unsur::create([
            'name' => 'Sistem Informasi dan Multimedia',
            'code' => 'III'
        ]);
        $iiic = SubUnsur::create([
            'name' => 'Area TI Spesial/Khusus',
            'code' => 'III.C',
            'unsur_id' => $iii->id
        ]);
        $ii = Unsur::create([
            'name' => 'Infrastruktur Teknologi Informasi',
            'code' => 'II'
        ]);
        $i = Unsur::create([
            'name' => 'Infrastruktur Teknologi Informasi',
            'code' => 'I'
        ]);

        $iib = SubUnsur::create([
            'name' => 'Manajemen Infrastruktur TI',
            'code' => 'II.B',
            'unsur_id' => $ii->id
        ]);
        $ic = SubUnsur::create([
            'name' => 'Pengelolaan Data (Data Management)',
            'code' => 'I.C',
            'unsur_id' => $i->id
        ]);
        $ib = SubUnsur::create([
            'name' => 'Manajemen Layanan TI',
            'code' => 'I.B',
            'unsur_id' => $i->id
        ]);

        ButirKegiatan::create([
            'name' => 'Mengelola Permintaan Dan Layanan Teknologi Informasi',
            'code' => 'I.B.21',
            'subunsur_id' => $ib->id,
            'credit' => 0.150
        ]);
        ButirKegiatan::create([
            'name' => 'Melakukan Deteksi Dan Atau Perbaikan Terhadap Permasalahan Infrastruktur TI',
            'code' => 'II.B.12',
            'subunsur_id' => $iib->id,
            'credit' => 0.030
        ]);
        ButirKegiatan::create([
            'name' => 'Melakukan Pemasangan Infrastruktur TI',
            'code' => 'II.B.9',
            'subunsur_id' => $iib->id,
            'credit' => 0.165
        ]);
        ButirKegiatan::create([
            'name' => 'Melakukan Pemeliharaan Infrastruktur TI',
            'code' => 'II.B.8',
            'subunsur_id' => $iib->id,
            'credit' => 0.060
        ]);
        ButirKegiatan::create([
            'name' => 'Melakukan Backup Atau Pemulihan Data',
            'code' => 'I.C.39',
            'subunsur_id' => $ic->id,
            'credit' => 0.020
        ]);
        ButirKegiatan::create([
            'name' => 'Membuat Obyek Multimedia Kompleks Dengan Peranti Lunak',
            'code' => 'III.C.8',
            'subunsur_id' => $iiic->id,
            'credit' => 0.165
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

        ServiceType::create([
            'name' => 'Perbaikan Jaringan Internet',
            'type' => 'fix'
        ]);
        ServiceType::create([
            'name' => 'Perbaikan Infrastruktur TI',
            'type' => 'fix'
        ]);
        ServiceType::create([
            'name' => 'Lainnya',
            'type' => 'fix'
        ]);
        ServiceType::create([
            'name' => 'Peminjaman Infrastruktur TI',
            'type' => 'request'
        ]);
        ServiceType::create([
            'name' => 'Peta',
            'type' => 'request'
        ]);
        ServiceType::create([
            'name' => 'Aksesoris IT',
            'type' => 'request'
        ]);
        ServiceType::create([
            'name' => 'Cartridge/Toner/Tinta',
            'type' => 'request'
        ]);
        ServiceType::create([
            'name' => 'UPS',
            'type' => 'request'
        ]);
        ServiceType::create([
            'name' => 'Lainnya',
            'type' => 'request'
        ]);

        ServiceMedia::create([
            'name' => 'Whatsapp'
        ]);
        ServiceMedia::create([
            'name' => 'Permintaan Langsung'
        ]);
        ServiceMedia::create([
            'name' => 'Lainnya'
        ]);
    }
}
