<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$pesquisou = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		// $arrayCampos = explode(";", $urltotem);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'BUS':

					$num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CELULAR']));
					$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));

					$pesquisou = 1;



					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					break;
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B, LOG_BLOQUEIAPJ FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
		$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];
		$bloqueiaPj = $qrBuscaEmpresa['LOG_BLOQUEIAPJ'];


		if ($qrBuscaEmpresa['TIP_RETORNO'] == 1) {
			$casasDec = 0;
		} else {
			$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
			$pref = "R$ ";
		}

		// echo($casasDec);
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sqlCamp = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa and DES_CAMPANHA = 'Mais Cash (Automática)'";

$arrayCamp = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);
$qrCamp = mysqli_fetch_assoc($arrayCamp);

$cod_campanha = $qrCamp["COD_CAMPANHA"];

$sql = "SELECT * FROM  USUARIOS
			WHERE LOG_ESTATUS='S' AND
				  COD_EMPRESA = $cod_empresa AND
				  COD_TPUSUARIO = 10  limit 1  ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$cod_univend = $_SESSION['SYS_COD_UNIVEND'];

$cod_univend = explode(",", $_SESSION['SYS_COD_UNIVEND']);

if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) {

	$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
				  WHERE COD_EMPRESA = $cod_empresa 
				  AND LOG_ESTATUS = 'S'
				  AND LOG_UNIPREF = 'S'
				  ORDER BY 1 ASC LIMIT 1";

	$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
	$qrLista = mysqli_fetch_assoc($arrayUn);

	$idlojaKey = $qrLista['COD_UNIVEND'];

	if ($idlojaKey == "") {

		$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
					  WHERE COD_EMPRESA = $cod_empresa 
					  AND LOG_ESTATUS = 'S'
					  ORDER BY 1 ASC LIMIT 1";

		$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
		$qrLista = mysqli_fetch_assoc($arrayUn);

		$idlojaKey = $qrLista['COD_UNIVEND'];
	}
} else if ($cod_univend[0] != "") {

	$idlojaKey = $cod_univend[0];
} else {

	$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
				  WHERE COD_EMPRESA = $cod_empresa 
				  AND LOG_ESTATUS = 'S' 
				  ORDER BY 1 ASC LIMIT 1";

	$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
	$qrLista = mysqli_fetch_assoc($arrayUn);

	$idlojaKey = $qrLista['COD_UNIVEND'];
}


$idmaquinaKey = 0;
$codvendedorKey = 0;
$nomevendedorKey = 0;

$urltotem = $log_usuario . ';'
	. $des_senhaus . ';'
	. $idlojaKey . ';'
	. $idmaquinaKey . ';'
	. $cod_empresa . ';'
	. $codvendedorKey . ';'
	. $nomevendedorKey;

// FNeSCREVE($urltotem);

//fnMostraForm();

$sqlPasso1 = "SELECT DES_SOBRE, DES_IMG_G, DES_IMG, DES_IMGMOB FROM MC_CONFIG WHERE COD_EMPRESA = $cod_empresa AND COD_PASSO = 1";
$array1 = mysqli_query(connTemp($cod_empresa, ''), $sqlPasso1);
$qrPasso1 = mysqli_fetch_assoc($array1);
// fnEscreve($qrPasso1);
$passo1 = $qrPasso1['DES_SOBRE'];
$img_g_1 = $qrPasso1['DES_IMG_G'];
$img_m_1 = $qrPasso1['DES_IMG'];
$img_p_1 = $qrPasso1['DES_IMGMOB'];


$sqlPasso2 = "SELECT DES_SOBRE, DES_IMG_G, DES_IMG, DES_IMGMOB FROM MC_CONFIG WHERE COD_EMPRESA = $cod_empresa AND COD_PASSO = 2";
$array2 = mysqli_query(connTemp($cod_empresa, ''), $sqlPasso2);
$qrPasso2 = mysqli_fetch_assoc($array2);

if (isset($qrPasso2['DES_SOBRE'])) {
	$passo2 = $qrPasso2['DES_SOBRE'];
} else {
	$passo2 = "";
}

if (isset($qrPasso2['DES_IMG_G'])) {
	$img_g_2 = $qrPasso2['DES_IMG_G'];
} else {
	$img_g_2 = "";
}

if (isset($qrPasso2['DES_IMG'])) {
	$img_m_2 = $qrPasso2['DES_IMG'];
} else {
	$img_m_2 = "";
}

if (isset($qrPasso2['DES_IMGMOB'])) {
	$img_p_2 = $qrPasso2['DES_IMGMOB'];
} else {
	$img_p_2 = "";
}



$sqlPasso3 = "SELECT DES_SOBRE, DES_IMG_G, DES_IMG, DES_IMGMOB FROM MC_CONFIG WHERE COD_EMPRESA = $cod_empresa AND COD_PASSO = 3";
$array3 = mysqli_query(connTemp($cod_empresa, ''), $sqlPasso3);
$qrPasso3 = mysqli_fetch_assoc($array3);

if (isset($qrPasso3['DES_SOBRE'])) {
	$passo3 = $qrPasso3['DES_SOBRE'];
} else {
	$passo3 = "";
}

if (isset($qrPasso3['DES_IMG_G'])) {
	$img_g_3 = $qrPasso3['DES_IMG_G'];
} else {
	$img_g_3 = "";
}

if (isset($qrPasso3['DES_IMG'])) {
	$img_m_3 = $qrPasso3['DES_IMG'];
} else {
	$img_m_3 = "";
}

if (isset($qrPasso3['DES_IMGMOB'])) {
	$img_p_3 = $qrPasso3['DES_IMGMOB'];
} else {
	$img_p_3 = "";
}



$sqlPasso4 = "SELECT DES_SOBRE, DES_IMG_G, DES_IMG, DES_IMGMOB FROM MC_CONFIG WHERE COD_EMPRESA = $cod_empresa AND COD_PASSO = 4";
$array4 = mysqli_query(connTemp($cod_empresa, ''), $sqlPasso4);
$qrPasso4 = mysqli_fetch_assoc($array4);

if (isset($qrPasso4['DES_SOBRE'])) {
	$passo4 = $qrPasso4['DES_SOBRE'];
} else {
	$passo4 = "";
}

if (isset($qrPasso4['DES_IMG_G'])) {
	$img_g_4 = $qrPasso4['DES_IMG_G'];
} else {
	$img_g_4 = "";
}

if (isset($qrPasso4['DES_IMG'])) {
	$img_m_4 = $qrPasso4['DES_IMG'];
} else {
	$img_m_4 = "";
}

if (isset($qrPasso4['DES_IMGMOB'])) {
	$img_p_4 = $qrPasso4['DES_IMGMOB'];
} else {
	$img_p_4 = "";
}



$sqlPasso5 = "SELECT DES_SOBRE, DES_IMG_G, DES_IMG, DES_IMGMOB FROM MC_CONFIG WHERE COD_EMPRESA = $cod_empresa AND COD_PASSO = 5";
$array5 = mysqli_query(connTemp($cod_empresa, ''), $sqlPasso5);
$qrPasso5 = mysqli_fetch_assoc($array5);

if (isset($qrPasso5['DES_SOBRE'])) {
	$passo5 = $qrPasso5['DES_SOBRE'];
} else {
	$passo5 = "";
}

if (isset($qrPasso5['DES_IMG_G'])) {
	$img_g_5 = $qrPasso5['DES_IMG_G'];
} else {
	$img_g_5 = "";
}

if (isset($qrPasso5['DES_IMG'])) {
	$img_m_5 = $qrPasso5['DES_IMG'];
} else {
	$img_m_5 = "";
}

if (isset($qrPasso5['DES_IMGMOB'])) {
	$img_p_5 = $qrPasso5['DES_IMGMOB'];
} else {
	$img_p_5 = "";
}



$sqlPasso6 = "SELECT DES_SOBRE, DES_IMG_G, DES_IMG, DES_IMGMOB FROM MC_CONFIG WHERE COD_EMPRESA = $cod_empresa AND COD_PASSO = 6";
$array6 = mysqli_query(connTemp($cod_empresa, ''), $sqlPasso6);
$qrPasso6 = mysqli_fetch_assoc($array6);

if (isset($qrPasso6['DES_SOBRE'])) {
	$passo6 = $qrPasso6['DES_SOBRE'];
} else {
	$passo6 = "";
}

if (isset($qrPasso6['DES_IMG_G'])) {
	$img_g_6 = $qrPasso6['DES_IMG_G'];
} else {
	$img_g_6 = "";
}

if (isset($qrPasso6['DES_IMG'])) {
	$img_m_6 = $qrPasso6['DES_IMG'];
} else {
	$img_m_6 = "";
}

if (isset($qrPasso6['DES_IMGMOB'])) {
	$img_p_6 = $qrPasso6['DES_IMGMOB'];
} else {
	$img_p_6 = "";
}


?>

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

	.margin-mob-dsk {
		margin-top: 30px;
	}

	.panel.with-nav-tabs .panel-heading {
		padding: 5px 5px 0 5px;
	}

	.panel.with-nav-tabs .nav-tabs {
		border-bottom: none;
	}

	.panel.with-nav-tabs .nav-justified {
		margin-bottom: -1px;
	}

	/********************************************************************/
	/*** PANEL DEFAULT ***/
	.with-nav-tabs.panel-default .nav-tabs>li>a,
	.with-nav-tabs.panel-default .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-default .nav-tabs>li>a:focus {
		color: #777;
	}

	.with-nav-tabs.panel-default .nav-tabs>.open>a,
	.with-nav-tabs.panel-default .nav-tabs>.open>a:hover,
	.with-nav-tabs.panel-default .nav-tabs>.open>a:focus,
	.with-nav-tabs.panel-default .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-default .nav-tabs>li>a:focus {
		color: #777;
		background-color: #ddd;
		border-color: transparent;
	}

	.with-nav-tabs.panel-default .nav-tabs>li.active>a,
	.with-nav-tabs.panel-default .nav-tabs>li.active>a:hover,
	.with-nav-tabs.panel-default .nav-tabs>li.active>a:focus {
		color: #555;
		background-color: #fff;
		border-color: #ddd;
		border-bottom-color: transparent;
	}

	.with-nav-tabs.panel-default .nav-tabs>li.dropdown .dropdown-menu {
		background-color: #f5f5f5;
		border-color: #ddd;
	}

	.with-nav-tabs.panel-default .nav-tabs>li.dropdown .dropdown-menu>li>a {
		color: #777;
	}

	.with-nav-tabs.panel-default .nav-tabs>li.dropdown .dropdown-menu>li>a:hover,
	.with-nav-tabs.panel-default .nav-tabs>li.dropdown .dropdown-menu>li>a:focus {
		background-color: #ddd;
	}

	.with-nav-tabs.panel-default .nav-tabs>li.dropdown .dropdown-menu>.active>a,
	.with-nav-tabs.panel-default .nav-tabs>li.dropdown .dropdown-menu>.active>a:hover,
	.with-nav-tabs.panel-default .nav-tabs>li.dropdown .dropdown-menu>.active>a:focus {
		color: #fff;
		background-color: #555;
	}

	/********************************************************************/
	/*** PANEL PRIMARY ***/
	.with-nav-tabs.panel-primary .nav-tabs>li>a,
	.with-nav-tabs.panel-primary .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-primary .nav-tabs>li>a:focus {
		color: #fff;
	}

	.with-nav-tabs.panel-primary .nav-tabs>.open>a,
	.with-nav-tabs.panel-primary .nav-tabs>.open>a:hover,
	.with-nav-tabs.panel-primary .nav-tabs>.open>a:focus,
	.with-nav-tabs.panel-primary .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-primary .nav-tabs>li>a:focus {
		color: #fff;
		background-color: #3071a9;
		border-color: transparent;
	}

	.with-nav-tabs.panel-primary .nav-tabs>li.active>a,
	.with-nav-tabs.panel-primary .nav-tabs>li.active>a:hover,
	.with-nav-tabs.panel-primary .nav-tabs>li.active>a:focus {
		color: #428bca;
		background-color: #fff;
		border-color: #428bca;
		border-bottom-color: transparent;
	}

	.with-nav-tabs.panel-primary .nav-tabs>li.dropdown .dropdown-menu {
		background-color: #428bca;
		border-color: #3071a9;
	}

	.with-nav-tabs.panel-primary .nav-tabs>li.dropdown .dropdown-menu>li>a {
		color: #fff;
	}

	.with-nav-tabs.panel-primary .nav-tabs>li.dropdown .dropdown-menu>li>a:hover,
	.with-nav-tabs.panel-primary .nav-tabs>li.dropdown .dropdown-menu>li>a:focus {
		background-color: #3071a9;
	}

	.with-nav-tabs.panel-primary .nav-tabs>li.dropdown .dropdown-menu>.active>a,
	.with-nav-tabs.panel-primary .nav-tabs>li.dropdown .dropdown-menu>.active>a:hover,
	.with-nav-tabs.panel-primary .nav-tabs>li.dropdown .dropdown-menu>.active>a:focus {
		background-color: #4a9fe9;
	}

	/********************************************************************/
	/*** PANEL SUCCESS ***/
	.with-nav-tabs.panel-success .nav-tabs>li>a,
	.with-nav-tabs.panel-success .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-success .nav-tabs>li>a:focus {
		color: #3c763d;
	}

	.with-nav-tabs.panel-success .nav-tabs>.open>a,
	.with-nav-tabs.panel-success .nav-tabs>.open>a:hover,
	.with-nav-tabs.panel-success .nav-tabs>.open>a:focus,
	.with-nav-tabs.panel-success .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-success .nav-tabs>li>a:focus {
		color: #3c763d;
		background-color: #d6e9c6;
		border-color: transparent;
	}

	.with-nav-tabs.panel-success .nav-tabs>li.active>a,
	.with-nav-tabs.panel-success .nav-tabs>li.active>a:hover,
	.with-nav-tabs.panel-success .nav-tabs>li.active>a:focus {
		color: #3c763d;
		background-color: #fff;
		border-color: #d6e9c6;
		border-bottom-color: transparent;
	}

	.with-nav-tabs.panel-success .nav-tabs>li.dropdown .dropdown-menu {
		background-color: #dff0d8;
		border-color: #d6e9c6;
	}

	.with-nav-tabs.panel-success .nav-tabs>li.dropdown .dropdown-menu>li>a {
		color: #3c763d;
	}

	.with-nav-tabs.panel-success .nav-tabs>li.dropdown .dropdown-menu>li>a:hover,
	.with-nav-tabs.panel-success .nav-tabs>li.dropdown .dropdown-menu>li>a:focus {
		background-color: #d6e9c6;
	}

	.with-nav-tabs.panel-success .nav-tabs>li.dropdown .dropdown-menu>.active>a,
	.with-nav-tabs.panel-success .nav-tabs>li.dropdown .dropdown-menu>.active>a:hover,
	.with-nav-tabs.panel-success .nav-tabs>li.dropdown .dropdown-menu>.active>a:focus {
		color: #fff;
		background-color: #3c763d;
	}

	/********************************************************************/
	/*** PANEL INFO ***/
	.with-nav-tabs.panel-info .nav-tabs>li>a,
	.with-nav-tabs.panel-info .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-info .nav-tabs>li>a:focus {
		color: #31708f;
	}

	.with-nav-tabs.panel-info .nav-tabs>.open>a,
	.with-nav-tabs.panel-info .nav-tabs>.open>a:hover,
	.with-nav-tabs.panel-info .nav-tabs>.open>a:focus,
	.with-nav-tabs.panel-info .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-info .nav-tabs>li>a:focus {
		color: #31708f;
		background-color: #bce8f1;
		border-color: transparent;
	}

	.with-nav-tabs.panel-info .nav-tabs>li.active>a,
	.with-nav-tabs.panel-info .nav-tabs>li.active>a:hover,
	.with-nav-tabs.panel-info .nav-tabs>li.active>a:focus {
		color: #31708f;
		background-color: #fff;
		border-color: #bce8f1;
		border-bottom-color: transparent;
	}

	.with-nav-tabs.panel-info .nav-tabs>li.dropdown .dropdown-menu {
		background-color: #d9edf7;
		border-color: #bce8f1;
	}

	.with-nav-tabs.panel-info .nav-tabs>li.dropdown .dropdown-menu>li>a {
		color: #31708f;
	}

	.with-nav-tabs.panel-info .nav-tabs>li.dropdown .dropdown-menu>li>a:hover,
	.with-nav-tabs.panel-info .nav-tabs>li.dropdown .dropdown-menu>li>a:focus {
		background-color: #bce8f1;
	}

	.with-nav-tabs.panel-info .nav-tabs>li.dropdown .dropdown-menu>.active>a,
	.with-nav-tabs.panel-info .nav-tabs>li.dropdown .dropdown-menu>.active>a:hover,
	.with-nav-tabs.panel-info .nav-tabs>li.dropdown .dropdown-menu>.active>a:focus {
		color: #fff;
		background-color: #31708f;
	}

	/********************************************************************/
	/*** PANEL WARNING ***/
	.with-nav-tabs.panel-warning .nav-tabs>li>a,
	.with-nav-tabs.panel-warning .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-warning .nav-tabs>li>a:focus {
		color: #8a6d3b;
	}

	.with-nav-tabs.panel-warning .nav-tabs>.open>a,
	.with-nav-tabs.panel-warning .nav-tabs>.open>a:hover,
	.with-nav-tabs.panel-warning .nav-tabs>.open>a:focus,
	.with-nav-tabs.panel-warning .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-warning .nav-tabs>li>a:focus {
		color: #8a6d3b;
		background-color: #faebcc;
		border-color: transparent;
	}

	.with-nav-tabs.panel-warning .nav-tabs>li.active>a,
	.with-nav-tabs.panel-warning .nav-tabs>li.active>a:hover,
	.with-nav-tabs.panel-warning .nav-tabs>li.active>a:focus {
		color: #8a6d3b;
		background-color: #fff;
		border-color: #faebcc;
		border-bottom-color: transparent;
	}

	.with-nav-tabs.panel-warning .nav-tabs>li.dropdown .dropdown-menu {
		background-color: #fcf8e3;
		border-color: #faebcc;
	}

	.with-nav-tabs.panel-warning .nav-tabs>li.dropdown .dropdown-menu>li>a {
		color: #8a6d3b;
	}

	.with-nav-tabs.panel-warning .nav-tabs>li.dropdown .dropdown-menu>li>a:hover,
	.with-nav-tabs.panel-warning .nav-tabs>li.dropdown .dropdown-menu>li>a:focus {
		background-color: #faebcc;
	}

	.with-nav-tabs.panel-warning .nav-tabs>li.dropdown .dropdown-menu>.active>a,
	.with-nav-tabs.panel-warning .nav-tabs>li.dropdown .dropdown-menu>.active>a:hover,
	.with-nav-tabs.panel-warning .nav-tabs>li.dropdown .dropdown-menu>.active>a:focus {
		color: #fff;
		background-color: #8a6d3b;
	}

	/********************************************************************/
	/*** PANEL DANGER ***/
	.with-nav-tabs.panel-danger .nav-tabs>li>a,
	.with-nav-tabs.panel-danger .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-danger .nav-tabs>li>a:focus {
		color: #a94442;
	}

	.with-nav-tabs.panel-danger .nav-tabs>.open>a,
	.with-nav-tabs.panel-danger .nav-tabs>.open>a:hover,
	.with-nav-tabs.panel-danger .nav-tabs>.open>a:focus,
	.with-nav-tabs.panel-danger .nav-tabs>li>a:hover,
	.with-nav-tabs.panel-danger .nav-tabs>li>a:focus {
		color: #a94442;
		background-color: #ebccd1;
		border-color: transparent;
	}

	.with-nav-tabs.panel-danger .nav-tabs>li.active>a,
	.with-nav-tabs.panel-danger .nav-tabs>li.active>a:hover,
	.with-nav-tabs.panel-danger .nav-tabs>li.active>a:focus {
		color: #a94442;
		background-color: #fff;
		border-color: #ebccd1;
		border-bottom-color: transparent;
	}

	.with-nav-tabs.panel-danger .nav-tabs>li.dropdown .dropdown-menu {
		background-color: #f2dede;
		/* bg color */
		border-color: #ebccd1;
		/* border color */
	}

	.with-nav-tabs.panel-danger .nav-tabs>li.dropdown .dropdown-menu>li>a {
		color: #a94442;
		/* normal text color */
	}

	.with-nav-tabs.panel-danger .nav-tabs>li.dropdown .dropdown-menu>li>a:hover,
	.with-nav-tabs.panel-danger .nav-tabs>li.dropdown .dropdown-menu>li>a:focus {
		background-color: #ebccd1;
		/* hover bg color */
	}

	.with-nav-tabs.panel-danger .nav-tabs>li.dropdown .dropdown-menu>.active>a,
	.with-nav-tabs.panel-danger .nav-tabs>li.dropdown .dropdown-menu>.active>a:hover,
	.with-nav-tabs.panel-danger .nav-tabs>li.dropdown .dropdown-menu>.active>a:focus {
		color: #fff;
		/* active text color */
		background-color: #a94442;
		/* active bg color */
	}

	.p-r-0 {
		padding-right: 0;
	}

	.p-l-0 {
		padding-left: 0;
	}

	.img-g {
		display: none;
	}

	.img-m {
		display: block;
	}

	.img-p {
		display: none;
	}

	@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: none;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}


	}

	/* (320x480) Smartphone, Portrait */
	@media only screen and (device-width: 320px) and (orientation: portrait) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: none;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}

	}

	/* (320x480) Smartphone, Landscape */
	@media only screen and (device-width: 480px) and (orientation: landscape) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: none;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}

	}

	/* (1024x768) iPad 1 & 2, Landscape */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: block;
		}

		.p-r-0 {
			padding-right: 15px;
		}

		.p-l-0 {
			padding-left: 0;

		}

	}

	/* (1280x800) Tablets, Portrait */
	@media only screen and (max-width: 800px) and (orientation : portrait) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: none;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}

	}

	/* (768x1024) iPad 1 & 2, Portrait */
	@media only screen and (max-width: 768px) and (orientation : portrait) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: none;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}

	}

	/* (2048x1536) iPad 3 and Desktops*/
	@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

		.img-g {
			display: block;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: none;
		}

		.p-r-0 {
			padding-right: 0;
		}

		.p-l-0 {
			padding-left: 0;
		}

	}

	@media only screen and (min-device-width: 1100px) and (orientation : portrait) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: block;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}

	}

	@media (max-height: 824px) and (max-width: 416px) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: none;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}

	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media (max-device-width: 737px) and (max-height: 416px) {

		.img-g {
			display: none;
		}

		.img-m {
			display: none;
		}

		.img-p {
			display: block;
		}

		.input-lg,
		.btn-lg {
			font-size: 20px;
			height: 53px;
		}

		.btn-lg {
			padding-top: 10px;
		}

		.portlet {
			margin: 0px;
		}

		.margin-mob-dsk {
			margin-top: 0px;
		}

		#roteiro {
			display: none;
		}

		.p-r-0 {
			padding-right: 15px;
			padding-left: 15px;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 15px;
			padding-right: 15px;
		}

		.p-0 {
			padding: 0;
		}

		.nav-tabs li {
			width: 100%;
		}

		.nav-tabs li:last-child {
			margin-bottom: 5px;
		}


	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="../images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
</div>

<div class="row">

	<div class="col-md12 margin-bottom-30 margin-mob-dsk">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<span class="text-primary">$ <?php echo $NomePg; ?> - <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1681";
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

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>" autocomplete="off">

						<div class="row">


							<div class="col-md-6 col-xs-12" id="roteiro">

								<div class="col-md-12" id="boxPasso1">

									<?php if ($img_g_1 != "") { ?>
										<img class="img-responsive img-g" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_g_1 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_m_1 != "") { ?>
										<img class="img-responsive img-m" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_m_1 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_p_1 != "") { ?>
										<img class="img-responsive img-p" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_p_1 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>


									<?= html_entity_decode($passo1) ?>

								</div>

								<div class="col-md-12" id="boxPasso2" style="display: none;">

									<?php if ($img_g_2 != "") { ?>
										<img class="img-responsive img-g" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_g_2 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_m_2 != "") { ?>
										<img class="img-responsive img-m" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_m_2 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_p_2 != "") { ?>
										<img class="img-responsive img-p" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_p_2 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>


									<?= html_entity_decode($passo2) ?>

								</div>

								<div class="col-md-12" id="boxPasso3" style="display: none;">

									<?php if ($img_g_3 != "") { ?>
										<img class="img-responsive img-g" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_g_3 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_m_3 != "") { ?>
										<img class="img-responsive img-m" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_m_3 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_p_3 != "") { ?>
										<img class="img-responsive img-p" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_p_3 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>


									<?= html_entity_decode($passo3) ?>

								</div>

								<div class="col-md-12" id="boxPasso4" style="display: none;">

									<?php if ($img_g_4 != "") { ?>
										<img class="img-responsive img-g" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_g_4 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_m_4 != "") { ?>
										<img class="img-responsive img-m" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_m_4 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_p_4 != "") { ?>
										<img class="img-responsive img-p" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_p_4 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>


									<?= html_entity_decode($passo4) ?>

								</div>

								<div class="col-md-12" id="boxPasso5" style="display: none;">

									<?php if ($img_g_5 != "") { ?>
										<img class="img-responsive img-g" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_g_5 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_m_5 != "") { ?>
										<img class="img-responsive img-m" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_m_5 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_p_5 != "") { ?>
										<img class="img-responsive img-p" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_p_5 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>


									<?= html_entity_decode($passo5) ?>

								</div>

								<div class="col-md-12" id="boxPasso6" style="display: none;">

									<?php if ($img_g_6 != "") { ?>
										<img class="img-responsive img-g" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_g_6 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_m_6 != "") { ?>
										<img class="img-responsive img-m" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_m_6 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>

									<?php if ($img_p_6 != "") { ?>
										<img class="img-responsive img-p" src="../media/clientes/<?= $cod_empresa ?>/<?= $img_p_6 ?>" width="100%" style="margin-left: auto; margin-right: auto; border-radius: 6px;">
									<?php } ?>


									<?= html_entity_decode($passo6) ?>

								</div>

							</div>

							<div class="col-md-6 col-xs-12 p-0">

								<?php

								$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

								$arrayCampos = mysqli_query($connAdm->connAdm(), $sqlCampos);

								// echo($sqlCampos);

								$lastField = "";

								$qrCampos = mysqli_fetch_assoc($arrayCampos);

								$field = [];
								$nom_campo = [];
								$mask = [];
								$placeholder = [];

								switch ($qrCampos['COD_CHAVECO']) {

									case 2:

										$field[0] = "Cartão";
										$nom_campo[0] = 'KEY_NUM_CARTAO';
										$mask[0] = 'int';
										$placeholder[0] = '123456';

										break;

									case 3:

										$field[0] = "Celular";
										$nom_campo[0] = 'KEY_NUM_CELULAR';
										$mask[0] = 'sp_celphones';
										$placeholder[0] = '(99) 99999 - 9999';

										break;

									case 4:

										$field[0] = "Código Externo";
										$nom_campo[0] = 'KEY_COD_EXTERNO';
										$mask[0] = 'int';
										$placeholder[0] = '123456';

										break;

									case 5:

										$label = "CPF/CNPJ";
										$charLenght = "18";

										if ($bloqueiaPj == 'S') {
											$label = "CPF";
											$charLenght = "14";
										}

										$field[0] = $label;
										$field[1] = "Cartão";
										$nom_campo[0] = 'KEY_NUM_CGCECPF';
										$nom_campo[1] = 'KEY_NUM_CARTAO';
										$mask[0] = 'cpfcnpj2';
										$mask[1] = 'int';
										$placeholder[0] = '123.456.789-12';
										$placeholder[1] = '123456';

										break;

									case 6:

										$label = "CPF/CNPJ";
										$charLenght = "18";

										if ($bloqueiaPj == 'S') {
											$label = "CPF";
											$charLenght = "14";
										}

										$field[0] = $label;
										$nom_campo[0] = 'KEY_NUM_CGCECPF';
										$mask[0] = 'cpfcnpj2';
										$placeholder[0] = '123.456.789-12';

										break;

									default:

										$label = "CPF/CNPJ";
										$charLenght = "18";

										if ($bloqueiaPj == 'S') {
											$label = "CPF";
											$charLenght = "14";
										}

										$field[0] = $label;
										$nom_campo[0] = 'KEY_NUM_CGCECPF';
										$mask[0] = 'cpfcnpj2';
										$placeholder[0] = '123.456.789-12';

										break;
								}

								?>

								<div class="col-md-12 p-0">
									<div class="panel with-nav-tabs panel-default" style="margin-bottom: 0px;">
										<div class="panel-heading">
											<ul class="nav nav-tabs">
												<li class="active"><a href="#tab1default" data-toggle="tab" class="limpaBuffer">Pesquisa por <?= $field[0] ?></a></li>
												<?php if ($qrCampos['COD_CHAVECO'] == 5) { ?>
													<li><a href="#tab2default" data-toggle="tab" class="limpaBuffer">Pesquisa por <?= $field[1] ?></a></li>
												<?php } ?>
											</ul>
										</div>
										<div class="panel-body">
											<div class="tab-content">

												<div class="tab-pane fade in active" id="tab1default">

													<div class="col-md-8 col-xs-12 text-left p-r-0">
														<div class="form-group">
															<!-- <label for="inputName" class="control-label required">&nbsp;</label> -->
															<input type="tel" placeholder="<?= $placeholder[0] ?>" name="<?= $nom_campo[0] ?>" id="<?= $nom_campo[0] ?>" readonly maxlength="50" class="form-control input-lg <?= $mask[0] ?> pesquisa foco" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-4 col-xs-12 p-l-0">
														<!-- <label>&nbsp;</label> -->
														<a name="BUS" id="BUS" style="width: 100%; border-radius: 0!important; color:#fff;" class="btn btn-info btn-lg" onclick='ajxCliente("BUS","")'>Pesquisar</a>
													</div>

												</div>

												<?php if ($qrCampos['COD_CHAVECO'] == 5) { ?>

													<div class="tab-pane fade" id="tab2default">

														<div class="col-md-8 col-xs-12 text-left p-r-0" style="padding-right: 0;">
															<div class="form-group">
																<!-- <label for="inputName" class="control-label required">&nbsp;</label> -->
																<input type="tel" placeholder="<?= $placeholder[1] ?>" name="<?= $nom_campo[1] ?>" id="<?= $nom_campo[1] ?>" readonly maxlength="50" class="form-control input-lg <?= $mask[1] ?> pesquisa" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-4 col-xs-12 p-l-0" style="padding-left: 0;">
															<!-- <label>&nbsp;</label> -->
															<a name="BUS" id="BUS" style="width: 100%; border-radius: 0!important; color:#fff;" class="btn btn-info btn-lg" onclick='ajxCliente("BUS","")'>Pesquisar</a>
														</div>

													</div>

												<?php } ?>

											</div>
										</div>
									</div>
								</div>

								<div class="push20"></div>

								<div id="relatorioNome"></div>

							</div>

						</div>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

						</div>

						<div id="anchor"></div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="PREF" id="PREF" value="<?= $pref ?>">
						<input type="hidden" name="COD_USUCADA" id="COD_USUCADA" value="<?= fnEncode($_SESSION['SYS_COD_USUARIO']) ?>">
						<input type="hidden" name="HID_TKNRESG" id="HID_TKNRESG" value="">
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="QTD_CHARTKN" id="QTD_CHARTKN" value="<?= $qtd_chartkn ?>">
						<input type="hidden" name="TIP_TOKEN" id="TIP_TOKEN" value="<?= $tip_token ?>">
						<input type="hidden" name="URL_TOTEM" id="URL_TOTEM" value="<?= fnEncode($urltotem) ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>




					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	let bloqueiaPj = "<?= $bloqueiaPj ?>";

	$(function() {

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$(".pesquisa").attr("readonly", false);
		$(".foco").focus();

		$(".limpaBuffer").click(function() {
			$("#NUM_CELULAR,#NUM_CGCECPF").val("");
		});

		if ($('.cpfcnpj').val() != undefined) {
			mascaraCpfCnpj2($('.cpfcnpj2'));
		}

		$(".pesquisa").focusout(function() {
			if ($(".pesquisa").val().trim() != "") {
				$("#BUS").click();
			}
		});

	});



	var SPMaskBehavior = function(val) {
			return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
			onKeyPress: function(val, e, field, options) {
				field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};

	function ajxCliente(opcao, tipo) {

		var div = "",
			id = "",
			msg = "",
			aut = 0;

		if (opcao == "BUS") {
			div = "relatorioNome";
		} else if (opcao == "TKNCAD") {
			div = "relatorioValidaToken";
		} else if (opcao == "TKNRSG") {
			div = "relatorioValidaResgate";
		} else if (opcao == "TIP") {
			div = "relatorioTipoVenda";
		} else if (opcao == "RES") {
			div = "relatorioResgate";
			$("#relatorioVenda").html("");
		} else if (opcao == "SRES") {
			div = "relatorioVenda";
			$("#relatorioResgate").html("");
		} else if (opcao == "VEN") {
			div = "relatorioVenda";
		} else if (opcao == "VALTKNCAD") {
			div = "relatorioVenda";
		} else if (opcao == "VALTKNRES") {
			div = "relatorioVenda";
		} else if (opcao == "PROD" || opcao == "EXCPROD") {
			div = "relatorioProdutos";
		} else {
			div = "relatorioPos";
			$("#blocker").show();
			$("#btnVenda").prop("disabled", true).addClass("disabled");
		}


		if ($(".pesquisa").val() == "") {

			$.alert({
				title: 'Atenção',
				content: "O campo não pode ser vazio.",
			});

		} else {

			aut = 1;

			if ($(".pesquisa").hasClass('sp_celphones') != "") {

				if ($(".sp_celphones").val().length != 15) {

					aut = 0;

					$.alert({
						title: 'Atenção!',
						content: "O celular informado é inválido!",
					});

				}

			}

			if ($(".pesquisa").hasClass('cpfcnpj')) {

				if (!valida_cpf_cnpj($('.cpfcnpj').val())) {

					aut = 0;

					$.alert({
						title: 'Atenção!',
						content: "O CPF/CNPJ informado é inválido!",
					});

				}

			}

		}

		if (opcao == 'PROD' && $("#VAL_UNITARIO").val().trim() == '' || opcao == 'PROD' && $("#VAL_UNITARIO").cleanVal() <= 0) {
			aut = 0;
			$.alert({
				title: 'Atenção!',
				content: "Valor da venda não informado.",
			});
		}

		if (aut == 1) {

			$.ajax({
				method: "POST",
				url: "../maiscash/ajxMCPesquisa.do?id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&opcao=" + opcao + "&tip=" + tipo,
				data: $("#formulario").serialize(),
				beforeSend: function() {
					$('#' + div).html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					console.log(data);
					$('#' + div).html(data);
					$("#boxPasso1,#boxPasso2,#boxPasso3,#boxPasso4,#boxPasso5,#boxPasso6").fadeOut(0, function() {
						if (opcao == "BUS") {
							$("#boxPasso2").fadeIn('fast');
						} else if (opcao == "TKNCAD") {
							$("#boxPasso3").fadeIn('fast');
						} else if (opcao == "TKNRSG") {
							$("#boxPasso5").fadeIn('fast');
						} else if (opcao == "RES") {
							$("#boxPasso4").fadeIn('fast');
						} else if (opcao == "SRES") {
							$("#boxPasso6").fadeIn('fast');
						}

						$("#blocker").hide();
					});
				}
			});

			$('html, body').animate({
				scrollTop: $("#anchor").offset().top
			}, 250);

		}

	}

	function mascaraCpfCnpj2(cpfCnpj) {
		var optionsCpfCnpj = {
			onKeyPress: function(cpf, ev, el, op) {
				if (bloqueiaPj != 'S') {
					var masks = ['000.000.000-000', '00.000.000/0000-00'],
						mask = (cpf.length >= 15) ? masks[1] : masks[0];
				} else {
					var mask = '000.000.000-00';
				}
				cpfCnpj.mask(mask, op);
			}
		}

		var masks = ['000.000.000-000', '00.000.000/0000-00'];
		mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];

		cpfCnpj.mask(mask, optionsCpfCnpj);
	}
</script>