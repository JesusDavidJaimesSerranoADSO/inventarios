<?php 

    require_once("../inc/session_start.php");
    require_once("main.php");

    $id = limpiar_cadena($_POST['usuario_id']);

    $check_usuario = conexion();
    $check_usuario = $check_usuario -> query("SELECT * FROM usuario WHERE usuario_id='$id'");

    if($check_usuario -> rowCount() <= 0){
        echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                el usuario no exisite en el sistema
                </div>';
        exit();

    }else{
        $datos = $check_usuario -> fetch();
    }

    $check_usuario = null;

    $admin_usuario = limpiar_cadena($_POST['administrador_usuario']);
    $admin_clave = limpiar_cadena($_POST['administrador_clave']);

    if(empty($admin_usuario) || empty($admin_clave) ){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios USUARIO Y CLAVE
    </div>';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Su usuario no coincide col los datos solicitados
        </div>';    
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9]{4,20}",$admin_clave)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        Su usuario no coincide col los datos solicitados
        </div>';    
        exit();
    }

    $ckeck_admin = conexion();
    $ckeck_admin = $ckeck_admin -> query("SELECT usuario_usuario, usuario_clave FROM usuario WHERE usuario_usuario ='".$admin_usuario."' AND usuario_id ='".$_SESSION['id']."'");
    

    if($ckeck_admin -> rowCount() == 1){
        $ckeck_admin = $ckeck_admin -> fetch();

        if($ckeck_admin['usuario_usuario'] != $admin_usuario  || !password_verify($admin_clave,$ckeck_admin['usuario_clave'])){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            USUARIO o CLAVE incorrectos
            </div>';    
            exit();

        }

    }else{
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        USUARIO o CLAVE incorrectos
        </div>';    
        exit();
    }
    $ckeck_admin = null;

    $nombre = limpiar_cadena($_POST['usuario_nombre']);
    $apellido = limpiar_cadena($_POST["usuario_apellido"]);
    $usuario = limpiar_cadena($_POST["usuario_usuario"]);
    $email = limpiar_cadena($_POST["usuario_email"]);
    $clave1 = limpiar_cadena($_POST["usuario_clave_1"]);
    $clave2 = limpiar_cadena($_POST["usuario_clave_2"]);

    if(empty($nombre) || empty($apellido) || empty($usuario)){
        echo'<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

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

?>