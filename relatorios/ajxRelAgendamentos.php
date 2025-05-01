<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_usuarios_age = fnDecode($_GET['idU']);
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			
			// Filtro por Grupo de Lojas
			// include "filtroGrupoLojas.php";			
           			       
			$sql = "SELECT DISTINCT EA.*,
					(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 31)) AS ORG,
					(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 28)) AS RT,
					(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 29)) AS GT
					FROM EVENTOS_AGENDA EA
					LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EA.COD_EVENT
					WHERE EA.COD_EMPRESA = $cod_empresa
					AND (
						(EA.DAT_INI >= '$dat_ini' AND EA.DAT_INI <='$dat_fim' )
						OR (EA.DAT_FIM >= '$dat_ini' AND EA.DAT_FIM <='$dat_fim' )
						OR (EA.DAT_INI <= '$dat_ini' AND EA.DAT_FIM >='$dat_ini' )
						OR (EA.DAT_FIM <= '$dat_fim' AND EA.DAT_FIM >='$dat_fim' )
					)
					AND UE.COD_USUARIO IN($cod_usuarios_age)
					AND EA.COD_EXCLUSA = 0 
					ORDER BY EA.HOR_INI,EA.HOR_FIM
					";

			// echo ($sql);

			//fnTestesql(connTemp($cod_empresa,''),$sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


			// DISTIBUI OS EVENTOS NAS DATAS DO MÊS, CONFORME A REPETIÇÃO CONFIFURADA
			$items = [];
			while ($qrAtend = mysqli_fetch_assoc($arrayQuery)) {
			    $dti = max($dat_ini, $qrAtend["DAT_INI"]);
			    $dtf = min($dat_fim, $qrAtend["DAT_FIM"]);
			    while ($dti <= $dtf) {
			        $repet = [];
			        if ($qrAtend["DIAS_REPETE"] != "") {
			            $repet = explode(",", $qrAtend["DIAS_REPETE"]);
			        } else {
			            $repet = [0, 1, 2, 3, 4, 5, 6];
			        }
			        $w = date('w', strtotime($dti));
			        if (in_array($w, $repet)) {
			            $items[$dti][] = $qrAtend;
			        }
			        $dti = date('Y-m-d', strtotime($dti . ' +1 day'));
			    }
			}
			$dts = array_keys($items);
			sort($dts);

			$diasSemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');


			// MOSTRA NA GRID OS EVENTOS
			$count = 0;
			$array = array();
			foreach ($dts as $data) {
			    foreach ($items[$data] as $qrAtend) {
			        $nomUsuarios = "";
			        $nomSolicitantes = "";

			        $sql2 = "SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO IN(SELECT COD_USUARIO FROM USUARIO_EVENTO WHERE COD_EVENT = $qrAtend[COD_EVENT])";

			        $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

			        while ($qrUsu = mysqli_fetch_assoc($arrayQuery2)) {
			            $nomUsuarios .= ucwords(strtolower($qrUsu['NOM_USUARIO'])) . ", ";
			        }

			        $sql3 = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE IN(SELECT COD_CLIENTE FROM CLIENTE_EVENTO WHERE COD_EVENT = $qrAtend[COD_EVENT])";
			        $arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);
			        while ($qrCli = mysqli_fetch_assoc($arrayQuery3)) {
			            $nomSolicitantes .= ucwords(strtolower($qrCli['NOM_CLIENTE'])) . ", ";
			        }

			        $nomUsuarios = rtrim(trim($nomUsuarios), ",");
			        $nomSolicitantes = rtrim(trim($nomSolicitantes), ",");

			        $count++;

			        $newRow = array();

			        array_push($newRow, fnDataShort($data));
			        array_push($newRow, $diasSemana[date('w', strtotime($data))]);
			        array_push($newRow, $qrAtend['HOR_INI']."-".$qrAtend['HOR_FIM']);
			        array_push($newRow, $qrAtend['DES_LOCAL']);
			        array_push($newRow, $qrAtend['NOM_EVENT']);
			        array_push($newRow, $nomSolicitantes);
			        array_push($newRow, $nomUsuarios);
			        array_push($newRow, $qrAtend['ORG']);
			        array_push($newRow, $qrAtend['RT']);
			        array_push($newRow, $qrAtend['GT']);

					$array[] = $newRow;
			        
			    }
			}		
			
			$arrayColumnsNames = array();
			array_push($arrayColumnsNames, "DATA");
	        array_push($arrayColumnsNames, "DIA DA SEMANA");
	        array_push($arrayColumnsNames, "HORA";
	        array_push($arrayColumnsNames, "LOCAL";
	        array_push($arrayColumnsNames, "EVENTO");
	        array_push($arrayColumnsNames, "SOLICITANTES");
	        array_push($arrayColumnsNames, "RESPONSAVEIS");
	        array_push($arrayColumnsNames, "ORG");
	        array_push($arrayColumnsNames, "RT");
	        array_push($arrayColumnsNames, "GT");		

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			

		break;      
		case 'paginar':

			$sql = "SELECT DISTINCT EA.*
					FROM EVENTOS_AGENDA EA
					LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EA.COD_EVENT
					WHERE EA.COD_EMPRESA = $cod_empresa
					AND (
						(EA.DAT_INI >= '$dat_ini' AND EA.DAT_INI <='$dat_fim' )
						OR (EA.DAT_FIM >= '$dat_ini' AND EA.DAT_FIM <='$dat_fim' )
						OR (EA.DAT_INI <= '$dat_ini' AND EA.DAT_FIM >='$dat_ini' )
						OR (EA.DAT_FIM <= '$dat_fim' AND EA.DAT_FIM >='$dat_fim' )
					)
					AND UE.COD_USUARIO IN($cod_usuarios_age)
					AND EA.COD_EXCLUSA = 0";
			//fnTestesql(connTemp($cod_empresa,''),$sql);
			//fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			$sql = "SELECT DISTINCT EA.*,
					(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 31)) AS ORG,
					(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 28)) AS RT,
					(SELECT FC.DES_FILTRO FROM FILTROS_CLIENTE FC WHERE FC.COD_FILTRO = (SELECT EF.COD_FILTRO FROM EVENTO_FILTROS EF WHERE EF.COD_EVENTO = EA.COD_EVENT AND EF.COD_TPFILTRO = 29)) AS GT
					FROM EVENTOS_AGENDA EA
					LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EA.COD_EVENT
					WHERE EA.COD_EMPRESA = $cod_empresa
					AND (
						(EA.DAT_INI >= '$dat_ini' AND EA.DAT_INI <='$dat_fim' )
						OR (EA.DAT_FIM >= '$dat_ini' AND EA.DAT_FIM <='$dat_fim' )
						OR (EA.DAT_INI <= '$dat_ini' AND EA.DAT_FIM >='$dat_ini' )
						OR (EA.DAT_FIM <= '$dat_fim' AND EA.DAT_FIM >='$dat_fim' )
					)
					AND UE.COD_USUARIO IN($cod_usuarios_age)
					AND EA.COD_EXCLUSA = 0 
					ORDER BY EA.HOR_INI,EA.HOR_FIM
					LIMIT $inicio,$itens_por_pagina
					";

			//echo ($sql);

			//fnTestesql(connTemp($cod_empresa,''),$sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


			// DISTIBUI OS EVENTOS NAS DATAS DO MÊS, CONFORME A REPETIÇÃO CONFIFURADA
			$items = [];
			while ($qrAtend = mysqli_fetch_assoc($arrayQuery)) {
			    $dti = max($dat_ini, $qrAtend["DAT_INI"]);
			    $dtf = min($dat_fim, $qrAtend["DAT_FIM"]);
			    while ($dti <= $dtf) {
			        $repet = [];
			        if ($qrAtend["DIAS_REPETE"] != "") {
			            $repet = explode(",", $qrAtend["DIAS_REPETE"]);
			        } else {
			            $repet = [0, 1, 2, 3, 4, 5, 6];
			        }
			        $w = date('w', strtotime($dti));
			        if (in_array($w, $repet)) {
			            $items[$dti][] = $qrAtend;
			        }
			        $dti = date('Y-m-d', strtotime($dti . ' +1 day'));
			    }
			}
			$dts = array_keys($items);
			sort($dts);

			$diasSemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');


			// MOSTRA NA GRID OS EVENTOS
			$count = 0;
			foreach ($dts as $data) {
			    foreach ($items[$data] as $qrAtend) {
			        $nomUsuarios = "";
			        $nomSolicitantes = "";

			        $sql2 = "SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO IN(SELECT COD_USUARIO FROM USUARIO_EVENTO WHERE COD_EVENT = $qrAtend[COD_EVENT])";

			        $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

			        while ($qrUsu = mysqli_fetch_assoc($arrayQuery2)) {
			            $nomUsuarios .= ucwords(strtolower($qrUsu['NOM_USUARIO'])) . ", ";
			        }

			        $sql3 = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE IN(SELECT COD_CLIENTE FROM CLIENTE_EVENTO WHERE COD_EVENT = $qrAtend[COD_EVENT])";
			        $arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);
			        while ($qrCli = mysqli_fetch_assoc($arrayQuery3)) {
			            $nomSolicitantes .= ucwords(strtolower($qrCli['NOM_CLIENTE'])) . ", ";
			        }

			        $nomUsuarios = rtrim(trim($nomUsuarios), ",");
			        $nomSolicitantes = rtrim(trim($nomSolicitantes), ",");

			        $count++;
			        echo "
					<tr>
					<td>" . fnDataShort($data) . "</td>
					<td>" . $diasSemana[date('w', strtotime($data))] . "</td>
					<td>" . $qrAtend['HOR_INI']."-".$qrAtend['HOR_FIM'] . "</td>
					<td>" . $qrAtend['DES_LOCAL'] . "</td>
					<td>" . $qrAtend['NOM_EVENT'] . "</td>
					<td>" . $nomSolicitantes . "</td>
					<td>" . $nomUsuarios . "</td>
					<td>" . $qrAtend['ORG'] . "</td>
					<td>" . $qrAtend['RT'] . "</td>
					<td>" . $qrAtend['GT'] . "</td>
					</tr>
					";
			    }
			}
										

			break; 		
	}
?>