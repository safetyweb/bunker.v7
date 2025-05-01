<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$mensagemEnvio = rand(0, 4);
// fnEscreve($mensagemEnvio);

$adm = $connAdm->connAdm();

$cod_univendURL = fnDecode($_GET['idUv']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
		$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '" . $cod_grupotr . "', 
				 '" . $des_grupotr . "', 
				 '" . $cod_empresa . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			$arrayProc = mysqli_query($adm, $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
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
	$cod_responsavel = fnDecode($_GET['idU']);
	$contactados = $_GET['ctt'];
	$ordenac = $_GET['odb'];
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

$conn = conntemp($cod_empresa, "");

$sqlCod = "SELECT GROUP_CONCAT( DISTINCT A.COD_DESAFIO SEPARATOR '||') COD_DESAFIO  
			FROM desafio_controle_v2 A 
			INNER JOIN CAMPANHA B ON B.COD_DESAFIO = A.COD_DESAFIO 
			INNER JOIN DESAFIO_V2 C ON C.COD_DESAFIO = A.COD_DESAFIO 
			WHERE A.COD_RESPONSAVEL=$cod_responsavel 
			AND A.COD_EMPRESA=$cod_empresa
			AND B.COD_EXCLUSA = 0 
			AND B.LOG_ATIVO = 'S'
			AND C.LOG_ATIVO = 'S'
			AND DATE_FORMAT(b.DAT_FIM, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d') ";

$arrayCod = mysqli_query(connTemp($cod_empresa, ''), $sqlCod);
$qrCod = mysqli_fetch_assoc($arrayCod);

// fnEscreve($sqlCod);

$cod_desafios = explode("||", $qrCod[COD_DESAFIO]);
$cod_desafio_concat = implode(",", $cod_desafios);

$desafioFiltro = fnLimpaCampoZero(fnDecode($_GET['fIdC']));

if($desafioFiltro != 0){
	$cod_desafio_concat = $desafioFiltro;
}

$sqlDesafiosAtivos = "SELECT A.COD_DESAFIO FROM DESAFIO_V2 A
					  INNER JOIN CAMPANHA B ON B.COD_DESAFIO = A.COD_DESAFIO
					  WHERE A.COD_EMPRESA = $cod_empresa
					  AND A.COD_DESAFIO IN($cod_desafio_concat)
					  AND A.LOG_ATIVO = 'S'
					  AND B.LOG_ATIVO = 'S'
					  AND DATE_FORMAT(B.DAT_FIM, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')";

$arrayDesafios = mysqli_query(connTemp($cod_empresa, ''), $sqlDesafiosAtivos);

//fnEscreve($sqlDesafiosAtivos);

$codDesafios = "";

while($qrDesafios = mysqli_fetch_assoc($arrayDesafios)){
	$codDesafios .= $qrDesafios[COD_DESAFIO].",";
}

$codDesafios = rtrim(ltrim($codDesafios,','),',');

// fnEscreve($codDesafios);

//fnMostraForm();

?>

<style type="text/css">
	body {
		overflow-x: hidden !important;
	}

	body#filtros-mob {
		/* remove scroll bars when modal visible */
		overflow: hidden !important;
	}

	.text-muted {
		color: #839192;
	}

	.col-flex-4-centered {
		display: flex;
		flex: 33.333333%;
		align-items: center;
		justify-content: center;
	}

	.outContainer {
		width: 100% !important;
	}

	.filters {
		height: 55px;
		position: relative;
		background: #fff;
		width: 100%;
	}

	.filters ul {
		display: flex;
		list-style: none;
		padding: 0;
		border-bottom: 1px solid #ddd;
		border-top: 1px solid #ddd;
	}

	.filters li {
		flex: 50%;
		text-align: center;
		padding: 15px 0px 15px;
		width: unset !important;
	}

	.filters li a {
		color: #00491F;
	}

	.filters li:first-child:after {
		position: absolute;
		content: '';
		border-right: 1px solid #ddd;
		height: 25px;
		top: 50%;
		right: 50%;
		transform: translateY(-50%);
	}

	.tag {
		padding-left: 0 !important;
	}

	.tag span {
		border-radius: 40px !important;
	}

	.shadow {
		box-shadow: 0px 2px 8px 1px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 0px 2px 8px 1px rgba(0, 0, 0, 0.2);
		-moz-box-shadow: 0px 2px 8px 1px rgba(0, 0, 0, 0.2);
		border-radius: 5px;
	}

	.col-md-12>.portlet {
		border-radius: 10px !important;
		-webkit-border-radius: 10px !important;
	}

	.red-detail:before {
		position: absolute;
		content: '';
		/*      border-right: 5px solid #18BC9C;*/
		border-right: 5px solid #B64645;
		height: 45px;
		top: 55%;
		left: 5.7%;
		transform: translateY(-45%);
		border-radius: 0 5px 5px 0;
	}

	.green-detail:before {
		position: absolute;
		content: '';
		border-right: 5px solid #18BC9C;
		/*      border-right: 5px solid #B64645;*/
		height: 45px;
		top: 55%;
		left: 5.7%;
		transform: translateY(-45%);
		border-radius: 0 5px 5px 0;
	}

	.btn-wpp {
		height: 54px;
		width: 54px;
		border-radius: 30px;
		padding: 10px;
		background-color: green;
		border-color: green;
	}

	.btn-wpp:before {
		content: attr(data-count);
		width: 19px;
		height: 19px;
		line-height: 19px;
		text-align: center;
		display: block;
		border-radius: 50%;
		background: #18bc9b;
		border: 1px solid #FFF;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
		color: #FFF;
		font-size: 13px;
		position: absolute;
		top: -5px;
		left: -5px;
	}

	.btn-wpp.badge-top-right:before {
		left: auto;
		right: -5px;
	}

	.btn-wpp.badge-bottom-right:before {
		left: auto;
		top: auto;
		right: -5px;
		bottom: -5px;
	}

	.btn-wpp.badge-bottom-left:before {
		top: auto;
		bottom: -5px;
	}

	.collapsible,
	.ordenador {
		background-color: #fff;
		color: #000;
		cursor: pointer;
		padding: 15px 0px;
		padding-left: 15px;
		width: 100%;
		border: none;
		border-bottom: 1px solid #eee;
		text-align: left;
		outline: none;
		font-size: 17px;
		font-weight: 500;
	}

	.active,
	.collapsible:hover {
		/*      background-color: #555;*/
	}

	.content {
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.2s ease-out;
		background-color: #fafafa;
		padding: 0;
	}

	#filtros-mob,
	#detalhes_prod,
	#ordenac-mob,
	#msg-wpp {
		position: fixed;
		padding: 0;
		margin: 0;

		top: 0;
		left: 0;

		width: 100%;
		height: 100%;
		background: #fff;
		z-index: 1001;

		overflow: scroll !important;
	}

	#filtros-mob ul li a {
		font-size: 16px;
		color: #00491F;
		font-weight: 300;
	}

	#filtros-mob ul li {
		padding-top: 15px;
		padding-bottom: 15px;
		border-bottom: 1px solid #eee;
	}

	#filtros-mob ul {
		padding: 0;
	}

	.margin-left-15 {
		margin-left: 15px;
	}

	.margin-top-10 {
		margin-top: 10px;
	}

	.margin-top-20 {
		margin-top: 20px;
	}

	.margin-top-40 {
		margin-top: 40px;
	}

	.margin-top-60 {
		margin-top: 60px;
	}

	.margin-top-80 {
		margin-top: 80px;
	}

	.margin-top-100 {
		margin-top: 100px;
	}

	.margin-bottom-0 {
		margin-bottom: 0px;
	}

	.margin-bottom-10 {
		margin-bottom: 10px;
	}

	.margin-bottom-20 {
		margin-bottom: 20px;
	}

	.margin-bottom-30 {
		margin-bottom: 30px;
	}

	.margin-bottom-100 {
		margin-bottom: 100px;
	}

	.blockAction {
		-webkit-touch-callout: none !important;
		-webkit-user-select: none !important;
		-ms-user-select: none !important;
		/* IE 10 and IE 11 */
		user-select: none !important;
		/* Standard syntax */
	}

	.ord-active {
		font-weight: 999 !important;
		color: #000 !important;
	}

	@media screen and (max-width: 1024px) {
		.portlet {
			margin: 0 10px !important;
			padding: 15px !important;
		}
	}


	.md-backdrop {
		background: #000;
		opacity: .5;
		position: absolute;
		z-index: 9999;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}

	#md-images {
		position: fixed;
		top: 0;
		z-index: 10000;
		width: 100%;
		height: 100%;
		padding: 10px;
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: center;
	}

	#md-images .container {
		width: 100%;
		background: #FFF;
		display: flex;
		flex-direction: column;
		padding: 10px 6px;
	}

	#md-images .list-images {
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
	}

	.label{
		font-size: 12px!important;
	}

	.f17{
		font-size: 17px!important;
	}

	div.sticky_top_mob {
		position: sticky;
		position: -webkit-sticky;
		position: -moz-sticky;
		position: -ms-sticky;
		position: -o-sticky;
		bottom: 0px;
		padding-top: 10px;
		padding-left: 5px;
		padding-right: 5px;
	}

	.loading-img div {
		background-color: #f0f0f0;

		display: block;
		position: absolute;
		top: 0;
		left: -100%;
		width: 100%;
		height: 100%;
		background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
		animation: loading 1.5s infinite;
	}

	#blocker, #blocker2
    {
        display:none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: .8;
        background-color: #f2f2f2;
        z-index: 1002;
    }

    #blocker div, #blocker2 div
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

	@keyframes loading {
		0% {
			left: -100%;
		}

		100% {
			left: 100%;
		}
	}
</style>

<!-- <link rel="stylesheet" href="https://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" /> -->

<div id="blocker2">
    <center>
        <img src="media/spinnerBlue.gif" width="200px" style="margin-top: 150px;" />
        <br />
        <p class="f16">Enviando...</p>
    </center>
</div>

<div class="filters">
	<ul>
		<li><a href="javascript:void(0)" onclick="mostraFiltros('ordenac-mob')"><span class="fal fa-sort-alt"></span>&nbsp; Ordenar</a></li>
		<li><a href="javascript:void(0)" onclick="mostraFiltros('filtros-mob')"><span class="fal fa-sliders-h"></span>&nbsp; Filtrar</a></li>
	</ul>
</div>

<div id="ordenac-mob" style="display: none;">

	<div id="close_filtros" class="margin-left-15 margin-top-100">
		<a href="javascript:void(0)" onclick="mostraFiltros('ordenac-mob')" style="padding: 15px 15px 15px 0; color: #2C3E50;">
			<b><span class="far fa-arrow-left fa-2x"></span></b>
		</a>
	</div>

	<div id="sanfona" class="margin-top-40">

		<h3 class="margin-top-10 margin-bottom-30 margin-left-15"><b>Ordenar por</b></h3>

		<?php

		switch ($ordenac) {
			case 'menc': // menor compra
				$orderBy = "ORDER BY V.VAL_TOTVENDA DESC";
				$menc = "ord-active";
				break;

			case 'maic': // maior compra
				$orderBy = "ORDER BY V.VAL_TOTVENDA ASC";
				$maic = "ord-active";
				break;

			case 'aniv': //aniversario
				$orderBy = "ORDER BY B.MES, B.DIA";
				$aniv = "ord-active";
				break;

			case 'masa': // maior saldo
				$orderBy = "ORDER BY CREDITO_DISPONIVEL DESC";
				$masa = "ord-active";
				break;

			default:
				$orderBy = "ORDER BY B.NOM_CLIENTE";
				$alfa = "ord-active";
				break;
		}

		?>


		<button class="ordenador" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET['idUv']?>&ctt=<?= $_GET[ctt] ?>'"><span class="<?= $alfa ?>">Nome do Cliente</span></button>
		<button class="ordenador" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET['idUv']?>&ctt=<?= $_GET[ctt] ?>&odb=menc'"><span class="<?= $menc ?>">Compra Crescente</span></button>
		<button class="ordenador" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET['idUv']?>&ctt=<?= $_GET[ctt] ?>&odb=maic'"><span class="<?= $maic ?>">Maior Valor Compra</span></button>
		<button class="ordenador" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET['idUv']?>&ctt=<?= $_GET[ctt] ?>&odb=aniv'"><span class="<?= $aniv ?>">Data Aniversário</span></button>
		<button class="ordenador" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET['idUv']?>&ctt=<?= $_GET[ctt] ?>&odb=masa'"><span class="<?= $masa ?>">Maior Saldo Cashback</span></button>

	</div>

</div>

<?php
include "filtrosAgendaVendedor.php";
include "detalhesAgendaVendedor.php";
include "mensagemWhatsappAgenda.php";

$sqlCount = " SELECT  (SELECT COUNT(cod_controle) 
								FROM desafio_controle_v2 A
								INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
								INNER JOIN desafio_v2 E ON E.COD_DESAFIO= A.COD_DESAFIO
								INNER JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND
								WHERE A.COD_RESPONSAVEL=$cod_responsavel 
								AND A.COD_EMPRESA=$cod_empresa
								AND E.COD_DESAFIO IN($codDesafios)
								AND E.LOG_ATIVO = 'S') as LISTA,
						(SELECT COUNT(cod_controle) 
								FROM desafio_controle_v2 A
								INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
								INNER JOIN desafio_v2 E ON E.COD_DESAFIO= A.COD_DESAFIO
								INNER JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND
								WHERE A.COD_RESPONSAVEL=$cod_responsavel 
								AND A.COD_EMPRESA=$cod_empresa
								AND E.COD_DESAFIO IN($codDesafios)
								AND E.LOG_ATIVO = 'S'
								AND A.LOG_CONCLUIDO = 'S') as CONTACTADOS";

// fnEscreve($sqlCount);
$arrCount = mysqli_query(connTemp($cod_empresa, ''), $sqlCount);
$qrCount = mysqli_fetch_assoc($arrCount);

?>



<div class="push10"></div>
<div class="push5"></div>


<div class="col-md-12">
	<!-- Portlet -->
	<div class="portlet portlet-bordered" style="padding: 0!important;">
		<img src="media/roi_sms_email.jpg" class="img-responsive" width="100%" style="border-radius: 10px;">
	</div>
	<!-- fim Portlet -->
</div>

<div class="push30"></div>
<div>
	<div class="col-md-12">

		<div class="row" style="display: flex;">

			<div class="col-flex-4-centered">
				<a href="javascript:void(0)" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET[idUv]?>'" class="f17 text-muted">Lista (<?= $qrCount[LISTA] ?>)</a>
			</div>
			<div class="col-flex-4-centered">
				<a href="javascript:void(0)" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET[idUv]?>&ctt=true'" class="f17 text-muted">Contactados (<?= $qrCount[CONTACTADOS] ?>)</a>
			</div>
			<div class="col-flex-4-centered">
				<div class="row">
					<div class="col-xs-12" style="padding: 0;">
						<a href="javascript:void(0)" data-count="0" class="btn btn-success btn-wpp badge-top-right" onclick="mostraFiltros('msg-wpp')" style="position: fixed; z-index: 99; margin-top: -23px;">
							<i class="fab fa-whatsapp fa-2x"></i>
						</a>
					</div>
				</div>

			</div>

		</div>

	</div>
</div>

<div class="push30"></div>


<?php

$andConcluido = "";

if ($contactados == 'true') {
	$andConcluido = "AND A.LOG_CONCLUIDO = 'S'";
}

$sqlDesafio = " SELECT
			    	A.COD_CLIENTE, 
			    	A.LOG_CONCLUIDO, 
					B.NOM_CLIENTE, 
					D.NOM_FAIXACAT, 
					B.DIA, 
					B.MES, 
					B.DAT_ULTCOMPR, 
					A.COD_RESPONSAVEL, 
					A.COD_UNIVEND, 
					C.NOM_FANTASI AS UNIVEND_CADASTRO,
					SUM(v.VAL_TOTVENDA) VAL_TOTVENDA,
					IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos f 
					WHERE f.cod_cliente = A.cod_cliente AND 
							f.tip_credito = 'C' AND 
							f.cod_statuscred = 1 AND 
							f.tip_campanha = EMP.tip_campanha AND 
							(( f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR ( f.log_expira = 'N' ) )),0)+
					IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos_bkp g
					WHERE g.cod_cliente = A.cod_cliente AND 
							g.tip_credito = 'C' AND 
							g.cod_statuscred = 1 AND 
							g.tip_campanha = EMP.tip_campanha AND 
							((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR (g.log_expira = 'N' ) )),0) AS CREDITO_DISPONIVEL, 
					GROUP_CONCAT( DISTINCT E.NOM_DESAFIO SEPARATOR '||') NOM_DESAFIO 
				FROM desafio_controle_v2 A
				INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
				INNER JOIN vendas V ON V.COD_CLIENTE = A.COD_CLIENTE AND V.COD_AVULSO = 2
				INNER JOIN desafio_v2 E ON E.COD_DESAFIO= A.COD_DESAFIO
				INNER JOIN CAMPANHA F ON F.COD_DESAFIO= A.COD_DESAFIO
				INNER JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND
				INNER JOIN EMPRESAS EMP ON EMP.COD_EMPRESA = A.COD_EMPRESA
				LEFT JOIN CATEGORIA_CLIENTE D ON D.COD_CATEGORIA=B.COD_CATEGORIA
				WHERE A.COD_RESPONSAVEL=$cod_responsavel 
				AND A.COD_EMPRESA=$cod_empresa
				AND E.COD_DESAFIO IN($codDesafios)
				AND E.LOG_ATIVO = 'S'
				AND F.LOG_ATIVO = 'S'
				$andConcluido
				GROUP BY A.COD_CLIENTE
				$orderBy";

// fnEscreve($sqlDesafio);
$arrayDesafio = mysqli_query(connTemp($cod_empresa, ''), $sqlDesafio);
$count = 0;

while ($qrDesafio = mysqli_fetch_assoc($arrayDesafio)) {

	$cod_cliente = $qrDesafio[COD_CLIENTE];
	$log_concluido = $qrDesafio[LOG_CONCLUIDO];
	$nom_faixacat = $qrDesafio['NOM_FAIXACAT'];

	$desafios = explode("||", $qrDesafio[NOM_DESAFIO]);

	$credito_disponivel = $qrDesafio['CREDITO_DISPONIVEL'];


	$nome = explode(" ", $qrDesafio[NOM_CLIENTE]);
	if(ucfirst(strtolower($nome[0])) != ucfirst(strtolower(end($nome)))){
		$nom_cliente = ucfirst(strtolower($nome[0])) . " " . ucfirst(strtolower(end($nome)));
	}else{
		$nom_cliente = ucfirst(strtolower($nome[0]));
	}

	$sqlVenda = "SELECT
				    Subquery.ordenacao,
				    Subquery.COD_EMPRESA,
				    Subquery.COD_CLIENTE,
				    Subquery.NOM_FANTASI,
				    Subquery.NOM_VENDEDOR,
				    Subquery.COD_UNIVEND_CAD,
				    Subquery.COD_VENDEDOR,
				    Subquery.COD_VENDA,
				    Subquery.COD_UNIVEND_VEN, 
				    Subquery.DAT_CADASTR_WS,
				    Subquery.VAL_TOTPRODU,
				    Subquery.VAL_RESGATE,
				    Subquery.VAL_DESCONTO,
				    Subquery.VAL_TOTVENDA,
				    NULL AS COD_PRODUTO,
				    NULL AS DES_PRODUTO,
				    NULL AS QTD_PRODUTO,
				    NULL AS VAL_TOTITEM
				FROM (
				    SELECT
				        1 ordenacao,
				        P.COD_EMPRESA,
				        P.COD_CLIENTE,
				        UV.NOM_FANTASI,
				        US.NOM_USUARIO NOM_VENDEDOR,
				        v.COD_VENDA,
				        P.COD_UNIVEND AS COD_UNIVEND_CAD,
				        v.COD_VENDEDOR,
				        v.COD_UNIVEND AS COD_UNIVEND_VEN, 
				        v.DAT_CADASTR_WS,
				        v.VAL_TOTPRODU,
				        v.VAL_RESGATE,
				        v.VAL_DESCONTO,
				        SUM(v.VAL_TOTVENDA) VAL_TOTVENDA
				    FROM CLIENTES P
				    INNER JOIN vendas v ON v.COD_CLIENTE = P.COD_CLIENTE AND v.COD_AVULSO = 2
				    INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = V.COD_UNIVEND
				    INNER JOIN USUARIOS US ON US.COD_USUARIO = V.COD_VENDEDOR
				    WHERE P.COD_EMPRESA = $cod_empresa
				        AND P.COD_CLIENTE = $cod_cliente
				        ORDER BY COD_VENDA DESC
				        LIMIT 1
				) AS Subquery";

	// fnEscreve($sqlVenda);

	$arrayVenda = mysqli_query(connTemp($cod_empresa, ''), $sqlVenda);

	$qrUltimaVenda = mysqli_fetch_assoc($arrayVenda);

	$borda = "red-detail";

	if ($log_concluido == "S") {
		$borda = "green-detail";
	}

	//verifica se tem bloqueio
	$sql4 = "SELECT COUNT(*) as TEM_BLOQUEIO
	FROM CLIENTES A, VENDAS B
	LEFT JOIN $connAdm->DB.unidadevenda d ON d.cod_univend = b.cod_univend 
	WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
	B.COD_STATUSCRED=3 AND 
	B.cod_avulso!=1 AND
	A.COD_EMPRESA = $cod_empresa and
	A.COD_CLIENTE = $cod_cliente ";
	//fnEscreve($sql4);
	$qrBuscaBloqueio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql4));
	//fnEscreve($sql4);

	$tem_bloqueio = $qrBuscaBloqueio['TEM_BLOQUEIO'];

	$badgeBloqueio = "";

	if ($tem_bloqueio > 0) {
		$badgeBloqueio = "&nbsp;<span class='text-danger f12'>(<i class='fal fa-ban'></i>&nbsp; Bloqueado)</span>";
	}


?>

	<div class="col-md-12">
		<!-- Portlet -->
		<div class="portlet portlet-bordered <?= $borda ?> shadow blockAction" id="CLIENTE_<?= $count ?>" data-cliente="<?= fnEncode($cod_cliente) ?>">
			<div class="portlet-body">
				<div class="row" style="margin-bottom: 0;">
					<a data-toggle="collapse" href="#collapseExample_<?= $count ?>" aria-expanded="false" aria-controls="collapseExample_<?= $count ?>" style="text-decoration: none; color: #2C3E50;">
						<div class="col-xs-10">
							<div class="row">
								<div class="col-xs-12">
									<h4 style="margin: 0"><b><?= $nom_cliente . $badgeBloqueio ?></b></h4>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<ul class="tag">
										<?php
										foreach ($desafios as $desafio) {
										?>
											<li class="tag"><span class="label label-default">&nbsp; <?= $desafio ?> &nbsp;</span></li>
										<?php
										}
										?>
									</ul>
								</div>
							</div>
							<div class="row" style="margin-bottom: 0">
								<div class="col-xs-12">
									<div class="push5"></div>
									<p class="f16 text-muted" style="margin-bottom: 0">
										Última Compra: <?= fnDataShort($qrDesafio[DAT_ULTCOMPR]) ?>
									</p>
								</div>
							</div>
						</div>
					</a>
					<div class="col-xs-2 text-right">
						<p class="f14"><i class="fal fa-birthday-cake text-warning"></i>&nbsp;<?= $qrDesafio[DIA] ?>/<?= $qrDesafio[MES] ?></p>
					</div>
				</div>
				<div class="row" style="margin-bottom: 0">
					<div class="col-xs-12">
						<div class="collapse" id="collapseExample_<?= $count ?>">
							<a data-toggle="collapse" href="#collapseExample_<?= $count ?>" aria-expanded="false" aria-controls="collapseExample_<?= $count ?>" style="text-decoration: none; color: #2C3E50;">
								<div class="card card-body">
									<div class="row">
										<div class="col-xs-12">
											<p class="f16 text-muted" style="margin-bottom: 0">
												Saldo: <b>R$<?= fnValor($credito_disponivel, 2) ?></b>
											</p>
										</div>
										<div class="col-xs-12">
											<span class="f16 text-muted" style="margin-bottom: 0">
												Categoria: &nbsp;
											</span>
											<ul class="tag">
												<?php if ($nom_faixacat != "") { ?>
													<li class="tag"><span class="label label-info">&nbsp;<i class="fal fa-bookmark"></i>&nbsp; <?= $nom_faixacat ?> &nbsp;</span></li>
												<?php } else { ?>
													<li>Sem categoria</li>
												<?php } ?>
											</ul>
										</div>
										<div class="col-xs-12">
											<p class="f16 text-muted" style="margin-bottom: 0">
												Compras Acumuladas: <b>R$<?= fnValor($qrUltimaVenda[VAL_TOTVENDA], 2) ?></b>
											</p>
										</div>
										<div class="col-xs-12">
											<p class="f16 text-muted" style="margin-bottom: 0">
												Ult. Vendedor: <b><?= $qrUltimaVenda[NOM_VENDEDOR] ?></b>
											</p>
										</div>
										<div class="col-xs-12 text-right">
											<a href="javascript:void(0)" class="btn btn-xs btn-primary" style="border-radius:7px; padding:0px 5px;" onclick='mostraFiltros("detalhes_prod","<?= fnEncode($qrDesafio[COD_CLIENTE]) ?>")'>Detalhes</a>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- fim Portlet -->

		<script type="text/javascript">
			$(function() {

				let count<?= $count ?> = 0,
					cliente = 0,
					rplc = "",
					countCliLimit = 0,
					countCli = 0;

				$("#CLIENTE_<?= $count ?>").on("taphold", function(e) {

					e.stopPropagation();

					if ($("#COD_CLIENTES").val() != "") {
						countCliLimit = $("#COD_CLIENTES").val().split(",").length;
					}

					// alert(countCliLimit);

					cliente = $(this).attr("data-cliente");

					if (count<?= $count ?> == 0) {

						if(countCliLimit <= 2){

							$(this).css("background", "#D5F5E3");
							count<?= $count ?> = 1;

							if ($("#COD_CLIENTES").val() != "") {
								cliente = $("#COD_CLIENTES").val() + "," + cliente;
							}

						}else{

							cliente = $("#COD_CLIENTES").val();
							// alert("Para evitar bloqueios do chip, o limite de contatos simultâneos é de 3 clientes por vez.");
							$.alert({
			                    title: "Aviso",
			                    content: "Para evitar bloqueios do chip, o limite de contatos simultâneos é de 3 clientes por vez.",
			                    type: 'orange',
			                    buttons: {
									Ok: function () {
										
									}
								}
			                });

						}

					} else {

						$(this).css("background", "#FFF");
						count<?= $count ?> = 0;

						if ($("#COD_CLIENTES").val().includes(cliente + ',')) {
							rplc = cliente + ',';
						} else if ($("#COD_CLIENTES").val().includes(',' + cliente)) {
							rplc = ',' + cliente;
						} else {
							rplc = cliente;
						}

						cliente = $("#COD_CLIENTES").val().replace(rplc, '');


					}

					$("#COD_CLIENTES").val(cliente);

					if ($("#COD_CLIENTES").val() != "") {
						countCli = $("#COD_CLIENTES").val().split(",").length;
					} else {
						countCli = 0;
					}

					$('.btn-wpp').attr('data-count', countCli);
					$('#CLIENTES_COUNT').text(countCli);

					navigator.vibrate(350);

					

				});

				document.getElementById("CLIENTE_<?= $count ?>").oncontextmenu = function(event) {
					event.preventDefault();
					event.stopPropagation(); // not necessary in my case, could leave in case stopImmediateProp isn't available? 
					event.stopImmediatePropagation();
					return false;
				};

			});
		</script>

	</div>

	<div class="push10"></div>
	<div class="push5"></div>

<?php

	$count++;
}

?>
<input type="hidden" name="COD_CLIENTES" id="COD_CLIENTES" value="">

<div class="md-backdrop" onclick="hideModalImages()" style="display:none"></div>
<div id="md-images" onclick="hideModalImages()" style="display:none">
	<div class="container shadow" onclick="event.stopPropagation();">
		<div>
			<a style="float: right;color: #AAA;" onclick="hideModalImages()">
				<span aria-hidden="true">&times;</span>
			</a>
		</div>
		<div class="list-images">
		</div>
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


<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

<script type="text/javascript">
	$.mobile.autoInitializePage = false;

	$.event.special.tap.tapholdThreshold = 350;

	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
		coll[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var content = this.nextElementSibling;
			if (content.style.maxHeight) {
				content.style.maxHeight = null;
			} else {
				content.style.maxHeight = content.scrollHeight + "px";
			}
		});
	}

	function mostraFiltros(obj, idC = 0, idW = 0) {
		var x = document.getElementById(obj);
		if (x.style.display === "none") {

			x.style.display = "block";
			document.body.style.overflow = "hidden";

			if (idC != 0) {
				fetch("ajxDetalhesAgendaVendedor.do?id=<?= fnEncode($cod_empresa) ?>&idC=" + idC, {
						method: "POST",
						headers: {
							"Content-Type": "application/x-www-form-urlencoded",
						},
					})
					.then(function(response) {
						if (!response.ok) {
							throw new Error("Erro na solicitação");
						}
						return response.text();
					})
					.then(function(data) {
						document.getElementById("detalhes_prod").innerHTML = data;

						$("#detalhes_prod").find(".loading-img").parent().find("img").each(function() {
							$(this).on('load', function() {
								$(this).parent().find('.loading-img').hide();
								$(this).show();
							});
						});


						//Verifica se algum produto está sem imagem
						$("#detalhes_prod").find("img.no-image").each(function() {
							carregaImagem($(this).data("id"), $(this).data("desc"));
						});


						acoesProduto();
						// console.log(data);
					})
					.catch(function(error) {
						document.getElementById("detalhes_prod").innerHTML =
							'<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>';
					});


			} else if (idW != 0) {

			}

		} else {

			x.style.display = "none";
			document.body.style.overflow = "auto";

		}
	}


	var pressTimer;
	$(document).ready(function() {
		acoesProduto();
	});

	function acoesProduto() {
		$(".div-produto").on('mousedown touchstart', function() {
			let id = $(this).data("id");
			let desc = $(this).data("desc");
			pressTimer = window.setTimeout(function() {
				showModalImages(id, desc);
			}, 1000);
		}).on('mouseup touchend', function() {
			clearTimeout(pressTimer);
		});

		$(".div-produto").on('mouseleave touchleave', function() {
			clearTimeout(pressTimer);
		});
	}

	function carregaImagem(id, descricao) {
		buscaImagens(descricao, function(data) {
			if (data.images && data.images.length > 0) {
				let image = data.images[0].uri;
				$(`[data-id=${id}]`).attr("src", image);

				//Salva Imagem no Banco de Dados;
				saveImage(id, image);
			}
		})
		console.log("Carrega Imagem do Google", id, descricao)
	}

	function buscaImagens(query, callback = () => {}) {
		fetch('ajxBuscaImagensWeb.php?limit=6&query=' + query, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
			})
			.then(function(response) {
				if (!response.ok) {
					throw new Error('Erro na solicitação');
				}
				return response.json();
			})
			.then(function(data) {
				callback(data);
			})
			.catch(function(error) {
				console.error('Erro:', error);
			});

	}

	async function getImageType(url) {
		const response = await fetch(url, {
			method: 'HEAD'
		});
		const contentType = response.headers.get('content-type');
		return contentType.split('/')[1];
	}

	async function fetchImage(url) {
		const response = await fetch(url);
		const blob = await response.blob();
		return blob;
	}

	function saveImage(id, url) {
		console.log("Baixa Imagem do Google", id, url)
		getImageType(url)
			.then((imageType) => {
				return fetchImage(url).then((blob) => ({
					blob,
					imageType
				}));
			})
			.then(({
				blob,
				imageType
			}) => {
				const formData = new FormData();
				const nomeArquivo = 'prod_' + id;

				formData.append('arquivo', blob, `${nomeArquivo}.${imageType}`);
				formData.append('diretorio', '../media/clientes');
				formData.append('diretorioAdicional', 'produtos');
				formData.append('id', <?php echo $cod_empresa ?>);
				formData.append('typeFile', `image/${imageType}`);

				return fetch('../uploads/uploaddoc.php', {
						method: 'POST',
						body: formData,
					})
					.then(response => {
						if (!response.ok) {
							throw new Error('Erro ao carregar a imagem');
						}
						return response.text();
					})
					.then(data => {
						console.log("Salva Imagem no Banco de Dados", id, `${nomeArquivo}.${imageType}`, data);

						return fetch('ajxSalvaImagemProduto.php?COD_EMPRESA=<?php echo $cod_empresa ?>&COD_PRODUTO=' + id + '&DES_IMAGEM=' + nomeArquivo + '.' + imageType, {
								method: 'POST',
							})
							.then(response => {
								if (!response.ok) {
									throw new Error('Erro ao salvar a imagem no banco de dados');
								}
								return response.text();
							})
							.then(data => {
								console.log("Retorno Banco de Dados", data)
							});
					})
			})
			.catch((error) => {
				console.error('Erro ao baixar a imagem:', error, url);
			});


	}

	function showModalImages(id, descricao) {
		$(".md-backdrop").fadeIn();
		$("#md-images").fadeIn();
		$("#md-images .list-images").html("Buscando novas imagens....");

		buscaImagens(descricao, function(data) {
			$("#md-images .list-images").html("");
			console.log("IMAG", id, descricao, data);
			if (data.images && data.images.length > 0) {
				data.images.map(image => {
					$("#md-images .list-images").append(`
						<a href='javascript:' onclick="selecionaImagem('${id}','${image.uri}')" style='margin:5px;'>
							<img src='${image.uri}'>
						</a>
					`);
				});
			}

			/* SEM IMAGEM */
			<?php if ($_REQUEST["t"] == "TESTE") { ?>
				$("#md-images .list-images").append(`
					<a href='javascript:' onclick="selecionaImagem('${id}','https://bunker.mk/images/no_image.png')" style='margin:5px;'>
						<img src='https://bunker.mk/images/no_image.png'>
					</a>
				`);
			<?php } ?>
		})
	}

	function selecionaImagem(id, url) {
		$(`[data-id=${id}]`).attr("src", url);
		hideModalImages();

		//Salva Imagem no Banco de Dados;
		saveImage(id, url);
	}

	function hideModalImages() {
		$(".md-backdrop").fadeOut();
		$("#md-images").fadeOut();
	}

	function customizarMsg(idTemplate) {
		$("#enviarWpp_"+idTemplate).attr("data-template","99");
		$("#btnCustom_"+idTemplate).hide();
		$("#btnCancel_"+idTemplate).show();
		$("#MSG_"+idTemplate).hide();
		$("#DES_TEMPLATE_"+idTemplate).show().focus();
	}

	function cancelarMsg(idTemplate, nroTemplate) {
		$("#enviarWpp_"+idTemplate).attr("data-template", nroTemplate);
		$("#btnCustom_"+idTemplate).show();
		$("#btnCancel_"+idTemplate).hide();
		$("#MSG_"+idTemplate).show();
		$("#DES_TEMPLATE_"+idTemplate).hide();
	}

	function enviarWhatsapp(cod_desafio, sugestoes = "", nroTemplate = 0, cod_template) {
		let clientes = $("#COD_CLIENTES").val(),
			msgRet = "",
			headRet = "",
			tipRet = "",
			data = {DES_TEMPLATE: $("#DES_TEMPLATE_"+cod_template).val(), idUv: "<?= fnEncode($cod_univendURL) ?>"};

		$("#blocker2").show();

		fetch("ajxEnvioAgendaVendedor.do?id=<?= fnEncode($cod_empresa) ?>&idR=<?= fnEncode($cod_responsavel) ?>&idD=" + cod_desafio + "&idC=" + clientes + "&nroT=" + nroTemplate + "&SUGESTOES=" + sugestoes+ "&dev=<?=$_GET['dev']?>", {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
				},
				body: Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&'),
			})
			.then(function(response) {
				if (!response.ok) {
					throw new Error("Erro na solicitação");
				}
				return response.text();
			})
			.then(function(data) {
				// document.getElementById("detalhes_prod").innerHTML = data;
				// alert('mensagem enviada');
				console.log(data);
				$("#blocker2").hide();

				if(data > 0){
					msgRet = "Mensagem enviada para " + data + " cliente(s)";
					headRet = "Sucesso";
					tipRet = "green";
				}else{
					msgRet = "Os clientes selecionados estão em agendas e mensagens diferentes.";
					headRet = "Mensagem não enviada";
					tipRet = "red";
				}

				$.alert({
                    title: headRet,
                    content: msgRet,
                    type: tipRet,
                    buttons: {
						Ok: function () {
							if(data > 0){
								location.reload();
							}
						}
					}
                });
			})
			.catch(function(error) {
				document.getElementById("detalhes_prod").innerHTML =
					'<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>';
			});
	}
</script>
