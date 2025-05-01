<?php 

	include '_system/_functionsMain.php'; 

	//echo fnDebug('true');
	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);	
	$cod_desafio = fnLimpaCampoZero($_POST['COD_DESAFIO']);
	$num_cartao = $_POST['NUM_CARTAO'];

	//fnEscreve('paginação');

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

	$sql = "SELECT COUNT(1) AS TEM_CATEGOR FROM CATEGORIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa";
	$qrCat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
																
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
				   A.COD_RESPONSAVEL,
				   C.NOM_FAIXACAT,
				   A.COD_VENDEDOR,
				   A.LOG_CONCLUIDO,
				   (SELECT FC.DES_COMENT FROM FOLLOW_CLIENTE FC WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = B.cod_cliente AND FC.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DES_COMENT,
				   (SELECT FC2.DAT_CADASTR FROM FOLLOW_CLIENTE FC2 WHERE FC2.COD_EMPRESA = $cod_empresa AND FC2.COD_CLIENTE = B.cod_cliente AND FC2.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DAT_CADASTR,
				   (SELECT DES_CLASSIFICA FROM CLASSIFICA_ATENDIMENTO WHERE COD_CLASSIFICA = (SELECT COD_CLASSIFICA FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa))) AS DES_CLASSIFICA,
				   (SELECT FC3.DAT_AGENDAME FROM FOLLOW_CLIENTE FC3 WHERE FC3.COD_EMPRESA = $cod_empresa AND FC3.COD_CLIENTE = B.cod_cliente AND FC3.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DAT_AGENDAME
				FROM   desafio_controle A 
				   INNER JOIN clientes B 
				           ON A.COD_CLIENTE = B.COD_CLIENTE 
				              AND A.COD_EMPRESA = B.COD_EMPRESA

				    LEFT JOIN CATEGORIA_CLIENTE C ON C.COD_CATEGORIA = B.COD_CATEGORIA
				WHERE  B.LOG_AVULSO = 'N' 
				   AND A.COD_DESAFIO = $cod_desafio 
				   AND A.COD_EMPRESA = $cod_empresa
			       AND B.NUM_CARTAO = '$num_cartao'";
	//fnEscreve($sql);
	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$qrListaDesafio = mysqli_fetch_assoc($arrayQuery);

	$responsavel = "";

	$NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaDesafio['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
	$NOM_ARRAY_NON_RESPONSAVEL=(array_search($qrListaDesafio['COD_RESPONSAVEL'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

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
								
	echo"

		 <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaDesafio['COD_CLIENTE'])."' target='_blank'>".$mostraSexo." &nbsp; ".$qrListaDesafio['NOM_CLIENTE']."</a></td>
		 <td><small>".$qrListaDesafio['NUM_CARTAO']."</small></td>
		 ".$categoria."
		 <td><small>".$mostraMail." ".$mostraCel." ".$mostraFone."</small></td>
		 <td class='text-right'><small>".fnValor($credito_disponivel,2)."</small></td>
		 <td><small>".substr($qrListaDesafio['DAT_NASCIME'],0,5)."</small></td>
		 <td><small>".$responsavel."</small></td>																		  
		 <td><small>".$ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']."</small></td>
		 <td><small></small></td>																			  
		 <td><small>".fnDataShort($qrListaDesafio['DAT_CADASTR'])."<br>".$qrListaDesafio['DES_COMENT']."</small></td>																			  
		 <td><small>".$qrListaDesafio['DES_CLASSIFICA']."</small></td>
		 <td class='text-center'><small>".fnDataShort($qrListaDesafio['DAT_AGENDAME'])."</small></td>																				  
		 <td class='text-center'>
			<a class='btn btn-xs ".$corBotao." addBox' data-url='action.php?mod=".fnEncode(1377)."&id=".fnEncode($cod_empresa)."&idD=".fnEncode($cod_desafio)."&idC=".fnEncode($qrListaDesafio['COD_CLIENTE'])."&pop=true' data-title='Desafio'>&nbsp; <i class='fas fa-user-tag'></i> &nbsp;</a>
		 </td>

		";
									

?>



