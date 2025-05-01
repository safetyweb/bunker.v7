<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	
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
		$cod_player = fnDecode($_GET['idp']);
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

	//busca usuário modelo	
	$sql = "SELECT * FROM  USUARIOS
			WHERE LOG_ESTATUS='S' AND
				  COD_EMPRESA = $cod_empresa AND
				  COD_TPUSUARIO=10  limit 1  ";
	// fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
					
	if (isset($arrayQuery)) {
		$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
		$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
	}

	if($cod_player != 0){



		$sql = "SELECT T.COD_PLAYERS,
					   U.COD_EXTERNO,
					   T.COD_EMPRESA,
					   T.COD_UNIVEND,
					   U.NOM_FANTASI,
					   T.COD_USUARIO,
					   S.NOM_USUARIO, 
					   T.VAL_INATIVO, 
					   T.LOG_TICKET, 
					   T.DES_PAGHOME, 
					   T.LOG_NPS 
				FROM TOTEM_PLAYERS T 
				LEFT JOIN WEBTOOLS.UNIDADEVENDA U ON U.COD_UNIVEND=T.COD_UNIVEND
				LEFT JOIN WEBTOOLS.USUARIOS S ON S.COD_USUARIO=T.COD_USUARIO
				WHERE T.COD_EMPRESA = $cod_empresa
				AND T.COD_PLAYERS = $cod_player
				AND U.LOG_ESTATUS != 'N'";
		
		// fnEscreve($sql);
		// exit();
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
		
		$qrLista = mysqli_fetch_assoc($arrayQuery);													  
			

		$idlojaKey = $qrLista['COD_UNIVEND'];
		$idmaquinaKey = 0;
		$codvendedorKey = 0;
		$nomevendedorKey = 0;

		$urltotem = fnEncode($log_usuario.';'
					.$des_senhaus.';'
					.$idlojaKey.';'
					.$idmaquinaKey.';'
					.$cod_empresa.';'
					.$codvendedorKey.';'
					.$nomevendedorKey.';'
					.$qrLista['COD_PLAYERS']
		);

		// // echo($log_usuario);

		$des_paghome = $qrLista['DES_PAGHOME'];
		$destinoHome = "";

		if($des_paghome == "index"){
			$destinoHome = "";
		}else if($des_paghome == "nps"){
			$destinoHome = "pesquisa.do";
		}else if($des_paghome == "cad"){
			$destinoHome = "consulta_V2.do";
		}else if($des_paghome == "meta"){
			$destinoHome = "meta.do";
		}else if($des_paghome == "atd"){
			$destinoHome = "atendente.do";
		}else{
			$destinoHome = "banner.do";
		}

		$linkCode = "https://totem.bunker.mk/".$destinoHome."?key=".rawurlencode($urltotem)."&".date("Ymdhis").round(microtime(true) * 1000);
		
	}
	

	
?>
	
	<div class="row" id="div_Report">				
	
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
						<span class="text-primary"><?php echo $NomePg; ?></span>
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
				
					<div class="login-form">

						<div class="push20"></div>
					
						<center><div id="qrcodeCanvas"></div></center>

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-12 text-center">
								<!-- <div class="push5"></div>
								<h3><?=$nomeConsulta?></h3> -->
								<div class="push20"></div>
								<a href="javascript:void(0)" class="btn btn-info" id="saveQr"><span class="fal fa-save"></span>&nbsp;Salvar imagem</a>
							</div>
						</div>
					
					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>
	
	<div class="push20"></div>

	<script type="text/javascript" src="js/jquery-qrcode-master/src/jquery.qrcode.js"></script>
	<script type="text/javascript" src="js/jquery-qrcode-master/src/qrcode.js"></script>

    <script>
	
		geraQRCode();

		function geraQRCode(){
			$("#qrcodeCanvas").html("");
			jQuery('#qrcodeCanvas').qrcode({
				text: "<?=$linkCode?>",
				width: 400,
				height: 400
			});	
		}

		$("#saveQr").click(function(){
			this.href = $('#qrcodeCanvas canvas')[0].toDataURL();// Change here
    		this.download = '<?=@$qrLista["COD_EXTERNO"]?>_qrCode_<?=str_replace(" ","_",strtolower(@$qrLista["NOM_FANTASI"]))?>.jpg';
		});

	</script>	
   