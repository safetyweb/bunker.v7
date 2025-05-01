<?php

	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;
			
			$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
			$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
			$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
			$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
			$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
			$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
			
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			}  
			

		}
	}
    
	//busca dados da url	
    if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            //busca dados da empresa
            $cod_empresa = fnDecode($_GET['id']);	
            $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
            //fnEscreve($sql);
            $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
            $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

            if (isset($arrayQuery)){
                    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
                    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
                    $cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
            }

    }else {
            $cod_empresa = 0;
           // $codEmpresa = $qrBuscaEmpresa['COD_SISTEMA'];

    }
	
	//Busca módulos autorizados
	$sql = "SELECT COD_PERFILS FROM usuarios WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
	$qrPfl = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

	$sqlAut = "SELECT COD_MODULOS FROM perfil WHERE
			   COD_SISTEMA = 4 
			   AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
	$qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlAut));

	$modsAutorizados = explode(",", $qrAut['COD_MODULOS']);
	
	//liberação das abas
	$abaPersona	= "S";
	$abaCampanha = "S";
	$abaVantagem = "N";
	$abaRegras = "N";
	$abaComunica = "N";
	$abaAtivacao = "N";
	$abaResultado = "N";

	$abaPersonaComp = "";
	$abaCampanhaComp = "active";
	$abaVantagemComp = "";
	$abaRegrasComp = "";
	$abaComunicaComp = "";
	$abaResultadoComp = "";
	//revalidada na aba de regras	
	$abaAtivacaoComp = "";
	
	// esquema do X da barra - (recarregar pesquisa)
	if($val_pesquisa != ""){
		$esconde = " ";
	}else{
		$esconde = "display: none;";
	}
	//fnMostraForm();
	//fnEscreve("QunXraEOVrg¢");

?>

<style>
	.fa-1dot5x{
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
	}
	.tile{
		border: none!important;
	}
</style>

<link rel="stylesheet" href="css/widgets.css" />
			
					<div class="push30"></div> 
						
					<!-- Portlet -->
					<div class="portlet portlet-bordered">
						
						<div class="portlet-title">
							<div class="caption">
								<i class="far fa-terminal"></i>
								<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
							</div>
							
							<?php 
							$formBack = "1048";
							include "atalhosPortlet.php"; ?>
							
						</div>								
							
						<div class="push10"></div> 
						
						<div class="portlet-body">
							
							<?php if ($msgRetorno <> '') { ?>	
							<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							 <?php echo $msgRetorno; ?>
							</div>
							<?php } ?>
									
								
							<div class="push30"></div>
								
		
								<div class="row">
								
									<h3 style="margin: 0 0 20px 15px;">Potencialize os resultados ao editar ou criar <strong>Campanhas</strong></h3>
									
									<div class="col-md-3">
									
										<a class="btn btn-info btn-block addBox" data-url="action.do?mod=<?php echo fnEncode(1889)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Campanha / <?php echo $nom_empresa; ?>"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Criar Nova Campanha</a>
																				
									</div>								
								
									<div class="push20"></div>
										
									<!-- <a name="campanha"/> -->

									<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
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
										                   <!--  <li><a href="#NOM_EMPRESA">Razão social</a></li>
										                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
										                    <li><a href="#NUM_CGCECPF">CNPJ</a></li> -->										                      
									                    </ul>
									                </div>
									                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">         
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

									<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

									<div class="col-md-2">   
										<div class="form-group">
											<label for="inputName" class="control-label">Somente minhas campanhas</label> 
											<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_PERSONASUSU" id="LOG_CAMPUSU" class="switch" value="S" onchange='RefreshCampanha("<?=fnEncode($cod_empresa)?>")'>
											<span></span>
										</label>
										</div>
									</div>

									<div class="col-md-2">   
										<div class="form-group">
											<label for="inputName" class="control-label">Somente campanhas na validade</label> 
											<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_PERSONASUSU" id="LOG_CAMPVALIDA" class="switch" value="S" onchange='RefreshCampanha("<?=fnEncode($cod_empresa)?>")'>
											<span></span>
										</label>
										</div>
									</div>
								
									<div class="col-md-12">									
									
										<table class="table table-bordered table-striped table-hover tablesorter buscavel">
										  <thead>
											<tr>
											  <th>Nome da Campanha</th>
											  <th class="text-center">Unidade</th>
											  <th>Tipo Campanha</th>
											  <th>Usuário Cad.</th>
											  <th class="text-center {sorter:false}">Ativa</th>
											  <th class="text-center {sorter:false}">Live Data</th>
											  <th>Data de Criação</th>
											  <th>Última Alteração</th>
											  <th>Expira Em</th>
											  <th class="{sorter:false}"></th>
											  <!-- <th class="{sorter:false}"></th> -->
											</tr>
										  </thead>
										  
										<tbody id="div_refreshCampanha">											
									
										<?php

											$ARRAY_UNIDADE1=array(
														   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
														   'cod_empresa'=>$cod_empresa,
														   'conntadm'=>$connAdm->connAdm(),
														   'IN'=>'N',
														   'nomecampo'=>'',
														   'conntemp'=>'',
														   'SQLIN'=> ""   
														   );
											$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

											$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

											$sql = "SELECT A.*, B.NOM_TPCAMPA, B.COD_TPCAMPA, C.NOM_USUARIO, D.NOM_FANTASI,
													IFNULL((SELECT B.NUM_PESSOAS FROM CAMPANHAREGRA B where B.COD_CAMPANHA = A.COD_CAMPANHA),0) as NUM_PESSOAS
													FROM CAMPANHA A
													LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON B.COD_TPCAMPA = A.TIP_CAMPANHA
													LEFT JOIN WEBTOOLS.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
													LEFT JOIN WEBTOOLS.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
													WHERE A.COD_EMPRESA = $cod_empresa 
													AND A.COD_EXCLUSA = 0
													order by A.DES_CAMPANHA ";
											//fnEscreve($sql);
											//echo($sql);
											
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

											if(fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='1'){
												$CarregaMaster = '1';
											} else {
												$CarregaMaster = '0';
											}
											
											$count=0;
											while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery)){	                                           
												$count++; 
												
												if ($qrListaCampanha['LOG_ATIVO'] == "S"){$campanhaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";}
												else {$campanhaAtivo = "";}
												
												if ($qrListaCampanha['LOG_ATUALIZA'] == "S"){$campanhaAtualiza = "<i class='fal fa-check' aria-hidden='true'></i>";}
												else {$campanhaAtualiza = "";}

												if ($qrListaCampanha['COD_TPCAMPA'] == 21){
													$mod = 1169;
												}
												else {
													$mod = 1022;
												}

												$dat_expira = $qrListaCampanha['DAT_FIM']." ".$qrListaCampanha['HOR_FIM'];

												if($dat_expira < date('Y-m-d H:i:s')){
													$cor = "text-danger";
												}else{
													$cor = "";
												}

												$nomeLoja = $qrListaCampanha['NOM_FANTASI'];

												if ($qrListaCampanha['cod_univend'] == 9999){
														$nomeLoja = "Todas";
												}

												if($CarregaMaster == '1'){

													$lojaLoop = $qrListaCampanha['cod_univend'];
													
													
												if ($qrListaCampanha['LOG_CONTINU'] == "S"){
													$fimCampanha = "Contínua";
													$cor = "";
													}
												else{ 
													$fimCampanha = fnDataFull($dat_expira);
													}
													
												
												
										?>

													<tr>
													  <td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaCampanha['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaCampanha['DES_CAMPANHA'];; ?></td>
													  <td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
													  <td><small><?php echo $qrListaCampanha['NOM_TPCAMPA']; ?></td>
													  <td><small><?php echo $qrListaCampanha['NOM_USUARIO']; ?></td>
													  <td class='text-center'><?php echo $campanhaAtivo; ?></td>
													  <td class='text-center'><?php echo $campanhaAtualiza; ?></td>
													  <td><small><?php echo fnDataFull($qrListaCampanha['DAT_CADASTR']); ?></td>
													  <td><small><?php echo fnDataFull($qrListaCampanha['DAT_ALTERAC']); ?></td>
													  <td class="<?=$cor?>"><small><?php echo $fimCampanha; ?></td>
													  <?php if(fnControlaAcesso("1600",$modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
													  <td></td>
										           	  <?php }else{ ?>
										           		<td class="text-center">
											           		<small>
											           			<div class="btn-group dropdown dropleft">
																	<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		ações &nbsp;
																		<span class="fas fa-caret-down"></span>
																    </button>
																	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																		<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1889)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA'])?>&pop=true" data-title="Campanha / <?php echo $qrListaCampanha['DES_CAMPANHA']; ?>">Editar </a></li>
																		<li><a href="action.do?mod=<?php echo fnEncode($mod);?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']); ?>&idt=<?php echo fnEncode($qrListaCampanha[COD_TPCAMPA]); ?>">Acessar </a></li>
																		<li class="divider"></li>
																		<li><a href="javascript:void(0)" onclick='excluiCampanha("<?=fnEncode($cod_empresa)?>","<?=fnEncode($qrListaCampanha[COD_CAMPANHA])?>","<?=$qrListaCampanha[DES_CAMPANHA]?>")'>Excluir </a></li>
																		<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																	</ul>
																</div>
											           		</small>
											           	</td>
										              <?php } ?>
													</tr>

										<?php
												}else{

													if(recursive_array_search($qrListaCampanha['cod_univend'],$arrayAutorizado) !== false){
										?>

														<tr>
														  <td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaCampanha['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaCampanha['DES_CAMPANHA']; ?></td>
														  <td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
														  <td><small><?php echo $qrListaCampanha['NOM_TPCAMPA']; ?></td>
														  	<td><small><?php echo $qrListaCampanha['NOM_USUARIO']; ?></td>
														  <td class='text-center'><?php echo $campanhaAtivo; ?></td>
														  <td class='text-center'><?php echo $campanhaAtualiza; ?></td>
														  <td><small><?php echo fnDataFull($qrListaCampanha['DAT_CADASTR']); ?></td>
														  <td><small><?php echo fnDataFull($qrListaCampanha['DAT_ALTERAC']); ?></td>
														  <td class="<?=$cor?>"><small><?php echo $fimCampanha; ?></td>
														  <?php if(fnControlaAcesso("1600",$modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
														  <td></td>
											           	  <?php }else{ ?>
											           		<td class="text-center">
												           		<small>
												           			<div class="btn-group dropdown dropleft">
																		<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			ações &nbsp;
																			<span class="fas fa-caret-down"></span>
																	    </button>
																		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																			<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1889)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA'])?>&pop=true" data-title="Campanha / <?php echo $qrListaCampanha['DES_CAMPANHA']; ?>">Editar </a></li>
																			<li><a href="action.do?mod=<?php echo fnEncode($mod);?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']); ?>&idt=<?php echo fnEncode($qrListaCampanha[COD_TPCAMPA]); ?>">Acessar </a></li>
																			<li class="divider"></li>
																			<li><a href="javascript:void(0)" onclick='excluiCampanha("<?=fnEncode($cod_empresa)?>","<?=fnEncode($qrListaCampanha[COD_CAMPANHA])?>","<?=$qrListaCampanha[DES_CAMPANHA]?>")'>Excluir </a></li>
																			<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																		</ul>
																	</div>
												           		</small>
												           	</td>
											              <?php } ?>
														</tr>

										<?php			
													}	
												}	
											}									
										
										?>
											
										</tbody>
										</table>
										
									</div>										
								
									<div class="push30"></div>
											
									
								</div>
									
								<div class="push10"></div>
											
							</div>
							
						</div><!-- fim Portlet body -->
				
					</div><!-- fim Portlet  -->							
	
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
						
					<div class="push20"></div>

					<form id="formModal">					
						<input type="hidden" class="input-sm" name="REFRESH_CAMPANHA" id="REFRESH_CAMPANHA" value="N"> 
						<input type="hidden" class="input-sm" name="REFRESH_PERSONA" id="REFRESH_PERSONA" value="N"> 					
					</form>
	
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
			
			//modal close
			$('#popModal').on('hidden.bs.modal', function () {
			  
			  if ($('#REFRESH_PERSONA').val() == "S"){
				//alert("atualiza");
				RefreshPersona("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_PERSONA').val("N");				
			  }	
			  
			  if ($('#REFRESH_CAMPANHA').val() == "S"){
				//alert("atualiza");
				RefreshCampanha("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_CAMPANHA').val("N");				
			  }
			  
			});
			
		});	

		function RefreshPersona(idEmp) {

			if(tipo == 'Arquivadas'){
				log_ativo = 'N';
			}else{
				log_ativo = 'S';
			}

			$.ajax({
				type: "GET",
				url: "ajxRefreshPersona.do",
				data: { ajx1:idEmp},
				beforeSend:function(){
					// alert(idEmp);
					$('#div_refreshPersona').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_refreshPersona").html(data); 
				},
				error:function(){
					$('#div_refreshPersona').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
		
		function RefreshCampanha(idEmp) {

			var log_campusu, log_campvalida;

			if($('#LOG_CAMPUSU').prop('checked')){
				log_campusu = 'S';
			}else{
				log_campusu = 'N';
			}

			if($('#LOG_CAMPVALIDA').prop('checked')){
				log_campvalida = 'S';
			}else{
				log_campvalida = 'N';
			}
			// alert(idEmp);
			$.ajax({
				type: "GET",
				url: "ajxRefreshCampanhaMini.do",
				data: {ajx1:idEmp, LOG_CAMPUSU: log_campusu, LOG_CAMPVALIDA: log_campvalida},
				beforeSend:function(){
					$('#div_refreshCampanha').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_refreshCampanha").html(data); 
				},
				error:function(){
					$('#div_refreshCampanha').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}

		function excluiCampanha(idEmp,cod_campanha,des_campanha) {

			$.alert({
                title: "Alerta",
                type: 'orange',
                content: "Deseja mesmo excluir a campanha <b>"+des_campanha+"</b>?<br>Essa ação não pode ser desfeita.",
                buttons: {
	            "Sim": {
	               btnClass: 'btn-danger',
	               action: function(){
	               		$.ajax({
							type: "POST",
							url: "ajxRefreshCampanhaMini.do?opcao=EXC",
							data: { COD_EMPRESA:idEmp, COD_CAMPANHA:cod_campanha},
							beforeSend:function(){
								$('#div_refreshCampanha').html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){

								RefreshCampanha(idEmp);
								
							},
							error:function(){
								$('#div_refreshCampanha').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
							}
						});
	               }
	            },
	            "Não": {
	               action: function(){
	                
	               }
	            }
	          }
            });
		
		}	
		
		function retornaForm(index){
			$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_"+index).val());
			$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_"+index).val());
			$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	