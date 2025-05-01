
<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();
	
	$adm = $connAdm->connAdm();
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
	$qtd_produto = 10;
	$cod_persona = 0;
	
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
			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			// $cod_univend = $_POST['COD_UNIVEND'];
			// $cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			// $cod_tiporeg = $_REQUEST['COD_TIPOREG'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$cod_externo = fnLimpaCampo($_POST['COD_EXTERNO']);			
			$cod_produto = fnLimpaCampo($_POST['COD_PRODUTO']);
			$cod_univend_aut =(implode("|", $_POST["COD_UNIVEND_AUT"]));
			$cod_propriedade = fnLimpacampoZero($_REQUEST['COD_PROPRIEDADE']);	

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
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}
		
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}

$andLojasUsu = "";
$optAllUnivend = "<option value='9999'>Todas Unidades</option>";
$CarregaMaster='1';

if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='0'){

	$lojasUsuario = $_SESSION["SYS_COD_UNIVEND"];
	$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);
	$arrayAutorizado2 = str_replace(",", "|", $_SESSION["SYS_COD_UNIVEND"]);
	$andLojasUsu = "AND COD_UNIVEND IN ($lojasUsuario)";
	$optAllUnivend = "";
	$CarregaMaster='0';


}

if($lojasUsuario == ""){
	$andUsuario = 0;
}else{
	$andUsuario = $lojasUsuario;
}
//fnEscreve($andUsuario);

$uniAutObg = "disabled";
$lblAutObg = "";
$txtAutObg = "Se vazio <b>todas</b> as unidades estarão <b>autorizadas</b>";
$radioAutObg = "";

if($_SESSION["SYS_COD_TPUSUARIO"] != 9 && $_SESSION["SYS_COD_TPUSUARIO"] != 16){
	$uniAutObg = "required";
	$lblAutObg = "required";
	$txtAutObg = "";
	$radioAutObg = "checked disabled";
}
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	//fnMostraForm();
	//fnEscreve($cod_cliente);
	
?>
		
					<div class="push30"></div> 
					
					<div class="row">
                                            
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fal fa-terminal"></i>
										<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa;?></span>
									</div>
									
									<?php 
									$formBack = "1015";
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
																	
										<fieldset>
											<legend>Filtros</legend> 
											
												<div class="row">
												
													<!-- <div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Unidade de Atendimento</label>
															<?php include "unidadesAutorizadasComboMulti.php"; ?>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Grupo de Lojas</label>
															<?php include "grupoLojasComboMulti.php"; ?>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Região</label>
															<?php include "grupoRegiaoMulti.php"; ?>
														</div>
													</div> -->
													
													<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Lista de Produtos</label>
																<select data-placeholder="escolha a quantidade" name="QTD_PRODUTO" id="QTD_PRODUTO" class="chosen-select-deselect">
																	<option value="0">&nbsp;</option>					
																	<option value="10">10</option>					
																	<option value="50">50</option>					
																	<option value="100">100</option>					
																	<option value="200">200</option>					
																	<option value="500">500</option>					
																	<option value="1000">1000</option>					
																</select>	
															<div class="help-block with-errors"></div>
														</div>
														<script>$("#formulario #QTD_PRODUTO").val(<?php echo $qtd_produto; ?>).trigger("chosen:updated");</script>
													</div> -->

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Unidade de Atendimento</label>
															<?php include "unidadesAutorizadasComboMulti.php"; ?>
														</div>
													</div>
													
													<!-- <div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Unidades de Venda</label>

															<select data-placeholder="Selecione uma unidade" name="COD_UNIVEND_AUT[]" id="FIL_COD_UNIVEND_AUT" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																<?php
																$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andLojasUsu ORDER BY NOM_FANTASI ";
																$arrayQuery = mysqli_query($adm, $sql);
																while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
																	echo "
																		<option value='" . $qrListaUnive['COD_UNIVEND'] . "' " . (in_array($qrListaUnive['COD_UNIVEND'], @$_POST["COD_UNIVEND_AUT"]) ? "selected" : "") . ">" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
																	";
																}
																?>
															</select>
															<?php //fnEscreve($sql); 
															?>
															<div class="help-block with-errors">Se vazio <b>todas</b> as unidades estarão <b>autorizadas</b></div>
														</div>
													</div> -->

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo Propriedade</label>
															<select data-placeholder="Selecione o tipo da propriedade" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect">
																<option value=""></option>					
																<option value="0">Todas</option>					
																<?php 																	
																$sql = "select COD_PROPRIEDADE, DES_PROPRIEDADE from tppropriedade ORDER BY DES_PROPRIEDADE";
																$arrayQuery = mysqli_query($adm,$sql);

																while ($qrListaProp = mysqli_fetch_assoc($arrayQuery))
																{														
																	echo"
																	<option value='".$qrListaProp['COD_PROPRIEDADE']."'>".$qrListaProp['DES_PROPRIEDADE']."</option> 
																	"; 
																}											
																?>	
															</select>	
															<div class="help-block with-errors"></div>
															<script type="text/javascript">$("#COD_PROPRIEDADE").val("<?=$cod_propriedade?>").trigger("chosen:updated");</script>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Cod. Externo</label>
															<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" value="<?php echo $cod_externo; ?>">
														</div>														
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Cod. Produto</label>
															<input type="text" class="form-control input-sm" name="COD_PRODUTO" id="COD_PRODUTO" value="<?php echo $cod_produto; ?>">
														</div>														
													</div>

												</div>

												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Final</label>
															
															<div class="input-group date datePicker" id="DAT_FIM_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>													
													
													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>
																		
													
												</div>

												<input type="hidden" name="AND_USUARIO" id="AND_USUARIO" value="<?=fnEncode($andUsuario)?>">
													
										</fieldset>
									</div>
							</div>
					</div>
						
					<div class="push20"></div>
					
					<div class="portlet portlet-bordered">
						
									<div class="portlet-body">
										
										<div class="login-form">
										
										<div class="push20"></div>
									
										<div class="row">
															
											<div class="col-md-12" id="div_Produtos">
  
												<div class="push20"></div>
												
												<table class="table table-bordered table-hover tablesorter">
												
												  <thead>
													<tr>
													  <th><small>Unidade</small></th> 
													  <th><small>Produto</small></th>
													  <th class="text-center"><small>Prod. Ticket</small></th> 
													  <th class="text-center"><small>Cod. Produto</small></th>
													  <th class="text-center"><small>Cod. Externo</small></th>
													  <th class="text-center"><small>Cod. Venda.</small></th>
													  <th class="text-center"><small>Qtd. Produto</small></th> 
													  <th class="text-center"><small>Val. Total</small></th>
													  <th class="text-center"><small>Val. Desconto</small></th>
													  <th class="text-center"><small>Val. Líquido</small></th>
													</tr>
												  </thead>
													
													<?php

														// Filtro por Grupo de Lojas
														// include "filtroGrupoLojas.php";

														if($cod_externo != ""){
															$andCodExt = "AND prod.COD_EXTERNO = '$cod_externo'";
														}else{
															$andCodExt = "";
														}

														if($cod_produto != ""){
															$andCodProd = "AND itm_venda.COD_PRODUTO = '$cod_produto'";
														}else{
															$andCodProd = "";
														}

														if(fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='1'){
															$CarregaMaster='1';
														
														} else {
															$CarregaMaster='0';
														}													
														$where = "";
														
														if($carregaMaster == '1'){
															$andUnivend = "";
														}


														if(count($cod_univend_aut) > 0){
															$where = " AND prdtkt.COD_UNIVEND_AUT REGEXP '^(" .$cod_univend_aut. ")'";
															$andUnidade = " AND ven.COD_UNIVEND IN (".str_replace("|", ",",$cod_univend_aut).")";
														}else if($CarregaMaster == 1){
															$where = "";
														}else{
															$where = "AND prdtkt.COD_UNIVEND REGEXP '^(".str_replace(",", "|",$andUsuario).")'";
															$andUnidade = " AND ven.COD_UNIVEND IN (".str_replace(",", "|",$andUsuario).")";
														}

														if($cod_propriedade != 0){
															$andProp = " AND UNI.COD_PROPRIEDADE = $cod_propriedade";
														}else{
															$andProp = "";
														}

														// REGEXP '^("9999|9999")'
															   

                                                        // $sql = "SELECT 

														// 	             CASE WHEN prdtkt.COD_PRODUTO IS NOT NULL 
														// 	             AND prdtkt.DAT_FIMPTKT >= '$dat_ini 00:00:00' THEN '1' ELSE '0' END LISTA_TICKET, 
															             
														// 	            itm_venda.COD_TICKET,
														// 					TKT.COD_PRODUTO PRODUTO_TKT, 
														// 					itm_venda.COD_PRODUTO, 
														// 					uni.NOM_FANTASI, 
														// 					itm_venda.COD_ITEMVEN, 
														// 					itm_venda.COD_EXTERNO, 
														// 					itm_venda.COD_VENDA, 
														// 					sum(itm_venda.QTD_PRODUTO) QTD_PRODUTO, 
														// 					sum(itm_venda.VAL_TOTITEM) VAL_TOTITEM, 
														// 					sum(itm_venda.VAL_DESCONTO) VAL_DESCONTO, 
														// 					sum(itm_venda.VAL_LIQUIDO) VAL_LIQUIDO, 
														// 					itm_venda.DAT_CADASTR, 
														// 				   prod.DES_PRODUTO, 
														// 			    	cli.NOM_CLIENTE, 
														// 					ven.COD_UNIVEND 
															      
															      
														// 	    FROM  vendas ven
														// 	      inner JOIN itemvenda itm_venda ON  itm_venda.COD_VENDA = ven.COD_VENDA and ven.cod_empresa = itm_venda.cod_empresa  
														// 	      inner JOIN ticket TKT  ON TKT.COD_TICKET = itm_venda.COD_TICKET and TKT.cod_empresa = ven.cod_empresa 
														// 		   INNER JOIN clientes cli ON cli.COD_CLIENTE = ven.COD_CLIENTE      
														// 		   INNER JOIN produtocliente prod ON prod.COD_PRODUTO = itm_venda.COD_PRODUTO	   
														// 			left JOIN produtotkt prdtkt ON prdtkt.COD_PRODUTO = itm_venda.COD_PRODUTO 		   
														// 			LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND = ven.COD_UNIVEND  
														// 	  WHERE ven.cod_empresa = $cod_empresa
														// 	      AND ven.LOG_TICKET = 'S'  
														// 	      AND ven.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
														// 	      $andUnidade
														// 	      $andCodExt
														// 		  $andCodProd
														// 	 GROUP BY ven.COD_UNIVEND,itm_venda.COD_PRODUTO";	

															 $sql = "SELECT 
																		CASE
																		         WHEN prdtkt.cod_produto IS NOT NULL
																		              AND prdtkt.dat_fimptkt >= '$dat_ini 00:00:00' THEN '1'
																		         ELSE '0'
																		       END                         LISTA_TICKET,
																		       tmptkt.COD_TICKET,
																		       tmptkt.PRODUTO_TKT,
																		       tmptkt.COD_PRODUTO,
																		       tmptkt.NOM_FANTASI,
																		       tmptkt.COD_ITEMVEN,
																		       tmptkt.COD_EXTERNO,
																		       tmptkt.COD_VENDA,
																		       tmptkt.QTD_PRODUTO,
																		       tmptkt.VAL_TOTITEM,
																		       tmptkt.VAL_DESCONTO,
																		       tmptkt.VAL_LIQUIDO,
																		       tmptkt.DAT_CADASTR,
																		       tmptkt.DES_PRODUTO,
																		       tmptkt.NOM_CLIENTE,
																		       tmptkt.COD_UNIVEND 


																		FROM (

																		SELECT 
																		       itm_venda.cod_ticket,
																		       TKT.cod_produto             PRODUTO_TKT,
																		       itm_venda.cod_produto,
																		       uni.nom_fantasi,
																		       itm_venda.cod_itemven,
																		       itm_venda.cod_externo,
																		       itm_venda.cod_venda,
																		       Sum(itm_venda.qtd_produto)  QTD_PRODUTO,
																		       Sum(itm_venda.val_totitem)  VAL_TOTITEM,
																		       Sum(itm_venda.val_desconto) VAL_DESCONTO,
																		       Sum(itm_venda.val_liquido)  VAL_LIQUIDO,
																		       itm_venda.dat_cadastr,
																		       prod.des_produto,
																		       cli.nom_cliente,
																		       ven.cod_univend
																		FROM   vendas ven
																		       INNER JOIN itemvenda itm_venda ON itm_venda.cod_venda = ven.cod_venda AND ven.cod_empresa = itm_venda.cod_empresa
																		       INNER JOIN ticket TKT ON TKT.cod_ticket = itm_venda.cod_ticket AND TKT.cod_empresa = ven.cod_empresa
																		       INNER JOIN clientes cli  ON cli.cod_cliente = ven.cod_cliente
																		       INNER JOIN produtocliente prod ON prod.cod_produto = itm_venda.cod_produto
																		       LEFT JOIN unidadevenda UNI ON uni.cod_univend = ven.cod_univend
																		WHERE  ven.cod_empresa = $cod_empresa
																		       AND ven.log_ticket = 'S'
																		       AND date(ven.dat_cadastr_ws) BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
																		       AND ven.COD_UNIVEND IN ($lojasSelecionadas)
																		       $andProp
																		       $andCodExt
																			   $andCodProd
																		 GROUP  BY ven.cod_univend,    itm_venda.cod_produto 
																		)tmptkt
																		 LEFT JOIN produtotkt prdtkt ON prdtkt.cod_produto = tmptkt.cod_produto
																		 GROUP  BY tmptkt.cod_univend,    tmptkt.cod_produto";	

														// fnEscreve($sql);
															
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

														//echo "<pre>";
														//print_r($arrayQuery);
														//echo "</pre>";
														
														$countLinha = 1;
														$totalUnit = 0;
														
														while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
														  {

															

														  	$listaTicket = "";

														  	$prod = $qrListaVendas['DES_PRODUTO'];

														  	if($qrListaVendas['LISTA_TICKET'] == 1){
														  		$listaTicket = "<span class='fas fa-check'></span>";
														  		$prod = "<b>".$qrListaVendas['DES_PRODUTO']."</b>";
														  	}

															?>	
																<tr>
																  <td><small><?php echo $qrListaVendas['COD_UNIVEND']." - ".$qrListaVendas['NOM_FANTASI']; ?></small></b></td>
																  <td><small><?php echo $prod; ?></small></td>
																  <td class="text-center"><b><small><?php echo $listaTicket ?></small></b></td>
																  <td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></b></td>
																  <td class="text-center"><small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></b></td>
																  <td class="text-center"><small><?php echo $qrListaVendas['COD_VENDA']; ?></small></b></td>
																  <td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'],0); ?></small></b></td>
																  <td class="text-right"><b><small>R$ </small><small> <?php echo fnValor($qrListaVendas['VAL_TOTITEM'],2); ?></small></b></td>
																  <td class="text-right"><b><small>R$ </small><small> <?php echo fnValor($qrListaVendas['VAL_DESCONTO'],2); ?></small></b></td>
																  <td class="text-right"><b><small>R$ </small><small> <?php echo fnValor($qrListaVendas['VAL_LIQUIDO'],2); ?></small></b></td>
																</tr>
															<?php
															
															$totalProd += $qrListaVendas['QTD_PRODUTO']; 
															$totalVendas += $qrListaVendas['VAL_TOTITEM']; 
															$totalDesc += $qrListaVendas['VAL_DESCONTO']; 
															$totalLiq += $qrListaVendas['VAL_LIQUIDO']; 
															
														  $countLinha++;	
														  
														  }

														  
													//fnEscreve($countLinha-1);				
													?>	
														<tr>
														  <td colspan="6"></td>
														  <td class="text-center"><small><b><?=fnValor($totalProd,0); ?></b></small></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($totalVendas,2); ?></b></small></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($totalDesc,2); ?></b></small></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($totalLiq,2); ?></b></small></td>
														</tr>
												
													</tbody>
													<tfoot>
														<tr>
															<th colspan="100">
																<a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="N"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a> &nbsp;&nbsp;
																<!-- <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="S"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar Detalhes </a> -->
															</th>
														</tr>													
													</tfoot>													
												</table>
																								
											</div>
											
										</div>
										<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />						
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<div class="push5"></div> 
										
									
										
									<div class="push50"></div>									
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
                                            </form>	
					</div>					
	
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
					
	
														
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
	
		//datas
		$(function () {
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});			

		});	
	

		function abreDetail(idBloco){
			var idItem = $('.abreDetail_' + idBloco)
			if (!idItem.is(':visible')){
				idItem.show();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
			}else{
				idItem.hide();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
			}
		}

		function exportarCSV(btn) {
			// log_detalhes = $(btn).attr('value');
			// alert(id);
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
									icon: 'fa fa-check-square',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxRelProdutosTopTicket.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>", 
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
			}
		
	</script>	
   