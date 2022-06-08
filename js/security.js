var veri_user=false;
var veri_email=false;
var veri_password=false;
window.onload=function(){
    email();
    user();
    password();
    $("#aviso").html("");
}
function update(){
    email();
    user();
    password();
    $("#aviso").html("ACTUALIZANDO DATOS....");
    $("#aviso").html("");
    if(veri_user==true && veri_email==true && veri_password==true && $("#password_actual").val()!=''){
        $.ajax({
            url:"controllers/user.php?v=updateUserSecurity",
            method:"post",
            data:{
                'user':$("#user").val(),
                'email':$("#email").val(),
                'password':$("#password").val(),
                'password_actual':$("#password_actual").val()
            },
            success:function(value){
                if(value=='password'){
                    $("#aviso").html("CONTRASEÑA ACTUAL INCORRECTA");
                }else{
                    if(value=='error'){
                        $("#aviso").html("ERROR EN SERVIDOR");
                    }else{
                        $("#aviso").html("");
                        window.location='security.php';
                    }
                }
            }
        })
    }else{
        if($("#password_actual").val()==''){
            $("#aviso").html("DÍGITE SU CONTRASEÑA ACTUAL");
        }else{
            $("#aviso").html("DATOS INCORRECTOS");
        }
    }
}
function user(){
    $.ajax({
        url:"controllers/user.php?v=user",
        method:"post",
        data:{
            'user':$("#user").val(),
            'posi':'security'
        },
        success:function(value){
            if(value=='El usuario ya existe'){
                $("#aviso_user").html(value);
                $("#user").css('border','2px solid red');
                $("#user").attr('title','No válido');
                veri_user=false;
            }else{
                $("#aviso_user").html("");
                $("#user").css('border','1px solid #c2c2c2');
                $("#user").attr('title','');
                veri_user=true;
            }
            if($("#user").val()==''){
                $("#user").attr('title','Sin usuario');
                veri_user=true;
            }
        }
    })
    $("#aviso").html("");
}
function email(){
    $.ajax({
        url:"controllers/user.php?v=email",
        method:"post",
        data:{
            'email':$("#email").val(),
            'posi':'security'
        },
        success:function(value){
            if(value=='Este email ya esta registrado' || value=='Email no válido'){
                $("#aviso_email").html(value);
                $("#email").css('border','2px solid red');
                $("#email").attr('title','No válido');
                veri_email=false;
            }else{
                $("#aviso_email").html("");
                $("#email").css('border','1px solid #c2c2c2');
                $("#email").attr('title','');
                veri_email=true;
            }
            if($("#email").val()==''){
		    	$("#aviso_email").html("");
		        $("#email").css('border','1px solid #c2c2c2');
		        $("#email").attr('title','');
				veri_email=false;
		    }
        }
    })
    $("#aviso").html("");
}
function password(){
    $.ajax({
        url:"controllers/user.php?v=password",
        method:"post",
        data:{
            'password':$("#password").val()
        },
        success:function(value){
            if(value=='valido'){
                $("#aviso_password").html("");
                $("#password").css('border','1px solid #c2c2c2');
                $("#password").attr('title','');
                veri_password=true;
            }else{
                $("#aviso_password").html(value);
                $("#password").css('border','2px solid red');
                $("#password").attr('title','No válido');
                veri_password=false;
            }
            if($("#password").val()==''){
                $("#password").css('border','1px solid #c2c2c2');
                $("#password").attr('title','No válido');
                veri_password=true;
            }
        }
    })
    $("#aviso").html("");
}