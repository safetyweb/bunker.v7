<?php

	//echo fnDebug('true');

$hashLocal = mt_rand();	
$adm = $connAdm->connAdm();

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

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

		$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
		$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
		$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
		$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
		$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
		$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo($_POST['INPUT']);	

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];			

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
	$cod_empresa = fnDecode($_GET['id']);	
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
            //fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)){
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
	}

}else {
	$cod_empresa = 0;
           // $codEmpresa = $qrBuscaEmpresa['COD_SISTEMA'];

}

	//Busca módulos autorizados
$sql = "SELECT COD_PERFILS FROM usuarios WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrPfl = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

$sqlAut = "SELECT COD_MODULOS FROM perfil WHERE
COD_SISTEMA = 4 
AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
$qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlAut));

$modsAutorizados = explode(",", $qrAut['COD_MODULOS']);

	//liberação das abas
$abaPersona	= "S";
$abaCampanha = "S";
$abaVantagem = "N";
$abaRegras = "N";
$abaComunica = "N";
$abaAtivacao = "N";
$abaResultado = "N";

$abaPersonaComp = "";
$abaCampanhaComp = "active";
$abaVantagemComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaResultadoComp = "";
	//revalidada na aba de regras	
$abaAtivacaoComp = "";

	// esquema do X da barra - (recarregar pesquisa)
if($val_pesquisa != ""){
	$esconde = " ";
}else{
	$esconde = "display: none;";
}
	//fnMostraForm();
	//fnEscreve("QunXraEOVrg¢");

$sqlCamp = "SELECT DES_CAMPANHA, COD_CAMPANHA, LOG_ATIVO, LOG_PROCESSA_WHATSAPP, LOG_PROCESSA_SMS 
FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND TIP_CAMPANHA = 21";
$query = mysqli_query(connTemp($cod_empresa , ''),$sqlCamp);

$arrayCampanhas = array();
while($qrResult = mysqli_fetch_assoc($query)){
	array_push($arrayCampanhas, $qrResult);
}

?>

<style>
	.fa-1dot5x{
		font-size: 24px;
		margin-top: 3px;
		margin-bottom: 3px;
	}
	.tile{
		border: none!important;
	}


	.icon-headernav {
		color: #ff6200;
		font-size: 16px;
	}

	.button-header {
		cursor: pointer;
	}


	.notification-dot {
		height: 11px;
		width: 11px;
		background-color: #18BC9C;
		border-radius: 50%;
		border: 1px solid white;
		display: inline-block;
		position: relative;
		top: -28px;
		left: 11px;
	}

	.panelBox {
		width: 40px;
		height: 40px;
		display: flex;
		align-content: center;
		align-items: center;
	}

</style>

<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div> 

<div class="portlet portlet-bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="far fa-terminal"></i>
			<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
		</div>

		<?php 
		$formBack = "1048";
		include "atalhosPortlet.php"; ?>

	</div>								

	<div class="push10"></div> 

	<?php

	$sql = "SELECT case 
	when   SUM(PM.QTD_SALDO_ATUAL) <=   SUM(PM.QTD_PRODUTO)
	then 
	SUM(PM.QTD_SALDO_ATUAL) 
	ELSE 
	SUM(PM.QTD_PRODUTO) - SUM(PM.QTD_SALDO_ATUAL) end QTD_PRODUTO ,
	PM.TIP_LANCAMENTO,
	CC.DES_CANALCOM 
	FROM PEDIDO_MARKA PM
	INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
	INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM 
	WHERE PM.COD_ORCAMENTO > 0 
	AND PM.PAG_CONFIRMACAO='S'
	AND  PM.TIP_LANCAMENTO='C'
	AND PM.COD_EMPRESA = $cod_empresa
	AND  PM.QTD_SALDO_ATUAL > 0
	GROUP BY CC.COD_TPCOM";

            //fnEscreve($sql);

	$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

	while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

              // fnEscreve($qrLista[QTD_PRODUTO]);

		$count++;

		switch($qrLista['DES_CANALCOM']){

			case 'SMS':
			if($qrLista['TIP_LANCAMENTO'] == 'D'){
				$qtd_sms = $qtd_sms - $qrLista[QTD_PRODUTO];
			}else{
				$qtd_sms = $qtd_sms + $qrLista[QTD_PRODUTO];
			}
			break;

			case 'WhatsApp':
			if($qrLista['TIP_LANCAMENTO'] == 'D'){
				$qtd_wpp = $qtd_wpp - $qrLista[QTD_PRODUTO];
			}else{
				$qtd_wpp = $qtd_wpp + $qrLista[QTD_PRODUTO];
			}
			break;

			default:
			if($qrLista['TIP_LANCAMENTO'] == 'D'){
				$qtd_email = $qtd_email - $qrLista[QTD_PRODUTO];
			}else{
				$qtd_email = $qtd_email + $qrLista[QTD_PRODUTO];
			}
			break;

		}

	}

	$sql = "SELECT SENHAS_WHATSAPP.*,
                            EMP.NOM_FANTASI,
                            UNV.NOM_FANTASI AS NOM_UNIVEND
                            from SENHAS_WHATSAPP
                            INNER JOIN EMPRESAS EMP ON EMP.COD_EMPRESA = SENHAS_WHATSAPP.COD_EMPRESA
                            LEFT JOIN UNIDADEVENDA UNV ON UNV.COD_UNIVEND = SENHAS_WHATSAPP.COD_UNIVEND
                            WHERE SENHAS_WHATSAPP.COD_EMPRESA = $cod_empresa";

                                
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $resultBusca = mysqli_num_rows($arrayQuery);

	?>

	<div class="row">

		<div class="col-md-3">

			<div class="widget widget-default widget-item-icon">
				<a href="javascript:void(0)" class="addBox" style="all: unset; cursor: pointer;" data-url="action.do?mod=<?php echo fnEncode(2044) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastro de WhatsApp - <?= $nom_empresa ?>">
					<div class="widget-item-left">
						<span class="fab fa-whatsapp fa-3x text-success"></span>
					</div>                             
					<div class="widget-data">
						<div class="widget-int num-count"><?= $resultBusca ?></div>
						<div class="widget-title">Números Conectados</div>
						<div class="widget-subtitle">WhatsApp</div>
					</div> 
				</a>     
			</div>                            

		</div>

		<div class="col-md-3">
			<a href="javascript:void(0)" class="addBox" style="all: unset; cursor: pointer;" data-url="action.php?mod=<?php echo fnEncode(2061)?>&id=<?php echo fnEncode($cod_empresa)?>&tp=20&pop=true" data-title="Adicionar Créditos - <?=$nom_empresa?>">
				<div class="widget widget-default widget-item-icon">
					<div class="widget-item-left">
						<span class="fab fa-whatsapp fa-3x"></span>
					</div>                             
					<div class="widget-data">
						<div class="widget-int num-count"><?= $qtd_wpp ?></div>
						<div class="widget-title">WhatsApp</div>
						<div class="widget-subtitle">Saldo</div>
					</div>      
				</div>                            
			</a>
		</div>

		<div class="col-md-3">
			<a href="javascript:void(0)" class="addBox" style="all: unset; cursor: pointer;" data-url="action.php?mod=<?php echo fnEncode(2061)?>&id=<?php echo fnEncode($cod_empresa)?>&tp=21&pop=true" data-title="Adicionar Créditos - <?=$nom_empresa?>">
				<div class="widget widget-default widget-item-icon">
					<div class="widget-item-left">
						<span class="fal fa-comment-alt"></span>
					</div>                             
					<div class="widget-data">
						<div class="widget-int num-count"><?= $qtd_sms ?></div>
						<div class="widget-title">Sms</div>
						<div class="widget-subtitle">Saldo</div>
					</div>      
				</div> 
			</a>                               

		</div>

		<div class="col-md-3">
			<a href="javascript:void(0)" class="addBox" style="all: unset; cursor: pointer;" data-url="action.php?mod=<?php echo fnEncode(2061)?>&id=<?php echo fnEncode($cod_empresa)?>&tp=13&pop=true" data-title="Adicionar Créditos - <?=$nom_empresa?>">
				<div class="widget widget-default widget-item-icon">
					<div class="widget-item-left">
						<span class="fal fa-envelope"></span>
					</div>                             
					<div class="widget-data">
						<div class="widget-int num-count"><?= $qtd_email ?></div>
						<div class="widget-title">Email</div>
						<div class="widget-subtitle">Saldo</div>
					</div>      
				</div>                            
			</a>
		</div>

<!-- 		<div class="col-md-2">

			<div class="widget widget-default widget-item-icon">
				<div class="widget-item-left">
					<span class="fal fa-calendar-edit"></span>
				</div>                             
				<div class="widget-data">
					<div class="widget-int num-count"><?= $qtd_atend_iniciado; ?></div>
					<div class="widget-title">INICIADOS</div>
					<div class="widget-subtitle">Novos em Andamento</div>
				</div>      
			</div>                            

		</div>

		<div class="col-md-2">

			<div class="widget widget-default widget-item-icon">
				<div class="widget-item-left">
					<span class="fal fa-user-clock"></span>
				</div>                             
				<div class="widget-data">
					<div class="widget-int num-count"><?= $qtd_atend_pendente; ?></div>
					<div class="widget-title">PENDENTES</div>
					<div class="widget-subtitle">Em Atendimento</div>
				</div>      
			</div>                            

		</div> -->

	</div>
</div>


<div class="push20"></div> 

<!-- Portlet -->
<div class="portlet portlet-bordered">

	<div class="portlet-body">

		<?php if ($msgRetorno <> '') { ?>	
			<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php echo $msgRetorno; ?>
			</div>
		<?php } ?>



		<div class="row">

			<?php
			$sqlTpComuni = "select * from cat_comunicacao order by num_ordenac";
			$arrayQuery = mysqli_query($adm, $sqlTpComuni);

			while($qrTpComuni = mysqli_fetch_assoc($arrayQuery)){
				?>

				<h3 style="margin: 0 0 20px 15px;"><?=$qrTpComuni['des_comunicacao'];?></h3>							

				<div class="push20"></div>

				<div class="col-md-12">	


					<table class="table table-bordered table-hover tablesorter buscavel">

						<tbody>
							<?php 
							$sqlTpEvento = "select * from tip_evento where cod_comunicacao = ".$qrTpComuni['cod_comunicacao'];
							$array = mysqli_query($adm, $sqlTpEvento);

							$evento = '';
							$campanhaAtiva = "N";
							while($qrTpEvento = mysqli_fetch_assoc($array)){

								if(array_search($qrTpEvento['des_evento'], array_column($arrayCampanhas, 'DES_CAMPANHA'))){
									
									foreach ($arrayCampanhas as $value) {

										//verifica se existe template cadastrada e a campanha esta ativa, se for verdadeiro exibe icone sucesso
										if(($value['LOG_PROCESSA_WHATSAPP'] == 'S' || $value['LOG_PROCESSA_SMS'] == 'S') && $value['LOG_ATIVO'] == 'S'){
											$config = '<i class="fas fa-circle fa-1dot5x text-success"></i>';
										}else{
											$config = '<span class="far fa-circle fa-1dot5x text-danger"></span>';
										}

										//verifica se a descrição da campanha é a mesma do evento
										if($value['DES_CAMPANHA'] == $qrTpEvento['des_evento']){

											//verifica se a campanha esta ativa, se estiver deixa o checkbox marcado e inclui a função desabilicampanha para ativar ou inativar a campanha
											if($value['LOG_ATIVO'] == 'S'){
												$campanhaAtiva = "S";
												$evento = 'checked';
												$cod_campanha = $value['COD_CAMPANHA'];
												$temCampanha = "";
												$desabilitaCamp = "onChange=\"desabiliCampanha(event, $cod_campanha, $cod_empresa)\"";
											}else{
												$campanhaAtiva = "N";
												$temCampanha = "pointer-events: none; opacity: 0.5;";
												$evento = '';
												$cod_campanha = $value['COD_CAMPANHA'];
												$desabilitaCamp = "onChange=\"desabiliCampanha(event, $cod_campanha, $cod_empresa)\"";
											}
											break;
										}else{
											$cod_campanha = "";
											$temCampanha = "pointer-events: none; opacity: 0.5;";
											$desabilitaCamp = "";
										}

									}
								}else{
									$cod_campanha = "";
									$evento = "onChange=\"addCampanha(event, '".$qrTpEvento['des_evento']."', $cod_empresa)\"";
									$temCampanha = "pointer-events: none; opacity: 0.5;";
								}

								if($cod_campanha != ""){

									//busca template cadastrada whatsapp
									$sqlWhats = "SELECT * FROM MENSAGEM_WHATSAPP WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
									$queryWhats = mysqli_query(connTemp($cod_empresa,''), $sqlWhats);


									if($qrBusca = mysqli_fetch_assoc($queryWhats)){
										// se tiver template cadastrada e a campanha estiver ativa, exibe icone de sucesso
										if(!is_null($qrBusca['COD_TEMPLATE_WHATSAPP']) && $campanhaAtiva != "N"){
											$whatsSucess = "";
											$iniComWhats = "";
											$config = '<i class="fas fa-circle fa-1dot5x text-success"></i>';
										}else{
											$whatsSucess = "hidden";
											$iniComWhats = "onClick=\"addComunicacao($cod_campanha, $cod_empresa, 'WHATSAPP')\"";
											$config = '<span class="far fa-circle fa-1dot5x text-danger"></span>';
										}
									}else{
										$whatsSucess = "hidden";
										$iniComWhats = "onClick=\"addComunicacao($cod_campanha, $cod_empresa, 'WHATSAPP')\"";
									}


									//busca Template Cadastrada Sms
									$sqlSms = "SELECT * FROM MENSAGEM_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

									$querySms = mysqli_query(connTemp($cod_empresa,''), $sqlSms);
									if($qrBuscaSms = mysqli_fetch_assoc($querySms)){
										if(!is_null($qrBuscaSms['COD_TEMPLATE_SMS']) && $campanhaAtiva != "N"){
											$smsSucess = "";
											$iniComSms = "";
											$config = '<i class="fas fa-circle fa-1dot5x text-success"></i>';
										}else{
											$smsSucess = "hidden";
											$iniComSms = "onClick=\"addComunicacao($cod_campanha, $cod_empresa, 'SMS')\"";
											$config = '<span class="far fa-circle fa-1dot5x text-danger"></span>';
										}
									}else{
										$smsSucess = "hidden";
										$iniComSms = "onClick=\"addComunicacao($cod_campanha, $cod_empresa, 'SMS')\"";
									}

									//busca Template Cadastrada Email
									$sqlEmail = "SELECT * FROM MENSAGEM_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

									$queryEmail = mysqli_query(connTemp($cod_empresa,''), $sqlEmail);
									if($qrBuscaEmail = mysqli_fetch_assoc($queryEmail)){
										$emailSucess = "";
										$iniComEmail = "";
									}else{
										$iniComEmail = "onClick=\"addComunicacao($cod_campanha, $cod_empresa, 'EMAIL')\"";
										$emailSucess = "hidden";
									}

								} else {
									$emailSucess = "hidden";
									$whatsSucess = "hidden";
									$smsSucess = "hidden";
									$config = '<span class="far fa-circle fa-1dot5x text-danger"></span>';
									$temCampanha = "pointer-events: none; opacity: 0.5;";
									$iniComWhats = "";
									$iniComSms = "";
								}

								?>
								<tr>
									<td width="5%">
										<div class="form-group">
											<label class="switch">
												<input type="checkbox" name="LOG_CADASTRO" id="LOG_CADASTRO" <?= $desabilitaCamp ?> <?=$evento;?> class="switch" value="S">
												<span></span>
											</label>
										</div>
									</td>
									<td width="20%">
										<?=$qrTpEvento['des_evento'];?>
									</td>

									<td width="15%" class="text-center">
										<div class="button-header">
											<?=$config;?>
										</div>
									</td>
									<td width="15%" class="text-center">
										<div class="button-header">
											<span class="fa-1dot5x"></span>
										</div>
									</td>

									<td width="15%" class="text-center">
										<div class="button-header">
											<a href="javascript:void(0)" class="addBox text-primary" <?= $iniComEmail ?> style="<?= $temCampanha ?>" data-title="Templates Email" data-url="action.php?mod=<?=fnEncode(2058)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true">
												<span class="fal fa-envelope fa-1dot5x"></span>
											</a>
										</div>
										<div class='notification-dot <?=$emailSucess?>'></div>
									</td>
									<td width="15%" class="text-center">
										<div class="button-header">
											<a href="javascript:void(0)" class="addBox text-primary" <?= $iniComSms ?> style="<?= $temCampanha ?>" data-title="Templates SMS" data-url="action.php?mod=<?=fnEncode(2049)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true">
												<span class="fal fa-comment-alt fa-1dot5x"></span>
											</a>
										</div>
										<div class='notification-dot <?=$smsSucess?>'></div>
									</td>
									<td width="15%" class="text-center">
										<div class="button-header">
											<a href="javascript:void(0)" class="addBox text-primary" <?= $iniComWhats ?> style="<?= $temCampanha ?>" data-title="Templates WhatsApp" data-url="action.php?mod=<?=fnEncode(2055)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true">
												<span class="fab fa-whatsapp fa-1dot5x"></span>
											</a>
										</div>
										<div class='notification-dot <?=$whatsSucess?>' ></div>
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>

					</table>

				</div>

				<?php
			}
			?>									

			<div class="push10"></div>

		</div>

	</div><!-- fim Portlet body -->

</div><!-- fim Portlet  -->

<div class="push20"></div>

<!-- Portlet -->
<div class="portlet portlet-bordered">							

	<div class="push10"></div> 

	<div class="portlet-body">

		<div class="row">

			<h3 style="margin: 0 0 20px 15px;">Comunicação em Massa</h3>

			<div class="push10"></div>
			<!-- <div class="col-md-12">
				<a href="javascript:void(0)" class="btn btn-primary btn-sm"><span class="fal fa-plus"></span></a>
			</div> -->
			<div class="col-md-2">

				<div class="panelBox borda">

					<div class="addBox" data-url="action.php?mod=<?php echo fnEncode(2059)?>&id=<?php echo fnEncode($cod_empresa)?>&tipo=<?php echo fnEncode('CAD')?>&pop=true" data-title="Template">
						<i class="fal fa-plus fa-2x" aria-hidden="true" style="align-self: center;"></i>
					</div>											
				</div> 
				
			</div> 		
			<div class="push10"></div>							

			<div class="push20"></div>

			<div class="col-md-12">									

				<table class="table table-bordered table-hover tablesorter buscavel">

					<?php

					$sql = "SELECT * FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND ABR_CAMPANHA = 'MASS'";
					$query = mysqli_query(connTemp($cod_empresa,''), $sql);

					?>

					<tbody>
						<?php 
						$check = '';

						while($qrResult = mysqli_fetch_assoc($query)){

							if($qrResult['LOG_ATIVO'] == 'S'){
								$check = 'checked';
							}else{
								$check = '';
							}

							//BUSCA MENSAGEM WHATSAPP
							$sql = "SELECT * FROM MENSAGEM_WHATSAPP WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $qrResult[COD_CAMPANHA]";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
							$result1 = mysqli_fetch_assoc($query);

							if($result1){
								$whatsSucess1 = "";
							}else{
								$whatsSucess1 = "hidden";
							}

							//BUSCA WHATSAPP_PARAMETROS PARA EXIBIR OU NÃO O NOTIFICATION DOT

							$sql = "SELECT * FROM WHATSAPP_PARAMETROS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $qrResult[COD_CAMPANHA] LIMIT 1";
							
							$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
							$result2 = mysqli_fetch_assoc($query);

							if($result2){
								$parametrosSucess = "";
							}else{
								$parametrosSucess = "hidden";
							}

							if($whatsSucess1 == "" && $parametrosSucess == ""){
								$configSucess = "fa-play";
							}else{
								$configSucess = "fa-users";
							}

							?>
							<tr>
								<td width="5%">
									<div class="form-group">
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" <?= $check ?> class="switch" value="S">
											<span></span>
										</label>
									</div>
								</td>
								<td width="20%"><?= $qrResult['DES_CAMPANHA'] ?></td>
								<td width="15%" class="text-center">
									<div class="button-header">
										<a href="javascript:void(0)" onclick="iniComunicacaoMassa('<?=$qrResult['DES_CAMPANHA']?>', <?=$qrResult['COD_CAMPANHA']?>, <?=$cod_empresa?>)" class="text-primary">
											<span class="fal <?= $configSucess ?> fa-1dot5x"></span>
										</a>
									</div>
									<!-- <div class='notification-dot'></div> -->
								</td>
								<td width="15%" class="text-center">
									<div class="button-header">
										<a href="javascript:void(0)" class="addBox text-primary" data-title="Templates Email" data-url="action.php?mod=<?=fnEncode(2060)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($qrResult['COD_CAMPANHA'])?>&pop=true">
											<span class="fal fa-users fa-1dot5x"></span>
										</a>
									</div>
									<div class='notification-dot <?=$parametrosSucess?>'></div>
								</td>
								<td width="15%" class="text-center">
									<div class="button-header">
										<a href="javascript:void(0)" class="addBox text-primary" style="pointer-events: none; opacity: 0.5;" data-title="Templates Email" data-url="action.php?mod=mymlIlYEs5s¢&id=QunXraEOVrg¢&idc=5p£flh5WUzOg¢&pop=true">
											<span class="fal fa-envelope fa-1dot5x"></span>
										</a>
									</div>
									<!-- <div class='notification-dot'></div> -->
								</td>
								<td width="15%" class="text-center">
									<div class="button-header">
										<a href="javascript:void(0)" class="addBox text-primary" style="pointer-events: none; opacity: 0.5;" data-title="Templates SMS" data-url="action.php?mod=TRp£jGKp£A1OM¢&id=QunXraEOVrg¢&idc=5p£flh5WUzOg¢&pop=true">
											<span class="fal fa-comment-alt fa-1dot5x"></span>
										</a>
									</div>
									<!-- <div class='notification-dot'></div> -->
								</td>
								<td width="15%" class="text-center">
									<div class="button-header">
										<a href="javascript:void(0)" class="addBox text-primary" data-title="Templates WhatsApp" data-url="action.php?mod=<?=fnEncode(2055)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($qrResult['COD_CAMPANHA'])?>&pop=true">
											<span class="fab fa-whatsapp fa-1dot5x"></span>
										</a>
									</div>
									<div class='notification-dot <?=$whatsSucess1?>'></div>
								</td>
							</tr>
							<?php 
						}
						?>
						
					</tbody>

				</table>

			</div>

			<input type='hidden' class="" name="ATUALIZA_TELA" id="ATUALIZA_TELA" value="N"/>									

			<div class="push10"></div>

		</div>

	</div><!-- fim Portlet body -->

</div><!-- fim Portlet  -->						

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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

	$('#popModal').on('hidden.bs.modal', function () {
		var atualiza = $('#ATUALIZA_TELA').val();

		if(atualiza == 'S'){
			location.reload();
		}
	});

	//função pra ativar ou desativar campanha
	function desabiliCampanha(event, cod_campanha, cod_empresa){
		if(event.target.checked){
			habilita = "S";
		}else{
			habilita = "N";
		}

		var codCampanha = cod_campanha;
		var codEmpresa = cod_empresa

		var formData = new FormData();
		formData.append('id', codEmpresa);
		formData.append('camp', codCampanha);
		formData.append('usu', <?=$cod_usucada?>);
		formData.append('hab', habilita);

		$.ajax({
			url: 'ajxComunicacaoSimples.php?opcao=DSBC',
			type: 'POST',
			data: formData,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			success: function(data) {
				console.log(data);
				if (data == "") {
					window.location.reload();
				} else {
					$.alert({
						title: "Erro ao iniciar Campanha, se persistir entre em contato com o suporte.",
						content: data,
						type: 'red'
					});
				}
			}
		});
	}

	//função para criar campanha e persona
	function addCampanha(event, des_campanha, cod_empresa){
		if(event.target.checked){
			var desCampanha = des_campanha;
			var codEmpresa = cod_empresa

			var formData = new FormData();
			formData.append('id', codEmpresa);
			formData.append('dcp', desCampanha);
			formData.append('usu', <?=$cod_usucada?>);
			formData.append('dcp', tpc);

			$.ajax({
				url: 'ajxComunicacaoSimples.php?opcao=CAD',
				type: 'POST',
				data: formData,
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function(data) {
					console.log(data);
					if (data == "") {
						window.location.reload();
					} else {
						$.alert({
							title: "Erro ao iniciar Campanha, se persistir entre em contato com o suporte.",
							content: data,
							type: 'red'
						});
					}
				}
			});
		}
	}

	//função para criar e habilitar comunicação
	function addComunicacao(cod_campanha, cod_empresa, tipCom){
		var codCampanha = cod_campanha;
		var codEmpresa = cod_empresa;
		var tipCom = tipCom;

		var formData = new FormData();
		formData.append('id', codEmpresa);
		formData.append('camp', codCampanha);
		formData.append('usu', <?=$cod_usucada?>);
		formData.append('tpc', tipCom);

		$.ajax({
			url: 'ajxComunicacaoSimples.php?opcao=COM',
			type: 'POST',
			data: formData,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			success: function(data) {
				console.log(data);
				if (data == "") {
				} else {
					$.alert({
						title: "Erro ao iniciar Campanha, se persistir entre em contato com o suporte.",
						content: data,
						type: 'red'
					});
				}
			}
		});
	}

	function iniComunicacaoMassa(des_campanha, cod_campanha, cod_empresa) {

		var formData = new FormData();
		formData.append('id', cod_empresa);
		formData.append('camp', cod_campanha);
		formData.append('usu', <?=$cod_usucada?>);		 

		$.confirm({
			title: 'Gerar Comunicação',
			content: '' +
			'<form action="" class="formName">' +
			'<div class="form-group">' +
			'<span>Você esta prestes a iniciar um disparo <b> '+ des_campanha +' </b> para uma lista de .</span>' +				
			'<span>Deseja Iniciar a comunicação?</span>' +				
			'</div>' +
			'</form>',
			buttons: {
				formSubmit: {
					text: 'Gerar',
					btnClass: 'btn-blue',
					action: function () {
						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fal fa-check-square-o',
							content: function(){
								var self = this;
								return $.ajax({
									url: "ajxComunicacaoSimples.php?opcao=INIMASSA", 
									data: formData,
									method: 'POST',
									processData: false,  // Impede jQuery de processar o data
									contentType: false
								}).done(function (response) {
									console.log(response);
									self.setContentAppend('<div>Comunicação Realizada com Sucesso.</div>');
								}).fail(function(){
									self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
								});
							},							
							buttons: {
								fechar: function () {
											//close
								}									
							}
						});								
					}
				},
				cancelar: function () {
							//close
				},
			}
		});				
	};

	function abreModalPai(link,title,tam){

		if(tam == "lg"){

			try {
				parent.$('#popModal').find('.modal-content').css({
					'width':'100vw',
					'height':'99.5vh',
					'marginLeft':'auto',
					'marginRight':'auto'

				});
				parent.$('#popModal').find('.modal-dialog').css({
					'margin':'0'
				});
			}catch(err) {}

		}else{

			try {
				parent.$('#popModal').find('.modal-content').css({
					'width':'auto',
					'height':'850px',
					'marginLeft':'auto',
					'marginRight':'auto'
				});
				parent.$('#popModal').find('.modal-dialog').css({
					'margin':'30px auto'
				});
			}catch(err) {}

		}

		try {

			parent.$('#popModal .modal-title').text(title);
			parent.$('#popModal iframe').attr("src",link+"&rnd="+Math.random());
			parent.$('#popModal').modal('show').appendTo('body');

		} catch(err) {}

	}
	
</script>