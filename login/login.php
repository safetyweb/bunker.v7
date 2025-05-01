<?php

include '../_system/_functionsMain.php';

$sql = "SELECT DES_IMAGEM FROM BANNER_LOGIN WHERE LOG_ATIVO = 'S'
        ORDER BY RAND() LIMIT 1";

$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());

$qrImg = mysqli_fetch_assoc($arrayQuery);


$fundo = '../media/login/0/'.$qrImg['DES_IMAGEM'];

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
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons">
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
      opacity: 0.9;
      -webkit-filter: blur(13px);
      -moz-filter: blur(13px);
      -ms-filter: blur(13px);
      -o-filter: blur(13px);
      filter: blur(13px);
    }

    .blur, .imagem{
      background: url(<?=$fundo?>);
      background-position: center;
      background-repeat: no-repeat;
      display: none;
    }

    /*.bar{
      width: 100%;
      padding: 1px;
      height: 10px;
      background: #1976D2;
      z-index: 5;
    }*/

    .card{
      background: #FFF;
      border-radius: 0px;
    }

  </style>
</head>

<body style="background: #DDD;">

  <!-- Full Page Intro -->
  <div class="view">
    <div class="imagem"></div>

    <!-- Mask & flexbox options-->
    <div class="mask d-flex justify-content-center align-items-center">

      <!-- Content -->
      <div class="container">

        <!--Grid row-->
        <div class="row wow fadeIn">

         

          <!--Grid column-->
          <div class="col-md-4 col-xl-4 mb-4 marginCenter">

            <!--Card-->
            <div class="card">
              <div class="blur"></div>

              <!--Card content-->
              <div class="card-body">

                <!-- Form -->
                <form action="#" method="POST">

                  <div class="md-form">
                    <i class="fal fa-user prefix dark-text"></i>
                    <input type="text" id="DES_EMAIL" name="DES_EMAIL" class="form-control text-dark">
                    <label for="DES_EMAIL" class="text-dark">Usuário</label>
                  </div>
                  
                  <div class="md-form">
                    <i class="fal fa-lock prefix dark-text"></i>
                    <input type="password" id="DES_SENHA" name="DES_SENHA" class="form-control text-dark">
                    <label for="DES_SENHA" class="text-dark">Senha</label>
                  </div>

                  <div class="md-form">
                    <button type="submit" class="btn btn-blue form-control p-2"><b>Logar-se</b></button>
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

    $(document).ready(function(){
      $('.imagem,.blur').fadeIn('slow');
    });

    // Animations initialization
  //   new WOW().init();

  //   $(document).ready(function() {   

  //   var i =0;
  //   var j =0;
  //   var images = ['<?= $img1; ?>','<?= $img2; ?>','<?= $img3; ?>','<?= $img4; ?>','<?= $img5; ?>'];
  //   var imagesMob = ['<?= $imgM1; ?>'];

  //   $('.blur,.imagem').css("background", "url('<?= $img5; ?>') no-repeat center");
  //   $('.blurMobile,.imagemMobile').css("background", "url('<?= $imgM1; ?>') no-repeat center");

  //   setInterval(function(){
      
  //     $('.blur').fadeOut(1000);

  //     $('.imagem').fadeOut(1000, function () {

  //       $('.blur, .imagem').css({'background-image':'url(' + images [i++] +')',"background-position":"center","background-repeat":"no-repeat"});
  //       $('.blur,.imagem').fadeIn(1000);

  //     });

  //     $('.blurMobile').fadeOut(1000);
  //     $('.imagemMobile').fadeOut(1000, function () {

  //       $('.blurMobile, .imagemMobile').css({'background-image':'url(' + imagesMob [j++] +')',"background-position":"center","background-repeat":"no-repeat"});
  //       $('.blurMobile,.imagemMobile').fadeIn(1000);

  //     });

  //     if(i == images.length)
  //       i = 0;

  //     if(j == imagesMob.length)
  //       j = 0;

  //   }, 10000);            
  // });

  </script>
</body>

</html>