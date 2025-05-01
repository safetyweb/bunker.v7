<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
//fnEscreve($buscaAjx2);
?>
								<?php 
									$sql = "SELECT  CONVENIO.COD_CONVENI,
													CONVENIO.COD_EMPRESA,
													CONVENIO.COD_ENTIDAD,
													CONVENIO.NUM_PROCESS,
													CONVENIO.NUM_CONVENI,
													CONVENIO.NOM_CONVENI,
													CONVENIO.NOM_ABREVIA,
													CONVENIO.DES_DESCRIC,
													CONVENIO.VAL_VALOR,
													CONVENIO.VAL_CONTPAR,
													CONVENIO.DAT_INICINV,
													CONVENIO.DAT_FIMCONV,
													CONVENIO.DAT_ASSINAT,
													EMPRESAS.NOM_EMPRESA,
													ENTIDADE.NOM_ENTIDAD 
										FROM CONVENIO
											LEFT JOIN $connAdm->DB.empresas ON CONVENIO.COD_EMPRESA = empresas.COD_EMPRESA
											LEFT JOIN ENTIDADE ON CONVENIO.COD_ENTIDAD = ENTIDADE.COD_ENTIDAD
										WHERE empresas.COD_EMPRESA = $buscaAjx1
										ORDER BY COD_CONVENI";
										
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										?>	
										
										<div class="col-md-2 item-busca">  
											<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
												<a data-url="action.php?mod=<?php echo fnEncode(1097)?>&id=<?php echo fnEncode($buscaAjx1)?>&idC=<?php echo fnEncode($qrBuscaModulos['COD_CONVENI']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Template" class="informer informer-default addBox" style="color: #2c3e50;">
													<span class="fa fa-edit"></span>
												</a>
												<a href='action.php?mod=<?php echo fnEncode(1098)?>&id=<?php echo fnEncode($buscaAjx1)?>&idC=<?php echo fnEncode($qrBuscaModulos['COD_CONVENI'])?>' style='color: #2c3e50; border: none; text-decoration: none;'>
													<i class="fa fa-folder fa-lg" style="font-size: 40px"></i>
													<i class="fa fa-folder-open fa-lg" style="font-size: 40px"></i>
													<p class="folder referencia-busca"><?php echo $qrBuscaModulos['NOM_CONVENI']; ?></p>
												</a>
											</div> 										
										</div>

								<?php			
										  }											
								?>			