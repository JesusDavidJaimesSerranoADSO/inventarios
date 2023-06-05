<div class="container is-fluid mb-6">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle">Lista de productos por categoría</h2>
</div>

<div class="container pb-6 pt-6">

    <?php

    require_once("./php/main.php");

    ?>
    <div class="columns">



        <div class="column is-one-third">
            <h2 class="title has-text-centered">Categorías</h2>

            <?php

                require_once("./php/main.php");

                $categoria = conexion();
                $categoria = $categoria ->query("SELECT * FROM categoria");

                if($categoria -> rowCount() > 0){
                    $categoria = $categoria -> fetchAll();
                    foreach($categoria as $opcion){
                        echo '<a href="index.php?vista=producto_category&category_id='.$opcion['categoria_id'].'" class="button is-link is-inverted is-fullwidth">'.$opcion['categoria_nombre'].'</a>';
                    }
                }else{
                    echo '<p class="has-text-centered" >No hay categorías registradas</p>';
                }
                $categoria = null;
                ?>

        </div>



        <div class="column">

            <h2 class="title has-text-centered">Nombre de categoría</h2>
            <p class="has-text-centered pb-6" >Ubicacion de categoría</p>

            <article class="media">
                <figure class="media-left">
                    <p class="image is-64x64">
                        <img src="./img/producto.png">
                    </p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>1 - Nombre de producto</strong><br>
                            <strong>CODIGO:</strong> 00000000, 
                            <strong>PRECIO:</strong> $10.00, 
                            <strong>STOCK:</strong> 21, 
                            <strong>CATEGORIA:</strong> Nombre categoria, 
                            <strong>REGISTRADO POR:</strong> Nombre de usuario
                        </p>
                    </div>
                    <div class="has-text-right">
                        <a href="#" class="button is-link is-rounded is-small">Imagen</a>
                        <a href="#" class="button is-success is-rounded is-small">Actualizar</a>
                        <a href="#" class="button is-danger is-rounded is-small">Eliminar</a>
                    </div>
                </div>
            </article>

            <p class="has-text-centered" >
                <a href="#" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic acá para recargar el listado
                </a>
            </p>

            <p class="has-text-centered" >No hay registros en el sistema</p>

            <hr>

            <p class="has-text-right">Mostrando productos <strong>1</strong> al <strong>17</strong> de un <strong>total de 17</strong></p>

            <nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">
                <a class="pagination-previous" href="#">Anterior</a>

                <ul class="pagination-list">
                    <li><a class="pagination-link" href="#">1</a></li>
                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                    <li><a class="pagination-link is-current" href="#">2</a></li>
                    <li><a class="pagination-link" href="#">3</a></li>
                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                    <li><a class="pagination-link" href="#">3</a></li>
                </ul>

                <a class="pagination-next" href="#">Siguiente</a>
            </nav>

            

            

            <h2 class="has-text-centered title" >Seleccione una categoría para empezar</h2>

        </div>

    </div>
</div>