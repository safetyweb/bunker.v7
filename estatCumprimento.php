<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$dat_ini2="";
	$dat_fim2="";
	$cod_persona = 0;
	$hashLocal = mt_rand();
	$tip_relat = 1;
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	
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
			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_univend = $_POST['COD_UNIVEND'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$dat_ini2 = fnDataSql($_POST['DAT_INI2']);
			$dat_fim2 = fnDataSql($_POST['DAT_FIM2']);
			$tip_relat = fnLimpacampo($_REQUEST['TIP_RELAT']);	
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];

			if (isset($_POST['COD_PERSONA'])){
				$cod_persona = "";
				$Arr_COD_PERSONA = $_POST['COD_PERSONA'];			 
				 
				   for ($i=0;$i<count($Arr_COD_PERSONA);$i++) 
				   { 
					$cod_persona = $cod_persona.$Arr_COD_PERSONA[$i].",";
				   } 
				   
				   $cod_persona = ltrim(rtrim($cod_persona,','),',');
					
			}else{$cod_persona = "0";}	

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){

			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);

			$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
		
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
				
			if (isset($qrBuscaTemplate)){
				$cod_conveni = $qrBuscaTemplate['COD_CONVENI'];
				$cod_entidad = $qrBuscaTemplate['COD_ENTIDAD'];
				$num_process = $qrBuscaTemplate['NUM_PROCESS'];
				$num_conveni = $qrBuscaTemplate['NUM_CONVENI'];
				$cod_tpconveni = $qrBuscaTemplate['COD_TPCONVENI'];
				$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
				$nom_abrevia = $qrBuscaTemplate['NOM_ABREVIA'];
				$des_descric = $qrBuscaTemplate['DES_DESCRIC'];
				$val_valor = fnValor($qrBuscaTemplate['VAL_VALOR'],2);
				$val_conced = fnValor($qrBuscaTemplate['VAL_CONCED'],2);
				$val_contpar = fnValor($qrBuscaTemplate['VAL_CONTPAR'],2);
				$dat_inicinv = fnDataShort($qrBuscaTemplate['DAT_INICINV']);
				$dat_fimconv = fnDataShort($qrBuscaTemplate['DAT_FIMCONV']);
				$dat_assinat = fnDataShort($qrBuscaTemplate['DAT_ASSINAT']);
				$log_licitacao = $qrBuscaTemplate['LOG_LICITACAO'];
			
			}

		}
	}
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" && strlen($dat_ini2) == 0 || $dat_ini2 == "1969-12-31"){
		$dat_ini = fnDataSql($dias30); 
		$dat_ini2 = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31" && strlen($dat_fim2) == 0 || $dat_fim2 == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
		$dat_fim2 = fnDataSql($hoje); 
	}
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 

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

	$sql = "SELECT SUM(A.VAL_VALOR) AS VALOR_CONVENIO,SUM(A.VAL_CONVENI)AS VALOR_CONCEDENTE,SUM(A.VAL_CONTPAR)AS VAL_CONTRAPARTIDA,B.NOM_CONVENI,B.NUM_CONVENI
			FROM CONTRATO A,CONVENIO B 
			WHERE 
			A.COD_CONVENI=B.COD_CONVENI AND 
			A.COD_CONVENI = $cod_conveni AND 
			A.DES_TPCONTRAT='CON'";	
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrConveni = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrConveni)){
		$nom_conveni = $qrConveni['NOM_CONVENI'];
		$num_conveni = $qrConveni['NUM_CONVENI'];
		$valor_convenio = $qrConveni['VALOR_CONVENIO'];
		$valor_concedente = $qrConveni['VALOR_CONCEDENTE'];
		$val_contrapartida = $qrConveni['VAL_CONTRAPARTIDA'];
	}

	// fnescreve($val_recebido);
	// fnescreve($debitos_convenio);


	$log_labels = 'S';
	
	//fnMostraForm();	
	//fnEscreve($dat_ini);
	//fnEscreve($dat_fim);
	//fnEscreve($cod_univendUsu);
	//fnEscreve($qtd_univendUsu);
	//fnEscreve($lojasAut);
	//fnEscreve($usuReportAdm);
	//fnEscreve($lojasReportAdm);
	
?>

<style>

.circle {
  width: 120px;
  margin: 6px 6px 20px;
  display: inline-block;
  position: relative;
  text-align: center;
  line-height: 1.2;
}

.circle canvas {
  vertical-align: top;
  width: 120px !important;
}

.circle strong {
  position: absolute;
  top: 23.5%;
	  left: 0;
  width: 100%;
  text-align: center;
  line-height: 40px;
  font-size: 16px;
  font-weight: normal!important;
  color: #17202A;
}

.circle strong i {
  font-style: normal;
  font-size: 0.6em;
  font-weight: normal;
}

.circle span {
  display: block;
  color: #aaa;
  margin-top: 12px;
}

table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
.table-bordered:hover{
    z-index: 2;
}
.p1  {
    background-color: #f8f9f9;
	z-index: 0;
}

tr:hover{
	background-color: #ECF0F1!important;

}

tr:hover td {
    background-color: transparent; /* or #000 */
}
/*.drop-shadow {
    -webkit-box-shadow: 0 0 5px 2px #ECEFF2;
    box-shadow: 0 0 5px 2px #ECEFF2;
    border-radius:5px;
}*/

.graficoRedondo{
	position: relative; width: 100%;
}
</style>
		
<div class="push30"></div> 

<div class="row" id="div_Report">
        
	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>
				
				<?php 
				//$formBack = "1015";
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
					
			
				<div class="login-form">

					<div class="push30"></div> 
							
						<div class="row">  

							<div class="col-md-1">
								
								<div class="tabbable-line">
			
									<ul class="nav nav-tabs ">
										<li>
											<a href="action.do?mod=<?php echo fnEncode(1348)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
											<span class="fal fa-arrow-circle-left fa-2x"></span></a>
										</li>
									</ul>
								</div>	

							</div>
								
							<div class="col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label">Nome</label>
									<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni; ?>" maxlength="60" readonly>
								</div>
								<div class="help-block with-errors"></div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Inicial</label>
									<input type='text' class="form-control input-sm data leitura" name="DAT_INICINV" id="DAT_INICINV" value="<?=$dat_inicinv?>" readonly/>
								</div>
							</div>       
				
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Final</label>
									<input type='text' class="form-control input-sm data leitura" name="DAT_FIMCONV" id="DAT_FIMCONV" value="<?=$dat_fimconv?>" readonly/>
								</div>
							</div>						
											
							<?php																	
								$sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
								//fnEscreve($cod_entidad);
								while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
								{
								?>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Entidade</label>
										<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $qrListaTipoEntidade['NOM_ENTIDAD']; ?>" maxlength="60" readonly>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								
								<?php 													
								}											
							?>	
										

						</div>

						<div class="push30"></div>

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

					</form>
												
				</div>

			</div>

		</div>

		<div class="push20"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

					<div class="push30"></div>

					<div class="row">
						
						<div class="form-group text-center col-md-4">
										
							<h4>Valor do Convênio</h4>
							<div class="push20"></div>
							
							<div>
								<canvas id="doughnut1" class="graficoRedondo"></canvas>
							</div>												

						</div>

						<div class="form-group text-center col-md-4">
										
							<h4>Valor Recebido</h4>
							<div class="push20"></div>
							
							<div>
								<canvas id="doughnut2" class="graficoRedondo"></canvas>
							</div>												

						</div>

						<div class="form-group text-center col-md-4">
										
							<h4>Saldo</h4>
							<div class="push20"></div>
							
							<div>
								<canvas id="doughnut3" class="graficoRedondo"></canvas>
							</div>												

						</div>


					</div>

					<div class="push30"></div>
	
				</div>
    
			</div>

		</div><!-- fim Portlet -->

		<div class="push30"></div>

		<div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

					<div class="push30"></div>

					<div class="row text-center">			
											
						<div class="form-group text-center col-md-4 col-md-offset-2">
										
							<h4>Concedente</h4>
							<div class="push20"></div>
							
							<div >
								<canvas id="doughnut4" class="graficoRedondo"></canvas>
							</div>												

						</div>

						<div class="form-group text-center col-md-4 ">
										
							<h4>Convenente</h4>
							<div class="push20"></div>
							
							<div >
								<canvas id="doughnut5" class="graficoRedondo"></canvas>
							</div>												

						</div>

					</div>

				</div>

			</div>

		</div><!-- fim Portlet -->

		<!-- <div class="push30"></div>

		<div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

					<div class="push30"></div>

					<div class="row text-center">			
											
						<div class="form-group text-center col-md-10">
						
							<div class="push20"></div>
							<h4>Linha 2</h4>
							<div class="push20"></div>
							
							<div style="height: 200px; width:100%;">
								<canvas id="lineChart2" ></canvas>
							</div>

						</div>

						<div class="col-md-2">
							
						</div>

					</div>

				</div>

			</div>

		</div> --><!-- fim Portlet -->

		<div class="push30"></div>

	</div>

</div>
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script> 
	<?php
		if($log_labels == 'S'){
	?>
			<!-- Script dos labels -->
			<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

	<?php
		}
	?> 
	<!-- Script dos labels -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script> -->
	<!-- --------------------------------------------------------------------------- -->
	<script src="js/pie-chart.js"></script>
    <script src="js/plugins/Chart_Js/utils.js"></script>	
	
    <script>
	
		//datas
		$(function () {

			var persona = '<?php echo $cod_persona; ?>';
			if(persona != 0 && persona != ""){
				//retorno combo multiplo - USUARIOS_ENV
				$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

				var sistemasUni = '<?php echo $cod_persona; ?>';				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_PERSONA").trigger("chosen:updated");
			}
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});
				
			$(".exportarCSV").click(function() {
				$.confirm({
					title: 'Exportação',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
					'</div>' +
					'</form>',
					buttons: {
						formSubmit: {
							text: 'Gerar',
							btnClass: 'btn-blue',
							action: function () {
								var nome = this.$content.find('.nome').val();
								if(!nome){
									$.alert('Por favor, insira um nome');
									return false;
								}
								
								$.confirm({
									title: 'Mensagem',
									type: 'green',
									icon: 'fa fa-check-square-o',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxPercPeriodoPersonas.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											console.log(response);
										}).fail(function(){
											self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										});
									},							
									buttons: {
										fechar: function () {
											//close
										}									
									}
								});								
							}
						},
						cancelar: function () {
							//close
						},
					}
				});				
			});

			// function escondeUnidade(id_linha){

			// }

			var ctx = document.getElementById("doughnut1");
			var myChart = new Chart(ctx, {
			  type: 'doughnut',
			  data: {
			    labels: ['Concedente', 'Convenente'],
			    datasets: [{
			    	<?php if($log_labels == 'S'){ ?>
				  	datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: [
					        '#DCE4EC',
					        '#5AA9E1'
					    ],
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				  <?php } ?>
			      data: [<?=$valor_concedente?>, <?=$val_contrapartida?>],
			      backgroundColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderWidth: 1
			    }]
			  },
			  <?php if($log_labels == 'S'){ ?>
				plugins: [ChartDataLabels],
			  <?php } ?>
			  options: {
			  	tooltips: {
			      callbacks: {
			        title: function(tooltipItem, data) {
			          return data['labels'][tooltipItem[0]['index']];
			        },
			        label: function(tooltipItem, data) {
			          if(parseInt(data['datasets'][0]['data'][tooltipItem['index']]) >= 1000){
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		              } else {
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']];
		              }
			        },
			        // afterLabel: function(tooltipItem, data) {
			        //   var dataset = data['datasets'][0];
			        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
			        //   return '(' + percent + '%)';
			        // }
			      }
			    },
			    title: {
					display: true,
					position: 'bottom',
					text: "Total: R$<?=fnValor($valor_convenio,2)?>" 
			  	},
			   	//cutoutPercentage: 40,
			    responsive: true,

			  }
			});

			var ctx = document.getElementById("doughnut2");
			var myChart = new Chart(ctx, {
			  type: 'doughnut',
			  data: {
			    labels: ['Concedente', 'Convenente'],
			    datasets: [{
			    <?php if($log_labels == 'S'){ ?>
				  	datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: [
					        '#DCE4EC',
					        '#5AA9E1'
					    ],
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
			      data: [<?=$creditos_concedente?>, <?=$creditos_convenente?>],
			      backgroundColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderWidth: 1
			    }]
			  },
			  options: {
			  	<?php if($log_labels == 'S'){ ?>
					plugins: [ChartDataLabels],
				<?php } ?>
			  	tooltips: {
			      callbacks: {
			        title: function(tooltipItem, data) {
			          return data['labels'][tooltipItem[0]['index']];
			        },
			        label: function(tooltipItem, data) {
			          if(parseInt(data['datasets'][0]['data'][tooltipItem['index']]) >= 1000){
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		              } else {
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']];
		              }

			        },
			        // afterLabel: function(tooltipItem, data) {
			        //   var dataset = data['datasets'][0];
			        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
			        //   return '(' + percent + '%)';
			        // }
			      }
			    },
			    title: {
					display: true,
					position: 'bottom',
					text: "Total: R$<?=fnValor($val_recebido,2)?>" 
			  	},
			   	//cutoutPercentage: 40,
			    responsive: true,

			  }
			});

			var ctx = document.getElementById("doughnut3");
			var myChart = new Chart(ctx, {
			  type: 'doughnut',
			  data: {
			    labels: ['Receita', 'Despesa'],
			    datasets: [{
			    <?php if($log_labels == 'S'){ ?>
				  	datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: [
					        '#DCE4EC',
					        '#5AA9E1'
					    ],
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
			      data: [<?=$val_recebido?>, <?=$debitos_convenio?>],
			      backgroundColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderWidth: 1
			    }]
			  },
			  <?php if($log_labels == 'S'){ ?>
					plugins: [ChartDataLabels],
			  <?php } ?>
			  options: {
			  	tooltips: {
			      callbacks: {
			        title: function(tooltipItem, data) {
			          return data['labels'][tooltipItem[0]['index']];
			        },
			        label: function(tooltipItem, data) {
			          if(parseInt(data['datasets'][0]['data'][tooltipItem['index']]) >= 1000){
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		              } else {
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']];
		              }

			        },
			        // afterLabel: function(tooltipItem, data) {
			        //   var dataset = data['datasets'][0];
			        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
			        //   return '(' + percent + '%)';
			        // }
			      }
			    },
			    title: {
					display: true,
					position: 'bottom',
					text: "Saldo Líquido: R$<?=fnValor($val_recebido-$debitos_convenio,2)?>"
			  	},
			   	//cutoutPercentage: 40,
			    responsive: true,

			  }
			});

			var ctx = document.getElementById("doughnut4");
			var myChart = new Chart(ctx, {
			  type: 'doughnut',
			  data: {
			    labels: ['Concedente Convênio', 'Concedente Recebido'],
			    datasets: [{
			   	<?php if($log_labels == 'S'){ ?>
				  	datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: [
					        '#DCE4EC',
					        '#5AA9E1'
					    ],
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
			      data: [<?=$valor_concedente?>, <?=$creditos_concedente?>],
			      backgroundColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderWidth: 1
			    }]
			  },
			  <?php if($log_labels == 'S'){ ?>
					plugins: [ChartDataLabels],
			  <?php } ?>
			  options: {
			  	tooltips: {
			      callbacks: {
			        title: function(tooltipItem, data) {
			          return data['labels'][tooltipItem[0]['index']];
			        },
			        label: function(tooltipItem, data) {
			          if(parseInt(data['datasets'][0]['data'][tooltipItem['index']]) >= 1000){
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		              } else {
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']];
		              }

			        },
			        // afterLabel: function(tooltipItem, data) {
			        //   var dataset = data['datasets'][0];
			        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
			        //   return '(' + percent + '%)';
			        // }
			      }
			    },
			    title: {
					display: true,
					position: 'bottom',
					text: "Total: R$<?=fnValor($valor_concedente-$creditos_concedente,2)?>"
			  	},
			   	//cutoutPercentage: 40,
			    responsive: true,

			  }
			});

			var ctx = document.getElementById("doughnut5");
			var myChart = new Chart(ctx, {
			  type: 'doughnut',
			  data: {
			    labels: ['Convenente Convênio', 'Convenente Recebido'],
			    datasets: [{
			    <?php if($log_labels == 'S'){ ?>
				  	datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: [
					        '#DCE4EC',
					        '#5AA9E1'
					    ],
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
			      data: [<?=$val_contrapartida?>, <?=$creditos_convenente?>],
			      backgroundColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderColor: [
			        '#DCE4EC',
			        '#5AA9E1'
			      ],
			      borderWidth: 1
			    }]
			  },
			  <?php if($log_labels == 'S'){ ?>
					plugins: [ChartDataLabels],
			  <?php } ?>
			  options: {
			  	tooltips: {
			      callbacks: {
			        title: function(tooltipItem, data) {
			          return data['labels'][tooltipItem[0]['index']];
			        },
			        label: function(tooltipItem, data) {
			          if(parseInt(data['datasets'][0]['data'][tooltipItem['index']]) >= 1000){
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		              } else {
		                return 'R$ ' + data['datasets'][0]['data'][tooltipItem['index']];
		              }

			        },
			        // afterLabel: function(tooltipItem, data) {
			        //   var dataset = data['datasets'][0];
			        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
			        //   return '(' + percent + '%)';
			        // }
			      }
			    },
			    title: {
					display: true,
					position: 'bottom',
					text: "Total: R$<?=fnValor($val_contrapartida-$creditos_convenente,2)?>"
			  	},
			   	//cutoutPercentage: 40,
			    responsive: true,

			  }
			});

			
		});


		
	</script>	
   