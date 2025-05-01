<?php
include './_system/_functionsMain.php';

@$cod_empresa = 19;

// definir o numero de itens por pagina
@$itens_por_pagina = 50;

// Página default
@$pagina = 1;
@$dat_ini="";
@$dat_fim="";
@$tipoVenda = "C";

//inicialização de variáveis
@$hoje = fnFormatDate(date("Y-m-d"));
@$dias30=fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
//@$dias30=fnFormatDate(date('Y-m-d', strtotime($dias30. '- 7 days')));
@$cod_univend = "9999"; //todas revendas - default

if($_SESSION["COD_RETORNO"]!=''){$cod_cliente=$_SESSION["COD_RETORNO"];} else {$cod_cliente= fnDecode($_GET['secur']);} 

$MENUSTARING=fnEncode($cod_cliente);

//fnEscreve($cod_cliente);
		
@$sql2="SELECT COD_MULTEMP,COD_CLIENTE, COD_PROFISS FROM clientes A				
		WHERE A.NUM_CARTAO = $cod_cliente
		AND A.COD_EMPRESA = 19 ";
		
$qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql2));		
@$cod_multemp = $qrBuscaCliente['COD_MULTEMP'];
@$lojasSelecionadas = $qrBuscaCliente['COD_MULTEMP'];

	
//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
	@$dat_ini = fnDataSql(@$dias30);
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
	@$dat_fim = fnDataSql(@$hoje); 
}	
if (strlen($cod_univend ) == 0){
	@$cod_univend = "9999"; 
}

if ($tipoVenda == "T"){
	@$checkTodas = "checked"; 
	@$checkCreditos = ""; 
}else{
	@$checkTodas = ""; 
	@$checkCreditos = "checked"; 
}

$r = 215;
$g = 215;
$b = 215;

?>﻿
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />

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
			
			table a:not(.btn), .table a:not(.btn) {
				text-decoration: none;
			}
			table a:not(.btn):hover, .table a:not(.btn):hover {
				text-decoration: underline;
			}

@media only screen and (max-width: 800px) {
    
    /* Force table to not be like tables anymore */
	#no-more-tables table, 
	#no-more-tables thead, 
	#no-more-tables tbody, 
	#no-more-tables th, 
	#no-more-tables td, 
	#no-more-tables tr { 
		display: block; 
	}
 
	/* Hide table headers (but not display: none;, for accessibility) */
	#no-more-tables thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
 
	#no-more-tables tr { border: 1px solid #ccc; }
 
	#no-more-tables td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
		white-space: normal;
		text-align:left;
	}
 
	#no-more-tables td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		text-align:left;
		font-weight: bold;
	}
 
	/*
	Label the data
	*/
	#no-more-tables td:before { content: attr(data-title); }
}
	
/* try removing the "hack" below to see how the table overflows the .body */
.hack1 {
  display: table;
  table-layout: fixed;
  width: 100%;
}

.hack2 {
  display: table-cell;
  overflow-x: auto;
  width: 100%;
}

.shadow{
    -webkit-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
    -moz-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
    box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
    width: 100%;
    border-radius: 5px;
}

.shadow2{
    -webkit-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
	-moz-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
	box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
    width: 100%;
    border-radius: 5px;
}

.reduzMargem{
    margin-bottom: 10px;
    margin-left: 0px;
    margin-right: 0px;
}

.zeraPadLateral{
	padding-right: 0px;
	padding-left: 0px;
}

.f12{
	font-size: 12px;
}

.f9{
	font-size: 11px;
}
	
        </style>


        <script src="libs/ie-emulation-modes-warning.js"></script>


        <!-- Include jQuery.mmenu .css files -->
        <link type="text/css" href="libs/jquery.mmenu.all.css" rel="stylesheet" />

        <!-- Include jQuery and the jQuery.mmenu .js files -->
        <script src="libs/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="libs/jquery.mmenu.all.js"></script>

        <!-- Fire the plugin onDocumentReady -->
        <script type="text/javascript">
            jQuery(document).ready(function( $ ) {
				$("#menu").mmenu({
					// options
					extensions	: ["theme-white"]
				}, {
					// configuration
					offCanvas: {
						pageSelector: ".container"
					}
				});
            });
        </script>        
}

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
	
 		<?php 
		$tituloPagina = "Minhas Compras";
		include "menu.php"; 
		?>	

        <div class="container">
            <div class="push20"></div> 

            <div id="relConteudo">

            <?php

            	$sqlCount = "SELECT 1																   
						FROM VENDAS a
						WHERE a.COD_CLIENTE = ".$qrBuscaCliente['COD_CLIENTE']."
						AND a.COD_EMPRESA = $cod_empresa";											
				$arrayCount = mysqli_query(connTemp($cod_empresa,''),$sqlCount);

				$registros = mysqli_num_rows($arrayCount);

            	$sql = "SELECT B.DES_LANCAMEN,
							   C.DES_OCORREN,
							   D.NOM_FANTASI,
							   E.DES_FORMAPA,
							   A.*,
							   ROUND(IFNULL((SELECT SUM(VAL_CREDITO) 
													FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
													AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
													AND TIP_CREDITO = 'C'), 0), 2) VAL_CREDITOS,
							   (SELECT MIN(DAT_EXPIRA) 
										FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
										AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
										AND TIP_CREDITO = 'C') DAT_EXPIRA,
							   (select count(*) from itemvenda where cod_venda=a.cod_venda and itemvenda.cod_exclusa > 0)as EXCLUIDO																   
						FROM VENDAS a
						LEFT JOIN $connAdm->DB.tipolancamentomarka b ON b.COD_LANCAMEN = a.COD_LANCAMEN
						LEFT JOIN $connAdm->DB.ocorrenciamarka c ON c.COD_OCORREN = a.COD_OCORREN
						LEFT JOIN $connAdm->DB.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND
						LEFT JOIN formapagamento e ON e.COD_FORMAPA = a.COD_FORMAPA
						WHERE a.COD_CLIENTE = ".$qrBuscaCliente['COD_CLIENTE']."
						AND a.COD_EMPRESA = $cod_empresa 
						ORDER BY DAT_CADASTR_WS DESC limit 10
						";
				// echo $sql;											
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				
				$count = 0;
				$valorTTotal = 0;
				$valorTRegaste = 0;
				$valorTDesconto = 0;
				$valorTvenda = 0;
				$classeExc = "";
				//pegar o ultimo tokem gerado 
                                                            
                //==fim==============
				while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)){

					$count++;
					if ($qrBuscaProdutos['COD_STATUSCRED'] != 6) {
						$valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
						$valorTRegaste = $valorTRegaste + $qrBuscaProdutos['VAL_RESGATE'];
						$valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
						$valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
						$classeExc = "";
					}else{
						$classeExc = "text-danger";	
					}
					
					$count++;
					if ($qrBuscaProdutos['EXCLUIDO'] == 0) {
						$classeExc2 = "";
						$mostraItemExcluido = "";
					}else{
						$classeExc2 = "text-danger";	
						$mostraItemExcluido = "<i class='fa fa-minus-circle' aria-hidden='true'></i>";	
					}
					
					
					if ($cod_empresa != 19) {
						if ($qrBuscaProdutos['COD_STATUSCRED'] != 6) {	
							$colunaEspecial = $qrBuscaProdutos['DES_OCORREN'];
						}else{
							$colunaEspecial = "venda estornada";
						}
					} else { 
						   $tokem="select itemvenda.COD_VENDA,itemvenda.DES_PARAM1,
										  itemvenda.DES_PARAM2,vendas.COD_VENDAPDV 
										  from itemvenda 
									inner join vendas on itemvenda.COD_VENDA= vendas.COD_VENDA
									where vendas.COD_VENDAPDV='".$qrBuscaProdutos['COD_VENDAPDV']."'";

							// echo $tokem;
							// exit();
						   $tokemexec=mysqli_query(connTemp($cod_empresa,''),$tokem);
						   $rwtokem=mysqli_fetch_assoc($tokemexec);
						   $colunaEspecial = $rwtokem['DES_PARAM2'];
						   if($colunaEspecial=='' || $colunaEspecial=='None')
						   {
								$colunaEspecial = '<i class="fa fa-times text-danger fa-1x" aria-hidden="true"></i>';
						   }     
						   
					}

					$data = explode(" ", $qrBuscaProdutos['DAT_CADASTR_WS']);

					$txtExpira = "Expira: ";
					$corExpira = "";

					if($qrBuscaProdutos['DAT_EXPIRA'] == ""){
						$txtExpira = "&nbsp;";
						$corExpira = "";
					}else if($qrBuscaProdutos['DAT_EXPIRA'] < date("Y-m-d")){
						$txtExpira = "Expirado: ";
						$corExpira = "text-danger";
					}

            ?>
			
					<div class="col-xs-12 reduzMargem corIcones zeraPadLateral" style="color: <?=$cor_textos?>">
						<div class="shadow2">
			        		<div class="push5"></div>
			                <div class="col-xs-4 zeraPadLateral text-center">
			                    <h5 class="f12"><b><?=fnDataShort($data[0])?></b><br/><span class="f9"><?=$data[1]?></span></h5>
			                </div>
			                <div class="col-xs-2 zeraPadLateral text-center">
			                    <h5 class="f9"><?=$colunaEspecial?></h5>
			                </div>
			                <div class="col-xs-4 zeraPadLateral text-center">
			                    <h5 class="f12"><?=$qrBuscaProdutos['NOM_FANTASI']?>
			                    <?php if($qrBuscaCliente['COD_PROFISS'] == 108){ ?>
			                    	<div class="push5"></div>
			                	<?php } ?>
			                    </h5>
			                </div>
			                <div class="col-xs-2 zeraPadLateral text-center">
			                    <h5 class="f12"><?=fnValor($qrBuscaProdutos['VAL_TOTVENDA'],2)?>
			                    </h5>
			                </div>
		                    <?php // if($qrBuscaCliente['COD_PROFISS'] == 108){ ?>
		                    	<div class="push"></div>
		                    	<div class="col-xs-6" style="margin-top: -10px;">
				        			<span class="f9 <?=$corExpira?>"><?=$txtExpira?> <b><?=fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])?></b></span>
				        		</div>
				        		<div class="col-xs-6 text-right" style="margin-top: -10px;">
				        			<span class="f9">Cashback: <b>+ <?=fnValor($qrBuscaProdutos['VAL_CREDITOS'],2)?></b></span>
				        		</div>
		                	<?php //} ?>
			        		
			        		<div class="push5"></div>
			            </div>
			        </div>

			<?php 

				}

			?>

			</div>
			
			<?php 

				if($registros > 10){
				
			?>	

				<div class="col-xs-12 reduzMargem corIcones zeraPadLateral text-center" style="color: <?=$cor_textos?>">
					<div class="shadow2">
		        		<a href="javascript:void(0)" class="btn btn-primary btn-block" id="loadMore">Carregar mais</a>
		            </div>
		        </div>

			<?php 

				}
				
			?>		

        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>	

		<script type="text/javascript">
			
			var cont = 0;
			$(function(){
				$('#loadMore').click(function(){
				
					cont +=10;

					if(cont >= "<?=$registros?>"){
						$('#loadMore').addClass('disabled');
						$('#loadMore').text('Todos os Itens Já se Encontam na Lista');
					}

					$.ajax({
						type: "POST",
						url: "ajxRelCompras.do?id=<?php echo $cod_empresa; ?>",
						data:{itens: cont, COD_CLIENTE: "<?=fnEncode($qrBuscaCliente[COD_CLIENTE])?>"},
						beforeSend:function(){	
							$('#loadMore').text('Carregando...');
						},
						success:function(data){
							$('#loadMore').text('Carregar Mais Produtos Da Lista');
							$('#relConteudo').append(data);
						},
						error:function(){
							alert('Erro ao carregar...');
						}
					});
				});
			});

		</script>	

    </body>
</html>