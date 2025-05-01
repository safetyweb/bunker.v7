<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

/* Set the default timezone */
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');					
date_default_timezone_set("america/sao_paulo");

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

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

				$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
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
	$cod_contrat = fnDecode($_GET['idCT']);
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

$sql = "SELECT CE.*,  
				VC.DES_RENAVAM, 
				VC.DES_TIPO, 
				VC.DES_PLACA, 
				VC.DES_MARCA, 
				VC.DES_MODELO, 
				VC.DES_ANO
		FROM CONTRATO_ELEITORAL CE
		LEFT JOIN VEICULO_CLIENTE VC ON VC.COD_VEICULO = CE.COD_VEICULO AND VC.COD_EXCLUSA = 0
		WHERE CE.COD_EMPRESA = $cod_empresa 
		AND CE.COD_CONTRAT = $cod_contrat";

$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

$cod_cliente = $qrBuscaModulos[COD_CLIENTE];

$sqlCli = "SELECT CL.*, MU.NOM_MUNICIPIO, ES.UF 
			FROM CLIENTES CL
            LEFT JOIN ESTADO ES ON ES.COD_ESTADO = CL.COD_ESTADO
            LEFT JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = CL.COD_MUNICIPIO
            WHERE CL.COD_EMPRESA = $cod_empresa 
            AND CL.COD_CLIENTE = $cod_cliente";

$arrayCli = mysqli_query(connTemp($cod_empresa,''), $sqlCli);
$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_univend = $qrCli[COD_UNIVEND];

//fnEscreve($sqlCli);
//echo($sqlCli);

$sqlContrato = "SELECT CL.*, 
                       UV.NOM_FANTASI,
                       ES.UF,
                       MU.NOM_MUNICIPIO,
                       UV.NUM_CGCECPF AS CNPJ,
                       CDT.NUM_CANDIDATO,
                       CDT.DES_PARTIDO,
                       CDT.DES_CARGO,
                       CDT.NOM_ADMIN,
                       CL2.NUM_CGCECPF AS CPF_ADMIN,
					   CONCAT(UV.nom_cidadec,'/',UV.cod_estadof) as COMARCA
                FROM CANDIDATO CDT
                INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CDT.COD_UNIVEND
                INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = CDT.COD_CLIENTE
                LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = CDT.COD_CLIENTE_ADM
                LEFT JOIN ESTADO ES ON ES.COD_ESTADO = CL.COD_ESTADO
                LEFT JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = CL.COD_MUNICIPIO
                WHERE CDT.COD_EMPRESA = $cod_empresa 
                AND CDT.COD_UNIVEND = $cod_univend";

//fnescreve($sqlContrato);
//echo($sqlContrato);

$arrayCont = mysqli_query(connTemp($cod_empresa,''), $sqlContrato);
$qrContrato = mysqli_fetch_assoc($arrayCont);

switch ($qrContrato[COD_ESTACIV]) {
    case '1':
        $estadoCivil = "casado(a)";
    break;

    case '2':
        $estadoCivil = "solteiro(a)";
    break;

    case '3':
        $estadoCivil = "viúvo(a)";
    break;

    case '4':
        $estadoCivil = "divorciado(a)";
    break;
    
    default:
        $estadoCivil = "outros";
    break;
}

switch ($qrContrato[DES_CARGO]) {
    case 'pref':
        $cargoPolitico = "PREFEITO";
    break;

    case 'ver':
        $cargoPolitico = "VEREADOR";
    break;

    case 'depE':
        $cargoPolitico = "DEPUTADO ESTADUAL";
    break;

    case 'depF':
        $cargoPolitico = "DEPUTADO FEDERAL";
    break;

    case 'sen':
        $cargoPolitico = "SENADOR";
    break;
    
    default:
        $cargoPolitico = "PRESIDENTE";
    break;
}

$pessoa = "FÍSICA";
$letraPessoa = "F";

if($qrCli[LOG_JURIDICO] == "S"){
    $pessoa = "JURÍDICA";
    $letraPessoa = "J";
}

$formaPag = "Dinheiro";
$diplayCabo = "block";
$diplayServico = "none";
$diplayCessao = "none";
$diplayGenerico = "none";

switch ($qrBuscaModulos[COD_FORMAPA]) {
	case '2':
		$formaPag = "Pix";
	break;

	case '3':
		$formaPag = "TED/DOC";
	break;

	case '4':
		$formaPag = "Cheque";
	break;
	
	default:
		$formaPag = "Dinheiro";
	break;
}

switch ($qrBuscaModulos[TIP_PAGAMEN]) {
	case '1':
		$tipoPag = "Diário";
	break;

	case '7':
		$tipoPag = "Semanal";
	break;

	case '15':
		$tipoPag = "Quinzenal";
	break;

	case '30':
		$tipoPag = "Mensal";
	break;
	
	default:
		$tipoPag = "Pagamento Único";
	break;
}

//fnMostraForm();

?>

<style>
	.contrato {
		text-align: justify;
		max-width: 700px;
		margin-left: auto;
		margin-right: auto;
	}

	.clausula {
		text-align: justify;
	}

	.paragrafo {
		text-align: justify;
	}

	.assinatura {
		text-align: center;
		margin-top:30px;
	}
	h1 {
		margin:20px;
	}

	@media print {
		.assinatura {
			text-align: center;
			line-height:20px;
			color:red;
		}
		/*body {
			margin-left:75px;
			margin-right:65px;
			margin-top:10px;
		}*/
	}
</style>

<meta charset="UTF-8">

<div class="push30"></div>

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
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>
					<?php include "atalhosPortlet.php"; ?>
				</div>
			<?php } ?>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<div class="push30"></div>

				<div class="login-form">

					<div id="impressao">

						<div class="contrato" id="impressao1">

							<?php 

								$tipoContrato = "PRESTAÇÃO";

								switch ($qrBuscaModulos[TIP_CONTRAT]) {
									case '2': // cabo
										include "contratoCaboEleitoral.php";
									break;

									case '3': // coordenador
										include "contratoCoordenadorCabo.php";
									break;

									case '4': // cessão
										$tipoContrato = "CESSÃO";
										include "contratoCessao.php";
									break;
									
									case '5': // veículos
										$tipoContrato = "CESSÃO GRATUITA DE VEÍCULO";
										include "contratoVeiculo.php";
									break;
									
									default: // generico
										include "contratoGenerico.php";
									break;
								}

							?>

							<?=$qrCli['NOM_MUNICIPIO']?>, <?=date('d', strtotime($qrBuscaModulos['DAT_INI']))?> de <?=ucfirst(strftime("%B", strtotime($qrBuscaModulos['DAT_INI'])))?> de 2022.

							<div class="assinatura">

								<div class="row">
									<div class="col-xs-6">
										_______________________________________
										<br/>
										<b><?=strtoupper($qrContrato['NOM_FANTASI'])?></b>
										<br/>
										Administrador Financeiro: <?=$qrContrato['NOM_ADMIN'] ?> <br/>
										CPF/CNPJ <span class="cpfcnpj"><?=fnCompletaDoc($qrContrato['CPF_ADMIN'],"$letraPessoa")?></span>
									</div>
									<div class="col-xs-6">
										__________________________________________
										<br/>
										<?=ucwords(strtolower($qrCli['NOM_CLIENTE']))?>
										<br/>
										CPF/CNPJ <span class="cpfcnpj"><?=fnCompletaDoc($qrCli['NUM_CGCECPF'],"$letraPessoa")?></span>
										<br/> Contratado(a)
									</div>
								</div>
								<div class="push10"></div>
								<div class="row">
									<br/>
									<p>Testemunha:</p>
									<br/>
									<div class="col-xs-6">
										_______________________________________
										<br/>
										<span class="pull-left">Nome:</span>
										<br/>
										<span class="pull-left">CPF/CNPJ:</span>
									</div>
									<div class="col-xs-6">
										_______________________________________
										<br/>
										<span class="pull-left">Nome:</span>
										<br/>
										<span class="pull-left">CPF/CNPJ:</span>
									</div>
								</div>
								  
							</div>

						</div>

					</div>

				</div>

				<div class="push10"></div>

				<button type="button" class="btn btn-info addBox pull-left" onclick="imprimeContrato()" ><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Impressão do Contrato </button>


				<div class="push50"></div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script src='js/printThis.js'></script>

<script type="text/javascript">
	$(function(){

		var SPMaskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
		  onKeyPress: function(val, e, field, options) {
			  field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};			
		
		$('.sp_celphones').mask(SPMaskBehavior, spOptions);	
	});

	function imprimeContrato(){

		$.ajax({
            type: "GET",
            url: "ajxImpressaoContrato.do?id=<?=fnEncode($cod_empresa)?>",
            data: {idc:"<?=fnEncode($cod_contrat)?>"},
            success: function(data) {
                console.log(data);
                $("#impressao").printThis();
            }
        });

		// $("#impressao").printThis();

	}
</script>