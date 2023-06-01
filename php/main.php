<?php

function conexion(){
    $pdo = new PDO('mysql:host=localhost;dbname=inventario','root','');
    //$pdo->query("INSERT INTO categoria(categoria_nombre,categoria_ubicacio) VALUES ('holi','desde italia')");
    return $pdo;
};


function verificar_datos($filtro,$cadena){
    if(preg_match("/^".$filtro."$/",$cadena)){
        return false;
    }else{
        return true;
    } 
};

//limpiar cadena de texto

function limpiar_cadena($cadena){
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    $cadena=str_ireplace("<script>", "", $cadena);
    $cadena=str_ireplace("</script>", "", $cadena);
    $cadena=str_ireplace("<script src", "", $cadena);
    $cadena=str_ireplace("<script type=", "", $cadena);
    $cadena=str_ireplace("SELECT * FROM", "", $cadena);
    $cadena=str_ireplace("DELETE FROM", "", $cadena);
    $cadena=str_ireplace("INSERT INTO", "", $cadena);
    $cadena=str_ireplace("DROP TABLE", "", $cadena);
    $cadena=str_ireplace("DROP DATABASE", "", $cadena);
    $cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
    $cadena=str_ireplace("SHOW TABLES;", "", $cadena);
    $cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
    $cadena=str_ireplace("<?php", "", $cadena);
    $cadena=str_ireplace("?>", "", $cadena);
    $cadena=str_ireplace("--", "", $cadena);
    $cadena=str_ireplace("^", "", $cadena);
    $cadena=str_ireplace("<", "", $cadena);
    $cadena=str_ireplace("[", "", $cadena);
    $cadena=str_ireplace("]", "", $cadena);
    $cadena=str_ireplace("==", "", $cadena);
    $cadena=str_ireplace(";", "", $cadena);
    $cadena=str_ireplace("::", "", $cadena);
    $cadena=trim($cadena);
    $cadena=stripslashes($cadena);
    return $cadena;
}

//renombrar fotos

function renombrar_fotos($nombre){
    $nombre=str_ireplace(" ","_",$nombre);
    $nombre=str_ireplace("/","_",$nombre);
    $nombre=str_ireplace("#","_",$nombre);
    $nombre=str_ireplace("-","_",$nombre);
    $nombre=str_ireplace("$","_",$nombre);
    $nombre=str_ireplace(".","_",$nombre);
    $nombre=str_ireplace(",","_",$nombre);
    $nombre=$nombre."_".rand(0,100);
    return $nombre;
}

//funcion paginador de tablas

function paginador_tablas($paginas,$num_paginas,$url,$botones){
    $tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';
    
    if($paginas<=1){
        $tabla.='
			<a class="pagination-previous is-disabled" disabled >Anterior</a>
			<ul class="pagination-list">';
    }else{
        $tabla.='
			<a class="pagination-previous" href="'.$url.($paginas-1).'" >Anterior</a>
			<ul class="pagination-list">';
    }

    $ci=0;
		for($i=$paginas; $i<=$num_paginas; $i++){
			if($ci>=$botones){
				break;
			}
			if($paginas==$i){
				$tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';
			}else{
				$tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
			}
			$ci++;
		}

    if($paginas==$num_paginas){
        $tabla.='
                </ul>
                <a class="pagination-next is-disabled" disabled >Siguiente</a>';
    }else{
        $tabla.='
                <li><span class="pagination-ellipsis">&hellip;</span></li>
                <li><a class="pagination-link" href="'.$url.$num_paginas.'">'.$num_paginas.'</a></li>
            </ul>
            <a class="pagination-next " href="'.$url.($paginas+1).'" >Siguiente</a>';
    }

    $tabla.='</nav>';
    return $tabla;
}
?>
