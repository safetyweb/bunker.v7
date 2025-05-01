<?php
/*
codigo Kind
5 - Visualização de email
6 - Clique de email
7 - Optout de email
8 - Bounce de email
9 - Denuncia de spam do email
*/
require '../../_system/_functionsMain.php';
require '../../_system/func_dinamiza/Function_dinamiza.php';


$conadmin = $connAdm->connAdm();

if ($_GET['COD_EMPRESA'] != "") {
	$empresa = "and apar.cod_empresa=" . $_GET['COD_EMPRESA'];

	if ($_GET['disparo'] != '') {
		$disparourl = "AND COD_DISPARO_EXT in (" . $_GET['disparo'] . ")";
		//  echo $disparourl;
	}
	$data_rel = $_GET['dataini'];

	$camposelect = "COUNT(COD_DISPARO_EXT) AS contadorarray, date(DAT_CADASTR) as DAT_CADASTR,GROUP_CONCAT(COD_DISPARO_EXT) as COD_DISPARO_EXT,COD_CAMPANHA";
	//$datinterval="AND DAT_CADASTR BETWEEN '$data_rel 00:00:00' and '$data_rel 23:59:59'";
	$datinterval = '';
	$sleep = '1';
} else {
	$camposelect = "COUNT(COD_DISPARO_EXT) AS contadorarray, date(DAT_CADASTR) as DAT_CADASTR,GROUP_CONCAT(COD_DISPARO_EXT) as COD_DISPARO_EXT,COD_CAMPANHA";
	//dias para reprocessar os disparos
	$data_filtro1 = date('Y-m-d', strtotime("-1 days"));
	$datinterval = "AND DAT_CADASTR >= '$data_filtro1 00:00:00'";
	$groupby = "GROUP BY date(DAT_CADASTR),cod_campanha";
	//dias reprocessamento
	//inicio hoje = 10 dias;
	$data_rel = date('Y-m-d', strtotime("-0 days"));
	echo '<br>' . $data_rel . '<br>';
	//$empresa="and apar.cod_empresa=77";
	// $disparourl= "AND COD_DISPARO_EXT in ('2536537')";
	$sleep = '1';
}

$sqlinicio = "SELECT * FROM senhas_parceiro apar
			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
			WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S'  $empresa";


$rwcomunicacao = mysqli_query($conadmin, $sqlinicio);

while ($rscomunicacao = mysqli_fetch_assoc($rwcomunicacao)) {

	$contador = '100';

	$contact_list_code = $rscomunicacao['COD_LISTA'];

	$contemporaria = connTemp($rscomunicacao['COD_EMPRESA'], '');
	$atenticacaoDInamize = autenticacao_dinamiza(
		$rscomunicacao['DES_USUARIO'],
		$rscomunicacao['DES_AUTHKEY'],
		$rscomunicacao['DES_CLIEXT']
	);
	$senha_dinamize = $atenticacaoDInamize['body']['auth-token'];

	$cod_disparossql = "SELECT " . $camposelect . " FROM email_lote  
                            where cod_empresa='$rscomunicacao[COD_EMPRESA]'
                                   and COD_DISPARO_EXT  IS NOT NULL 
                                    and log_teste='N' 
                                    and LOG_ENVIO='S'  
                                    AND COD_EXT_SEGMENTO IS NOT NULL
                                   $datinterval 
	                           $disparourl
				   $groupby
                            ORDER BY COD_CONTROLE ASC";

	$rwdisporos = mysqli_query($contemporaria, $cod_disparossql);
	while ($rsdisporos = mysqli_fetch_assoc($rwdisporos)) {
		$contador = '100';
		unset($url_clien);
		unset($dadosconsultaf);
		$dadosconsultaf = explode(',', $rsdisporos['COD_DISPARO_EXT']);

		foreach ($dadosconsultaf as $dadosdisparolooping) {

			ob_start();
			if ($sleep == '1') {
				sleep(2);
			}

			$url_clien = LinksHTML($senha_dinamize, $dadosdisparolooping);
			foreach ($url_clien['body']['items'] as $key => $dadoslink) {

				//inserindo links          
				//COD_DISPARO=$dadosdisparolooping and
				$sqlc = "SELECT count(*) as contador FROM  link_template WHERE  COD_LINK = '" . $dadoslink['code'] . "' and COD_EMPRESA='" . $rscomunicacao['COD_EMPRESA'] . "'";
				$rssqlc = mysqli_fetch_assoc(mysqli_query($contemporaria, $sqlc));


				if ($rssqlc['contador'] <= 0) {
					$inserthtml = "INSERT INTO link_template (COD_EMPRESA, 
                                                                                                                COD_LINK, 
                                                                                                                DES_LINK,
                                                                                                                DES_TITULO, 
                                                                                                                COD_DISPARO) VALUES 
                                                                                                                ('" . $rscomunicacao['COD_EMPRESA'] . "', 
                                                                                                               '" . $dadoslink['code'] . "', 
                                                                                                                '" . $dadoslink['url'] . "', 
                                                                                                               '" . $dadoslink['title'] . "', 
                                                                                                               '" . $dadosdisparolooping . "');";
					mysqli_query($contemporaria, $inserthtml);
					//echo '<br>'.$inserthtml.'<br>';
				}
			}
		}



		if ($rsdisporos['COD_DISPARO_EXT'] != '') {

			for ($i = 1; $i <= $contador; ++$i) {
				if ($sleep == '1') {
					usleep(1200000);
				}


				unset($teste12);
				unset($dadossistema);
				//echo'<br>entrou aqui<br>'.$rsdisporos[COD_DISPARO_EXT].'<br>';
				$teste12 = Reldetalhando(
					$senha_dinamize,
					$rsdisporos['COD_DISPARO_EXT'],
					$data_rel . ' 00:00:00',
					$data_rel . ' 23:59:59',
					$i,
					$contact_list_code
				);
				foreach ($teste12['body']['items'] as $key => $dadossistema) {

					if ($dadossistema['Kind'] == '7') {

						$sqlblck = "SELECT COUNT(DES_EMAIL) as DES_EMAIL FROM blacklist_email 
													  where DES_EMAIL='" . $dadossistema['email'] . "' AND 
													  COD_EMPRESA=" . $rscomunicacao['COD_EMPRESA'];
						$rwemail = mysqli_query($contemporaria, $sqlblck);
						while ($rsemail = mysqli_fetch_assoc($rwemail)) {
							if ($rsemail['DES_EMAIL'] <= '0') {
								$inblck = "INSERT INTO blacklist_email (COD_EMPRESA, 
                                                                                                                                            DES_EMAIL, 
                                                                                                                                            COD_USUCADA,
                                                                                                                                            COD_CLIENTE
                                                                                                                                            ) 
                                                                                                                                            VALUES 
                                                                                                                                            (
                                                                                                                                             '" . $rscomunicacao['COD_EMPRESA'] . "', 
                                                                                                                                             '" . $dadossistema['email'] . "', 
                                                                                                                                             '9999',
                                                                                                                                             '" . $dadossistema['external_code'] . "'
                                                                                                                                             );";
								mysqli_query($contemporaria, $inblck);
								$sqlUP = "UPDATE clientes SET LOG_EMAIL='N' WHERE  
																	 COD_CLIENTE='" . $dadossistema['external_code'] . "' 
																	 and COD_EMPRESA='" . $rscomunicacao['COD_EMPRESA'] . "';";
								mysqli_query($contemporaria, $sqlUP);
							}
						}
					}
					if ($dadossistema['Kind'] == '6') {

						//delete todos os registros para reinserir novamente
						$delte = "DELETE FROM click_links WHERE 
                                                                                                                    COD_EMPRESA ='" . $rscomunicacao['COD_EMPRESA'] . "' 
                                                                                                                    AND COD_CLIENTE='" . $dadossistema['external_code'] . "'	
                                                                                                                    AND COD_DISPARO = " . $dadossistema['GenericInfo'] . " 
                                                                                                                    AND COD_LINK = " . $dadossistema['GenericInfo2'] . "     
                                                                                                                    and date(DAT_CADASTR)='" . $data_rel . "';";

						//echo '<br>'.$delte.'<br>';
						mysqli_query($contemporaria, $delte);

						$iserirclientes = "INSERT INTO click_links (COD_CLIENTE, 
                                                                                                                                    COD_EMPRESA, 
                                                                                                                                    DAT_CADASTR,
                                                                                                                                    COD_DISPARO, 
                                                                                                                                    COD_LINK,
                                                                                                                                    KIND, 
                                                                                                                                    COD_CAMPANHA, 
                                                                                                                                    DAT_SINCRONIZACAO
                                                                                                                                 ) VALUES 
                                                                                                                                 ('" . $dadossistema['external_code'] . "', 
                                                                                                                                  '" . $rscomunicacao['COD_EMPRESA'] . "', 
                                                                                                                                  '" . $dadossistema['Time'] . "', 
                                                                                                                                  '" . $dadossistema['GenericInfo'] . "', 
                                                                                                                                  '" . $dadossistema['GenericInfo2'] . "',
                                                                                                                                  '" . $dadossistema['Kind'] . "', 
                                                                                                                                  '0', 
                                                                                                                                  '" . $teste12['body']['last_sync'] . "'
                                                                                                                                   );";
						mysqli_query($contemporaria, $iserirclientes);
					}
					// echo $iserirclientes.'<br>';
					//verificar se o cliente foi inserido na lista email_lista_ret
					$sqllista_ret = "SELECT count(*) as temnao FROM email_lista_ret WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
																		   AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
																		   AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";

					$rslista_ret = mysqli_fetch_assoc(mysqli_query($contemporaria, $sqllista_ret));
					if ($rslista_ret['temnao'] > '0') {
						//4 - Participou do disparo de email
						if ($dadossistema['Kind'] == '4') {
							$sqlview1 = "UPDATE email_lista_ret SET ENTREGUE='1',COD_SCONFIRMACAO='1',COD_LEITURA='0',BOUNCE='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
																																						AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
																																						AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";

							mysqli_query($contemporaria, $sqlview1);
						}

						//5 - Visualização de email
						if ($dadossistema['Kind'] == '5') {
							$sqlview1 = "UPDATE email_lista_ret SET ENTREGUE='1',COD_LEITURA='1',BOUNCE='0',COD_SCONFIRMACAO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
																																	AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
																																	AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";

							mysqli_query($contemporaria, $sqlview1);
						}
						//6 - Clique de email
						if ($dadossistema['Kind'] == '6') {
							$sqlview = "UPDATE email_lista_ret SET COD_LEITURA='1',ENTREGUE='1',CLICK='1',BOUNCE='0',COD_SCONFIRMACAO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
																																			AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
																																			AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
							mysqli_query($contemporaria, $sqlview);
						}
						//7 - Optout de email
						if ($dadossistema['Kind'] == '7') {
							$sqloptoutlista = "UPDATE email_lista_ret SET COD_LEITURA='1',ENTREGUE='1',COD_OPTOUT_ATIVO='1',BOUNCE='0',COD_SCONFIRMACAO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
																																				AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
																																				AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
							//echo '<br>'.$sqloptoutlista.'<br>';																	
							mysqli_query($contemporaria, $sqloptoutlista);
						}
						//8 - Bounce de email
						if ($dadossistema['Kind'] == '8') {
							//HARD BOUNCE
							//cod 1
							if ($dadossistema['GenericInfo2'] == 'DE' || $dadossistema['GenericInfo2'] == 'CE') {
								$sqlspam = "UPDATE email_lista_ret SET COD_LEITURA='0',ENTREGUE='0',BOUNCE='1',COD_SCONFIRMACAO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                                AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                                AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
								mysqli_query($contemporaria, $sqlspam);

								//blacklist email problematicos
								$sqlblck = "SELECT COUNT(DES_EMAIL) as DES_EMAIL FROM blacklist_email 
													            where DES_EMAIL='" . $dadossistema['email'] . "' AND
                                                                                                                          COD_CLIENTE='" . $dadossistema['external_code'] . "' AND		 													  
                                                                                                                          COD_EMPRESA=" . $rscomunicacao['COD_EMPRESA'];
								$rwemail = mysqli_query($contemporaria, $sqlblck);
								while ($rsemail = mysqli_fetch_assoc($rwemail)) {
									if ($rsemail['DES_EMAIL'] <= '0') {
										$inblck = "INSERT INTO blacklist_email (COD_EMPRESA, 
                                                                                                                                                                DES_EMAIL, 
                                                                                                                                                                COD_USUCADA,
                                                                                                                                                                COD_CLIENTE
                                                                                                                                                                ) 
                                                                                                                                                                VALUES 
                                                                                                                                                                (
                                                                                                                                                                 '" . $rscomunicacao['COD_EMPRESA'] . "', 
                                                                                                                                                                 '" . $dadossistema['email'] . "', 
                                                                                                                                                                 '9999',
                                                                                                                                                                 '" . $dadossistema['external_code'] . "'
                                                                                                                                                                 );";
										mysqli_query($contemporaria, $inblck);
									}
								}
							} else {
								//SOFT BOUNCE
								// cod 2
								$sqlspam = "UPDATE email_lista_ret SET COD_LEITURA='0',ENTREGUE='0',BOUNCE='1',COD_SCONFIRMACAO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
																																						AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
																																							AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
								mysqli_query($contemporaria, $sqlspam);
							}
						}

						if ($dadossistema['Kind'] == '9') {
							$sqlspam = "UPDATE email_lista_ret SET ENTREGUE='1',SPAM='1',BOUNCE='0',COD_SCONFIRMACAO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
																																				AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
																																					AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
							mysqli_query($contemporaria, $sqlspam);
						}
						//diferente de erro cod 8 é entrege 


					}
					ob_end_flush();
					ob_flush();
					flush();
				}

				if ($teste12['body']['next'] == '' || $teste12['body']['next'] == false) {
					$contador = '0';
					break;
					$contador = '0';
				}
				usleep(1200000);
			}
		} else {
			//echo 'nao tem disparos';
		}

		//inserir template	

	}
}
