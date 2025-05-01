<?php

$img1 = 'img/backgrounds/1-shopping.jpg';
$img2 = 'img/backgrounds/1-gasstation.jpg';
$img3 = 'img/backgrounds/1-turism.jpg';
$img4 = 'img/backgrounds/1-food.jpg';
$img5 = 'img/backgrounds/1-drugstore.jpg';
$img6 = 'img/backgrounds/1-cosmetics.jpg';
$img7 = 'img/backgrounds/1-gym.jpg';
$img8 = 'img/backgrounds/1-petshop.jpg';
$img9 = 'img/backgrounds/1-digiservices.jpg';

$imgM1 = 'img/backgrounds/2-shopping.jpg';
$imgM2 = 'img/backgrounds/2-gaspump.jpg';
$imgM3 = 'img/backgrounds/2-carRepair.jpg';

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Material Design Bootstrap</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" type="text/css" href="font/css/fa5-1all.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="css/style.min.css" rel="stylesheet">
  <style type="text/css">
    html,
    body,
    header,
    .view {
      height: 100%;
    }

    @media (max-width: 740px) {
      html,
      body,
      header,
      .view {
        height: 100vh;
      }
    }

    @media (min-width: 800px) and (max-width: 850px) {
      html,
      body,
      header,
      .view {
        height: 100vh;
      }
    }
    @media (min-width: 800px) and (max-width: 850px) {
              .navbar:not(.top-nav-collapse) {
                  background: #1C2331!important;
              }
          }

    .imagem,.imagemMobile{
      position: absolute;
      height: 100%;
      width: 100%;
    }

    .marginCenter{
      margin-left: auto;
      margin-right: auto;
    }

    .blur,.blurMobile{
      box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.7), 0 12px 40px 0 rgba(0, 0, 0, 0.7);
      width: 100%;
      height: 100%;
      position: absolute;
      opacity: 0.7;
      -webkit-filter: blur(13px);
      -moz-filter: blur(13px);
      -ms-filter: blur(13px);
      -o-filter: blur(13px);
      filter: blur(13px);
    }

    /* (320x480) iPhone (Original, 3G, 3GS) */
    @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
        #desktop{display: none;}
        #mobile{display: block;}
        .imagemMobile{height: 100%;}
    }
     
    /* (320x480) Smartphone, Portrait */
    @media only screen and (device-width: 320px) and (orientation: portrait) {
        #desktop{display: none;}
        #mobile{display: block;}
        .imagemMobile{height: 100%;}
    }
     

    /* (1280x800) Tablets, Portrait */
    @media only screen and (max-width: 800px) and (orientation : portrait) {
        #desktop{display: none;}
        #mobile{display: block;}
        .imagemMobile{height: 100%;}
    }

    /* (768x1024) iPad 1 & 2, Portrait */
    @media only screen and (max-width: 768px) and (orientation : portrait) {
        #desktop{display: none;}
        #mobile{display: block;}
        .imagemMobile{height: 100%;}
    }
     
    /* (2048x1536) iPad 3 and Desktops*/
    @media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
        #desktop{display: block;}
        #mobile{display: none;}
        .imagemMobile{height: 100%;}
    }


  </style>
</head>

<body style="background: #3c3c3c;">

  <!-- Full Page Intro -->
  <div class="view full-page-intro" id="desktop">
    <div class="imagem"></div>

    <!-- Mask & flexbox options-->
    <div class="mask rgba-black-light d-flex justify-content-center align-items-center">

      <!-- Content -->
      <div class="container">

        <!--Grid row-->
        <div class="row wow fadeIn">

         

          <!--Grid column-->
          <div class="col-md-6 col-xl-5 mb-4 marginCenter">

            <!--Card-->
            <div class="card" style="background: #3c3c3c;">
              <div class="blur"></div>

              <!--Card content-->
              <div class="card-body">

                <!-- Form -->
                <form name="">
                  <!-- Heading -->
                  <!-- <h3 class="white-text text-center">
                    <strong>Fazer login</strong>
                  </h3>
                  <hr> -->

                  <div class="md-form">
                    <i class="fal fa-user prefix white-text"></i>
                    <input type="text" id="form3" class="form-control text-white">
                    <label for="form3" class="text-white">Usuário</label>
                  </div>
                  <div class="md-form">
                    <i class="fal fa-lock prefix white-text"></i>
                    <input type="text" id="form2" class="form-control text-white">
                    <label for="form2" class="text-white">Senha</label>
                  </div>

                  <!-- <div class="md-form">
                    <i class="fa fa-pencil-alt prefix white-text"></i>
                    <textarea type="text" id="form8" class="md-textarea"></textarea>
                    <label for="form8" class="text-white">Your message</label>
                  </div> -->

                  <div class="text-center">
                    <button class="btn btn-indigo">Logar-se</button>
                    <hr>
                    <fieldset class="form-check">
                      <input type="checkbox" class="form-check-input" id="checkbox1">
                      <label for="checkbox1" class="form-check-label white-text">Lorem Ipsum</label>
                    </fieldset>
                  </div>

                </form>
                <!-- Form -->

              </div>

            </div>
            <!--/.Card-->

          </div>
          <!--Grid column-->

        </div>
        <!--Grid row-->

      </div>
      <!-- Content -->

  <!-- Full Page Intro -->
    </div>
    <!-- Mask & flexbox options-->

  </div>

  <!-- Full Page Intro -->
  <div class="view full-page-intro" id="mobile">
    <div class="imagemMobile"></div>

    <!-- Mask & flexbox options-->
    <div class="mask rgba-black-light d-flex justify-content-center align-items-center">

      <!-- Content -->
      <div class="container">

        <!--Grid row-->
        <div class="row wow fadeIn">

         

          <!--Grid column-->
          <div class="col-md-6 col-xl-5 mb-4 marginCenter">

            <!--Card-->
            <div class="card" style="background: #3c3c3c;">
              <div class="blurMobile"></div>

              <!--Card content-->
              <div class="card-body">

                <!-- Form -->
                <form name="">
                  <!-- Heading -->
                  <!-- <h3 class="white-text text-center">
                    <strong>Fazer login</strong>
                  </h3>
                  <hr> -->

                  <div class="md-form">
                    <i class="fal fa-user prefix white-text"></i>
                    <input type="text" id="form3" class="form-control text-white">
                    <label for="form3" class="text-white">Usuário</label>
                  </div>
                  <div class="md-form">
                    <i class="fal fa-lock prefix white-text"></i>
                    <input type="text" id="form2" class="form-control text-white">
                    <label for="form2" class="text-white">Senha</label>
                  </div>

                  <!-- <div class="md-form">
                    <i class="fa fa-pencil-alt prefix white-text"></i>
                    <textarea type="text" id="form8" class="md-textarea"></textarea>
                    <label for="form8" class="text-white">Your message</label>
                  </div> -->

                  <div class="text-center">
                    <button class="btn btn-indigo">Logar-se</button>
                    <hr>
                    <fieldset class="form-check">
                      <input type="checkbox" class="form-check-input" id="checkbox1">
                      <label for="checkbox1" class="form-check-label white-text">Lorem Ipsum</label>
                    </fieldset>
                  </div>

                </form>
                <!-- Form -->

              </div>

            </div>
            <!--/.Card-->

          </div>
          <!--Grid column-->

        </div>
        <!--Grid row-->

      </div>
      <!-- Content -->

  <!-- Full Page Intro -->
    </div>
    <!-- Mask & flexbox options-->

  </div>

  <!-- SCRIPTS -->
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <!-- Initializations -->
  <script type="text/javascript">
    // Animations initialization
    new WOW().init();

    $(document).ready(function() {   

    var i =0;
    var j =0;
    var images = ['<?= $img1; ?>','<?= $img2; ?>','<?= $img3; ?>','<?= $img4; ?>','<?= $img5; ?>','<?= $img6; ?>','<?= $img7; ?>','<?= $img8; ?>','<?= $img9; ?>'];
    var imagesMob = ['<?= $imgM1; ?>','<?= $imgM2; ?>','<?= $imgM3; ?>'];

    $('.blur,.imagem').css("background", "url('<?= $img9; ?>') no-repeat center");
    $('.blurMobile,.imagemMobile').css("background", "url('<?= $imgM3; ?>') no-repeat center");

    setInterval(function(){
      
      $('.blur').fadeOut(1000);

      $('.imagem').fadeOut(1000, function () {

        $('.blur, .imagem').css({'background-image':'url(' + images [i++] +')',"background-position":"center","background-repeat":"no-repeat"});
        $('.blur,.imagem').fadeIn(1000);

      });

      $('.blurMobile').fadeOut(1000);
      $('.imagemMobile').fadeOut(1000, function () {

        $('.blurMobile, .imagemMobile').css({'background-image':'url(' + imagesMob [j++] +')',"background-position":"center","background-repeat":"no-repeat"});
        $('.blurMobile,.imagemMobile').fadeIn(1000);

      });

      if(i == images.length)
        i = 0;

      if(j == imagesMob.length)
        j = 0;

    }, 10000);            
  });

  </script>
</body>

</html>