<?php

include '_system/_functionsMain.php'; 

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');					
date_default_timezone_set("america/sao_paulo");

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$contratos_cliente = fnLimpaCampo($_POST['CONTRATOS_CLIENTE']);

$contratos_cliente = explode(",", $contratos_cliente);

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

		.quebra {page-break-after: always!important;}
		/*body {
			margin-left:75px;
			margin-right:65px;
			margin-top:10px;
		}*/
	}
</style>

<meta charset="UTF-8">



<div class="contrato">

	<?php 

	foreach ($contratos_cliente as $cod_contrat) {
		
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
				$formaPag = "TED/DOC";
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
		<div class="quebra"> </div>
	<?php 

		$sql = "UPDATE CONTRATO_ELEITORAL SET 
						NUM_IMPRESSAO = (NUM_IMPRESSAO+1)
				WHERE COD_EMPRESA = $cod_empresa
				AND COD_CONTRAT = $cod_contrat";

		mysqli_query(connTemp($cod_empresa,''), $sql);

	}

	?>

</div>

<!-- fim Portlet -->

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

</script>