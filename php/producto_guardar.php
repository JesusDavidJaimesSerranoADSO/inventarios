<?php

    require_once("../inc/session_start.php");
    require_once("main.php");

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
    
// ----------------------------------------------------------
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

    // ----------------------------------------------------------
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

    // directorio de imagenes

    $img_dir = "../img/productos/";

    // comprobar imagen 


    if($_FILES['producto_foto']['name'] !="" && $_FILES['producto_foto']['size'] >0){

        if(!file_exists($img_dir)){
            if(!mkdir($img_dir,0777)){
                echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                ERROR al crear el directorio
                </div>';
                exit();
            }
        }

        if(mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" || mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg"){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La imagen no tiene un formato permitido
            </div>';
            exit();
        }

        if(($_FILES['producto_foto']['size']/1024) > 3072 ){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La imagen ha exedidio el peso permitido
            </div>';
            exit();
        }

        switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
            case'image/jpeg':
                $img_ext = '.jpg';
                break;

            case'image/png':
                $img_ext = '.png';
                break;
        }

        $img = renombrar_fotos($nombre);
        $foto = $img.$img_ext;

        // moviendo imagenes al directorio
        if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir.$foto)){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No fue posible cargar la imagen
            </div>';
            exit();
        }
    }else{
        $foto = "";
    }

    $gusrdar = conexion();
    $gusrdar = $gusrdar -> prepare("INSERT INTO producto (producto_codigo, producto_nombre, producto_precio, producto_stock, producto_foto, categoria_id, usuario_id) VALUES (:codigo, :nombre, :precio, :stock, :foto, :categoria_id, :usuario_id)");
    $marcadores = [
        ":codigo" => $codigo,
        ":nombre" => $nombre,
        ":precio" => $precio,
        ":stock" => $stock,
        ":foto" => $foto,
        ":categoria_id" => $categoria,
        ":usuario_id" => $_SESSION['id']
    ];

    $gusrdar -> execute($marcadores);

    if($gusrdar -> rowCount() == 1){
        echo'<div class="notification is-info is-light">
            <strong>¡Producto registrado!</strong><br>
            Producto registrado con exicto
            </div>';
        exit();

    }else{
        if(is_file($img_dir.$foto)){
            chmod($img_dir.$foto, 0777);
            unlink($img_dir.$foto);
        }
        echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No fue posible registrar el producto
            </div>';
        exit();
    }
    $gusrdar = null;

?>