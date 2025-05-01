<?php
include './_system/_functionsMain.php';

@$cod_empresa = 19;

// definir o numero de itens por pagina
@$itens_por_pagina = 5;

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

if( $_SERVER['REQUEST_METHOD']=='POST' )
	{

	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$cod_univend = $_POST['COD_UNIVEND'];

}
		
@$sql2="SELECT COD_MULTEMP FROM clientes A				
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
		$tituloPagina = "Vendas Overview";
		include "menu.php"; 
		?>	

        <div class="container">
            <div class="push20"></div> 

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
												
					<div class="col-lg-12">
						<div class="collapse-chevron">
							<a data-toggle="collapse" class="btn btn-sm btn-default collapsed" href="#collapseFilter" aria-expanded="false">
							<span class="fa fa-chevron-down" aria-hidden="true"></span>&nbsp;
							Visualizar Filtros
							</a>
							<div class="collapse" id="collapseFilter" aria-expanded="false" style="height: 0px;">
								<div class="push20"></div> 
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>
											
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>
											
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>	
								
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Postos</label><br/>
												<select data-placeholder="Selecione a unidade de atendimento" style="height:35px; width: 100%; border: 2px solid #dce4ec; line-height: 1.5; border-radius: 3px;" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
													<option value=""></option>					
													<?php
														if ($cod_univend == "9999"){
														echo "<option value='9999' selected>Todas Unidades</option>";
														} else {
														echo "<option value='9999'>Todas Unidades</option>";
														}																	

														$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = 19 and cod_exclusa is null and COD_UNIVEND IN ($cod_multemp)order by NOM_UNIVEND ";
														$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
														while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery))
														  {
															if ($cod_univend == $qrListaUnidades['COD_UNIVEND']){ $selecionado = "selected";}else{$selecionado = "";}	
															echo"
																  <option value='".$qrListaUnidades['COD_UNIVEND']."' ".$selecionado.">".$qrListaUnidades['NOM_FANTASI']."</option> 
																"; 
															  }											
													?>	
												</select>	
											<div class="help-block with-errors"></div>
										</div>
									</div>	

									<div class="col-md-2">
										<div class="push10"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>										
								</div>
							</div>
						</div>
					</div>							
					
					<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
					<input type="hidden" class="form-control input-sm" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
					<input type="hidden" class="form-control input-sm" name="LOJAS_SELECIONADAS" id="LOJAS_SELECIONADAS" value="<?php echo $lojasSelecionadas; ?>">
					
					
			</fieldset>	
			
			</form>	
			
						<div class="push20"></div>
						
  <div class="hack1">
    <div class="hack2">
						
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

									<div class="push20"></div>
						
									<table class="table table-bordered table-hover">

										<thead>
										<tr>
										  <th class="f14 text-center"><b>Loja</b></th>
										  <th class="f14 text-center"><b>Vendas <br/>Total</th>
										  <th class="f14 text-center"><b>Vendas <br/>Identificadas</th>
										  <th class="f14 text-center"><b>Vendas <br/>Avulsas</th>
										</tr>
										</thead>

										<tbody id="relatorioConteudo">

								<?php
										
											if($cod_univend  == 9999){ $ANDcodUnivend = " AND uni.COD_UNIVEND IN($lojasSelecionadas) "; } else { $ANDcodUnivend = " AND uni.COD_UNIVEND IN($cod_univend) "; }
									
									//busca resgates - loop															
									$sql = "SELECT 
											uni.COD_UNIVEND, 
											uni.NOM_FANTASI, 
											Sum(Case When ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 Else 0 end) as VENDA_TOTAL,
										  
											(0) TOTAL_CLIENTE,
									   
											count(distinct case when ven.COD_UNIVEND = uni.COD_UNIVEND and cli.LOG_AVULSO='N'  Then  cli.COD_CLIENTE  else 0 end) as CLIENTES_COMPRA,          
										
											sum(case when cli.LOG_AVULSO = 'S' and ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 else 0 end) as AVULSO
																									
									  
										from webtools.unidadevenda uni
										Inner join vendas ven
												on ven.COD_EMPRESA = uni.COD_EMPRESA
											   and ven.COD_UNIVEND = uni.COD_UNIVEND
											   $ANDcodUnivend											   
											   and ven.DAT_CADASTR_WS >= '$dat_ini  00:00' 
											   and ven.DAT_CADASTR_WS <= '$dat_fim  23:59'        
											   AND ven.DAT_CADASTR < NOW()
										Inner join clientes cli 
												on cli.COD_CLIENTE = ven.COD_CLIENTE 
										where uni.COD_EMPRESA = $cod_empresa
										 
										group by uni.cod_univend 

										order by uni.NOM_UNIVEND limit $itens_por_pagina ";
									
									//fnEscreve($sql);	
									
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									while ($qrBuscaDados = mysqli_fetch_assoc($arrayQuery))
									  {	
										$nom_univend = $qrBuscaDados['NOM_FANTASI'];
										$venda_total = $qrBuscaDados['VENDA_TOTAL'];
										$clientes_compra = $qrBuscaDados['CLIENTES_COMPRA'];
										//$total_cliente = $qrBuscaDados['TOTAL_CLIENTE'];
										$clientes = $qrBuscaDados['CLIENTES'];
										$avulso = $qrBuscaDados['AVULSO'];
										$clientes_outras = $qrBuscaDados['CLIENTES_OUTRAS'];
										
										$masculino = $qrBuscaDados['MASCULINO'];
										$feminino = $qrBuscaDados['FEMININO'];
										$indefinido = $qrBuscaDados['INDEFINIDO'];
										$total_cliente = $masculino + $feminino + $indefinido;
										
										$totalVenda = $totalVenda + $venda_total;
										$totalFidelizado = $totalFidelizado + ($venda_total-$avulso);
										$totalAvulso = $totalAvulso + $avulso;
										$totalCliCompra = $totalCliCompra + $clientes_outras;
										$totalCliente = $totalCliente + $total_cliente;
										$totalMasculino = $totalMasculino + $masculino;
										$totalFeminino = $totalFeminino + $feminino;
										$totalIndefinido = $totalIndefinido + $indefinido;
										?>
										
										<tr>
										  <td><?php echo $nom_univend; ?></td>
										  <td class="text-right"><b class="f14 text-info"><?php echo fnValor($venda_total,0); ?></b></td>
										  <td class="text-right"><b class="f14 text-info"><?php echo fnValor(($venda_total-$avulso),0); ?></b></td>
										  <td class="text-right"><b class="f14 text-info"><?php echo fnValor($avulso,0); ?></b></td>
										</tr>

										<?php
										}
										?>
										
									
										</tbody>
										
									</table>
									<div id="carregarMaisAjax"></div>											
								</div>								
								
							</div>
		

    </div>
  </div>
    <div class="push20"></div>
		  	<div class="row">

				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-primary btn-hg carregarMais" >Carregar mais</button>
				</div>

			</div>
			
			<div class="push50"></div>

        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>		

    </body>
    <link rel="stylesheet" href="libs/bootstrap-datetimepicker.css" />
    <script>
    	
    	$(document).ready(function(){

    		var itens_por_pagina = <?php echo $itens_por_pagina; ?>;
			
			$(".carregarMais").click(function() {
				itens_por_pagina += 5;
				$.ajax({
					type: "POST",
					url: "ajxRelatorios.php?opcao=overview&itens_por_pagina="+itens_por_pagina+"&cod_univend=<?php echo fnEncode($cod_univend); ?>",
					data: $('#formulario').serialize(),
					beforeSend:function(){
						$('#carregarMaisAjax').html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						//console.log(data);	
						$(data).hide().appendTo("#relatorioConteudo").fadeIn(1000);
						$('#carregarMaisAjax').html('');
						setTimeout(function(){
							$('html, body').animate({
								scrollTop: $("#carregarMaisAjax").offset().top
							}, 1000);
						}, 500);
					},
					error:function(){
						$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					}
				});				
			});	

			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
			});			
		});

    </script>
</html>