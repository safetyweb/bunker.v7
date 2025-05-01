<?php

include '../_system/_functionsMain.php';
// require_once '../js/plugins/Spout/Autoloader/autoload.php';

// use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Common\Type;

$opcao = $_GET['opcao'];
$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
$cod_univend = fnLimpaCampoZero($_GET['idu']);
$lojasSelecionadas = fnDecode($_REQUEST['LOJAS']);
$andData = fnDecode($_REQUEST['DATA']);
$cod_campanha = fnDecode($_REQUEST['COD_CAMPANHA']);
$andCampanha = fnDecode($_REQUEST['AND_CAMPANHA']);

//fnEscreve($andCampanha);

$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);

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

			$andData = fnDecode($_REQUEST['DATA']);

			$sql = "SELECT  
						ret.COD_UNIVEND, 
						ret.COD_UNIVEND COD_UNIVEND_LISTA, 
						g.TIP_GATILHO, 
						ret.LOG_TESTE, 
						ret.ID_DISPARO, 
						ret.COD_CAMPANHA, 
						cap.DES_CAMPANHA, 
						DATE(ret.DAT_CADASTR) DATA_CADASTRO, 
						SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
						SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
						SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
						SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
						SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
					FROM sms_lista_ret ret
					INNER JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
					INNER JOIN campanha cap ON cap.COD_CAMPANHA=ret.cod_campanha
					WHERE ret.CHAVE_CLIENTE IS NOT NULL 
					$andCampanha
					AND ret.COD_EMPRESA=$cod_empresa
					AND DATE(ret.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' 
					AND ret.COD_UNIVEND = $cod_univend
					GROUP BY log_teste, COD_CAMPANHA, DATE(DATA_CADASTRO), ret.cod_univend
					ORDER BY DATE(DATA_CADASTRO) DESC";

			//fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$count=0;
			while ($qrCampanhasEmail = mysqli_fetch_assoc($arrayQuery)){													  
					$count++;

					$dat_envio = fnDataFull($qrBuscaModulos['DAT_ENVIO']);

				  	$contatos_graph = $qrCampanhasEmail[SUB_TOTAL];
	                $sucesso_graph = $qrCampanhasEmail[COD_CCONFIRMACAO];
	                $nrecebidos_graph = $qrCampanhasEmail[COD_NRECEBIDO];
	                $optout_graph = $qrCampanhasEmail[COD_OPTOUT_ATIVO];
	                $falha_graph = $qrCampanhasEmail[BOUNCE];

					$perc_sucesso = fnValorSql(fnValor(($sucesso_graph/$contatos_graph)*100,2));
					$perc_nrecebidos = fnValorSql(fnValor(($nrecebidos_graph/$contatos_graph)*100,2));
					$perc_optout = fnValorSql(fnValor(($optout_graph/$contatos_graph)*100,2));
					$perc_falha = fnValorSql(fnValor(($falha_graph/$contatos_graph)*100,2));
					$perc_aguardo = fnValorSql(fnValor(($aguardo_graph/$contatos_graph)*100,2));

                  // fnEscreve($qrBuscaModulos['COD_DISPARO']);

		?>
					
					<tr>
			           <!-- <td class="text-center"><small><?=$urlAnexo?></small></td> -->
			           <td></td>
			           <td><small><small>(<?=$qrCampanhasEmail['COD_CAMPANHA']?>)</small>&nbsp;<?=$qrCampanhasEmail['DES_CAMPANHA']?></small>&nbsp;<span class="f10"><?=$qrCampanhasEmail['COD_DISPARO_EXT']?></span></td>
			           <td><small><?=fnDatafull($qrCampanhasEmail['DATA_CADASTRO'])?></small></td>
			           <td class='text-right'><small><?=fnValor($contatos_graph,0)?>
			           <td class='text-right'><small><?=fnValor($sucesso_graph,0)?><br/><span class="text-muted" style="font-size: 10px;"><?=fnValor($perc_sucesso,2)?>%</span></small></td>
			           <td class='text-right'><small><?=fnValor($nrecebidos_graph,0)?><br/><span class="text-muted" style="font-size: 10px;"><?=fnValor($perc_nrecebidos,2)?>%</span></small></td>
			           <td class='text-right'><small><?=fnValor($optout_graph,0)?><br/><span class="text-muted" style="font-size: 10px;"><?=fnValor($perc_optout,2)?>%</span></small></td>
			           <td class='text-right'><small><?=fnValor($falha_graph,0)?><br/><span class="text-muted" style="font-size: 10px;"><?=fnValor($perc_falha,2)?>%</span></small></td>
			           <td class='text-right'><small><?=fnValor($aguardo_graph,0)?><br/><span class="text-muted" style="font-size: 10px;"><?=fnValor($perc_aguardo,2)?>%</span></small></td>
			           <?php if($qrCampanhasEmail['COD_DISPARO'] != "" && $_SESSION['SYS_COD_EMPRESA'] == 2){ ?>
			           		<!-- <td class='text-center'><a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick='reprocessaDisparo("<?=fnEncode($cod_campanha)?>","<?=fnEncode($qrCampanhasEmail[COD_DISPARO])?>", this)'><span class="fal fa-cogs"></span></a></td> -->
			       	   <?php }else{ ?>
			       	   		<!-- <td></td> -->
			       	   <?php } ?>
			        </tr>

		<?php

			}

	break;

}

?>