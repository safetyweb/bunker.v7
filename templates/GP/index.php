<?php

$numImg = rand(1,5);
if($numImg == 2){
    $background = "./assets/img/banner/bg2.jpeg";
}else{
    $background = "./assets/img/banner/bg".$numImg.".jpg";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Hub de Suporte Marka</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo/logo_branco.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Gp
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/gp-free-multipurpose-html-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center">

      <!-- <h1 class="logo me-auto me-lg-0"><a href="index.php">Gp<span>.</span></a></h1> -->
      <!-- Uncomment below if you prefer to use an image logo -->
      <a href="javascript:void(0)" class="logo me-auto me-lg-0"><img src="assets/img/logo/logo_modelo.png" alt="" class="img-fluid"></a>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link" href="javascript:void(0)" target="_blank">Home</a></li>
          <li><a class="nav-link" href="https://marka.mk" target="_blank">Site Institucional</a></li>
          <li><a class="nav-link" href="https://www.instagram.com/markafidelidade/" target="_blank">Instagram</a></li>
          <li class="dropdown"><a href="#"><span>Downloads</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <!-- <li><a href="#">Drop Down 1</a></li> -->
              <!-- <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li> -->
              <li><a href="https://www.teamviewer.com/pt-br/download/windows/" target="_blank">Team Viewer</a></li>
              <li><a href="https://anydesk.com/pt/downloads/windows" target="_blank">Anydesk</a></li>
              <!-- <li><a href="#">Drop Down 4</a></li> -->
            </ul>
          </li>
          <!-- <li><a class="nav-link scrollto" href="#contact">Contact</a></li> -->
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <!-- <a href="#about" class="get-started-btn scrollto">Get Started</a> -->

    </div>
  </header><!-- End Header -->
  <style type="text/css">
      #hero{
        background: url("<?=$background?>") top center;
        background-size: cover;
      }
      .icon-box{
        background: rgba(255, 255, 255, 0.4);
        border-radius: 3px;
      }
  </style>
  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container" data-aos="fade-up">

      <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
        <div class="col-xl-6 col-lg-8">
          <h1>Marka<span>.Mk</span></h1>
          <h2>Hub de suporte e atendimento</h2>
        </div>
      </div>

      <div class="row gy-4 mt-5 justify-content-center" data-aos="zoom-in" data-aos-delay="250">
        <div class="col-xl-2 col-md-4 room-box">
          <div class="icon-box">
            <i class="ri-questionnaire-line"></i>
            <h3><a href="https://suporte.marka.mk" target="_blank">Sala do<br /> Suporte</a></h3>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 room-box">
          <div class="icon-box">
            <i class="ri-code-box-line"></i>
            <h3><a href="https://dev.marka.mk" target="_blank">Sala do<br /> Desenvolvimento</a></h3>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 room-box">
          <div class="icon-box">
            <i class="ri-chat-smile-2-line"></i>
            <h3><a href="https://maurice.marka.mk" target="_blank">Sala da<br /> Comunicação</a></h3>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 room-box">
          <div class="icon-box">
            <i class="ri-money-dollar-circle-line"></i>
            <h3><a href="">Sala do<br /> Financeiro</a></h3>
          </div>
        </div>
        <div class="col-xl-2 col-md-4 room-box">
          <div class="icon-box">
            <i class="ri-time-line"></i>
            <h3><a href="">Sala do<br /> AMI</a></h3>
          </div>
        </div>
      </div>

    </div>
  </section><!-- End Hero -->

  <!-- <div id="preloader"></div> -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>