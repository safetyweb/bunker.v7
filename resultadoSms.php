<?php
	
	//echo fnDebug('true');
	
	$log_ativo = 'N';

	if(isset($_GET['pop'])){
		$popUp = fnLimpaCampo($_GET['pop']);
	}else{
		$popUp = '';
	}
	
	$cod_template = "";
 
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
			
			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
			$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
			$des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);
			
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
		$cod_campanha = fnDecode($_GET['idc']);	
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
	      
	//fnMostraForm();
	//fnEscreve($cod_checkli);

?>
	<link rel="stylesheet" href="css/widgets.css" />
	
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
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
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
						
						<h4 style="margin: 0 0 5px 0;"><span class="bolder">Visão Geral de e-Mails</span></h4>
						<div class="push20"></div>						
							
<style>
	body{
		overflow: hidden;
		overflow-x: scroll;
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

	.tile{
		cursor: pointer;
	}
	
	.item{
		padding-top: 0;
	}
</style>

	<div class="col-md-12">									
								
		<table class="table table-bordered table-striped table-hover table-fixed tablesorter">
		  	<thead>
				<tr>
					<th class="{ sorter: false }"></th>
					<th>Lote</th>
					<th>Data de Envio</th>
					<th>Enviados</th>
					<th>Exclusão</th>
					<th>Disparados</th>
					<th>Sucesso</th>
					<th>% Sucesso </th>
					<th>Falhas </th>
					<th>% Falhas </th>
					<th>Opt Out </th>
				</tr>
		  	</thead>
		  
			<tbody id="listaTemplates">

				<?php 
					$sql = "SELECT
								EL.COD_DISPARO_EXT,
								CEM.QTD_DIFERENCA,
								CEM.QTD_EXCLUSAO,
								CEM.QTD_DISPARADOS,
								CEM.QTD_SUCESSO,
								CEM.QTD_FALHA,
								CEM.QTD_OPTOUT,
								CEM.DAT_ENVIO,
								TE.NOM_TEMPLATE,
								EL.QTD_LISTA,
								EL.DES_PATHARQ,
								EL.COD_GERACAO,
								EL.COD_CONTROLE,
								EL.COD_LOTE,
								EL.LOG_TESTE
							FROM SMS_LOTE EL
							LEFT JOIN CONTROLE_ENTREGA_SMS CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
							LEFT JOIN TEMPLATE_SMS TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
							WHERE EL.COD_EMPRESA = $cod_empresa
							AND EL.COD_CAMPANHA = $cod_campanha
							AND EL.LOG_ENVIO = 'S'
							-- GROUP BY EL.COD_DISPARO_EXT
							ORDER BY EL.COD_CONTROLE DESC";
					
					// fnEscreve($sql);
					
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
					
					$count=0;
					$alturaTela = 600;
					while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
						$count++;

						if($qrBuscaModulos['DES_PATHARQ'] != ''){
							$urlAnexo = $qrBuscaModulos['DES_PATHARQ'];
						}else{
							$urlAnexo = '';
						}

						if($qrBuscaModulos['COD_GERACAO'] != ''){
							$pref = $qrBuscaModulos['COD_GERACAO'];
						}else{
							if($qrBuscaModulos['LOG_TESTE'] != 'S'){
								$pref = 'ANIV';
							}else{
								$pref = 'TESTE';
							}
						}

						$pct_sucesso = ($qrBuscaModulos['QTD_SUCESSO']/$qrBuscaModulos['QTD_DISPARADOS'])*100;
						$pct_falha = ($qrBuscaModulos['QTD_FALHA']/$qrBuscaModulos['QTD_DISPARADOS'])*100;
						$pct_lidos = ($qrBuscaModulos['QTD_LIDOS']/$qrBuscaModulos['QTD_DISPARADOS'])*100;
						$pct_nlidos = ($qrBuscaModulos['QTD_NLIDOS']/$qrBuscaModulos['QTD_DISPARADOS'])*100;

						?>
							
							<tr>
					           <!-- <td class="text-center"><small><?=$urlAnexo?></small></td> -->
					           	<td class="text-center">
					           		<small>
					           			<div class="dropdown">
											<a class="dropdown-toggle transparency" data-toggle="dropdown" href="#">
												<span class="fas fa-ellipsis-v"></span>
											</a>
											<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
												<li><a tabindex="-1" href="<?=$qrBuscaModulos[DES_PATHARQ]?>" download>Lista enviada</a></li>
												<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('sent','<?=$qrBuscaModulos[COD_DISPARO_EXT]?>')">Sucesso</a></li>
												<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('lidos','<?=$qrBuscaModulos[COD_DISPARO_EXT]?>')">Lidos</a></li>
												<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('nlidos','<?=$qrBuscaModulos[COD_DISPARO_EXT]?>')">Não-lidos</a></li>
												<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('hbounce','<?=$qrBuscaModulos[COD_DISPARO_EXT]?>')">Hardbounce</a></li>
												<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('sbounce','<?=$qrBuscaModulos[COD_DISPARO_EXT]?>')">Softbounce</a></li>
												<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('optout','<?=$qrBuscaModulos[COD_DISPARO_EXT]?>')">Opt-Out</a></li>
												<!-- <li class="divider"></li> -->
												<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
											</ul>
										</div>
					           		</small>
					           	</td>
					           	<td><small><small><?=$pref?></small>&nbsp;Geração do lote #<?=$qrBuscaModulos['COD_CONTROLE']?>/<?=$qrBuscaModulos['COD_LOTE']?></small>&nbsp;<span class="f10"><?=$qrBuscaModulos['COD_DISPARO_EXT']?></span></td>
					           	<td><small><?=fnDataShort($qrBuscaModulos['DAT_ENVIO'])?></small></td>
					           	<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_LISTA'],0)?></small></td>
					           	<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_EXCLUSAO']+$qrBuscaModulos['QTD_DIFERENCA'],0)?></small></td>
					           	<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_DISPARADOS'],0)?></small></td>
					           	<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_SUCESSO'],0)?></small></td>
					           	<td class='text-center'><small><?=fnValor($pct_sucesso,2)?>%</small></td>
					           	<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_FALHA'],0)?></small></td>
					           	<td class='text-center'><small><?=fnValor($pct_falha,2)?>%</small></td>
					           	<td class='text-center'><small><?=fnValor($qrBuscaModulos['QTD_OPTOUT'],0)?></small></td>
					        </tr>
							
				<?php

						$tot_qtd += $qrBuscaModulos['QTD_LISTA'];
						$tot_disparados += $qrBuscaModulos['QTD_DISPARADOS'];
						$tot_sucesso += $qrBuscaModulos['QTD_SUCESSO'];
						$tot_falha += $qrBuscaModulos['QTD_FALHA'];
						$tot_contatos += $qrBuscaModulos['QTD_CONTATOS'];
						$tot_exclusao += $qrBuscaModulos['QTD_EXCLUSAO']+$qrBuscaModulos['QTD_DIFERENCA'];

						$alturaTela += 75;

					}
				?>

		    </tbody>
		    <tfoot>
				<tr>
					<td colspan="3"></td>
					<td class="text-center"><b><?=fnValor($tot_qtd,0)?></b></td>
					<td class="text-center"><b><?=fnValor($tot_contatos,0)?></b></td>
					<td class="text-center"><b><?=fnValor($tot_exclusao,0)?></b></td>
					<td class="text-center"><b><?=fnValor($tot_disparados,0)?></b></td>
					<td class="text-center"><b><?=fnValor($tot_sucesso,0)?></b></td>
					<td></td>
					<td class="text-center"><b><?=fnValor($tot_falha,0)?></b></td>
					<td colspan="7"></td>
				</tr>
				<tr>
					<th colspan="100">
						<a class="btn btn-info btn-sm" onclick="parent.exportaRel('all','0')"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
					</th>
				</tr>
			</tfoot>
		</table>
		
		<!--
		<div class="row">
			<div class="col-xs-12">
				<a href="javascript:void(0)" class="btn btn-xs btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1409)?>&id=<?php echo fnEncode($cod_empresa)?>&tipo=<?php echo fnEncode('CAD')?>&pop=true" data-title="Template do Email" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'><i class="fa fa-plus fa-2x" aria-hidden="true" style="padding: 5px 5px;"></i></a>
			</div>	
		</div>
		-->
	
	</div>			
			
			
			
	<input type="hidden" class="input-sm" name="REFRESH_TEMPLATES" id="REFRESH_TEMPLATES" value="N">

	<!-- modal -->									
	<!-- <div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>		
			</div>
		</div>
	</div>	 -->
		
	<div class="push20"></div> 
	
	<script type="text/javascript">

		parent.$("#conteudoAba").css("height","<?=$alturaTela?>px");

		$(document).ready(function(){
			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
			  //console.log('entrou');
			  if ($('#REFRESH_TEMPLATES').val() == "S"){
				//alert("atualiza");
				RefreshTemplates(<?php echo $cod_empresa; ?>,<?php echo $cod_template; ?>);
				$('#REFRESH_TEMPLATES').val("N");				
			  }	
			});
			
		});
		
		function RefreshTemplates(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshTemplatesEmail.php",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#listaTemplates").html(data); 
				},
				error:function(){
					$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}

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