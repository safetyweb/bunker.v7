<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 100;
	
	// Página default
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	
	$cod_externo = "";
	$cod_empresa = "";
	$nom_chamado = "";

	$cod_tpsolicitacao = "";
	$cod_status = "";
	$cod_status_exc = "10,6";
	$cod_tipo_exc = "21";
	$cod_integradora = "";
	$cod_plataforma = "";
	$cod_versaointegra = "";
	$cod_prioridade = "";
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$cod_univend = "9999"; //todas revendas - default

	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$cod_usuario = $cod_usucada;
	$cod_usures = $cod_usucada;
	
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
			$cod_vendapdv = $_POST['COD_VENDAPDV'];

			
			
			
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
	
	
	$eventos = [];

	$sql = "SELECT EV.*, TE.DES_COR, UE.COD_USUARIO FROM EVENTOS_AGENDA EV 
	LEFT JOIN TIPO_EVENTO TE ON TE.COD_TPEVENT = EV.COD_TPEVENT
	LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EV.COD_EVENT
	WHERE EV.COD_EMPRESA = $cod_empresa
	AND UE.COD_USUARIO = $_SESSION[SYS_COD_USUARIO]
	";

	// echo("_".$sql."_");

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	while($qrEvento = mysqli_fetch_assoc($arrayQuery)){

		$sqlUsu = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrEvento[COD_USUARIO]";
		$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsu));

		$titulo = "(".fnIniciais($qrUsu['NOM_USUARIO']).") ".$qrEvento['NOM_EVENT'];

		if(strlen($qrEvento['HOR_INI']) == 1){
			$horaIni = "0".$qrEvento['HOR_INI'];
		}else{
			$horaIni = $qrEvento['HOR_INI'];
		}
		if(strlen($qrEvento['HOR_FIM']) == 1){
			$horaFim = "0".$qrEvento['HOR_FIM'];
		}else{
			$horaFim = $qrEvento['HOR_FIM'];
		}

		if($horaIni == $horaFim){
			$horaFim++;
		}

		$inicio = $qrEvento['DAT_INI']." ".$horaIni.":00";
		$fim = $qrEvento['DAT_FIM']." ".$horaFim.":00";

		if($qrEvento['DIAS_REPETE'] != ''){

			$inicioRepete = $qrEvento['DAT_INI'];
			$fimRepete = date('Y-m-d', strtotime("+1 day", strtotime($qrEvento['DAT_FIM'])));
			$array_repete = explode(',', $qrEvento["DIAS_REPETE"]);

			$evento = [
					"title" => $titulo,
					"id" => $qrEvento['COD_EVENT'],
					"start" => $inicio,
					"end" => $fim,				
					"color" => $qrEvento['DES_COR'],
					"daysOfWeek" => json_encode($array_repete),
					"startTime" => $horaIni.":00",
					"endTime" => $horaFim.":00",
					"startRecur" => $inicioRepete,
					"endRecur" => $fimRepete

				  ];
		
		}else{

			$evento = [
					"title" => $titulo,
					"id" => $qrEvento['COD_EVENT'],
					"start" => $inicio,
					"end" => $fim,				
					"color" => $qrEvento['DES_COR']

				  ];

		}
		
		

		array_push($eventos, $evento);

	}

	$eventos = json_encode($eventos);

?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
.rounded-shadow{
	-webkit-box-shadow: 0px 0px 7px 0px rgba(237,237,237,1);
	-moz-box-shadow: 0px 0px 7px 0px rgba(237,237,237,1);
	box-shadow: 0px 0px 7px 0px rgba(237,237,237,1);
	border-radius: 4px 4px 4px 4px;
	-moz-border-radius: 4px 4px 4px 4px;
	-webkit-border-radius: 4px 4px 4px 4px;
	border: 0px solid #000000;
}
.table-small{
	height: 350px!important;
}
.badge{
    display: table;
    border-radius: 30px 30px 30px 30px;
    width: 26px;
    height: 26px;
    text-align: center;
    color:white;
    font-size:11px;
    margin-right: auto;
    margin-left: auto;
}

.txtBadge{
	display: table-cell;
	vertical-align: middle;
}
.pitstop{
	background:#d98880;
	color:#FFF;
	padding:1px 5px 2px 5px;
	border-radius:3px;
}
.pitstop:hover{
	color:#FFF;
}


/*state overview*/

.state-overview .symbol, .state-overview .value {
    display: inline-block;
    text-align: center;
}

.state-overview .value  {
    float: right;

}

.state-overview .value h1, .state-overview .value p  {
    margin: 0;
    padding: 0;
    color: #c6cad6;
}

.state-overview .value h1 {
    font-weight: 300;
}

.state-overview .symbol i {
    color: #fff;
    font-size: 50px;
}

.state-overview .symbol {
    width: 40%;
    padding: 25px 15px;
    -webkit-border-radius: 4px 0px 0px 4px;
    border-radius: 4px 0px 0px 4px;
}

.state-overview .value {
    width: 58%;
    padding-top: 21px;
}

.state-overview .terques {
    background: #6ccac9;
}

.state-overview .red {
    background: #ff6c60;
}

.state-overview .yellow {
    background: #f8d347;
}

.state-overview .blue {
    background: #57c8f2;
}




ul.summary-list {
    display: inline-block;
    padding-left:0 ;
    width: 100%;
    margin-bottom: 0;
}

ul.summary-list > li {
    display: inline-block;
    width: 19.5%;
    text-align: center;
}

ul.summary-list > li > a > i {
    display:block;
    font-size: 18px;
    padding-bottom: 5px;
}

ul.summary-list > li > a {
    padding: 10px 0;
    display: inline-block;
    color: #818181;
}

ul.summary-list > li  {
    border-right: 1px solid #eaeaea;
}

ul.summary-list > li:last-child  {
    border-right: none;
}

.fc-event-container a{
	text-decoration: none!important;
	font-weight: bold!important;
	cursor: pointer;
}

.fc-center h2, .fc-day-header{
	font-size: 14px!important;
	font-weight: 700;
}

/*
#taskbar{
	height: 750px;
}
*/

.badge{
    display: table-cell;
    border-radius: 30px 30px 30px 30px;
    width: 23px!important;
    height: 23px!important;
    /*text-align: center;*/
    color:white;
    font-size:11px;
    /*margin-right: auto;
    margin-left: auto;*/
}

.txtBadge{
	display: table-cell;
	vertical-align: middle;
}

.txtSideBadge{
	position: relative;
	display: table-cell;
}

.fixed-hg{
	height: 450px!important;
	/*overflow: auto!important;*/
}
	
.fc-list-view {
  border-style: none !important;
}

.fc-list-heading-alt{
	display: none!important;
}

::-webkit-scrollbar {
    width: 0px;
    background: transparent; /* make scrollbar transparent */
}

.slimScrollBar{
	background-color: rgb(155, 155, 155)!important;
}

</style>

		
	<div class="push30"></div> 

	
	<?php
	//fnEscreve($_SESSION["SYS_COD_USUARIO"]);
	//usuários: adilson.damaris, rone, adilson, ricardo  
	if ($_SESSION["SYS_COD_USUARIO"] ==  33125 || $_SESSION["SYS_COD_USUARIO"] ==  16928 || $_SESSION["SYS_COD_USUARIO"] ==  28 || $_SESSION["SYS_COD_USUARIO"] ==  11478){
	?>

	<link href='js/plugins/fullcalendar/core/main.css' rel='stylesheet' />
    <link href='js/plugins/fullcalendar/daygrid/main.css' rel='stylesheet' />
    <link href='js/plugins/fullcalendar/timegrid/main.css' rel='stylesheet' />
    <link href='js/plugins/fullcalendar/list/main.css' rel='stylesheet' />
    <link href='js/plugins/fullcalendar/bootstrap/main.css' rel='stylesheet' />

    <script src='js/plugins/fullcalendar/core/main.js'></script>
    <script src='js/plugins/fullcalendar/daygrid/main.js'></script>
    <script src='js/plugins/fullcalendar/timegrid/main.js'></script>
    <script src='js/plugins/fullcalendar/list/main.js'></script>
    <script src='js/plugins/fullcalendar/bootstrap/main.js'></script>

    <script type="text/javascript" src="js/plugins/fullcalendar/core/locales-all.min.js"></script>	
	
	<div class="row">
	  <div class="col-lg-12">
	  
		  <section class="panel">
		  
			  <div class="panel-body">
			  
				<h4 class="pull-left" style="margin-left: 5px;">Meus objetivos</h4>
				<div class="push20"></div> 
				
				  <ul class="summary-list">
					  <li>
						  <a href="javascript:;">
							  <i class=" fa fa-shopping-cart text-primary"></i>
							  1 Purchase
						  </a>
					  </li>
					  <li>
						  <a href="javascript:;">
							  <i class="fa fa-envelope text-info"></i>
							  15 Emails
						  </a>
					  </li>
					  <li>
						  <a href="javascript:;">
							  <i class=" fa fa-picture-o text-muted"></i>
							  2 Photo Upload
						  </a>
					  </li>
					  <li>
						  <a href="javascript:;">
							  <i class="fa fa-tags text-success"></i>
							  19 Sales
						  </a>
					  </li>
					  <li>
						  <a href="javascript:;">
							  <i class="fa fa-microphone text-danger"></i>
							  4 Audio
						  </a>
					  </li>
				  </ul>
			  </div>
		  </section>
	  </div>
	  
	</div>					
	
	<div class="push15"></div> 
	
	<div class="row">
		
		<div class="col-md-6">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body fixed-hg">						
				
					<div class="no-more-tables">

						<div class="col-md-6">
							<h4>Agenda da Semana</h4>
						</div>

						<div class="col-md-6 text-right">
							<a href="action.php?mod=<?php echo fnEncode(1400)?>&id=<?php echo fnEncode($cod_empresa)?>" target="_blank" class="btn btn-xs btn-default transparency">Acessar</a>
						</div>

						<div class="push10"></div>

						<div class="col-md-12">
										
								<div id="calendar"></div>

								

								<?php

									// setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
									// date_default_timezone_set('America/Sao_Paulo');

									// $dt_min = new DateTime("last saturday"); // Edit
									// $dt_min->modify('+1 day'); // Edit
									// $dt_max = clone($dt_min);
									// $dt_max->modify('+6 days');

									// $dat_ini = $dt_min->format('Y-m-d');
									// $dat_fim = $dt_max->format('Y-m-d');


									// $sqlAgenda = "SELECT EV.*, TE.DES_COR, UE.COD_USUARIO FROM EVENTOS_AGENDA EV 
									// 				LEFT JOIN TIPO_EVENTO TE ON TE.COD_TPEVENT = EV.COD_TPEVENT
									// 				LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EV.COD_EVENT
									// 				WHERE EV.COD_EMPRESA = $cod_empresa
									// 				AND EV.DAT_FIM BETWEEN '$dat_ini' AND '$dat_fim'
									// 				AND UE.COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";

									// $arrayAgenda = mysqli_query(connTemp($cod_empresa,''),$sqlAgenda);

									// while($qrAgenda = mysqli_fetch_assoc($arrayAgenda)){


									// 	if($qrAgenda[DIAS_REPETE] != ""){

									// 		$start    = new DateTime($dat_ini);
									// 		$end      = new DateTime($dat_fim);
									// 		$interval = DateInterval::createFromDateString('1 day');
									// 		$period   = new DatePeriod($start, $interval, $end);

									// 		$diasComp = explode(',', $qrAgenda[DIAS_REPETE]);

									// 		// print_r($diasComp);
									// 		if(count($diasComp) > 0){

									// 			$cont = 0;

									// 			foreach ($period as $dt) {


									// 				if($diasComp[$cont] == 0){
									// 					$comp = 7;
									// 				}else{
									// 					$comp = $diasComp[$cont];
									// 				}

									// 			    if ($dt->format("N") == $comp) {
									// 			        echo $dt->format("l Y-m-d") . "<br>\n";
									// 			    }

									// 			}

									// 		}else{

									// 			// foreach ($period as $dt) {

									// 			// 	if($diasComp[0] == 0){
									// 			// 		$comp = '$dt->format("N") == 7';
									// 			// 	}else{
									// 			// 		$comp = '$dt->format("N") == $diasComp[0]';
									// 			// 	}

									// 			// 	echo $comp;

									// 			//     if ($comp) {
									// 			//         echo $dt->format("l Y-m-d") . "<br>\n";
									// 			//     }
									// 			// }

									// 		}

											

									// 	}else{

									// 	}

									// 	$dia = strftime('%d', strtotime($qrAgenda[DAT_INI]));
									// 	$mes = ucfirst(substr(strftime('%B', strtotime($qrAgenda[DAT_INI])),0,3));
									// 	$diaSem = ucfirst(strftime('%A', strtotime($qrAgenda[DAT_INI])));

								?>

									<!-- <div class="col-md-12">

										<h4>
											<?=$dia.".".$mes." ".$diaSem?>
										</h4>

										<p>
											<span><?=$qrAgenda[HOR_INI]." - ".$qrAgenda[HOR_FIM]?></span>&nbsp;
											<span class="fas fa-circle" style="color: <?=$qrAgenda[DES_COR]?>"></span>&nbsp;
											<?=$qrAgenda[NOM_EVENT]?>&nbsp;
											<?=$qrAgenda[DES_EVENT]?>
										</p>

										<div class="push5"></div>

									</div> -->

										  
										
											
								
										

								<?php 

									// }

								?>

								

							</div>

						</div>

					</div>							
					
				</div>
			</div>
			

		<div class="col-md-6">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow ">
			
				<div class="portlet-body fixed-hg">

					<div class="no-more-tables">

						<div class="col-md-6">
							<h4>Atendimentos Recentes</h4>
						</div>

						<div class="col-md-6 text-right">
							<a href="action.php?mod=<?php echo fnEncode(1435)?>&id=<?php echo fnEncode($cod_empresa)?>" target="_blank" class="btn btn-xs btn-default transparency">Acessar</a>
						</div>

						<div class="push10"></div>
				
						<form name="formLista">
						
						<table class="table table-bordered table-striped table-hover">
						  <thead>
							<tr>
							  <th><small>Chamado</small></th>
							  <th><small>Título</small></th>
							  <th><small>Solicitante</small></th>
							  <th><small>Solicitação</small></th>
							  <!-- <th><small>Responsável</small></th> -->
							  <th><small>Prioridade</small></th>
							  <th><small>Status</small></th>
							  <th><small>Cadastro</small></th>
							  <th><small>Prazo</small></th>
							  <!-- <th><small>Atualizado</small></th> -->
							</tr>
						  </thead>
						<tbody id="relatorioConteudo">
						  
						<?php
						
							$sqlCount = "SELECT COD_ATENDIMENTO FROM ATENDIMENTO_CHAMADOS AC 
						  				WHERE AC.COD_EMPRESA = $cod_empresa 
										AND DATE_FORMAT(AC.DAT_CADASTR, '%Y-%m-%d') <= '$hoje' 
										AND AC.COD_USURES = $_SESSION[SYS_COD_USUARIO] 
										AND AC.COD_STATUS NOT IN(13)												  				
										";
							// fnEscreve($sqlCount);
							
							$retorno = mysqli_query(connTemp($cod_empresa,''),$sqlCount);
							$total_itens_por_pagina = mysqli_num_rows($retorno);
							
							$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

							//variavel para calcular o início da visualização com base na página atual
							$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
						
							$sqlSac = "SELECT AC.*, AT.DES_TPSOLICITACAO, 
										AP.DES_PRIORIDADE, AP.DES_COR AS COR_PRIORIDADE, AP.DES_ICONE AS ICO_PRIORIDADE,
										AST.ABV_STATUS, AST.DES_COR AS COR_STATUS, AST.DES_ICONE AS ICO_STATUS 
										FROM ATENDIMENTO_CHAMADOS AC
										LEFT JOIN ATENDIMENTO_PRIORIDADE AP ON AP.COD_PRIORIDADE = AC.COD_PRIORIDADE
										LEFT JOIN ATENDIMENTO_STATUS AST ON AST.COD_STATUS = AC.COD_STATUS
										LEFT JOIN ATENDIMENTO_TPSOLICITACAO AT ON AT.COD_TPSOLICITACAO = AC.COD_TPSOLICITACAO
										WHERE AC.COD_EMPRESA = $cod_empresa 
										AND DATE_FORMAT(AC.DAT_CADASTR, '%Y-%m-%d') <= '$hoje' 
										AND AC.COD_USURES = $_SESSION[SYS_COD_USUARIO] 
										AND AC.COD_STATUS NOT IN(13)
										ORDER BY AC.COD_ATENDIMENTO DESC limit $inicio,$itens_por_pagina
										";
							// fnEscreve($sqlSac);

							$arrayQuerySac = mysqli_query(connTemp($cod_empresa,''),$sqlSac);
							
							$count=0;
							$adm="";
							$entrega = "";
							while ($qrSac = mysqli_fetch_assoc($arrayQuerySac))
							 {	

							 	if($qrSac['LOG_ADM'] == 'S'){
							 		$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
							 	}else{
							 		$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
							 	}

								$count++;


								$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_SOLICITANTE]) AS NOM_SOLICITANTE,
														(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
								$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsuarios));
								//fnEscreve($sqlUsuarios);										  

								if($qrSac['DAT_ENTREGA'] == "1969-12-31"){
									$entrega = "";
								}else{
									$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
								}

								if($qrSac['DAT_INTERAC'] != ""){
									if(fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)){
										$atualizado = "Hoje";
									}else if(fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))){
										$atualizado = "Ontem";
									}else{
										$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
									}
								}else{
									$atualizado = "";
								}

								//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
								// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
							?>

							<tr>
							  <td class="text-center">
							  	<small>
							  		<a href="action.php?mod=<?=fnEncode(1440);?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']);?>&idC=<?php echo fnEncode($qrSac['COD_ATENDIMENTO']); ?>" target="_blank"><?=$qrSac['COD_ATENDIMENTO'] ?>&nbsp; 
							  			<span class="fa fa-external-link-square"></span>
							  		</a>
							  	</small>
							  </td>
							  <td><small><?=$qrSac['NOM_CHAMADO'] ?></small></td>
							  <td><small><?=$qrNomUsu['NOM_SOLICITANTE'] ?></small></td>
							  <td><small><?=$qrSac['DES_TPSOLICITACAO'] ?></small></td>
							  <!-- <td><small><?=$qrNomUsu['NOM_RESPONSAVEL'] ?></small></td> -->
							  
							  <td class="text-center">
							  	<small>
							  		<p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>"> 
							  			<span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
							  			<!-- &nbsp; <?php echo $qrSac['DES_PRIORIDADE']; ?> -->
							  		</p>
							  	</small>
							  </td>

							  <td class="text-center">
							  	<small>
							  		<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>"> 
							  			<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
							  			&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
							  		</p>
							  	</small>
							  </td>
							  
							  <td class="text-center"><small><?=fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
							  <td class="text-center"><small><?=$entrega?></small></td>
							  <!-- <td class="text-center"><small><?=$atualizado?></small></td> -->

							</tr>
						    <?php
							}									
						?> 
							
						</tbody>
						<tfoot>
							<tr>
							  <th class="" colspan="100">
								<center><ul id="paginacao" class="pagination-sm"></ul></center>
							  </th>
							</tr>
						</tfoot>												
						</table>


						
						</form>
						
					<div class="push10"></div>	

					</div>					
					
				</div>
			</div>
			
		</div>

	</div>

	
	
	<div class="row">
		
		<div class="col-md-6">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body">						
				
					<div class="no-more-tables">
						
						<h4 style="margin-left: 5px;">Meus cadastros </h4>
						
										<div class="content-top">
											<div class="col-md-6 top-content">
												<h5>Tasks</h5>
												<label>8761</label>
											</div>
											<div class="col-md-6">	   
												<div id="demo-pie-1" class="pie-title-center" data-percent="25">
													<span class="pie-value">25%</span>
												</div>
											</div>
											<div class="clearfix"> </div>
										</div>

										
						<div class="push10"></div>	

					</div>							
					
				</div>
			</div>
			
		</div>

		<div class="col-md-6">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body">						
				
					<div class="no-more-tables">
										
						<form name="formLista">
							
							<h4 style="margin-left: 5px;">Meus objetivos</h4>
						
							<table class="table table-bordered table-striped table-hover table-sm">

								<thead>
									<tr>
										<th><small>Chamado</small></th>
										<th><small>Empresa</small></th>
										<th><small>Status</small></th>
										<th><small>Dt. de Criação</small></th>
									</tr>
								</thead>

								<tbody id="relatorioConteudoRecentes">
								  
								<?php

																			
								
									$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.COD_EXTERNO, 
												SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.DAT_ENTREGA, SC.DAT_PROXINT, SC.DES_PREVISAO, SC.COD_USUARIO,
												SC.COD_USURES, SC.LOG_ADM, SP.DES_PLATAFORMA, ST.DES_TPSOLICITACAO, 
												SV.DES_VERSAOINTEGRA, SPR.DES_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE, SPR.DES_ICONE AS ICO_PRIORIDADE,
												SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS,
												(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC
												FROM SAC_CHAMADOS SC 
												LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
												LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
												LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
												LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
												LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
												WHERE SC.COD_STATUS NOT IN($cod_status_exc)
												AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc)
												$ANDcodUsuario
												$ANDcod_usures
												$ANDcodStatus
												ORDER BY SC.COD_CHAMADO DESC limit 5
												";
									// fnEscreve($sqlSac);

									$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(),$sqlSac) or die(mysqli_error());
									
									$count=0;
									$adm="";
									$entrega = "";
									while ($qrSac = mysqli_fetch_assoc($arrayQuerySac))
									 {	

									 	if($qrSac['LOG_ADM'] == 'S'){
									 		$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
									 	}else{
									 		$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
									 	}

										$count++;

										$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
										$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmpresa));

										$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE,
																(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
										$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsuarios));
										//fnEscreve($sqlUsuarios);										  

										if($qrSac['DAT_ENTREGA'] == "1969-12-31"){
											$entrega = "";
										}else{
											$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
											if(fnDatasql($entrega) < fnDatasql($hoje)){
												$entrega = "<span class='text-danger'><b>".fnDataShort($qrSac['DAT_ENTREGA'])."</b></span>";
											}
										}

										if($qrSac['DAT_PROXINT'] == "1969-12-31"){
											$proxInt = "";
										}else{
											$proxInt = fnDataShort($qrSac['DAT_PROXINT']);
											if(fnDatasql($proxInt) < fnDatasql($hoje)){
												$proxInt = "<span class='text-danger'><b>".fnDataShort($qrSac['DAT_PROXINT'])."</b></span>";
											}
										}

										if($qrSac['DAT_INTERAC'] != ""){
											if(fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)){
												$atualizado = "<b>Hoje</b>";
												$f = "f17";
											}else if(fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))){
												$atualizado = "<b>Ontem</b>";
												$f = "f17";
											}else{
												$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
												$f = "f14";
											}
										}else{
											$atualizado = "";
										}

										if($qrSac['COD_STATUS'] == 12){

											$difference = fnValor((abs(strtotime(date("Y-m-d H:i:s")) - strtotime($qrSac['DAT_CADASTR']))/3600),0);

											if($difference <= 12){
												$corDiff = "label-success";
											}else if($difference > 12 && $difference <= 24){
												$corDiff = "label-warning";
											}else{
												$corDiff = "label-danger";
											}

											$badgeDias = "<span class='label-as-badge text-center ".$corDiff."'><span class='txtBadge'>".$difference."</span></span>";
										}else{
											$badgeDias = "";
										}

										//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
										// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
									?>

									<tr>
									  <td>
									  	<small>
									  		<a href="action.php?mod=<?=fnEncode(1285);?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']);?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank">#<?=$qrSac['COD_CHAMADO'] ?>&nbsp; 
									  			<?=$qrSac['NOM_CHAMADO'] ?>
									  			<!-- <span class="fa fa-external-link-square"></span> -->
									  		</a>
									  	</small>
									  </td>
									  <td><small><?=$qrNomEmp['NOM_FANTASI'] ?></small></td>

									  <td class="text-center">
									  	<div style="height: 0.5px;"></div>
									  	<small>
									  		<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>"> 
									  			<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
									  			&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
									  		</p>
									  		&nbsp;
									  		<?=$badgeDias?>
									  	</small>
									  		
									  	<!-- <div><?=$badgeDias?></div> -->
									  </td>
									  
									  <td class="text-center f14"><small><?=fnDataShort($qrSac['DAT_CADASTR'])?></small></td>

									</tr>
								    <?php
									}									
								?> 
									
								</tbody>

							</table>
						
						</form>
						
						<div class="push10"></div>	

					</div>							
					
				</div>
			</div>
			
		</div>

	</div>
	
	<input type="hidden" name="COD_USUARIOS_AGE[]" id="COD_USUARIOS_AGE" value="<?=$_SESSION[SYS_COD_USUARIO]?>">
	
	<div class="row">
		
		<div class="col-md-12">							
				
			<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			
				<div class="portlet-body">						
				
					<div class="no-more-tables">
										
						
						<h4 style="margin-left: 5px;">Quick links</h4>
						
						<div class="push100"></div>		
							
						<div class="push10"></div>	

					</div>							
					
				</div>
			</div>
			
		</div>

	</div>

	<input type="hidden" name="REFRESH_TAREFA" id="REFRESH_TAREFA" value="N">

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

	<a type="hidden" name="btnCad" id="btnCad" class="addBox"></a>
	<a type="hidden" name="btnPrint" id="btnPrint" class="addBox"></a>
	
	
	<script type="text/javascript" src="js/slimscroll2/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
	<script src="js/pie-chart.js"></script>
	
    <script>
		

		// alert($('#COD_USUARIOS_AGE').val());

		var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
        	locale: 'pt-br',
        	plugins: [ 'list', 'bootstrap' ],
        	themeSystem: 'bootstrap',
        	height: 750,
        	defaultView: 'listWeek',
        	customButtons: {
			    imprimir: {
			      click: function() {

			      	view = calendar.view;
			      	dat_ini = view.currentStart.toISOString().substring(0,10);
			      	dat_fim = view.currentEnd.toISOString().substring(0,10);
			      	
					// alert(dat_ini+"   "+dat_fim);

			        $('#btnPrint').attr('data-url',"action.php?mod=<?php echo fnEncode(1443)?>&id=<?php echo fnEncode($cod_empresa)?>&idU="+JSON.stringify($('#COD_USUARIOS_AGE').val())+"&dat_ini="+dat_ini+"&dat_fim="+dat_fim+"&pop=true").attr('data-title',"Imprimir Agenda ").click();
			      }
			    }
			},
			views: {
		      listDay: { buttonText: 'Dia' }
		    },
		    listDayFormat: { 
			    month: 'short',
			    year: 'numeric',
			    day: 'numeric',
			    weekday: 'long'
			},
      //   	header: {
		    //     left: ' ',
		    //     center: 'title',
		    //     right: ' ',
		    // },
		    header:false,
		    // listDayFormat:false,
		    eventLimit: true,
		    events: <?=$eventos?>,
      //   	loading: function( isLoading ) {
		    //       if(isLoading) {// isLoading gives boolean value
		    //           $('#wait').show();
		    //       } else {
		    //           $('#wait').hide();
		    //       }
		    // },
        	eventClick: function(info) {
        		$('#btnCad').attr('data-url',"action.php?mod=<?php echo fnEncode(1402)?>&id=<?php echo fnEncode($cod_empresa)?>&idE="+info.event.id+"&pop=true").attr('data-title',"Editar Evento - "+info.event.title).click();
        	}

        });

        calendar.render();

        $('.fixed-hg').slimScroll({
			height: '450px'
		});

        //modal close
		$('.modal').on('hidden.bs.modal', function () {
			if($('#REFRESH_TAREFA').val() == "S"){

				// calendar.getEventSourceById('AJX').remove();
				calendar.addEventSource({
			    	id: 'AJX',
	                url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
	                method: 'POST', // Send post data,
	                extraParams: {COD_USUARIOS_AGE: $('#COD_USUARIOS_AGE').val()},
	                success: function(data){
	                	console.log(data);
	                	// alert($('#COD_USUARIOS_AGE').val());
	                },
	                error: function(data) {
	                	console.log(data);
	                    alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
	            	}
		        });		

			}
		});

        // calendar.getEventSourceById('AJX').refetch();

	$(function(){


            
        

  //       $(".fc-imprimir-button").html('<i class="fal fa-print"></i>');

  //       //modal close
		// $('.modal').on('hidden.bs.modal', function () {
		// 	if($('#REFRESH_TAREFA').val() == "S"){

		// 		calendar.getEventSourceById('AJX').remove();
		// 		calendar.addEventSource({
		// 	    	id: 'AJX',
	 //                url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
	 //                method: 'POST', // Send post data,
	 //                extraParams: {COD_USUARIOS_AGE: $('#COD_USUARIOS_AGE').val()},
	 //                success: function(data){
	 //                	console.log(data);
	 //                	// alert($('#COD_USUARIOS_AGE').val());
	 //                },
	 //                error: function(data) {
	 //                	console.log(data);
	 //                    alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
	 //            	}
		//         });		

		// 	}
		// });

		// $('#CAD').click(function(){
		// 	$('#btnCad').attr('data-url',"action.php?mod=<?php echo fnEncode(1402)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true").attr('data-title',"Novo Evento").click();
		// });

		// $('#COD_USUARIOS_AGE').change(function(){

		//  	calendar.getEventSourceById('AJX').remove();
		// 	calendar.addEventSource({
		//     	id: 'AJX',
  //               url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
  //               method: 'POST', // Send post data,
  //               extraParams: {COD_USUARIOS_AGE: $('#COD_USUARIOS_AGE').val()},
  //               success: function(data){
  //               	console.log(data);
  //               	// alert($('#COD_USUARIOS_AGE').val());
  //               },
  //               error: function(data) {
  //               	console.log(data);
  //                   alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
  //           	}
	 //        });

		// });

		// // ajax para debug
  //       $.ajax({
  //       	url: 'ajxAgenda.php?id=<?=fnEncode($cod_empresa)?>&idU='+JSON.stringify($('#COD_USUARIOS_AGE').val()),
  //        	method: 'POST', // Send post data,
  //        	success: function(data){
  //        	console.log(data);
  //        	// alert($('#COD_USUARIOS_AGE').val());
  //        	},
  //        	error: function(data) {
  //        		console.log(data);
  //        	    alert('Ocorreu um erro ao carregar os eventos, Tente novamente mais tarde');
  //           }
  //       });

	});
		
	</script>

	<?php
	}
	?>
   