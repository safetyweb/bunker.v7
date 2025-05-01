<?php 

	include './_system/_functionsMain.php'; 
	require_once './js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$cod_empresa = fnDecode($_GET['id']);			
	$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
	$cod_disparo = fnLimpaCampoZero($_REQUEST['COD_DISPARO']);

	$nomeRel = $_GET['nomeRel'];
	$arquivo = './media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

	$writer = WriterFactory::create(Type::CSV);
	$writer->setFieldDelimiter(';');
	$writer->openToFile($arquivo);
	
	switch ($opcao) {

		case 'links':

			$arrayColumnsNames = "";
			
			$sql = "SELECT DISTINCT(COUNT(links.cod_link)) AS CLIQUES, 
					temp.DES_LINK,
					links.COD_LINK
					FROM click_links links
					INNER JOIN link_template temp ON links.COD_LINK=temp.COD_LINK
					 INNER JOIN  email_lote lot ON lot.COD_DISPARO_EXT=links.cod_disparo

					WHERE links.COD_EMPRESA = $cod_empresa AND LOT.COD_CAMPANHA = $cod_campanha
					GROUP BY links.COD_LINK";
					
			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$array = array();

			while($qrGraph = mysqli_fetch_assoc($arrayQuery)){

				$newRow = array();

				array_push($newRow, $qrGraph["DES_LINK"]);

				$array[] = $newRow;
				$newRow = array();

				array_push($newRow, "NOM. CLIENTE");
				// array_push($newRow, "COD. CLIENTE");
				array_push($newRow, "QTD. CLIQUES");

				$array[] = $newRow;
				$newRow = array();

				$sqlLink = "SELECT CLK.COD_CLIENTE, 
									CL.NOM_CLIENTE, 
									COUNT(CLK.ID) AS QTD_CLIQUES 
							FROM click_links CLK
							INNER JOIN clientes CL ON CL.COD_CLIENTE = CLK.COD_CLIENTE AND CL.COD_EMPRESA = $cod_empresa
							INNER JOIN  email_lote lot ON lot.COD_DISPARO_EXT=CLK.cod_disparo
							WHERE CLK.COD_LINK = $qrGraph[COD_LINK]
							AND LOT.COD_CAMPANHA = $cod_campanha
							GROUP BY CLK.COD_CLIENTE";

				$arrayLinks = mysqli_query(connTemp($cod_empresa,''),$sqlLink);

				// fnEscreve($sqlLink);

				$totCliques = "";

				while($qrLink = mysqli_fetch_assoc($arrayLinks)){

					array_push($newRow, $qrLink["NOM_CLIENTE"]);
					// array_push($newRow, $qrLink["COD_CLIENTE"]);
					array_push($newRow, $qrLink["QTD_CLIQUES"]);
					$totCliques += $qrLink["QTD_CLIQUES"];

					$array[] = $newRow;
					$newRow = array();

				}

				array_push($newRow, 'TOTAL');
				array_push($newRow, $totCliques);

				$array[] = $newRow;
				$newRow = array();

				array_push($newRow, "");
				// array_push($newRow, "");
				array_push($newRow, "");

				$array[] = $newRow;
				$newRow = array();


			}

		break;
	
		case 'lidos':		
		case 'nlidos':		
		case 'hbounce':		
		case 'sbounce':		
		case 'optout':
		case 'sent':
		case 'all':

			// if($opcao == 'lidos'){
			// 	$andFiltroOpcao = "AND ELR.COD_LEITURA = 1";
			// }else if($opcao == 'nlidos'){
			// 	$andFiltroOpcao = "AND ELR.COD_LEITURA = 0
			// 					   AND ELR.BOUNCE = 0
			// 					   AND ELR.COD_OPTOUT_ATIVO = 0
			// 					   ";
			// }else if($opcao == 'hbounce'){
			// 	$andFiltroOpcao = "AND ELR.BOUNCE = 1";
			// }else if($opcao == 'sbounce'){
			// 	$andFiltroOpcao = "AND ELR.BOUNCE = 2";
			// }else if($opcao == 'optout'){
			// 	$andFiltroOpcao = "AND ELR.COD_OPTOUT_ATIVO = 1";
			// }else if($opcao == 'spam'){
			// 	$andFiltroOpcao = "AND ELR.SPAM = 1";
			// }else if($opcao == 'sent'){
			// 	$andFiltroOpcao = "AND ELR.ENTREGUE = 1";
			// }else{
			// 	$andFiltroOpcao = "";
			// }

			$sqlRel = "SELECT 
							EL.NOM_CLIENTE, 
							EL.COD_CLIENTE, 
							EL.DAT_NASCIME, 
							EL.DES_EMAILUS,
							CL.NUM_CGCECPF, 
							CL.NUM_CELULAR,
							CL.COD_UNIVEND,
							UV.COD_EXTERNO,
							UV.NOM_FANTASI AS LOJA
					   FROM EMAIL_LISTA EL
					   INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = EL.COD_CLIENTE
					   INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
					   WHERE EL.COD_CAMPANHA = $cod_campanha 
					   AND EL.LOG_COMPARA = 0
					   ORDER BY EL.NOM_CLIENTE";

			fnEscreve($sqlRel);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlRel);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

					array_push($newRow, $objeto);
					
					$cont++;
				  }
				$array[] = $newRow;
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

		case 'black':

			$sqlRel = "SELECT BE.DES_EMAIL, 
								BE.DAT_CADASTR,
								CL.NUM_CARTAO
						FROM BLACKLIST_EMAIL BE
						LEFT JOIN CLIENTES CL ON CL.DES_EMAILUS = BE.DES_EMAIL
						WHERE BE.COD_EMPRESA = $cod_empresa
						ORDER BY BE.DAT_CADASTR DESC";

			// fnEscreve($sqlRel);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlRel);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

					array_push($newRow, $objeto);
					
					$cont++;
				  }
				$array[] = $newRow;
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

		default:

			$sqlRel = "SELECT 
							EL.NOM_CLIENTE, 
							EL.COD_CLIENTE, 
							EL.DAT_NASCIME, 
							EL.DES_EMAILUS,
							CL.NUM_CGCECPF, 
							CL.NUM_CELULAR,
							CL.COD_UNIVEND,
							UV.COD_EXTERNO,
							UV.NOM_FANTASI AS LOJA
					   FROM EMAIL_LISTA EL
					   INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = EL.COD_CLIENTE
					   INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
					   WHERE EL.COD_CAMPANHA = $cod_campanha 
					   AND EL.LOG_COMPARA = 1
					   ORDER BY EL.NOM_CLIENTE";

			fnEscreve($sqlRel);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlRel);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

					array_push($newRow, $objeto);
					
					$cont++;
				  }
				$array[] = $newRow;
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