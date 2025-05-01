<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$dias30 = '';
$hoje = '';

$opcao = fnLimpaCampo(@$_GET['opcao']);
$cod_empresa = fnLimpaCampoZero(fnDecode(@$_GET['id']));
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$cod_univend = fnLimpaCampoZero(@$_GET['idu']);

$hoje = fnFormatDate(date("Y-m-d"));
// $hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}



switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$tipo = $_GET['tipo'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		//============================
		//         $ARRAY_UNIDADE1=array(
		//            'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
		//            'cod_empresa'=>$cod_empresa,
		//            'conntadm'=>$connAdm->connAdm(),
		//            'IN'=>'N',
		//            'nomecampo'=>'',
		//            'conntemp'=>'',
		//            'SQLIN'=> ""   
		//            );
		// $ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
		// $ARRAY_VENDEDOR1=array(
		//             'sql'=>"select COD_USUARIO ,COD_USUARIO as COD_ATENDENTE,COD_USUARIO as COD_VENDEDOR ,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
		//             'cod_empresa'=>$cod_empresa,
		//             'conntadm'=>$connAdm->connAdm(),
		//             'IN'=>'N',
		//             'nomecampo'=>'',
		//             'conntemp'=>'',
		//             'SQLIN'=> ""   
		//             );
		// $ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);

		if ($tipo == "all") {

			$sql = "SELECT UV.NOM_FANTASI LOJA, 
							(SELECT COUNT( distinct cod_cliente)
								FROM TICKET
								WHERE TICKET.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
								AND TICKET.COD_UNIVEND IN ($lojasSelecionadas)
								AND TICKET.COD_EMPRESA=$cod_empresa
								AND LOG_VISUALIZACAO=1) TICKETGERADO, 
						
							COUNT(DISTINCT a.cod_cliente) CLIENTES_UNICOS,  
	                                                                           
						  ((SELECT COUNT(DISTINCT cod_cliente)
							FROM TICKET
							WHERE date(TICKET.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' AND 
							TICKET.COD_UNIVEND=A.COD_UNIVEND AND TICKET.COD_EMPRESA=A.COD_EMPRESA AND TICKET.COD_UNIVEND!=4 AND 
							TICKET.COD_UNIVEND=A.COD_UNIVEND AND LOG_VISUALIZACAO=1)/COUNT(case when A.COD_AVULSO=2 then
								   (A.COD_CLIENTE)
								END) *100) INDICE_EMISSAO,
																				
						IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0) AS VENDAS_COM_OFERTA, 
						'' INDICE_VENDAS_OFERTA,
						ROUND((SUM(IF(A.LOG_TICKET='S',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0)),2) TKT_MEDIO_COM_OFERTA, 
						IFNULL(SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',1,0)),0) AS VENDAS_SEM_OFERTA, 
						ROUND((SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',1,0)),0)),2) TKT_MEDIO_SEM_OFERTA,
						'' VARIACAO_TKT													

						FROM vendas A FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)
						INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE AND B.LOG_AVULSO = 'N'
						LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = A.COD_UNIVEND
						WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND A.cod_empresa = $cod_empresa 
						AND A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
						AND A.COD_UNIVEND IN ($lojasSelecionadas)
						GROUP BY A.COD_UNIVEND";


			// fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$array = array();
			while ($row = mysqli_fetch_assoc($arrayQuery)) {
				$newRow = array();

				$indice_com_oferta = $row['VENDAS_COM_OFERTA'] / ($row['VENDAS_SEM_OFERTA'] + $row['VENDAS_COM_OFERTA']) * 100;
				$variacao_tkt = ($row['TKT_MEDIO_COM_OFERTA'] / $row['TKT_MEDIO_SEM_OFERTA']) * 100 - 100;

				$cont = 0;
				foreach ($row as $objeto) {

					// Colunas que são double converte com fnValor
					if ($cont == 1 || $cont == 2) {

						array_push($newRow, fnValor($objeto, 0));
					} else if ($cont > 2) {

						if ($cont == 3) {

							array_push($newRow, fnValor($objeto, 2) . "%");
						} else if ($cont == 5) {

							$objeto = $indice_com_oferta;
							array_push($newRow, fnValor($objeto, 2) . "%");
						} else if ($cont == 9) {

							$objeto = $variacao_tkt;
							array_push($newRow, fnValor($objeto, 2) . "%");
						} else {

							array_push($newRow, fnValor($objeto, 2));
						}
					} else {

						array_push($newRow, $objeto);
					}

					$cont++;
				}
				$array[] = $newRow;
			}
		} else {

			$sql = "SELECT 
							UV.NOM_FANTASI LOJA,
							VA.NOM_USUARIO NOME_VENDEDOR,
						--	VA.NOM_USUARIO VENDEDOR,
                                                 
							COUNT(DISTINCT A.COD_CLIENTE) AS QTD_CLIENTE,
							 IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0) AS VENDAS_COM_OFERTA, 
							 '' INDICE_VENDAS_OFERTA,
							 ROUND((SUM(IF(A.LOG_TICKET='S',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0)),2) TKT_MEDIO_COM_OFERTA, 
							 IFNULL(SUM(IF(A.LOG_TICKET='N' AND A.COD_AVULSO=2 ,1,0)),0) AS VENDAS_SEM_OFERTA, 
							 ROUND((SUM(IF(A.LOG_TICKET='N' AND A.COD_AVULSO=2,A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='N' AND A.COD_AVULSO=2,1,0)),0)),2) TKT_MEDIO_SEM_OFERTA, 
							 '' VARIACAO_TKT
						
						FROM vendas A FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)
					--	left JOIN usuarios VA ON (VA.cod_usuario = A.cod_atendente)
					--	left JOIN usuarios VD ON (VD.cod_usuario = A.cod_vendedor)
                                                left JOIN usuarios VA ON (VA.cod_usuario = A.cod_atendente) OR (VA.cod_usuario = A.cod_vendedor)
						left JOIN unidadevenda UV ON (UV.cod_univend = A.cod_univend)

						WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) 
						AND A.cod_empresa = $cod_empresa 
						AND a.cod_avulso=2  
						AND date(A.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
						AND A.COD_UNIVEND IN ($lojasSelecionadas)
	   				    GROUP BY VA.COD_USUARIO -- ,VD.COD_USUARIO
	   				    ORDER BY A.COD_UNIVEND";

			// fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$array = array();
			while ($row = mysqli_fetch_assoc($arrayQuery)) {
				$newRow = array();

				$cont = 0;

				$indice_com_oferta = $row['VENDAS_COM_OFERTA'] / ($row['VENDAS_SEM_OFERTA'] + $row['VENDAS_COM_OFERTA']) * 100;
				$variacao_tkt = ($row['TKT_MEDIO_COM_OFERTA'] / $row['TKT_MEDIO_SEM_OFERTA']) * 100 - 100;

				foreach ($row as $objeto) {

					// Colunas que são double converte com fnValor
					if ($cont == 2) {

						array_push($newRow, fnValor($objeto, 0));
					} else if ($cont > 2) {

						if ($cont == 4) {

							$objeto = $indice_com_oferta;
							array_push($newRow, fnValor($objeto, 2) . "%");
						} else if ($cont == 8) {

							$objeto = $variacao_tkt;
							array_push($newRow, fnValor($objeto, 2) . "%");
						} else {

							array_push($newRow, fnValor($objeto, 2));
						}
					} else {

						array_push($newRow, $objeto);
					}

					$cont++;
				}
				$array[] = $newRow;
			}
		}

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	default:


?>

		<div class="push20"></div>
		<table class="table table-bordered" style="width: 100%;">

			<thead>
				<tr>
					<th class="f12" colspan="12">
						<h5><b>Tickets X Vendas Agrupadas por Vendedor/Atendente</b></h5>
					</th>
				</tr>
				<tr>
					<th class="f12" style="width:40px;">&nbsp;</th>
					<th class="f14 text-left"><b>Vendedor/Atendente</b></th>
					<th class="f12 text-center" style="width:120px;"><b>&nbsp; - </th>
					<th class="f12 text-center"><b>clientes únicos <br /><small>por vendedor/atendente</small></th>
					<th class="f12 text-center" style="width:120px;"><b>&nbsp; - </th>
					<th class="f12 text-center"><b>vendas <br><small>com ofertas</small></th>
					<th class="f12 text-center"><b>índice de vendas <br /><small>com ofertas</small></th>
					<th class="f12 text-center"><b>ticket médio <br /><small>com ofertas</small></th>
					<th class="f12 text-center"><b>vendas <br><small>sem ofertas</small></th>
					<th class="f12 text-center"><b>ticket médio <br /><small>sem ofertas</small></th>
					<th class="f12 text-center"><b>variação <br /><small>ticket médio</small></th>
				</tr>
			</thead>

			<tbody>


				<?php

				$undadearray = array(
					'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
					'cod_empresa' => $cod_empresa,
					'conntadm' => $connAdm->connAdm(),
					'IN' => 'N',
					'nomecampo' => '',
					'conntemp' => '',
					'SQLIN' => ""
				);
				$univendaarray = fnUnivend($undadearray);

				/*$sql = "SELECT
								  NOM_FANTASI,
								  COD_ATENDENTE,
								  NOM_CLIENTE,
								  NOM_USUARIO,
								  COUNT(distinct COD_CLIENTE) QTD_CLIENTE,
								  SUM(QTD_VENDAS_OFERTA) QTD_VENDAS_OFERTA,
								  SUM(QTD_VENDAS_SEM) QTD_VENDAS_SEM,    
								  TRUNCATE(SUM(VAL_TOTPRODU),2) VAL_TOTPRODU,     
								  Ifnull(TRUNCATE(SUM(QTD_VENDAS_OFERTA)/SUM(QTD_VENDAS_OFERTA),2),0) VAL_MEDIO_OFERTA,
								  TRUNCATE(SUM(LOG_TICKET_N_AVULSO),2) LOG_TICKET_N_AVULSO,
								  Ifnull(TRUNCATE(SUM(QTD_VENDAS_SEM)/SUM(QTD_AVULSO),2),0) VAL_MEDIA_VENDA,
								  TRUNCATE(SUM(VAL_COMPRA_SEM),2) VAL_COMPRA_SEM,
								  TRUNCATE(SUM(VAL_COMPRA_COM),2) VAL_COMPRA_COM
							FROM (

							SELECT
								  UNI.NOM_FANTASI,
								  A.COD_UNIVEND,
								  A.COD_ATENDENTE,
								  A.COD_CLIENTE,
								  B.NOM_CLIENTE,
								  US.NOM_USUARIO,
								  CASE WHEN A.LOG_TICKET = 'S' THEN '1' ELSE '0' END QTD_VENDAS_OFERTA,
								  CASE WHEN A.COD_AVULSO=1 THEN '1' ELSE '0' END QTD_VENDAS_SEM,
								  CASE WHEN A.COD_AVULSO=2 THEN TRUNCATE(A.VAL_TOTPRODU,2) ELSE '0.0' END VAL_TOTPRODU,     
								  '0.00' VAL_MEDIO_OFERTA ,
								  CASE WHEN A.LOG_TICKET = 'N' AND A.COD_AVULSO=2 THEN TRUNCATE(A.VAL_TOTPRODU,2) ELSE '0.00' END LOG_TICKET_N_AVULSO,
									'0.00' VAL_MEDIA_VENDA,	
								   CASE WHEN A.LOG_TICKET = 'N' AND A.COD_AVULSO=1 THEN TRUNCATE(A.VAL_TOTPRODU,2) ELSE '0.00' END VAL_COMPRA_SEM,
								   CASE WHEN A.LOG_TICKET = 'S' THEN TRUNCATE(A.VAL_TOTPRODU,2) ELSE '0.00' END VAL_COMPRA_COM,
								   CASE WHEN A.LOG_TICKET = 'N' AND A.COD_AVULSO=2  THEN '1' ELSE '0' END QTD_AVULSO
								  
							FROM   vendas A
								 INNER JOIN clientes B   ON B.cod_cliente = A.cod_cliente 
								 LEFT JOIN usuarios US ON US.cod_usuario = A.cod_atendente
								 LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=A.COD_UNIVEND 
							 WHERE  A.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 )
								   AND A.cod_empresa = $cod_empresa
								   AND DATE(A.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
								  AND A.cod_univend = $cod_univend
									 
									 
									 ) VENDA_TMPTESTE
							  GROUP  BY cod_atendente ";*/


				$sql = "
								SELECT 
										A.COD_UNIVEND,
										VA.COD_USUARIO,
										VA.NOM_USUARIO,
										COUNT(DISTINCT A.COD_CLIENTE) AS QTD_CLIENTE,
										 IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0) AS QTD_VENDAS_OFERTA, IFNULL(SUM(IF(A.LOG_TICKET='N' AND A.COD_AVULSO=2 ,1,0)),0) AS QTD_VENDAS_SEM, 
										 ROUND((SUM(IF(A.LOG_TICKET='S',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0)),2) VAL_MEDIO_OFERTA, 
										 ROUND((SUM(IF(A.LOG_TICKET='N' AND A.COD_AVULSO=2,A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='N' AND A.COD_AVULSO=2,1,0)),0)),2) VAL_MEDIA_VENDA, 
										 SUM(IF(A.LOG_TICKET='N' AND A.COD_AVULSO=2,A.VAL_TOTPRODU,0)) VAL_COMPRA_SEM, 
										 SUM(IF(A.LOG_TICKET = 'S', A.VAL_TOTPRODU, 0)) VAL_COMPRA_COM
										
										FROM vendas A FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)
								--		left JOIN usuarios VA ON (VA.cod_usuario = A.cod_atendente)
			 					--		left JOIN usuarios VD ON (VD.cod_usuario = A.cod_vendedor)
	                                                                         left JOIN usuarios VA ON (VA.cod_usuario = A.cod_atendente) OR (VA.cod_usuario = A.cod_vendedor)
										WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) 
										AND A.cod_empresa = $cod_empresa 
										AND a.cod_avulso=2  
										AND date(A.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
										AND A.COD_UNIVEND = $cod_univend
					   				   GROUP BY VA.COD_USUARIO -- ,VD.COD_USUARIO";

				// fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


				while ($qrBuscaDados = mysqli_fetch_assoc($arrayQuery)) {

					$indice_com_oferta = $qrBuscaDados['QTD_VENDAS_OFERTA'] / ($qrBuscaDados['QTD_VENDAS_SEM'] + $qrBuscaDados['QTD_VENDAS_OFERTA']) * 100;
					$variacao_tkt = ($qrBuscaDados['VAL_MEDIO_OFERTA'] / $qrBuscaDados['VAL_MEDIA_VENDA']) * 100 - 100;

					$TOT_TICKT_GERADO = $TOT_TICKT_GERADO + $qrBuscaDados['TICKT_GERADO'];
					$TOT_QTD_CLIENTE = $TOT_QTD_CLIENTE + $qrBuscaDados['QTD_CLIENTE'];
					$TOT_QTD_VENDAS_OFERTA = $TOT_QTD_VENDAS_OFERTA + $qrBuscaDados['QTD_VENDAS_OFERTA'];
					$TOT_QTD_VENDAS_SEM = $TOT_QTD_VENDAS_SEM + $qrBuscaDados['QTD_VENDAS_SEM'];

				?>
					<tr>
						<th class="f14" style="width:40px;">&nbsp;</th>
						<td><small><?php echo $qrBuscaDados['NOM_USUARIO']; ?></small></td>
						<td class="text-center" style="width:100px;"><b class="f14 text-info">&nbsp;&nbsp;</b></td>
						<td class="text-center"><b class="f14 text-info"><?php echo fnValor($qrBuscaDados['QTD_CLIENTE'], 0); ?></b></td>
						<td class="text-center" style="width:100px;"><b class="f14 text-info">&nbsp;&nbsp;</b></td>
						<td class="text-center corOn" style="background-color: #F8F9F9;"><b class="f14 text-info"><?php echo fnValor($qrBuscaDados['QTD_VENDAS_OFERTA'], 0); ?></b></td>
						<td class="text-center" style="background-color: #F8F9F9;"><b class="f14 text-info"><?php echo fnValor($indice_com_oferta, 2); ?>%</b></td>
						<td class="text-center" style="background-color: #F8F9F9;"><b class="f14 text-info">R$ <?php echo fnValor($qrBuscaDados['VAL_MEDIO_OFERTA'], 2); ?></b></td>
						<td class="text-center"><b class="f14 text-info"><?php echo fnValor($qrBuscaDados['QTD_VENDAS_SEM'], 0); ?></b></td>
						<td class="text-center"><b class="f14 text-info">R$ <?php echo fnValor($qrBuscaDados['VAL_MEDIA_VENDA'], 2); ?></b></td>
						<td class="text-center" style="background-color: #F8F9F9;"><b class="f14 text-info"><?php echo fnValor($variacao_tkt, 2); ?>%</b></td>
					</tr>


				<?php
				}
				?>

				<tr>
					<th class="f14" style="width:40px;">&nbsp;</th>
					<td></td>
					<td class="text-center"><b class="f14 text-info"></b></td>
					<td class="text-center"><b class="f14 text-info"><b><?php echo fnValor($TOT_QTD_CLIENTE, 0); ?></b></td>
					<td class="text-center"><b class="f14 text-info"></td>
					<td class="text-center corOn" style="background-color: #F8F9F9;"><b class="f14 text-info"><?php echo fnValor($TOT_QTD_VENDAS_OFERTA, 0); ?></b></td>
					<td class="text-center" style="background-color: #F8F9F9;"><b class="f14 text-info"></b></td>
					<td class="text-center" style="background-color: #F8F9F9;"><b class="f14 text-info"></b></td>
					<td class="text-center"><b class="f14 text-info"><?php echo fnValor($TOT_QTD_VENDAS_SEM, 0); ?></b></td>
					<td class="text-center"><b class="f14 text-info"></b></td>
					<td class="text-center" style="background-color: #F8F9F9;"><b class="f14 text-info"></b></td>
				</tr>



			</tbody>


		</table>

<?php

		break;
}

?>