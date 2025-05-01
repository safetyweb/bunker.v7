<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$pesquisou = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B  FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
		$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];


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

$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
			  WHERE COD_EMPRESA = $cod_empresa 
			  AND LOG_ESTATUS = 'S' 
			  ORDER BY 1 ASC LIMIT 1";

$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
$qrLista = mysqli_fetch_assoc($arrayUn);

$idlojaKey = $qrLista['COD_UNIVEND'];
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

if (isset($qrPasso1['DES_SOBRE'])) {
	$passo1 = $qrPasso1['DES_SOBRE'];
} else {
	$passo1 = "";
}

if (isset($qrPasso1['DES_IMG_G'])) {
	$img_g_1 = $qrPasso1['DES_IMG_G'];
} else {
	$img_g_1 = "";
}

if (isset($qrPasso1['DES_IMG'])) {
	$img_m_1 = $qrPasso1['DES_IMG'];
} else {
	$img_m_1 = "";
}

if (isset($qrPasso1['DES_IMGMOB'])) {
	$img_p_1 = $qrPasso1['DES_IMGMOB'];
} else {
	$img_p_1 = "";
}



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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

		.p-r-0 {
			padding-right: 0;
			padding-left: 0;
			margin-bottom: 10px;
		}

		.p-l-0 {
			padding-left: 0;
			padding-right: 0;
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

	.alert {
		padding: 15px;
		margin-bottom: 20px;
		border: 1px solid transparent;
		border-radius: 4px;
	}

	.alert h4 {
		margin-top: 0;
		color: inherit;
	}

	.alert .alert-link {
		font-weight: bold;
	}

	.alert>p,
	.alert>ul {
		margin-bottom: 0;
	}

	.alert>p+p {
		margin-top: 5px;
	}

	.alert-dismissable,
	.alert-dismissible {
		padding-right: 35px;
	}

	.alert-dismissable .close,
	.alert-dismissible .close {
		position: relative;
		top: -2px;
		right: -21px;
		color: inherit;
	}

	.alert-success {
		background-color: #d4edda;
		border-color: #c3e6cb;
		color: #155724;
		padding-top: 10px;
		padding-bottom: 10px;
	}

	.alert-success hr {
		border-top-color: #c3e6cb;
	}

	.alert-success .alert-link,
	.alert-success .close {
		color: #0b2e13;
	}

	.alert-danger {
		background-color: #f8d7da;
		border-color: #f5c6cb;
		color: #721c24;
		padding-top: 10px;
		padding-bottom: 10px;
	}

	.alert-danger hr {
		border-top-color: #f5c6cb;
	}

	.alert-danger .alert-link,
	.alert-danger .close {
		color: #491217;
	}

	.alert-info {
		background-color: #cce5ff;
		border-color: #b8daff;
		color: #004085;
		padding-top: 10px;
		padding-bottom: 10px;
	}

	.alert-info hr {
		border-top-color: #b8daff;
	}

	.alert-info .alert-link,
	.alert-info .close {
		color: #002752;
	}

	.alert-dark {
		background-color: #d6d8d9;
		border-color: #c6c8ca;
		color: #1b1e21;
		padding-top: 10px;
		padding-bottom: 10px;
	}

	.alert-dark hr {
		border-top-color: #c6c8ca;
	}

	.alert-dark .alert-link,
	.alert-dark .close {
		color: #040505;
	}

	.alert-warning {
		background-color: #fff3cd;
		border-color: #ffeeba;
		color: #856404;
		padding-top: 10px;
		padding-bottom: 10px;
	}

	.alert-warning hr {
		border-top-color: #ffeeba;
	}

	.alert-warning .alert-link,
	.alert-warning .close {
		color: #002752;
	}

	/* Tabs panel */
	.tabbable-panel {
		border: 0;
		padding: 10px;
	}

	/* Default mode */
	.tabbable-line>.nav-tabs {
		border: none;
		margin: 0px;
	}

	.tabbable-line>.nav-tabs>li {
		margin-right: 2px;
	}

	.tabbable-line>.nav-tabs>li>a {
		border: 0;
		margin-right: 0;
		color: #737373;
	}

	.tabbable-line>.nav-tabs>li>a>i {
		color: #a6a6a6;
	}

	.tabbable-line>.nav-tabs>li.open,
	.tabbable-line>.nav-tabs>li:hover {
		border-bottom: 4px solid #fbcdcf;
	}

	.tabbable-line>.nav-tabs>li.open>a,
	.tabbable-line>.nav-tabs>li:hover>a {
		border: 0;
		background: none !important;
		color: #333333;
	}

	.tabbable-line>.nav-tabs>li.open>a>i,
	.tabbable-line>.nav-tabs>li:hover>a>i {
		color: #a6a6a6;
	}

	.tabbable-line>.nav-tabs>li.open .dropdown-menu,
	.tabbable-line>.nav-tabs>li:hover .dropdown-menu {
		margin-top: 0px;
	}

	.tabbable-line>.nav-tabs>li.active {
		border-bottom: 4px solid #18bc9c;
		position: relative;
	}

	.tabbable-line>.nav-tabs>li.active>a {
		border: 0;
		color: #333333;
	}

	.tabbable-line>.nav-tabs>li.active>a>i {
		color: #404040;
	}

	.tabbable-line>.tab-content {
		margin-top: -3px;
		background-color: #fff;
		border: 0;
		border-top: 1px solid #eee;
		padding: 15px 0;
	}

	.portlet .tabbable-line>.tab-content {
		padding-bottom: 0;
	}

	/* Below tabs mode */

	.tabbable-line.tabs-below>.nav-tabs>li {
		border-top: 4px solid transparent;
	}

	.tabbable-line.tabs-below>.nav-tabs>li>a {
		margin-top: 0;
	}

	.tabbable-line.tabs-below>.nav-tabs>li:hover {
		border-bottom: 0;
		border-top: 4px solid #fbcdcf;
	}

	.tabbable-line.tabs-below>.nav-tabs>li.active {
		margin-bottom: -2px;
		border-bottom: 0;
		border-top: 4px solid #f3565d;
	}

	.tabbable-line.tabs-below>.tab-content {
		margin-top: -10px;
		border-top: 0;
		border-bottom: 1px solid #eee;
		padding-bottom: 15px;
	}
</style>


<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
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

				<div class="tabbable-line">

					<ul class="nav nav-tabs">
						<li class="active text-center" id="p1" onclick="mudaTab(this)">
							<a href="javascript:void(0)">
								Pesquisa de Cliente <br> <span class="f12">Passo 1</span></a>
						</li>

						<li class=" text-center" id="p2" onclick="mudaTab(this)">
							<a href="javascript:void(0)">
								Novo Cadastro<br> <span class="f12">Passo 2</span></a>
						</li>

						<li class=" text-center" id="p3" onclick="mudaTab(this)">
							<a href="javascript:void(0)">
								Token Cadastro<br> <span class="f12">Passo 3</span></a>
						</li>

						<li class=" text-center" id="p4" onclick="mudaTab(this)">
							<a href="javascript:void(0)">
								Venda com Resgate<br> <span class="f12">Passo 4</span></a>
						</li>

						<li class=" text-center" id="p5" onclick="mudaTab(this)">
							<a href="javascript:void(0)">
								Token Resgate<br> <span class="f12">Passo 5</span></a>
						</li>

						<li class=" text-center" id="p6" onclick="mudaTab(this)">
							<a href="javascript:void(0)">
								Venda sem Resgate<br> <span class="f12">Passo 6</span></a>
						</li>

					</ul>

				</div>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>" autocomplete="off">

						<div class="row">

							<div class="col-md-12">

								<div class="col-md-12 col-xs-12" id="tab_p1">
									<a class="btn btn-sm btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1684) ?>&id=<?php echo fnEncode($cod_empresa) ?>&step=1&pop=true" data-title="Mais Cash - Bloco de Pesquisa"><i class="fal fa-pen" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Editar Bloco de Pesquisa</a>
								</div>

								<div class="col-md-12 col-xs-12" id="tab_p2" style="display: none;">
									<a class="btn btn-sm btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1684) ?>&id=<?php echo fnEncode($cod_empresa) ?>&step=2&pop=true" data-title="Mais Cash - Bloco de Pesquisa"><i class="fal fa-pen" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Editar Bloco Novo Cadastro</a>
								</div>

								<div class="col-md-12 col-xs-12" id="tab_p3" style="display: none;">
									<a class="btn btn-sm btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1684) ?>&id=<?php echo fnEncode($cod_empresa) ?>&step=3&pop=true" data-title="Mais Cash - Bloco de Pesquisa"><i class="fal fa-pen" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Editar Bloco Token Cadastro</a>
								</div>

								<div class="col-md-12 col-xs-12" id="tab_p4" style="display: none;">
									<a class="btn btn-sm btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1684) ?>&id=<?php echo fnEncode($cod_empresa) ?>&step=4&pop=true" data-title="Mais Cash - Bloco de Pesquisa"><i class="fal fa-pen" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Editar Bloco Venda com Resgate</a>
								</div>

								<div class="col-md-12 col-xs-12" id="tab_p5" style="display: none;">
									<a class="btn btn-sm btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1684) ?>&id=<?php echo fnEncode($cod_empresa) ?>&step=5&pop=true" data-title="Mais Cash - Bloco de Pesquisa"><i class="fal fa-pen" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Editar Bloco Token Resgate</a>
								</div>

								<div class="col-md-12 col-xs-12" id="tab_p6" style="display: none;">
									<a class="btn btn-sm btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1684) ?>&id=<?php echo fnEncode($cod_empresa) ?>&step=6&pop=true" data-title="Mais Cash - Bloco de Pesquisa"><i class="fal fa-pen" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Editar Bloco Venda sem Resgate</a>
								</div>

								<div class="col-md-12 col-xs-12" id="helpTabs">

								</div>

								<div class="push20"></div>

							</div>

							<div class="col-md-6 col-xs-12">

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

								<div class="col-md-12 p-0 foco" id="relatorioPesquisa">

									<div class="panel with-nav-tabs panel-default" style="margin-bottom: 0px;">
										<div class="panel-heading">
											<ul class="nav nav-tabs">
												<li class="active"><a href="#tab1default" data-toggle="tab" class="limpaBuffer">Pesquisa por Celular</a></li>
												<li><a href="#tab2default" data-toggle="tab" class="limpaBuffer">Pesquisa por CPF/CNPJ</a></li>
											</ul>
										</div>
										<div class="panel-body">
											<div class="tab-content">

												<div class="tab-pane fade in active" id="tab1default">

													<div class="col-md-8 col-xs-12 text-left p-r-0">
														<div class="form-group">
															<!-- <label for="inputName" class="control-label required">&nbsp;</label> -->
															<input type="text" placeholder="(99) 99999 - 9999" name="NUM_CELULAR" id="NUM_CELULAR" readonly value="" maxlength="50" class="form-control input-lg sp_celphones" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-4 col-xs-12 p-l-0">
														<!-- <label>&nbsp;</label> -->
														<a href="javascript:void(0)" name="BUS" id="BUS" style="width: 100%; border-radius: 0!important; color:#fff;" class="btn btn-info btn-lg">Pesquisar</a>
													</div>

												</div>

												<div class="tab-pane fade" id="tab2default">

													<div class="col-md-8 col-xs-12 text-left p-r-0" style="padding-right: 0;">
														<div class="form-group">
															<!-- <label for="inputName" class="control-label required">&nbsp;</label> -->
															<input type="text" placeholder="123.456.789-12" name="NUM_CGCECPF" id="NUM_CGCECPF" readonly value="<?php echo $nom_usuario; ?>" maxlength="50" class="form-control input-lg cpfcnpj" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-4 col-xs-12 p-l-0" style="padding-left: 0;">
														<!-- <label>&nbsp;</label> -->
														<a href="javascript:void(0)" name="BUS" id="BUS" style="width: 100%; border-radius: 0!important; color:#fff;" class="btn btn-info btn-lg">Pesquisar</a>
													</div>

												</div>

											</div>
										</div>
									</div>
								</div>

								<div class="push20"></div>

								<div id="relatorioNome" style="display: none;"><!--  NOME ----------------------------------------------------------------------------------------------------------------------------------------------------- -->

									<div class="col-xs-12 text-left">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Celular</label> -->
											<input type="text" placeholder="(99) 99999 - 9999" name="CAD_NUM_CELULAR" id="CAD_NUM_CELULAR" value="" readonly maxlength="50" class="form-control input-lg sp_celphones" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-xs-12 text-left">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Celular</label> -->
											<input type="text" placeholder="123.456.789-12" name="CAD_NUM_CGCECPF" id="CAD_NUM_CGCECPF" value="" readonly maxlength="50" class="form-control input-lg cpfcnpj" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-8 col-xs-12 text-left p-r-0">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Nome do Cliente</label> -->
											<input type="text" placeholder="Nome" name="NOM_USUARIO" id="NOM_USUARIO" value="" readonly maxlength="50" class="form-control input-lg" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4 col-xs-12 p-l-0">
										<a href="javascript:void(0)" style="width: 100%; border-radius: 0!important; height:66px;" class="btn btn-success btn-lg f18">Enviar Token</a>
									</div>

									<div class="col-md-12 col-xs-12 text-left">

										<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; margin-top: 20px; height: 43px;">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

											<span class="pull-left">
												Saldo Acumulado:
											</span>
											<span class="pull-right" style="margin-right: 15px;">
												<small>R$</small><b style="font-size:16px;">9,99</b>
											</span>

										</div>

									</div>

									<div class="col-md-12 col-xs-12 text-left">

										<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; height: 43px;">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

											<span class="pull-left">
												Validade:
											</span>
											<span class="pull-right" style="margin-right: 15px; margin-top: -10px;">
												de <b style="font-size:14px;"><?= date("d/m/Y") ?></b>
												<div class="push"></div> a <b style="font-size:14px;"><?= date("d/m/Y") ?></b>
											</span>

										</div>

									</div>

									<div class="col-md-12 col-xs-12 text-left">

										<div class="alert alert-dark" role="alert" style="margin-bottom: 0px; height: 43px;">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

											<span class="pull-left">
												Regra:
											</span>
											<span class="pull-right" style="margin-right: 15px;">
												até <b style="font-size:16px;">99</b>% da compra
											</span>

										</div>

									</div>

									<div class="push20"></div>

								</div>

								<div id="relatorioToken" style="display: none;"><!--  TOKEN ----------------------------------------------------------------------------------------------------------------------------------------------------- -->

									<div class="col-md-8 col-xs-12 text-left p-r-0">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Token</label> -->
											<input type="text" placeholder="Token" name="DES_TOKEN" id="DES_TOKEN" value="" maxlength="99" class="form-control input-lg" readonly="" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4 col-xs-12 p-l-0">
										<!-- <label>&nbsp;</label> -->
										<a href="javascript:void(0)" style="width: 100%; border-radius: 0!important; height:66px;" class="btn btn-info btn-lg f18">Validar Token</a>
									</div>

									<div class="push20"></div>

								</div>

								<div id="relatorioTipoVenda" style="display: none;"><!--  TIPO VENDA ----------------------------------------------------------------------------------------------------------------------------------------------------- -->

									<div id="btnResgate" class="col-md-6 col-xs-12">
										<a style="width: 100%; border-radius: 0!important;"
											href="javascript:void(0)" class="btn btn-default f12">
											Compra sem Resgate
										</a>
										<div class="push20"></div>
									</div>

									<div id="btnResgate" class="col-md-6 col-xs-12">
										<a style="width: 100%; border-radius: 0!important;"
											href="javascript:void(0)" class="btn btn-default f12">
											Compra com Resgate
										</a>
										<div class="push20"></div>
									</div>

								</div>

								<div id="relatorioResgate" style="display: none;"><!--  RESGATE ----------------------------------------------------------------------------------------------------------------------------------------------------- -->

									<div class="col-md-8 col-xs-12 text-left p-r-0">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Valor do Resgate</label> -->
											<input type="text" placeholder="Valor do Resgate (R$)" name="VAL_RESGATE" id="VAL_RESGATE" readonly value="" maxlength="50" class="form-control input-lg money" style="border-radius:0 3px 3px 0;">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4 col-xs-12 p-l-0">
										<!-- <label>&nbsp;</label> -->
										<a href="javascript:void(0)" style="width: 100%; border-radius: 0!important; height:66px;" class="btn btn-success btn-lg f18">Enviar Token</a>
									</div>

									<div class="push20"></div>

								</div>

								<div id="relatorioValidaResgate" style="display: none;"><!--  VALIDA RESGATE ---------------------------------------------------------------- -->

									<div class="col-md-8 col-xs-12 text-left p-r-0">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Token</label> -->
											<input type="text" placeholder="Token de resgate" name="TKN_RESGATE" id="TKN_RESGATE" value="" maxlength="99" readonly class="form-control input-lg" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4 col-xs-12 p-l-0">
										<!-- <label>&nbsp;</label> -->
										<a href="javascript:void(0)" style="width: 100%; border-radius: 0!important; height:66px;" class="btn btn-info btn-lg f18">Validar Token</a>
									</div>

									<div class="push20"></div>

								</div>

								<div id="relatorioVenda" style="display: none;"><!--  VENDA ----------------------------------------------------------------------------------------------------------------------------------------------------- -->

									<div class="col-md-8 col-xs-12 text-left p-r-0">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Valor da Venda</label> -->
											<input type="text" placeholder="Valor Total da Compra (R$)" name="VAL_VENDA" id="VAL_VENDA" value="" readonly maxlength="50" class="form-control input-lg money" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4 col-xs-12 p-l-0">
										<!-- <label>&nbsp;</label> -->
										<a href="javascript:void(0)" style="width: 100%; border-radius: 0!important; height:66px;" class="btn btn-success btn-lg f18">Lançar</a>
									</div>

									<div class="push20"></div>

								</div>

								<div id="relatorioPos"></div>

							</div>

						</div>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!-- <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="PREF" id="PREF" value="<?= $pref ?>">
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


<style>
	.tabOff {
		background-color: #FEF9E7;
		border-radius: 5px;
		opacity: 0.7;
		width: 100%;
		z-index: 99999;
		padding: 20px;
		border-radius: 5px;
		cursor: not-allowed;
	}

	.foco {
		border: 3px dashed #cecece;
		border-radius: 5px;
		padding: 20px;
		margin: 0 0 20px 0;
	}
</style>


<script type="text/javascript">
	function mudaTab(el) {

		var id = $(el).attr("id");

		$("#relatorioPesquisa,#relatorioNome,#relatorioTipoVenda,#relatorioToken,#relatorioResgate,#relatorioValidaResgate,#relatorioVenda").removeClass("foco").addClass("tabOff");
		$("#boxPasso1,#boxPasso2,#boxPasso3,#boxPasso4,#boxPasso5,#boxPasso6").fadeOut(0);

		if (id == "p1") {
			$("#relatorioNome,#relatorioTipoVenda,#relatorioToken,#relatorioResgate,#relatorioValidaResgate,#relatorioVenda").fadeOut('fast', function() {
				$("#relatorioPesquisa").fadeIn('fast');
				$("#relatorioPesquisa").addClass("foco").removeClass("tabOff");

				$("#boxPasso1").fadeIn('fast');

				//blocos do botão
				$("#tab_p1").show();
				$("#tab_p2").hide();
				$("#tab_p3").hide();
				$("#tab_p4").hide();
				$("#tab_p5").hide();
				$("#tab_p6").hide();
			});
		} else if (id == "p2") {
			$("#relatorioToken,#relatorioResgate,#relatorioValidaResgate,#relatorioVenda").fadeOut('fast', function() {
				//blocos formulario
				$("#relatorioNome,#relatorioTipoVenda").fadeIn('fast');
				$("#relatorioNome").addClass("foco").removeClass("tabOff");

				$("#boxPasso2").fadeIn('fast');

				//blocos do botão
				$("#tab_p1").hide();
				$("#tab_p2").show();
				$("#tab_p3").hide();
				$("#tab_p4").hide();
				$("#tab_p5").hide();
				$("#tab_p6").hide();

			});
		} else if (id == "p3") {
			$("#relatorioResgate,#relatorioValidaResgate,#relatorioVenda").fadeOut('fast', function() {
				$("#relatorioNome,#relatorioTipoVenda,#relatorioToken").fadeIn('fast');
				$("#relatorioToken").addClass("foco").removeClass("tabOff");

				$("#boxPasso3").fadeIn('fast');

				//blocos do botão
				$("#tab_p1").hide();
				$("#tab_p2").hide();
				$("#tab_p3").show();
				$("#tab_p4").hide();
				$("#tab_p5").hide();
				$("#tab_p6").hide();
			});
		} else if (id == "p4") {
			$("#relatorioValidaResgate,#relatorioVenda").fadeOut('fast', function() {
				$("#relatorioNome,#relatorioTipoVenda,#relatorioToken,#relatorioResgate").fadeIn('fast');
				$("#relatorioResgate").addClass("foco").removeClass("tabOff");

				$("#boxPasso4").fadeIn('fast');

				//blocos do botão
				$("#tab_p1").hide();
				$("#tab_p2").hide();
				$("#tab_p3").hide();
				$("#tab_p4").show();
				$("#tab_p5").hide();
				$("#tab_p6").hide();
			});
		} else if (id == "p5") {
			$("#relatorioVenda").fadeOut('fast', function() {
				$("#relatorioNome,#relatorioTipoVenda,#relatorioToken,#relatorioResgate,#relatorioValidaResgate").fadeIn('fast');
				$("#relatorioValidaResgate").addClass("foco").removeClass("tabOff");

				$("#boxPasso5").fadeIn('fast');

				//blocos do botão
				$("#tab_p1").hide();
				$("#tab_p2").hide();
				$("#tab_p3").hide();
				$("#tab_p4").hide();
				$("#tab_p5").show();
				$("#tab_p6").hide();
			});
		} else if (id == "p6") {
			$("#relatorioVenda").fadeOut('fast', function() {
				$("#relatorioNome,#relatorioTipoVenda,#relatorioToken,#relatorioResgate,#relatorioValidaResgate,#relatorioVenda").fadeIn('fast');
				$("#relatorioVenda").addClass("foco").removeClass("tabOff");

				$("#boxPasso6").fadeIn('fast');

				//blocos do botão
				$("#tab_p1").hide();
				$("#tab_p2").hide();
				$("#tab_p3").hide();
				$("#tab_p4").hide();
				$("#tab_p5").hide();
				$("#tab_p6").show();
			});
		}

		$("#p1,#p2,#p3,#p4,#p5,#p6").removeClass('active');

		$("#" + id).addClass('active');

	}
</script>