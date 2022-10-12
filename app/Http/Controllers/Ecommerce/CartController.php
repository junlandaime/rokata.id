<?php

namespace App\Http\Controllers\Ecommerce;

use GuzzleHttp\Client;
use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\CustomerRegisterMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// use Facade\FlareClient\Http\Client;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);

        $carts = json_decode($request->cookie('jdi-carts'), true);
        
        if ($carts && array_key_exists($request->product_id, $carts)) {
            
            $carts[$request->product_id]['qty'] += $request->qty;
        } else {
            $product = Product::find($request->product_id);

            $carts[$request->product_id] = [
                'qty' => $request->qty,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => $product->image,
                'weight' => $product->weight
            ];
        }

        $cookie = cookie('jdi-carts', json_encode($carts), 2880);

        return redirect()->back()->with(['success' => 'Produk Ditambahkan ke Keranjang'])->cookie($cookie);
    }


    
    public function listCart()
    {
        $carts = $this->getCarts();

        $subtotal = collect($carts)->sum(function($q){
            return $q['qty'] * $q['product_price'];
        });

        return view('ecommerce.cart', compact('carts', 'subtotal'));
    }

    public function updateCart(Request $request)
    {
        $carts = $this->getCarts();

        foreach ($request->product_id as $key => $value) {
            if ($request->qty[$key] == 0) {
                unset($carts[$value]);
            } else {
                $carts[$value]['qty'] = $request->qty[$key];
            }
        }

        $cookie = cookie('jdi-carts', json_encode($carts), 2880);

        return redirect()->back()->cookie($cookie);
    }

    
    private function getCarts()
    {
        $carts = json_decode(request()->cookie('jdi-carts'), true);
        $carts = $carts != '' ? $carts:[];
        return $carts;
    }

    public function checkout()
    {

        // $this->validate($request, [
        //     'destination' => 'required',
        //     'weight' => 'required|integer'
        // ]);

        //mengirimkan permintaan ke api ruangapi untuk mengambil data ongkos kirim
        
        // $url = 'https://api.rajaongkir.com/starter/cost';
        // $client = new Client();
        // $response = $client->request('POST', $url, [
        //     'headers' => [
        //         'key' => 'c4d3aa1fcf43238faad9cb4fc9017468'
        //     ],
        //     'form_params' => [
        //         'origin' => 22, //asal pengiriman 22=bandung
        //         'destination' => 23,
        //         'weight' => 100,
        //         'courier' => 'jne'
        //     ]
        //     ]);

        //     $body = json_decode($response->getBody(), true);
        //     return $body;


        $provinces = Province::orderBy('created_at', 'DESC')->get();
        $carts = $this->getCarts();

        $subtotal = collect($carts)->sum(function($q){
            return $q['qty'] * $q['product_price'];
        });

        $weight = collect($carts)->sum(function($q){
            return $q['qty'] * $q['weight'];
        });

        return view('ecommerce.checkout', compact('provinces', 'carts', 'subtotal', 'weight'));
    }

    public function getCity()
    {
        
        $cities = City::where('province_id', request()->province_id)->get();

        return response()->json(['status' => 'success', 'data' => $cities]);
    }

    public function getDistrict()
    {
        $districts = District::where('city_id', request()->city_id)->get();

        return response()->json(['status' => 'success', 'data' => $districts]);
    }

    public function processCheckout(Request $request)
    {
        // $service = $request->service;
        // $arser = explode('-', $service);
        // dd($request->courier);
        $this->validate($request, [
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required',
            'email' => 'required|email',
            'customer_address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'required|exists:districts,id',
            'courier' => 'required',
            'service' => 'required'
        ]);

        DB::beginTransaction();
        try {
            //tambahkan dua baris code ini
            //get cookie dari browser
            $affiliate = json_decode(request()->cookie('rokata-afiliasi'), true);
            //explode data cookie untuk memisahkan userid dan productid
            $explodeAffiliate = explode('-', $affiliate);



            $customer = Customer::where('email', $request->email)->first();

            if (!auth()->guard('customer')->check() && $customer) {
                return redirect()->back()->with(['error' => 'Silahkan Login Terlebih Dahulu']);
            }

            $carts = $this->getCarts();

            $subtotal = collect($carts)->sum(function($q){
                return $q['qty'] * $q['product_price'];
            });

            if (!auth()->guard('customer')->check()) {
                
                $password = Str::random(8);
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'email' => $request->email,
                    'password' => $password,
                    'phone_number' => $request->customer_phone,
                    'address' => $request->customer_address,
                    'district_id' => $request->district_id,
                    'activate_token' => Str::random(30),
                    'status' => false
                ]);
            }

            $serviceongkir = explode('-', $request->service);
            $service = $serviceongkir[0] . '-' . $serviceongkir[1];
            $ongkir = $serviceongkir[2];

            $order = Order::create([
                'invoice' => Str::random(4) . '-' . time(),
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'district_id' => $request->district_id,  
                'courier_service' => $request->courier . '-' .$service, 
                'ongkir' =>$ongkir,
                'subtotal' => $subtotal,
                'ref' => $affiliate != '' && $explodeAffiliate[0] != auth()->guard('customer')->user()->id ? $affiliate:NULL
            ]);

            foreach ($carts as $row) {
                $product = Product::find($row['product_id']);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $row['product_id'],
                    'price' => $row['product_price'],
                    'qty' => $row['qty'],
                    'weight' => $product->weight
                ]);

                if ($product->stock - $row['qty'] < 0) {
                    return redirect()->back()->with(['error' => 'Jumlah pesananmu, melebihi stock, Stock Produk ' . $product->name . ' = ' . $product->stock]);
                }
                

                $product->update([
                    'stock' => $product->stock - $row['qty']
                ]);
            }

            DB::commit();

            $carts =[];

            $cookie = cookie('jdi-carts', json_encode($carts), 2880);

            //hapus data cookie afiliasi
            Cookie::queue(Cookie::forget('rokata-afiliasi'));

            if (!auth()->guard('customer')->check()) {
                
                Mail::to($request->email)->send(new CustomerRegisterMail($customer, $password));
            }

            return redirect(route('front.finish_checkout', $order->invoice))->cookie($cookie);
            
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function checkoutFinish($invoice)
    {
        $order = Order::with(['district.city'])->where('invoice', $invoice)->first();
        $detail = OrderDetail::with(['product'])->where('order_id', $order->id)->get();
        return view('ecommerce.checkout_finish', compact('order', 'detail'));

    }

    public function getCourier(Request $request)
    {   
        // dd($request);
        $this->validate($request, [
            'destination' => 'required',
            'courier' => 'required',
            'weight' => 'required|integer'
        ]);

        //MENGIRIM PERMINTAAN KE API RUANGAPI UNTUK MENGAMBIL DATA ONGKOS KIRIM
        //BACA DOKUMENTASI UNTUK PENJELASAN LEBIH LANJUT
        $url = 'https://api.rajaongkir.com/starter/cost';
        $client = new Client();
        $response = $client->request('POST', $url, [
            'headers' => [
                'key' => 'c4d3aa1fcf43238faad9cb4fc9017468'
            ],
            'form_params' => [
                'origin' => 22, //ASAL PENGIRIMAN, 22 = BANDUNG
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier //MASUKKAN KEY KURIR LAINNYA JIKA INGIN MENDAPATKAN DATA ONGKIR DARI KURIR YANG LAIN
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body;
    }
}
