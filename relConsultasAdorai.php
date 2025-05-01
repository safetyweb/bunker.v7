<?php

//echo "<h5>_".$opcao."</h5>";

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;

$dias30="";
//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CELULAR']));
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_origem = fnLimpaCampo($_REQUEST['DES_ORIGEM']);
		$cod_hotel = fnLimpaCampoZero($_REQUEST['COD_HOTEL']);
		$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			
			
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
	$dat_ini = fnDataSql($dias30); 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
	$dat_fim = fnDataSql($hoje); 
}

//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
					$abaAdorai = 1833;
					include "abasAdorai.php";

					$abaManutencaoAdorai = 1864;
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Origem</label>
										<select data-placeholder="Selecione a origem" name="DES_ORIGEM" id="DES_ORIGEM" class="chosen-select-deselect" >
											<option value=""></option>
											<option value="SITE">Site</option>
											<option value="BUNKER">Bunker</option>
											<?php
												$sqlOrig = "SELECT DISTINCT DES_ORIGEM FROM ACESSOS_ADORAI WHERE COD_EMPRESA = $cod_empresa AND DES_ORIGEM NOT IN('SITE','BUNKER','') ORDER BY DES_ORIGEM";
												$arrayOrig = mysqli_query(connTemp($cod_empresa,''), $sqlOrig);

												while ($qrOrig = mysqli_fetch_assoc($arrayOrig)) {
											?>
													<option value="<?=$qrOrig[DES_ORIGEM]?>"><?=$qrOrig[DES_ORIGEM]?></option>
											<?php 
												}
											?>
										</select>
									<div class="help-block with-errors"></div>
									<script type="text/javascript">$("#DES_ORIGEM").val("<?=$des_origem?>").trigger("chosen:updated")</script>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Localidade</label>
										<select data-placeholder="Selecione a localidade" name="COD_HOTEL" id="COD_HOTEL" class="chosen-select-deselect">
											<option value=""></option>
											<?php
												$sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
												$arrayHotel = mysqli_query(connTemp($cod_empresa,''), $sqlHotel);

												while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
											?>
													<option value="<?=$qrHotel[COD_EXTERNO]?>"><?=$qrHotel[NOM_FANTASI]?></option>
											<?php 
												}
											?>
										</select>
									<div class="help-block with-errors"></div>
									<script type="text/javascript">$("#COD_HOTEL").val("<?=$cod_hotel?>").trigger("chosen:updated")</script>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Chalé</label>
										<div id="relatorioChale">
											<select data-placeholder="Selecione o chalé" name="COD_CHALE" id="COD_CHALE" class="chosen-select-deselect">
												<option value=""></option>
												<?php
													$sqlChale = "SELECT COD_EXTERNO, NOM_QUARTO FROM ADORAI_CHALES WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0";
													$arrayChale = mysqli_query(connTemp($cod_empresa,''), $sqlChale);

													while ($qrChale = mysqli_fetch_assoc($arrayChale)) {
												?>
														<option value="<?=$qrChale[COD_EXTERNO]?>"><?=$qrChale[NOM_QUARTO]?></option>
												<?php 
													}
												?>
											</select>
										</div>
									<div class="help-block with-errors"></div>
									<script type="text/javascript">$("#COD_CHALE").val("<?=$cod_chale?>").trigger("chosen:updated")</script>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Dt. Inicio Check-in</label>
									
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
									<label for="inputName" class="control-label required">Dt. Fim Check-in</label>
									
									<div class="input-group date datePicker" id="DAT_INI_GRP">
										<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<!-- <div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Check-out</label>
									
									<div class="input-group date datePicker" id="DAT_FIM_GRP">
										<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div> -->

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Celular</label>
									<input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" value="<?=$num_celular?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
							<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
							<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button> -->
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th>Código</th>
											<th>Origem</th>
											<th>Celular</th>
											<th>Dt. Cadastro</th>
											<th>Dt. Check-in</th>
											<th>Hotel</th>
											<th>Chalé</th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										if($des_origem != ""){
											$andOrigem = "AND AD.DES_ORIGEM = '$des_origem'";
										}else{
											$andOrigem = "";
										}

										if($cod_hotel != "" && $cod_hotel != "0"){
											$andHotel = "AND AD.COD_HOTEL IN($cod_hotel)";
										}else{
											$andHotel = "";
										}

										if($cod_chale != "" && $cod_chale != "0"){
											$andChale = "AND AD.COD_CHALE = $cod_chale";
										}else{
											$andChale = "";
										}

										if($num_celular != ""){
											$andCelular = "AND AD.NUM_CELULAR = '$num_celular'";
										}else{
											$andCelular = "";
										}

										$ARRAY_UNIDADE1=array(
													   'sql'=>"SELECT COD_UNIVEND,COD_EXTERNO,COD_EMPRESA,NOM_FANTASI,NOM_UNIVEND FROM UNIDADEVENDA WHERE COD_EMPRESA=$cod_empresa AND COD_EXCLUSA=0 AND LOG_ESTATUS = 'S'",
													   'cod_empresa'=>$cod_empresa,
													   'conntadm'=>$connAdm->connAdm(),
													   'IN'=>'N',
													   'nomecampo'=>'',
													   'conntemp'=>'',
													   'SQLIN'=> ""   
													   );
										$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

										$ARRAY_UNIDADE2=array(
													   'sql'=>"SELECT COD_EXTERNO, NOM_QUARTO FROM ADORAI_CHALES WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0",
													   'cod_empresa'=>$cod_empresa,
													   'conntadm'=>conntemp($cod_empresa,""),
													   'IN'=>'N',
													   'nomecampo'=>'',
													   'conntemp'=>'',
													   'SQLIN'=> ""   
													   );
										$ARRAY_CHALES=fnUnivend($ARRAY_UNIDADE2);

										$sql = "SELECT AD.* FROM ACESSOS_ADORAI AD
												WHERE AD.COD_EMPRESA = $cod_empresa
												AND AD.DAT_INI BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												$andOrigem
												$andHotel
												$andChale
												$andCelular";

										// fnEscreve($sql);
										
										$retorno = mysqli_query(conntemp($cod_empresa,""),$sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);
										
										$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
										
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT AD.* FROM ACESSOS_ADORAI AD
												WHERE AD.COD_EMPRESA = $cod_empresa
												AND AD.DAT_INI BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												$andOrigem
												$andHotel
												$andChale
												$andCelular
												ORDER BY DAT_INI ASC, DAT_CADASTR DESC
												LIMIT $inicio, $itens_por_pagina";

										// fnEscreve($sql);

										$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

											$count++;

											$nomeHotel = "";
											$nomeChale = "";

											if($qrBuscaModulos['COD_HOTEL'] == "2957,3010,3008,956" || $qrBuscaModulos['COD_HOTEL'] == "2957,3010,956,3008"){

												$nomeHotel = "Todas as Localidades";

											}else{

												$hoteis = explode(",", $qrBuscaModulos['COD_HOTEL']);

												foreach ($hoteis as $codExtHotel) {
													$NOM_ARRAY_UNIDADE=(array_search($codExtHotel, array_column($ARRAY_UNIDADE, 'COD_EXTERNO')));
													$nomeHotel .= $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['NOM_FANTASI'].", ";
												}

											}

											$nomeHotel = rtrim(ltrim(trim($nomeHotel),","),",");

											if($qrBuscaModulos['COD_CHALE'] != 0){
												$NOM_ARRAY_CHALE=(array_search($qrBuscaModulos['COD_CHALE'], array_column($ARRAY_CHALES, 'COD_EXTERNO')));
												$nomeChale = $ARRAY_CHALES[$NOM_ARRAY_CHALE]['NOM_QUARTO'];
											}
											
											echo "
												<tr>
													<td>" . $qrBuscaModulos['COD_ACESSO'] . "</td>
													<td>" . $qrBuscaModulos['DES_ORIGEM'] . "</td>
													<td>" . $qrBuscaModulos['NUM_CELULAR'] . "</td>
													<td><small>" . fnDataFull($qrBuscaModulos['DAT_CADASTR']) . "</small></td>
													<td><small>" . fnDataShort($qrBuscaModulos['DAT_INI']) . "</small></td>
													<td>" . $nomeHotel . "</td>
													<td>" . $nomeChale . "</td>
												</tr>
											";

										}

										?>

									</tbody>

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

					</div>

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

<script type="text/javascript">

	$(function(){

		var numPaginas = <?php echo $numPaginas; ?>;
		if(numPaginas != 0){
			carregarPaginacao(numPaginas);
		}

		$('.datePicker').datetimepicker({
			 format: 'DD/MM/YYYY'
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});
		
		$("#DAT_INI_GRP").on("dp.change", function (e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});
		
		$("#DAT_FIM_GRP").on("dp.change", function (e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

		$("#COD_HOTEL").on('change', function(){

			$.ajax({
				type: "POST",
				url: "ajxChalesConsulta.do?id=<?=fnEncode($cod_empresa)?>",
				data: {COD_HOTEL: $("#COD_HOTEL").val()},
				beforeSend:function(){
					$('#relatorioChale').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioChale").html(data);										
				},
				error:function(data){
					console.log(data);
					$('#relatorioChale').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});	

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
										url: "ajxRelConsultasAdorai.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxRelConsultasAdorai.do?idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend:function(){
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$("#relatorioConteudo").html(data);										
			},
			error:function(data){
				console.log(data);
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});		
	}

</script>