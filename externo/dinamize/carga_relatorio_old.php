<?php
require '../../_system/_functionsMain.php';
require '../../_system/func_dinamiza/Function_dinamiza.php';
$conadmin = $connAdm->connAdm();
if ($_GET['EMPRESA'] != '') {
	$GETURL = '1';
	$cod_empresa = $_GET['EMPRESA'];
	$sqlselect = 'and COD_EMPRESA=' . $cod_empresa;
	if ($_GET['DISPARO'] != '') {
		$disporo = 'COD_DISPARO_EXT=' . $_GET['DISPARO'] . ' and ';
	}
	if ($_GET['CAMPANHA'] != '') {
		$campanha = 'lote.COD_CAMPANHA=' . $_GET['CAMPANHA'] . ' AND ';
	}
} else {
	$data_filtro = date('Y-m-d', strtotime("-1 days"));
	$dataini = "lote.DAT_CADASTR >= '" . $data_filtro . " 00:00:00' and";
}

$sqlinicio = "SELECT * FROM senhas_parceiro apar
			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
			WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S' $sqlselect";


$rwempresa = mysqli_query($conadmin, $sqlinicio);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
	$contadorpaginacao = '100';
	$contadorpaginacao1 = '100';

	if ($_GET['EMPRESA'] == '') {
		$sleep2 = '1';
	}
	if ($GETURL != '1') {
		$cod_empresa = $rsempresa['COD_EMPRESA'];
	}
	$contemporaria = connTemp($rsempresa['COD_EMPRESA'], '');

	$atenticacaoDInamize = autenticacao_dinamiza(
		$rsempresa['DES_USUARIO'],
		$rsempresa['DES_AUTHKEY'],
		$rsempresa['DES_CLIEXT']
	);
	$senha_dinamize = $atenticacaoDInamize['body']['auth-token'];


	$lote = "SELECT * FROM email_lote lote 
				inner JOIN  TEMPLATE_EMAIL tmp ON tmp.COD_EXT_TEMPLATE=lote.COD_EXT_TEMPLATE
				INNER JOIN campanha cp ON cp.COD_CAMPANHA=lote.COD_CAMPANHA
			   where $campanha 
			  lote.LOG_ENVIO='S' and
			  lote.LOG_TESTE='N' AND
                           $disporo
                           $dataini  
	                       lote.COD_EMPRESA=" . $rsempresa['COD_EMPRESA'] . "
                        AND lote.COD_EXT_SEGMENTO IS NOT null    
			   ORDER BY cod_controle desc";
	//echo '<br>'.$lote.'<br>';       
	$rwsql = mysqli_query($contemporaria, $lote);
	if ($GETURL != '1') {
		$dados = mysqli_num_rows($rwsql);
		$limit = '1';
	} else {
		$dados = '0';
		$limit = '0';
	}
	if ($dados >= $limit) {

		while ($rssql = mysqli_fetch_assoc($rwsql)) {
			ob_start();
			$report = ReportSumary($senha_dinamize, $rssql['COD_DISPARO_EXT']);
			if ($report['code_detail'] == "Sucesso") {
				$naolidos = $report['body']['contacts'] - $report['body']['view'];



				//verificar se no relatorio ja existe
				$entregaMail = "SELECT count(COD_CAMPANHA) as existe FROM controle_entrega_mail  WHERE 
                                                                                                COD_CAMPANHA=" . $rssql['COD_CAMPANHA'] . " AND 
                                                                                                cod_empresa=" . $rsempresa['COD_EMPRESA'] . " AND 
                                                                                                COD_DISPARO='" . $rssql['COD_DISPARO_EXT'] . "' 
                                                                                                LIMIT 100;";

				$rwentrega = mysqli_fetch_assoc(mysqli_query($contemporaria, $entregaMail));
				if ($rwentrega['existe'] <= '0') {

					//inserir registro na base de dados. 
					$lista = "INSERT INTO controle_entrega_mail 
								(cod_empresa, 
								 COD_DISPARO,
								 cod_campanha_ext,
								 cod_campanha, 
								 dat_cadastr, 
								 dat_envio,
								 qtd_disparados,
								 qtd_sucesso,
								 qtd_falha,
								 qtd_lidos, 
								 qtd_nlidos, 
								 qtd_optout, 
								 qtd_cliques,
								 id_templete,
								 qtd_contatos,
								 qtd_exclusao,
								 span,
								 error_perm,
								 error_temp
								 ) 
								 VALUES 
								 (" . $rsempresa['COD_EMPRESA'] . ",
								 '" . $rssql['COD_DISPARO_EXT'] . "', 
								 '" . $rssql['COD_EXT_CAMPANHA'] . "', 
								 '" . $rssql['COD_CAMPANHA'] . "', 
								 now(), 
								 now(), 
								 '" . $report['body']['contacts'] . "', 
								 '" . $report['body']['delivered'] . "', 
								 '" . $report['body']['error'] . "',
								 '" . $report['body']['view'] . "', 
								 '" . $naolidos . "', 
								 '" . $report['body']['optout'] . "',
								 '" . $report['body']['click'] . "',
								 '" . $rssql['COD_EXT_TEMPLATE'] . "',
								 '" . $report['body']['contacts'] . "',
								 '0',
								 '" . $report['body']['spam'] . "',
								 '" . $report['body']['error_perm'] . "',
								 '" . $report['body']['error_temp'] . "')";
					//	echo $lista.'<br>';
					mysqli_query($contemporaria, $lista);


					//echo $lista;				
				} else {
					//atualizar registro
					$listaupdate = "UPDATE controle_entrega_mail set
								 qtd_disparados='" . $report['body']['delivered'] . "', 
								 qtd_sucesso='" . $report['body']['delivered'] . "',
								 qtd_falha='" . $report['body']['error'] . "',
								 qtd_lidos='" . $report['body']['view'] . "',
								 qtd_nlidos='" . $naolidos . "',
								 qtd_optout='" . $report['body']['optout'] . "',
								 qtd_cliques='" . $report['body']['click'] . "',
								 qtd_contatos='" . $report['body']['contacts'] . "',
								 qtd_exclusao='0',
								 span='" . $report['body']['spam'] . "',
								 error_perm='" . $report['body']['error_perm'] . "',
								 error_temp='" . $report['body']['error_temp'] . "'
								 where cod_empresa=" . $rsempresa['COD_EMPRESA'] . " and COD_DISPARO='" . $rssql['COD_DISPARO_EXT'] . "'";
					mysqli_query($contemporaria, $listaupdate);
					//echo $listaupdate;
				}
				if ($sleep2 == '1') {
					sleep(1);
				}
				//echo'<br>erro no contato de importação da lista<br>';	
				/*
				$erro_lista=relimport($senha_dinamize,$rssql[COD_MAILING_EXT]);
				if($erro_lista[body][Errors] > '0')
				{	
					$updatelistaerro="UPDATE controle_entrega_mail SET QTD_IMPORT_ERRO='".$erro_lista[body][Errors]."' 
								   where 
								   cod_empresa=$cod_empresa and 
								   COD_DISPARO='".$rssql['COD_DISPARO_EXT']."'";
						mysqli_query($contemporaria, $updatelistaerro);
				}else{*/
				$sqlsobra = "SELECT ABS((ctrl.error_perm+ctrl.error_temp+ctrl.qtd_sucesso)-lot.qtd_lista) AS sobra
						FROM controle_entrega_mail ctrl 
						iNNER JOIN email_lote lot ON lot.COD_DISPARO_EXT = ctrl.cod_disparo
						WHERE ctrl.cod_empresa=$cod_empresa AND ctrl.cod_disparo=" . $rssql['COD_DISPARO_EXT'] . " ";
				$rssobra = mysqli_fetch_assoc(mysqli_query($contemporaria, $sqlsobra));

				$updatelistaerro1 = "UPDATE controle_entrega_mail SET QTD_IMPORT_ERRO='" . $rssobra['sobra'] . "' 
                                                where 
                                                cod_empresa=" . $rsempresa['COD_EMPRESA'] . " and 
                                                COD_DISPARO='" . $rssql['COD_DISPARO_EXT'] . "'";

				mysqli_fetch_assoc(mysqli_query($contemporaria, $updatelistaerro1));

				//}

			}


			//entrgues lista
			if ($sleep2 == '1') {
				sleep(2);
			} else {
				sleep(1);
			}
			for ($i = 1; $i <= $contadorpaginacao; ++$i) {

				$entreguelista = relEntregue($senha_dinamize, $rssql['COD_DISPARO_EXT'], $i, 'DELIVER');
				foreach ($entreguelista['body']['items'] as $CHAVE => $dados_cliente) {

					$sqlentregue = "UPDATE email_lista_ret SET ENTREGUE='1',BOUNCE='0'  WHERE ID_DISPARO= '" . $rssql['COD_DISPARO_EXT'] . "' 
                                                                                                                        AND COD_EMPRESA = '" . $rsempresa['COD_EMPRESA'] . "'
                                                                                                                        AND COD_CLIENTE='" . $dados_cliente['external_code'] . "';";

					$testesql = mysqli_query($contemporaria, $sqlentregue);
				}
				if ($entreguelista['body']['next'] == '' || $entreguelista['body']['next'] == false) {
					$contadorpaginacao = '0';
				}
			}
			sleep(1);

			for ($i1 = 1; $i1 <= $contadorpaginacao1; ++$i1) {

				$entreguelista = relEntregue($senha_dinamize, $rssql['COD_DISPARO_EXT'], $i1, 'VIEW');
				foreach ($entreguelista['body']['items'] as $CHAVE => $dados_cliente) {


					$sqlentregue1 = "UPDATE email_lista_ret SET COD_LEITURA='1',ENTREGUE='1',BOUNCE='0'  WHERE ID_DISPARO= '" . $rssql['COD_DISPARO_EXT'] . "' 
                                                                                                                                                AND COD_EMPRESA = '" . $rsempresa['COD_EMPRESA'] . "'
                                                                                                                                                AND COD_CLIENTE='" . $dados_cliente['external_code'] . "';";
					$testesql = mysqli_query($contemporaria, $sqlentregue1);
				}
				if ($entreguelista['body']['next'] == '' || $entreguelista['body']['next'] == false) {
					$contadorpaginacao1 = '0';
				}
			}
			ob_end_flush();
			ob_flush();
			flush();
		}
	}
}
