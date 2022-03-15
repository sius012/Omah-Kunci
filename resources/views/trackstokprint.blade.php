@php 
    $no = 1;
@endphp
@php
        $date = \Carbon\Carbon::parse(date('d-M-Y'))->locale('id');

        $date->settings(['formatFunction' => 'translatedFormat']);

    @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
   
        * {
         
            margin: 0px;
        }

        body{
            margin: 1cm;
        }

         .logo-img {
           width: 120px;
        }

        .container .header {
            align-items: center;
    

            margin-top: 35px;
        }

        .container .address {
  
            font-size: 8pt;
            margin-bottom: 40px;
            width: 
        }

        .container .data-wrapper {
   

        }
        .container .data-wrapper .table tbody tr td, th{
            padding: 2px;
        }

        .container .data-wrapper .table{
            border-collapse: collapse;
        
        }

        .table-data{
        width: 1px;
        border-collapse: collapse;
        
        }

        .table-data td, .table-data th{
            border: 1px solid black;
            font-size: 8pt;
            margin: 0;
            text-align: left;
        }

        .barcode{
       
            font-size: 8pt !important;
        }

    

        td{
            padding: 2px;
        }

        p{
            margin: 0;
            font-size: 8pt;

        }
        h3{
            font-size: 10pt;
        }
    </style>
</head>

<body>
  
    
            <table style="width: 190mm; margin-left:0px">
                <tr>
                    <th align="center"> <img class="logo-img" src="{{ public_path('assets/logo.svg') }}" alt=""></th>
                </tr>
                <tr>
                    <th align="center"><p class="address">
                            Jl. Agus Salim D no.10 <br> Telp/Fax. (024) 3554929 / 085712423453 Semarang <br>
                        </p></th>
                </tr>
            </table>
  
            <br>
            <br>
            <h3>Stok Harian Produk</h3>
        <p style="margin-bottom: 20px">Tanggal : {{date('d-M-Y')}}</p>

     
        @isset($m2)
           <h5>Daftar Barang masuk melalui stok harian</h5>
            <table class="table-data" style="width:180mm !important; margin-top: 20px; margin: 20px" >
            <tr>
                        <th >No</th>
                        <th>Tanggal</th>
                        <th style="width:60px">Kode Produk</th>
                        <th>Nama Produk</th>
                        <th >Merek</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>A. Gudang</th>
                    </tr>
    
            @php  $no = 1@endphp
         
              
              
                    @foreach($m2 as $da)
                    <tr>
                       <td >{{$no}}</td>
                       <td>{{date("d-m-y",strtotime($da->created_at))}}</td>
                        <td>{{$da->kode_produk}}</td>
                        <td>{{$da->nama_produk}}</td>
                        <td >{{$da->nama_merek}}</td>
                        <td>{{$da->jumlah}}</td>
                        <td >{{$da->keterangan}}</td>
                        <td >{{$da->name}}</td>
                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
        @endisset

        @isset($m1)
        <h5>Daftar Barang masuk melalui transaksi</h5>
            <table class="table-data" style="width:180mm !important; margin-top: 20px; margin: 20px" >
            <tr>
                        <th >No</th>
                        <th style="width:60px">Tanggal</th>
                        <th style="width:60px">Kode Produk</th>
                        <th>Nama Produk</th>
                        <th >Merek</th>
                        <th>Jumlah</th>
                    </tr>
    
            @php  $no = 1@endphp
         
              
              
                    @foreach($m1 as $da)
                    <tr>
                       <td >{{$no}}</td>
                       <td>{{date("d-m-y",strtotime($da->created_at))}}</td>
                        <td>{{$da->kode_produk}}</td>
                        <td>{{$da->nama_produk}}</td>
                        <td >{{$da->nama_merek}}</td>
                        <td>{{$da->jumlah}}</td>

                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
        @endisset


        @isset($k2)
            <h5>Daftar Barang keluar melalui stok harian</h5>
            <table class="table-data" style="width:180mm !important; margin-top: 20px; margin: 20px" >
            <tr>
            <th >No</th>
                        <th style="width:60px">Kode Produk</th>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        
                        <th >Merek</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>A. Gudang</th>
                    </tr>
    
            @php  $no = 1@endphp
         
              
              
                    @foreach($k2 as $da)
                    <tr>
                       <td >{{$no}}</td>
                       <td>{{date("d-m-y",strtotime($da->created_at))}}</td>
                        <td>{{$da->kode_produk}}</td>
                        <td>{{$da->nama_produk}}</td>
                        <td >{{$da->nama_merek}}</td>
                        <td>{{$da->jumlah}}</td>
                        <td >{{$da->keterangan}}</td>
                        <td >{{$da->name}}</td>
                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
        @endisset

        
        @isset($k1)
            <h5>Produk yang keluar melalui transaksi</h5>
            <table class="table-data" style="width:180mm !important; margin-top: 20px; margin: 20px" >
            <tr>
                        <th >No</th>
                        <th>Tanggal</th>
                        <th style="width:60px">Kode Produk</th>
                        <th>Nama Produk</th>
                      
                        <th >Merek</th>
                       @if(Auth::user()->roles[0]['name']=='manager') <th>Harga</th> @endif
                        <th>Jumlah</th>
                    </tr>
    
            @php  
            $jumlah=0;
            $total=0;
            
            $no = 1@endphp
         

              
                    @foreach($k1 as $da)
                    <tr>
                       <td >{{$no}}</td>
                       <td>{{$da->created_at}}</td>
                        <td>{{$da->kode_produk}}</td>
                        <td>{{$da->nama_produk}}</td>
                        <td >{{$da->nama_merek}}</td>
                        @if(Auth::user()->roles[0]['name']="manager") <td >{{number_format(Tools::doDisc($da->jumlah,$da->harga_produk,$da->potongan,$da->prefix),0,",",".")}}</td>@endif
                        <td>{{$da->jumlah}}</td>

                    </tr>
                    @php
                    $total += Tools::doDisc($da->jumlah,$da->harga_produk,$da->potongan,$da->prefix);
                    $jumlah += $da->jumlah;
                    
                    
                    $no++ @endphp
                    
                    @endforeach
                    <tr>
                    @if(Auth::user()->roles[0]['name']="manager") <td colspan="5">Total</td> @else <td colspan="6">Total</td>@endif
                        @if(Auth::user()->roles[0]['name']="manager")<td>Rp. {{number_format($total,0,",",".")}}</td>@endif
                        <td>{{$jumlah}}</td>

                    </tr>
                
            </table>
        @endisset

        
        @isset($suplier)
            <h5>Data retur ke suplier</h5>
            <table class="table-data" style="width:180mm !important; margin-top: 20px; margin: 20px" >
            <tr>
                        <th >No</th>
                        <th>Kode Produk</th>
                        <th >Tanggal</th>
                        <th>Nama Produk</th>
                        <th >Merek</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Admin Gudang</th>
                    </tr>
    
            @php  $no = 1@endphp
         
              
              
                    @foreach($suplier as $da)
                    <tr>
                       <td >{{$no}}</td>
                        <td>{{$da["kode_produk"]}}</td>
                        <td>{{date("d-m-yy",strtotime($da['tanggal']))}}</td>
                        <td>{{$da["nama_produk"]}}</td>
                        <td>{{$da["nama_merek"]}}</td>
                        <td >{{$da["jumlah"]}}</td>
                        <td>{{$da["keterangan"]}}</td>
                        <td>{{$da["Nama Admin"]}}</td>
                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
        @endisset
        
 
 
</body>

</html>