<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$cod_geral = "";
$msgRetorno = "";
$msgTipo = "";
$cod_campanha = "";
$qtd_limitprodu = 0;
$des_produto = "";
$cod_externo = "";
$tip_faixas = "";
$val_faixini = "";
$val_faixfim = "";
$cod_produto = "";
$qtd_faixext = 0;
$tip_faixext = "";
$qtd_faixlim = 0;
$cod_usucada = "";
$tip_calculo = "";
$cod_formapa = "";
$filtro = "";
$val_pesquisa = "";
$hHabilitado = "";
$hashForm = "";
$sqlVantagem = "";
$arrayQuery = [];
$tem_extra = "";
$sqlExtra = "";
$sqlCad = "";
$qrBuscaProd = "";
$produtosCad = [];
$duplicados = "";
$inseridos = "";
$array = [];
$item = "";
$qrVant = [];
$sql2 = "";
$qrBuscaTotalExtra = "";
$temfaixa = "";
$sql3 = "";
$produto_item = "";
$qrBusca = "";
$qrBuscaCampanha = "";
$log_ativo = "";
$des_campanha = "";
$abr_campanha = "";
$des_icone = "";
$tip_campanha = "";
$log_realtime = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$abaPersona = "";
$abaVantagem = "";
$abaRegras = "";
$abaComunica = "";
$abaAtivacao = "";
$abaResultado = "";
$abaPersonaComp = "";
$abaCampanhaComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaAtivacaoComp = "";
$abaResultadoComp = "";
$qrBuscaTpCampanha = "";
$nom_tpcampa = "";
$abv_tpcampa = "";
$des_iconecp = "";
$label_1 = "";
$label_2 = "";
$label_3 = "";
$label_4 = "";
$label_5 = "";
$cod_persona = "";
$tem_personas = "";
$pct_vantagem = "";
$qtd_vantagem = 0;
$qtd_resultado = 0;
$nom_vantagem = "";
$num_pessoas = "";
$cod_vantage = "";
$esconde = "";
$andFiltro = "";
$sqlPessoas = "";
$arrayPessoas = [];
$qrBuscaPessoas = "";
$popUp = "";
$abaCampanhas = "";
$abaCli = "";
$selecionados = "";
$andExternoTkt = "";
$andExterno = "";
$retorno = "";
$inicio = "";
$countLinha = "";
$qrBuscaCampanhaExtra = "";
$tipoGanho = "";
$content = "";


// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

$hashLocal = mt_rand();

$cod_geral = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_geral = fnLimpaCampoZero(@$_POST['COD_GERAL']);
		$cod_campanha = fnLimpaCampoZero(@$_POST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$qtd_limitprodu = fnLimpaCampoZero(@$_POST['QTD_LIMITPRODU']);
		$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$tip_faixas = "PRD";
		$val_faixini = 0;
		$val_faixfim = 0;
		$cod_produto = fnLimpacampo(@$_REQUEST['COD_PRODUTO']);
		$qtd_faixext = @$_POST['QTD_FAIXEXT'];
		$tip_faixext = @$_POST['TIP_FAIXEXT'];
		$qtd_faixlim = @$_POST['QTD_FAIXLIM'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$tip_calculo = fnLimpaCampoZero(@$_REQUEST['TIP_CALCULO']);
		$cod_formapa = 0;

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$sql = "";
		$sqlVantagem = "";


		if ($opcao != '') {

			if ($opcao != 'EXC_SEL' && @$_POST['SELECIONADOS'] == "") {
				//busca dados da regra extra (tela) 
				$sql = "SELECT COD_EXTRA FROM VANTAGEMEXTRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
				//fnEscreve($sql);

				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
				$tem_extra = mysqli_num_rows($arrayQuery);

				if ($tem_extra == 0) {

					$sqlExtra = "INSERT INTO VANTAGEMEXTRA(
												COD_CAMPANHA, 
												COD_USUCADA, 
												COD_EMPRESA
											 ) VALUES(
											 	$cod_campanha,
											 	$cod_usucada,
											 	$cod_empresa
											 )";

					mysqli_query(connTemp($cod_empresa, ''), $sqlExtra);
				}

				//Adicionado por Lucas Ref chamado #6518 buscando produtos já cadastrados
				$sqlCad = "SELECT COD_PRODUTO 
				FROM VANTAGEMEXTRAFAIXA
				WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sqlCad));


				while ($qrBuscaProd = mysqli_fetch_assoc($arrayQuery)) {
					$produtosCad[] = $qrBuscaProd['COD_PRODUTO'];
				}

				$duplicados = 0;
				$inseridos = 0;
				if (@$_POST['MULTI_PROD'] != "") {

					$array = json_decode(@$_POST['MULTI_PROD'], true);

					foreach ($array as $item) {

						//Adicionado por Lucas Ref chamado #6518 verifica se o produto já esta registrado na campanha
						if (in_array($item['COD_PRODUTO'], $produtosCad) && $opcao != 'ALT') {
							$duplicados++;
						} else {
							$inseridos++;
							// fnEscreve("loop");
							$sqlVantagem = "CALL SP_ALTERA_VANTAGEMEXTRAFAIXA (
								 '" . $cod_geral . "', 
								 '" . $cod_campanha . "', 
								 '" . $cod_empresa . "',
								 '0', 
								 '" . $tip_faixas . "', 
								 '" . fnValorSql($val_faixini) . "',
								 '" . fnValorSql($val_faixfim) . "',
								 '" . fnValorSql($qtd_faixext) . "',
								 '" . $tip_faixext . "',
								 '" . $qtd_faixlim . "',
								 '" . $item['COD_PRODUTO'] . "',
								 '" . $cod_formapa . "',
								 '" . $cod_usucada . "',
	                             '" . $tip_calculo . "',
								 '" . $qtd_limitprodu . "',
								 '" . $opcao . "'    
							); ";

							$qrVant = mysqli_query(connTemp($cod_empresa, ''), trim($sqlVantagem));
							if ($qrVant instanceof mysqli) {
								while (mysqli_next_result($qrVant));
							}
						}
					}

					$msgRetorno = "$inseridos Registros gravado com <strong>sucesso!</strong> </br> $duplicados Registros já existentes <strong>não foram gravados!</strong>";
					$msgTipo = 'alert-success';
				} else {

					if (in_array($cod_produto, $produtosCad) && $opcao == 'CAD') {
						$msgRetorno = "Produto já <strong>existente</strong> na lista!";
						$msgTipo = 'alert-danger';
					} else {

						$sqlVantagem = "CALL SP_ALTERA_VANTAGEMEXTRAFAIXA (
						 '" . $cod_geral . "', 
						 '" . $cod_campanha . "', 
						 '" . $cod_empresa . "',
						 '0', 
						 '" . $tip_faixas . "', 
						 '" . fnValorSql($val_faixini) . "',
						 '" . fnValorSql($val_faixfim) . "',
						 '" . fnValorSql($qtd_faixext) . "',
						 '" . $tip_faixext . "',
						 '" . $qtd_faixlim . "',
						 '" . $cod_produto . "',
						 '" . $cod_formapa . "',
						 '" . $cod_usucada . "',
	             		 '" . $tip_calculo . "',
						 '" . $qtd_limitprodu . "',
						 '" . $opcao . "'    
						); ";

						// fnEscreve($sqlVantagem);
						mysqli_query(connTemp($cod_empresa, ''), trim($sqlVantagem));

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						$msgTipo = 'alert-success';
					}
				}

				//fnEscreve($sql2); 

				//busca quantidade total de itens	
				$sql2 = "select count(*) as TEMFAIXA from VANTAGEMEXTRAFAIXA where COD_CAMPANHA = '" . $cod_campanha . "' AND TIP_FAIXAS = 'PRD' ";
				//fnEscreve($sql2);

				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
				$qrBuscaTotalExtra = mysqli_fetch_assoc($arrayQuery);
				$temfaixa = $qrBuscaTotalExtra['TEMFAIXA'];

				//if ($temfaixa > 0) {					

				$sql3 = "update VANTAGEMEXTRA set QTD_TOTPRODU = " . $temfaixa . " where cod_campanha = " . $cod_campanha . " ";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql3);

				//atualiza lista iframe				
?>
				<script>
					try {
						parent.$('#REFRESH_PROD').val("S");
					} catch (err) {}
				</script>
<?php

				//}	
			} else if ($opcao == "ALT" && @$_POST['SELECIONADOS'] != "") {

				$array = explode(',', @$_POST['SELECIONADOS']);

				foreach ($array as $item) {

					$produto_item = "";

					$sqlCad = "SELECT COD_PRODUTO 
						FROM VANTAGEMEXTRAFAIXA
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha
						AND COD_VANTAGEMFAIXA = $item";

					$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sqlCad));
					$qrBusca = mysqli_fetch_assoc($arrayQuery);
					$produto_item = $qrBusca['COD_PRODUTO'];

					if ($produto_item != '') {
						$sqlVantagem = "CALL SP_ALTERA_VANTAGEMEXTRAFAIXA (
							 '" . $item . "', 
							 '" . $cod_campanha . "', 
							 '" . $cod_empresa . "',
							 '0', 
							 '" . $tip_faixas . "', 
							 '" . fnValorSql($val_faixini) . "',
							 '" . fnValorSql($val_faixfim) . "',
							 '" . fnValorSql($qtd_faixext) . "',
							 '" . $tip_faixext . "',
							 '" . $qtd_faixlim . "',
							 '" . $produto_item . "',
							 '" . $cod_formapa . "',
							 '" . $cod_usucada . "',
		             		 '" . $tip_calculo . "',
							 '" . $qtd_limitprodu . "',
							 '" . $opcao . "'    
							); ";
						$qrVant = mysqli_query(connTemp($cod_empresa, ''), trim($sqlVantagem));
					}
				}
			} else {

				if (@$_POST['SELECIONADOS'] != "") {

					$array = explode(',', @$_POST['SELECIONADOS']);

					foreach ($array as $item) {

						$sqlVantagem = "CALL SP_ALTERA_VANTAGEMEXTRAFAIXA (
												'" . $item . "', 
												'" . $cod_campanha . "', 
												'" . $cod_empresa . "',
												'0', 
												'0', 
												'0',
												'0',
												'0',
												'0',
												'0',
												'0',
												'0',
												'0',
												'0',
												'0',
												'EXC'    
								); ";
						$qrVant = mysqli_query(connTemp($cod_empresa, ''), trim($sqlVantagem));
						// while (mysqli_next_result($qrVant));
						//fnEscreve($sqlVantagem);
					}

					$msgRetorno = "Registro(s) excluido(s) com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';
				}
			}
		}
	}
}

//busca dados da campanha
$cod_campanha = fnDecode(@$_GET['idc']);
$cod_empresa = fnDecode(@$_GET['id']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
	$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
}


//busca dados da url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "S";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "active ";
		$abaCampanhaComp = "active";
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "";
		$abaAtivacaoComp = "";
		$abaResultadoComp = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
	$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
	$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
	$label_1 = $qrBuscaTpCampanha['LABEL_1'];
	$label_2 = $qrBuscaTpCampanha['LABEL_2'];
	$label_3 = $qrBuscaTpCampanha['LABEL_3'];
	$label_4 = $qrBuscaTpCampanha['LABEL_4'];
	$label_5 = $qrBuscaTpCampanha['LABEL_5'];
}

//busca dados da regra 
$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$cod_persona = $qrBuscaTpCampanha['COD_PERSONA'];
	if (!empty($cod_persona)) {
		$tem_personas = "sim";
	} else {
		$tem_personas = "nao";
	}
	$pct_vantagem = $qrBuscaTpCampanha['PCT_VANTAGEM'];
	$qtd_vantagem = $qrBuscaTpCampanha['QTD_VANTAGEM'];
	$qtd_resultado = $qrBuscaTpCampanha['QTD_RESULTADO'];
	$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
	$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
	$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];
} else {

	$cod_persona = 0;
	$pct_vantagem = "";
	$qtd_vantagem = "";
	$qtd_vantagem = "";
	$nom_vantagem = "";
	$num_pessoas = 0;
	$cod_vantage = 0;
}

if ($val_pesquisa != '') {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

if ($filtro != '') {
	if ($filtro == "DUPLICADOS") {
		$andFiltro = "AND a.cod_produto IN(SELECT a.cod_produto
							FROM VANTAGEMEXTRAFAIXA A
							LEFT JOIN CAMPANHA B ON A.COD_CAMPANHA= B.COD_CAMPANHA
							LEFT JOIN produtocliente P ON A.COD_PRODUTO = P.COD_PRODUTO
							WHERE A.COD_CAMPANHA = '46' 
							AND A.TIP_FAIXAS = 'PRD'
							GROUP BY A.COD_PRODUTO
							HAVING COUNT( A.COD_PRODUTO)>1
							ORDER BY P.DES_PRODUTO)";
	} else {
		$andFiltro = " AND P.$filtro LIKE '%$val_pesquisa%' ";
	}
} else {
	$andFiltro = " ";
}

$sqlPessoas = "SELECT COUNT(*) as PESSOAS FROM PERSONACLASSIFICA WHERE COD_PERSONA = $cod_persona AND COD_EMPRESA = $cod_empresa";

$arrayPessoas = mysqli_query(connTemp($cod_empresa, ''), $sqlPessoas);
if ($qrBuscaPessoas = mysqli_fetch_assoc($arrayPessoas)) {
	$num_pessoas = $qrBuscaPessoas['PESSOAS'];
} else {
	$num_pessoas = 0;
}

//fnMostraForm();

?>

<style>
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.input-xs {
		height: 26px;
		padding: 2px 5px;
		font-size: 12px;
		line-height: 1.5;
		/* If Placeholder of the input is moved up, rem/modify this. */
		border-radius: 3px;
		border: 0;
	}
</style>


<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php
					$abaCampanhas = 1022;
					if ($popUp != "true") {
						include "abasCampanhasConfig.php";
					}
					?>

					<div class="push10"></div>

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>


					<?php
					//menu superior - empresas
					$abaCli = 1063;
					switch (fnDecode(@$_GET['mod'])) {
						case 1187: //produtos específicos				
							include "abasRegrasConfig.php";
							echo "<div class='push30'></div>";
							break;
					}
					?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<?php
							//menu superior - empresas
							$abaCli = 1063;
							if (fnDecode(@$_GET['mod']) == 1187) {
							?>

								<fieldset>
									<legend>Dados Gerais</legend>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Código</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Empresa</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
												<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Campanha</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Tipo do Programa</label>
												<div class="push10"></div>
												<span class="fa <?php echo $des_iconecp; ?>"></span> <b><?php echo $nom_tpcampa; ?> (<?php echo $nom_vantagem; ?>) </b>
											</div>
										</div>


										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Pessoas Atingidas</label>
												<div class="push10"></div>
												<span class="fa fa-users"></span>&nbsp; <?php echo number_format($num_pessoas, 0, ",", "."); ?>
											</div>
										</div>

									</div>

								</fieldset>

								<div class="push20"></div>

							<?php
							}
							?>

							<fieldset>
								<legend>Dados do Produto Específico</legend>

								<div class="row">


									<div class="col-md-4" id="divProduto">
										<label for="inputName" class="control-label required">Produto </label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
											<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
										</div>
									</div>

									<div class="col-md-4" id="ITENSSELECIONADOS" style="display: none;">
										<div class="form-group">
											<label for="inputName" class="control-label required">Qtd. Selecionados</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="QTD_SELECIONADOS" id="QTD_SELECIONADOS" value="">
										</div>
										<input type="hidden" name="SELECIONADOS" id="SELECIONADOS" value="<?= $selecionados ?>" />
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Qtd. Extra</label>
											<input type="text" class="form-control input-sm text-center money" name="QTD_FAIXEXT" id="QTD_FAIXEXT" maxlength="20" value="" required>
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Ganha</label>
											<select data-placeholder="Selecione um tipo de ganho" name="TIP_FAIXEXT" id="TIP_FAIXEXT" class="chosen-select-deselect requiredChk" onchange="escondeCampo(this,'S',0)" required>
												<option value="">...</option>
												<option value="PCT">Percentual sobre produto único</option>
												<option value="PCP">Percentual sobre quantidade de produtos</option>
												<option value="ABS"><?php echo $nom_tpcampa; ?></option>
												<option value="ABP">Créditos sobre quantidade de produtos</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="col-md-2" id="PERCENTUAL" hidden>
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo do Percentual</label>
											<select data-placeholder="Selecione um tipo de percentual" name="TIP_CALCULO" id="TIP_CALCULO" class="chosen-select-deselect requiredChk" required>
												<option value="">...</option>
												<option value="1">Sobre valor geral (do produto)</option>
												<option value="2">Sobre valor líquido (do produto)</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Limite de Uso</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_FAIXLIM" id="QTD_FAIXLIM" maxlength="20" value="" required>
											<span class="help-block">quantidade máxima</span>
										</div>
									</div>

									<div class="col-md-2" id="div_qtd_produto" style="display:none;">
										<div class="form-group">
											<label for="inputName" class="control-label required">Limite qtd. produto</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_LIMITPRODU" id="QTD_LIMITPRODU" maxlength="20" value="0">
											<span class="help-block"></span>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group col-md-6">
								<a href="javascript:void(0)" class="btn btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1508) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Busca Produtos (Múltiplo)"><i class="fas fa-box-open" aria-hidden="true"></i>&nbsp; Múltiplos Produtos</a>


								<a href="javascript:void(0)" class="btn btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1616) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Importar Produtos"><i class="fas fa-file-excel" aria-hidden="true"></i>&nbsp; Importar Produtos</a>

							</div>
							<div class="form-group text-right col-md-6">
								<button type="reset" id="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="COD_GERAL" id="COD_GERAL" value="<?php echo $cod_geral; ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="MULTI_PROD" id="MULTI_PROD" value="" />
							<input type="hidden" name="AND_FILTRO" id="AND_FILTRO" value="<?= $andFiltro ?>" />

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<!-- modal -->
						<div class="modal fade popModalAux" id="popModalAux" tabindex='-1'>
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


						<div class="push50"></div>

						<div id="div_Ordena"></div>

						<div class="push30"></div>

						<div class="row">
							<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

								<div class="col-xs-4 col-xs-offset-4">
									<div class="input-group activeItem">
										<div class="input-group-btn search-panel">
											<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
												<span id="search_concept">Sem filtro</span>&nbsp;
												<span class="far fa-angle-down"></span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li class="divisor"><a href="#">Sem filtro</a></li>
												<!-- <li class="divider"></li> -->
												<li><a href="#DES_PRODUTO">Nome do Produto</a></li>
												<li><a href="#COD_EXTERNO">Código Externo</a></li>
												<li><a href="#DUPLICADOS" onclick="$('#VAL_PESQUISA').val('DUPLICADOS'); $('#formLista2').submit();">Duplicados</a></li>
											</ul>
										</div>
										<input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
										<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
										<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
											<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										</div>
										<div class="input-group-btn">
											<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										</div>
									</div>
								</div>

								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							</form>

						</div>

						<div class="push30"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover table-sortable buscavel">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Cód. Externo</th>
												<th>Campanha</th>
												<th>Produto</th>
												<th class='text-center'><input type="checkbox" id="check_all" name="check_all"></th>
												<th>Ganha</th>
												<th>Limite</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">

											<?php



											//pesquisa no form local
											$andExternoTkt = ' ';

											// fnEscreve($andFiltro);

											//se pesquisa dos produtos do ticket
											if (!empty(@$_GET['idP'])) {
												$andExterno = 'AND A.COD_EXTERNO = "' . @$_GET['idP'] . '"';
											}

											$sql = "select count(*) as CONTADOR from VANTAGEMEXTRAFAIXA A
																LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
																LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
																where A.COD_CAMPANHA = '" . $cod_campanha . "' AND A.TIP_FAIXAS = 'PRD'
																" . $andFiltro . "
																order by P.DES_PRODUTO ";

											//fnEscreve($sql);

											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

											$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											$sql = "SELECT A.*,B.DES_CAMPANHA as NOM_CAMPANHA,P.DES_PRODUTO,P.COD_EXTERNO, 
																IFNULL(P.COD_PRODUTO,0) as COD_PRODUTO from VANTAGEMEXTRAFAIXA A
																LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
																LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
																where A.COD_CAMPANHA = '" . $cod_campanha . "' AND A.TIP_FAIXAS = 'PRD'
																" . $andFiltro . "
																order by P.DES_PRODUTO limit $inicio,$itens_por_pagina";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$countLinha = 1;
											while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {

												$count++;

												if ($qrBuscaCampanhaExtra['TIP_FAIXEXT'] == "ABS") {
													$tipoGanho = $nom_tpcampa;
												} else if ($qrBuscaCampanhaExtra['TIP_FAIXEXT'] == "ABP") {
													$tipoGanho = "Créditos";
												} else {
													$tipoGanho = "%";
												}

												echo "
																<tr>
																  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
																  <td>" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "</td>
																  <td>" . $qrBuscaCampanhaExtra['COD_EXTERNO'] . "</td>
																  <td>" . $qrBuscaCampanhaExtra['NOM_CAMPANHA'] . "</td>
																  <td ><a href='action.do?mod=" . fnEncode(1046) . "&id=" . fnEncode($cod_empresa) . "&idP=" . $qrBuscaCampanhaExtra['COD_EXTERNO'] . "'>" . $qrBuscaCampanhaExtra['DES_PRODUTO'] . "</a></td>
																  <td class='text-center'><input type='checkbox' name='check_data' value=" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "></td>
																  <td>" . number_format($qrBuscaCampanhaExtra['QTD_FAIXEXT'], 2, ",", ".") . " " . $tipoGanho . "</td>															
																  <td>" . $qrBuscaCampanhaExtra['QTD_FAIXLIM'] . "</td>
																</tr>
																<input type='hidden' id='ret_COD_GERAL_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "'>
																<input type='hidden' id='ret_VAL_FAIXINI_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_FAIXINI'], 2, ",", ".") . "'>
																<input type='hidden' id='ret_VAL_FAIXFIM_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_FAIXFIM'], 2, ",", ".") . "'>
																<input type='hidden' id='ret_QTD_FAIXEXT_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['QTD_FAIXEXT'], 2, ",", ".") . "'>
																<input type='hidden' id='ret_TIP_FAIXEXT_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_FAIXEXT'] . "'>
																<input type='hidden' id='ret_QTD_LIMITPRODU_" . $count . "' value='" . $qrBuscaCampanhaExtra['QTD_LIMITPRODU'] . "'>
																<input type='hidden' id='ret_TIP_CALCULO_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_CALCULO'] . "'>
																<input type='hidden' id='ret_QTD_FAIXLIM_" . $count . "' value='" . $qrBuscaCampanhaExtra['QTD_FAIXLIM'] . "'>
																<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_PRODUTO'] . "'>
																<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrBuscaCampanhaExtra['DES_PRODUTO'] . "'>
																";

												$countLinha++;
											}

											?>

										</tbody>

										<tfoot>
											<tr>
												<td colspan="5"></td>
												<td colspan="1" class="text-center">
													<button onClick="exc_selecionados();return false;" name="EXC" class="btn btn-xs btn-danger transparency"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir Selecionados</button>
												</td>
												<td colspan=8></td>
											</tr>

											<tr>
												<th colspan="100">
													<div class="btn-group dropdown left">
														<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-excel" aria-hidden="true"></i>
															&nbsp; Exportar&nbsp;
															<span class="fas fa-caret-down"></span>
														</button>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<li><a class="btn btn-sm exportarCSV" onclick="exportar('exportAll')" data-attr="all" style="text-align: left">&nbsp; Exportar Todos </a></li>
															<li><a class="btn btn-sm exportSimpl" onclick="exportar('exportSele')" data-attr="exportSimpl" style="text-align: left">&nbsp; Exportar Selecionados </a></li>
														</ul>
													</div>
												</th>
											</tr>

											<tr>
												<th class="" colspan="100">
													<center>
														<ul id="paginacao" class="pagination-sm"></ul>
													</center>
												</th>
											</tr>
										</tfoot>

									</table>

								</form>

							</div>

						</div>

						<div class="push"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script>
		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e) {
			var value = $('#INPUT').val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#", "");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
			});

			$('#CLEAR').click(function() {
				$('#INPUT').val('');
				$('#INPUT').focus();
				$('#CLEARDIV').hide();
				if ("<?= $filtro ?>" != "") {
					location.reload();
				} else {
					var value = $('#INPUT').val().toLowerCase().trim();
					if (value) {
						$('#CLEARDIV').show();
					} else {
						$('#CLEARDIV').hide();
					}
					$(".buscavel tr").each(function(index) {
						if (!index) return;
						$(this).find("td").each(function() {
							var id = $(this).text().toLowerCase().trim();
							var sem_registro = (id.indexOf(value) == -1);
							$(this).closest('tr').toggle(!sem_registro);
							return sem_registro;
						});
					});
				}
			});

			// $('#SEARCH').click(function(){
			// 	$('#formulario').submit();
			// });


		});

		function buscaRegistro(el) {
			var filtro = $('#search_concept').text().toLowerCase();

			if (filtro == "sem filtro") {
				var value = $(el).val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel tr").each(function(index) {
					if (!index) return;
					$(this).find("td").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('tr').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		}

		//-----------------------------------------------------------------------------------

		$(document).ready(function() {

			$('#TIP_FAIXEXT').change(function() {
				if ($(this).val() == 'ABP') {
					$("#div_qtd_produto").show();
				} else {
					$("#div_qtd_produto").hide();
				}
			});

			// Função para resetar os campos de seleção e ocultar os elementos
			function resetarSelecoes() {
				$("#ITENSSELECIONADOS").hide();
				$("#ITENSSELECIONADOS").val("");
				$("#SELECIONADOS").val("");
				$("#divProduto").show();
				$('#formulario').validator();
				$("#formulario #hHabilitado").val('N');
				$('#CAD').prop('disabled', true);
				$('#EXC').prop('disabled', false);
			}

			// VERIFICA SE O CHECKBOX FOI MARCADO E DESMARCA O RADIO SELECIONADO
			$(document).on('click', 'input[name="check_data"]', function() {
				var isChecked = $(this).is(':checked');

				if (isChecked) {
					$('input[type="radio"]').prop('checked', false);
					$('#reset').trigger('click');
					$('#CAD').prop('disabled', true);
					$('#EXC').prop('disabled', true);
				} else if ($('input[name="check_data"]:checked').length == 0) {
					resetarSelecoes();
				}

				atualizaSelecionados(); // Apenas no final da verificação
			});

			// VERIFICA O CHECK DO RADIO E DESMARCA TODOS OS CHECKBOX
			$(document).on('click', 'input[type="radio"]', function(event) {

				// Desmarca os checkboxes
				$('input[name="check_data"]').prop('checked', false);
				$('#check_all').prop('checked', false);

				// Reseta seleções e habilita o botão CAD
				$("#ITENSSELECIONADOS").hide();
				$("#ITENSSELECIONADOS").val("");
				$("#SELECIONADOS").val("");
				$("#divProduto").show();
				$('#CAD').prop('disabled', false);
				$('#EXC').prop('disabled', false);
			});

			// CHECK TODOS OS CHECKBOX DA LISTA
			$('#check_all').click(function() {
				var isChecked = $(this).is(':checked');
				$('input[name="check_data"]').prop('checked', isChecked);

				if (isChecked) {
					$('input[type="radio"]').prop('checked', false);
					$('#reset').trigger('click');
					$('#EXC').prop('disabled', true);
				} else if ($('input[name="check_data"]:checked').length == 0) {
					resetarSelecoes();
				}

				atualizaSelecionados();
			});

			// QUANDO O CHECKBOX EM MASSA ESTÁ SELECIONADO
			$('input[name="check_data"]').click(function() {
				if (!$(this).is(':checked')) {
					$('#check_all').prop('checked', false);
				} else if ($('input[name="check_data"]:checked').length == $('input[name="check_data"]').length) {
					$('#check_all').prop('checked', true);
				}

				atualizaSelecionados();
			});


			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
			}

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//modal close
			// $('.modal').on('hidden.bs.modal', function () {

			// 	if ($('#MULTI_PROD').val() != ""){
			// 		// $("#formulario").submit();				
			// 	}
			// });
		});

		function atualizaSelecionados() {
			var ids = "";

			$('input[name="check_data"]:checked').each(function() {
				ids += (ids ? "," : "") + $(this).val();
			});

			if (ids === "") {
				return false;
			}

			$("#SELECIONADOS").val(ids);

			$("#divProduto").hide();
			let qtd = $("[name=check_data]:checked").length;
			$('#QTD_SELECIONADOS').val(qtd);
			$("#ITENSSELECIONADOS").show();
			$("#formulario #hHabilitado").val('S');

			return true;
		}


		function msg() {
			let qtd = $("[name=check_data]:checked").length;
			if (qtd <= 0) {
				alert("Nenhum registro selecionado para exportação!");
				return false; // interrompe a execução
			}

			// Continuação do código para exibir a confirmação
			$.confirm({
				title: 'Atenção!',
				animation: 'opacity',
				closeAnimation: 'opacity',
				content: 'Você tem certeza que deseja continuar?',
				buttons: {
					confirmar: function() {
						return true; // ou continue o fluxo de exportação aqui
					},
					cancelar: function() {
						return false; // interrompe a exportação
					},
				}
			});
		}



		function exportar(tipoExport) {
			if (tipoExport === 'exportSele') {
				// Atualiza os selecionados e verifica se há algo para exportar
				if (!atualizaSelecionados()) {
					msg();
					return; // Para a execução caso não haja registros
				}
			}

			$.confirm({
				title: 'Exportação',
				content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +
					'</div>' +
					'</form>',
				buttons: {
					formSubmit: {
						text: 'Gerar',
						btnClass: 'btn-blue',
						action: function() {
							var nome = this.$content.find('.nome').val();
							if (!nome) {
								$.alert('Por favor, insira um nome');
								return false;
							}

							$.confirm({
								title: 'Mensagem',
								type: 'green',
								icon: 'fa fa-check-square',
								content: function() {
									var self = this;
									return $.ajax({
										url: "ajxConfigFidProdExtra.do?opcao=" + tipoExport + "&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
									}).fail(function(response) {
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									});
								},
								buttons: {
									fechar: function() {
										// fechar
									}
								}
							});
						}
					},
					cancelar: function() {
						// fechar
					}
				}
			});
		}



		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxConfigFidProdExtra.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&cod_campanha=<?php echo fnEncode($cod_campanha); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
				},
				error: function() {
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		}

		function escondeCampo(el, vldt, val) {

			let tipo = $(el).val(),
				percentual = $("#PERCENTUAL"),
				campo = $("#TIP_CALCULO"),
				req = false;

			percentual.fadeOut('fast');

			if (tipo == "PCT" || tipo == "PCP") {
				req = true;
				percentual.fadeIn('fast');
			}

			campo.prop('required', req);

			if (vldt == 'S') {
				$('#formulario').validator('validate');
			}

			if (val != 0) {
				campo.val(val).trigger("chosen:updated");
			}

		}

		function exc_selecionados() {
			let qtd = $("[name=check_data]:checked").length;
			if (qtd <= 0) {
				alert("Nenhum registro selecionado para exclusão!");
				return;
			}
			let msg = `Deseja realmente excluir esses ${qtd} registros selecionados?`;
			if (qtd <= 1) {
				msg = `Deseja realmente excluir o registro selecionado?`;
			}
			$.confirm({
				title: 'Atenção!',
				animation: 'opacity',
				closeAnimation: 'opacity',
				content: msg,
				buttons: {
					confirmar: function() {
						let ids = 0;
						atualizaSelecionados();

						$("#formulario #opcao").val("EXC_SEL");
						$("#CAD, #ALT, #EXC").prop('disabled', true);
						$("#formulario")[0].submit();
						$("#hHabilitado").val('N');
					},
					cancelar: function() {

					},
				}
			});

		}

		function retornaForm(index) {

			$("#formulario #COD_GERAL").val($("#ret_COD_GERAL_" + index).val());
			$("#formulario #VAL_FAIXINI").val($("#ret_VAL_FAIXINI_" + index).val());
			$("#formulario #VAL_FAIXFIM").val($("#ret_VAL_FAIXFIM_" + index).val());
			$("#formulario #TIP_FAIXEXT").val($("#ret_TIP_FAIXEXT_" + index).val()).trigger("chosen:updated");
			$("#formulario #QTD_FAIXEXT").val($("#ret_QTD_FAIXEXT_" + index).val());
			$("#formulario #QTD_FAIXLIM").val($("#ret_QTD_FAIXLIM_" + index).val());
			$("#formulario #TIP_CALCULO").val($("#ret_TIP_CALCULO_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
			escondeCampo("#TIP_FAIXEXT", "N", $("#ret_TIP_CALCULO_" + index).val());
			$("#formulario #QTD_LIMITPRODU").val($("#ret_QTD_LIMITPRODU_" + index).val());
			if ($("#ret_TIP_FAIXEXT_" + index).val() == 'ABP') {
				$("#div_qtd_produto").show();
			} else {
				$("#div_qtd_produto").hide();
			}

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>