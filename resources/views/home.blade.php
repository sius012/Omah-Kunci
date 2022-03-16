@php $whoactive="dashboard";
$master='home'; @endphp
@extends('layouts.layout2')

@section('title', 'Omah Kunci || Dashboard')

@section('content_header')
<h1 class="m-0 text-dark"></h1>
@stop

@section('content')
<!-- Main content -->
<div class="container-fluid">
    <!-- Info boxes -->

    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-0 bg-primary text-light">
                    <h5 class="card-title mt-2">Laporan harian</h5>
                    <h5 class="card-title float-right mt-2"><b>Hari Ini,
                            {{ date('d M Y') }}</b></h5>
                </div>
                <!-- /.card-header -->

                <!-- ./card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan Nota Kecil</span>

                                <h5 class="description-header">
                                    Rp.{{ number_format($daily['hari']['pemasukan nota kecil']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan nota besar</span>
                                <h5 class="description-header">Rp.
                                    {{ number_format($daily['hari']['pemasukan nota besar']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan preorder</span>
                                <h5 class="description-header">Rp.
                                    {{ number_format($daily['hari']['pemasukan preorder']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
            <div class="card">
                <div class="card-header mb-0 bg-primary text-light">
                    <h5 class="card-title mt-2">Laporan mingguan</h5>
                    <h5 class="card-title float-right mt-2"><b>Hari Ini,
                            {{ date('d M Y') }}</b></h5>
                </div>
                <!-- /.card-header -->

                <!-- ./card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan Nota Kecil</span>

                                <h5 class="description-header">
                                    Rp.{{ number_format($daily['hari']['pemasukan nota kecil']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan nota besar</span>
                                <h5 class="description-header">Rp.
                                    {{ number_format($daily['hari']['pemasukan nota besar']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan preorder</span>
                                <h5 class="description-header">Rp.
                                    {{ number_format($daily['hari']['pemasukan preorder']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
            <div class="card">
                <div class="card-header mb-0 bg-primary text-light">
                    <h5 class="card-title mt-2">Laporan bulanan</h5>
                    <h5 class="card-title float-right mt-2"><b>Hari Ini,
                            {{ date('d M Y') }}</b></h5>
                </div>
                <!-- /.card-header -->

                <!-- ./card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan Nota Kecil</span>

                                <h5 class="description-header">
                                    Rp.{{ number_format($daily['hari']['pemasukan nota kecil']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan nota besar</span>
                                <h5 class="description-header">Rp.
                                    {{ number_format($daily['hari']['pemasukan nota besar']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-6">
                            <div class="description-block border-right">
                                <span class="description-text">Pemasukan preorder</span>
                                <h5 class="description-header">Rp.
                                    {{ number_format($daily['hari']['pemasukan preorder']) }}
                                </h5>

                            </div>
                            <!-- /.description-block -->
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- Main row -->
    <div class="row">
        <!-- Left col -->


        <div class="col-md-4">
            <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3 bg-warning">
                <span class="info-box-icon"><i class="fas fa-archive text-dark"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Gudang</span>
                    <span class="info-box-number">{{number_format($produk) }} Produk</span>
                </div>

                <a href="{{url('/produk')}}"> <span class="info-box-icon"><i class="fa fa-list bg-dark text-warning p-2 rounded-circle"></i></span></a>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

            <!-- /.info-box -->

            <!-- /.info-box -->



            <!-- PRODUCT LIST -->

        </div>
        <div class="col">
            <div class="info-box mb-3 bg-info">
                <span class="info-box-icon"><i class="fa fa-inbox"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Produk Terjual</span>
                    <span class="info-box-number">{{$pt}} Item</span>
                </div>

                <a href="{{url('/transaksi')}}"><span class="info-box-icon"><i class="fa fa-list bg-light text-info p-2 rounded-circle"></i></span></a>
                <!-- /.info-box-content -->
            </div>

        </div>
        <div class="col">
            <div class="info-box mb-3 bg-light">
                <span class="info-box-icon"><i class="far fa-user text-dark"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Pengguna</span>
                    <span class="info-box-number">{{$user}} Pengguna</span>
                </div>

                <a href="{{route('ma')}}"><span class="info-box-icon"><i class="fa fa-list bg-primary p-2 rounded-circle"></i></span></a>
                <!-- /.info-box-content -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!--/. container-fluid -->
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
</section>
@stop
