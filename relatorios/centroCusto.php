<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} 
	else 
	{
		$_SESSION['last_request']  = $request;
		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
        $val_pesquisa = fnLimpaCampo($_POST['INPUT']);

		$id = fnLimpaCampoZero($_POST['ID']);
		$cod_empresa = fnLimpaCampo($_POST['COD_EMPRESA']);
		$descricao = fnLimpaCampo($_POST['DESCRICAO']);
		$abreviacao = fnLimpaCampo($_POST['ABREVICAO']);
		if (empty($_POST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = "S";
		}

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

    	if ($val_pesquisa != "") {
        	$esconde = " ";
    	} else {
        	$esconde = "display: none;";
    	}

		if ($LOG_ATIVO == "S") {
		$check_LOG_ATIVO = "checked";
		} else {
			$check_LOG_ATIVO = "";
		}

		if ($opcao == "CAD"){

					$sqlCentro = "INSERT INTO centro_custo (
	                                                COD_EMPRESA,
	                                                DESCRICAO,
	                                                ABREVICAO,
	                                                LOG_ATIVO,
	                                                DAT_CADASTR
	                                            )VALUES(
	                                                $cod_empresa,
	                                                '$descricao',
	                                                '$abreviacao',
	                                                '$log_ativo',
	                                                NOW()
	                                            )";

                    mysqli_query($adm,$sqlCentro);
                    //fnEscreve($sqlCentro);
                                         

            }else if($opcao == "ALT"){
                
	                $sqlCentro = "UPDATE centro_custo SET
	                                            COD_EMPRESA = $cod_empresa,
	                                            DESCRICAO = '$descricao',
	                                            ABREVICAO = '$abreviacao',
	                                            LOG_ATIVO = '$log_ativo'
	                                WHERE ID = $id";
	                
	                mysqli_query($adm,$sqlCentro);
	                //fnEscreve($sqlCentro);
                
            }else if($opcao == "EXC"){
                
	                $sqlCentro = "DELETE FROM centro_custo WHERE ID = $id";
	                
	                mysqli_query($adm,$sqlCentro);
            }
			}

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
					echo ('<div class="push20"></div>'); 
					$abaControle = 1941;
					include "abasControleHoras.php"; 
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="">
									</div>
								</div>

								<div class="col-md-1">   
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Ativo</label> 
                                        <div class="push5"></div>
                                            <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch"
                                            <?php echo $check_LOG_ATIVO; ?>>
                                            <span></span>
                                        </label>
                                    </div>

                                    <div class="push10"></div>
                                </div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
                                                                                                    
											<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect requiredChk" required="required" >
												<option value=""></option>					
												<?php																	
													
													if ($_SESSION["SYS_COD_MASTER"] == 2 ) {
														$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
														(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
														from empresas A where A.cod_empresa <> 1 and A.cod_exclusa = 0 order by A.NOM_FANTASI 
														";
                                                                                                                                  
													}else {
														$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
														(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
														from empresas A where A.COD_EMPRESA IN (1,".$_SESSION["SYS_COD_MULTEMP"].") and A.cod_exclusa = 0 order by A.NOM_FANTASI 
														";
													}																	
													
													$arrayQuery = mysqli_query($adm,$sql) or die(mysqli_error());
																												
													while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery))
													  {													
														if ((int)$qrListaEmpresa['COD_DATABASE'] == 0){ $desabilitado = "disabled";}
														else {$desabilitado = "";}
														
														echo"
															  <option value='".$qrListaEmpresa['COD_EMPRESA']."' ".$desabilitado." >".$qrListaEmpresa['NOM_FANTASI']."</option>
															"; 
													  }											
												?>	
											</select>
                                                                                     	
											<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Descrição</label>
											<input type="text" class="form-control input-sm" name="DESCRICAO" id="DESCRICAO" value="" maxlength="50" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Abreviação</label>
											<input type="text" class="form-control input-sm" name="ABREVICAO" id="ABREVICAO" value="" maxlength="20" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

				<div class="row">
                    <form name="formLista2" id="formLista2" method="post" action="<?= $cmdPage; ?>">
                        <div class="container">
                            <div class="col-xs-4 col-xs-offset-4">
                                <div class="input-group activeItem">
                                    <div class="input-group-btn search-panel">
                                        <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                            <span id="search_concept">Sem filtro</span>&nbsp;
                                            <span class="fal fa-angle-down"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="divisor"><a href="#">Sem filtro</a></li>
                                            <!-- <li class="divider"></li> -->
                                        </ul>
                                    </div>
                                    <input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
                                    <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
                                    <div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
                                        <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                    </div>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
									<thead>
										<tr>
											<th width="40"></th>
											<th>Código</th>
											<th>Empresa</th>
											<th>Descrição</th>
											<th>Abreviação</th>
											<th>Ativo</th>
										</tr>
									</thead>
									<tbody>

										<?php
												$sql = "SELECT centro_custo.*, empresas.NOM_FANTASI 
														FROM centro_custo
														INNER JOIN empresas ON centro_custo.COD_EMPRESA = empresas.COD_EMPRESA";

											$arrayQuery = mysqli_query($adm, $sql);

											$count = 0;
											while ($qrBuscaCentro = mysqli_fetch_assoc($arrayQuery)) {
											    $count++;

											   	if ($qrBuscaCentro['LOG_ATIVO'] == 'S') {
													$mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraAtivo = '';
												}

											    echo "
											        <tr>
											            <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
											 			<td>" . $qrBuscaCentro['ID'] . "</td>
												        <td>" . $qrBuscaCentro['NOM_FANTASI'] . "</td>
												        <td>" . $qrBuscaCentro['DESCRICAO'] . "</td>
												        <td>" . $qrBuscaCentro['ABREVICAO'] . "</td>
												        <td>" . $mostraAtivo . "</td>
											        </tr>
											        <input type='hidden' id='ret_ID_" . $count . "' value='" . $qrBuscaCentro['ID'] . "'>
											        <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaCentro['COD_EMPRESA'] . "'>
											        <input type='hidden' id='ret_DAT_CADASTR_" . $count . "' value='" . $qrBuscaCentro['DAT_CADASTR'] . "'>
											        <input type='hidden' id='ret_DESCRICAO_" . $count . "' value='" . $qrBuscaCentro['DESCRICAO'] . "'>
											        <input type='hidden' id='ret_ABREVICAO_" . $count . "' value='" . $qrBuscaCentro['ABREVICAO'] . "'>
											        <input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrBuscaCentro['LOG_ATIVO'] . "'>
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

<script type="text/javascript">

		//Barra de pesquisa essentials ------------------------------------------------------
	$(document).ready(function(e) {
		var value = $('#INPUT').val().toLowerCase().trim();
		if (value) {
			$('#CLEARDIV').show();
		} else {
			$('#CLEARDIV').hide();
		}
		$('.search-panel .dropdown-menu').find('a').click(function(e) {
			e.preventDefault();
			var param = $(this).attr("href").replace("#", "");
			var concept = $(this).text();
			$('.search-panel span#search_concept').text(concept);
			$('.input-group #VAL_PESQUISA').val(param);
			$('#INPUT').focus();
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		});

		$('#CLEAR').click(function() {
			$('#INPUT').val('');
			$('#INPUT').focus();
			$('#CLEARDIV').hide();
			if ("<?= $filtro ?>" != "") {
				location.reload();
			} else {
				var value = $('#INPUT').val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel tr").each(function(index) {
					if (!index) return;
					$(this).find("td").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('tr').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		});

		// $('#SEARCH').click(function(){
		// 	$('#formulario').submit();
		// });


	});

	function buscaRegistro(el) {
		var filtro = $('#search_concept').text().toLowerCase();

		if (filtro == "sem filtro") {
			var value = $(el).val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$(".buscavel tr").each(function(index) {
				if (!index) return;
				$(this).find("td").each(function() {
					var id = $(this).text().toLowerCase().trim();
					var sem_registro = (id.indexOf(value) == -1);
					$(this).closest('tr').toggle(!sem_registro);
					return sem_registro;
				});
			});
		}
	}

	//-----------------------------------------------------------------------------------


	function retornaForm(index) {
		$("#formulario #ID").val($("#ret_ID_" + index).val());
		$("#formulario #DAT_CADASTR").val($("#ret_DAT_CADASTR_" + index).val());
		$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val()).trigger("chosen:updated");
		$("#formulario #DESCRICAO").val($("#ret_DESCRICAO_" + index).val());
		$("#formulario #ABREVICAO").val($("#ret_ABREVICAO_" + index).val());
		if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
			$('#formulario #LOG_ATIVO').prop('checked', true);
		} else {
			$('#formulario #LOG_ATIVO').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');						
	}
</script>