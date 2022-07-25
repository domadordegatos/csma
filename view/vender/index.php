<?php require_once "../home/navbar.php" ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../model/libraries/lib.php"; ?>
    <title>VENTAS CSMA</title>
    <link rel="icon" type="image/png" href="../media/recursos/ico.png" />
</head>
<body onload="cancelar_compra()">

    <div class="container-x d-flex mt-4">
        <div class="contenedor1" style="width: 30%; margin-left: 3rem; margin-right: 3rem;">
            <div class="row my-3">
                <div class="col-sm-6 border border-white d-flex justify-content-center align-items-center" style="border-top-left-radius: 20px; border-bottom-left-radius: 20px;">
                    <img src="../media/recursos/profile.png" id="u_img" width="95%" alt="">
                </div>
                <div class="col-sm-6">
                    <div class="row border border-white p-1" style="border-top-right-radius: 20px;">
                        <div class="col-sm-12">
                            <label class="text-white">Nombre: <input type="text" disabled id="u_name" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1">
                        <div class="col-sm-12">
                            <label class="text-white" id="prueba">Apellido: <input type="text" disabled id="u_last_name" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1">
                        <div class="col-sm-12">
                            <label class="text-white">Grado: <input type="text" disabled id="u_grade" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1">
                        <div class="col-sm-12">
                            <label class="text-white">Id: <input type="text" disabled id="u_id" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1" style="border-bottom-right-radius: 20px;">
                        <div class="col-sm-12">
                            <label class="text-white">Saldo: $<input type="text" disabled id="u_coin" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row w-100">
                <div class="col-sm-12 d-flex justify-content-center pt-0 mt-0">
                    <button class="btn btn-success" onclick="location.reload()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-upc-scan" viewBox="0 0 16 16">
                            <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z"/>
                          </svg>  SCAN
                    </button>
                    <input class="form-control col-sm-5 mt-1 mx-2" autofocus type="text" id="codigo" name="codigo" placeholder="123..">
                </div>
            </div>
            <div class="row mt-5 w-100">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4" style="padding: 0 3px 0 3px;"><button class="btn btn-block btn-lg btn-danger" onclick="cancelar_compra()">Cancelar compra</button></div>
                        <div class="col-sm-4" style="padding: 0 3px 0 3px;"><button class="btn btn-block btn-lg btn-success" onclick="crearventa()">Realizar compra</button></div>
                        <div class="col-sm-4" style="padding: 0 3px 0 3px;"><button class="btn btn-block btn-lg btn-warning" onclick="prestar_dinero()">Prestar dinero</button></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="contenedor2" style="width: 50%;">
            <div class="row w-100">
                <div class="col-sm-12 text-center">
                    <h3 class="text-white">Productos para venta</h3>
                </div>
            </div>
            <div class="row w-100">
                <div class="col-sm-12">
                    <button class="btn btn-warning" onclick="carrito_compras(1)">
                        <img src="../media/recursos/productos/1.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-warning" onclick="carrito_compras(2)">
                        <img src="../media/recursos/productos/2.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-primary" onclick="carrito_compras(3)">
                        <img src="../media/recursos/productos/3.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-primary" onclick="carrito_compras(4)">
                        <img src="../media/recursos/productos/4.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-light" onclick="carrito_compras(7)">
                        <img src="../media/recursos/productos/7.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-light" onclick="carrito_compras(8)">
                        <img src="../media/recursos/productos/8.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-success" onclick="carrito_compras(9)">
                        <img src="../media/recursos/productos/9.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-success" onclick="carrito_compras(10)">
                        <img src="../media/recursos/productos/10.png" alt="" width="80px" height="80px">
                    </button>
                    <!-- <button class="btn btn-dark" onclick="carrito_compras(5)">
                        <img src="../media/recursos/productos/5.png" alt="" width="80px" height="80px">
                    </button> -->
                    <!--<button class="btn btn-dark" onclick="carrito_compras(6)">
                        <img src="../media/recursos/productos/6.png" alt="" width="80px" height="80px">
                    </button>-->
                    <button class="btn btn-outline-light" onclick="carrito_compras(11)">
                        <img src="../media/recursos/productos/11.png" alt="" width="80px" height="80px">
                    </button>
                    <!--<button class="btn btn-outline-light" onclick="carrito_compras(17)">
                        <img src="../media/recursos/productos/17.png" alt="" width="80px" height="80px">
                    </button>-->
                    <!--<button class="btn btn-outline-light" onclick="carrito_compras(12)">
                        <img src="../media/recursos/productos/12.png" alt="" width="80px" height="80px">
                    </button>-->
                    <!--<button class="btn btn-danger" onclick="carrito_compras(13)">
                        <img src="../media/recursos/productos/13.png" alt="" width="80px" height="80px">
                    </button>-->
                    <button class="btn btn-warning" onclick="carrito_compras(18)">
                        <img src="../media/recursos/productos/18.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-warning" onclick="carrito_compras(19)">
                        <img src="../media/recursos/productos/19.png" alt="" width="80px" height="80px">
                    </button>
                    <button class="btn btn-warning" onclick="carrito_compras(20)">
                        <img src="../media/recursos/productos/20.png" alt="" width="80px" height="80px">
                    </button>
                </div>
                <div class="col-sm-12">
                    
                    
                </div>
                <style>
                    .btn{
                        margin: 3px;
                    }
                </style>
            </div>

            <div class="row">
                <div class="col-sm-12 text-center" id="carrito_temp">
                    

                </div>
            </div>

        </div>
    </div>
</body>
</html>

<style>
    body{
        /* background-image: radial-gradient(circle at 84.17% 84.17%, #aff5ff 0, #83daec 25%, #42b9d4 50%, #0099be 75%, #007fae 100%); */
        /* background-repeat: no-repeat;
        height: 100vh; */
        background-image: url(../media/recursos/fondo.png);
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
    }
</style>

<script>

        function quitarproducto(index){
                $.ajax({
                    type:"POST",
                    data:"ind=" +index,
                    url:"./quitar_producto.php",
                    success:function(r){
                        $('#carrito_temp').load("carrito_temp.php");
                        alertify.success("Producto quitado");
                    }
                });
            }

        function prestar_dinero(){
            if($('#codigo').val()==""){
                alertify.error("Debes escanear un codigo");
                return false;
            }
            cadena="form1=" + $('#codigo').val();
                $.ajax({
                    type:"POST",
                    data:cadena,
                    url:"../../controller/crear_venta_prestada.php",
                    success:function(r){
                        if(r > 0){
                            solicitar_informacion();
                            $('#carrito_temp').load("carrito_temp.php");
                            document.getElementById("codigo").value = "";
                            alertify.success("Productos pagados con exito!");
                            setTimeout ("solicitar_informacion();", 2000);
                            $("#codigo").focus();
                            return false;
                        }else if (r == 0) {
                            alertify.error("No haz agregado productos");
                        }else if(r == -1){
                            $('#carrito_temp').load("carrito_temp.php");
                            alertify.error("El usuario no tiene fondos suficientes");
                            return false;
                        }else{
                            $('#carrito_temp').load("carrito_temp.php");
                            alertify.error("Error en el proceso al pagar");
                        }
                    }
                });
        }

        function crearventa(){
            if($('#codigo').val()==""){
                alertify.error("Debes escanear un codigo");
                return false;
            }
            
            cadena="form1=" + $('#codigo').val();
                $.ajax({
                    type:"POST",
                    data:cadena,
                    url:"../../controller/crear_venta.php",
                    success:function(r){
                        if(r > 0){
                            solicitar_informacion();
                            $('#carrito_temp').load("carrito_temp.php");
                            document.getElementById("codigo").value = "";
                            alertify.success("Productos pagados con exito!");
                            setTimeout ("solicitar_informacion();", 2000);
                            $("#codigo").focus();
                            return false;
                        }else if (r == 0) {
                            alertify.error("No haz agregado productos");
                        }else if(r == -1){
                            $('#carrito_temp').load("carrito_temp.php");
                            alertify.error("El usuario no tiene fondos suficientes");
                            return false;
                        } else{
                            $('#carrito_temp').load("carrito_temp.php");
                            alertify.error("Error en el proceso al pagar");
                        }
                    }
                });
            }


        function cancelar_compra(){
            $.ajax({
			url:"./vaciartemp.php",
			success:function(r){
                $('#carrito_temp').load("carrito_temp.php");
                document.getElementById("codigo").value = "";
                solicitar_informacion();
                $("#codigo").focus();
                return false;
			}
		});
        }

        function carrito_compras(idarticulo){ 
			$.ajax({
				type:"POST",
				data:"idart=" + idarticulo,
				url:"../../controller/carrito_temporal.php",
				success:function(r){
					if(r==2){
					alertify.error("Este producto no existe!!");
					$('#carrito_temp').load("carrito_temp.php");
				    }else if(r==1){
				    $('#carrito_temp').load("carrito_temp.php");
			        }
                }
			});
		}

    $(document).ready(function(){
    $("input[name=codigo]").change(function(){
        if($('#codigo').val()=="CFFL53WJN"){
            document.getElementById("codigo").value = "1122334455";
            }
      cadena="form1=" + $('#codigo').val();
            $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#u_name').val(dato['0']);
                $('#u_last_name').val(dato['1']);
                $('#u_grade').val(dato['2']);
                $('#u_id').val(dato['3']);
                $('#u_coin').val(dato['4']);
                $('#u_id_bd').val(dato['6']);
                $("#valor_recarga").focus();
                var id = (dato['6']);
                var dinero = (dato['4']); 
                if(dinero<0){
                    document.getElementById("u_coin").style.backgroundColor = "#FF0000";
                    document.getElementById("u_coin").style.color = "white";
                }else{
                    document.getElementById("u_coin").style.backgroundColor = "";
                    document.getElementById("u_coin").style.color = "";
                }
                
                fetch("../media/fotos_usuarios/"+id+".jpg")
                .then(
                function(response) {
                    if (response.status !== 200) {/* problemas para encontrar la imagen */
                    console.log('problemas encontrando la imagen, error: ' +
                    response.status);
                    $("#u_img").attr("src","../media/recursos/profile.png");
                    return;
                    }else{
                        console.log("encontramos la imagen");
                        $("#u_img").attr("src","../media/fotos_usuarios/"+id+".jpg");
                    }
                }
                )
                .catch(function(err) {
                console.log('Fetch Error :-S', err);
                });
              }
            });
            });
          });

          function solicitar_informacion(){
            cadena="form1=" + $('#codigo').val();
            $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#u_name').val(dato['0']);
                $('#u_last_name').val(dato['1']);
                $('#u_grade').val(dato['2']);
                $('#u_id').val(dato['3']);
                $('#u_coin').val(dato['4']);
                $('#u_id_bd').val(dato['6']);
                var id = (dato['6']);
                var dinero = (dato['4']); 
                if(dinero<0){
                    document.getElementById("u_coin").style.backgroundColor = "#FF0000";
                    document.getElementById("u_coin").style.color = "white";
                }else{
                    document.getElementById("u_coin").style.backgroundColor = "";
                    document.getElementById("u_coin").style.color = "";
                }

                fetch("../media/fotos_usuarios/"+id+".jpg")
                .then(
                function(response) {
                    if (response.status !== 200) {/* problemas para encontrar la imagen */
                    console.log('problemas encontrando la imagen, error: ' +
                    response.status);
                    $("#u_img").attr("src","../media/recursos/profile.png");
                    return;
                    }else{
                        console.log("encontramos la imagen");
                        $("#u_img").attr("src","../media/fotos_usuarios/"+id+".jpg");
                    }
                }
                )
                .catch(function(err) {
                console.log('Fetch Error :-S', err);
                });
              }
            });
          }

</script>