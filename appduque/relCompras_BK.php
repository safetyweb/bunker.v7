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
		
@$sql2="SELECT COD_MULTEMP,COD_CLIENTE FROM clientes A				
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

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
	
 		<?php 
		$tituloPagina = "Minhas Compras";
		include "menu.php"; 
		?>	

        <div class="container">
            <div class="push20"></div> 

			
						<div class="push20"></div>
						
  <div class="hack1">
    <div class="hack2">
						
						<div id="relatorioConteudo">
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

									<div class="push20"></div>

										<table class="table table-bordered table-hover  ">
										  <thead>
											<tr>
											  <th>Data</th>
											  <!--<th>ID Venda</th>-->
											  <th>Tipo</th>
											  <th>Token</th>                                                                                         
                                                                                          <th>Loja</th>											  
											  <th>Vl. Pago</th>
											  <th>Pagamento</th>
											</tr>
										  </thead>
										<tbody>
										  
										<?php 
										
											$sql = "SELECT B.DES_LANCAMEN,
														   C.DES_OCORREN,
														   D.NOM_UNIVEND,
														   E.DES_FORMAPA,
														   A.*,
														   (select count(*) from itemvenda where cod_venda=a.cod_venda and itemvenda.cod_exclusa > 0)as EXCLUIDO																   
													FROM VENDAS a
													LEFT JOIN $connAdm->DB.tipolancamentomarka b ON b.COD_LANCAMEN = a.COD_LANCAMEN
													LEFT JOIN $connAdm->DB.ocorrenciamarka c ON c.COD_OCORREN = a.COD_OCORREN
													LEFT JOIN $connAdm->DB.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND
													LEFT JOIN formapagamento e ON e.COD_FORMAPA = a.COD_FORMAPA
													WHERE a.COD_CLIENTE = ".$qrBuscaCliente['COD_CLIENTE']."
													AND a.COD_EMPRESA = $cod_empresa 
													ORDER BY DAT_CADASTR_WS DESC limit 500
													";											
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											
											$count = 0;
											$valorTTotal = 0;
											$valorTRegaste = 0;
											$valorTDesconto = 0;
											$valorTvenda = 0;
											$classeExc = "";
											//pegar o ultimo tokem gerado 
                                                                                        
                                                                                        //==fim==============
											while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
											  {														  
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
													   $tokemexec=mysqli_query(connTemp($cod_empresa,''),$tokem);
													   $rwtokem=mysqli_fetch_assoc($tokemexec);
													   $colunaEspecial = $rwtokem['DES_PARAM2'];
													   if($colunaEspecial=='')
													   {
															$colunaEspecial = '<i class="fa fa-times text-danger fa-1x" aria-hidden="true"></i>';
													   }     
													   
												} 														
													  
												//<td class='".$classeExc."' >".$qrBuscaProdutos['COD_VENDAPDV']."</td>												
												
												echo"
													<tr id="."cod_venda_".$qrBuscaProdutos['COD_VENDA'].">															
													  <td class='".$classeExc."'><small>".fnFormatDateTime($qrBuscaProdutos['DAT_CADASTR_WS'])."</small></td>
													  <td class='".$classeExc."' >".$qrBuscaProdutos['DES_LANCAMEN']."</td>												
													  <td class='".$classeExc."'  class='text-center'><small>".$colunaEspecial."</small></td>												
													  <td class='".$classeExc."' >".$qrBuscaProdutos['NOM_UNIVEND']."</td>	
												          <td class='".$classeExc."'  class='text-center'><b>".fnValor($qrBuscaProdutos['VAL_TOTVENDA'],2)."</b></td>
													  <td class='".$classeExc."' >".$qrBuscaProdutos['DES_FORMAPA']."</td>												
													</tr>
													
												  <tr style='display:none; background-color: #fff;' id='abreDetail_".$qrBuscaProdutos['COD_VENDA']."'>
													<td colspan='11'>
													<div id='mostraDetail_".$qrBuscaProdutos['COD_VENDA']."'>
						
													
													

													</div>
													</td>
												  </tr>
												  
													";
												  }											

										?>
												
										</tbody>
										  <tfoot>
											<tr>
											  <th colspan="4">Total</th>
											  <th class="text-center"><?php echo fnValor($valorTTotal,2);?></th>
											  <th></th>
											</tr>
										  </tfoot>
										</table>
																					
								</div>								
								
							</div>
						</div>
		

    </div>
  </div>
			
			<div class="push50"></div>

        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>		

    </body>
</html>