<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;


class PreorderController extends Controller
{
    public function loaddata(Request $req){
        $isEmpty = true;
        $data = null;
        $dataopsi = null;
        if($req->session()->has('id_nb')){
           $data =  DB::table('nota_besar')->where('id_transaksi', $req->session()->get('id_nb'))->first();
           $dataopsi =  DB::table('nb_detail')->where('id_nb', $req->session()->get('id_nb'))->get()->toArray();
           $isEmpty = false;
        }
        return json_encode(['data' => (array)$data, 'dataopsi' => (array) $dataopsi, 'isEmpty'  => $isEmpty,'page'=>'kasir']);
    }
    public function index(Request $req,$id = null){
        if($id != null){
            return view("notabesar", ["id"=>$id]);
        }else{
            return view("notabesar");
        }
            
          
        
     
    }

    public function bayaring($id){

        return view("notabesar");
        
}


    public function tambahpreorder(Request $req){
        $data = $req->input('data');

     
    

      
       
        $id_trans = $data["id_pre"];
        
        if($id_trans != "null"){
       
        }else{
            $id = DB::table('preorder')->insertGetId(['ttd'=>'orang']);
            $id_trans = $id;
        }
    
        $counter = DB::table('preorder_detail')->where('id_preorder', $id_trans)->where('kode_produk', $data['kode_produk'])->count();
        if($counter < 1){
            DB::table('preorder_detail')->insert(['id_preorder' => $id_trans, 'kode_produk' => $data['kode_produk'], "jumlah" => $data["jumlah"]]);
        }else{
            $jumlah = DB::table('preorder_detail')->where('id_preorder', $id_trans)->where('kode_produk', $data['kode_produk'])->pluck('jumlah')->first();
            DB::table('preorder_detail')->where('id_preorder', $id_trans)->where('kode_produk', $data['kode_produk'])->update(['jumlah' => $jumlah + $data['jumlah']]);
        }
        
        $datadetail = DB::table('preorder_detail')->join('new_produks','preorder_detail.kode_produk','=','new_produks.kode_produk')
        ->join('kode_types','kode_types.id_kodetype','=','new_produks.id_ct')
        ->join('mereks','mereks.id_merek','=','new_produks.id_merek')->where('id_preorder',$id_trans)->get();

        
        
       
        return(json_encode(['datadetail'=>$datadetail]));
    }


    public function tambahtransaksi(Request $req){
        // $ttd = $req->input('ttd');
        // $up = $req->input('up');
        // $us = $req->input('us');
        // $brp = $req->input('brp');
        // $gm = $req->input('gm');
        // $total = $req->input('total');

        $judulopsi = $req->input('judulopsi');
        $ketopsi = $req->input('ketopsi');
        $formdata = $req->input('formData');
        $tanggal = $req->tanggal;
        

        $tanggal2 = $req->jt;



        $id;
        $id2 = "";
        $id3;
        $no;
        if($req->session()->has('id_nb')){
            $id = $req->session()->get('id_nb');
            DB::table('nota_besar')->where('id_transaksi', $id)->update($req->input('formData'));
        }else{
            $counter = DB::table('nota_besar')->count();
            $counter = $counter+1;
            $no = date("ymd").str_pad($counter+1,3,0,STR_PAD_LEFT);
            $counting = DB::table('nota_besar')->where("no_nota",$no)->count();

                    

            
            $id = DB::table('nota_besar')->insertGetId(array_merge($req->input('formData'),['no_nota' => $no, 'termin' => 1, "status" => "dibayar",'created_at'=>$tanggal, 'jatuh_tempo'=>$tanggal2,]));
            $id2 = DB::table('nota_besar')->insertGetId(['ttd'=> $formdata["ttd"],'up'=> $formdata["up"],'gm'=> $formdata["gm"],'total'=> $formdata["total"],'no_nota' => $no, 'termin' => 2, "status" => "ready",'jatuh_tempo'=>$tanggal2]);
            $id3 = DB::table('nota_besar')->insertGetId(['ttd'=> $formdata["ttd"],'up'=> $formdata["up"],'gm'=> $formdata["gm"],'total'=> $formdata["total"],'no_nota' => $no, 'termin' => 3,'jatuh_tempo'=>$tanggal2]);
             
            
          
        }


       

    
        
        for($i = 0; $i < count($judulopsi);$i++){
            if($req->session()->has('id_nb')){
              
                $count = DB::table('nb_detail')->where('id_nb', $id)->where('opsi',$i+1)->count();
                if($count > 0){
                    DB::table('nb_detail')->where('id_nb', $id)->where('opsi',$i+1)->update(['judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
                }else{
                    DB::table('nb_detail')->insert(['id_nb' => $id, 'opsi' => $i+1, 'judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
                }
            }else{
                DB::table('nb_detail')->insert(['id_nb' => $id, 'opsi' => $i+1, 'judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
            }
            
        }

        for($i = 0; $i < count($judulopsi);$i++){
            if($req->session()->has('id_nb')){
              
                $count = DB::table('nb_detail')->where('id_nb', $id2)->where('opsi',$i+1)->count();
                if($count > 0){
                    DB::table('nb_detail')->where('id_nb', $id2)->where('opsi',$i+1)->update(['judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
                }else{
                    DB::table('nb_detail')->insert(['id_nb' => $id2, 'opsi' => $i+1, 'judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
                }
            }else{
                DB::table('nb_detail')->insert(['id_nb' => $id2, 'opsi' => $i+1, 'judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
            }
            
        }

        for($i = 0; $i < count($judulopsi);$i++){
            if($req->session()->has('id_nb')){
              
                $count = DB::table('nb_detail')->where('id_nb', $id3)->where('opsi',$i+1)->count();
                if($count > 0){
                    DB::table('nb_detail')->where('id_nb', $id3)->where('opsi',$i+1)->update(['judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
                }else{
                    DB::table('nb_detail')->insert(['id_nb' => $id3, 'opsi' => $i+1, 'judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
                }
            }else{
                DB::table('nb_detail')->insert(['id_nb' => $id3, 'opsi' => $i+1, 'judul' => $judulopsi[$i],'ket' => $ketopsi[$i]]);
            }
            
        }

        



       

        return json_encode(["id_nb" => $id, "no_nota" => $no]);
        
    }


    public function bayarpreorder(Request $req){
        $id = $req->input("id_transaksi");
        $formdata = $req->input("formData");

        
        $termin = DB::table("nota_besar")->where("id_transaksi", $id)->get()[0]->termin;



        $no_nota = DB::table("nota_besar")->where("id_transaksi", $id)->get()[0]->no_nota;
        $has = DB::table("nota_besar")->where("id_transaksi", $id)->first();

        if($has->kunci == null and $has->jatuh_tempo == null and $termin == 3){
            
        }else{



        DB::table("nota_besar")->where("no_nota", $no_nota)->where("termin",$termin)->update(["status" => "dibayar",'created_at'=>$req->tanggal]);
     if($termin != 3){
        DB::table("nota_besar")->where("no_nota", $no_nota)->where("termin",$termin+1)->update(["status" => "menunggu"]);
     }  
        DB::table('nota_besar')->where("id_transaksi", $id)->update(['created_at'=>$req->tanggal,'us'=> $formdata["us"],'brp'=> $formdata["brp"]]);
        if($termin == 2){
            DB::table('nota_besar')->where("id_transaksi", $id)->update(['kunci'=>$req->kunci]);
        }
        }

        return json_encode(["id_nb" => $id,"no_nota" => $no_nota]);
    }









    public function resettrans(Request $req){
        $req->session()->forget('id_nb');
    }


    public function search(Request $req){
        $no_nota = $req->input("kw");


        $data = DB::table("nota_besar")->where("no_nota",$no_nota)->get();

        return json_encode($data);
    }

    public function getnb(Request $req){
        $id_trans = $req->input("id_transaksi");

       
        $data1 = DB::table('nota_besar')->where('id_transaksi', $id_trans)->select(DB::raw("substr(created_at,1,10) as tanggalbuat"),"nota_besar.*")->get();
        $data2 = DB::table('nb_detail')->where('id_nb', $id_trans)->get();
        $nn = $data1[0]->no_nota;
        
        $termin = $data1[0]->termin;
        $td = DB::table("nota_besar")->where("no_nota", $nn)->where("termin", "<", $termin)->sum("us");
         //cek apakah termin sebelumnya sudah lunas
         if($termin-1){
            $status = DB::table('nota_besar')->where("no_nota",$nn)->where("termin", $termin-1)->get()[0]->status;

         if($status == "dibayar"){
            
         
            return json_encode(["nb" => $data1,  "opsi" => $data2, "td"=>$td,]);
         }else{
            $data1 = DB::table('nota_besar')->where("no_nota",$nn)->where("termin", $termin-1)->get();
            $td = DB::table("nota_besar")->where("no_nota", $nn)->where("termin", "<", $termin-1)->sum("us");
            return json_encode(["nb" => $data1,  "opsi" => $data2, "td"=>$td, "peringatan"=>"Termin Sebelumnya harus dilunasi"]);
         }
         }else{
            return json_encode(["nb" => $data1,  "opsi" => $data2, "td"=>$td,]);
         }
         
        
      
    }

    public function cetaknotabesar(Request $req){
        $id_trans = $req->input("id_transaksi");

        $data = DB::table("nota_besar")->where('id_transaksi', $id_trans)->get();
        $td = DB::table("nota_besar")->where('no_nota',$data[0]->no_nota)->where("termin","<",$data[0]->termin)->count();
        $opsi = DB::table("nb_detail")->join("nota_besar", "nota_besar.id_transaksi", "=", "nb_detail.id_nb")->where("id_nb", $id_trans)->get();
        
        $pdf = PDF::loadview('nota_besar', ["data" => $data[0],"opsi"=>$opsi, "td" => $td < 1 ? 0 : $td = DB::table("nota_besar")->where('no_nota',$data[0]->no_nota)->where("termin","<",$data[0]->termin)->sum("us")])
        ;
        $path = public_path('pdf/');
        $fileName =  date('mdy').'-'.$data[0]->id_transaksi. '.' . "nota_besar".'pdf' ;
        $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);

    	return response()->json(["filename" => $base64]);
    }
    public function cetaksuratjalan(Request $req){
        $id_trans = $req->id_transaksi;
        $data = DB::table("nota_besar")->where('id_transaksi', $id_trans)->get();
        $opsi = DB::table("nb_detail")->join("nota_besar", "nota_besar.id_transaksi", "=", "nb_detail.id_nb")->where("id_nb", $id_trans)->get();
        $pdf = PDF::loadview('notabesarsj', ["data" => $data[0],"opsi"=>$opsi]);
        $path = public_path('pdf/');
        $fileName =  date('mdy').'-'.$data[0]->id_transaksi. '.' . "suratjalan".'.pdf' ;
        $pdf->save(storage_path("pdf/Surat Jalan Nota Besar/$fileName"));
        $storagepath = storage_path("pdf/Surat Jalan Nota Besar/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);

    	return response()->json(["filename" => $base64]);
    }

    public function cetaksuratjalan2($id){
        $id_trans = $id;
        $data = DB::table("nota_besar")->where('id_transaksi', $id_trans)->get();
        $opsi = DB::table("nb_detail")->join("nota_besar", "nota_besar.id_transaksi", "=", "nb_detail.id_nb")->where("id_nb", $id_trans)->get();
        $pdf = PDF::loadview('notabesarsj', ["data" => $data[0],"opsi"=>$opsi]);
        $path = public_path('pdf/');
        $fileName =  date('mdy').'-'.$data[0]->id_transaksi. '.' . "suratjalan".'.pdf' ;
        $pdf->save(storage_path("pdf/Surat Jalan Nota Besar/$fileName"));
        $storagepath = storage_path("pdf/Surat Jalan Nota Besar/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);

    	return $base64;
    }

    public function kirimsj(Request $req){
        $id=$req->id_trans;
       
        $status=DB::table('nota_besar')->where('id_transaksi',$id)->pluck('status')->first();
        $key=DB::table('nota_besar')->where('id_transaksi',$id)->pluck('kunci')->first();
        if($status == "dibayar"){
            if($key == null){
                $no_nota = DB::table("nota_besar")->where('id_transaksi',$id)->pluck("no_nota")->first();
                $termin = DB::table("nota_besar")->where('id_transaksi',$id)->pluck("termin")->first();
                $update = DB::table('nota_besar')->where('no_nota',$no_nota)->update(['kunci'=>$req->kunci,"jatuh_tempo"=>$req->jt]);
                $update2 = DB::table('nota_besar')->where('no_nota',$no_nota)->where('termin',$termin+1)->update(['kunci'=>$req->kunci,"jatuh_tempo"=>$req->jt,'status'=>'ready']);
                return response()->json(["filename" => null]);
            }else{
                $base64 = $this->cetaksuratjalan2($id);
                return response()->json(["filename" => $base64]);
    
            }
      
          
        }

        
        
     

        
    }



    
    public function sj2(Request $req){
        $base64 = $this->cetaksuratjalan2($req->id_trans);
                return response()->json(["filename" => $base64]);
    }
  
}
