<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use App\Http\Controllers\Tools;
use Carbon\Carbon;

use PDF;
use Auth;

class KasirController extends Controller
{
    public function __construct()
    {   
        
    }

    public function index(){
        $data = DB::table("new_produks")->join("tipes",'new_produks.id_tipe',"=","tipes.id_tipe")->orderBy("tipes.id_tipe")->get();
        $produk = [];

            foreach($data as $i => $datos){

                $getter1 =  DB::table("new_produks")->join("tipes",'new_produks.id_tipe',"=","tipes.id_tipe")->join("kode_types",'kode_types.id_ct',"=","kode_types.id_kodetype")->where("kode_types.id_kodetype",$datos->id_ct)->orderBy("kode_types.id_ct")->get();
                
                foreach($getter1 as $j => $gs1){
                    $getter2 =  DB::table("new_produks")->join("mereks",'mereks.id_merek',"=","new_produks.id_merek")->where("new_produks.id_kodetype",$datos->id_kodetype)->where("mereks.id_merek",$gs1->id_merek)->orderBy("mereks.id_merek")->get();
                    foreach($getter2 as $k => $gt2){
                         $produk[$i][$j][$gt2] = "";
                        
                    }
                    
                }
            }

            dd($produk);
            return view("kasir");
    }

   

    public function loader(Request $req){
        if($req->jenis == "normal"){
        if($req->session()->has('transaksi')){
        
           $data = DB::table('detail_transaksi')->join('new_produks', 'detail_transaksi.kode_produk', '=', 'new_produks.kode_produk')->join('kode_types','kode_types.id_kodetype','new_produks.id_ct')->join('mereks','mereks.id_merek','new_produks.id_merek')->where('kode_trans', $req->session()->get('transaksi')['id_transaksi'])->get();
                return(json_encode(["datadetail" => $data]));
            
        }
        }else{
            if($req->session()->has('transaksi')){
                if($req->jenis)
                $data = DB::table('preorder_detail')->join('new_produks', 'preorder_detail.kode_produk', '=', 'new_produks.kode_produk')->join('kode_types','kode_types.id_kodetype','new_produks.id_ct')->join('mereks','mereks.id_merek','new_produks.id_merek')->where('id_preorder', $req->session()->get('transaksi')['id_pre'])->get();
                     return(json_encode(["datadetail" => $data]));
                 
             }
        }
    }

    public function cari(Request $req){
        $kw = $req->input('data');
        $data = DB::table('new_produks')->join("tipes","tipes.id_tipe","=","new_produks.id_tipe")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->join("mereks","mereks.id_merek","=","new_produks.id_merek")->where('nama_produk',"LIKE","%".$kw."%")->orWhere('kode_produk',"LIKE","%".$kw."%")->where("kode_produk","!=","")->take(10)->get();
        $data2 = DB::table('paket')->where("kode_paket","LIKE","%".$kw."%")->orWhere("nama_paket","LIKE","%".$kw."%")->take(10)->get();
        $count = DB::table('new_produks')->where('kode_produk',$kw)->count();
       
        if($count == 1){
             $data2 = DB::table('new_produks')->join("tipes","tipes.id_tipe","=","new_produks.id_tipe")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->join("mereks","mereks.id_merek","=","new_produks.id_merek")->where("kode_produk",$kw)->orWhere("nama_paket","LIKE","%".$kw."%")->get();
             return json_encode(['data' => $data, 'currentproduk' => (array) $data2[0]]);
        }else{
            return( json_encode(['data' => $data,"data2"=>$data2]));
            
        }
        
       
    }

    public function tambahTransaksi(Request $req){
        $no = DB::table('transaksi')->get()->count();
        $no += 1;   
        $id = DB::table('transaksi')->insertGetId(['no_nota' => $no]);
        $req->session()->put('transaksi', ['id_transaksi' => $id, 'no_nota' => $no]);
    }

    public function bcnk(Request $req){
        $id = $req->id;
        $nominal = $req->nominal;

        $getbayar =  DB::table('transaksi')->where('kode_trans',$id)->pluck('bayar')->first();

        DB::table('transaksi')->where('kode_trans',$id)->update(['status'=>'lunas','bayar'=>$getbayar+$nominal  ,'terakhir_dilunasi'=>date("Y-m-d h:i:s")]);
    }
    public function tambahTransaksiDetail(Request $req){
        $data = $req->input('data');
        $id_trans = "";
      
        $id_kasir = Auth::user()->id;   

        if($data['jenis'] =="produk"){
          //checking stock
        $stock = DB::table('stok')->where('kode_produk',$data['kode_produk'])->sum('jumlah');
        $hasilpengurangan = $stock - $data['jumlah'];
        if($hasilpengurangan < 0){
            return json_encode(['datadetail'=>'barang habis','as'=>$stock]);
        }
        
      
        $harga = DB::table('new_produks')->where('kode_produk',$data['kode_produk'])->pluck("harga")->first();
        $disc = DB::table('new_produks')->where('kode_produk',$data['kode_produk'])->pluck("diskon")->first();
        $prefix = DB::table('new_produks')->where('kode_produk',$data['kode_produk'])->pluck("diskon_tipe")->first();
        
        
        if($req->session()->has('transaksi')){
            $id_transaksi = $req->session()->get('transaksi')['id_transaksi'];
            $id_trans = $id_transaksi;
        }else{
           
            $id = DB::table('transaksi')->insertGetId(['id_kasir' =>$id_kasir]);
            $req->session()->put('transaksi', ['id_transaksi' => $id]);   
            $id_trans = $id;
        }
    
        $counter = DB::table('detail_transaksi')->where('kode_trans', $id_trans)->where('kode_produk', $data['kode_produk'])->count();
        if($counter < 1){
            DB::table('detail_transaksi')->insert(['kode_trans' => $id_trans, 'kode_produk' => $data['kode_produk'], "jumlah" => $data["jumlah"],"potongan" => $disc,'harga_produk'=>$harga,'prefix'=>"persen"]);
        }else{
            $jumlah = DB::table('detail_transaksi')->where('kode_trans', $id_trans)->where('kode_produk', $data['kode_produk'])->pluck('jumlah')->first();
            $jml = $harga  * ((int)$jumlah + (int) $data['jumlah']);
            $ptg = $disc;
            DB::table('detail_transaksi')->where('kode_trans', $id_trans)->where('kode_produk', $data['kode_produk'])->update(['jumlah' => $jumlah + $data['jumlah'], 'potongan' => $ptg]);
        }
        
        $datadetail = DB::table('detail_transaksi')->join('new_produks','detail_transaksi.kode_produk','=','new_produks.kode_produk')->join('mereks','mereks.id_merek','=','new_produks.id_merek')
        ->join('kode_types','kode_types.id_kodetype','=','new_produks.id_ct')
        ->where('kode_trans',$id_trans)->get();
        }else{
            $getpaket = DB::table("paket")->where("kode_paket",$data['kode_produk'])->get();
            $produk = explode(",",substr($getpaket[0]->kode_produk,0,-1));
            $jumlah = explode(",",substr($getpaket[0]->jumlah,0,-1));
            $harga = explode(",",substr($getpaket[0]->harga,0,-1));

            if($req->session()->has('transaksi')){
                $id_transaksi = $req->session()->get('transaksi')['id_transaksi'];
                $id_trans = $id_transaksi;
            }else{
                $no = DB::table('transaksi')->whereDate('transaksi.created_at', Carbon::today())->count();
                $no += 1;   
                $id = DB::table('transaksi')->insertGetId(['no_nota' => date("ymd").str_pad($no+1, 3, '0', STR_PAD_LEFT), 'id_kasir' =>$id_kasir]);
                $req->session()->put('transaksi', ['id_transaksi' => $id, 'no_nota' => $no]);   
                $id_trans = $id;
            }

            foreach($produk as $i => $produks){
                $stock2 = DB::table('stok')->where('kode_produk',$produks)->sum('jumlah');
                $hasilpengurangan2 = $stock2 - $jumlah[$i];
                if($hasilpengurangan2 < 0){
                    return json_encode(['datadetail'=>'barang habis','as'=>$stock2]);
                }
                $hargas = $harga[$i]/$jumlah[$i];
                $jumlahs = $jumlah[$i];
                
                DB::table('detail_transaksi')->insert(["potongan"=>0,"kode_produk"=>$produks,"kode_trans"=>$id_trans,"jumlah"=>$jumlahs,"harga_produk"=>$hargas]);
            }
        }

        $datadetail = DB::table("detail_transaksi")->join("new_produks","detail_transaksi.kode_produk","=","new_produks.kode_produk")->join('kode_types',"kode_types.id_kodetype","new_produks.id_ct")->join("mereks","mereks.id_merek","=","new_produks.id_merek")->where("kode_trans",$id_trans)->get();
        
        
       
        return(json_encode(['datadetail'=>$datadetail]));
    }

    public function selesai(Request $req){
        $data = $req->input('data');
        $telp = $data['telp'];
        $alamat = $data['alamat'];
        $subtotal = 0;
        $id_transaksi = $req->session()->get('transaksi')['id_transaksi'];
        $diantar = $data['antarkah'];
        $metode = $data['via'];
        $no = DB::table('transaksi')->where("status","!=","draf")->whereDate('transaksi.created_at', Carbon::today())->count();
        $no += 1;   
        $no_nota = date("ymd").str_pad($no,3,0,STR_PAD_LEFT);


        $stok = DB::table("detail_transaksi")->join('new_produks','new_produks.kode_produk','=','detail_transaksi.kode_produk')->where('kode_trans', $id_transaksi)->get();

        

        foreach($stok as $stoks){
            $subtotal += Tools::doDisc($stoks->jumlah,$stoks->harga_produk,$stoks->potongan,$stoks->prefix);
        }

        $afterdiskon = $subtotal - $data['diskon']; 
        $status = $data["bayar"] - $afterdiskon >= 0 ? "lunas":"belum lunas";
       

        foreach($stok as $produks){
            $currentstok = DB::table("stok")->where('kode_produk', $produks->kode_produk)->pluck('jumlah')->first();
            DB::table("stok")->where('kode_produk', $produks->kode_produk)->update(["jumlah" => (int) $currentstok - (int) $produks->jumlah]);
        }


        

        DB::table('transaksi')->where('kode_trans', $id_transaksi)->update(["nama_pelanggan" => $data['nama_pelanggan'],'telepon' => $telp,'alamat'=>$alamat,"subtotal" => $subtotal, "status" => $status, "diskon" => $data["diskon"],"metode" => $data['via'],"bayar" => $data["bayar"],"antar"=>$diantar]);

        

    }


    public function selesaipreorder(Request $req){
        $data = $req->input('data');
        $telp = $data['telp'];
        $alamat = $data['alamat'];
        $subtotal = 0;
        $id_transaksi = $req->session()->get('transaksi')['id_pre'];
        $counter = DB::table("preorder")->where("status","!=","draft")->whereDate('preorder.created_at', Carbon::today())->count();
        $no_nota = date("ymd").str_pad($counter+1,3,0,STR_PAD_LEFT);


        

        DB::table('preorder')->where('id', $id_transaksi)->update(["status"=>"selesai","no_nota" => $no_nota,"ttd" => $data['nama_pelanggan'],'telepon' => $telp,"us" => $data["bayar"]]);

        

    }

    public function forgoting(Request $req){
        $req->session()->forget('transaksi');
        $req->session()->forget('datadetail');

        return redirect()->route('kasir');
    }

    
    public function resetTransaction(Request $req){
        $id_trans = $req->session()->get('transaksi')['id_transaksi'];

        DB::table("transaksi")->where("kode_trans",$id_trans)->delete();
       // DB::table("detail_transaksi")->where("kode_trans",$id_trans)->delete();
        
        if ($req->session()->has("transaksi")){$req->session()->forget('transaksi');}
        $req->session()->forget('datadetail');
        return redirect()->route('kasir');
    }

    public function removedetail(Request $req){
        if($req->jenis == "normal"){
            $id_trans = $req->session()->get('transaksi')['id_transaksi'];
            $id_detail = $req->input('id_detail');
            DB::table("detail_transaksi")->where("id",$id_detail)->delete();
            $req->session()->forget('datadetail');
            $datadetail = DB::table('detail_transaksi')->join('new_produks','detail_transaksi.kode_produk','=','new_produks.kode_produk')->join('mereks','mereks.id_merek','=','new_produks.id_merek')->join('kode_types','kode_types.id_kodetype','=','new_produks.id_ct')->where('kode_trans',$id_trans)->get();

            $subtotal = 0;
            foreach($datadetail as $ds){
                $subtotal += Tools::doDisc($ds->jumlah,$ds->harga_produk,$ds->potongan,$ds->prefix);
            }

            DB::table("transaksi")->where("kode_trans", $id_trans)->update(['subtotal' => $subtotal]);
            
            $req->session()->put('datadetail', $datadetail); 
            return(json_encode($datadetail));
        }else{
            $id_trans = $req->session()->get('transaksi')['id_pre'];
            $id_detail = $req->input('id_detail');
            DB::table("preorder_detail")->where("id",$id_detail)->delete();
            $req->session()->forget('datadetail');
            $datadetail = DB::table('preorder_detail')->join('new_produks','preorder_detail.kode_produk','=','new_produks.kode_produk')->join('mereks','mereks.id_merek','=','new_produks.id_merek')->where('id_preorder',$id_trans)->get();

           
            
            $req->session()->put('datadetail', $datadetail); 
            return(json_encode($datadetail));
        }
    }



    public function cetaknotakecil(Request $req){
        $id = $req->session()->get('transaksi')['id_transaksi'];
        $data = DB::table('transaksi')->join('users', 'users.id', '=', 'transaksi.id_kasir')->where('kode_trans',$id)->get();
        $data2 = DB::table('detail_transaksi')->join('new_produks', 'new_produks.kode_produk','=','detail_transaksi.kode_produk')->join("mereks","mereks.id_merek","=","new_produks.id_merek")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->where('kode_trans',$id)->get();

        $pdf = PDF::loadview('nota.notakecil', ["data" => $data,"data2"=>$data2]);
        if($data[0]->antar == "ya"){
            $pdf = PDF::loadview('nota.notakecilkirim', ["data" => $data,"data2"=>$data2])->setPaper('a4', 'landscape');;
        }else{
            
        }
        
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'.$data[0]->kode_trans. '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink(storage_path("pdf/$fileName"));

    	return response()->json(["filename" => $base64]);
    }

    public function printnotakecil(Request $req){
        $id = $req->id;
        $data = DB::table('transaksi')->join('users', 'users.id', '=', 'transaksi.id_kasir')->where('kode_trans',$id)->get();
       
        $data2 = DB::table('detail_transaksi')->join('new_produks', 'new_produks.kode_produk','=','detail_transaksi.kode_produk')->join("mereks","mereks.id_merek","=","new_produks.id_merek")->where('kode_trans',$id)->get();
    
       
        

        $pdf = PDF::loadview('nota.notakecil', ["data" => $data,"data2"=>$data2]);
         if($data[0]->antar == "ya"){
            $pdf = PDF::loadview('nota.notakecilkirim', ["data" => $data,"data2"=>$data2]);
        }else{
            
        }
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'.$data[0]->kode_trans. '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink(storage_path("pdf/$fileName"));

    	return response()->json(["filename" => $base64]);
    }



    public function printpreorder($id){
        $data = DB::table('preorder')->where('id', $id)->get();
        $data2 = DB::table('preorder_detail')->join("new_produks","new_produks.kode_produk","=","preorder_detail.kode_produk")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->join("mereks","mereks.id_merek","=","new_produks.id_merek")->where('id_preorder', $id)->get();
        $pdf = PDF::loadview('preorder', ["data" => $data[0],"data2"=>$data2])->setPaper('a4', 'landscape');;
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'."PREORDER". '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink(storage_path("pdf/$fileName"));
        return $base64;
    }
    public function tambahpreorder(Request $req){
        $ttd = $req->ttd;
        $telepon = $req->telepon;
        $us = $req->us;
        $gm = $req->gm;
        $sejumlah = $req->sejumlah;
        
        $id = DB::table('preorder')->insertGetId(['ttd' => $ttd,'us'=>$us,'gm'=> $gm,'sejumlah'=>$sejumlah,'telepon'=>$telepon]);
        
        $preordercetak = $this->printpreorder($id);
        
        return json_encode(['filename' => $preordercetak,'id'=> $id]);
    }

    public function cetakpreorder(Request $req){
        $id= $req->id;
        $printku = $this->printpreorder($id);

        return json_encode(['filename'=>$printku]);
    }
 
}
