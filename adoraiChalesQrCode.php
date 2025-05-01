<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();

	$cod_hotel = $_GET['idh'];
	$cod_chale = $_GET['idc'];	
	
	//busca dados url
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
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}

	$linkCode = "https://roteirosadorai.com.br/detalhes.php?idh=".$cod_hotel."&idc=".$cod_chale;
		
	

	
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
    		this.download = '<?=@$cod_hotel?>_qrCode_<?=str_replace(" ","_",strtolower(@$cod_chale))?>.jpg';
		});

	</script>	
   