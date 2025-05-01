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
if ($_GET['EMPRESA'] != '') {
	$GETURL = '1';
	$cod_empresa = $_GET['EMPRESA'];
	$sqlselect = 'and COD_EMPRESA=' . $cod_empresa;
	if ($_GET['DISPARO'] != '') {
		$disporo = 'ID_DISPARO=' . $_GET['DISPARO'] . ' and ';
	}
	if ($_GET['CAMPANHA'] != '') {
		$campanha = 'COD_CAMPANHA=' . $_GET['CAMPANHA'] . ' AND ';
	}
}


$sqlinicio = "SELECT * FROM senhas_parceiro apar
			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
			WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S'  $sqlselect";
$rwcomunicacao = mysqli_query($conadmin, $sqlinicio);
while ($rscomunicacao = mysqli_fetch_assoc($rwcomunicacao)) {

	$contact_list_code = $rscomunicacao['COD_LISTA'];

	$contemporaria = connTemp($rscomunicacao['COD_EMPRESA'], '');
	$atenticacaoDInamize = autenticacao_dinamiza(
		$rscomunicacao['DES_USUARIO'],
		$rscomunicacao['DES_AUTHKEY'],
		$rscomunicacao['DES_CLIEXT']
	);
	$senha_dinamize = $atenticacaoDInamize['body']['auth-token'];

	//echo $senha_dinamize;
	//exit();

	/*$cod_disparossql = "SELECT 
                            GROUP_CONCAT( DISTINCT  L.COD_DISPARO_EXT) as COD_DISPARO_EXT,
                            DATE(L.DAT_AGENDAMENTO) DAT_CADASTR
                    from email_lote L
                    INNER JOIN email_lista_ret R ON R.ID_DISPARO=L.COD_DISPARO_EXT AND R.COD_EMPRESA=L.COD_EMPRESA AND COD_OPTOUT_ATIVO=0 AND  ENTREGUE=0 AND  SPAM=0 AND bounce=0 AND  COD_OPTOUT_ATIVO=0
                    WHERE 
                        L.COD_EMPRESA='$rscomunicacao[COD_EMPRESA]' and
                        LENGTH(L.COD_DISPARO_EXT) > 4 AND 
                        L.COD_DISPARO_EXT is not NULL 
                        group BY  DATE(L.DAT_AGENDAMENTO),COD_DISPARO_EXT  ORDER BY L.COD_CONTROLE  desc";*/
	$cod_disparossql = "SELECT 
									GROUP_CONCAT( DISTINCT ID_DISPARO) as COD_DISPARO_EXT,
									DATE(dat_cadastr) DAT_CADASTR
									FROM email_lista_ret
									WHERE 
									   $campanha
									    dat_cadastr >= DATE_SUB(NOW(), INTERVAL 12 MONTH) AND 
										$disporo
										COD_EMPRESA='$rscomunicacao[COD_EMPRESA]' and
										LENGTH(ID_DISPARO) > 4 AND 
										ID_DISPARO is not NULL 
									GROUP BY DATE(dat_cadastr), ID_DISPARO ORDER BY DATE(dat_cadastr) DESC;";

	//echo $cod_disparossql;
	$rwdisporos = mysqli_query($contemporaria, $cod_disparossql);
	while ($rsdisporos = mysqli_fetch_assoc($rwdisporos)) {

		$contador = '1000000';
		if ($rsdisporos['COD_DISPARO_EXT'] != '') {
			for ($i = 1; $i <= $contador; ++$i) {
				echo 'contador: ' . $i . '<br>';


				unset($teste12);
				unset($dadossistema);
				//echo'<br>entrou aqui<br>'.$rsdisporos[COD_DISPARO_EXT].'<br>';
				$teste12 = Reldetalhando(
					$senha_dinamize,
					$rsdisporos['COD_DISPARO_EXT'],
					$rsdisporos['DAT_CADASTR'] . ' 00:00:00',
					$rsdisporos['DAT_CADASTR'] . ' 23:59:59',
					$i,
					$contact_list_code
				);
				if ($teste12['code'] == 240024) {
					echo '<pre>';
					print_r($teste12);
					echo '</pre>';
					sleep($teste12['code']['retry-after']);
				}


				//exit();
				foreach ($teste12['body']['items'] as $key => $dadossistema) {

					if ($dadossistema['Kind'] == '7') {


						$sqlUP = "UPDATE clientes SET LOG_EMAIL='N' WHERE  
																	 COD_CLIENTE='" . $dadossistema['external_code'] . "' 
																	 and COD_EMPRESA='" . $rscomunicacao['COD_EMPRESA'] . "';";
						mysqli_query($contemporaria, $sqlUP);
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
							$sqlview0 = "UPDATE email_lista_ret SET ENTREGUE='1',COD_SCONFIRMACAO='1',COD_LEITURA='0',BOUNCE='0',COD_OPTOUT_ATIVO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                                        AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                                        AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";

							mysqli_query($contemporaria, $sqlview0);
							//echo '<br>' . $sqlview0 . '<br>';
						}

						//5 - Visualização de email
						if ($dadossistema['Kind'] == '5') {
							$sqlview1 = "UPDATE email_lista_ret SET ENTREGUE='1',COD_LEITURA='1',BOUNCE='0',COD_SCONFIRMACAO='0',COD_OPTOUT_ATIVO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                                        AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                                        AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";

							mysqli_query($contemporaria, $sqlview1);
							//echo '<br>' . $sqlview1 . '<br>';
						}
						//6 - Clique de email
						if ($dadossistema['Kind'] == '6') {
							$sqlview = "UPDATE email_lista_ret SET COD_LEITURA='1',ENTREGUE='1',CLICK='1',BOUNCE='0',COD_SCONFIRMACAO='0',COD_OPTOUT_ATIVO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                                        AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                                        AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
							mysqli_query($contemporaria, $sqlview);
							//echo '<br>' . $sqlview . '<br>';
						}
						//7 - Optout de email
						if ($dadossistema['Kind'] == '7') {
							$sqloptoutlista = "UPDATE email_lista_ret SET COD_LEITURA='1',ENTREGUE='1',COD_OPTOUT_ATIVO='1',BOUNCE='0',COD_SCONFIRMACAO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                                                                AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                                                                AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
							//echo '<br>'.$sqloptoutlista.'<br>';																	
							mysqli_query($contemporaria, $sqloptoutlista);
							//echo '<br>' . $sqloptoutlista . '<br>';
						}
						//8 - Bounce de email
						if ($dadossistema['Kind'] == '8' || $dadossistema['Kind'] == '13') {
							//HARD BOUNCE
							//cod 1
							if ($dadossistema['GenericInfo2'] == 'DE' || $dadossistema['GenericInfo2'] == 'CE') {
								$sqlspam = "UPDATE email_lista_ret SET COD_LEITURA='0',ENTREGUE='0',BOUNCE='1',COD_SCONFIRMACAO='0',COD_OPTOUT_ATIVO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                                AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                                AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
								mysqli_query($contemporaria, $sqlspam);
								//echo '<br>' . $sqlspam . '<br>';
							} else {
								//SOFT BOUNCE
								// cod 2
								$sqlspam = "UPDATE email_lista_ret SET COD_LEITURA='0',ENTREGUE='0',BOUNCE='2',COD_SCONFIRMACAO='0',COD_OPTOUT_ATIVO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                                            AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                                             AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
								mysqli_query($contemporaria, $sqlspam);
								//echo '<br>' . $sqlspam . '<br>';
							}
						}

						if ($dadossistema['Kind'] == '9') {
							$sqlspam = "UPDATE email_lista_ret SET ENTREGUE='1',SPAM='1',BOUNCE='0',COD_SCONFIRMACAO='0',COD_OPTOUT_ATIVO='0'  WHERE ID_DISPARO= '" . $dadossistema['GenericInfo'] . "' 
                                                                                                                                                                                                        AND COD_EMPRESA = '" . $rscomunicacao['COD_EMPRESA'] . "'
                                                                                                                                                                                                         AND COD_CLIENTE='" . $dadossistema['external_code'] . "';";
							mysqli_query($contemporaria, $sqlspam);
							//echo '<br>' . $sqlspam . '<br>';
						}
						//diferente de erro cod 8 é entrege 


					}
					ob_end_flush();
					ob_flush();
					flush();
					//sleep(3);
				}


				if ($teste12['body']['next'] == '' || $teste12['body']['next'] == false) {

					$contador = '0';
					break;
				}
				//echo $contador . '<br>';
				usleep(1200000);
			}
		} else {
			//echo 'nao tem disparos';
		}

		//inserir template	

	}
}
