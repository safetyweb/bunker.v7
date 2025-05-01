<?php

	//echo fnDebug('true');

$log_ativo = 'N';

if(isset($_GET['pop'])){
	$popUp = fnLimpaCampo($_GET['pop']);
}else{
	$popUp = '';
}

$cod_template = "";

$hashLocal = mt_rand();	

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

		$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
		$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
		$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
		$des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != ''){				

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
	$cod_campanha = fnDecode($_GET['idc']);	
	$cod_empresa = fnDecode($_GET['id']);	
	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	

		//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)){
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}

}else {	
	$nom_empresa = "";
}

	//fnMostraForm();
	//fnEscreve($cod_checkli);

?>

<style>
	body{
		overflow: hidden;
		overflow-x: scroll;
	}
	.change-icon .fa + .fa,
	.change-icon:hover .fa:not(.fa-edit) {
		display: none;
	}
	.change-icon:hover .fa + .fa:not(.fa-edit){
		display: inherit;
	}
	
	.fa-edit:hover{
		color: #18bc9c;
		cursor: pointer;
	}

	.tile{
		cursor: pointer;
	}
	
	.item{
		padding-top: 0;
	}
</style>
<!-- 	<link rel="stylesheet" href="css/widgets.css" /> -->

<div class="row portlet">				
	
	<div class="col-md-12 margin-bottom-30">

			<div class="portlet-body">

				<h4 style="margin: 0 0 5px 0;"><span class="bolder">Visão Geral de e-Mails</span></h4>
				<div class="push20"></div>						

				<div class="col-md-12">									

					<table class="table table-bordered table-striped table-hover tablesorter">
						<!-- TOT_QTD
						TOT_CONTATOS
						TOT_EXCLUSAO
						TOT_DISPARADOS
						TOT_SUCESSO
						TOT_FALHA -->
						<thead>
							<tr>
								<th class="{ sorter: false }"></th>
								<th>Lote</th>
								<th>Data de Envio</th>
								<th>Data de Disparo</th>
								<th>Enviados<br/><br/><span id="TOT_QTD"></span></th>
								<th>Filtrados<br/><br/><span id="TOT_CONTATOS"></span></th>
								<th>Exclusão<br/><br/><span id="TOT_EXCLUSAO"></span></th>
								<th>Disparados<br/><br/><span id="TOT_DISPARADOS"></span></th>
								<th>Sucesso<br/><br/><span id="TOT_SUCESSO"></span></th>
								<th>% Sucesso </th>
								<th>Falhas <br/><br/><span id="TOT_FALHA"></span></th>
								<th>% Falhas </th>
								<th>Lidos </th>
								<th>% Lidos </th>
								<th>Não Lidos </th>
								<th>% Não Lidos </th>
								<th>Opt Out </th>
								<th>Cliques </th>
							</tr>
						</thead>

						<tbody id="listaTemplates">

							<?php 
							$sql = "SELECT
							CEM.COD_DISPARO,
							SUM(CEM.QTD_DIFERENCA) AS QTD_DIFERENCA,
							SUM(CEM.QTD_CONTATOS) AS QTD_CONTATOS,
							SUM(CEM.QTD_EXCLUSAO) AS QTD_EXCLUSAO,
							SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS,
							SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
							SUM(CEM.QTD_FALHA) AS QTD_FALHA,
							SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
							SUM(CEM.QTD_NLIDOS) AS QTD_NLIDOS,
							SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
							SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
							CEM.DAT_ENVIO AS DAT_DISPARO,
							TE.NOM_TEMPLATE,
							EL.QTD_LISTA,
							EL.DES_PATHARQ,
							EL.COD_GERACAO,
							EL.COD_CONTROLE,
							EL.COD_LOTE,
							EL.DAT_AGENDAMENTO AS DAT_ENVIO,
							EL.LOG_TESTE
							FROM EMAIL_LOTE EL
							LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
							LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
							WHERE EL.COD_EMPRESA = $cod_empresa
							AND EL.COD_CAMPANHA = $cod_campanha
							AND EL.LOG_ENVIO = 'S'
							GROUP BY EL.COD_DISPARO_EXT
							ORDER BY EL.COD_CONTROLE DESC, CEM.DAT_ENVIO ASC";

											// fnEscreve($sql);

													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

													$count=0;
													$alturaTela = 600;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
														$count++;

														if($qrBuscaModulos['DES_PATHARQ'] != ''){
															$urlAnexo = $qrBuscaModulos['DES_PATHARQ'];
														}else{
															$urlAnexo = '';
														}

														if($qrBuscaModulos['COD_GERACAO'] != ''){
															$pref = $qrBuscaModulos['COD_GERACAO'];
														}else{
															if($qrBuscaModulos['LOG_TESTE'] != 'S'){
																$pref = 'ANIV';
															}else{
																$pref = 'TESTE';
															}
														}

														$pct_sucesso = ($qrBuscaModulos['QTD_SUCESSO']/$qrBuscaModulos['QTD_DISPARADOS'])*100;
														$pct_falha = ($qrBuscaModulos['QTD_FALHA']/$qrBuscaModulos['QTD_DISPARADOS'])*100;
														$pct_lidos = ($qrBuscaModulos['QTD_LIDOS']/$qrBuscaModulos['QTD_DISPARADOS'])*100;
														$pct_nlidos = ($qrBuscaModulos['QTD_NLIDOS']/$qrBuscaModulos['QTD_DISPARADOS'])*100;

														?>

														<tr>
															<!-- <td class="text-center"><small><?=$urlAnexo?></small></td> -->
															<td class="text-center">
																<small>
																	<div class="dropdown">
																		<a class="dropdown-toggle transparency" data-toggle="dropdown" href="#">
																			<span class="fas fa-ellipsis-v"></span>
																		</a>
																		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																			<li><a tabindex="-1" href="<?=$qrBuscaModulos[DES_PATHARQ]?>" download>Lista enviada</a></li>
																			<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('sent','<?=$qrBuscaModulos[COD_DISPARO]?>')">Sucesso</a></li>
																			<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('lidos','<?=$qrBuscaModulos[COD_DISPARO]?>')">Lidos</a></li>
																			<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('nlidos','<?=$qrBuscaModulos[COD_DISPARO]?>')">Não-lidos</a></li>
																			<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('hbounce','<?=$qrBuscaModulos[COD_DISPARO]?>')">Hardbounce</a></li>
																			<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('sbounce','<?=$qrBuscaModulos[COD_DISPARO]?>')">Softbounce</a></li>
																			<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('optout','<?=$qrBuscaModulos[COD_DISPARO]?>')">Opt-Out</a></li>
																			<!-- <li class="divider"></li> -->
																			<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																		</ul>
																	</div>
																</small>
															</td>
															<td><small><small><?=$pref?></small>&nbsp;Geração do lote #<?=$qrBuscaModulos['COD_CONTROLE']?>/<?=$qrBuscaModulos['COD_LOTE']?></small>&nbsp;<span class="f10"><?=$qrBuscaModulos['COD_DISPARO']?></span></td>
															<td><small><?=fnDataFull($qrBuscaModulos['DAT_ENVIO'])?></small></td>
															<td><small><?=$qrBuscaModulos['DAT_DISPARO']?></small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_LISTA'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_CONTATOS'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_EXCLUSAO']+$qrBuscaModulos['QTD_DIFERENCA'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_DISPARADOS'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_SUCESSO'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($pct_sucesso,2)?>%</small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_FALHA'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($pct_falha,2)?>%</small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_LIDOS'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($pct_lidos,2)?>%</small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_NLIDOS'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($pct_nlidos,2)?>%</small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_OPTOUT'],0)?></small></td>
															<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_CLIQUES'],0)?></small></td>
														</tr>

														<?php

														$tot_qtd += $qrBuscaModulos['QTD_LISTA'];
														$tot_disparados += $qrBuscaModulos['QTD_DISPARADOS'];
														$tot_sucesso += $qrBuscaModulos['QTD_SUCESSO'];
														$tot_falha += $qrBuscaModulos['QTD_FALHA'];
														$tot_contatos += $qrBuscaModulos['QTD_CONTATOS'];
														$tot_exclusao += $qrBuscaModulos['QTD_EXCLUSAO']+$qrBuscaModulos['QTD_DIFERENCA'];

														$alturaTela += 75;

													}
													?>

												</tbody>
												<tfoot>
													<tr>
														<th colspan="100">
															<a class="btn btn-info btn-sm" onclick="parent.exportaRel('all','0')"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
														</th>
													</tr>
												</tfoot>
											</table>
											<script>
												$("#TOT_QTD").text("<?=fnValor($tot_qtd,0)?>");
												$("#TOT_CONTATOS").text("<?=fnValor($tot_contatos,0)?>");
												$("#TOT_EXCLUSAO").text("<?=fnValor($tot_exclusao,0)?>");
												$("#TOT_DISPARADOS").text("<?=fnValor($tot_disparados,0)?>");
												$("#TOT_SUCESSO").text("<?=fnValor($tot_sucesso,0)?>");
												$("#TOT_FALHA").text("<?=fnValor($tot_falha,0)?>");
											</script>

								<!--
								<div class="row">
									<div class="col-xs-12">
										<a href="javascript:void(0)" class="btn btn-xs btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1409)?>&id=<?php echo fnEncode($cod_empresa)?>&tipo=<?php echo fnEncode('CAD')?>&pop=true" data-title="Template do Email" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'><i class="fa fa-plus fa-2x" aria-hidden="true" style="padding: 5px 5px;"></i></a>
									</div>	
								</div>
							-->
							
						</div>			
					</div>			
				</div>			
			</div>							



		<input type="hidden" class="input-sm" name="REFRESH_TEMPLATES" id="REFRESH_TEMPLATES" value="N">

		<!-- modal -->									
	<!-- <div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>		
			</div>
		</div>
	</div>	 -->

	<div class="push20"></div> 
	
	<script type="text/javascript">

		$(document).ready(function(){


			$(".tablesorter").bind("tablesorter-initialized",function(e, table) {
				parent.$("#conteudoAba").css("height", $('.portlet').height() + "px");
			});

		});
		
		function RefreshTemplates(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshTemplatesEmail.php",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#listaTemplates").html(data); 
				},
				error:function(){
					$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}

		function retornaForm(index){
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_"+index).val());
			$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_"+index).val());
			$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_"+index).val());
			$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_"+index).val());
			$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_"+index).val());
			$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_"+index).val());
			$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_"+index).val());
			$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_"+index).val());
			$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	