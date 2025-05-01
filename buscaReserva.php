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

				// $curl = curl_init();

				// curl_setopt_array($curl, array(
				//   CURLOPT_URL => 'https://api.soufoco.com.br/v1/avaiability/ota/OTA_HotelAvailRQ',
				//   CURLOPT_RETURNTRANSFER => true,
				//   CURLOPT_ENCODING => '',
				//   CURLOPT_MAXREDIRS => 10,
				//   CURLOPT_TIMEOUT => 360,
				//   CURLOPT_FOLLOWLOCATION => true,
				//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				//   CURLOPT_CUSTOMREQUEST => 'POST',
				//   CURLOPT_POSTFIELDS =>'<OTA_HotelAvailRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
				// 						    <AvailRequestSegments>
				// 						        <AvailRequestSegment>
				// 						            <HotelSearchCriteria AvailableOnlyIndicator="true">
				// 						                <Criterion>
				// 						                    '.$reservaHotel.'
				// 						                </Criterion>
				// 						            </HotelSearchCriteria>
				// 						            <RoomStayCandidates>
				// 						                <RoomStayCandidate EffectiveDate="'.$dat_ini.'" ExpireDate="'.$dat_fim.'">
				// 						                    <GuestCounts>
				// 						                        <GuestCount AgeQualifyingCode="10" Count="'.$num_adultos.'"/>
				// 						                        '.$hospedeCrianca.'
				// 						                    </GuestCounts>
				// 						                </RoomStayCandidate>
				// 						            </RoomStayCandidates>
				// 						        </AvailRequestSegment>
				// 						    </AvailRequestSegments>
				// 						</OTA_HotelAvailRQ>',
				//   CURLOPT_HTTPHEADER => array(
				//     'Content-Type: text/xml',
				//     'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
				//     'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
				//   ),
				// ));

				// $response = curl_exec($curl);
				// $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
				// $jsonHotel = json_encode($xml);
				// $hotel = json_decode($jsonHotel,TRUE);

				// // echo "<pre>";
				// // print_r($hotel);
				// // echo "</pre>";

				// curl_close($curl);

				// $num_pessoas = $num_adultos + $num_criancas;

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
$conn = conntemp($cod_empresa,"");

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.soufoco.com.br/v1/booking/ota/OTA_ReadRQ',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 360,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'<OTA_ReadRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_ReadRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
						  <ReadRequests>
						    <HotelReadRequest HotelCode="2957">
						      <SelectionCriteria SelectionType="Undelivered"/>
						    </HotelReadRequest>
						  </ReadRequests>
						</OTA_ReadRQ>',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: text/xml',
    'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
    'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
  ),
));

$response = curl_exec($curl);
$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$jsonHotel = json_encode($xml);

$hotel = json_decode($jsonHotel,TRUE);

if($_GET[dev] != ""){
	// echo "<pre>";
	// print_r($hotel);
	// echo "</pre>";
}

// echo "<pre>";
// // fnEscreve("PEGA RESERVAS");
// print_r($hotel);
// echo "</pre>";

curl_close($curl);

// exit();

$reservas = $hotel['ReservationsList']['HotelReservation'];

foreach ($reservas as $reserva) {

    $nom_chale  = $reserva['RoomStays']['RoomStay']['RoomTypes']['RoomType']['RoomDescription']['@attributes']['Name'];
    $reservados = $reserva['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate'];
    $COD_CHALE  = $reserva['RoomStays']['RoomStay']['RoomRates']['RoomRate']['@attributes']['RoomID'];

    $UniqueID = $reserva['UniqueID']['@attributes']['ID'];
    $Cliente = $reserva ['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['GivenName'];
    $ApelidoCliente = $reserva ['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['PersonName']['Surname'];                                                                             
    $TelCliente = $reserva ['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Telephone']['@attributes']['PhoneNumber']; 
    $EmailCliente = $reserva ['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer']['Email']; 

    $Data_reserva = $reserva['@attributes']['CreateDateTime'];                                                                                
    $Status_reserva = $reserva['ResGlobalInfo']['DepositPayments']['GuaranteePayment']['Description']['Text'];
    $FormaPagamentoCli = $reserva['ResGlobalInfo']['DepositPayments']['GuaranteePayment']['@attributes']['PaymentCode'];
    $ValorPago = $reserva['ResGlobalInfo']['DepositPayments']['GuaranteePayment']['AmountPercent']['@attributes']['Amount'];

    $nom_chale = explode('-', $nom_chale);
    foreach ($reserva['RoomStays']['RoomStay']['RoomRates']['RoomRate']['Rates']['Rate'] as $periodoReserva){


    	$dat_checkin = fnDataShort($periodoReserva['@attributes']['EffectiveDate']);
    	$dat_checout = fnDataShort($periodoReserva['@attributes']['ExpireDate']);
    	$val_reserva = $periodoReserva['Total']['@attributes']['AmountAfterTax'];

    	$nomeC=$Cliente." ".$ApelidoCliente;
        $values[] = '('.$cod_empresa.','.$UniqueID.', "'.$COD_CHALE.'","'.$nom_chale[0].'",
                        "'.$nom_chale[1].'","'.$dat_checkin.'","'.$dat_checout.'",
                        "'. fnValorSql(fnValor($val_reserva,2),2).'","'.$nomeC.'","'.$Data_reserva.'",
                          "'.$Status_reserva.'","'.$TelCliente.'","'.$EmailCliente.'","'.$FormaPagamentoCli.'","'. fnValorSql($ValorPago,2).'")';
    	            
    }

}

$in="INSERT INTO buscahoteis (COD_EMPRESA,
	UniqueID, 
	COD_CHALE,
	DES_CHALE,
	DES_LOCAL, 
	Check_in, 
	Check_out, 
	Valor,
	Resevado, 
	Data_Reserva,
	DES_Status, 
	Telefone, 
	EMAIL, 
	FormaPagamento,
	ValorPago) 
VALUES 
".implode(',', $values);

mysqli_query($conn, $in);


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
					$abaAdorai = 1838;
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

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group col-lg-2">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon">
										<span class="fal fa-mobile"></span>
									</span>
									<input type="text" class="form-control text-center sp_celphones" placeholder="Celular para envio" name="NUM_CELULAR" id="NUM_CELULAR" value="">
								</div>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						<div class="form-group text-right col-lg-6 col-lg-offset-4">
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

					

					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-striped table-hover tableSorter">
								<thead>
									<tr>
										<th>Cod. Chale</th>
										<th>UniqueID</th>
										<th>Local</th>
                                                                                <th>Chalé</th>
										<th>Check-in</th>
										<th>Check-out</th>
										<th>Valor</th>
										<th>Resevado</th>
										<th>Data Reserva</th>
										<th>Status</th>
										<th>Telefone</th>
										<th>email</th>
										<th>Forma<br/>Pagamento</th>
										<th>Valor<br/>Pago</th>
									</tr>
								</thead>
								<tbody>

									<?php

									$sqlReserva = "SELECT 
													      COD_EMPRESA,
														  UniqueID,
														  COD_CHALE,
														  DES_CHALE,
														  DES_LOCAL,
														  min(Check_in) Check_in,
														  max(Check_out) Check_out,
														  SUM(Valor) Valor,
														  Resevado,
														  Data_Reserva,
														  DES_Status,
														  Telefone,
														  EMAIL,
														  FormaPagamento,
														  ValorPago  

													FROM  buscahoteis
													GROUP BY UniqueID";

									$arrRes = mysqli_query(connTemp($cod_empresa,''),$sqlReserva);

									while ($qrRes = mysqli_fetch_assoc($arrRes)) {           

										echo "
												<tr>
													<td>" . $qrRes["COD_CHALE"] . "</td> 
													<td>" . $qrRes["UniqueID"] . "</td>
													<td>" . $qrRes["DES_LOCAL"] . "</td>
                                                                                                        <td>" . $qrRes["DES_CHALE"] . "</td>
													<td>" . $qrRes["Check_in"] . "</td>
													<td>" . $qrRes["Check_out"] . "</td>
													<td>" . fnValor($qrRes["Valor"],2) . "</td>
													<td>" . $qrRes["Resevado"] . "</td> 
													<td>" .  fnDataFull($qrRes["Data_Reserva"]) . "</td> 
													<td>" . $qrRes["DES_Status"] . "</td> 
													<td>" . $qrRes["Telefone"] . "</td> 
													<td>" . $qrRes["EMAIL"] . "</td> 
													<td>" . $qrRes["FormaPagamento"] . "</td>     
													<td>" . fnValor($qrRes["ValorPago"],2) . "</td>    
												</tr>
											";        
										

									}

									$sqlTruncate = "TRUNCATE buscahoteis";

									mysqli_query(connTemp($cod_empresa,''),$sqlTruncate);

									?>

								</tbody>
							</table>

						</form>

					</div>

					<div class="push20"></div>

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

<script type="text/javascript">

	$(function(){

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
		let celular = $("#NUM_CELULAR").val();
		if(celular.trim() != ""){
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
							data: {NUM_CELULAR: celular, ARR_MENSAGEM: mensagem},
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
			$.alert({
                title: "Aviso",
                content: "Celular não informado.",
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