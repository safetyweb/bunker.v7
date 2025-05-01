<?php 

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;

	// echo fnDebug('true');

	$cod_empresa = fnLimpaCampo(fnDecode($_GET['id']));
	$cod_registr = fnLimpaCampoZero(fnDecode($_GET['COD_REGISTR']));
	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_pesquisa = fnLimpaCampoZero($_REQUEST['COD_PESQUISA']);
	$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
	$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);
	$lojasSelecionadas = $_REQUEST['LOJAS'];

	// fnEscreve($cod_registr);

	// fnEscreve($opcao);

	switch ($opcao) {
		case 'exportar':


			// fnEscreve($cod_empresa);		

			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 

			$tipo = fnLimpaCampo($_GET['tipo']);

			$sql = "SELECT * FROM MODELOPESQUISA 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_TEMPLATE = $cod_pesquisa 
					AND COD_BLPESQU = 2
					AND DAT_EXCLUSA IS NULL";

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			// fnEscreve($sql);
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {

				$contador++;
		

				$texto = true;

				if ($qrBusca["DES_TIPO_RESPOSTA"] == "R" || $qrBusca["DES_TIPO_RESPOSTA"] == "C" ||
					$qrBusca["DES_TIPO_RESPOSTA"] == "RB" || $qrBusca["DES_TIPO_RESPOSTA"] == "CB" ||
					$qrBusca["DES_TIPO_RESPOSTA"] == "A"){
					$texto = false;
				}

				$sql2 = "SELECT UV.NOM_FANTASI,
							   CL.NOM_CLIENTE,
							   CL.NUM_CGCECPF, 	
							   DP.DT_HORAINICIAL,
							   MP.DES_PERGUNTA,	
							   CL.DES_EMAILUS,
							   CL.NUM_CELULAR,
							   DPI.*
						FROM DADOS_PESQUISA_ITENS DPI
						INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = DPI.COD_CLIENTE
						INNER JOIN DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO
						INNER JOIN MODELOPESQUISA MP ON MP.COD_REGISTR = DPI.COD_PERGUNTA
						LEFT JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = DPI.COD_UNIVEND
						WHERE DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
						AND DPI.COD_UNIVEND IN($lojasSelecionadas)
						AND DPI.COD_PESQUISA = $cod_pesquisa
						AND DPI.COD_PERGUNTA = $qrBusca[COD_REGISTR]
						ORDER BY NOM_FANTASI,NOM_CLIENTE";

				// fnEscreve($sql2);
				// exit();



				$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);
				$countHeader = 0;

				while ($qrBusca2 = mysqli_fetch_assoc($arrayQuery2)) {

					$newRow = array();

					if($countHeader == 0){
						array_push($newRow, 'LOJA');
						array_push($newRow, 'NOME');
						array_push($newRow, 'CPF');
						array_push($newRow, 'HORÁRIO');
						array_push($newRow, $qrBusca2['DES_PERGUNTA']);
						array_push($newRow, 'EMAIL');
						array_push($newRow, 'CELULAR');
						$array[] = $newRow;
						$newRow = array();
					}

					array_push($newRow, $qrBusca2['NOM_FANTASI']);
					array_push($newRow, $qrBusca2['NOM_CLIENTE']);
					array_push($newRow, $qrBusca2['NUM_CGCECPF']);
					array_push($newRow, $qrBusca2['DT_HORAINICIAL']);

					$countHeader ++;

					if(trim($qrBusca2['resposta_texto']) != ""){

						switch ($qrBusca["DES_TIPO_RESPOSTA"]) {
							case 'R':
							case 'RB':

								// lista e bloco
								$resposta = json_decode($qrBusca2['resposta_texto'],true);
								$resposta = implode(',', $resposta);
								$resposta = explode(',', $resposta);
								array_push($newRow, $resposta[0]);

							break;

							case 'C':
							case 'CB':

								// lista multi e bloco multi
								$resp = "";
								$resposta = json_decode($qrBusca2['resposta_texto'],true);
								foreach($resposta as $rk => $rv){
									$resp .= $rv.", ";
								}

								$resp = ltrim(rtrim(trim($resp),','),',');
								array_push($newRow, $resp);

							break;

							case 'A':

								// avaliacao
								$resp = "";
								$resposta = json_decode($qrBusca2['resposta_texto'],true);

								print_r($resposta);

								foreach($resposta as $rk => $rv){

									if($rv['opcao'] == 'S'){
										$like = "UP";
									}else{
										$like = "DOWN";
									}

									$resp .= $rv['texto'].": ".$like.", ";
								}

								$resp = ltrim(rtrim(trim($resp),','),',');

								array_push($newRow, $resp);

							break;
							
							default:

								// texto
								array_push($newRow, $qrBusca2['resposta_texto']);

							break;
						}

					}

					array_push($newRow, $qrBusca2['DES_EMAILUS']);
					array_push($newRow, $qrBusca2['NUM_CELULAR']);

					$array[] = $newRow;

				}

				$newRow = array();

				array_push($newRow, ' ');
				array_push($newRow, ' ');
				array_push($newRow, ' ');
				array_push($newRow, ' ');
				array_push($newRow, ' ');
				array_push($newRow, ' ');
				array_push($newRow, ' ');

				$array[] = $newRow;

			}
			
			$writer->addRows($array);

			$writer->close();
			
		break;
		
		
	}

	

?>