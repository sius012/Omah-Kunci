

function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}


$(document).ready(function () {
    
    var hasfinish = false;
    if($("#jenis-transaksi").val() == "normal"){
        $(".normalt").show();
    }else{
        $(".normalt").hide();
    }

    $("#jenis-transaksi").change(function(){
        if($(this).val()=='normal'){
           
            window.location = "/kasir?jenis=umum";
        }else{
            
            window.location = "/kasir?jenis=preorder";
        }
    });
    $(".alerts").hide();
    $(document).on('keypress', function (event) {
        let keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13' && $("#searcher").val() != "" && !hasfinish) {

            tambahItem(
                $("#searcher").val(),
                $("#hrg").val(),
                $("#qty").val(),
                0,
                $("#jenisproduk").val()
            );


            $("#searcher").val("");
            $("#myUL").html("");
        }

        $.ajax({
            headers: function (e) {

            },
        });

    });

    $("#tambahproduk").click(function (event) {

        if(!hasfinish){
            tambahItem(
                $("#searcher").val(),
                $("#hrg").val(),
                $("#qty").val(),
                0,
                $("#jenisproduk").val()
            );
        }
     

    });

    $("#next-button").attr("disabled", "disabled");
    $("#suratjalan").attr("disabled", "disabled");
    $("#suratjalan").attr("disabled", "disabled");
    $(".antartd").hide();
    $("#antarkah").change(function (e) {

        if ($(this).val() !== "antar") {
            $("#suratjalan").attr("disabled", "disabled");
            $(".antartd").hide();
        } else {
            $(".antartd").show();
            $("#suratjalan").removeAttr("disabled");
        }
    }
    );
    //loader
    function loader() {
        $("#tabling").hide();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                jenis: $("#jenis-transaksi").val()
            },

            type: "POST",
            dataType: "JSON",
            url: "/loader",
            success: function (data) {
                if($("#jenis-transaksi").val()=="normal"){

                var no = 1;
                var row = "";
                let subtotal = 0;

                var row = data['datadetail'].map(function (dato, i) {
                    subtotal += doDisc(dato['jumlah'], dato['harga'], dato['potongan'], dato['prefix']);
                    return `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${dato['kode_produk']}</td>
                            <td>${dato['nama_kodetype']+" "+dato['nama_merek']+" "+dato['nama_produk']}</td>
                            <td>${dato['jumlah']}</td>
                            <td>Rp. ${parseInt(dato['harga']).toLocaleString()}</td>
                            <td>Rp. ${parseInt(dato['potongan']).toLocaleString()}</td>
                            <td> Rp. ${doDisc(dato['jumlah'], dato['harga'], dato['potongan'], dato['prefix']).toLocaleString()}</td>
                            <td><buttton class="btn btn-danger beforesend buang" id_detail="${dato['id']}"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    `

                });

                $("#totality").val(subtotal.toLocaleString());


                $('#subtotal').val(subtotal.toLocaleString());

                $('#tabling').html(row);
                $("#tabling").show("slow");
                $('#subtotal').val(subtotal.toLocaleString());
                subtotal1 = subtotal;
                 }else{
                     
                 var row = data['datadetail'].map(function (dato, i) {
                    return `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${dato['kode_produk']}</td>
                        <td>${dato['nama_kodetype']+" "+dato['nama_merek']+" "+dato['nama_produk']}</td>
                        <td>${dato['jumlah']}</td>
                        <td><button  class="btn btn-danger beforesend buang" id_detail="${dato['id']}"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `;});
                $('#tabling').html(row);
                $("#tabling").show("slow");
                 }

            },
            error: function (err) {
                // Swal.fire("terjadi kesalahan","","info");
                alert(err.responseText);
            }
        });
    }








    var id_trans = "{{session()->has('transaksi') ? Session::get('transaksi')['id_transaksi']:0}}";
    var subtotal1 = 0;
    var subtotalafterdiskon = 0;

    var metode = "cash";

    if (metode == "cash") {
        $("#kredit input").attr("disabled", "disabled");
    }

    // $("#kredit").click(function(){
    //     $("#kredit div input").removeAttr("disabled");
    //     $("#kredit div select").removeAttr("disabled");
    //     $("#tunai div input").attr("disabled","disabled");
    //     $("#tunai div select").attr("disabled","disabled");
    //     $(this).addClass("active");
    //      $("#tunai").removeClass("active");
    //      $("#tunai").removeClass("active");
    //      $("#kredit-input").addClass("usethis");
    //      $("#kreditvia-input").addClass("usethisvia");
    //      $("#cash-input").removeClass("usethis");
    //      $("#cashvia-input").removeClass("usethisvia");
    //      metode = "kredit";
    // });

    //  $("#tunai").click(function(){
    //     $("#tunai input").removeAttr("disabled");
    //     $("#tunai select").removeAttr("disabled");
    //     $("#kredit input").attr("disabled","disabled");
    //     $("#kredit select").attr("disabled","disabled");
    //     $(this).addClass("active");
    //     $("#kredit").removeClass("active");
    //     $("#cash-input").addClass("usethis");
    //     $("#cashvia-input").addClass("usethisvia");
    //     $("#kredit-input").removeClass("usethis");
    //     $("#kreditvia-input").removeClass("usethisvia");
    //     metode = "cash";    
    // });





    if (id_trans == 0) {
        $("#selesai").attr('disabled', 'disabled');
    }
    $("#totality").html(subtotal1);


    $(".kredit input").attr("disabled", "disabled");

    $(".drop").hide();
    $("#searcher").keyup(function () {
        $("#myUL").show();

        kw = $(this).val();


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                data: kw
            },

            type: "POST",
            dataType: "JSON",
            url: "/cari",
            success: function (data) {
                if (data['currentproduk'] != undefined) {
                    $("#hrg").val($(data['currentproduk']['harga']));
                    $("#hrg-nominal").html(":  RP. " + parseInt(data['currentproduk']['harga']).toLocaleString());
                }



                $(".drop").show();
                if (data['data'].length > 0) {
                    var li = "";
                    var li2 = "";
                    for (var i = 0; i < data['data'].length; i++) {
                        li += `<li>

                                   <a jenis="produk" kode="${data['data'][i]['kode_produk']}" harga="${data['data'][i]['harga']}" jumlah="1" potongan="0" class="sear">${data['data'][i]["kode_produk"]+ " "+data['data'][i]['nama_kodetype'] +" "+ data['data'][i]['nama_merek'] + " " + data['data'][i]["nama_produk"] + " "}</a>
                                </div>
                            
                            </li>`;
                    }

                    for (var i = 0; i < data['data2'].length; i++) {
                        li += `<li>

                                   <a jenis="paket" kode="${data['data2'][i]['kode_paket']}" harga="${data['data'][i]['harga']}" jumlah="1" potongan="0" class="sear">${data['data2'][i]["kode_paket"] + " " + data['data2'][i]["nama_paket"]}</a>
                                </div>
                            
                            </li>`;
                    }

                    $("#myUL").html(li+li2);
                } else {
                    $(".drop").hide();
                }

                console.log(data.length);

            },
            error: function (err, response,) {
                Swal.fire("terjadi kesalahan", "", "info");
                alert(err.responseText);
            }
        });
    });

    $(document).click(function () {
        $("#myUL").hide();
    });

    $("#myUL").click(function (e) {
        e.stopPropagation();
    });





    function tambahItem(id, harga, jumlah, potongan,jenis) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                data: {
                    kode_produk: id,
                    harga: harga,
                    jumlah: jumlah,
                    potongan: potongan,
                    jenis_transaksi: $("#jenis-transaksi").val(),
                    jenis: jenis
                }
            },
            type: "POST",
            dataType: "JSON",
            url: $("#jenis-transaksi").val() == 'preorder' ? "/tambahpre" : "/tambahItem",
            success: function (data, response) {
               
                if ($("#jenis-transaksi").val() == 'normal') {
                   
                    console.log("data is" + data['datadetail']);
                    if (data['datadetail'] == 'barang habis') {
                        $(".alerts p").text("Stok Tersedia : " + data['as'] + " Item");
                        $(".alerts").show("slow");
                    } else {
                        $(".alerts").hide("slow");
                        console.log(data['datadetail']);
                        var subtotal = 0;
                        var no = 1;

                        let row = data['datadetail'].map(function (dato, i) {


                            subtotal += doDisc(dato['jumlah'], dato['harga_produk'], dato['potongan'], dato['prefix']);

                            return `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${dato['kode_produk']}</td>
                                <td>${dato['nama_kodetype']+ " " +dato['nama_merek']+" "+dato['nama_produk']}</td>
                               
                                <td>${dato['jumlah']}</td>
                                <td>Rp. ${parseInt(dato['harga_produk']).toLocaleString()}</td>
                                <td>${renderDisc(dato['potongan'], dato['prefix'])}</td>
                                <td> Rp.${doDisc(dato['jumlah'], dato['harga_produk'], dato['potongan'], dato['prefix']).toLocaleString()}</td>
                                <td><button   class="btn btn-danger beforesend buang" id_detail="${dato['id']}"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        `;


                        });

                        if ($("#antarkah").val() !== "antar") {
                            $("#suratjalan").attr("disabled", "disabled");
                            $(".antartd").hide();
                        } else {
                            $(".antartd").show();
                            $(".suratjalan").removeAttr("disabled");
                        }

                        $('#tabling').html(row);
                        $("#tabling").show("slow");
                        $("#totality").val(subtotal.toLocaleString());


                        $('#subtotal').val(subtotal.toLocaleString());
                        subtotal1 = subtotal;
                        id_trans = data['datadetail'][0]['kode_trans'];
                        $("#kodetrans").val(data['datadetail'][0]['kode_trans']);
                    }
                } else {
                 
                    console.log("data is" + data['datadetail']);
                    if (data['datadetail'] == 'barang habis') {
                        $(".alerts p").text("Stok Tersedia : " + data['as'] + " Item");
                        $(".alerts").show("slow");
                    } else {

                        $("#id_pre").val(data['datadetail'][0]['id_preorder']);
                        $(".normalt").hide();
                        $(".alerts").hide("slow");
                        console.log(data['datadetail']);
                        var subtotal = 0;
                        var no = 1;

                        let row = data['datadetail'].map(function (dato, i) {


                            subtotal += doDisc(dato['jumlah'], dato['harga_produk'], dato['potongan'], dato['prefix']);

                            return `
                            <tr>
                                <td>${i + 1}</td>
                                <td>${dato['kode_produk']}</td>
                                <td>${dato['nama_kodetype']+" "+dato['nama_merek']+" "+dato['nama_produk']}</td>
                                <td>${dato['jumlah']}</td>
                                <td><button  class="btn btn-danger beforensend buang" id_detail="${dato['id']}"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        `;


                        });

                        if ($("#antarkah").val() !== "antar") {
                            $("#suratjalan").attr("disabled", "disabled");
                            $(".antartd").hide();
                        } else {
                            $(".antartd").show();
                            $(".suratjalan").removeAttr("disabled");
                        }

                        $('#tabling').html(row);
                        $("#tabling").show("slow");
                        $("#totality").val(subtotal.toLocaleString());


                        $('#subtotal').val(subtotal.toLocaleString());
                        subtotal1 = subtotal;
                        id_trans = data['datadetail'][0]['kode_trans'];
                        $("#kodetrans").val(data['datadetail'][0]['kode_trans']);
                    }
                }




            },
            error: function (err, response, errorThrown, jqXHR) {
            }
        });

        $("#selesai").removeAttr('disabled');
    }

    $("#myUL").on("click", ".sear", function (event) {  
        $("#searcher").val($(event.target).attr("kode"));
        $("#myUL").hide();
        $("#hrg").val($(event.taret).attr("harga"));
        $("#hrg-nominal").html(":  RP. " + parseInt($(event.target).attr("harga")).toLocaleString());
        $("#jenisproduk").val($(event.target).attr("jenis") != undefined ? $(event.target).attr("jenis") : "produk");

    });


    $("#formsubmitter").submit(function (e) {
        e.preventDefault();
        tambahItem(
            $("#searcher").val(),
            $("#hrg").val(),
            $("#qty").val(),
            0,
            $("#jenisproduk").val()
        );
    });



    $(".drop").on("keyup", ".jml", function () {
        $(event.target).closest(".bungkuser").children(".sear").attr("jumlah", $(event.target).val());
    });

    $(".drop").on("keyup", ".potongan", function () {
        $(event.target).closest(".bungkuser").children(".sear").attr("potongan", $(event.target).val());
    });

   

    $("#selesai").click(function () {
       

        if(!hasfinish){
        
        if (parseInt($(".usethis").val()) < subtotalafterdiskon && $('input[name=payment]:checked').val() == "cash") {
            alert("uang kurang");
        } else {
           
            id_trans = $("#kodetrans").val();
            if ($("#nama").val() == null || $("#nama").val() == "" || $(".usethis").val() == "" || $(".usethis").val() == null  || $(".usethisvia").val() == " " || $('#telp').val() == "" || $("#alamat").val() == "") {
                Swal.fire("Pastikan Semua Kolom terisi(kecuali diskon)", "", "info");
            } else {
                $("#reset-button").hide();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: {
                            nama_pelanggan: $("#nama").val(),
                            diskon: $("#diskon").val().replace(/[._]/g, ''),
                            bayar: $(".usethis").val().replace(/[._]/g, ''),
                            metode: "cash",
                            via: $(".usethisvia").val(),
                            telp: $("#telp").val(),
                            alamat: $("#alamat").val(),

                            antarkah: $("#antarkah").val(),

                        }
                    },
                    type: "POST",
                    url: $("#jenis-transaksi").val() == "normal" ? "/selesaitransaksi" : "/selesaipreorder",
                    success: function (data) {
                        hasfinish = true;
                        $("#selesai").removeClass("selesaiindi");
                        $("#selesai").addClass("printindi");
                        $("#selesai").html("<i class='fa fa-print mr-3'></i>Cetak");
                        Swal.fire(
                            'Transaksi Berhasil!',
                            '',
                            'success'
                        );
                        if($("#jenis-transaksi").val() == "normal"){
                            print();
                        }else{
                
                            printpreorder($("#id_pre").val());
                        }
                        $("#next-button").removeAttr("disabled");
                        $(".beforesend").attr("disabled","disabled");
                    },
                    error: function (err, response, errorThrown, jqXHR) {
                        Swal.fire(
                            'Data barang tidak tersedia di katalog!',
                            '',
                            'info'
                        );
                        alert(err.responseText);
                    }
                });
                //window.location = "{{url('/selesai')}}";
            }

        }
    }else{
        if($("#jenis-transaksi").val() == "normal"){
            print();
        }else{

            printpreorder($("#id_pre").val());
        }
    }



    });

    //input diskon
    $("#diskon").keyup(function () {
        subtotalafterdiskon = subtotal1 - $(this).val().replace(/[._]/g, '');
        $("#totality").val(subtotalafterdiskon.toLocaleString());
    });

    $("#reset-button").click(function () {
        if(!hasfinish){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/resettransaction",
            type: "GET",
            success: function () {
                alert("transaksi diulang");
                window.location = "/kasir";
            },
            error: function (err) {
               window.location="/kasir";
            }
        });
        }
    });

   
    function hapusdetail(id_detail) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id_detail: id_detail,
                jenis: $("#jenis-transaksi").val()
            },

            type: "POST",
            url: "/removedetail",
            success: function (data) {
                loader();
            },
            error: function (err) {
                alert(err.responseText);
            }

        });
    }

    //NEXt TRANSACTION
    $("#next-button").click(function (e) {
        window.location = "/selesaitrans";
    });












    $(document).on("click", "tr td .buang", function (e) {
        // $.ajax({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //      },
        //      data: {
        //          $id_detail : $(e.target).attr("id_detail")
        //      },
        //      type:"POST",
        //      url: "/removedetail",
        //      success: function(){
        //         alert($(e.target).children("a").attr("id_detail"));
        //      },
        //      error: function(){
        //         alert($(e.target).children("a").attr("id_detail"));
        //      }

        // });

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Hapus`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire('Saved!', '', 'success')
            } else if (result.isDenied) {
                Swal.fire('Item dibatalkan', '', 'info');
                hapusdetail($(e.target).attr("id_detail") != undefined ? $(e.target).attr("id_detail") : $(e.target).closest(".buang").attr("id_detail"));
            }
        })


    });

    function printpreorder(id_trans){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                id: id_trans,
            },
            type: 'post',
            dataType: "json",
            url: "/cetakpreorder",
            success: function(data){
                printJS({printable: data['filename'], type: 'pdf', base64: true});
            },
            error: function(err){
                alert(err.responseText);
            }
        });
    }
    
    function print() {

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr('content')
            },
            url: "/cetaknotakecil",
            type: "post",
            success: function (response) {
                printJS({ printable: response['filename'], type: 'pdf', base64: true });
            },
            error: function (err) {
                Swal.fire("terjadi kesalahan", "", "info");
                alert(err.responseText);
            }
        });
    };

    $("#suratjalan").click(function () {
        $(".antarcheck:checked").each(function () {
            alert($(this).val());
        })
    });

    $("#list-return").hide();


});