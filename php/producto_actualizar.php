<?php
    require_once("main.php"); 

    $id = limpiar_cadena($_POST['producto_id']);

    $check_producto = conexion();
    $check_producto = $check_producto -> query("SELECT * FROM producto WHERE producto_id = '$id'");

    if($check_producto -> rowCount() <= 0){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El producto no se encuentra registrada
        </div>';
        exit();
    }else{
        $datos = $check_producto -> fetch();
    }
    $check_producto = null;

    $codigo = limpiar_cadena($_POST['producto_codigo']);
    $nombre = limpiar_cadena($_POST['producto_nombre']);
    $precio = limpiar_cadena($_POST['producto_precio']);
    $stock = limpiar_cadena($_POST['producto_stock']);
    $categoria = limpiar_cadena($_POST['producto_categoria']);

    if($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == ""){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Los datos no coinciden con el formato solicitado 
        </div>';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Los datos no coinciden con el formato solicitado 
        </div>';
        exit();
    }

    if(verificar_datos("[0-9.]{1,25}",$precio)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Los datos no coinciden con el formato solicitado 
        </div>';
        exit();
    }

    if(verificar_datos("[0-9.]{1,25}",$stock)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Los datos no coinciden con el formato solicitado 
        </div>';
        exit();
    }

    if($codigo != $datos['producto_codigo']){
        $check_codigo = conexion();
        $check_codigo  = $check_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo = '$codigo'");
    
        if($check_codigo->rowCount()>0){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El codigo ya se encuentra registrado 
            </div>';
            exit();
        }
        $check_codigo = null;
    }
    
// ----------------------------------------------------------
if($nombre != $datos['producto_nombre']){
    $check_nombre = conexion();
    $check_nombre  = $check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre = '$nombre'");

    if($check_nombre->rowCount()>0){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El nombre ya se encuentra registrado 
        </div>';
        exit();
    }
    $check_nombre = null;
}

if($categoria != $datos['categoria_id']){
    $check_categoria = conexion();
    $check_categoria  = $check_categoria->query("SELECT categoria_id  FROM categoria WHERE categoria_id ='$categoria'");

    if($check_categoria->rowCount() <= 0){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La categoria selexionada no existe
        </div>';
        exit();
    }
    $check_categoria = null;
}

$actualizar = conexion();
    $actualizar = $actualizar -> prepare("UPDATE producto SET producto_codigo=:codigo, producto_nombre=:nombre, producto_precio=:precio, producto_stock=:stock, categoria_id=:categoria_id where producto_id=:id");
    $marcadores = [
        ":codigo" => $codigo,
        ":nombre" => $nombre,
        ":precio" => $precio,
        ":stock" => $stock,
        ":categoria_id" => $categoria,
        "id" => $id
    ];

    if($actualizar -> execute($marcadores)){
        echo'<div class="notification is-info is-light">
            <strong>¡Producto registrado!</strong><br>
            Producto Actualizado con exito con exicto
            </div>';
        exit();

    }else{
        echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No fue posible registrar el producto
            </div>';
        exit();
    }

?>