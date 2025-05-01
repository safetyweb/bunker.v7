<?php 
include './_system/_functionsMain.php';	
 
      $_SESSION['login'] = "OK";
      if($_SESSION["COD_RETORNO"]!='')
      {$cod_cliente=$_SESSION["COD_RETORNO"];} else {$cod_cliente= fnDecode($_GET['secur']);} 
	   
	$sql2="SELECT *  FROM clientes   WHERE NUM_CARTAO=$cod_cliente and COD_EMPRESA=19";
        $qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql2)); 
       //fnescreve($sql2);

    $nome_cliente=$qrBuscaCliente['NOM_CLIENTE'];
    $cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];
    $_SESSION["cod_entidad"]=$qrBuscaCliente['COD_ENTIDAD'];
    
    $sql3="select NOM_ENTIDAD,COD_EXTERNO,COD_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
    $qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql3));		
   // fnEscreve($sql3);	
    @$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
    @$COD_ENTIDAD = $qrBuscaEntidade['COD_ENTIDAD'];
  
//cookies
$json=array("RD_userId"=>$cod_cliente,
            "RD_userCompany"=>$COD_ENTIDAD,
            "RD_userMail"=>$qrBuscaCliente['DES_EMAILUS'],
            "RD_userName"=>$qrBuscaCliente['NOM_CLIENTE'],
            "RD_userType"=>$qrBuscaCliente['COD_TPCLIENTE']
         );
$jsoncookies=json_encode($json);
//$jsoncookies=str_replace(':', '=', $jsoncookies);

setcookie("REDE_DUQUE",$jsoncookies);
 
?>	

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
		$tituloPagina = "Endereços";
		include "menu.php";

		//echo "<h1>_".$_SESSION['login']."</h1>";
		
		?>	

        <div class="container">
            <div class="push30"></div> 
		
			<h4>Olá, <?php echo $nome_cliente; ?>. 
			<div class="push20"></div> 
			Seja bem vindo.</h4>

            <div class="push20"></div> 
			
			Confira abaixo seus veículos cadastrados e o preço promocional para: <b><?php echo $nom_entidad; ?></b>.
			
            <div class="push20"></div> 
			
		<?php
			$sql1 = "select veiculos.DES_PLACA, MARCA.COD_MARCA, veiculos.COD_EXTERNO, 
                                        MARCA.NOM_MARCA, modelo.NOM_MODELO from veiculos 
                                left join MARCA on MARCA.COD_MARCA=veiculos.COD_MARCA 
                                left join modelo on modelo.COD_MODELO=veiculos.COD_MODELO 
                                where veiculos.COD_CLIENTE ='".$qrBuscaCliente['COD_CLIENTE']."'"; 
                        if($qrBuscaCliente['COD_CLIENTE']=='255313')
                        {
                         echo $sql1;    
                        }        
                        $arrayQuery = mysqli_query(connTemp(19,''),$sql1) or die(mysqli_error());
          //fnescreve($sql1);
               
             
             while ($qrListaVeiculo = mysqli_fetch_assoc($arrayQuery))
              {
             ?>
             
             <div class="col-md-3 text-center"> 
             <i class="fa fa-car fa-3x" aria-hidden="true"></i> 
             <div class="push10"></div>
             <small><b>Placa:</b></small> <?php echo $qrListaVeiculo['DES_PLACA']; ?> <br/>            
             <!-- <small><b>Marca:</b></small> <?php echo $qrListaVeiculo['NOM_MARCA']; ?> <br/>            
             <small><b>Modelo:</b></small> <?php echo $qrListaVeiculo['NOM_MODELO']; ?> <br/>  -->           
             </div>
             
             <?php 
            } 
             ?>

             <div class="col-md-3"> 
             
             <div class="push10"></div>
             <?php
             /*
             $sql2 = "select b.DES_PRODUTO,a.VAL_PRODUTO from plano_valor a,produtocliente b
               where 
               a.cod_produto=b.cod_produto and
               a.cod_entidad = $cod_entidad "; 
              * 
             */
             //Preço comentado aqui
           /*  
            $sql2= "SELECT 
                             * 
                            FROM 	portalduque.vw_mk_condicao_produto 
                            JOIN 	portalduque.vw_mk_produto	on	portalduque.vw_mk_condicao_produto.id_produto =  portalduque.vw_mk_produto.cod_produto
                            JOIN	portalduque.vw_mk_condicao_preco	on	portalduque.vw_mk_condicao_produto.id_condicao_produto =  portalduque.vw_mk_condicao_preco.id_condicao_produto	
                            where 	portalduque.vw_mk_condicao_preco.id_cliente = '".$qrBuscaEntidade['COD_EXTERNO']."'";
							
			 //fnEscreve($sql2);
             //$arrayQuery = mysqli_query(connTemp(19,''),$sql2) or die(mysqli_error());
             $arrayQuery = mysqli_query($connDUQUE->connDUQUE(),$sql2) or die(mysqli_error());
             while ($qrListaPrecos = mysqli_fetch_assoc($arrayQuery))
               {
             ?>
             
             <small><b> <?php echo $qrListaPrecos['descricao']; ?>:</b></small> <?php echo $qrListaPrecos['preco']; ?> <br/>            
             
             <?php 
             } 
             */
             // até aqui
             ?> 
			
			<div class="push30"></div> 
			<div class="push50"></div> 
                        
                        <a href="geratokem.php?secur="<?php echo $_GET['secur']?> class='btn btn-primary btn-block'><i class="fa fa-unlock-alt" aria-hidden="true"></i>&nbsp;&nbsp;Gerar Token de Desconto</a>
            		
			
        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>		

    </body>
</html>