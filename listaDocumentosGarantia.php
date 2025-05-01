<?php

	//echo fnDebug('true');

$hashLocal = mt_rand();	
$cod_tpmodal = 0;

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

		$cod_tpmodal;

		$cod_licitac = fnLimpaCampoZero($_REQUEST['COD_LICITAC']);
		$num_licitac = fnLimpaCampo($_REQUEST['NUM_LICITAC']);
		$des_licitac = fnLimpaCampo($_REQUEST['DES_LICITAC']);
		$cod_tpmodal = fnLimpaCampoZero($_REQUEST['COD_TPMODAL']);
		$num_adminis = fnLimpaCampo($_REQUEST['NUM_ADMINIS']);
		$dat_habilit = fnLimpaCampo($_REQUEST['DAT_HABILIT']);
		$dat_propost = fnLimpaCampo($_REQUEST['DAT_PROPOST']);
		$dat_edital = fnLimpaCampo($_REQUEST['DAT_EDITAL']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

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
	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	

		//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)){
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}

}else {	
	$nom_empresa = "";
}

if(isset($_GET['idC'])){
	if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do cliente
		$cod_cliente = fnDecode($_GET['idC']);
		$status = "Proposta";

	}
}

if(isset($_GET['idCt'])){
	if (is_numeric(fnLimpacampo(fnDecode($_GET['idCt'])))){
		
			//busca dados do cliente
		$num_contrato = fnDecode($_GET['idCt']);

	}
}

if(isset($_GET['idBem'])){

	$bens = rtrim($_GET['idBem'],',');
	$bens = explode(",", $bens);
	$cod_bens = "";



	foreach ($bens as $bem) {
		$cod_bens .= fnDecode($bem).",";
	}

	$cod_bens = rtrim($cod_bens,",");
	// fnEscreve2($cod_bens);

}

	//busca dados do usuário
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = ".$cod_usucada;	

	//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaUsuario)){
	$nom_usuario = $qrBuscaUsuario['NOM_USUARIO'];
}

	//fnMostraForm();
	//fnEscreve($cod_checkli);

?>

<link href="js/plugins/hummingbird-treeview.css" rel="stylesheet" type="text/css">

<style>
	.stylish-input-group .input-group-addon{
		background: white !important;
	}
	.stylish-input-group .form-control{
		/*border-right:0;*/
		box-shadow:0 0 0;
		border-color:#ccc;
	}
	.stylish-input-group button{
		border:0;
		background:transparent;
	}

	.h-scroll {
		height: 260px;
		overflow-y: scroll;
	}

	.badge{
		display: table;
		border-radius: 30px 30px 30px 30px;
		width: 20px;
		height: 20px;
		text-align: center;
		color:white;
		font-size:8px;
		margin-right: auto;
		margin-left: auto;
		/*cursor: help;*/
	}

	.txtBadge{
		display: table-cell;
		vertical-align: middle;
	}
</style>

<?php if ($popUp != "true"){  ?>							
	<div class="push30"></div> 
<?php } ?>

<div class="row">				
	
	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;" >
				<?php } ?>

				<?php if ($popUp != "true"){  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>
				<div class="portlet-body">
					
					<?php if ($msgRetorno <> '') { ?>	
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>	

					<!-- <div class="tabbable-line">
		
						<ul class="nav nav-tabs ">
							<li>
								<a href="action.do?mod=<?php echo fnEncode(1098)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
								<span class="fal fa-arrow-circle-left fa-2x"></span></a>
							</li>
						</ul>
					</div> -->					



					<!--<form>
						<div class="col-md-12">
							<button type="button" class="fas fa-check" style="background:#18bc9c;color:white; border-radius:70%; margin:1px;width:30px;"></button>
							<button type="button" class="fas fa-info" style="background:red;color:white; border-radius:70%; margin:1px;width:30px;"></button>
							<button type="button" class="fas fa-sync" style="background:blue;color:white; border-radius:70%; margin:1px;"></button>
						</div>
					</form>-->

					<?php include "bensHeader.php"; ?>
					
					<div class="push30"></div> 

					<!-- https://codepen.io/n3k1t/pen/OJMGgyq -->


					<!-- ----------------------------------------------------------------------------------------------------------------------- -->

					<table class="table table-condensed table-bordered table-hover">

						<?php

						$arrayConvenio = array(
							'Documentos do Cliente' => 'COD_CLIENTE',
							'Contrato do Convênio' => 'COD_CONTRAT_CON'
						);

						$arrayLicitac = array(
							'Dados da Licitação' => 'COD_LICITAC',
							'Itens do Objeto' => 'COD_OBJETO',
							'Propostas' => 'COD_PROPOSTA',
							'Ata da Proposta' => 'COD_PUBLICA',
							'Contrato da Licitação' => 'COD_CONTRAT_LIC'
						);

						$arrayExec = array(
							'Créditos' => 'COD_CAIXA',
							'Medição/Recebimento' => 'COD_RECEBIM',
							'Movimentação' => 'COD_EMPENHO',
							'Pagamento' => 'COD_PAGAMEN'
						);

						$itens_indice = array(
							'Documentos do Cliente' => 'COD_CLIENTE'
						);

						$sql = "SELECT * FROM BENS_CLIENTE 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CLIENTE = $cod_cliente 
						AND COD_BEM IN($cod_bens)
						AND COD_EXCLUSA IS NULL";

			                                    //fnEscreve($sql);
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
						while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){
							$itens_indice[$qrBuscaModulos[DES_NOMEBEM]] = $qrBuscaModulos[COD_BEM];
						}

							// echo "<pre>";
							// print_r($arrayDocumentos);
							// echo "</pre>";

						$count = 0;

						foreach ($itens_indice as $nom_linha => $chave_linha) {

							if($count == 0){
								$completaCont = "AND AC.COD_BEM = 0";
							}else{
								$completaCont = "AND AC.COD_BEM = $chave_linha";
							}
							?>

							<thead>

								<tr data-toggle="collapse" class="accordion-toggle" data-target="#<?=$chave_linha?>" onclick='rotacionaSeta("<?=$chave_linha?>")'>
									<th></th>
									<th><span class="fal fa-angle-right <?=$chave_linha?>" data-expande='0'></span>&nbsp; <a href="javascript:void(0)"><?=$nom_linha?></a></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tr>

							</thead>

							<tbody>

								<tr>

									<td colspan="12" class="hiddenRow">
										<div class="accordian-body collapse" id="<?=$chave_linha?>"> 
											<table class="table table-striped">

												<thead>

													<tr>
														<th class="{ sorter: false }" width="5%"></th>
														<th class="{ sorter: false }" width="30%">Arquivo</th>
														<th class="{ sorter: false }" width="10%">Dt. Recebimento</th>
														<th class="{ sorter: false } text-center" width="8%">Status</th>
														<th class="{ sorter: false }" width="10%">Dt. Status</th>
														<th class="{ sorter: false }" width="20%">Usuário</th>
														<th class="{ sorter: false } text-center" width="10%">Histórico</th>
														<th class="{ sorter: false }" width="7%">Ação</th>
													</tr>

												</thead>

												<?php

												$completaCont = str_replace("$chave_linha = 0", "$chave_linha != 0", $completaCont);

												$sqlDocConvenio = "SELECT AD.*, SA.DES_STATUS, US.NOM_USUARIO FROM ANEXO_DOC AD
												INNER JOIN ANEXO_DOCUMENTO AC ON AC.COD_ANEXO = AD.COD_ANEXO
												INNER JOIN WEBTOOLS.STATUS_ANEXO SA ON SA.COD_STATUS = AC.COD_STATUS
												LEFT JOIN WEBTOOLS.USUARIOS US ON US.COD_USUARIO = AC.COD_USUCADA
												WHERE AD.COD_CLIENTE = $cod_cliente $completaCont ORDER BY DAT_CADASTR DESC";

														// fnEscreve($sqlDocConvenio);

												$arDocConvenio = mysqli_query(connTemp($cod_empresa,''),$sqlDocConvenio);

												while($qrDocConvenio = mysqli_fetch_assoc($arDocConvenio)){

													$file_ext = strtolower(end(explode('.', $qrDocConvenio['NOM_ORIGEM'])));

													$sqlHist = "SELECT * FROM HISTORICO_ANEXO 
													WHERE COD_ANEXO = $qrDocConvenio[COD_DOCUMENTO]";


													$arrayHist = mysqli_query(connTemp($cod_empresa,''),$sqlHist);

													$qtd_hist = mysqli_num_rows($arrayHist);

													$qtd_hist++;

													$sqlHist = "SELECT A.*,
													CASE WHEN A.COD_STATUS =3 THEN
													B.DES_JUSTIFICA
													ELSE 
													A.DES_JUSTIFICA
													END AS JUSTIFICA
													FROM HISTORICO_ANEXO A
													LEFT JOIN JUSTIFICATIVA B ON A.DES_JUSTIFICA=COD_JUSTIFICA AND A.COD_STATUS=3
													WHERE A.COD_ANEXO = $qrDocConvenio[COD_DOCUMENTO]
													ORDER BY 1 DESC
													LIMIT 1";

																		//fnEscreve $sqlHist;

													$arrayHist = mysqli_query(connTemp($cod_empresa,''),$sqlHist);

													$qrHist = mysqli_fetch_assoc($arrayHist);

													$dat_status = $qrHist[DAT_STATUS];

													$mostra_status = "";
													$cor = "";
													$badge = "badge";
													$txtBadge = "txtBadge";
													$tooltip = "";
													$mostraAprovar = "block";
													$textoReprova = "Reprovar";

													if($qtd_hist == 0){
														$qtd_hist = 1;
													}

													if($dat_status == ""){
														$dat_status = $qrDocConvenio['DAT_CADASTR'];
													}

													if($qrHist[COD_STATUS] == 2){

														$cor = "background:#18bc9c;";
														$mostra_status = "<span class='fas fa-check'></span>";
														$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrHist[JUSTIFICA]'";
														$mostraAprovar = "none";

													}else if($qrHist[COD_STATUS] == 3){

														$cor = "background:red; color:white;";
														$mostra_status = "<span class='fas fa-info'></span>";
														$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrHist[JUSTIFICA]'";
														$textoReprova = "Alterar Justificativa";

													}else{
														$cor = "background:blue; color:white;";
														$mostra_status = "<span class='fas fa-sync'></span>";
														$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrDocConvenio[DES_STATUS]'";
														$textoReprova = "Alterar Justificativa";

													}

													$status = "<span class='".$badge."' style='".$cor."' $tooltip><span class='".$txtBadge." ".$textRed."'>".$mostra_status."</span></span>";

													?>				


													<tbody>

														<tr class="accordion-toggle"  data-toggle="collapse" data-target=".Convenio">
															<td></td>
															<td>
																<?php if($file_ext == "jpeg" || $file_ext == "jpg" || $file_ext == "png"){ ?>
																	<a href="https://adm.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrDocConvenio['NOM_ORIGEM']; ?>" class="download" target="files" onclick="openNav()"><span class="fal fa-file-search"></span>
																	</a>
																<?php }else{ ?>
																	<a href="https://docs.google.com/a/192.99.240.249/viewer?url=http://adm.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrDocConvenio['NOM_ORIGEM']; ?>&pid=explorer&efh=false&a=v&chrome=false&embedded=true" class="download" target="files" onclick="openNav()"><span class="fal fa-file-search"></span></a>
																<?php } ?>

																&nbsp;&nbsp;

																<a class="download" href="../media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrDocConvenio['NOM_ORIGEM']; ?>" download><span class="fal fa-arrow-to-bottom"></span></a>

																&nbsp;&nbsp;

																<?php echo $qrDocConvenio['NOM_REFEREN']; ?>
															</td>
															<td>
																<small><?php echo date("d/m/Y",strtotime($qrDocConvenio['DAT_CADASTR'])) ?></small>
																&nbsp;
																<small><?php echo date("H:i:s",strtotime($qrDocConvenio['DAT_CADASTR'])) ?></small>
															</td>
															<td class="text-center"><?=$status?></td>
															<td>
																<small><?php echo date("d/m/Y",strtotime($dat_status)) ?></small>
																&nbsp;
																<small><?php echo date("H:i:s",strtotime($dat_status)) ?></small>
															</td>
															<td><?=$qrDocConvenio['NOM_USUARIO']?></td>
															<td class="text-center"><?=$qtd_hist?></td>
															<td>
																<small>
																	<div class="btn-group dropdown dropleft">
																		<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			ações &nbsp;
																			<span class="fas fa-caret-down"></span>
																		</button>
																		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																			<!-- <li class="divider"></li> -->
																			<li style="display: <?=$mostraAprovar?>;"><a href="javascript:void(0)" onclick='reloadAnexo("<?=fnEncode($qrDocConvenio[COD_DOCUMENTO])?>","aprovar","<?=$chave_linha?>")'><span class="fal fa-clipboard-check"></span>&nbsp; Aprovar</a></li>
																			<li><a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1851)?>&id=<?php echo fnEncode($cod_empresa)?>&ida=<?=fnEncode($qrDocConvenio[COD_DOCUMENTO])?>&idc=<?=fnEncode($chave_linha)?>&pop=true" data-title="Justificativa de reprovação"><span class="fal fa-ban"></span>&nbsp; <?=$textoReprova?></a></li>
																		</ul>
																	</div>
																</small>
															</td>
														</tr>

													</tbody>


													<?php
												}
												?>

											</table>

										</div> 

									</td>
								</tr>

							</tbody>

							<?php 

							$count++;

						}

						?>




					</table>


					<style>
						.hiddenRow {
							padding: 0 !important;
						}
					</style>

					<div class="push100"></div> 

					<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
					
					<div class="push"></div>
					
				</div>								
				
			</div>
		</div>
		<!-- fim Portlet -->
	</div>

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

<!-- <script src="js/plugins/hummingbird-treeview.js"></script> -->

<style type="text/css">
	
/* The Overlay (background) */
.overlay {
	/* Height & width depends on how you want to reveal the overlay (see JS below) */    
	height: 100%;
	width: 100%;
	position: fixed; /* Stay in place */
	left: 0;
	top: 0;
	background-color: rgba(0,0,0, 0.9); /* Black w/opacity */
	overflow-x: hidden; /* Disable horizontal scroll */
	transition: 0.5s; /* 0.5 second transition effect to slide in or slide down the overlay (height or width, depending on reveal) */
	display: none;
	z-index: 9999;
}

/* Position the content inside the overlay */
.overlay-content {
	position: relative;
	top: 0; /* 5% from the top */
	width: 80%; /* 100% width */
	text-align: center; /* Centered text/links */
	margin-left: auto;
	margin-right: auto;
}

/* Position the close button (top right corner) */
.overlay .closebtn {
	position: absolute;
	top: 60px;
	right: 45px;
	font-size: 60px;
}

.modal-dialog2{
	width: 100vw;
	height: 100vh;

}

.modal-content2{
	width: 100vw;
	height: 100vh;
	border-radius: 0;
}


</style>

<!-- The overlay -->
<div id="myNav" class="overlay">

	<!-- Button to close the overlay navigation -->
	<div class="push50"></div>
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

	<!-- Overlay content -->
	<div class="overlay-content">
		<iframe name="files" id="files" src='' width='100%' height='100%' frameborder='0'></iframe>
	</div>

</div>

<script>

	let carrega = 0;

	/* Open */
	function openNav() {
		$('#myNav').show();
		try {
			$('.modal-dialog').attr("class", 'modal-dialog2');
			$('.modal-content').attr("class", 'modal-content2');
		} catch(err) {}
	}

	/* Close */
	function closeNav() {
		$('#myNav').hide();
		try { 
			$('.modal-dialog2').attr("class", 'modal-dialog');
			$('.modal-content2').attr("class", 'modal-content');
		} catch(err) {}
		$('#files').attr('src', '');
	}

	function reloadAnexo(cod_doc,tipo,chave) {
		alert(tipo);
		if(tipo == "aprovar"){
			carrega = 0;
			$.alert({
				title: "AVISO",
				content: "Deseja aprovar o documento?",
				backgroundDismiss: true,
				buttons: {
					"APROVAR": {
						btnClass: 'btn-success',
						action: function(){
							$.ajax({
								type: "POST",
								url: "ajxListaDocumentoGarantia.php?opcao="+tipo+"&id=<?php echo fnEncode($cod_empresa); ?>",
								data: {CHAVE_LINHA:chave, COD_ANEXO:cod_doc, COD_CLIENTE:"<?=fnEncode($cod_cliente)?>"},
								beforeSend:function(){
									$("#"+chave).html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
								},
								success:function(data){
									console.log(data);
									$("#"+chave).html(data);
									if(tipo != "paginar"){
										reloadAnexo(cod_doc,"paginar",chave);
									}										
								},
								error:function(data){
							//console.log(data);
									$("#"+chave).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
									$("#"+chave).append(data);	
								}
							});
						}
					},
					"Cancelar": {
						action: function(){

						}
					},
				}
			});
		}else{
			carrega = 1;
		}

		// alert(carrega);

		if(carrega == 1){
			$.ajax({
				type: "POST",
				url: "ajxListaDocumentoGarantia.php?opcao="+tipo+"&id=<?php echo fnEncode($cod_empresa); ?>",
				data: {CHAVE_LINHA:chave, COD_ANEXO:cod_doc, COD_CLIENTE:"<?=fnEncode($cod_cliente)?>"},
				beforeSend:function(){
					$("#"+chave).html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
				},
				success:function(data){
					// console.log(data);
					$("#"+chave).html(data);
					if(tipo != "paginar"){
						reloadAnexo(cod_doc,"paginar",chave);
					}										
				},
				error:function(data){
					//console.log(data);
					$("#"+chave).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					$("#"+chave).append(data);	
				}
			});
		}
		
	}

	function rotacionaSeta(obj){

		let expande = $("."+obj).attr('data-expande');

		if(expande == 0){
			$("."+obj).attr('data-expande','1').removeClass('fa-angle-right').addClass('fa-angle-down');
		}else{
			$("."+obj).attr('data-expande','0').removeClass('fa-angle-down').addClass('fa-angle-right');
		}

	}

	// $("#treeview").hummingbird();
	// $( "#checkAll" ).click(function() {
	//   $("#treeview").hummingbird("checkAll");
	// });
	// $( "#uncheckAll" ).click(function() {
	//   $("#treeview").hummingbird("uncheckAll");
	// });
	// $( "#collapseAll" ).click(function() {
	//   $("#treeview").hummingbird("collapseAll");
	// });
	// $( "#checkNode" ).click(function() {
	//   $("#treeview").hummingbird("checkNode",{attr:"id",name: "node-0-2-2",expandParents:false});
	// });

</script>
