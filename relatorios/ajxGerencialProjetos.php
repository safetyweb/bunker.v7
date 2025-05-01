<?php 

	include '../_system/_functionsMain.php'; 
	//require_once '../js/plugins/Spout/Autoloader/autoload.php';
	$connboard = $Cdashboard->connUser();
	
//use Box\Spout\Writer\WriterFactory;
//use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);
	$dat_ini = fnmesanosql("01/" . $_POST['DAT_INI']);
	$dat_ini_campo = $_POST['DAT_INI'];
	$cod_grupotr = $_REQUEST['COD_GRUPOTR'];
	$cod_tiporeg = $_REQUEST['COD_TIPOREG'];

	//array dos sistemas da empresas	
	if (isset($_POST['COD_SISTEMAS'])) {
		$Arr_COD_SISTEMAS = $_POST['COD_SISTEMAS'];
		//print_r($Arr_COD_SISTEMAS);			 

		for ($i = 0; $i < count($Arr_COD_SISTEMAS); $i++) {
			@$cod_sistemas .= $Arr_COD_SISTEMAS[$i] . ",";
		}

		$cod_sistemas = substr($cod_sistemas, 0, -1);
	} else {
		$cod_sistemas = "0";
	}

	//array das empresas
	if (isset($_POST['COD_EMPRESA'])) {

		if($_POST['COD_EMPRESA'][0] == 9999){

			$cod_empresas_combo = "9999";
			$cod_empresas = $_SESSION["SYS_COD_MULTEMP"];

		}else if($_POST['COD_EMPRESA'][0] == 9998){

			$cod_empresas_combo = "9998";
			$cod_empresas = "0";

		}else{

			$cod_empresas = "";
			$Arr_COD_EMPRESA = $_POST['COD_EMPRESA'];
			//print_r($Arr_COD_EMPRESA);			 

			for ($i = 0; $i < count($Arr_COD_EMPRESA); $i++) {
				$cod_empresas .= $Arr_COD_EMPRESA[$i] . ",";
			}

			$cod_empresas = substr($cod_empresas, 0, -1);
			$cod_empresas_combo = $cod_empresas;

		}

	} else {

		$cod_empresas = "0";
		$cod_empresas_combo = "9998";

	}

	if ($cod_empresas != 0) {
		$andEmpresas = " AND COD_EMPRESA IN($cod_empresas)";
	} else {
		$andEmpresas = "";
	}

	if ($cod_segment != 0) {
		$andSegment = " AND COD_SEGMENT = $cod_segment";
	} else {
		$andSegment = "";
	}

	if ($cod_sistemas != 0) {
		$codSistemas = str_replace(",", "|", $cod_sistemas);
		$andSistemas =  ' AND COD_SISTEMAS REGEXP "' . $codSistemas . '"';
	} else {
		$andSistemas = "";
	}		
	
	
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			/*writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			*/
			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";			
           			       
			$sql = "SELECT D.* FROM dash_consultor D 
					WHERE D.ANO_MES = '$dat_ini'
					$andEmpresas
					$andSegment
					$andSistemas
					ORDER BY D.NOM_FANTASI,D.ANO_MES DESC
						";
					  
					
			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query($connboard,$sql);			
				
			$arquivo = fopen($arquivoCaminho, 'w',0);
                    
			// while($headers=mysqli_fetch_field($arrayQuery)){
			// 	 $CABECALHO[]=$headers->name;
			// }
			$CABECALHO[]="Cód. Empresa";
			$CABECALHO[]="Empresa";
			$CABECALHO[]="Cliente Desde";
			$CABECALHO[]="Base Clientes¹";
			$CABECALHO[]="Clientes Novos";
			$CABECALHO[]="Qtd. Transações";
			$CABECALHO[]="Qtd. Transações Avulsas";
			$CABECALHO[]="Qtd. Transações Fid.";
			$CABECALHO[]="% Transações Fid.¹";
			$CABECALHO[]="Qtd. Itens Transações Fid.¹";
			$CABECALHO[]="Qtd. Resgates";
			$CABECALHO[]="Índice Freq. Mês¹";
			$CABECALHO[]="% Qtd. Resg. / Qtd. Transac. Fideliz.";
			$CABECALHO[]="% Qtd. Resg. / Qtd. Transac. Gerais";
			$CABECALHO[]="% Qtd. Expi. / Qtd. Cred. Gerados";
			$CABECALHO[]="% Resgate Fat.";
			$CABECALHO[]="Cred. Expirados";
			$CABECALHO[]="% Val. Expira. / Val. Cred. Gerados";
			$CABECALHO[]="% $ Variação TM Resg. X Avulso";
			$CABECALHO[]="% $ Variação TM Fid. X Avulso¹";
			$CABECALHO[]="Qtd. Prod. Vigentes Tkt.";
			$CABECALHO[]="Dat. Ult. Acesso";
			$CABECALHO[]="Usu. Cadastrado / Acesso";
			$CABECALHO[]="Qtd. Disparos SMS";
			$CABECALHO[]="Cobrança Contratada SMS";
			$CABECALHO[]="Qtd. Disparos E-mail";
			$CABECALHO[]="Cobrança Contratada Email";
			$CABECALHO[]="LGPD";
			fputcsv ($arquivo,$CABECALHO,';','"','\n');

			$actualRow = array();
		  
			while ($row=mysqli_fetch_assoc($arrayQuery)){

				$totalFidelizado = $row['QT_FIDELIZA'] / $row['QT_TOTAL'] * 100;

				$itensFidelizado = $row['QTD_ITEM_FIDELIZA'];

				$totalResgate = $row['QTD_RESGATE'] / $row['QTD_CLIENTE_RESGATE'] * 100;

				$totalResgateFid = $row['QTD_RESGATE'] / $row['QT_FIDELIZA'] * 100;

				$totalTransac = $row['QTD_RESGATE'] / $row['QT_TOTAL'] * 100;

				$totVariacaoTM = ((((($row['VAL_VINCULADO1'] - $row['VAL_RESGATE']) / $row['QTD_VINCULADO1']) / ($row['VAL_TOTAL_AV'] / $row['QT_AVULSA']))-1) * 100);

				$totVariacaoTMFid = (((($row['VAL_TOTAL_FIDELI'] / $row['QT_FIDELIZA']) / ($row['VAL_TOTAL_AV'] / $row['QT_AVULSA']))-1) * 100);

				$totCadXacesso = $row['QTD_ACESSO'] / $row['QTD_USUARIO'];

				$totalExpira = $row['VAL_CRED_EXPIRADO'] / $row['VAL_CREDITOS_GERADO'] * 100;

				$totalResGeral = $row['QTD_RESGATE'] / $row['QT_TOTAL'] * 100;

				$totalCredExpira = $row['QTD_EXPIRA_SALDO'] / $row['QTD_CREDITO_GERADO'] * 100;

				$totalIndiceFideliz = $row['VAL_FREQUENCIA'];

				$totalFaturamento = $row['VAL_RESGATE'] / $row['VAL_TOTPRODU'] * 100;

				$actualRow["Cód Empresa"] = $row['COD_EMPRESA'];
				$actualRow["Empresa"] = $row['NOM_FANTASI'];
				$actualRow["Cliente Desde"] = $datProdEmpresa;
				$actualRow["Base Clientes¹"] = fnvalor($row['QTD_TOT_CLIENTE']);
				$actualRow["Clientes Novos"] = fnvalor($row['QTD_CLIENTE_PERIODO']);
				$actualRow["Qtd. Transações"] = fnvalor($row['QT_TOTAL']);
				$actualRow["Qtd. Transações Avulsas"] = fnvalor($row['QT_AVULSA']);
				$actualRow["Qtd. Transações Fid."] = fnvalor($row['QT_FIDELIZA']);
				$actualRow["% Transações Fid.¹"] = fnvalor($totalFidelizado, 2);
				$actualRow["Qtd. Itens Transações Fid.¹"] = fnvalor($itensFidelizado, 0);
				$actualRow["Qtd. Resgates"] = fnvalor($row['QTD_RESGATE']);
				$actualRow["Índice Freq. Mês¹"] = round($totalIndiceFideliz, 2);
				$actualRow["% Qtd. Resg. / Qtd. Transac. Fideliz."] = fnvalor($totalResgateFid, 2);
				$actualRow["% Qtd. Resg. / Qtd. Transac. Gerais"] = fnvalor($totalResGeral, 2);
				$actualRow["% Qtd. Expi. / Qtd. Cred. Gerados"] = fnvalor($totalCredExpira, 2);
				$actualRow["% Resgate Fat."] = fnvalor($totalFaturamento, 2);
				$actualRow["Cred. Expirados"] = fnvalor($row['VAL_CRED_EXPIRADO'], 2);
				$actualRow["% Val. Expira. / Val. Cred. Gerados"] = fnvalor($totalExpira, 2);
				$actualRow["% $ Variação TM Resg. X Avulso"] = fnvalor($totVariacaoTM, 2);
				$actualRow["% $ Variação TM Fid. X Avulso¹"] = fnvalor($totVariacaoTMFid, 2);
				$actualRow["Qtd. Prod. Vigentes Tkt."] = fnvalor($row['QTD_PRODTKT']);
				$actualRow["Dat. Ult. Acesso"] = fndatashort($row['DAT_ULT_ACESSO']);
				$actualRow["Usu. Cadastrado / Acesso"] = $row['QTD_USUARIO']."/".$row['QTD_ACESSO'];
				$actualRow["Qtd. Disparos SMS"] = fnvalor($row['QTD_COMUNICACAO_SMS']);
				$actualRow["Cobrança Contratada SMS"] = fnvalor($row['QTD_DEBITOS_SMS']);
				$actualRow["Qtd. Disparos E-mail"] = fnvalor($row['QTD_COMUNICACAO_EMAIL']);
				$actualRow["Cobrança Contratada Email"] = fnvalor($row['QTD_DEBITOS_EMAIL']);
				$actualRow["LGPD"] = $row['LGPD'];
				
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                //$textolimpo = json_decode($limpandostring, true);

                // echo "<pre>";
				// print_r($actualRow);
				// echo "</pre>";
                $array = array_map("utf8_decode", $actualRow);
                fputcsv($arquivo, $array, ';', '"', '\n');	
			}
			fclose($arquivo);
			

		break;      
		case 'paginar':

												

			break; 		
	}
?>