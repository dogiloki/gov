function login(){
    var save='false';
    if($('#save_login').prop('checked')){
        save='true';
    }else{
        save='false';
    }
    $("#aviso").html('INGRESANDO...');
    $("#login-btn").attr('disabled','disabled');
    $("#login-btn").css('opacity','0.7');
    $.ajax({
        url:"controllers/user.php?v=login",
        method:"post",
        data:{
            'user':$("#user").val(),
            'password':$("#password").val(),
            'save':save
        },
        success:function(value){
            $("#login-btn").removeAttr('disabled');
            $("#login-btn").css('opacity','none');
            if(value=='vacio'){
                $("#aviso").html('Llena todos los campos');
            }
            if(value=='active'){
                $("#aviso").html('Tú cuenta no esta activa, ve a tú correo y activala<br>si no recibiste el correo contacta la página');
            }
            if(value=='locked'){
                $("#aviso").html('Tú cuenta esta bloqueada, contacte con la página');
            }
            if(value=='password'){
                $("#aviso").html('La contraseña es incorrecta');
            }
            if(value=='user'){
                 $("#aviso").html('El usuario no existe');
            }
            if(value=='error'){
                $("#aviso").html('Error enviar correo, contacta con la página para poner acceder');
            }
            if(value=='logiado'){
                window.location='index.php';
                //window.history.back();
            }
            if(value=='logiado_admin'){
                window.location='panel/index.php';
            }
        }
    })
}
function login2(){
    var keycode=event.keyCode;
    if(keycode=='13'){
        login();
    }
}
var id_global="";
function save_login_info(id){
    id_global=id;
    $.ajax({
        url:"controllers/user.php?v=login_datos",
        method:"post",
        data:{
            'id':id
        },
        success:function(value){
            var json=eval("("+value+")");
            if(json.user!='user'){
                $("#img_save").attr("src",json.img);
                $("#name_save").html(json.name);
                modal('modal_save_login');
            }else{
                delete_save(id);
            }
        }
    })
}
function login_save(){
    $("#aviso_save").html('INGRESANDO...');
    $.ajax({
        url:"controllers/user.php?v=login",
        method:"post",
        data:{
            'id':id_global,
            'login_save':'true',
            'password':$("#password_save").val(),
            'save':'false'
        },
        success:function(value){
            if(value=='active'){
                $("#aviso_save").html('Tú cuenta no esta activa, ve a tú correo y activala<br>si no recibiste el correo contacta la página');
            }
            if(value=='locked'){
                $("#aviso_save").html('Tú cuenta esta bloqueada, contacte con la página');
            }
            if(value=='password'){
                $("#aviso_save").html('La contraseña es incorrecta');
            }
            if(value=='user'){
                delete_save(id_global);
            }
            if(value=='error'){
                $("#aviso_save").html('Error enviar correo, contacta con la página para poner acceder');
            }
            if(value=='logiado'){
                window.location='index.php';
                //window.history.back();
            }
            if(value=='logiado_admin'){
                window.location='panel/index.php';
            }
        }
    })
}
function login_save2(){
    var keycode=event.keyCode;
    if(keycode=='13'){
        login_save();
    }
}
function delete_save(id){
    $.ajax({
        url:"controllers/user.php?v=delete_save",
        method:"post",
        data:{
            'id':id
        },
        success:function(value){
            modal("user"+id);
        }
    })
}