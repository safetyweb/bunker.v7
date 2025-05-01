<?php 
session_destroy();
session_start();
include 'header.php';

$rand = fnEncode(microtime());

// if($_COOKIE['login'] == "" && $_COOKIE['senha'] == "")
  // {
      //1 ano de  duração
      // setcookie("login", 'true',time()+3600*24*30*12);
      // setcookie("senha",'true',time()+3600*24*30*12);
  // }


if($cor_fullpag == ''){
    $cor_fullpag = 'white';
}
if($cor_botao == ''){
    $cor_botao = '#03214f';
}

if($cor_botaoon == ''){
    $cor_botaoon = '#03214f';
}
if($cor_textfull == ''){
    $cor_textfull = '#FFF';
}

list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf($cor_fullpag, "#%02x%02x%02x");

  if($r_cor_backpag > 50){
      $r = ($r_cor_backpag-50);
  }else{
      $r =($r_cor_backpag+50);
      if($r_cor_backpag < 30){
          $r = $r_cor_backpag;
      }
  }
  if($g_cor_backpag > 50){
      $g = ($g_cor_backpag-50);
  }else{
      $g =($g_cor_backpag+50);
      if($g_cor_backpag < 30){
          $g = $g_cor_backpag;
      }
  }
  if($b_cor_backpag > 50){
      $b = ($b_cor_backpag-50);
  }else{
      $b =($b_cor_backpag+50);
      if($b_cor_backpag < 30){
          $b = $b_cor_backpag;
      }
  }

  if($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50){
      $r =($r_cor_backpag+40);
      $g =($g_cor_backpag+40);
      $b =($b_cor_backpag+40);
  }
?>

	<style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            padding-bottom: 40px;
            font-size: 14px;
            background-color: <?=$cor_fullpag?>!important;
            <?php  
                if($des_imgback != ""){ 
            ?>
                    background-image: url("https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>");
            <?php 
                } 
            ?>
        }

        a{
            color: <?=$cor_textfull?>!important;
        }

        .shadow2{
            -webkit-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
            -moz-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
            box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
            width: 100%;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: <?=$cor_botao?>;
            border-color: white;
            font-size: 16px;
            width: 100%;
            text-align: center;
            font-weight: bold;
            margin: 0 auto;
        }

        .btn-primary:hover {
            background-color: <?=$cor_botaoon?>;
            border-color: <?=$cor_botaoon?>;
        }

        h2 {
            font-size: 16px;
            color: #fff;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }
        .form-signin .checkbox {
            font-weight: normal;
        }
        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        hr{
            border: 1px solid #219c54;
            width: 90%;
            max-width: 400px;
        }

        .logo{
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .btn-lg-spc{
            padding: 10px 30px 10px 30px !important;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
        }

        .desktop, .desktop:hover, .desktop:visited{
            color: #fff;
        }

        .navbar {
            min-height: 53px;
            background-color: #03214f;
            margin-bottom: 15px;
        }

        #openMenu {
            position: relative;
            font-size: 28px;
            margin-left: 15px;
            line-height: 38px;
            z-index: 999;
            color: white;
        }

        .menuName {
            text-transform: uppercase;
            font-size: 8px;
            margin-left: 15px;
            font-weight: bold;
            margin-top: -5px;
            color: white;
        }

        .menuTitulo {
            position:absolute;
            width: 100%;
            text-align: center; 
            line-height: 50px;
            font-size: 20px;
            font-weight: bold;
            color: white;
        }

        .logoNavbar {
            position:absolute;
            width: 100%;
            text-align: right; 
            line-height: 50px;
            font-size: 20px;
            font-weight: bold;
            color: white;
            right: 15px;
        }     

        .mytextdiv{
            display:flex;
            flex-direction:row;
            align-items: center;
        }
        .mytexttitle{
            flex-grow:0;
            text-transform: uppercase;
            font-weight: bold;
            color: #03214f;
        }

        .divider{
            flex-grow:1;
            height: 1px;
            background-color: #9f9f9f;
            margin-left: 5px;
        }       

        .form-signin-heading {
            color: white;
            text-transform: uppercase;
            font-size: 16px;
            font-weight: bold;
            margin-top: 13px;
            cursor: pointer;
        }

        #menu {
            background-color: #03214f;
        }

        .mm-listview {
            color: white;
            font-size: 18px !important;
        }

        .mm-title {
            color: white !important;
            font-size: 18px;
        }

        .mm-next {
            width: 100% !important;
        }

        .mm-next:after{
            border-color: white !important;
        }

        .mm-prev:before{
            border-color: white !important;
        }
		
		a:hover {
			text-decoration: none;
		}
		
    </style>

    <div class="container">
	    
        <div class="push30"></div>

        <?php if($des_logo != ""){ ?>

        <div class="text-center pagination-centered">
            <a class="">
                <img alt="" class="logo img-responsive center-block" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" width="150px">
            </a>
        </div>

        <?php } ?>

         <div class="push30"></div>
        <form class="form-signin" method="post">
            <a href="app.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="btn btn-primary btn-block shadow2" style="color: #fff; border-radius: 35px;">LOGIN</a>
        </form>

        <div class="push30"></div>
        <div class="text-center" style="color: ">
            <!-- <a href="cadastrarSe.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading"> Cadastre-se </a> <br /><div class="push30"></div> -->
            <?php // if($_GET['dev'] == 11478){ ?>
            <a href="consulta_V2.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">Cadastre-se</a> <br /><div class="push30"></div>
            <?php // } ?> 
            <!-- <a href="empresa.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">a empresa</a> <br /><div class="push30"></div> -->
           <!--  <a href="regioes.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">endereços</a> <br /><div class="push30"></div> -->
            <!-- <a href="faleConosco.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">fale conosco</a> <br /><div class="push30"></div> -->
            <?php 
                if($des_sobre != ""){
            ?>
            <a href="info.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">o programa</a> <br /><div class="push30"></div>
            <?php
                } 
            ?>
            <?php 
                if($des_termosapp != ""){
            ?>
                <a href="termosPrograma.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">termos e condições</a> <br /><div class="push30"></div>
            <?php
                } 
            ?>
            <?php 
                if($cod_empresa == 19){
            ?>
                <a href="faleConosco.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">fale conosco</a> <br /><div class="push30"></div>
                <a href="parceiro.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">seja nosso parceiro</a> <br /><div class="push30"></div>
            <?php
                } 
            ?>
            <!-- <a href="login2.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="form-signin-heading">esqueci minha senha</a> <br /><div class="push30"></div> -->
        </div>


    </div> <!-- /container -->	

<?php include 'footer.php';?>