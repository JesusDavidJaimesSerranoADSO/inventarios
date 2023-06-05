<?php  

    require("main.php");

    $producto_id = limpiar_cadena($_POST['img_up_id']);
    $img_dir = "../img/productos/";

    $check_foto  = conexion();
    $check_foto = $check_foto ->query("SELECT * FROM producto WHERE producto_id ='$producto_id'");

    if($check_foto -> rowCount() == 1){
        $datos = $check_foto -> fetch();
    }else{
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La imagen no existe en el sistema
        </div>';
        exit();
    }
    $producto_id = null;
    if($_FILES["producto_foto"]["name"] == "" || $_FILES["producto_foto"]["size"] == 0){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has selexionado una imagen valida
        </div>';
        exit();
    }


    if(!file_exists( $img_dir)){
        if(!chmod($img_dir,0777)){
            echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No se ha podido crear el directorio
        </div>';
        exit();
        }
        
    }

    chmod($img_dir,0777);


    if(mime_content_type($_FILES["producto_foto"]["tmp_name"]) != "image/jpeg" && mime_content_type($_FILES["producto_foto"]["tmp_name"]) != "image/png"){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La imagen no esta en un formato valido
        </div>';
        exit();
    }

    if(($_FILES["producto_foto"]["size"]/1024) > 3070 ){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La imagen no tiene un peso valido
        </div>';
        exit();
    }

    switch(mime_content_type($_FILES["producto_foto"]["tmp_name"])){
        case 'image/jpeg':
            $img_ext = ".jpg";
            break;
        case'image/png':
            $img_ext = ".png";
            break;
    }


    $img_foto = renombrar_fotos($datos['producto_nombre']);

    $foto =$img_foto.$img_ext;

    if(!move_uploaded_file($_FILES["producto_foto"]["tmp_name"],$img_dir.$foto)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No podemos subir la imagen al sistema
        </div>';
        exit();
    }

    if(is_file($img_dir.$datos['producto_foto']) && $datos['producto_foto'] =! $foto){
        chmod($img_dir.$datos['producto_foto'],0777);
        unlink($img_dir.$datos['producto_foto']);
    }

    $actualizar_foto = conexion();
    $actualizar_foto = $actualizar_foto -> prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id");
    $marcador=[
        ":foto" => $foto, 
        ":id" => $producto_id
    ];

    echo $foto;

    if($actualizar_foto -> execute([":foto" => $foto, ":id" => $producto_id])){
        echo'<div class="notification is-info is-light">
        <strong>¡Producto registrado!</strong><br>
        Producto registrado con exicto
        </div>';

    }else{
        if(is_file($img_dir.$foto)){
            chmod($img_dir.$foto,0777);
            unlink($img_dir.$foto);
        }
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No fue posible registrar el producto
        </div>';
    }
    $actualizar_foto = null;
?>