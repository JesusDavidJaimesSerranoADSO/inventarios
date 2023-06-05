<?php  

    $inicio = ($pagina>0) ? (($pagina * $registro) - $registro) : 0 ;
    $tabla="";

    if(isset($busqueda) && $busqueda!=""){
        $consulta_datos = "SELECT * FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacio LIKE '%$busqueda%'  ORDER BY categoria_nombre ASC LIMIT $inicio,$registro";
        $total_datos = "SELECT COUNT(categoria_id) FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacio LIKE '%$busqueda%'";
    }else{
        $consulta_datos = "SELECT * FROM categoria ORDER BY categoria_nombre ASC LIMIT $inicio,$registro";
        $total_datos = "SELECT COUNT(categoria_id) FROM categoria";
    }

    $check_consulta = conexion();
    $datos = $check_consulta->query($consulta_datos);
    $datos = $datos -> fetchAll();

    $total = $check_consulta->query($total_datos);
    $total = (int) $total->fetchColumn();

    $npaginas = ceil($total / $registro);

    $tabla.='
    <div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr class="has-text-centered">
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Productos</th>
                    <th colspan="2">Opciones</th>
                </tr>
            </thead>
            <tbody>';

    if($total >= 1 && $pagina <= $npaginas){
        $contador = $inicio + 1;
        $pagina_ini = $inicio + 1;

        foreach($datos as $row){
            $tabla.='
            <tr class="has-text-centered" >
                    <td>'.$contador.'</td>
                    <td>'.$row['categoria_nombre'].'</td>
                    <td>'.substr($row['categoria_ubicacio'],0,25).'</td>
                    <td>
                        <a href="index.php?vista=producto_category&category_id='.$row['categoria_id'].'" class="button is-link is-rounded is-small">Ver productos</a>
                    </td>
                    <td>
                        <a href="index.php?vista=category_update&category_id_up='.$row['categoria_id'].'" class="button is-success is-rounded is-small">Actualizar</a>
                    </td>
                    <td>
                        <a href="'.$url.$pagina.'&category_id_del='.$row['categoria_id'].'" class="button is-danger is-rounded is-small">Eliminar</a>
                    </td>
                </tr>';
            $contador ++;
        }
        $pagina_final = $contador - 1;
    }else{
        if($total >= 1){
            $tabla.='
            <tr class="has-text-centered" >
            <td colspan="6">
                <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic acá para recargar el listado
                </a>
            </td>
        </tr>';
        }else{
            $tabla.='
            <tr class="has-text-centered" >
                    <td colspan="6">
                        No hay registros en el sistema
                    </td>
                </tr>
            ';
        }
    }

    $tabla.='</tbody></table></div>';

    $check_consulta = null;

    if($total >= 1 && $pagina <= $npaginas){
        $tabla.='<p class="has-text-right">Mostrando categorías <strong>'.$pagina_ini.'</strong> al <strong>'.$pagina_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
    }

    echo $tabla;

    if($total >= 1 && $pagina <= $npaginas){
        echo paginador_tablas($pagina,$npaginas,$url,7);
    }

?>