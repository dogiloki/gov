var veri_birth=false;
var veri_postal_code=false;
var veri_phone=false;
window.onload=function(){
	phone();
	cp();
    birth();
    $("#img").val("");
    $("#carga").val(0);
    $("#pro").html("");
}
function update(){
	if(veri_birth==true && veri_postal_code==true && veri_phone==true && $("#name").val()!='' && $("#birth").val()!='' && $("#sexo").val()!=''){
		updateUser('name');
		updateUser('phone');
		updateUser('cp');
		updateUser('birth');
		updateUser('sexo');
		if($("#img").val()!=''){
			updateImg();
		}
	}else{
		alert("DATOS INCORRECTOS");
	}
}
function updateUser(campo){
	$.ajax({
        url:"controllers/user.php?v=updateUser&campo="+campo,
        method:"post",
        data:{
            'dato':$("#"+campo).val()
        },
        success:function(value){
            if(value=='ERROR EN SERVIDOR'){
                alert("ERROR AL ACTUALZAR DATOS, INTENTE MÁS TARDE");
            }else{
                if(campo=='sexo' && $("#img").val()==''){
                	window.location='account.php';
                }
            }
        }
    })
}
function updateImg(){
	$("#img").upload('controllers/user.php?v=updateUser&campo=img',
    {
        'img':$('#img')[0].files[0]
    },
    function(value){
        //Subida finalizada.
	    $("#carga").val(0);
	    $("#pro").html("");
        if(value==1 || value!="error"){
            window.location='account.php';
        }else{
            alert("ERROR AL ACTUALZAR IMAGEN, INTENTE MÁS TARDE");
        }
    },
    function(progress,value) {
        //Barra de progreso.
        $("#carga").val(value);
        $("#pro").html(value+"%");
    });
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
    if($("#phone").val()==''){
        $("#phone").css('border','1px solid #c2c2c2');
	    $("#phone").attr('title','');
	    $("#aviso_phone").html("");
	    veri_phone=true;
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
            if($("#cp").val()==''){
				$("#cp").css('border','1px solid #c2c2c2');
				$("#colonia").val("Sin ubicación");
		        $("#municipio").val("Sin ubicación");
		        $("#estado").val("Sin ubicación");
		        $("#cp").attr('title','No váildo');
		        veri_postal_code=true;
		    }
        }
    })
}
function birth(){
    if($("#birth").val()==''){
        $("#birth").css('border','2px solid red');
        $("#birth").attr('title','No válido');
        veri_birth==false;
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
        $("#birth").css('border','2px solid red');
        $("#birth").attr('title','No válido');
        veri_birth==false;
    }
}