<?php
include './_system/_functionsMain.php';

@$cod_empresa = 19;

// definir o numero de itens por pagina
@$itens_por_pagina = 5;

// Página default
@$pagina = 1;
@$dat_ini="";
@$dat_fim="";
@$tipoVenda = "T";

//inicialização de variáveis
@$hoje = fnFormatDate(date("Y-m-d"));
@$dias30=fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
//@$dias30=fnFormatDate(date('Y-m-d', strtotime($dias30. '- 7 days')));
@$cod_univend = "9999"; //todas revendas - default

if($_SESSION["COD_RETORNO"]!=''){$cod_cliente=$_SESSION["COD_RETORNO"];} else {$cod_cliente= fnDecode($_GET['secur']);} 

$MENUSTARING=fnEncode($cod_cliente);

//fnEscreve($cod_cliente);

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
		$tituloPagina = "Tokens Inválidos";
		include "menu.php"; 
		?>	

        <div class="container">
            <div class="push20"></div> 
            <div class="push20"></div> 

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
					
				<div class="row">
					
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
										  <th><small>Cliente</small></th>
										  <th><small>Convênio</small></th>
										  <th><small>Código</small></th>
										  <th><small>Loja</small></th>
										  <th><small>Data/Hora</small></th>
										  <th><small>Valor Venda</small></th>
										  <th><small>Placa</small></th>
										  <th><small>PDV</small></th>
										  <th><small>Vendedor</small></th>
										  <th><small>Token</small></th>
										  <th><small>Status</small></th>
										  <th><small>Conformidade</small></th>
										</tr>
									  </thead>

									  <tbody id="relatorioConteudo">
										
										<?php
										
											if($cod_univend  == 9999){ $ANDcodUnivend = " AND A.COD_UNIVEND IN($lojasSelecionadas) "; } else { $ANDcodUnivend = " AND a.COD_UNIVEND IN($cod_univend) "; }
										
										
											if ($tipoVenda == "T"){
												$andCreditos = " "; 
											}else{
												$andCreditos = "AND B.NUM_CARTAO != 0 "; 
											}
											
											$sql = "SELECT
												    A.COD_VENDA,														
												    A.COD_VENDAPDV,														
												    A.COD_MAQUINA,														
												    A.COD_VENDEDOR,														
												    A.COD_CUPOM,														
												    B.COD_CLIENTE,
												    B.NOM_CLIENTE,
												    B.NUM_CARTAO,
												    D.NOM_FANTASI,
												    A.DAT_CADASTR,
												    A.VAL_TOTVENDA,                                                   
												    C.NOM_USUARIO AS VENDEDOR,
												    E.NOM_USUARIO AS OPERADOR,
													F.DES_TOKEM,
													G.NOM_ENTIDAD
													FROM VENDAS A
													INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
													LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR
													LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
													LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA	
													LEFT JOIN tokem F ON F.COD_PDV = A.cod_vendapdv 	
													LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 															
													WHERE                                                 
													  A.DAT_CADASTR between '$dat_ini 00:00' AND '$dat_fim 23:59:59' 
													  AND A.COD_EMPRESA = $cod_empresa
													  $ANDcodUnivend
                                                                                                          AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8)
													  AND A.COD_CLIENTE != 58272 													  
													  order by  A.DAT_CADASTR desc  limit $itens_por_pagina";
											
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											
											$countLinha = 1;
											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
											  {
												if ($countLinha == 1){
													$vendaIni = $qrListaVendas['DAT_CADASTR'];													
												}
												
												$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];
							
												$sqlToken="select 
															itemvenda.COD_VENDA,								
															itemvenda.DES_PARAM1,
															itemvenda.DES_PARAM2,
															tokem.des_tokem,
															tokem.COD_PDV,
															tokem.cod_cliente,
															max(if(itemvenda.DES_PARAM2=tokem.des_tokem,'S','N')) temToken
															from itemvenda 
															left join tokem on itemvenda.DES_PARAM2=tokem.des_tokem
															where 
															cod_venda='".$qrListaVendas['COD_VENDA']."' limit 1 ";
														
												$tokenExec=mysqli_query(connTemp($cod_empresa,''),$sqlToken);
												$queryToken=mysqli_fetch_assoc($tokenExec);
												
												$colunaEspecial = $queryToken['DES_PARAM2'];
												if($queryToken['temToken']=='S')
												{
													if ($qrListaVendas['NUM_CARTAO'] == $queryToken['cod_cliente']) {
														$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
														$statusToken = "Token já utilizado";
														
													}else {
														$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
														$statusToken = "Token inválido";
														}

													if ($qrListaVendas['NUM_CARTAO'] != $queryToken['cod_cliente'] ){																																												//$temToken = '<i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i>';
															$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
															$statusToken = "Token pertence a outro usuario";
													}
													
												}elseif (!empty($qrListaVendas['NUM_CARTAO']) &&
															($queryToken['des_tokem'] != $queryToken['DES_PARAM2'])) {
																$temToken = '<i class="fa fa-lock text-danger" aria-hidden="true"></i>';
																$statusToken = "Token inexistente";
												}else {
													$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
													
															if (!empty($queryToken['DES_PARAM1'])){
															$temToken = '<i class="fa fa-unlock-alt text-warning" aria-hidden="true"></i>';
															$statusToken = "Token não informado";
															} else {$statusToken = "";}
										                }
												
                                                                                                
												if ($qrListaVendas['COD_CLIENTE'] == 58272) {													
													$temToken = ""; }
												
												if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1'])) ) {													
													$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
													$statusToken = "Cliente não cadastrado"; }
													
												?>
													<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
													  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
													  <td><small><?php echo $qrListaVendas['NOM_ENTIDAD']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
													  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
													  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
													  <td><small><?php echo $queryToken['DES_PARAM1']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['COD_MAQUINA']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['COD_VENDEDOR']; ?></small></td>
													  <td><small><?php echo $queryToken['DES_PARAM2']; ?> </small></td>
													  <td class="text-center"><small><?php echo $temToken; ?></small></td>
													  <td class="text-center"><small><?php echo $statusToken; ?></small></td>
													</tr>
												<?php
												
											  $vendaFim = $qrListaVendas['DAT_CADASTR'];
											  $countLinha++;	
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
					url: "ajxRelatorios.php?opcao=tokensNOK&itens_por_pagina="+itens_por_pagina+"&cod_univend=<?php echo fnEncode($cod_univend); ?>",
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