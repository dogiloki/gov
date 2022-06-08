window.onload=function(){
    calclular_cart_fisico();
    calclular_cart_digital();
}
function cart(id_product){
    var location="";
    if($("#btn_"+id_product).html()=='CARRITO' || $("#btn_"+id_product).html()=='Añadir al carrito' || $("#btn_record"+id_product).html()=='CARRITO'){
        if($("#btn_"+id_product).html()=='Añadir al carrito'){
            location='product';
        }
        op='add';
        $("#btn_"+id_product).css("background","#444");
        $("#btn_record"+id_product).css("background","#444");
    }else{
        if($("#btn_"+id_product).html()=='Quitar del carrito'){
            location='product';
        }
        op='remove';
        $("#btn_"+id_product).css("background","#75a800");
        $("#btn_record"+id_product).css("background","#75a800");
    }
    $("#btn_"+id_product).html('ESPERE...');
    $("#btn_record"+id_product).html('ESPERE...');
    $.ajax({
        url:"controllers/products.php?cart="+op,
        method:"post",
        data:{
            'id_product':id_product,
            'location':location
        },
        success:function(value){
            if(value=='login'){
                window.location='login.php';
            }else{
                $("#btn_"+id_product).html(value);
                $("#btn_record"+id_product).html(value);
            }
        }
    })
}
function delete_cart_product(id_product){
    $.ajax({
        url:"controllers/products.php?cart=remove",
        method:"post",
        data:{
            'id_product':id_product,
            'location':''
        },
        success:function(value){
            if(value=='login'){
                window.location='login.php';
            }
            calclular_cart_fisico();
            calclular_cart_digital();
        }
    })
}

//Zoom a la imagen
function imageZoom(imgID,resultID){
    var img, lens, result, cx, cy;
    img=document.getElementById(imgID);
    result=document.getElementById(resultID);
    //Crear lens
    lens=document.createElement("DIV");
    lens.setAttribute("class","img-zoom-lens");
    //Insertar lens
    img.parentElement.insertBefore(lens,img);
    //Calcula el tamaño del lens
    cx=result.offsetWidth/lens.offsetWidth;
    cy=result.offsetHeight/lens.offsetHeight; 
    //Propiedades del DIV
    result.style.backgroundImage="url('"+img.src+"')";
    result.style.backgroundSize=(img.width*cx)+"px "+(img.height*cy)+"px";
    lens.addEventListener("mousemove",moveLens);
    img.addEventListener("mousemove",moveLens);
    lens.addEventListener("touchmove",moveLens);
    img.addEventListener("touchmove",moveLens);

    function moveLens(e){
        var pos, x, y;
        e.preventDefault();
        //Posision del cursor
        pos=getCursorPos(e);
        x=pos.x-(lens.offsetWidth/2);
        y=pos.y-(lens.offsetHeight/2);
        if(x>img.width-lens.offsetWidth){
            x=img.width-lens.offsetWidth;
        }
        if(x<0){
            x=0;
        }
        if(y>img.height-lens.offsetHeight){
            y=img.height-lens.offsetHeight;
        }
        if(y<0){
            y=0;
        }
        lens.style.left=x+"px";
        lens.style.top=y+"px";
        result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
    }

    function getCursorPos(e){
        var a, x=0, y=0;
        e=e || window.event;
        a=img.getBoundingClientRect();
        x=e.pageX-a.left;
        y=e.pageY-a.top;
        x=x-window.pageXOffset;
        y=y-window.pageYOffset;
        return {x:x,y:y};
    }
}

/* Filtado */
function filtro(tipo,op){
    if(tipo=='category'){
        $("#"+tipo+"_filter").val(op);
    }else{
        $("#"+tipo).val(op);
    }
}
/* Cambiar color */
function color(id,color){
    $(".product-color").css("border","none");
    $("#"+id).css("border","3px solid #f5f5f5");
    $("#color_elegido").val(color);
    $("#aviso").html("");
}
function color_cart(id_cart,color){
    $.ajax({
        url:"controllers/products.php?cart_color=cambiar",
        method:"post",
        data:{
            'id_cart':id_cart,
            'color':color
        },
        success:function(value){
            $("#color_elegido"+id_cart).val(color);
            $("#"+id_cart).css("background",color);
            modal("modal"+id_cart);
            calclular_cart_fisico();
            calclular_cart_digital();
        }
    })
}
/* Cambiar cantidad */
function cantidad(op,id_product,id_cart){
    $.ajax({
        url:"controllers/products.php?cantidad="+op,
        method:"post",
        data:{
            'id_product':id_product,
            'canti_actual':$("#cantidad"+id_product).html()
        },
        success:function(value){
            $("#cantidad"+id_product).html(value);
            if(id_cart!=''){
                update_cart(id_cart,id_product);
            }
            calclular_cart_fisico();
            calclular_cart_digital();
        }
    })
}
function calclular_cart_fisico(){
    $.ajax({
        url:"controllers/products.php?cart=calcular&format=Físico",
        method:"post",
        data:{},
        success:function(value){
            if(value=="login"){
                return;
            }
            var json=eval("("+value+")");
            $("#cantidad_productos_fisico").html("Productos añadidos: "+json[0]);
            $("#total_cart_fisico").html("Total a pagar: $"+json[1]);
        }
    })
}
function calclular_cart_digital(){
    $.ajax({
        url:"controllers/products.php?cart=calcular&format=Digital",
        method:"post",
        data:{},
        success:function(value){
            if(value=="login"){
                return;
            }
            var json=eval("("+value+")");
            $("#cantidad_productos_digital").html("Productos añadidos: "+json[0]);
            $("#total_cart_digital").html("Total a pagar: $"+json[1]);
        }
    })
}
function update_cart(id_cart,id_product){
    $.ajax({
        url:"controllers/products.php?cantidad=update",
        method:"post",
        data:{
            'id_cart':id_cart,
            'canti_actual':$("#cantidad"+id_product).html()
        },
        success:function(value){
            calclular_cart_fisico();
            calclular_cart_digital();
        }
    })
}
/* Hacer compra */
function comprar(id_product,format){
    if($("#color_elegido").val()==''){
        window.location="buy.php?id_product="+id_product+"&cantidad="+$("#cantidad"+id_product).html()+"&format="+format;
    }else{
        window.location="buy.php?id_product="+id_product+"&cantidad="+$("#cantidad"+id_product).html()+"&color="+$("#color_elegido").val().replace("#","")+"&format="+format;
    }
}
function comprar_cart(id_product,id_cart,format){
    if($("#color_elegido"+id_cart).val()==''){
        window.location="buy.php?id_product="+id_product+"&cantidad="+$("#cantidad"+id_product).html()+"&format="+format;
    }else{
        window.location="buy.php?id_product="+id_product+"&cantidad="+$("#cantidad"+id_product).html()+"&color="+$("#color_elegido"+id_cart).val().replace("#","")+"&format="+format;
    }
}
/* Opiniones */
function getOpinion(id_buy){
    global_id_buy=id_buy;
    $.ajax({
        url:"controllers/buy.php?v=get_opinion",
        method:"post",
        data:{
            'id_buy':id_buy
        },
        success:function(value){
            var json=eval("("+value+")");
            $("#opinion").val(json[0]);
            var imgs="";
            for(var a=1; a<=5; a++){
                if(a<=json[1]){
                    imgs+="<img src='img/estrella.png' width='30px' height='30px' onclick=\"addStars(\'"+a+"\')\" class='img' id='img_stars"+a+"'>";
                }else{
                    imgs+="<img src='img/estrella_vacia.png' width='30px' height='30px' onclick=\"addStars(\'"+a+"\')\" class='img' id='img_stars"+a+"'>";
                }
            }
            $("#num_stars").val(json[1]);
            $("#content-stars").html(imgs);
            $("#id-buy-opinion").val(id_buy);
            modal('modal-add-opinion');
        }
    })
}
function addOpinion(){
    $.ajax({
        url:"controllers/buy.php?v=add_opinion",
        method:"post",
        data:{
            'id_buy':$("#id-buy-opinion").val(),
            'opinion':$("#opinion").val(),
            'stars':$("#num_stars").val()
        },
        success:function(value){
            
            $("#opinion").val(value);
            modal('modal-add-opinion');
        }
    })
}
function addStars(op){
    for(var a=1; a<=5; a++){
        if(a<=op){
            $("#img_stars"+a).attr("src","img/estrella.png");
        }else{
            $("#img_stars"+a).attr("src","img/estrella_vacia.png");    
        }
    }
    $("#num_stars").val(op);
}