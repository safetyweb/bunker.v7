<?php
 
$hashLocal = mt_rand();	

$mod = fnDecode($_GET['mod']);

$modRetorno = 1348;


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
		
		$cod_tarefa = fnLimpaCampoZero($_REQUEST['COD_TAREFA']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_subtarefa = fnLimpaCampoZero($_REQUEST['COD_SUBTAREFA']);
		$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENIO']);
		$cod_controle = fnLimpaCampoZero($_REQUEST['COD_CONTROLE']);
		$tip_tarefa = fnLimpaCampoZero($_REQUEST['TIP_TAREFA']);
		$nom_tarefa = fnLimpaCampo($_REQUEST['NOM_TAREFA']);
		// $pct_tarefa = fnLimpaCampoZero($_REQUEST['PCT_TAREFA']);
		$val_projeto = fnvalorsql($_REQUEST['VAL_PROJETO']);
		$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);
		if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		$cod_sistema = $_SESSION['SYS_COD_SISTEMA'];
		
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$num_ordenac = 1;

		$sqlOrdenac = "SELECT MAX(NUM_ORDENAC) NUM_ORDENAC 
						FROM TAREFA 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CONVENIO = $cod_conveni";

		// fnEscreve($sqlOrdenac);

		$qrOrdenac = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlOrdenac));

		if($qrOrdenac[NUM_ORDENAC] != ""){
			$num_ordenac = $qrOrdenac[NUM_ORDENAC]+1;
		}
                  
		if ($opcao != ''){

			switch ($opcao)
			{
				case 'CAD':

					$sqlReordena = "SELECT MAX(NUM_ORDENAC) NUM_ORDEMTASK FROM TAREFA WHERE COD_TAREFA = $cod_subtarefa";

					// fnEscreve($sqlReordena);

					$qrOrdemtask = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlReordena));

					if($qrOrdemtask[NUM_ORDEMTASK] != ""){
						$num_ordenac = $qrOrdemtask[NUM_ORDEMTASK]+1;

						// fnEscreve($num_ordenac);

						$sqlUpdtOrdem = "UPDATE TAREFA SET
												NUM_ORDENAC = (NUM_ORDENAC+1)
										WHERE COD_CONVENIO = $cod_conveni
										AND COD_EMPRESA = $cod_empresa
										AND NUM_ORDENAC >= $num_ordenac";

						// fnEscreve($sqlUpdtOrdem);

						mysqli_query(connTemp($cod_empresa,''),$sqlUpdtOrdem);

					}


					$sql = "INSERT INTO TAREFA(
											COD_EMPRESA,
											COD_SISTEMA,
											COD_SUBTAREFA,
											NUM_ORDENAC,
											COD_CONVENIO,
											COD_CONTROLE,
											TIP_TAREFA,
											NOM_TAREFA,
											VAL_PROJETO,
											LOG_ATIVO,
											DAT_INI,
											DAT_FIM,
											COD_USUCADA
										) VALUES(
											'$cod_empresa',
											'$cod_sistema',
											'$cod_subtarefa',
											'$num_ordenac',
											'$cod_conveni',
											'$cod_controle',
											'$tip_tarefa',
											'$nom_tarefa',
											'$val_projeto',
											'$log_ativo',
											'$dat_ini',
											'$dat_fim',
											'$cod_usucada'
										)";

					// fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa,''),$sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	

				break;

				case 'ALT':

					$sql = "UPDATE TAREFA SET
										COD_SUBTAREFA = '$cod_subtarefa',
										COD_CONTROLE = '$cod_controle',
										TIP_TAREFA = '$tip_tarefa',
										NOM_TAREFA = '$nom_tarefa',
										VAL_PROJETO = '$val_projeto',
										LOG_ATIVO = '$log_ativo',
										DAT_INI = '$dat_ini',
										DAT_FIM = '$dat_fim',
										DAT_ALTERAC = NOW(),
										COD_ALTERAC = $cod_usucada
							WHERE COD_TAREFA = $cod_tarefa
							AND COD_EMPRESA = $cod_empresa";

					mysqli_query(connTemp($cod_empresa,''),$sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";	

				break;

				case 'EXC':

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

				break;

			}
			if($popUp == 'true'){
			?>
				<script>
					parent.location.reload();
				</script>
			<?php 	
			}	
			$msgTipo = 'alert-success';
		}                
	}
}
	
//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
        
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	$cod_conveni = fnDecode($_GET['idc']);
	$tarefas = json_decode($_GET['idt'],true);

	// echo "<pre>";
	// print_r($tarefas);
	// echo "</pre>";

	$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
		
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaTemplate)){
		$cod_conveni = $qrBuscaTemplate['COD_CONVENI'];
		$cod_entidad = $qrBuscaTemplate['COD_ENTIDAD'];
		$num_process = $qrBuscaTemplate['NUM_PROCESS'];
		$num_conveni = $qrBuscaTemplate['NUM_CONVENI'];
		$cod_tpconveni = $qrBuscaTemplate['COD_TPCONVENI'];
		$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
		$nom_abrevia = $qrBuscaTemplate['NOM_ABREVIA'];
		$des_descric = $qrBuscaTemplate['DES_DESCRIC'];
		$val_valor = fnValor($qrBuscaTemplate['VAL_VALOR'],2);
		$val_conced = fnValor($qrBuscaTemplate['VAL_CONCED'],2);
		$val_contpar = fnValor($qrBuscaTemplate['VAL_CONTPAR'],2);
		$dat_inicinv = fnDataShort($qrBuscaTemplate['DAT_INICINV']);
		$dat_fimconv = fnDataShort($qrBuscaTemplate['DAT_FIMCONV']);
		$dat_assinat = fnDataShort($qrBuscaTemplate['DAT_ASSINAT']);
		$log_licitacao = $qrBuscaTemplate['LOG_LICITACAO'];
	
	}


	$sqlAd = "SELECT * FROM TERMOADITIVO 
			WHERE COD_EMPRESA = $cod_empresa
			AND TIP_ADITIVO = 'P'
			AND COD_CONVENI = $cod_conveni
			ORDER BY 1 DESC
			LIMIT 1";

	$arrayAd = mysqli_query(connTemp($cod_empresa,''),$sqlAd);
	$qrAditivo = mysqli_fetch_assoc($arrayAd);

	if($qrAditivo[DAT_FINAL] != ""){

		$dat_aditivo = fnDataShort($qrAditivo[DAT_FINAL]);

	}else{

		$dat_aditivo = "";

	}


	$leitura = "disabled";

	$cod_subtarefa = fnLimpaCampoZero($tarefas[id]);

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaEmpresa)){
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
											
}else {	
	$nom_empresa = "";
	$cod_conveni = "";
	$cod_entidad = "";
	$num_process = "";
	$num_conveni = "";
	$cod_tpconveni = "";
	$nom_conveni = "";
	$nom_abrevia = "";
	$des_descric = "";
	$val_valor = "";
	$val_conced = "";
	$val_contpar = "";
	$dat_inicinv = "";
	$dat_fimconv = "";
	$dat_assinat = "";
	$log_licitacao = "";
	$dat_aditivo = "";
	$leitura = "";
}


if($cod_subtarefa != 0 && ($cod_tarefa == 0 || $cod_tarefa == "")){
	$subtarefa = true;
	$cod_tarefasql = $cod_subtarefa;
}else{
	$subtarefa = false;
	$cod_tarefasql = $cod_tarefa;
}	


$sqlTarefa = "SELECT * FROM TAREFA WHERE COD_TAREFA = $cod_tarefasql AND COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlTarefa);

$arrayTarefa = mysqli_query(connTemp($cod_empresa,''),$sqlTarefa);
$qrTarefa = mysqli_fetch_assoc($arrayTarefa);
	
if (isset($qrTarefa)){
	$cod_tarefa = $qrTarefa['COD_TAREFA'];
	$nom_tarefa = $qrTarefa['NOM_TAREFA'];
	$cod_subtarefa = $qrTarefa['COD_SUBTAREFA'];
	$cod_controle = $qrTarefa['COD_CONTROLE'];
	$tip_tarefa = $qrTarefa['TIP_TAREFA'];
	$nom_tarefa = $qrTarefa['NOM_TAREFA'];
	$pct_tarefa = $qrTarefa['PCT_TAREFA'];
	$log_ativo = $qrTarefa['LOG_ATIVO'];
	$dat_ini = $qrTarefa['DAT_INI'];
	$dat_fim = $qrTarefa['DAT_FIM'];
}else{
	$cod_tarefa = "0";
	$cod_subtarefa = "0";
	$cod_controle = "0";
	$tip_tarefa = "0";
	$nom_tarefa = "";
	$pct_tarefa = 0;
	$log_ativo = "S";
	$dat_ini = "";
	$dat_fim = "";
}

$sql = "SELECT SUM(A.VAL_VALOR) AS VAL_VALOR,SUM(A.VAL_CONVENI)AS VAL_CONCED,SUM(A.VAL_CONTPAR)AS VAL_CONTPAR,B.NOM_CONVENI,B.NUM_CONVENI
		FROM CONTRATO A,CONVENIO B 
		WHERE 
		A.COD_CONVENI=B.COD_CONVENI AND 
		A.COD_CONVENI = $cod_conveni AND 
		A.DES_TPCONTRAT='CON'";	

//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrConveni = mysqli_fetch_assoc($arrayQuery);
	
if (isset($qrConveni)){
	$nom_conveni = $qrConveni['NOM_CONVENI'];
	$num_conveni = $qrConveni['NUM_CONVENI'];
	$val_valor = $qrConveni['VAL_VALOR'];
	$val_conced = $qrConveni['VAL_CONCED'];
	$val_contpar = $qrConveni['VAL_CONTPAR'];
}

$pct_reserva = $pct_tarefa;
// FNeSCREVE($pct_reserva);
$valores_pct = array(	
						0 => 0,
						1 => 5,
						2 => 10,
						3 => 15,
						4 => 20,
						5 => 25,
						6 => 30,
						7 => 35,
						8 => 40,
						9 => 45,
						10 => 50,
						11 => 55,
						12 => 60,
						13 => 65,
						14 => 70,
						15 => 75,
						16 => 80,
						17 => 85,
						18 => 90,
						19 => 95,
						20 => 100
				   );


$pct_reservaVl = $pct_reserva;
$pct_reserva = array_search($pct_reserva, $valores_pct);

if($log_ativo == "S"){
	$checkAtivo = "checked";
}else{
	$checkAtivo = "";
}

if ($popUp != "true"){  

?>							
	<div class="push30"></div> 
<?php 
} 
?>

	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />

	<div class="row">				

		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
			<div class="portlet" style="padding: 0 20px 20px 20px;" >
			<?php } ?>
			
				<?php if ($popUp != "true"){  ?>
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?>
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
					<?php } 

						if($popUp != 'true'){
					?>

						<div class="push30"></div> 
							
						<div class="row">  

							<div class="col-md-1">
								
								<div class="tabbable-line">
			
									<ul class="nav nav-tabs ">
										<li>
											<a href="action.do?mod=<?php echo fnEncode($modRetorno)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
											<span class="fal fa-arrow-circle-left fa-2x"></span></a>
										</li>
									</ul>
								</div>	

							</div>
								
							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label">Nome</label>
									<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni; ?>" maxlength="60" readonly>
								</div>
								<div class="help-block with-errors"></div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Inicial</label>
									<input type='text' class="form-control input-sm data leitura" name="DAT_INICINV" id="DAT_INICINV" value="<?=$dat_inicinv?>" readonly/>
								</div>
							</div>       
				
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Final</label>
									<input type='text' class="form-control input-sm data leitura" name="DAT_FIMCONV" id="DAT_FIMCONV" value="<?=$dat_fimconv?>" readonly/>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Aditivo</label>
									<input type='text' class="form-control input-sm data leitura" name="DAT_ADITIVO" id="DAT_ADITIVO" value="<?=$dat_aditivo?>" readonly/>
								</div>
							</div>							
											
							<?php																	
								$sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
								//fnEscreve($cod_entidad);
								while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
								{
								?>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Entidade</label>
										<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $qrListaTipoEntidade['NOM_ENTIDAD']; ?>" maxlength="60" readonly>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								
								<?php 													
								}											
							?>	
										

						</div>

						<div class="push30"></div>

					<?php 
						}
					?>	
					
					<?php if($popUp != 'true'){ ?>

						<div class="col-md-12">

							<div class="no-more-tables">
						
								<form name="formLista">
								

								<table class="table table-striped">

									<thead>

										<tr data-toggle="collapse" class="accordion-toggle" data-target="#<?=$chave_linha?>" onclick='rotacionaSeta("<?=$chave_linha?>")'>
											<th width="5%"></th>
											<th width="45%">Projeto</th>
											<th width="10%">Dat. Início</th>
											<th width="10%">Dat. Fim</th>
											<th width="20%" class="text-right">Val. Projeto</th>
											<th width="10%"></th>
										</tr>

									</thead>
								  
								<?php 
								
									$sql = "SELECT 
												TF1.*
											FROM TAREFA TF1
											WHERE TF1.COD_EMPRESA = $cod_empresa
											AND TF1.COD_CONVENIO = $cod_conveni
											AND TF1.LOG_ATIVO = 'S'
											AND TF1.COD_SUBTAREFA = 0
											GROUP BY TF1.COD_TAREFA";

									//fnEscreve($sql);	

									$val_total = array();	

									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                                    while ($qrTask = mysqli_fetch_assoc($arrayQuery))
                                    {		

                                    	$chave_linha = $qrTask['COD_TAREFA'];

                                ?>

									<thead>

										<tr data-toggle="collapse" class="accordion-toggle" data-target="#<?=$chave_linha?>" onclick='rotacionaSeta("<?=$chave_linha?>")'>
											<th width="5%"></th>
											<th width="45%"><span class="fal fa-angle-right <?=$chave_linha?>" data-expande='0'></span>&nbsp; <a href="javascript:void(0)"><?=$qrTask[NOM_TAREFA]?></a></th>
											<th width="10%"><?=fnDataShort($qrTask['DAT_INI'])?></th>
											<th width="10%"><?=fnDataShort($qrTask['DAT_FIM'])?></th>
											<th width="20%" class="text-right"><?=fnValor($qrTask['VAL_PROJETO'],2)?></th>
											<th width="10%"></th>
										</tr>

									</thead>

									<tbody>

										<tr>

											<td colspan="6" class="hiddenRow">
												<div class="accordian-body collapse" id="<?=$chave_linha?>"> 
													<table class="table table-striped">

														<!-- <thead>

															<tr data-toggle="collapse" class="accordion-toggle" data-target="#<?=$chave_linha?>" onclick='rotacionaSeta("<?=$chave_linha?>")'>
																<th width="5%"></th>
																<th width="45%"></th>
																<th width="10%"></th>
																<th width="10%"></th>
																<th width="20%"></th>
																<th width="10%"></th>
															</tr>

														</thead> -->

														<?php

															$sql2 = "SELECT 
																		TF1.*
																	FROM TAREFA TF1
																	WHERE TF1.COD_EMPRESA = $cod_empresa
																	AND TF1.COD_CONVENIO = $cod_conveni
																	AND TF1.LOG_ATIVO = 'S'
																	AND COD_SUBTAREFA = $chave_linha";

															// fnEscreve($sql2);		

															$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

						                                    while ($qrTask2 = mysqli_fetch_assoc($arrayQuery2))
						                                    {

																

											        	?>				
																													

																<tbody>

																	<tr class="accordion-toggle"  data-toggle="collapse" data-target=".Convenio">
																		<td width="5%"></td>
																		<td width="45%"><?=$qrTask2['NOM_TAREFA']?></td>
																		<td width="10%"><?=fnDataShort($qrTask2['DAT_INI'])?></td>
																		<td width="10%"><?=fnDataShort($qrTask2['DAT_FIM'])?></td>
																		<td width="20%" class="text-right"><?=fnValor($qrTask2['VAL_PROJETO'],2)?></td>
																		<td width="10%" class="text-right">
																			<small>
															           			<div class="btn-group dropdown dropright">
																					<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																						ações &nbsp;
																						<span class="fas fa-caret-down"></span>
																				    </button>
																					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																						<!-- <li class="divider"></li> -->
																						<li style="display: <?=$mostraAprovar?>;"><a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1783)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_conveni)?>&idts=<?=fnEncode($qrTask2[COD_TAREFA])?>&pop=true" data-title="<?=$qrTask2['NOM_TAREFA']?>"><span class="fal fa-edit"></span>&nbsp; Alterar</a></li>
																					</ul>
																				</div>
															           		</small>
																		</td>
																	</tr>
																	
																</tbody>
																
																
											            <?php

											            	$val_total[$chave_linha] += $qrTask2['VAL_PROJETO'];

											        		}
											            ?>

											            <tbody>

													        <td width="5%"></td>
															<td width="45%"></td>
															<td width="10%"></td>
															<td width="10%"><b>Total</b></td>
															<td width="20%" class="text-right"><b><?=fnValor($val_total[$chave_linha],2)?></b></td>
															<td width="10%"></td>

														</tbody>

														<tbody>

													        <td width="5%"></td>
															<td width="45%"></td>
															<td width="10%"></td>
															<td width="10%"><b>Valor do Projeto</b></td>
															<td width="20%" class="text-right"><b><?=fnValor($qrTask['VAL_PROJETO'],2)?></b></td>
															<td width="10%"></td>

														</tbody>

														<tbody>

													        <td width="5%"></td>
															<td width="45%"></td>
															<td width="10%"></td>
															<td width="10%"><b>Saldo</b></td>
															<td width="20%" class="text-right"><b><?=fnValor($qrTask['VAL_PROJETO'] - $val_total[$chave_linha],2)?></b></td>
															<td width="10%"></td>

														</tbody>

											            <tbody>

													        <tr>
													        	<td colspan="6" style="padding-top: 20px;"><a class="btn btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1783)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_conveni)?>&idtp=<?=fnEncode($chave_linha)?>&datIni=<?=fnEncode(fnDataShort($qrTask[DAT_INI]))?>&datFim=<?=fnEncode(fnDataShort($qrTask[DAT_FIM]))?>&pop=true" data-title="<?=$qrTask['NOM_TAREFA']?>"> <i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Subprojeto</a>
																</td>
															</tr>

														</tbody>
															

													</table>

												</div> 

											</td>
										</tr>

									</tbody>

					

												

                                <?php

									}											

								?>

								</table>
									
								
								
								</form>

							</div>
							
						</div>

					<?php } ?>								
				
					<div class="push"></div>
				
				</div>

			</div>

		</div>

	</div>								
					
	<div class="push50"></div>

	<!-- modal -->									
	<div class="modal fade" id="popModal" tabindex='-1'>
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

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	<script src="js/plugins/ion.rangeSlider.js"></script>

	<script type="text/javascript">

		$(function(){

			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 // maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});

			$("#PCT_TAREFA").ionRangeSlider({
		        grid: true,
		        from: <?=$pct_reserva?>,
		        values: [
		            0, 5, 10, 15, 20, 25,
		            30, 35, 40, 45, 50, 55,
		            60, 65, 70, 75, 80, 85, 90, 95, 100
		        ]
		    });

		});

		function retornaForm(index){
			$("#formulario #COD_TAREFA").val($("#ret_COD_TAREFA_"+index).val());
			$("#formulario #NOM_TAREFA").val($("#ret_NOM_TAREFA_"+index).val());
			$("#formulario #DAT_INI").val($("#ret_DAT_INI_"+index).val());
			$("#formulario #DAT_FIM").val($("#ret_DAT_FIM_"+index).val());
			$("#formulario #COD_SUBTAREFA").val($("#ret_COD_SUBTAREFA_"+index).val()).trigger('chosen:updated');
			$("#formulario #VAL_PROJETO").val($("#ret_VAL_PROJETO_"+index).val());

			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVO').prop('checked', false);}

			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		function rotacionaSeta(obj){

			let expande = $("."+obj).attr('data-expande');

			if(expande == 0){
				$("."+obj).attr('data-expande','1').removeClass('fa-angle-right').addClass('fa-angle-down');
			}else{
				$("."+obj).attr('data-expande','0').removeClass('fa-angle-down').addClass('fa-angle-right');
			}

		}

	</script>