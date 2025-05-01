<?php
include_once 'header.php';
$tituloPagina = "Ofertas";
include_once "navegacao.php";
include_once '_system/Lista_oferta.php';

// if(!isset($_SESSION["usuario"])){

//        header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));

//     }

$cpf = $usuario;


$arrayOfertas = fnofertas($cpf, $dadoslogin);

// if($_SESSION["usuario"] == 1734200014){
//     $log_bannerlista = 'S';
// }

?>

<style>
  body {
    padding-bottom: 40px;
    background-color: #eee;
    font-size: 14px;
    color: #03214f;
  }

  .shadow2 {
    border-radius: 5px;
  }

  .fa-map-marker {
    font-size: 80px;
  }

  .barcode {
    width: 320px !important;
    height: 60px !important;
    margin-left: auto;
    margin-right: auto;
  }

  .text-right {
    float: right;
  }

  .shadow {
    -webkit-box-shadow: 0px 0px 8px -2px rgba(204, 200, 204, 1);
    -moz-box-shadow: 0px 0px 8px -2px rgba(204, 200, 204, 1);
    box-shadow: 0px 0px 8px -2px rgba(204, 200, 204, 1);
    /*width: 100%;*/
    border-radius: 5px;
  }

  .carousel {
    border-radius: 10px 10px 10px 10px;
    overflow: hidden;
  }

  .carousel-caption {
    color: <?= $cor_textos ?>;
    /*background-color: rgba(0,0,0,0.2);*/
    border-radius: 30px 30px 30px 30px;
    padding-top: 5px;
    padding-bottom: 5px;
    bottom: 0px;
    background-color: rgba(255, 255, 255, 0.7);
  }

  .contorno {
    /*-webkit-text-fill-color: white;  Will override color (regardless of order) */
    /*-webkit-text-stroke-width: 0.5px;
      -webkit-text-stroke-color: white;*/
    text-shadow: 1px 1px black;
  }

  .carousel-indicators {
    z-index: 0;
  }

  .carousel-control.left,
  .carousel-control.right {
    background-image: none
  }

  .img-lista {
    height: 85px;
    width: 85px;
    border-radius: 50px;
  }

  .center {
    margin: auto;
    position: absolute;
    right: 0;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
  }
</style>

<div class="container">

  <div class="push50"></div>


  <div class="row">
    <?php
    // echo "<pre>";
    // print_r($arrayOfertas);
    // echo "</pre>";
    if (trim($arrayOfertas['oferta0']['produtoticket']['msgerro']) != "Não há Produtos no ticket!" && count($arrayOfertas['oferta0']) > 0) {
    ?>

      <div class="col-md-12">

        <?php  //if ($cod_cliente=='01734200014') { 
        ?>

        <div id="carouselOfertas" class="carousel slide shadow2">

          <?php

          // if ($arrayOfertas['oferta0']['produtoticket'][imagem] != ''){
          if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])) {

            // echo 1;

          ?>


            <ol class="carousel-indicators">
              <?php

              $count = 0;
              $active = 'active';

              foreach ($arrayOfertas['oferta0']['produtoticket'] as $chave => $valor) {

                if ($valor['descricao'] != '') {

              ?>
                  <li data-target="#carouselOfertas" data-slide-to="<?= $count ?>" class="<?= $active ?>"></li>
              <?php

                  $count++;
                  $active = '';
                }
              }

              ?>
            </ol>
            <div class="carousel-inner shadow2">

              <?php

              $active = 'active';

              foreach ($arrayOfertas['oferta0']['produtoticket'] as $chave => $valor) {

                // print_r($chave);

              ?>

                <div class="item <?= $active ?>">
                  <?php
                  if ($valor['imagem'] != '') {

                  ?>
                    <img src="<?= $valor['imagem'] ?>" width="100%">
                  <?php

                  } else {
                  ?>
                    <img src="https://img.bunker.mk/media/clientes/branco.jpg" width="100%">
                  <?php
                  }
                  ?>
                </div>

              <?php

                $active = '';
              }
            } else {

              if ($arrayOfertas['oferta0']['produtoticket']['imagem'] != '') {
              ?>

                <div class="item <?= $active ?>">
                  <?php
                  if ($arrayOfertas['oferta0']['produtoticket']['imagem'] != '') {

                  ?>
                    <img src="<?= $arrayOfertas['oferta0']['produtoticket']['imagem'] ?>" width="100%">
                  <?php

                  } else {
                  ?>
                    <img src="https://img.bunker.mk/media/clientes/branco.jpg" width="100%">
                  <?php
                  }
                  ?>
                </div>

            <?php

                $active = '';
              }
            }


            ?>

            </div>

            <?php

            // if ($arrayOfertas['oferta0']['produtoticket'][imagem] != ''){
            if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])) {

              // echo 1;

            ?>

              <!-- Carousel controls -->
              <a class="carousel-control left" href="#carouselOfertas" data-slide="prev">
                <div class="push20"></div>
                <span class="fa fa-angle-left"></span>
              </a>
              <a class="carousel-control right" href="#carouselOfertas" data-slide="next">
                <div class="push20"></div>
                <span class="fa fa-angle-right"></span>
              </a>

        </div>

      <?php

            }

      ?>

      <div class="push20"></div>



      </div>

    <?php

    } else {
    ?>
      <div class="col-xs-12 reduzMargem corIcones" style="color: <?= $cor_textos ?>">
        <div class="shadow2">
          <div class="push5"></div>
          <div class="col-xs-12 zeraPadLateral text-center">
            <h5>Nenhuma oferta disponível no momento</h5>
          </div>
          <div class="push5"></div>
        </div>
      </div>
    <?php
    }
    ?>

  </div>


  <div class="push30"></div>

</div> <!-- /container -->

<?php include_once 'footer.php'; ?>

<!--<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>-->
<!-- Pré-carrega se for essencial -->
<!--<link rel="preload" href="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js" as="script">-->

<!-- Script com async e SRI -->
<script async src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"
  integrity="sha512-6+6IQ6K0J6Xg0Z+K+5QrDv0K4nrH+6z7f6FLZawp0mW8Jd/t3P4E9+4OoNw7lW4QmJ5qO4BZ+0JkZv1F4KjA=="
  crossorigin="anonymous"></script>

<script>
  $(document).ready(function() {

    $('.carousel').carousel({
      interval: 5000
    });

    $(".carousel").swipe({

      swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

        if (direction == 'left') $(this).carousel('next');
        if (direction == 'right') $(this).carousel('prev');

      },
      allowPageScroll: "vertical"

    });

  });
</script>