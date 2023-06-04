<?php 
    require_once("main.php");

    //almacenando datos 

    $nombre = limpiar_cadena($_POST['usuario_nombre']);
    $apellido = limpiar_cadena($_POST["usuario_apellido"]);
    $usuario = limpiar_cadena($_POST["usuario_usuario"]);
    $email = limpiar_cadena($_POST["usuario_email"]);
    $clave1 = limpiar_cadena($_POST["usuario_clave_1"]);
    $clave2 = limpiar_cadena($_POST["usuario_clave_2"]);
    
    // verificando campos obligatorios

    if(empty($nombre) || empty($apellido) || empty($usuario) || empty($clave1) || empty($clave2)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }
    

    // verificacion de la integridad de los datos

    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El nombre no coincide con los datos solicitados
        </div>';    
        exit();
    }

    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El apellido no coincide con los datos solicitados
        </div>';    
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El usuario no coincide con los datos solicitados
        </div>';    
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9$@.-]{4,100}",$clave1) || verificar_datos("[a-zA-Z0-9$@.-]{4,100}",$clave2)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La clave no coincide con los datos solicitados
        </div>';    
        exit();
    }

    // verificacion del email

    if(!empty($email)){
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            $check_email = conexion();
            $check_email = $check_email ->query("SELECT usuario_email FROM `usuario` WHERE usuario_email='$email'"); 
            if($check_email-> rowCount()>0){
                echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El email ya esta registrado
                </div>';    
                exit();
            }
            $check_email = null;
        }else{
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El email no coincide con los datos solicitados
            </div>';    
            exit();
        }
    }


    // verificando usuario

    $check_usuario = conexion();
    $check_usuario = $check_usuario ->query("SELECT usuario_usuario FROM `usuario` WHERE usuario_usuario='$usuario'"); 
    if($check_usuario-> rowCount()>0){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El usuario ya esta registrado
        </div>';    
        exit();
    }
    $check_usuario = null;

    // verificar clave

    if( $clave1 != $clave2 ){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Las claves no coincideo
        </div>';    
        exit();
    }else{
        $clave = password_hash($clave1, PASSWORD_BCRYPT,["cost"=>10]);
    }

    // guardando datos

    $guardar_usuario = conexion();
    $guardar_usuario = $guardar_usuario ->prepare("INSERT INTO usuario(usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email) VALUES(:nombre,:apellido,:usuario,:clave,:email)"); 
    $marcador=[
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":usuario" => $usuario,
        ":clave" => $clave,
        ":email" => $email
    ];

    $guardar_usuario-> execute( $marcador);

    if($guardar_usuario -> rowCount() == 1){
        echo'<div class="notification is-info is-light">
        <strong>¡USUARIO REGISTRADO!</strong><br>
        Registro realizado con exito
        </div>'; 
    }else{
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No se pudo realizar el registro
        </div>'; 
    }
    $guardar_usuario=null;
    // $_GET['vista']="user_new";
?> 