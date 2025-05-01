<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;
		$cod_comunicacao = fnLimpaCampo($_REQUEST['cod_comunicacao']);
		$des_comunicacao = fnLimpaCampo($_REQUEST['des_comunicacao']);
		$num_ordenac = fnLimpaCampoZero($_REQUEST['num_ordenac']);

		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {			

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

				$sql = "select max(num_ordenac) num_ordenac from cat_comunicacao ";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				$qrModulo = mysqli_fetch_assoc($arrayQuery);
				$num_ordenac = $qrModulo["num_ordenac"] + 1;


				$sql = "insert into cat_comunicacao(
					des_comunicacao,
					num_ordenac
					)
				values(
					'$des_comunicacao',
					$num_ordenac
					)";

				$arrayProc = mysqli_query($adm,$sql);

				if (!$arrayProc){
					$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
				}
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
				}
				break;



				case 'ALT':

				$sql = "update cat_comunicacao set 
				des_comunicacao = '$des_comunicacao'
				where cod_comunicacao = $cod_comunicacao
				";

				$arrayProc = mysqli_query($adm,$sql);
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}
				break;



				case 'EXC':
				$sql = "delete from cat_comunicacao where cod_comunicacao = $cod_comunicacao";
				
				$arrayProc = mysqli_query($adm,$sql);
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
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}

	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}
	tr{
		border-bottom: none!important;
	}
	#blocker
	{
		display:none; 
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div
	{
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando...</div>
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

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="cod_comunicacao" id="cod_comunicacao" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição</label>
										<input type="text" class="form-control input-sm" name="des_comunicacao" id="des_comunicacao" value="" maxlength="100" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>

						<div class="form-group text-right col-lg-8 col-lg-offset-4">

							<div class="form-group text-right col-lg-12">
								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							</div>
						</div>
						
						<input type="hidden" name="num_ordenac" id="num_ordenac" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					

					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class='{ sorter: false } text-center' width='50'></th>
										<th class='{ sorter: false } text-center'></th>
										<th>Código</th>
										<th>Descrição</th>
									</tr>
								</thead>
								<tbody>

									<?php
									$sql = "select * from cat_comunicacao order by num_ordenac";
													//fnEscreve($sql);
									
									$arrayQuery = mysqli_query($adm, $sql);
									$count = 0;
										//fnEscreve($sql);
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;
										
										echo "
										<tr>
										<td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBusca['cod_comunicacao'] . "'></span></td>
										<td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
										<td>".$qrBusca['cod_comunicacao']."</td>
										<td>".$qrBusca['des_comunicacao']."</td>											
										</tr>
										<input type='hidden' id='ret_cod_comunicacao_" . $count . "' value='" . $qrBusca['cod_comunicacao'] . "'>
										<input type='hidden' id='ret_des_comunicacao_" . $count . "' value='" . $qrBusca['des_comunicacao'] . "'>
										<input type='hidden' id='ret_num_ordenac_" . $count . "' value='" . $qrBusca['num_ordenac'] . "'>
										";
									}
									?>
								</tbody>
							</table>

						</form>

					</div>

					<div class="push20"></div>

					<div id="AREACODE_OFF" style="display: none;">
						<textarea id="AREACODE"></textarea>
					</div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>


<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

	$(function() {

		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				$('table tr').each(function(index) {
					if (index != 0) {
						Ids = Ids + $(this).children().find('span.fa-equals').attr('data-id') + ",";
					}
				});

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				execOrdenacao(arrayOrdem, '<?= $cod_empresa ?>');

				function execOrdenacao(p1, p3) {
					//alert(p2);
					$.ajax({
						type: "GET",
						url: "ajxTipoComunicacao.php", 
						data: {
							ajx1: p1,
							ajx3: p3
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							// $("#divId_sub").html(data); 
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});

		$(".table-sortable tbody").disableSelection();

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

	});


		//-----------------------------------------------------------------------------------
	$(function() {

		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: false,
			center: false,
			color: false,
			right: false,
			strike: false,
			source: false,
			link: false,
			unlink: false,
			remove: false,
			rule: false,
			fsize: false,
			format: false,
		});


	});



	function retornaForm(index) {
		$("#formulario #cod_comunicacao").val($("#ret_cod_comunicacao_" + index).val());	
		$("#formulario #des_comunicacao").val($("#ret_des_comunicacao_" + index).val());
		$("#formulario #num_ordenac").val($("#ret_num_ordenac_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>