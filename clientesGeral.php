<?php

/*function fnConsoleLog($var){
	?>
	<script>
		var variavel = ('<?=$var?>').replace(/(\r\n\t|\n|\r\t)/gm,"");
		if(variavel == ''){
			variavel = '__';
		}
		console.log(variavel);
	</script>
	<?php 
}
	*/
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
		
	//inicialização das variáveis
	@$cod_multemp = "0";
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
	}
	
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
			
			$cod_usuario = fnLimpacampoZero($_REQUEST['COD_USUARIO']);
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$nom_usuario = fnLimpacampo($_REQUEST['NOM_USUARIO']);
			$des_senhaus = fnEncode(fnLimpacampo($_REQUEST['DES_SENHAUS']));
			$log_usuario = fnLimpacampo($_REQUEST['LOG_USUARIO']);
			$des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);
			if (empty($_REQUEST['LOG_ESTATUS'])) {$log_estatus='N';}else{$log_estatus=$_REQUEST['LOG_ESTATUS'];}
			$num_rgpesso = fnLimpacampo($_REQUEST['NUM_RGPESSO']);
			$dat_nascime = fnLimpacampo($_REQUEST['DAT_NASCIME']);
			$cod_estaciv = fnLimpaCampoZero($_REQUEST['COD_ESTACIV']);
			$cod_sexopes = fnLimpacampoZero($_REQUEST['COD_SEXOPES']);
			$num_tentati = fnLimpacampoZero($_REQUEST['NUM_TENTATI']);
			$num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
			$num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
			$num_comercial = fnLimpacampo($_REQUEST['NUM_COMERCIAL']);
			$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
			$num_cartao = fnLimpacampoZero($_REQUEST['NUM_CARTAO']);
			$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
			if($num_cartao == 0 || $num_cartao == ""){ $num_cartao = fnLimpacampo($_REQUEST['NUM_CGCECPF']); }
			$des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
			$num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
			$des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
			$des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
			$num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
			$nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
			$cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);
			$cod_tpcliente = fnLimpacampoZero($_REQUEST['COD_TPCLIENTE']);
			
			//array dos sistemas da empresas
			if (isset($_POST['COD_PERFILS'])){
				$Arr_COD_PERFILS = $_POST['COD_PERFILS'];
				//print_r($Arr_COD_SISTEMAS);			 
			 
			   for ($i=0;$i<count($Arr_COD_PERFILS);$i++) 
			   { 
				$cod_perfils = $cod_perfils.$Arr_COD_PERFILS[$i].",";
			   } 
			   
			   $cod_perfils = substr($cod_perfils,0,-1);
				
			}else{$cod_perfils = "0";}
			
			
			//array das empresas multiacesso
			if (isset($_POST['COD_MULTEMP'])){
				$Arr_COD_MULTEMP = $_POST['COD_MULTEMP'];
				//print_r($Arr_COD_MULTEMP);			 
			 
			   for ($i=0;$i<count($Arr_COD_MULTEMP);$i++) 
			   { 
				$cod_multemp = $cod_multemp.$Arr_COD_MULTEMP[$i].",";
			   } 
			   
			   $cod_multemp = substr($cod_multemp,0,-1);
				
			}else{$cod_multemp = "0";}
			

			//fnEscreve($cod_perfils);
			
			$des_apelido = fnLimpacampo($_REQUEST['DES_APELIDO']);
			$cod_profiss = fnLimpacampoZero($_REQUEST['COD_PROFISS']);
			$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
			$des_contato = fnLimpacampo($_REQUEST['DES_CONTATO']);
			if (empty($_REQUEST['LOG_EMAIL'])) {$log_email='N';}else{$log_email=$_REQUEST['LOG_EMAIL'];}
			if (empty($_REQUEST['LOG_SMS'])) {$log_sms='N';}else{$log_sms=$_REQUEST['LOG_SMS'];}
			if (empty($_REQUEST['LOG_TELEMARK'])) {$log_telemark='N';}else{$log_telemark=$_REQUEST['LOG_TELEMARK'];}
			if (empty($_REQUEST['LOG_FUNCIONA'])) {$log_funciona='N';}else{$log_funciona=$_REQUEST['LOG_FUNCIONA'];}
			$nom_pai = fnLimpacampo($_REQUEST['NOM_PAI']);
			$nom_mae = fnLimpacampo($_REQUEST['NOM_MAE']);
			$cod_chaveco = fnLimpacampo($_REQUEST['COD_CHAVECO']);
			$key_externo = fnLimpacampo($_REQUEST['KEY_EXTERNO']);
			$tip_cliente = fnLimpacampo($_REQUEST['TIP_CLIENTE']);
			//fnEscreve($cod_chaveco);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$cod_usucada = 1;
						
			if ($opcao != ''){
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
	
						//verifica 
						switch ($cod_chaveco) {
							
							case 1: //cpf
								$num_cartao = fnLimpaDoc($num_cgcecpf);
								break;    
							case 2: //cartao pre cadastrado
								//$num_cartao = "active";
								$num_cartao = $num_cartao;
								break;    
							case 3: //telefone
								$num_cartao =  fnLimpaDoc($num_celular);
								break;
							case 4: //código externo
								$num_cartao = $num_cartao;
								break;    
							case 5: //cartao + cpf
								$num_cartao = $num_cartao;
								break;    
						}
						
						if (strlen(fnLimpaDoc($num_cgcecpf))=='11'  ){
							$tip_cliente = "F";	
						}
						
						//RICARDO APOS AQUI - VAI TER TODAS AS CRÍTICIAS SE FOR TIPO COM CARTAO  
						//$cod_chaveco = 2 ou 5
						
						$sql1 = "CALL SP_ALTERA_CLIENTES(
							'".$cod_usuario."',
							'".$cod_empresa."',
							'".$nom_usuario."',
							'".$des_senhaus."',
							'".$log_usuario."',
							'".$des_emailus."',
							'".$_SESSION["SYS_COD_USUARIO"]."',    
							'".fnLimpaDoc($num_cgcecpf)."',
							'".$log_estatus."',
							'".$num_rgpesso."',
							'".$dat_nascime."',
							'".$cod_estaciv."',
							'".$cod_sexopes."',
							'".$num_telefon."',
							'".$num_celular."',
							'".$num_comercial."',
							'".$cod_externo."',
							'".fnLimpaDoc($num_cartao)."',
							'".$num_tentati."',
							'".$des_enderec."',
							'".$num_enderec."',
							'".$des_complem."',
							'".$des_bairroc."',
							'".$num_cepozof."',
							'".$nom_cidadec."',
							'".$cod_estadof."',
							'".$des_apelido."',
							'".$cod_profiss."',
							".$cod_univend.",
							'".$tip_cliente."',
							'".$des_contato."',
							'".$log_email."',
							'".$log_sms."',
							'".$log_telemark."',
							'".$nom_pai."',
							'".$nom_mae."',
							'".$cod_chaveco."',
							'".$cod_multemp."',
							'".$key_externo."',
							'".$cod_tpcliente."',
							'".$log_funciona."',
							'".$opcao."'   
						);";
					
					//fnEscreve($sql1);

					if($num_cgcecpf != "" && $num_cgcecpf != 0){
					
						$execCliente = mysqli_query(connTemp($cod_empresa,''),$sql1);
						$qrGravaCliente = mysqli_fetch_assoc($execCliente);
						$cod_clienteRetorno = $qrGravaCliente['COD_CLIENTE'];					
						$mensagem = $qrGravaCliente['MENSAGEM'];
						$msgTipo = 'alert-success';
					}else{

						$cod_clienteRetorno = 0;					
						$mensagem = "Cliente avulso não pode ser alterado!";
						$msgTipo = 'alert-danger';
					}
					
					//fnEscreve($cod_clienteRetorno);
					//fnEscreve($mensagem);
					if ($mensagem == "Este cliente já existe !"){

						$msgRetorno = $mensagem;
						$msgTipo = 'alert-danger';

					}
					else if($mensagem == "Novo cliente cadastrado com <strong> sucesso! </strong>"){
						$cod_empresa = fnEncode($cod_empresa);
						$cod_cliente = fnEncode($cod_clienteRetorno);
						?>
							<script>
								window.location.replace("action.php?mod=PvUR9sokXEM¢&id=<?=$cod_empresa?>&idC=<?=$cod_cliente?>"); 
							</script>
						<?php 
					}
					else {

						$msgRetorno = $mensagem;
					}
										
					break;
						
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
						$msgTipo = 'alert-success';
						
						$sql2 = "CALL SP_ALTERA_CLIENTES(
							'".$cod_usuario."',
							'".$cod_empresa."',
							'".$nom_usuario."',
							'".$des_senhaus."',
							'".$log_usuario."',
							'".$des_emailus."',
							'".$_SESSION["SYS_COD_USUARIO"]."',    
							'".fnLimpaDoc($num_cgcecpf)."',
							'".$log_estatus."',
							'".$num_rgpesso."',
							'".$dat_nascime."',
							'".$cod_estaciv."',
							'".$cod_sexopes."',
							'".$num_telefon."',
							'".$num_celular."',
							'".$num_comercial."',
							'".$cod_externo."',
							'".$num_cartao."',
							'".$num_tentati."',
							'".$des_enderec."',
							'".$num_enderec."',
							'".$des_complem."',
							'".$des_bairroc."',
							'".$num_cepozof."',
							'".$nom_cidadec."',
							'".$cod_estadof."',
							'".$des_apelido."',
							'".$cod_profiss."',
							".$cod_univend.",
							'".$tip_cliente."',
							'".$des_contato."',
							'".$log_email."',
							'".$log_sms."',
							'".$log_telemark."',
							'".$nom_pai."',
							'".$nom_mae."',
							'".$cod_chaveco."',
							'".$cod_multemp."',
							'".$key_externo."',
							'".$cod_tpcliente."',
							'".$log_funciona."',
							'".$opcao."'   
								
						);";

					//echo $sql2;
					if($num_cgcecpf != "" && $num_cgcecpf != 0){
			  
						mysqli_query(connTemp($cod_empresa,''),$sql2);
					}else{
						$msgRetorno = "Cliente avulso não pode ser alterado!";
						$msgTipo = 'alert-danger';
					}
					
                    break;
					
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";	
						$msgTipo = 'alert-success';
                                       
					break;
				}			
				
			}  
			
			$newDate = explode('/', $dat_nascime);
			$dia = $newDate[0];
			$mes   = $newDate[1];
			$ano  = $newDate[2];	

			$sql = "UPDATE CLIENTES SET DIA = $dia, MES = $mes, ANO = $ano WHERE NUM_CGCECPF = ". fnLimpaDoc($num_cgcecpf);
			//fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa,''),$sql);

		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		
		$cod_empresa = fnDecode($_GET['id']);
		if (empty($cod_clienteRetorno)){
			//fnEscreve("if");
			if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
				//fnEscreve("if1");
				$cod_cliente = fnDecode($_GET['idC']);
				//fnEscreve($cod_cliente);		
			} else {
				//fnEscreve("if2");
				$cod_cliente = 0;			
			}
		}else {
			//fnEscreve("else");
			$cod_cliente = $cod_clienteRetorno;
		}	
		
		$sql="SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
			  FROM empresas WHERE COD_EMPRESA=$cod_empresa";
			  
		//fnEscreve($sql);		
		$qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sql)));
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_chaveco = $qrBuscaEmpresa['COD_CHAVECO'];
		$log_categoria = $qrBuscaEmpresa['LOG_CATEGORIA'];
		$log_autocad = $qrBuscaEmpresa['LOG_AUTOCAD'];
				
		$sql2="SELECT B.NOM_FAIXACAT,A.* 
				FROM clientes A
				left join categoria_cliente B ON B.COD_CATEGORIA=A.COD_CATEGORIA
				WHERE A.COD_CLIENTE = $cod_cliente and 
				A.COD_EMPRESA = $cod_empresa";
				
		$qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql2));		
		//fnEscreve($sql2);	
		  
		if (isset($qrBuscaCliente)){
			
			if($cod_cliente !=0){ 
				$cod_usuario = $qrBuscaCliente['COD_CLIENTE'];
				$cod_externo = $qrBuscaCliente['COD_EXTERNO'];
				$nom_usuario = $qrBuscaCliente['NOM_CLIENTE'];
				$num_cartao =  $qrBuscaCliente['NUM_CARTAO'];
				$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
				$num_rgpesso = $qrBuscaCliente['NUM_RGPESSO'];
				$dat_nascime = $qrBuscaCliente['DAT_NASCIME'];
				$cod_estaciv = $qrBuscaCliente['COD_ESTACIV'];
				$cod_sexopes = $qrBuscaCliente['COD_SEXOPES'];
				$des_emailus = $qrBuscaCliente['DES_EMAILUS'];
				$num_telefon = $qrBuscaCliente['NUM_TELEFON'];
				$num_celular = $qrBuscaCliente['NUM_CELULAR'];
				$num_comercial = $qrBuscaCliente['NUM_COMERCI'];
				$des_enderec = $qrBuscaCliente['DES_ENDEREC'];
				$num_enderec = $qrBuscaCliente['NUM_ENDEREC'];
				$des_complem = $qrBuscaCliente['DES_COMPLEM'];
				$des_bairroc = $qrBuscaCliente['DES_BAIRROC'];
				$num_cepozof = $qrBuscaCliente['NUM_CEPOZOF'];
				$nom_cidadec = $qrBuscaCliente['NOM_CIDADEC'];
				$cod_estadof = $qrBuscaCliente['COD_ESTADOF'];
				$dat_cadastr = fnFormatDateTime($qrBuscaCliente['DAT_CADASTR']);
				$log_usuario = $qrBuscaCliente['LOG_USUARIO'];
				if ($qrBuscaCliente['LOG_ESTATUS']=='S') {$check_ativo='checked';}else{$check_ativo='';}
				$des_senhaus = fnDecode($qrBuscaCliente['DES_SENHAUS']);
				$num_tentati = $qrBuscaCliente['NUM_TENTATI'];                                
				$des_apelido = $qrBuscaCliente['DES_APELIDO'];
				$cod_profiss = $qrBuscaCliente['COD_PROFISS'];
				$cod_univend = $qrBuscaCliente['COD_UNIVEND'];
				$cod_tpcliente = $qrBuscaCliente['COD_TPCLIENTE'];
				$tip_cliente = $qrBuscaCliente['TIP_CLIENTE'];
				$des_contato = $qrBuscaCliente['DES_CONTATO'];
				if ($qrBuscaCliente['LOG_FUNCIONA']=='S') {$check_funciona='checked';}else{$check_funciona='';}
				if ($qrBuscaCliente['LOG_EMAIL']=='S') {$check_mail='checked';}else{$check_mail='';}
				if ($qrBuscaCliente['LOG_SMS']=='S') {$check_sms='checked';}else{$check_sms='';}
				if ($qrBuscaCliente['LOG_TELEMARK']=='S') {$check_telemark='checked';}else{$check_telemark='';}
				$nom_pai = $qrBuscaCliente['NOM_PAI'];
				$nom_mae = $qrBuscaCliente['NOM_MAE'];
				$cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];
				$cod_multemp = $qrBuscaCliente['COD_MULTEMP'];
				if (empty($cod_multemp)){$cod_multemp = "0";}
				$key_externo = $qrBuscaCliente['KEY_EXTERNO'];
				$cod_categoria = $qrBuscaCliente['COD_CATEGORIA'];
				$nom_faixacat = $qrBuscaCliente['NOM_FAIXACAT'];
				$cod_indicad = $qrBuscaCliente['COD_INDICAD'];
				$dat_indicad = $qrBuscaCliente['DAT_INDICAD'];
								
			} 
			else{
				@$cod_usuario = 0;
				@$nom_usuario = '';
				@$cod_externo = '';
				@$num_cartao = '';
				@$num_cgcecpf = '';
				@$num_rgpesso = '';
				@$dat_nascime = '';
				@$cod_estaciv = 0;
				@$cod_sexopes = 0;
				@$des_emailus = '';
				@$num_telefon = '';
				@$num_celular = '';
				@$num_comercial = '';
				@$des_enderec = '';
				@$num_enderec = '';
				@$des_complem = '';
				@$des_bairroc = '';
				@$num_cepozof = '';
				@$nom_cidadec = '';
				@$cod_estadof = 0;
				@$dat_cadastr = '';
				@$log_usuario = '';
				@$des_senhaus = '';
				@$num_tentati = '';
				@$des_apelido = '';
				@$cod_profiss = '';
				@$cod_univend = '';
				@$des_contato = '';
				@$log_email = '';
				@$log_sms = '';
				@$log_telemark = '';
				@$nom_pai = '';
				@$nom_mae = '';
				@$check_ativo = 'checked';
				@$check_funciona='';
				@$check_mail='';
				@$check_sms='';
				@$check_telemark='';
				@$cod_entidad = 0;
				@$cod_multemp = "0";
				@$key_externo = "";
				@$cod_tpcliente = "";
				@$cod_tpcliente = "";
				@$check_funciona='';
				@$cod_indicad=0;
				@$dat_indicad='';
			}
			
		}
												
	}else {
		@$cod_empresa = 0;		
		@$nom_empresa = '';
		@$cod_externo = '';
		@$cod_usuario = 0;
		@$nom_usuario = '';
		@$num_cartao = '';
	    @$num_cgcecpf = '';
		@$num_rgpesso = '';
		@$dat_nascime = '';
		@$cod_estaciv = 0;
		@$cod_sexopes = 0;
		@$des_emailus = '';
		@$num_telefon = '';
		@$num_celular = '';
		@$num_comercial = '';
		@$des_enderec = '';
		@$num_enderec = '';
		@$des_complem = '';
		@$des_bairroc = '';
		@$num_cepozof = '';
		@$nom_cidadec = '';
		@$cod_estadof = 0;
		@$dat_cadastr = '';
		@$log_usuario = '';
		@$des_senhaus = '';
		@$num_tentati = '';
		@$des_apelido = '';
		@$cod_profiss = '';
		@$cod_univend = '';
		@$des_contato = '';
		@$log_email = '';
		@$log_sms = '';
		@$log_telemark = '';
		@$nom_pai = '';
		@$nom_mae = '';	
		@$cod_chaveco = 0;
		@$cod_entidad = 0;
		@$cod_multemp = "0";
		@$key_externo = "";
		@$cod_tpcliente = "";
		@$check_ativo = 'checked';
		@$check_funciona='';
		@$cod_indicad=0;
		@$dat_indicad='';
		
	}

	if($cod_indicad !=0 ){
		$sql = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $cod_indicad";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		$qrIndicad = mysqli_fetch_assoc($arrayQuery);
		$nom_indicad = $qrIndicad['NOM_CLIENTE'];
	}
	
	//fnEscreve(fnEncode($des_senhaus));
	//fnEscreve($des_senhaus);
	//fnEscreve($des_emailus);
	//fnMostraForm();
	//fnConsoleLog($cod_cliente);
      
?>

<style>

.alert .alert-link {
    text-decoration: none;
}
.alert:hover .alert-link:hover {
    text-decoration: underline;
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
								
								<?php
								if ($popUp != "true"){								
									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 4: //fidelidade
											$formBack = "1102";
											break;
										case 14: //rede duque
											$formBack = "1102";
											break;
										default;											
											$formBack = "1015";
											break;
									}
								}
								?>	
									
								<div class="portlet-body">
			
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>

									<?php 
									//verifica se tem bloqueio
									$sql4="SELECT COUNT(*) as TEM_BLOQUEIO
											FROM CLIENTES A, VENDAS B
											LEFT JOIN $connAdm->DB.unidadevenda d ON d.cod_univend = b.cod_univend 
											WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
											B.COD_STATUSCRED=3 AND 
											A.COD_EMPRESA = $cod_empresa and
											A.COD_CLIENTE = $cod_cliente ";
											$qrBuscaBloqueio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql4));		
											//fnEscreve($sql4);
									  
											$tem_bloqueio = $qrBuscaBloqueio['TEM_BLOQUEIO'];
									
									if ($tem_bloqueio > 0) { ?>
									
									<div class="alert alert-warning alert-dismissible" role="alert">
									  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
									  Cliente possui vendas bloqueadas. <br/> 
									  <a href="action.do?mod=<?php echo fnEncode(1099); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
									</div>
									<?php } ?>
									
									<?php
									if ($popUp != "true"){ 
										//menu superior - cliente
										$abaEmpresa = 1020;	
										$abaCli = 1024;									
										switch ($_SESSION["SYS_COD_SISTEMA"]) {
											case 14: //rede duque
												include "abasClienteDuque.php";
												break;
											default;											
												include "abasClienteConfig.php";
												break;
										}
									}									
									?>
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=<?php echo fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">
																	
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
									
													<?php 
													if ($_SESSION["SYS_COD_SISTEMA"] == 14) {
														
														$sql3="select NOM_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
														$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql3));		
														//fnEscreve($sql3);	
														$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
													?>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Empresa Associada</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_ENTIDAD" id="NOM_ENTIDAD" value="<?php echo $nom_entidad; ?>" maxlength="50" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="push10"></div> 
													
													<?php	
													}									
													?>				
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
                                                            <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario;?>">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
														</div>														
													</div>
																		
													<div class="col-md-5">
														<label for="inputName" class="control-label required">Nome do Usuário</label>
														<div class="input-group">
														<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
														</span>
														<input type="text" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $nom_usuario; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
														</div>
														<div class="help-block with-errors"></div>														
													</div>
													
													<?php
													switch ($_SESSION["SYS_COD_SISTEMA"]) {
														case 14: //rede duque
															$cartaoObg = "";
															break;
														default;											
															$cartaoObg = "required";
															break;
													}
													?>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label <?php echo $cartaoObg;?> ">Número do Cartão</label>
															<?php if ($cod_cliente == 0) { ?>
															
																<?php if ($log_autocad == "N") { 
																	if($cod_chaveco == 2 || $cod_chaveco == 5){
																	?>
																		<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="" maxlength="50" data-error="Campo obrigatório" required>
																	<?php }else{ ?>

																<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO_VAZIO" id="NUM_CARTAO_VAZIO" value="" maxlength="50" readonly="readonly" data-error="Campo obrigatório" >
																<input type="hidden" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="">

																<?php
																	}
																} else { ?>

																<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="" maxlength="50" data-error="Campo obrigatório" required>

																<?php } ?>
															
															<?php } else { ?>

                                                            <input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao;?>" maxlength="50" readonly="readonly" data-error="Campo obrigatório" <?php echo $cartaoObg;?> >
                                                            
															<?php } ?>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Apelido</label>
                                                            <input type="text" class="form-control input-sm" name="DES_APELIDO" id="DES_APELIDO" value="<?php echo $des_apelido;?>" maxlength="18" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">CNPJ/CPF</label>
                                                             <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnCompletaDoc($num_cgcecpf,'F');?>" maxlength="18" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">RG</label>
                                                            <input type="text" class="form-control input-sm" name="NUM_RGPESSO" id="NUM_RGPESSO" value="<?php echo $num_rgpesso;?>" maxlength="15" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>					
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data de Nascimento</label>
                                                            <input type="text" class="form-control input-sm data" name="DAT_NASCIME" value="<?php echo $dat_nascime;?> "id="DAT_NASCIME" maxlength="10" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Estado Civil</label>
																<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect">
																	<option value=""></option>					
																	<?php																	
																		$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			echo"
																				  <option value='".$qrListaEstCivil['COD_ESTACIV']."'>".$qrListaEstCivil['DES_ESTACIV']."</option> 
																				"; 
																			  }											
																	?>	
																</select>	
                                                                <script>$("#formulario #COD_ESTACIV").val("<?php echo $cod_estaciv; ?>").trigger("chosen:updated"); </script>
    															<div class="help-block with-errors"></div>
														</div>
													</div>													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Sexo</label>
																<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>					
																	<?php 																	
																		$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaSexo['COD_SEXOPES']."'>".$qrListaSexo['DES_SEXOPES']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
                                                                <script>$("#formulario #COD_SEXOPES").val("<?php echo $cod_sexopes; ?>").trigger("chosen:updated"); </script>
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<div class="row">

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo do Cliente </label>
																<select data-placeholder="Selecione o tipo do cliente" name="COD_TPCLIENTE" id="COD_TPCLIENTE" class="chosen-select-deselect">
																	<option value=""></option>					
																	<?php 																	
																		$sql = "select * from tipo_cliente where COD_EMPRESA = $cod_empresa order by DES_TIPOCLI ";
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
																	
																		while ($qrListaTipoCli = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaTipoCli['COD_TIPOCLI']."'>".$qrListaTipoCli['DES_TIPOCLI']."</option> 
																				"; 
																			  }											
																	?>	
																</select>	
                                                                <script>$("#formulario #COD_TPCLIENTE").val("<?php echo $cod_tpcliente; ?>").trigger("chosen:updated"); </script>                                                       
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Profissão </label>
																<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect">
																	<option value=""></option>					
																	<?php 																	
																		$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																	
																		while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaProfi['COD_PROFISS']."'>".$qrListaProfi['DES_PROFISS']."</option> 
																				"; 
																			  }											
																	?>	
																</select>	
                                                                <script>$("#formulario #COD_PROFISS").val("<?php echo $cod_profiss; ?>").trigger("chosen:updated"); </script>                                                       
															<div class="help-block with-errors"></div>
														</div>
													</div>	

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Pai</label>
															<input type="text" class="form-control input-sm" name="NOM_PAI" id="NOM_PAI" value="<?php echo $nom_pai ?>" maxlength="60" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome da Mãe</label>
															<input type="text" class="form-control input-sm" name="NOM_MAE" id="NOM_MAE" value="<?php echo $nom_mae ?>" maxlength="60" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<div class="row">

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Inscrição Estadual</label>
															<input type="text" class="form-control input-sm" name="NUM_ESCRICA" id="NUM_ESCRICA" maxlength="20" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-9">
														<div class="form-group">
															<label for="inputName" class="control-label">Tags</label>
																<select data-placeholder="Selecione as tags" name="COD_TAG[]" id="COD_TAG" multiple="multiple" class="chosen-select-deselect">
																	<?php 																	
																		$sql = "SELECT * FROM TAG_CLIENTES WHERE COD_EMPRESA = $cod_empresa";
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																	
																		while ($qrListaTag = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaTag['COD_TAG']."'>".$qrListaTag['DES_TAG']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
                                                                <option data-img-src="fonts/font-awesome-5.1.0/svgs/solid/plus.svg" value="modal">ADICIONAR NOVO</option>
															<div class="help-block with-errors"></div>
														</div>
													</div>	

												</div>
												
										</fieldset>	
										
										<div class="push10"></div>
											
										<fieldset>
											<legend>Comunicação</legend> 
												
												<div class="row">

													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">e-Mail</label>
                                                            <input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" value="<?php echo $des_emailus;?>" maxlength="100" value="" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Contato</label>
                                                            <input type="text" class="form-control input-sm" name="DES_CONTATO" value="<?php echo $des_contato;?>" id="DES_CONTATO" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Principal</label>
                                                            <input type="text" class="form-control input-sm fone" name="NUM_TELEFON" value="<?php fnCorrigeTelefone($num_telefon); ?>" id="NUM_TELEFON" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
														
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Celular</label>
                                                            <input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?php fnCorrigeTelefone($num_celular); ?>" id="NUM_CELULAR" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Comercial</label>
                                                            <input type="text" class="form-control input-sm fone" name="NUM_COMERCIAL" value="<?php fnCorrigeTelefone($num_comercial); ?>" id="NUM_COMERCIAL" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
											
										</fieldset>
										
										<div class="push10"></div>
											
										<fieldset>
											<legend>Localização</legend> 
												
												<div class="row">									

																	
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Endereço</label>
                                                            <input type="text" class="form-control input-sm" name="DES_ENDEREC" value="<?php echo $des_enderec;?>"id="DES_ENDEREC" maxlength="40">
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Número</label>
                                                            <input type="text" class="form-control input-sm" name="NUM_ENDEREC" value="<?php echo $num_enderec; ?>" id="NUM_ENDEREC" maxlength="10">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Complemento</label>
                                                            <input type="text" class="form-control input-sm" name="DES_COMPLEM" value="<?php echo $des_complem;?>" id="DES_COMPLEM" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Bairro</label>
                                                            <input type="text" class="form-control input-sm" name="DES_BAIRROC" value="<?php echo $des_bairroc;?>" id="DES_BAIRROC" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<div class="row">	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">CEP</label>
                                                            <input type="text" class="form-control input-sm cep" name="NUM_CEPOZOF" value="<?php echo $num_cepozof;?>" id="NUM_CEPOZOF" maxlength="9">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cidade</label>
                                                            <input type="text" class="form-control input-sm" name="NOM_CIDADEC" value="<?php echo $nom_cidadec; ?>" id="NOM_CIDADEC" maxlength="40">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Estado</label>
																<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
																	<option value=""></option>					
																	<option value="AC">AC</option> 
																	<option value="AL">AL</option> 
																	<option value="AM">AM</option> 
																	<option value="AP">AP</option> 
																	<option value="BA">BA</option> 
																	<option value="CE">CE</option> 
																	<option value="DF">DF</option> 
																	<option value="ES">ES</option> 
																	<option value="GO">GO</option> 
																	<option value="MA">MA</option> 
																	<option value="MG">MG</option> 
																	<option value="MS">MS</option> 
																	<option value="MT">MT</option> 
																	<option value="PA">PA</option> 
																	<option value="PB">PB</option> 
																	<option value="PE">PE</option> 
																	<option value="PI">PI</option> 
																	<option value="PR">PR</option> 
																	<option value="RJ">RJ</option> 
																	<option value="RN">RN</option> 
																	<option value="RO">RO</option> 
																	<option value="RR">RR</option> 
																	<option value="RS">RS</option> 
																	<option value="SC">SC</option> 
																	<option value="SE">SE</option> 
																	<option value="SP">SP</option> 
																	<option value="TO">TO</option> 							
																</select>
                                                                <script>$("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated"); </script>
															<div class="help-block with-errors"></div>
														</div>
													</div>	

													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Latitude</label>
                                                            <input type="text" class="form-control input-sm leitura" readonly="readonly"  name="LATITUDE" id="LATITUDE" value="">
														</div>														
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Longitude</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LONGITUDE" id="LONGITUDE" value="">
														</div>														
													</div>
													
													
												</div>			
												
										</fieldset>	
									
										<?php 
										if ($_SESSION["SYS_COD_SISTEMA"] == 14) {
											
											$sql3="select NOM_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
											$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql3));		
											//fnEscreve($sql3);	
											$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
										?>
									
										<div class="push10"></div> 

										
										<fieldset>
											<legend>Veículos</legend> 
											
												<div class="row">

													<?php
													$sql = "select veiculos.DES_PLACA, MARCA.COD_MARCA, veiculos.COD_EXTERNO, MARCA.NOM_MARCA, modelo.NOM_MODELO from veiculos 
															left join MARCA on MARCA.COD_MARCA=veiculos.COD_MARCA
															left join modelo on modelo.COD_MODELO=veiculos.COD_MODELO
															where COD_CLIENTE = $cod_cliente ";	
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());	
													while ($qrListaVeiculo = mysqli_fetch_assoc($arrayQuery))
													  {
													?>
													
													<div class="col-md-3 text-center"> 
													<i class="fa fa-car fa-3x" aria-hidden="true"></i> 
													<div class="push10"></div>
													<small><b>Placa:</b></small> <?php echo $qrListaVeiculo['DES_PLACA']; ?>	<br/>												
													<small><b>Marca:</b></small> <?php echo $qrListaVeiculo['NOM_MARCA']; ?>	<br/>												
													<small><b>Modelo:</b></small> <?php echo $qrListaVeiculo['NOM_MODELO']; ?>	<br/>												
													</div>
													
													<?php	
													}	
													?>

													<div class="col-md-3"> 
													
													<div class="push10"></div>
													<?php
													$sql = "select b.DES_PRODUTO,a.VAL_PRODUTO from plano_valor a,produtocliente b
															where 
															a.cod_produto=b.cod_produto and
															a.cod_entidad = $cod_entidad group by a.COD_PRODUTO";	
													//fnEscreve($sql);
                                                                                                        //precisa ver o group by e corrigir o select 
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());	
													while ($qrListaPrecos = mysqli_fetch_assoc($arrayQuery))
													  {
													?>
													
													<small><b> <?php echo $qrListaPrecos['DES_PRODUTO']; ?>:</b></small> <?php echo $qrListaPrecos['VAL_PRODUTO']; ?>	<br/>												
													
													<?php	
													}	

													?>	
													</div>
													
												
												</div>	
												
										</fieldset>

										
										<?php	
										}									
										?>	
										
										<div class="push10"></div>
										
										<fieldset>
											<legend>Controle de Acesso</legend> 
											
												<div class="row">	
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Data de Cadastro</label>
                                                            <input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $dat_cadastr;?>" name="DAT_CADASTR" id="DAT_CADASTR">
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Externo</label>
                                                            <input type="text" class="form-control input-sm" name="COD_EXTERNO" value="<?php echo $cod_externo;?>" id="COD_EXTERNO" maxlength="20">
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Chave Externa</label>
                                                            <input type="text" class="form-control input-sm" name="KEY_EXTERNO" value="<?php echo $key_externo;?>" id="KEY_EXTERNO" maxlength="20">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Login Usuário</label>
                                                            <input type="text" class="form-control input-sm" name="LOG_USUARIO" id="LOG_USUARIO"value="<?php echo $log_usuario;?>" maxlength="50" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Senha</label>
                                                            <input type="password" class="form-control input-sm" name="DES_SENHAUS" id="DES_SENHAUS" maxlength="10" value="<?php echo $des_senhaus;?>" >
															<div class="help-block with-errors"></div>
														</div>
													</div>													

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">N° Acessos</label>
                                                            <input type="text" class="form-control input-sm" name="NUM_TENTATI" id="NUM_TENTATI" value="<?php echo $num_tentati;?>" maxlength="2" value="">
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="push10"></div>													
													
												</div>						
												
										</fieldset>	
										
										
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">

											<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											<?php if ($cod_cliente == 0) {?>	
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											<?php } else { ?>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											<?php } ?>

										</div>
										
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="TIP_CLIENTE" id="TIP_CLIENTE" value="<?php echo $tip_cliente; ?>">
										<input type="hidden" name="COD_CHAVECO" id="COD_CHAVECO" value="<?php echo $cod_chaveco; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
										
									<div class="push50"></div>									
									
									<div class="push"></div>
									
									</div>								
								
								</div>
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
						
					<div class="push20"></div>

	<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="js/plugins/chosenImage/chosenImage.jquery.js"></script> 
	
	<script type="text/javascript">

		$('#COD_STATUS').chosenImage();

		$('#COD_STATUS').change(function(){
			val = $('#COD_STATUS').val();
			if(val == "modal"){
				$("#COD_STATUS").val('').trigger("chosen:updated");
				$('.chosenImage-container .chosen-single span').css('background', 'none');
				$('#popModal').appendTo("body").modal('show');
			}
		});
	
		$(document).ready(function(){
			
			var SPMaskBehavior = function (val) {
			  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
			  onKeyPress: function(val, e, field, options) {
				  field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};			
			
			$('.sp_celphones').mask(SPMaskBehavior, spOptions);	
			
			//mascaraCpfCnpj($("#formulario #NUM_CGCECPF"));
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
			  
			  if ($('#REFRESH_CLIENTE').val() == "S"){
				var newCli = $('#NOVO_CLIENTE').val();  
				window.location.href = "action.php?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC="+newCli+" ";
				$('#REFRESH_PRODUTOS').val("N");				
			  }	
			  
			});	
			
		});		
			
		//retorno combo multiplo - master
		$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
		var sistemasMst = "<?php echo $cod_multemp; ?>";
		var sistemasMstArr = sistemasMst.split(',');				
		//opções multiplas
		for (var i = 0; i < sistemasMstArr.length; i++) {
		  $("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");				  
		}
		$("#formulario #COD_MULTEMP").trigger("chosen:updated");    
		
		
	</script>	
