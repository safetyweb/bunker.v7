<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

/* Set the default timezone */
setlocale(LC_ALL, NULL);
setlocale(LC_ALL, 'pt_BR');  					
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
	$cod_profiss = fnDecode($_GET['idp']);
	$cod_cliente = fnDecode($_GET['idc']);
	
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


$sqlCli = "SELECT CL.*, MU.NOM_MUNICIPIO, ES.UF FROM CLIENTES CL
            LEFT JOIN ESTADO ES ON ES.COD_ESTADO = CL.COD_ESTADO
            LEFT JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = CL.COD_MUNICIPIO
            WHERE CL.COD_EMPRESA = $cod_empresa 
            AND CL.COD_CLIENTE = $cod_cliente";

$arrayCli = mysqli_query(connTemp($cod_empresa,''), $sqlCli);
$qrCli = mysqli_fetch_assoc($arrayCli);

$sqlDadosCli = "SELECT * FROM DADOS_APOIADOR
	            WHERE COD_EMPRESA = $cod_empresa 
	            AND COD_CLIENTE = $cod_cliente";

$arrayDadosCli = mysqli_query(connTemp($cod_empresa,''), $sqlDadosCli);
$qrDadosCli = mysqli_fetch_assoc($arrayDadosCli);

$cod_univend = $qrCli[COD_UNIVEND];

$pessoa = "FÍSICA";
$letraPessoa = "F";

if($qrCli[LOG_JURIDICO] == "S"){
    $pessoa = "JURÍDICA";
    $letraPessoa = "J";
}

if($cod_profiss == 364){
	$titulo = "FUNÇÃO DIVULGADOR CAMPANHA";
}else{
	$titulo = "FUNÇÃO LÍDER EQUIPE DE DIVULGAÇÃO";
}


?>

<style>
	.contrato {
		text-align: justify;
		max-width: 700px;
		margin-left: auto;
		margin-right: auto;
	}

	.contrato::before {
		/*width: 10%;
		min-width: 100px;
		max-width: 200px;*/
	}

	.clausula {
		/*margin-left: 20px;*/
		font-weight: bold;
		text-align: justify;
	}

	.paragrafo {
		text-align: justify;
		/*margin-left: 20px;
		padding: 20px;*/
	}

	.assinatura {
	}
	h1 {
		margin:20px;
	}

	.text-right{
		float: right;
	}

	@media print {
		/*.contrato {
			text-align: center;
			display: inline-block;
		}

		.contrato::before {
			width: 10%;
			min-width: 100px;
			max-width: 200px;
			display: inline-block;
		}

		.clausula {
			text-align: left;
			margin-left: 20px;
			font-weight: bold;
			font-size: 14px;
		}

		.paragrafo {
			text-align: left;
			margin-left: 20px;
			margin: 20px ;
			font-size: 14px;
		}*/

		.assinatura {/*
			line-height:20px;
			color:red;*/
		}
		body {
			margin-left:85px;
			margin-right:65px;
			margin-top:30px;
		}
	}
</style>


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

							<!-- <div class="contrato" id="impressao1" style="display: <?=$diplayCabo?>"> -->
							<div class="contrato" id="impressao1">

								<div class="col-md-12 text-center">
									<b>ELEIÇÕES 2022</b>
									<br/>
									<br/>
									<b>FICHA CADASTRAL</b>
									<br/>
									<br/>
									<b><?=$titulo?></b>
									<br/>
									<br/>
									<br/>
									<br/>
								</div>


								<div class="col-md-12">
									<b>Nome:</b> <?=ucwords(strtolower($qrCli[NOM_CLIENTE]))?> <span class="text-right"><b>Data de Nascimento:</b> <?=$qrCli[DAT_NASCIME]?></span>
									<br/>
									<b>RG Nº:</b> <?=$qrCli[NUM_RGPESSO]?>
									<br/>
									<b>CPF Nº:</b> <span class="cpfcnpj"><?=fnCompletaDoc($qrCli['NUM_CGCECPF'],"$letraPessoa")?></span>
									<br/>
									<b>Título de Eleitor:</b> <?=$qrDadosCli[NUM_TITULO]?> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<b>Seção:</b> <?=$qrDadosCli[NUM_SECAO]?> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<b>Zona:</b> <?=$qrDadosCli[DES_ZONA]?>
									<br/>
									<b>Endereço:</b> <?=$qrCli[DES_ENDEREC]?>, <?=$qrCli[NUM_ENDEREC]?> <span class="text-right"><b>Bairro:</b> <?=$qrCli[DES_BAIRROC]?></span>
									<br/>
									<b>Município:</b> <?=$qrCli[NOM_MUNICIPIO]?> <span class="text-right"><b>CEP.:</b> <?=$qrCli[NUM_CEPOZOF]?></span>
								</div>

								<div class="col-md-12">
									<br/>
									<br/>
									<br/>
									<br/>
									<center><b>DADOS BANCÁRIOS</b></center> 
									<br/>
									<br/>
									<table class="table table-bordered">

										<thead>

											<tr>
												<th class="text-center">Banco</th>
												<th class="text-center">Agência</th>
												<th class="text-center">Conta Corrente</th>
											</tr>

										</thead>

										<tbody>

											<?php 

												$sql = "SELECT * FROM DADOS_BANCARIOS 
														WHERE COD_EMPRESA = $cod_empresa 
														AND COD_CLIENTE = $cod_cliente
														AND LOG_JURIDICO = 'N'";

												// fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
												
												$count=0;
												while ($qrDados = mysqli_fetch_assoc($arrayQuery)){	

												if($qrPix['TIP_PIX'] != ""){
													continue;
												}	


										?>

													<tr>
														<td class="text-center"><?=$qrDados['NUM_BANCO']?></td>
														<td class="text-center"><?=$qrDados['NUM_AGENCIA']?></td>
														<td class="text-center"><?=$qrDados['NUM_CONTACO']?></td>
													</tr>

										<?php 

											} 

										?>

										</tbody>

									</table>
									<br/>
										<?php 

											$sql = "SELECT * FROM DADOS_BANCARIOS 
													WHERE COD_EMPRESA = $cod_empresa 
													AND COD_CLIENTE = $cod_cliente
													AND LOG_JURIDICO = 'N'
													AND NUM_PIX != ''";

													// fnEscreve($sql);
											$arrayPix = mysqli_query(connTemp($cod_empresa,''),$sql);
											$qrPix = mysqli_fetch_assoc($arrayPix);

											switch ($qrPix['TIP_PIX']) {

										  		case 1:
									  				$tip_pix = "Celular";
									  			break;

									  			case 2:
									  				$tip_pix = "Email";
									  			break;

									  			case 3:
									  				$tip_pix = "CPF/CNPJ";
									  			break;
										  		
										  		default:
									  				$tip_pix = "";
									  			break;

										  	}

										?>
										<b>Chave PIX (<?=$tip_pix?>):</b> <?=$qrPix['NUM_PIX']?>
									
								</div>

								<div class="col-md-12">
									<br/>
									<br/>
									<br/>
									<br/>
									<br/>
									<br/>
									Subscrevo a presente ficha, reconhecendo como verdadeiro o seu conteúdo. Qualquer omissão de informação
									ou apresentação de declaração, dados ou documentos divergentes podem acarretar atraso na formalização do
									contrato.
									<br/>
									<br/>
									<br/>
									<br/>
								</div>
								<div class="col-md-12">
									<br/>
									___________________________________________________
									<br/>
									<?=ucwords(strtolower($qrCli['NOM_CLIENTE']))?>
									<br/>
									CNPJ/CPF <span class="cpfcnpj"><?=fnCompletaDoc($qrCli['NUM_CGCECPF'],"$letraPessoa")?></span>
									<br/>
									<br/>
									<br/>
									<br/>
								</div>

						</div>

					</div>

					<div class="push10"></div>
					<button type="button" class="btn btn-info addBox pull-left" id="print"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Impressão da Ficha </button>
					<div class="push50"></div>
				</div>
				<!-- fim Portlet -->
			</div>

		</div>
	</div>
</div>

<div class="push20"></div>

<script src='js/printThis.js'></script>

<script type="text/javascript">
	$(function(){
		$("#print").click(function() {
			// let impressao = document.getElementById("impressao").innerHTML;
			// let a = window.open('', '', 'height=3508, widht=2480');
			// a.document.write('<html>');
			// a.document.write('<body>');
			// a.document.write(impressao);
			// a.document.write('</body></html>');
			// a.document.close();
			// a.print();

			$("#impressao").printThis();
		});
	});
</script>