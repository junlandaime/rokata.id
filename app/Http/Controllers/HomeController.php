<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function orderReport()
    {
        //inisiasi 30 hari range saat ini jika halaman pertama kali di load
        //kita gunakan startofmont untuk mengambil tanggal 1
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        //dan endofmonth untuk mengambil tanggal terakhir di bulan yang berlakuk saat ini 
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        
        //jika user melakukan filter manual, maka parameter date akan terisi
        if (request()->date != '') {
            //maka formatting tanggalnya berdasarkan filter user
            $date = explode(' - ', request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        }

        //buat query ke db menggunakan wherebetween dari tanggal filter
        $orders = Order::with(['customer.district'])->whereBetween('created_at', [$start, $end])->get();
        //kemudian load view
        return view('report.order', compact('orders'));
    }

    public function orderReportPdf($daterange)
    {
        $date = explode('+', $daterange); //explode tanggalnya untuk memishkan start and end
        //definiisaikan valriablenya dengan format temstamp
        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        //kemudian buat quety berdasarkan range created_at yang telah ditetapkan rangenya dari $start ke $end
        $orders = Order::with(['customer.district'])->whereBetween('created_at', [$start, $end])->get();
        //load view untuk pdfnya dengan mengirimkan data dari hasil query
        $pdf = PDF::loadView('report.order_pdf', compact('orders', 'date'));
        //generetae pdfnya
        return $pdf->stream();
    }
    
    public function returnReport()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        
        if (request()->date != '') {
            $date = explode(' - ', request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }
        $orders = Order::with(['customer.district'])->has('return')->whereBetween('created_at', [$start, $end])->get();
        return view('report.return', compact('orders'));
    }

    public function returnReportPdf($daterange)
    {
        $date = explode('+', $daterange); 
        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        $orders = Order::with(['customer.district'])->has('return')->whereBetween('created_at', [$start, $end])->get();
        $pdf = PDF::loadView('report.order_pdf', compact('orders', 'date'));
        return $pdf->stream();
    }
}
