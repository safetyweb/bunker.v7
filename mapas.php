<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
echo fnDebug('true');
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	$refresh = "N";

	$tipo = fnDecode(@$_GET['tipo']);	
	$cod_empresa = fnDecode(@$_GET['id']);	
	$sql="SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
		  FROM empresas WHERE COD_EMPRESA=$cod_empresa";
	$qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sql)));
	
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$popup = @$_GET["pop"];
	
	
	$cod_mapa = "";
	if (@$_GET["idT"] <> ""){
		$cod_mapa = fnDecode(@$_GET["idT"]);
		$sql="SELECT * FROM MAPAS WHERE COD_EMPRESA=$cod_empresa AND COD_MAPA=$cod_mapa";
		$qrBusca = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),trim($sql)));

		$cod_mapa = fnLimpacampoZero($qrBusca['COD_MAPA']);
		$nom_mapa = fnLimpacampo($qrBusca['NOM_MAPA']);
		$log_pessoas = fnLimpacampo($qrBusca['LOG_PESSOAS']);
		$log_unidades = fnLimpacampo($qrBusca['LOG_UNIDADES']);
		$des_mapa_tipos = explode(",","0".fnLimpacampo($qrBusca['DES_MAPA_TIPOS']));
	}else{
		$des_mapa_tipos = explode(",","0");
	}
	


      
	//fnMostraForm();
?>
<style>
html,body{
	overflow-x: hidden !important;
}

table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}

.change-icon .fa + .fa,
.change-icon:hover .fa:not(.fa-edit) {
  display: none;
}
.change-icon:hover .fa + .fa:not(.fa-edit){
  display: inherit;
}

.fa-edit:hover{
	color: #18bc9c;
	cursor: pointer;
}

.item{
	padding-top: 0;
}



</style>			

	
	<div class="row">
			
			<?php if ($tipo == "MAP"){ ?>

					<?php include("mapasDados.php") ?>
					
			<?php }elseif ($tipo == "ALT" || $tipo == "CAD"){ ?>

					<?php include("mapasCadastro.php") ?>

			<?php }elseif ($tipo == "IMP"){ ?>

					<?php include("mapasImport.php") ?>

			<?php }else{ ?>
		
				<div class="col-md12 margin-bottom-30 portlet portlet-bordered">
					<!-- Portlet -->
					<div class="portlet-title">
						<div class="caption">
							<i class="fas fa-map-marker-alt"></i>
							<span class="text-primary"> <?php echo $NomePg; ?></span>
						</div>
						<?php
						include "atalhosPortlet.php";
						?>
					</div>
					<div class="portlet-body">
					
						<div class="col-md-2">

							<div class="panelBox borda">
							
							<div class="addBox" data-url="action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&tipo=<?=fnEncode('CAD')?>&pop=true" data-title="Mapa">
							<i class="fa fa-plus fa-2x" aria-hidden="true" style="margin: 55px 0 60px 0;"></i>
							</div>											
							</div> 
							
						</div>
						
						<div id="listaMapas">
						<?php 
							$sql = "SELECT  * FROM MAPAS WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_MAPA";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
							
							$count=0;
							while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
							  {														  
								$count++;	
								?>
								
								<div class="col-md-2">  
									<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
										<a data-url="action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($qrBuscaModulos['COD_MAPA']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Mapa" class="informer informer-default addBox" style="color: #2c3e50;">
											<span class="fa fa-edit"></span>
										</a>
										<a href='action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($qrBuscaModulos['COD_MAPA'])?>&tipo=<?=fnEncode('MAP')?>' style='color: #2c3e50; border: none; text-decoration: none; text-align:center;'>
											<div class="push30"></div> 
											<center><i class="fas fa-map-marked-alt fa-lg" style="font-size: 40px"></i></center>
											<div class="push20"></div> 
											<p class="folder"><?php echo $qrBuscaModulos['NOM_MAPA']; ?></p>
										</a>
									</div> 										
								</div>					
						<?php			
								  }											
						?>
						</div>
					</div>
					
					<div class="push20"></div> 
				</div>
				
			<?php } ?>

		<input type="hidden" class="input-sm" name="REFRESH" id="REFRESH" value="<?=$refresh?>">
	</div>					
		
	<div class="push20"></div> 





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
	
	
	

	<script type="text/javascript">

		
		$(document).ready(function(){

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
			  console.log($("#REFRESH").val());
			  if ($("#REFRESH").val() == "S"){
				RefreshMapas("<?=$cod_empresa?>");
				$('#REFRESH').val("N");				
			  }
			});
			
		});	
		
		
		function RefreshMapas(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshMapas.php",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#listaMapas').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#listaMapas").html(data); 
				},
				error:function(){
					$('#listaMapas').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}

		function RefresListaEnderecos(idEmp,idMapa) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshMapaListaEnderecos.php",
				data: { ajx1:idEmp,ajx2:idMapa},
				beforeSend:function(){
					$('#listaEnderecos').html('<td colspan=100 class="loading" style="width: 100%;"></td>');
				},
				success:function(data){
					$("#listaEnderecos").html(data);
				},
				error:function(){
					$('#listaEnderecos').html('<td colspan=100 class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</td>');
				}
			});		
		}	

	</script>