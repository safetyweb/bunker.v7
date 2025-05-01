<?php include "_system/_functionsMain.php"; 
      include './totem/funWS/atualizacadastro.php'; 
      include './totem/funWS/inserirvenda.php'; 
      include './totem/funWS/buscaConsumidor.php';
      include './totem/funWS/buscaConsumidorCNPJ.php';
	  

//echo fnDebug('true');
//fnMostraForm();
@$opcao=$_GET['opcao'];
@$num_cartao=$_GET['c10'];
if($_GET['c1']==''){
	@$cpf=$_GET['c10'];
	$cartao = 'true';
} else {
	@$cpf=$_GET['c1'];
	$cartao = 'false';
}
$num_cartao = $cpf;
// fnEscreve($cartao);
@$cod_empresa=$_GET['COD_EMPRESA'];
@$cod_univend=$_REQUEST['COD_UNIVEND'];	
//busca dados do orçamento
$sql = "SELECT IFNULL(MAX(COD_ORCAMENTO), 0) + 1 as COD_ORCAMENTO FROM CONTADOR ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrBuscaOrcamento = mysqli_fetch_assoc($arrayQuery);
if (isset($qrBuscaOrcamento)){
	
	$cod_orcamento = $qrBuscaOrcamento['COD_ORCAMENTO'];
	//fnEscreve($cod_orcamento);
	
	//atualiza contador do orçamento
	$sql = "UPDATE CONTADOR SET COD_ORCAMENTO = '".$cod_orcamento."' WHERE COD_CONTADOR = 1 ";
	mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
}

$sql = "select LOG_USUARIO, DES_SENHAUS,COD_UNIVEND from usuarios where COD_EMPRESA = ".$cod_empresa." AND COD_TPUSUARIO=10 and DAT_EXCLUSA is null ";
//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
$log_usuario = $qrBuscaUsuario['LOG_USUARIO'];								
$des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];	
//consulta empresa
$sql = "SELECT COD_EMPRESA, NOM_FANTASI,LOG_CONSEXT, LOG_PDVMANU,TIP_CAMPANHA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
  
    if(strlen(fnLimpaDoc($cpf))<='11' || $cartao == 'true')
    {  $tp_cliente='F'; }else{$tp_cliente='J';}   	

        $arrayCampos=array('0'=>$log_usuario,
                           '1'=> fnDecode($des_senhaus),
                           '2'=>$cod_univend,
                           '3'=>$_SESSION["USU_COD_USUARIO"],
                           '4'=>$cod_empresa
                           );              
if($qrBuscaEmpresa['LOG_CONSEXT']=='S')
{  
    if(strlen(fnLimpaDoc($cpf))<='11' || $cartao == 'true')
    {  
    $tp_cliente='F';   
    $buscaconsumidor = fnconsulta(fnLimpaDoc($cpf), $arrayCampos);
    }
    else
    {
    $buscaconsumidor = fnconsultacnpf(fnLimpaDoc($cpf), $arrayCampos);
    $tp_cliente='J';

    }  
}else{
    $tp_cliente='F';   
    $buscaconsumidor = fnconsulta(fnLimpaDoc($cpf), $arrayCampos);
}

if($cartao == 'true'){
	$cpf = $buscaconsumidor['cpf'];
}

$dadosatualiza=Array(  'nome'=>$_REQUEST['c2'],
                       'sexo'=>$_REQUEST['sexo'],
                       'email'=>$_REQUEST['c3'],
                       'telefone'=>$_REQUEST['c4'],
                       'cpf'=>fnLimpaDoc($cpf),
                       'cartao'=>fnLimpaDoc($num_cartao),
                       'dt_nascimento'=>$_REQUEST['c6'],
                       'codatendente'=>$_REQUEST['c8'],
                       'tp_cliente'=>$tp_cliente,
                       'senha'=>$buscaconsumidor['senha'],
                       'canal'=>5,
                       'venda'=>'S'
                  );

 // echo '<pre>';
 //   print_r($dadosatualiza);
 //    print_r($buscaconsumidor);
 //    echo '</pre>';
if(fnLimpaDoc($cpf)=='72215889101')
{    
    echo '<pre>';
   print_r($dadosatualiza);
    // print_r($buscaconsumidor);
    echo '</pre>';
}
if($_REQUEST['sexo']!='')
{    
    $atualiza=atualizacadastro($dadosatualiza, $arrayCampos);
}    
//$urlTKT = geratkt($dadosatualiza,$arrayCampos);
// echo "<pre>";
// print_r($atualiza);	
// echo "</pre>";


//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE FROM CLIENTES where NUM_CGCECPF = '".fnLimpaDoc($cpf)."' AND COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaCliente)){
	
	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];

}else{
			
	$nom_cliente =  $buscaconsumidor['nome'];
	$cod_cliente = "";
	$num_cartao =$buscaconsumidor['cartao'];
	$num_cgcecpf = $buscaconsumidor['cpf'];
		
}

//busca dados url
//$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
//fnEscreve($sql);
//$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
//$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)){
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
}

if($atualiza == "Registro inserido!"){

	if(!isset($tipoAtiv)){
		$tipoAtiv = 6;
	}

	$sqlUpdtCanal = "UPDATE LOG_CANAL SET COD_TIPO = $tipoAtiv WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCanal);
}
											

//busca saldos de resgate
$sql = "CALL SP_CONSULTA_SALDO_CLIENTE('".$cod_cliente."') ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrBuscaSaldoResgate = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSaldoResgate)){		
	$credito_disponivel = $qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];		
	$total_credito = $qrBuscaSaldoResgate['TOTAL_CREDITO'];
}		
if($cpf==0){$disable='readOnly="readonly"';}else{$disable='';} 

	
	//fnEscreve($cod_cliente);
	//fnEscreve(fnLimpaDoc($_REQUEST['c1']));
	//fnEscreve($_SESSION["USU_COD_USUARIO"]);
	//fnEscreve($cod_orcamento);
	//fnEscreve($cod_empresa);
	//fnEscreve(fnValor($credito_disponivel,2));
	//fnEscreve($total_credito);
	//fnEscreve($_SESSION["USU_COD_USUARIO"]);
	//$credito_disponivel = 20;	
?>

<div class="row">

	<div class="col-md-6">
	<h4>Produtos da Venda</h4>
	<div class="push20"></div>
	
		<div class="col-lg-12 col-md-12 col-sm-1 col-xs-12">
			<div class="push20"></div>
			<a name="addProdutos" class="btn btn-success btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1070)?>&id=<?php echo fnEncode($cod_empresa); ?>&idO=<?php echo fnEncode($cod_orcamento);?>&pop=true" data-title="Busca Produtos / <?php echo $nom_empresa;?> / Venda Nº <?php echo $cod_orcamento;?>"><i class="fa fa-1x fa-search" aria-hidden="true"></i>&nbsp; ADICIONAR PRODUTOS</a>
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
			
				$sql = "select B.DES_PRODUTO,A.* from AUXVENDA A,PRODUTOCLIENTE B
						where 
						A.COD_PRODUTO=B.COD_PRODUTO AND
						A.COD_ORCAMENTO = '".$cod_orcamento."' order by A.COD_VENDA	";
				
				//fnEscreve($sql);
				
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$count = 0;
				$valorTotal = 0;
				
				while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;
					
					$valorTotalProd = $qrBuscaProdutos['QTD_PRODUTO'] * $qrBuscaProdutos['VAL_UNITARIO'];	
					
					$valorTotal = $valorTotal + $valorTotalProd; 
					
					echo"
						<tr>
						  <td class='text-center'><a href='javascript:void(0);' onclick='deleteProd(".$cod_orcamento.",".$qrBuscaProdutos['COD_VENDA'].")'><i class='fa fa-remove text-danger' aria-hidden='true'></i></a></td>
						  <td><small>".$qrBuscaProdutos['DES_PRODUTO']."</small></td>												
						  <td class='text-right'><small>".fnValor($qrBuscaProdutos['VAL_UNITARIO'],2)."</small></td>
						  <td class='text-center'><small>".fnValor($qrBuscaProdutos['QTD_PRODUTO'],2)."</small></td>
						  <td class='text-right'><small>".fnValor($valorTotalProd,2)."</small></td>
						</tr>
						<input type='hidden' id='COD_PRODUTO' value='".$qrBuscaProdutos['COD_PRODUTO']."'>
						"; 
					  }											

			?>
						
			</tbody>
			</table>	
		
		</div>									
		
	</div>			
	<div class="col-md-6">
	
	<h4>Detalhes da Venda <small>(Nº <?php echo $cod_orcamento; ?>)</small></h4>
	<div class="push20"></div>
			
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
		
			<div class="form-group">
				<label for="inputName" class="control-label">Cliente</label>
				<input type="text" class="form-control leituraOff" readOnly="readonly" name="NOM_CLIENTE" id="NOM_CLIENTE" tabindex="1" value="<?php echo $nom_cliente; ?>">
			</div>
				
		</div>											
			
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		
			<div class="form-group">
				<label for="inputName" class="control-label">Saldo Resgate</label>
				<input type="text" class="form-control text-center leituraOff calcula money" <?php echo $disable; ?> name="VAL_DISPONIVEL" id="VAL_DISPONIVEL" tabindex="1" value="<?php echo fnValor($credito_disponivel,2); ?>">
			</div>
				
		</div>											

		<div class="push10"></div>	
		
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<div class="form-group">
				<label for="inputName" class="control-label required">Nome do Vendedor </label>
					<select data-placeholder="Selecione a unidade de atendimento" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" required >
					<option value=""></option>					
					<?php
					
					$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS
							WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
							AND FIND_IN_SET($cod_univend, COD_UNIVEND)
							AND USUARIOS.DAT_EXCLUSA IS NULL 
							AND USUARIOS.COD_TPUSUARIO IN (2,7,11,8) 
							ORDER BY  USUARIOS.NOM_USUARIO ";
					
					//fnEscreve($sql);
					
					$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																
					while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery))
					  {
						//if ($qrListaUsuario['COD_USUARIO'] == $_SESSION["USU_COD_USUARIO"]){$checado = "selected";} else {$checado = " ";}
						echo"
							  <option value='".$qrListaUsuario['COD_USUARIO']."' >".ucfirst($qrListaUsuario['NOM_USUARIO']). "</option> 
							"; 
						  }	
					?>					
					</select>
					<?php // fnEscreve($sql); ?>
				<div class="help-block with-errors"></div>
			</div>
		</div>

		<?php
			if($_SESSION['SYS_COD_EMPRESA'] == 28 || $cod_empresa == 28){
		?>
				<input type="hidden" name="VAL_RESGATE" id="VAL_RESGATE" value="0,00">
		<?php
			}else{
		?>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<label for="inputName" class="control-label">Valor do Resgate </label>
		                <input type="text" class="form-control text-center calcula money" <?php echo $disable ?> name="VAL_RESGATE" id="VAL_RESGATE" tabindex="1" value="0,00" required >
					</div>
				</div>
		<?php
			}

			if($qrBuscaEmpresa['LOG_PDVMANU']=='1')			
		{
                      //pegar o prazo dos creditos
                $prazo="SELECT min(cr.QTD_VALIDAD) as QTD_VALIDAD FROM  CAMPANHARESGATE cr 
                        INNER JOIN campanha c ON c.COD_CAMPANHA=cr.COD_CAMPANHA
                        WHERE cr.cod_empresa=$cod_empresa AND c.LOG_REALTIME='S' AND c.tip_campanha='".$qrBuscaEmpresa['TIP_CAMPANHA']."'";
                    $rs_prazo=mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $prazo));                
                    $dias30 = fnFormatDate(date('Y-m-d', strtotime('+ '.$rs_prazo['QTD_VALIDAD'].' days')));
                    ?>
		
		<div class="push10"></div>	
		
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
		</div>

		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="form-group">
				<label for="inputName" class="control-label">Data de Validade</label>
				<div class="input-group date datePicker">
					<input type="text" class="form-control data" name="DAT_EXPIRA" id="DAT_EXPIRA" value="<?php echo $dias30; ?>">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
	
									
		<!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="form-group">
				<label for="inputName" class="control-label">Data de Validade</label>
				<input type="text" class="form-control data" name="DAT_EXPIRA" id="DAT_EXPIRA" value="<?php echo $dias30; ?>">
			</div>
		</div> -->
	
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="form-group">
				<label for="inputName" class="control-label">Valor do Crédito </label>
                <input type="text" class="form-control text-center calcula money" name="VAL_CREDITO" id="VAL_CREDITO" tabindex="1" value="0,00">
			</div>
		</div>
		
		<?php 
		}
		?>
		
		<div class="push10"></div>
		
		<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
			<div class="push15"></div>
			<a id="addDesconto" class="btn btn-primary btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1251)?>&id=<?php echo fnEncode($cod_empresa); ?>&pop=true" data-title="Desconto"><i class="fa fa-1x fa-search" aria-hidden="true"></i>&nbsp; Adicionar Desconto</a>
		</div>	

		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
			<div class="form-group">
				<label for="inputName" class="control-label">Desconto</label>
				<input type="text" class="form-control text-center calcula money leituraOff" name="VAL_DESCONTO" id="VAL_DESCONTO" readOnly="readonly" tabindex="1" value="0,00">
				<input type="hidden" class="form-control text-center" name="COD_OCORREN" id="COD_OCORREN" value="">
				<input type="hidden" class="form-control text-center" name="OBSERVACAO" id="OBSERVACAO" value="">
			</div>
		</div>	
		
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<div class="form-group">
				<label for="inputName" class="control-label required">Forma de Pagamento</label>
					<select data-placeholder="Selecione a forma de pagamento" name="COD_FORMAPA" id="COD_FORMAPA" class="chosen-select-deselect requiredChk" required >
					<option value=""></option>					
					<?php
					
					$sql = "select * from formapagamento where COD_EMPRESA = $cod_empresa";
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());					
					while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)) {
                                            
						echo"  <option value='".$qrListaUsuario['DES_FORMAPA']."'>".$qrListaUsuario['DES_FORMAPA']."</option>  "; 
					}	
					?>					
					</select>
				<div class="help-block with-errors"></div>
			</div>
		</div>		
		
		<div class="push10"></div>	
		
		<div class="push20"></div>	

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<a class="tile tile-info tile-valign">
				<div id="total_de_produtos"><?php echo fnValor($valorTotal,2); ?></div>
				<input type="hidden" name="total_de_produtos" class="total_de_produtos" value="<?php echo fnValor($valorTotal,2); ?>">
				<div class="informer informer-default">TOTAL DE PRODUTOS</div>
			</a>                            
		</div>

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<a class="tile tile-primary tile-valign">
				<div id="total_da_venda"><?php echo fnValor(($valorTotal),2); ?></div>
				<input type="hidden" name="total_da_venda" class="total_da_venda" value="<?php echo fnValor($valorTotal,2); ?>">
				<div class="informer informer-default">TOTAL DA VENDA</div>
			</a>                            
		</div>
		
		<div class="push20"></div>	

		<div class="col-md-12">
			<button type="button" name="FINALIZA" id="FINALIZA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-1x fa-cart-plus" aria-hidden="true"></i>&nbsp; Finalizar Compra </button>
									
			<div class="push10"></div> 
			
			<!--<a href="" name="HOME2" id="HOME2" class="btn btn-info btn-lg btn-block" tabindex="5"><i class="fa fa-1x fa-home" aria-hidden="true"></i>&nbsp; Voltar Menu Principal </a>-->
			<?php

				$modDestino = 1240;

				if($opcao != ""){
					$modDestino = 1758;
				}

			?>
			<a href="action.do?mod=<?php echo fnEncode($modDestino); ?>&id=<?php echo fnEncode($cod_empresa); ?>" name="HOME3" id="HOME3" class="btn btn-info btn-lg btn-block" tabindex="5"><i class="fa fa-1x fa-home" aria-hidden="true"></i>&nbsp; Voltar Menu Principal </a>
		
		</div>									
	
	</div>
	
</div><!-- /row -->
<input type="hidden" name="c1" id="c1" value="<?php echo $cpf; ?>" required >
<input type="hidden" name="c10" id="c10" value="<?php echo $cpf; ?>" required >
<input type="hidden" name="COD_LOJA" id="COD_LOJA" value="<?php echo $cod_univend; ?>">
<input type="hidden" name="COD_ORCAMENTO" id="COD_ORCAMENTO" value="<?php echo $cod_orcamento; ?>">

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
		  
		  <script>
		  
		$(document).ready(function(){

			$('.datePicker').datetimepicker({
			 format: 'DD/MM/YYYY',
			 minDate: moment()
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});	
				
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();	

			$("#COD_FORMAPA").chosen();
			$("#COD_FORMAPA").chosen({allow_single_deselect:true});					
			$("#COD_USUARIO").chosen();
			$("#COD_USUARIO").chosen({allow_single_deselect:true});			
			
		});		  
		  
			var mensagem = '<?php echo $print; ?>';
			
			if(mensagem != ""){
				$.alert({
					title: 'Atenção!',
					content: mensagem,
				});				
			}
			
			$('.modal').on('hidden.bs.modal', function () {
				if($('#VAL_DESCONTO').val() != ""){
					var valor = converterFloatValueToCalc($('#total_de_produtos').text()) - converterFloatValueToCalc($('#VAL_DESCONTO').val());
					$('#total_da_venda, .total_da_venda').unmask();
					$('#total_da_venda').text(valor.toFixed(2));
					$('.total_da_venda').val(valor.toFixed(2));					
					$('#total_da_venda, .total_da_venda').mask("#.##0,00", {reverse: true});
				}
			});			

			$("#addDesconto").click(function() {
				$('body').find('.modal-body').css({
					width:'auto', //probably not needed
					height:'auto', //probably not needed 
					'max-height':'60%'
				});
			});	

			$("#FINALIZA").click(function() {
				var val_resgate = converterFloatValueToCalc($("#VAL_RESGATE").val());
				var val_desconto = converterFloatValueToCalc($("#VAL_DESCONTO").val());


				if($("#total_de_produtos").text() == '0,00'){
					$.alert({
						title: 'Atenção!',
						content: 'Adicione produtos a venda',
					});
					return false;
				}				
				
				if($("#COD_USUARIO").val().trim() == ""){
					$.alert({
						title: 'Atenção!',
						content: 'Informe o vendedor',
					});
					return false;
				}	
				
				/*
				if(val_resgate > 0 && val_desconto > 0){
					$.alert({
						title: 'Atenção!',
						content: 'Não é possível ter valores de resgate e desconto simultâneos',
					});
					return false;
				}
				*/
				
				if($("#COD_FORMAPA").val().trim() == ""){
					$.alert({
						title: 'Atenção!',
						content: 'Informe a forma de pagamento',
					});
					return false;
				}	

				
			});
			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
				
			  if ($('#REFRESH_PRODUTOS').val() == "S"){
				  
				//alert('asas ' + $('#COD_ORCAMENTO').val());
				//alert('teste' + <?php echo $cod_orcamento; ?>);
				RefreshProdutos(<?php echo $cod_empresa; ?>, $('#COD_ORCAMENTO').val(),"VAL");
				$('#REFRESH_PRODUTOS').val("N");				
			  }				
			  
			});	
			
		  </script>

		  
		  