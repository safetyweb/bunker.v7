<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
//$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
//fnEscreve($buscaAjx1);

$dbmane=$connGERADOR->DB=$buscaAjx1;

?>

	<div class="row">
		
		<div  class="col-sm-12">

			<div class="col-xs-1"> <!-- required for floating -->
			  <!-- Nav tabs -->
			  <ul class="vTab nav nav-tabs tabs-left text-center">
				<li class="active vTab text-center"><a href="#abaColunas" data-toggle="tab">
				<i class="fa fa-table" style="margin: 10px 0 2px 0"></i><br/></a></li>
				<li class="vTab text-center disabled"><a href="#abaForm" data-toggle="tab">
				<i class="fa fa-file-text-o" style="margin: 10px 0 2px 0"></i><br/></a></li>
				<li class="vTab text-center disabled"><a href="#itens" data-toggle="tab">
				<i class="fa fa-code" style="margin: 10px 0 2px 0"></i><br/></a></li>
				<li class="vTab text-center disabled"><a href="#aniversario" data-toggle="tab">
				<i class="fa fa-database" style="margin: 10px 0 2px 0"></i><br/></a></li>
			  </ul>
			</div>
			
			<div class="col-xs-11">
			  <!-- Tab panes -->
			  <div class="tab-content">
				<div class="tab-pane active" id="abaColunas" style="padding: 0 20px 0 20px;">
					<h4 style="margin: 0 0 5px 0;">Colunas da Tabela </h4>
											
					<div class="row" style="padding: 20px;">
					
						<div class="col-md-2">   
							<div class="form-group">
								<label for="inputName" class="control-label">Tela com Lista</label> 
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="LISTA" id="LISTA" class="switch" value="S" >
									<span></span>
									</label>
							</div>
						</div>

						<div class="push10"></div>						
					
						<?php
						$sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS   
								where   TABLE_SCHEMA = '".$buscaAjx1."' and TABLE_NAME = '".$buscaAjx2."'";
						$arrayQuery = mysqli_query($connGERADOR->connGERADOR(),$sql) or die(mysqli_error());
						//fnEscreve($sql);
						?>	                                              

						<table class="table table-striped" style="margin-bottom: 0;">
							
							<thead>
							<tr>
							<th></th>
							<th>Coluna</th>
							<th>Tipo/Tam./Def.</th>
							<th>Default</th>
							<th>Lista</th>
							<th>Título</th>
							</tr>
							</thead>
						
							<tbody>
							<?php
							$valor=0;
							while ($qrListaTabelas = mysqli_fetch_assoc($arrayQuery))
							{														

							?>
							<tr>
								<td><input type="checkbox" style="height: 18px; width:18px;" value="<?php echo $qrListaTabelas['COLUMN_NAME']; ?>" name="colCampo[]"/> &nbsp; </td>
								<td><?php echo $qrListaTabelas['COLUMN_NAME']; ?></td>
								<td><?php echo $qrListaTabelas['DATA_TYPE'];?> | <?php echo $qrListaTabelas['CHARACTER_MAXIMUM_LENGTH']; ?> | <?php echo $qrListaTabelas['COLUMN_DEFAULT']; ?></td>
								<td>
									<select style="width:80px;" name="colTipo[]">
										<option value=""></option>
										<option value="text">texto</option>
										<option value="select">combo</option>
										<option value="radio">radio</option>
										<option value="check">check</option>
										<option value="password">senha</option>
									</select>
								</td>
								<td><input type="checkbox" style="height: 18px; width:18px;" value="" name="colCombo[]" multiple="multiple"/> &nbsp; </td>
								<td><div style="min-width: 80px; min-height: 25px; border-bottom: 1px solid #cecece; cursor:text;" contentEditable="true"></div></td>
							</tr>

							<?php
							$valor++;    
							}?>
							</tbody>
						
						</table>													
													
					</div>
				
				</div>
				
				<div class="tab-pane" id="abaForm">Profile Tab.</div>
				<div class="tab-pane" id="abaCodigos">Messages Tab.</div>
				<div class="tab-pane" id="abaBD">Settings Tab.</div>
			  </div>
			</div>

			<div class="clearfix"></div>

		</div>

		<div class="push10"></div>
		<hr>	
		<div class="form-group text-right col-lg-12">
			
			  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
			  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-code" aria-hidden="true"></i>&nbsp; Gerar Código</button>
			
		</div>

		<input type="hidden" name="opcao" id="opcao" value="">
		<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
		<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		


		<div class="push50"></div>
		<div class="push"></div>

	</div>

	<script>	

		$(document).ready(function() {
			
			$(".disabled").click(function (e) {
					e.preventDefault();
					return false;
			});
			
		});

	</script>	