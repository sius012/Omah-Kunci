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

            font-size: 12pt;
            line-height: ;
        }
        @media print {
            @page {
                size: landscape
            }
        }

        body {
            max-height: 10px;
            
            
        }

        td{
            height: 0px;
            padding: 1px;
        }

        td h4{
          
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
        }

        .container-wrapper table .address .brand-address {
            margin-top: 0;

            font-size: 10pt;
        }

        .container-wrapper table .date-times {
            font-size: 10pt;

            margin-left: 230px;
            width: 200px;
        }

        .container-wrapper .big-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .container-wrapper .big-title .title {
            margin: 0;
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

            font-size: 12pt;
            margin: 0px;
            padding: 0px !important;
        }



    </style>
</head>

<body>
    <div class="container-wrapper">
        <table style="margin-top: 20px; width: 1100px">
            <tr>
                <td>
                    <div class="address" style="width:150px">
                        <img style="height:25px;" src="{{ public_path('assets/logo.svg') }}" alt="">
                        <p class="brand-address">Jl. Agus Salim D no.10 <br> Telp/Fax 085712423453 / (024) 3554929  <br>
                            Semarang </p>
                    </div>
                </td>
                <td style="width:350px"></td>
                <td align="right" style="width:50px" valign="top">
                    <h4 class="date-times">Semarang,
                        {{ date("d-M-Y", strtotime($data->updated_at))}}</h4>
                </td>
            </tr>
            <tr>
                <td align="center" id="bigtitle" colspan="3">
                    <div class="big-title">
                        <h2 class="title">
                            {{ $data->termin != 3 ? "TANDA TERIMA" : "NOTA" }}
                        </h2>
                        <h5 class="no-nota">NO.{{ $data->no_nota }}</h5>
                    </div>
                </td>

            </tr>
            <tr>
                <td style="width:100px" valign="top">
                    <h4>Telah terima dari</h4>
                </td>
                <td> {{ $data->ttd }}</td>
                <td></td>
            </tr>
            <tr>
                <td valign="top">
                    <h4>Untuk Proyek</h4>
                </td>
                <td> {{ $data->up }}</td>
                <td></td>
            </tr>
            @if($td != 0)
                <tr>

                    <td valign="top">
                        <h4>Pembayaran Sebelumnya</h4>
                    </td>
                    <td>Rp. {{ number_format($td,0,',','.') }}</td>
                    <td></td>

                </tr>
            @endif
            <tr>
                <td style="" valign="top">
                    <h4>Uang Sejumlah</h4>
                </td>
                <td style="padding-bottom: 10px;"> Rp. {{ number_format($data->us,0,',','.') }}</td>
                <td></td>
            </tr>
            <tr>
                <td valign="top">
                    <h4>Berupa</h4>
                </td>
                <td> {{ $data->brp }}</td>
                <td></td>
            </tr>
            <tr>
                <td valign="top">
                    <h4>Guna Membayar</h4>
                </td>
                <td> {{ $data->gm }}</td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-bottom: 5px;" valign="top">
                    <h4>Total</h4>
                </td>
                <td style="padding-bottom: 5px;"> Rp. {{ number_format($data->total,0,',','.') }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            @foreach($opsi as $opsis)
                <tr>

                    <td valign="top">
                        <h4>{{ $opsis->judul }}</h4>
                    </td>
                    <td colspan="2"> {{ $opsis->ket }}</td>


                </tr>
            @endforeach
            @if($data->termin == 3)
                <tr>

                    <td valign="top">
                        <h4>Kunci</h4>
                    </td>
                    <td colspan="2"> {{ $data->kunci }}</td>
                   

                </tr>

            @endif

            @if($data->termin != 3)
            <tr align="center">
                <td colspan="3" style="padding-top:25px; padding-bottom:30px">
                    <h4 class="ttd-header">Mengetahui,</h4>

                </td>
            </tr>
            <tr>
                <td align="center">
                    <div class="wrappers">
                        <h4 class="customer">Customer,</h4>

                    </div>
                </td>
                <td></td>
                <td align="center" style="padding-left:150px">
                    <div class="wrappers">
                        <h4 class="sales">Sales,</h4>

                    </div>
                </td>
            </tr>
            @endif
        </table>
    </div>
</body>

</html>
