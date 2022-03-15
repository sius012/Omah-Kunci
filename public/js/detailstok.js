$(document).ready(function () {
    $("#toggle-tanggal").hide();
    $("#berdasarkan").change(function () {
        if ($(this).val() == "tanggal") {
            $("#toggle-tanggal").show();
            $("#toggle-hmb").hide();
        } else {
            $("#toggle-tanggal").hide();
            $("#toggle-hmb").show();
        }
    });








    $(document).click(function () {
        $(".myUL").hide();
    });

    $(document).on("click", ".myUL", function (e) {
        e.stopPropagation();
    });

    $(document).on("click", ".sear", function (event) {
        $(event.target).closest(".parent1").children("input").val($(event.target).attr("kode"));
        $(event.target).closest(".form-row").children(".parent1").children(".nama-produk").val($(event.target).attr("nama"));

    });



    $(".myUL").on("click", ".sear", function (event) {
        $("#produk-select").val($(event.target).attr("kode"));
        $(".myUL").hide();
    });


    loaddetail();
    $("#detailstoksubmitter").submit(function (e) {
        
        e.preventDefault();
        var data = {
            'created_at': $("#tanggal").val(),
            'kode_produk': $("#produk-select").val(),
            'jumlah': $("#jumlah").val(),
            'status': $("#status-select").val(),
            'keterangan': $("#keterangan").val(),
            
        };

        alert($("#status-select").val());

        console.log(data);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token'").attr('content')
            },
            data: {
                data: data
            },
            url: "/tambahdetailstok",
            type: "post",
            success: function (data) {
                Swal.fire('Berhasil Ditambahkan', '', 'success');
                loaddetail();
                $("#examplemodal").modal('hide');
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    });


    function loaddetail(kw = null) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr('content')
            },
            url: "/loaddatadetailstok",
            type: "post",
            data: {
                kw: kw
            },
            dataType: "JSON",
            success: function (data) {

                let row = data["data1"].map(function (rows, i) {

                    return `
                        <div class="card bg-light">
                            <div class="card-header">
                                <div class="row">
                                <div class="col-6">
                                <div class=''>Nama Admin: ${rows['name']}</div>
                            </div>
                            <div class="col-6">
                                <div class="  float-right">${rows['created_at']}</div>
                            </div>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <thead class="thead">
                                    <tr>
                                    <th style="width:180px;">Waktu</th>
                                        <th style="width:170px;">Kode Produk</th>
                                        <th style="width:180px;">Nama Produk</th>
                                        <th style="width:70px;">Jumlah</th>
                                        <th style="width:90px;">Status</th>
                                        <th style="width:120px;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                <tr>
                                        <td>${rows['created_at']}</td>
                                        <td>${rows['kode_produk']}</td>
                                        <td>${rows['nama_kodetype']+" "+rows['nama_merek']+" "+rows['nama_produk']}</td>
                                        <td>${rows['jumlah']} ${rows['satuan']}</td>
                                        <td><div class="status ${rows['status'] != 'masuk' ? "bg-danger" : "bg-success"}">${rows['status']}</div></td>
                                        <td>${rows['keterangan']}</td>
                                    </tr
                                </tbody>
                            </table>
                        </div>
                        
                       `;
                });


                let row2 = data["data2"].map(function (rows) {
                    var subrow = "";

                    for (var i = 0; i < rows['jumlahproduk']; i++) {
                        subrow += `
                        <tr>
                        <td>${i + 1}</td>
                        <td>${rows['produk' + i]["kode_produk"]}</td>
                        <td>${rows['produk' + i]["nama_kodetype"]+" "+rows['produk' + i]["nama_merek"]+" " + rows['produk' + i]["nama_produk"]}</td>
                        <td>${rows['jumlah' + i]} ${rows['produk' + i]["satuan"]}</td>
                    </tr`;
                    }
                    return `
                        <div class="card bg-light">
                            <div class="card-header">
                                <div class="row">
                                <div class="col">
                                    Retur ke Suplier
                                </div>
                                <div class="col-2">
                                <div class=''>Nama Admin: ${rows['name']}</div>
                                </div>
                            <div class="col-2">
                                <div class="  float-right">${rows['tanggal']}</div>
                            </div>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <thead class="thead">
                                    <tr>
                                       <th style="width:10px;">No</th>
                                        <th style="width:100px;">Kode Produk</th>
                                        <th style="width:180px;">Produk</th>
                                        <th style="width:70px;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                               ${subrow
                        }
                                </tbody>
                            </table>
                            <div class='card-footer'>Keterangan: ${rows['keterangan']}</div>
                        </div>
                        
                       `;
                });


                $("#dscont").html(row);
                $("#rscont").html(row2);
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    }

    $(document).click(function () {
        $("#myUL").hide();
    });

    $("#myUL").click(function (e) {
        e.stopPropagation();
    });

    $("#cetaksubmitter").submit(function (e) {
        e.preventDefault();
        alert($("#tanggal2").val());
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr("content")
            },
            url: "/printstoktrack",
            data: {
                "berdasarkan": $("#berdasarkan").val(),
                "tanggal": $("#tanggal2").val(),
                "tanggalakhir": $("#tanggal3").val(),
                "hmb": $("#hmb").val(),
                "keluar": $("#keluars").prop("checked") == true ? "true" : "false",
                "masuk": $("#masuks").prop("checked") == true ? "true" : "false",
                "suplier": $("#suplier").prop("checked") == true ? "true" :"false",

            },
            dataType: "json",
            type: "post",
            success: function (data) {
                printJS({ printable: data['filename'], type: 'pdf', base64: true });
            }, error: function (err) {
                alert(err.responseText);
            }
        })
    });

    $(document).on('keyup', '.inputan-produk', function (e) {
        $(e.target).closest(".parent1").children(".myUL").show();

        let kw = $(e.target).val();


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





                $(".drop").show();
                if (data['data'].length > 0) {
                    var li = "";
                    for (var i = 0; i < data['data'].length; i++) {
                        li += `<li>

                                   <a kode="${data['data'][i]['kode_produk']}" nama="${data['data'][i]['nama_produk']}" jumlah="1" potongan="0" class="sear">${data['data'][i]["kode_produk"] + " " + data['data'][i]["nama_produk"] + " " + data['data'][i]['nama_merek']}</a>
                                </div>
                            
                            </li>`;
                    }
                 

                    $(e.target).closest(".parent1").children(".myUL").html(li);
                } else {
                    $(".drop").hide();
                }
               
                console.log(data.length);

            },
            error: function (err, response,) {
                Swal.fire("terjadi kesalahan", "", "info");
            }
        });


    });

    $("#tambahbutton").click(function () {
        $("#first-row").after(`<div class="form-row mt-3">
        <div class="col-6 parent1">
       
            <input type="text" class="form-control inputan-produk" placeholder="Ketik Kode Atau Nama" name='kode[]'>
            <ul class="myUL">

            </ul>
        </div>
        <div class="col-3 parent1">
  
            <input type="text" class="form-control nama-produk" placeholder="Nama Produk">
        </div>
        <div class="col parent1">
    
            <input type="text" class="form-control" placeholder="Jumlah" name='jumlah[]'>
            
        </div>
        <div class="col-sm parent" >
        <button class="btndel btn btn-danger">hapus</button>
        </div>
    </div>`);
    });

    $(document).on("click", ".btndel", function (e) {
        $(e.target).closest(".form-row").remove();
    });
});
