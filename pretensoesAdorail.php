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
		$cod_hotel = fnLimpaCampoZero($_REQUEST['COD_HOTEL']);
		$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);
		$cod_atendente = fnLimpaCampoZero($_REQUEST['COD_ATENDENTE']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$dat_acesso = fnDataSql($_POST['DAT_ACESSO']);

		if (empty($_REQUEST['LOG_AGRUPA'])) {
			$log_agrupa = 'N';
		} else {
			$log_agrupa = $_REQUEST['LOG_AGRUPA'];
		}

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
	$dat_ini = ""; 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
	$dat_fim = ""; 
}
if (strlen($dat_acesso ) == 0 || $dat_acesso == "1969-12-31"){
	$dat_acesso = ""; 
}

$checkAgrupa = "";

if($log_agrupa == "S"){
	$checkAgrupa = "checked";
}

//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
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

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Acesso</label>
										
										<div class="input-group date datePicker" id="DAT_ACESSO_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_ACESSO" id="DAT_ACESSO" value="<?php echo fnFormatDate($dat_acesso); ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
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
										<label for="inputName" class="control-label">Atendente</label>
											<div id="relatorioChale">
												<select data-placeholder="Selecione o atendente" name="COD_ATENDENTE" id="COD_ATENDENTE" class="chosen-select-deselect">
													<option value=""></option>
													<?php
														$sqlCoord="SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS 
																   WHERE USUARIOS.COD_EMPRESA = 274
																   AND USUARIOS.COD_TPUSUARIO = 9
																   AND USUARIOS.LOG_ESTATUS = 'S'
																   AND USUARIOS.DAT_EXCLUSA IS NULL ORDER BY  USUARIOS.NOM_USUARIO";
														$arrayCoord = mysqli_query($connAdm->connAdm(),$sqlCoord) or die(mysqli_error());
														while($qrCoord = mysqli_fetch_assoc($arrayCoord))
														{
													?>
															<option value="<?=$qrCoord[COD_USUARIO]?>"><?=$qrCoord[NOM_USUARIO]?></option>
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
										<label for="inputName" class="control-label">Dt. Check-in</label>
										
										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Dt. Check-out</label>
										
										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Celular</label>
										<input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" value="<?=$num_celular?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Agrupar por Celular</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_AGRUPA" id="LOG_AGRUPA" class="switch" value="S" <?=$checkAgrupa?>>
											<span></span>
										</label>
									</div>
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
											<th>Celular</th>
											<th>Dt. Check-in</th>
											<th>Dt. Check-out</th>
											<th>Hotel</th>
											<th>Chalé</th>
											<th class="text-right {sorter: 'valorBr'}">Vl. Reserva</th>
											<th>Origem</th>
											<th>Dt. Acesso</th>
											<th>Atendente</th>
											<th class="{sorter:false}">Atendido</th>
											<th class="{sorter:false}">Fechado</th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

<?php

										if($dat_acesso != "" && $dat_acesso != "0"){
											$andAcesso = "AND LA.DAT_ACESSO BETWEEN '$dat_acesso 00:00:00' AND '$dat_acesso 23:59:59'";
										}else{
											$andAcesso = "";
										}

										if($dat_ini != "" && $dat_ini != "0"){
											$andIni = "AND AND LA.DAT_INI = '$dat_ini'";
										}else{
											$andIni = "";
										}

										if($dat_fim != "" && $dat_fim != "0"){
											$andfim = "AND AND LA.DAT_FIM = '$dat_fim'";
										}else{
											$andfim = "";
										}

										if($cod_hotel != "" && $cod_hotel != "0"){
											$andHotel = "AND LA.COD_HOTEL IN($cod_hotel)";
										}else{
											$andHotel = "";
										}

										if($cod_chale != "" && $cod_chale != "0"){
											$andChale = "AND LA.COD_CHALE = $cod_chale";
										}else{
											$andChale = "";
										}

										if($cod_atendente != "" && $cod_atendente != "0"){
											$andAtendente = "AND LA.COD_ATENDENTE = $cod_atendente";
										}else{
											$andAtendente = "";
										}

										if($num_celular != ""){
											$andCelular = "AND LA.NUM_CELULAR = '$num_celular'";
										}else{
											$andCelular = "";
										}

										if($log_agrupa == "S"){
											$groupBy = "GROUP BY LA.NUM_CELULAR";
										}else{
											$groupBy = "";
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

										$sql = "SELECT LA.* FROM LINK_ADORAI LA
												WHERE LA.NUM_CELULAR != ''
												$andAcesso
												$andIni
												$andfim
												$andHotel
												$andChale
												$andAtendente
												$andCelular";

										// fnEscreve($sql);
										
										$retorno = mysqli_query(conntemp($cod_empresa,""),$sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);
										
										$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
										
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT LA.*, US.NOM_USUARIO FROM LINK_ADORAI LA
												LEFT JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_ATENDENTE
												WHERE LA.NUM_CELULAR != ''
												$andAcesso
												$andIni
												$andfim
												$andHotel
												$andChale
												$andAtendente
												$andCelular
												$groupBy
												ORDER BY DAT_ACESSO DESC
												LIMIT $inicio, $itens_por_pagina";

										// fnEscreve($sql);

										$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

										// TOTALIZADORES --------------------------------------------

										$totalRegistros = $total_itens_por_pagina;
										$vl_totreserva = 0;

										while ($qrTotal = mysqli_fetch_assoc($retorno)) {
											$val_reserva = 0;
											if($qrTotal['VAL_RESERVA'] != ""){
												$val_reserva = $qrTotal['VAL_RESERVA'];
											}else{
												$parts = parse_url($qrTotal['DES_LINK_ORIGEM']);
												parse_str($parts['query'], $query);
												$val_reserva = base64_decode($query[iv]);
											}
											$vl_totreserva += $val_reserva;
										}

										$sql = "SELECT LA.* FROM LINK_ADORAI LA
												WHERE LA.NUM_CELULAR != ''
												$andAcesso
												$andIni
												$andfim
												$andHotel
												$andChale
												$andAtendente
												$andCelular
												GROUP BY LA.NUM_CELULAR";

										// fnEscreve($sql);
										
										$arrGroup = mysqli_query(conntemp($cod_empresa,""),$sql);
										$totGroup = mysqli_num_rows($arrGroup);

										while ($qrTotal2 = mysqli_fetch_assoc($arrGroup)) {
											$val_reserva = 0;
											if($qrTotal2['VAL_RESERVA'] != ""){
												$val_reserva = $qrTotal2['VAL_RESERVA'];
											}else{
												$parts = parse_url($qrTotal2['DES_LINK_ORIGEM']);
												parse_str($parts['query'], $query);
												$val_reserva = base64_decode($query[iv]);
											}
											$vl_totreservaGroup += $val_reserva;
										}

										// -----------------------------------------------------------

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

											if($qrBuscaModulos['DAT_OK'] != ''){
												$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'><span class='fa fa-check'></span></a>";
											}else{
												$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okMisto(\"".fnEncode($qrBuscaModulos['COD_LINK'])."\",\"okReserva\")'><span class='fa fa-flag'></span></a>";
											}

											if($qrBuscaModulos['DAT_FECHAMENTO'] != ''){
												$log_fechado = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'><span class='fa fa-thumbs-up'></span></a>";
											}else{
												$log_fechado = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okMisto(\"".fnEncode($qrBuscaModulos['COD_LINK'])."\",\"okFechamento\")'><span class='fa fa-thumbs-down'></span></a>";
											}

											if($qrBuscaModulos['VAL_RESERVA'] != ""){
												$val_reserva = $qrBuscaModulos['VAL_RESERVA'];
											}else{
												$parts = parse_url($qrBuscaModulos['DES_LINK_ORIGEM']);
												parse_str($parts['query'], $query);
												$val_reserva = base64_decode($query[iv]);
											}

?>
											
											
												<tr id='<?=fnEncode($qrBuscaModulos['COD_LINK'])?>'>
													<td><small><?=$qrBuscaModulos['COD_LINK']?></small></td>
													<td><small><?=$qrBuscaModulos['NUM_CELULAR']?></small></td>
													<td><small><?=fnDataShort($qrBuscaModulos['DAT_INI'])?></small></td>
													<td><small><?=fnDataShort($qrBuscaModulos['DAT_FIM'])?></small></td>
													<td><small><?=$nomeHotel?></small></td>
													<td><small><?=$nomeChale?></small></td>
													<td class="text-right"><small><?=fnValor($val_reserva,2)?></small></td>
													<td><small><?=$qrBuscaModulos['DES_CANAL']?></small></td>
													<td><small><?=fnDataFull($qrBuscaModulos['DAT_ACESSO'])?></small></td>
													<td>
													  	<a href="#" class="editable-atendente" 
														  	data-type='select' 
														  	data-title='Editar Atentente'
														  	data-id="<?=fnEncode($cod_empresa)?>"
														  	data-pk="<?=fnencode($qrBuscaModulos['COD_LINK'])?>" 
														  	data-name="COD_ATENDENTE" 
														  	data-count="<?php echo $count; ?>"><?=$qrBuscaModulos['NOM_USUARIO']?>
													  		
													  	</a>
												  	</td>
													<td><small><?=$log_ok?></small></td>
													<td><small><?=$log_fechado?></small></td>
												</tr>

<?php 
											

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

		<div class="push10"></div>

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">


						<div class="row text-center">

							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p><span><?php echo fnValor($totalRegistros, 0); ?></span></p>
								<p><b>Total de Leads</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($vl_totreserva, 2); ?></span></p>
								<p><b>Vl. Total de Reservas</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p><span><?php echo fnValor($totGroup, 0); ?></span></p>
								<p><b>Total Leads Agrupados</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-3 col-lg-3">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($vl_totreservaGroup, 2); ?></span></p>
								<p><b>Vl. Total de Reservas Agrupadas</b></p>

								<div class="push20"></div>

							</div>


						</div>

					</div>
					<!-- fim Portlet -->
				</div>

			</div>

		</div>

	</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">

	const myTimeout = setTimeout(refresh, 300000);

	let atendentes = [];

	combos(atendentes);

	function refresh() {
	  // reloadPage(1);
		location.reload();
	}

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
										url: "ajxRelPretensoesAdorai.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

		$('.editable-atendente').editable({ 
	    	emptytext: '_______________',  
	        source: atendentes,
	        url: 'ajxAtendentePretensoes.php',
    		ajaxOptions:{type:'post'},
    		params: function(params) {
		        params.count = $(this).data('count');
		        params.id = $(this).data('id');
		        return params;
		    },
    		success:function(data){
				console.log(data);
			}
	    });

	});

	function okMisto(cod_link, tipo){
		$.ajax({
			type: "POST",
			url: "ajxRelPretensoesAdorai.php?opcao=ok&tipo="+tipo+"&id=<?=fnEncode($cod_empresa)?>",
			data: {COD_LINK:cod_link},
			beforeSend:function(){	
				$('#'+cod_link).html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$('#'+cod_link).html(data);
			},
			error:function(){
				alert('Erro ao carregar...');
				console.log(data);
			}
		});
	}



	function combos($atendentes){
		<?php 
			
			$sqlCoord="SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS 
					   WHERE USUARIOS.COD_EMPRESA = 274
					   AND USUARIOS.COD_TPUSUARIO = 9
					   AND USUARIOS.LOG_ESTATUS = 'S'
					   AND USUARIOS.DAT_EXCLUSA IS NULL ORDER BY  USUARIOS.NOM_USUARIO";
			$arrayCoord = mysqli_query($connAdm->connAdm(),$sqlCoord) or die(mysqli_error());
			while($qrCoord = mysqli_fetch_assoc($arrayCoord))
			{
				?>
					usuario = {value: "<?=$qrCoord['COD_USUARIO']?>", text: "<?=$qrCoord['NOM_USUARIO']?>"};
					$atendentes.push(usuario);
				<?php 
			}
			
		?>
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxRelPretensoesAdorai.do?id=<?=fnEncode($cod_empresa)?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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