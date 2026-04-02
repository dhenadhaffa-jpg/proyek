<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    // 🔥 1. AMBIL DATA PROVINSI (VERSI KOMERCE V2) 🔥
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

        return response()->json([
            'data' => $response['data'] ?? []
        ]);
    }

    // 🔥 2. AMBIL DATA KOTA (VERSI KOMERCE V2) 🔥
    public function getCities($province_id)
    {
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->get("https://rajaongkir.komerce.id/api/v1/destination/city/$province_id");

        return response()->json([
            'data' => $response['data'] ?? []
        ]);
    }

    // 🔥 3. CEK ONGKOS KIRIM (VERSI KOMERCE V2) 🔥
    public function checkCost(Request $request)
    {
        // Komerce mewajibkan format body x-www-form-urlencoded
        $response = Http::asForm()->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin' => '3428', // 🔥 Ini ID khusus Tasikmalaya di sistem Komerce V2
            'destination' => $request->destination,
            'weight' => 1000,
            'courier' => $request->courier
        ]);

        return response()->json($response->json());
    }
}