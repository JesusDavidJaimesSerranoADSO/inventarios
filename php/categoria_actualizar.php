<?php

    require("main.php");

    $id =($_POST['categoria_id']);
    $id = limpiar_cadena($id);
    
    $check_categoria = conexion();
    $check_categoria = $check_categoria->query("SELECT * FROM categoria WHERE categoria_id=$id");


    if( $check_categoria -> rowCount() <=0 ){
        echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria no se encuentra registrada
                </div>';
        exit();
    }else{
        $datos = $check_categoria -> fetch();
    }
    $check_categoria = null;

    $nombre = limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

    if($nombre==""){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado los espacios solicitados
        </div>';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El nombre no concide con los datos solicitadso
        </div>';
        exit();
    }

    if($ubicacion != ""){
        if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No has llenado los espacios solicitados
            </div>';
            exit();
        }
    }

    if($nombre != ""){
        $check_nombre = conexion();
        $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre ='$nombre'");

        if( $check_nombre -> rowCount() == 1 ){
            echo'<div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La categoria ya se encuentra registrada
                    </div>';
            exit();
        }
    }
    $check_nombre = null;

    $check_actualizar = conexion();
    $check_actualizar = $check_actualizar -> prepare("UPDATE categoria SET categoria_nombre=:nombre, categoria_ubicacio=:ubicacion WHERE categoria_id=:id");
    $marcador=[
        ":nombre" => $nombre,
        ":ubicacion" => $ubicacion,
        ":id" => $id
    ];

    if($check_actualizar->execute($marcador)){
        echo'<div class="notification is-info is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria no se pudo actualizar
            </div>';

    }else{
        echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria no se pudo actualizar
            </div>';
    }

    $check_actualizar = null


?>