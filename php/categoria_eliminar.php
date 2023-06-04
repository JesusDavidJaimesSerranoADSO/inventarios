<?php

    $category_id_del = limpiar_cadena($_GET['category_id_del']);

    $check_categoria = conexion();
    $check_categoria = $check_categoria -> query("SELECT * FROM categoria WHERE categoria_id ='$category_id_del'");

    if($check_categoria -> rowCount() == 1){

        $check_producto = conexion();
        $check_producto = $check_producto -> query("SELECT categoria_id FROM producto WHERE categoria_id ='$category_id_del' LIMIT 1");

        if($check_producto -> rowCount() <= 0){

            $eliminar_categoria = conexion();
            $eliminar_categoria = $eliminar_categoria ->prepare("DELETE FROM categoria WHERE categoria_id =:id");
            $eliminar_categoria -> execute([":id" => $category_id_del]);

            if($eliminar_categoria -> rowCount() == 1){
                echo'<div class="notification is-info is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria eliminada con exito
                </div>';

            }else{
                echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria no se puede eliminar
                </div>';
            }

            $eliminar_categoria = null;

        }else{
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La categoria no se puede eliminar
            </div>';
            exit();
        }
        $check_producto = null;

    }else{
        echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La categoria no existe
            </div>';
            exit();
    }
    $check_categoria = null;

?>