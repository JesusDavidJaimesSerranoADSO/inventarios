<?php

    require_once("main.php");

    $id = limpiar_cadena($_POST['img_del_id']);

    $check_img = conexion();
    $check_img = $check_img ->query("SELECT * FROM producto WHERE producto_id='$id'");

    if($check_img -> rowCount() == 1){
        $datos = $check_img -> fetch();
    }else{
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La imagen de el producto no se encuentra registrada
        </div>';
        exit();
    }
    $check_img = null;

    $img_dir = "../img/productos/";

    chmod($img_dir,0777);

    if(is_file($img_dir.$datos['producto_foto'])){
        chmod($img_dir.$datos['producto_foto'],0777);

        if(!unlink($img_dir.$datos['producto_foto'])){
            echo'<div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La imagen no pudo eliminarse
                </div>';
            exit();
        }
        
    }

    $actualizar_foto = conexion();
    $actualizar_foto = $actualizar_foto -> prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id");

    if($actualizar_foto -> execute([":foto" => "", ":id" => $id])){
        echo'<div class="notification is-info is-light">
            <strong>¡IMAGEN ELIMINADA!</strong><br>
            La imagen eliminada con exito

            <p class="has-text-centered pt-5 pb-5">moda
            <a href ="index.php?vista=producto_img&producto_id_up='.$id.'" class="button is-link is-rounded" >aceptar </a>
        </p>
        </div>';
        exit();

    }else{
        echo'<div class="notification is-warning is-light">
            <strong>¡IMAGEN ELIMINADA!</strong><br>
            no se pudo actualizar, imagen imagen eliminada

            <p class="has-text-centered pt-5 pb-5">moda
                <a href ="index.php?vista=producto_img&producto_id_up='.$id.'" class="button is-link is-rounded" >aceptar </a>
            </p>
        </div>';
        exit();
    }

    