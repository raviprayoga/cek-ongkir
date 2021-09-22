<?php

namespace App\Http\Controllers;

use App\City;
use App\Courier;
use App\Province;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class HomeController extends Controller
{
    public function index()
    {
        $couriers = Courier::pluck('title', 'code');
        $province = Province::pluck('title', 'province_id');
        $cityy = City::find(39);
        $prov = Province::find(5);
        
        return view('welcome', compact('couriers', 'province', 'cityy','prov'));
    }

    public function getCities($id)
    {
        $city = City::where('province_id', $id)->pluck('title', 'city_id');
        return json_encode($city);
    }

    public function submit(Request $request)
    {
        // $couriers = Courier::pluck('title', 'code');
        // $province = Province::pluck('title', 'province_id');
        $cost = RajaOngkir::ongkosKirim([
            'origin' => $request->city_origin,
            'destination' => $request->city_destination,
            'weight' => $request->weight,
            'courier' =>$request->courier,
        ])->get();

        $ongkir = $cost;
        // dd($ongkir[0]['costs'][0]['cost'][0]);
        // return json_encode($cost);
        return response()->json($ongkir);
        // return view('welcome', compact('ongkir','couriers', 'province'));
    }

}
