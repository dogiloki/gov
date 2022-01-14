function modal2(op){
    $(".user").css("background","none");
    $("#"+op).css("background","#666");
    var x = window.event.clientX;
    var y = window.event.clientY;
    $(".content-option").css("top",y+"px");
    $(".content-option").css("left",x+"px");
    if(document.getElementById("modal-option"+op).style.display==''){
        document.getElementById("modal-option"+op).style.display='none';
        $(".user").css("background","none");
    }else{
        document.getElementById("modal-option"+op).style.display='';
    }
}
var id_global="";
function getUpdate(op,id){
    if(id!=""){
        id_global=id;
    }
    $.ajax({
        url:"controllers/users.php?v=get-update&op="+op,
        method:"post",
        data:{
            'id':id_global
        },
        success:function(value){
            var json=eval("("+value+")");
            if(op=='user'){
                $("#img-user"+id_global).attr("src","../"+json.img_user);
                $("#name-user"+id_global).html(json.name_user);
            }
            if(op=='opinion'){
                $("#content-opinions").html(json.opinion);
            }
            if(op=='question'){
                $("#content-questions").html(json.question);
            }
            if(op=='compras'){
                $("#content-compras").html(json.compras);
            }
            if(op=='mas'){
                $("#content-mas").html(json.mas);
            }
            modal('modal-'+op);
        }
    })
}
function delete_opinion(id){
    if(confirm("Esta seguro de eliminar la opinon")){
        $.ajax({
            url:"controllers/users.php?v=delete-opinion",
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
        url:"controllers/users.php?v=info-answer",
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
        url:"controllers/users.php?v=add-answer",
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
                    $("#btn-respond"+id).html("RESPONDER");
                }else{
                    $("#btn-respond"+id).html("ACTUALIZAR RESPUESTA");
                }
            }
        }
    })
}
function delete_question(id){
    if(confirm("Esta seguro de eliminar la pregunta")){
        $.ajax({
            url:"controllers/users.php?v=delete-question",
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
function update_mas(campo,op){
    $.ajax({
        url:"controllers/users.php?v="+campo,
        method:"post",
        data:{
            'id':id_global,
            'op':op
        },
        success:function(value){
            if(value=='error'){
                alert("ERROR AL MODIFICAR USUARIO INTENTE MÁS TARDE");
            }else{
                $("#btn-"+campo+"0").css("background","none");
                $("#btn-"+campo+"1").css("background","none");
                if(op=='1'){
                    $("#btn-"+campo+op).css("background","#2e82ff");
                }else{
                    $("#btn-"+campo+op).css("background","#ff6d5c");
                }
            }
        }
    })
}