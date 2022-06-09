var espacios=0;
var veri_phone=false;
var veri_cp=false;
window.onload=function(){
    phone();
    espacios=0;
    $("#tarjeta").val("");
}
function phone(){
    if($("#phone_new").val().length>9 && $("#phone_new").val().length==10 && !isNaN($('#phone_new').val())){
        $("#phone_new").css('border','1px solid #c2c2c2');
        $("#phone_new").attr('title','');
        $("#aviso_phone").html("");
        veri_phone=true;
    }else{
        $("#phone_new").css('border','2px solid red');
        $("#phone_new").attr('title','No válido');
        $("#aviso_phone").html("Telefono no váildo");
        veri_phone=false;
    }
}
function cp(){
    $.ajax({
        url:"controllers/user.php?v=cp",
        method:"post",
        data:{
            'cp':$("#cp_new").val()
        },
        success:function(value){
            if(value=='sin_ubicacion'){
                $("#cp_new").css('border','2px solid red');
                $("#colonia_new").val("Sin ubicación");
                $("#municipio_new").val("Sin ubicación");
                $("#estado_new").val("Sin ubicación");
                $("#cp_new").attr('title','No váildo');
                $("#cp").css('border','2px solid red');
                $("#colonia").val("Sin ubicación");
                $("#municipio").val("Sin ubicación");
                $("#estado").val("Sin ubicación");
                $("#cp").attr('title','No váildo');
                veri_cp=false;
            }else{
                $("#cp_new").css('border','1px solid #c2c2c2');
                var datos=value.split("&");
                $("#colonia_new").val(datos['0']);
                $("#municipio_new").val(datos['1']);
                $("#estado_new").val(datos['2']);
                $("#cp_new").attr('title','');
                $("#cp").css('border','1px solid #c2c2c2');
                var datos=value.split("&");
                $("#colonia").val(datos['0']);
                $("#municipio").val(datos['1']);
                $("#estado").val(datos['2']);
                $("#cp").attr('title','');
                veri_cp=true;
            }
        }
    })
}
function updateUser(op){
    if(veri_cp==true || veri_phone==true){
        $.ajax({
            url:"controllers/user.php?v=updateUser&campo="+op,
            method:"post",
            data:{
                'dato':$("#"+op+"_new").val()
            },
            success:function(value){
                $("#"+op).val($("#"+op+"_new").val());
                $("#"+op+"_new").val("");
                modal("modal_"+op);
            }
        })
    }
}
function comprar(total_buy,id_product,cantidad1,color,format){
    var cantidad=1;
    if(format=='Digital'){
        var cantidad=1;
    }else{
        var cantidad=cantidad1;
    }
    very_compra=false;
    $.ajax({
        url:"controllers/buy.php?v=very_compra",
        method:"post",
        data:{
            'id_product':id_product,
            'cantidad':cantidad,
            'format':format
        },
        success:function(value_very){
            if(value_very=='login'){
                very_compra=false;
                window.location='login.php';
            }
            if(value_very=='CORRECTO'){
                very_compra=true;
            }else{
                $("#aviso_pago").html(value_very);
                very_compra=false;
            }
        }
    })
    if(very_compra==false){
        $("#aviso_pago").html("");
    }
    if($("#name_recibe").val()=='' || $("#direccion").val()=='' || $("#direccion").val()=='' || $("#phone").val()=='' || $("#referencias").val()=='' || $("#cp").val()=='' || $("#colonia").val()=='' || $("#municipio").val()=='' || $("#estado").val()==''){
        $("#aviso_pago").html("DATOS INCORRECTO INFORMACIÓN DE ENTREGA");
    }else{
        $.ajax({
            url:"http://localhost/kodil/controllers/kodil.php",
            method:"post",
            data:{
                'v':'comprar',
                'cuenta_para':'8100 6736 2395 13',
                'num_tarjeta':$("#num_tarjeta").val(),
                'dia':$("#exp_dia").val(),
                'mes':$("#exp_mes").val(),
                'cvv':$("#cvv").val(),
                'cantidad':total_buy
            },
            success:function(value){
                if(value=='COMPRA CORRECTA'){
                    $.ajax({
                        url:"controllers/buy.php?v=comprar",
                        method:"post",
                        data:{
                            'id_product':id_product,
                            'cantidad':cantidad,
                            'color':color,
                            'total':total_buy,
                            'name_recibe':$("#name_recibe").val(),
                            'direccion':$("#direccion").val(),
                            'phone':$("#phone").val(),
                            'referencias':$("#referencias").val(),
                            'cp':$("#cp").val(),
                            'format':format
                        },
                        success:function(value2){
                            if(value2=='ERROR AL ALMACENAR LA COMPRA'){
                                $("#aviso_pago").html(value2);
                            }else{
                                window.location='ticker.php?id='+value2;
                            }
                        }
                    }) 
                }else{
                    $("#aviso_pago").html(value);
                }
            }
        })
    }
}