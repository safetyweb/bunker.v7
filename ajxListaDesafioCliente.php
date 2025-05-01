<?php 

	include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);	
	$cod_desafio = fnDecode($_GET['codDesafio']);
	$cod_usuario = 	fnLimpaCampoZero(fnDecode($_REQUEST['COD_USUARIO']));
	$andResponsavel = fnLimpaCampo($_REQUEST['andResponsavel']);
	$andVendedor = fnLimpaCampo($_REQUEST['andVendedor']);
	$andLista = fnLimpaCampo($_REQUEST['andLista']);
	$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);

	
	switch ($opcao) {
		   
		case 'exportar':

			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

			$sql = "SELECT B.COD_CLIENTE CODIGO, 
						   B.NUM_CARTAO CARTAO, 
						   B.NUM_CGCECPF CPF, 
						   B.NOM_CLIENTE CLIENTE, 
						   B.DES_EMAILUS EMAIL, 
						   B.NUM_TELEFON TELEFONE, 
						   B.NUM_CELULAR CELULAR, 
						   B.DAT_CADASTR CADASTRO, 
						   B.DAT_NASCIME NASCIMENTO, 
						   B.COD_SEXOPES SEXO,
						   US1.NOM_USUARIO AS RESPONSAVEL,
						   C.NOM_FAIXACAT FAIXA,
						   US2.NOM_USUARIO AS VENDEDOR,
						   A.LOG_CONCLUIDO CONCLUIDO,
						   (SELECT FC.DES_COMENT FROM FOLLOW_CLIENTE FC WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = B.cod_cliente AND FC.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS COMENTARIO,
						   (SELECT FC2.DAT_CADASTR FROM FOLLOW_CLIENTE FC2 WHERE FC2.COD_EMPRESA = $cod_empresa AND FC2.COD_CLIENTE = B.cod_cliente AND FC2.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS CADASTRO_COMENT,
						   (SELECT DES_CLASSIFICA FROM CLASSIFICA_ATENDIMENTO WHERE COD_CLASSIFICA = (SELECT COD_CLASSIFICA FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa))) AS CLASSIFICACAO,
						   (SELECT FC3.DAT_AGENDAME FROM FOLLOW_CLIENTE FC3 WHERE FC3.COD_EMPRESA = $cod_empresa AND FC3.COD_CLIENTE = B.cod_cliente AND FC3.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DAT_AGENDAMENTO
						FROM DESAFIO_CONTROLE A 
						   INNER JOIN CLIENTES B 
						           ON A.COD_CLIENTE = B.COD_CLIENTE 
						              AND A.COD_EMPRESA = B.COD_EMPRESA
						   LEFT JOIN CATEGORIA_CLIENTE C ON C.COD_CATEGORIA = B.COD_CATEGORIA 
						   LEFT JOIN USUARIOS US1 ON US1.COD_USUARIO = A.COD_RESPONSAVEL 
						   LEFT JOIN USUARIOS US2 ON US2.COD_USUARIO = A.COD_VENDEDOR 
						WHERE  B.LOG_AVULSO = 'N' 
						   AND A.COD_DESAFIO = $cod_desafio 
						   AND A.COD_EMPRESA = $cod_empresa
						   $andResponsavel
						   $andVendedor
						   $andLista
						ORDER BY B.NOM_CLIENTE";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$arquivo = fopen($arquivoCaminho, 'w',0);
                        
				while($headers=mysqli_fetch_field($arrayQuery)){
					 $CABECHALHO[]=$headers->name;
				}
				fputcsv ($arquivo,$CABECHALHO,';','"','\n');
			  
				while ($row=mysqli_fetch_assoc($arrayQuery)){

					//busca dados do cliente
					$sqlCred = "CALL total_wallet('$row[COD_CLIENTE]', '$cod_empresa')";
					
					//fnEscreve($sql);
					
					$arrayCred = mysqli_query(connTemp($cod_empresa,''),$sqlCred);
					$qrBuscaTotais = mysqli_fetch_assoc($arrayCred);
                                          
					
					if (isset($arrayCred)){
						
						$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
						                             
					}else{
						
						$credito_disponivel = 0;
						
					}

					if($log_estatus == "N"){
						$credito_disponivel = 0;
					}
					
					$row[NASCIMENTO]= substr($row['NASCIMENTO'],0,5);
					$row[CADASTRO]= fnDataShort($row['CADASTRO']);
					$row[DAT_AGENDAMENTO]= fnDataShort($row['DAT_AGENDAMENTO']);
					$row[CREDITO_DISPONIVEL]= fnValor($credito_disponivel,2);
					
					//$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
					//$textolimpo=json_decode($limpandostring,true);
					$array = array_map("utf8_decode", $row);
					fputcsv ($arquivo,$textolimpo,';','"','\n');	
				}
				fclose($arquivo);

		break;
		case 'paginar':

			$ARRAY_VENDEDOR1=array(
						   'sql'=>"select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
						   'cod_empresa'=>$cod_empresa,
						   'conntadm'=>$connAdm->connAdm(),
						   'IN'=>'N',
						   'nomecampo'=>'',
						   'conntemp'=>'',
						   'SQLIN'=> ""   
						   );
			$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);
																
			$sql = "SELECT COUNT(*) as CONTADOR FROM DESAFIO_CONTROLE A
					WHERE
					A.COD_DESAFIO = $cod_desafio AND 
					A.COD_EMPRESA = $cod_empresa 
					$andResponsavel
					$andVendedor
					$andLista";
			//fnEscreve($sql);
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;												
		
			$sql = "SELECT B.COD_CLIENTE, 
						   B.NUM_CARTAO, 
						   B.NUM_CGCECPF, 
						   B.NOM_CLIENTE, 
						   B.DES_EMAILUS, 
						   B.NUM_TELEFON, 
						   B.NUM_CELULAR, 
						   B.DAT_CADASTR, 
						   B.DAT_NASCIME, 
						   B.COD_SEXOPES,
						   US1.NOM_USUARIO AS NOM_RESPONSAVEL,
						   C.NOM_FAIXACAT,
						   US2.NOM_USUARIO AS NOM_VENDEDOR,
						   A.LOG_CONCLUIDO,
						   (SELECT FC.DES_COMENT FROM FOLLOW_CLIENTE FC WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = B.cod_cliente AND FC.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DES_COMENT,
						   (SELECT FC2.DAT_CADASTR FROM FOLLOW_CLIENTE FC2 WHERE FC2.COD_EMPRESA = $cod_empresa AND FC2.COD_CLIENTE = B.cod_cliente AND FC2.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DAT_CADASTR,
						   (SELECT DES_CLASSIFICA FROM CLASSIFICA_ATENDIMENTO WHERE COD_CLASSIFICA = (SELECT COD_CLASSIFICA FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa))) AS DES_CLASSIFICA,
						   (SELECT FC3.DAT_AGENDAME FROM FOLLOW_CLIENTE FC3 WHERE FC3.COD_EMPRESA = $cod_empresa AND FC3.COD_CLIENTE = B.cod_cliente AND FC3.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DAT_AGENDAME
						FROM DESAFIO_CONTROLE A 
						   INNER JOIN CLIENTES B 
						           ON A.COD_CLIENTE = B.COD_CLIENTE 
						              AND A.COD_EMPRESA = B.COD_EMPRESA
						   LEFT JOIN CATEGORIA_CLIENTE C ON C.COD_CATEGORIA = B.COD_CATEGORIA 
						   LEFT JOIN USUARIOS US1 ON US1.COD_USUARIO = A.COD_RESPONSAVEL 
						   LEFT JOIN USUARIOS US2 ON US2.COD_USUARIO = A.COD_VENDEDOR 
						WHERE  B.LOG_AVULSO = 'N' 
						   AND A.COD_DESAFIO = $cod_desafio 
						   AND A.COD_EMPRESA = $cod_empresa
						   $andResponsavel
						   $andVendedor
						   $andLista
						ORDER BY B.NOM_CLIENTE 
						LIMIT $inicio, $itens_por_pagina 
						";
					
			//(SELECT MAX(cod_venda) FROM vendas v WHERE A.COD_CLIENTE=V.COD_CLIENTE AND v.cod_empresa=A.COD_EMPRESA ) AS max_venda
		    //fnEscreve($sql);
		    //echo($sql);
			
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			
			$count=0;
			while ($qrListaDesafio = mysqli_fetch_assoc($arrayQuery))
			  {														  
				$count++;

				$responsavel = "";

				// fnEscreve($qrListaDesafio['COD_RESPONSAVEL']);

				// $NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaDesafio['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
				// $NOM_ARRAY_NON_RESPONSAVEL=(array_search($qrListaDesafio['COD_RESPONSAVEL'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

				if($qrListaDesafio['COD_RESPONSAVEL'] != 0){
					$responsavel = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_RESPONSAVEL]['NOM_USUARIO'];
				}
											
				if ($qrListaDesafio['COD_SEXOPES'] == 1){		
					$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
				}else{ 
					$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>'; 
				}	
											
				if ($qrListaDesafio['DES_EMAILUS'] != ""){	
					$mostraMail = '<i class="fal fa-envelope-open" aria-hidden="true"></i> '.$qrListaDesafio['DES_EMAILUS'].' <br/>';	
				}else{ 
					$mostraMail = ''; 
				}	
											
				if ($qrListaDesafio['NUM_CELULAR'] != ""){	
					$mostraCel = '<i class="fal fa-mobile" aria-hidden="true"></i> '.$qrListaDesafio['NUM_CELULAR'].' <br/>';	
				}else{ 
					$mostraCel = ''; 
				}	
											
				if ($qrListaDesafio['NUM_TELEFON'] != ""){	
					$mostraFone = '<i class="fal fa-phone" aria-hidden="true"></i> '.$qrListaDesafio['NUM_TELEFON'].' <br/>';	
				}else{ 
					$mostraFone = ''; 
				}

				if($qrListaDesafio['LOG_CONCLUIDO'] == "S"){
					$corBotao = "btn-success";
				}else{
					$corBotao = "btn-default";
				}

				if($qrCat['TEM_CATEGOR'] > 0){
					$categoria = "<td class='text-center'><small>".$qrListaDesafio['NOM_FAIXACAT']."</small></td>";
				}else{
					$categoria = "";
				}

				if($qrListaDesafio['DAT_AGENDAME'] != "" && $qrListaDesafio['DAT_AGENDAME'] < Date("Y-m-d")){
					$corData = "text-danger";
				}else{
					$corData = "";
				}

				//busca dados do cliente
				$sqlCred = "CALL total_wallet('$qrListaDesafio[COD_CLIENTE]', '$cod_empresa')";
				
				//fnEscreve($sql);
				
				$arrayCred = mysqli_query(connTemp($cod_empresa,''),$sqlCred);
				$qrBuscaTotais = mysqli_fetch_assoc($arrayCred);
                                      
				
				if (isset($arrayCred)){
					
					$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
					                             
				}else{
					
					$credito_disponivel = 0;
					
				}

				if($log_estatus == "N"){
					$credito_disponivel = 0;
				}

				// if($qrListaDesafio['DAT_AGENDAME'] < Date()){}
											
				echo"
					<tr id='".$qrListaDesafio['NUM_CARTAO']."'>
					  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaDesafio['COD_CLIENTE'])."' target='_blank'>".$mostraSexo." &nbsp; ".$qrListaDesafio['NOM_CLIENTE']."</a></td>
					  <td><small>".$qrListaDesafio['NUM_CARTAO']."</small></td>
					  ".$categoria."
					  <td><small>".$mostraMail." ".$mostraCel." ".$mostraFone."</small></td>
					  <td class='text-right'><small>".fnValor($credito_disponivel,2)."</small></td>
					  <td><small>".substr($qrListaDesafio['DAT_NASCIME'],0,5)."</small></td>
					  <td><small>".$qrListaDesafio['NOM_RESPONSAVEL']."</small></td>																		  
					  <td><small>".$qrListaDesafio['NOM_VENDEDOR']."</small></td>
					  <td><small></small></td>																			  
					  <td><small>".fnDataShort($qrListaDesafio['DAT_CADASTR'])."<br>".$qrListaDesafio['DES_COMENT']."</small></td>																			  
					  <td><small>".$qrListaDesafio['DES_CLASSIFICA']."</small></td>																		  
					  <td class='text-center $corData'><small>".fnDataShort($qrListaDesafio['DAT_AGENDAME'])."</small></td>																		  
					  <td class='text-center'>
						<a class='btn btn-xs ".$corBotao." addBox' data-url='action.php?mod=".fnEncode(1377)."&id=".fnEncode($cod_empresa)."&idD=".fnEncode($cod_desafio)."&idC=".fnEncode($qrListaDesafio['COD_CLIENTE'])."&pop=true' data-title='Desafio / ".$des_desafio." '>&nbsp; <i class='fas fa-user-tag'></i> &nbsp;</a>
					  </td>
					</tr>
					";
					//<td><small>".$qrListaDesafio['NOM_UNIVEND']."</small></td>
				  }										

			break; 		
	}
?>



