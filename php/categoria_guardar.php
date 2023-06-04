<?php 

    require_once("main.php");

    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);

    if($nombre == "" ){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El nombre no coincide con los datos solicitados
        </div>';    
        exit();
    }

    if($ubicacion != ""){
        if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}", $ubicacion)){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La ubicacion no coincide con los datos solicitados
            </div>';    
            exit();
        }
    }

    // verificando nombre 

    $check_nombre=conexion();
    $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");
    if($check_nombre->rowCount() > 0){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El nombre ya se encuentra registrado 
        </div>';    
        exit();
    }
    $check_nombre = null;

    $guardar_categoria=conexion();
    $guardar_categoria= $guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacio) VALUES(:nombre,:ubicacion)");
    $marcadores=[
        ":nombre" => $nombre,
        ":ubicacion" => $ubicacion
    ];
    $guardar_categoria->execute($marcadores);
    if($guardar_categoria->rowCount() == 1){
        echo'<div class="notification is-info is-light">
        <strong>¡categoria registrada!</strong><br>
        Categoria cargada exitosamente
        </div>'; 
    }else{
        echo'<div class="notification is-info is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No fue posible registrar la categoria
        </div>'; 
    }
    $guardar_categoria=null;


?>