<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$tip_roi = "";
$des_canal = "";
$lojasSelecionadas = "";
$dias30 = "";
$hoje = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$ARRAY_VENDEDOR1 = "";
$ARRAY_VENDEDOR = "";
$andEntreguesSms = "";
$anulaEmail = "";
$anulaSms = "";
$andEntregues = "";
$filtroVal = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$col = "";
$array = [];
$qrCamp = "";
$ativoCamp = "";
$dat_iniFiltro = "";
$dat_fimFiltro = "";
$canal = "";
$sqlCli = "";
$sql2 = "";
$arrayCli = [];
$qrCli = "";
$arrayVal = [];
$qrVal = "";
$val_unit = "";
$invest = "";
$retorno = "";
$roi = "";
$newRow = "";
$objeto = "";
$arrayColumnsNames = [];
$name = "";


include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

echo fnDebug('true');

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$tip_roi = @$_POST['TIP_ROI'];
$des_canal = @$_POST['DES_CANAL'];
$lojasSelecionadas = @$_POST['LOJAS'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

$ARRAY_UNIDADE1 = array(
	'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
$ARRAY_VENDEDOR1 = array(
	'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

switch ($opcao) {
	case 'exportar':


		$andEntreguesSms = "AND CASE
								WHEN cli_list.cod_cconfirmacao = '1' THEN '1'
								WHEN cli_list.cod_sconfirmacao = '1' THEN '1'
								ELSE '0'
								END IN ( 1, 1 )";

		$anulaEmail = "";
		$anulaSms = "";

		if ($des_canal == "SMS") {
			$anulaEmail = "AND 1 = 0";
			$anulaSms = "";
		} else if ($des_canal == "Email") {
			$anulaEmail = "";
			$anulaSms = "AND 1 = 0";
		}

		if ($tip_roi == 0) {

			$andEntregues = "AND cli_list.ENTREGUE = 1";
			$filtroVal = $andEntregues;
		} else if ($tip_roi == 1) {

			$andEntregues = "AND cli_list.CLICK IN('1')
								 and cli_list.bounce = '0'
								 and cli_list.SPAM = '0'";

			$filtroVal = $andEntregues;
		} else {

			$andEntregues = "AND cli_list.cod_optout_ativo = '0' 
								 AND cli_list.cod_leitura=1 
								 AND cli_list.bounce = '0' 
								 AND cli_list.SPAM = '0'";

			$filtroVal = $andEntregues;
		}


		$sql = "(
						SELECT
							CAMP.DES_CAMPANHA,
							'SMS' AS DES_CANAL,
							CAMP.LOG_ATIVO,
							0 FATURAMENTO,
							VAL.VAL_UNITARIO,
							0 INVESTIMENTO,
							0 RETORNO,
							0 ROI,
							(SELECT COUNT(DISTINCT cli_list.COD_CLIENTE)
									  FROM   sms_lista_ret cli_list
									  WHERE
											   cli_list.cod_empresa = $cod_empresa
											   AND cli_list.cod_campanha = COD_CAMPANHA
										AND	DATE(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
										$andEntreguesSms
											AND cli_list.COD_CLIENTE IN ( SELECT v.COD_CLIENTE
																						FROM   vendas v
																						WHERE  DATE(v.dat_cadastr_ws) BETWEEN
																							   '$dat_ini' AND '$dat_fim'
																							   AND v.cod_empresa = $cod_empresa
																				   )) AS CLI_ATIVOS, 
							sum(LOT.QTD_LISTA) QTD_LISTA, VAL.COD_VALOR, LOT.COD_CAMPANHA, 
							case when CAMP.DAT_INI > '$dat_ini' then CAMP.DAT_INI ELSE '$dat_ini' END DAT_INI,					   
							case when CAMP.DAT_FIM < '$dat_fim' then  CAMP.DAT_FIM ELSE '$dat_fim' END DAT_FIM
						FROM sms_lote LOT 
						INNER JOIN campanha CAMP ON CAMP.COD_CAMPANHA=LOT.COD_CAMPANHA 
						LEFT JOIN VALORES_COMUNICACAO VAL ON VAL.COD_CAMPANHA = CAMP.COD_CAMPANHA AND VAL.DES_CANAL = 'SMS' 
						WHERE date(LOT.DAT_AGENDAMENTO) BETWEEN '$dat_ini' AND '$dat_fim' 
						AND LOT.LOG_ENVIO = 'S' 
						AND LOT.cod_empresa=$cod_empresa 
						AND CAMP.LOG_PROCESSA_SMS = 'S'
						$anulaSms
						GROUP BY LOT.COD_CAMPANHA
					)

					UNION

					(
						SELECT
							CAMP.DES_CAMPANHA,
							'EMAIL' AS DES_CANAL,
							CAMP.LOG_ATIVO,
							0 FATURAMENTO,
							VAL.VAL_UNITARIO,
							0 INVESTIMENTO,
							0 RETORNO,
							0 ROI,
							(SELECT COUNT(DISTINCT cli_list.cod_cliente)
											  FROM   email_lista_ret cli_list
											  WHERE
								cli_list.cod_empresa = $cod_empresa
								AND cli_list.cod_campanha = COD_CAMPANHA
								AND DATE(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
								$andEntregues
								AND  cli_list.cod_cliente IN (SELECT v.COD_CLIENTE
																			 FROM   vendas v
																			 WHERE  DATE(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
																					AND v.cod_empresa = $cod_empresa
																					AND v.cod_avulso = 2
																				  )) AS CLI_ATIVOS, 
							sum(LOT.QTD_LISTA) QTD_LISTA, VAL.COD_VALOR, LOT.COD_CAMPANHA, 
							case when CAMP.DAT_INI > '$dat_ini' then CAMP.DAT_INI ELSE '$dat_ini' END DAT_INI,					   
							case when CAMP.DAT_FIM < '$dat_fim' then  CAMP.DAT_FIM ELSE '$dat_fim' END DAT_FIM
						FROM email_lote LOT 
						INNER JOIN campanha CAMP ON CAMP.COD_CAMPANHA=LOT.COD_CAMPANHA 
						LEFT JOIN VALORES_COMUNICACAO VAL ON VAL.COD_CAMPANHA = CAMP.COD_CAMPANHA AND VAL.DES_CANAL = 'EMAIL' 
						WHERE date(LOT.DAT_AGENDAMENTO) BETWEEN '$dat_ini' AND '$dat_fim' 
						AND LOT.LOG_ENVIO = 'S' 
						AND LOT.cod_empresa=$cod_empresa 
						AND CAMP.LOG_PROCESSA = 'S'
						$anulaEmail
						GROUP BY LOT.COD_CAMPANHA
					) 

					ORDER BY COD_CAMPANHA, DES_CAMPANHA";


		$nomeRel = @$_GET['nomeRel'];
		$arquivo = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$col["DES_CAMPANHA"] = "Campanha";
		$col["DES_CANAL"] = "Canal";
		$col["LOG_ATIVO"] = "Campanha Ativa";
		$col["FATURAMENTO"] = "Faturamento";
		$col["VAL_UNITARIO"] = "Valor Unitário";
		$col["INVESTIMENTO"] = "Investimento";
		$col["RETORNO"] = "Retorno da Campanha";
		$col["ROI"] = "ROI";
		$col["CLI_ATIVOS"] = "Clientes c/ Compras";
		$array = array();
		while ($qrCamp = mysqli_fetch_assoc($arrayQuery)) {

			$ativoCamp = "";

			$dat_iniFiltro = $qrCamp['DAT_INI'];
			$dat_fimFiltro = $qrCamp['DAT_FIM'];

			if ($qrCamp["LOG_ATIVO"] == 'S') {
				$ativoCamp = "<span class='fas fa-check'></span>";
			}

			if ($qrCamp['DES_CANAL'] == 'SMS') {

				$canal = "SMS";

				$sqlCli = "SELECT COUNT( distinct cli_list.cod_cliente) CLI_ATIVOS,'SMS' FROM   sms_lista_ret cli_list 
			                WHERE  cli_list.cod_empresa = $cod_empresa                      
			                       AND DATE(cli_list.dat_cadastr) BETWEEN  '$dat_iniFiltro' AND '$dat_fimFiltro' 
			                       AND  cli_list.cod_campanha = $qrCamp[COD_CAMPANHA]
			                       $andEntreguesSms
			                       AND cli_list.cod_cliente IN (SELECT v.cod_cliente 
			                                                    FROM   vendas v 
			                                                    WHERE 
			                           DATE(v.dat_cadastr_ws) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro' 
			                           AND v.cod_empresa = $cod_empresa) 
			                           $anulaSms                           
			              GROUP BY cli_list.cod_campanha";

				$sql2 = "SELECT    
							SUM(v.VAL_TOTPRODU)   VAL_TOTVENDA,
					      	SUM(v.VAL_TOTPRODU-v.VAL_DESCONTO) VAL_TOTPRODU, 
								SUM(v.VAL_RESGATE) VAL_RESGATE
						 FROM vendas v
						WHERE
						date(v.DAT_CADASTR_WS) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
						AND v.cod_empresa=$cod_empresa
						AND v.COD_AVULSO=2
						AND v.cod_statuscred IN ( 0, 1, 2, 3,4, 5, 7, 8, 9 )
						AND v.COD_CLIENTE IN (
							SELECT cli_list.COD_CLIENTE FROM  sms_lista_ret cli_list WHERE
							 cli_list.COD_EMPRESA=v.cod_empresa
							AND  cli_list.COD_CAMPANHA=$qrCamp[COD_CAMPANHA] 
							AND  Date(cli_list.DAT_CADASTR) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
							$andEntreguesSms
							$anulaSms
						GROUP BY cli_list.cod_cliente
						)";
			} else {

				$canal = "Email";

				$sqlCli = "SELECT COUNT( distinct cli_list.cod_cliente) CLI_ATIVOS,'EMAIL'
							         FROM   email_lista_ret cli_list
							         WHERE  cli_list.cod_empresa = $cod_empresa
							                AND cli_list.cod_campanha = $qrCamp[COD_CAMPANHA]
							                AND DATE(cli_list.dat_cadastr) BETWEEN
							                    '$dat_iniFiltro' AND '$dat_fimFiltro'
							                $filtroVal
							                AND cli_list.cod_cliente IN (SELECT v.cod_cliente
							                                             FROM   vendas v
							                                             WHERE
							                    DATE(v.dat_cadastr_ws) BETWEEN
							                    '$dat_iniFiltro' AND '$dat_fimFiltro'
							                    AND v.cod_empresa = $cod_empresa
							                    AND v.cod_avulso = 2)
							                    $anulaEmail
							   GROUP BY cli_list.cod_campanha";

				$sql2 = "SELECT    
							SUM(v.VAL_TOTPRODU)   VAL_TOTVENDA,
					      	SUM(v.VAL_TOTPRODU-v.VAL_DESCONTO) VAL_TOTPRODU, 
								SUM(v.VAL_RESGATE) VAL_RESGATE
						 FROM vendas v
						WHERE
						date(v.DAT_CADASTR_WS) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
						AND v.cod_empresa=$cod_empresa
						AND v.COD_AVULSO=2
						AND v.cod_statuscred IN ( 0, 1, 2, 3,4, 5, 7, 8, 9 )
						AND v.COD_CLIENTE IN (
							SELECT cli_list.COD_CLIENTE FROM  email_lista_ret cli_list WHERE
							 cli_list.COD_EMPRESA=v.cod_empresa
							AND  cli_list.COD_CAMPANHA=$qrCamp[COD_CAMPANHA] 
							AND  Date(cli_list.DAT_CADASTR) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
							$filtroVal
							$anulaEmail
						GROUP BY cli_list.cod_cliente
						)";
			}

			// fnEscreve($sqlCli);

			$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);
			$qrCli = mysqli_fetch_assoc($arrayCli);

			$arrayVal = mysqli_query(connTemp($cod_empresa, ''), $sql2);
			$qrVal = mysqli_fetch_assoc($arrayVal);

			if ($qrCamp["VAL_UNITARIO"] == "") {
				$val_unit = 0;
			} else {
				$val_unit = $qrCamp["VAL_UNITARIO"];
			}

			$invest = $val_unit * $qrCamp["QTD_LISTA"];
			$retorno = ($qrVal["VAL_TOTPRODU"] - $invest);
			$roi = $retorno / $invest;

			// fnEscreve($qrCamp['VAL_UNITARIO']);

			$newRow = array();

			$cont = 0;
			foreach ($qrCamp as $objeto) {
				if ($cont > 8) {
					continue;
				}

				if ($cont == 1) {
					if ($objeto == "EMAIL") {
						$objeto = "Email";
					}
					array_push($newRow, $objeto);
				} elseif ($cont == 2) {
					if ($objeto == "S") {
						$objeto = "Sim";
					} else {
						$objeto = "Não";
					}
					array_push($newRow, $objeto);
				} elseif ($cont == 3) {
					$objeto = fnValor($qrVal["VAL_TOTPRODU"], 2);
					array_push($newRow, $objeto);
				} elseif ($cont == 4) {
					array_push($newRow, fnValor($objeto, 5));
				} elseif ($cont == 5) {
					array_push($newRow, "R$" . fnValor($invest, 2));
				} elseif ($cont == 6) {
					array_push($newRow, "R$" . fnValor($retorno, 2));
				} elseif ($cont == 7) {
					array_push($newRow, fnValor($roi, 0) . "x");
				} elseif ($cont == 8) {
					array_push($newRow, fnValor($qrCli["CLI_ATIVOS"], 0));
				} else {
					array_push($newRow, $objeto);
				}

				// fnEscreve($cont);
				// fnEscreve($objeto);

				$cont++;
			}
			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$cont = 0;
		while ($qrCamp = mysqli_fetch_field($arrayQuery)) {
			if ($cont > 8) {
				continue;
			}
			$name = (@$col[$qrCamp->name] <> "" ? $col[$qrCamp->name] : $qrCamp->name);
			array_push($arrayColumnsNames, $name);
			$cont++;
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
}
