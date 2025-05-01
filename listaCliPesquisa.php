<?php

//echo "<h5>_".$opcao."</h5>";
$valores_pct = [];

$hashLocal = mt_rand();
$cod_personas = "";

if (isset($_GET['pop'])) {
	$popUp = fnLimpaCampo($_GET['pop']);
} else {
	$popUp = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_pesquisa = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		// $des_emailex = fnLimpaCampo($_POST['DES_EMAILEX']);

		if (isset($_POST['COD_PERSONA'])) {
			$Arr_COD_PERSONAS = $_POST['COD_PERSONA'];

			for ($i = 0; $i < count($Arr_COD_PERSONAS); $i++) {
				$cod_personas = $cod_personas . $Arr_COD_PERSONAS[$i] . ",";
			}

			$cod_personas = rtrim($cod_personas, ",");
			$cod_personas = ltrim($cod_personas, ",");
		} else {
			$cod_personas = "0";
		}

		// fnEscreve($cod_personas);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_RELAT_NPS_LISTA($cod_empresa, $cod_pesquisa, '$cod_personas', '$opcao')";

			// fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);

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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_pesquisa = fnDecode($_GET['idp']);
	$cod_empresa = fnDecode($_GET['id']);
	$des_dominio = fnDecode($_GET['idd']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sql = "SELECT DISTINCT COD_PERSONA FROM NPS_LISTA WHERE COD_PESQUISA = $cod_pesquisa";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$cod_persona = "";

while ($qrPersona = mysqli_fetch_assoc($arrayQuery)) {
	$cod_persona .= $qrPersona['COD_PERSONA'] . ',';
}

$cod_persona = ltrim($cod_persona, ',');

// fnEscreve($cod_persona);


if (isset($retorno) && $retorno != "") {
	$qrTot = mysqli_fetch_assoc($retorno);
	if (isset($qrTot['TOTAL_PERSONAS'])) {
		$tot_personas = $qrTot['TOTAL_PERSONAS'];
	} else {
		$tot_personas = "";
	}

	if (isset($qrTot['CLIENTES_UNICOS'])) {
		$clientes_unicos = $qrTot['CLIENTES_UNICOS'];
	} else {
		$clientes_unicos = "";
	}

	if (isset($qrTot['CLIENTES_UNICOS_EMAIL'])) {
		$clientes_unicos_email = $qrTot['CLIENTES_UNICOS_EMAIL'];
	} else {
		$clientes_unicos_email = "";
	}

	if (isset($qrTot['CLIENTES_UNICO_PERC'])) {
		$clientes_unico_perc = $qrTot['CLIENTES_UNICO_PERC'];
	} else {
		$clientes_unico_perc = "";
	}

	if (isset($qrTot['TOTAL_CLIENTE_EMAIL_NAO'])) {
		$total_cliente_email_nao = $qrTot['TOTAL_CLIENTE_EMAIL_NAO'];
	} else {
		$total_cliente_email_nao = "";
	}
} else {
	$pct_reserva = 10;
	$tot_personas = "0";
	$clientes_unicos = "0";
	$clientes_unicos_email = "0";
	$clientes_unico_perc = "0";
	$total_cliente_email_nao = "0";
}

if ($valores_pct != "") {
	$pct_reserva = array_search($pct_reserva, $valores_pct);
}

// fnescreve($des_dominio);

//fnMostraForm();

?>
<link rel="stylesheet" href="css/ion.rangeSlider.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />

<style>
	body {
		overflow-y: scroll;
		scrollbar-width: none;
		/* Firefox */
		-ms-overflow-style: none;
		/* IE 10+ */
	}

	body::-webkit-scrollbar {
		/* WebKit */
		width: 0;
		height: 0;
	}
</style>


<div class="row">

	<div class="col-md12 margin-bottom-30" id="corpo">
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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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

					<h4 style="margin: 0 0 5px 0;"><span class="bolder">Parâmetros de Geração da Lista</span></h4>
					<div class="push20"></div>


					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">



							<div class="row">

								<div class="col-sm-7">
									<div class="form-group">
										<label for="inputName" class="control-label">Personas para Geração da Lista</label>
										<div class="push10"></div>

										<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<option value=""></option>
											<?php

											$sql = "SELECT * from persona where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' order by DES_PERSONA  ";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												echo "
																		  <option value='" . $qrListaPersonas['COD_PERSONA'] . "'>" . ucfirst($qrListaPersonas['DES_PERSONA']) . "</option> 
																		";
											}

											?>
										</select>

									</div>

								</div>

								<!-- <div class="col-md-5">
													<div class="form-group">
														<label for="inputName" class="control-label">Emails Extras</label>
														<div class="push10"></div>
														<input type="text" class="form-control input-sm" name="DES_EMAILEX" id="DES_EMAILEX" maxlength="500" value="">
													</div>
													<div class="help-block with-errors">Separar múltiplos emails por ";"</div>
												</div>	 -->

							</div>

							<div class="push10"></div>

							<!-- <div class="row">

												<div class="col-md-2 col-md-offset-1 text-center">
													<h5>Personas Selecionadas</h5>
													<i class="fal fa-users fa-2x">&nbsp; </i><span class="f17" id="TOT_PERSONAS"><?= $tot_personas ?></span> 
												</div>
																	
												<div class="col-md-2 text-center">
													<h5>Clientes Únicos</h5>
													<i class="fal fa-user-tag fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_UNICOS"><?= $clientes_unicos ?></span> 
												</div>

												<div class="col-md-2 text-center">
													<h5>Clientes Únicos Com Email</h5>
													<i class="fal fa-envelope fa-2x">&nbsp; </i><span class="f17" id="CLIENTES_UNICOS_EMAIL"><?= $clientes_unicos_email ?></span> 
												</div>

												<div class="col-md-2 text-center">
													<h5>Clientes Únicos Sem Email</h5>
													<i class="fal fa-user-slash fa-2x">&nbsp; </i><span class="f17" id="TOTAL_CLIENTE_EMAIL_NAO"><?= $total_cliente_email_nao ?></span> 
												</div>

												<div class="col-md-2 text-center">
													<h5>Lista de Envio</h5>
													<i class="fal fa-paper-plane fa-2x">&nbsp; </i><span class="f17" id="LISTA_ENVIO"><?= ($clientes_unicos_email - $clientes_unico_perc) ?></span> 
												</div>
												
											</div> -->

							<!-- <div class="push10"></div>

											<div class="row">
												
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Manter Pessoas em Cópia</label>
														<input type="text" class="form-control input-sm" name="DES_PESSOAS" id="DES_PESSOAS" value="<?php echo "" ?>">
													</div>														
												</div>

											</div> -->

							<div class="push10"></div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<?php
								if ($cod_persona == 0) {
								?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Gerar Lista</button>
								<?php
								} else {
								?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar Lista</button>
									<a class="btn btn-info exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar Lista</a>
								<?php
								}
								?>



							</div>

							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_pesquisa ?>">
							<input type="hidden" name="DES_DOMINIO" id="DES_DOMINIO" value="<?= $des_dominio ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">


						</form>

						<div class="push30"></div>

						<!-- <div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th>Cliente</th>
													  <th>Email</th>
													  <th>Loja</th>
													  <th>Dt. Envio</th>
													  <th>Dt. Confirmação</th>
													</tr>
												  </thead>
												<tbody id="relatorioConteudo">
												  
												<?php

												$ARRAY_UNIDADE1 = array(
													'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
													'cod_empresa' => $cod_empresa,
													'conntadm' => $connAdm->connAdm(),
													'IN' => 'N',
													'nomecampo' => '',
													'conntemp' => '',
													'SQLIN' => ""
												);
												$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

												$sql = "SELECT COD_LISTA FROM EMAIL_LISTA
															WHERE COD_EMPRESA = $cod_empresa
															AND COD_CAMPANHA = $cod_pesquisa
															AND LOG_COMPARA = 1";

												// $num_clientes = mysqli_num_rows(mysqli_query(connTemp($cod_empresa,''),$sql));

												// $sql = "SELECT EL.NOM_CLIENTE, EL.DES_EMAILUS, CL.COD_UNIVEND FROM EMAIL_LISTA EL 
												// 		INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = EL.COD_CLIENTE
												// 		WHERE EL.COD_EMPRESA = $cod_empresa
												// 		AND EL.COD_CAMPANHA = $cod_pesquisa
												// 		AND EL.LOG_COMPARA = 1
												// 		ORDER BY NOM_CLIENTE
												// 		LIMIT 50";

												// //fnEscreve($sql);

												// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

												// $count=0;
												// while ($qrLista = mysqli_fetch_assoc($arrayQuery)){	

												// 	$count++;
												// 	$NOM_ARRAY_UNIDADE=(array_search($qrLista['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

												// 	echo"
												// 		<tr>
												// 		  <td>".$qrLista['NOM_CLIENTE']."</td>
												// 		  <td>".$qrLista['DES_EMAILUS']."</td>
												// 		  <td>".$ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']."</td>
												// 		  <td></td>
												// 				  <td></td>
												// 		</tr>
												// 	"; 
												// }											

												?>
													
												</tbody>
												</table>

												<?php
												// if($num_clientes > 50){ 
												?>
														<a class="btn btn-primary col-md-4 col-md-offset-4" type="button" id="loadMore">Carregar mais clientes da lista</a>
												<?php
												// } 
												?>
												
												</form>

											</div>
											
										</div> -->

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script src="js/plugins/ion.rangeSlider.js"></script>

	<script type="text/javascript">
		parent.$("#conteudoAba").css("height", (($(document).height()) + 100) + "px");
		//alert($(document).height());

		$(function() {

			var cont = 0;

			$('#loadMore').click(function() {

				cont += 50;

				$.ajax({
					type: "GET",
					url: "ajxAprovacaoEmail.php?acao=loadMore&itens=" + cont + "&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_pesquisa) ?>",
					beforeSend: function() {
						$('#loadMore').text('Carregando...');
					},
					success: function(data) {
						if (cont >= "<?= @$num_clientes ?>") {
							$('#loadMore').text('Todos os clientes já se encontam na lista');
							$('#loadMore').addClass('disabled');
						} else {
							$('#loadMore').text('Carregar mais clientes da lista');
						}
						$('#relatorioConteudo').append(data);

						parent.$("#conteudoAba").css("height", $(document).height() + "px");

						console.log(data);
					},
					error: function() {
						alert('Erro ao carregar...');
						console.log(data);
					}
				});
			});

			var cod_persona = '<?php echo $cod_persona; ?>';
			//alert(cod_persona);
			if (cod_persona != 0 && cod_persona != "") {
				//retorno combo multiplo - USUARIOS_ENV
				$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

				var sistemasUni = cod_persona;
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");
				}
				$("#formulario #COD_PERSONA").trigger('chosen:updated');

			}

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
											url: "ajxListaCliPesquisa.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&idp=<?= fnEncode($cod_pesquisa) ?>",
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

		function retornaForm(index) {
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>