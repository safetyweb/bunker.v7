<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 200;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;
			
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);

			//array dos filtros
			if (isset($_POST['COD_FILTRO'])){
				$arr_cod_filtro = $_POST['COD_FILTRO'];
				//print_r($Arr_COD_FILTRO);			 
			 
			   for ($i=0;$i<count($arr_cod_filtro);$i++) 
			   { 
				$cod_filtro = $cod_filtro.$arr_cod_filtro[$i].",";
			   } 
			   
			   $cod_filtro = rtrim($cod_filtro,',');
				
			}else{$cod_filtro = "0";}

			if (isset($_POST['COD_USUARIO'])){
				$arr_cod_usuario = $_POST['COD_USUARIO'];
				//print_r($Arr_COD_FILTRO);			 
			 
			   for ($i=0;$i<count($arr_cod_usuario);$i++) 
			   { 
				$cod_usuario = $cod_usuario.$arr_cod_usuario[$i].",";
			   } 
			   
			   $cod_usuario = rtrim($cod_usuario,',');
				
			}else{$cod_usuario = "0";}

			if (isset($_POST['COD_MUNICIPIO_E'])){
				$arr_cod_municipio_e = $_POST['COD_MUNICIPIO_E'];
				//print_r($Arr_COD_FILTRO);			 
			 
			   for ($i=0;$i<count($arr_cod_municipio_e);$i++) 
			   { 
				$cod_municipio_e = $cod_municipio_e.$arr_cod_municipio_e[$i].",";
			   } 
			   
			   $cod_municipio_e = rtrim($cod_municipio_e,',');
				
			}else{$cod_municipio_e = "0";}

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$cod_campanha = fnDecode($_GET['idc']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_ESTADO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$cod_estado = $qrBuscaEmpresa['COD_ESTADO'];
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
		$cod_estado = 0;	
	}

	$sqlInd = "SELECT COD_PERFILS FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
	$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sqlInd)));
	// fnEscreve($cod_empresa);

	if($qrUsu['COD_PERFILS'] == 1154){
	  $cod_usuario = $_SESSION[SYS_COD_USUARIO];
	  $disableCombo = "disabled";
	}else{
	  // $cod_indicad = "";
	  $disableCombo = "";
	}

	// echo "_ ".$_SESSION[SYS_COD_USUARIO]." _";
	
?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
</style>
		
	<div class="push30"></div> 
	
	<div class="row" id="div_Report">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
					</div>
					
					<?php 
					//$formBack = "1015";
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
						
				
					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
													
						<fieldset>
							<legend>Filtros</legend> 
							
								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Indicadores</label>
												<select data-placeholder="Selecione os apoiadores" name="COD_USUARIO[]" id="COD_USUARIO" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1" <?=$disableCombo?>>
													<?php 
													
														$sql = "SELECT A.COD_USUARIO, A.NOM_USUARIO FROM WEBTOOLS.USUARIOS A
																INNER JOIN REGIAO_USUARIO RU ON RU.COD_USUARIO = A.COD_USUARIO
																WHERE A.COD_EMPRESA = $cod_empresa AND A.LOG_ESTATUS = 'S' 
																GROUP BY A.COD_USUARIO
																ORDER BY A.NOM_USUARIO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
														while ($qrUsu = mysqli_fetch_assoc($arrayQuery))
														  {														
															echo"
																  <option value='".$qrUsu['COD_USUARIO']."'>".$qrUsu['NOM_USUARIO']."</option> 
																"; 
															  }											
													?>	
												</select>
											<div class="help-block with-errors"></div>
											<?php //fnEscreve($arrayQuery); ?>
										</div>
									</div>

									<?php

										if($disableCombo == 'disabled'){
									?>

										<input type="hidden" name="COD_USUARIO2" id="COD_USUARIO2" value="<?=$cod_usuario?>">

									<?php
										}

									?>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Cidade</label>
												<select data-placeholder="Selecione as cidades" name="COD_MUNICIPIO_E[]" id="COD_MUNICIPIO_E" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
												<?php

													$sql = "SELECT A.COD_MUNICIPIO_E, A.NOM_MUNICIPIO FROM MUNICIPIOS A 
															INNER JOIN REGIAO_USUARIO RU ON RU.COD_MUNICIPIO_E = A.COD_MUNICIPIO_E
															WHERE A.COD_ESTADO = $cod_estado AND A.COD_MUNICIPIO_E != 0 
															GROUP BY A.COD_MUNICIPIO_E
															ORDER BY A.NOM_MUNICIPIO";
													$arrayCidade = mysqli_query(connTemp($cod_empresa,''),$sql);
													while($qrCidade = mysqli_fetch_assoc($arrayCidade)){
												?>
														<option value="<?=$qrCidade['COD_MUNICIPIO_E']?>"><?=$qrCidade['NOM_MUNICIPIO']?></option>
												<?php
													}

												?>								
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>	

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Filtros</label>
												<select data-placeholder="Selecione o filtro" name="COD_FILTRO[]" id="COD_FILTRO" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
													<?php 
													
														$sql = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																WHERE COD_EMPRESA = $cod_empresa
																AND COD_TPFILTRO = 28
																ORDER BY DES_FILTRO";
																
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrStatus = mysqli_fetch_assoc($arrayQuery))
														{
														  	?>
														  	<option value="<?php echo $qrStatus['COD_FILTRO']; ?>"><?php echo $qrStatus['DES_FILTRO']; ?></option>
													<?php 
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
						
						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>
									
									<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<th>Indicador</th>
											<th>Município</th>
											<th>Filtros</th>
											<th>Qtd. Apoiadores</th>
											<th>Votos</th>
											<th>Membros</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">							

									<?php

										if($cod_usuario != "" && $cod_usuario != 0){
											$andUsuario = "AND A.COD_USUARIO IN($cod_usuario)";
										}else{
											$andUsuario = "";
										}

										if($cod_municipio_e != "" && $cod_municipio_e != 0){
											$andMunicipio = "AND A.COD_MUNICIPIO_E IN($cod_municipio_e)";
										}else{
											$andMunicipio = "";
										}

										if($cod_filtro != "" && $cod_filtro != 0){
											$andFiltros = "AND A.COD_FILTRO IN($cod_filtro)";
										}else{
											$andFiltros = "";
										}
									
										$sql = "SELECT A.COD_USUARIO,
												SUM((SELECT COUNT(*) FROM CLIENTES E WHERE  E.COD_MUNICIPIO=A.COD_MUNICIPIO)) AS TOT_APOIADOR,
												SUM((SELECT  SUM(QT_VOTOS_NOMINAIS) FROM ELEICOES F WHERE  F.CD_MUNICIPIO=A.COD_MUNICIPIO_E AND ANO_ELEICAO=2018 AND NR_CANDIDATO=31031)) AS TOT_VOTOS,
												SUM(E.QTD_MEMBROS) AS TOT_MEMBROS
												FROM regiao_usuario A
												INNER JOIN MUNICIPIOS B ON B.COD_MUNICIPIO=A.COD_MUNICIPIO
												INNER JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO=A.COD_USUARIO
												INNER JOIN MEMBROS_CIDADE E ON E.COD_MUNICIPIO=B.COD_MUNICIPIO
												WHERE A.COD_USUARIO != ''
												$andUsuario												
												$andMunicipio												
												$andFiltros";

										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
										$qrTot = mysqli_fetch_assoc($retorno);
										$totalitens_por_pagina = mysqli_num_rows($retorno);

										$tot_apoiador = $qrTot['TOT_APOIADOR'];
										$tot_votos = $qrTot['TOT_VOTOS'];
										$tot_membros = $qrTot['TOT_MEMBROS'];

										$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
										
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT D.NOM_CLIENTE,B.NOM_MUNICIPIO, E.QTD_MEMBROS,
												(SELECT COUNT(*) FROM CLIENTES E WHERE  E.COD_MUNICIPIO=A.COD_MUNICIPIO) AS QTD_APOIADOR,
												(SELECT  SUM(QT_VOTOS_NOMINAIS) FROM ELEICOES F WHERE  F.CD_MUNICIPIO=A.COD_MUNICIPIO_E AND ANO_ELEICAO=2018 AND NR_CANDIDATO=31031) AS QTD_VOTOS,
												A.COD_FILTRO
												FROM regiao_usuario A
												INNER JOIN MUNICIPIOS B ON B.COD_MUNICIPIO=A.COD_MUNICIPIO
												INNER JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO=A.COD_USUARIO 
												LEFT JOIN CLIENTES D ON D.COD_CLIENTE=C.COD_INDICADOR
												INNER JOIN MEMBROS_CIDADE E ON E.COD_MUNICIPIO=B.COD_MUNICIPIO
												WHERE A.COD_USUARIO != ''
												$andUsuario												
												$andMunicipio												
												$andFiltros																									
												ORDER BY D.NOM_CLIENTE 
												LIMIT $inicio,$itens_por_pagina";
										
										// fnEscreve($sql);
                                                                               
										// fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
															  
										$count=0;
										while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
										  {

										  	$sqlFiltros = "SELECT DES_FILTRO FROM FILTROS_CLIENTE WHERE COD_EMPRESA = $cod_empresa AND COD_FILTRO IN($qrApoia[COD_FILTRO]) ORDER BY DES_FILTRO";
										  	$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),$sqlFiltros);

										  	$filtros = "";

										  	while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
										  		$filtros .= $qrFiltros['DES_FILTRO'].", "; 
										  	}

										  	$filtros = rtrim(trim($filtros),',');			

											$count++;	
											echo"
												<tr>
												  <td>".$qrApoia['NOM_CLIENTE']."</td>
												  <td>".$qrApoia['NOM_MUNICIPIO']."</td>
												  <td>".$filtros."</td>
												  <td>".$qrApoia['QTD_APOIADOR']."</td>
												  <td>".$qrApoia['QTD_VOTOS']."</td>
												  <td>".$qrApoia['QTD_MEMBROS']."</td>
												</tr>
												"; 
											  }											
                                                                                         
									?>
										</tbody>

										<tfoot>
											<tr>
												<th colspan="3"></th>
												<th><?=fnValor($tot_apoiador,0)?></th>
												<th><?=fnValor($tot_votos,0)?></th>
												<th><?=fnValor($tot_membros,0)?></th>
											</tr>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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
					
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						<div class="push5"></div> 
						
						</form>
						
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
	
    <script>
	
		//datas
		$(function () {

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}
			
			var cod_filtro = "<?=$cod_filtro?>";
			if(cod_filtro != 0 && cod_filtro != ""){
				//retorno combo multiplo - cod_filtro
			$("#formulario #COD_FILTRO").val('').trigger("chosen:updated");

				var sistemasUni = cod_filtro;				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_FILTRO option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_FILTRO").trigger("chosen:updated");
			}

			var cod_usuario = "<?=$cod_usuario?>";
			if(cod_usuario != 0 && cod_usuario != ""){
				//retorno combo multiplo - cod_usuario
			$("#formulario #COD_USUARIO").val('').trigger("chosen:updated");

				var sistemasUni = cod_usuario;				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_USUARIO option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_USUARIO").trigger("chosen:updated");
			}

			var cod_municipio_e = "<?=$cod_municipio_e?>";
			if(cod_municipio_e != 0 && cod_municipio_e != ""){
				//retorno combo multiplo - cod_municipio_e
			$("#formulario #COD_MUNICIPIO_E").val('').trigger("chosen:updated");

				var sistemasUni = cod_municipio_e;				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_MUNICIPIO_E option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_MUNICIPIO_E").trigger("chosen:updated");
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
											url: "relatorios/ajxRelMembrosRegiao.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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
				url: "relatorios/ajxRelMembrosRegiao.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
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
   