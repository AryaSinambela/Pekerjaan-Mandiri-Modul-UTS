<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $srv = Category::create(['name' => 'Server & Cloud', 'code' => 'SRV']);
        $iot = Category::create(['name' => 'Internet of Things', 'code' => 'IOT']);
        $drn = Category::create(['name' => 'Multimedia & Drone', 'code' => 'DRN']);

        Equipment::create([
            'category_id' => $srv->id,
            'asset_code' => 'EQ-SRV-001',
            'name' => 'Dell PowerEdge T40 Server',
            'specification' => 'Intel Xeon E-2224G 3.5GHz, 16GB ECC DDR4, 1TB SATA HDD',
            'condition' => 'baik',
            'status' => 'dipinjam',
            'daily_rental_fee' => 75000,
            'image_path' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=600&auto=format&fit=crop&q=80',
        ]);

        Equipment::create([
            'category_id' => $srv->id,
            'asset_code' => 'EQ-SRV-002',
            'name' => 'Synology NAS DiskStation DS923+',
            'specification' => 'AMD Ryzen R1600 Dual-core, 4GB DDR4 ECC, 4-Bay NAS Server',
            'condition' => 'baik',
            'status' => 'tersedia',
            'daily_rental_fee' => 45000,
            'image_path' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&auto=format&fit=crop&q=80',
        ]);

        Equipment::create([
            'category_id' => $iot->id,
            'asset_code' => 'EQ-IOT-001',
            'name' => 'Arduino Mega 2560 Sensor Kit',
            'specification' => 'ATmega2560 Microcontroller, 37-in-1 Sensor Module Kit',
            'condition' => 'baik',
            'status' => 'tersedia',
            'daily_rental_fee' => 10000,
            'image_path' => 'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=600&auto=format&fit=crop&q=80',
        ]);

        Equipment::create([
            'category_id' => $iot->id,
            'asset_code' => 'EQ-IOT-002',
            'name' => 'ESP32-CAM WiFi Bluetooth Development Kit',
            'specification' => 'Dual-core 32-bit CPU, OV2640 2MP Camera, MicroSD Slot',
            'condition' => 'rusak_ringan',
            'status' => 'dalam_perbaikan',
            'daily_rental_fee' => 8000,
            'image_path' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&auto=format&fit=crop&q=80',
        ]);

        Equipment::create([
            'category_id' => $drn->id,
            'asset_code' => 'EQ-DRN-001',
            'name' => 'DJI Mavic Air 2 Fly More Combo',
            'specification' => 'Kamera 48MP 4K/60fps, 3-Axis Gimbal, Waktu Terbang 34 Menit',
            'condition' => 'baik',
            'status' => 'tersedia',
            'daily_rental_fee' => 50000,
            'image_path' => 'https://images.unsplash.com/photo-1527977966376-1c8408f9f108?w=600&auto=format&fit=crop&q=80',
        ]);

        Equipment::create([
            'category_id' => $drn->id,
            'asset_code' => 'EQ-DRN-003',
            'name' => 'FPV Racing Drone BetaFPV Cetus Pro',
            'specification' => 'Brushless Whoop Quadcopter, Altitude Hold, VR02 Goggles',
            'condition' => 'baik',
            'status' => 'tersedia',
            'daily_rental_fee' => 25000,
            'image_path' => 'https://images.unsplash.com/photo-1508614589041-895b88991e3e?w=600&auto=format&fit=crop&q=80',
        ]);
    }
}