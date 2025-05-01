<?php

//echo "<h5>_".$opcao."</h5>";

$hotel = "";
$log_diaria = 'N';
$num_adultos = 2;
$num_criancas = 0;
$cod_hotel = "2957,3010,3008,956";
$num_pessoas = 0;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		// $dat_ini = fnDataSql($_POST['DAT_INI']);
		// $dat_fim = fnDataSql($_POST['DAT_FIM']);
		$dat_reserva = fnLimpaCampo($_POST['DAT_RESERVA']);
		$num_adultos = fnLimpaCampoZero($_POST['NUM_ADULTOS']);
		$num_criancas = fnLimpaCampoZero($_POST['NUM_CRIANCAS']);

		if (empty($_REQUEST['LOG_DIARIA'])) {
			$log_diaria = 'N';
		} else {
			$log_diaria = $_REQUEST['LOG_DIARIA'];
		}

		//array dos hoteis
		if (isset($_POST['COD_HOTEL'])) {
			$Arr_COD_HOTEL = $_POST['COD_HOTEL'];
			$cod_hotel = "";
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_HOTEL); $i++) {
				$cod_hotel = $cod_hotel . $Arr_COD_HOTEL[$i] . ",";
			}

			$cod_hotel = rtrim(ltrim($cod_hotel,","),",");
		} else {
			$cod_hotel = 0;
		}

		// fnEscreve($cod_hotel);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			// $sql = "CALL SP_ALTERA_GRUPOTRABALHO (
			// 	 '" . $cod_grupotr . "', 
			// 	 '" . $des_grupotr . "', 
			// 	 '" . $cod_empresa . "', 
			// 	 '" . $opcao . "'    
			// 	) ";

			// //echo $sql;

			// $arrayProc = mysqli_query($adm, $sql);

			// if (!$arrayProc) {

			// 	$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
			// }

			if($cod_hotel != 0){

				$arrHotel = explode(",", $cod_hotel);
				$reservaHotel = "";
				$hospedeCrianca = "";

				// fnEscreve($dat_reserva);

				$dat_reserva = explode("-", $dat_reserva);

				$dat_ini = fnDataSql($dat_reserva[0]);
				$dat_fim = fnDataSql($dat_reserva[1]);

				// fnEscreve($dat_ini);
				// fnEscreve($dat_fim);

				if($num_criancas > 0){
					// $hospedeCrianca = '<GuestCount AgeQualifyingCode="14" Count="'.$num_criancas.'"/>';
				}

				foreach ($arrHotel as $hotel) {
					//fnEscreve($hotel);
					$reservaHotel .= '<HotelRef HotelCode="'.$hotel.'"/>';
					//fnEscreve($reservaHotel);
				}

				// fnEscreve($reservaHotel);

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://services-hotels.focomultimidia.com/v1/avaiability/ota/OTA_HotelAvailRQ',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 360,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>'<OTA_HotelAvailRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
										    <AvailRequestSegments>
										        <AvailRequestSegment>
										            <HotelSearchCriteria AvailableOnlyIndicator="true">
										                <Criterion>
										                    '.$reservaHotel.'
										                </Criterion>
										            </HotelSearchCriteria>
										            <RoomStayCandidates>
										                <RoomStayCandidate EffectiveDate="'.$dat_ini.'" ExpireDate="'.$dat_fim.'">
										                    <GuestCounts>
										                        <GuestCount AgeQualifyingCode="10" Count="'.$num_adultos.'"/>
										                        '.$hospedeCrianca.'
										                    </GuestCounts>
										                </RoomStayCandidate>
										            </RoomStayCandidates>
										        </AvailRequestSegment>
										    </AvailRequestSegments>
										</OTA_HotelAvailRQ>',
				  CURLOPT_HTTPHEADER => array(
				    'Content-Type: text/xml',
				    'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
				    'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
				  ),
				));

				$response = curl_exec($curl);
				// echo "<pre>";
				// print_r($response);
				// echo "</pre>";
				$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
				$jsonHotel = json_encode($xml);
				$hotel = json_decode($jsonHotel,TRUE);

				curl_close($curl);

				$num_pessoas = $num_adultos + $num_criancas;

				$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];

				if($nom_usuario == ""){
					$nom_usuario == "BUNKER";
				}

				$sqlDesc = "INSERT INTO ACESSOS_ADORAI(
			                                COD_EMPRESA,
			                                DES_ORIGEM,
			                                NUM_CELULAR,
			                                DAT_INI,
			                                DAT_FIM,
			                                COD_HOTEL,
			                                COD_CHALE
			                            ) VALUES(
			                                274,
			                                '$nom_usuario',
			                                '',
			                                '$dat_ini',
			                                '$dat_fim',
			                                '$cod_hotel',
			                                '$cod_chale'
			                            )";

			    mysqli_query(connTemp(274,''), $sqlDesc);

			}

			

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

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = date("Y-m-d", strtotime('+ 1 days'));
}

//fnMostraForm();

$checkDiaria = "";

if($log_diaria == "S"){
	$checkDiaria = "checked";
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
.caleran-container.caleran-hotel-style .caleran-calendar .caleran-disabled.caleran-disabled-range, .caleran-container-mobile.caleran-hotel-style .caleran-calendar .caleran-disabled.caleran-disabled-range {
    background: #efaba0!important;
    color: #fff!important;
}
.caleran-disabled-range-start {
    background-image: -o-linear-gradient(225deg, #efaba0 50%, #b3d8b5 50%)!important;
    background-image: linear-gradient(225deg, #efaba0 50%, #b3d8b5 50%)!important;
}
.caleran-disabled-range-end {
    background-image: -o-linear-gradient(45deg, #efaba0 50%, #b3d8b5 50%)!important;
    background-image: linear-gradient(45deg, #efaba0 50%, #b3d8b5 50%)!important;
}
.caleran-start span, .caleran-end span{
	background-color: #00491F!important;
}
.caleran-selected{
	background-color: #4CAF50!important;
}
.caleran-input{
	min-width: 860px;
}
.caleran-cell-text{
	font-weight: 500!important;
	font-size: 8px!important;
}
</style>

<link href="js/caleran/css/caleran.min.css" rel="stylesheet" />

<div id="blocker">
   <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
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
					$abaAdorai = 1820;
					include "abasAdorai.php"; 
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								
								<div class="col-xs-5">
									<div class="form-group">
										<label for="inputName" class="control-label required">Hotéis</label>
											<select data-placeholder="Selecione os hotéis" name="COD_HOTEL[]" id="COD_HOTEL" multiple="multiple" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php
													$sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
													$arrayHotel = mysqli_query(connTemp($cod_empresa,''), $sqlHotel);

													while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
												?>
														<option value="<?=$qrHotel[COD_EXTERNO]?>">"<?=$qrHotel[NOM_FANTASI]?>"</option>
												<?php 
													}
												?>
												<!-- <option value="2957">Adorai/SP</option>
												<option value="3010">Piedade 2/SP</option>
												<option value="3008">Cunha/SP</option>
												<option value="956">Paraty/RJ</option> -->
											</select>
										<div class="help-block with-errors"></div>
										<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
										<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
									</div>
								</div>

								<div class="col-sm-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Adultos</label>
										<select name="NUM_ADULTOS" id="NUM_ADULTOS" class="chosen-select-deselect" tabindex="1" required>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
										</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$(function(){
												$("#formulario #NUM_ADULTOS").val("<?=$num_adultos?>").trigger("chosen:updated");
											});
										</script>
									</div>
								</div>

								<div class="col-sm-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Crianças</label>
										<select name="NUM_CRIANCAS" id="NUM_CRIANCAS" class="chosen-select-deselect" tabindex="1" readonly>
											<option value="0">0</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
										</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$(function(){
												$("#formulario #NUM_CRIANCAS").val("<?=$num_criancas?>").trigger("chosen:updated");
											});
										</script>
									</div>
								</div>

								<!-- <div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Reserva</label>

										<div class="input-group date input-daterange datePicker" id="DAT_INI_GRP">

											<input type="text" class="form-control input-sm" name="DAT_INI" id="DAT_INI"  value="<?php echo fnDataShort($dat_ini); ?>"  required autocomplete="off" readonly>
										    <div class="input-group-addon">até</div>
										    <input type="text" class="form-control input-sm" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnDataShort($dat_fim); ?>" required autocomplete="off">
										</div>
										<div class="help-block with-errors">Multipla seleção, da maior pra menor</div>
									</div>
								</div> -->

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Período da Reserva</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<span class="input-group-addon">
												<span class="fal fa-calendar"></span>
											</span>
											<input type='text' class="form-control input-sm" name="DAT_RESERVA" id="DAT_RESERVA" value="<?php echo fnDataShort($dat_ini); ?> - <?php echo fnDataShort($dat_fim); ?>" required />
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<!-- <div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Somente Diárias</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_DIARIA" id="LOG_DIARIA" class="switch" value="S" <?=$checkDiaria?>>
											<span></span>
										</label>
									</div>
								</div> -->

							</div>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data da reserva</label>
										<a href="javascript:void(0)" id="caleran_start" class="btn btn-info">Data da reserva</a>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group col-lg-7">

							<div class="form-group col-lg-3">

								<div class="row">

									<?php 

										$sql = "SELECT * FROM CANAL_ADORAI WHERE COD_EMPRESA = 274 AND LOG_PREF = 'S'";

										$arrCanal = mysqli_query(conntemp(274,""), $sql);

										$count = 0;

										while($qrCanal = mysqli_fetch_assoc($arrCanal)){

											// if($qrCanal[LOG_PREF] == "S"){
											// 	$checkCanal = "checked";
											// }else{
											// 	$checkCanal = "";
											// }
									?>

											<div class="col-md-12">
												<div class="radio radio-info radio-inline">
													<input type="radio" id="canal_<?=$count?>" value="<?=$qrCanal[COD_CANAL]?>" name="canalEnvio" <?=$checkCanal?>>
													<label for="canal_<?=$count?>"><?=$qrCanal[DES_CANAL]?>&nbsp;&nbsp;<small><small><?=$qrCanal[NUM_CANAL]?></small></small></label>
												</div>
											</div>

											<div class="push5"></div>

									<?php

											$count++;

										}

									?>


								</div>
							</div>

							<div class="form-group col-lg-3">
								<div class="input-group">
									<span class="input-group-addon">
										<span class="fal fa-mobile"></span>
									</span>
									<input type="text" class="form-control text-center sp_celphones" placeholder="Celular para envio" name="NUM_CELULAR" id="NUM_CELULAR" value="">
								</div>
								<div class="help-block with-errors"></div>
							</div>


						</div>
						<div class="form-group text-right col-lg-5">
							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar Reservas</button>
							<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					

					<div class="col-lg-12">

						<?php 

							// echo "<pre>";
							// print_r($hotel['RoomStays']);
							// echo "</pre>";

						// print_r($hotel['RoomStays']);

						$countQuarto = 0;
						$countVaga = 0;
						$val_total = 0;
						$arrayVagas = array();

						if(count($hotel['RoomStays']) > 0){

						?>

							<table class="table">

								<thead>

									<tr data-toggle="collapse" class="accordion-toggle" data-target="#<?=$chave_linha?>" onclick='rotacionaSeta("<?=$chave_linha?>")'>
										<th width="2%"></th>
										<th>Chalé</th>
										<th class="text-center">Ações</th>
										<th>Local</th>
										<th class="text-right">Preço por Dia</th>
										<th class="text-right">Valor da Reserva</th>
										<th class="text-center">Dt. Check In</th>
										<th class="text-center">Dt. Check Out</th>
									</tr>

								</thead>
							  
								<?php 
								
									if($hotel['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['Total']['@attributes']['AmountAfterTax'] > 0){
										// fnEscreve("if 1");

										$chave_linha = $countQuarto;
										$dat_min = $hotel['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['@attributes'];
										$dat_max = $hotel['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['@attributes']['ExpireDate'];
										$nom_quarto = $hotel['RoomStays']['RoomStay']['RoomTypes']['RoomType']['TPA_Extensions']['Room']['@attributes']['Name'];
										$id_hotel = $hotel['RoomStays']['RoomStay']['@attributes']['RPH'];
										$cod_quarto = $hotel['RoomStays']['RoomStay']['RoomTypes']['RoomType']['TPA_Extensions']['Room']['@attributes']['ID'];
										$val_diaria = $hotel['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['Total']['@attributes']['AmountAfterTax'];
										$val_total = $hotel['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate']['Total']['@attributes']['AmountAfterTax'];

										$nom_quarto = explode("-", $nom_quarto);

										$diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

										// $data = date('Y-m-d');

										$diasemana_inicio = date('w', strtotime($dat_min));
										$diasemana_fim = date('w', strtotime($dat_max));
										$nroQuarto = explode(" ", $nom_quarto[0]);
										$nroDiarias = fnDateDif($dat_min,$dat_max);

										$sqlDesc = "SELECT DES_QUARTO, DES_IMAGEM, DES_VIDEO FROM ADORAI_CHALES 
													WHERE COD_EXTERNO = $cod_quarto";
										$arrayDesc = mysqli_query(connTemp($cod_empresa,''), $sqlDesc);
										$qrDesc = mysqli_fetch_assoc($arrayDesc);

										$sqlVend = "SELECT VA.COD_EXT_VENDEDOR FROM VENDEDOR_ADORAI VA 
														INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = VA.COD_UNIVEND
														WHERE VA.COD_USUARIO = $_SESSION[SYS_COD_USUARIO] 
														AND UV.COD_EXTERNO = $id_hotel";

										// if($_GET['dev'] == 'true'){
										// 	echo $sqlVend;
										// }

										$arrayVend = mysqli_query(connTemp($cod_empresa,''), $sqlVend);
										$qrVend = mysqli_fetch_assoc($arrayVend);

										$arrayVagas[$nroQuarto[1]] = array(
															"idHotel" => $id_hotel,
															"idQuarto" => $cod_quarto,
															"codVendedor" => "$qrVend[COD_EXT_VENDEDOR]",
															"chale" => $nom_quarto[0],
															"local" => $nom_quarto[1],
															"diaria" => $val_diaria,
															"total" => $val_total,
															"dataMin" => $dat_min,
															"dataMax" => $dat_max,
															"semanaIni" => $diasemana[$diasemana_inicio],
															"semanaFim" => $diasemana[$diasemana_fim],
															"nroDiarias" => $nroDiarias,
															"nroPessoas" => $num_pessoas,
															"descricao" => "$qrDesc[DES_QUARTO]",
															"imagem" => "$qrDesc[DES_IMAGEM]",
															"video" => "$qrDesc[DES_VIDEO]"
														);

										$arrayOrdenado = fnorderby_array($arrayVagas, $nroQuarto[1], SORT_ASC );
								
									}

									$abrQuarto = $hotel['RoomStays']['RoomStay'];

									if($abrQuarto == ""){
										// fnEscreve("sem roomstays");
										$abrQuarto = $hotel['RoomStay'];
									}

									// echo "<pre>";
									// print_r($abrQuarto);
									// echo "</pre>";

									// [RoomTypes] => Array
							  //       (
							  //           [RoomType] => Array
							  //               (
							  //                   [TPA_Extensions] => Array
							  //                       (
							  //                           [Room] => Array
							  //                               (
							  //                                   [@attributes] => Array
							  //                                       (
							  //                                           [ID]

									foreach ($abrQuarto as $quarto) {

										// echo "<pre>";
										// print_r($quarto);
										// echo "</pre>";

										$chave_linha = $countQuarto;

										if(count($quarto['RoomRates']['RoomRate']['Rates']['Rate']) > 0){
											$quartosVaga = $quarto['RoomRates']['RoomRate']['Rates']['Rate'];
										}else if(count($quarto['RoomRate']['Rates']['Rate']) > 0){
											$quartosVaga = $quarto['RoomRate']['Rates']['Rate'];
										}else{
											$quartosVaga = "";
										}


										foreach ($quartosVaga as $vaga) {

											// echo "<pre>";
											// print_r($quartosVaga);
											// echo "</pre>";


											if($countVaga == 0){
												$dat_min = $vaga['@attributes']['EffectiveDate'];
											}

											$nom_quarto = $quarto['RoomTypes']['RoomType']['TPA_Extensions']['Room']['@attributes']['Name'];
											$id_hotel = $quarto['@attributes']['RPH'];
											$cod_quarto = $quarto['RoomTypes']['RoomType']['TPA_Extensions']['Room']['@attributes']['ID'];

											$dat_max = $vaga['@attributes']['ExpireDate'];

											if($nom_quarto == ""){
												$nom_quarto = $abrQuarto['RoomTypes']['RoomType']['TPA_Extensions']['Room']['@attributes']['Name'];
												$id_hotel = $abrQuarto['@attributes']['RPH'];
												$cod_quarto = $abrQuarto['RoomTypes']['RoomType']['TPA_Extensions']['Room']['@attributes']['ID'];
											}

											$val_diaria = $vaga['Total']['@attributes']['AmountAfterTax'];
											$val_total += $vaga['Total']['@attributes']['AmountAfterTax'];

											$nom_quarto = explode("-", $nom_quarto);

											$countVaga++;

										}

										if($log_diaria == "N" && $val_total != 0){

											$diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

											// $data = date('Y-m-d');

											$diasemana_inicio = date('w', strtotime($dat_min));
											$diasemana_fim = date('w', strtotime($dat_max));
											$nroQuarto = explode(" ", $nom_quarto[0]);
											$nroDiarias = fnDateDif($dat_min,$dat_max);

											$sqlDesc = "SELECT DES_QUARTO, DES_IMAGEM, DES_VIDEO FROM ADORAI_CHALES WHERE COD_EXTERNO = $cod_quarto";
											$arrayDesc = mysqli_query(connTemp($cod_empresa,''), $sqlDesc);
											$qrDesc = mysqli_fetch_assoc($arrayDesc);

											$sqlVend = "SELECT VA.COD_EXT_VENDEDOR FROM VENDEDOR_ADORAI VA 
														INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = VA.COD_UNIVEND
														WHERE VA.COD_USUARIO = $_SESSION[SYS_COD_USUARIO] 
														AND UV.COD_EXTERNO = $id_hotel";

											// if($_GET['dev'] == 'true'){
											// 	echo $sqlVend;
											// }

											$arrayVend = mysqli_query(connTemp($cod_empresa,''), $sqlVend);
											$qrVend = mysqli_fetch_assoc($arrayVend);


											$arrayVagas[$nroQuarto[1]] = array(
																"idHotel" => $id_hotel,
																"idQuarto" => $cod_quarto,
																"codVendedor" => "$qrVend[COD_EXT_VENDEDOR]",
																"chale" => $nom_quarto[0],
																"local" => $nom_quarto[1],
																"diaria" => $val_diaria,
																"total" => $val_total,
																"dataMin" => $dat_min,
																"dataMax" => $dat_max,
																"semanaIni" => $diasemana[$diasemana_inicio],
																"semanaFim" => $diasemana[$diasemana_fim],
																"nroDiarias" => $nroDiarias,
																"nroPessoas" => $num_pessoas,
																"descricao" => "$qrDesc[DES_QUARTO]",
																"imagem" => "$qrDesc[DES_IMAGEM]",
																"video" => "$qrDesc[DES_VIDEO]"
															);

											$arrayOrdenado = fnorderby_array($arrayVagas, $nroQuarto[1], SORT_ASC );


										}

										$countQuarto++;
										$countVaga = 0;
										$val_total = 0;

									} 

									// echo "<pre>";
									// print_r($arrayVagas);
									// print_r($arrayOrdenado);
									// echo "</pre>";

									foreach ($arrayOrdenado as $qrQuarto) {
								
										$chave_linha = $countQuarto;

										$linkEnvio = "https://roteirosadorai.com.br/detalhes.php?datI=".fnDataShort($qrQuarto['dataMin'])."&datF=".fnDataShort($qrQuarto['dataMax'])."&idh=".$qrQuarto['idHotel']."&idc=".$qrQuarto['idQuarto']."&iv=".base64_encode($qrQuarto[total])."&cv=".$qrQuarto['codVendedor'];
								?>

											<tbody>

												<tr>
													<td><input type='checkbox' name='radio_<?=$chave_linha?>'></td>
													<td data-toggle="collapse" class="accordion-toggle" data-target="#<?=$chave_linha?>" onclick='rotacionaSeta("<?=$chave_linha?>")'><span class="fal fa-angle-right <?=$chave_linha?>" data-expande='0'></span>&nbsp; <a href="javascript:void(0)"><?=$qrQuarto['chale']?></a></td>
													<td class="text-center transparency"><a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="copyToClipboard('<?=$chave_linha?>')"><span class="fal fa-copy"></span></a>
																						 <a href="javascript:void(0)" class="btn btn-xs btn-success" onclick="enviarWhatsapp($('.copy-<?=$chave_linha?>').attr('data-msg-array'))"><span class="fab fa-whatsapp"></span></a></td>
													<td><?=$qrQuarto['local']?></td>
													<td class="text-right"><?=fnValor($qrQuarto['diaria'],2)?></td>
													<td class="text-right"><?=fnValor($qrQuarto['total'],2)?></td>
													<td class="text-center"><?=fnDataShort($qrQuarto['dataMin'])?></td>
													<td class="text-center"><?=fnDataShort($qrQuarto['dataMax'])?></td>
												</tr>

											</tbody>

											<tbody>

												<tr>

													<td colspan="15" class="hiddenRow">
														<div class="accordian-body collapse" id="<?=$chave_linha?>"> 
															<table class="table">

																<thead>

																	<th></th>
																	<th></th>
																	<th></th>
																	<th></th>
																	<th></th>

																</thead>

																		
																															

																<tbody>

																	<tr colspan="5">
																		<div class="row">

																			<div class="push20"></div>

																			<div class="col-md-3 copy-<?=$chave_linha?>"

																				data-copy="*Período:* <?=fnDataShort($qrQuarto['dataMin'])?> <?=$qrQuarto['semanaIni']?> a <?=fnDataShort($qrQuarto['dataMax'])?> <?=$qrQuarto['semanaFim']?>&#013;*Local:*<?=$qrQuarto['local']?>&#013;*Diárias:* <?=$qrQuarto['nroDiarias']?> *Pessoas:* <?=$qrQuarto['nroPessoas']?>&#013;&#013;*Acomodação:* <?=$qrQuarto['chale']?>&#013;<?=$qrQuarto['descricao']?>&#013;&#013;*Valores e opções de pgto:*&#013;*Total:* R$<?=fnValor($qrQuarto['total'],2)?>&#013;*1) PIX* 50% na reserva e 50% até 72hrs antes do check-in, *2x* de *R$<?=fnValor(($qrQuarto['total']/2),2)?>*&#013;*2) Cartão* sem juros até *6x* de *R$<?=fnValor(($qrQuarto['total']/6),2)?>*&#013;&#013;&#013;*Ver detalhes e reservar:* Clique no link...&#013;<?=$linkEnvio?>&#013;&#013;----------------------------------------------&#013;&#013;"
																				
																				data-msg-array='<?=json_encode($qrQuarto)?>'>

																				<a href="javascript:void(0)" class="btn btn-xs btn-default" onclick='visualizarImagem("<?=$chave_linha?>")'><span class="fal fa-image"></span> Visualizar Imagem</a>
																				<div class="push5"></div>
																				<img src="<?=$qrQuarto['imagem']?>" class="img-responsive troca-img-<?=$chave_linha?> off" style="border-radius: 10px; display: none;">
																				<br/>
																				<b>Período:</b> <?=fnDataShort($qrQuarto['dataMin'])?> <?=$qrQuarto['semanaIni']?> a <?=fnDataShort($qrQuarto['dataMax'])?> <?=$qrQuarto['semanaFim']?>
																				<br/>
																				<b>Local:</b><?=$qrQuarto['local']?>
																				<br/>
																				<b>Diárias:</b> <?=$qrQuarto['nroDiarias']?> <b>Pessoas:</b> <?=$qrQuarto['nroPessoas']?>
																				<br/>
																				<br/>
																				<b>Acomodação:</b><?=$qrQuarto['chale']?>
																				<br/>
																				<?=$qrQuarto['descricao']?>
																				<br/>
																				<br/>
																				<b>Valores e opções de pgto:</b>
																				<br/>
																				<b>Total:</b> R$<?=fnValor($qrQuarto['total'],2)?>
																				<br/>
																				<b>1) PIX</b> 50% reserva e pré check-in <b>2x</b> de <b>R$<?=fnValor(($qrQuarto['total']/2),2)?></b>
																				<br/>
																				<b>2) PIX</b> Parcelado Addi até <b>4x</b> (1+3) de <b>R$<?=fnValor(($qrQuarto['total']/4),2)?></b>
																				<br/>
																				<b>3) Cartão</b> sem juros até <b>6x</b> de <b>R$<?=fnValor(($qrQuarto['total']/6),2)?></b>
																				<br/>
																				<br/>
																				<br/>
																				<b>Ver detalhes e reservar:</b> Clique no link...
																				<br/>
																				https://roteirosadorai.com.br/search/<?=$qrQuarto['dataMin']?>/<?=$qrQuarto['dataMax']?>/1/<?=$qrQuarto['idHotel']?>/<?=$qrQuarto['idQuarto']?>/<?=$qrQuarto['codVendedor']?>

																			</div>

																			<div class="col-md-9">
																				<!-- <div class="push100"></div>
																				<div class="push30"></div> -->
																				<a href="javascript:void(0)" class="btn btn-xs btn-info transparency" onclick="copyToClipboard('<?=$chave_linha?>')"><span class="fal fa-copy"></span>&nbsp;Copiar Texto</a>
																				<div class="push5"></div>
																				<a href="javascript:void(0)" class="btn btn-xs btn-success transparency" onclick="enviarWhatsapp($('.copy-<?=$chave_linha?>').attr('data-msg-array'))"><span class="fab fa-whatsapp"></span>&nbsp;Enviar Whatsapp</a>
																			</div>
																			
																		</div>
																	</tr>
																	
																</tbody>
																		

															</table>

														</div> 

													</td>
												</tr>

											</tbody>


								<?php

										$countQuarto++;
									}

	                            ?>

									

									


							</table>

						<?php 

						}else{

							if($_SERVER['REQUEST_METHOD'] == 'POST'){
						?>

								<div class="row">
									<div class="col-md-12 text-center">
										<h4>Não há vagas no hotel e período pesquisado.</h4>
									</div>
								</div>

								<div class="push50"></div>

						<?php 
							}
						}
						?>

					</div>

					<div class="push20"></div>

					<div class="col-md-2">
						<a href="javascript:void(0)" class="btn btn-info btn-sm btn-block" onclick="acaoSelecionados('copiar')"><span class="fal fa-copy"></span>&nbsp;Copiar Selecionados</a>
						<div class="push10"></div>
						<a href="javascript:void(0)" class="btn btn-success btn-sm btn-block" onclick="acaoSelecionados('enviar')"><span class="fab fa-whatsapp"></span>&nbsp;Enviar Selecionados</a>
					</div>

					<div id="AREACODE_OFF" style="display: none;">
						<textarea id="AREACODE"></textarea>
					</div>

					<div class="push"></div>

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
<script type="text/javascript" src="js/caleran/js/caleran.min.js"></script>

<script type="text/javascript">

    var texts = {};
    for (var p = 0; p < 30; p++ ) {
      var timestamp = moment('2023-09-12').add(p, 'day').middleOfDay().unix();
      texts[timestamp] = 'Min. 2 diárias';
    }

    const data = {
        '2023-09-01': 1,
        '2023-09-02': 0,
        '2023-09-03': 0,
        '2023-09-04': 1,
        '2023-09-05': 1,
        '2023-09-06': 1,
        '2023-09-07': 1,
        '2023-09-08': 1,
        '2023-09-09': 1,
        '2023-09-10': 1,
        '2023-09-11': 1,
        '2023-09-12': 1,
        '2023-09-13': 1,
        '2023-09-14': 1,
        '2023-09-15': 1,
        '2023-09-16': 1,
        '2023-09-17': 1,
        '2023-09-18': 1,
        '2023-09-19': 1,
        '2023-09-20': 1,
        '2023-09-21': 1,
        '2023-09-22': 1,
        '2023-09-23': 1,
        '2023-09-24': 1,
        '2023-09-25': 1,
        '2023-09-26': 1,
        '2023-09-27': 1,
        '2023-09-28': 1,
        '2023-09-29': 1,
        '2023-09-30': 1,
    };

            

	$(function(){

		$('#caleran_start').caleran({
            inline: false,
            showOn:"right",
            showHeader: false,
            showFooter: false,
            isHotelBooking: true,
            continuous: true,
            mobileBreakpoint: 300,
            calendarCount: 2,
            minSelectedDays: 1,
            startEmpty: true,
            dayText: texts,
            startOnMonday: false,
            minDate: moment('2023-09-01'),
            maxDate: moment('2023-10-30'),
            oninit: function (instance) {
                instance.globals.currentDate = moment().endOf('day');
                instance.reDrawCalendars();
            },
            disableDays: function (day) {
                return !(day.format('YYYY-MM-DD') in data) ||
                    data[day.format('YYYY-MM-DD')] == 0
                    ? true
                    : false;
                //var new_date = moment(day.format('YYYY-MM-DD'), "YYYY-MM-DD").add(1, 'days');
                //return !(new_date.format('YYYY-MM-DD') in data) || data[new_date.format('YYYY-MM-DD')] == 0 ? true : false;
            },
            onafterselect: function (caleran, startDate, endDate) {
                console.log(startDate.inspect(), endDate.inspect());
                alert('selectable');
            },
        });

		$('input[name="DAT_RESERVA"]').daterangepicker({
		    opens: 'bottom',
		    autoApply: true,
		    locale: { cancelLabel: 'Cancelar', applyLabel: 'Aplicar' }  
		}, function(start, end, label) {
		    //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});


		$('#iAll').on('click', function(e) {
			e.preventDefault();
			$('#COD_HOTEL option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNone').on('click', function(e) {
			e.preventDefault();
			$("#COD_HOTEL option:selected").removeAttr("selected").trigger('chosen:updated');
		});

		var cod_hotel = "<?=$cod_hotel?>";
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

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

	});

	function copyToClipboard(chave) {
		$("#AREACODE_OFF").show();
		$("#AREACODE").text($(".copy-"+chave).attr("data-copy")).select();
		document.execCommand('copy');
		$("#AREACODE_OFF").hide();
	}

	function rotacionaSeta(obj){

		let expande = $("."+obj).attr('data-expande');

		if(expande == 0){
			$("."+obj).attr('data-expande','1').removeClass('fa-angle-right').addClass('fa-angle-down');
		}else{
			$("."+obj).attr('data-expande','0').removeClass('fa-angle-down').addClass('fa-angle-right');
		}

	}

	function acaoSelecionados(acao){
		listaCopia = [];
		param = "data-msg-array";
		if(acao == "copiar"){param = "data-copy";}
		$("table tr").each(function(index) {
			if($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')){
				var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
				listaCopia.push($(".copy-"+codigo).attr(param));
				//alert(codigo);
			}
		});
		if(listaCopia != ''){
			let textoCopia = "";
			if(acao == "copiar"){
				$.each(listaCopia, function (index, value) {
					textoCopia += value;
			    });
			    $("#AREACODE_OFF").show();
				$("#AREACODE").text(textoCopia).select();
				document.execCommand('copy');
				$("#AREACODE_OFF").hide();
			}else{
		    	enviarWhatsapp(listaCopia);
		    }
		}else{
			$.alert({
                title: "Aviso",
                content: "Não há quartos selecionados.",
                type: 'red'
            });
		}
	}

	function enviarWhatsapp(mensagem){
		let celular = $("#NUM_CELULAR").val(),
			canal = $('input[name=canalEnvio]:checked', '#formulario').val(),
			msg = "";
			console.log(canal);
		if(celular.trim() != "" && typeof(canal) !== "undefined"){
			$.alert({
	          title: "Confirmação",
	          content: "Deseja mesmo enviar mensagem para <br /><b>"+celular+"</b>?",
	          type: 'orange',
	          buttons: {
	            "Enviar Mensagem": {
	               btnClass: 'btn-success',
	               action: function(){
	               	    $.ajax({
							type: "POST",
							url: "ajxEnvioReserva.do",
							data: {NUM_CELULAR: celular, ARR_MENSAGEM: mensagem, CANAL: canal},
							beforeSend: function() {
								$('#blocker').show();
							},
							success: function(data) {
								$('#blocker').hide();
								$.alert({
					                title: "Aviso",
					                content: "Mensagem enviada.",
					                type: 'green'
					            });

								console.log(data);
							},
							error: function() {
								$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
							}
						});
	               }
	            },
	            "Cancelar": {
	               action: function(){
	                
	               }
	            }
	          }
	        });
		}else{
			if(celular.trim() == ""){
				msg = "Celular";
			}else{
				msg = "Canal";
			}
			$.alert({
                title: "Aviso",
                content: msg+" não informado.",
                type: 'red'
            });
		}
		
	}

	function visualizarImagem(index){
		img = $(".troca-img-"+index);

		if(img.hasClass("off")){
			img.fadeIn('fast').removeClass("off").addClass("on");
		}else{
			img.fadeOut('fast').removeClass("on").addClass("off");
		}
	}

</script>