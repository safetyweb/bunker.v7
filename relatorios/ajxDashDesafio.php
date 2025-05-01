<?php

include '../_system/_functionsMain.php';
// require_once '../js/plugins/Spout/Autoloader/autoload.php';

// use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Common\Type;

$opcao = $_GET['opcao'];
$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
$cod_desafio = fnLimpaCampoZero($_REQUEST['COD_DESAFIO']);
$tip_ordenac = fnLimpaCampo($_REQUEST['TIP_ORDENAC']);

$sql = "SELECT NOM_FANTASI,
(select NOM_DESAFIO from DESAFIO_V2 where cod_desafio = $cod_desafio) as NOM_DESAFIO,
(select VAL_METADES from DESAFIO_V2 where cod_desafio = $cod_desafio) as VAL_METADES
FROM ".$connAdm->DB.".empresas where COD_EMPRESA = '".$cod_empresa."' 		
";			
	//fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
$nom_desafio = $qrBuscaEmpresa['NOM_DESAFIO'];
$val_metades = $qrBuscaEmpresa['VAL_METADES'];
//fnEscreve($andCampanha);

$dat_ini = $_POST['DAT_INI'];
$dat_fim = $_POST['DAT_FIM'];

switch ($opcao) {

	case 'exportar':

		$log_detalhes = fnLimpaCampo($_GET['detalhes']);
		// fnEscreve($_GET['detalhes']);

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// fnEscreve($arquivoCaminho);

		$sql = "SELECT 
					LOJA,
					CAMPANHA,
					DATA_CADASTRO,
					COD_OPTOUT_ATIVO,
					BOUNCE,
					COD_NRECEBIDO,
					COD_CCONFIRMACAO,
					SUB_TOTAL
				FROM (
					SELECT
						1 ordenacao,
						uni.NOM_FANTASI AS LOJA,
						uni.COD_UNIVEND,
						'' CAMPANHA, 
						'' DATA_CADASTRO, 
						SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
						SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
						SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
						SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
						SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
					FROM unidadevenda uni
					INNER JOIN sms_lista_ret ret ON ret.COD_UNIVEND=uni.COD_UNIVEND
					WHERE ret.CHAVE_CLIENTE IS NOT NULL AND uni.COD_EMPRESA=$cod_empresa 
					AND DATE(ret.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' 
					AND uni.COD_UNIVEND IN($lojasSelecionadas)
					GROUP BY uni.cod_univend 

				UNION ALL
					SELECT
						2 ordenacao,
						'' LOJA,
						ret.COD_UNIVEND,
						cap.DES_CAMPANHA CAMPANHA, 
						DATE(ret.DAT_CADASTR) DATA_CADASTRO, 
						SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
						SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
						SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
						SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
						SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
					FROM sms_lista_ret ret
					INNER JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
					INNER JOIN campanha cap ON cap.COD_CAMPANHA=ret.cod_campanha
					WHERE ret.CHAVE_CLIENTE IS NOT NULL AND ret.COD_EMPRESA=$cod_empresa 
					AND DATE(ret.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' 
					AND ret.COD_UNIVEND IN($lojasSelecionadas)
					GROUP BY log_teste, cap.COD_CAMPANHA,ret.cod_univend)tmpuni

				ORDER BY COD_UNIVEND,ordenacao ASC";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

		$arquivo = fopen($arquivoCaminho, 'w',0);
                
		while($headers=mysqli_fetch_field($arrayQuery)){
			$CABECHALHO[]=$headers->name;
		}

		fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
		while ($row=mysqli_fetch_assoc($arrayQuery)){  	
                                
	        //$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
	        //$textolimpo=json_decode($limpandostring,true);
	        $array = array_map("utf8_decode", $row);
	        fputcsv ($arquivo,$textolimpo,';','"','\n');

		}

		fclose($arquivo);

	break;

	default:

			$andOrdem = "";

			if($tip_ordenac == "ALFA"){
				$andOrdem = "ORDER BY NOM_RESPONSAVEL DESC";
			}else if($tip_ordenac == "BEST"){
				$andOrdem = "ORDER BY VAL_TOTVENDA DESC";
			}

			$andData = fnDecode($_REQUEST['DATA']);

			$sqlUsu = "SELECT
						      Z.VAL_METADES,
								COUNT(A.COD_CLIENTE), 
								usu.NOM_USUARIO NOM_COMUNICADOR,
								usu2.NOM_USUARIO NOM_RESPONSAVEL,
								usu3.NOM_USUARIO ULT_VENDEDOR,
								IFNULL((
						SELECT COUNT(DISTINCT C.COD_CLIENTE)
						FROM VENDAS C,DESAFIO_CONTROLE_V2 D
						WHERE C.COD_CLIENTE=D.COD_CLIENTE 
							AND D.COD_DESAFIO=A.COD_DESAFIO 
							AND C.DAT_CADASTR_WS >= '$dat_ini 00:00:00' 
							AND C.DAT_CADASTR_WS <= '$dat_fim 23:59:59'
							AND D.LOG_CONCLUIDO = 'S' 
							AND C.COD_STATUSCRED != 6),0) QTD_CLIENTE, 
						      IFNULL((
						SELECT SUM(VAL_TOTVENDA)
						FROM VENDAS C,DESAFIO_CONTROLE_V2 D
						WHERE C.COD_CLIENTE=D.COD_CLIENTE 
							AND D.COD_DESAFIO=A.COD_DESAFIO 
							AND C.DAT_CADASTR_WS >= '$dat_ini 00:00:00' 
							AND C.DAT_CADASTR_WS <= '$dat_fim 23:59:59'
							AND D.LOG_CONCLUIDO = 'S' 
							AND C.COD_STATUSCRED != 6),0) VAL_TOTVENDA, 
						      IFNULL((
											SELECT SUM(VAL_CREDITO)
											FROM CREDITOSDEBITOS D,DESAFIO_CONTROLE_V2 E
											WHERE D.COD_CLIENTE=E.COD_CLIENTE 
												AND E.COD_DESAFIO=A.COD_DESAFIO 
												AND D.TIP_CREDITO='D' 
												AND D.DAT_REPROCE >= '$dat_ini 00:00:00' 
												AND D.DAT_REPROCE <= '$dat_fim 23:59:59'
												AND E.LOG_CONCLUIDO = 'S'
												AND D.COD_STATUSCRED != 6),0) VAL_RESGATE,
						 IFNULL((
						SELECT SUM(VAL_TOTVENDA)
						FROM VENDAS E,CREDITOSDEBITOS F, DESAFIO_CONTROLE_V2 G
						WHERE E.COD_VENDA=F.COD_VENDA 
							AND F.COD_CLIENTE=G.COD_CLIENTE 
							AND G.COD_DESAFIO=A.COD_DESAFIO 
							AND F.TIP_CREDITO='D' 
							AND F.DAT_REPROCE >= '$dat_ini 00:00:00' 
							AND F.DAT_REPROCE <= '$dat_fim 23:59:59' 
							AND E.COD_STATUSCRED != 6 
							AND F.COD_STATUSCRED != 6),0) VAL_VENDAS_VINCULADAS


						FROM DESAFIO_CONTROLE_V2 A
						INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE AND A.COD_EMPRESA = B.COD_EMPRESA
						INNER JOIN DESAFIO_V2 Z ON A.COD_DESAFIO = Z.COD_DESAFIO
						LEFT JOIN usuarios usu ON usu.COD_USUARIO = A.COD_USUCADA
						LEFT JOIN usuarios usu2 ON usu2.COD_USUARIO = A.COD_RESPONSAVEL
						LEFT JOIN usuarios usu3 ON usu3.COD_USUARIO = B.COD_VENDEDOR
						WHERE A.COD_DESAFIO = $cod_desafio 
						AND A.COD_EMPRESA = $cod_empresa
						AND A.COD_UNIVEND = $cod_univend
						AND A.LOG_CONCLUIDO = 'S'
						GROUP BY A.COD_RESPONSAVEL
						$andOrdem";

			// $sqlUsu = "SELECT US.COD_USUARIO,
			// 				  US.NOM_USUARIO 
			// 				  FROM USUARIOS US
			// 				  WHERE US.COD_EMPRESA = $cod_empresa
			// 				  AND US.LOG_ESTATUS = 'S'
			// 				  AND US.COD_UNIVEND = $cod_univend
			// 				  AND (US.COD_EXCLUSA IS NULL OR US.COD_EXCLUSA = 0)
			// 				  ORDER BY TRIM(US.NOM_USUARIO)";

			$arrUsu = mysqli_query(connTemp($cod_empresa,''),$sqlUsu);
			// fnEscreve($sqlUsu);

			$num_linhas = mysqli_num_rows($arrUsu);

			$count=0;
			if($num_linhas > 0){

				while ($qrListaUsu = mysqli_fetch_assoc($arrUsu)){													  
						$count++;

			?>
						
						<tr>
				           <td></td>
				           <td><?=$qrListaUsu[NOM_RESPONSAVEL]?></td>
				           <td><?=$qrListaUsu[NOM_COMUNICADOR]?></td>
				           <td class="text-center"><?=fnValor($qrListaUsu[QTD_CLIENTE],0)?></td>
				           <td class="text-right"><?=fnValor($qrListaUsu[VAL_TOTVENDA],2)?></td>
				           <td><?=$qrListaUsu[ULT_VENDEDOR]?></td>
				        </tr>

			<?php
				}

			}else{

			?>
						
						<tr>
						   <td></td>
				           <td colspan="5">Não houve comunicações nessa agenda para <span style="font-weight: 700!important;">essa unidade</span> no período selecionado.</td>
				        </tr>

			<?php


			}

	break;

}

?>