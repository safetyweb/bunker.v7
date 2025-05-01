<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$buscaAjx1 = $_GET['ajx1'];
$buscaAjx2 = $_GET['ajx2'];
$cod_empresa = $buscaAjx1;

//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);

//busca dados da entidade do cliente
$sql1 = "SELECT COD_ENTIDAD FROM clientes WHERE COD_CLIENTE = $buscaAjx2 and COD_EMPRESA = $buscaAjx1";
//fnEscreve($sql1);
$qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql1));
//fnTestesql(connTemp($cod_empresa,''),$sql1);		
$cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];

//busca nome da entidade
$sql3="select NOM_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql3));		
//fnEscreve($sql3);	
$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];

//fnEscreve($cod_empresa);	
//fnEscreve($$buscaAjx2);	
//fnEscreve($nom_entidad);	

?>

	<div class="col-md-3">
		<div class="form-group">
			<label for="inputName" class="control-label required">Empresa Conveniada</label>
			<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_ENTIDAD" id="NOM_ENTIDAD" value="<?php echo $nom_entidad; ?>">
			<input type="hidden" class="form-control input-sm" name="COD_ENTIDAD" id="COD_ENTIDAD" value="<?php echo $cod_entidad; ?>" required>
		</div>														
	</div>
	
	<div class="push10"></div> 
	
	
	<div class="col-md-3">
		<div class="form-group">
			<label for="inputName" class="control-label required">Grupo de Postos </label>
				<select data-placeholder="Selecione o grupo" name="COD_GRUPO" id="COD_GRUPO" class="chosen-select-deselect requiredChk" required>
					<option value=""></option>					
					<?php 																	
						$sql = "select a.COD_GRUPO,c.NOM_ENTIDAD,a.COD_ENTIDAD,b.NOME from plano a, grupo_plano b, entidade c
								where a.cod_grupo=b.cod_grupo and
									  a.COD_ENTIDAD=c.COD_ENTIDAD and
									  a.COD_ENTIDAD = $cod_entidad ";
									  
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
					
						while ($qrListaGrupo = mysqli_fetch_assoc($arrayQuery))
						  {														
							echo"
								  <option value='".$qrListaGrupo['COD_GRUPO']."'>".$qrListaGrupo['NOME']."</option> 
								"; 
							  }											
					?>	
				</select>
			<div class="help-block with-errors"></div>
		</div>
	</div>
	
	<div class="col-md-1 text-center">
		<div class="push20"></div> 
		<a href="#" onclick="carrega2();"  class="btn btn-primary btn-sm btn-block"><i class="fa fa-refresh" aria-hidden="true"></i></a>
	</div>
	
	<script language=javascript> 
	$(".chosen-select-deselect").chosen({allow_single_deselect:true});
	//$("#COD_SUBCATE").val(<?php echo $buscaAjx2 ?>).trigger("chosen:updated");
	
		
		function carrega2(){
			
			//alert($('#COD_GRUPO').val());

			var idEmp = <?php echo $cod_empresa; ?>;
			var idGrupo = $('#COD_GRUPO').val();
			var idEnt = $('#COD_ENTIDAD').val();
			
			$.ajax({
				type: "GET",
				url: "ajxDuqueDadosBasicos2.php",
				data: { ajx1:idEmp, ajx2:idGrupo, ajx3:idEnt},
				beforeSend:function(){
					$('#div_basicos2').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_basicos2").html(data); 
				},
				error:function(){
					$('#div_basicos2').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});				
			
		} 	
	
	</script>
	
	<div class="push30"></div>
	
	<h3 style="margin: 0 0 0 20px;">Veículos do Motorista</h3>

	<div class="push20"></div>
	
	<?php
	$sql = "select veiculos.DES_PLACA, MARCA.COD_MARCA, veiculos.COD_EXTERNO, MARCA.NOM_MARCA, modelo.NOM_MODELO from veiculos 
			left join MARCA on MARCA.COD_MARCA = veiculos.COD_MARCA
			left join modelo on modelo.COD_MODELO=veiculos.COD_MODELO
			where COD_CLIENTE = $buscaAjx2 ";	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());	
	while ($qrListaVeiculo = mysqli_fetch_assoc($arrayQuery))
	  {
	?>
	
	<div class="col-md-3 text-center"> 
	<i class="fa fa-car fa-3x" aria-hidden="true"></i> 
	<div class="push10"></div>
	<small><b>Placa:</b></small> <?php echo $qrListaVeiculo['DES_PLACA']; ?>	<br/>												
	<small><b>Marca:</b></small> <?php echo $qrListaVeiculo['NOM_MARCA']; ?>	<br/>												
	<small><b>Modelo:</b></small> <?php echo $qrListaVeiculo['NOM_MODELO']; ?>	<br/>												
	</div>
	
	<?php	
	}	
	?>
	
	<div class="push30"></div>
	
	<div id="div_basicos2">
	
	
	</div>
	
	
	
	