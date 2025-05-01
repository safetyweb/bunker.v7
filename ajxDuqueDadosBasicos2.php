<?php include "_system/_functionsMain.php"; 

echo fnDebug('true');

$buscaAjx1 = $_GET['ajx1'];
$buscaAjx2 = $_GET['ajx2'];
$buscaAjx3 = $_GET['ajx3'];
$cod_empresa = $buscaAjx1;

//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);
//fnEscreve($buscaAjx3);

?>

	<div class="push10"></div>
	
	<h3 style="margin: 0 0 0 20px;">Postos Autorizados</h3>

	<div class="push20"></div>
	
	<?php
	$sql = "select a.COD_UNIVEND,b.NOM_UNIVEND from  grupo_posto a, webtools.unidadevenda b
			where a.cod_univend=b.cod_univend and
				  a.cod_grupo = $buscaAjx2
			order by b.nom_univend ";	
			
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());	
	while ($qrListaPostos = mysqli_fetch_assoc($arrayQuery))
	  {
	?>
	
	<div class="col-md-2 text-center" style="height: 140px;"> 
		<i class="fa fa-map-marker fa-2x" aria-hidden="true"></i> 
		<div class="push10"></div>
		<small> <?php echo $qrListaPostos['NOM_UNIVEND']; ?> </small>
		<div class="push10"></div> 
		<a href="#" onclick="carrega3(<?php echo $qrListaPostos['COD_UNIVEND']; ?>);" class="btn btn-default btn-sm" style="width: 50px;"><i class="fa fa-usd" aria-hidden="true"></i></a>
	</div>
	
	<?php	
	}	
	?>
	
	
	<script language=javascript> 
	$(".chosen-select-deselect").chosen({allow_single_deselect:true});
	//$("#COD_SUBCATE").val(<?php echo $buscaAjx2 ?>).trigger("chosen:updated");
	
		
		function carrega3(posto){
			
			//alert(p);

			var idEmp = <?php echo $cod_empresa; ?>;
			var idGrupo = $('#COD_GRUPO').val();
			var idEnt = $('#COD_ENTIDAD').val();
			
			$.ajax({
				type: "GET",
				url: "ajxDuqueDadosBasicos3.php",
				data: { ajx1:idEmp, ajx2:idGrupo, ajx3:idEnt, ajx4:posto},
				beforeSend:function(){
					$('#div_basicos3').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_basicos3").html(data); 
				},
				error:function(){
					$('#div_basicos3').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});				
			
		} 	
	
	</script>
	
	<div class="push30"></div>
	
	<div id="div_basicos3">
	
	
	</div>