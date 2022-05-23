

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

     <style>
        @font-face {

font-family: tes;
font-style: normal;
src: url("{{storage_path('/fonts/Consolas-Font/CONSOLA.ttf')}}");
}

@font-face {

font-family: tesb;
font-style: normal;
src: url("{{storage_path('/fonts/Consolas-Font/CONSOLAB.ttf')}}");
}
        * {
            margin: 0px;
            font-family: tes !important;
            font-size: 10pt;
            line-height: 70% ;
        }
     

        
        body {
          
            
            
        }
        
        td{
            height: 0px;
            padding: 1px;
        }

        td h4,h5{
            font-family: tesb !important;
            font-weight: normal;
        }

        .container-wrapper {
            margin: 30px;
            margin-top: 0;
        }

        .container-wrapper .header {
            display: inline-flex;
            margin-bottom: 40px;
            margin-left: 80px;
        }

        .container-wrapper .header .brand-title {
            margin-bottom: 0;
            text-transform: uppercase;
            font-family: tesb !important;
            font-weight: normal;
        }

        .container-wrapper table .address .brand-address {
            margin-top: 0;

            font-size: 8pt;
            line-height: 100%;
        }

        .container-wrapper table .date-times {
            font-size: 10pt;

            margin-left: 230px;
            width: 200px;
        }

        .container-wrapper .big-title {
            text-align: center;
          
            font-family: tesb !important;
            font-weight: normal;
        }

        .container-wrapper .big-title .title {
              margin-bottom: 3px;
          
            font-family: tesb !important;
            font-weight: normal;
            font-size: 12pt;
        }

        .container-wrapper .big-title .hr {
            margin: 0;

            width: 200px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .container-wrapper .big-title .no-nota {
            margin: 0;
        }

        .container-wrapper .content,
        .content h4 {
            text-transform: uppercase;
            margin: 0;
            margin-left: 40px;
        }

        .container-wrapper .ttd .ttd-header {
            text-align: center;
        }

        .container-wrapper .ttd .wrappers {
            display: inline-flex;
        }

        .container-wrapper .ttd .wrappers .customer {
            margin-left: 200px;
        }

        .container-wrapper .ttd .wrappers .sales {
            margin-left: 700px;
        }

        .container-wrapper table {
            width: 750px;
        }

        #bigtitle {
            height: 20px;

        }

        h4 {

            font-size: 10pt;
            margin: 0px;
            padding: 0px !important;
        }



    </style>
</head>

<body>
    <div class="container-wrapper">
        <table style="margin-top: 20px; width: 450px">
            <tr>
                <td>
                    <div class="address" style="width:150px">
                        <img style="height:20px;" src="{{ public_path('assets/logo.svg') }}" alt="">
                        <p class="brand-address">Jl. Agus Salim D no.10 <br> Telp/Fax 085712423453 / (024) 3554929  <br>
                            Semarang </p>
                    </div>
                </td>
                <td colspan=2></td>
            
                <td align="right"  valign="top " style="width:175px">
                    <h4 class="">Semarang,
                        {{ date("d-M-Y", strtotime($data[0]->updated_at)) }}</h4>
                </td>
            </tr>
            <tr>
                <td align="center" id="bigtitle" colspan="4">
                    <div class="big-title">
                        <h2 class="title">
                            {{ $data[0]->status === "lunas" ? "TANDA TERIMA" : "SURAT JALAN" }}
                        </h2>
                        <h5 class="no-nota">NO.{{ $data[0]->no_nota }}</h5>
                    </div>
                </td>

            </tr>
            <tr>
                <td valign="top">
                    <h4>Telah terima dari</h4>
                </td>
                <td style="width:200px"> {{ $data[0]->nama_pelanggan }}</td>
                <td stlye="width:100px"></td>
                <td></td>
            </tr>

            <tr>

                <td valign="top">
                    <h4>No Telepon</h4>
                </td>
                <td>{{$data[0]->telepon}}</td>
                <td></td>
                <td></td>

            </tr>
            <tr>

                <td valign="top">
                    <h4>Alamat</h4>
                </td>
                <td>{{$data[0]->alamat }}</td>
                <td></td>
                <td></td>
                </tr>

            <tr>
                <td valign="top">
                 <h4>Uang Sejumlah</h4>
                </td>
                <td>Rp. {{ number_format($data[0]->bayar) }}</td>
                <td></td>
                <td></td>

            </tr>
            <tr>
                <td valign="top">
                    <h4>Berupa</h4>
                </td>
                <td> {{ $data[0]->metode }}</td>
                <td></td>
                <td></td>
            </tr>

            @foreach($data2 as $i => $datas)
                <tr>
                    @if($i == 0)
                    <th valign="top" align="left">Barang yang dibeli</th>
                    @else
                    <td></td>
                    @endif
                    <td>{{$datas->nama_produk}} {{$datas->nama_merek}}  {{$datas->jumlah}} {{$datas->satuan}}</td>
                    <td style="width:175px">- {{$datas->prefix == "rupiah"? "Rp.".number_format($datas->potongan) : $datas->potongan."%"}}</td>
                    <td>Rp. {{number_format(Tools::doDisc($datas->jumlah,$datas->harga_produk,$datas->potongan,$datas->prefix))}}</td>
                </tr>
            @endforeach
            <tr>
                <th style="height:20px"></th>
            </tr>
            <tr>
                <td style="padding-bottom: 5px;" valign="top">
                    <h4>Subtotal</h4>
                </td>
                <td style="padding-bottom: 5px;"> Rp. {{ number_format($data[0]->subtotal) }}</td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-bottom: 5px;" valign="top">
                    <h4>Diskon</h4>
                </td>
                <td style="padding-bottom: 5px;"> Rp. {{ number_format($data[0]->diskon) }}</td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-bottom: 5px;" valign="top">
                    <h4>Total</h4>
                </td>
                <td style="padding-bottom: 5px;"> Rp. {{ number_format($data[0]->subtotal - $data[0]->diskon) }}</td>
                <td></td>
            </tr>
            @if($data[0]->status != "lunas")
            <tr>
                <td style="padding-bottom: 5px;" valign="top">
                    <h4>Kurang Bayar</h4>
                </td>
                <td style="padding-bottom: 5px;"> Rp. {{ number_format($data[0]->subtotal-$data[0]->diskon - $data[0]->bayar) }}</td>
                <td></td>
            </tr>
            @endif

            <tr align="center">
                <td colspan="5" style="padding-top:25px; padding-bottom:30px">
                    <h4 class="ttd-header">Mengetahui,</h4>

                </td>
            </tr>

           
        </table>
        <table width="200" style="margin-top:0px">
                        <tr>
                <td align="center">
                    <div class="wrappers">
                        <h4 class="customer">Customer,</h4>

                    </div>
                </td>
                <td></td>
                <td></td>
                <td align="center" style="">
                   
                        <h4 class="sales">Sales,</h4>

             
                </td>
            </tr>
           <tr>
                <td align="center">
                   <br><br><br><br><br>
                         <h4 class="">{{"(".str_repeat('.', 25).")"}}</h4>

                  
                </td>
                <td></td>
                <td>
                <td align="center" style="">
                        <br><br><br><br><br>
                   
                           <h4 class="">{{"(".str_repeat('.', 25).")"}}</h4>

             
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
