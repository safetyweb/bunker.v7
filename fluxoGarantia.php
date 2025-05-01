
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
		$cod_empresa = fnLimpacampoZero(fnDecode($_GET['id']));
		$cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));
		$cod_cliente = fnLimpacampoZero(fnDecode($_GET['idC']));	
		
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}

		//busca dados do convênio
												
	}else {	
		$nom_empresa = "";
		$cod_conveni = "";
		$cod_entidad = "";
		$num_process = "";
		$num_conveni = "";
		$cod_tpconveni = "";
		$nom_conveni = "";
		$nom_abrevia = "";
		$des_descric = "";
		$val_valor = "";
		$val_conced = "";
		$val_contpar = "";
		$dat_inicinv = "";
		$dat_fimconv = "";
		$dat_assinat = "";
		$log_licitacao = "";
		$dat_aditivo = "";
		$leitura = "";
	}
	
	//busca dados do usuário
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = ".$cod_usucada;	
			
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaUsuario)){
		$nom_usuario = $qrBuscaUsuario['NOM_USUARIO'];
	}

	$sqlTasks = "SELECT * FROM TAREFA_GARANTIA 
				 WHERE COD_EMPRESA = $cod_empresa 
				 AND COD_BEM = $cod_bem
				 AND LOG_ATIVO = 'S'
				 ORDER BY NUM_ORDENAC, DAT_INI";

	$arrayTask = mysqli_query(connTemp($cod_empresa,''),$sqlTasks);

	$tasks = "";

	// {
	// 	start: '2018-10-01',
	// 	end: '2018-10-08',
	// 	name: 'Redesign website',
	// 	id: "0",
	// 	progress: 91
	// }

	while ($qrTask = mysqli_fetch_assoc($arrayTask)) {

		$tarefaPai = "";

		if($qrTask[COD_SUBTAREFA] != 0){
			$tarefaPai = "dependencies: '".$qrTask[COD_SUBTAREFA]."',";
		}

		if($qrTask['PCT_TAREFA'] >= 85){
			$corProgresso = "cor-success";	
		}else if($qrTask['PCT_TAREFA'] >= 50){
			$corProgresso = "cor-warning";
		}else{
			$corProgresso = "cor-danger";
		}


		$sqlSubTasks = "SELECT VAL_PROJETO FROM TAREFA_GARANTIA 
						 WHERE COD_EMPRESA = $cod_empresa 
						 AND COD_BEM = $cod_bem
						 AND LOG_ATIVO = 'S'
						 AND COD_TAREFA = $qrTask[COD_SUBTAREFA]
						 ORDER BY COD_TAREFA DESC";

		$arraySubTask = mysqli_query(connTemp($cod_empresa,''),$sqlSubTasks);

		$qrSub = mysqli_fetch_assoc($arraySubTask);

		// fnEscreve($qrTask['NOM_TAREFA']);
		// fnEscreve($qrTask['VAL_PROJETO']);
		// fnEscreve($qrSub['VAL_PROJETO']);

		$tasks .= "{
					start: '".$qrTask['DAT_INI']."',
					end: '".$qrTask['DAT_FIM']."',
					name: '".$qrTask['NOM_TAREFA']."',
					id: '".$qrTask['COD_TAREFA']."',
					progress: '".$qrTask['PCT_TAREFA']."',
					custom_class: '".$corProgresso."',
					".$tarefaPai."
				},";



		// $sqlSubTasks = "SELECT * FROM TAREFA 
		// 			 WHERE COD_EMPRESA = $cod_empresa 
		// 			 AND COD_CONVENIO = $cod_conveni
		// 			 AND LOG_ATIVO = 'S'
		// 			 AND COD_SUBTAREFA = $qrTask[COD_TAREFA]
		// 			 ORDER BY COD_TAREFA DESC";

		// $arraySubTask = mysqli_query(connTemp($cod_empresa,''),$sqlSubTasks);

		// while ($qrSubTask = mysqli_fetch_assoc($arraySubTask)) {

		// 	$tasks .= "{
		// 			start: '".fnDataSql($qrSubTask['DAT_INI'])."',
		// 			end: '".fnDataSql($qrSubTask['DAT_FIM'])."',
		// 			name: '".$qrSubTask['NOM_TAREFA']."',
		// 			id: '".$qrSubTask['COD_TAREFA']."',
		// 			progress: '".$qrSubTask['PCT_TAREFA']."',
		// 			dependencies: '".$qrSubTask[COD_SUBTAREFA]."'
		// 		},";

		// }

	}
	  

	// $sql = "SELECT * FROM CONTROLE_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa AND TIP_CONTROLE = 'BLM' AND COD_CONVENI = $cod_conveni";

	// fnEscreve($sql);    
	
	// echo "<pre>";
	// print_r($tasks);
	// echo "</pre>";

?>
<!-- <link rel="stylesheet" href="js/plugins/ganttView/dist/frappe-gantt.css" />
<script src="js/plugins/ganttView/dist/frappe-gantt.js"></script> -->

<!-- <link rel="stylesheet" href="js/plugins/simple-gantt/frappe-gantt.css" /> -->
<!-- <script src="js/plugins/simple-gantt/frappe-gantt.js"></script> -->
<link rel="stylesheet" href="js/new-gantt-master/dist/frappe-gantt.css?v=<?=date("Ymdhis")?>" />
<script src="js/new-gantt-master/dist/frappe-gantt.js?v=<?=date("Ymdhis")?>"></script>


<style type="text/css">
	.outline{
		outline:0px!important;
	}
	.container{
		width:100%!important;
	}
	.gantt .handle {
	  display: none;
	}
	.cor-success .bar-progress{
		fill: #18BC9C;
	}

	.cor-warning .bar-progress{
		fill: #F39C12;
	}

	.cor-danger .bar-progress{
		fill: #D62C1A;
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
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"><?php echo $NomePg; ?> <!--/ <?php echo $nom_empresa; ?>--></span>
					</div>
					<?php include "atalhosPortlet.php"; ?>
				</div>
				<?php } ?>					
				
				<div class="push10"></div>

				<div class="push30"></div>

				<div class="portlet-body">
					
					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>	
				
					<?php

						if($popUp != 'true'){
					?>

						<?php include "bensHeader.php"; ?>

					<?php 
						}
					?>	
					
					<div class="push30"></div> 

					<div class="row">
						
						<div class="col-md-3 col-md-offset-9">
							<a href="javascript:void(0)" class="btn btn-info btn-sm addBox pull-right" data-title="Nova Tarefa" data-url="action.php?mod=<?php echo fnEncode(1981)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=fnEncode($cod_cliente)?>&idBem=<?=fnEncode($cod_bem)?>&pop=true">
								<span class="fal fa-plus"></span>&nbsp; Nova tarefa
							</a>
						</div>						

					</div>

					<!-- <div class="row">
						<div class="col-md-2">
							<a href="javascript:void(0)" class="btn btn-info " >
								<span class="fal fa-shopping-cart"></span>&nbsp; Teste Mu
							</a>
						</div>
					</div> -->

					<div class="push30"></div>
					
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						
							<!-- gráfico gantt -->
							<div class="container">
								<div class="gantt-target"></div>
							</div>							
						
						</form>

																	
					
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
	
	<script type="text/javascript">

	var tasks = [<?=$tasks?>];

	var gantt_chart = new Gantt(".gantt-target", tasks, {
	on_click: function (task) {
		abreModalTask(task);
	},
	on_date_change: function(task, start, end) {
		console.log(task, start, end);
	},
	on_progress_change: function(task, progress) {
		console.log(task, progress);
	},
	on_view_change: function(mode) {
		console.log(mode);
	},
	view_mode: 'Week',
	language: 'en',
	popup_trigger: 'none',
	});
	// console.log(gantt_chart);

	function abreModalTask(task){
		// console.log(JSON.stringify(task));
		var popLink = "action.php?mod=<?php echo fnEncode(1981)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=fnEncode($cod_cliente)?>&idBem=<?=fnEncode($cod_bem)?>&idt="+JSON.stringify(task)+"&pop=true";
		var popTitle = task.name;
		//alert(popLink);	
		setIframe(popLink, popTitle);
		$('.modal').not('#popModalNotifica').appendTo("body").modal('show');
	}
	
	</script>	

