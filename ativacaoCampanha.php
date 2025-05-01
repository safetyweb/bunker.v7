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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//liberação das abas
	$abaPersona	= "S";
	$abaVantagem = "S";
	$abaRegras = "S";
	$abaComunica = "N";
	$abaAtivacao = "N";
	$abaResultado = "N";

	$abaPersonaComp = "completed ";
	$abaVantagemComp = "completed ";
	$abaRegrasComp = "completed";
	$abaComunicaComp = "completed";
	$abaResultadoComp = "";	
	$abaAtivacaoComp = "active";	
	
	//fnMostraForm();

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									$formBack = "1048";
									include "atalhosPortlet.php"; 
									?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									<?php $abaCampanhas = 1056; include "abasCampanhasConfig.php"; ?>
									
									<div class="push10"></div> 
					
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
											
										<div class="push10"></div>
										
<style>

.widget.widget-default .widget-controls a{
	border-color: transparent;
}

  #sortable1, #sortable2, #listaAtiva {
    border: 3px dashed #cecece; 
	padding: 20px;
    width: 142px;
    list-style-type: none;
    margin: 0;
    padding: 15px 10px 15px 10px;
    float: left;
    margin-right: 10px;
	width: 100%;
	border-radius: 5px;
	min-height: 200px;
  }

.connectedSortable li{
	margin: 5px 5px 10px 5px;	
	border-radius: 5px;
	padding: 10px 0;
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #F8F9F9));
	-webkit-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.3);
	position: relative;
	min-height: 90px;
    max-height: 200px;
}

.ladoEsquerdo{
	float: left;
	font-size: 40px;
	width: 20%;
	display: table;
	text-align: center;
	border-right: 1px solid rgba(0, 0, 0, 0.1);
	padding: 15px 0;
	margin-right: 10px; 
	min-height: 150px;
    max-height: 200px;
}

.ladoDireito{
	margin: 5px;
}

.icon{
	display: table-cell;
    vertical-align: middle;
}

.titulo{
	margin-bottom: 5px;
	font-weight: bold;
}

.subTitulo{
	font-size: 12px;
	margin-bottom: 2px;
	line-height:12px;
}

.col1{
	display: inline-block; overflow: hidden; min-width:120px; margin-right: 5px;
}

.col2{
	display: inline-block; overflow: hidden; min-width:110px;
}

</style>											

 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>

  $( function() {
    $( "#sortable1, #sortable2" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
  } );

  </script>								
									<div class="push10"></div> 
					
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="/action.php?mod=NAUbaxxRbts%C2%A2&id=QunXraEOVrg%C2%A2&idx=QunXraEOVrg%C2%A2">
										<div class="row">
											<div class="col-md-4">
											
												<h4 style="margin-bottom: 2px;">Campanhas Disponíveis</h4>
												<span class="help-block"><b>Apenas</b> campanhas que controlam <b>venda em tempo real</b></span>
												<div class="push15"></div> 
												
												<ul id="sortable1" class="connectedSortable">
												       <?php
                                                                                                      
													$sql = " SELECT campanha.*,
																	campanharegra.LOG_PRODUTO,
																	campanharegra.QTD_VANTAGEM,
																	campanharegra.QTD_RESULTADO,
																	campanharegra.NUM_PESSOAS,
																	campanharegra.NOM_VANTAGE,
																	campanharegra.PCT_VANTAGEM,
																	vantagemextra.TIP_EXTRACAD,
																	vantagemextra.TIP_EXTRAANI,
																	vantagemextra.QTD_TOTFAIXA,
																	vantagemextra.QTD_TOTITENS,
																	vantagemextra.QTD_TOTPRODU,
																	vantagemextra.QTD_TOTPRODU,
																	campanharesgate.TIP_MOMRESG,
																	campanharesgate.NUM_DIASRSG,
																	campanharesgate.TIP_DIASVLD,
																	campanharesgate.NUM_INATIVO,
																	campanharesgate.TIP_FRAUDES,
																	campanharesgate.NUM_MINRESG,
																	campanharesgate.PCT_MAXRESG,
																	tipocampanha.ABV_TPCAMPA FROM CAMPANHA
																left join campanharegra on CAMPANHA.COD_CAMPANHA=campanharegra.COD_CAMPANHA 
																left join vantagemextra on CAMPANHA.COD_CAMPANHA=vantagemextra.COD_CAMPANHA 
																left join campanharesgate on CAMPANHA.COD_CAMPANHA=campanharesgate.COD_CAMPANHA
																inner join tipocampanha on campanha.TIP_CAMPANHA=tipocampanha.COD_TPCAMPA 
																where campanha.COD_EMPRESA=$cod_empresa and campanha.LOG_REALTIME = 'S' and campanha.LOG_ATIVO = 'S' ";
														 
														 //fnEscreve($sql);
														 $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
                                                                                                                 
														$count=0;
														while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery))
														  {	                                           
															//fnEscreve($qrListaCampanha['LOG_REALTIME']);
															//fnEscreve($qrListaCampanha['COD_CAMPANHA']);
															$count++;
															//venda contínua
															if ( $qrListaCampanha['LOG_CONTINU'] == "S" ){$vendaContinua = "Sim";} else {$vendaContinua = "Não";}
															//por produtos
															if ($qrListaCampanha['LOG_PRODUTO'] == "S"){$porProduto = "Todos";}else{$porProduto = "Espec.";}
															//taxa de conversão - se pontos															
															$qtd_vantagem = $qrListaCampanha['QTD_VANTAGEM'];
															$qtd_resultado = $qrListaCampanha['QTD_RESULTADO'];
															$custoReal = fnValor($qtd_vantagem/$qtd_resultado,2);
															//extra por cadastro
															if ( !is_null($qrListaCampanha['TIP_EXTRACAD']) ){$extraCad = "Sim";} else {$extraCad = "Não";}
															if ( !is_null($qrListaCampanha['TIP_EXTRAANI']) ){$extraAni = "Sim";} else {$extraAni = "Não";}
															//estra por valor, qtd. itens e prod. específicos
															if ( $qrListaCampanha['QTD_TOTFAIXA'] > 0 ){$extraFaixaValor = "Sim";} else {$extraFaixaValor = "Não";}
															if ( $qrListaCampanha['QTD_TOTITENS'] > 0 ){$extraTotItens = "Sim";} else {$extraTotItens = "Não";}
															if ( $qrListaCampanha['QTD_TOTPRODU'] > 0 ){$extraTotProdu = "Sim";} else {$extraTotProdu = "Não";}
															//resgate, validade, expiração
															if ( $qrListaCampanha['TIP_MOMRESG'] == "I" ){$resgateTempo = "Imediato";} else {$resgateTempo = "Em ".$qrListaCampanha['NUM_DIASRSG']." dia(s)";}
															if ( $qrListaCampanha['TIP_DIASVLD'] == "NEX" ){$resgateValidade = "Não";} else {$resgateValidade = "Sim";}
															if ( $qrListaCampanha['NUM_INATIVO'] == "NEX" ){$resgateExpira = "Não";} else {$resgateExpira = "Sim";}
															if ( $qrListaCampanha['TIP_FRAUDES'] == "ILM" ){$resgateFraude = "Sim";} else {$resgateFraude = "Não";}
													
															//fnEscreve($qrListaCampanha['COD_CAMPANHA']);
													?>

														<li data-nome="Campanha1" class="grabbable shadow" cod-campanha="<?php echo $qrListaCampanha['COD_CAMPANHA']; ?>">
															<div class="ladoEsquerdo">
																<i class="fa <?php echo $qrListaCampanha['DES_ICONE']; ?> icon" style="color: #<?php echo $qrListaCampanha['DES_COR']; ?>" aria-hidden="true"></i>
															</div>
															<div class="ladoDireito">
																<p class="titulo"><?php echo $qrListaCampanha['DES_CAMPANHA']; ?></p>																
																<p class="subTitulo">
																<span class="col1"><b>Tipo:</b> <?php echo $qrListaCampanha['ABV_TPCAMPA']; ?></span>
																<span class="col2"><b>Hit:</b> <?php echo fnValor($qrListaCampanha['NUM_PESSOAS'],0); ?></span>
																<br/>
																<span class="col1"><b>Produtos:</b> <?php echo $porProduto; ?></span>
																<span class="col2"><b>Nick:</b> <?php echo $qrListaCampanha['NOM_VANTAGE']; ?></span>
																<br/>
																<span class="col1"><b>Reversão:</b> <?php echo fnValor($qrListaCampanha['PCT_VANTAGEM'],2); ?>%</span>
																<?php if ($qrListaCampanha['TIP_CAMPANHA'] == 12 && $custoReal != "0,00") { ?>
																<span class="col2"><b>Conversão:</b> <?php echo $custoReal; ?></span>
																<?php } ?>
																<br/>
																<span class="col1"><b>Extras (Cad.):</b> <?php echo $extraCad; ?></span>
																<span class="col2"><b>Extras (Aniv.):</b> <?php echo $extraAni; ?></span>
																<br/>
																<span class="col1"><b>Ext. (Valor):</b> <?php echo $extraFaixaValor; ?></span>
																<span class="col2"><b>Ext. (Qt. Itens):</b> <?php echo $extraTotItens; ?></span>
																<br/>
																<span class="col2"><b>Ext. (Prod. Esp.):</b> <?php echo $extraTotProdu; ?></span>
																<br/>
																<span class="col1"><b>Resgate:</b> <?php echo $resgateTempo; ?></span>
																<span class="col2"><b>Validade:</b> <?php echo $resgateValidade; ?></span>
																<br/>
																<span class="col1"><b>Exp. Inativ.:</b> <?php echo $resgateExpira; ?></span>
																<span class="col2"><b>Fraude:</b> <?php echo $resgateFraude; ?></span>
																<br/>
																<span class="col1"><b>Vl. Min. Resgate:</b> <?php echo $qrListaCampanha['NUM_MINRESG']; ?></span>
																<span class="col2"><b>Máx. Resgate:</b> <?php echo $qrListaCampanha['PCT_MAXRESG']; ?>%</span>
																</p>
															</div>
														</li>
														

													<?php
														  }											
													
													?>

												</ul>



											</div>

											<div class="col-md-4">
											
												<h4 style="margin-bottom: 2px;">Composição da Nova Campanha </h4>
												<span class="help-block">Arraste as campanhas ao lado e monte sua <b>nova composição</b></span>
												<div class="push15"></div> 
						
												<ul id="sortable2" class="connectedSortable">
												
											

												</ul>
												
												<div class="push20"></div> 
												
												<button name="ativar" id="ativar" class="btn btn-primary btn-lg btn-block ativarCampanha disabled">&nbsp; Ativar Campanha</button>

											</div>
											
											<div class="col-md-1"></div>

											<div class="col-md-3">

												<h4 style="margin-bottom: 2px;">Campanha Ativa (Atual) </h4>
												<span class="help-block">Composição ativada em: <b>01/01/2000</b></span>
												<div class="push15"></div> 
												
												<ul id="listaAtiva" class="connectedSortable" style="border: 3px dashed #7DCEA0;">
												
												<div class="push10"></div> 												
			
													<?php
													$sql = " SELECT campanha.*,campanharegra.*,vantagemextra.*,campanharesgate.*,
																tipocampanha.ABV_TPCAMPA FROM CAMPANHA
																left join campanharegra on CAMPANHA.COD_CAMPANHA=campanharegra.COD_CAMPANHA 
																left join vantagemextra on CAMPANHA.COD_CAMPANHA=vantagemextra.COD_CAMPANHA 
																left join campanharesgate on CAMPANHA.COD_CAMPANHA=campanharesgate.COD_CAMPANHA
																inner join tipocampanha on campanha.TIP_CAMPANHA=tipocampanha.COD_TPCAMPA 
																where campanha.COD_EMPRESA=$cod_empresa and campanha.LOG_REALTIME = 'S' and campanha.LOG_ONLINE = 'S' 
																order by ord_online";
														//fnEscreve($sql);
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
														
														$count=0;
														while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery))
														  {	                                           
															$count++;                                                                                                
													?>										  

														  
														<div id="widget-<?php echo $qrListaCampanha['COD_CAMPANHA']?>" class="box widget widget-default widget-item-icon shadow" style="width: 100%;">
															<div class="widget-item-left" style="width: 50px;">
																<span class="fa <?php echo $qrListaCampanha['DES_ICONE'] ?> " style="color: #<?php echo $qrListaCampanha['DES_COR'] ?>"></span>
															</div>                             
															<div class="widget-data" style="padding-left: 70px;">
																<div class="widget-title" style="font-size: 14px;"><?php echo $qrListaCampanha['DES_CAMPANHA'] ?></div>
																<div class="widget-subtitle">
																<b>Tipo:</b> <?php echo $qrListaCampanha['ABV_TPCAMPA'] ?> <br/>
																<b>Hit:</b> <?php echo fnValor($qrListaCampanha['NUM_PESSOAS'],0); ?> <br/>
																<b>Reversão:</b> <?php echo fnValor($qrListaCampanha['PCT_VANTAGEM'],2); ?> <br/>
																</div>
															</div>      
														</div>                            


													<?php
														  }											
													
													?>													
											</ul>
											
											</div>

										</div>
										
										<div class="push10"></div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">

	$(document).ready( function() {
		
		$( ".ativarCampanha" ).click(function() {
			var idEmp = <?php echo $cod_empresa ?>;
			var array = $("#sortable2").sortable('toArray', {attribute: 'cod-campanha'});
			var jsonString = JSON.stringify(array)
			$.ajax({
				type: "GET",
				url: "ajxAtivCampanha.do",
				data: { ajx1:idEmp, ajx2:jsonString},
				beforeSend:function(){
					$('#listaAtiva').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$('#listaAtiva').html(data);
				},
				error:function(){
					//$('#sortable2 li[cod-campanha=' +codCampanha+ ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});			
		});

        $("#sortable1").sortable({
            connectWith: ".connectedSortable",
            remove: function (event, ui) {
				$('.ativarCampanha').removeClass('disabled');
            }
        }).disableSelection();	

        $("#sortable2").sortable({
            connectWith: ".connectedSortable",
			stop: function(event, ui) {
				//ordenar();
				//alert('teste ord');
			},
            remove: function (event, ui) {
				if($("#sortable2").sortable("toArray", {attribute: 'cod-campanha'}).length === 0){
					$('.ativarCampanha').addClass('disabled');
				}
            }			
        }).disableSelection();	
		

	});
		
	function retornaForm(index){
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
		$('#formulario').validator('validate');			
		$("#formulario #hHabilitado").val('S');						
	}		
		
	</script>	