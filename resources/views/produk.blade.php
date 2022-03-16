@php
$whoactive = 'produk';
$master = 'manager';
$no = 1;

$kw = isset($keyword) ? $keyword : '';
$tp = isset($tipe) ? $tipe : '';
$ct = isset($id_ct) ? $id_ct : '';
$m = isset($mereknya) ? $mereknya : '';
@endphp
@extends('layouts.layout2')
@section('title','Produk')

@section('titlepage', 'Produk')

@section('js')
    <script src="{{ asset('js/print.js') }}"></script>
    <script src="{{ asset('js/produk.js') }}"></script>
    <script src="{{ asset('js/produk2.js') }}"></script>
    <style>
        td{
            font-weight: unset !important;
            font-size: 8pt
        }
    </style>
    <script>
        $(document).ready(function(){
           $("#modalprodukedit").modal("show");

            $(".btclose").click(function(){
                window.location = "/produk";
            });
        });
    </script>
@stop
@section('content')


    <div class="card">
        <div class="card-header">
            <h2 style="font-size: 1.5rem;" class="card-title mt-2 font-weight-bold"><i class="fa fa-list mr-3"></i>Data Produk</h2>
        </div>
        <div class="card-body">
            <form action="ProdukController@index" method="get">
                @csrf
                <input type="text" name="nama" class="form-control form-control-sm w-25" placeholder="ketik nama atau kodeproduk">
                <br>
                <h5 class="card-title mb-0">Cari Berdasarkan : </h5>
                <br>
                <div class="wrappers d-inline-flex mt-1">
                    
                <div class="form-group d-inline-flex">
                    
                    <select name="tipe" id="tipe" class="form-control dynamic w-50 form-control-sm mr-5"  data-dependent = "state">
                        <option value="">TIPE</option>
                        @foreach($tipe as $tipes)
                            <option value = "{{$tipes->id_tipe}}">{{$tipes->nama_tipe}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-left: -100px;" class="form-group d-inline-flex">
                    <select name="kodetipe" id="kodetype" class="form-control dynamic w-75 form-control-sm" data-dependent = "state">
                        <option value="">TIPE KODE</option>
                        @foreach($kodetype as $kt)
                            <option value="{{$kt->id_kodetype}}">{{$kt->nama_kodetype}}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-left: -43px;" class="form-group d-inline-flex">
                    <select name="merek" id="merek" class="form-control dynamic w-75 form-control-sm" data-dependent = "state">
                        <option value="">MEREK</option>
                        @foreach($merek as $merks)
                            <option value="{{$merks->id_merek}}">{{$merks->nama_merek}}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <button style="margin-left: -20px" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                <button type="button" class="btn btn-sm mt-1 btn-primary float-right" data-toggle="modal" data-target="#modalproduk">
                    Tambah Produk <i class="fa fa-plus ml-2"></i>
                </button>
            </form>

            <table class="table table-striped mt-1 table-bordered">
                <thead style="font-size:10pt;">
                    <tr>
                        <th>No</th>
                        <th style="width:110px;">Tipe</th>
                        <th style="width:130px;">Tipe Kode</th>
                        <th>Merek</th>
                        <th>Barcode</th>
                        <th style="width:180px;">Nama Produk</th>
                        <th>Satuan</th>
                        <th style="width:130px;">Harga</th>
                        <th>Diskon</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $produks)
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $produks->nama_tipe}}</td>
                            <td>{{ $produks->nama_kodetype}}</td>
                            <td>{{ $produks->nama_merek }}</td>
                            <td>{{ $produks->kode_produk }}</td>
                            <td>{{ $produks->nama_produk }}</td>
                            
                            
                            
                            <td>{{ $produks->satuan }}</td>
                            <td>Rp. {{ number_format($produks->harga) }}</td>
                            <td>{{$produks->diskon_tipe == "persen" ? $produks->diskon."%" : "Rp.".number_format((int)$produks->diskon) }}</td>
                            <td class="d-inline-flex" align="center">
                                <a class="btn btn-primary"
                                    href={{ url('/editproduk?kodebarcode=' . $produks->kode_produk) }}><i
                                        class="fa fa-edit"></i></a>
                                <a class="btn btn-danger ml-1 mr-1 hapusproduk"
                                    href="{{ route('hapusproduk', ['kode' => $produks->kode_produk]) }}"><i
                                        class="fa fa-trash"></i></a>
                                <a kode_produk='{{ $produks->kode_produk }}' href="#" class="btn btn-primary cetak-barcode"
                                    data-toggle="modal" data-target="#exampleModal"><i class="fa fa-barcode"></i></a>
                            </td>
                        </tr>
                        @php $no++ @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Button trigger modal -->


    <!-- Modal Tambah/Edit Produk-->
    <div class="modal fade tambahbarangform" id="modalproduk" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/updateproduk" id="submitterproduk">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="nama-produk" aria-describedby="emailHelp"
                                required>
                        </div>
                        <div class="div">
                        <label for="exampleInputEmail1" class="form-label">Tipe</label>
                        <div class="mb-3 input-group">
                            
                      
                            <div class="input-group-prepend">
                                       <select name="" class="form-control" id="tipe-opsi">
                                           <option value="sudah ada">Sudah ada</option>
                                           <option value="baru">Baru</option>
                                       </select>
                                </div>
                            <select name="" id="tipe-produk" class="form-control tipe-selected">
                            @foreach($tipe as $tipes)
                            <option value = "{{$tipes->id_tipe}}">{{$tipes->nama_tipe}}</option>
                        @endforeach
                            </select>
                            <input type="text" class="form-control tipe-produk" id="tipe-baru">

               
                        </div>
                        </div>
                        <div class="div">
                        <label for="exampleInputEmail1" class="form-label">Tipe Kode</label>
                        <div class="mb-3 input-group">
                            
                      
                            <div class="input-group-prepend">
                                       <select name="" class="form-control" id="tipekode-opsi">
                                           <option value="sudah ada">Sudah ada</option>
                                           <option value="baru">Baru</option>
                                       </select>
                                </div>
                            <select name="" id="tipekode-produk" class="form-control kodetipe-selected">
                            @foreach($kodetype as $tp)
                            <option value = "{{$tp->id_kodetype}}">{{$tp->nama_kodetype}}</option>
                        @endforeach
                            </select>
                            <input type="text" class="form-control tipekode-produk" id="tipekode-baru">

               
                        </div>
                        </div>
                        <div class="div">
                        <label for="exampleInputEmail1" class="form-label">Merek</label>
                        <div class="mb-3 input-group">
                            
                      
                            <div class="input-group-prepend">
                                       <select name="" class="form-control" id="merek-opsi">
                                           <option value="sudah ada">Sudah ada</option>
                                           <option value="baru">Baru</option>
                                       </select>
                                </div>
                            <select name="" id="merek-produk" class="form-control merek-selected">
                            @foreach($merek as $mereks)
                            <option value = "{{$mereks->id_merek}}">{{$mereks->nama_merek}}</option>
                        @endforeach
                            </select>
                            <input type="text" class="form-control merek-produk" id="merek-baru">

               
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Harga</label>
                            <input type="text" class="form-control uang" id="harga-produk" aria-describedby="emailHelp">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="inputEmail4">Diskon</label>
                            <input type="number" class="form-control" id="diskon" placeholder="Diskon">
                            </div>
                            <div class="form-group col-md-6">
                            <label for="inputPassword4">Tipe</label>
                            <select name="typediskon" id="typediskon" class="custom-select">
                                <option value="rupiah">Rupiah(Rp)</option>
                                <option value="persen">Persentase(%)</option>
                            </select>
                            </div>
                        </div>
                            
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan-produk" aria-describedby="emailHelp">
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div>

    </div>













    @php

    @endphp


    @isset($data)


        <!-- Modal Tambah/Edit Produk-->
        <div class="modal fade tambahbarangform" id="modalprodukedit" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Produk</h5>
                        <button type="button" class="button btclose" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('editproduk', ['id' => $data->kode_produk]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Nama Barang</label>
                                <input type="text" class="form-control" aria-describedby="emailHelp" required
                                    value="{{ $data->nama_produk }}" name="nama_produk">
                            </div>
                            <div class="mb-3 form-group">
                        
                                <label for="exampleInputEmail1" class="form-label">Merek</label>
                               
                                <select class="form-control" name="id_merek" disabled>
                                    @foreach ($merek as $merks)
                                        <option value="{{ $merks->id_merek }}" @if ($merks->nama_merek == $data->nama_merek) selected @endif>
                                            {{ $merks->nama_merek}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tipe</label>
                                <select class="form-control" name="id_tipe" disabled>
                                    @foreach ($tipe as $kats)
                                        <option value="{{ $kats->id_tipe }}"
                                            @if ($kats->id_tipe == $data->id_tipe) selected @endif>{{ $kats->nama_tipe }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tipe Kode</label>
                                <select class="form-control" name="id_ct" disabled>
                                    @foreach ($kodetype as $kt)
                                        <option value="{{ $kt->id_kodetype }}" @if ($kt->id_kodetype == $data->id_ct) selected @endif>
                                            {{ $kt->nama_kodetype}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Harga</label>
                                <input type="text" class="form-control uang" aria-describedby="emailHelp"
                                    value="{{number_format( $data->harga,0,',','.') }}" name="harga">
                            </div>
                            <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="inputEmail4">Diskon</label>
                            <input type="number" class="form-control" id="diskon" name="diskon" placeholder="Diskon" value="{{$data->diskon}}">
                            </div>
                            <div class="form-group col-md-6">
                            <label for="inputPassword4">Tipe</label>
                            <select name="diskon_tipe" id="typediskon" class="custom-select">
                                <option @if($data->diskon_tipe == "rupiah") selected @endif value="rupiah">Rupiah(Rp)</option>
                                <option @if($data->diskon_tipe == "persen") selected @endif value="persen">Persentase(%)</option>
                            </select>
                            </div>
                        </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Satuan</label>
                                <input type="text" class="form-control" aria-describedby="emailHelp"
                                    value="{{ $data->satuan }}" name="satuan">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btclose" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                    </form>
                </div>
            </div>
        @endisset

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Barcode Print</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-target="#exampleModalBarcode">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Jumlah</label>
                            <input max=50 min=1 type="number" class="form-control" id="jml" aria-describedby="emailHelp">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-warning" kode_produk="" id="cetaker"><i
                                class="fa fa-print mr-1"></i>Print</button>
                    </div>
                </div>
            </div>
        </div>

        <script> 

$(document).ready(function(){


   

$(".uang").keyup(function(){
        $(this).val(formatRupiah($(this).val(),""))
});


function formatRupiah(angka, prefix){
var number_string = angka.replace(/[^,\d]/g, '').toString(),
split   		= number_string.split(','),
sisa     		= split[0].length % 3,
rupiah     		= split[0].substr(0, sisa),
ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

// tambahkan titik jika yang di input sudah menjadi angka ribuan
if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
}

rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
}
});
</script>
    @stop
