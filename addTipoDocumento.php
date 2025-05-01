<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

// $conn = conntemp($cod_empresa,"");
// $adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_tipodoc = fnLimpaCampoZero($_REQUEST['COD_TIPODOC']);
		$nom_tipodoc = fnLimpaCampo($_REQUEST['NOM_TIPODOC']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

		// $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		// $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		// $MODULO = $_GET['mod'];
		// $COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if($opcao != ""){

			switch ($opcao) {
				case 'CAD':
						
					$sql = "INSERT INTO TIPO_DOCUMENTO(
												NOM_TIPODOC,
												COD_EMPRESA,
												COD_CADASTR
											) VALUES (
											    '$nom_tipodoc',
											    '$cod_empresa',
											    '$cod_usucada'
											    )";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

				break;
				case 'ALT':
					
					$sql = "UPDATE TIPO_DOCUMENTO SET
										NOM_TIPODOC = '$nom_tipodoc',
										COD_ALTERAC = '$cod_usucada',
										DAT_ALTERAC = NOW()
									WHERE COD_TIPODOC = '$cod_tipodoc'
									AND COD_EMPRESA = '$cod_empresa'
										";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

				break;
				case 'EXC':
					
					$sql = "UPDATE TIPO_DOCUMENTO SET
										COD_EXCLUSA = '$cod_usucada',
										DAT_EXCLUSA = NOW()
									WHERE COD_TIPODOC = '$cod_tipodoc'
									AND COD_EMPRESA = '$cod_empresa'
										";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

				break;

			}

			$msgTipo = 'alert-success';

			?>
				<script>parent.$('#REFRESH_COMBO').val('S');</script>				
			<?php

		}

	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
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

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Cadastro Documento</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TIPODOC" id="COD_TIPODOC" value="">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Documento</label>
										<input type="text" class="form-control input-sm" name="NOM_TIPODOC" id="NOM_TIPODOC" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

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
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Nome do Documento</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from tipo_documento where cod_empresa = $cod_empresa and COD_EXCLUSA = 0 order by NOM_TIPODOC";
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''),$sql);

										$count = 0;
										while ($qrBuscaDocumento = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaDocumento['COD_TIPODOC'] . "</td>
														<td>" . $qrBuscaDocumento['NOM_TIPODOC'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_TIPODOC_" . $count . "' value='" . $qrBuscaDocumento['COD_TIPODOC'] . "'>
													<input type='hidden' id='ret_NOM_TIPODOC_" . $count . "' value='" . $qrBuscaDocumento['NOM_TIPODOC'] . "'>
													";
										}

										?>

									</tbody>
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

<input type="hidden" class="input-sm" name="REFRESH_DOCUMENTOS" id="REFRESH_DOCUMENTOS" value="N">

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

<script type="text/javascript">

	$(document).ready(function(){
			
		//modal close
		$('.modal').on('hidden.bs.modal', function () {
		  console.log('entrou');
		  if ($('#REFRESH_DOCUMENTOS').val() == "S"){
			//alert("atualiza");
			RefreshDocumentos(<?php echo $cod_empresa; ?>,<?php echo $cod_tipodoc; ?>);
			$('#REFRESH_DOCUMENTOS').val("N");				
		  }	
		});
		
	});
		
	function RefreshDocumentos(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxAddTipoDocumento.php",
			data: { ajx1:idEmp},
			beforeSend:function(){
				$('.formLista').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$(".formLista").html(data); 
			},
			error:function(){
				$('.formLista').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});		
	}

	function retornaForm(index) {
		$("#formulario #COD_TIPODOC").val($("#ret_COD_TIPODOC_" + index).val());
		$("#formulario #NOM_TIPODOC").val($("#ret_NOM_TIPODOC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>