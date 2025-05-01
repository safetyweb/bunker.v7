<?php
	
	echo fnDebug('true');

	$hashLocal = mt_rand();

	$cod_univend = "";
	$nom_fantasi = "";

	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;
 
			$cod_consumo = fnLimpaCampoZero($_POST['COD_CONSUMO']);			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_categor = fnLimpaCampoZero($_POST['COD_CATEGOR']);
			$cod_entidad  = fnLimpaCampoZero($_POST['COD_ENTIDAD']);
			$qtd_limite = fnLimpaCampo($_POST['QTD_LIMITE']);
			$cod_tpunida  = fnLimpaCampoZero($_POST['COD_TPUNIDA']);
			$tip_limite  = fnLimpaCampo($_POST['TIP_LIMITE']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
			
				$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
       
				if ($opcao == 'CAD'){
					$sql = "INSERT INTO totem_app(
								COD_EMPRESA, 
								DES_LOGO, 
								DES_IMGBACK, 
								COR_BACKBAR, 
								COR_BACKPAG, 
								COR_TITULOS, 
								COR_TEXTOS, 
								COR_BOTAO, 
								COR_BOTAOON, 
								COR_FULLPAG, 
								COR_TEXTFULL) 
								VALUES (
								'$cod_empresa', 
								'$des_logo', 
								'$des_imgback', 
								'$cor_backbar', 
								'$cor_backpag', 
								'$cor_titulos', 
								'$cor_textos', 
								'$cor_botao', 
								'$cor_botaoon', 
								'$cor_fullpag', 
								'$cor_textfull'
								)";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
					fnEscreve($sql); 					
					
				}
				
				if ($opcao == 'ALT'){
					$sql = "UPDATE TOTEM_APP SET 
								DES_LOGO = '$des_logo', 
								DES_IMGBACK = '$des_imgback', 
								COR_BACKBAR = '$cor_backbar', 
								COR_BACKPAG = '$cor_backpag', 
								COR_TITULOS = '$cor_titulos', 
								COR_TEXTOS = '$cor_textos', 
								COR_BOTAO = '$cor_botao', 
								COR_BOTAOON = '$cor_botaoon', 
								COR_FULLPAG = '$cor_fullpag', 
								COR_TEXTFULL = '$cor_textfull'
								WHERE COD_APP = $cod_app and COD_EMPRESA = $cod_empresa ";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
					fnEscreve($sql);					
					
				}
				
				

				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			} 

		}
	} 
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		}
		
		
		//busca dados da entidade
		if (@$_GET['idU'] <> ""){
			$cod_univend = fnDecode(@$_GET['idU']);	
		}

		$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '".$cod_empresa."' and COD_UNIVEND = '".$cod_univend."'";
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		//fnEscreve($sql);
		
		$qrBuscaUnidade = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaUnidade)){
			$cod_univend = $qrBuscaUnidade['COD_UNIVEND'];
			$nom_fantasi = $qrBuscaUnidade['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		$nom_empresa = "";		
		$cod_entidad = 0;		
		$nom_entidad = "";
	
	}
	
	//fnMostraForm();
	//fnEscreve($cod_empresa);
	
?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
</style>

			
					<?php if ($popUp != "true"){  ?>							
					<div class="push30"></div> 
					<?php } ?>
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<?php if ($popUp != "true"){  ?>							
							<div class="portlet portlet-bordered">
							<?php } else { ?>
							<div class="portlet" style="padding: 0 20px 20px 20px;" >
							<?php } ?>
							
								<?php if ($popUp != "true"){  ?>
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<?php } ?>								
								
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>
									
									<?php 
										$abaMetas = 1333;
										include "abasUsuariosMetas.php";
									?>									
									
									<div class="push30"></div> 
																
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
											
													
										<div class="row">									
											
											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Empresa</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
													<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
												</div>														
											</div>
											<?php
											if (@$cod_univend != ""){
											?>
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Unidade</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_FANTASI" id="NOM_FANTASI" value="<?php echo $nom_fantasi; ?>">
														<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo $cod_univend; ?>">
													</div>														
												</div>
	
												<?php 

													$sql = "SELECT DAT_INICIO, DAT_FIM, VAL_RATEIO FROM VALOR_RATEIO 
													WHERE 1=1 ".(@$cod_univend != ""?" AND COD_UNIVEND IN ($cod_univend)":"")."
													AND COD_RATEIO = (SELECT MAX(COD_RATEIO) FROM VALOR_RATEIO)";
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
													$qrData = mysqli_fetch_assoc($arrayQuery);

												?>

												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label">Data Inicial</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_INICIAL" id="DAT_INICIAL" value="<?=date('d/m/Y',strtotime($qrData['DAT_INICIO']))?>">
														<input type="hidden" name="DAT_INI" id="DAT_INI" value="<?=$qrData['DAT_INICIO']?>">
													</div>														
												</div>

												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label">Data Final</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_FINAL" id="DAT_FINAL" value="<?=date('d/m/Y',strtotime($qrData['DAT_FIM']))?>">
														<input type="hidden" name="DAT_FIM" id="DAT_FIM" value="<?=$qrData['DAT_FIM']?>">
													</div>														
												</div>
												
												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label">Valor de Rateio</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="VALOR_RATEIO" id="VALOR_RATEIO" maxlength="50" value="R$ <?=fnValor($qrData['VAL_RATEIO'],2)?>"required>
														<input type="hidden" name="VAL_RATEIO" id="VAL_RATEIO" value="<?=$qrData['VAL_RATEIO']?>">
													</div>
												</div>
											<?php }?>
										
										</div>
																						
										<input type="hidden" name="LOG_HABITKT" id="LOG_HABITKT" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
									
										
										<div class="push30"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
											
												
												<table id="edit" class="table table-bordered table-striped table-hover">
												<?php
													$scripts = "";
													$count=0;
													$sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa".
															(@$cod_univend != ""?" AND COD_UNIVEND IN ($cod_univend)":"").
															" AND LOG_ESTATUS = 'S' AND LOG_ATIVOMETA='S'
															AND (COD_EXCLUSA IS NULL OR COD_EXCLUSA = 0) ORDER BY TRIM(NOM_FANTASI)";
													//fnEscreve($sql);
                                                    $arrayQueryUni = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQueryUni)){
														$count++;
														$cod_univend = $qrListaUniVendas['COD_UNIVEND'];
														$nom_fantasi = $qrListaUniVendas['NOM_FANTASI'];
														$cod_empresa = $qrListaUniVendas['COD_EMPRESA'];


														$sql = "SELECT DAT_INICIO, DAT_FIM, VAL_RATEIO FROM VALOR_RATEIO 
														WHERE 1=1 ".(@$cod_univend != ""?" AND COD_UNIVEND IN ($cod_univend)":"")."
														AND COD_RATEIO = (SELECT MAX(COD_RATEIO) FROM VALOR_RATEIO)";
														//fnEscreve($sql);
														$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
														$qrData = mysqli_fetch_assoc($arrayQuery);
												?>
													  <thead>
														<tr style="height: 61px;">
															<th style="background-color:#BBB;position: sticky;top:0;z-index:10;">
																<?=$cod_univend." - ".$nom_fantasi?>
															</th>
															<th style="background-color:#BBB;position: sticky;top:0;z-index:10;" class="text-center"><?=($qrData['DAT_INICIO'] <> ""?"Data Inicial<br>".date('d/m/Y',strtotime($qrData['DAT_INICIO'])):"")?></th>
															<th style="background-color:#BBB;position: sticky;top:0;z-index:10;" class="text-center"><?=($qrData['DAT_FIM'] <> ""?"Data Final<br>".date('d/m/Y',strtotime($qrData['DAT_FIM'])):"")?></th>
															<th style="background-color:#BBB;position: sticky;top:0;z-index:10;" class="text-center"><?=($qrData['VAL_RATEIO'] <> ""?"Valor de Rateio<br>".number_format($qrData['VAL_RATEIO'],2,",","."):"")?></th>
														</tr>
														<tr>
														  <th width="25%" style="position: sticky;top:50px;z-index:10;background-color:#E5E7E9;"></th>
														  <th class="text-center" width="25%" style="position: sticky;top:50px;z-index:10;background-color:#E5E7E9;">VALOR PRÊMIO</th>
														  <th class="text-center" width="25%" style="position: sticky;top:50px;z-index:10;background-color:#E5E7E9;">RATEIO (%)</th>
														  <th class="text-center" width="25%" style="position: sticky;top:50px;z-index:10;background-color:#E5E7E9;">META</th>
														</tr>
													  </thead>
													  
													<tbody id="relatorioConteudo" data-univend="<?=$cod_univend?>">

														<?php

															$sqlMeta = "SELECT * FROM CONTROLE_METAS CM
															LEFT JOIN MATRIZ_RATEIO MR ON MR.COD_RATEIO = CM.COD_REGISTRO
															WHERE CM.COD_REGISTRO = (SELECT MAX(COD_REGISTRO) FROM CONTROLE_METAS WHERE COD_EMPRESA = $cod_empresa AND COD_UNIDADE=$cod_univend)";
															//fnEscreve($sqlMeta);
															$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sqlMeta) or die(mysqli_error());
															$qrMeta = mysqli_fetch_assoc($arrayQuery);
															$cod_matriz = fnLimpaCampoZero($qrMeta['COD_MATRIZ']);
														?>

														<tr>
															<td>Produtos de Incentivo</td>
															<td class="text-center">R$ <span class="valor vl1"></span></td>
															<td class='text-center'>
																	<a href="#" class="editable" 
																		data-type='text' 
																		data-title='Editar' 
																		data-rateio="<?=$qrMeta['COD_REGISTRO']?>"
																		data-pk="<?=$cod_matriz?>" 
																		data-name="PCT_DESTAQUE" 
																		data-univend="<?=$cod_univend?>" 
																		data-empresa="<?=$cod_empresa?>"
																		data-class=".vl1"
																		data-percent1="PCT_PRODUTOS"
																		data-percent2="PCT_OBJETIVO"
																		data-percent3="PCT_FIDELIDADE"
																		>
																		<?=fnValor($qrMeta['PCT_DESTAQUE'],2)?>
																	</a>
																  </td>	
															<td class="text-center"><?=fnValor($qrMeta['VAL_METADEST'],2)?> <small>LITROS</small></td>
														</tr>

														<tr>
															<td>Demais Produtos</td>
															<td class="text-center">R$ <span class="valor vl2"></span></td>
															<td class='text-center'>
																	<a href="#" class="editable" 
																		data-type='text' 
																		data-title='Editar' 
																		data-rateio="<?=$qrMeta['COD_REGISTRO']?>"
																		data-pk="<?=$cod_matriz?>" 
																		data-name="PCT_PRODUTOS" 
																		data-univend="<?=$cod_univend?>" 
																		data-empresa="<?=$cod_empresa?>"
																		data-class=".vl2"
																		data-percent1="PCT_DESTAQUE"
																		data-percent2="PCT_OBJETIVO"
																		data-percent3="PCT_FIDELIDADE"
																		>
																		<?=fnValor($qrMeta['PCT_PRODUTOS'],2)?>
																	</a>
																  </td>	
															<td class="text-center"><?=fnValor($qrMeta['VAL_METAPROD'],2)?> <small>LITROS</small></td>
														</tr>	

														<tr>
															<td>Acima de 20%</td>
															<td class="text-center">R$ <span class="valor vl3"></span></td>
															<td class='text-center'>
																	<a href="#" class="editable" 
																		data-type='text' 
																		data-title='Editar' 
																		data-rateio="<?=$qrMeta['COD_REGISTRO']?>"
																		data-pk="<?=$cod_matriz?>" 
																		data-name="PCT_OBJETIVO" 
																		data-univend="<?=$cod_univend?>" 
																		data-empresa="<?=$cod_empresa?>"
																		data-class=".vl3"
																		data-percent1="PCT_DESTAQUE"
																		data-percent2="PCT_PRODUTOS"
																		data-percent3="PCT_FIDELIDADE"
																		>
																		<?=fnValor($qrMeta['PCT_OBJETIVO'],2)?>
																	</a>
																  </td>	
															<td class="text-center"><?=fnValor($qrMeta['VAL_ALERTMIN'],2)?> <small>LITROS</small></td>
														</tr>

														<tr>
															<td>Fidelidade</td>
															<td class="text-center">R$ <span class="valor vl4"></span></td>
															<td class='text-center'>
																	<a href="#" class="editable" 
																		data-type='text' 
																		data-title='Editar' 
																		data-rateio="<?=$qrMeta['COD_REGISTRO']?>"
																		data-pk="<?=$cod_matriz?>" 
																		data-name="PCT_FIDELIDADE"
																		data-univend="<?=$cod_univend?>" 
																		data-empresa="<?=$cod_empresa?>"
																		data-class=".vl4"
																		data-percent1="PCT_DESTAQUE"
																		data-percent2="PCT_PRODUTOS"
																		data-percent3="PCT_OBJETIVO"
																		>
																		<?=fnValor($qrMeta['PCT_FIDELIDADE'],2)?>
																	</a>
																  </td>	
															<td class="text-center"><?=fnValor($qrMeta['QTD_FIDELIZ'],2)?> <small>LITROS</small></td>
														</tr>

														<tr>
															<td></td>
															<td></td>
															<td class="text-center"><b><span class="TOTAL"></span></b></td>
															<td>
																<input type="hidden" name="VAL_RATEIO" value="<?=$qrData['VAL_RATEIO']?>">
																<input type="hidden" name="PCT_DESTAQUE" value="<?=$qrMeta['PCT_DESTAQUE']?>">
																<input type="hidden" name="PCT_PRODUTOS" value="<?=$qrMeta['PCT_PRODUTOS']?>">
																<input type="hidden" name="PCT_OBJETIVO" value="<?=$qrMeta['PCT_OBJETIVO']?>">
																<input type="hidden" name="PCT_FIDELIDADE" value="<?=$qrMeta['PCT_FIDELIDADE']?>">
															</td>
														</tr>	
														
													</tbody>
													<?php
													$scripts .= "calc_valores($cod_univend);";
													?>
													
													<!-- <tfoot>
														<tr>
														  <th class="" colspan="100">
															<center><ul id="paginacao" class="pagination-sm"></ul></center>
														  </th>
														</tr>
													</tfoot> -->
												<?php
													}
												?>
												
												</table>

											</div>
											
										</form>	
											
										</div>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>
	
					<!-- modal -->									
					<div class="modal fade" id="popModalAux" tabindex='-1'>
						<div class="modal-dialog" style="">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title"></h4>
								</div>
								<div class="modal-body">
									<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
								</div>		
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->					
						
					<div class="push20"></div>

					<div id="atualizaValor"></div>
					
 	<script>

		function calc_valores(cod_univend){
			var $el = $("tbody[data-univend="+cod_univend+"]");
			var valor = $el.find("[name=VAL_RATEIO]").val();
			var destaque = $el.find("[name=PCT_DESTAQUE]").val()*1;
			var produtos = $el.find("[name=PCT_PRODUTOS]").val()*1;
			var objetivo = $el.find("[name=PCT_OBJETIVO]").val()*1;
			var fidelidade = $el.find("[name=PCT_FIDELIDADE]").val()*1;
	
			$el.find('.vl1').text((valor*destaque/100).toFixed(2));
			$el.find('.vl2').text((valor*produtos/100).toFixed(2));
			$el.find('.vl3').text((valor*objetivo/100).toFixed(2));
			$el.find('.vl4').text((valor*fidelidade/100).toFixed(2));
			$el.find('.TOTAL').text((destaque+produtos+objetivo+fidelidade).toFixed(2));
		}
		<?=$scripts?>
		
        $(document).ready( function() {       	

        	$.fn.editable.defaults.mode = 'popup';
        	$('.editable-input .input-sm').mask('000.000.000.000.000,00', {reverse: true});
        	$('.valor,#TOTAL').mask("#.##0,00", {reverse: true});

        	$('.editable').editable({
        		url: '/ajxUsuarioRateio.php',
        		ajaxOptions:{type:'post'},
        		params: function(params) {
			        params.rateio = $(this).data('rateio');
			        params.univend = $(this).data('univend');
			        params.empresa = $(this).data('empresa');
			        params.class = $(this).data('class');
			        params.percent1 = $(this).data('percent1');
			        params.percent2 = $(this).data('percent2');
			        params.percent3 = $(this).data('percent3');
			        //params.rateio = $("#VAL_RATEIO").val();
			        return params;
			    },
        		success:function(data){
					console.log(data);
					$('#atualizaValor').html(data);
				},
				error: function(xmlHTTP) {
					console.clear();
			       $('.popover-content').append(xmlHTTP.responseText);
			       $(document).keyup(function(){
			       		$('.msg-erro').hide();
			       });
			    }
        	});
        	

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
        });
		
		function retornaForm(index){
			
			$("#formulario #COD_CONSUMO").val($("#ret_COD_CONSUMO_"+index).val());
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val());
			$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val());
			$("#formulario #QTD_LIMITE").val($("#ret_QTD_LIMITE_"+index).val());
			$("#formulario #COD_TPUNIDA").val($("#ret_COD_TPUNIDA_"+index).val()).trigger("chosen:updated");
			$("#formulario #TIP_LIMITE").val($("#ret_TIP_LIMITE_"+index).val()).trigger("chosen:updated");
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>
   