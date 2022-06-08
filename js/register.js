var veri_birth=false;
var veri_postal_code=true;
var veri_phone=true;
var veri_user=false;
var veri_email=false;
var veri_password=false;
var veri_password_veri=false;
window.onload=function(){
    birth();
    email();
    user();
    password();
    password_veri();
    $("#sexo").val("");
    $("#id_user").val("");
    $("#code").val("");
}
function register(){
    $("#aviso").html('REGISTRANDO...');
    if(veri_birth==true && veri_postal_code==true && veri_phone==true && veri_password==true && veri_password_veri==true && veri_email==true && veri_user==true && $("#sexo").val()!=''){
        $("#register-btn").attr('disabled','disabled');
        $("#register-btn").css('opacity','0.7');
        $.ajax({
            url:"controllers/user.php?v=register",
            method:"post",
            data:{
                'name':$("#name").val(),
                'surname':'',
                'birth':$("#birth").val(),
                'cp':'',
                'phone':'',
                'email':$("#user_register").val(),
                'user':$("#user_register").val(),
                'password_veri':$("#password_veri").val(),
                'password':$("#password").val(),
                'sexo':$("#sexo").val()
            },
            success:function(value){
                $("#register-btn").removeAttr('disabled');
                $("#register-btn").css('opacity','none');
                if(value=='register'){
                    $("#aviso").html('EL USUARIO YA ESTA REGISTRADO');
                }else
                if(value=='password'){
                    $("#aviso").html('La contraseña no coinciden');
                }else
                if(value=='error'){
                    $("#aviso").html('ERROR INTENTER MÁS TARDE');
                }else
                if(value=='email'){
                    $("#aviso").html('Error enviar correo, contacta con la página para poner acceder');
                }else{
                    var datos=value.split("$&$&");
                    $("#id_user").val(datos[1]);
                    window.location='login.php';
                }
            }
        })
    }else{
        $("#aviso").html('Datos incorrectos');
    }
}
function active(){
    $.ajax({
        url:"controllers/user.php?v=active&op=1",
        method:"post",
        data:{
            'id_user':$("#id_user").val(),
            'code':$("#code").val()
        },
        success:function(value){
            if(value='active'){
                window.location='login.php';
            }else{
                $("#aviso_active").html(value);
            }
        }
    })
}

//Validación
function birth(){
    if($("#birth").val()==''){
        $("#birth").css('border','2px solid red');
        $("#birth").attr('title','No válido');
        veri_birth==false;
        return;
    }
	var fecha=$("#birth").val();
	if(typeof fecha != "string" && fecha && esNumero(fecha.getTime())){
        fecha = formatDate(fecha, "yyyy-MM-dd");
    }
    var values = fecha.split("-");
    var dia = values[2];
    var mes = values[1];
    var ano = values[0];
    var fecha_hoy = new Date();
    var ahora_ano = fecha_hoy.getYear();
    var ahora_mes = fecha_hoy.getMonth() + 1;
    var ahora_dia = fecha_hoy.getDate();

    //Calcular edad
    var edad = (ahora_ano + 1900) - ano;
    if (ahora_mes < mes) {
        edad--;
    }
    if(mes==ahora_mes && ahora_dia<dia) {
        edad--;
    }
    if(edad>1900) {
        edad-=1900;
    }
	//Calcular meses
    var meses=0;
    if(ahora_mes>mes && dia>ahora_dia){
        meses=ahora_mes-mes-1;
    }
    if(ahora_mes>mes){
        meses=ahora_mes-mes;
    }
    if(ahora_mes<mes && dia<ahora_dia){
        meses = 12 - (mes - ahora_mes);
    }
    if(ahora_mes<mes){
        meses = 12 - (mes - ahora_mes + 1);
    }
    if(ahora_mes==mes && dia>ahora_dia){
        meses = 11;
    }
    //Calcular dias
    var dias=0;
    if(ahora_dia>dia){
        dias=ahora_dia-dia;
    }
    if(ahora_dia<dia){
        ultimoDiaMes = new Date(ahora_ano, ahora_mes - 1, 0);
        dias = ultimoDiaMes.getDate() - (dia - ahora_dia);
    }
    if(edad<18){
    	if(mes>0 && dia>0){
    		$("#birth").css('border','2px solid red');
			$("#birth").attr('title','No válido');
            veri_birth=false;
    	}else{
    		$("#birth").css('border','1px solid #c2c2c2');
			$("#birth").attr('title','');
            veri_birth=true;
    	}
    }else{
    	$("#birth").css('border','1px solid #c2c2c2');
		$("#birth").attr('title','');
        veri_birth=true;
    }
    if($("#birth").val()==''){
        $("#birth").css('border','2px solid #c2c2c2');
        $("#birth").attr('title','No válido');
        veri_birth==false;
    }
}
function cp(){
    $.ajax({
        url:"controllers/user.php?v=cp",
        method:"post",
        data:{
            'cp':$("#cp").val()
        },
        success:function(value){
            if(value=='sin_ubicacion'){
                $("#cp").css('border','2px solid red');
                $("#colonia").val("Sin ubicación");
                $("#municipio").val("Sin ubicación");
                $("#estado").val("Sin ubicación");
                $("#cp").attr('title','No váildo');
                veri_postal_code=false;
            }else{
                $("#cp").css('border','1px solid #c2c2c2');
                var datos=value.split("&");
                $("#colonia").val(datos['0']);
                $("#municipio").val(datos['1']);
                $("#estado").val(datos['2']);
                $("#cp").attr('title','');
                veri_postal_code=true;
            }
        }
    })
}
function phone(){
    if($("#phone").val().length>9 && $("#phone").val().length==10 && !isNaN($('#phone').val())){
        $("#phone").css('border','1px solid #c2c2c2');
        $("#phone").attr('title','');
        $("#aviso_phone").html("");
        veri_phone=true;
    }else{
        $("#phone").css('border','2px solid red');
        $("#phone").attr('title','No válido');
        $("#aviso_phone").html("Telefono no váildo");
        veri_phone=false;
    }
}
function cambio_sexo(op){
    $('.register-sexo').css("transform","none");
    $('.register-sexo').css("border","1px solid #c2c2c2");
    $('#sexo').val(op);
    $('#img-'+op).css("transform","scale(1.1)");
    $('#img-'+op).css("border","1px solid #F86400");
}
function email(){
    $.ajax({
        url:"controllers/user.php?v=email",
        method:"post",
        data:{
            'email':$("#user_register").val()
        },
        success:function(value){
            if(value=='Este email ya esta registrado' || value=='Email no válido'){
                $("#aviso_user").html(value);
                $("#user_register").css('border','2px solid red');
                $("#user_register").attr('title','No válido');
                veri_email=false;
            }else{
                $("#aviso_user").html("");
                $("#user_register").css('border','1px solid #c2c2c2');
                $("#user_register").attr('title','');
                veri_email=true;
            }
        }
    })
}
function user(){
    $.ajax({
        url:"controllers/user.php?v=user",
        method:"post",
        data:{
            'user':$("#user_register").val()
        },
        success:function(value){
            if(value=='El usuario ya existe'){
                $("#aviso_user").html(value);
                $("#user_register").css('border','2px solid red');
                $("#user_register").attr('title','No válido');
                veri_user=false;
            }else{
                $("#aviso_user").html("");
                $("#user_register").css('border','1px solid #c2c2c2');
                $("#user_register").attr('title','');
                veri_user=true;
            }
        }
    })
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
                veri_password=false;
            }
        }
    })
}
function password_veri(){
    $.ajax({
        url:"controllers/user.php?v=password",
        method:"post",
        data:{
            'password':$("#password_veri").val()
        },
        success:function(value){
            if(value=='valido'){
                $("#aviso_password_veri").html("");
                $("#password_veri").css('border','1px solid #c2c2c2');
                $("#password_veri").attr('title','');
                veri_password_veri=true;
            }else{
                $("#aviso_password_veri").html(value);
                $("#password_veri").css('border','2px solid red');
                $("#password_veri").attr('title','No válido');
                veri_password_veri=false;
            }
            if($("#password_veri").val()==''){
                $("#password_veri").css('border','1px solid #c2c2c2');
                $("#password_veri").attr('title','No válido');
                veri_password_veri=false;
            }
        }
    })
}
function esNumero(value){
	if(value==null) return false;
	if(value==undefined) return false;
	if(typeof value==="number" && !isNaN(value)) return true;
	if(value=="") return false;
	if(value==="") return false;
	return ParseInt(value);
}