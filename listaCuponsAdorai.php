<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$hotel = "";
$log_diaria = "";
$num_adultos = "";
$num_criancas = "";
$cod_hotel = "";
$num_pessoas = "";
$filtro_data = "";
$hoje = "";
$ontem = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_statuspag = "";
$cod_formapag = "";
$cod_propriedade = "";
$cod_chale = "";
$dat_ini = "";
$dat_fim = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$checkDiaria = "";
$formBack = "";
$abaAdorai = "";
$abaManutencaoAdorai = "";
$abaUsuario = "";
$sql2 = "";
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$qrBusca = "";
$qtd_uso = 0;
$validade = "";
$tip_desc = "";
$val_desconto = "";
$content = "";


//echo "<h5>_".$opcao."</h5>";

$hotel = "";
$log_diaria = 'N';
$num_adultos = 2;
$num_criancas = 0;
$cod_hotel = "2957,3010,3008,956";
$num_pessoas = 0;
$filtro_data = "RESERVA";
$itens_por_pagina = 50;
$pagina = 1;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$ontem = fnFormatDate(date('Y-m-d', strtotime($ontem . '-1 days')));


$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_statuspag = fnLimpaCampo(@$_POST['COD_STATUSPAG']);
		$cod_formapag = fnLimpaCampo(@$_POST['COD_FORMAPAG']);
		$cod_empresa = fnLimpaCampo(@$_POST['COD_EMPRESA']);
		$cod_propriedade = fnLimpaCampo(@$_POST['COD_PROPRIEDADE']);
		$cod_chale = fnLimpaCampo(@$_POST['COD_CHALE']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$filtro_data = fnLimpaCampo(@$_POST['FILTRO_DATA']);

		// fnEscreve($cod_hotel);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($ontem);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {

	$dat_fim = fnDataSql($hoje);
}

//fnMostraForm();

$checkDiaria = "";

if ($log_diaria == "S") {
	$checkDiaria = "checked";
}
$conn = conntemp($cod_empresa, "");

?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}

	tr {
		border-bottom: none !important;
	}

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

	/*Menu DropDown*/
	.menu {
		top: 0 !important;
		left: -100px !important;
		width: 100px !important;
		z-index: 9999999;
		font-size: 13px !important;
	}



	.menu li a {
		color: #3c3c3c !important;
	}



	.menu-down-right,
	.menu-down-left,
	.menu.menu--right {
		transform-origin: top left !important;
	}

	@media screen and (max-width:778px) {
		.dropleft ul {
			right: inherit !important;
		}
	}

	.panelBox {
		width: 40px;
		height: 40px;
		display: flex;
		align-content: center;
		align-items: center;
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
</div>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
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

				<?php
				$abaAdorai = 2006;
				include "abasAdorai.php";

				$abaManutencaoAdorai = fnDecode(@$_GET['mod']);
				//echo $abaUsuario;

				//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasSistemaAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código do Cupom</label>
										<input type="text" class="form-control input-sm" name="NOM_DESCTKT" id="NOM_DESCTKT" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Cupom</label>
										<input type="text" class="form-control input-sm" name="NOM_DESCTKT" id="NOM_DESCTKT" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Cliente</label>
										<input type="text" class="form-control input-sm" name="NOM_DESCTKT" id="NOM_DESCTKT" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= $dat_ini ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= $dat_fim ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>


						<div class="push10"></div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="LOG_ATUALIZA" id="LOG_ATUALIZA" value="N">
						<div class="push5"></div>

					</form>

					<div class="row">
						<div class="col-md-2">

							<div class="panelBox borda">

								<div class="addBox" data-url="action.php?mod=<?php echo fnEncode(2064) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Novo Cupom">
									<i class="fal fa-plus fa-2x" aria-hidden="true" style="align-self: center;"></i>
								</div>
							</div>

						</div>

						<div class="push20"></div>
					</div>

					<div class="no-more-tables">

						<form name="formLista">
							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class="text-center">Nom. Cupom</th>
										<th>Chave</th>
										<th>Qtd. Uso</th>
										<th>Validade</th>
										<th>Data Inicial</th>
										<th>Data Final</th>
										<th class='text-right'>Tip. Desconto</th>
										<th class='text-right'>Val. Desconto</th>
										<th width='40' class="{ sorter: false }"></th>
									</tr>
								</thead>
								<tbody id='div_refreshCarrinho'>

									<?php


									$sql2 = "
									SELECT * FROM CUPOM_ADORAI
									";

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql2);
									$totalitens_por_pagina = mysqli_num_rows($retorno);
									$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "
									SELECT * FROM CUPOM_ADORAI
									";
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$count = 0;

									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										switch ($qrBusca['LOG_QTDUSO']) {
											case 'I':
												$qtd_uso = "Ilimitada";
												break;

											default:
												$qtd_uso = $qrBusca['QTD_USO'];
												break;
										}

										switch ($qrBusca['LOG_VALIDADE']) {
											case 'I':
												$validade = "Indefinida";
												break;

											default:
												$validade = "Por Data";
												break;
										}

										switch ($qrBusca['TIP_DESCONTO']) {
											case '1':
												$tip_desc = "Valor fixo sobre DIÁRIAS";
												$val_desconto = "R$ " . fnValor($qrBusca['VAL_DESCONTO'], 2);
												break;
											case '2':
												$tip_desc = "Valor percentual sobre DIÁRIAS";
												$val_desconto = "% " . fnValor($qrBusca['VAL_DESCONTO'], 2);
												break;
											case '3':
												$tip_desc = "Percentual sobre TOTAL";
												$val_desconto = "% " . fnValor($qrBusca['VAL_DESCONTO'], 2);
												break;

											default:
												$tip_desc = "Valor fixo sobre TOTAL";
												$val_desconto = "R$ " . fnValor($qrBusca['VAL_DESCONTO'], 2);
												break;
										}

										echo "
									<tr>
									    <td class='text-center'>" . $qrBusca['NOM_CUPOM'] . "</td>
									    <td>" . $qrBusca['DES_CHAVECUPOM'] . "</td>                                                    
									    <td>" . $qtd_uso . "</td>                                                        
									    <td>" . $validade . "</td>                                                        
									    <td>" . fnDataShort($qrBusca['DAT_INI']) . "</td>                    
									    <td>" . fnDataShort($qrBusca['DAT_FIN']) . "</td>                    
									    <td class='text-right'>" . $tip_desc . "</td>                                                        
									    <td class='text-right'>" . $val_desconto . "</td>                                                                            
									    <td width='40' class='text-center'>
									        <small>
									            <div class='btn-group dropdown dropleft'>
									                <a href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
									                    <span style='opacity: 0.4;' class='fal fa-ellipsis-v fa-2x'></span>
									                </a>
									                <ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
									                    <li><a href='javascript:void(0)' class='addBox' data-url='action.php?mod=" . fnEncode(2064) . "&id=" . fnEncode($cod_empresa) . "&idc=" . fnEncode($qrBusca['COD_CUPOMADORAI']) . "&pop=true' data-title='Alterar Cupom'>Alterar </a></li>
									                </ul>
									            </div>
									        </small>
									    </td>
									</tr>";
									}
									?>

								</tbody>

								<div class="push20"></div>

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
						</form>

					</div>

					<div class="push20"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
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

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	// $(".exportarCSV").click(function() {
	// 	$.confirm({
	// 		title: 'Exportação',
	// 		content: '' +
	// 		'<form action="" class="formName">' +
	// 		'<div class="form-group">' +
	// 		'<label>Insira o nome do arquivo:</label>' +
	// 		'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
	// 		'</div>' +
	// 		'</form>',
	// 		buttons: {
	// 			formSubmit: {
	// 				text: 'Gerar',
	// 				btnClass: 'btn-blue',
	// 				action: function () {
	// 					var nome = this.$content.find('.nome').val();
	// 					if(!nome){
	// 						$.alert('Por favor, insira um nome');
	// 						return false;
	// 					}

	// 					$.confirm({
	// 						title: 'Mensagem',
	// 						type: 'green',
	// 						icon: 'fal fa-check-square-o',
	// 						content: function(){
	// 							var self = this;
	// 							return $.ajax({
	// 								url: "ajxCheckoutAdorai.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>", 
	// 								data: $('#formulario').serialize(),
	// 								method: 'POST'
	// 							}).done(function (response) {
	// 								self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
	// 								var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
	// 								SaveToDisk('media/excel/' + fileName, fileName);
	// 								console.log(response);
	// 							}).fail(function(){
	// 								self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
	// 							});
	// 						},							
	// 						buttons: {
	// 							fechar: function () {
	// 										//close
	// 							}									
	// 						}
	// 					});								
	// 				}
	// 			},
	// 			cancelar: function () {
	// 						//close
	// 			},
	// 		}
	// 	});				
	// });

	$(document).ready(function() {
		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('#popModal').on('hidden.bs.modal', function() {
			var atualiza = $('#LOG_ATUALIZA').val();

			if (atualiza == 'S') {
				location.reload();
			}
		});
	});


	function retornaForm(index) {
		$("#formulario #COD_STATUSPAG").val($("#ret_COD_STATUSPAG_" + index).val());
		$("#formulario #DES_STATUSPAG").val($("#ret_DES_STATUSPAG_" + index).val());
		$("#formulario #ABV_STATUSPAG").val($("#ret_ABV_STATUSPAG_" + index).val());
		$("#formulario #DES_COR").val($("#ret_DES_COR_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$('.datePicker').datetimepicker({
		format: 'DD/MM/YYYY'
	}).on('changeDate', function(e) {
		$(this).datetimepicker('hide');
	});

	$("#DAT_INI_GRP").on("dp.change", function(e) {
		$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
	});

	$("#DAT_FIM_GRP").on("dp.change", function(e) {
		$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
	});

	$("#DAT_INI").val("<?= fnDataShort($dat_ini) ?>");
	$("#DAT_FIM").val("<?= fnDataShort($dat_fim) ?>");
</script>