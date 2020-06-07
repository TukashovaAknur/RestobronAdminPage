<?php

namespace App\Http\Controllers;

use App\Average_check;
use App\Charts\OrderChart;
use App\Charts\SaleChart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Reservation;
use Illuminate\Support\Facades\DB;

class SaleChartController extends Controller
{
    public function index()
    {
        $sales = Average_check:: select(\DB::raw("SUM(price) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        $chart = new SaleChart;
        $chart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $chart->dataset('Sales value', 'line', $sales)->options([
            'fill' => 'true',
            'borderColor' => '#51C1C0'
        ]);


        $orders = Reservation:: select(\DB::raw("count(*) as count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(\DB::raw("Month(created_at)"))
            ->pluck('count');

        $orderchart = new OrderChart;
        $orderchart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $orderchart->dataset('Total orders', 'bar', $orders)->options([
            'fill' => 'true',
            'backgroundColor'=>'orange',
            'borderColor' => 'orange'
        ]);

        return view('chart', compact('chart','orderchart'));
    }
}
