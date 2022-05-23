<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class ReturController extends Controller
{
    public function kembali(Request $req){
        $id_kasir = Auth::user()->id;   
        $kode = $req->input('kode');
        $idtrans  = $req->id_trans;
        $btrans = DB::table('transaksi')->where('kode_trans',$idtrans)->select("no_nota","nama_pelanggan","telepon","alamat")->get()[0];


        $datos = [];



        $counter = DB::table('transaksi')->whereDate('created_at', Carbon::today())->count();
        $no_nota = ['no_nota' => date("ymd").str_pad($counter+2, 3, '0', STR_PAD_LEFT)];

        if(count($kode) > 0){
            $id = DB::table('transaksi')->insertGetId(['status'=>'return','nama_pelanggan'=>$btrans->nama_pelanggan,'telepon'=>$btrans->telepon,"alamat"=>$btrans->alamat, 'id_kasir' =>$id_kasir,'keterangan'=>$btrans->no_nota]);
            foreach($kode as $kodes){
             $jml = DB::table('detail_transaksi')->where('kode_trans',$idtrans)->where('kode_produk',$kodes)->get()[0]->jumlah;
            
             $status = DB::table('detail_transaksi')->where('kode_trans',$idtrans)->where('kode_produk',$kodes)->pluck('status')->first();
          
             if($status != 'return'){
                $jmlstok =  DB::table('stok')->where('kode_produk',$kodes)->pluck('jumlah')->first();
                DB::table('detail_transaksi')->where('kode_trans',$req->id_trans)->where('kode_produk',$kodes)->update(['status'=>'return']);
                DB::table('stok')->where('kode_produk',$kodes)->update(['jumlah'=>$jmlstok + $jml]);
             }
    
             $getdata = DB::table('detail_transaksi')->where('kode_trans',$idtrans)->where('kode_produk',$kodes)->get()[0];
    
    
    
             DB::table('detail_transaksi')->insert(['kode_trans'=>$id, "kode_produk" => $kodes,"jumlah"=>$getdata->jumlah,"potongan"=>$getdata->potongan,"harga_produk"=>$getdata->harga_produk,"prefix"=>$getdata->prefix,"status"=>"return"]);
            
            }
        }
       
        
        return back();
        
    }
}
