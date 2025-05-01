<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$u = "";
$msgTipo = "";
$msgRetorno = "";
$q = "";
$url = "";
$abaTour = "";
$filtro = "";
$val_pesquisa = "";
$esconde = "";
$hashLocal = "";
$arrayQuery = [];
$qrBuscaModulos = "";
$sqlTour = "";
$array = [];
$qrBusca = "";
$checked = "";

	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		if (@$_POST["URL"] <> ""){
			$u = parse_url(@$_POST["URL"]);
			if (@$u["query"] == ""){
				$msgTipo="alert-danger";
				$msgRetorno="URL inválida!";
			}else{
				parse_str(@$u["query"], $q);
				if (@$q["mod"] == ""){
					$msgTipo="alert-danger";
					$msgRetorno="Parâmetro 'mod' não encontrado!";
				}else{
					$url = "action.php?mod=".fnEncode(1794)."&id=".$q["mod"];
					header("Location: $url");
					echo "<script>window.location='$url';</script>";
					exit;
				}
			}
		}
	}
?>
<style>
	table a:not(.btn), .table a:not(.btn) {
		text-decoration: none;
	}
	table a:not(.btn):hover, .table a:not(.btn):hover {
		text-decoration: underline;
	}
</style>
	

	<div class="push30"></div> 

		<div class="col-md12 margin-bottom-30">
		
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
					</div>
					<?php include "atalhosPortlet.php"; ?>
				</div>
				<div class="portlet-body">
					
					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
					<?php } ?>

					<?php 

						$abaTour = fnDecode(@$_GET['mod']);

						echo ('<div class="push20"></div>');
						include "abasTour.php";

					?>
						
					<form data-toggle="validator" role="form_url" method="post" id="form_url" action="<?php echo $cmdPage; ?>">
					
				    <div class="login-form">

				    	<div class="push20"></div>

						<fieldset>
							<legend>Filtros</legend> 

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Url</label>
										<input type="text" class="form-control input-sm" name="URL" id="URL" value="<?=@$_POST["URL"]?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="LOAD_URL" id="LOAD_URL" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fal fa-link" aria-hidden="true"></i>&nbsp; Acessar</button>
								</div>					

							</div>

						</fieldset>
					
					</form>
					
					</div>
            
				</div>
				
			</div>
		
		</div>

		<div class="push10"></div>

            <div class="portlet portlet-bordered">

                     <div class="portlet-body">

                        <div class="login-form">

                    <div class="push20"></div>

                    <div>
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="no-more-tables">
				
				
				
				
					<div class="login-form">
						
						<div class="push30"></div>

						<div class="row">
							<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

								<div class="col-xs-4 col-xs-offset-4">
									<div class="input-group activeItem">
										<div class="input-group-btn search-panel">
											<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
												<span id="search_concept">Sem filtro</span>&nbsp;
												<span class="far fa-angle-down"></span>										                    	
											</button>
											<ul class="dropdown-menu" role="menu">
												<li class="divisor"><a href="#">Sem filtro</a></li>
												<!-- <li class="divider"></li> -->
												<!-- <li><a href="#DES_PRODUTO">Nome do Produto</a></li>
												<li><a href="#COD_EXTERNO">Código Externo</a></li> -->									                      
											</ul>
										</div>
										<input type="hidden" name="VAL_PESQUISA" value="<?=$filtro?>" id="VAL_PESQUISA">         
										<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
										<div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
											<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										</div>
										<div class="input-group-btn">
											<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										</div>
									</div>
								</div>
									
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							</form>
							
						</div>

						<div class="push30"></div>
						
						<div class="col-lg-12">

							<div class="no-more-tables">
						
								<form name="formLista">
								
								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
									<thead>
									<tr>
										<th class="{ sorter: false }"></th>
										<th>Código</th>
										<th>Nome do Menu</th>
										<th>Aliás</th>
										<th class="{ sorter: false }">Tour</th>
									</tr>
									</thead>
								<tbody>
									
								<?php 
								
									$sql = "select * from modulos order by DES_MODULOS";
									$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
										{	

										$sqlTour = "select * from tour where COD_MODULOS = ".$qrBuscaModulos['COD_MODULOS'];
										$array = mysqli_query($connAdm->connAdm(), $sqlTour);
										$qrBusca = mysqli_fetch_assoc($array);													  
										$count++;

										if($qrBusca){
											$checked = "<i class='fal fa-check' aria-hidden='true'></i>";
										}else{
											$checked = "";
										}

										echo"
											<tr>
												<td class='text-center' ><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
												<td>".$qrBuscaModulos['COD_MODULOS']."</td>
												<td>".$qrBuscaModulos['DES_MODULOS']."</td>
												<td>".$qrBuscaModulos['NOM_MODULOS']."</td>
												<td align='center'>"
												.$checked.
												"</td>
											</tr>
											<input type='hidden' id='ret_ID_".$count."' value='".fnEncode($qrBuscaModulos['COD_MODULOS'])."'>
											"; 
											}											

								?>
									
								</tbody>
								</table>
								
								</form>

							</div>
							
						</div>										
					
					<div class="push"></div>
					
					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>					
		
	<div class="push20"></div> 
		
		<script type="text/javascript">

			//Barra de pesquisa essentials ------------------------------------------------------
			$(document).ready(function(e){
				var value = $('#INPUT').val().toLowerCase().trim();
				if(value){
					$('#CLEARDIV').show();
				}else{
					$('#CLEARDIV').hide();
				}
				$('.search-panel .dropdown-menu').find('a').click(function(e) {
					e.preventDefault();
					var param = $(this).attr("href").replace("#","");
					var concept = $(this).text();
					$('.search-panel span#search_concept').text(concept);
					$('.input-group #VAL_PESQUISA').val(param);
					$('#INPUT').focus();
				});

				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
					$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
				});

				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
					$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
				});

				$('#CLEAR').click(function(){
					$('#INPUT').val('');
					$('#INPUT').focus();
					$('#CLEARDIV').hide();
					if("<?=$filtro?>" != ""){
						location.reload();
					}else{
						var value = $('#INPUT').val().toLowerCase().trim();
						if(value){
							$('#CLEARDIV').show();
						}else{
							$('#CLEARDIV').hide();
						}
						$(".buscavel tr").each(function (index) {
							if (!index) return;
							$(this).find("td").each(function () {
								var id = $(this).text().toLowerCase().trim();
								var sem_registro = (id.indexOf(value) == -1);
								$(this).closest('tr').toggle(!sem_registro);
								return sem_registro;
							});
						});
					}
				});

				// $('#SEARCH').click(function(){
				// 	$('#formulario').submit();
				// });
					
				
			});

			function buscaRegistro(el){
				var filtro = $('#search_concept').text().toLowerCase();

				if(filtro == "sem filtro"){
					var value = $(el).val().toLowerCase().trim();
					if(value){
						$('#CLEARDIV').show();
					}else{
						$('#CLEARDIV').hide();
					}
					$(".buscavel tr").each(function (index) {
						if (!index) return;
						$(this).find("td").each(function () {
							var id = $(this).text().toLowerCase().trim();
							var sem_registro = (id.indexOf(value) == -1);
							$(this).closest('tr').toggle(!sem_registro);
							return sem_registro;
						});
					});
				}
			}

		//-----------------------------------------------------------------------------------
		
			$(document).ready(function(){

				//chosen
				$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
				$('#formulario').validator();
				
			});	
			
			function retornaForm(index){
				url = "action.php?mod=<?=fnEncode(1794)?>&id="+$("#ret_ID_"+index).val();
				window.location=url;
			}
			
		</script>
