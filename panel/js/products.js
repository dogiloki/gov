function color(op){
    var num=$("#num_color").val();
    if(op=='add'){
        num++;
        $("#num_color").val(num);
        $("#content-color").html($("#content-color").html()+"<input type='color' placeholder='Color' class='caja' id='color"+num+"' required>");
    }
    if(op=='remove'){
        if(num!=1){
            $("#color"+num).css("display","none");
            $("#color"+num).attr("id","none");
            num--;
            $("#num_color").val(num);
        }
    }
}
function register_product(category,seccion,status,format){
    $("#btn").css("opacity","0.8");
    $("#btn").attr("disabled");
    if($("#title").val()=='' || $("#description").val()=='' || $("#quantity").val()=='' || $("#price").val()=='' || $("#shipping").val()=='' || $("#num_color").val()==''){
        $("#espere").html("LLENE TODOS LOS CAMPOS");
        $("#btn").css("opacity","1");
        $("#btn").removeAttr("disabled");
    }else{
        var colores="";
        for(var a=1; a<=$("#num_color").val(); a++){
            colores+=$("#color"+a).val()+"&"
        }
        $.ajax({
            url:"../controllers/products.php?v=add",
            method:"post",
            data:{
                'title':$("#title").val(),
                'description':$("#description").val(),
                'quantity':$("#quantity").val(),
                'price':$("#price").val(),
                'shipping':$("#shipping").val(),
                'color':colores,
                'category':category,
                'seccion':seccion,
                'status':status,
                'format':format
            },
            success:function(value){
                if(value=="error"){
                    $("#espere").html("ERROR AL REGISTRAR PRODUCTO INTENTE MÁS TARDE");
                    $("#btn").css("opacity","1");
                    $("#btn").removeAttr("disabled");
                }else{
                    if(format=='Digital'){
                        add_file(value);
                    }else{
                        $("#btn").css("opacity","1");
                        $("#btn").removeAttr("disabled");
                        window.location='add6.php?id='+value;
                    }
                }
            }
        })
    }
}
function add_file(id){
    if($("#file").val()==''){
        delete_product(id);
        $("#espere").html("ELIGE UN ARCHIVO");
    }else{
        $("#file").upload('../controllers/products.php?v=file',
        {
            'id':id,
            'file':$('#file')[0].files[0]
        },
        function(value){
            //Subida finalizada.
            $("#carga").val(0);
                if(value==1 || value!="error") {
                    $("#btn").css("opacity","1");
                    $("#btn").removeAttr("disabled");
                    window.location='add6.php?id='+id;
                }else{
                    $("#espere").html("ERROR AL REGISTRAR PRODUCTO INTENTE MÁS TARDE");
                    delete_product(id);
                    $("#btn").css("opacity","1");
                    $("#btn").removeAttr("disabled");
                }
        },
        function(progress,value) {
            //Barra de progreso.
            $("#carga").val(value);
        });
    }
}
function add_img(id){
    if($("#img").val()==''){
        $("#espere").html("ELIGE IMAGENES");
    }else{
        $("#btn").css("opacity","0.8");
        $("#btn").attr("disabled");
        var num=$('#img')[0].files.length;
        var contador=1;
        $("#num").html(contador+" / "+num+" --- 0%");
        for(var i=0; i<num; i++){
            $("#img").upload('../controllers/products.php?v=add-img',
            {
                'id':id,
                'img[]':$('#img')[0].files[i]
            },
            function(value){
                //Subida finalizada.
                if(value=='valido'){
                    $("#espere").html("ARCHIVO NO VALIDO "+contador);
                }
                if(value=='error'){
                    $("#espere").html("ERROR AL SUBIR IMAGEN: "+contador);
                }
                if(value==1){
                    $("#num").html(i+" / "+num);
                    $("#carga").val(0);
                }
                if(i==num){
                    window.location='add6.php?id='+id;
                }
            },
            function(progress,value) {
                //Barra de progreso.
                $("#carga").val(value);
                $("#num").html(contador+" / "+num+" --- "+value+"%");
                if(value==100){
                    contador++;
                }
            });
        }
    }
}
function delete_product(id){
    if(confirm("Esta seguro de eliminar")){
        $.ajax({
            url:"../controllers/products.php?v=delete-product",
            method:"post",
            data:{
                'id':id
            },
            success:function(value){
                if(value=='error'){
                    $("#espere").html("ERROR AL ELIMINAR INTENTE MÁS TARDE");
                }else{
                    modal(value);
                }
            }
        })
    }
}
function delete_img(id,op){
    var pre="";
    if(op==''){
        pre="Esta seguro de eliminar todas las imagenes";
    }else{
        pre="Esta seguro de eliminar la imagen";
    }
    if(confirm(pre)){
        $.ajax({
            url:"../controllers/products.php?v=delete-img&op="+op,
            method:"post",
            data:{
                'id':id
            },
            success:function(value){
                if(value=='error'){
                    $("#espere").html("ERROR AL ELIMINAR INTENTE MÁS TARDE");
                }else{
                    window.location='add6.php?id='+id;
                }
            }
        })
    }
}


/* Funciones editar */
function modal2(op){
    $(".product").css("background","none");
    $("#"+op).css("background","#666");
    var x = window.event.clientX;
    var y = window.event.clientY;
    $(".content-option").css("top",y+"px");
    $(".content-option").css("left",x+"px");
    if(document.getElementById("modal-option"+op).style.display==''){
        document.getElementById("modal-option"+op).style.display='none';
        $(".product").css("background","none");
    }else{
        document.getElementById("modal-option"+op).style.display='';
    }
}
var id_global="";
function getUpdate(op,id,show){
    $("#espere_info").html("");
    $("#espere_img").html("");
    $("#espere_color").html("");
    $("#espere_file").html("");
    if(id!=''){
        id_global=id;
    }
    $.ajax({
        url:"../controllers/products.php?v=get-update&op="+op,
        method:"post",
        data:{
            'id':id_global
        },
        success:function(value){
            var json=eval("("+value+")");
            if(op=='view'){
                $("#img-view"+id_global).attr("src","../../"+json.img_view);
                $("#title-view"+id_global).html(json.title_view);
                $("#sold-view"+id_global).html("Vendidos: "+json.sold_view);
                $("#views-view"+id_global).html("Visitas: "+json.views_view);
                $("#stars-view"+id_global).html(json.stars_view);
            }
            if(op=='opinion'){
                $("#content-opinions").html(json.opinion);
            }
            if(op=='question'){
                $("#content-questions").html(json.question);
            }
            if(op=='info'){
                $("#title").val(json.title);
                $("#description").val(json.description);
                $("#price").val(json.price);
                $("#shipping").val(json.shipping);
                $("#quantity").val(json.quantity);
                $("#status").val(json.status);
                $("#Nuevo").css("background","#444");
                $("#Usado").css("background","#444");
                $("#Reacondicionado").css("background","#444");
                $("#"+json.status).css("background","#2e82ff");
            }
            if(op=='color'){
                $("#content-color-view").html("");
                $("#content-color").html("<input type='color' placeholder='Color' class='caja' id='color1' required>");
                $("#num_color").val("1");
                var color=json.colores.split("&");
                for(var a=0; a<color.length-1; a++){
                    $("#content-color-view").html($("#content-color-view").html()+"<div class='color-product' style='background: "+color[a]+";' onclick=\"delete_color('"+color[a]+"')\"></div>");
                }
            }
            if(op=='img'){
                $("#content-img").html("");
                $("#content-img").html(json.imgs);
            }
            if(op=='img-sele'){
                $("#content-img-sele").html("");
                $("#content-img-sele").html(json.imgs);
            }
            if(op=='file'){
                $("#file_view").val("");
                $("#title_file").html(json.title_file);
            }
            if(op!='view'){
                if(show==true){
                    modal('modal-'+op);
                }
            }
        }
    })
}
function cambiar_status(op){
    $("#status").val(op);
    $("#Nuevo").css("background","#444");
    $("#Usado").css("background","#444");
    $("#Reacondicionado").css("background","#444");
    $("#"+op).css("background","#2e82ff");
}
function update_info(){
    $("#espere_info").html("ACTUALIZANDO INFORMACIÓN...");
    $.ajax({
        url:"../controllers/products.php?v=update-info",
        method:"post",
        data:{
            'id':id_global,
            'title':$("#title").val(),
            'description':$("#description").val(),
            'quantity':$("#quantity").val(),
            'price':$("#price").val(),
            'shipping':$("#shipping").val(),
            'status':$("#status").val()
        },
        success:function(value){
            if(value=='error'){
                $("#espere_info").html("ERROR AL GUARDAR CAMBIOS INTENTE MÁS TARDE");
            }else{
                getUpdate('info',id_global,true);
                getUpdate('view',id_global,false);
            }
        }
    })
}
function delete_img_view_all(){
    if(confirm("Esta seguro de eliminar todas las imagenes")){
        $("#espere_img").html("ELIMINANDO TODAS LAS IMAGENES...");
        $.ajax({
            url:"../controllers/products.php?v=delete-img&op=all",
            method:"post",
            data:{
                'id':id_global
            },
            success:function(value){
                if(value=='error'){
                    $("#espere_img").html("ERROR AL ELIMINAR INTENTE MÁS TARDE");
                }else{
                    $("#content-img").html("");
                    $("#espere_img").html("");
                    getUpdate('view',id_global,false);
                }
            }
        })
    }
}
function delete_img_view(id,op){
    if(confirm("Esta seguro de eliminar la imagen")){
        $("#espere_img").html("ELIMINANDO IMAGEN...");
        $.ajax({
            url:"../controllers/products.php?v=delete-img&op="+op,
            method:"post",
            data:{
                'id':id
            },
            success:function(value){
                if(value=='error'){
                    $("#espere_img").html("ERROR AL ELIMINAR INTENTE MÁS TARDE");
                }else{
                    modal("img_view"+op);
                    $("#espere_img").html("");
                    getUpdate('view',id_global,false);
                }
            }
        })
    }
}
function cambiar_portada(id,img){
    $("#espere_img").html("CAMBIANDO PORTADA...");
    $.ajax({
        url:"../controllers/products.php?v=portada",
        method:"post",
        data:{
            'id':id,
            'img':img
        },
        success:function(value){
            if(value=='error'){
                $("#espere_img").html("ERROR AL CAMBIAR PORTADA INTENTE MÁS TARDE");
            }else{
                getUpdate('img',id_global,false);
                modal('modal-img-sele');
                getUpdate('view',id_global,false);
            }
        }
    })
}
function add_img_view(){
    $("#espere_img").html("SUBIENDO IMAGENES...");
    if($("#img").val()==''){
        $("#espere_img").html("ELIGE IMAGENES");
    }else{
        $("#btn").css("opacity","0.8");
        $("#btn").attr("disabled");
        var num=$('#img')[0].files.length;
        var num2=0;
        $("#carga").val(0);
        $("#num").html("");
        var contador=1;
        $("#num").html(contador+" / "+num+" --- 0%");
        for(var i=0; i<num; i++){
            num2++;
            if($("#carga").val()==0){
                $("#img").upload('../controllers/products.php?v=add-img',
                {
                    'id':id_global,
                    'img[]':$('#img')[0].files[i]
                },
                function(value){
                    //Subida finalizada.
                    if(value=='valido'){
                        alert("ARCHIVO NO VALIDO "+contador);
                    }
                    if(value=='error'){
                        $("#espere_img").html("ERROR AL SUBIR IMAGEN "+contador);
                    }
                    $("#carga").val(0);
                    $("#num").html("");
                    getUpdate('img',id_global,false);
                    getUpdate('view',id_global,false);
                },
                function(progress,value){
                    //Barra de progreso.
                    $("#carga").val(value);
                    $("#num").html(contador+" / "+num+" --- "+value+"%");
                    if(value==100){
                        contador++;
                    }
                });
            }
        }
    }
}
function add_color_view(){
    $("#espere_color").html("AÑADIENDO COLORES...");
    var num=$("#num_color").val();
    var colores="";
    for(var a=1; a<=num; a++){
        colores+=$("#color"+a).val()+"&";
    }
    $.ajax({
        url:"../controllers/products.php?v=add-color",
        method:"post",
        data:{
            'id':id_global,
            'color':colores
        },
        success:function(value){
            if(value=='error'){
                $("#espere_color").html("ERROR AL CAMBIAR COLORES INTENTE MÁS TARDE");
            }else{
                getUpdate('color',id_global,false);
                getUpdate('view',id_global,false);
            }
        }
    })
}
function delete_color(color){
    $("#espere_color").html("ELIMINANDO COLOR...");
    $.ajax({
        url:"../controllers/products.php?v=delete-color",
        method:"post",
        data:{
            'id':id_global,
            'color':color
        },
        success:function(value){
            if(value=='error'){
                $("#espere_color").html("ERROR AL CAMBIAR COLORES INTENTE MÁS TARDE");
            }else{
                getUpdate('color',id_global,false);
                getUpdate('view',id_global,false);
            }
        }
    })
}
function add_file_view(){
    $("#espere_file").html("SUBIENDO ARCHIVO...");
    if($("#file_view").val()==''){
        $("#espere_file").html("ELIGE UN ARCHIVO");
    }else{
        $("#file_view").upload('../controllers/products.php?v=file',
        {
            'id':id_global,
            'file':$('#file_view')[0].files[0]
        },
        function(value){
            //Subida finalizada.
            $("#carga_view").val(0);
                if(value==1 || value!="error"){
                    $("#btn").css("opacity","1");
                    $("#btn").removeAttr("disabled");
                    getUpdate('file',id_global,false);
                    getUpdate('view',id_global,false);
                }else{
                    $("#espere_file").html("ERROR AL REGISTRAR PRODUCTO INTENTE MÁS TARDE");
                    $("#btn").css("opacity","1");
                    $("#btn").removeAttr("disabled");
                }
            },
        function(progress,value) {
            //Barra de progreso.
            $("#espere_file").html("SUBIENDO ARCHIVO...   "+value+"%");
            $("#carga_view").val(value);
        });
    }
}
function delete_opinion(id){
    if(confirm("Esta seguro de eliminar la opinon")){
        $.ajax({
            url:"../controllers/products.php?v=delete-opinion",
            method:"post",
            data:{
                'id':id
            },
            success:function(value){
                if(value=='error'){
                    alert("ERROR AL ELIMINAR OPINION INTENTE MÁS TARDE");
                }else{
                    modal('opinion'+id);
                }
            }
        })
    }
}
function info_answer(id){
    $.ajax({
        url:"../controllers/products.php?v=info-answer",
        method:"post",
        data:{
            'id':id
        },
        success:function(value){
            if(value=='error'){
                alert("ERROR AL RESPONDER INTENTE MÁS TARDE");
            }else{
                var json=eval("("+value+")");
                modal('modal-info-answer');
                $("#text-question").html(json.question);
                if(json.answer=="" || json.answer=="No hay respuesta"){
                    $("#text-answer").val("");
                }else{
                    $("#text-answer").val(json.answer);
                }
                $("#content-btn-answer").html("<input type='button' class='btn' id='btn' value='CERRAR' onclick=\"modal('modal-info-answer')\">"+json.btn_answer);
            }
        }
    })
}
function add_answer(id){
    $.ajax({
        url:"../controllers/products.php?v=add-answer",
        method:"post",
        data:{
            'id':id,
            'answer':$("#text-answer").val()
        },
        success:function(value){
            if(value=='error'){
                alert("ERROR AL ACTUALIZAR RESPONDER INTENTE MÁS TARDE");
            }else{
                info_answer(id);
                if($("#text-answer").val()==""){
                    $("#btn-respond").html("RESPONDER");
                }else{
                    $("#btn-respond").html("ACTUALIZAR RESPUESTA");
                }
            }
        }
    })
}
function delete_question(id){
    if(confirm("Esta seguro de eliminar la pregunta")){
        $.ajax({
            url:"../controllers/products.php?v=delete-question",
            method:"post",
            data:{
                'id':id
            },
            success:function(value){
                if(value=='error'){
                    alert("ERROR AL ELIMINAR PREGUNTA INTENTE MÁS TARDE");
                }else{
                    modal('question'+id);
                }
            }
        })
    }
}