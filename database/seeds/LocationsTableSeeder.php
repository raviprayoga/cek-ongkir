<?php

use App\City;
use App\Province;
use Illuminate\Database\Seeder;
use Kavist\RajaOngkir\Facades\RajaOngkir;
// use Kavist\RajaOngkir\RajaOngkir;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $daftarProvinsi = RajaOngkir::provinsi()->all();
        foreach($daftarProvinsi as $kolomProvinsi){
            Province::create([
                'province_id' => $kolomProvinsi['province_id'],
                'title' => $kolomProvinsi['province']
            ]);
            $daftarKota = RajaOngkir::kota()->dariProvinsi($kolomProvinsi['province_id'])->get();
            foreach($daftarKota as $kolomKota){
                City::create([
                    'province_id' => $kolomProvinsi['province_id'],
                    'city_id' => $kolomKota['city_id'],
                    'title' => $kolomKota['city_name']
                ]);
            }
        }
    }
}
