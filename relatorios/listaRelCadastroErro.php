<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$pagina = "";
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = "";
$hoje = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$filtro = "";
$val_pesquisa = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_empresaCode = "";
$cod_cliente = "";
$nom_cliente = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$lojasSelecionadas = "";
$qrBuscaSexo = "";
$totalSex = "";
$sqlunidade = "";
$rwunidades = "";
$rsunidades = "";
$qrBuscaData = "";
$totalData = "";
$qrBuscaDataVazia = "";
$totalDataVazia = "";
$esconde = "";
$sqlUni = "";
$qrUni = "";
$andFiltro = "";
$andUnidade = "";
$sqlidade = "";
$rsidade = "";
$idadepes = "";
$cod_persona = "";
$arrayParamAutorizacao = "";
$autoriza = "";
$retorno = "";
$orUnidade = "";
$sql2 = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$inicio = "";
$qrListaPersonas = "";
$loja = "";
$NOM_ARRAY_UNIDADE = "";
$mostraSexo = "";
$colCliente = "";
$colCartao = "";
$content = "";
$DestinoPg = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}

$itens_por_pagina = 50;
$pagina  = "1";


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, '');
$adm = $connAdm->connAdm();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_univend = getInput($_POST, 'COD_UNIVEND');
		$cod_grupotr = getInput($_REQUEST, 'COD_GRUPOTR');
		$cod_tiporeg = getInput($_REQUEST, 'COD_TIPOREG');
		$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
		$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));

		$filtro = fnLimpaCampo(getInput($_POST, 'VAL_PESQUISA'));
		$val_pesquisa = fnLimpaCampo(getInput($_POST, 'INPUT'));

		$opcao = getInput($_REQUEST, 'opcao');
		$hHabilitado = getInput($_REQUEST, 'hHabilitado');
		$hashForm = getInput($_REQUEST, 'hashForm');

		if ($opcao != '') {
		}
	}
}

if (isset($_POST['COD_EMPRESA'])) {
} else {
	$cod_empresa = "";
	$cod_empresaCode = "";
	$cod_cliente  = "";
	$nom_cliente  = "";


	if (is_numeric(fnLimpacampo(fnDecode(getInput($_GET, 'id'))))) {

		//busca dados da empresa
		$cod_empresa = fnDecode(getInput($_GET, 'id'));
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

		// fnEscreve($sql);
		$arrayQuery = mysqli_query($adm, $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaEmpresa)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
	}
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = " ";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//Totalizadores
$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
	WHERE
	B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND
	B.LOG_AVULSO='N' AND
	B.COD_EMPRESA = $cod_empresa AND
	( B.COD_SEXOPES = 3  or 
		B.COD_SEXOPES = 0 or
		B.COD_SEXOPES is null or
		B.NOM_CLIENTE = '' or
		B.NOM_CLIENTE is null  
		)
		AND B.COD_UNIVEND IN(0,$lojasSelecionadas)
		";
//fnEscreve($sql);
$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaSexo = mysqli_fetch_assoc($arrayQuery);
$totalSex = $qrBuscaSexo['CONTADOR'];
//sem unidades
//Totalizadores
$sqlunidade = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
	WHERE
	B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND
	B.LOG_AVULSO='N' AND
	B.COD_EMPRESA = $cod_empresa AND
	( B.COD_UNIVEND = '0'  or B.COD_UNIVEND is null)
	";
//fnEscreve($sqlunidade);
$rwunidades = mysqli_query($conn, $sqlunidade);
$rsunidades = mysqli_fetch_assoc($rwunidades);



$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
	WHERE
	B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
	B.LOG_AVULSO='N' AND
	B.COD_EMPRESA = $cod_empresa AND
	(DATE_FORMAT(str_to_date(B.DAT_NASCIME,'%d/%m/%Y'), '%Y-%m-%d') > DATE_FORMAT(CURRENT_DATE() , '%Y-%m-%d')
	or  B.DAT_NASCIME is NULL 	
	or B.DAT_NASCIME =''
	or ANO <='1914') 
	AND B.COD_UNIVEND IN(0,$lojasSelecionadas)	
	";
//fnEscreve($sql);
$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaData = mysqli_fetch_assoc($arrayQuery);
$totalData = $qrBuscaData['CONTADOR'];

$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
	WHERE
	B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
	B.LOG_AVULSO='N' AND
	B.COD_EMPRESA = $cod_empresa AND
	B.DAT_NASCIME is null or
	B.DAT_NASCIME = ''
	AND B.COD_UNIVEND IN(0,$lojasSelecionadas)
	";
//fnEscreve($sql);
$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaDataVazia = mysqli_fetch_assoc($arrayQuery);
$totalDataVazia = $qrBuscaDataVazia['CONTADOR'];

if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

if ($filtro != "") {
	if ($filtro == "UNIDADE") {
		$sqlUni = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
			WHERE (NOM_FANTASI LIKE '%$val_pesquisa%' 
			OR NUM_CGCECPF = '$val_pesquisa' 
			OR NOM_UNIVEND LIKE '%$val_pesquisa%')
			AND COD_EMPRESA = $cod_empresa";
		// fnEscreve($sqlUni);
		$qrUni = mysqli_fetch_assoc(mysqli_query($adm, $sqlUni));

		// fnEscreve($qrUni['COD_UNIVEND']);

		$andFiltro = " ";
		$andUnidade = " AND B.COD_UNIVEND IN ($qrUni[COD_UNIVEND]) ";
	} else {
		$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
		$andUnidade = "";
	}
} else {
	$andFiltro = " ";
}

//idade do individuo
$sqlidade = "SELECT COUNT(*) as idadep FROM CLIENTES B
				WHERE
				B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
				B.LOG_AVULSO='N' AND
				B.COD_EMPRESA = $cod_empresa AND
				B.idade between '0' and '17'
				AND B.COD_UNIVEND IN(0,$lojasSelecionadas)
	";
//fnEscreve($sqlidade);
$rsidade =  mysqli_fetch_assoc(mysqli_query($conn, $sqlidade));
$idadepes = $rsidade['idadep'];

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
//fnMostraForm();

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

// if(fnControlaAcesso("1024",$arrayParamAutorizacao) === true) { 
// 	$autoriza = 1;
// }else{
// 	$autoriza = 0;
// }

?>


<style>
	input[type="search"]::-webkit-search-cancel-button {
		height: 16px;
		width: 16px;
		background: url(images/close-filter.png) no-repeat right center;
		position: relative;
		cursor: pointer;
	}

	input.tableFilter {
		border: 0px;
		background-color: #fff;
	}

	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.filtrado,
	.filtrado:link,
	.filtrado:visited,
	.filtrado:active {
		text-decoration: none;
		font-weight: 1000;
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<div class="login-form">
					<div class="push20"></div>

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= ($dat_ini != " ") ? fnFormatDate($dat_ini) : ''; ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<input type="hidden" name="FILTRO" id="FILTRO" value="<?= $andFiltro ?>">
						<input type="hidden" name="UNIDADE" id="UNIDADE" value="<?= $andUnidade ?>">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="FILTRO_INCONSIST" id="FILTRO_INCONSIST" value="" />

					</form>
				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">

						<?php

						$sql = "SELECT count(B.COD_CLIENTE) CONTADOR FROM CLIENTES B
								WHERE
									B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
							   AND B.COD_EMPRESA = $cod_empresa 
							   AND CASE
									   WHEN B.cod_sexopes = 3 THEN '1'
									   WHEN B.cod_sexopes = 0 THEN '1'
									   WHEN Date_format(Str_to_date(B.dat_nascime, '%d/%m/%Y'), '%Y-%m-%d')> Date_format(CURRENT_DATE(), '%Y-%m-%d') THEN '1'
									   WHEN B.dat_nascime IS NULL THEN '1'
									   WHEN B.dat_nascime = '' THEN '1'
									   WHEN B.cod_univend = '0' THEN '1'
									   WHEN B.ano <= '1910' THEN '1'
									   WHEN  B.cod_univend IS NULL  THEN '1'
									   WHEN B.IDADE BETWEEN '0' AND '17' THEN '1'
									   ELSE '0'
									   END IN (1,1,1,1,1,1,1,1,1)
									  AND  B.cod_univend IN(0,$lojasSelecionadas)
							     	order by B.NOM_CLIENTE";


						//fnEscreve($sql);


						$retorno = mysqli_query($conn, $sql);
						$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

						$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

						?>

						<div class="row text-center">

							<div class="form-group text-center col-md-2 col-lg-2">
								<div class="push20"></div>

								<p><span><?php echo fnValor($total_itens_por_pagina['CONTADOR'], 0); ?> </span></p>
								<p class="text-info"><b>
										<a class="activeRel" href="javascript:void(0)" id="GERAL" onclick="geraFiltro(this)">Cadastros Inconsistentes</a>
									</b></p>
								<p class="f26"><i class="far fa-user-times text-info"></i></p>
							</div>

							<div class="form-group text-center col-md-3 col-lg-3">
								<div class="push20"></div>

								<p><span><?php echo fnValor($totalSex, 0); ?> </span></p>
								<p class="text-info"><b>
										<a class="activeRel" href="javascript:void(0)" id="SEXO_INDEF" onclick="geraFiltro(this)">Sexo Indefinido</a>
									</b></p>
								<p class="f26"><i class="far fa-venus-mars text-success"></i></p>
							</div>

							<div class="form-group text-center col-md-2 col-lg-2">
								<div class="push20"></div>

								<p><span><?php echo fnValor($totalData, 0); ?> </span></p>
								<p class="text-info"><b>
										<a class="activeRel" href="javascript:void(0)" id="DT_INVAL" onclick="geraFiltro(this)">Data Inválida</a>
									</b></p>
								<p class="f26"><i class="far fa-calendar-exclamation text-danger"></i></p>
							</div>

							<div class="form-group text-center col-md-3 col-lg-3">
								<div class="push20"></div>

								<p><span><?php echo fnValor($totalDataVazia, 0); ?> </span></p>
								<p class="text-info"><b>
										<a class="activeRel" href="javascript:void(0)" id="SEM_DT" onclick="geraFiltro(this)">Sem Data</a>
									</b></p>
								<p class="f26"><i class="far fa-calendar-times text-danger"></i></p>
							</div>

							<div class="form-group text-center col-md-2 col-lg-2">
								<div class="push20"></div>

								<p><span><?php echo fnValor($rsunidades['CONTADOR'], 0); ?> </span></p>
								<p class="text-info"><b>
										<a class="activeRel" href="javascript:void(0)" id="SEM_UNIVEND" onclick="geraFiltro(this)">Sem Unidades</a>
									</b></p>
								<p class="f26"><i class="far fa-home text-info"></i></p>
							</div>
							<div class="form-group text-center col-md-2 col-lg-2">
								<div class="push20"></div>

								<p><span><?php echo fnValor($idadepes, 0); ?> </span></p>
								<p class="text-info"><b>
										<a class="activeRel" href="javascript:void(0)" id="idade" onclick="geraFiltro(this)"> Idade < 17 </a>
									</b></p>
								<p class="f26"><i class="fas fa-child"></i></p>
							</div>

						</div>

					</div>
					<!-- fim Portlet -->
				</div>

			</div>

		</div>


		<div class="portlet portlet-bordered">
			<div class="portlet-body">
				<div class="login-form">
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
											<li><a href="#B.NOM_CLIENTE">Nome do Cliente</a></li>
											<li><a href="#B.NUM_CGCECPF">CPF</a></li>
											<!-- <li><a href="#UNIDADE">Loja</a></li>										                       -->
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

					<table class="table table-bordered table-striped table-hover buscavel tablesorter" id="tablista">
						<thead>
							<tr>
								<th class="{ sorter: false }"></th>
								<th>Nome</th>
								<th>Cartão</th>
								<th>CPF</th>
								<th>e-Mail</th>
								<th class="{ sorter: false }">Sexo</th>
								<th>Nascimento</th>
								<th>Idade</th>
								<th>Cadastro</th>
								<th>Origem</th>
								<th class="{ sorter: false }"></th>
							</tr>
						</thead>

						<tbody id="relatorioConteudo">

							<?php

							// Filtro por Grupo de Lojas
							include "filtroGrupoLojas.php";

							if ($andUnidade == "") {
								$orUnidade = "AND (B.COD_UNIVEND IN(0,$lojasSelecionadas) OR B.COD_UNIVEND = 0 OR B.COD_UNIVEND IS NULL)";
							} else {
								$orUnidade = "";
							}

							$sql2 = "SELECT count(B.COD_CLIENTE) qtd_pagina FROM CLIENTES B
								WHERE
									B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
							   AND B.COD_EMPRESA = $cod_empresa 
							   AND CASE
									   WHEN B.cod_sexopes = 3 THEN '1'
									   WHEN B.cod_sexopes = 0 THEN '1'
									   WHEN Date_format(Str_to_date(B.dat_nascime, '%d/%m/%Y'), '%Y-%m-%d')> Date_format(CURRENT_DATE(), '%Y-%m-%d') THEN '1'
									   WHEN B.dat_nascime IS NULL THEN '1'
									   WHEN B.dat_nascime = '' THEN '1'
									   WHEN B.cod_univend = '0' THEN '1'
									   WHEN B.ano <= '1910' THEN '1'
									   WHEN  B.cod_univend IS NULL  THEN '1'
									   WHEN B.IDADE BETWEEN '0' AND '17' THEN '1'
									   ELSE '0'
									   END IN (1,1,1,1,1,1,1,1,1)
									  AND  B.cod_univend IN(0,$lojasSelecionadas)
							     	order by B.NOM_CLIENTE
								";

							// fnEscreve($sql2);

							$retorno = mysqli_fetch_assoc(mysqli_query($conn, $sql2));
							$total_itens_por_pagina = $retorno['qtd_pagina'];

							$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);



							//============================
							/*$ARRAY_UNIDADE1=array(
									'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
									'cod_empresa'=>$cod_empresa,
									'conntadm'=>$connAdm->connAdm(),
									'IN'=>'N',
									'nomecampo'=>'',
									'conntemp'=>'',
									'SQLIN'=> ""   
								);
								$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1); 
                                                                 * 
                                                                 */

							//variavel para calcular o início da visualização com base na página atual
							$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

							$sql = "SELECT B.COD_CLIENTE,B.NUM_CARTAO,B.NUM_CGCECPF,B.NOM_CLIENTE,
								B.DES_EMAILUS,B.DAT_CADASTR,B.DAT_NASCIME ,B.COD_SEXOPES,B.COD_UNIVEND,uni.NOM_FANTASI,B.IDADE
								FROM CLIENTES B
								LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=B.COD_UNIVEND
                                                                WHERE
                                                                B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
                                                                AND B.COD_EMPRESA = $cod_empresa 
                                                                AND CASE
                                                                            WHEN B.cod_sexopes = 3 THEN '1'
									   WHEN B.cod_sexopes = 0 THEN '1'
									   WHEN Date_format(Str_to_date(B.dat_nascime, '%d/%m/%Y'), '%Y-%m-%d')> Date_format(CURRENT_DATE(), '%Y-%m-%d') THEN '1'
									   WHEN B.dat_nascime IS NULL THEN '1'
									   WHEN B.dat_nascime = '' THEN '1'
									   WHEN B.cod_univend = '0' THEN '1'
									   WHEN B.ano <= '1910' THEN '1'
									   WHEN  B.cod_univend IS NULL  THEN '1'
									   WHEN B.IDADE BETWEEN '0' AND '17' THEN '1'
									   ELSE '0'
									   END IN (1,1,1,1,1,1,1,1,1)
									  AND  B.cod_univend IN(0,$lojasSelecionadas)
							   	order by B.NOM_CLIENTE limit $inicio,$itens_por_pagina";

							///fnEscreve($sql);

							$arrayQuery = mysqli_query($conn, $sql);

							$count = 0;
							while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {


								$count++;
								$loja = "";
								/*$NOM_ARRAY_UNIDADE=(array_search($qrListaPersonas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                                                                         * 
                                                                         */
								if ($qrListaPersonas['COD_UNIVEND'] != 0 && $qrListaPersonas['COD_UNIVEND'] != "") {
									$loja = $qrListaPersonas['NOM_FANTASI'];
								}

								if ($qrListaPersonas['COD_SEXOPES'] == 1) {
									$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
								}

								if ($qrListaPersonas['COD_SEXOPES'] == 2) {
									$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';
								}

								if ($qrListaPersonas['COD_SEXOPES'] == 3) {
									$mostraSexo = '<i class="fa fa-venus-mars" aria-hidden="true"></i>';
								}

								if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
									$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NOM_CLIENTE']) . "</a></small></td>";
									$colCartao = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NUM_CARTAO']) . "</a></small></td>";
								} else {
									$colCliente = "<td><small>" . fnMascaraCampo($qrListaPersonas['NOM_CLIENTE']) . "</small></td>";
									$colCartao = "<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CARTAO']) . "</small></td>";
								}

								echo "
										<tr>
											<td></td>
											" . $colCliente . "
											" . $colCartao . "
											<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CGCECPF']) . "</small></td>
											<td><small>" . fnMascaraCampo(strtolower($qrListaPersonas['DES_EMAILUS'])) . "</small></td>
											<td class='text-center'><small>" . $mostraSexo . "</small></td>
											<td><small>" . fnMascaraCampo($qrListaPersonas['DAT_NASCIME']) . "</small></td>
											<td>" . $qrListaPersonas['IDADE'] . "</small></td>
											<td><small>" . fnDataFull($qrListaPersonas['DAT_CADASTR']) . "</small></td>
											<td>" . $loja . "</small></td>											
											<td><small><a class='btn btn-xs btn-default addBox' href='action.do?mod=" . fnEncode(1343) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "&pop=true' data-title='Reprocessamento de Inconsistências'><small><i class='fas fa-cog'></i></small></a></td>
										</tr>
									";
							}

							?>

						</tbody>

						<tfoot>
							<tr>
								<th colspan="100">
									<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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

					<!-- modal -->
					<div class="modal fade" id="popModal" tabindex='-1'>
						<div class="modal-dialog" style="">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title"></h4>
								</div>
								<div class="modal-body">
									<iframe frameborder="0" style="width: 100%; height: 40%"></iframe>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->

					<div class="push"></div>


				</div>

			</div>
		</div>

		<div class="push"></div>

	</div>
	<!-- fim Portlet -->
</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
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
		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//table sorter
		$(function() {
			var tabelaFiltro = $('table.tablesorter')
			tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
				$(this).prev().find(":checkbox").click()
			});
			$("#filter").keyup(function() {
				$.uiTableFilter(tabelaFiltro, this.value);
			})
			$('#formLista').submit(function() {
				tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
				return false;
			}).focus();
		});

		//pesquisa table sorter
		$('.filter-all').on('input', function(e) {
			if ('' == this.value) {
				var lista = $("#filter").find("ul").find("li");
				filtrar(lista, "");
			}
		});

		$(".exportarCSV").click(function() {
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
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "relatorios/ajxRelCadastroErro.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
									}).fail(function() {
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									});
								},
								buttons: {
									fechar: function() {
										//close
									}
								}
							});
						}
					},
					cancelar: function() {
						//close
					},
				}
			});
		});

	});

	$(document).on('change', '#COD_EMPRESA', function() {
		$("#dKey").val($("#COD_EMPRESA").val());
	});

	function geraFiltro(el) {

		if (!$(el).hasClass('filtrado')) {

			tipo = $(el).attr('id');

			if (tipo == "SEXO_INDEF") {
				filtro = "AND (B.COD_SEXOPES = 3  or B.COD_SEXOPES = 0 or B.COD_SEXOPES is null or	B.NOM_CLIENTE = '' or B.NOM_CLIENTE is null)";
			} else if (tipo == "DT_INVAL") {
				filtro = "AND DATE_FORMAT(str_to_date(B.DAT_NASCIME,'%d/%m/%Y'), '%Y-%m-%d') > DATE_FORMAT(CURRENT_DATE() , '%Y-%m-%d')";
			} else if (tipo == "SEM_DT") {
				filtro = "AND (DAT_NASCIME = '' OR DAT_NASCIME IS NULL)";
			} else if (tipo == "SEM_UNIVEND") {
				filtro = "AND ( B.COD_UNIVEND = '0'  or B.COD_UNIVEND is null)";
			} else {
				filtro = "";
			}

			$("#FILTRO_INCONSIST").val(filtro);

			$("." + tipo + " a").removeClass('filtrado');
			$('.activeRel').removeClass('filtrado');
			$(el).addClass('filtrado');

		} else {

			filtro = "";
			$("#FILTRO_INCONSIST").val('');
			$(el).removeClass('filtrado');

		}

		reloadPage(1);

	}


	function page(index) {
		$("#pagina").val(index);
		$("#formulario")[0].submit();
		//alert(index);		
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelCadastroErro.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

	function retornaForm(index) {

		$('#formulario').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_COD_EMPRESA_" + index).val() + '&idC=' + $("#ret_COD_CLIENTE_" + index).val());
		$("#formulario #hHabilitado").val('S');
		$("#formulario")[0].submit();

	}
</script>