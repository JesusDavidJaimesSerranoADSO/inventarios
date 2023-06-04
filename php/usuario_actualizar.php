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

    // verificar email

    if($email!="" && $email != $datos['usuario_email']){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $check_email = conexion();
            $check_email = $check_email ->query("SELECT usuario_email FROM usuario WHERE usuario_email ='$email'");
    
            if($check_email->rowCount()> 0){
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

    // verificar usuario

    if($usuario!="" && $usuario != $datos['usuario_usuario']){
        $check_usuario = conexion();
        $check_usuario = $check_usuario ->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario ='$usuuario'");

        if($check_usuario->rowCount()> 0){
            echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario ya esta registrado
                </div>';    
                exit();

        }
        $check_usuario = null;
    }

    if($clave1 != "" || $clave2!=""){
        if(verificar_datos("[a-zA-Z0-9$@.-]{4,100}",$clave1) || verificar_datos("[a-zA-Z0-9$@.-]{4,100}",$clave2)){
            echo'<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las claves no coincideo
            </div>';    
            exit();
        }else{
            if($clave1 != $clave2){
                echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La clave no coincide con los datos solicitados
                </div>';    
                exit();
            }else{
                $clave1 = password_hash($clave1,PASSWORD_BCRYPT,['cont' => 10]);
            }
        }
    }else{
        $clave1 = $datos['usuario_clave'];
    }

    // actualizar usuario 

    $actualizar_usuario = conexion();
    $actualizar_usuario = $actualizar_usuario -> prepare("UPDATE usuario SET usuario_nombre=:nombre, usuario_apellido=:apellido, usuario_usuario=:usuario, usuario_clave=:clave, usuario_email=:email WHERE usuario_id=:id");
    $marcador = [
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":usuario" => $usuario,
        ":clave" => $clave1,
        ":email" => $email,
        ":id" => $id
    ];

    if($actualizar_usuario->execute($marcador)){
        echo'<div class="notification is-info is-light">
        <strong>¡USUARIO REGISTRADO!</strong><br>
        Registro realizado con exito
        </div>'; 
    }else{
        echo'<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No fue posible actualizarse 
                </div>';
    }
    $actualizar_usuario=null
?>