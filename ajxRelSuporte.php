<?php 

	include '_system/_functionsMain.php'; 	
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$tipo = $_GET['tipo'];

	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$dat_ini_ent = fnDataSql($_POST['DAT_INI_ENT']);
	$dat_fim_ent = fnDataSql($_POST['DAT_FIM_ENT']);
	$cod_externo = $_POST['COD_EXTERNO'];
	$cod_empresa = $_POST['COD_EMPRESA'];
	$nom_chamado = $_POST['NOM_CHAMADO'];
	$cod_chamado = $_POST['COD_CHAMADO'];
	$cod_usuario = $_POST['COD_USUARIO'];

	$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
	$cod_status = $_POST['COD_STATUS'];
	$cod_integradora = $_POST['COD_INTEGRADORA'];
	$cod_plataforma = $_POST['COD_PLATAFORMA'];
	$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
	$cod_prioridade = $_POST['COD_PRIORIDADE'];
	$cod_usures = $_POST['COD_USURES'];

	if (isset($_POST['COD_STATUS_EXC'])){
		$Arr_COD_STATUS_EXC = $_POST['COD_STATUS_EXC'];
		$cod_status_exc = "";	 
		 
		   for ($i=0;$i<count($Arr_COD_STATUS_EXC);$i++) 
		   { 
			$cod_status_exc = $cod_status_exc.$Arr_COD_STATUS_EXC[$i].",";
		   } 
		   
		   $cod_status_exc = rtrim($cod_status_exc, ',');
			
	}else{$cod_status_exc = "0";}

	if (isset($_POST['COD_TIPO_EXC'])){
		$Arr_COD_TIPO_EXC = $_POST['COD_TIPO_EXC'];
		$cod_tipo_exc = "";	 
		 
		   for ($i=0;$i<count($Arr_COD_TIPO_EXC);$i++) 
		   { 
			$cod_tipo_exc = $cod_tipo_exc.$Arr_COD_TIPO_EXC[$i].",";
		   } 
		   
		   $cod_tipo_exc = rtrim($cod_tipo_exc, ',');
			
	}else{$cod_tipo_exc = "0";}

	$hoje = fnFormatDate(date("Y-m-d"));


	// fnEscreve($cod_status_exc);
	

			if($dat_ini == ""){$ANDdatIni = " ";}else{$ANDdatIni = "AND DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";}

			if($dat_ini_ent == ""){$ANDdatIniEnt = " ";}else{$ANDdatIniEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";}

			if($dat_fim_ent == ""){$ANDdatFimEnt = " ";}else{$ANDdatFimEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";}

			if($cod_externo == ""){$ANDcodExterno = " ";}else{$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";}

			if($cod_empresa == ""){$ANDcodEmpresa = " ";}else{$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";}

			if($nom_chamado == ""){$ANDnomChamado = " ";}else{$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";}

			if($cod_tpsolicitacao == ""){$ANDcodTipo = " ";}else{$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";}

			if($cod_status == ""){$ANDcodStatus = "";}else{$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";}

			if($cod_status_exc == "0"){$ANDcodStatusExc = "";}else{$ANDcodStatusExc = "AND SC.COD_STATUS NOT IN($cod_status_exc) ";}

			if($cod_tipo_exc == "0"){$ANDcodTipoExc = "";}else{$ANDcodTipoExc = "AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc) ";}

			if($cod_integradora == ""){$ANDcodIntegradora = " ";}else{$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";}

			if($cod_plataforma == ""){$ANDcodPlataforma = " ";}else{$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";}

			if($cod_versaointegra == ""){$ANDcodVersaointegra = " ";}else{$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";}

			if($cod_prioridade == ""){$ANDcodPrioridade = " ";}else{$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";}

			if($cod_usuario == ""){$ANDcodUsuario = " ";}else{$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";}

			if($cod_usures == ""){$ANDcod_usures = " ";}else{$ANDcod_usures = "AND SC.COD_USURES = $cod_usures ";}

			$ANDdatFim = "AND 	DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '".$dat_fim."'";

			if($cod_chamado == ""){
				$ANDcodChamado = " ";
			}else{
				$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";
				$ANDcodStatusExc = "";
			}

			if($tipo == "opn"){
				$cod_chamado = fnDecode($_POST['COD_CHAMADO_ABERTO']);
				$ANDcodChamado = "AND SC.COD_CHAMADO IN($cod_chamado) ";
				$ANDcodEmpresa = "";
				$ANDdatIni = "";
				$ANDdatFim = "";
			}

			if($tipo == "cls"){
				$cod_chamado = fnDecode($_POST['COD_CHAMADO_FECHADO']);
				$ANDcodChamado = "AND SC.COD_CHAMADO IN($cod_chamado) ";
				$ANDcodEmpresa = "";
				$ANDdatIni = "";
				$ANDdatFim = "";
			}

			// fnEscreve($tipo);

	switch ($opcao) {

		case 'exportar':

			$nomeRel = $_GET['nomeRel'];
			$arquivo = 'media/excel/0_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
	

			$sql = "SELECT SC.COD_CHAMADO, 
						   SC.LOG_ADM, 
						   SC.COD_EMPRESA AS EMPRESA, 
						   SC.NOM_CHAMADO,  
						   SC.COD_USUARIO AS SOLICITANTE, 
						   SC.COD_USURES AS RESPONSAVEL,  
						   ST.DES_TPSOLICITACAO, 
						   SPR.ABV_PRIORIDADE, 
						   SS.ABV_STATUS,
						   SC.DAT_CADASTR,
						   '' AS HOR_CADASTR,
						   (SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC,
						   '' AS HOR_INTERAC,
						   SC.DAT_PROXINT,
						   SC.DAT_ENTREGA,
						   SC.COD_STATUS AS DAT_CONCLUSAO
				    FROM SAC_CHAMADOS SC 
				    LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
				    LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
				    LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
				    WHERE
				    DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
				    $ANDdatFim
				    $ANDdatIni
				    $ANDcodExterno
				    $ANDcodChamado
				    $ANDcodEmpresa
				    $ANDnomChamado
				    $ANDcodStatus
				    $ANDcodTipo
				    $ANDcodIntegradora
				    $ANDcodPlataforma
				    $ANDcodVersaointegra
				    $ANDcodPrioridade
				    $ANDcod_usures
				    $ANDcodUsuario
				    $ANDcodStatusExc
				    $ANDcodTipoExc
				    $ANDdatIniEnt
				    $ANDdatFimEnt
				    ORDER BY SC.COD_CHAMADO DESC";

			fnEscreve($sql);
					
			$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql);

			$array = array();
			$cont = 0;

			while($row = mysqli_fetch_assoc($arrayQuery)){

				$newRow = array();

				$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $row[EMPRESA]";
				$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmpresa));

				$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $row[SOLICITANTE]) AS NOM_SOLICITANTE,
										(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $row[RESPONSAVEL]) AS NOM_RESPONSAVEL";
				$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsuarios));

				if($row['DAT_ENTREGA'] == "1969-12-31"){
					$entrega = "";
				}else{
					$entrega = fnDataShort($row['DAT_ENTREGA']);
				}

				if($row['DAT_PROXINT'] == "1969-12-31"){
					$proxInt = "";
				}else{
					$proxInt = fnDataShort($row['DAT_PROXINT']);
				}

				if($row['DAT_CONCLUSAO'] == 10){
					$conclusao = $row['DAT_INTERAC'];
				}else{
					$conclusao = "";
				}

				$dat_cadastr = fnDataShort($row['DAT_CADASTR']);
				$dat_interac = fnDataShort($row['DAT_INTERAC']);
				$hor_cadastr = date("H:i:s",strtotime($row['DAT_CADASTR']));
				$hor_interac = date("H:i:s",strtotime($row['DAT_INTERAC']));

				array_push($newRow, $row['COD_CHAMADO']);
				array_push($newRow, $row['LOG_ADM']);
				array_push($newRow, $qrNomEmp['NOM_FANTASI']);
				array_push($newRow, $row['NOM_CHAMADO']);
				array_push($newRow, $qrNomUsu['NOM_SOLICITANTE']);
				array_push($newRow, $qrNomUsu['NOM_RESPONSAVEL']);
				array_push($newRow, $row['DES_TPSOLICITACAO']);
				array_push($newRow, $row['ABV_PRIORIDADE']);
				array_push($newRow, $row['ABV_STATUS']);
				array_push($newRow, $dat_cadastr);
				array_push($newRow, $hor_cadastr);
				array_push($newRow, $dat_interac);
				array_push($newRow, $hor_cadastr);
				array_push($newRow, $proxInt);
				array_push($newRow, $entrega);
				array_push($newRow, $conclusao);
				
				
				$array[] = $newRow;
				$cont++;

			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

		break;

		

	}							
	?>