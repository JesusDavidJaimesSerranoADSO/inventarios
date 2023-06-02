    <?php 
    require_once("./inc/session_start.php");
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php  include("./inc/head.php");?>
</head>
<body>
    <?php 
        if(empty($_GET['vista'])){
            $_GET['vista']="login";
        }

        if(is_file("./vista/".$_GET['vista'].".php" ) && $_GET['vista']!="login" && $_GET['vista']!="404"){
            
            if((empty($_SESSION['id'])) || (empty($_SESSION['usuario']))){

                // require("./vista/logout.php");
                // exit();

            }

            include("./inc/navbar.php");
            include("./vista/".$_GET['vista'].".php");
            include("./inc/script.php");

        }else{
            if($_GET['vista'] == "login"){
                include("./vista/login.php");
            }
            else{
                include("./vista/404.php");
            }
        }
         ?>
</body>
</html>