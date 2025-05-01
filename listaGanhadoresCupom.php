<?php
	//echo fnDebug('true');

$hashLocal = mt_rand();	

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
		$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
		$num_sorteio = fnLimpaCampoZero($_REQUEST['NUM_SORTEIO']);
		$num_sorteado = fnLimpaCampoZero($_REQUEST['NUM_SORTEADO']);
		$cod_cupom = fnLimpaCampoZero($_REQUEST['COD_CUPOM']);

		if (isset($_POST['COD_UNIVEND'])) {
			$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_UNIVEND); $i++) {
				$cod_univend = $cod_univend . $Arr_COD_UNIVEND[$i] . ",";
			}

			$cod_univend = substr($cod_univend, 0, -1);
		} else {
			$cod_univend = "0";
		}


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

	}

}

	//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);	
$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

//fnEscreve($qrBuscaEmpresa['NOM_FANTASI']);

if (isset($arrayQuery)) {
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
}

$cod_campanha = fnDecode($_GET['idc']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
	//fnEscreve($sql);
$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)){
	$cod_campanha = $qrBuscaCampanha['COD_CAMPANHA'];
}

$cod_cupom = $_GET['idcp'];


if($opcao != ""){
	?>
	<script>
		parent.$("#ATUALIZA_TELA").val("S");
	</script>
	<?php
}

?>

<?php if ($popUp != "true"){ ?>
	<div class="push30"></div> 
<?php } ?>
<div class="row">				

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;" >
				<?php } ?>

				<?php if ($popUp != "true"){  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>								

				<div class="portlet-body">

					<div class="push10"></div> 

					<?php if ($msgRetorno <> '') { ?>	
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>								

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend> 

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código Campanha</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Sorteio</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CUPOM" id="COD_CUPOM" value="<?php echo $cod_cupom ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<!--<div class="push20"></div>

								<div class="row">


									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Número do Concurso</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_SORTEIO" id="NUM_SORTEIO" value="<?php echo $num_sorteio ?>">
											<div class="help-block with-errors">Loteria Federal</div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Número Sorteado</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_SORTEADO" id="NUM_SORTEADO" value="<?php echo $num_sorteado ?>">
											<div class="help-block with-errors">Loteria Federal</div>
										</div>
									</div>

								</div>-->

							</fieldset>

							<div class="push20"></div>

							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		

							<div class="push5"></div> 

						</form>

						<!--<div class="row">
							<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

								<div class="col-xs-4 col-xs-offset-4">
									<div class="input-group activeItem">
										<div class="input-group-btn search-panel">
											<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
												<span id="search_concept">Sem filtro</span>&nbsp;
												<span class="far fa-angle-down"></span>										                    	
											</button>
											<ul class="dropdown-menu" role="menu">
												<li class="divisor"><a href="#">Sem filtro</a></li>								                      
											</ul>
										</div>
										<input type="hidden" name="VAL_PESQUISA" value="<?=$filtro?>" id="VAL_PESQUISA">         
										<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
										<div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
											<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										</div>
										<div class="input-group-btn">
											<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										</div>
									</div>
								</div>

							</form>

						</div>-->

						<div class="push30"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">									

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover table-sortable buscavel">
										<thead>
											<tr>
												<th>Código</th>
												<th>Nome</th>
												<th>Número Cupom</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">

											<?php

											$sql = "SELECT B.COD_CLIENTE,B.NOM_CLIENTE,NUM_CUPOM FROM geracupom A, clientes B
													WHERE A.COD_EMPRESA = $cod_empresa AND
													A.COD_CAMPANHA = $cod_campanha AND
													A.cod_cupom= $cod_cupom AND 
													A.COD_CLIENTE=B.COD_CLIENTE AND 
													A.log_sorteado='S'";

											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

											$count=0;
											$countLinha = 1;
											while ($qrBuscaGanhadores = mysqli_fetch_assoc($arrayQuery))
											{														  
												$count++;

												echo"
												<tr>
												<td>".$qrBuscaGanhadores['COD_CLIENTE']."</td>
												<td>".$qrBuscaGanhadores['NOM_CLIENTE']."</td>														
												<td>".$qrBuscaGanhadores['NUM_CUPOM']."</td>
												</tr>
												"; 

												$countLinha++;
											}								

											?>

										</tbody>
										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV" data-opcao="exportar"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
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

	<script>

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
										url: "ajxListaGanhadoresCupom.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?=fnEncode($cod_campanha)?>&idCp=<?=fnEncode($cod_cupom)?>",
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
	</script>	
