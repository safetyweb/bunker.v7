<?php 

	include '../_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$cod_empresa = fnDecode($_GET['id']);			
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$dat_ini2 = fnDataSql($_POST['DAT_INI2']);
	$dat_fim2 = fnDataSql($_POST['DAT_FIM2']);
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
	}
	if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
	}

	if (strlen($dat_ini2) == 0 || $dat_ini2 == "1969-12-31") {
	$dat_ini2 = fnDataSql($dias30);
	}
	if (strlen($dat_fim2) == 0 || $dat_fim2 == "1969-12-31") {
	$dat_fim2 = fnDataSql($hoje);
	}


	$andDatIni = '';
	$andDatFim = '';

	if ($opcao == "p1") {
		$andDatIni = $dat_ini;
		$andDatFim = $dat_fim;
	}else if ($opcao == "p2") {
		$andDatIni = $dat_ini2;
		$andDatFim = $dat_fim2;
	}

	// fnEscreve($andDatIni);
	// fnEscreve($andDatFim);

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";
   

	switch ($opcao) {

		case 'paginar':

				$sql = "SELECT 
						A.COD_UNIVEND,B.NOM_FANTASI,
						SUM(A.qtd_cliente_novo_compra) qtd_cliente_novo_compra,
						SUM(A.val_cliente_novo_compra) val_cliente_novo_compra,
						SUM(A.qtd_cliente_antigo_compra)qtd_cliente_antigo_compra,
						SUM(A.val_cliente_antigo_compra)val_cliente_antigo_compra
						FROM vendas_diarias A, unidadevenda B
						WHERE A.COD_UNIVEND = B.COD_UNIVEND AND A.COD_EMPRESA = B.COD_EMPRESA 
						AND A.dat_movimento between '$andDatIni' AND '$andDatFim'
						AND A.COD_EMPRESA=$cod_empresa
						GROUP BY A.cod_univend";


				$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$count = 0;
				while ($qrRetorno = mysqli_fetch_assoc($retorno)) {
					
					$count++;
					echo"
						<tr>
						  <td>".$qrRetorno['NOM_FANTASI']."</td>
						  <td class='text-center'>".fnValor($qrRetorno['qtd_cliente_novo_compra'],0)."</td>
						  <td class='text-right'>R$ ".fnValor($qrRetorno['val_cliente_novo_compra'],2)."</td>
						  <td class='text-center'>".fnValor($qrRetorno['qtd_cliente_antigo_compra'],0)."</td>
						  <td class='text-right'>R$ ".fnValor($qrRetorno['val_cliente_antigo_compra'],2)."</td>
						</tr>
						";
				
			  	}
		break;

		default:

			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

			// fnEscreve($arquivoCaminho);

			$sql = "SELECT A.COD_UNIVEND AS CODIGO,
						B.NOM_FANTASI AS UNIDADE,
						SUM(A.qtd_cliente_novo_compra) AS CLIENTES_NOVOS,
						SUM(A.val_cliente_novo_compra) AS COMPRAS_NOVOS_CLIENTE,
						SUM(A.qtd_cliente_antigo_compra) AS CLIENTES_ANTIGOS,
						SUM(A.val_cliente_antigo_compra) AS COMPRAS_ANTIGOS_CLIENTES
						FROM vendas_diarias A, unidadevenda B
						WHERE A.COD_UNIVEND = B.COD_UNIVEND AND A.COD_EMPRESA = B.COD_EMPRESA 
						AND A.dat_movimento between '$andDatIni' AND '$andDatFim'
						AND A.COD_EMPRESA=$cod_empresa
						GROUP BY A.cod_univend";
			// fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			// fnEscreve($sql);

			$arquivo = fopen($arquivoCaminho, 'w',0);
	                
			while($headers=mysqli_fetch_field($arrayQuery)){
				$CABECHALHO[]=$headers->name;
			}

			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
		
			while ($row=mysqli_fetch_assoc($arrayQuery)){ 

				$row[COMPRAS_NOVOS_CLIENTE] = fnValor($row['COMPRAS_NOVOS_CLIENTE'],2);
				$row[COMPRAS_ANTIGOS_CLIENTES] = fnValor($row['COMPRAS_ANTIGOS_CLIENTES'],2);
	                                
		        $limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
		        $textolimpo=json_decode($limpandostring,true);
		        $array = array_map("utf8_decode", $row);
		        fputcsv ($arquivo,$textolimpo,';','"','\n');

			}

			fclose($arquivo);

		break; 

	}
?>