<!DOCTYPE html>
<html>
<head>
	<title></title>

	<?php 	
		$css_skin = "bootstrap.flatly.min.css";
		include "../../_system/_functionsMain.php";
	?>
	
	<link href="../../css/<?php echo $css_skin ?>" rel="stylesheet">

	<script src="../../js/jquery.min.js"></script>
	<!-- extras -->
	<link href="../../css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="../../css/chosen-bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../../css/fa5all.css" />
	<link href="../../css/bootstrap.vertical-tabs.css" rel="stylesheet" />
	<!-- complement -->
	<link href="../../css/default.css" rel="stylesheet" />
	<link href="../../css/checkMaster.css" rel="stylesheet" />

	<link rel="stylesheet" href="../../css/widgets.css" />
	<link rel="stylesheet" href="../../css/default.css" />
		
	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="../../js/plugins/ie-emulation-modes-warning.js"></script>
	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="../../images/favicon.ico"/>

</head>

<body>

	<div class="push30"></div>


	
	<div class="row">				
	
		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet">
				<!-- <div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary">Certificações e Módulos da Jornada de Fidelização Marka</span>
					</div>
				</div> -->
				<div class="portlet-body">	
					
					<div class="push20"></div> 
							
<style>
	.change-icon > .fa + .fa,
	.change-icon:hover > .fa {
	  display: none;
	}
	.change-icon:hover > .fa + .fa {
	  display: inherit;
	}
	
	.fa-edit:hover{
		color: #18bc9c;
	}
	
	.item{
		padding-top: 0;
	}
	
	.folder {
		height: 30px;
		font-size: 12px!important;
	}
	
	a, a:hover {
		text-decoration:none;
	}
	
</style>

								<h3 style="margin: 0 0 40px 15px;">Como vamos melhorar os resultados do seu negócio hoje?</h3>

								<?php 
								
									$sql = "SELECT * FROM GRUPOMODULOSMARKA ORDER BY NUM_ORDENAC";
									
									$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());
                                                                      
									$count=0;
									while ($qrBuscaCertificacao = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;
										$cod_looping = $qrBuscaCertificacao['COD_GRUPOMODMK']; 
										?>
										
											<div class="row">
											<!--
												<div class="col-md-1 text-right">
													<div class="push10"></div>
													<i class="fa <?php echo $qrBuscaCertificacao['DES_ICONE']; ?> fa-lg" style="font-size: 50px"></i>
												</div>
											-->	
												<div class="col-md-12">
													<h3 style="margin: 0 0 5px 20px;"><?php echo $qrBuscaCertificacao['NOM_GRUPOMODMK']; ?></h3>
													<h5 style="margin: 0 0 20px 20px;"><?php echo $qrBuscaCertificacao['DES_GRUPOMODMK']; ?></h5>
												</div>
											</div>
										
											<?php 
										
											$sql1 = "select * from MODULOSMARKA where COD_GRUPOMODMK = $cod_looping order by NUM_ORDENAC";
											$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
											
											$count=0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery1))
											  {														  
												$count++;	
												?>
												<div class="col-md-2">  
													
													<div class='tile tile-default shadow change-icon' style='background-color: <?php echo $qrBuscaModulos['DES_COR']; ?>; font-size: 15px;'>		
													<a href="javascript:void(0);" class="addBox" data-url="http://adm.bunker.mk/externo/fidelizacao_marka/extraModulosMarka.do?id=<?php echo $qrBuscaModulos['COD_MODULMK']; ?>" data-title="<?php echo $qrBuscaCertificacao['NOM_GRUPOMODMK']; ?>">&nbsp;<i class="fa fa-plus" style="font-size: 15px; line-height: 4px; color: #fff; float: right; margin: 5px 0 0 0;"></i>&nbsp;</a>
													<div class="push"></div>
													<a href='http://adm.bunker.mk/action.php?mod=bQwf3u7GcvA%C2%A2&id=QunXraEOVrg%C2%A2&idx=4Nu0wviKV1Y%C2%A2' style='color: #fff; border: none' >
													
														<i class="fa <?php echo $qrBuscaModulos['DES_ICONE']; ?> fa-lg" style="font-size: 40px; line-height: 40px; margin-bottom: 25px;"></i>
													
														<p class="folder"><?php echo $qrBuscaModulos['NOM_MODULMK']; ?> </p>
														<p style="font-size: 12px; height: 60px;"><?php echo $qrBuscaModulos['DES_MODULMK']; ?> </p>
													</a> 										
													</div> 										
														
												</div>

												<?php			
												  }											
												?>	
										
										<div class="push30"></div>
										<?php		
										  }											
								?>
							
						</div>										
					
					<div class="push50"></div>
					
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
					<iframe frameborder="0" style="width: 90%; height: 80%"></iframe>
				</div>		
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script src="../../js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/main.js" type="text/javascript"></script>
		
	<div class="push20"></div>

</body>
</html> 
	
	<script type="text/javascript">

		$(function(){
			$(".addBox").click(function(){
				$('#popModal').find('.modal-content').css({
		              'width':'1200px',
		              'height':'90vh',
		              'marginLeft':'auto',
		              'marginRight':'auto'
		        });
		        $('#popModal').find('.modal-body').css({
		              'width':'1200px',
		              'height':'89vh',
		              'marginLeft':'auto',
		              'marginRight':'auto'
		        });
				$('#popModal').find('.modal-dialog').css({
					  'maxWidth':'100vw'
		       	});
			});
		});
	
		function retornaForm(index){
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_"+index).val());
			$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_"+index).val());
			$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_"+index).val());
			$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_"+index).val());
			$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_"+index).val());
			$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_"+index).val());
			$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_"+index).val());
			$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_"+index).val());
			$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	