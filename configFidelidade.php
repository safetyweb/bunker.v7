<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_program = "";
$conta_faixa = "";
$qtde_regras = 0;
$tip_campanha = "";
$nom_empresa = "";
$lojasSelecionadas = "";
$cod_campanha = "";
$Arr_COD_PERSONA = "";
$i = 0;
$cod_persona = "";
$pct_vantagem = "";
$qtd_vantagem = 0;
$cod_vantage = "";
$qtd_resultado = 0;
$nom_vantagem = "";
$log_produto = "";
$log_catprod = "";
$log_indicador = "";
$pct_vantagem_ind = "";
$cod_vantagem_ind = "";
$qtd_resultado_ind = 0;
$tip_geracao = "";
$cps_extra_dom = "";
$cps_extra_seg = "";
$cps_extra_ter = "";
$cps_extra_qua = "";
$cps_extra_qui = "";
$cps_extra_sex = "";
$cps_extra_sab = "";
$cps_extind_dom = "";
$cps_extind_seg = "";
$cps_extind_ter = "";
$cps_extind_qua = "";
$cps_extind_qui = "";
$cps_extind_sex = "";
$cps_extind_sab = "";
$hHabilitado = "";
$hashForm = "";
$univend = "";
$cod_usucada = "";
$log_unifica = "";
$cod_controle = "";
$sql1 = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$log_categoria = "";
$tip_credito = "";
$abaPersona = "";
$abaVantagem = "";
$abaRegras = "";
$abaComunica = "";
$abaAtivacao = "";
$abaResultado = "";
$abaPersonaComp = "";
$abaCampanhaComp = "";
$abaVantagemComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaResultadoComp = "";
$abaAtivacaoComp = "";
$qrBuscaCampanha = "";
$log_ativo = "";
$des_campanha = "";
$abr_campanha = "";
$des_icone = "";
$log_realtime = "";
$dat_ini = "";
$hor_ini = "";
$dat_fim = "";
$hor_fim = "";
$maxPersona = "";
$msgPersona = "";
$qrBuscaTpCampanha = "";
$nom_tpcampa = "";
$abv_tpcampa = "";
$des_iconecp = "";
$label_1 = "";
$label_2 = "";
$label_3 = "";
$label_4 = "";
$label_5 = "";
$tem_personas = "";
$custoReal = "";
$log_cpfcnpj = "";
$checaCPF = "";
$log_email = "";
$checaMail = "";
$log_celular = "";
$checaCel = "";
$checaProduto = "";
$disabPct = "";
$checaCatProd = "";
$checaIndicador = "";
$checaUnifica = "";
$txtBntExtra6 = "";
$temRegra = "";
$addbox = "";
$habilitaIndicacao = "";
$icoBntExtra6 = "";
$sqlPessoas = "";
$arrayPessoas = [];
$qrBuscaPessoas = "";
$num_pessoas = "";
$sqlCatEsp = "";
$arrCatEsp = "";
$temCatEsp = "";
$log_catesp = "";
$sqlUpdtCatEsp = "";
$abaCampanhas = "";
$abaCli = "";
$arrayAutorizado = [];
$andUnidade = "";
$qrListaPersonas = "";
$desabilitado = "";
$desabilitadoOnTxt = "";
$desabilitadoRg = "";
$desabilitadoRgTxt = "";
$mask = "";
$titulo = "";
$txt = "";
$col = "";
$colTxt = "";
$txt_cred = "";
$sqlIndica = "";
$queryIndica = "";
$qrbuscaIndica = "";
$qtd_indica = 0;
$qrListaVantagem = "";
$checado = "";
$sqlprodespecifico = "";
$sqlprodresultado = "";
$prodretorno = "";
$updateproduto = "";
$icoBntExtra3 = "";
$sqlcategoria = "";
$categoriaresultado = "";
$querycategoria = "";
$updatecategoria = "";
$sqlvalor = "";
$sqlvalorResultado = "";
$valorRetorno = "";
$updatefaixavalor = "";
$sqlpagamento = "";
$sqlpagamentoquery = "";
$querypagamento = "";
$updatepagamento = "";
$sqlfornecedor = "";
$sqlfornecedorquery = "";
$queryfornecedor = "";
$updatefornecedor = "";


$hashLocal = mt_rand();

//busca revendas do usuário
include "unidadesAutorizadas.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_program = fnLimpaCampoZero(@$_REQUEST['COD_PROGRAM']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$conta_faixa = fnLimpaCampoZero(@$_REQUEST['CONTA_FAIXA']);
		$qtde_regras = fnLimpaCampoZero(@$_REQUEST['QTDE_REGRAS']);
		$tip_campanha = fnLimpaCampoZero(@$_REQUEST['TIP_CAMPANHA']);
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
		$nom_empresa = fnLimpaCampo(@$_REQUEST['NOM_EMPRESA']);

		if ($cod_univend == 9999 || $cod_univend == '') {
			$lojasSelecionadas = 0;
		}

		$cod_campanha = fnDecode(@$_GET['idc']);

		if (isset($_POST['COD_PERSONA'])) {
			$Arr_COD_PERSONA = @$_POST['COD_PERSONA'];

			for ($i = 0; $i < count($Arr_COD_PERSONA); $i++) {
				$cod_persona = $cod_persona . $Arr_COD_PERSONA[$i] . ",";
			}

			$cod_persona = substr($cod_persona, 0, -1);
		} else {
			$cod_persona = "0";
		}

		//fnEscreve(@$_REQUEST['PCT_VANTAGEM']);

		$pct_vantagem = fnLimpaCampo(@$_REQUEST['PCT_VANTAGEM']);
		$qtd_vantagem = fnLimpaCampo(@$_REQUEST['QTD_VANTAGEM']);
		$cod_vantage = fnLimpaCampoZero(@$_REQUEST['COD_VANTAGE']);
		$qtd_resultado = fnLimpaCampo(@$_REQUEST['QTD_RESULTADO']);
		$nom_vantagem = fnLimpaCampo(@$_REQUEST['NOM_VANTAGEM']);

		$log_produto = fnLimpaCampo(@$_REQUEST['LOG_PRODUTO']);
		if ($log_produto == "") {
			$log_produto = "N";
		}

		$log_catprod = fnLimpaCampo(@$_REQUEST['LOG_CATPROD']);
		if ($log_catprod == "") {
			$log_catprod = "N";
		}

		$log_indicador = fnLimpaCampo(@$_REQUEST['LOG_INDICADOR']);
		if ($log_indicador == "") {
			$log_indicador = "N";
		}

		$pct_vantagem_ind = fnLimpaCampo(@$_REQUEST['PCT_VANTAGEM_IND']);
		$cod_vantagem_ind = fnLimpaCampoZero(@$_REQUEST['COD_VANTAGEM_IND']);
		$qtd_resultado_ind = fnLimpaCampoZero(@$_REQUEST['QTD_RESULTADO_IND']);

		$tip_geracao = fnLimpaCampo(@$_REQUEST['TIP_GERACAO']);

		$cps_extra_dom = fnLimpaCampoZero(@$_REQUEST['CPS_EXTRA_DOM']);
		$cps_extra_seg = fnLimpaCampoZero(@$_REQUEST['CPS_EXTRA_SEG']);
		$cps_extra_ter = fnLimpaCampoZero(@$_REQUEST['CPS_EXTRA_TER']);
		$cps_extra_qua = fnLimpaCampoZero(@$_REQUEST['CPS_EXTRA_QUA']);
		$cps_extra_qui = fnLimpaCampoZero(@$_REQUEST['CPS_EXTRA_QUI']);
		$cps_extra_sex = fnLimpaCampoZero(@$_REQUEST['CPS_EXTRA_SEX']);
		$cps_extra_sab = fnLimpaCampoZero(@$_REQUEST['CPS_EXTRA_SAB']);

		$cps_extind_dom = fnLimpaCampoZero(@$_REQUEST['CPS_EXTIND_DOM']);
		$cps_extind_seg = fnLimpaCampoZero(@$_REQUEST['CPS_EXTIND_SEG']);
		$cps_extind_ter = fnLimpaCampoZero(@$_REQUEST['CPS_EXTIND_TER']);
		$cps_extind_qua = fnLimpaCampoZero(@$_REQUEST['CPS_EXTIND_QUA']);
		$cps_extind_qui = fnLimpaCampoZero(@$_REQUEST['CPS_EXTIND_QUI']);
		$cps_extind_sex = fnLimpaCampoZero(@$_REQUEST['CPS_EXTIND_SEX']);
		$cps_extind_sab = fnLimpaCampoZero(@$_REQUEST['CPS_EXTIND_SAB']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($tip_campanha == 23) {
			$univend = implode(',', $lojasSelecionadas);
		} else {
			$univend = implode(',', $cod_univend);
		}

		if ($univend == "" || $univend == 9999) {
			$univend = 0;
		}


		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			$sql = "CALL SP_ALTERA_CAMPANHAREGRA (
			'" . $cod_campanha . "', 
			'" . $cod_persona . "', 
			'" . fnValorSql($pct_vantagem) . "', 
			'" . $qtd_vantagem . "', 
			'" . fnValorSql($qtd_resultado) . "', 
			'" . $cod_usucada . "', 
			'" . $nom_vantagem . "', 
			'" . $cod_vantage . "', 
			'" . $log_produto . "', 
			'" . $log_catprod . "', 
			'" . $cod_empresa . "', 
			'" . $log_indicador . "', 
			'" . $log_unifica . "', 
			'" . fnValorSql($pct_vantagem_ind) . "', 
			'" . $cod_vantagem_ind . "',
			'" . $qtd_resultado_ind . "',

			'" . $tip_geracao . "',
			'" . $cps_extra_dom . "',
			'" . $cps_extra_seg . "',
			'" . $cps_extra_ter . "',
			'" . $cps_extra_qua . "',
			'" . $cps_extra_qui . "',
			'" . $cps_extra_sex . "',
			'" . $cps_extra_sab . "', 					 					
			'" . $cps_extind_dom . "',
			'" . $cps_extind_seg . "',
			'" . $cps_extind_ter . "',
			'" . $cps_extind_qua . "',
			'" . $cps_extind_qui . "',
			'" . $cps_extind_sex . "',
			'" . $cps_extind_sab . "',
			'" . $univend . "'

		) ";

			// fnEscreve($sql);	
			//fnTestesql(connTemp($cod_empresa,''),$sql);
			//fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa, ''), trim($sql));

			//fnEscreve($conta_faixa);

			//categorizacao de clientes
			if ($conta_faixa > 0) {

				//fnEscreve("entrou faixas");

				for ($i = 1; $i <= $conta_faixa; $i++) {

					//fnEscreve("pct -> ".@$_REQUEST['PCT_VANTAGEM_CAT_'.$i]);	
					//fnEscreve("cat -> ".@$_REQUEST['COD_CATEGORIA_'.$i]);

					$cod_controle = 0;
					$sql1 = "CALL SP_ALTERA_CATEGORIA_CLIENTE_CAMPANHA (
				'" . $cod_controle . "', 
				'" . $cod_empresa . "', 
				'" . $cod_campanha . "', 
				'" . @$_REQUEST['COD_CATEGORIA_' . $i] . "', 
				'" . fnValorSql(@$_REQUEST['PCT_VANTAGEM_CAT_' . $i]) . "', 
				'" . $cod_usucada . "', 
				'CAD' 
			) ";

					// fnEscreve($sql);
					//fnTestesql(connTemp($cod_empresa,''),$sql1);
					mysqli_query(connTemp($cod_empresa, ''), trim($sql1));
				}
			}


			//mensagem de retorno
			switch ($opcao) {
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, LOG_CATEGORIA, TIP_CAMPANHA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$log_categoria = $qrBuscaEmpresa['LOG_CATEGORIA'];
		$tip_credito = $qrBuscaEmpresa['TIP_CAMPANHA'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "S";
		$abaAtivacao = "N";
		$abaResultado = "N";

		//$abaPersonaComp = "completed ";
		$abaPersonaComp = "active ";
		$abaCampanhaComp = "active";
		$abaVantagemComp = "completed ";
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "";
		$abaResultadoComp = "";
		//revalidada na aba de regras	
		$abaAtivacaoComp = "";
	}

	//busca dados da campanha
	$cod_campanha = fnDecode(@$_GET['idc']);
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
		$dat_ini = $qrBuscaCampanha['DAT_INI'];
		$hor_ini = $qrBuscaCampanha['HOR_INI'];
		$dat_fim = $qrBuscaCampanha['DAT_FIM'];
		$hor_fim = $qrBuscaCampanha['HOR_FIM'];

		if ($log_realtime == "S") {
			$maxPersona = 1;
			$msgPersona = "Campanhas em <b>tempo real</b> permitem a utilização de <b>uma persona por campanha</b>";
		} else {
			$maxPersona = 10;
			$msgPersona = "";
		}
	}

	//fnEscreve($tip_campanha);

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
	// fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	if ($qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery)) {

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
		$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];
		$cod_univend = $qrBuscaTpCampanha['COD_UNIVENDESP'];
		$custoReal = fnValor($qtd_vantagem / $qtd_resultado, 2);

		$log_cpfcnpj = $qrBuscaTpCampanha['LOG_CPFCNPJ'];
		if ($log_cpfcnpj == 'S') {
			$checaCPF = 'checked';
		} else {
			$checaCPF = '';
		}

		$log_email = $qrBuscaTpCampanha['LOG_EMAIL'];
		if ($log_email == 'S') {
			$checaMail = 'checked';
		} else {
			$checaMail = '';
		}

		$log_celular = $qrBuscaTpCampanha['LOG_CELULAR'];
		if ($log_celular == 'S') {
			$checaCel = 'checked';
		} else {
			$checaCel = '';
		}

		$log_produto = $qrBuscaTpCampanha['LOG_PRODUTO'];
		if ($log_produto == 'S') {
			$checaProduto = 'checked';
			$disabPct = "";
		} else {
			$disabPct = "disabled";
			$checaProduto = '';
		}

		$log_catprod = $qrBuscaTpCampanha['LOG_CATPROD'];
		if ($log_catprod == 'S') {
			$checaCatProd = 'checked';
		} else {
			$checaCatProd = '';
		}

		$log_indicador = $qrBuscaTpCampanha['LOG_INDICADOR'];
		if ($log_indicador == 'S') {
			$checaIndicador = 'checked';
		} else {
			$checaIndicador = '';
		}

		$log_unifica = $qrBuscaTpCampanha['LOG_UNIFICA'];
		if ($log_unifica == 'S') {
			$checaUnifica = 'checked';
		} else {
			$checaUnifica = '';
		}

		$pct_vantagem_ind = $qrBuscaTpCampanha['PCT_VANTAGEM_IND'];
		$cod_vantagem_ind = $qrBuscaTpCampanha['COD_VANTAGEM_IND'];
		$qtd_resultado_ind = $qrBuscaTpCampanha['QTD_RESULTADO_IND'];

		$tip_geracao = $qrBuscaTpCampanha['TIP_GERACAO'];
		$cps_extra_dom = $qrBuscaTpCampanha['CPS_EXTRA_DOM'];
		$cps_extra_seg = $qrBuscaTpCampanha['CPS_EXTRA_SEG'];
		$cps_extra_ter = $qrBuscaTpCampanha['CPS_EXTRA_TER'];
		$cps_extra_qua = $qrBuscaTpCampanha['CPS_EXTRA_QUA'];
		$cps_extra_qui = $qrBuscaTpCampanha['CPS_EXTRA_QUI'];
		$cps_extra_sex = $qrBuscaTpCampanha['CPS_EXTRA_SEX'];
		$cps_extra_sab = $qrBuscaTpCampanha['CPS_EXTRA_SAB'];

		$cps_extind_dom = $qrBuscaTpCampanha['CPS_EXTIND_DOM'];
		$cps_extind_seg = $qrBuscaTpCampanha['CPS_EXTIND_SEG'];
		$cps_extind_ter = $qrBuscaTpCampanha['CPS_EXTIND_TER'];
		$cps_extind_qua = $qrBuscaTpCampanha['CPS_EXTIND_QUA'];
		$cps_extind_qui = $qrBuscaTpCampanha['CPS_EXTIND_QUI'];
		$cps_extind_sex = $qrBuscaTpCampanha['CPS_EXTIND_SEX'];
		$cps_extind_sab = $qrBuscaTpCampanha['CPS_EXTIND_SAB'];

		$txtBntExtra6 = "Cadastrar";
		$temRegra = 'S';
		$addbox = "addBox";
		$habilitaIndicacao = "";
	} else {

		$cod_persona = 0;
		$pct_vantagem = "";
		$qtd_vantagem = "";
		$qtd_vantagem = "";
		$nom_vantagem = "";
		$cod_vantage = 0;
		$custoReal = "";
		$txtBntExtra6 = "Cadastrar";
		$icoBntExtra6 = "fa-plus";
		$habilitaIndicacao = 'opacity: 0.5;';
		$temRegra = 'N';
		$addbox = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');

}

if ($cod_univend == 0) {
	$cod_univend = '9999';
}

$sqlPessoas = "SELECT COUNT(*) as PESSOAS FROM PERSONACLASSIFICA WHERE COD_PERSONA = $cod_persona AND COD_EMPRESA = $cod_empresa";

$arrayPessoas = mysqli_query(connTemp($cod_empresa, ''), $sqlPessoas);
if ($qrBuscaPessoas = mysqli_fetch_assoc($arrayPessoas)) {
	$num_pessoas = $qrBuscaPessoas['PESSOAS'];
} else {
	$num_pessoas = 0;
}


$sqlCatEsp = "SELECT * FROM CUPOMCATEGORIAESPECIFICA
			  WHERE COD_EMPRESA = $cod_empresa 
 			  AND COD_CAMPANHA = $cod_campanha
 			  AND COD_EXCLUSA = 0 ";

$arrCatEsp = mysqli_query(connTemp($cod_empresa, ''), $sqlCatEsp);
$temCatEsp = mysqli_num_rows($arrCatEsp);
$log_catesp = 'N';
if ($temCatEsp > 0) {
	$log_catesp = 'S';
}

$sqlUpdtCatEsp = "UPDATE CAMPANHAREGRA 
				  SET LOG_CATESP = '$log_catesp'
				  WHERE COD_EMPRESA = $cod_empresa
				  AND COD_CAMPANHA = $cod_campanha";
mysqli_query(connTemp($cod_empresa, ''), $sqlCatEsp);



?>
<style>
	#SABADO {
		position: absolute;
		left: 26px;
		top: 355px;
	}

	#SABADO2 {
		position: absolute;
		left: 26px;
		top: 317px;
	}
</style>
<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php $abaCampanhas = 1022;
				include "abasCampanhasConfig.php"; ?>

				<div class="push10"></div>

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>


				<?php $abaCli = 1022;
				include "abasRegrasConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<style>
						#blocker {
							display: none;
							position: fixed;
							top: 0;
							left: 0;
							width: 100%;
							height: 100%;
							opacity: .8;
							background-color: #fff;
							z-index: 1000;
						}

						#blocker div {
							position: absolute;
							top: 30%;
							left: 48%;
							width: 200px;
							height: 2em;
							margin: -1em 0 0 -2.5em;
							color: #000;
							font-weight: bold;
						}

						.notify-badge {
							position: absolute;
							right: 36%;
							top: 10px;
							background: #18bc9c;
							border-radius: 30px 30px 30px 30px;
							text-align: center;
							color: white;
							font-size: 11px;
						}

						.notify-badge span {
							margin: 0 auto;
						}

						.pos {
							right: 33;
							top: -10;
							background: #ffbf00;
							font-size: 9px;
							padding-top: 2px;
						}
					</style>


					<div id="blocker">
						<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
					</div>

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Campanha</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Programa</label>
										<div class="push10"></div>
										<span class="fa <?php echo $des_iconecp; ?>"></span> <b><?php echo $nom_tpcampa; ?> </b>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Início Campanha</label>
										<input type="text" class="form-control input-sm leitura f14" readonly="readonly" value="<?= fnDataShort($dat_ini) . " " . $hor_ini ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Fim Campanha</label>
										<input type="text" class="form-control input-sm leitura f14" readonly="readonly" value="<?= fnDataShort($dat_fim) . " " . $hor_fim ?>">
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

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Vantagem</label>
										<input type="text" class="form-control input-sm" name="NOM_VANTAGEM" id="NOM_VANTAGEM" maxlength="20" value="<?php echo $nom_vantagem; ?>" required>
									</div>
								</div>

								<?php
								//campanha de sorteio
								if ($tip_campanha != 20 && $tip_campanha != 22) { ?>
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Utilizar <b>Todos</b> <br />Produtos do Catálogo</label>
											<div class="push5"></div>
											<input type="hidden" name="TOUR_LOG_PRODUTO" id="TOUR_LOG_PRODUTO">
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_PRODUTO" id="LOG_PRODUTO" class="switch" value="S" <?php echo $checaProduto; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2" id="logCatProd">
										<div class="form-group">
											<label for="inputName" class="control-label">Permite <b>Adicional</b> <br /> por Categorias</label>
											<div class="push5"></div>
											<input type="hidden" name="TOUR_LOG_CATPROD" id="TOUR_LOG_CATPROD">
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_CATPROD" id="LOG_CATPROD" class="switch" value="S" <?php echo $checaCatProd; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<input type="hidden" name="TIP_GERACAO" id="TIP_GERACAO" value="">

									<?php } else {

									if ($tip_campanha != 22) {

									?>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Tipo de Geração</label>
												<select data-placeholder="Selecione o tipo da vantagem" name="TIP_GERACAO" id="TIP_GERACAO" class="chosen-select-deselect requiredChk" required>
													<option value="">&nbsp;</option>
													<option value="UND">Unidade (números únicos por unidade)</option>
													<option value="GRL">Geral (números únicos geral) </option>
													<option value="REG">Região (números únicos por região)</option>
												</select>
												<script>
													$("#formulario #TIP_GERACAO").val("<?php echo $tip_geracao; ?>").trigger("chosen:updated");
												</script>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Regra do Sorteio</label>
												<select data-placeholder="Selecione a regra" name="DES_REGRA" id="DES_REGRA" class="chosen-select-deselect" required>
													<option value="1" selected>Nro. imediatamente anterior ao primeiro premio sorteado</option>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									<?php

									}

									?>
									<input type="hidden" name="LOG_PRODUTO" id="LOG_PRODUTO" value="S">
									<input type="hidden" name="LOG_CATPROD" id="LOG_CATPROD" value="N">
								<?php

								}

								?>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Personas Participantes da Campanha</label>

										<select data-placeholder="Selecione as personas desejadas" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
											<?php
											//se venda em tempo real
											//$sql = "select * from persona where cod_empresa = ".$cod_empresa." order by DES_PERSONA  ";	

											// $arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

											if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {

												$andUnidade = "";
											} else {

												$andUnidade = "AND PERSONA.COD_UNIVEND IN($_SESSION[SYS_COD_UNIVEND])";
											}

											$sql = "SELECT IFNULL(PERSONAREGRA.COD_REGRA,0) AS TEM_REGRA, 
											PERSONA.* 
											FROM PERSONA 
											LEFT JOIN PERSONAREGRA ON PERSONAREGRA.COD_PERSONA = PERSONA.COD_PERSONA
											WHERE COD_EMPRESA = $cod_empresa 
											$andUnidade
											GROUP BY COD_PERSONA
											ORDER BY DES_PERSONA ";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrListaPersonas['LOG_ATIVO'] == "N") {
													$desabilitado = "disabled";
													$desabilitadoOnTxt = " (Off)";
												} else {
													$desabilitado = "";
													$desabilitadoOnTxt = "";
												}

												if ($qrListaPersonas['TEM_REGRA'] == "0") {
													$desabilitadoRg = " disabled";
													$desabilitadoRgTxt = " (s/ regra)";
												} else {
													$desabilitadoRg = "";
													$desabilitadoRgTxt = "";
												}

												echo "
												<option value='" . $qrListaPersonas['COD_PERSONA'] . "' " . $desabilitado . $desabilitadoRg . ">" . ucfirst($qrListaPersonas['DES_PERSONA']) . $desabilitadoRgTxt . $desabilitadoOnTxt . "</option> 
												";
											}


											?>
										</select>
										<span class="help-block"><?php echo $msgPersona; ?></span>
										<div class="help-block with-errors"></div>
										<script>
											//retorno combo multiplo
											if ("<?php echo $tem_personas; ?>" == "sim") {
												var sistemasCli = "<?php echo $cod_persona; ?>";
												var sistemasCliArr = sistemasCli.split(',');
												//opções multiplas
												for (var i = 0; i < sistemasCliArr.length; i++) {
													$("#formulario #COD_PERSONA option[value=" + sistemasCliArr[i] + "]").prop("selected", "true");
												}
												$("#formulario #COD_PERSONA").trigger("chosen:updated");
											} else {
												$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");
											}
										</script>
									</div>

								</div>

								<?php
								// fnEscreve($tip_campanha);
								if ($tip_campanha == 23) {
								?>

									<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="0">

								<?php } else { ?>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidades Específicas</label>
											<?php include "unidadesAutorizadasComboMulti.php"; ?>
										</div>
									</div>

								<?php } ?>
							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">

							<?php

							// fnEscreve($tip_campanha);
							//se bloco de cash back
							if ($tip_campanha == 13 || $tip_campanha == 22) {
								$mask = "money";
								$titulo = "Percentual";
								$txt = "Qual o percentual do valor da compra será revertido em vantagens?";
								$col = "6";
								$colTxt = "9";

								if ($tip_campanha == 22) {
									$mask = "int";
									$titulo = "Valor";
									$col = "9";
									$colTxt = "6";
									if ($tip_credito == 13) {
										$txt_cred = "créditos";
									} else {
										$txt_cred = "pontos";
									}
									$txt = "Informe o valor de $txt_cred a serem ganhos no cadastro:";

									$disabPct = "";
									// if ($pct_vantagem != '' && $pct_vantagem != 0){
									// 	$pct_vantagem = fnValor($pct_vantagem,0);
									// }
								}

							?>

								<div class="col-md-<?= $col ?>">

									<fieldset>
										<legend><?= $titulo ?> da Campanha </legend>

										<div class="row">

											<div class="push25"></div>

											<div class="col-md-<?= $colTxt ?>" style="margin:0; padding: 0 0 0 15px;">
												<div class="push20"></div>
												<h5><?= $txt ?></h5>
											</div>

											<div class="col-md-3">
												<div class="col-md-9" style="margin:0; padding: 0;">
													<div class="form-group">
														<label for="inputName" class="control-label required">&nbsp;</label>
														<input type="text" class="form-control text-center input-sm money" name="PCT_VANTAGEM" id="PCT_VANTAGEM" maxlength="6" value="<?php echo $pct_vantagem; ?>" <?= $disabPct; ?> data-error="Campo obrigatório">
														<div class="help-block with-errors"></div>
													</div>
												</div>
												<?php if ($tip_campanha != 22) { ?>
													<span style="margin:0; padding: 27px 0 0 3px; font-size: 18px;" class="col-md-2 pull-left">%<span>
														<?php } else { ?>
															<span style="margin:0; padding: 27px 0 0 3px; font-size: 16px;" class="col-md-2 pull-left"><?= $txt_cred ?><span>
																<?php } ?>
											</div>

											<?php if ($tip_campanha == 22) { ?>



												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label required">Validade</label>
														<input type="text" class="form-control text-center input-sm int" name="QTD_VANTAGEM" id="QTD_VANTAGEM" maxlength="3" value="<?php echo $qtd_vantagem; ?>" data-error="Campo obrigatório">
														<div class="help-block with-errors">Em dias</div>
													</div>
												</div>


											<?php } else { ?>
												<input type="hidden" name="QTD_VANTAGEM" id="QTD_VANTAGEM" value="1">
											<?php } ?>

										</div>


										<input type="hidden" class="money" name="CONTA_FAIXA" id="CONTA_FAIXA" maxlength="6" value="0" data-error="Campo obrigatório">
										<input type="hidden" name="COD_VANTAGE" id="COD_VANTAGE" value="1">
										<input type="hidden" name="QTD_RESULTADO" id="QTD_RESULTADO" value="1">

										<div class="push10"></div>

									</fieldset>

									<?php

									if ($tip_campanha == 22) {

										$sqlIndica = "SELECT COUNT(COD_CONTROLE) AS QTD_INDICA FROM INDICA_CLIENTE_CAMPANHA WHERE COD_CAMPANHA = $cod_campanha";
										$queryIndica = mysqli_query(connTemp($cod_empresa, ''), $sqlIndica);

										if ($qrbuscaIndica = mysqli_fetch_assoc($queryIndica)) {
											$qtd_indica = $qrbuscaIndica['QTD_INDICA'];
										} else {
											$qtd_indica = 0;
										}

										if ($qtd_indica != "") {
											$txtBntExtra6 = "Editar";
											$icoBntExtra6 = "fa-pencil";
											$temRegra = 'S';
											$addbox = "addBox";
											$habilitaIndicacao = "";
										}

									?>
								</div>
								<div class="col-md-3">

									<div id="div_refreshIndica">
										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-handshake" style="font-size: 30px;"></span>
											</div>
											<div class="widget-data">
												<div class="widget-title">Extra por Indicação de Clientes</div>
												<div class="widget-int"><?php echo number_format($qtd_indica, 0, ",", "."); ?></div>
												<div class="widget-title" style="font-weight: 400; font-size: 14px;">Indicação de clientes </div>
												<div class="widget-subtitle">
													<div class="push20"></div>
													<div class="push5"></div>
													<?php //módulo antigo 1339 
													?>
													<a class="btn btn-primary btn-sm <?= $addbox ?> btn-indicad" style="padding: 0 4px 0 4px; float: right; margin-right: 15px; <?= $habilitaIndicacao ?>" data-url="action.php?mod=<?php echo fnEncode(2075) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Indicação de Cliente"><i class="fa <?php echo $icoBntExtra6; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra6; ?></a>
													<div class="push5"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<input type="hidden" class="input-sm" name="REFRESH_INDICA" id="REFRESH_INDICA" value="N">
							<?php
									}

									//fim bloco campanha cash back	
								}

								//se bloco de pontos
								else if ($tip_campanha == 12) {
							?>
							<div class="col-md-6">
								<fieldset>
									<legend><?php echo $label_1; ?></legend>

									<div class="row">

										<div class="col-md-12">

											<?php echo $label_2; ?>

										</div>

									</div>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Qtd.</label>
												<input type="text" class="form-control text-center calcula input-sm int" name="QTD_VANTAGEM" id="QTD_VANTAGEM" maxlength="3" value="<?php echo $qtd_vantagem; ?>" data-error="Campo obrigatório" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-8">
											<div class="form-group">
												<label for="inputName" class="control-label required">&nbsp;</label>
												<select data-placeholder="Selecione o tipo da vantagem" name="COD_VANTAGE" id="COD_VANTAGE" class="chosen-select-deselect calcula requiredChk" required>
													<option value="">&nbsp;</option>
													<?php
													$sql = "select  COD_VANTAGE, DES_VANTAGE, LOG_ATIVO from tipovantagem ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													while ($qrListaVantagem = mysqli_fetch_assoc($arrayQuery)) {
														if ($qrListaVantagem['LOG_ATIVO'] == "S") {
															$checado = " ";
														} else {
															$checado = "disabled";
														}
														echo "
																						<option value='" . $qrListaVantagem['COD_VANTAGE'] . "' " . $checado . ">" . $qrListaVantagem['DES_VANTAGE'] . "</option> 
																						";
													}
													?>
												</select>
												<script>
													$("#formulario #COD_VANTAGE").val("<?php echo $cod_vantage; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required"><?php echo $abv_tpcampa; ?></label>
												<input type="text" class="form-control text-center input-sm calcula money" name="QTD_RESULTADO" id="QTD_RESULTADO" value="<?php echo fnValor($qtd_resultado, 2); ?>" data-error="Campo obrigatório" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="push20"></div>
										<div class="push10"></div>

									</div>

								</fieldset>

							</div>

							<div class="col-md-6">
								<div class="push10"></div>
								<div class="tile-stats">
									<div class="icon" style="font-size: 40px;"><i class="fa fa-comments-o"></i>
									</div>
									<div class="count" style="font-size: 21px; margin-top: 10px;">Resumo da Pontuação</div>
									<div class="push10"></div>
									<h3 style="font-size: 20px;">
										A cada <b> R$ <span id="divQtd"><?php echo fnValor($qtd_vantagem, 2); ?></span> </b> reais em vendas
										<div class="push5"></div>
										serão distribuidos <b> <span id="divPontos"><?php echo fnValor($qtd_resultado, 0); ?></span> pontos </b> e
										<div class="push5"></div>
										cada ponto valerá <b> R$ <span class="money"><?php echo $custoReal; ?></span> </b>
									</h3>
									<div class="push20"></div>
								</div>
							</div>

							<input type="hidden" name="PCT_VANTAGEM" id="PCT_VANTAGEM" value="<?php echo $pct_vantagem; ?>">

						<?php
								}

								//se bloco de numero da sorte
								else if ($tip_campanha == 20) {
						?>

							<div class="col-md-7">

								<div class="push10"></div>

								<div class="row">
									<div class="col-md-12">
										<fieldset>
											<legend>Campanha Principal - <?php echo $label_1; ?></legend>
											<div class="push10"></div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Unificar Campanhas Diarias</label>
													<div class="push5"></div>
													<label class="switch switch-small">
														<input type="checkbox" name="LOG_UNIFICA" id="LOG_UNIFICA" class="switch" value="S" <?php echo $checaUnifica; ?>>
														<span></span>
													</label>
												</div>
											</div>

											<div class="col-md-3">
												<a class="btn btn-default addBox" href="javascript:void(0)" data-url="action.php?mod=<?php echo fnEncode(1923) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Cupom - Categorias Específicas">
													<?php if ($temCatEsp > 0) { ?>
														<div class="notify-badge text-center pos">
															<?= $temCatEsp ?>
														</div>
													<?php } ?>
													<i class="fal fa-2x fa-bullseye-pointer"></i>&nbsp;
													<div class="push3"></div> Categorias específicas <div class="push"></div>a <b class="text-danger">não pontuar</b>
												</a>
											</div>

											<div class="push20"></div>


											<div class="col-md-12">

												<?php echo $label_2; ?>

											</div>

											<div class="push10"></div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label required">A cada</label>
													<input type="text" class="form-control text-center calcula input-sm money" name="PCT_VANTAGEM" id="PCT_VANTAGEM" maxlength="6" value="<?php echo $pct_vantagem; ?>" data-error="Campo obrigatório" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-5">
												<div class="form-group">
													<label for="inputName" class="control-label required">&nbsp;</label>
													<select data-placeholder="Selecione o tipo da vantagem" name="COD_VANTAGE" id="COD_VANTAGE" class="chosen-select-deselect calcula requiredChk" required>
														<option value="">&nbsp;</option>
														<?php
														$sql = "select  COD_VANTAGE, DES_VANTAGE, LOG_ATIVO from tipovantagem ";
														$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

														while ($qrListaVantagem = mysqli_fetch_assoc($arrayQuery)) {
															if ($qrListaVantagem['COD_VANTAGE'] == 1) {
																$checado = " ";
															} else {
																$checado = "disabled";
															}
															echo "
																							<option value='" . $qrListaVantagem['COD_VANTAGE'] . "' " . $checado . ">" . $qrListaVantagem['DES_VANTAGE'] . "</option> 
																							";
														}
														?>
													</select>
													<script>
														$("#formulario #COD_VANTAGE").val("<?php echo $cod_vantage; ?>").trigger("chosen:updated");
													</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="inputName" class="control-label required">Cupons Ganhos</label>
													<input type="text" class="form-control text-center input-sm int" name="QTD_RESULTADO" id="QTD_RESULTADO" value="<?php echo fnValor($qtd_resultado, 0); ?>" data-error="Campo obrigatório" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="push20"></div>



											<div class="col-md-6">
												<div class="form-group">
													<h5>Cupons em Dobro <small>(sobre valor total da venda)</small></h5>
												</div>
											</div>



											<div class="push5"></div>

											<div class="flexrow">

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Domingo</label>
														<select name="CPS_EXTRA_DOM" id="CPS_EXTRA_DOM" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTRA_DOM").val("<?php echo $cps_extra_dom; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Segunda</label>
														<select name="CPS_EXTRA_SEG" id="CPS_EXTRA_SEG" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTRA_SEG").val("<?php echo $cps_extra_seg; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Terça</label>
														<select name="CPS_EXTRA_TER" id="CPS_EXTRA_TER" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTRA_TER").val("<?php echo $cps_extra_ter; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Quarta</label>
														<select name="CPS_EXTRA_QUA" id="CPS_EXTRA_QUA" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTRA_QUA").val("<?php echo $cps_extra_qua; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Quinta</label>
														<select name="CPS_EXTRA_QUI" id="CPS_EXTRA_QUI" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTRA_QUI").val("<?php echo $cps_extra_qui; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Sexta</label>
														<select name="CPS_EXTRA_SEX" id="CPS_EXTRA_SEX" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTRA_SEX").val("<?php echo $cps_extra_sex; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col-md-2" id="SABADO">
													<div class="form-group">
														<label for="inputName" class="control-label">Sábado</label>
														<select name="CPS_EXTRA_SAB" id="CPS_EXTRA_SAB" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTRA_SAB").val("<?php echo $cps_extra_sab; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

											</div>

											<div class="push50"></div>

										</fieldset>

									</div>
								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-12">
										<fieldset>
											<legend>Campanha Principal - Cupons Extras para Bonificador </legend>
											<div class="push10"></div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Bonificar Indicador</label>
													<div class="push5"></div>
													<label class="switch">
														<input type="checkbox" name="LOG_INDICADOR" id="LOG_INDICADOR" class="switch" value="S" <?php echo $checaIndicador; ?>>
														<span></span>
													</label>
												</div>
											</div>

											<div class="push10"></div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label">A cada</label>
													<input type="text" class="form-control text-center calcula input-sm money" name="PCT_VANTAGEM_IND" id="PCT_VANTAGEM_IND" maxlength="5" value="<?php echo $pct_vantagem_ind; ?>" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-5">
												<div class="form-group">
													<label for="inputName" class="control-label">&nbsp;</label>
													<select data-placeholder="Selecione o tipo da vantagem" name="COD_VANTAGEM_IND" id="COD_VANTAGEM_IND" class="chosen-select-deselect">
														<option value="">&nbsp;</option>
														<?php
														$sql = "select  COD_VANTAGE, DES_VANTAGE, LOG_ATIVO from tipovantagem ";
														$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

														while ($qrListaVantagem = mysqli_fetch_assoc($arrayQuery)) {
															if ($qrListaVantagem['COD_VANTAGE'] == 1) {
																$checado = " ";
															} else {
																$checado = "disabled";
															}
															echo "
																							<option value='" . $qrListaVantagem['COD_VANTAGE'] . "' " . $checado . ">" . $qrListaVantagem['DES_VANTAGE'] . "</option> 
																							";
														}
														?>
													</select>
													<script>
														$("#formulario #COD_VANTAGEM_IND").val("<?php echo $cod_vantagem_ind; ?>").trigger("chosen:updated");
													</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="inputName" class="control-label">Cupons Ganhos</label>
													<input type="text" class="form-control text-center input-sm int" name="QTD_RESULTADO_IND" id="QTD_RESULTADO_IND" value="<?php echo fnValor($qtd_resultado_ind, 0); ?>" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="push20"></div>

											<div class="col-md-6">
												<h5>Cupons em Dobro para Bonificador Indicador <small>(sobre valor total da venda)</small></h5>
											</div>

											<div class="push5"></div>

											<div class="flexrow">

												<div class="col-md-1">
													<div class="form-group">
														<label for="inputName" class="control-label">Domingo</label>
														<select name="CPS_EXTIND_DOM" id="CPS_EXTIND_DOM" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTIND_DOM").val("<?php echo $cps_extind_dom; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Segunda</label>
														<select name="CPS_EXTIND_SEG" id="CPS_EXTIND_SEG" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTIND_SEG").val("<?php echo $cps_extind_seg; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Terça</label>
														<select name="CPS_EXTIND_TER" id="CPS_EXTIND_TER" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTIND_TER").val("<?php echo $cps_extind_ter; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Quarta</label>
														<select name="CPS_EXTIND_QUA" id="CPS_EXTIND_QUA" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTIND_QUA").val("<?php echo $cps_extind_qua; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Quinta</label>
														<select name="CPS_EXTIND_QUI" id="CPS_EXTIND_QUI" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTIND_QUI").val("<?php echo $cps_extind_qui; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col">
													<div class="form-group">
														<label for="inputName" class="control-label">Sexta</label>
														<select name="CPS_EXTIND_SEX" id="CPS_EXTIND_SEX" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTIND_SEX").val("<?php echo $cps_extind_sex; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col-md-2" id="SABADO2">
													<div class="form-group">
														<label for="inputName" class="control-label">Sábado</label>
														<select name="CPS_EXTIND_SAB" id="CPS_EXTIND_SAB" data-placeholder="Qtd. Cupons" class="chosen-select-deselect">
															<option value=""></option>
															<option value="1">1</option>
															<option value="2">2</option>
														</select>
														<script>
															$("#formulario #CPS_EXTIND_SAB").val("<?php echo $cps_extind_sab; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>

											</div>

											<div class="push50"></div>

										</fieldset>
									</div>
								</div>
							</div>

							<div class="col-md-5">

								<div class="push10"></div>

								<div class="row">


									<fieldset>
										<legend>Cupons Extras</legend>

										<div class="push30"></div>
										<div class="col-md-6 col-xs-12">

											<?php
											$sqlprodespecifico = "SELECT COUNT(*) AS CONTADOR
																			FROM cupomproduto A
																			WHERE A.COD_EMPRESA = $cod_empresa
																			AND A.COD_CAMPANHA = $cod_campanha
																			AND A.COD_EXCLUSA =0
																			ORDER BY A.COD_CUPOMFAIXA";

											$sqlprodresultado = mysqli_query(connTemp($cod_empresa, ''), $sqlprodespecifico);
											$prodretorno = mysqli_fetch_assoc($sqlprodresultado);

											$updateproduto = "UPDATE campanharegra SET QTD_CUPOMPROD = $prodretorno[CONTADOR]
																			WHERE COD_CAMPANHA= $cod_campanha";

											mysqli_query(connTemp($cod_empresa, ''), $updateproduto);

											//fnEscreve($updateproduto);

											//fnEscreve($sqlprodespecifico);
											//fnEscreve($prodretorno['CONTADOR']);

											?>
											<div id="div_refreshProd">
												<div class="widget widget-default widget-item-icon">
													<div class="widget-item-left">
														<span class="fal fa-box-full"></span>
													</div>
													<div class="widget-data">
														<div class="display2" style="font-size:18px">Produtos Específicos</div>
														<div class="widget-int" style="font-size:26px"><?php echo $prodretorno['CONTADOR'] ?></div>
														<div class="display4" style="font-weight: 400; font-size: 15px;">Produtos cadastrados</div>
														<div class="widget-subtitle">
															<div class="push20"></div>
															<div class="push5"></div>
															<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1766) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Cupom - Produtos Específicos"><i class="fa <?php echo $icoBntExtra3; ?>" aria-hidden="true"></i>&nbsp; Cadastrar</a>
															<div class="push5"></div>
														</div>
													</div>
												</div>
											</div>

										</div>
										<div class="col-md-6 col-xs-12">

											<?php
											$sqlcategoria = "SELECT COUNT(*) AS CONTADOR
																			FROM CUPOMCATEGORIAPRODUTO A
																			WHERE A.COD_EMPRESA = $cod_empresa AND A.COD_CAMPANHA = $cod_campanha 
																			AND A.COD_EXCLUSA = 0
																			ORDER BY A.COD_CATEGORIA";

											$categoriaresultado = mysqli_query(connTemp($cod_empresa, ''), $sqlcategoria);
											$querycategoria = mysqli_fetch_assoc($categoriaresultado);

											$updatecategoria = "UPDATE campanharegra SET QTD_CUPOMCATEG = $querycategoria[CONTADOR]
																			WHERE COD_CAMPANHA= $cod_campanha";

											mysqli_query(connTemp($cod_empresa, ''), $updatecategoria);
											//fnEscreve($updatecategoria);

											?>

											<div id="div_refreshProd">
												<div class="widget widget-default widget-item-icon">
													<div class="widget-item-left">
														<span class="fal fa-bullseye-arrow"></span>
													</div>
													<div class="widget-data">
														<div class="display2" style="font-size:18px">Produtos Categoria</div>
														<div class="widget-int " style="font-size:26px"><?php echo $querycategoria['CONTADOR'] ?></div>
														<div class="display4" style="font-weight: 400; font-size: 15px;">Produtos cadastrados</div>
														<div class="widget-subtitle">
															<div class="push20"></div>
															<div class="push5"></div>
															<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1765) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Cupom - Produtos Categoria"><i class="fa <?php echo $icoBntExtra3; ?>" aria-hidden="true"></i>&nbsp; Cadastrar</a>
															<div class="push5"></div>
														</div>
													</div>
												</div>
											</div>

										</div>
										<?php
										$sqlvalor = "SELECT COUNT(*) AS CONTADOR
																		FROM CUPOMFAIXAVALOR A                                                                                           
																		WHERE A.COD_EMPRESA = $cod_empresa
																		AND A.COD_CAMPANHA = $cod_campanha
																		AND A.COD_EXCLUSA = 0
																		ORDER BY A.COD_VALOR";

										$sqlvalorResultado = mysqli_query(connTemp($cod_empresa, ''), $sqlvalor);

										$valorRetorno = mysqli_fetch_assoc($sqlvalorResultado);
										$updatefaixavalor = "UPDATE campanharegra SET QTD_CUPOMFAIXA = $valorRetorno[CONTADOR]
																		WHERE COD_CAMPANHA= $cod_campanha";

										mysqli_query(connTemp($cod_empresa, ''), $updatefaixavalor);
										//fnEscreve($updatefaixavalor);
										?>

										<div class="col-md-6 col-xs-12">

											<div id="div_refreshProd">
												<div class="widget widget-default widget-item-icon">
													<div class="widget-item-left">
														<span class="fal fa-chart-bar"></span>
													</div>
													<div class="widget-data">
														<div class="display2" style="font-size:18px">Faixa de Valor</div>
														<div class="widget-int" style="font-size:26px"><?php echo $valorRetorno['CONTADOR'] ?></div>
														<div class="display4" style="font-weight: 400; font-size: 15px;">Produtos cadastrados</div>
														<div class="widget-subtitle">
															<div class="push20"></div>
															<div class="push5"></div>
															<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1767) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Cupom - Faixa de Valor"><i class="fa <?php echo $icoBntExtra3; ?>" aria-hidden="true"></i>&nbsp; Cadastrar</a>
															<div class="push5"></div>
														</div>
													</div>
												</div>
											</div>

										</div>

										<?php
										$sqlpagamento = "SELECT COUNT(*) AS CONTADOR FROM cupomextraformapa A WHERE A.COD_EMPRESA = $cod_empresa AND A.COD_CAMPANHA = $cod_campanha AND (A.COD_EXCLUSA IS NULL OR A.COD_EXCLUSA = 0)";

										$sqlpagamentoquery = mysqli_query(connTemp($cod_empresa, ''), $sqlpagamento);

										$querypagamento = mysqli_fetch_assoc($sqlpagamentoquery);
										$updatepagamento = "UPDATE campanharegra SET QTD_CUPOMFORM = $querypagamento[CONTADOR]
																		WHERE COD_CAMPANHA= $cod_campanha";

										mysqli_query(connTemp($cod_empresa, ''), $updatepagamento);

										?>

										<div class="col-md-6 col-xs-12">

											<div id="div_refreshProd">
												<div class="widget widget-default widget-item-icon">
													<div class="widget-item-left">
														<span class="fal fa-credit-card" style="font-size: 40px"></span>
													</div>
													<div class="widget-data">
														<div class="display2" style="font-size:18px">Pagamento</div>
														<div class="widget-int" style="font-size:26px"><?= $querypagamento['CONTADOR'] ?></div>
														<div class="display4" style="font-weight: 400; font-size: 15px;">Produtos cadastrados</div>
														<div class="widget-subtitle">
															<div class="push20"></div>
															<div class="push5"></div>
															<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1768) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Cupom - Pagamento"><i class="fa <?php echo $icoBntExtra3; ?>" aria-hidden="true"></i>&nbsp; Cadastrar</a>
															<div class="push5"></div>
														</div>
													</div>
												</div>
											</div>

										</div>

										<div class="col-md-6 col-xs-12">
											<?php
											$sqlfornecedor = "SELECT COUNT(*) AS CONTADOR FROM cupomfornecedor A WHERE A.COD_EMPRESA = $cod_empresa AND A.COD_CAMPANHA = $cod_campanha AND (A.COD_EXCLUSA IS NULL OR A.COD_EXCLUSA = 0)";

											$sqlfornecedorquery = mysqli_query(connTemp($cod_empresa, ''), $sqlfornecedor);

											$queryfornecedor = mysqli_fetch_assoc($sqlfornecedorquery);
											$updatefornecedor = "UPDATE campanharegra SET QTD_CUPOMFORNE = $queryfornecedor[CONTADOR]
																		WHERE COD_CAMPANHA= $cod_campanha";

											mysqli_query(connTemp($cod_empresa, ''), $updatefornecedor);

											?>

											<div id="div_refreshProd">
												<div class="widget widget-default widget-item-icon">
													<div class="widget-item-left">
														<span class="fal fa-people-carry" style="font-size: 40px"></span>
													</div>
													<div class="widget-data">
														<div class="display2" style="font-size:18px">Fornecedor</div>
														<div class="widget-int" style="font-size:26px"><?= $queryfornecedor['CONTADOR'] ?></div>
														<div class="display4" style="font-weight: 400; font-size: 15px;">Fornecedores cadastrados</div>
														<div class="widget-subtitle">
															<div class="push20"></div>
															<div class="push5"></div>
															<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1772) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Cupom - Fornecedor"><i class="fa <?php echo $icoBntExtra3; ?>" aria-hidden="true"></i>&nbsp; Cadastrar</a>
															<div class="push5"></div>
														</div>
													</div>
												</div>
											</div>

										</div>

									</fieldset>

								</div>
							</div>

							<!-- bloco de sorteio / cupons  -->
							<input type="hidden" name="QTD_VANTAGEM" id="QTD_VANTAGEM" value="1">


						<?php
								}
								//campos default obrigatórios - bloco créditos
								else {
						?>

							<!-- bloco de sorteio / cupons  -->
							<input type="hidden" name="CPS_EXTRA_DOM" id="CPS_EXTRA_DOM" value="0">
							<input type="hidden" name="CPS_EXTRA_SEG" id="CPS_EXTRA_SEG" value="0">
							<input type="hidden" name="CPS_EXTRA_TER" id="CPS_EXTRA_TER" value="0">
							<input type="hidden" name="CPS_EXTRA_QUA" id="CPS_EXTRA_QUA" value="0">
							<input type="hidden" name="CPS_EXTRA_QUI" id="CPS_EXTRA_QUI" value="0">
							<input type="hidden" name="CPS_EXTRA_SEX" id="CPS_EXTRA_SEX" value="0">
							<input type="hidden" name="CPS_EXTRA_SAB" id="CPS_EXTRA_SAB" value="0">
							<input type="hidden" name="CPS_EXTIND_DOM" id="CPS_EXTIND_DOM" value="0">
							<input type="hidden" name="CPS_EXTIND_SEG" id="CPS_EXTIND_SEG" value="0">
							<input type="hidden" name="CPS_EXTIND_TER" id="CPS_EXTIND_TER" value="0">
							<input type="hidden" name="CPS_EXTIND_QUA" id="CPS_EXTIND_QUA" value="0">
							<input type="hidden" name="CPS_EXTIND_QUI" id="CPS_EXTIND_QUI" value="0">
							<input type="hidden" name="CPS_EXTIND_SEX" id="CPS_EXTIND_SEX" value="0">
							<input type="hidden" name="CPS_EXTIND_SAB" id="CPS_EXTIND_SAB" value="0">

							<input type="hidden" name="QTD_VANTAGEM" id="QTD_VANTAGEM" value="1">
							<input type="hidden" name="COD_VANTAGE" id="COD_VANTAGE" value="1">
							<input type="hidden" name="QTD_RESULTADO" id="QTD_RESULTADO" value="1">

						<?php
									//fim numero da sorte 	
								}
						?>


						</div>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp; Atualizar Campanha</button>


						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="TEMREGRA" id="TEMREGRA" value="<?= $temRegra ?>">
						<input type="hidden" name="ATUALIZA_TELA" id="ATUALIZA_TELA" value="N">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="TIP_CAMPANHA" id="TIP_CAMPANHA" value="<?php echo $tip_campanha; ?>">
						<input type="hidden" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

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

<script type="text/javascript">
	$(document).ready(function() {

		$('.btn-indicad').on('click', function(event) {
			// Prevenir ação padrão inicialmente
			event.preventDefault();

			// Verificar valor de #TEMREGRA
			if ($('#TEMREGRA').val() == 'N') {
				$.confirm({
					title: 'Regra',
					type: 'red',
					content: '' +
						'<div>' +
						'<span>Campanha não configurada </br> Finalize a seleção da Persona</span>' +
						'</div>'
				});
			} else {
				event.currentTarget.click();
			}
		});


		if ('<?= $checaIndicador ?>' == '') {
			$('#PCT_VANTAGEM_IND,#QTD_RESULTADO_IND').prop('disabled', true).val("");
			$('#COD_VANTAGEM_IND').prop('disabled', true).val("").trigger("chosen:updated");
		}

		$('#LOG_INDICADOR').change(function() {
			if ($('#LOG_INDICADOR').is(':checked')) {
				$('#PCT_VANTAGEM_IND,#QTD_RESULTADO_IND').prop('disabled', false);
				$('#COD_VANTAGEM_IND').prop('disabled', false).trigger("chosen:updated");
			} else {
				$('#PCT_VANTAGEM_IND,#QTD_RESULTADO_IND').prop('disabled', true).val("");
				$('#COD_VANTAGEM_IND').prop('disabled', true).val("").trigger("chosen:updated");
				$('#CPS_EXTIND_DOM').val("").trigger("chosen:updated");
				$('#CPS_EXTIND_SEG').val("").trigger("chosen:updated");
				$('#CPS_EXTIND_TER').val("").trigger("chosen:updated");
				$('#CPS_EXTIND_QUA').val("").trigger("chosen:updated");
				$('#CPS_EXTIND_QUI').val("").trigger("chosen:updated");
				$('#CPS_EXTIND_SEX').val("").trigger("chosen:updated");
				$('#CPS_EXTIND_SAB').val("").trigger("chosen:updated");

			}
		});
		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			if ($('#REFRESH_INDICA').val() == "S") {
				RefreshIndica(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "IND");
				$('#REFRESH_INDICA').val("N");
			}
		});

		//retorno combo multiplo - lojas			

		var sistemasUni = "<?= $cod_univend ?>";
		var sistemasUniArr = sistemasUni.split(',');
		//opções multiplas
		for (var i = 0; i < sistemasUniArr.length; i++) {
			$("#formulario #COD_UNIVEND option[value=" + Number(sistemasUniArr[i]) + "]").prop("selected", "true");
		}
		$("#formulario #COD_UNIVEND").trigger("chosen:updated");


		calcularPorcentagem();

		$(".calcula").change(function() {
			calculaFator($(this).attr('id'));
		});

		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//máximo de personas por tipo de campanha
		$('#COD_PERSONA').chosen({
			max_selected_options: <?php echo $maxPersona; ?>
		});

		$("input[id^='QTD_VANTAGEM_'], input[id^='QTD_RESULTADO']").on('change', function() {
			calcularPorcentagem();
		});

		$('.modal').on('hidden.bs.modal', function() {
			if ($('#ATUALIZA_TELA').val() == "S") {
				location.reload(true);
			}
		});
	});

	$("#LOG_PRODUTO").change(function() {
		if (!$(this).prop('checked')) {
			$('#LOG_CATPROD').prop('checked', false);
			$('#PCT_VANTAGEM').prop('disabled', true).val("");
		} else {
			$('#PCT_VANTAGEM').prop('disabled', false);
		}
	});

	$("#LOG_CATPROD").change(function(e) {
		if (!$("#LOG_PRODUTO").prop('checked')) {
			$("#LOG_CATPROD").prop('checked', false);
			$.alert({
				title: "Aviso.",
				content: "Para uso das categorias adicionais, <br/>habilite todos os produtos do catálogo.",
				buttons: {
					Ok: function() {

					}
				}
			});
		}
	});

	function RefreshIndica(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshIndica').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				console.log(data);
				$("#div_refreshIndica").html(data);
			},
			error: function() {
				$('#div_refreshIndica').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function calculaFator() {
		var valPctVant = 0;
		var valQtdVant = 0;
		var valQtdResu = 0;
		var valTotal = 0;

		if ($('#PCT_VANTAGEM').val() != '' && $('#QTD_VANTAGEM').val() != '' && $('#COD_VANTAGE').val() != '' && $('#QTD_RESULTADO').val() != '') {

			valPctVant = limpaValor($('#PCT_VANTAGEM').val());
			valQtdVant = limpaValor($('#QTD_VANTAGEM').val());
			valQtdResu = limpaValor($('#QTD_RESULTADO').val());

			if (valQtdVant != 0 && valQtdResu != 0) {
				valTotal = valQtdVant / valQtdResu;
			} else {
				valTotal = 0;
			}

			$('#divQtd').html(valQtdVant);
			$('#divPontos').html(valQtdResu);
			$('#divResult').html(valTotal);
			// $('#divResult').mask("#.##0,00", {reverse: true});
		}

	}


	function calcularPorcentagem() {
		var tipoCampanha = <?php echo $tip_campanha; ?>;


		// Igual a PONTOS
		if (tipoCampanha == 12) {

			var qtdVantagem = converterFloatValueToCalc($("#QTD_VANTAGEM").val());
			var qtdResultado = converterFloatValueToCalc($("#QTD_RESULTADO").val());

			$('#PCT_VANTAGEM').val(0);

			if (!isNaN(qtdVantagem) && !isNaN(qtdResultado)) {

				if (qtdResultado != 0 && qtdVantagem != 0) {

					var result = (100 * qtdResultado) / qtdVantagem;

					result = converterValorTela(result, 2);

					$('#PCT_VANTAGEM').val(result);
					calculaFator();

				}

			}

		}

	}

	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>