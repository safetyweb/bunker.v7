<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];	
	$cod_empresa = fnDecode($_GET['id']);
	
	$cod_cliente = $_POST['COD_CLIENTE'];
	$itens_carregar_mais = $_GET['itens_carregar_mais'];
	
	switch ($opcao) {

		case 'exportar':
		
		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

		$sql = "CALL LISTA_WALLET('$cod_cliente', '$cod_empresa', 0, 99999)";
		//echo($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
		
		$arquivo = fopen($arquivoCaminho, 'w',0);
			
		while($headers=mysqli_fetch_field($arrayQuery)){
			$CABECHALHO[]=$headers->name;
		}
		fputcsv ($arquivo,$CABECHALHO,';','"','\n');

		while ($row=mysqli_fetch_assoc($arrayQuery)){ 

			$row[VAL_PONTUACAO] = fnValor($row['VAL_PONTUACAO'],2);
			$row[VAL_CREDITO] = fnValor($row['VAL_CREDITO'],2);
			$row[VAL_SALDO] = fnValor($row['VAL_SALDO'],2);
						
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"', '\n');
			
			$sqlitems = "SELECT '',A.COD_PRODUTO,B.COD_EXTERNO,B.DES_PRODUTO,a.QTD_PRODUTO,a.VAL_UNITARIO,a.VAL_TOTITEM,a.VAL_TOTLIQUI   
						FROM itemvenda a
						LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
						WHERE a.COD_VENDA = $row[COD_VENDA]
						UNION				
						SELECT '',A.COD_PRODUTO,B.COD_EXTERNO,B.DES_PRODUTO,a.QTD_PRODUTO,a.VAL_UNITARIO,a.VAL_TOTITEM,a.VAL_TOTLIQUI    
						FROM itemvenda_bkp a
						LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
						WHERE a.COD_VENDA = $row[COD_VENDA]
			";

			$arrayitem = mysqli_query(connTemp($cod_empresa,''),$sqlitems);
			//fnEscreve($sqlitems);
				
			$CABECHALHOITEM=["",CODIGO_VENDA,COD_EXTERNO,NOM_PRODUTO,QTD_PRODUTO,VAL_UNI,VAL_TOTAL,VAL_TOTLIQUI];
			fputcsv ($arquivo,$CABECHALHOITEM,';','"','\n');
			
			while($qrListaItem = mysqli_fetch_assoc($arrayitem)){
				$qrListaItem[VAL_UNITARIO] = fnValor($qrListaItem['VAL_UNITARIO'],2);
				$qrListaItem[VAL_TOTITEM] = fnValor($qrListaItem['VAL_TOTITEM'],2);
				$qrListaItem[VAL_TOTLIQUI] = fnValor($qrListaItem['VAL_TOTLIQUI'],2);
				$qrListaItem[QTD_PRODUTO] = fnValor($qrListaItem['QTD_PRODUTO'],2);
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"', '\n');
				}
			}

			fclose($arquivo);
			
		break;

		case 'carregarMais':
		
			$itens_carregar_mais = $_GET['itens_carregar_mais'];
			$casasDec = $_GET['casasDec'];
			$cod_cliente = $_POST['COD_CLIENTE'];
		
			$sql = "CALL LISTA_WALLET('$cod_cliente', '$cod_empresa',$itens_carregar_mais,15)";
											
			// fnEscreve($sql);
			// fnEscreve($sql);
			
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
                                                
			$count = 0;
			$valorTTotal = 0;
			$valorTRegaste = 0;
			$valorTDesconto = 0;
			$valorTvenda = 0;
			
			while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
			  {	
                                                   
				$count++;
				if ($qrBuscaProdutos['TIP_CREDITO'] == "D"){
					$textRed = "text-danger";
					$badge = "";
					$cor = "";
					$txtBadge = "";
					$valorCred = 0;
					$valorDeb = $qrBuscaProdutos['VAL_CREDITO'];
					$tag_campanha = "";
					$tag_persona =  "";	
					$diff_dias =  "";	
					$opcaoExpandir =  "";
					$mostra_expira = 0;
					$cor = "";
					
					//se débito tem recibo de venda
					if ($qrBuscaProdutos['COD_PRODUTO'] > 0){
					// $opcaoExpandir =  "<a href='javascript:void(0);' onclick='abreDetail(".$qrBuscaProdutos['COD_CREDITO'].",".$qrBuscaProdutos['COD_VENDA'].")'><i class='fa fa-receipt' aria-hidden='true'></i></a>";

						$opcaoExpandir =  "<a type='button' class='addBox' data-title='Recibo de Resgate' data-url='action.php?mod=".fnEncode(1250)."&id=".fnEncode($cod_empresa)."&idR=".fnEncode($qrBuscaProdutos['COD_CREDITO'])."&idC=".fnEncode(0)."&pop=true'><i class='fa fa-receipt' aria-hidden='true'></i></a>";																						
					} 
						
				}else {
					$badge = "badge";
					$txtBadge = "txtBadge";

					$diff_dias = fnDateDif(fnDataSql($qrBuscaProdutos['atual']),fnDataSql($qrBuscaProdutos['DAT_EXPIRA']));
					if($diff_dias > 0){
						$mostra_expira = $diff_dias;
						$cor = "background:#18bc9c;";
					}else{
						$mostra_expira = 0;
						$cor = "background:red; color:white;";
					}


					$textRed = "";
					$valorCred = $qrBuscaProdutos['VAL_CREDITO'];
					$valorDeb = 0;
					
					if ($qrBuscaProdutos['COD_VENDA'] != 0){
						//mostrar detalhes da venda	
						$tag_campanha = "<li class='tag'><span class='label label-info'>● &nbsp; ".$qrBuscaProdutos['DES_CAMPANHA']."</span></li>";
						$tag_persona =  "<li class='tag'><span class='label label-warning'>● &nbsp; ".$qrBuscaProdutos['DES_PERSONA']."</span></li>";
						$opcaoExpandir =  "<a href='javascript:void(0);'onclick='abreDetail(".$qrBuscaProdutos['COD_CREDITO'].",\"".$qrBuscaProdutos['COD_VENDA']."\",\"".$qrBuscaProdutos['COD_ITEMVEN']."\",\"".$qrBuscaProdutos['NOM_VENDEDOR']."\",\"".$qrBuscaProdutos['NOM_ATENDENTE']."\")'><i class='fa fa-plus' aria-hidden='true'></i></a>";																						
						
					}else{
				
						$tag_campanha = "";
						$tag_persona =  "";	
						$opcaoExpandir = "";
						
					}


					if (strlen($qrBuscaProdutos['DAT_EXPIRA']) == 0 || $qrBuscaProdutos['DAT_EXPIRA'] == "1969-12-31" ){
						$data = " "; 
					}else{
						$data = date("d/m/Y", strtotime($qrBuscaProdutos['DAT_EXPIRA']));
					}


					
				}													
				
				if ($qrBuscaProdutos['STATUS_AVULSO'] == 16){
					$dataLancamento = 	fnDataFull($qrBuscaProdutos['DAT_CADASTR']);
					$codLancamento = $qrBuscaProdutos['COD_CREDITO'];
				}else{
					$dataLancamento = 	fnDataFull($qrBuscaProdutos['DAT_CADASTR2']);
					$codLancamento = $qrBuscaProdutos['COD_VENDAPDV'];
				}	
				
				
				echo"
					<tr id="."cod_credito_".$qrBuscaProdutos['COD_CREDITO'].">															
					  <td class='text-center'>".$opcaoExpandir."</td>
				      <td><small>".$dataLancamento."</small></td>
					  <td>".$qrBuscaProdutos['COD_VENDA']."</td>												
					  <td>".$codLancamento."</td>												
					  <td>".$qrBuscaProdutos['COD_CUPOM']."</td>												
					  <td class='text-center ".$textRed." '>".$qrBuscaProdutos['TIP_CREDITO']."</td>												
					  <td class='text-right ".$textRed." ".$textRed."'>".fnValor($valorCred,2)."</td>
					  <td class='text-right ".$textRed." '>".fnValor($valorDeb,2)."</td>
					  <td><small>".$data."</small></td>												
					  <td class='text-center'><span class='".$badge." text-center' style='".$cor."'><span class='".$txtBadge." ".$textRed."'>".$mostra_expira."</span></span></td>		
					  <td>".$qrBuscaProdutos['DES_ABREVIA']."</td>												
					  <td class='".$textRed."'>".$qrBuscaProdutos['DES_STATUSCRED']."</td>
					  <td>".$qrBuscaProdutos['NOM_FANTASI']."</td>";
					if (fnDecode($_GET['mod']) != "1211") {
					echo"
					  <td>".$tag_campanha."</td>												
					  <td>".$tag_persona."</td>";
					}  
				echo"											
					</tr>";
				echo"
					<tr style='display:none; background-color: #fff;' id='abreDetail_".$qrBuscaProdutos['COD_CREDITO']."' idvenda='".$qrBuscaProdutos['COD_VENDA']."'>
						<td></td>
						<td colspan='14'>
						<div id='mostraDetail_".$qrBuscaProdutos['COD_CREDITO']."'>
						</div>
						</td>
					</tr>														  
					";
				  }				

		break; 	
		}	
?>