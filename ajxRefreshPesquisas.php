<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));

?>
			<?php 
				$sql = "SELECT  * FROM PESQUISA WHERE cod_empresa = $buscaAjx1 AND COD_CAMPANHA = $cod_campanha order by DES_PESQUISA";
						
				$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql) or die(mysqli_error());
				
				$count=0;
				while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;	
					?>
					<div class="col-md-2">  
						<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
							<a data-url="action.php?mod=<?php echo fnEncode(1255)?>&id=<?php echo fnEncode($buscaAjx1)?>&idP=<?php echo fnEncode($qrBuscaModulos['COD_PESQUISA']); ?>&idc=<?=fnEncode($cod_campanha)?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Template" class="informer informer-default addBox" style="color: #2c3e50;">
								<span class="fa fa-edit"></span>
							</a>
							<a href='action.php?mod=<?php echo fnEncode(1510)?>&id=<?php echo fnEncode($buscaAjx1)?>&idP=<?php echo fnEncode($qrBuscaModulos['COD_PESQUISA'])?>&idc=<?=fnEncode($cod_campanha)?>' style='color: #2c3e50; border: none; text-decoration: none;'>
								<i class="fa fa-list fa-lg" style="font-size: 40px"></i>
								<i class="fa fa-list fa-lg" style="font-size: 40px"></i>
								<p class="folder"><?php echo $qrBuscaModulos['DES_PESQUISA']; ?></p>
							</a>
						</div> 										
					</div>

			<?php			
					  }											
			?>			
<script language=javascript> 
$(".chosen-select-deselect").chosen({allow_single_deselect:true});
</script> 