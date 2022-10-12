<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->where([
            ['status', 1], 
            ['stock', '>', 0 ]
            ])->paginate(10);

        return view('ecommerce.index', compact('products'));
    }

    public function product()
    {
        
        $products = Product::orderBy('created_at', 'DESC')->where('status', 1)->where('stock', '>', 0 )->paginate(8);

        return view('ecommerce.product', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with(['category'])->where('slug', $slug)->first();

        return view('ecommerce.show', compact('product'));
    }

    public function verifyCustomerRegistration($token)
    {
        $customer = Customer::where('activate_token', $token)->first();

        if ($customer) {
            $customer->update([
                'activate_token' => null,
                'status' => 1
            ]);

            return redirect(route('customer.login'))->with(['success' => 'Verifikasi Berhasil, Silahkan Login']);
        }

        return redirect(route('customer.login'))->with(['error' => 'Invalid Verifikasi Token']);
    }

    public function customerSettingForm()
    {
        //mengambil data customer yang sedang login
        $customer = auth()->guard('customer')->user()->load('district');
        //get data propinsi untuk ditampilkan pada selectbox
        $provinces = Province::orderBy('name', 'ASC')->get();
        //load view setting.blade.php dan passing data customer - provinces
        return view('ecommerce.setting', compact('customer', 'provinces'));
    }

    public function customerUpdateProfile(Request $request)
    {
        //validasi data yang dikirim   
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'phone_number' => 'required|max:15',
            'address' => 'required|string',
            'district_id' => 'required|exists:districts,id',
            'password' => 'nullable|string|min:6'
        ]);

        //ambil data customer yang sedang login
        $user = auth()->guard('customer')->user();
        //ambil data yang dikirim dari form
        //tapi hanya 4 column saja sesuai yang ada di bawah
        $data = $request->only('name', 'phone_number', 'address', 'district_id');
        //adapun password kita cek dulu, jika tidak kosong
        if ($request->password != '') {
            //maka tambahkan ke dalam array
            $data['password'] = $request->password;
        }

        //terus update datanya
        $user->update($data);
        //dan redirect kemblai dengan mengirimkan pesan berhasil
        return redirect()->back()->with(['success' => 'Profil berhasil diperbaharui']);
    }

    public function referalProduct($user, $product)
    {
        $code = $user . '-' . $product; //kita merge userid dan productid
        $product = Product::find($product); //find product berdasarkan productid
        $cookie = cookie('rokata-afiliasi', json_encode($code), 2880); //buat cookie dengan nama rokata-afiliasi dan valuenya adalah code yang sudah di-merge
        //kemudian redirect ke halaman show product dan mengirimkan cookie ke browser
        return redirect(route('front.show_product', $product->slug))->cookie($cookie);
    }

    public function listCommission()
    {
        $user = auth()->guard('customer')->user(); //ambil data user yang login
        //quury berdasarkan id user dari data ref yang ada diorder dengan status 4 atau selesai
        $orders = Order::where('ref', $user->id)->where('status', 4)->paginate(10);
        //load view affilitate.blade.php dan passing data orders
        return view('ecommerce.affiliate', compact('orders'));
    }
}
