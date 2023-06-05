<?php 

    $categoria = limpiar_cadena($_GET['producto_id_up']);

    $check_producto = conexion();
    $check_producto =  $check_producto -> query("SELECT * FROM producto where producto_id = '$categoria'");

    if($check_producto -> rowCount() == 1){
        $datos = $check_producto -> fetch();

        $eliminar_producto = conexion();
        $eliminar_producto =  $eliminar_producto -> prepare("DELETE FROM producto WHERE producto_id=:id");
        $eliminar_producto -> execute([":id" => $categoria]);

        if($eliminar_producto -> rowCount() == 1){
            if(is_file("./img/productos/".$datos['producto_foto'])){
                chmod("./img/productos/".$datos['producto_foto'],0777);
                unlink("./img/productos/".$datos['producto_foto']);
            }
            echo'<div class="notification is-danger is-light">
            <strong>¡Producto eliminado!</strong><br>
            el producto se elimino con exito
            </div>';
    exit();
        }else{
            echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                el producto no se pudo eliminar
            </div>';
            exit();
        }
        $eliminar_producto = null;

    }else{
        echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                el producto no exixte
            </div>';
        exit();
    }
    $check_producto = null;


?>