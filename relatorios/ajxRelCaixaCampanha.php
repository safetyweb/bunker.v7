<?php 

	include '../_system/_functionsMain.php'; 
	//require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
//use Box\Spout\Writer\WriterFactory;
//use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_univend = $_POST['COD_UNIVEND'];
	
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

			$sql = "SELECT a.COD_CLIENTE CODIGO,
						   b.COD_EXTERNO,
						   b.NOM_CLIENTE NOM_APOIADOR,
						   C.NOM_CLIENTE NOM_ASSESSOR,
						   b.DAT_NASCIME NASCIMENTO,
						   b.NUM_CGCECPF 'CPF/CNPJ',
						   b.NUM_CELULAR CELULAR,
						   DB.NUM_BANCO BANCO,
						   DB.NUM_AGENCIA AGENCIA,
						   DB.NUM_CONTACO CC,
						   DB.NUM_PIX PIX,
						   CASE WHEN DB.TIP_PIX = 1 THEN 'CELULAR'
						 	  WHEN DB.TIP_PIX = 2 THEN 'EMAIL'
						      WHEN DB.TIP_PIX = 3 THEN 'CPF'
						      ELSE ''
						   END AS TIPO_CHAVE,
							case when a.TIP_CONTRAT=1 then
							'Genérico'
							when a.TIP_CONTRAT=2 then
							'Cabo Eleitoral'
							when a.TIP_CONTRAT=3 then
							'Coordenador Cabo Eleitoral'
							when a.TIP_CONTRAT=4 then
							'Cessão Serviços'
							when a.TIP_CONTRAT=5 then
							'Cessão Gratuita de Veículos'
							END AS TIP_CONTRATO,
							a.VAL_CONTRAT,
							IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)VAL_PAGO,
							(a.VAL_CONTRAT-
							IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)) AS VALOR_APAGAR

					FROM CONTRATO_ELEITORAL A
					INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
					LEFT JOIN CLIENTES C ON C.COD_CLIENTE=B.COD_INDICAD
					LEFT JOIN DADOS_BANCARIOS DB ON DB.COD_CLIENTE = B.COD_CLIENTE
					WHERE A.COD_UNIVEND = $cod_univend
					AND A.COD_EMPRESA = $cod_empresa
					AND A.COD_EXCLUSA = 0
					AND A.TIP_CONTRAT NOT IN(4,5)";
					  
					
			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);			
				
				$arquivo = fopen($arquivoCaminho, 'w',0);
                        
				while($headers=mysqli_fetch_field($arrayQuery)){
					 $CABECHALHO[]=$headers->name;
				}
				fputcsv ($arquivo,$CABECHALHO,';','"','\n');
			  
				while ($row=mysqli_fetch_assoc($arrayQuery)){  	
					
					$row[VAL_CONTRAT] = fnValor($row['VAL_CONTRAT'],2);
					$row[VAL_PAGO] = fnValor($row['VAL_PAGO'],2);
					$row[VALOR_APAGAR] = fnValor($row['VALOR_APAGAR'],2);
					//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                    //$textolimpo = json_decode($limpandostring, true);
                    $array = array_map("utf8_decode", $row);
                    fputcsv($arquivo, $array, ';', '"', '\n');	
				}
				fclose($arquivo);

		break;

		case 'paginar':

			$sql = "SELECT a.COD_CLIENTE,
						   b.COD_EXTERNO,
						   b.NOM_CLIENTE,
						   b.NUM_CGCECPF,
							case when a.TIP_CONTRAT=1 then
							'Genérico'
							when a.TIP_CONTRAT=2 then
							'Cabo Eleitoral'
							when a.TIP_CONTRAT=3 then
							'Coordenador Cabo Eleitoral'
							when a.TIP_CONTRAT=4 then
							'Cessão Serviços'
							when a.TIP_CONTRAT=5 then
							'Cessão Gratuita de Veículos'
							END AS TIP_CONTRATO,
							a.VAL_CONTRAT,
							IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)VAL_PAGO,
							(a.VAL_CONTRAT-
							IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)) AS VALOR_APAGAR

					FROM CONTRATO_ELEITORAL A
					INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
					WHERE A.COD_UNIVEND = $cod_univend
					AND A.COD_EMPRESA = $cod_empresa
					AND A.COD_EXCLUSA = 0
					AND A.TIP_CONTRAT NOT IN(4,5)";
			//fnTestesql(connTemp($cod_empresa,''),$sql);		
			// fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			// Filtro por Grupo de Lojas
			//include "filtroGrupoLojas.php";

			$sql = "SELECT a.COD_CLIENTE,
						   b.COD_EXTERNO,
						   b.NOM_CLIENTE,
						   b.NUM_CGCECPF,
						   b.NUM_CGCECPF,
						   b.LOG_JURIDICO,
							case when a.TIP_CONTRAT=1 then
							'Genérico'
							when a.TIP_CONTRAT=2 then
							'Cabo Eleitoral'
							when a.TIP_CONTRAT=3 then
							'Coordenador Cabo Eleitoral'
							when a.TIP_CONTRAT=4 then
							'Cessão Serviços'
							when a.TIP_CONTRAT=5 then
							'Cessão Gratuita de Veículos'
							END AS TIP_CONTRATO,
							a.VAL_CONTRAT,
							IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)VAL_PAGO,
							(a.VAL_CONTRAT-
							IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)) AS VALOR_APAGAR

					FROM CONTRATO_ELEITORAL A
					INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
					WHERE A.COD_UNIVEND = $cod_univend
					AND A.COD_EMPRESA = $cod_empresa
					AND A.COD_EXCLUSA = 0
					AND A.TIP_CONTRAT NOT IN(4,5)
					LIMIT $inicio, $itens_por_pagina";
			//fnEscreve($sql);
                                                    //fnTestesql(connTemp($cod_empresa,''),$sql);											
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
								  
			$count=0;
			while ($qrContrat = mysqli_fetch_assoc($arrayQuery))
			  {		

				$letraPessoa = "F";

				if($qrContrat[LOG_JURIDICO] == "S"){
				    $letraPessoa = "J";
				}						

				$count++;	
				echo"
					<tr>
					  <td>".$qrContrat['COD_EXTERNO']."</td>
					  <td>".$qrContrat['NOM_CLIENTE']."</td>
					  <td class='cpfcnpj'>".fnCompletaDoc($qrContrat['NUM_CGCECPF'],$letraPessoa)."</td>
					  <td>".$qrContrat['TIP_CONTRATO']."</td>
					  <td class='text-right'>R$ ".fnValor($qrContrat['VAL_CONTRAT'],2)."</td>
					  <td class='text-right'>R$ ".fnValor($qrContrat['VAL_PAGO'],2)."</td>
					  <td class='text-right'>R$ ".fnValor($qrContrat['VALOR_APAGAR'],2)."</td>
					</tr>
					"; 
				  }											

			break; 		
	}
?>