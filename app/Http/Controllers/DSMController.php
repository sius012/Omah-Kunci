<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;



class DSMController extends Controller
{
    public function loaddatadetailstok(){
        $data = DB::table('detail_stok')->join('users','users.id','=','detail_stok.id_ag')->join('produk', 'produk.kode_produk','detail_stok.kode_produk')->select('detail_stok.*','produk.nama_produk','produk.stn','users.name')->get();
        return json_encode($data);
    }
    public function index(){
        $produk = DB::table('produk')->join('kategori','kategori.id_kategori','=','produk.id_kategori')->get();


        $data = DB::table('detail_stok')->get();
        return view('managerDetailStok', ['produk' => $produk,'detail_stok' => $data]);
    }

    public function tambahdetailstok(Request $req){
        $data = $req->input('data');
        $data['id_ag'] = Auth::user()->id;
        DB::table('detail_stok')->insert($data);
    }

    public function verifiying(Request $req){
        $id = $req->input('id');
        $getproduk = DB::table('detail_stok')->where("id",$id)->get();
        $getcode = $getproduk[0]->kode_produk;
        $getstokold = DB::table("stok")->where("kode_produk", $getcode)->get()[0]->jumlah;
        if($getproduk[0]->status == "masuk"){
            DB::table('stok')->where("kode_produk", $getcode)->update(["jumlah" => $getstokold + $getproduk[0]->jumlah]);
        }else{
            DB::table('stok')->where("kode_produk", $getcode)->update(["jumlah" => $getstokold - $getproduk[0]->jumlah]);
        }
      
        DB::table('detail_stok')->where('id',$id)->update(['status2' => 'terverifikasi']);
    }

    public function rejecting(Request $req){
        $id = $req->input('id');
        DB::table('detail_stok')->where('id',$id)->update(['status2' => 'ditolak']);
    }
}
