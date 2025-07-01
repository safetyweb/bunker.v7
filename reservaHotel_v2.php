<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$hotel = "";
$log_diaria = "";
$num_adultos = "";
$num_criancas = "";
$cod_hotel = "";
$date1 = "";
$date2 = "";
$format = "";
$dates = "";
$current = "";
$stepVal = "";
$hoje = "";
$diaPrimeiro = "";
$dias30 = "";
$ultimoDia = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$dat_filtro = "";
$Arr_COD_HOTEL = "";
$Arr_COD_MULTEMP = "";
$i = 0;
$movimento_calendario = "";
$complementoData = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$checkDiaria = "";
$arrHotel = "";
$dat_reserva = "";
$dat_ini = "";
$dat_fim = "";
$codHotel = "";
$curl = "";
$response = "";
$xml = "";
$DadosHotel = "";
$sqlHotel = "";
$arrayHotel = [];
$qrHotel = "";
$HotelCode = "";
$dados = "";
$cod_chale = "";
$sqlChale = "";
$qrChale = "";
$arrData = "";
$dataIniReserva = "";
$diaIniReserva = "";
$dataFimReserva = "";
$arrayOpen = [];
$date = "";
$day = "";
$month = "";
$year = "";
$firstDay = "";
$title = "";
$diasMes = "";
$dia = "";
$anoMes = "";
$nroSem = "";
$timestamp = "";
$diaSem = "";
$corSemana = "";
$boldHoje = "";
$formBack = "";
$abaAdorai = "";
$dataHoje = "";
$selectedDat = "";
$dataFutura = "";
$countHotel = "";
$hotelLoop = "";
$paramIni = "";
$diaMes = "";
$chale = "";
$corStatus = "";
$nroDiarias = "";
$diaReserva = "";
$diaIni = "";
$statusReserva = "";


//echo "<h5>_".$opcao."</h5>";

$hotel = "";
$log_diaria = 'N';
$num_adultos = 2;
$num_criancas = 0;
// $cod_hotel = fnDecode(@$_GET['idH']);
$cod_hotel = "956,5156,5158,2957,3010,3008";

/* Set the default timezone */
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set("america/sao_paulo");

function fnArrayPeriodoData($date1, $date2, $format = 'Y-m-d')
{
	$dates = array();
	$current = strtotime($date1);
	$date2 = strtotime($date2);
	$stepVal = '+1 day';
	while ($current <= $date2) {
		$dates[] = date($format, $current);
		$current = strtotime($stepVal, $current);
	}
	return $dates;
}

// fnEscreve($cod_hotel);

//inicialização de variáveis
$hoje = date("Y-m-d");
$diaPrimeiro = date("Y-m-01");
if ($hoje > $diaPrimeiro) {
	$diaPrimeiro = $hoje;
}
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$ultimoDia = date("Y-m-t");


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {

		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;

		$dat_filtro = fnDataSql(@$_POST['DAT_FILTRO']);
		//array dos hoteis
		if (isset($_POST['COD_HOTEL'])) {
			$Arr_COD_HOTEL = @$_POST['COD_HOTEL'];
			$cod_hotel = "";
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_HOTEL); $i++) {
				$cod_hotel = $cod_hotel . $Arr_COD_HOTEL[$i] . ",";
			}

			$cod_hotel = rtrim(ltrim($cod_hotel, ","), ",");
		} else {
			$cod_hotel = 0;
		}
		$movimento_calendario = fnLimpaCampo(@$_POST['MOVIMENTO_CALENDARIO']);

		// fnEscreve($movimento_calendario);

		if ($movimento_calendario == "prev") {
			$complementoData = " - 1 month";
		} else if ($movimento_calendario == "next") {
			$complementoData = " + 1 month";
		} else {
			$complementoData = "";
		}

		$diaPrimeiro = date("Y-m-01", strtotime($dat_filtro . $complementoData));
		if ($hoje > $diaPrimeiro) {
			$diaPrimeiro = $hoje;
		}
		$ultimoDia = date("Y-m-t", strtotime($dat_filtro . $complementoData));

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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
	$cod_empresa = 0;
	$cod_empresa = 274;
}


//fnMostraForm();

$checkDiaria = "";

if ($log_diaria == "S") {
	$checkDiaria = "checked";
}


$arrHotel = explode(",", $cod_hotel);

$dat_reserva = explode("-", $dat_reserva);

$dat_ini = $diaPrimeiro;
$dat_fim = $ultimoDia;

$dat_filtro = date("Y-m-01", strtotime($dat_ini));

// fnEscreve($dat_ini);

// $cod_hotel = '5158';

foreach ($arrHotel as $codHotel) {

	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.soufoco.com.br/v1/avaiability/ota/OTA_HotelAvailGetRQ',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 360,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
							<OTA_HotelAvailGetRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelResNotifRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
							    <HotelAvailRequests>
							        <HotelAvailRequest>
							        	<DateRange Start="' . $dat_ini . '" End="' . $dat_fim . '" />
							            <HotelRef HotelCode="' . $codHotel . '" />
							            <TPA_Extensions>
							        		<RestrictionStatusCandidates SendAllRestrictions="true"/>
							        	</TPA_Extensions>
							        </HotelAvailRequest>
							    </HotelAvailRequests>
							</OTA_HotelAvailGetRQ>',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: text/xml',
			'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
			'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
		),
	));

	$response = curl_exec($curl);
	curl_close($curl);

	$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
	$hotel = json_decode(json_encode($xml), TRUE);
	unset($hotel['@attributes']);
	unset($hotel['Success']);

	if (@$_GET['dev'] != "") {
		echo "<pre>";
		print_r($hotel);
		echo "</pre>";
	}

	foreach ($hotel as $DadosHotel) {

		$sqlHotel = "SELECT NOM_FANTASI FROM UNIDADEVENDA 
					 WHERE COD_EMPRESA = $cod_empresa 
					 AND LOG_ESTATUS = 'S'
					 AND COD_EXTERNO = $codHotel";
		$arrayHotel = mysqli_query(connTemp($cod_empresa, ''), $sqlHotel);

		$qrHotel = mysqli_fetch_assoc($arrayHotel);

		// fnEscreve($sqlHotel);

		$HotelCode = $DadosHotel['@attributes']['HotelCode'];

		foreach ($DadosHotel['AvailStatusMessage'] as $dados) {


			if ($dados['@attributes']['BookingLimit'] >= '1') {


				if (
					$dados['StatusApplicationControl']['RestrictionStatus'][2]['@attributes']['Status'] == "Open"
					// && $dados['StatusApplicationControl']['RestrictionStatus'][0]['@attributes']['Status']=="Open" 
					// && $dados['StatusApplicationControl']['RestrictionStatus'][1]['@attributes']['Status']=="Open"
				) {

					if (isset($HotelCode)) {

						// fnEscreve($dados['StatusApplicationControl']['LengthsOfStay']['LengthOfStay'][0]['@attributes']['Time']);

						$cod_chale = $dados['StatusApplicationControl']['@attributes']['RatePlanCode'];

						$sqlChale = "SELECT DISTINCT * FROM ADORAI_CHALES 
						WHERE COD_EMPRESA = $cod_empresa
						AND COD_HOTEL = $codHotel
						AND COD_EXTERNO = $cod_chale
						AND COD_EXCLUSA = 0";

						// fnEscreve($sqlChale);

						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlChale);
						$qrChale = mysqli_fetch_assoc($arrayQuery);

						$arrData = fnArrayPeriodoData($dados['StatusApplicationControl']['@attributes']['Start'], $dados['StatusApplicationControl']['@attributes']['End']);

						// echo "<pre>";
						// print_r($arrData);
						// echo "</pre>";

						foreach ($arrData as $dataIniReserva) {

							$diaIniReserva = date("d", strtotime($dataIniReserva));
							$dataFimReserva = date('Y-m-d', strtotime($dataIniReserva . ' +1 day'));

							$arrayOpen[$qrHotel['NOM_FANTASI']][$qrChale['NOM_QUARTO']][] = array(
								'Status' => 'OPEN',
								'Nome' => "$qrChale[NOM_QUARTO]",
								'ID' => "$qrChale[COD_EXTERNO]",
								'diaInicio' => $diaIniReserva,
								'DataInicio' => $dataIniReserva,
								'DataFim' => $dataFimReserva,
								'diff' => 1,
								'status' => $dados['StatusApplicationControl']['RestrictionStatus'][2]['@attributes']['Status']
							);
						}
					}
				}
			} else {

				if (isset($HotelCode)) {

					$cod_chale = $dados['StatusApplicationControl']['@attributes']['RatePlanCode'];

					$sqlChale = "SELECT DISTINCT * FROM ADORAI_CHALES 
					WHERE COD_EMPRESA = $cod_empresa
					AND COD_HOTEL = $codHotel
					AND COD_EXTERNO = $cod_chale
					AND COD_EXCLUSA = 0";

					// fnEscreve($sqlChale);

					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlChale);
					$qrChale = mysqli_fetch_assoc($arrayQuery);

					$diaIniReserva = date("d", strtotime($dados['StatusApplicationControl']['@attributes']['Start']));

					$arrayOpen[$qrHotel['NOM_FANTASI']][$qrChale['NOM_QUARTO']][] = array(
						'Status' => 'CLOSE',
						'Nome' => "$qrChale[NOM_QUARTO]",
						'ID' => "$qrChale[COD_EXTERNO]",
						'diaInicio' => $diaIniReserva,
						'DataInicio' => $dados['StatusApplicationControl']['@attributes']['Start'],
						'DataFim' => $dados['StatusApplicationControl']['@attributes']['End'],
						'diff' => (fnDateDif(
							$dados['StatusApplicationControl']['@attributes']['Start'],
							$dados['StatusApplicationControl']['@attributes']['End']
						) - 1),
						'status' => $dados['StatusApplicationControl']['RestrictionStatus'][2]['@attributes']['Status']
					);
				}
			}

			ksort($arrayOpen[$qrHotel['NOM_FANTASI']]);
		}
	}
}

asort($arrayOpen);

$arrayOpen = array_reverse($arrayOpen, true);

// echo "<pre>";
// print_r($arrayOpen);
// echo "</pre>";

// exit();

/* Set the date */
$date = strtotime($dat_ini);

$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);

$firstDay = mktime(0, 0, 0, $month, 1, $year);
$title = strftime('%B', $firstDay);

$diasMes = array();

// for each day in the month
for ($i = 1; $i <=  date('t', strtotime($dat_ini)); $i++) {

	$dia = str_pad($i, 2, '0', STR_PAD_LEFT);
	$anoMes = date('Y', strtotime($dat_ini)) . "-" . date('m', strtotime($dat_ini));
	$nroSem = date('w', strtotime($anoMes . "-" . $dia));
	$timestamp = strtotime($anoMes . "-" . $dia);
	$diaSem = utf8_encode(strftime('%a', $timestamp));

	$corSemana = "";

	if ($diaSem == "Sáb" || $diaSem == "Dom") {
		$corSemana = 'background-color:#FEF9E7;';
	}

	$boldHoje = "";

	if (date("Y") == $year && date("m") == $month && date("d") == $dia) {
		$boldHoje = "font-weight: bolder;";
	}

	// add the date to the dates array
	$diasMes[$dia] = array(
		"dia" => $dia,
		"nroSem" => $nroSem,
		"diaSem" => $diaSem,
		"corSem" => $corSemana,
		"boldHoje" => $boldHoje
	);
}



?>

<style type="text/css">
	.fa-stack {
		width: unset;
	}

	.chosen-container .chosen-results li em {
		font-style: normal;
		font-weight: bold;
	}

	.ntop {
		border-top: none !important;
	}

	.f20 {
		font-size: 20px;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
				$abaAdorai = fnLimpacampo(fnDecode(@$_GET['mod']));
				include "abasAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados da Busca</legend>

							<div class="row">

								<div class="col-xs-5">
									<div class="form-group">
										<label for="inputName" class="control-label required">Hotéis</label>
										<select data-placeholder="Selecione os hotéis" name="COD_HOTEL[]" id="COD_HOTEL" multiple="multiple" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php
											$sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
											$arrayHotel = mysqli_query(connTemp($cod_empresa, ''), $sqlHotel);

											while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
											?>
												<option value="<?= $qrHotel['COD_EXTERNO'] ?>">"<?= $qrHotel['NOM_FANTASI'] ?>"</option>
											<?php
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
										<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
									</div>
								</div>

								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Datas</label>
										<select data-placeholder="Selecione o período" name="DAT_FILTRO" id="DAT_FILTRO" class="chosen-select-deselect">

											<?php

											$dataHoje = date("Y-m-01");

											if ($dat_filtro == "") {
												$dat_filtro = $dat_ini;
											}

											?>
											<option value="<?= $dataHoje ?>" <?= $selectedDat ?>><?= utf8_encode(ucfirst(strftime("%B"))) ?>/<?= date("Y", strtotime($dataHoje)) ?></option>
											<?php

											for ($i = 1; $i <= 16; $i++) {

												$dataFutura = date("Y-m-01", strtotime($dataHoje . " + $i months"));
											?>
												<option value="<?= $dataFutura ?>"><?= utf8_encode(ucfirst(strftime('%B', strtotime($dataFutura)))) ?>/<?= date("Y", strtotime($dataFutura)) ?></option>
											<?php
											}

											?>
										</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$("#formulario #DAT_FILTRO").val("<?= $dat_filtro ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>


							</div>

						</fieldset>

						<div class="push10"></div>

						<div class="form-group text-right col-lg-6 col-lg-offset-6">
							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar Calendários</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="MOVIMENTO_CALENDARIO" id="MOVIMENTO_CALENDARIO" value="">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>




					<?php

					$countHotel = 0;
					foreach (array_keys($arrayOpen) as $hotelLoop) {

						$paramIni = date('Y-m-01', strtotime($dat_ini));

					?>

						<div class="row">
							<div class="col-md-4">
								<a class="f20" data-toggle="collapse" href="#collapse_<?= $countHotel ?>" role="button" aria-expanded="false" aria-controls="collapse_<?= $countHotel ?>" onclick='$(".troca_<?= $countHotel ?>").toggleClass("fa-minus fa-plus");'>
									<b><span class="fal fa-minus troca_<?= $countHotel ?>"></span>&nbsp;&nbsp;<?= $hotelLoop ?></b>
								</a>
							</div>
						</div>

						<div class="push20"></div>

						<div class="collapse in" id="collapse_<?= $countHotel ?>">

							<style>
								#DAT_FILTRO_<?= $countHotel ?>_chosen .chosen-single {
									font-weight: bolder !important;
									border: unset !important;
									box-shadow: unset !important;
									font-size: 16px;
								}

								#DAT_FILTRO_<?= $countHotel ?>_chosen .chosen-single:active {
									border: unset !important;
									box-shadow: unset !important;
								}
							</style>

							<div class="row">
								<div class="col-xs-4 text-left">
									<a href="javascript:void(0)" onclick='changeMonth("<?= $paramIni ?>","prev")'><span class="fal fa-angle-left fa-2x"></span></a>
								</div>
								<div class="col-xs-4 text-center">
									<div class="col-xs-6 col-xs-offset-3">
										<div class="form-group">
											<select name="DAT_FILTRO_<?= $countHotel ?>" id="DAT_FILTRO_<?= $countHotel ?>" class="chosen-select-deselect bold-select">

												<option value="<?= $dataHoje ?>" <?= $selectedDat ?>><?= utf8_encode(ucfirst(strftime("%B"))) ?>/<?= date("Y", strtotime($dataHoje)) ?></option>

												<?php

												for ($i = 1; $i <= 16; $i++) {

													$dataFutura = date("Y-m-01", strtotime($dataHoje . " + $i months"));
												?>
													<option value="<?= $dataFutura ?>"><?= utf8_encode(ucfirst(strftime('%B', strtotime($dataFutura)))) ?>/<?= date("Y", strtotime($dataFutura)) ?></option>
												<?php
												}

												?>
											</select>
											<div class="help-block with-errors"></div>
											<script type="text/javascript">
												$("#DAT_FILTRO_<?= $countHotel ?>").val("<?= $dat_filtro ?>").trigger("chosen:updated");
												$("#DAT_FILTRO_<?= $countHotel ?>").on("change", function() {
													changeMonth($("#DAT_FILTRO_<?= $countHotel ?>").val(), "keep");
												});
											</script>
										</div>
									</div>
								</div>
								<div class="col-xs-4 text-right">
									<a href="javascript:void(0)" onclick='changeMonth("<?= $paramIni ?>","next")'><span class="fal fa-angle-right fa-2x"></span></a>
								</div>
							</div>


							<table class="table table-bordered">
								<tr>
									<td>&nbsp;</td>
									<?php
									foreach ($diasMes as $diaMes) {
									?>
										<td class="text-center" style="<?= $diaMes['corSem'] ?> <?= $diaMes['boldHoje'] ?>"><?= $diaMes['diaSem'] ?></td>
									<?php
									}
									?>
								</tr>

								<tr>
									<td class="ntop"></td>


									<?php
									foreach ($diasMes as $diaMes) {
									?>
										<td class="text-center ntop" style="<?= $diaMes['corSem'] ?> <?= $diaMes['boldHoje'] ?>"><?= $diaMes['dia'] ?></td>
									<?php
									}
									?>


								</tr>

								<?php

								foreach (array_keys($arrayOpen[$hotelLoop]) as $chale) {

								?>

									<tr>
										<td><b><?= $chale ?></b></td>


										<?php
										foreach ($diasMes as $diaMes) {
										?>
											<td class="text-center" style="<?= $diaMes['corSem'] ?>">
												<?php

												$corStatus = "text-muted";
												$nroDiarias = 0;

												foreach ($arrayOpen[$hotelLoop][$chale] as $diaReserva) {

													if ($diaReserva['status'] == "Close") {
														$corStatus = "text-danger";
													}

													$diaIni = $diaReserva['diaInicio'];
													$statusReserva = $diaReserva['Status'];

													if ($diaIni == $diaMes['dia']) {
														if ($statusReserva == "OPEN") {
															$corStatus = "text-success";
															$nroDiarias = $diaReserva['diff'];
															if ($nroDiarias < 0) {
																$nroDiarias = 0;
															}
															break;
														}
													}
												}

												?>
												<span class="fa-stack">
													<i class="fas fa-square fa-2x <?= $corStatus ?>"></i>
													<span class="fa-stack-1x fa-inverse" style="font-size: 12px;"><?= $nroDiarias ?></span>
												</span>

											</td>



										<?php
										}
										?>



									</tr>

								<?php


								}

								?>


							</table>

							<div class="push30"></div>

						</div>

					<?php

						$countHotel++;
					}

					?>

				</div>

			</div>

		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/daterangepicker-master/daterangepicker.js"></script>
<link rel="stylesheet" href="js/daterangepicker-master/daterangepicker.css" />

<script type="text/javascript">
	$(function() {
		$('#iAll').on('click', function(e) {
			e.preventDefault();
			$('#COD_HOTEL option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNone').on('click', function(e) {
			e.preventDefault();
			$("#COD_HOTEL option:selected").removeAttr("selected").trigger('chosen:updated');
		});

		var cod_hotel = "<?= $cod_hotel ?>";
		if (cod_hotel != 0 && cod_hotel != "") {
			//retorno combo multiplo - USUARIOS_ATE
			$("#formulario #COD_HOTEL").val('').trigger("chosen:updated");

			var sistemasUni = cod_hotel;
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_HOTEL option[value=" + Number(sistemasUniArr[i]) + "]").prop("selected", "true");
			}
			$("#formulario #COD_HOTEL").trigger("chosen:updated");
		}
	});

	function changeMonth(data, movimento) {
		$("#DAT_FILTRO").val(data).trigger("chosen:updated");
		$("#MOVIMENTO_CALENDARIO").val(movimento);
		// $("#formulario").removeAttr("action").attr("action","<?php echo $cmdPage; ?>&DAT_FILTRO="+"<?= $dat_filtro ?>");
		document.forms["formulario"].submit();
	}
</script>