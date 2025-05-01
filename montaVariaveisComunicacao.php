<?php

	$sqlVar = "select cod_bancovar from comunicacao_modelo where cod_comunic = $cod_comunic";
	fnEscreve($sqlVar);
	$arrayQueryVariaveis = mysqli_query(connTemp($cod_empresa,""),trim($sqlVar)) or die(mysqli_error());
	$qrListaVariaveis = $arrayQueryVariaveis->fetch_assoc();
	
	$cod_bancovar = $qrListaVariaveis['cod_bancovar'];
	
	if(empty($cod_bancovar)){
		$sqlVar = "select * from variaveis where cod_bancovar in ('')";
	}else{
		$sqlVar = "select * from variaveis where cod_bancovar in ($cod_bancovar)";
	}

	fnEscreve($sqlVar);
	$arrayQueryVariaveis = mysqli_query($connAdm->connAdm(),$sqlVar) or die(mysqli_error());
	
	$fields = "";
	
	while ($qrListaVariaveis = mysqli_fetch_assoc($arrayQueryVariaveis)){
		$temp = $qrListaVariaveis['KEY_BANCOVAR'];
		
		if($temp === '@codigo'){
			$fields .= 'COD_CLIENTE as "@codigo",';
	    } else if($temp === '@nome'){
			$fields .= 'NOM_CLIENTE as "@nome",';
		} else if($temp === '@cartao'){
			$fields .= 'NUM_CARTAO as "@cartao",';
		} else if($temp === '@estadoCivil'){
			$fields .= 'A.DES_ESTACIV as "@estadoCivil",';
		} else if($temp === '@sexo'){
			$fields .= 'B.DES_SEXOPES as "@sexo",';
		} else if($temp === '@profissao'){	
			$fields .= 'C.DES_PROFISS as "@profissao",';
		} else if($temp === '@nascimento'){	
			$fields .= 'DAT_NASCIME as "@nascimento",';
		} else if($temp === '@endereco'){
			$fields .= 'DES_ENDEREC as "@endereco",';
		} else if($temp === '@numero'){	
			$fields .= 'NUM_ENDEREC as "@numero",';
		} else if($temp === '@bairro'){	
			$fields .= 'DES_BAIRROC as "@bairro",';
		} else if($temp === '@cidade'){	
			$fields .= 'NOM_CIDADEC as "@cidade",';
		} else if($temp === '@estado'){			
			$fields .= 'COD_ESTADOF as "@estado",';
		} else if($temp === '@cep'){
			$fields .= 'NUM_CEPOZOF as "@cep",';
		} else if($temp === '@complemento'){		
			$fields .= 'DES_COMPLEM as "@complemento",';
		} else if($temp === '@telefone'){		
			$fields .= 'NUM_TELEFON as "@telefone",';
		} else if($temp === '@email'){		
			$fields .= 'DES_EMAILUS as "@email",';
		} else if($temp === '@celular'){		
			$fields .= 'NUM_CELULAR as "@celular",';		
		} else if($temp === '@saldo'){	
			$fields .= "format(((SELECT ifnull(SUM(VAL_SALDO),0)

					FROM CREDITOSDEBITOS 

					WHERE COD_CLIENTE=CLIENTES.COD_CLIENTE AND

						  TIP_CREDITO='C' AND

						  COD_STATUSCRED=1 AND

						  (DAT_EXPIRA > NOW() or(LOG_EXPIRA='N'))

				) ),2,'de_DE') as '@saldo',";
		} else if($temp === '@primeiraCompra'){		
			$fields .= "DATE_FORMAT(DAT_CADASTR, '%d/%m/%Y') DAT_PRIMEIRA as '@primeiraCompra',";
		} else if($temp === '@ultimaCompra'){
			$fields .= "DATE_FORMAT(DAT_ULTCOMPR, '%d/%m/%Y') DAT_ULTIMA as '@ultimaCompra',";
		} else if($temp === '@totalCompras'){		
			$fields .= '(SELECT SUM(VAL_TOTVENDA) FROM  VENDAS E WHERE E.COD_CLIENTE=CLIENTES.COD_CLIENTE) VAL_TOTVALOR as "@totalCompras",';
		}
	}
	
	if(!empty($fields)){
		$sqlVar =   "SELECT 
					". substr($fields, 0, -1) . "
					FROM CLIENTES
					LEFT JOIN  WEBTOOLS.estadocivil A ON CLIENTES.COD_ESTACIV = A.COD_ESTACIV
					LEFT JOIN  WEBTOOLS.sexo B ON CLIENTES.COD_SEXOPES = B.COD_SEXOPES
					LEFT JOIN  WEBTOOLS.profissoes C ON CLIENTES.COD_PROFISS = C.COD_PROFISS
					WHERE LOG_AVULSO='N' and COD_CLIENTE = $cod_cliente";	
		fnEscreve($sqlVar);
		$arrayQuery44 = mysqli_query(connTemp($cod_empresa,""),trim($sqlVar)) or die(mysqli_error());	
		$qrListaVariaveis = $arrayQuery44->fetch_assoc();
	}else{
		$qrListaVariaveis = "";
	}
?>