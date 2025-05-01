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
			
			$cod_empresa = fndecode($_GET['id']);
			$cod_grupoent = fnLimpaCampoZero($_REQUEST['COD_GRUPOENT']);
			$cod_regitra = fnLimpaCampoZero($_REQUEST['COD_REGITRA']);
			$des_grupoent = fnLimpaCampo($_REQUEST['DES_GRUPOENT']);
			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			//echo $cod_empresa;
						
			if ($opcao != ''){
	
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO ENTIDADE_GRUPO(
												COD_EMPRESA, 
												DES_GRUPOENT, 
												COD_REGITRA
											) VALUES(
												$cod_empresa, 
												'$des_grupoent', 
												$cod_regitra
											)";
						
						//echo $sql;
						
						mysqli_query(connTemp($cod_empresa,''),$sql);	

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

							$sql = "UPDATE ENTIDADE_GRUPO SET
													COD_EMPRESA = $cod_empresa,  
													DES_GRUPOENT = '$des_grupoent', 
													COD_REGITRA = $cod_regitra
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_GRUPOENT = $cod_grupoent";
							
							//echo $sql;
							
							mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':

							$sql = "DELETE FROM ENTIDADE_GRUPO 
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_GRUPOENT = $cod_grupoent";
							
							//echo $sql;
							
							mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			}  	

		}
	}

	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}
      
	//fnMostraForm();
	//fnEscreve($cod_tpentid);
if($val_pesquisa != ""){
                $esconde = " ";
}else{
        $esconde = "display: none;";
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
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									
									<?php 
										$abaEmpresa = 1730;	
										
										switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 14: //rede duque
											include "abasEmpresaDuque.php";
											break;
										case 15: //quiz
											include "abasEmpresaQuiz.php";
											break;
										case 16: //gabinete
											include "abasGabinete.php";
											break;
										case 18: //mais cash
											include "abasMaisCash.php";
											break;
										case 19: //rh
											include "abasRH.php";
											break;
										default;
											include "abasEmpresaConfig.php";
											//$formBack = "1019";
											break;
										}	
									
									?>	

									<div class="push30"></div>									
								
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_GRUPOENT" id="COD_GRUPOENT" value="">
														</div>
													</div>
										
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Agrupador</label>
															<input type="text" class="form-control input-sm" name="DES_GRUPOENT" id="DES_GRUPOENT" maxlength="50">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<?php
													if($cod_empresa == 136){
													?>
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Agrupador</label>
																<select data-placeholder="Selecione um estado civil" name="COD_REGITRA" id="COD_REGITRA" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<?php																	
																		$sql = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																				WHERE COD_TPFILTRO = 28
																				AND COD_EMPRESA = $cod_empresa
																				ORDER BY DES_FILTRO";
																		$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
																	
																		while ($qrFiltro = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			echo"
																				  <option value='".$qrFiltro['COD_FILTRO']."'>".$qrFiltro['DES_FILTRO']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													<?php
													}
													?>
																				
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>
                                                                                
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="faL fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										</form>
										<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">
										<div class="push5"></div>
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
																	<li><a href="#EG.COD_GRUPOENT">Código</a></li>
																	<li><a href="#EG.DES_GRUPOENT">Nome</a></li>
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
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tablesorter buscavel">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Grupo</th>
													  <?php if($cod_empresa != 311){
														?>
													  <th>Agrupador</th>
													  <?php
													  }
													  ?>
													</tr>
												  </thead>
												<tbody>
												  
												<?php
                                                                                                        
												if($filtro != ""){
													$andFiltro = " AND $filtro = '$val_pesquisa' ";
												}else{
													$andFiltro = " ";
												}
										
													$sql = "SELECT EG.*, FC.DES_FILTRO FROM ENTIDADE_GRUPO EG
															LEFT JOIN FILTROS_CLIENTE FC ON EG.COD_REGITRA = FC.COD_FILTRO
															$andFiltro
															WHERE EG.COD_EMPRESA = $cod_empresa";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													//echo($sql);
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {					
														if($cod_empresa != 311){
															$agrupador = "<td>".$qrBuscaModulos['DES_FILTRO']."</td>";
														}else{
															$agrupador = "";
														}										  
														$count++;	
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_GRUPOENT']."</td>
															  <td>".$qrBuscaModulos['DES_GRUPOENT']."</td>
															  $agrupador	
															</tr>
															<input type='hidden' id='ret_COD_GRUPOENT_".$count."' value='".$qrBuscaModulos['COD_GRUPOENT']."'>
															<input type='hidden' id='ret_DES_GRUPOENT_".$count."' value='".$qrBuscaModulos['DES_GRUPOENT']."'>
															<input type='hidden' id='ret_COD_REGITRA_".$count."' value='".$qrBuscaModulos['COD_REGITRA']."'>
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
$(document).ready(function(e){
        var value = $('#INPUT').val().toLowerCase().trim();
    if(value){
        $('#CLEARDIV').show();
    }else{
        $('#CLEARDIV').hide();
    }
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
                e.preventDefault();
                var param = $(this).attr("href").replace("#","");
                var concept = $(this).text();
                $('.search-panel span#search_concept').text(concept);
                $('.input-group #VAL_PESQUISA').val(param);
                $('#INPUT').focus();
        });

    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
    });

    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
    });

    $('#CLEAR').click(function(){
        $('#INPUT').val('');
        $('#INPUT').focus();
        $('#CLEARDIV').hide();
        if("<?=$filtro?>" != ""){
                location.reload();
        }else{
                var value = $('#INPUT').val().toLowerCase().trim();
                    if(value){
                        $('#CLEARDIV').show();
                    }else{
                        $('#CLEARDIV').hide();
                    }
                    $(".buscavel tr").each(function (index) {
                        if (!index) return;
                        $(this).find("td").each(function () {
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

function buscaRegistro(el){
        var filtro = $('#search_concept').text().toLowerCase();

        if(filtro == "sem filtro"){
            var value = $(el).val().toLowerCase().trim();
            if(value){
                $('#CLEARDIV').show();
            }else{
                $('#CLEARDIV').hide();
            }
            $(".buscavel tr").each(function (index) {
                if (!index) return;
                $(this).find("td").each(function () {
                    var id = $(this).text().toLowerCase().trim();
                    var sem_registro = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!sem_registro);
                    return sem_registro;
                });
            });
        }
}
function retornaForm(index){
        $("#formulario #COD_GRUPOENT").val($("#ret_COD_GRUPOENT_"+index).val());
        $("#formulario #DES_GRUPOENT").val($("#ret_DES_GRUPOENT_"+index).val());
        $("#formulario #COD_REGITRA").val($("#ret_COD_REGITRA_"+index).val()).trigger('chosen:updated');
        $('#formulario').validator('validate');			
        $("#formulario #hHabilitado").val('S');						
}

</script>	