<?php
	
	//echo fnDebug('true');
 
    $hashLocal = mt_rand();	
	$log_obrigat='N';
	
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
			
			$cod_empenho = fnLimpaCampoZero($_REQUEST['COD_EMPENHO']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_contrat = fnLimpaCampoZero($_REQUEST['COD_CONTRAT']);
			$cod_recebim = fnLimpaCampoZero($_REQUEST['COD_RECEBIM']);
			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$dat_nota = fnLimpaCampo($_REQUEST['DAT_NOTA']);
			$dat_empenho = fnLimpaCampo($_REQUEST['DAT_EMPENHO']);
			$num_nota = fnLimpaCampo($_REQUEST['NUM_NOTA']);
			$num_empenho = fnLimpaCampo($_REQUEST['NUM_EMPENHO']);
			$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
			$val_conveni = fnLimpaCampo($_REQUEST['VAL_CONVENI']);
			$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
			
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
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){

			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);

		}
	}

	$andRecebim = "";

	if(isset($_GET['idRC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idRC'])))){

			//busca dados do recebimento
			$cod_recebim = fnDecode($_GET['idC']);
			$andRecebim = "AND COD_RECEBIM = $cod_recebim";
		}
	}
			
	$sql = "SELECT DISTINCT c.NOM_CLIENTE,a.val_valor AS VALOR_CONVENIO,
				   a.val_conced AS VALOR_CONCEDENTE,
					 a.val_contpar AS VAL_CONTRAPARTIDA,
					 a.DAT_INICINV,
					 ifnull((SELECT SUM(val_credito) FROM caixa
								WHERE COD_EMPRESA = a.COD_EMPRESA AND 
									  COD_CONVENI = a.COD_CONVENI AND 
									  cod_tipo=1),0) AS CREDITOS_CONCEDENTE,
				  IFNULL((SELECT SUM(val_credito) FROM caixa
								WHERE COD_EMPRESA = a.COD_EMPRESA AND 
									  COD_CONVENI = a.COD_CONVENI AND 
									  cod_tipo=2),0) AS CREDITOS_CONVENENTE,
					ifnull((SELECT SUM(val_credito) FROM caixa
								WHERE COD_EMPRESA = a.COD_EMPRESA AND 
									  COD_CONVENI = a.COD_CONVENI AND 
									  cod_tipo=3),0) AS CREDITOS_APLICACAO,
					IFNULL((SELECT SUM(val_credito) FROM caixa
					 WHERE COD_EMPRESA = a.COD_EMPRESA AND 
						   COD_CONVENI = a.COD_CONVENI AND 
						   cod_tipo not IN(1,2,3)),0) AS DEBITOS_CONVENIO
					 
			from CONVENIO a
			LEFT JOIN CONTROLE_RECEBIMENTO b ON a.cod_empresa=b.cod_empresa AND a.cod_conveni=b.cod_conveni  
			LEFT JOIN CLIENTES C ON C.COD_CLIENTE = b.COD_CLIENTE
			WHERE a.COD_EMPRESA = $cod_empresa AND 
				  a.COD_CONVENI = $cod_conveni
			";

	//fnEscreve($sql);
	//echo($sql);
	$arrayQuery =  mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrContrat = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrContrat)){
		
		//$cod_cliente = $qrContrat['COD_CLIENTE'];
		$nom_cliente = $qrContrat['NOM_CLIENTE'];
		$valor_convenio = $qrContrat['VALOR_CONVENIO'];
		$valor_concedente = $qrContrat['VALOR_CONCEDENTE'];
		$val_contrapartida = $qrContrat['VAL_CONTRAPARTIDA'];
		$dat_inicinv = $qrContrat['DAT_INICINV'];
		$val_debito = $qrContrat['VAL_DEBITO'];
		$creditos_concedente = $qrContrat['CREDITOS_CONCEDENTE'];
		$creditos_convenente = $qrContrat['CREDITOS_CONVENENTE'];
		$val_recebido = $qrContrat['CREDITOS_CONCEDENTE']+$qrContrat['CREDITOS_CONVENENTE'];
		$creditos_aplicacao = $qrContrat['CREDITOS_APLICACAO'];
		$debitos_convenio = $qrContrat['DEBITOS_CONVENIO'];
		$saldo_recebido = ($val_recebido + $creditos_aplicacao) - $debitos_convenio;
	}

	//fnMostraForm();
	//fnEscreve($cod_contrat);

?>

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
						<i class="glyphicon glyphicon-calendar"></i>
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
				
					<div class="tabbable-line">
						<ul class="nav nav-tabs">
							<li>
								<a href="action.do?mod=<?php echo fnEncode(1348)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>" style="text-decoration: none;">
								<span class="fal fa-arrow-circle-left fa-2x"></span></a>
							</li>
						</ul>
					</div>										
										
					<div class="push20"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
							
						<div class="row">

							<div class="col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label required">Empresa</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>" required>
									<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
								</div>														
							</div>      
				
							<div class="col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label required">Convênio</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_FAVORECIDO" id="NOM_FAVORECIDO" value="<?php echo $nom_cliente ?>" required>
								</div>														
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data de Início</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_EXECUCAO" id="DAT_EXECUCAO" value="<?php echo fnDataShort($dat_inicinv) ?>" required>
								</div>														
							</div>	
							
							<div class="push10"></div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Valor do Convênio</label>
									<input type="text" class="form-control input-sm leitura" readonly name="VAL_MEDICAO" id="VAL_MEDICAO" value="<?php echo fnValor($valor_convenio,2)?>">
								</div>
									
								<div class="row">
									<div class="col-md-6">
										<span class="f12"><?php echo fnValor($valor_concedente,2)?></span>
										<div class="help-block with-errors">concedente</div>								
									</div>
									
									<div class="col-md-6">
										<span class="f12"><?php echo fnValor($val_contrapartida,2)?></span>
										<div class="help-block with-errors">convenente</div>								
									</div>
								</div>
									
							</div>

							<div class="col-md-1"></div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Valor Recebido</label>
									<input type="text" class="form-control input-sm leitura" readonly name="VAL_MEDICAO" id="VAL_MEDICAO" value="<?php echo fnValor($val_recebido,2)?>">
								</div>														
								
								<div class="row">
									<div class="col-md-6">
										<span class="f12"><?php echo fnValor($creditos_concedente,2)?></span>
										<div class="help-block with-errors">concedente</div>								
									</div>
									
									<div class="col-md-6">
										<span class="f12"><?php echo fnValor($creditos_convenente,2)?></span>
										<div class="help-block with-errors">convenente</div>								
									</div>
								</div>
							</div>

							<div class="col-md-1"></div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Valor de Aplicação</label>
									<input type="text" class="form-control input-sm leitura" readonly name="VAL_MEDICAO" id="VAL_MEDICAO" value="<?php echo fnValor($creditos_aplicacao,2)?>">
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Total de Débitos</label>
									<input type="text" class="form-control input-sm leitura" readonly name="VAL_MEDICAO" id="VAL_MEDICAO" value="<?php echo fnValor($debitos_convenio,2)?>">
								</div>														
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Saldo do Convênio</label>
									<input type="text" class="form-control input-sm leitura" readonly name="VAL_MEDICAO" id="VAL_MEDICAO" value="<?php echo fnValor($saldo_recebido,2)?>">
								</div>														
							</div>	
							

						</div>
						
						</form>
						
						<div class="push20"></div>
						
						<div class="col-lg-12">

							<div class="no-more-tables">
						
								<form name="formLista">
								
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th width="150"><small>Data</small></th>
									  <th><small>Favorecido</small></th>
									  <th><small>Comentário</small></th>
									  <th><small>Operação</small></th>
									  <th width="80"><small>Tipo</small></th>
									  <th colspan="2" class="text-center"><small>Valor</small></th>
									</tr>
								  </thead>
									<tbody>
									
									<?php 
										//$sql = "SELECT * FROM EMPENHO WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM = $cod_recebim";
										/*
										$sql = "SELECT 	CAIXA.VAL_CREDITO,
														TIP_CREDITO.DES_TIPO,
														TIP_CREDITO.TIP_OPERACAO,
														DATE_FORMAT(CAIXA.DAT_LANCAME, '%d/%m/%Y') DAT_LANCAME,
														CLIENTES.NOM_CLIENTE,
														LICITACAO_OBJETO.NOM_OBJETO
										FROM CAIXA
										left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
										left join CONTRATO on caixa.COD_CONTRAT=CONTRATO.COD_CONTRAT
										left join CLIENTES on CONTRATO.COD_CLIENTE=CLIENTES.COD_CLIENTE
										left join LICITACAO_OBJETO on CONTRATO.COD_OBJETO=LICITACAO_OBJETO.COD_OBJETO
										where CAIXA.cod_conveni = $cod_conveni AND
											  CAIXA.COD_EMPRESA = $cod_empresa 
											  ORDER BY CAIXA.DAT_LANCAME DESC";										
										*/
										
										$sql = "SELECT  c.NOM_CLIENTE,
														d.DES_TIPO,
														d.ABV_TIPO,
														d.TIP_OPERACAO, 
														a.VAL_CREDITO,
														a.DAT_CREDITO,
														e.DES_COMENT
												FROM caixa a 
												LEFT JOIN contrato b ON b.cod_empresa=a.cod_empresa AND b.cod_conveni=a.cod_conveni AND b.COD_CONTRAT=a.cod_contrat
												LEFT JOIN clientes c ON c.cod_cliente=b.COD_CLIENTE AND c.COD_EMPRESA=a.cod_empresa 
												INNER JOIN tip_credito d ON d.COD_EMPRESA=a.cod_empresa AND d.COD_TIPO=a.COD_TIPO
												LEFT JOIN empenho f ON f.cod_empenho=a.cod_empenho AND f.COD_EMPRESA=a.cod_empresa 
												LEFT JOIN controle_recebimento e ON e.cod_recebim=f.cod_recebim AND e.COD_EMPRESA=a.cod_empresa 

												WHERE a.cod_empresa = $cod_empresa
												AND a.cod_conveni = $cod_conveni 
												GROUP BY a.COD_CAIXA
												ORDER BY a.DAT_CREDITO ";
										
										// fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										
										$count=0;
										$val_totalCred = 0;
										$val_totalDeb = 0;
										$val_totCont = 0;
										$val_totConv = 0;
										while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery))
										  {														  
											$count++;
											if ($dat_lancame ==  $qrListaCaixa['DAT_CREDITO']){
												$dat_lancame = "";	
											} else {
												$dat_lancame = $qrListaCaixa['DAT_CREDITO'];		
												//$dat_lancame = fnDataShort($qrListaCaixa['DAT_CREDITO']);		
											}
											
											$tip_operacao = $qrListaCaixa['TIP_OPERACAO'];
											
											if ($tip_operacao == "D") {
												$corTexto = "text-danger";
												$val_debito = fnValor($qrListaCaixa['VAL_CREDITO'],2);
												$val_totalDeb = $val_totalDeb + $qrListaCaixa['VAL_CREDITO'];
												$val_credito = "";
											} else { 
												$corTexto = ""; 
												$val_credito = fnValor($qrListaCaixa['VAL_CREDITO'],2);
												$val_totalCred = $val_totalCred + $qrListaCaixa['VAL_CREDITO'];
												$val_debito = "";
												} 
												
											?>
												<tr>
												  <td class="f14"><b><?php echo fnDataShort($dat_lancame); ?></b></td>
												  <td class='f12'><?=$qrListaCaixa['NOM_CLIENTE']?></td>
												  <td class='f12'><?=$qrListaCaixa['DES_COMENT']?></td>
												  <td class="f12"><?php echo $qrListaCaixa['DES_TIPO']; ?></td>
												  <td class='text-center <?php echo $corTexto; ?> f12'><?php echo $qrListaCaixa['TIP_OPERACAO']; ?></td>
												  <td class='text-right <?php echo $corTexto; ?> f14'><?php echo $val_credito; ?></td>
												  <td class='text-right <?php echo $corTexto; ?> f14'><?php echo $val_debito; ?></td>
												</tr>	

											<?php
											}
											?>
									
									</tbody>

									<tfoot>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-right f16"><b><?=fnValor($val_totalCred,2);?></b></td>
											<td class="text-right f16"><b><?=fnValor($val_totalDeb,2);?></b></td>
										</tr>
									</tfoot>

								</table>

								<input type="hidden" id="ret_VAL_SALDO" value="<?=fnValor($val_saldo,2);?>">

								
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
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
	<script type="text/javascript">

		$(document).ready(function(){
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$('#VAL_SALDO').val($('#ret_VAL_SALDO').val());


		});

		$('#VAL_CONTPAR,#VAL_CONVENI').change(function(){
				$('#VAL_VALOR').unmask();
					if($('#VAL_CONTPAR').val() != ''){
						val_contpar = parseFloat($('#VAL_CONTPAR').val().replace('.','').replace(',','.'));
					}else{
						val_contpar = 0;
					}
					if($('#VAL_CONVENI').val() != ''){
						val_conveni = parseFloat($('#VAL_CONVENI').val().replace('.','').replace(',','.'));
					}else{
						val_conveni = 0;
					}
				total = (val_contpar+val_conveni).toFixed(2);
				$('#VAL_VALOR').val(total).toString().mask('##0');
			});
		
		function retornaForm(index){
			$("#formulario #COD_EMPENHO").val($("#ret_COD_EMPENHO_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_CONTRAT").val($("#ret_COD_CONTRAT_"+index).val());
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
			$("#formulario #COD_RECEBIM").val($("#ret_COD_RECEBIM_"+index).val());
			$("#formulario #DAT_NOTA").val($("#ret_DAT_NOTA_"+index).val());
			$("#formulario #DAT_EMPENHO").val($("#ret_DAT_EMPENHO_"+index).val());
			$("#formulario #NUM_NOTA").val($("#ret_NUM_NOTA_"+index).val());
			$("#formulario #NUM_EMPENHO").val($("#ret_NUM_EMPENHO_"+index).val());
			$("#formulario #VAL_CONTPAR").val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #VAL_CONVENI").val($("#ret_VAL_CONVENI_"+index).val());
			$("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_"+index).val());
			// $("#formulario #UPDATE_EFETIVO").val($("#ret_VAL_VALOR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');
	
		}

		$('.upload').on('click', function (e) {
	        var idField = 'arqUpload_' + $(this).attr('idinput');
	        var typeFile = $(this).attr('extensao');

	        $.dialog({
	            title: 'Arquivo',
	            content: '' +
	                    '<form method = "POST" enctype = "multipart/form-data">' +
	                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
	                    '<div class="progress" style="display: none">' +
	                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
	                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
	                    '</div>' +
	                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
	                    '</form>'
			});
		});

		function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', 'banner');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }
		
	</script>	

