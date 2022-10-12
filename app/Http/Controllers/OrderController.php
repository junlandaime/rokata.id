<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        //query untuk mengambil semua pesanandan load data yang berelasi menggunakan eager loading dan urutannya berdasarkan created_at
        $orders = Order::with(['customer.district.city.province'])
        ->withCount('return')
        ->orderBy('created_at', 'DESC');

        //jika q untuk pencarian tidak kosong
        if (request()->q != '') {
            //maka dibuat query untuk mencari data berdasarkan nama invoice dan alamat
            $orders = $orders->where(function($q){
                $q->where('customer_name', 'LIKE', '%' . request()->q . '%')
                ->orWhere('invoice', 'LIKE', '%' . request()->q . '%')
                ->orWhere('customer_address', '%' . request()->q . '%');
            });
        }

        //jika status tidak kosong
        if (request()->status != '') {
            //maka data difilter berdasarkan status
            $orders = $orders->where('status', request()->status);
        }
        $orders = $orders->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->details()->delete();
        $order->payment()->delete();
        $order->delete();
        return redirect(route('orders.index'));
    }

    public function view($invoice)
    {
        $order = Order::with(['customer.district.city.province', 'payment', 'details.product'])->where('invoice', $invoice)->first();
        return view('orders.view', compact('order'));
    }

    public function acceptPayment($invoice)
    {
        //Mengambil data customer berdasarkan invoice
        $order = Order::with(['payment'])->where('invoice', $invoice)->first();
        //Ubah Status di table payments melalui order yang terkait
        $order->payment()->update(['status' => 1]);
        //Ubah status order menjadi proses
        $order->update(['status' => 2]);
        //redirect ke halaman yang sama
        return redirect(route('orders.view', $order->invoice));
    }

    public function shippingOrder(Request $request)
    {
        //mengambil data order berdasarkan id
        $order = Order::with(['customer'])->find($request->order_id);
        //update data order dengan memasukkan nomor resi dan mengubah status menjadi dikirim
        $order->update(['tracking_number' => $request->tracking_number, 'status' => 3]);
        //kirim eail ke pelanggan terkait
        Mail::to($order->customer->email)->send(new OrderMail($order));

        return redirect()->back();
    }

    public function return($invoice)
    {
        $order = Order::with(['return', 'customer'])->where('invoice', $invoice)->first();
        return view('orders.return', compact('order'));
    }

    public function approveReturn(Request $request)
    {
        $this->validate($request, ['status' => 'required']);
        $order = Order::find($request->order_id);
        $order->return()->update(['status' => $request->status]);
        $order->update(['status' => 4]);
        return redirect()->back();
    }
}