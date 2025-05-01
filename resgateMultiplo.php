<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();

	if(isset($_POST['c1'])){
		$cpf = $_POST['c1'];
	}else{
		$cpf = 0;
	}
	
	$tem_prodaux = "";
	
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
			
			$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
			$cod_resgate = fnLimpacampoZero($_REQUEST['COD_RESGATE']);
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
			$cod_usuario = fnLimpacampoZero($_REQUEST['COD_USUARIO']);
			$val_resgate = fnValorSql(fnLimpacampo($_REQUEST['VAL_RESGATE']));
			$val_saldo = fnLimpacampo($_REQUEST['VAL_SALDO']);

			// fnEscreve($cod_cliente);
			// fnEscreve($cod_resgate);
			// fnEscreve($cod_empresa);
			// fnEscreve($cod_univend);
			// fnEscreve($cod_usuario);
			// fnEscreve($val_resgate);
			
			$opcao = 'FINALIZA';
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			//fnEscreve($opcao);

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];	

						
			if ($opcao != ''){
					
					if($val_resgate > $val_saldo){
						//fnEscreve('entrou aqui');
						$msgRetorno = "Valor do resgate <strong>superior</strong> ao saldo <strong>disponível</strong>!";	
						$msgTipo = 'alert-danger';

					}else {
						
						$sql = "SELECT * FROM AUXRESGATE WHERE COD_RESGATE = $cod_resgate";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
						$recibos = [];
						// fnEscreve($sql);
						while($qrResg = mysqli_fetch_assoc($arrayQuery))
						{
							$val_resgate = $qrResg['QTD_PRODUTO'] * $qrResg['VAL_UNITARIO'];

							$sql1 = "CALL SP_DEBITA_CREDITO(
										'".$cod_cliente."',
										'".$val_resgate."',
										'".$cod_empresa."',
										'".$cod_usuario."',   
										'".$cod_univend."',
										'".$qrResg['COD_PRODUTO']."',
										'".$qrResg['QTD_PRODUTO']."',
										'".$qrResg['VAL_UNITARIO']."',
										'".$val_resgate."'
									);";

							$qrRecibo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql1));

							array_push($recibos, $qrRecibo['V_COD_CREDITO']);

						}

						$msgTipo = 'alert-success';
						$msgRetorno .= "<br> <a id='btnImprimirVoucher' class='addBox' data-url='action.php?mod=".fnEncode(1393)."&id=".fnEncode($cod_empresa)."&idR=".json_encode($recibos)."&idC=".fnEncode(0)."&pop=true' data-title='Recibo de Resgate'><b>Clique aqui &nbsp;</b></a> para imprimir o voucher";
						
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
	}
                                                        
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$cod_cliente = fnDecode($_GET['idC']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
			if ($tip_retorno == 2){
				$casasDec = 2;
			}else { $casasDec = 0; }
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
		$casasDec = 2;
	}


	//busca dados do cliente
	$sql1="select count(1) as TEMPROMO from PRODUTOPROMOCAO 
		where COD_EMPRESA=$cod_empresa AND COD_EXCLUSA=0 ";

	//fnEscreve($sql1);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1);
	$qrBuscaProdPromocao = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$tempromo = $qrBuscaProdPromocao['TEMPROMO'];
	}else{
		$tempromo = 0;
	}
		
	//busca dados do cliente
	$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE, LOG_TROCAPROD FROM CLIENTES where COD_CLIENTE = '".$cod_cliente."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		
		$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
		$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
		$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
		$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
		$log_trocaprod = $qrBuscaCliente['LOG_TROCAPROD'];

	}else{
				
		$nom_cliente = "";
		$cod_cliente = "";
		$num_cartao = "";
		$num_cgcecpf = "";
		$log_trocaprod = "";
			
	}

	if($log_trocaprod == 'N'){
		$msgRetorno = "Cliente <strong>bloqueado</strong> para trocas!";	
		$msgTipo = 'alert-danger';
	}
	
	//verifica saldo atual					
	$sql = "CALL `SP_CONSULTA_SALDO_CLIENTE`('$cod_cliente')";
	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		
		$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
	}
    	
	//fnMostraForm();
	//fnEscreve($tempromo);
	//fnEscreve($reciboVenda);

	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

	$sql3 = "SELECT COD_UNIVEND FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
	$arrayQuery3 = mysqli_query($connAdm->connAdm(),$sql3);

	//fnEscreve($sql3);

	$qrUs = mysqli_fetch_assoc($arrayQuery3);

	$codUnivend_usu = $qrUs['COD_UNIVEND'];

	$arrayUnidade = explode(",", $codUnivend_usu);

	if(sizeof($arrayUnidade)==1){
		$unidade_retorno = $arrayUnidade[0];
	}else{
		$unidade_retorno = 0;
	}

	// echo '<pre><br><br><br>';
 //    print_r($arrayUnidade);
 //    echo '</pre>';

	$sql = "UPDATE CONTADOR SET COD_ORCAMENTO = (COD_ORCAMENTO+1) WHERE COD_CONTADOR = 1";
	mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));


	$sqlOrc = "SELECT COD_ORCAMENTO FROM CONTADOR WHERE COD_CONTADOR = 1";
	$qrOrc = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlOrc));
	$cod_resgate = $qrOrc['COD_ORCAMENTO'];

	//fnMostraForm();
	//fnEscreve(fnValorSql(fnValor($credito_disponivel,$casasDecimais)));

	// fnEscreve($casasDec);
	// fnEscreve($credito_disponivel);
	
?>
<style>
.widget .widget-title {
    font-size: 14px;
}
.widget .widget-int {
    font-size: 20px;
	padding: 0 0 10px 0;
}
.widget .widget-item-left .fa, .widget .widget-item-right .fa, .widget .widget-item-left .glyphicon, .widget .widget-item-right .glyphicon {
    font-size: 35px;
}

#btnImprimirVoucher {
	color: #1d22ea;
}

#btnImprimirVoucher:hover {
	color: #5c5ef0;
	cursor: pointer;
	text-decoration: none;
}
/* TILES */
	.tile {
	  width: 100%;
	  float: left;
	  margin: 0px;
	  list-style: none;
	  text-decoration: none;
	  font-size: 38px;
	  font-weight: 300;
	  color: #FFF;
	  -moz-border-radius: 5px;
	  -webkit-border-radius: 5px;
	  border-radius: 5px;
	  padding: 10px;
	  margin-bottom: 20px;
	  min-height: 100px;
	  position: relative;
	  border: 1px solid #D5D5D5;
	  text-align: center;
	}
	.tile.tile-valign {
	  line-height: 75px;
	}
	.tile.tile-default {
	  background: #FFF;
	  color: #656d78;
	}
	.tile.tile-default:hover {
	  background: #FAFAFA;
	}
	.tile.tile-primary {
	  background: #33414e;
	  border-color: #33414e;
	}
	.tile.tile-primary:hover {
	  background: #2f3c48;
	}
	.tile.tile-success {
	  background: #95b75d;
	  border-color: #95b75d;
	}
	.tile.tile-success:hover {
	  background: #90b456;
	}
	.tile.tile-warning {
	  background: #fea223;
	  border-color: #fea223;
	}
	.tile.tile-warning:hover {
	  background: #fe9e19;
	}
	.tile.tile-danger {
	  background: #b64645;
	  border-color: #b64645;
	}
	.tile.tile-danger:hover {
	  background: #af4342;
	}
	.tile.tile-info {
	  background: #3fbae4;
	  border-color: #3fbae4;
	}
	.tile.tile-info:hover {
	  background: #36b7e3;
	}
	.tile:hover {
	  text-decoration: none;
	  color: #FFF;
	}
	.tile.tile-default:hover {
	  color: #656d78;
	}
	.tile .fa {
	  font-size: 52px;
	  line-height: 74px;
	}
	.tile p {
	  font-size: 14px;
	  margin: 0px;
	}
	.tile .informer {
	  position: absolute;
	  left: 5px;
	  top: 5px;
	  font-size: 12px;
	  color: #FFF;
	  line-height: 14px;
	}
	.tile .informer.informer-default {
	  color: #FFF;
	}
	.tile .informer.informer-primary {
	  color: #33414e;
	}
	.tile .informer.informer-success {
	  color: #95b75d;
	}
	.tile .informer.informer-info {
	  color: #3fbae4;
	}
	.tile .informer.informer-warning {
	  color: #fea223;
	}
	.tile .informer.informer-danger {
	  color: #b64645;
	}
	.tile .informer .fa {
	  font-size: 14px;
	  line-height: 16px;
	}
	.tile .informer.dir-tr {
	  left: auto;
	  right: 5px;
	}
	.tile .informer.dir-bl {
	  top: auto;
	  bottom: 5px;
	}
	.tile .informer.dir-br {
	  left: auto;
	  top: auto;
	  right: 5px;
	  bottom: 5px;
	}
	/* EOF TILES */
</style>
	<div class="push30"></div> 
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
					</div>
					
					<?php 
					$formBack = "1015";
					include "atalhosPortlet.php"; 
					?>	
					
				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>					
					
					
					
					<div class="push30"></div> 

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						
						<div class="push30"></div>						
		
						<div class="row">
						
							<style>
								.chosen-container {
									font-size: 16px;
								}

								.chosen-container-single .chosen-single {
									height: 45px;
								}

								.chosen-container-single .chosen-single span {
									margin-top: 5px;
								}
							</style>				
							
								
														
							
										
							
						</div>	

							

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="QTD_ESTOQUE" id="QTD_ESTOQUE" value="0">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						
						</form>										
						</div>
						
						
<!-- ######################################################bloco novo ######################################## -->


<div class="row">

	<div class="col-md-6">
	<h4>Produtos do Resgate</h4>
	<div class="push20"></div>
	
		<div class="col-lg-12 col-md-12 col-sm-1 col-xs-12">
			<div class="push20"></div>
			<a name="addProdutos" class="btn btn-success btn-block" onclick='abreModalProduto("action.php?mod=<?php echo fnEncode(1390)?>&id=<?php echo fnEncode($cod_empresa); ?>&idR=<?php echo fnEncode($cod_resgate);?>&pop=true","Busca Produtos Resgate / <?php echo $nom_empresa;?> / Venda Nº <?php echo $cod_resgate;?>")'><i class="fa fa-1x fa-search" aria-hidden="true"></i>&nbsp; ADICIONAR PRODUTOS</a>
		</div>

		<div class="push20"></div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"  id="div_Produtos">
		
			<table class="table table-bordered table-hover">
			  <thead>
				<tr>
				  <th width="40" class="text-center"><i class='fa fa-trash' aria-hidden='true'></i></th>
				  <!--<th>Código</th>-->
				  <th><small>Produto </small></th>
				  <th class="text-right"><small>Preço </small></th>
				  <th class="text-center"><small>Qtd.</small></th>
				  <th class="text-right"><small>Valor Total </small></th>
				</tr>
			  </thead>
			<tbody>
			  
			<?php 
			
				$sql = "select B.DES_PRODUTO,A.* from AUXRESGATE A,PRODUTOPROMOCAO B
						where 
						A.COD_PRODUTO=B.COD_PRODUTO AND
						A.COD_RESGATE = '".$cod_resgate."' order by A.COD_ITEM	";
				
				//fnEscreve($sql);
				
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				
				$count = 0;
				$valorTotal = 0;
				$excManual = '"EXC_MANUAL"';
				$valorTotalResg = 0;
				
				while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;
					
					$valorTotalProd = $qrBuscaProdutos['QTD_PRODUTO'] * $qrBuscaProdutos['VAL_UNITARIO'];	
					
					$valorTotal += $valorTotalProd;

					$valorTotalResg +=$valorTotal;
					
					echo"
						<tr>
						  <td class='text-center'><a href='javascript:void(0);' onclick='RefreshProdutos(".$cod_empresa.",".$qrBuscaProdutos['COD_RESGATE'].",".$excManual.",".$qrBuscaProdutos['COD_ITEM'].")'><i class='fas fa-times text-danger' aria-hidden='true'></i></a></td>
						  <td><small>".$qrBuscaProdutos['DES_PRODUTO']."</small></td>												
						  <td class='text-right'><small>".fnValor($qrBuscaProdutos['VAL_UNITARIO'],$casasDec)."</small></td>
						  <td class='text-center'><small>".fnValor($qrBuscaProdutos['QTD_PRODUTO'],$casasDec)."</small></td>
						  <td class='text-right'><small>".fnValor($valorTotalProd,$casasDec)."</small></td>
						</tr>
						<input type='hidden' id='COD_PRODUTO' value='".$qrBuscaProdutos['COD_PRODUTO']."'>
						"; 
					  }
					  //fnEscreve($valorTotalResg);										

			?>
						
			</tbody>
			</table>	
		
		</div>									
		
	</div>

	<form data-toggle="validator" role="form2" method="post" id="formulario2" action="<?php echo $cmdPage; ?>">

		<div class="col-md-6">
		
		<h4>Detalhes do Resgate <small>(Nº <?=$cod_resgate?>)</small></h4>
		
		<div class="push20"></div>
				
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			
				<div class="form-group">
					<label for="inputName" class="control-label">Cliente</label>
					<input type="text" class="form-control leituraOff" readOnly="readonly" name="NOM_CLIENTE" id="NOM_CLIENTE" tabindex="1" value="<?php echo $nom_cliente; ?>">
				</div>
					
			</div>									

			<div class="push20"></div>

			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="form-group">
					<label for="inputName" class="control-label required">Unidade de Atendimento </label>
						<select data-placeholder="Selecione a unidade de atendimento" data-error="Campo obrigatório" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" onchange="buscaVendedor()" required>
							<option value=""></option>					
							<?php 

								if($_SESSION["SYS_COD_EMPRESA"] != $cod_empresa){
									$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) AND LOG_ESTATUS = 'S' ORDER BY NOM_UNIVEND";
								}else{
									$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND IN($codUnivend_usu) AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) AND LOG_ESTATUS = 'S' ORDER BY NOM_UNIVEND";
								}
									
								$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
							
								while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery))
								{	
									echo"
										  <option value='".$qrListaUnidade['COD_UNIVEND']."'>".$qrListaUnidade['NOM_FANTASI']."</option> 
										"; 
								}											
							?>	
						</select>
					<div class="help-block with-errors"></div>
					<script type="text/javascript">$("#formulario2 #COD_UNIVEND").val(<?=$unidade_retorno?>).trigger("chosen:updated");</script>
				</div>
			</div>	
			
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="form-group">
					<label for="inputName" class="control-label required">Vendedores</label>
					
						<select data-placeholder="Selecione um vendedor" data-error="Campo Obrigatório" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" tabindex="1" required>								
							<option value="">&nbsp;</option>
						</select>
						<?php //fnEscreve($sql); ?>		
					<div class="help-block with-errors"></div>
					<div id="loadPage"></div>
				</div>
			</div>
			
			<div class="push20"></div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<a class="tile tile-info tile-valign">
					<div id="total_de_produtos"><?php echo fnValor($credito_disponivel,$casasDec); ?></div>
					<input type="hidden" id="VAL_SALDO" name="VAL_SALDO" class="VAL_SALDO" value="<?=$credito_disponivel?>">
					<div class="informer informer-default">SALDO DISPONÍVEL PARA RESGATE</div>
				</a>                            
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<a class="tile tile-primary tile-valign">
					<div id="total_da_venda"><?php echo fnValor(($valorTotal),$casasDec); ?></div>
					<input type="hidden" id="VAL_RESGATE" name="VAL_RESGATE" class="VAL_RESGATE" value="<?php echo fnValor($valorTotal,$casasDec); ?>">
					<div class="informer informer-default">TOTAL DO RESGATE</div>
				</a>                            
			</div>
			
			<div class="push20"></div>	

			<div class="col-md-12">
			
				<button type="submit" name="FINALIZA" id="FINALIZA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fal fa-1x fa-box-usd" aria-hidden="true"></i>&nbsp; Finalizar Resgate </button>
										
				<div class="push10"></div> 
				
				<a href="action.do?mod=<?php echo fnEncode(1391); ?>&id=<?php echo fnEncode($cod_empresa); ?>" name="HOME3" id="HOME3" class="btn btn-info btn-lg btn-block" tabindex="5"><i class="fal fa-1x fa-hand-holding-usd" aria-hidden="true"></i>&nbsp; Novo Resgate </a>
			
			</div>									
		
		</div>

		<input type="hidden" name="REFRESH_PRODUTOS" id="REFRESH_PRODUTOS" value="N">
		<input type="hidden" name="NUM_PONTOS" id="NUM_PONTOS" value="">
		<input type="hidden" name="QTD_PRODUTO" id="QTD_PRODUTO" value="">
		<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
		<input type="hidden" name="COD_RESGATE" id="COD_RESGATE" value="<?php echo $cod_resgate; ?>">
		<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
		<input type="hidden" name="opcao" id="opcao" value="">

	</form>
	
</div><!-- /row -->

<!-- <input type="hidden" name="c1" id="c1" value="<?php echo $cpf; ?>" required >
<input type="hidden" name="c10" id="c10" value="<?php echo $cpf; ?>" required >
<input type="hidden" name="COD_LOJA" id="COD_LOJA" value="<?php echo $COD_UNIVEND; ?>"> -->




<!-- ##################################################fim bloco novo ######################################## -->
						
						
						
	<div class="push100"></div>						
						
						
						
						
			</div>
			<!-- fim Portlet -->
		</div>
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
	
	<script type="text/javascript">
		$(document).ready(function(){
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario2').validator();

			buscaVendedor();

			if('<?=$log_trocaprod?>' == 'N'){
				$('#COD_UNIVEND').attr('disabled','disabled');
				$('#COD_PRODUTO').attr('disabled','disabled');
				$('#VAL_RESGATE').attr('disabled','disabled');
				$('#QTD_PRODUTO').attr('disabled','disabled');
			}
			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
			  
			  if ($('#REFRESH_PRODUTOS').val() == "S"){
				//alert("atualiza");
				RefreshProdutos(<?php echo $cod_empresa; ?>,<?php echo $cod_resgate; ?>,"VAL",0);
				$('#REFRESH_PRODUTOS').val("N");				
			  }	
			  
			 //  if ($('#REFRESH_CLIENTE').val() == "S"){
				// var newCli = $('#NOVO_CLIENTE').val();  
				// window.location.href = "action.php?mod=<?php echo fnEncode(1067); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC="+newCli+" ";
				// $('#REFRESH_PRODUTOS').val("N");				
			 //  }	
			  
			});		

			$("#VAL_RESGATE").change(function() {
				if($('#VAL_TOTPROD').val() != $(this).val() && $("#COD_PRODUTO").val().trim() != 0){
					$.alert({
						title: 'Atenção',
						content: 'Valor do resgate deve ser igual ao valor total!',
					});			
					$(this).val('');
				}
			});			
		});

		function RefreshProdutos(idEmp, idResg, tipo, idItem) {
			$.ajax({
				type: "GET",
				url: "ajxListaResgateMultiplo.php",
				data: { ajx1:idEmp, ajx2:idResg, ajx3:tipo, ajx4:idItem},
				beforeSend:function(){
					$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_Produtos").html(data);
				},
				error:function(){
					$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});	
		}

		
		function calcularTotal(valor){
			var casasDecimais = "<?=$casasDec?>";
			var val_saldo = "<?=fnValor($credito_disponivel,$casasDec)?>";
			if(casasDecimais == 0){
				val_saldo = val_saldo.replace('.','');
			}
			var sal_disponivel = val_saldo - valor;

			console.log(val_saldo);
			console.log(valor);

				
			if(casasDecimais == 0){
				$('#VAL_RESGATE').val(valor);
				$('#total_da_venda').text(valor);
				$('#total_de_produtos').text(Math.floor(sal_disponivel).toFixed(casasDecimais));
				// alert("casasDecimais=0: "+$('#VAL_RESGATE').val());
			}else{
				$('#VAL_RESGATE').unmask();
				$('#VAL_RESGATE').val(valor.toFixed(casasDecimais));
				$('#total_da_venda').text(valor.toFixed(casasDecimais));
				$('#total_de_produtos').text(sal_disponivel.toFixed(casasDecimais));
				//alert("casasDecimais !=0: "+$('#VAL_RESGATE').val());	
			}

			//$('#VAL_RESGATE').mask("#.##0,00", {reverse: true});				
		}

		function abreModalProduto(dataUrl, dataTitle){

			let cod_univend = $("#COD_UNIVEND").val();

			if(cod_univend && cod_univend != ""){
				var popLink = dataUrl+"&idu="+cod_univend;
				var popTitle = dataTitle;
				// alert(popLink);	
				setIframe(popLink, popTitle);
				$('.modal').not('#popModalNotifica').appendTo("body").modal('show');
			}else{
				$.alert({
					title: 'Atenção',
					content: 'A unidade de atendimento não pode ser vazia.',
				});
			}

		}

		function buscaVendedor(){
			$.ajax({
				type: "GET",
				url: "ajxPdvVirtual.do",
				data: {opcao: "vendedores", cod_univend: $('#COD_UNIVEND').val(), cod_empresa: <?php echo $cod_empresa; ?>},
				beforeSend:function(){
					$('#loadPage').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$('#loadPage').html("");
					$('#COD_USUARIO').html(data);
					 $('#COD_USUARIO').trigger("chosen:updated");
					 console.log(data);
				},
				error:function(){
				}
			});
		}
	
	</script>	
