<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

$hoje = fnFormatDate(date("Y-m-d"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;
		$cod_proxpagamento = fnLimpaCampo($_REQUEST['COD_PROXPAGAMENTO']);
		$cod_formapag = fnLimpaCampo($_REQUEST['COD_FORMAPAG']);
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		$val_valor = fnLimpaCampo(fnValorSql($_REQUEST['VAL_VALOR']));
		$dat_pagamento = fnDataSql($_POST['DAT_PAGAMENTO']);

		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {			

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$sql = "INSERT INTO PROXPAGAMENTO_ADORAI(
						COD_FORMAPAG,
						VAL_VALOR,
						DAT_PAGAMENTO,
						COD_USUCADA
					)
					VALUES(
						$cod_formapag,
						$val_valor,
						'$dat_pagamento',
						$cod_usucada
						)
					 ";

					$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);

					if (!$arrayProc){
						$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					}
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;

				case 'ALT':
					$sql = "UPDATE PROXPAGAMENTO_ADORAI SET 
						COD_FORMAPAG = $cod_formapag,
						VAL_VALOR = $val_valor,
						DAT_PAGAMENTO = '$dat_pagamento',
						COD_ALTERAC = $cod_usucada,
						DAT_ALTERAC = NOW()
						WHERE COD_PROXPAGAMENTO = $cod_proxpagamento
					";

					fnEscreve($sql);
				
					$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					$sql = "";
			
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;					
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_empresa = 274;
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

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = $hoje;
}



?>

<style>
.hiddenRow {
    padding: 0 !important;
}
tr{
	border-bottom: none!important;
}
#blocker
{
    display:none; 
	position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: .8;
    background-color: #fff;
    z-index: 1000;
}
    
#blocker div
{
	position: absolute;
	top: 30%;
	left: 48%;
	width: 200px;
	height: 2em;
	margin: -1em 0 0 -2.5em;
	color: #000;
	font-weight: bold;
}
</style>

<div id="blocker">
   <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando...</div>
</div>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1019";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php 
					$abaAdorai = 2006;
					include "abasAdorai.php"; 

					$abaManutencaoAdorai = fnDecode($_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasSistemaAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROXPAGAMENTO" id="COD_PROXPAGAMENTO" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-xs-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Forma de Pagamento</label>
										<select data-placeholder="Selecione os hotéis" name="COD_FORMAPAG" id="COD_FORMAPAG" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php
											$sqlFormapag = "SELECT COD_FORMAPAG, ABV_FORMAPAG FROM ADORAI_FORMAPAG WHERE COD_EXCLUSA IS NULL AND COD_EMPRESA = $cod_empresa order by COD_FORMAPAG";
											$arrayFormpag = mysqli_query(connTemp($cod_empresa,''), $sqlFormapag);

											while ($qrFormpag = mysqli_fetch_assoc($arrayFormpag)) {
												?>
												<option value="<?=$qrFormpag[COD_FORMAPAG]?>"><?=$qrFormpag[ABV_FORMAPAG]?></option>
												<?php 
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor</label>
											<input type="text" class="form-control input-sm money" name="VAL_VALOR" id="VAL_VALOR" maxlength="100" value="" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data de Pagamento</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_PAGAMENTO" id="DAT_PAGAMENTO" value="<?=$dat_ini?>" required/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
				
						<div class="form-group text-right col-lg-8 col-lg-offset-4">

							<div class="form-group text-right col-lg-12">
								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							</div>
						</div>
						
						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					

					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class='{ sorter: false } text-left'></th>
										<th>Código</th>
										<th>Forma de Pagamento</th>
                                        <th >Valor</th>
                                        <th >Data</th>
									</tr>
								</thead>
								<tbody>

									<?php
										$sql = "SELECT 
												PA.*, AF.ABV_FORMAPAG 
												FROM proxpagamento_adorai AS PA 
												INNER JOIN adorai_formapag AS AF ON AF.COD_FORMAPAG = PA.COD_FORMAPAG";

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$count = 0;

											while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												echo "
												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
													<td>".$qrBusca['COD_PROXPAGAMENTO']."</td>													
													<td>".$qrBusca['ABV_FORMAPAG']."</td>													
													<td>R$ ".fnValor($qrBusca['VAL_VALOR'],2)."</td>					
													<td>".fnDataShort($qrBusca['DAT_PAGAMENTO'])."</td>													
													
												</tr>
												<input type='hidden' id='ret_COD_PROXPAGAMENTO_" . $count . "' value='" . $qrBusca['COD_PROXPAGAMENTO'] . "'>
												<input type='hidden' id='ret_VAL_VALOR_" . $count . "' value='" . fnValor($qrBusca['VAL_VALOR'],2) . "'>
												<input type='hidden' id='ret_DAT_PAGAMENTO_" . $count . "' value='" . fnDataShort($qrBusca['DAT_PAGAMENTO']) . "'>
												<input type='hidden' id='ret_COD_FORMAPAG_" . $count . "' value='" . $qrBusca['COD_FORMAPAG'] . "'>
												";
											}
									?>
								</tbody>
							</table>

						</form>

					</div>

					<div class="push20"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>


<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">



function retornaForm(index) {
			$("#formulario #COD_PROXPAGAMENTO").val($("#ret_COD_PROXPAGAMENTO_" + index).val());	
			$("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_" + index).val());
			$("#formulario #DAT_PAGAMENTO").val($("#ret_DAT_PAGAMENTO_" + index).val());
			$("#formulario #ABV_FORMAPAG").val($("#ret_ABV_FORMAPAG_" + index).val());
			$("#formulario #COD_FORMAPAG").val($("#ret_COD_FORMAPAG_" + index).val()).trigger("chosen:updated");
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');

}

	$('.datePicker').datetimepicker({
		format: 'DD/MM/YYYY'
	}).on('changeDate', function(e) {
		$(this).datetimepicker('hide');
	});


</script>