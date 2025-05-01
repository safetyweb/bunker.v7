<?php

	//echo fnDebug('true');

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
		
		$cod_cupomadorai = fnLimpaCampoZero($_POST['COD_CUPOMADORAI']);			
		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
		$cod_hospede = fnLimpaCampoZero($_POST['COD_HOSPEDE']);	
		$cod_pedido = fnLimpaCampoZero($_POST['COD_PEDIDO']);	
		$nom_cupom  = fnLimpaCampo($_POST['NOM_CUPOM']);			
		$hospede  = fnLimpaCampo($_POST['HOSPEDE']);			
		$des_chavecupom  = fnLimpaCampo($_POST['DES_CHAVECUPOM']);			
		$qtd_uso  = fnLimpaCampoZero($_POST['QTD_USO']);			
		$dat_ini  = fnDataSql($_POST['DAT_INI']);			
		$dat_fin  = fnDataSql($_POST['DAT_FIN']);
		$val_desconto = fnValorSql($_POST['VAL_DESCONTO']);			

		if (empty($_REQUEST['TIP_DESCONTO'])) {$tip_desconto='';}else{$tip_desconto=$_REQUEST['TIP_DESCONTO'];}
		if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
		if (empty($_REQUEST['LOG_HOSPEDE'])) {$log_hospede='';}else{$log_hospede=$_REQUEST['LOG_HOSPEDE'];}
		if (empty($_REQUEST['LOG_QTDUSO'])) {$log_qtduso='';}else{$log_qtduso=$_REQUEST['LOG_QTDUSO'];}
		if (empty($_REQUEST['LOG_VALIDADE'])) {$log_validade='';}else{$log_validade=$_REQUEST['LOG_VALIDADE'];}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];	

		$nom_usuarioSESSION = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

					//array PRO
		if (isset($_POST['COD_PROPRIEDADE'])){
			$Arr_COD_PROPRIEDADE = $_POST['COD_PROPRIEDADE'];
				//print_r($Arr_COD_SISTEMAS);			 
			
			for ($i=0;$i<count($Arr_COD_PROPRIEDADE);$i++) 
			{ 
				$cod_propriedade = $cod_propriedade.$Arr_COD_PROPRIEDADE[$i].",";
			} 
			
			$cod_propriedade = substr($cod_propriedade,0,-1);
			
		}else{$cod_propriedade = "0";}

		if ($opcao != ''){

			if($dat_ini != ""){
				$andDatIni = 'DAT_INI,';
				$andIni = "'$dat_ini',";
				$andDatFin = 'DAT_FIN,';
				$andFin = "'$dat_ini',";

				$datIniUpd = "DAT_INI = '$dat_ini', DAT_FIN = '$dat_fin',";
			}

			//VERIFICA SE EXISTE CUPOM COM
			$sqlValida = "SELECT * FROM CUPOM_ADORAI WHERE DES_CHAVECUPOM = '".$des_chavecupom."'";
			$queryValida = mysqli_query(conntemp($cod_empresa, ''), $sqlValida);

			switch ($opcao)
			{

				case 'CAD':

				if(!mysqli_fetch_assoc($queryValida)){

					$sql = "INSERT INTO CUPOM_ADORAI(
										LOG_ATIVO,
										LOG_HOSPEDE,
										COD_HOSPEDE,
										COD_PEDIDO,
										NOM_CUPOM,
										DES_CHAVECUPOM,
										LOG_QTDUSO,
										QTD_USO,
										LOG_VALIDADE,
										$andDatIni
										$andDatFin
										TIP_DESCONTO,
										COD_USUCADA,
										VAL_DESCONTO,
										COD_PROPRIEDADE,
										DAT_CADASTR
										)VALUES(
										'" . $log_ativo . "',
										'" . $log_hospede . "',
										'" . $cod_hospede . "',
										'" . $cod_pedido . "',
										'" . $nom_cupom . "',
										'" . $des_chavecupom . "',
										'" . $log_qtduso . "',
										'" . $qtd_uso . "',
										'" . $log_validade . "',
											 $andIni
											 $andFin
										'" . $tip_desconto . "',
										'" . $cod_usucada . "',
										'" . $val_desconto . "',
										'" . $cod_propriedade . "',
										NOW())";

					$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

					if (!$arrayProc) {
						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}else{
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
						?>
						<script>parent.$("#LOG_ATUALIZA").val('S');</script>
					<?php
					}
				}else{
					$msgRetorno = "Não é permitido criar cupons com a mesma chave.";
				}


				break;
				case 'ALT':

						$sql = "UPDATE CUPOM_ADORAI SET
										    LOG_ATIVO = '" . $log_ativo . "',
										    LOG_HOSPEDE = '" . $log_hospede . "',
										    COD_HOSPEDE = " . $cod_hospede . ",
										    COD_PEDIDO = " . $cod_pedido . ",
										    NOM_CUPOM = '" . $nom_cupom . "',
										    DES_CHAVECUPOM = '" . $des_chavecupom . "',
										    LOG_QTDUSO = '" . $log_qtduso . "',
										    QTD_USO = " . $qtd_uso . ",
										    LOG_VALIDADE = '" . $log_validade . "',
										    $datIniUpd
										    TIP_DESCONTO = '" . $tip_desconto . "',
										    COD_ALTERAC = " . $cod_usucada . ",
										    VAL_DESCONTO = '" . $val_desconto . "',
										    COD_PROPRIEDADE = '" . $cod_propriedade . "',
										    DAT_ALTERAC = NOW()
										WHERE COD_CUPOMADORAI = $cod_cupomadorai
										";

						$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);
						if (!$arrayProc) {
							$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
							$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
						}else{
							$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
							?>
							<script>parent.$("#LOG_ATUALIZA").val('S');</script>
						<?php
						}
			
					break;
					case 'EXC':

					$sql = "DELETE FROM CUPOM_ADORAI WHERE COD_CUPOMADORAI = $cod_cupomadorai";

					$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

					if (!$arrayProc) {
						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}else{
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
					?>
						<script>parent.$("#LOG_ATUALIZA").val('S');</script>
					<?php
					}



				break;
			}			
			$msgTipo = 'alert-success';

		} 

	}
} 

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}

//busca dados do cupom
if(is_numeric(fnLimpacampo(fnDecode($_GET['idc'])))) {

	$cod_cupomadorai = fnDecode($_GET['idc']);
	$sql = "SELECT * FROM CUPOM_ADORAI WHERE COD_CUPOMADORAI = $cod_cupomadorai";
	$array = mysqli_query(conntemp($cod_empresa, ''), $sql);

	if($qrBusca = mysqli_fetch_assoc($array)){
		$cod_cupomadorai = $qrBusca['COD_CUPOMADORAI'];
		$cod_hospede = $qrBusca['COD_HOSPEDE'];
		$cod_pedido = $qrBusca['COD_PEDIDO'];
		$nom_cupom = $qrBusca['NOM_CUPOM'];
		$des_chavecupom = $qrBusca['DES_CHAVECUPOM'];
		$qtd_uso = $qrBusca['QTD_USO'];
		$dat_ini = fnDataShort($qrBusca['DAT_INI']);
		$dat_fin = fnDataShort($qrBusca['DAT_FIN']);
		$tip_desconto = $qrBusca['TIP_DESCONTO'];
		$log_hospede = $qrBusca['LOG_HOSPEDE'];
		$log_qtduso = $qrBusca['LOG_QTDUSO'];
		$log_validade = $qrBusca['LOG_VALIDADE'];
		$log_ativo = $qrBusca['LOG_ATIVO'];
		$cod_propriedade = $qrBusca['COD_PROPRIEDADE'];
		$val_desconto = fnValor($qrBusca['VAL_DESCONTO'],2);
	}

	$sqlConsulta = "SELECT COUNT(COD_CUPOM) AS QTD_UTILIZADO FROM ADORAI_PEDIDO WHERE COD_CUPOM = '$des_chavecupom'";
	$result = mysqli_query(conntemp($cod_empresa, ''), $sqlConsulta);

	if($qrResult = mysqli_fetch_assoc($result)){
		$qtd_utilizado = $qrResult['QTD_UTILIZADO'];
	}

}else{
		$cod_cupomadorai = "";
		$log_ativo = "";
		$log_hospede = "";
		$cod_hospede = "";
		$cod_pedido = "";
		$nom_cupom = "";
		$des_chavecupom = "";
		$log_qtduso = "";
		$qtd_uso = "";
		$log_validade = "";
		$dat_ini = "";
		$dat_fin = "";
		$tip_desconto = "";	
		$val_desconto = 0;
		$qtd_utilizado =0;	
	}

		if (empty($log_hospede)) {
			$checkHospede='';
			$exibiHospede = "style='display: none;'";
		}else{
			$exibiHospede = "";
			$checkHospede= 'checked';
			$sqlHospede = "SELECT * FROM HOSPEDES_ADORAI WHERE COD_HOSPEDE = $cod_hospede AND COD_PEDIDO = $cod_pedido";
			$array = mysqli_query(conntemp($cod_empresa, ''), $sqlHospede);

			if($qrBuscaHosp = mysqli_fetch_assoc($array)){
				$hospede = $qrBuscaHosp['NOM_HOSP'] . " " . $qrBuscaHosp['SOBRENOM_HOSP'];
			}
		}

		if (empty($log_ativo)) {$checkAtivo='';}else{$checkAtivo= 'checked';}

		switch ($log_qtduso){
			case 'I':
				$checkIlimitada='checked';
				$checkLimitada='';
				$exibiQtdUso = "style='display: none;'";
				$requiQtdUso = "";
				break;
			case 'L':
				$checkIlimitada='';
				$checkLimitada='checked';
				$exibiQtdUso = '';
				$requiQtdUso = "required";
				break;
			default:
				$checkIlimitada='';
				$checkLimitada='';
				$exibiQtdUso = "style='display: none;'";
		}

		switch ($log_validade){
			case 'I':
				$checkIndefinida='checked';
				$checkData='';
				$exibiData = "style='display: none;'";
				break;
			case 'D':
				$checkData= 'checked';
				$checkIndefinida ='';
				$exibiData = "";
				break;
			default:
				$checkData= '';
				$checkIndefinida ='';
				$exibiData = "style='display: none;'";
				break;
		}


		switch ($tip_desconto){

			case '1':
				$check1 ="checked";
				$check2="";
				$check3="";
				$check4="";
				break;

			case '2':
				$check1 ="";
				$check2="checked";
				$check3="";
				$check4="";

				break;

			case '3':

				$check1 ="";
				$check2="";
				$check3="checked";
				$check4="";

				break;

			case '4':
				
				$check1 ="";
				$check2="";
				$check3="";
				$check4="checked";
				break;
		}

?>	

<style>

	.rdo-grp {
		position: absolute;
		top: calc(50% - 10px);
	}
	.rdo-grp label {
		cursor: pointer;
		-webkit-tap-highlight-color: transparent;
		padding: 6px 8px;
		border-radius: 20px;
		float: left;
		transition: all 0.2s ease;
	}
	.rdo-grp label:hover {
		background: rgba(52,152,219,0.06);
	}
	.rdo-grp label:not(:last-child) {
		margin-right: 16px;
	}
	.rdo-grp label span {
		vertical-align: middle;
	}
	.rdo-grp label span:first-child {
		position: relative;
		display: inline-block;
		vertical-align: middle;
		width: 20px;
		height: 20px;
		background: #e8eaed;
		border-radius: 50%;
		transition: all 0.2s ease;
		margin-right: 8px;
	}
	.rdo-grp label span:first-child:after {
		content: '';
		position: absolute;
		width: 16px;
		height: 16px;
		margin: 2px;
		background: #fff;
		border-radius: 50%;
		transition: all 0.2s ease;
	}
	.rdo-grp label:hover span:first-child {
		background: #3498DB;
	}
	.rdo-grp input {
		display: none;
	}
	.rdo-grp input:checked + label span:first-child {
		background: #3498DB;
	}
	.rdo-grp input:checked + label span:first-child:after {
		transform: scale(0.5);
	}

	.compra,
	.forma,
	.itemRadio,
	.outros{
		display: none;
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
							<i class="fal fa-terminal"></i>
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

					<div class="push30"></div> 

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>  

								<div class="row">

									<div class="col-md-2" id="">
										<div class="form-group">
											<label for="inputName" class="control-label">Qtd. Usados</label>
											<input type="number" readonly="" class="form-control input-sm leitura" name="QTD_UTILIZADO" id="QTD_UTILIZADO" maxlength="50" value="<?= $qtd_utilizado ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Desconto Ativo</label> 
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?= $checkAtivo ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cupom por CPF</label> 
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_HOSPEDE" id="LOG_HOSPEDE" class="switch" value="S" <?= $checkHospede ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-6 cliente" id='cliente' <?= $exibiHospede; ?>>
										<label for="inputName" class="control-label required">Hospede</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(2065) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode(0) ?>&pop=true&op=IND" data-title="Busca Hospede"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="HOSPEDE" id="HOSPEDE" value="<?=$hospede?>" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label ">Nome Cupom</label>
											<input type="text" class="form-control input-sm" name="NOM_CUPOM" id="NOM_CUPOM" maxlength="30" value="<?= $nom_cupom ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Chave do Cupom</label>
											<input type="text" class="form-control input-sm" name="DES_CHAVECUPOM" id="DES_CHAVECUPOM" maxlength="50" value="<?= $des_chavecupom ?>" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">

										<label for="inputName" class="control-label required">Quantidade de Uso:</label>

										<div class="push50"></div>
										<div class="rdo-grp">
											<input id="LOG_QTDUSO1" type="radio" name="LOG_QTDUSO" value="I" <?= $checkIlimitada ?>/>
											<label for="LOG_QTDUSO1"><span></span><span>Ilimitada</span></label>
											<input id="LOG_QTDUSO2" type="radio" name="LOG_QTDUSO" value="L" <?= $checkLimitada ?>/>
											<label for="LOG_QTDUSO2"><span></span><span>Limitada</span></label>
										</div>	
									</div>

									<div class="col-md-2 div_qtd_cupom" id="div_qtd_cupom" <?= $exibiQtdUso ?>>
										<div class="form-group">
											<label for="inputName" class="control-label required">Qtd. Cupons</label>
											<input type="number" class="form-control input-sm" name="QTD_USO" id="QTD_USO" maxlength="50" value="<?= $qtd_uso ?>" <?= $requiQtdUso ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">

										<label for="inputName" class="control-label required">Validade:</label>

										<div class="push50"></div>
										<div class="rdo-grp">
											<input id="LOG_VALIDADE1" type="radio" name="LOG_VALIDADE" value="I" <?= $checkIndefinida ?>/>
											<label for="LOG_VALIDADE1"><span></span><span>Indefinida</span></label>
											<input id="LOG_VALIDADE2" type="radio" name="LOG_VALIDADE" value="D" <?= $checkData ?>/>
											<label for="LOG_VALIDADE2"><span></span><span>Por Data</span></label>
										</div>	
									</div>

									<div class="col-md-3 div_dat_ini" id="div_dat_ini" <?= $exibiData ?>>
										<div class="form-group" >
											<label for="inputName" class="control-label required">Data Inicial</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= $dat_ini ?>"/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<span class="help-block">Validade</span>
										</div>
									</div>

									<div class="col-md-3 div_dat_fim" id="div_dat_fim" <?= $exibiData ?>>
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>

											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIN" id="DAT_FIN" value="<?= $dat_fin ?>"/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<span class="help-block">Validade</span>
										</div>
									</div>

								</div>


								<div class="row">

									<div class="col-md-4">
										<label for="inputName" class="control-label required">Tipo de desconto:</label>

										<div class="push50"></div>

										<div class="rdo-grp">
											<input id="TIP_DESCONTO1" type="radio" name="TIP_DESCONTO" value="1" <?= $check1 ?>/>
											<label for="TIP_DESCONTO1"><span></span><span>Cupom por valor <b>fixo</b> sobre DIÁRIAS</span></label>
											<input id="TIP_DESCONTO2" type="radio" name="TIP_DESCONTO" value="2" <?= $check2 ?>/>
											<label for="TIP_DESCONTO2"><span></span><span>Cupom por valor <b>percentual</b> sobre DIÁRIAS</span></label>
										</div>		

									</div>
									<div class="col-md-4">

										<div class="push50"></div>

										<div class="rdo-grp">
											<input id="TIP_DESCONTO3" type="radio" name="TIP_DESCONTO" value="3" <?= $check3 ?>/>
											<label for="TIP_DESCONTO3"><span></span><span>Cupom por <b>percentual</b> sobre TOTAL</span></label>
											<input id="TIP_DESCONTO4" type="radio" name="TIP_DESCONTO" value="4" <?= $check4 ?>/>
											<label for="TIP_DESCONTO4"><span></span><span>Cupom por valor <b>fixo</b> sobre TOTAL</span></label>
										</div>		

									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor Desconto</label>
											<input type="tel" class="form-control input-sm money" name="VAL_DESCONTO" id="VAL_DESCONTO" maxlength="50" value="<?= $val_desconto ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">
									<div class="push20"></div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Propriedade</label>
											<select data-placeholder="Selecione um Hotel" name="COD_PROPRIEDADE[]" id="COD_PROPRIEDADE" multiple class="chosen-select-deselect" style="width:100%;" required>									
												<option value="9999" selected>Todas</option>
												<?php 

												$sql = "SELECT UV.*, TP.DES_PROPRIEDADE from unidadevenda UV
												LEFT JOIN TPPROPRIEDADE TP ON TP.COD_PROPRIEDADE = UV.COD_PROPRIEDADE
												where UV.COD_EMPRESA = '".$cod_empresa."' and UV.cod_exclusa =0 $andFiltro order by UV.NOM_FANTASI ASC";

												$arrayHotel = mysqli_query($connAdm->connAdm(),$sql);

												while($qrHoteis = mysqli_fetch_assoc($arrayHotel)){
													echo "<option value='".$qrHoteis['COD_EXTERNO']."'>".$qrHoteis['NOM_FANTASI']."</option>";
												}
												?>		
											</select>									
											<div class="help-block with-errors"></div>
											<?php if($cod_propriedade != ""){ ?>
												<script>$("#formulario #COD_PROPRIEDADE").val("<?php echo $cod_propriedade; ?>").trigger("chosen:updated"); </script>
											<?php } ?>
										</div>
									</div>

								</div>

								<div class="push10"></div>												

							</fieldset>	

							<div class="push10"></div>
							<hr>	
							<div class="form-group text-right col-lg-12">

						<?php 


							if ($cod_cupomadorai == 0 && $cod_cupomadorai == "") {
							?>
							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

							<?php
							}
							// Se não houver resultados, exibe os botões de "Alterar" e "Excluir"
							if ($qtd_utilizado == 0) {
							?>
							    <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							    <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							<?php
							}
						?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="COD_HOSPEDE" id="COD_HOSPEDE" value="<?php echo $cod_hospede ?>" />	
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
							<input type="hidden" name="COD_PEDIDO" id="COD_PEDIDO" value="<?= $cod_pedido ?>" />	
							<input type="hidden" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?= $num_cgcecpf ?>"/>		
							<input type="hidden" name="COD_CUPOMADORAI" id="COD_CUPOMADORAI" value="<?php echo $cod_cupomadorai; ?>"/>		
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

							<div class="push5"></div> 

						</form>									

						<div class="push"></div>

					</div>	

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


				</div>
			</div>
			<!-- fim Portlet -->
		</div>

	</div>					

	<div class="push20"></div> 

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
	<script>
		
		$(document).ready( function() {
			
				//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="hidden"],[type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();


			$('.datePicker').datetimepicker({
				format: 'DD/MM/YYYY',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

			$('#LOG_HOSPEDE').change(function() {
				if ($(this).prop("checked") == true){
					$('#cliente').show();
					$('#HOSPEDE').attr("required",true);
				}else{
					$('#cliente').hide();
					$('#HOSPEDE').attr("required",false);
				}
			});

			$('input[type=radio][name=LOG_VALIDADE]').change(function() {
				if(this.value == "I"){
					$("#DAT_INI").attr("required",false);
					$("#DAT_FIN").attr("required",false);
					$('#div_dat_fim').hide();
					$('#div_dat_ini').hide();
				}else{
					$("#DAT_INI").attr("required",true);
					$("#DAT_FIN").attr("required",true);
					$('#div_dat_ini').show();
					$('#div_dat_fim').show();
				}
			});

			$('input[type=radio][name=LOG_QTDUSO]').change(function() {
				if(this.value == "I"){
					$("#QTD_USO").attr("required",false);
					$('#div_qtd_cupom').hide();
				}else{
					$("#QTD_USO").attr("required",true);
					$('#div_qtd_cupom').show();
				}
			});
			
		});
		
	</script>	
