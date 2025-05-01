<?php include "_system/_functionsMain.php"; 
$cod_empresa = fnLimpacampo($_GET['ajx1']);
$_GET["mod"]=fnEncode(1545);
//fnEscreve($buscaAjx2);
?>
			<?php 
				$sql = "SELECT  * FROM ORGANOGRAMA WHERE cod_empresa = $cod_empresa ORDER BY NOM_ORGANOGRAMA";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$count=0;
				while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;	
					?>
								<div class="col-md-2">  
									<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
										<a data-url="action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($qrBuscaModulos['COD_ORGANOGRAMA']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Organograma" class="informer informer-default addBox" style="color: #2c3e50;">
											<span class="fa fa-edit"></span>
										</a>
										<a href='action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($qrBuscaModulos['COD_ORGANOGRAMA'])?>&tipo=<?=fnEncode('ORG')?>' style='color: #2c3e50; border: none; text-decoration: none; text-align:center;'>
											<div class="push30"></div> 
											<center><i class="fal fa-file-check fa-lg" style="font-size: 40px"></i></center>
											<div class="push20"></div> 
											<p class="folder"><?php echo $qrBuscaModulos['NOM_ORGANOGRAMA']; ?></p>
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