<?php
 
$hashLocal = mt_rand();	

$mod = fnDecode($_GET['mod']);

$modRetorno = 1344;
$andSubtarefa = "AND TF1.COD_SUBTAREFA = 0";

if($mod == 1788){
	$modRetorno = 1348;
	$andSubtarefa = "AND TF1.COD_SUBTAREFA != 0";
}

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
		$cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$cod_subtarefa = fnLimpaCampoZero($_REQUEST['COD_SUBTAREFA']);
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
						FROM TAREFA_GARANTIA 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_BEM = $cod_bem";

		// fnEscreve($sqlOrdenac);

		$qrOrdenac = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlOrdenac));

		if($qrOrdenac[NUM_ORDENAC] != ""){
			$num_ordenac = $qrOrdenac[NUM_ORDENAC]+1;
		}
                  
		if ($opcao != ''){

			switch ($opcao)
			{
				case 'CAD':

					$sqlReordena = "SELECT MAX(NUM_ORDENAC) NUM_ORDEMTASK FROM TAREFA_GARANTIA WHERE COD_TAREFA = $cod_subtarefa";

					// fnEscreve($sqlReordena);

					$qrOrdemtask = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlReordena));

					if($qrOrdemtask[NUM_ORDEMTASK] != ""){
						$num_ordenac = $qrOrdemtask[NUM_ORDEMTASK]+1;

						// fnEscreve($num_ordenac);

						$sqlUpdtOrdem = "UPDATE TAREFA_GARANTIA SET
												NUM_ORDENAC = (NUM_ORDENAC+1)
										WHERE COD_BEM = $cod_bem
										AND COD_EMPRESA = $cod_empresa
										AND NUM_ORDENAC >= $num_ordenac";

						// fnEscreve($sqlUpdtOrdem);

						mysqli_query(connTemp($cod_empresa,''),$sqlUpdtOrdem);

					}


					$sql = "INSERT INTO TAREFA_GARANTIA(
											COD_EMPRESA,
											COD_SISTEMA,
											COD_SUBTAREFA,
											NUM_ORDENAC,
											COD_BEM,
											COD_CLIENTE,
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
											'$cod_bem',
											'$cod_cliente',
											'$cod_controle',
											'$tip_tarefa',
											'$nom_tarefa',
											'$val_projeto',
											'$log_ativo',
											'$dat_ini',
											'$dat_fim',
											'$cod_usucada'
										)";

					// fnEscreve2($sql);

					mysqli_query(connTemp($cod_empresa,''),$sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	

				break;

				case 'ALT':

					$sql = "UPDATE TAREFA_GARANTIA SET
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
	$cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));
	$cod_cliente = fnLimpacampoZero(fnDecode($_GET['idC']));
	$datIni = "";
	$datFim = "";
	if($_GET['datIni'] != ""){
		$datIni = fnDataSql(fnDecode($_GET['datIni']));
	}
	if($_GET['datFim'] != ""){
		$datFim = fnDataSql(fnDecode($_GET['datFim']));
	}
	$cod_tarefa_principal = fnLimpaCampoZero(fnDecode($_GET['idtp']));
	$cod_tarefa_secundaria = fnLimpaCampoZero(fnDecode($_GET['idts']));
	$tarefas = json_decode($_GET['idt'],true);

	// echo "<pre>";
	// print_r($tarefas);
	// echo "</pre>";

	$cod_subtarefa = fnLimpaCampoZero($tarefas[id]);

	if($cod_tarefa_secundaria != 0){
		$cod_subtarefa = $cod_tarefa_secundaria;
	}

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaEmpresa)){
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
											
}else {	
	$nom_empresa = "";
	$cod_bem = "";
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


$sqlTarefa = "SELECT * FROM TAREFA_GARANTIA WHERE COD_TAREFA = $cod_tarefasql AND COD_EMPRESA = $cod_empresa";

// fnEscreve2($sqlTarefa);

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
	$val_projeto = fnValor($qrTarefa['VAL_PROJETO'],2);
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
	$val_projeto = "";
	$log_ativo = "S";
	$dat_ini = "";
	$dat_fim = "";
}

if($dat_ini == ""){
	$dat_ini = $datIni;
}
if($dat_fim == ""){
	$dat_fim = $datFim;
}

// $sql = "SELECT SUM(A.VAL_VALOR) AS VAL_VALOR,SUM(A.VAL_CONVENI)AS VAL_CONCED,SUM(A.VAL_CONTPAR)AS VAL_CONTPAR,B.NOM_CONVENI,B.NUM_CONVENI
// 		FROM CONTRATO A,CONVENIO B 
// 		WHERE 
// 		A.COD_CONVENI=B.COD_CONVENI AND 
// 		A.COD_CONVENI = $cod_bem AND 
// 		A.DES_TPCONTRAT='CON'";	

// //fnEscreve($sql);
// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
// $qrConveni = mysqli_fetch_assoc($arrayQuery);
	
// if (isset($qrConveni)){
// 	$nom_conveni = $qrConveni['NOM_CONVENI'];
// 	$num_conveni = $qrConveni['NUM_CONVENI'];
// 	$val_valor = $qrConveni['VAL_VALOR'];
// 	$val_conced = $qrConveni['VAL_CONCED'];
// 	$val_contpar = $qrConveni['VAL_CONTPAR'];
// }

// $pct_reserva = $pct_tarefa;
// // FNeSCREVE($pct_reserva);
// $valores_pct = array(	
// 						0 => 0,
// 						1 => 5,
// 						2 => 10,
// 						3 => 15,
// 						4 => 20,
// 						5 => 25,
// 						6 => 30,
// 						7 => 35,
// 						8 => 40,
// 						9 => 45,
// 						10 => 50,
// 						11 => 55,
// 						12 => 60,
// 						13 => 65,
// 						14 => 70,
// 						15 => 75,
// 						16 => 80,
// 						17 => 85,
// 						18 => 90,
// 						19 => 95,
// 						20 => 100
// 				   );


// $pct_reservaVl = $pct_reserva;
// $pct_reserva = array_search($pct_reserva, $valores_pct);

if($log_ativo == "S"){
	$checkAtivo = "checked";
}else{
	$checkAtivo = "";
}

if($cod_tarefa_principal != 0){
	$cod_subtarefa = $cod_tarefa_principal;
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

				<?php
					if($popUp != 'true'){
			            $abaBens = 1981;
			            include "abasBens.php";
			        }
	            ?>							
				
				<div class="push10"></div>

				<div class="portlet-body">
					
					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } 
					?>	

					<div class="push30"></div>
												
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<?php 
							if($popUp != 'true'){
								include "bensHeader.php"; 
							}
						?>

						<fieldset>
							<legend>Dados Gerais</legend> 

							<!-- start: '2018-10-11',
							end: '2018-10-11',
							name: 'Go Live!',
							id: "5",
							progress: 10,
							dependencies: '4',
							custom_class: 'bar-milestone' -->

							<div class="row">
								
								<div class="col-md-2">   
									<div class="form-group">
										<label for="inputName" class="control-label">Tarefa Ativa</label> 
										<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?=$checkAtivo?>>
											<span></span>
											</label>
									</div>
								</div>

								<!-- <div class="col-md-2 col-md-offset-8">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor do Convênio</label>
										<input type="text" class="form-control input-sm leitura" readonly name="VAL_VALOR" id="VAL_VALOR" value="<?php echo fnValor($val_valor,2)?>">
									</div>														
								</div> -->

							</div>

							<div class="push10"></div>

							<div class="row">

								<?php if($mod == 1788){ ?>

									<div class="col-md-3">
	                                    <div class="form-group">
	                                        <label for="inputName" class="control-label required">Tarefa Principal</label>
	                                        <select data-placeholder="Selecione uma tarefa" name="COD_SUBTAREFA" id="COD_SUBTAREFA" class="chosen-select-deselect" required>
	                                            <option value=""></option>
	                                            <?php																	
		                                            $sql = "SELECT * FROM TAREFA_GARANTIA 
															WHERE COD_EMPRESA = $cod_empresa
															AND COD_BEM = $cod_bem
															AND LOG_ATIVO = 'S'";
		                                            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		                                            while ($qrListaTarefa = mysqli_fetch_assoc($arrayQuery))
		                                            {													
		                                                echo"
		                                                <option value='".$qrListaTarefa['COD_TAREFA']."'>".$qrListaTarefa['NOM_TAREFA']."</option> 
		                                                "; 
		                                            }											
	                                            ?>	
	                                        </select>	
	                                        <script>$("#formulario #COD_SUBTAREFA").val("<?php echo $cod_subtarefa; ?>").trigger("chosen:updated"); </script>
	                                        <div class="help-block with-errors"></div>
	                                    </div>
	                                </div>

								<?php }else{ ?>
									<input type="hidden" name="COD_SUBTAREFA" id="COD_SUBTAREFA" value="<?=$cod_subtarefa?>">
								<?php } ?>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Tarefa</label>
										<input type="text" class="form-control input-sm" name="NOM_TAREFA" id="NOM_TAREFA" maxlength="60" value="<?=$nom_tarefa?>" required>
									</div>
								</div>

								<div class="col-md-3">
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>
										
										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
                                                                                              
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<!-- <div class="col-md-4">
								
									<div class="form-group">
										<label for="inputName" class="control-label">Percentual de Conclusão</label>
										<div class="push5"></div>
										<input type="text" name="PCT_TAREFA" id="PCT_TAREFA" value="" />
									</div>

								</div> -->
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor</label>
										<input type="text" class="form-control input-sm money" name="VAL_PROJETO" id="VAL_PROJETO" value="<?=$val_projeto?>">
									</div>
								</div>

							</div>     

						</fieldset>


						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">

							<!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->


							<?php if($subtarefa){ ?>
								<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Adicionar subtarefa</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button> 
							<?php }else{ ?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php } ?>
							
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_TAREFA" id="COD_TAREFA" value="<?=$cod_tarefa?>">
						<input type="hidden" name="COD_BEM" id="COD_BEM" value="<?=$cod_bem?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

						<div class="push5"></div> 

					</form>
					
					<div class="push50"></div>
					
					<?php if($popUp != 'true'){ ?>

						<div class="col-md-12">

							<div class="no-more-tables">
						
								<form name="formLista">
								
								<table class="table table-bordered table-striped table-hover tableSorter">
								  <thead>
									<tr>
									  <th class="{ sorter: false }" width="40"></th>
									  <th>Código</th>
									  <?php if($mod == 1788){ ?>
									  	<th>Projeto</th>
									  	<th>Val. Projeto</th>
									  <?php } ?>
									  <th>Tarefa</th>
									  <th>Dt. Inicial</th>
									  <th>Dt. Final</th>
									  <th class='text-right'>Valor</th>
									</tr>
								  </thead>
								<tbody>
								  
								<?php 
								
									$sql = "SELECT 
												TF1.*, 
												TF2.NOM_TAREFA AS TAREFA_PRINCIPAL,
												TF2.VAL_PROJETO AS VAL_PRINCIPAL
											FROM TAREFA_GARANTIA TF1
											LEFT JOIN TAREFA_GARANTIA TF2 ON TF1.COD_SUBTAREFA = TF2.COD_TAREFA
											WHERE TF1.COD_EMPRESA = $cod_empresa
											AND TF1.COD_BEM = $cod_bem
											AND TF1.LOG_ATIVO = 'S'
											$andSubtarefa
											GROUP BY TF1.COD_TAREFA
											ORDER BY TF2.COD_TAREFA";

									//fnEscreve($sql);		

									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                                    while ($qrTask = mysqli_fetch_assoc($arrayQuery))
                                    {														  
										$count++;	

										$projeto = "";
										$val_principal = "";

										if($mod == 1788){
											$projeto = "<td>".$qrTask['TAREFA_PRINCIPAL']."</td>";
											$val_principal = "<td>".fnValor($qrTask['VAL_PRINCIPAL'],2)."</td>";
										}

										echo"
											<tr>
											  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrTask['COD_TAREFA']."</td>
											  ".$projeto."
											  ".$val_principal."
											  <td>".$qrTask['NOM_TAREFA']."</td>
											  <td>".fnDataShort($qrTask['DAT_INI'])."</td>
											  <td>".fnDataShort($qrTask['DAT_FIM'])."</td>
											  <td class='text-right'>".fnValor($qrTask['VAL_PROJETO'],2)."</td>
											</tr>
											<input type='hidden' id='ret_COD_TAREFA_".$count."' value='".$qrTask['COD_TAREFA']."'>
											<input type='hidden' id='ret_NOM_TAREFA_".$count."' value='".$qrTask['NOM_TAREFA']."'>
											<input type='hidden' id='ret_LOG_ATIVO_".$count."' value='".$qrTask['LOG_ATIVO']."'>
											<input type='hidden' id='ret_DAT_INI_".$count."' value='".fnDataShort($qrTask['DAT_INI'])."'>
											<input type='hidden' id='ret_DAT_FIM_".$count."' value='".fnDataShort($qrTask['DAT_FIM'])."'>
											<input type='hidden' id='ret_COD_SUBTAREFA_".$count."' value='".$qrTask['COD_SUBTAREFA']."'>
											<input type='hidden' id='ret_VAL_PROJETO_".$count."' value='".fnValor($qrTask['VAL_PROJETO'],2)."'>
											<input type='hidden' id='ret_VAL_PRINCIPAL_".$count."' value='".fnValor($qrTask['VAL_PRINCIPAL'],2)."'>
											"; 

									 $val_total += $qrTask['VAL_PROJETO'];
									}											

								?>
									
								</tbody>

								<?php if($mod != 1788){ ?>

									<tfoot>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Total</td>
											<td class="text-right"><b><?=fnValor($val_total,2);?></b></td>
										</tr>
										<!-- <tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Valor do projeto</td>
											<td class="text-right"><b><?=fnValor($val_valor,2);?></b></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Saldo</td>
											<td class="text-right"><b><?=fnValor($val_valor-$val_total,2);?></b></td>
										</tr> -->
									</tfoot>

								<?php } ?>

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

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	<script src="js/plugins/ion.rangeSlider.js"></script>

	<script type="text/javascript">

		$(function(){

			let datMax = "",
				datMin = "";

			<?php 
				if($datIni != ""){ 
					$datMin = "minDate : '".$datIni."',";
				} 
			?>

			<?php 
				if($datFim != ""){ 
					$datMax = "maxDate : '".$datFim."',";
			 	} 
			?>

			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 <?= $datMin ?>
				 <?= $datMax ?>
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});

			// $("#PCT_TAREFA").ionRangeSlider({
		    //     grid: true,
		    //     from: <?=$pct_reserva?>,
		    //     values: [
		    //         0, 5, 10, 15, 20, 25,
		    //         30, 35, 40, 45, 50, 55,
		    //         60, 65, 70, 75, 80, 85, 90, 95, 100
		    //     ]
		    // });

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

	</script>