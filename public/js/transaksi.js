

$(document).ready(function(e){
    $("#infomodal").modal("show");

    $(document).on('click', '.infopreorder', function(e){
    });


    $("#usercetak").submit(function(e){

    });

    $(".printing").click(function(){
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/printnotakecilbc",
            data: {
                id: $(this).attr('id_trans')
            },
            type: "post",
            success: function(response){
                printJS({printable: response['filename'], type: 'pdf', base64: true});
            },error: function(err){
                alert(err.responseText);
            }
        });
    });

    $(".content-wrapper").on("click", ".datatrans", function(event){
        $("#exampleModal").modal('show');
    
        $.ajax({
            headers:  {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }, 
            data: {id: $(event.target).is(".datatrans") ? $(event.target).attr('id_trans') : $(event.target).closest('.datatrans').attr('id_trans')},
            url: "/loadsingletrans",
            dataType: "JSON", 
            type: "post",
            success: function(data){   
              
                $(".t2after").hide();
                $(".t3after").hide();
              
             
              
                $(".viatermin1").text("Via : "+data['cicilan'][0][0]['via']);
                $(".kasirtermin1").text("Kasir : " + data['cicilan'][0][1]);
             
                var dato = data['trans'].map(function(transo){
                    return "<tr>"+"<td>"+transo["kode_produk"]+"</td>"+"<td>"+transo["nama_produk"]+"</td>"+"<td>"+transo["merk"]+"<td>"+transo["jumlah"]+"</td>"+"<td>"+transo["kategori"]+"</td>"+"</tr>";   
                });
                     
             
           
               $("#namapelanggan").html("Nama Pelanggan : " );
               $(".tgltermin1").text("Tanggal Pembayaran :  "+ data['cicilan'][0][0]['created_at']);
               $(".nominaltermin1").html("Nominal :  "+ data['cicilan'][0][0]['nominal']);



               console.log(data['cicilan']);


               $("#dtcontent").html(dato);
               $("#tanggaltrans").html(data['detail'][0]['created_at']);
               $("#idcontainertrans").val(data['trans'][0]['kode_trans']);


              
         if(data['cicilan'][1][0]['nominal'] == null){
            $(".termin3a").addClass("disabled");
          
         }else{
            $("#termin2form").hide();
            $(".t2after").show();
            $(".kasirtermin2").text("Kasir : " + data['cicilan'][1][1]);
            $(".tgltermin2").text("Tanggal Pembayaran :  "+ data['cicilan'][1][0]['created_at']);
            $(".nominaltermin2").html("Nominal :  "+ data['cicilan'][1][0]['nominal']);
            $(".viatermin2").text("Via : "+data['cicilan'][1][0]['via']);
         }

         if(data['cicilan'][2][0]['nominal'] != null){
 
            $("#termin3form").hide();
            $(".t3after").show();
            $(".kasirtermin3").text("Kasir : " + data['cicilan'][2][1]);
            $(".tgltermin3").text("Tanggal Pelunasan :  "+ data['cicilan'][2][0]['updated_at']);
            $(".nominaltermin3").html("Nominal :  "+ data['cicilan'][2][0]['nominal']);
            $(".viatermin3").text("Via : "+data['cicilan'][2][0]['via']);
         }

       
                
                
            },
            error: function(err){
            }
        });
    });


    function bayarcicilan(termin, data){
     
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $("meta[name='csrf-token']").attr('content')
            },
            data: {
                kode_transaksi: data['kode_transaksi'],
                termin: termin,
                nominal: data['nominal'],
                via:data['via']
            },
            url: "/bayarcicilan",
            type: "post",
            success: function(){
                swal.fire({
                    title: "Termin " + termin+" telah dilunasi",
                    type: "success",
                }).then(function(e){
                    window.location = "/transaksi"
                })
            },
            error: function(err){
            }
        });
    }

    $("#termin2form").submit(function(e){
        e.preventDefault();
        let dats = {
            "kode_transaksi" : $("#idcontainertrans").val(),
            "via" : $('.viatermin2').val(),
            "nominal" : $('.nominaltermin2').val(),
        };
       bayarcicilan(2, dats);
    });

    $("#termin3form").submit(function(e){
        e.preventDefault();
        let dats = {
            "kode_transaksi" : $("#idcontainertrans").val(),
            "via" : $('.viatermin3').val(),
            "nominal" : $('.nominaltermin3').val(),
        };
        Swal.fire({
            title: 'Apakah anda yakin ingin melunasi',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Lunasi`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
            } else if (result.isDenied) {
               bayarcicilan(3, dats);
            }
          });
        
      
     });

     $(".returntrans").click(function(e){
         e.preventDefault();
         $("#returnform").modal("show");
         $.ajax({
             headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content'),
             },
             data: {
                 'id_trans' : $(this).attr('id_trans')
             },
             url: "/tampilreturn",
             type: "post",
             dataType: "json",
             success: function(data){
                 let no = 1;
                 let row = data.map(function(r,i){
                    return `
                        <tr ${r['status'] == 'return' ? 'style="background: lightyellow"' : ""}>
                            <td ><input ${r['status'] == 'return' ? 'disabled' : ""} name="kode[]" style="margin-top: -6px;" class="form-control" type="checkbox" value="${r['kode_produk']}"></td>
                            <td>${r['nama_kodetype']} ${r['nama_merek']} ${r['nama_produk'] + " "}</</td>
                            <td>${parseInt(r['harga']).toLocaleString()}</</td>
                            <td>${r['potongan']}</</td>
                            <td>${r['jumlah']}</td>
                            <td>${r['status']}</td>
                        </tr>
                    `;
                 });
                 $("#np").text("Nama Pelanggan : "+data[0]['nama_pelanggan']);
                 $("#tp").text("No. Telp : "+data[0]['telepon']);
                 $("#almt").text("Alamat : "+data[0]['alamat']);
                 $("#id_trans").val(data[0]['kode_trans']);
                 if(    data[0]['status'] == 'return'){
                     $("#re-button").attr('disabled','disabled');
                 }
                 $("#returncont").html(row);

             },error: function(err){
                 alert(err.responseText);
             }
             
         })
     });

     $(".btn-bayar").click(function(e){
        e.preventDefault();
        $("#tombolbayar").attr('id_trans',$(this).attr('id_trans'));
        $("#totalbayar").val($(this).attr('subtotal'));
        $("#td").val($(this).attr('td'));
     });

     $("#tombolbayar").click(function(){
        let total=parseInt($("#totalbayar").val().replace(/[._]/g, ''));
        let td=parseInt($("#td").val().replace(/[._]/g, ''));
        
        let id = $(this).attr('id_trans');
        let nominal = parseInt($("#nominal-bayar").val().replace(/[._]/g, ''));
      
        if(nominal+td>=total){
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
                },
                url: "/bayarcicilannotakecil",
                data: {
                    'id': id,
                    'nominal': nominal,
                },
                type:'post',
                success: function(){
                    window.location = "/transaksi";
                },
                error: function(err){
                    alert(err.responseText);
                }
            });
        }else{
            Swal.fire("Nominal kurang","","info");
        }
       
     });

     
      
  

});


