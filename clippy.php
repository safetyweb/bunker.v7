<?php
	//echo fnDebug('true');

$hashLocal = mt_rand();	

$adm = $connAdm->connAdm();


	//verifica se vem da tela sem pop up
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
		$cod_modulos = fnLimpaCampoZero($_REQUEST['COD_MODULOS']);
		$des_modulos = fnLimpaCampo($_REQUEST['DES_MODULOS']);
		$des_clip_help = addslashes($_REQUEST['DES_CLIP_HELP']);
		$cod_clip_help = fnLimpaCampoZero($_REQUEST['COD_CLIP_HELP']);
		$nom_clip_help = fnLimpaCampo($_REQUEST['NOM_CLIP_HELP']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$num_ordenac = fnLimpaCampo($_REQUEST['NUM_ORDENAC']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if (isset($_POST['COD_EMPRESA'])) {
			$Arr_COD_EMPRESA = $_POST['COD_EMPRESA'];			 

			for ($i = 0; $i < count($Arr_COD_EMPRESA); $i++) {
				$cod_empresa = $cod_empresa . $Arr_COD_EMPRESA[$i] . ",";
			}

			$cod_empresa = substr($cod_empresa, 0, -1);
		} else {
			$cod_empresa="0";
		}

		if ($opcao != ''){
			
			switch ($opcao)
			{
				case 'CAD':


				$sqlConsult = "SELECT IFNULL(MAX(NUM_ORDENAC),0)+1 ORDEM FROM CLIP_HELP";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sqlConsult);
				$qrModulo = mysqli_fetch_assoc($arrayQuery);
				$num_ordenac = $qrModulo["ORDEM"];

				$sql = "INSERT INTO CLIP_HELP(
					COD_MODULOS,
					DES_CLIP_HELP,
					NOM_CLIP_HELP,
					COD_EMPRESA,
					NUM_ORDENAC,
					DES_OBJETO,
					DES_DICA
					)VALUES(
					'$cod_modulos',
					'$des_clip_help',
					'$nom_clip_help',
					'$cod_empresa',
					'$num_ordenac',
					'',
					''
				)";
					$qrInsert = mysqli_query($adm,$sql);	
					//fnEscreve($sql);
					
					if (!$qrInsert) {
						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;


					case 'ALT':
					$sql = "UPDATE CLIP_HELP set 
					DES_CLIP_HELP  = '$des_clip_help',
					NOM_CLIP_HELP = '$nom_clip_help',
					COD_EMPRESA = '$cod_empresa'
					WHERE COD_MODULOS = $cod_modulos AND COD_CLIP_HELP = $cod_clip_help
					";

					$qrUpdate = mysqli_query($adm,$sql);

					if (!$qrUpdate) {
						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro atualizado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

					break;
					case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
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
	if (is_numeric(fnLimpacampo(fnDecode($_GET['codMod'])))) {
		//busca dados da empresa
		$cod_modulos = fnDecode($_GET['codMod']);
		$sql = "SELECT * FROM modulos where COD_MODULOS = $cod_modulos";
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrModulo = mysqli_fetch_assoc($arrayQuery);
		$des_modulos = $qrModulo['DES_MODULOS'];
		$cod_modulos = $qrModulo['COD_MODULOS'];
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

						<?php if ($msgRetorno <> '') { ?>	
							<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<?php echo $msgRetorno; ?>
							</div>
						<?php } 
						
						
						?>
						
						<div class="login-form">

							<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Dados Gerais</legend> 

									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Código</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIP_HELP" id="COD_CLIP_HELP" value="<?php echo $cod_clip_help; ?>">
											</div>
										</div>
										
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Cod. Modulo</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_MODULOS" id="COD_MODULOS" value="<?php echo $cod_modulos; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label required">Modulo</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_MODULOS" id="DES_MODULOS" value="<?=$des_modulos;?>">
											</div>
										</div>
									</div>
									<div class="row">
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label required">Título</label>
												<div class="push5"></div>
												<input type="text" class="form-control input-sm" name="NOM_CLIP_HELP" id="NOM_CLIP_HELP" value="<?php echo $nom_clip_help; ?>" maxlenght="100" required>
											</div>														
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="inputName" class="control-label required">Selecione a Empresa</label>

												<select data-placeholder="Selecione uma ou mais empresas" name="COD_EMPRESA[]" id="COD_EMPRESA" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
													<option value="9999" selected>Todas Empresas</option>
													<?php
													$sql = "SELECT * FROM empresas WHERE LOG_ATIVO = 'S' ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
														echo "
														<option value='" . $qrListaUnive['COD_EMPRESA']. "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
														";
													}
													?>
												</select>
												<div class="help-block with-errors"></div>

												<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
												<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Mensagem</label>
												<textarea type="text" class="form-control input-sm" rows="4"  name="DES_CLIP_HELP" id="DES_CLIP_HELP" value="" required><?php echo $des_clip_help;?></textarea>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>


									<div class="push10"></div>															

								</fieldset>										

								<div class="push10"></div>
								<hr>
								<div class="form-group text-right col-md-12">
									<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								</div>

								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="<?php echo $num_ordenac; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

								<div class="push5"></div> 

							</form>

							<div class="push50"></div>

						</div>								

					</div>
				</div>
				<!-- fim Portlet -->
			</div>

			<div class="push20"></div>

		</div>

		<div class="portlet portlet-bordered">
			<div class="portlet-body">

				<div class="login-form">
					<div class="row">
						<div class="col-md-12" id="div_Produtos">

							<table id="table" class="table table-bordered table-hover table-sortable tablesorter">

								<thead>
									<tr>
										<th class='{ sorter: false } text-center' width="40"></th>
										<th class="{sorter:false}"></th>
										<th><small>ID</small></th>
										<th><small>Título</small></th>
										<th><small>Descrição</small></th>
										<?php /*
									  <th class="{sorter:false}"></th>
									  */ ?>
									</tr>
								</thead>

								<tbody>

									<?php

									$sql = "select * from CLIP_HELP WHERE COD_MODULOS='$cod_modulos' order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

									$count = 0;
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;
										echo "
										<tr>
										<td align='center'><span data-id='" . $qrBusca['COD_CLIP_HELP'] . "'></span></td>
										<td class='text-center' ><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
										<td>" . $qrBusca['COD_CLIP_HELP'] . "</td>
										<td>" . $qrBusca['NOM_CLIP_HELP'] . "</td>
										<td>" . $qrBusca['DES_CLIP_HELP'] . "</td>
										</tr>
										<input type='hidden' id='ret_COD_CLIP_HELP_" . $count . "' value='" . $qrBusca['COD_CLIP_HELP'] . "'>
										<input type='hidden' id='ret_NOM_CLIP_HELP_" . $count . "' value='" . $qrBusca['NOM_CLIP_HELP'] . "'>
										<input type='hidden' id='ret_DES_CLIP_HELP_" . $count . "' value='" . $qrBusca['DES_CLIP_HELP'] . "'>
										<input type='hidden' id='ret_DES_DICA_" . $count . "' value='" . $qrBusca['DES_DICA'] . "'>
										<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBusca['COD_EMPRESA'] . "'>
										<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBusca['NUM_ORDENAC'] . "'>
										";
									}

									?>

								</tbody>

							</table>

						</div>

					</div>
				</div>

				<div class="push"></div>

			</div>

		</div>					

		<div class="push20"></div>

		<script type="text/javascript">

			function retornaForm(index) {
				$("#formulario #COD_CLIP_HELP").val($("#ret_COD_CLIP_HELP_" + index).val());
				$("#formulario #NOM_CLIP_HELP").val($("#ret_NOM_CLIP_HELP_" + index).val());
				$("#formulario #DES_CLIP_HELP").val($("#ret_DES_CLIP_HELP_" + index).val());
				$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
				var codEmpresa = $("#ret_COD_EMPRESA_" + index).val();

				var codEmpresaArray = codEmpresa.split(',');

    			// Limpa todas as seleções atuais no campo multiselect
				$("#COD_EMPRESA").val('').trigger('chosen:updated');

    			// Seleciona as opções correspondentes no campo multiselect
				for (var i = 0; i < codEmpresaArray.length; i++) {
					$("#COD_EMPRESA option[value='" + codEmpresaArray[i] + "']").prop('selected', true);
				}
				 $("#COD_EMPRESA").trigger('chosen:updated');

				$('#formulario').validator('validate');
				$("#formulario #hHabilitado").val('S');
			}

			$('#iAll').on('click', function(e) {
				e.preventDefault();
				$('#COD_EMPRESA option').prop('selected', true).trigger('chosen:updated');
			});

			$('#iNone').on('click', function(e) {
				e.preventDefault();
				$("#COD_EMPRESA option:selected").removeAttr("selected").trigger('chosen:updated');
			});
		</script>	