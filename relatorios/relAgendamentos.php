<?php

//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$cod_usuarios_age = 0;
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $cod_univend = $_POST['COD_UNIVEND'];
        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);
        $cod_grupotr = $_REQUEST['COD_GRUPOTR'];
        $cod_tiporeg = $_REQUEST['COD_TIPOREG'];
        $num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
        $nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);

        if (isset($_POST['COD_USUARIOS_AGE'])) {
            $cod_usuarios_age = "";
            $Arr_COD_USUARIOS_AGE = $_POST['COD_USUARIOS_AGE'];
            //print_r($Arr_COD_USUARIOS_AGE);

            for ($i = 0; $i < count($Arr_COD_USUARIOS_AGE); $i++) {
                $cod_usuarios_age = $cod_usuarios_age . $Arr_COD_USUARIOS_AGE[$i] . ",";
            }

            $cod_usuarios_age = rtrim($cod_usuarios_age, ",");

        } else { $cod_usuarios_age = "0";}

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

        }

    }
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }

} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

//inicialização das variáveis - default
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

if (isset($_GET['dat_ini'])) {

    $dat_ini = $_GET['dat_ini'];
    $dat_fim = date("Y-m-d", strtotime($_GET['dat_fim'] . ' - 1 days'));

    // fnEscreve($dat_ini);
    // fnEscreve($dat_fim);

    if (isset($_GET['idU'])) {
        $cod_usuarios_age = "";
        $Arr_COD_USUARIOS_AGE = json_decode($_GET['idU']);
        //print_r($Arr_COD_USUARIOS_AGE);

        for ($i = 0; $i < count($Arr_COD_USUARIOS_AGE); $i++) {
            $cod_usuarios_age = $cod_usuarios_age . $Arr_COD_USUARIOS_AGE[$i] . ",";
        }

        $cod_usuarios_age = rtrim($cod_usuarios_age, ',');

    } else { $cod_usuarios_age = "0";}

    // fnEscreve($cod_usuarios_age);
	$usuariosAge = fnEncode($cod_usuarios_age);
}

//busca revendas do usuário
// include "unidadesAutorizadas.php";

//fnMostraForm();
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);

?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}

@media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
</style>

	<div class="push30"></div>

	<div class="row" id="div_Report">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<?php if ($popUp != "true") {?>
			<div class="portlet portlet-bordered">
			<?php } else {?>
			<div class="portlet" style="padding: 0 20px 20px 20px;" >
			<?php }?>

				<?php if ($popUp != "true") {?>
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"><?php echo $NomePg; ?> - <?php echo $nom_empresa ?></span>
					</div>
					<?php include "atalhosPortlet.php";?>
				</div>
				<?php }?>
				<div class="portlet-body">

					<?php if ($msgRetorno != '') {?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php }?>

					<div class="push30"></div>


					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						<?php if ($popUp != "true") {?>
						<fieldset>
							<legend>Filtros</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
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
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Responsáveis</label>
												<select data-placeholder="Selecione os responsáveis" name="COD_USUARIOS_AGE[]" id="COD_USUARIOS_AGE" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
													<?php

												    $sql = "select COD_USUARIO, NOM_USUARIO from usuarios
															where COD_EMPRESA = $cod_empresa AND usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
												    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

												    while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												        echo "
														  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option>
														";
												    }
    												?>
												</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>


								</div>

						</fieldset>

						<div class="push20"></div>

						<?php }else{ ?>

							<input type="hidden" name="DAT_INI" id="DAT_INI" value="<?php echo $dat_ini; ?>">
							<input type="hidden" name="DAT_FIM" id="DAT_FIM" value="<?php echo $dat_fim; ?>">
							<!-- <input type="hidden" name="COD_USUARIOS_AGE" id="COD_USUARIOS_AGE" value="<?php echo $usuariosAge; ?>"> -->

						<?php } ?>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo fnEncode($cod_empresa); ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>">
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>
						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>

									<table class="table table-bordered table-hover tablesorter" id="calendario">

									<thead>
										<tr>
											<th>Data</th>
											<th>Dia</th>
											<th>Horário</th>
											<th>Local</th>
											<th>Assunto</th>
											<th>Solicitantes</th>
											<th>Responsáveis</th>
											<th>ORG</th>
											<th>RT</th>
											<th>GT</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

									<?php

										$sql = "SELECT DISTINCT EA.*,
												(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 31)) AS ORG,
												(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 28)) AS RT,
												(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 29)) AS GT
												FROM EVENTOS_AGENDA EA
												LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EA.COD_EVENT
												WHERE EA.COD_EMPRESA = $cod_empresa
												AND (
													(EA.DAT_INI >= '$dat_ini' AND EA.DAT_INI <='$dat_fim' )
													OR (EA.DAT_FIM >= '$dat_ini' AND EA.DAT_FIM <='$dat_fim' )
													OR (EA.DAT_INI <= '$dat_ini' AND EA.DAT_FIM >='$dat_ini' )
													OR (EA.DAT_FIM <= '$dat_fim' AND EA.DAT_FIM >='$dat_fim' )
												)
												AND UE.COD_USUARIO IN($cod_usuarios_age)
												AND EA.COD_EXCLUSA = 0 
												ORDER BY EA.HOR_INI,EA.HOR_FIM";
										//fnTestesql(connTemp($cod_empresa,''),$sql);
										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT DISTINCT EA.*,
												(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 31)) AS ORG,
												(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 28)) AS RT,
												(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 29)) AS GT
												FROM EVENTOS_AGENDA EA
												LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EA.COD_EVENT
												WHERE EA.COD_EMPRESA = $cod_empresa
												AND (
													(EA.DAT_INI >= '$dat_ini' AND EA.DAT_INI <='$dat_fim' )
													OR (EA.DAT_FIM >= '$dat_ini' AND EA.DAT_FIM <='$dat_fim' )
													OR (EA.DAT_INI <= '$dat_ini' AND EA.DAT_FIM >='$dat_ini' )
													OR (EA.DAT_FIM <= '$dat_fim' AND EA.DAT_FIM >='$dat_fim' )
												)
												AND UE.COD_USUARIO IN($cod_usuarios_age)
												AND EA.COD_EXCLUSA = 0 
												ORDER BY EA.HOR_INI,EA.HOR_FIM
												LIMIT $inicio,$itens_por_pagina
												";

										//echo ($sql);

										//fnTestesql(connTemp($cod_empresa,''),$sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


										// DISTIBUI OS EVENTOS NAS DATAS DO MÊS, CONFORME A REPETIÇÃO CONFIFURADA
										$items = [];
										while ($qrAtend = mysqli_fetch_assoc($arrayQuery)) {
										    $dti = max($dat_ini, $qrAtend["DAT_INI"]);
										    $dtf = min($dat_fim, $qrAtend["DAT_FIM"]);
										    while ($dti <= $dtf) {
										        $repet = [];
										        if ($qrAtend["DIAS_REPETE"] != "") {
										            $repet = explode(",", $qrAtend["DIAS_REPETE"]);
										        } else {
										            $repet = [0, 1, 2, 3, 4, 5, 6];
										        }
										        $w = date('w', strtotime($dti));
										        if (in_array($w, $repet)) {
										            $items[$dti][] = $qrAtend;
										        }
										        $dti = date('Y-m-d', strtotime($dti . ' +1 day'));
										    }
										}
										$dts = array_keys($items);
										sort($dts);

										$diasSemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');


										// MOSTRA NA GRID OS EVENTOS
										$count = 0;
										foreach ($dts as $data) {
										    foreach ($items[$data] as $qrAtend) {
										        $nomUsuarios = "";
										        $nomSolicitantes = "";

										        $sql2 = "SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO IN(SELECT COD_USUARIO FROM USUARIO_EVENTO WHERE COD_EVENT = $qrAtend[COD_EVENT])";

										        $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

										        while ($qrUsu = mysqli_fetch_assoc($arrayQuery2)) {
										            $nomUsuarios .= ucwords(strtolower($qrUsu['NOM_USUARIO'])) . ", ";
										        }

										        $sql3 = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE IN(SELECT COD_CLIENTE FROM CLIENTE_EVENTO WHERE COD_EVENT = $qrAtend[COD_EVENT])";
										        $arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);
										        while ($qrCli = mysqli_fetch_assoc($arrayQuery3)) {
										            $nomSolicitantes .= ucwords(strtolower($qrCli['NOM_CLIENTE'])) . ", ";
										        }

										        $nomUsuarios = rtrim(trim($nomUsuarios), ",");
										        $nomSolicitantes = rtrim(trim($nomSolicitantes), ",");

										        $count++;
										        echo "
												<tr>
												<td>" . fnDataShort($data) . "</td>
												<td>" . $diasSemana[date('w', strtotime($data))] . "</td>
												<td>" . $qrAtend['HOR_INI']."-".$qrAtend['HOR_FIM'] . "</td>
												<td>" . $qrAtend['DES_LOCAL'] . "</td>
												<td>" . $qrAtend['NOM_EVENT'] . "</td>
												<td>" . $nomSolicitantes . "</td>
												<td>" . $nomUsuarios . "</td>
												<td>" . $qrAtend['ORG'] . "</td>
												<td>" . $qrAtend['RT'] . "</td>
												<td>" . $qrAtend['GT'] . "</td>
												</tr>
												";
										    }
										}

									?>
									</tbody>

									<tfoot class="no-print">
										<tr>
											<th>
												<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
											</th>
											<th>
												<a class="btn btn-info btn-sm print"> <i class="fa fa-print" aria-hidden="true"></i>&nbsp; Imprimir</a>
											</th>
										</tr>
										<tr>
										  <th class="" colspan="100">
											<center><ul id="paginacao" class="pagination-sm"></ul></center>
										  </th>
										</tr>
									</tfoot>

								</table>

							</div>


						</div>
					</div>

					<div class="push5"></div>

					<div class="push50"></div>

					<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>

	</div>

	<div class="push20"></div>

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	<script src='js/printThis.js'></script>

    <script>

		//datas
		$(function () {

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}

			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});

			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});

			var usuarios_age = '<?php echo $cod_usuarios_age; ?>';
			if(usuarios_age != 0 && usuarios_age != ""){
				//retorno combo multiplo - USUARIOS_AGE
			$("#formulario #COD_USUARIOS_AGE").val('').trigger("chosen:updated");

				var sistemasUni = usuarios_age;
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_USUARIOS_AGE option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");
				}
				$("#formulario #COD_USUARIOS_AGE").trigger("chosen:updated");
			}

			$(".print").click(function(){
				$("#calendario").printThis();
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
							action: function () {
								var nome = this.$content.find('.nome').val();
								if(!nome){
									$.alert('Por favor, insira um nome');
									return false;
								}

								$.confirm({
									title: 'Mensagem',
									type: 'green',
									icon: 'fa fa-check-square-o',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxRelAgendamentos.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&idU=<?php echo fnEncode($cod_usuarios_age); ?>",
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											console.log(response);
										}).fail(function(){
											self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										});
									},
									buttons: {
										fechar: function () {
											//close
										}
									}
								});
							}
						},
						cancelar: function () {
							//close
						},
					}
				});
			});

		});

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxRelAgendamentos.do?id=<?php echo fnEncode($cod_empresa); ?>&idU=<?php echo fnEncode($cod_usuarios_age); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);
					console.log(data);
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		}


	</script>
