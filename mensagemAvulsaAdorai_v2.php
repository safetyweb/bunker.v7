<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$num_celular = fnLimpaDoc($_REQUEST['NUM_CELULAR']);
		$cod_canal = fnLimpaCampoZero($_POST['canalEnvio']);

		$des_img1 = fnLimpaCampo($_REQUEST['DES_IMG1']);
		$des_template1 = base64_encode($_REQUEST['DES_TEMPLATE1']);
		$des_img2 = fnLimpaCampo($_REQUEST['DES_IMG2']);
		$des_template2 = base64_encode($_REQUEST['DES_TEMPLATE2']);
		$des_img3 = fnLimpaCampo($_REQUEST['DES_IMG3']);
		$des_template3 = base64_encode($_REQUEST['DES_TEMPLATE3']);
		
		$cod_empresa = 274;

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			switch($opcao){
			
			case 'CAD':

				$sql = "SELECT KEY_CANAL FROM CANAL_ADORAI WHERE COD_EMPRESA = 274 AND COD_CANAL = $cod_canal";

				// fnEscreve($sql);

				$arrCanal = mysqli_query(conntemp(274,""), $sql);

				$qrCanal = mysqli_fetch_assoc($arrCanal);

				$chave = $qrCanal['KEY_CANAL'];

				// fnEscreve($chave);

				$sql = "SELECT * FROM MENSAGEM_ADORAI 
						WHERE COD_EMPRESA = $cod_empresa
						ORDER BY COD_MENSAGEM DESC LIMIT 1";
				$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);


				if(isset($arrayQuery)){

					$qrBusca = mysqli_fetch_assoc($arrayQuery);

					$des_img1 = $qrBusca[DES_IMG1];
					$des_template1 = base64_decode($qrBusca[DES_TEMPLATE1]);
					$des_img2 = $qrBusca[DES_IMG2];
					$des_template2 = base64_decode($qrBusca[DES_TEMPLATE2]);
					$des_img3 = $qrBusca[DES_IMG3];
					$des_template3 = base64_decode($qrBusca[DES_TEMPLATE3]);

				}else{

					$des_img_1 = "";
					$des_template1 = "";
					$des_img_2 = "";
					$des_template2 = "";
					$des_img_3 = "";
					$des_template3 = "";

				}

				if($des_img1 != ""){

					$extFoto = explode(".", $des_img1);

					$ext = ".".end($extFoto);

					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-media',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_SSL_VERIFYPEER=> false,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS =>'{
					  "extension": "'.$ext.'",
					  "forceSend": true,
					  "number": "55'.$num_celular.'",
					  "verifyContact": true,
					  "linkUrl": "https://img.bunker.mk/media/clientes/274/$des_img1",
					  "fileName": "imagem",
					  "caption": "$des_template1"
					}',
					  CURLOPT_HTTPHEADER => array(
					    'access-token: '.$chave.'',
					    'Content-Type: application/json',
					    'Accept: application/json'
					  ),
					));

					$response = curl_exec($curl);

					// echo "<pre>";
					// print_r($response);
					// echo "</pre>";

					curl_close($curl);

				}else{

					if($des_template1 != ""){

						$exec = curl_init();

						curl_setopt_array($exec, array(
						  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-text',
						  CURLOPT_RETURNTRANSFER => true,
						  CURLOPT_SSL_VERIFYPEER=> false,
						  CURLOPT_ENCODING => '',
						  CURLOPT_MAXREDIRS => 10,
						  CURLOPT_TIMEOUT => 0,
						  CURLOPT_FOLLOWLOCATION => true,
						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						  CURLOPT_CUSTOMREQUEST => 'POST',
						  CURLOPT_POSTFIELDS =>'{
						  "forceSend": true,
						  "message": "'.$des_template1.'",
						  "number": "55'.$num_celular.'",
						  "verifyContact": true
						}',
						CURLOPT_HTTPHEADER => array(
							'access-token: '.$chave.'',
							'Content-Type: application/json',
							'Accept: application/json'
						  ),
						));

						$response = curl_exec($exec);

					}

				}

				sleep(1);

				if($des_img2 != ""){

					$extFoto = explode(".", $des_img2);

					$ext = ".".end($extFoto);

					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-media',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_SSL_VERIFYPEER=> false,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS =>'{
					  "extension": "'.$ext.'",
					  "forceSend": true,
					  "number": "55'.$num_celular.'",
					  "verifyContact": true,
					  "linkUrl": "https://img.bunker.mk/media/clientes/274/'.$des_img2.'",
					  "fileName": "imagem",
					  "caption": "'.$des_template2.'"
					}',
					  CURLOPT_HTTPHEADER => array(
					    'access-token: '.$chave.'',
					    'Content-Type: application/json',
					    'Accept: application/json'
					  ),
					));

					$response = curl_exec($curl);

					curl_close($curl);

				}else{

					if($des_template2 != ""){

						$exec = curl_init();

						curl_setopt_array($exec, array(
						  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-text',
						  CURLOPT_RETURNTRANSFER => true,
						  CURLOPT_SSL_VERIFYPEER=> false,
						  CURLOPT_ENCODING => '',
						  CURLOPT_MAXREDIRS => 10,
						  CURLOPT_TIMEOUT => 0,
						  CURLOPT_FOLLOWLOCATION => true,
						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						  CURLOPT_CUSTOMREQUEST => 'POST',
						  CURLOPT_POSTFIELDS =>'{
						  "forceSend": true,
						  "message": "'.$des_template2.'",
						  "number": "55'.$num_celular.'",
						  "verifyContact": true
						}',
						  CURLOPT_HTTPHEADER => array(
						    'access-token: '.$chave.'',
						    'Content-Type: application/json',
						    'Accept: application/json'
						  ),
						));

						$response = curl_exec($exec);

					}

				}

				sleep(1);

				if($des_img3 != ""){

					$extFoto = explode(".", $des_img2);

					$ext = ".".end($extFoto);

					$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-media',
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_SSL_VERIFYPEER=> false,
					  CURLOPT_ENCODING => '',
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 0,
					  CURLOPT_FOLLOWLOCATION => true,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => 'POST',
					  CURLOPT_POSTFIELDS =>'{
					  "extension": "'.$ext.'",
					  "forceSend": true,
					  "number": "55'.$num_celular.'",
					  "verifyContact": true,
					  "linkUrl": "https://img.bunker.mk/media/clientes/274/'.$des_img3.'",
					  "fileName": "imagem",
					  "caption": "'.$des_template3.'"
					}',
					  CURLOPT_HTTPHEADER => array(
					    'access-token: '.$chave.'',
					    'Content-Type: application/json',
					    'Accept: application/json'
					  ),
					));

					$response = curl_exec($curl);

					curl_close($curl);

				}else{

					if($des_template3 != ""){

						$exec = curl_init();

						curl_setopt_array($exec, array(
						  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-text',
						  CURLOPT_RETURNTRANSFER => true,
						  CURLOPT_SSL_VERIFYPEER=> false,
						  CURLOPT_ENCODING => '',
						  CURLOPT_MAXREDIRS => 10,
						  CURLOPT_TIMEOUT => 0,
						  CURLOPT_FOLLOWLOCATION => true,
						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						  CURLOPT_CUSTOMREQUEST => 'POST',
						  CURLOPT_POSTFIELDS =>'{
						  "forceSend": true,
						  "message": "'.$des_template3.'",
						  "number": "55'.$num_celular.'",
						  "verifyContact": true
						}',
						  CURLOPT_HTTPHEADER => array(
						    'access-token: '.$chave.'',
						    'Content-Type: application/json',
						    'Accept: application/json'
						  ),
						));

						$response = curl_exec($exec);

					}

				}


				// sleep(1);

				

				// $exec2 = curl_init();

				// curl_setopt_array($exec2, array(
				//   CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-text',
				//   CURLOPT_RETURNTRANSFER => true,
				//   CURLOPT_SSL_VERIFYPEER=> false,
				//   CURLOPT_ENCODING => '',
				//   CURLOPT_MAXREDIRS => 10,
				//   CURLOPT_TIMEOUT => 0,
				//   CURLOPT_FOLLOWLOCATION => true,
				//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				//   CURLOPT_CUSTOMREQUEST => 'POST',
				//   CURLOPT_POSTFIELDS =>'{
				//   "forceSend": true,
				//   "message": "Para *agilizar seu atendimento*, nos conte...",
				//   "number": "55'.$num_celular.'",
				//   "verifyContact": true
				// }',
				//   CURLOPT_HTTPHEADER => array(
				//     'access-token: '.$chave.'',
				//     'Content-Type: application/json',
				//     'Accept: application/json'
				//   ),
				// ));

				// $response2 = curl_exec($exec2);

				// sleep(1);

				// https://img.bunker.mk/media/clientes/274/mensagemAvulsa.jpg
				
			break;
			case 'ALT':	

				$sqlCad = "INSERT INTO MENSAGEM_ADORAI(
											COD_EMPRESA,
											DES_IMG1,
											DES_TEMPLATE1,
											DES_IMG2,
											DES_TEMPLATE2,
											DES_IMG3,
											DES_TEMPLATE3,
											COD_USUCADA
										)VALUES(
											$cod_empresa,
											'$des_img1',
											'$des_template1',
											'$des_img2',
											'$des_template2',
											'$des_img3',
											'$des_template3',
											$cod_usucada
										)";

				// fnescreve($sqlCad);

				//fnTestesql(connTemp($cod_empresa),$sqlCad);				
				$arrayProc = mysqli_query(conntemp($cod_empresa,''), $sqlCad);

				if (!$arrayProc) {

					$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCad,$nom_usuario);
				}
				
			break;
			case 'EXC':
				
			break;
			} 

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Mensagem enviada com <strong>sucesso!</strong>";
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
	//fnEscreve('entrou else');
}

$cod_empresa = 274;

$sql = "SELECT * FROM MENSAGEM_ADORAI 
		WHERE COD_EMPRESA = $cod_empresa
		ORDER BY COD_MENSAGEM DESC LIMIT 1";
$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);


if(isset($arrayQuery)){

	$qrBusca = mysqli_fetch_assoc($arrayQuery);

	$des_img1 = $qrBusca[DES_IMG1];
	$des_template1 = base64_decode($qrBusca[DES_TEMPLATE1]);
	$des_img2 = $qrBusca[DES_IMG2];
	$des_template2 = base64_decode($qrBusca[DES_TEMPLATE2]);
	$des_img3 = $qrBusca[DES_IMG3];
	$des_template3 = base64_decode($qrBusca[DES_TEMPLATE3]);

}else{

	$des_img_1 = "";
	$des_template1 = "";
	$des_img_2 = "";
	$des_template2 = "";
	$des_img_3 = "";
	$des_template3 = "";

}

//fnMostraForm();

?>



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
					$abaAdorai = 1833;
					include "abasAdorai.php";

					$abaManutencaoAdorai = fnDecode($_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row">

							<div class="col-md-4">

								<fieldset>
									<legend>Template</legend>
								
									<div class="row">

										<div class="col-md-6">
											<label for="inputName" class="control-label">Mensagem 1:</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG1" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_IMG1" id="DES_IMG1" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img1; ?>">
											</div>
										</div>

										<div class="push10"></div>

										<div class="col-lg-12">
											<div class="form-group">
												
												<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE1" id="DES_TEMPLATE1" maxlength="4000"><?php echo $des_template1; ?></textarea>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

									<div class="push10"></div>	

									<div class="row">

										<div class="col-md-6">
											<label for="inputName" class="control-label">Mensagem 2:</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG2" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_IMG2" id="DES_IMG2" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img2; ?>">
											</div>
										</div>

										<div class="push10"></div>
										
										<div class="col-lg-12">
											<div class="form-group">
												
												<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE2" id="DES_TEMPLATE2" maxlength="4000"><?php echo $des_template2; ?></textarea>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										
									</div>	

									<div class="push10"></div>

									<div class="row">

										<div class="col-md-6">
											<label for="inputName" class="control-label">Mensagem 3:</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG3" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_IMG3" id="DES_IMG3" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img3; ?>">
											</div>
										</div>

										<div class="push10"></div>
										
										<div class="col-lg-12">
											<div class="form-group">
												
												<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE3" id="DES_TEMPLATE3" maxlength="4000"><?php echo $des_template3; ?></textarea>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										
									</div>
										
									<div class="push10"></div>
									<hr>
									<div class="form-group text-right col-lg-12">

										<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar Template</button>

									</div>

								</fieldset>

							</div>


							<div class="col-md-3 col-md-offset-1">

								<fieldset>
									<legend>Preview</legend>

									<div class="row">
										
										<div class="col-md-12">
											
											<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Mensagem</label> -->

												<?php 

													if($des_img1 != ""){

												?>

														<!-- <img src="https://img.bunker.mk/media/clientes/274/<?=$des_img1?>" class="img-responsive"><br> -->

														<a href="javascript:void(0)" class="btn btn-xs btn-default" onclick='visualizarImagem("1")'><span class="fal fa-image"></span> Visualizar Imagem</a>
														<div class="push5"></div>
														<img src="https://img.bunker.mk/media/clientes/274/<?=$des_img1?>" class="img-responsive troca-img-1 off" style="border-radius: 10px; display: none;">
														<br>

												<?php 

													}

													if($des_template1 != ""){

												?>
															<?=$des_template1?>

												<?php 														

													}

													if($des_template1 != "" || $des_img1 != ""){
												?>

												<hr>

												<?php 
													}

													if($des_img2 != ""){

												?>

														<!-- <img src="https://img.bunker.mk/media/clientes/274/<?=$des_img2?>" class="img-responsive"><br> -->
														<a href="javascript:void(0)" class="btn btn-xs btn-default" onclick='visualizarImagem("2")'><span class="fal fa-image"></span> Visualizar Imagem</a>
														<div class="push5"></div>
														<img src="https://img.bunker.mk/media/clientes/274/<?=$des_img2?>" class="img-responsive troca-img-2 off" style="border-radius: 10px; display: none;">
														<br>


												<?php 

													}

													if($des_template2 != ""){

												?>
															<?=$des_template2?>

												<?php 

													}

													if($des_template2 != "" || $des_img2 != ""){
												?>

												<hr>

												<?php 
													}

													if($des_img3 != ""){

												?>

														<!-- <img src="https://img.bunker.mk/media/clientes/274/<?=$des_img3?>" class="img-responsive"><br> -->
														<a href="javascript:void(0)" class="btn btn-xs btn-default" onclick='visualizarImagem("3")'><span class="fal fa-image"></span> Visualizar Imagem</a>
														<div class="push5"></div>
														<img src="https://img.bunker.mk/media/clientes/274/<?=$des_img3?>" class="img-responsive troca-img-3 off" style="border-radius: 10px; display: none;">
														<br>


												<?php 

													}

													if($des_template3 != ""){

												?>
															<?=$des_template3?>

												<?php 

													}

													if($des_template3 != "" || $des_img3 != ""){
												?>

												<hr>

												<?php 
													}

												?>

											</div>

										</div>

									</div>

								</fieldset>

							</div>

							<div class="col-md-3 col-md-offset-1">

								<fieldset>
									<legend>Dados do envio</legend>

									<div class="row">

										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label required">Celular</label>
												<input type="text" class="form-control text-center sp_celphones" placeholder="Celular para envio" name="NUM_CELULAR" id="NUM_CELULAR" value="">
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

									<div class="row">

										<div class="form-group col-md-12">

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

									</div>

									<div class="push10"></div>
									<hr>
									<div class="form-group text-right col-lg-12">
										<button type="submit" name="CAD" id="CAD" class="btn btn-success getBtn"><i class="fal fa-paper-plane" aria-hidden="true"></i>&nbsp; Enviar</button>
									</div>
										
									

								</fieldset>

							</div>

						</div>							

						

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="ID" id="ID" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

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

<div class="push20"></div>

	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
	<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>	
	
<script type="text/javascript">

	$(function(){

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$('.upload').on('click', function(e) {
			var idField = 'arqUpload_' + $(this).attr('idinput');
			var typeFile = $(this).attr('extensao');

			$.dialog({
				title: 'Arquivo',
				content: '' +
					'<form method = "POST" enctype = "multipart/form-data">' +
					'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
					'<div class="progress" style="display: none">' +
					'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
					'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
					'</div>' +
					'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
					'</form>'
			});
		});

	});

	function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
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