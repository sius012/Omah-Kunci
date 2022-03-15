

$(document).ready(function(){
    $("#suratjalan").hide();
    $(".jt").hide();
    $(".kunci").hide();
    $("#printbutton").attr("disabled", "disabled");
    $(".td").hide();
    var jenisnota = "pintugarasi";
    $("#gm").val('Pintu Garasi');
    var pg = `
        <label for='ukuranpg readonly'>Ukuran : </label>
        <input required class="form-control readonly" id="ukuranpg">
        <label for='daunpintupg'>Daun Pintu : </label>
        <input required class="form-control readonly" id="daunpintupg">
        <label for='arahtikungpg'>Arah Tikung : </label>
        <input required class="form-control readonly" id="arahtikungpg">
        <label for='pilarpg'>Pilar : </label>
        <input required class="form-control readonly" id="pilarpg">
        <label for='warnatipepg'>Warna/Tipe : </label>
        <input required class="form-control readonly" id="warnatipepg">
        <label for='waktupg'>Waktu : </label>
        <textarea type="text-area" required class="form-control readonly" id="waktupg" value="">2 Bulan dari Penerimaan DP 50% dan persetujuan warna, tipe, ukuran lebar dan tinggi lapangan</textarea>
    `;

    var pgadp = `
    <label for='ukurankusenpgadp'>Ukuran : </label>
    <input required class="form-control readonly" id="ukurankusenpgadp">
    <label for='warnatipepgadp'>Warna/Tipe : </label>
    <input required class="form-control readonly" id="warnatipepgadp">
    <label for='waktupgadp'>Waktu : </label>
    <textarea type="text-area" required class="form-control readonly" id="waktupgadp" value="">2 Bulan dari Penerimaan DP 50% dan persetujuan warna, tipe, ukuran lebar dan tinggi lapangan</textarea>
`;

var ag = `
<label for='ukuranag'>Ukuran Diperuntukan : </label>
<input required class="form-control readonly" id="ukuranag">

`;
var upvc = `
<label for='itembarangupvc'>Item Barang : </label>
<input required class="form-control readonly" id="itembarangupvc">
<label for='warnatipeupvc'>Warna/Tipe : </label>
<input required class="form-control readonly" id="warnatipeupvc">
`;

var omge = `
<label for='ukuranomge'>Ukuran(Ekstimasi) : </label>
<input required class="form-control readonly" id="ukuranomge">

`;



$("#notabesar").change(function(){
   jenisnota = $(this).val();
   if($(this).val() == "pintugarasi"){
    $(".opsigrup").html(pg);
    $("#gm").val("Pintu Garasi");
}else if($(this).val() == "pintugadandp"){
    $(".opsigrup").html(pgadp);
    $("#gm").val("Pintu GA & DP");
}else if($(this).val() == 'autog'){
    $(".opsigrup").html(ag);
    $("#gm").val("Auto Gate & Auto Garage");
}else if($(this).val() == 'upvc'){
    $(".opsigrup").html(upvc);
    $("#gm").val("UPVC");
}else{
    $(".opsigrup").html(omge);
    $("#gm").val("OMGE");
}

  
});
$(".opsigrup").html(pg);
$("#trigger").click(function(e){
    alert(currentopsi);
})
 





    $(".td").hide();
    var jmlopsi = 1;
    console.log("{{'lol'}}");

    function callbacking(response){
        jmlopsi = response;
    
    }


    $("#searcher-nota").keyup(function(e){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                kw : $("#searcher-nota").val()
            },
            url: "/searchnotapreorder",
            type: "post",
            dataType: "json",
            success: function(data){
                console.log(data);
                let row = data.map(function(datas){
                    return `
                <li><a href="#"  id_nb = ${datas['id_transaksi']} class='${datas['us'] == null && datas['status'] == "menunggu" ? "disab" :  "cc"}'>${datas['no_nota'] + "  " + "Termin: " + datas['termin']}</a></li>

                    `;
                });

                $("#myUL").html(row);
            },
            error: function(err){
                Swal.fire(err.responseText)
            }

        });
    });

    $("ul").on("click", "li .cc", function(e){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content'),
            },
            data: {
                id_transaksi : $(e.target).attr("id_nb")
            },
            url: "/getnb",
            type: "post",
            dataType: "json",
            success: function(data){
                $(".readonly").attr("readonly","readonly");
                
                if(data["nb"][0]["termin"] == 2){
                    $("#suratjalan").show();
                    $(".jt").show();
                }else{
                $("#suratjalan").hide();
                }
                if(data["peringatan"] != undefined){
                    alert("error");
                }

                if(data['nb'][0]['termin'] > 1){
                    $(".kunci").show();
                    $("#kunci").val(data['nb'][0]['kunci']);
                 }else{
                    $(".kunci").hide();
                }
                console.log(data);
                $("#tt").text(data["nb"][0]["termin"] == 3 ? "PELUNASAN" : "Termin: "+data["nb"][0]["termin"]);
             //   $("#baseinputnb .col").show();
               // $("#baseinputnb input, label").show();
                $("#ttd").  val(data['nb'][0]['ttd']);
                $("#up").   val(data['nb'][0]['up']);
                $("#us").   val(data['nb'][0]['us']);
                $("#brp").  val(data['nb'][0]['brp']);
                $("#gm").   val(data['nb'][0]['gm']);
                $("#total").val(parseInt(data['nb'][0]['total']).toLocaleString());
                $("#total2").val(parseInt(data['nb'][0]['total']));
                $("#nn").text("No Nota: "+data["nb"][0]["no_nota"]);
                $("#tgl").val(data["nb"][0]["created_at"]);
                $("#jt").val(data["nb"][0]["jatuh_tempo"]);
                $("#td2").val(parseInt(data['nb'][0]['total']));
                $("#termin").val(data['nb'][0]['termin']);
    
    
    
                let row = data["opsi"].map(function(e,i){
                    return `
                    <div class="form-group">
                        <label>${e['judul']}</label>
                        <input type="text" class="form-control isi${i+1} readonly" readonly id="exampleInputPassword1" value="${e['ket']}">
                    </div>
                    `;
                    
                });

                callbacking(data['opsi'].length);
                $(".opsigrup").html(row);

                $("#buttonsubmit").text("Bayar");
                $("#preorderform").attr("action", "/bayarpreorder");
                $("#id_trans").val(data["nb"][0]["id_transaksi"]);
                $(".td").show();
                $(".td").children("input").val(parseInt(data["td"]).toLocaleString());
                $("#addopsi").hide();
                if(data["nb"][0]["status"] == "dibayar"){
                    $("#us").attr("disabled", "disabled");
                    $("#brp").attr("disabled", "disabled");
                    $("#buttonsubmit").attr("disabled", "disabled");
                    $("#buttonsubmit").text("Sudah Lunas");
                    $("#buttonsubmit").removeClass("btn-primary");
                    $("#buttonsubmit").addClass("btn-success");
                    $("#printbutton").removeAttr("disabled");
                    $("#suratjalan").removeAttr("disabled");
                    $("#kunci").attr("readonly",'readonly');
                
                
                }else{
                    $("#us").removeAttr("disabled");
                    $("#brp").removeAttr("disabled");
                    $("#buttonsubmit").removeAttr("disabled");
                    $("#buttonsubmit").removeClass("btn-success");
                    $("#buttonsubmit").addClass("btn-primary");
                    $("#buttonsubmit").text("Bayar");
                    $("#printbutton").attr("disabled", "disabled");
                   
                }
            },
            error: function(err){
                alert(err.responseText);
                Swal.fire("error", "", "info");
            }
        });
    });




   


    
   
    $("#addopsi").click(function(){

        jmlopsi += 1
        if(jmlopsi > 4){
            Swal.fire({
                title : "lol"
            });
        }else{
        $(".opsigrup").append(`
        <div class="form-group">
            <input type="text" class="form-control form-control-sm title${jmlopsi}" id="exampleInputPassword1" >
            <input type="text" class="form-control isi${jmlopsi}" id="exampleInputPassword1" >
        </div>
        `);
        }
    });

   
    //ketika tombol submit/bayar tertekan
    $("#preorderform").submit(function(e){
      
        var judulpg = ["Ukuran", "Daun Pintu", "Arah Tikung", "Pilar", "Warna/Tipe", "Waktu"];
        var ospipg = [$("#ukuranpg").val(), $("#daunpintupg").val(), $("#arahtikungpg").val(), $("#pilarpg").val(), $("#warnatipepg").val(), $("#waktupg").val()];

        var judulpgad = ["Ukuran Kusen", "Warna/Tipe", "Waktu"];
        var ospipgagd = [$("#ukurankusenpgadp").val(), $("#warnatipepgadp").val(), $("#waktupgadp").val()];

        var judulag = ["Ukuran Diperuntukan"];
        var ospiag = [$("#ukuranag").val()];

        var judulupvc = ["Item Barang", "Warna/Tipe"];
        var ospiupvc = [$("#itembarangupvc").val(), $("#warnatipeupvc").val()];

        var judulomge = ["Ukuran(Ekstimasi)"];
        var ospiomge = [$("#ukuranomge").val()];

        var currentjudul = judulpg;
        var currentopsi = ospipg;


        if(jenisnota == "pintugarasi"){
            currentjudul = judulpg;
             currentopsi = ospipg;
        }else if(jenisnota == "pintugadandp"){
         currentjudul = judulpgad;
         currentopsi = ospipgagd;
        }else if(jenisnota == 'autog'){
         currentjudul = judulag;
         currentopsi = ospiag;
        }else if(jenisnota == 'upvc'){
         currentjudul = judulupvc;
         currentopsi = ospiupvc;
        }else{
         currentjudul = judulomge;
         currentopsi = ospiomge;
        }


        let url = $(this).attr("action");
        e.preventDefault();

                                                                                                                                                                           

        var judulopsi = [];
        var ketopsi = [];

        for(var j = 1; j <= jmlopsi; j++){
            judulopsi.push($(".title"+j).val());
            ketopsi.push($(".isi"+j).val());
        }

        var formData = {
            ttd: $("#ttd").val(),
            up: $("#up").val(),
            us: $("#us").val().replace(/[._]/g,''),
            brp: $("#brp").val(),
            gm: $("#gm").val(),
            total: $("#total").val().replace(/[._]/g,'')
            
        }

        console.log(formData);
        if(parseInt($("#termin").val()) != 3 || parseInt($("#td2").val()) + parseInt($("#us").val().replace(/[._]/g,'')) >= parseInt($("#total2").val())){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }, 
            data: {
                formData: formData,
                jenisnota: jenisnota,
                judulopsi: currentjudul,
                ketopsi: currentopsi,
                id_transaksi: $("#id_trans").val(),
                tanggal: $("#tgl").val(),
                kunci: $("#kunci").val(),
                jt: $("#jt").val()
            },
            type: "POST",
            url: url,
            dataType: "json",
            success: function(data){
                Swal.fire({
                    title: url == "/bayarpreorder" ? "Pembayaran dilunasi" : "Transaksi Berhasi Ditambahkan" 
                });
             //   $("#preorderform input").val("");
                $("#preorderform").attr("disabled", "disabled");
               
                $("#buttonsubmit").attr("disabled", "disabled");
                $("#buttonsubmit").text("Sudah dibayar");
                $("#buttonsubmit").removeClass("btn-primary");
                $("#buttonsubmit").addClass("btn-success");
                $("#id_trans").val(data["id_nb"]);
                $("#nn").text("No Nota: "+data["no_nota"]);
                $("#termin").val(data['termin']);
                $("#searcher-nota").val("");
                $("#printbutton").removeAttr('disabled');
                $("#suratjalan").removeAttr('disabled');
                $("#us").attr("disabled", "disabled");
               
            
            },
            error: function(err,response){
                Swal.fire("terjadi kesalahan");
                alert(err.responseText);
            }
        });
    }else{
        Swal.fire("Nominal kurang");
    }
       
    });


    $("#printbutton").click(function(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }, 
            data: {
                id_transaksi : $("#id_trans").val()
            },
            url: "/cetaknotabesar",
            type: "post",
            success: function(response){
                printJS({printable: response['filename'], type: 'pdf', base64: true, style: '@page { size: Letter landscape; }'});
            },error: function(err){
                Swal.fire('terjadi kesalahan','','info');
            }
        });
    });

    $("#resetbutton").click(function(e){
        alert( $(this).attr("href"));
        e.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $("meta[name='csrf-token'").attr('content')
            },
            url: '/resettrans',
            type: 'POST',
            success: function(){
                alert('hai');
                window.location = $("#resetbutton").attr("href");
            },
            error: function(err){
                alert('hai');
            },
        });
    });

    // $("#suratjalan").click(function(){
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //              }, 
    //         data: {
    //             id_transaksi : $("#id_trans").val()
    //         },
    //         url: "/cetaksjnb",
    //         type: "post",
    //         success: function(response){
    //             printJS({printable: response['filename'], type: 'pdf', base64: true, style: '@page { size: Letter landscape; }'});
    //         },error: function(err){
    //             Swal.fire('terjadi kesalahan','','info');
    //             alert(err.responseText);
    //         }
    //     });
    // });

    $("#sjsubmit").submit(function(e){
        e.preventDefault();
    })
});