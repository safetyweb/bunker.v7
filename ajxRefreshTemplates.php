<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
//fnEscreve($buscaAjx2);
?>
			<?php 
				//$sql = "SELECT  * FROM TEMPLATE ORDER BY COD_TEMPLATE";
				$sql = "SELECT  * FROM TEMPLATE WHERE cod_empresa = $buscaAjx1 ORDER BY NOM_TEMPLATE";
						
				$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql) or die(mysqli_error());
				
				$count=0;
				while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;	
					?>
					<div class="col-md-2">  
						<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
							<a data-url="action.php?mod=<?php echo fnEncode(1113)?>&id=<?php echo fnEncode($buscaAjx1)?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Template" class="informer informer-default addBox" style="color: #2c3e50;">
								<span class="fa fa-edit"></span>
							</a>
							<a href='action.php?mod=<?php echo fnEncode(1114)?>&id=<?php echo fnEncode($buscaAjx1)?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE'])?>' style='color: #2c3e50; border: none; text-decoration: none;'>
								<i class="fal fa-file-check fa-lg" style="font-size: 40px"></i>
								<div class="push20"></div> 
								<p class="folder"><?php echo $qrBuscaModulos['NOM_TEMPLATE']; ?></p>
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