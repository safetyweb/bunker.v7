<?php include "_system/_functionsMain.php"; 
$cod_empresa = fnLimpacampo($_GET['ajx1']);

//fnEscreve($buscaAjx2);
?>
			<?php 
				$sql = "SELECT  * FROM DOCUMENTOS WHERE cod_empresa = $cod_empresa AND COD_EXCLUSA = 0 ORDER BY NOM_DOCUMEN";
						
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$count=0;
				while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;	
					?>
					<div class="col-md-2">  
						<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
							<a data-url="action.php?mod=<?php echo fnEncode(1891)?>&id=<?php echo fnEncode($cod_empresa)?>&idD=<?php echo fnEncode($qrBuscaModulos['COD_DOCUMEN']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Documento" class="informer informer-default addBox" style="color: #2c3e50;">
								<span class="fa fa-edit"></span>
							</a>
							<a href='action.php?mod=<?php echo fnEncode(1892)?>&id=<?php echo fnEncode($cod_empresa)?>&idD=<?php echo fnEncode($qrBuscaModulos['COD_DOCUMEN'])?>' style='color: #2c3e50; border: none; text-decoration: none;'>
								<i class="fal fa-file-alt fa-lg" style="font-size: 40px"></i>
								<div class="push20"></div> 
								<p class="folder"><?php echo $qrBuscaModulos['NOM_DOCUMEN']; ?></p>
							</a>
						</div> 										
					</div>

			<?php			
					  }											
			?>			
<script language=javascript> 
$(".chosen-select-deselect").chosen({allow_single_deselect:true});
//$("#COD_SUBCATE").val(<?php echo $buscaAjx2 ?>).trigger("chosen:updated");
</script> 