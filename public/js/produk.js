$(document).ready(function(){   
    $("button[data-target='#modalproduk']").click(function(e){
        $("#submitterproduk").attr('action', '/tambahbarang');
    });

    $("#submitterkategori").submit(function(e){
        let url = $(this).attr('action');
        e.preventDefault();
        let kat = {
            'id_kategori' : $("#nokat-input").val(),
            'kategori' : $('#kategori-input').val(),
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $("meta[name='csrf-token'").attr('content')
            },
            data: {
                kat: kat
            },
            url : url,
            type: "POST",
            success: function(data){
                alert('databerhasilditambahkan');
            },
            error: function(err){
                alert(err.responseText);
            }
        });
    }); 

    function hapusmerek(nomer){
        alert(nomer);
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }, 
            data:{
                nomer: nomer
            },
            type: "POST",
            dataType: "JSON",
            url: "/hapusmerek", 
            success: function(data){
               Swal.fire({
                   title: "Berhasil dihapus"
               });
                
            },
            error: function(response,){
                alert(response.text);
                produkInfo = "notshow";
            }
        });
    }

    function getProdukInfo(id_produk){
        var produkInfo;
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }, 
            data:{
                kode_produk: id_produk
            },
            type: "POST",
            dataType: "JSON",
            url: "/getprodukinfo", 
            success: function(data){
                produkInfo = data;
                $("#kode-produk").val(data["kode_produk"]);
                $("#nama-produk").val(data["nama_produk"]);
                $("#merek-produk").val(data["merk"]);
                $("#kategori-produk").val(data["id_kategori"]);
                $("#harga-produk").val(data["harga"]);
                $("#satuan-produk").val(data["stn"]);
                
            },
            error: function(response,){
                alert(response.text);
                produkInfo = "notshow";
            }
        });
        
        
    }

    function getMerekInfo(id_merek){
    
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $("meta[name='csrf-token']").attr('content')
            },
            data: {
              nomer : id_merek 
            },
            url: "/getmerekinfo",
            type: "POST",
            dataType: "JSON",
            success: function(data){
                $("#nomermerek").val(data[0]['nomer']),
                $("#namamerek").val(data[0]['merek'])
            },
            error: function(){
                alert('lil');
            }

        });
    }


    function loadProduk(){
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }, 
            
            type: "POST",
            dataType: "JSON",
            url: "/loadproduk", 
            success: function(data){
                var row = data.map(function(datos){
                    return "<tr>"+"<td>"+datos["kode_produk"]+"</td>"+"<td>"+datos["nama_produk"]+"</td>"+"<td>"+datos["merk"]+"</td>"+"<td>"+datos["kategori"]+"</td>"+"<td>"+datos["harga"]+"<td>"+datos['stn']+"</td>"+"<td align='center'><button class='btn btn-info mr-3 editbarang'><a href='' kode-produk='"+datos["kode_produk"]+"'><i class='fa fa-edit'></i></a></button><button class='btn btn-danger hapusbarang'><a href='' kode-produk = '"+datos["kode_produk"]+"'><i class='fa fa-trash'></i></a></button>"+"</tr>";
                });
                $("#produkfiller").html(row);
            },
            error: function(response,){
                alert("ada yang salah");
            }
        });
    }

    
    $("#submitterproduk").submit(function(e){
        
        e.preventDefault();
        let url = $(this).attr("action");

        var dataform = {
            kode_produk : $("#kode-produk").val(),
            nama_produk : $("#nama-produk").val(),
            merek_produk : $("#merek-produk").val(),
            kategori_produk : $("#kategori-produk").val(),
            harga_produk : $("#harga-produk").val(),
            satuan_produk : $("#satuan-produk").val(),
            code_type : $("#tipe-kode").val(),
            nomermerek : $("#nomer-merek").val(),
        };
      

        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }, 
             data: dataform,
            
            type: "POST",
            url: url, 
            success: function(data){
                Swal.fire("Barang Berhasil ditambahkan", "Barang telah tersimpan","success").then(function(){
                    window.location = "/produk";
                });

                //clear the modal
                if(url == "/updateproduk"){
                
                
                $("#submitterproduk").modal("hide");
                }else{
                    $(".tambahbarangform input").val("");
                }

            },
            error: function(err){
                alert(err.responseText);
            }
        });
    });


    function hapusProduk(idproduk){


        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }, 
             data: {
                 kode_produk: idproduk
             },
            
            type: "POST",
            url: "/hapusbarang", 
            success: function(data){
                Swal.fire(
                    {
                        title: "Produk Berhasil dihapus"
                    }
                );

                //clear the modal
                $(".tambahbarangform input").val("");
                loadProduk();
            },
            error: function(response,){
                alert("cek apache server");
            }
        });
    }


    $("#produkfiller").on("click", "tr td .hapusbarang", function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Hapus`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
            } else if (result.isDenied) {
              Swal.fire('Permintaan diterima', '', 'info');
              hapusProduk($(e.target).children("a").attr("kode-produk"));
            }
          });
    });

   
   




    $("#produkfiller").on("click", "tr td .editbarang", function(e){
        e.preventDefault();
        $("#modalproduk").modal("show");
        $("#submitterproduk").attr('action', '/updateproduk');
        alert($(e.target).children("a").attr('kode-produk'))
        getProdukInfo($(e.target).children("a").attr('kode-produk') == undefined ? $(e.target).attr('kode-produk') : $(e.target).children("a").attr('kode-produk'));
        
    });

    
                //clear the modal
               
    $("button[data-target='#modalmerek']").click(function(){
        $(".tambahbarangform input").val("");
        $("#nomermerek").removeAttr('disabled');
        $("submittermerek").attr('action','/tambahmerek');
    });

    $("#submittermerek").submit(function(e){
        let url = $(this).attr('action');
        e.preventDefault();

        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name='csrf-token']").attr('content')
            },
            data: { data:{
               "nomer": $("#nomermerek").val(),
                "merek": $("#namamerek").val()
            }
            },
            url : url,
            type : "POST",
            dataType : "JSON",
            success : function(){
                if(url == "/ubahmerek"){
                    alert('merek berhasil diubah');
                }
            },
            error: function(err){
                alert(err.responseText);
            }
        });
    });

    $("#merekfiller").on('click', '.merekedit', function(e){
        getMerekInfo($(e.target).attr('nomer') == null ? $(e.target).closest('button').attr('nomer') : $(e.target).attr('nomer'));

        $("#modalmerek").modal('show');
        $("#nomermerek").attr('disabled', 'disabled');
        $("#submittermerek").attr('action', '/ubahmerek');
    });

    $("#merekfiller").on('click', '.merekhapus', function(e){

        Swal.fire({
            title: 'Apakah anda yakin ingin menghapus',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Hapus`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                
            } else if (result.isDenied) {
                hapusmerek($(e.target).attr('nomer') == null ? $(e.target).closest('button').attr('nomer') : $(e.target).attr('nomer'));
            }
          });
    });
          
    $('button[data-target="#modalkategori"]').click(function(){
        $("#submitterkategori").attr('action','/tambahkategori');
    });

  

    
});