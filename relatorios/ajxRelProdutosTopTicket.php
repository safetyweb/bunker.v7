<?php 

	include '../_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$cod_externo = fnLimpaCampo($_POST['COD_EXTERNO']);			
	$cod_produto = fnLimpaCampo($_POST['COD_PRODUTO']);
	$cod_univend_aut =(implode("|", $_POST["COD_UNIVEND_AUT"]));
	$cod_propriedade = fnLimpacampoZero($_REQUEST['COD_PROPRIEDADE']);	
	$andUsuario = fnLimpaCampo(fnDecode($_POST['AND_USUARIO']));	
	$lojasSelecionadas = fnLimpaCampo($_POST['LOJAS']);	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	
	if($cod_externo != ""){
		$andCodExt = "AND prod.COD_EXTERNO = '$cod_externo'";
	}else{
		$andCodExt = "";
	}

	if($cod_produto != ""){
		$andCodProd = "AND itm_venda.COD_PRODUTO = '$cod_produto'";
	}else{
		$andCodProd = "";
	}

	if(fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='1'){
		$CarregaMaster='1';
	
	} else {
		$CarregaMaster='0';
	}													
	$where = "";
	
	if($carregaMaster == '1'){
		$andUnivend = "";
	}


	if(count($cod_univend_aut) > 0){
		$where = " AND prdtkt.COD_UNIVEND_AUT REGEXP '^(" .$cod_univend_aut. ")'";
		$andUnidade = " AND ven.COD_UNIVEND IN (".str_replace("|", ",",$cod_univend_aut).")";
	}else if($CarregaMaster == 1){
		$where = "";
	}else{
		$where = "AND prdtkt.COD_UNIVEND REGEXP '^(".str_replace(",", "|",$andUsuario).")'";
		$andUnidade = " AND ven.COD_UNIVEND IN (".str_replace(",", "|",$andUsuario).")";
	}

	if($cod_propriedade != 0){
		$andProp = " AND UNI.COD_PROPRIEDADE = $cod_propriedade";
	}else{
		$andProp = "";
	}
	
	switch ($opcao) {
		case 'exportar':

			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
				   
			$sql = "SELECT 
					       tmptkt.NOM_FANTASI,
					       tmptkt.DES_PRODUTO,
							CASE
					         WHEN prdtkt.cod_produto IS NOT NULL
					              AND prdtkt.dat_fimptkt >= '$dat_ini 00:00:00' THEN 'S'
					         ELSE 'N'
					       END PROD_TICKET,
					       tmptkt.COD_PRODUTO,
					       tmptkt.COD_EXTERNO,
					       tmptkt.COD_VENDA,
					       tmptkt.QTD_PRODUTO,
					       tmptkt.VAL_TOTITEM,
					       tmptkt.VAL_DESCONTO,
					       tmptkt.VAL_LIQUIDO


					FROM (

					SELECT 
					       itm_venda.cod_ticket,
					       TKT.cod_produto PRODUTO_TKT,
					       itm_venda.cod_produto,
					       uni.nom_fantasi,
					       itm_venda.cod_itemven,
					       itm_venda.cod_externo,
					       itm_venda.cod_venda,
					       Sum(itm_venda.qtd_produto)  QTD_PRODUTO,
					       Sum(itm_venda.val_totitem)  VAL_TOTITEM,
					       Sum(itm_venda.val_desconto) VAL_DESCONTO,
					       Sum(itm_venda.val_liquido)  VAL_LIQUIDO,
					       itm_venda.dat_cadastr,
					       prod.des_produto,
					       cli.nom_cliente,
					       ven.cod_univend
					FROM   vendas ven
					       INNER JOIN itemvenda itm_venda ON itm_venda.cod_venda = ven.cod_venda AND ven.cod_empresa = itm_venda.cod_empresa
					       INNER JOIN ticket TKT ON TKT.cod_ticket = itm_venda.cod_ticket AND TKT.cod_empresa = ven.cod_empresa
					       INNER JOIN clientes cli  ON cli.cod_cliente = ven.cod_cliente
					       INNER JOIN produtocliente prod ON prod.cod_produto = itm_venda.cod_produto
					       LEFT JOIN unidadevenda UNI ON uni.cod_univend = ven.cod_univend
					WHERE  ven.cod_empresa = $cod_empresa
					       AND ven.log_ticket = 'S'
					       AND date(ven.dat_cadastr_ws) BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					       AND ven.COD_UNIVEND IN ($lojasSelecionadas)
					       $andProp
					       $andCodExt
						   $andCodProd
					 GROUP  BY ven.cod_univend,    itm_venda.cod_produto 
					)tmptkt
					 LEFT JOIN produtotkt prdtkt ON prdtkt.cod_produto = tmptkt.cod_produto
					 GROUP  BY tmptkt.cod_univend,    tmptkt.cod_produto";

			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$arquivo = fopen($arquivoCaminho, 'w',0);
                
			while($headers=mysqli_fetch_field($arrayQuery)){
				$CABECHALHO[]=$headers->name;
			}
			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
			while ($row=mysqli_fetch_assoc($arrayQuery)){  	
				$row[VAL_TOTITEM] = fnValor($row['VAL_TOTITEM'],2);
				$row[VAL_LIQUIDO] = fnValor($row['VAL_LIQUIDO'],2);
				$row[VAL_DESCONTO] = fnValor($row['VAL_DESCONTO'],2);
				$row[QTD_PRODUTO] = fnValor($row['QTD_PRODUTO'],0);
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"', '\n');	
			}
			fclose($arquivo);
			/*
			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					// Colunas que são double converte com fnValor
					 


					if($cont > 3 && $cont < 7){
						array_push($newRow, 'R$ '.fnValor($objeto, 2));
					}else if($cont == 3){
						array_push($newRow, fnValor($objeto, 0));
					}else if($cont == 10){

						if($objeto == 1) $listaTicket = "S";
					  	else $listaTicket = "";
					  	
						array_push($newRow, $listaTicket);

					}else{
						//retorno completo
						array_push($newRow, $objeto);
					}
						
					
					  
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
			*/
			break;     
		case 'paginar':											

			break; 		
	}
?>