<?php

    $inicio = ($pagina>0) ? (($pagina * $registro) - $registro) : 0;
    $tabla = "";
    $campo = "producto_id, producto_codigo, producto_nombre, producto_precio, producto_stock, producto_foto, categoria_nombre, usuario_nombre, usuario_apellido";


    if(isset($busqueda) && $busqueda != ""){
        $consulta_datos = "SELECT $campo FROM producto INNER JOIN categoria ON categoria.categoria_id=producto.categoria_id INNER JOIN usuario on usuario.usuario_id=producto.usuario_id WHERE producto_nombre LIKE '%$busqueda%' OR producto_codigo LIKE '%$busqueda%' ORDER BY producto_nombre ASC LIMIT $inicio,$registro";
        $consultar_total = "SELECT COUNT(producto_id) FROM producto WHERE producto_nombre LIKE '%$busqueda%' OR producto_codigo LIKE '%$busqueda%'";

    }elseif($categoria_id > 0){
        $consulta_datos = "SELECT $campo FROM producto INNER JOIN categoria ON categoria.categoria_id=producto.categoria_id INNER JOIN usuario on usuario.usuario_id=producto.usuario_id WHERE producto.categoria_id LIKE '%$categoria_id%' ORDER BY producto_nombre ASC LIMIT $inicio,$registro";
        $consultar_total = "SELECT COUNT(producto_id) FROM producto WHERE producto.categoria_id LIKE '%$categoria_id%'";
    
    }else{
        $consulta_datos = "SELECT $campo FROM producto INNER JOIN categoria ON categoria.categoria_id=producto.categoria_id INNER JOIN usuario on usuario.usuario_id=producto.usuario_id ORDER BY producto_nombre ASC LIMIT $inicio,$registro";
        $consultar_total = "SELECT COUNT(producto_id) FROM producto";
    }

    $conexion = conexion();

    $datos = $conexion -> query($consulta_datos);
    $datos = $datos -> fetchAll();

    $total = $conexion -> query($consultar_total);
    $total = (int) $total -> fetchColumn();

    $npaginas = ceil($total / $registro);

    if($total >= 1 && $pagina <= $npaginas){

        $contador =  $inicio + 1;
        $pagina_inicio = $inicio + 1;

        foreach($datos as $opcion){
            $tabla.='
            <article class="media">
                <figure class="media-left">
                <p class="image is-64x64">';
                if(is_file("./img/productos/".$opcion['producto_foto'])){
                    $tabla.='<img src="./img/productos/'.$opcion['producto_foto'].'">';
                }else{
                    $tabla.='<img src="./img/productos/img.jpg">';
                }
                $tabla.='
                </p>
            </figure>
        <div class="media-content">
            <div class="content">
                <p>
                    <strong>'. $contador.' - '.$opcion['producto_nombre'].'</strong><br>
                    <strong>CODIGO:</strong> '.$opcion['producto_codigo'].', 
                    <strong>PRECIO:</strong> $'.$opcion['producto_precio'].', 
                    <strong>STOCK:</strong> '.$opcion['producto_stock'].', 
                    <strong>CATEGORIA:</strong> '.$opcion['categoria_nombre'].', 
                    <strong>REGISTRADO POR:</strong> '.$opcion['usuario_nombre'].' ' .$opcion['usuario_nombre'].'
                </p>
            </div>
            <div class="has-text-right">
                <a href="index.php?vista=producto_img&producto_id_up='.$opcion['producto_id'].'" class="button is-link is-rounded is-small">Imagen</a>
                <a href="index.php?vista=producto_update&producto_id_up='.$opcion['producto_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
                <a href="'.$url.$pagina.'&producto_id_up='.$opcion['producto_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
            </div>
        </div>
    </article>


    <hr>';
        }

        $contador ++;
        $pagina_final = $contador - 1;
    }else{
        if($total >= 1){
            $tabla.='
            <p class="has-text-right">
                <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic acá para recargar el listado
                </a>
            </p>';
        }else{
            $tabla.='
            <p class="has-text-right"> No hay registros en el sistema </p>
            ';
        }
    }

    if($total >= 1 && $pagina <= $npaginas){
        $tabla.='<p class="has-text-right">Mostrando productos <strong>'.$pagina_inicio.'</strong> al <strong>'.$pagina_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
    }
    echo $tabla;
    $conexion = null;

    if($total >= 1 && $pagina <= $npaginas){
        echo paginador_tablas($pagina,$npaginas,$url,7);
    }
?>