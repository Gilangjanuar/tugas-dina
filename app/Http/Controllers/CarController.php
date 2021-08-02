<?php

namespace App\Http\Controllers;

use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    public function getData(Request $request)
    {
        try {
            $data = null;
            if ($request->type == "TERLARIS") {
                $data = Sale::query()->select(['cars.*', DB::raw('COUNT(sales.car_id) AS jml')])
                    ->join('cars', 'sales.car_id', '=', 'cars.id')
                    ->groupBy('sales.car_id', 'cars.id', 'car_name', 'car_type', 'car_color', 'car_capacity', 'car_price','hex_color', 'cars.created_at', 'cars.updated_at')
                    ->orderBy('jml', 'DESC')
                    ->get();
            }

            if ($request->type == "TERMAHAL") {
                $data = Sale::query()->select(['cars.*', DB::raw('COUNT(sales.car_id) AS jml')])
                    ->join('cars', 'sales.car_id', '=', 'cars.id')
                    ->groupBy('sales.car_id', 'cars.id', 'car_name', 'car_type', 'car_color', 'car_capacity', 'car_price','hex_color', 'cars.created_at', 'cars.updated_at')
                    ->orderBy('cars.car_price', 'DESC')
                    ->get();
            }

            if ($request->type == "TERMURAH") {
                $data = Sale::query()->select(['cars.*', DB::raw('COUNT(sales.car_id) AS jml')])
                    ->join('cars', 'sales.car_id', '=', 'cars.id')
                    ->groupBy('sales.car_id', 'cars.id', 'car_name', 'car_type', 'car_color', 'car_capacity', 'car_price', 'hex_color','cars.created_at', 'cars.updated_at')
                    ->orderBy('cars.car_price', 'ASC')
                    ->get();
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
