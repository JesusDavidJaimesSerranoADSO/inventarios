<?php

    $user_id_de=limpiar_cadena($_GET['user_id_del']);

    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_id FROM `usuario` WHERE usuario_id='$user_id_de'");

    if($check_usuario->rowCount()==1){
        $check_productos=conexion();
        $check_productos=$check_productos->query("SELECT usuario_id FROM `producto` WHERE usuario_id='$user_id_de' LIMIT 1");

        if($check_productos->rowCount()<=0){
            $eliminar_usuario=conexion();
            $eliminar_usuario=$eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id=:id");
            $eliminar_usuario->execute([":id" => $user_id_de]);

            if($check_usuario->rowCount()==1){
                echo'<div class="notification is-info is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Usuario eliminado exictosamente
                </div>';
                
            }else{
                echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                no se pudo eliminar el usuario intente de nuevo
                </div>';
            }
            
            $eliminar_usuario=null;
        }else{
            echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El usuario no puede eliminarse porque tiene productos eliminados
        </div>';
        }
        $check_productos=null;

    }else{
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El termino de busqueda no coincide con el formato solicitado
        </div>';
    }
    $check_usuario=null;
?>