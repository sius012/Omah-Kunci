<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Tools;
use Carbon\Carbon;

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
      $user = DB::table('users')->count();
      $product = DB::table('produk')->count();
      $date = Carbon::createFromFormat('Y.m.d', date("Y.m.d"));
      $date = $date->addDays(35);


      $jumlahproduk = DB::table('produk')->count();

    $dt=Carbon::now();

 

      $daily = [];
      $daily["hari"]["pemasukan nota kecil"] = 0;
      $daily["minggu"]["pemasukan nota kecil"] = 0;
      $daily["bulanan"]["pemasukan nota kecil"] = 0;
      $pt = DB::table("detail_transaksi")->where('status','!=','return')->sum("jumlah");

      $nk1 = DB::table("transaksi")->whereDay("created_at",Carbon::now()->day)->whereIn('status',['belum lunas','lunas'])->get();
      $nk2 = DB::table("transaksi")->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->whereIn('status',['belum lunas','lunas'])->get();
      $nk3 = DB::table("transaksi")->whereMonth("created_at",Carbon::now()->month)->whereIn('status',['belum lunas','lunas'])->get();

      foreach($nk1 as $ns){
        $daily["hari"]["pemasukan nota kecil"] += Tools::doDisc(1,$ns->subtotal,$ns->diskon,$ns->prefix);
      }

      foreach($nk2 as $ns){
        $daily["minggu"]["pemasukan nota kecil"] += Tools::doDisc(1,$ns->subtotal,$ns->diskon,$ns->prefix);
      }

      foreach($nk3 as $ns){
        $daily["bulanan"]["pemasukan nota kecil"] += Tools::doDisc(1,$ns->subtotal,$ns->diskon,$ns->prefix);
      }



    
      $daily["hari"]["pemasukan nota besar"] = DB::table("nota_besar")->whereDay("created_at",Carbon::now()->day)->sum("us");
      $daily["hari"]["pemasukan preorder"] = DB::table("preorder")->whereDay("created_at",Carbon::now()->day)->where('status','!=','draft')->sum("us");


     
      $daily["minggu"]["pemasukan nota besar"] = DB::table("nota_besar")->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum("us");
      $daily["minggu"]["pemasukan preorder"] =   DB::table("preorder")->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','!=','draft')->sum("us");


      
      $daily["bulanan"]["pemasukan nota besar"] =  DB::table("nota_besar")->whereMonth("created_at",Carbon::now()->month)->where('status','!=','draft')->sum("us");
      $daily["bulanan"]["pemasukan preorder"] = DB::table("preorder")->whereMonth("created_at",Carbon::now()->month)->where('status','!=','draft')->sum("us");
    
      
        return view('home',['daily'=>$daily,'produk'=>$product,'user'=>$user,'pt' => $pt]);
        
    }
}
