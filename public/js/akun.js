

$(document).ready(function(){
    $(".btnshow").click(function(){
       
        if($(this).closest(".input-group").children("input").attr("type") == "password"){
           
            $(this).closest(".input-group").children("input").attr("type","text");
            $(this).children("i").removeClass("fa-eye-slash");
            $(this).children("i").addClass("fa-eye");
        }else{
            
            $(this).closest(".input-group").children("input").attr("type","password");
            $(this).children("i").addClass("fa-eye-slash");
            $(this).children("i").removeClass("fa-eye");
        }
    });
 
    $("#tambahakun").submit(function(e){
       
        e.preventDefault();
        if($("input[name='password']").val().length < 8){
            alert('password minimal 8 karakter');
        }else{

        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            type: "post",
            url: "/emailmatch",
            data: {
                email: $("input[name=email]").val()
            },
            dataType: "json",
            success: function(data){
                if(data['jml']>0){
                    alert('email telah terdaftar');
                }else{
                   $("#tambahakun").unbind();
                   $("#tambahakun").submit();
                }
            },error: function(err){
                alert(err.responseText);
            }
        });
   
    }
});
});