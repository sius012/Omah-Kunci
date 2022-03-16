<!DOCTYPE html>
<html>
<head>
<style> 
*{
    margin: 0px;
    font-size: 8pt;
}
#main {
    width: 21cm;
    margin-left: 3px;
    
 

}

body{
    margin-top: 20px;
}

#main div {
  width: 5cm;
  height: 2cm;
  display: inline-block;

  
}

.cardi{
    background: white;
    border: 1px solid black;
    display: block;
    padding: 0px;
}
.barcode{
    letter-spacing: 8.2pt;
    font-size: 6pt;

    font-weight: bold;
}

.jdl{
    font-size: 6pt;
    font-weight: bold;
}

img{
    width: 30px;
    left: 30px;
    transform: rotate(-90);
}
</style>
</head>
<body>


<div id="main">
    @foreach($data as $datas)
    <div style="margin-left: 2px; margin-top: 12px;" class="cardi">
    <div style="margin-left: 12px">
    <span style="text-align:left; width:1px;" class="jdl">{{$datas->nama_produk}}</span><br>
    <span style="">{!! DNS1D::getBarcodeHTML($datas->kode_produk, 'C128',1.5,40) !!}</span><br>
    <span class="barcode" >{{$datas->kode_produk}}</span>
    <img style="left: 155px ;top: -15   px;" src="{{public_path('assets/ok.png')}}" alt="">
    </div>
    </div>
    @endforeach
  

</div>



</body>
</html>
