@php $whoactive='notabesar';
$master='kasir' @endphp
@extends('layouts.layout2')

@section('pagetitle', 'Nota Besar')
@section('icon', 'fa fa-sticky-note mr-2 ml-3')
@section('title', 'Nota Besar')

@section('js')
<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/transaksi.js') }}"></script>
<script src="{{ asset('js/notabesar.js') }}"></script>
@isset($id)
<script>
    $(document).ready(function() {
        $(".readonly").attr("readonly","readonly");
        $("#notabesar").hide();
        $(".kunci").hide();
        $("#suratjalan").hide();
        $("#buttonreset").attr('href','/transaksipreorder');
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr('content')
            , }
            , data: {
                id_transaksi: "{{$id}}"
            }
            , url: "/getnb"
            , type: "post"
            , dataType: "json"
            , success: function(data) {
                $("#baseinputnb input, label").show();
                if(data["nb"][0]["termin"] == 2){
                    $("#suratjalan").show();
                }

                if(data['nb'][0]['termin'] == 2){
                    $(".kunci").show();
                    $("#kunci").val(data['nb'][0]['kunci']);
                    $(".jt").show();
                }else{
                    $(".kunci").hide();
                 
                }
                console.log(data);
                $("#tt").text(data["nb"][0]["termin"] == 3 ? "PELUNASAN" : "Termin: " + data["nb"][0]["termin"]);
                $("#baseinputnb .col").show();
               
                
                $("#ttd").val(data['nb'][0]['ttd']);
                $("#up").val(data['nb'][0]['up']);
                $("#us").val(data['nb'][0]['us']);
                $("#brp").val(data['nb'][0]['brp']);
                $("#gm").val(data['nb'][0]['gm']);
                $("#total").val(parseInt(data['nb'][0]['total']).toLocaleString());
                $("#nn").text("No Nota: " + data["nb"][0]["no_nota"]);
                $("#tgl").val(data["nb"][0]["created_at"]);
                $("#jt").val(data["nb"][0]["jatuh_tempo"]);
                $("#termin").val(data['nb'][0]['termin']);

                let row = data["opsi"].map(function(e, i) {
                    return `
                    <div class="form-group">
                        <label>${e['judul']}</label>
                        <input type="text" readonly class="form-control isi${i+1} readonly" id="exampleInputPassword1" value="${e['ket']}">
                    </div>
                    `;

                });


                $(".opsigrup").html(row);

                $("#buttonsubmit").text("Bayar");
                $("#preorderform").attr("action", "/bayarpreorder");
                $("#id_trans").val(data["nb"][0]["id_transaksi"]);
                $(".td").show();
                $(".td").children("input").val(parseInt(data["td"]).toLocaleString());
                $("#addopsi").hide();
               // $("#suratjalan").attr("disabled","disabled");
                if (data["nb"][0]["status"] == "dibayar") {
                    $("#us").attr("disabled", "disabled");
                    $("#us").val(parseInt($("#us").val()).toLocaleString());
                    $("#brp").attr("disabled", "disabled");
                    $("#buttonsubmit").attr("disabled", "disabled");
                    $("#buttonsubmit").text("Sudah Lunas");
                    $("#buttonsubmit").removeClass("btn-primary");
                    $("#buttonsubmit").addClass("btn-success");
                    $("#printbutton").removeAttr("disabled");
                    $("#suratjalan").removeAttr("disabled");
                    $("#kunci").attr("readonly",'readonly');

                } else {
                    $("#buttonsubmit").removeAttr("disabled");
                    $("#buttonsubmit").removeClass("btn-success");
                    $("#buttonsubmit").addClass("btn-primary");
                    $("#buttonsubmit").text("Bayar");
                    $("#printbutton").attr("disabled", "disabled");
                }
                $(".readonly").attr('readonly', 'readonly');
            }
            , error: function(err) {
                Swal.fire("error", "", "info");
            }
        });


    });

</script>
@endisset

@stop

@section('css')
<link rel="stylesheet" href="{{asset('css/notabesar.css')}}">
@stop

@section('content')
<div class="container-fluid ">
    <div class="col">
        <div class="form-group">
            <input type="text" id="searcher-nota" class="form-control" placeholder="Cari Nomer Nota">
            <ul id="myUL">
            </ul>
        </div>
    </div>

    <div class="col">

        <input type="hidden" id="id_trans" val="0">
        
        <input type="hidden" id="termin" val="0">
        <form id="preorderform" action="/tambahpreorder">
        <div class="card">

            <div class="card-header">
                
                    <h4 id="tt" class="m">Tanda Terima</h4><span id="nn" style="color:#747474;" class="mr-2"> No Nota : ?</span><i style="color:#747474;" class="fa fa-copy mr-3"></i>
            </div>
            <div class="card-body">
                <label for="notabesar">Pilih Nota Besar</label>
                <select name="" class="custom-select mb-3" id="notabesar">
                    <option value="pintugarasi">Pintu Garasi</option>
                    <option value="pintugadandp">Pintu GA & DP </option>
                    <option value="autog">Auto Gate & Auto Garage</option>
                    <option value="upvc">UPVC</option>
                    <option value="omge">OMGE</option>
                </select>
                <div class="form-group">
                    <label for="exampleInputEmail1">Tanggal </label>
                    <input type="date" class="form-control form-control-" id="tgl" value="{{date('Y-m-d')}}">
                </div>
                <br>
                <div class="row" id="baseinputnb">
                    <div class="col">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Telah diterima dari</label>
                            <input type="text" class="form-control readonly" id="ttd" aria-describedby="emailHelp" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Untuk Proyek</label>
                            <input placeholder="" type="text" class="form-control readonly" id="up" required>
                        </div>
                        <div class="form-group td">
                            <label for="exampleInputPassword1">Telah Dibayar</label>
                            <input type="text" class="form-control readonly" id="td">
                            <input type="hidden" class="form-control readonly" id="td2">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Uang sejumlah</label>
                            <input type="text" class="form-control uang" required id="us" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Berupa</label>
                            <input type="text" class="form-control " required id="brp" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Guna Membayar</label>
                            <input type="text" class="form-control readonly" id="gm" required>
                        </div>
                        <div class="form-group jt">
                            <label for="exampleInputPassword1">Jatuh Tempo</label>
                            <input type="date" class="form-control readonly" id="jt" required>
                        </div>
                        <div class="form-group">
                            <label class="kunci" for="exampleInputPassword1">Kunci</label>
                            <input type="text" class="form-control kunci" id="kunci">
                        </div>



                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Total</label>
                            <input type="hidden" class="form-control readonly" id="total2">
                            <input type="text" class="form-control readonly uang" id="total" required>
                        </div>
                        <div class="form-group opsigrup">

                            <input placeholder="Judul" type="text" class="form-control form-control-sm mb-3 title1 readonly" id="exampleInputPassword1">
                            <input placeholder="Keterangan" type="text" class="form-control isi1 readonly" id="exampleInputPassword1">
                        </div>
                    </div>
                    <div class="col">


                    </div>
                </div>
            </div>
            <div class="row ml-3 mb-3">
                <button type="submit" class="btn btn-primary" id="buttonsubmit">Kirim</button>
                <button type="button" class="btn btn-primary ml-2" href="/notabesar" id="resetbutton"><i class="fa fa-back"></i>Kembali</button>
               
                <button type="button" class="btn btn-primary ml-2" id="printbutton"><i class="fa fa-print mr-2"></i>Print</button>
                <button type="button" id="suratjalan" data-toggle="modalgi" data-target="#exampleModal" class="btn btn-primary float-right ml-2">Surat Jalan</button>
            </div>
        </div>
        </form>
    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Keterangan : </label>
          <textarea class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label>Keterangan lainnya : </label>
          <textarea class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>



<script>
    $(document).ready(function() {




        $(".uang").keyup(function() {
            $(this).val(formatRupiah($(this).val(), ""))
        });


        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString()
                , split = number_string.split(',')
                , sisa = split[0].length % 3
                , rupiah = split[0].substr(0, sisa)
                , ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }
    });

</script>
@stop