<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	$cod_desafio = 0;	
				
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

			$cod_desafio = fnLimpaCampoZero($_REQUEST['COD_DESAFIO']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_persona = $_REQUEST['COD_PERSONA'];
			$nom_desafio = fnLimpaCampo($_REQUEST['NOM_DESAFIO']);
			$des_desafio = fnLimpaCampo($_REQUEST['DES_DESAFIO']);
			$dat_ini = fnLimpaCampo($_REQUEST['DAT_INI']);
			$dat_fim = fnLimpaCampo($_REQUEST['DAT_FIM']);
			$des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
			$des_cor = fnLimpaCampo($_REQUEST['DES_COR']);
			$val_metades = fnLimpaCampo($_REQUEST['VAL_METADES']);

			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}

			if (empty($_REQUEST['LOG_EMAIL'])) {$log_email='N';}else{$log_email=$_REQUEST['LOG_EMAIL'];}
			if (empty($_REQUEST['LOG_SMS'])) {$log_sms='N';}else{$log_sms=$_REQUEST['LOG_SMS'];}
			if (empty($_REQUEST['LOG_WPP'])) {$log_wpp='N';}else{$log_wpp=$_REQUEST['LOG_WPP'];}
			if (empty($_REQUEST['LOG_PUSH'])) {$log_push='N';}else{$log_push=$_REQUEST['LOG_PUSH'];}
			
			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			//fnEscreve($cod_empresa);
						
			if ($opcao != ''){					
				// //fnEscreve($qrBuscaNovo["COD_NOVO"]);				
				// $cod_desafio = $qrBuscaNovo["COD_NOVO"];

				//atualiza lista iframe				
				?>
				
				<script>
					try { parent.$('#REFRESH_DESAFIO').val("S"); } catch(err) {}
				</script>	
				
				<?php
				 		
				 
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						
		


						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

					$sql = "UPDATE DESAFIO SET
									COD_PERSONA=$cod_persona,
									NOM_DESAFIO='$nom_desafio',
									DES_DESAFIO='$des_desafio',
									DAT_INI='".fnDataSql($dat_ini)."',
									DAT_FIM='".fnDataSql($dat_fim)."',
									DES_ICONE='$des_icone',
									DES_COR='$des_cor',
									VAL_METADES='".fnValorsql($val_metades)."',
									LOG_EMAIL='$log_email',
									LOG_SMS='$log_sms',
									LOG_WPP='$log_wpp',
									LOG_PUSH='$log_push',
									COD_ALTERAC=$cod_usucada,
									DAT_ALTERAC=NOW(),
									LOG_ATIVO='$log_ativo'
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_DESAFIO = $cod_desafio";
				
						//fnEscreve($sql);
						mysqli_query(connTemp($cod_empresa,''),$sql);

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
	
	//defaul - perfil
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, TIP_RETORNO, NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);                     
			
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
			$NUM_DECIMAIS_B=$qrBuscaEmpresa['NUM_DECIMAIS_B'];
			if ($tip_retorno == 2){
				$casasDec = $NUM_DECIMAIS_B;
				$cifrao = "R$";
			}else { 
				$casasDec = '0';
				$cifrao = "";
			}
		}
												
	}else {
		$cod_empresa = 0;
		$cod_desafio = 0;		
		//fnEscreve('entrou else');
		$log_ativo = "N";
		$mostraChecado = "checked";
		$mostraChecadoATU = "checked";		
		$nom_desafio = "";
		$abr_campanha = "";
		$des_icone = "";
		$des_cor = "";
		$des_observa = "";	
		$tip_campanha = "";	
		$log_continu = "N";
		$dat_ini = "";
		$hor_ini = "";
		$dat_fim = "";
		$hor_fim = "";
		$casasDec = 2;
	}

	
	$cod_desafio = fnDecode($_GET['idD']);
	//fnEscreve($cod_desafio);
	if ($cod_desafio != 0){
		$sql = "SELECT * FROM DESAFIO WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $cod_desafio";
		 
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(ConnTemp($cod_empresa,''),$sql);
		$qrBuscaDesafio = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)){
			//fnEscreve('query busca');
			$log_ativo = $qrBuscaDesafio['LOG_ATIVO'];
			if ($log_ativo == "S"){ $mostraAtivo = "checked";}
			else {$mostraAtivo = "";}	

			$log_email = $qrBuscaDesafio['LOG_EMAIL'];
			if ($log_email == "S"){ $mostraEmail = "checked";}
			else {$mostraEmail = "";}

			$log_sms = $qrBuscaDesafio['LOG_SMS'];
			if ($log_sms == "S"){ $mostraSms = "checked";}
			else {$mostraSms = "";}

			$log_wpp = $qrBuscaDesafio['LOG_WPP'];
			if ($log_wpp == "S"){ $mostraWpp = "checked";}
			else {$mostraWpp = "";}

			$log_push = $qrBuscaDesafio['LOG_PUSH'];
			if ($log_push == "S"){ $mostraPush = "checked";}
			else {$mostraPush = "";}

			$cod_persona = $qrBuscaDesafio['COD_PERSONA'];
			$nom_desafio = $qrBuscaDesafio['NOM_DESAFIO'];
			$des_desafio = $qrBuscaDesafio['DES_DESAFIO'];
			$dat_ini = $qrBuscaDesafio['DAT_INI'];
			$dat_fim = $qrBuscaDesafio['DAT_FIM'];
			$des_icone = $qrBuscaDesafio['DES_ICONE'];
			$des_cor = $qrBuscaDesafio['DES_COR'];
			$val_metades = $qrBuscaDesafio['VAL_METADES']; 

		}
	
	}

	
	$cod_cliente = fnDecode($_GET['idC']);
	//$cod_cliente = fnDecode("sYrJ83Jp7YA¢");
	if ($cod_cliente != 0){
		
	$sql = "SELECT  COD_CLIENTE,
					NUM_CARTAO,
					NUM_CGCECPF,
					NOM_CLIENTE, 
					DES_EMAILUS,
					NUM_TELEFON,
					NUM_CELULAR,
					DAT_CADASTR,
					DAT_NASCIME,
					COD_SEXOPES,
					DAT_ULTCOMPR
			FROM CLIENTES 
			WHERE 
			COD_CLIENTE = $cod_cliente AND
			COD_EMPRESA = $cod_empresa
			";
	
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(ConnTemp($cod_empresa,''),$sql);
		$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)){
			$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
			$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
			$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
			$des_emailus = $qrBuscaCliente['DES_EMAILUS'];
			$num_telefon = $qrBuscaCliente['NUM_TELEFON'];
			$num_celular = $qrBuscaCliente['NUM_CELULAR'];
			$dat_nascime = $qrBuscaCliente['DAT_NASCIME'];
			$cod_sexopes = $qrBuscaCliente['COD_SEXOPES']; 			
			$dat_ultcompr = $qrBuscaCliente['DAT_ULTCOMPR']; 			
																									
			if ($qrBuscaCliente['COD_SEXOPES'] == 1){		
				$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
			}else{ $mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>'; }	
									
			if ($qrBuscaCliente['DES_EMAILUS'] != ""){	
				$mostraMail = '<div class="col-xs-4" style="padding-left:0; padding-right:0;"><i class="fal fa-envelope-open" aria-hidden="true"></i> '.$qrBuscaCliente['DES_EMAILUS'].' </div>';	
			}else{ $mostraMail = ''; }	
									
			if ($qrBuscaCliente['NUM_CELULAR'] != ""){	
				$mostraCel = '<div class="col-xs-4" style="padding-left:0; padding-right:0;"><i class="fal fa-mobile" aria-hidden="true"></i> '.$qrBuscaCliente['NUM_CELULAR'].' </div>';	
			}else{ $mostraCel = ''; }	
									
			if ($qrBuscaCliente['NUM_TELEFON'] != ""){	
				$mostraFone = '<div class="col-xs-4" style="padding-left:0; padding-right:0;"><i class="fal fa-phone" aria-hidden="true"></i> '.$qrBuscaCliente['NUM_TELEFON'].' </div>';	
			}else{ $mostraFone = ''; }
			}
	
	}

	$sqlResgate = "SELECT
					IFNULL((SELECT SUM(d.val_credito) FROM creditosdebitos d 
					WHERE d.cod_cliente = A.cod_cliente AND
							d.tip_credito = 'D' AND
							d.cod_statuscred != 6 AND
							d.tip_campanha = A.tip_campanha),0)+
							IFNULL((SELECT SUM(e.val_credito) FROM creditosdebitos_bkp e
							WHERE e.cod_cliente = A.cod_cliente AND
									e.tip_credito = 'D' AND
									e.cod_statuscred != 6
									AND e.tip_campanha = A.tip_campanha
									),0)  AS TOTAL_DEBITOS
					FROM creditosdebitos A, webtools.empresas B
					WHERE A.cod_empresa = B.cod_empresa AND
					A.tip_campanha = B.tip_campanha AND
					A.cod_cliente = $cod_cliente AND
					A.cod_empresa = $cod_empresa";

	// fnEscreve($sqlResgate);

	$arrayResgate = mysqli_query(connTemp($cod_empresa,''),$sqlResgate);

	$qrResgate = mysqli_fetch_assoc($arrayResgate);

	$vl_resgate = $qrResgate['TOTAL_DEBITOS'];

	$sqlGasto = "SELECT IFNULL(SUM(V.VAL_TOTVENDA),0) AS TOTAL_GASTO
				FROM VENDAS V
				INNER JOIN CLIENTES C ON C.COD_CLIENTE = V.COD_CLIENTE
				INNER JOIN ITEMVENDA I ON V.COD_VENDA = I.COD_VENDA
				INNER JOIN PRODUTOCLIENTE PR ON PR.COD_PRODUTO = I.COD_PRODUTO
				AND V.COD_CLIENTE = I.COD_CLIENTE
				WHERE V.COD_CLIENTE = $cod_cliente
				AND V.COD_EMPRESA = $cod_empresa";

	// fnEscreve($sqlGasto);

	$arrayGasto = mysqli_query(connTemp($cod_empresa,''),$sqlGasto);

	$qrGasto = mysqli_fetch_assoc($arrayGasto);

	$vl_gasto = $qrGasto['TOTAL_GASTO'];
	
	//busca saldo do cliente
	$sqlSaldo = "SELECT 
        
			        (SELECT Sum(val_saldo) 
			        FROM   creditosdebitos 
			        WHERE  cod_cliente = A.cod_cliente 
			            AND tip_credito = 'C' 
			            AND COD_STATUSCRED IN(1,2) 
			            AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS CREDITO_DISPONIVEL,
			      
					 (SELECT max(dat_libera) 
			        FROM   creditosdebitos 
			        WHERE  cod_cliente = A.cod_cliente 
			            AND tip_credito = 'C' 
			            AND COD_STATUSCRED IN(1,2) 
			            AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS DAT_LIBERA,
			            
					(SELECT min(dat_expira) 
			        FROM   creditosdebitos 
			        WHERE  cod_cliente = A.cod_cliente 
			            AND tip_credito = 'C' 
			            AND COD_STATUSCRED IN(1,2) 
			            AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS DAT_MINIMA
			     
			       
			      
			      FROM CREDITOSDEBITOS A
			      WHERE COD_CLIENTE = $cod_cliente
			      AND COD_EMPRESA = $cod_empresa
			      GROUP BY COD_CLIENTE";

	// fnEscreve($sqlSaldo);

  $row = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);
	$qrBuscaSaldo = mysqli_fetch_assoc($row);
    // fnEscreveArray($qrBuscaSaldo);
	$credito_disponivel = fnValor($qrBuscaSaldo['CREDITO_DISPONIVEL'],$casasDec);
	$dat_libera = fnDataShort($qrBuscaSaldo['DAT_LIBERA']); 
	$dat_minima = fnDataShort($qrBuscaSaldo['DAT_MINIMA']);

	// fnEscreve($credito_disponivel);
	// fnEscreve($dat_minima);

?>

<style>
.cd-timeline > h1, .cd-timeline > h2 {
  font-size: 16px;
  font-weight: 400;
  margin-top: 13px;
}

@media only screen and (min-width: 1170px) {
  .cd-is-hidden {
    visibility: hidden;
  }
}

.cd-timeline > h1, .cd-timeline > h2 {
  font-size: 16px;
  font-weight: 400;
  margin-top: 13px;
}

@media only screen and (min-width: 1170px) {
  .cd-is-hidden {
    visibility: hidden;
  }
}

.cd-timeline {
  overflow: hidden;
  margin: 2em auto;
  background: #fff;
}

.cd-timeline__block:nth-child(n) .cd-timeline__img {
  	background: #AED6F1;
}

.cd-timeline__block2:nth-child(n) .cd-timeline__img {
  	background: #cecece;
}

.cd-timeline__container {
  position: relative;
  width: 90%;
  max-width: 1170px;
  margin: 0 auto;
  padding: 6px 0 2em 0;
}

.cd-timeline__container::before {
  /* this is the vertical line */
  content: '';
  position: absolute;
  margin-top: 18px;
  left: 18px;
  height: 100%;
  border-right: dashed 4px #cecece;
}

.cd-timeline__block2:nth-child(n) .cd-timeline__content {
    background: #FFF;
    border: 1px #cecece;
}

.cd-timeline__block2:nth-child(n) .cd-timeline__content h2{
  	color: #2c3e50;
  	border-bottom-color: #2c3e50; 
}

.cd-timeline__block2:nth-child(n) .cd-timeline__content p{
  	color: #2c3e50;
}

.cd-timeline__block:nth-child(n) .cd-timeline__content::before { border-right-color: #AED6F1; }

.cd-timeline__img {
  position: absolute;
  top: 12px;
  left: 4.3%;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  -webkit-box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
          box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
}

@media only screen and (min-width: 1170px) {
  .cd-timeline {
    margin-top: 3em;
    margin-bottom: 3em;
  }
  .cd-timeline__container::before {
    left: 50%;
    margin-left: -2px;
  }
}

.cd-timeline__block,
.cd-timeline__block2 {
  position: relative;
  margin: 2em 0;
}

.cd-timeline__block:after,
.cd-timeline__block2:after {
  /* clearfix */
  content: "";
  display: table;
  clear: both;
}

.cd-timeline__block:first-child,
.cd-timeline__block2:first-child {
  margin-top: 0;
}

.cd-timeline__block:last-child,
.cd-timeline__block2:last-child {
  margin-bottom: 0;
}

@media only screen and (min-width: 1170px) {
  .cd-timeline__block,
  .cd-timeline__block2 {
    margin: 1.5em 0;
  }
}

@media only screen and (min-width: 1170px) {
  .cd-timeline__img {
    width: 21px;
    height: 21px;
    left: 51.65%;
    top: 20px;
    margin-left: -30px;
    /* Force Hardware Acceleration */
    -webkit-transform: translateZ(0);
            transform: translateZ(0);
  }
}

.cd-timeline__content {
  position: relative;
  margin-left: 60px;
  background: #AED6F1;
  border-radius: 0.25em;
  padding: 1em;
  border-radius: 5pt;
  box-shadow: 0px 3px 25px 0px rgba(10, 55, 90, 0.2);
}

.cd-timeline__content:after {
  /* clearfix */
  content: "";
  display: table;
  clear: both;
}

.cd-timeline__content::before {
  /* triangle next to content block */
  content: '';
  position: absolute;
  top: 16px;
  right: 100%;
  height: 0;
  width: 0;
  border: 7px solid transparent;
  border-right: 7px solid white;
}

.cd-timeline__content h2 {
  color: #2c3e50;
  padding-bottom: 10px;
}

.cd-timeline__content p{ color: #2c3e50; }

.cd-timeline__content p,
.cd-timeline__date {
  font-size: 1.3rem;
}

.cd-timeline__content p {
  margin: 1em 0;
  line-height: 1.6;
}
.cd-timeline__date {
  display: inline-block;
}
.cd-timeline__date {
  float: left;
  padding: .8em 0;
  opacity: .7;
}
.hora { 
	font-size: 14px;
	color: #3c3c3c; 
	font-weight: bolder;
}

@media only screen and (min-width: 768px) {
  .cd-timeline__content h2 {
    font-size: 18px;
    border-bottom: dashed 1px #2c3e50;
    margin-bottom: -10px;
  }

  .cd-timeline__content p {
    font-size: 1.6rem;
  }
  .cd-timeline__date {
    font-size: 1.4rem;
  }
 .cd-timeline__img {
	position: absolute;
	top: 12px;
	left: 1.3%;
	width: 18px;
	height: 18px;
	border-radius: 50%;
	-webkit-box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
	      box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
	}
}

@media only screen and (min-width: 1170px) {
  .cd-timeline__content {
    margin-left: 0;
    padding: 1px 1em 1em 1em;
    width: 45%;
    /* Force Hardware Acceleration */
    -webkit-transform: translateZ(0);
            transform: translateZ(0);
  }

  .cd-timeline__img {
  position: absolute;
  top: 22px;
  left: 399;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  -webkit-box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
          box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
}

  .cd-timeline__content::before {
    top: 24px;
    left: 100%;
    border-color: transparent;
    border-left-color: white;
  }
  .cd-timeline__date {
    position: absolute;
    width: 100%;
    left: 122%;
    top: 6px;
    font-size: 1.6rem;
  }
  .cd-timeline__block2:nth-child(n) .cd-timeline__content {
    float: right;
    background: #FFF;
    border: 1px #cecece;
  }
  .cd-timeline__block2:nth-child(n) .cd-timeline__content::before {
    top: 24px;
    left: auto;
    right: 100%;
    border-color: transparent;
    border-right-color: white;
  }

  .cd-timeline__block:nth-child(n) .cd-timeline__content::before {
    top: 24px;
    left: 100%;
    right: auto;
    border-color: transparent;
	border-bottom-color: #AED6F1;
    transform: rotate(90deg);
  }
  .cd-timeline__block2:nth-child(n) .cd-timeline__content h2{
  	color: #2c3e50;
  	border-bottom-color: #2c3e50; 
  }

  .cd-timeline__block2:nth-child(n) .cd-timeline__content p{
  	color: #2c3e50;
  }
  .cd-timeline__block2:nth-child(n) .cd-timeline__date {
    left: auto;
    right: 122%;
    text-align: right;
    color: #7f8c97;
  }

  .cd-timeline__block:nth-child(n) .cd-timeline__date {
    color: #6bbfee;
  }
}
</style>

			
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
									<?php } ?>
									
									<?php if ($log_preTipo =='S') { ?>	
									<div class="alert alert-warning top30 bottom30" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 Informe os dados para o preenchimento da sua <strong>Desafio</strong>. 
									</div>
									<?php } ?>
		
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

										<div class="row">
											
											<div class="col-md-7">
											
												<div class="row">
													<h5><b>Dados do Cliente</b></h5>
													<div class="push10"></div>
									
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?php echo $nom_cliente; ?>">
														</div>														
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Cartão</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>">
														</div>														
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Aniversário</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_NASCIME" id="DAT_NASCIME" value="<?php echo fnDataShort($dat_nascime); ?> (32 anos)">
														</div>														
													</div>

												</div>

												<div class="push20"></div>

												<div class="row">
													
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label">Contatos</label>
															<div class="push5"></div>
															<?php echo $mostraMail.$mostraCel.$mostraFone; ?>
														</div>														
													</div>

												</div>

												<div class="push20"></div>

												<div class="row">
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Última Compra</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_ULTCOMPR" id="DAT_ULTCOMPR" value="<?=fnDataShort($dat_ultcompr)?>">
														</div>														
													</div>	
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Total Gasto</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="VL_GASTO" id="VL_GASTO" value="R$<?=fnValor($vl_gasto,$casasDec)?>">
														</div>														
													</div>	
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Resgates</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="VL_RESGATE" id="VL_RESGATE" value="<?=$cifrao.fnValor($vl_resgate,$casasDec)?>">
														</div>														
													</div>
																										
												</div>

												<div class="push10"></div>

												<div class="row">
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Saldo do Cliente</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="VAL_CREDITO" id="VAL_CREDITO" value="R$<?=$credito_disponivel?>">
														</div>														
													</div>	
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Próximo Vencimento</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_MINIMA" id="DAT_MINIMA" value="<?=$dat_minima?>">
														</div>														
													</div>	
																										
												</div>

												<div class="push30"></div>												
											
												<div class="row">

													<h5>Últimas Compras</h5>
													<div class="push10"></div>
												
													<table class="table table-bordered table-striped table-hover">
													  <thead>
														<tr>
														  <th>Data</th>
														  <th class="text-center">Loja</th>
														  <th class="text-center">Produto</th>
														  <th class="text-center">Código</th>
														  <th class="text-center">Quantidade</th>
														  <th class="text-center">Valor</th>
														  <th class="text-center">Vendedor</th>
														</tr>
													  </thead>
													<tbody id="div_refreshDesafio">											
												
													<?php
																
														$sql = "SELECT V.COD_VENDA , I.COD_ITEMVEN , PR.DES_PRODUTO, V.DAT_CADASTR , C.NOM_CLIENTE , C.NUM_CGCECPF , V.VAL_TOTVENDA , I.QTD_PRODUTO
																FROM VENDAS V
																INNER JOIN CLIENTES C ON C.COD_CLIENTE = V.COD_CLIENTE
																INNER JOIN ITEMVENDA I ON V.COD_VENDA = I.COD_VENDA
																INNER JOIN PRODUTOCLIENTE PR ON PR.COD_PRODUTO = I.COD_PRODUTO
																AND V.COD_CLIENTE = I.COD_CLIENTE
																WHERE V.COD_CLIENTE = $cod_cliente
																AND V.COD_EMPRESA = $cod_empresa
																ORDER BY V.DAT_CADASTR DESC LIMIT 5 ";
														
														//fnEscreve($sql);
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
														
														$count=0;
														while ($qrListaVenda = mysqli_fetch_assoc($arrayQuery))
														  {	                                           
															$count++; 
															
															$dat_cadastr = $qrListaVenda['DAT_CADASTR'];
															$cod_venda = $qrListaVenda['COD_VENDA'];
															$cod_itemven = $qrListaVenda['COD_ITEMVEN'];
															$des_produto = $qrListaVenda['DES_PRODUTO'];
															$val_totvenda = $qrListaVenda['VAL_TOTVENDA'];
															$qtd_produto = $qrListaVenda['QTD_PRODUTO'];
															
													?>

													<tr>
													  <td><small><?php echo fnDataShort($dat_cadastr); ?></small></td>
													  <td></td>
													  <td><small><?php echo $des_produto; ?></small></td>
													  <td><small><?php echo $cod_itemven; ?></small></td>
													  <td class='text-center'><?php echo fnValor($qtd_produto,0); ?></td>
													  <td class="text-center"><small>R$ <?php echo fnValor($val_totvenda,$casasDec); ?></td>
													  <td class="text-center"></td>
													</tr>

													<?php
														  }											
													
													?>
														
													</tbody>
													</table>
													
												</div>
												
												<div class="push20"></div>

													<div class="row">

														<h5>Follow Up</h5><!-- bloco do follow up -->
														<div class="push10"></div>

														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label required">Classificação do Atendimento</label>
																
																	<select data-placeholder="Classifique aqui o atendimento" name="COD_CLASSIFICA" id="COD_CLASSIFICA" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
																		<option value=""></option>
																		<?php
																																				
																		
																		$sql = "SELECT * FROM CLASSIFICA_ATENDIMENTO WHERE COD_EMPRESA = $cod_empresa";																		
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());																
																		while ($qrClassifica = mysqli_fetch_assoc($arrayQuery))
																		{	
                                        ?>
                                        	<option value="<?=$qrClassifica['COD_CLASSIFICA']?>"><?=$qrClassifica['DES_CLASSIFICA']?></option>
                                        <?php                                                          
																		}
																		?>								
																	</select>
																	<span class="help-block"></span>
																	<?php // fnEscreve($arrayQuery); ?>																
																	<div class="help-block with-errors"></div>
																	<script>
																		// $("#formulario #COD_PERSONA").val('<?=$cod_persona?>').trigger("chosen:updated");
																	</script>	
															</div>
														</div>

														<div class="col-md-4">   
															<div class="form-group">
																<label for="inputName" class="control-label">Agendar novo contato</label> 
																<div class="push5"></div>
																<label class="switch">
																	<input type="checkbox" name="LOG_AGENDA" id="LOG_AGENDA" class="switch" value="S">
																	<span></span>
																</label>
															</div>
														</div>

														<?php if($_SESSION['SYS_COD_EMPRESA'] == 2){ ?>

														<!-- <div class="col-md-2">   
															<div class="form-group">
																<label for="inputName" class="control-label">Enviar WhatsApp</label> 
																<div class="push5"></div>
																<label class="switch">
																	<input type="checkbox" name="LOG_ENVIOWPP" id="LOG_ENVIOWPP" class="switch" value="S">
																	<span></span>
																</label>
															</div>
														</div> -->

														<?php } ?>

														<div class="col-md-3 dat-agendame" style="display:none;">
															<div class="form-group">
																<label for="inputName" class="control-label">Data Agendamento</label>
																<div class="input-group date datePicker" id="DAT_INI_GRP">
																	<input type='text' class="form-control input-sm data" name="DAT_AGENDAME" id="DAT_AGENDAME" value="">
																	<span class="input-group-addon">
																		<span class="glyphicon glyphicon-calendar"></span>
																	</span>
																</div>
																<div class="help-block with-errors"></div>
															</div>
														</div>

													</div>

													<div class="row">
														<div class="col-md-9">
															<div class="form-group">
																<label for="inputName" class="control-label required">Comentário: </label>
																<textarea class="form-control input-sm" rows="6" name="DES_COMENT" id="DES_COMENT"></textarea>
																<div class="help-block with-errors"></div>
															</div>
														</div>
													</div>

													<div class="push20"></div>

													<input type="hidden" name="COD_DESAFIO" value="<?=$cod_desafio?>">
													<input type="hidden" name="COD_CLIENTE" value="<?=$cod_cliente?>">
													<input type="hidden" name="COD_EMPRESA" value="<?=$cod_empresa?>">

													<?php 
													// fnEscreve($cod_cliente);
													// fnEscreve($cod_empresa);
													?>

												<div class="row">
													<div class="col-md-9">
														<div class="form-group">
															<a href="javascript:void(0)" name="CAD_COMMENT" id="CAD_COMMENT" class="btn btn-info pull-right"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Comentar</a>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="col-md-10">
														<hr>
													</div>
												</div>

												<div class="row">
													<div class="col-md-10">
													<h5>Histórico de Follow Up's<h5>

														<section class="cd-timeline">

															<div id="relatorioFollowUp">

																<?php

																	//setando locale da data
																	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
																	date_default_timezone_set('America/Sao_Paulo');		

																	$sql2 = "SELECT FC.*, CA.DES_CLASSIFICA FROM FOLLOW_CLIENTE FC 
																	LEFT JOIN CLASSIFICA_ATENDIMENTO CA ON CA.COD_CLASSIFICA = FC.COD_CLASSIFICA 
																	WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = $cod_cliente
																	ORDER BY FC.DAT_CADASTR ASC";

																	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2);
																	while($qrFollow = mysqli_fetch_assoc($arrayQuery)){

																		$mes = strtoupper(strftime('%B', strtotime($qrFollow['DAT_CADASTR'])));
																		$mes = substr("$mes", 0, 3);
																	?>

																		<div class="cd-timeline__container">
																			<div class="cd-timeline__block2">
																				<div class="cd-timeline__img"></div>
																				<div class="cd-timeline__content">
																					<h2><?=$qrFollow['DES_CLASSIFICA']?></h2>
																					<p><?=$qrFollow['DES_COMENT']?></p>
																					<span class="cd-timeline__date"><?php echo strftime('%d ', strtotime($qrFollow['DAT_CADASTR']))."".$mes; ?><br><span class="hora"><?php echo date("H:i", strtotime($qrFollow['DAT_CADASTR'])); ?></span></span>
																				</div>
																			</div>
																		</div>

																<?php 
																} 
																?>
															</div>

														</section>

													</div>
												</div>
												
											</div> <!-- /fim bloco do follow up -->
											
											<div class="col-md-1"></div>
											
											<div class="col-md-4"> 


												<!-- <ul class="nav nav-tabs">
												  <li class="active"><a data-toggle="tab" href="#roteiro">Script do Desafio</a></li>
												  <li><a data-toggle="tab" href="#comunicacao">Comunicação</a></li>
												</ul> -->
																			
												<div class="tab-content" style="padding: 20px; background: #F4F6F6; border-radius: 5px;">		

													<!-- aba roteiro -->
													<div id="roteiro" class="tab-pane active">

														<h4>Script de Atendimento</h4>
													
														<div class="push20"></div>	
														
														<div class="row">													
															
															<div class="col-md-12">
																	
																<?php echo html_entity_decode($des_desafio);?>
																
															</div>
															
														</div>	
																			
													</div>
													
													<!-- aba comunicação -->
													<div id="comunicacao" class="tab-pane fade">
													
														<div class="push30"></div>	
														
														<div class="row">													
															
															<div class="col-md-1"></div>
															
															<div class="col-md-5 text-center">
																					
																<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" data-title="Comunicação / <?php echo $des_persona; ?>" disabled>
																<i class="fa fa-envelope"></i>
																&nbsp; Enviar e-Mail
																</a>
																
															</div>	
															
															<div class="col-md-1"></div>
															
															<div class="col-md-5 text-center">
																					
																<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" data-title="Comunicação / <?php echo $des_persona; ?>" disabled>
																<i class="fa fa-comment-alt"></i>
																&nbsp; Enviar SMS
																</a>
																
															</div>

															<div class="push20"></div>
															
															<div class="col-md-1"></div>
															
															<div class="col-md-5 text-center">
																					
																<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" data-title="Comunicação / <?php echo $des_persona; ?>" disabled>
																<i class="fab fa-whatsapp"></i>
																&nbsp; Enviar Whats App
																</a>
																
															</div>

															<div class="col-md-1"></div>															
															
															<div class="col-md-5 text-center">
																					
																<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" data-title="Comunicação / <?php echo $des_persona; ?>" disabled>
																<i class="fas fa-comment-alt-smile"></i>
																&nbsp; Enviar Pesquisa NPS
																</a>
																
															</div>

															<div class="push20"></div>															
															
															<div class="col-md-1"></div>
															
															<div class="col-md-5 text-center">
																					
																<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" data-title="Comunicação / <?php echo $des_persona; ?>" disabled>
																<i class="fas fa-bell"></i>
																&nbsp; Enviar Push
																</a>
																
															</div>	
																															
														</div>	
																			
													</div>
													
												</div>												
											
											
											</div>
											
										</div>
														
							
											
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
										
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <?php if ($cod_desafio <> 0) { ?>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <?php } else { ?>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <?php } ?>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<?php if ($cod_desafio <> 0) { ?>
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										<?php } else { ?>
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N'">		
										<?php } ?>
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>	
						
					<div class="push20"></div>
					
	<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>
	
	<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
	<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
	
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
	
		//datas
		$(function () {
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 //maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			$('.clockPicker').datetimepicker({
				 format: 'LT',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

		});		
		
        $(document).ready( function() {

        	$('#LOG_AGENDA').change(function() { 
                if ($('#LOG_AGENDA').prop("checked")){
                    $('.dat-agendame').fadeIn('fast');
                }else{
                    $('.dat-agendame').fadeOut('fast');
                }                     
            }); 
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			$('#COD_PERSONA').chosen({ max_selected_options: 1});

			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',				
				theme: 'bootstrap'
			});
			
			//capturando o ícone selecionado no botão
			$('#btniconpicker').on('change', function(e) {
			    $('#DES_ICONE').val(e.icon);
			    //alert($('#DES_ICONE').val());
			});

			$('#CAD_COMMENT').click(function(){

				let comment = $('#DES_COMENT').val(),
				    classifica = $('#COD_CLASSIFICA').val();

				if(comment.trim() != "" && classifica.trim() != ""){

					$.ajax({
						method: 'POST',
						url: 'ajxComentarioDesafio.php',
						data: $('#formulario').serialize(),
						beforeSend:function(){
							$('#relatorioFollowUp').html('<div class="loading" style="width: 100%;"></div>');
						},
						success:function(data){
							$('#relatorioFollowUp').html(data);
							$('#DES_COMENT').val('');
							$('#COD_CLASSIFICA').val('').trigger("chosen:updated");
							try { parent.$('#REFRESH_CLIENTE').val("S"); } catch(err) {}
							try { parent.$('#ID_CARTAO').val('<?=$num_cartao?>'); } catch(err) {}
						}
					});

				}else{

					$.alert({
              title: "Aviso",
              content: "A mensagem/classificação não pode ser vazia.",
              type: 'orange'
          });

				}

			});
			
        });
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	