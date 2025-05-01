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
			
			$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
			$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
			$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
			$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
			$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
			$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
			
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
	}
    
	//busca dados da url	
    if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            //busca dados da empresa
            $cod_empresa = fnDecode($_GET['id']);	
            $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
            //fnEscreve($sql);
            $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
            $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

            if (isset($arrayQuery)){
                    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
                    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
                    $cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
            }

    }else {
            $cod_empresa = 0;
           // $codEmpresa = $qrBuscaEmpresa['COD_SISTEMA'];

    }

    $andLojasUsu = "";
	$optAllUnivend = "<option value='9999'>Todas Unidades</option>";
	$CarregaMaster='1';
	$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

	if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='0'){

		$lojasUsuario = $_SESSION["SYS_COD_UNIVEND"];
		$arrLojasAut = explode(",", $_SESSION["SYS_COD_UNIVEND"]);
		$arrayLojasAut2 = str_replace(",", "|", $_SESSION["SYS_COD_UNIVEND"]);
		$andLojasUsu = "AND (DESAFIO.COD_UNIVEND REGEXP '^($arrayLojasAut2)' OR DESAFIO.COD_UNIVEND = '9999')";
		$optAllUnivend = "";
		$CarregaMaster='0';

}
	
	//fnMostraForm();
	//fnEscreve("QunXraEOVrg¢");

?>

				<link rel="stylesheet" href="css/widgets.css" />
			
					<div class="push30"></div> 
						
					<!-- Portlet -->
					<div class="portlet portlet-bordered">
						
						<div class="portlet-title">
							<div class="caption">
								<i class="far fa-terminal"></i>
								<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
							</div>
							
							<?php 
							$formBack = "1048";
							include "atalhosPortlet.php"; ?>
							
						</div>								
							
						<div class="push10"></div> 
						
						<div class="portlet-body">
							
							<?php if ($msgRetorno <> '') { ?>	
							<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							 <?php echo $msgRetorno; ?>
							</div>
							<?php } ?>
							
							
							<div class="row">
								
								<div class="col-md-12">
								
									<div class="alert alert-warning" role="alert">
										<h3 class="bg-warning " style="margin:10px 0 10px 0;">Desafios <strong>simples</strong> com grandes <strong>Resultados</strong> </h3>
									</div>										
								
								</div>
								
							</div>
							
							<div class="push30"></div>	

							<div class="row">
							
								<h3 style="margin: 0 0 20px 15px;"><strong>Desafios</strong> que trazem <strong>Resultados</strong></h3>
								
								<div class="col-md-3">
								
									<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1375)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Desafio / <?php echo $nom_empresa; ?>"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Criar Novo Desafio</a>
																			
								</div>								
							
								<div class="push20"></div>
									
								<!-- <a name="campanha"/> -->
							
								<div class="col-md-12">
								
									<table class="table table-bordered table-striped table-hover tablesorter">
									  <thead>
										<tr>
										  <th>Nome do Desafio</th>
										  <th class="text-center">Hits</th>
										  <th class="text-center {sorter:false}">Ativo</th>
										  <th class="text-center">Data Início</th>
										  <th class="text-center">Data Fim</th>
										  <th class="text-center">Meta %</th>
										  <th class="{sorter:false}"></th>
										</tr>
									  </thead>
									<tbody id="div_refreshDesafio">											
								
									<?php
										$sql = "SELECT DESAFIO.*,
										(SELECT count(1) from DESAFIO_CONTROLE where DESAFIO_CONTROLE.COD_DESAFIO = DESAFIO.COD_DESAFIO) as hitsDesafio	
										FROM DESAFIO 
										WHERE DESAFIO.COD_EMPRESA = $cod_empresa 
										$andLojasUsu
										order by DAT_INI desc ";
										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										
										$count=0;
										while ($qrListaDesafio = mysqli_fetch_assoc($arrayQuery))
										  {	                                           
											$count++; 
											
											if ($qrListaDesafio['LOG_ATIVO'] == "S"){$desafioAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";}
											else {$desafioAtivo = "<i class='fas fa-times' aria-hidden='true' style='color: #F00;'></i>";}
											
									?>

									<tr>
									  <td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaDesafio['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaDesafio['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaDesafio['NOM_DESAFIO']; ?></td>
									  <td class='text-center'><?php echo fnValor($qrListaDesafio['hitsDesafio'],0); ?></td>
									  <td class='text-center'><?php echo $desafioAtivo; ?></td>
									  <td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_INI']); ?></td>
									  <td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_FIM']); ?></td>
									  <td class="text-center"><small><?php echo fnValor($qrListaDesafio['VAL_METADES'],2); ?></td>
									  <td class="text-center">
										<small>
											<div class="btn-group dropdown dropleft">
												<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													ações &nbsp;
													<span class="fas fa-caret-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
													<li><a href='javascript:void(0)' class='addBox' data-url="action.php?mod=<?php echo fnEncode(1375)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($qrListaDesafio['COD_DESAFIO'])?>&pop=true" data-title="Desafio / <?php echo $qrListaDesafio['NOM_DESAFIO']; ?>">Editar </a></li>
													<li><a href="action.php?mod=<?php echo fnEncode(1376);?>&id=<?php echo fnEncode($cod_empresa);?>&idD=<?php echo fnEncode($qrListaDesafio['COD_DESAFIO']); ?>">Acessar </a></li>
													<li class="divider"></li>
													<li><a href="javascript:void(0)" onclick='excTemplate("<?=fnEncode($qrListaDesafio[COD_DESAFIO])?>")'>Excluir</a></li>
												</ul>
											</div>
										</small>
									  </td>
									</tr>

									<?php
										  }											
									
									?>
										
									</tbody>
									</table>
									
								</div>
								
							</div>
											
							
							<div class="push30"></div>
							
							<div class="row">
							
								<h3 style="margin: 0 0 20px 15px;">Lista de <strong>Personas</strong> disponíveis </h3>

								<div class="col-md-12">
								
									<table class="table table-bordered table-striped table-hover tablesorter">
									 <thead>
										<tr>
										  <th class="{sorter:false}"></th>
										  <th>Nome da Persona</th>
										  <th class="text-center"><i class='fas fa-users'></i></th>
										  <th class="text-center">Unidade</th>
										  <th class="text-center">Usuário Cad.</th>
										  <th class="text-center {sorter:false}">Ativa</th>
										  <th class="text-center {sorter:false}">Bloqueada</th>
										  <th>Data de Criação</th>
										  <th>Última Alteração</th>
										  <th class="{sorter:false}"></th>
										  <!-- <th class="{sorter:false}"></th> -->
										</tr>
									  </thead>
									  
									<tbody id="div_refreshPersona">
								
									<?php
									
										$ARRAY_UNIDADE1=array(
													   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
													   'cod_empresa'=>$cod_empresa,
													   'conntadm'=>$connAdm->connAdm(),
													   'IN'=>'N',
													   'nomecampo'=>'',
													   'conntemp'=>'',
													   'SQLIN'=> ""   
													   );
										$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

										$ARRAY_VENDEDOR1=array(
													   'sql'=>"select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa in($cod_empresa,3)",
													   'cod_empresa'=>$cod_empresa,
													   'conntadm'=>$connAdm->connAdm(),
													   'IN'=>'N',
													   'nomecampo'=>'',
													   'conntemp'=>'',
													   'SQLIN'=> ""   
													   );
										$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);
	
										$sql = "CALL SP_BUSCA_PERSONA($cod_empresa, 'S');";

										// fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
                                                                             

										 
										$count=0;
										while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
										  {		
                                                                                    
											$count++;

											$NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaPersonas['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
											if ("S" == "S"){$personaaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";}
											else {$personaaAtivo = "";}

											if ($qrListaPersonas['LOG_CONGELA'] == "S"){$personaCongela = "<i class='far fa-pause-circle' aria-hidden='true'></i>";}
											else {$personaCongela = "";}
											
											if ($qrListaPersonas['LOG_RESTRITO'] == "S" ){
												$personaaAtualiza = "<i class='fal fa-check' aria-hidden='true'></i>";
											}
											else {
												$personaaAtualiza = "";
											}
											// fnEscreve($qrListaPersonas['LOG_RESTRITO']);
											//echo fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"],$_SESSION["SYS_COD_EMPRESA"]);
											//$qrListaPersonas['COD_UNIVED']

											$lojaLoop = $qrListaPersonas['COD_UNIVEND'];
											if ($lojaLoop == 9999){
												$nomeLoja = "Todas";
											} else {
												$NOM_ARRAY_UNIDADE=(array_search($qrListaPersonas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
												$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
											}

											if($qrListaPersonas['COD_USUCADA'] == 0 || $qrListaPersonas['COD_USUCADA'] == ''){
												$usuario = "";
											}else{
												$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
											}
                                                                                        
											if($CarregaMaster == '1'){
												
												
                                               ?> 
                                                          <tr>
                                                           <td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
                                                           <td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
                                                           <td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'],0); ?></td>
                                                           <td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
                                                           <td class="text-center"><small><?php echo $usuario; ?></small></td>
                                                           <td class='text-center'><?php echo $personaaAtivo; ?></td>
                                                           <td class='text-center'><?php echo $personaaAtualiza."&nbsp;".$personaCongela; ?></td>
                                                           <td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
                                                           <td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
                                                           <?php if(fnControlaAcesso("1600",$modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
														  		<td></td>
											           	   <?php }else{ ?>
                                                           <td class="text-center">
											           		<small>
											           			<div class="btn-group dropdown dropleft">
																	<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		ações &nbsp;
																		<span class="fas fa-caret-down"></span>
																    </button>
																	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																		<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar </a></li>
																		<li><a href="action.do?mod=<?php echo fnEncode(1035)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>">Acessar </a></li>
																		<li class="divider"></li>
																		<li><a href="javascript:void(0)" onclick='RefreshPersona("<?=fnEncode($cod_empresa)?>","","<?=fnEncode($qrListaPersonas[COD_PERSONA])?>")'>Desativar </a></li>
																		<li><a href="javascript:void(0)" onclick='excluiPersona("<?=fnEncode($cod_empresa)?>","<?=fnEncode($qrListaPersonas[COD_PERSONA])?>","<?=$qrListaPersonas[qtd_campanha]?>","<?=$qrListaPersonas[DES_PERSONA]?>")'>Excluir </a></li>
																		<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																	</ul>
																</div>
											           		</small>
											           	   </td>
											           	   <?php } ?>
                                                         </tr>
                                               <?php     
                                            }else{
                                              
                                                   
                                                if(recursive_array_search($qrListaPersonas['COD_UNIVEND'],$arrayAutorizado) !== false)
                                                {
                                                ?> 
                                                          <tr>
                                                           <td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
                                                           <td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
                                                           <td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'],0); ?></td>
                                                           <td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
                                                           <td class="text-center"><small><?php echo $usuario; ?></small></td>
                                                           <td class='text-center'><?php echo $personaaAtivo; ?></td>
                                                           <td class='text-center'><?php echo $personaaAtualiza."&nbsp;".$personaCongela; ?></td>
                                                           <td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
                                                           <td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
                                                           <?php if(fnControlaAcesso("1600",$modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
														  		<td></td>
											           	   <?php }else{ ?>
                                                           <td class="text-center">
											           		<small>
											           			<div class="btn-group dropdown dropleft">
																	<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		ações &nbsp;
																		<span class="fas fa-caret-down"></span>
																    </button>
																	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																		<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar </a></li>
																		<li><a href="action.do?mod=<?php echo fnEncode(1035)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>">Acessar </a></li>
																		<li class="divider"></li>
																		<li><a href="javascript:void(0)" onclick='RefreshPersona("<?=fnEncode($cod_empresa)?>","","<?=fnEncode($qrListaPersonas[COD_PERSONA])?>")'>Desativar </a></li>
																		<li><a href="javascript:void(0)" onclick='excluiPersona("<?=fnEncode($cod_empresa)?>","<?=fnEncode($qrListaPersonas[COD_PERSONA])?>","<?=$qrListaPersonas[qtd_campanha]?>","<?=$qrListaPersonas[DES_PERSONA]?>")'>Excluir </a></li>
																		<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																	</ul>
																</div>
											           		</small>
											           	   </td>
											           	   <?php } ?>
                                                         </tr>
                                               <?php     
                                                } 
                                            }        
                                                                                          
                                                                                        
									
										  }											
									
									?>
										
									</tbody>
									</table>
									
								</div>		
								
								<div class="push"></div>
							
								<div class="panel-group" id="accordion">
									 
									<div><!-- div controle do acordion -->
									
										<div class="panel-heading">
										  <h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
												<button class="btn btn-sm btn-default"><i class="fas fa-archive"></i> Personas Arquivadas</button>												  
											</a>
										  </h4>
										</div>

										<div id="collapse1" class="panel-collapse collapse">
										
											<div class="panel-body">
										  
												<div class="row">
												
													<div class="push10"></div>

													<div class="col-md-12">									
								
														<table class="table table-bordered table-striped table-hover tablesorter">
														  <thead>
															<tr>
															  <th class="text-center {sorter:false}"></th>
															  <th>Nome da Persona</th>
															  <th class="text-center"><i class='fas fa-users'></i></th>
															  <th class="text-center">Unidade</th>
															  <th class="text-center {sorter:false}">Ativa</th>
															  <th class="text-center {sorter:false}">Bloqueada</th>
															  <th>Data de Criação</th>
															  <th>Última Alteração</th>
															  <th class="{sorter:false}"></th>
															</tr>
														  </thead>
														  
														<tbody id="div_refreshPersona">											
													
														<?php
															$sql = "CALL SP_BUSCA_PERSONA($cod_empresa, 'N');";

															//fnEscreve($sql);
															$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															
															$count=0;
															while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
															  {														  
																$count++;
						
																if ("S" == "S"){$personaaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";}
																else {$personaaAtivo = "";}
																
																if ($qrListaPersonas['LOG_BLOQUEA'] == "S"){$personaaAtualiza = "<i class='fal fa-check' aria-hidden='true'></i>";}
																else {$personaaAtualiza = "";}
																
																//$qrListaPersonas['COD_UNIVED']
																	
																	if($CarregaMaster == '1')
																	{
																		$lojaLoop = $qrListaPersonas['COD_UNIVEND'];
																		if ($lojaLoop == 9999){
																			$nomeLoja = "Todas";
																		} else {
																			$nomeLoja = $qrListaPersonas['COD_UNIVEND'];
																		}
																		
																		
																	   ?> 
																				  <tr>
																				   <td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
																				   <td><?php echo $qrListaPersonas['DES_PERSONA'];; ?></td>
																				   <td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'],0); ?></td>
																				   <td class="text-center"><?php echo $nomeLoja; ?></td>
																				   <td class='text-center'><?php echo $personaaAtivo; ?></td>
																				   <td class='text-center'><?php echo $personaaAtualiza; ?></td>
																				   <td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
																				   <td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
																				   <td class="text-center">
																					<small>
																						<div class="btn-group dropdown dropleft">
																							<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																								ações &nbsp;
																								<span class="fas fa-caret-down"></span>
																						    </button>
																							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																								<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar </a></li>
																								<li><a href="action.do?mod=<?php echo fnEncode(1035)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>">Acessar </a></li>
																								<!-- <li class="divider"></li> -->
																								<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																							</ul>
																						</div>
																					</small>
																	           	   </td>
																				 </tr>
																	   <?php     
																	}else{
																	  
																		?> 
																				  <tr>
																				   <td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
																				   <td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
																				   <td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'],0); ?></td>
																				   <td class="text-center"><?php echo $nomeLoja; ?></td>
																				   <td class='text-center'><?php echo $personaaAtivo; ?></td>
																				   <td class='text-center'><?php echo $personaaAtualiza; ?></td>
																				   <td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
																				   <td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
																				   <td class="text-center">
																					<small>
																						<div class="btn-group dropdown dropleft">
																							<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																								ações &nbsp;
																								<span class="fas fa-caret-down"></span>
																						    </button>
																							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																								<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar </a></li>
																								<li><a href="action.do?mod=<?php echo fnEncode(1035)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA'])?>">Acessar </a></li>
																								<!-- <li class="divider"></li> -->
																								<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																							</ul>
																						</div>
																					</small>
																	           	   </td>
																				 </tr>
																	   <?php     
																	}
															  }											
														
														?>
															
														</tbody>
														</table>
														
													</div>

												</div>
										  
											</div>
										  
										</div>
										
									</div>
									
								</div>
								
							</div><!-- fim div controle ajax persona -->	
							
						</div>
				
						<div class="push10"></div>
									
					</div>
					
				</div><!-- fim Portlet body -->
		
			</div><!-- fim Portlet  -->							

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
				
			<div class="push20"></div>

			<form id="formModal">					
				<input type="hidden" class="input-sm" name="REFRESH_DESAFIO" id="REFRESH_DESAFIO" value="N"> 
				<input type="hidden" class="input-sm" name="REFRESH_PERSONA" id="REFRESH_PERSONA" value="N"> 					
			</form>
	
	<script type="text/javascript">
	
		$(document).ready(function(){

			jQuery('#paginacao').on('page',function(event, page) {
			    current_page = page;
			    // console.log('current_page', current_page);
			});
			
			//modal close
			$('#popModal').on('hidden.bs.modal', function () {
			  
			  if ($('#REFRESH_PERSONA').val() == "S"){
				//alert("atualiza");
				RefreshPersona("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_PERSONA').val("N");				
			  }	
			  
			  if ($('#REFRESH_DESAFIO').val() == "S"){
				//alert("atualiza lista");
				RefreshDesafio("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_DESAFIO').val("N");				
			  }
			  
			});
			
		});

		function excTemplate(idDesafio) {
			$.alert({
	          title: "Confirmação",
	          content: "Deseja mesmo excluir o desafio?",
	          type: 'red',
	          buttons: {
	            "Excluir": {
	               btnClass: 'btn-danger',
	               action: function(){
	               	    $.ajax({
							type: "POST",
							url: "ajxRefreshDesafio.do?opcao=excluir&id=<?=fnEncode($cod_empresa)?>",
							data: {COD_DESAFIO:idDesafio},
							beforeSend:function(){
								$('#div_refreshDesafio').html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$("#div_refreshDesafio").html(data);				
							},
							error:function(){
								$('#div_refreshDesafio').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
							}
						});
	               }
	            },
	            "Cancelar": {
	               action: function(){
	                
	               }
	            }
	          }
	        });		
		}

		function RefreshPersona(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshPersona.php",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#div_refreshPersona').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_refreshPersona").html(data); 
				},
				error:function(){
					$('#div_refreshPersona').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
		
		function RefreshDesafio(idEmp) {
			$.ajax({
				type: "POST",
				url: "ajxRefreshDesafio.do?id=<?=fnEncode($cod_empresa)?>",
				data: {COD_DESAFIO:0},
				beforeSend:function(){
					$('#div_refreshDesafio').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_refreshDesafio").html(data); 
				},
				error:function(){
					$('#div_refreshDesafio').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}		
		
		function retornaForm(index){
			
		}
		
	</script>	