@php $whoactive = "stokharian" ;

    $master='admingudang';
@endphp
@extends('layouts.layout2')
@section('icon', 'fa fa-cube ml-3 mr-2')

@section('pagetitle', 'Stok Harian')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail_stok.css') }}">
<script src="{{ asset('js/print.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/open_sans.css') }}">
@stop

    @section('title','Stok Harian')
    @section('js')
    <script src="{{ url('js/detailstok.js') }}"></script>
    @stop

        @section('content')
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <input required required class="search-box" type="text" placeholder="Scan atau cari item...">
                        <i class="fas fa-search ml-1 search-icon text-light"></i>
                    </div>
                    <div class="col-6">
                        <button style="font-size: 0.85rem" type="button" class="btn float-right btn-primary ml-2" data-toggle="modal"
                        data-target="#cetakmodal"><i style="font-size: 0.85rem" class="fa fa-print mr-1"></i>Print</button>

                        <button type="button" class="btn btn-tambah-data btn-primary ml-2 float-right"
                            data-toggle="modal" data-target="#exampleModals">
                            <i class="fa fa-undo mr-2"></i>Return Barang
                        </button>

                        <button type="button" class="btn float-right btn-tambah-data" data-toggle="modal"
                            data-target="#exampleModal"><i class="fa fa-plus mr-2"></i>Tambah Data</button>

                        
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <h2 class="card-title font-weight-bold ml-3 mt-4 mb-4">Hari Ini</h4>
                </div>
            </div>

            <div class="row">
                <div class="col" id="dscont">


                </div>
            </div>
            <div class="row">
                <div class="col" id="rscont">


                </div>
            </div>





            <!-- Modal -->
            <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form id="detailstoksubmitter">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label><br>
                                    <input required type="datetime-local" class="form-control" name="tanggal"
                                        id="tanggal">
                                </div>

                                <div class="form-group parent1">
                                    <label for="produk-select">Produk</label>
                                    <input class="custom-select inputan-produk" name="produk-select" id="produk-select">

                                    <ul class="myUL">
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah-select">Jumlah</label>
                                    <input required type="number" class="form-control" name="jumlah-select" id="jumlah">
                                </div>
                                <div class="form-group">
                                    <label for="status-select">Status</label>
                                    <select class="custom-select" name="status-select" id="status-select" required>
                                        <option value="masuk">Masuk</option>
                                        <option value="keluar">Keluar</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label><br>
                                    <textarea required cols="88.5" rows="5" class="form-control" name="keterangan"
                                        id="keterangan"></textarea>
                                </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div>



            <div class="modal fade bd-example-modal-lg" id="cetakmodal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cetak Stok Harian</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form id="cetaksubmitter">
                            <div class="form-group">
                                    <label for="tanggal">Pilih Berdasarkan</label><br>
                                    <select name="" id="berdasarkan" class="form-control">
                                    <option value="hmb">Harian Minggu Bulanan</option>
                                        <option value="tanggal">Tanggal</option>
                                      
                                    </select>
                                </div>
                                <div class="row" id="toggle-tanggal">
                                    <div class="col">
                                    <label for="">Dari</label>
                                    </div>
                                    <div>
                                    <input type="date" id="tanggal2" class="form-control">
                                    </div>
                                    <div class="w-100">

                                    </div>
                                    <div class="col">
                                    <label for="">Sampai</label>
                                    </div>
                                    <div>
                                    <input type="date" id="tanggal3" class="form-control">
                                    </div>
                                    
                                   
                                </div>
                                <div class="form-group" id="toggle-hmb">
                                    <select name="" id="hmb" class="form-control">
                                        <option value="harian">Harian</option>
                                        <option value="mingguan">Mingguan</option>
                                        <option value="bulanan">Bulanan</option>
                                    </select>
                                </div>
                                <div class="form-check">
                                    <input id="keluars" class="form-check-input" type="checkbox"
                                       >
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Barang Keluar
                                    </label>
                                
                                </div>
                                <div class="form-check">
                                    <input id="masuks" class="form-check-input" type="checkbox" >
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Barang Masuk
                                    </label>
                                
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        id="suplier">
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Retur  Kesuplier
                                    </label>
                                
                                </div>





                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Cetak</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade  bd-example-modal-lg" id="exampleModals" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Return Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url('/tambahrs') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input name="tanggal" type="date" class="form-control">
                                </div>
                                <div class="form-row mt-3 " id="first-row">
                                    <div class="col-6 parent1">
                                        <label for="">Kode Produk</label>
                                        <input type="text" class="form-control inputan-produk"
                                            placeholder="Ketik Kode Atau Nama" name='kode[]'>
                                        <ul class="myUL">

                                        </ul>
                                    </div>
                                    <div class="col-3 parent1">
                                        <label for="">Nama</label>
                                        <input type="text" class="form-control nama-produk" placeholder="Nama Produk">
                                    </div>
                                    <div class="col parent1">
                                        <label for="">Jml</label>
                                        <input type="text" class="form-control" placeholder="Jumlah" name='jumlah[]'>

                                    </div>
                                    <div class="col-sm parent">

                                    </div>
                                </div>
                                <a class="btn btn-primary mt-3" id="tambahbutton">Tambah+</a>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control"></textarea>
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


        </section>
        @stop
