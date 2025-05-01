<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;	
	$pagina  = "1";

	$hashLocal = mt_rand();
	
	$cod_geral = 0;
	
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
			
			$id = fnLimpaCampoZero($_POST['ID']);			
			$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$tip_faixas = "PRD";
			$qtd_cupomext = fnLimpaCampoZero($_POST['QTD_CUPOMEXT']);
			$tip_cupomext = fnLimpaCampo($_POST['TIP_CUPOMEXT']);
            $cod_categor = fnLimpaCampo($_POST['COD_CATEGOR']);
            $cod_subcate = fnLimpaCampoZero($_POST['COD_SUBCATE']);
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
            $dat_cadastr = "NOW()";

			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$sql = "";		
			$sqlVantagem = "";	

			if($opcao != ""){
				?>
	              <script>
	                parent.$("#ATUALIZA_TELA").val("S");
	              </script>
	            <?php
			}	
			
			if ($opcao == "CAD"){

					$sqlcategoria = "INSERT INTO CUPOMCATEGORIAESPECIFICA (
	                                                COD_EMPRESA,
	                                                COD_CAMPANHA,
	                                                QTD_CUPOMEXT,
	                                                TIP_CUPOMEXT,
	                                                COD_CATEGOR,
	                                                COD_SUBCATE,
	                                                COD_USUCADA,
	                                                DAT_CADASTR
	                                            )VALUES(
	                                                $cod_empresa,
	                                                $cod_campanha,
	                                                $qtd_cupomext,
	                                                0,
	                                                $cod_categor,
	                                                $cod_subcate,
	                                                $cod_usucada,
	                                                $dat_cadastr
	                                            )";
                    mysqli_query(connTemp($cod_empresa,''),$sqlcategoria);
                    // fnEscreve($sqlcategoria);
                                         

            }else if($opcao == "ALT"){
                
	                $sqlcategoria = "UPDATE CUPOMCATEGORIAESPECIFICA SET
	                                            QTD_CUPOMEXT = $qtd_cupomext,
	                                            COD_CATEGOR = $cod_categor,
	                                            COD_SUBCATE = $cod_subcate,
	                                            COD_ALTERAC = $cod_usucada,
	                                            DAT_ALTERAC = NOW()
	                                WHERE ID = $id";
	                
	                mysqli_query(connTemp($cod_empresa,''),$sqlcategoria);
                
            }else if($opcao == "EXC"){
                
	                $sqlcategoria = "UPDATE CUPOMCATEGORIAESPECIFICA SET 
	                                            COD_EXCLUSA = $cod_usucada,
	                                            DAT_EXCLUSA = NOW()
	                                WHERE ID = $id";
	                
	                mysqli_query(connTemp($cod_empresa,''),$sqlcategoria);
            }
				
			//mensagem de retorno
			switch ($opcao)
			{
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
					break;
				break;
			}			
			$msgTipo = 'alert-success';
				
		} 

	}
	
	//busca dados da campanha
	$cod_campanha = fnDecode($_GET['idc']);	
	$cod_empresa = fnDecode($_GET['id']);	
	$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];				
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];		
	}	
 		
  
	//busca dados da url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			
			//liberação das abas
			$abaPersona	= "S";
			$abaVantagem = "S";
			$abaRegras = "S";
			$abaComunica = "S";
			$abaAtivacao = "N";
			$abaResultado = "N";

			$abaPersonaComp = "active ";
			$abaCampanhaComp = "active";
			$abaRegrasComp = "completed ";
			$abaComunicaComp = "";
			$abaAtivacaoComp = "";
			$abaResultadoComp = "";				
			
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}		
		
	//busca dados do tipo da campanha
	$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '".$tip_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
		$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
		$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
		$label_1 = $qrBuscaTpCampanha['LABEL_1'];
		$label_2 = $qrBuscaTpCampanha['LABEL_2'];
		$label_3 = $qrBuscaTpCampanha['LABEL_3'];
		$label_4 = $qrBuscaTpCampanha['LABEL_4'];
		$label_5 = $qrBuscaTpCampanha['LABEL_5'];
		
	}
	
	//busca dados da regra 
	$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$cod_persona = $qrBuscaTpCampanha['COD_PERSONA'];
		if (!empty($cod_persona)) {
			$tem_personas = "sim";
		} else {$tem_personas = "nao";}
		$pct_vantagem = $qrBuscaTpCampanha['PCT_VANTAGEM'];
		$qtd_vantagem = $qrBuscaTpCampanha['QTD_VANTAGEM'];
		$qtd_resultado = $qrBuscaTpCampanha['QTD_RESULTADO'];
		$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
		$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
		$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];

	}else{
				
		$cod_persona = 0;
		$pct_vantagem = "";
		$qtd_vantagem = "";
		$qtd_vantagem = "";
		$nom_vantagem = "";
		$num_pessoas = 0;
		$cod_vantage = 0;
		
	}

	if($val_pesquisa != ""){
		$esconde = " ";
	}else{
		$esconde = "display: none;";
	}

	//fnMostraForm();

?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
									
.input-xs {
  height: 26px;
  padding: 2px 5px;
  font-size: 12px;
  line-height: 1.5; /* If Placeholder of the input is moved up, rem/modify this. */
  border-radius: 3px;
  border: 0;
}
</style>	
		
			
					<?php if ($popUp != "true"){  ?>							
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
								
									<?php 
									$abaCampanhas = 1022; 
									if ($popUp != "true"){ 
										include "abasCampanhasConfig.php"; 
									}
									?>
									
									<div class="push10"></div> 
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>
									
									
									<?php 
									//menu superior - empresas
									$abaCli = 1063;									
									switch (fnDecode($_GET['mod'])) {
										case 1187: //produtos específicos				
											include "abasRegrasConfig.php";
											echo "<div class='push30'></div>";
											break;
									}
									?>										
																
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
									
											<?php 
											//menu superior - empresas
											$abaCli = 1063;									
											if (fnDecode($_GET['mod']) == 1187) {
											?>

											<fieldset>
												<legend>Dados Gerais</legend> 
												
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
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
															<label for="inputName" class="control-label required">Campanha</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
														</div>														
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo do Programa</label>
															<div class="push10"></div>
															<span class="fa <?php echo $des_iconecp; ?>"></span>  <b><?php echo $nom_tpcampa; ?> (<?php echo $nom_vantagem; ?>) </b>
														</div>														
													</div>
													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Pessoas Atingidas</label>
															<div class="push10"></div>
															<span class="fa fa-users"></span>&nbsp;  <?php echo number_format ($num_pessoas,0,",","."); ?>
														</div>														
													</div>
													
												</div>

										</fieldset>
										
										<div class="push20"></div>
										
										<?php	
										}
										?>	
											
										<fieldset>
											<legend>Classificação</legend>  
															
												<div class="row">
												
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required" >Grupo do Produto</label>
																<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect" >
																	<option value="">&nbsp;</option>											  
																	<?php
																		$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND (COD_EXCLUSA is null OR COD_EXCLUSA =0) order by DES_CATEGOR";
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																		
																		while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaCategoria['COD_CATEGOR']."'>".$qrListaCategoria['DES_CATEGOR']."</option> 
																				"; 
																			  }	
																	?>
																</select>
																<script>$("#COD_CATEGOR").val("<?php echo $COD_CATEGOR; ?>").trigger("chosen:updated"); </script>
															<div class="help-block with-errors"></div>
														</div>
													</div>											
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Sub Grupo do Produto</label>
																<div id="divId_sub">
																<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																</select>	
																</div>
																<script>
																//buscaSubCat(<?php //echo $cod_categor; ?>,<?php //echo $cod_subcate; ?>,<?php //echo $cod_empresa; ?>);
																
																</script>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
										</fieldset>	
													
										<div class="push10"></div>
                                                                                
										<div class="form-group text-right col-md-12">
											<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
											<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="ID" id="ID" value="<?php echo $ID; ?>">
										<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="MULTI_PROD" id="MULTI_PROD" value="" />	
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
	
										<!-- modal -->									
										<div class="modal fade" id="popModalAux" tabindex='-1'>
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
										
										
										<div class="push50"></div>
										
										<div id="div_Ordena"></div>

										<div class="push30"></div>

										<div class="row">
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
										                    	<!-- <li class="divider"></li> -->
											                    <!-- <li><a href="#DES_PRODUTO">Nome do Produto</a></li>
											                    <li><a href="#COD_EXTERNO">Código Externo</a></li>										                      
											                    <li><a href="#DUPLICADOS" onclick="$('#VAL_PESQUISA').val('DUPLICADOS'); $('#formLista2').submit();">Duplicados</a></li> -->										                      
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
										         							
										        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
												<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

											</form>
										    
										</div>

										<div class="push30"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">									
										
												<form name="formLista">
												
													<table class="table table-bordered table-striped table-hover table-sortable buscavel">
													  <thead>
														<tr>
														  <th width="40"></th>
														  <th>Código</th>
														  <th>Grupo do Produto</th>
														  <th>Sub. Produto</th>
														</tr>
													  </thead>
													<tbody id="relatorioConteudo">
													  
													<?php 	

														
														
														//pesquisa no form local
														$andExternoTkt = ' ';

														if($filtro != ""){
															if($filtro == "DUPLICADOS"){
																$andFiltro = "AND a.cod_produto IN(SELECT a.cod_produto
																				FROM VANTAGEMEXTRAFAIXA A
																				LEFT JOIN CAMPANHA B ON A.COD_CAMPANHA= B.COD_CAMPANHA
																				LEFT JOIN produtocliente P ON A.COD_PRODUTO = P.COD_PRODUTO
																				WHERE A.COD_CAMPANHA = '46' 
																				AND A.TIP_FAIXAS = 'PRD'
																				GROUP BY A.COD_PRODUTO
																				HAVING COUNT( A.COD_PRODUTO)>1
																				ORDER BY P.DES_PRODUTO)";
															}else{
																$andFiltro = " AND P.$filtro LIKE '%$val_pesquisa%' ";
															}
														}else{
															$andFiltro = " ";
														}

														// fnEscreve($andFiltro);
														
														//se pesquisa dos produtos do ticket
														if (!empty($_GET['idP'])) {$andExterno = 'AND A.COD_EXTERNO = "'.$_GET['idP'].'"';}
												
														$sql="SELECT COUNT(*) AS CONTADOR
                                                              FROM CUPOMCATEGORIAESPECIFICA A
                                                              WHERE A.COD_EMPRESA = $cod_empresa AND A.COD_CAMPANHA = $cod_campanha AND A.COD_EXCLUSA = 0
                                                              ORDER BY A.ID
                                                              ";	

														//fnEscreve($sql);
														
														$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
														$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
														
														$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);															
																
														//variavel para calcular o início da visualização com base na página atual
														$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
													
														$sql="SELECT        A.ID,
	                                                                        A.QTD_CUPOMEXT,
	                                                                        A.TIP_CUPOMEXT,
	                                                                        A.COD_CATEGOR,
	                                                                        A.COD_SUBCATE,
	                                                                        B.DES_CAMPANHA,
	                                                                        C.DES_CATEGOR,
	                                                                        D.DES_SUBCATE
	                                                        FROM CUPOMCATEGORIAESPECIFICA A
	                                                        INNER JOIN campanha B ON A.COD_CAMPANHA = B.COD_CAMPANHA
	                                                        LEFT JOIN categoria C ON A.COD_CATEGOR = C.COD_CATEGOR
	                                                        LEFT JOIN subcategoria D ON A.COD_SUBCATE = D.COD_SUBCATE
	                                                        WHERE A.COD_EMPRESA = $cod_empresa AND A.COD_CAMPANHA = $cod_campanha
	                                                        AND A.COD_EXCLUSA = 0                                                                                                                 
	                                                        ORDER BY A.ID LIMIT $inicio,$itens_por_pagina";
														
														//fnEscreve($sql);
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										
														$count=0;
														$countLinha = 1;
														while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery))
														  {														  
															$count++;
															
															if ($qrBuscaCampanhaExtra['TIP_CUPOMEXT'] == "ABS") { $tipoGanho = $nom_tpcampa; }
															else { $tipoGanho = ""; }
													
															echo"
																<tr>
																  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
																  <td>".$qrBuscaCampanhaExtra['ID']."</td>
																  <td>".$qrBuscaCampanhaExtra['DES_CATEGOR']."</td>
																  <td>".$qrBuscaCampanhaExtra['DES_SUBCATE']."</td>															
																</tr>
																<input type='hidden' id='ret_COD_CATEGORIA_".$count."' value='".$qrBuscaCampanhaExtra['ID']."'>
																<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrBuscaCampanhaExtra['COD_CATEGOR']."'>
                                                                                                                                <input type='hidden' id='ret_TIP_CUPOMEXT_".$count."' value='".$qrBuscaCampanhaExtra['TIP_CUPOMEXT']."'>
																<input type='hidden' id='ret_QTD_CUPOMEXT_".$count."' value='".$qrBuscaCampanhaExtra['QTD_CUPOMEXT']."'>
																<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrBuscaCampanhaExtra['COD_SUBCATE']."'>
																"; 
																
																$countLinha++;
															  }											

													?>
														
													</tbody>
													
													<tfoot>
														<tr>
														  <th class="" colspan="100">
															<center><ul id="paginacao" class="pagination-sm"></ul></center>
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

//-----------------------------------------------------------------------------------

    $(document).ready( function() {

            var numPaginas = <?php echo $numPaginas; ?>;
            if(numPaginas != 0){
                    carregarPaginacao(numPaginas);
            }			

            //chosen obrigatório
            $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
            $('#formulario').validator();

            //modal close
            // $('.modal').on('hidden.bs.modal', function () {

            // 	if ($('#MULTI_PROD').val() != ""){
            // 		// $("#formulario").submit();				
            // 	}
            // });

    });

    $("#COD_CATEGOR").change(function () {
            var codBusca = $("#COD_CATEGOR").val();
            var codBusca3 = $("#COD_EMPRESA").val();
            buscaSubCat(codBusca,0,codBusca3);
    });

    function buscaSubCat(idCat,idSub,idEmp) {
            $.ajax({
                    type: "GET",
                    url: "ajxBuscaSubGrupo.php",
                    data: { ajx1:idCat,ajx2:idSub,ajx3:idEmp},
                    beforeSend:function(){
                            $('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
                    },
                    success:function(data){
                            $("#divId_sub").html(data); 
                    },
                    error:function(){
                            $('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
                    }
            });		
    }


    function reloadPage(idPage) {
            $.ajax({
                    type: "POST",
                    url: "ajxConfigFidProdExtra.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&cod_campanha=<?php echo fnEncode($cod_campanha); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
                    data: $('#formulario').serialize(),
                    beforeSend:function(){
                            $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
                    },
                    success:function(data){
                            $("#relatorioConteudo").html(data);										
                    },
                    error:function(){
                            $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
                    }
            });		
    }		


    function retornaForm(index){

            $("#formulario #ID").val($("#ret_COD_CATEGORIA_"+index).val());
            $("#formulario #TIP_CUPOMEXT").val($("#ret_TIP_CUPOMEXT_"+index).val()).trigger("chosen:updated");
            $("#formulario #QTD_CUPOMEXT").val($("#ret_QTD_CUPOMEXT_"+index).val());
            $("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val()).trigger("chosen:updated");

            var codCat = $("#ret_COD_CATEGOR_"+index).val();
            var codSub = $("#ret_COD_SUBCATE_"+index).val();			
            buscaSubCat(codCat,codSub,<?php echo $cod_empresa; ?>);


            $("#formulario #COD_SUBCATE").val($("#ret_COD_SUBCATE_"+index).val());
            $('#formulario').validator('validate');			
            $("#formulario #hHabilitado").val('S');						
    }

</script>	
   