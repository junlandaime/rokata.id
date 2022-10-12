<?php

namespace App\Http\Controllers\Ecommerce;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\OrderReturn;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withCount(['return'])->where('customer_id', auth()->guard('customer')->user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('ecommerce.orders.index', compact('orders'));
    }

    public function view($invoice)
    {
        $order = Order::with(['district.city.province', 'details', 'details.product', 'payment'])->where('invoice', $invoice)->first();

        //jadi kita cek, value forUser() nya adalah customer yang sedang login
        //dan allow nya meminta dua parameter
        //pertama adalah nama gate yang dibuat sebelumnya dan yang kedua adalah data order dari query di atas

        if (Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order)) {
            //jika hasilnya true, maka kita tampilkan datanya
            return view('ecommerce.orders.view', compact('order'));
        }

        return redirect(route('customer.orders'))->with(['error' => 'Anda Tidak Diizinkan Untuk Mengakses Order Orang Lain']);
    }

    public function paymentForm()
    {
        return view('ecommerce.payment');
    }

    public function storePayment(Request $request)
    {
        $this->validate($request, [
            'invoice' => 'required|exists:orders,invoice',
            'name' => 'required|string',
            'transfer_to' => 'required|string',
            'transfer_date' => 'required',
            'amount' => 'required|integer',
            'proof' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        //Define Database transaction untuk menghindari kesalahan sinkronisasi data jika terjadi error ditengah proses query
        DB::beginTransaction();
        try {
            //ambil data order berdasarkan invoice id
            $order = Order::where('invoice', $request->invoice)->first();
            if ($order->subtotal != $request->amount) return redirect()->back()->with(['error' => 'Error, Pembayaran Harus Sama Dengan Tagihan']);
            //jika statusnya masih 0 dan ada file bukti transfer yang dikirim
            if ($order->status == 0 && $request->hasFile('proof')) {
                //maka upload file gambar tersebut
                $file = $request->file('proof');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/payment', $filename);

                //kemudian simpan informasi pembayaran
                Payment::create([
                    'order_id' => $order->id,
                    'name' => $request->name,
                    'transfer_to' => $request->transfer_to,
                    'transfer_date' => Carbon::parse($request->transfer_date)->format('Y-m-d'),
                    'amount' => $request->amount,
                    'proof' => $filename,
                    'status' => false
                ]);
                //dan ganti status order menjadi 1
                $order->update(['status' => 1]);
                //jika tidak ada errror, maka commit untuk menandakan bahwa transaksi berhasil 
                DB::commit();
                //redirect dan kirimkan pesan
                return redirect()->back()->with(['success' => 'Pesanan Dikonfirmasi']);
            }
            //redirect dengan error message
            return redirect()->back()->with(['error' => 'Error, Upload Bukti Transfer']);
        } catch (\Exception $e) {
            //jika terjadi error, maka rollback seluruh proses query
            DB::rollback();
            //dan kirimkan pesan error
            
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    
    public function pdf($invoice)
        {
            //get data order berdasarkan invoice
            $order = Order::with(['district.city.province', 'details', 'details.product', 'payment'])->where('invoice', $invoice)->first();
            //mencegah direct akses oleh user, sehingga hanya pemiliknya yang bisa melihat fakturnya
            if (!Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order)) {
                return redirect(route('customer.view-order', $order->invoice));
            }

            //jika dia adalah pemiliknya, maka load view verikut dan passing data orders
            $pdf = PDF::loadView('ecommerce.orders.pdf', compact('order'));
            //kemudia buka file pdfnya di browser
            return $pdf->stream();
        }

        public function acceptOrder(Request $request)
        {
            //cari data order berdasarkan id
            $order = Order::find($request->order_id);
            //validasi kepemilikan 
            if (!Gate::forUser(auth()->guard('customer')->user())->allows('order-view', $order)) {
                return redirect()->back()->with(['error' => 'Bukan Pesanan Kamu']);

            }

            //ubah statusnya menjadi 4
            $order->update(['status' => 4]);
            //redirect kembali dengan menampilkan alert success
            return redirect()->back()->with(['success' => 'Pesanan Dikonfirmasi']);
        }

        public function returnForm($invoice)
        {
            //load data berdasarkan invoice
            $order = Order::where('invoice', $invoice)->first();
            //load view return.blade.php dan passing data order
            return view('ecommerce.orders.return', compact('order'));
        }

        public function processReturn(Request $request, $id)
        {   
            // dd($request);
            //lakukan validasi data
            $this->validate($request, [
                'reason' => 'required|string',
                'refund_transfer' => 'required|string',
                'photo' => 'required|image|mimes:jpg,png,jpeg'
            ]);

            //cari data return berdasarkan order_id yang ada di table order_returns nantinya
            $return = OrderReturn::where('order_id', $id)->first();
            //jika ditemukan, maka tampilkan notifikasi error
            if ($return) return redirect()->back()->with(['error' => 'Permintaan Refund Dalam Proses']);

            if ($request->hasFile('photo')) {
                //get file
                $file = $request->file('photo');
                //generate nama file berdasarkan time dan string random
                $filename = time() . Str::random(5) . '.' . $file->getClientOriginalExtension();
                //kemudian upload ke dalam folder storage/app/public/return
                $file->storeAs('public/return', $filename);

                //dan simpan informasinya ke dalma table order_returns
                OrderReturn::create([
                    'order_id' => $id,
                    'photo' => $filename,
                    'reason' => $request->reason,
                    'refund_transfer' => $request->refund_transfer,
                    'status' => 0
                ]);
                
                //CODE BARU HANYA PADA BAGIAN INI SAJA
                $order = Order::find($id); //AMBIL DATA ORDER BERDASARKAN ID
                //KIRIM PESAN MELALUI BOT
                $this->sendMessage('#' . $order->invoice, $request->reason); 
                //CODE BARU HANYA PADA BAGIAN INI SAJA
                
                //lalu tamplkan notifikasi sukses
                return redirect()->back()->with(['success' => 'Permintaan Refund Dikirim']);
            }
        }

        // private function getTelegram($url, $params)
        // {
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url . $params);

        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        //     $content = curl_exec($ch);
        //     curl_close($ch);
        //     return json_decode($content, true);
        // }
        private function getTelegram($url, $params)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . $params); 

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $content = curl_exec($ch);
            curl_close($ch);
            return json_decode($content, true);
        }

        // private function sendMessage($order_id, $reason)
        // {
        //     $key = env('TELEGRAM_KEY'); //ambil token dari env
        //     //kemudian kirim request ke telegram untuk mengambil data user yang melisten bot kita
        //     $chat = $this->getTelegram('https://api.telegram.org/'. $key .'/getUpdates', '');
        //     //jika ada
        //     if ($chat['ok']) {
        //         //saya berasumsi pesan ini hanya dikirim ke admin, maka kita tidak perlu melooping hasil dari get data user
        //         //cukup mengambil key 0 saja atau list yang pertama
        //         //untuk mendapatkan chat_id
        //         $chat_id = $chat['result'][0]['message']['chat']['id'];
        //         //teks yang diinginkan
        //         $text = 'Hai Rokata.id, OrderID' . $order_id . ' Melakukan Permintaan Refund dengan Alasan "' . $reason . '", Segera dicek Ya!';

        //         //dan kirim request ke telegram untuk mengirimkan pesan
        //         return $this->getTelegram('https://api.telegram.org/'. $key .'/sendMessage', '?chat_id=' . $chat_id . '$text=' . $text);
        //     }
        // }

        private function sendMessage($order_id, $reason)
        {
            $key = env('TELEGRAM_KEY'); //AMBIL TOKEN DARI ENV
            //KEMUDIAN KIRIM REQUEST KE TELEGRAM UNTUK MENGAMBIL DATA USER YANG ME-LISTEN BOT KITA
            $chat = $this->getTelegram('https://api.telegram.org/'. $key .'/getUpdates', '');
            //JIKA ADA
            if ($chat['ok']) {
                //SAYA BERASUMSI PESAN INI HANYA DIKIRIM KE ADMIN, MAKA KITA TIDAK PERLU MELOOPING HASIL DARI GET DATA USER
                //CUKUP MENGAMBIL KEY 0 SAJA ATAU LIST YANG PERTAMA
                //UNTUK MENDAPATKAN CHAT_ID
                $chat_id = $chat['result'][0]['message']['chat']['id'];
                //TEKS YANG DIINGINKAN
                $text = 'Hai Rokata.id, OrderID ' . $order_id . ' Melakukan Permintaan Refund Dengan Alasan "'. $reason .'", Segera Dicek Ya!';
            
                //DAN KIRIM REQUEST KE TELEGRAM UNTUK MENGIRIMKAN PESAN
                return $this->getTelegram('https://api.telegram.org/'. $key .'/sendMessage', '?chat_id=' . $chat_id . '&text=' . $text);
            }
        }
        
}
