<?php

//echo "<h5>_".$opcao."</h5>";

$hotel = "";
$log_diaria = 'N';
$num_adultos = 2;
$num_criancas = 0;
$cod_hotel = fnDecode($_GET['idH']);

function fnArrayPeriodoData($date1, $date2, $format = 'Y-m-d' ) {
  $dates = array();
  $current = strtotime($date1);
  $date2 = strtotime($date2);
  $stepVal = '+1 day';
  while( $current <= $date2 ) {
     $dates[] = date($format, $current);
     $current = strtotime($stepVal, $current);
  }
  return $dates;
}

// fnEscreve($cod_hotel);

//inicialização de variáveis
$hoje = date("Y-m-d");
$diaPrimeiro = date("Y-m-01");
if($hoje > $diaPrimeiro){
	$diaPrimeiro = $hoje;
}
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$ultimoDia = date("Y-m-t");


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

		$dat_filtro = fnDataSql($_POST['DAT_FILTRO']);
		$movimento_calendario = fnLimpaCampo($_POST['MOVIMENTO_CALENDARIO']);

		$complementoData = " - 1 month";

		if($movimento_calendario == "next"){
			$complementoData = " + 1 month";
		}

		$diaPrimeiro = date("Y-m-01", strtotime($dat_filtro.$complementoData));
		if($hoje > $diaPrimeiro){
			$diaPrimeiro = $hoje;
		}
		$ultimoDia = date("Y-m-t", strtotime($dat_filtro.$complementoData));

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
	$cod_empresa = 0;
	$cod_empresa = 274;
}


//fnMostraForm();

$checkDiaria = "";

if($log_diaria == "S"){
	$checkDiaria = "checked";
}




$dat_reserva = explode("-", $dat_reserva);

$dat_ini = $diaPrimeiro;
$dat_fim = $ultimoDia;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://services-hotels.focomultimidia.com/v1/avaiability/ota/OTA_HotelAvailGetRQ',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 360,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="UTF-8"?>
						<OTA_HotelAvailGetRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelResNotifRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
						    <HotelAvailRequests>
						        <HotelAvailRequest>
						        	<DateRange Start="'.$dat_ini.'" End="'.$dat_fim.'" />
						            <HotelRef HotelCode="'.$cod_hotel.'" />
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
$hotel = json_decode(json_encode($xml),TRUE);
unset($hotel['@attributes']);
unset($hotel['Success']);
               
foreach ($hotel as $DadosHotel) {

    $HotelCode=$DadosHotel['@attributes']['HotelCode'];

    foreach ($DadosHotel[AvailStatusMessage] as $dados){
    
  //   	echo "<pre>";
		// print_r($dados);
		// echo "</pre>";

        if($dados['@attributes']['BookingLimit']>='1'){
                
                    
            if ($dados['StatusApplicationControl']['RestrictionStatus'][2]['@attributes']['Status']=="Open"){
            
                if(isset($HotelCode)){  

                	$cod_chale = $dados['StatusApplicationControl']['@attributes']['RatePlanCode'];

                	$sqlChale = "SELECT DISTINCT * FROM ADORAI_CHALES 
								 WHERE COD_EMPRESA = $cod_empresa
								 AND COD_HOTEL = $cod_hotel
								 AND COD_EXTERNO = $cod_chale
								 AND COD_EXCLUSA = 0";

					// fnEscreve($sqlChale);

					$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sqlChale);
					$qrChale = mysqli_fetch_assoc($arrayQuery);

					$arrData = fnArrayPeriodoData($dados['StatusApplicationControl']['@attributes']['Start'], $dados['StatusApplicationControl']['@attributes']['End']);

					// echo "<pre>";
					// print_r($arrData);
					// echo "</pre>";

					foreach ($arrData as $dataIniReserva) {

						$diaIniReserva = date("d",strtotime($dataIniReserva));
						$dataFimReserva = date('Y-m-d', strtotime($dataIniReserva.' +1 day'));

						$arrayOpen[$qrChale[NOM_QUARTO]][]=array('Status'=>'OPEN',
                    										   'Nome'=>"$qrChale[NOM_QUARTO]",
                    										   'ID'=>"$qrChale[COD_EXTERNO]",
                    										   'diaInicio'=>$diaIniReserva,
                                                               'DataInicio'=>$dataIniReserva,
                                                               'DataFim'=>$dataFimReserva,
                                                               'diff'=>1
                                                            );

					}

                }
           }        

        }else{

            if(isset($HotelCode)){  

				$cod_chale = $dados['StatusApplicationControl']['@attributes']['RatePlanCode'];

                $sqlChale = "SELECT DISTINCT * FROM ADORAI_CHALES 
								 WHERE COD_EMPRESA = $cod_empresa
								 AND COD_HOTEL = $cod_hotel
								 AND COD_EXTERNO = $cod_chale
								 AND COD_EXCLUSA = 0";

					// fnEscreve($sqlChale);

					$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sqlChale);
					$qrChale = mysqli_fetch_assoc($arrayQuery);

					$diaIniReserva = date("d",strtotime($dados['StatusApplicationControl']['@attributes']['Start']));

                    $arrayOpen[$qrChale[NOM_QUARTO]][]=array('Status'=>'CLOSE',
                    										   'Nome'=>"$qrChale[NOM_QUARTO]",
                    										   'ID'=>"$qrChale[COD_EXTERNO]",
                                                               'diaInicio'=>$diaIniReserva,
                                                               'DataInicio'=>$dados['StatusApplicationControl']['@attributes']['Start'],
                                                               'DataFim'=>$dados['StatusApplicationControl']['@attributes']['End'],
                                                               'diff'=>(fnDateDif($dados['StatusApplicationControl']['@attributes']['Start'],
                                                               					  $dados['StatusApplicationControl']['@attributes']['End'])-1)
                                                            );
            }
        }    
    }  
}

// echo "<pre>";
// print_r($arrayOpen);
// echo "</pre>";

// exit();
                
 ksort($arrayOpen);

 // echo "<pre>";
 // print_r($hotel);
 // print_r($arrayOpen);
 // echo "</pre>";

 // exit();



?>

<style type="text/css">
	.fa-stack{
		width: unset;
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
				$abaAdorai = fnLimpacampo(fnDecode($_GET['mod']));
				include "abasAdorai.php"; 
				?>

				<div class="push30"></div>

				<div class="login-form">

										
					<?php

					/* Set the default timezone */
					setlocale(LC_ALL, NULL);
					setlocale(LC_ALL, 'pt_BR');  					
					date_default_timezone_set("america/sao_paulo");

					/* Set the date */
					// $date = strtotime(date("Y-m-d"));
					$date = strtotime($dat_ini);

					$day = date('d', $date);
					$month = date('m', $date);
					$year = date('Y', $date);

					// $nextyear = strtotime('+1 month', $date);

					$firstDay = mktime(0,0,0,$month, 1, $year);
					$title = strftime('%B', $firstDay);
					// $dayOfWeek = date('D', $firstDay);
					// $daysInMonth = cal_days_in_month(0, $month, $year);
					// /* Get the name of the week days */
					// $timestamp = strtotime('next Sunday');
					// $weekDays = array();

					// for ($i = 0; $i < 32; $i++) {
					// 	$weekDays[] = strftime('%a', $timestamp);
					// 	$timestamp = strtotime('+1 day', $timestamp);
					// }
					// $blank = date('w', strtotime("{$year}-{$month}-01"));



					$diasMes = array();

					// for each day in the month
					for($i = 1; $i <=  date('t', strtotime($dat_ini)); $i++)
					{

						$dia = str_pad($i, 2, '0', STR_PAD_LEFT);
						$anoMes = date('Y', strtotime($dat_ini)) . "-" . date('m', strtotime($dat_ini));
						$nroSem = date('w', strtotime($anoMes . "-" . $dia));
						$timestamp = strtotime($anoMes . "-" . $dia);
						$diaSem = utf8_encode(strftime('%a', $timestamp));

					    // add the date to the dates array
					    $diasMes[$dia] = array(
					   					"dia" => $dia,
					   					"nroSem" => $nroSem,
					   					"diaSem" => $diaSem
					   				   );

					    
					}


					 // echo "<pre>";
					 // print_r($diasMes);
					 // echo "</pre>";

					?>


<style>
.ntop { 
border-top: none !important;
}

/*
table, th, td {
  border: 1px solid !important;
}
*/

</style>

					<table class="table table-bordered">
						<tr>
							<td><a href="javascript:void(0)" onclick='changeMonth("<?=fnDataShort($dat_ini)?>","prev")'><span class="fal fa-angle-left fa-2x"></span></a></td>
							<th colspan="31" class="text-center"> <?php echo ucfirst($title) ?> <?php echo $year ?> </th>
							<td><a href="javascript:void(0)" onclick='changeMonth("<?=fnDataShort($dat_ini)?>","next")'><span class="fal fa-angle-right fa-2x"></span></a></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<?php 
								foreach($diasMes as $diaMes){ 
							?>
							<?php 
									$corSemana = "";
									if($diaMes['diaSem'] == "Sáb" || $diaMes['diaSem'] == "Dom"){
										$corSemana = 'background-color:#FEF9E7;';
									}
							?>
										<td class="text-center" style="<?=$corSemana?>"><?=$diaMes['diaSem']?></td>
							<?php 
								} 
							?>
						</tr>

						<tr>
							<td class="ntop"></td>
							
							
							<?php 
								foreach($diasMes as $diaMes){ 

									$corSemana = "";
									if($diaMes['diaSem'] == "Sáb" || $diaMes['diaSem'] == "Dom"){
										$corSemana = 'background-color:#FEF9E7;';
									}
							?>

								
									<td class="text-center ntop" style="<?=$corSemana?>"><?=$diaMes['dia']?></td>
								
								
							<?php 
								} 
							?>
							
							
						</tr>

						<?php 

							foreach (array_keys($arrayOpen) as $chale) {

						?>

								<tr>
									<td><b><?=$chale?></b></td>

									
									<?php 
										foreach($diasMes as $diaMes){ 

											$corSemana = "";
											if($diaMes['diaSem'] == "Sáb" || $diaMes['diaSem'] == "Dom"){
												$corSemana = 'background-color:#FEF9E7;';
											}
									?>
										
												<td class="text-center" style="<?=$corSemana?>">  
												<!-- quarto disponivel s/n --> 
									<?php 

													$corStatus = "text-muted";
													$nroDiarias = 0;

													foreach ($arrayOpen[$chale] as $diaReserva) {

														$diaIni = $diaReserva['diaInicio'];
														$statusReserva = $diaReserva['Status'];

														if($diaIni == $diaMes['dia']){
															if($statusReserva == "OPEN"){
																$corStatus = "text-success";
																$nroDiarias = $diaReserva['diff'];
																if($nroDiarias < 0){
																	$nroDiarias = 0;
																}
																break;
															}

														}
														
													}

									?>
													<span class="fa-stack">
													    <i class="fas fa-square fa-2x <?=$corStatus?>"></i>
													    <span class="fa-stack-1x fa-inverse" style="font-size: 12px;"><?=$nroDiarias?></span>
													</span>
													<!-- <span class="fas fa-square fa-2x <?=$corStatus?>"></span> -->

												</td>
										
									
										
									<?php 
										} 
									?>
									
							

								</tr>

						<?php


							}

						?>
												
						
					</table>

					<div class="push50"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<form role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

	<input type="hidden" name="opcao" id="opcao" value="">
	<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
	<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
	<input type="hidden" name="DAT_FILTRO" id="DAT_FILTRO" value="">
	<input type="hidden" name="MOVIMENTO_CALENDARIO" id="MOVIMENTO_CALENDARIO" value="">


</form>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/daterangepicker-master/daterangepicker.js"></script>
<link rel="stylesheet" href="js/daterangepicker-master/daterangepicker.css" />

<script type="text/javascript">

	function changeMonth(data, movimento){
		$("#DAT_FILTRO").val(data);
		$("#MOVIMENTO_CALENDARIO").val(movimento);
		document.forms["formulario"].submit();
	}

</script>