<?php
include './_system/_functionsMain.php';
//fnDebug('true');

@$cod_cliente=$_SESSION["COD_RETORNO"];   
@$cod_entidad=$_SESSION["cod_entidad"];
if($cod_cliente!='')
{
  
}else{
      //header("Location:http://191.252.2.68/app/"); 
       header("Location:http://bunker.mk/appduque/"); 
     // session_destroy();
     // session_unset();
      
} 

$cartao="select COD_CLIENTE from clientes where num_cartao='".$cod_cliente."'";
$rcartao=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $cartao));

$sqlproc="CALL SP_VERIFICA_TOKEN('19', '".$rcartao['COD_CLIENTE']."')";
$returnproc=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $sqlproc));
//fnEscreve($sqlproc);
if($returnproc['v_RESULTADO']=='S')
{    
 
    $senha = fngeraSenha(6, true, true, true);
    $placa="select 
                   clientes.cod_cliente,
                   veiculos.cod_cliente, 
                   veiculos.cod_cliente_ext,
                   clientes.COD_EXTERNO,
                   clientes.num_cartao,
                   veiculos.des_placa,
                   clientes.NOM_CLIENTE 
                   from clientes 
    left join veiculos on veiculos.cod_cliente_ext= clientes.NUM_CARTAO
    where clientes.NUM_CARTAO='$cod_cliente'";
    $des_placa=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $placa));
    //fnEscreve($placa);

    $gravatokem="INSERT INTO tokem 
                 (des_tokem, 
                 cod_cliente, 
                 dat_cadastr, 
                 cod_loja,
                 des_placa
                 ) 
                 VALUES ('".addslashes($senha)."', 
                          '".$cod_cliente."', 
                          '".date('Y-m-d H:i:s')."', 
                          '".$cod_entidad."',
                          '".$des_placa['des_placa']."'    
                          );";
    mysqli_query(connTemp(19,''), $gravatokem);  
$msg='Token gerado com sucesso!';
$img='<img src="rede_duque/token_ok.jpg">';
    //fnEscreve($senha); 

    include './_system/codebar/BarcodeGenerator.php';
    include './_system/codebar/BarcodeGeneratorHTML.php';
    //$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();    
    $codbar= $generator->getBarcode($senha, $generator::TYPE_CODE_39,2.5,60);     
		     
}else{

    $msg='Limite excedido!';  
 $img='<img src="rede_duque/token_nok.jpg">'; 
}

?>﻿

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="libs/bootstrap_flatly.css" rel="stylesheet">
        <link href="libs/font-awesome.min.css" rel="stylesheet">
        <link href="libs/bootstrap-social.css" rel="stylesheet">
        <link href="libs/layout.css" rel="stylesheet">


        <title>Rede Duque</title>

        <style>
            
            body {
                padding-bottom: 40px;
                background-color: #eee;
                font-size: 14px;
                color: #03214f;
            }
			
            .fa-map-marker {
                font-size: 80px;
            }

            .barcode{
              width: 320px!important;
              height: 60px!important;
              margin-left: auto;
              margin-right: auto;
            }
        </style>


        <script src="libs/ie-emulation-modes-warning.js"></script>


        <!-- Include jQuery.mmenu .css files -->
        <link type="text/css" href="libs/jquery.mmenu.all.css" rel="stylesheet" />

        <!-- Include jQuery and the jQuery.mmenu .js files -->
        <script src="libs/jquery.min.js"></script>
        <script type="text/javascript" src="libs/jquery.mmenu.all.js"></script>

        <!-- Fire the plugin onDocumentReady -->
        <script type="text/javascript">
            jQuery(document).ready(function( $ ) {
                $("#menu").mmenu();
            });
        </script>        

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
	
 		<?php 
		$tituloPagina = "Token";
		include "menu.php"; 
		?>	

        <div class="container text-center">

			<div class="push50"></div> 
			<?php echo $img; ?>
			<h1><?php echo $senha; ?></h1>			
			<h4><?php echo $msg; ?></h4>
			<div class="push50"></div> 
                       
        </div> 
        <div class="barcode text-center">
                          <?php  echo  $codbar; ?>
            <h4>Para obter seu desconto no momento do pagamento, apresente este código de barras para o frentista</h4>
			<div class="push50"></div> 
        </div>
        <!-- /container -->
        
		<?php include "jsLib.php"; ?>		

    </body>
</html>

