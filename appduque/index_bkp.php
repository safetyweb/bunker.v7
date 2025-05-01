<?php 

if($_COOKIE['login'] == "" && $_COOKIE['senha'] == "")
  {
	  //1 ano de  duração
      setcookie("login", 'true',time()+3600*24*30*12);
      setcookie("senha",'true',time()+3600*24*30*12);
  }

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">   
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
        <link rel="shortcut icon" href="#">

        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>		

        <style>
            html {
                position: relative;
                min-height: 100%;
            }

            body {
                padding-bottom: 40px;
                font-size: 14px;
               /* overflow: hidden;*/
            }

            .bgColor {
                background-color: #03204F;
                /*background-image: url(img/bg_intro.jpg);*/
                background-position: center; 
                background-size: cover;
            }

            .btn-primary {
                color: #03214f;
                background-color: white;
                border-color: white;
                font-size: 16px;
                width: 100%;
                text-align: center;
                font-weight: bold;
                margin: 0 auto;
            }

            .btn-primary:hover {
                color: #ffffff;
                background-color: #04347b;
                border-color: #04347b;
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

            .text-white{
                color: #fff!important;
            }

            .btn-outline {
                background-color: transparent;
                color: inherit;
                transition: all .5s;
            }

            .btn-primary.btn-outline {
                color: #428bca;
            }

            .btn-success.btn-outline {
                color: #5cb85c;
            }

            .btn-info.btn-outline {
                color: #5bc0de;
            }

            .btn-warning.btn-outline {
                color: #f0ad4e;
            }

            .btn-danger.btn-outline {
                color: #d9534f;
            }

            .btn-primary.btn-outline:hover,
            .btn-success.btn-outline:hover,
            .btn-info.btn-outline:hover,
            .btn-warning.btn-outline:hover,
            .btn-danger.btn-outline:hover {
                color: #fff;
            }
            .btn{
                font-size: 20px!important;
            }

            .text-white{
                color: #fff!important;
            }
            .text-right{
                float: right;
            }
			
        </style>

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">  

        <div class="container">
		    
            <div class="push30"></div>

            <div class="text-center pagination-centered">
                <a class="">
                    <img alt="" class="logo img-responsive center-block" src="img/logo_intro_big.png" width="80%">
                </a>
            </div>

             <div class="push30"></div>
            <form class="form-signin" method="post">
                <div class="col-xs-10 col-xs-offset-1">
                    <a href="novoLogin.do" class="btn btn-primary btn-block form-control">Login</a>
                    <a href="reenvioSenha.do" class="text-white text-right">Recuperar senha</a>
                </div>
            </form>

            <div class="push20"></div>
            <div class="row text-center">

                <div class="col-xs-8 col-xs-offset-2">
                    <a href="novoCadastro.do" class="btn btn-info btn-outline btn-block form-control text-white" style="padding-top: 5px;">Cadastre-se</a>
                </div>

                <div class="push10"></div>

                <div class="col-xs-8 col-xs-offset-2">
                    <a href="empresa.do" class="text-white"><h4>A Rede Duque</h4></a>
                </div>

                <div class="push10"></div>

                <div class="col-xs-8 col-xs-offset-2">
                    <a href="novoRegioes.do" class="text-white"><h4>Endereços</h4></a>
                </div>

                <div class="push10"></div>

                <div class="col-xs-8 col-xs-offset-2">
                    <a href="contato.do" class="text-white"><h4>Fale Conosco</h4></a>
                </div>

                <div class="push10"></div>

                <div class="col-xs-8 col-xs-offset-2">
                    <a href="parceiro.do" class="text-white"><h4>Seja Nosso Parceiro</h4></a>
                </div>
                <!--
                 <div class="col-xs-8 col-xs-offset-2">
                    <a href="uenderecos.do" class="text-white"><h4>u...</h4></a>
                </div>-->
                <!-- <a href="login2.do" class="form-signin-heading">esqueci minha senha</a> -->
                <!-- <a href="novoCadastro.do" class="form-signin-heading">Novo Cadastro</a>
                <a href="novoLogin.do" class="form-signin-heading">Novo Login</a> -->
            </div>
 
 
        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>		

    </body>
</html>