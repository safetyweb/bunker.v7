<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';


echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dat_ini = '';
$dat_fim = '';
$dias30 = '';
$hoje = '';

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$modulo = fnDecode(@$_POST['mod']);

//inicialização das variáveis - default	
if ((is_string($dat_ini) && strlen($dat_ini) == 0) || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}

if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (!is_string($cod_univend) || strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

/*$ARRAY_UNIDADE1=array(
	   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
	   'cod_empresa'=>$cod_empresa,
	   'conntadm'=>$connAdm->connAdm(),
	   'IN'=>'N',
	   'nomecampo'=>'',
	   'conntemp'=>'',
	   'SQLIN'=> ""   
   	);
         * 
         
    $ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
         * 
         */
/*$ARRAY_VENDEDOR1=array(
                               'sql'=>"select COD_USUARIO,NOM_USUARIO,COD_EXTERNO from usuarios where cod_empresa=$cod_empresa",
                               'cod_empresa'=>$cod_empresa,
                               'conntadm'=>$connAdm->connAdm(),
                               'IN'=>'N',
                               'nomecampo'=>'',
                               'conntemp'=>'',
                               'SQLIN'=> ""   
                               );
    $ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);
	*/

if ($modulo != 1617) {
	$selectValores = ", 
    	SUM(A.VAL_TOTVENDA) as VAL_TOTVENDA, 
    	SUM(A.VAL_TOTFIDELIZ) as VAL_TOTFIDELIZ";
} else {
	$selectValores = "";
}

switch ($opcao) {
	case 'exportarVendedor':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


		if (@$_POST['LOG_ONLINE'] == 'S') {
			$sql = "SELECT COD_USUARIO,
    		COD_UNIVEND,
    		NOM_FANTASI,
    		NOM_USUARIO,
    		COD_VENDEDOR,
    		COD_EXTERNO,
    		IFNULL(TRUNCATE(SUM(VAL_VINCULADO),2),0) VAL_VINCULADO,
    		SUM(QTD_TOTAVULSA)                  QTD_TOTAVULSA,
    		SUM(QTD_TOTFIDELIZ)                 QTD_TOTFIDELIZ,
    		(SUM(QTD_TOTAVULSA) + SUM(QTD_TOTFIDELIZ)) as QTD_TOTVENDAS, 
    		round(((SUM(QTD_TOTFIDELIZ) / (SUM(QTD_TOTAVULSA)  + sum(QTD_TOTFIDELIZ)))  * 100),2) AS PCT_FIDELIZADO,
    		TRUNCATE(SUM(VAL_TOTFIDELIZ),2)    VAL_TOTFIDELIZ,
    		SUM(VAL_TOTVENDA)                   VAL_TOTVENDA,
    		IFNULL(TRUNCATE(SUM(VAL_RESGATE),2),0)       VAL_RESGATE,
    		IFNULL(TRUNCATE(SUM(VAL_CREDITOGERADO),2),0) VAL_CREDITOGERADO
    		FROM(
    			SELECT																				  
    			A.COD_USUCADA  AS COD_USUARIO,
    			A.COD_UNIVEND,
    			uni.nom_fantasi,
    			A.COD_VENDEDOR,
    			US.NOM_USUARIO,
    			US.COD_EXTERNO,
    			(SELECT SUM(VLR.VAL_VINCULADO) FROM CREDITOSDEBITOS VLR WHERE VLR.COD_EMPRESA=A.COD_EMPRESA 
    				AND VLR.COD_VENDA=A.COD_VENDA 
    				AND TIP_CREDITO = 'D' 
    				GROUP BY VLR.COD_VENDA) AS VAL_VINCULADO,					 
    			CASE WHEN A.COD_AVULSO = 1 THEN A.QTD_VENDA ELSE '0' END AS QTD_TOTAVULSA,
    			CASE WHEN A.COD_AVULSO = 2 THEN 1  ELSE '0'  END AS QTD_TOTFIDELIZ,
    			'0.00'   AS PCT_FIDELIZADO,
    			CASE WHEN A.COD_AVULSO = 2 THEN A.VAL_TOTVENDA ELSE '0.00' END  AS VAL_TOTFIDELIZ,
    			A.VAL_TOTVENDA AS VAL_TOTVENDA,            
    			(SELECT SUM(VLR.VAL_CREDITO) FROM CREDITOSDEBITOS VLR WHERE VLR.COD_EMPRESA=A.COD_EMPRESA 
    				AND VLR.COD_VENDA=A.COD_VENDA 
    				AND TIP_CREDITO = 'D' 
    				GROUP BY VLR.COD_VENDA) AS VAL_RESGATE,
    			(SELECT SUM(VLR.VAL_CREDITO) FROM CREDITOSDEBITOS VLR WHERE VLR.COD_EMPRESA=A.COD_EMPRESA 
    				AND VLR.COD_VENDA=A.COD_VENDA AND TIP_CREDITO = 'C' GROUP BY VLR.COD_VENDA) AS VAL_CREDITOGERADO
    			FROM  VENDAS A
    			INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
    			LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
    			WHERE date(A.DAT_CADASTR_WS) between CURDATE() AND CURDATE()
    			AND   A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) 
    			AND A.COD_EMPRESA = $cod_empresa
    			AND A.COD_UNIVEND IN($lojasSelecionadas) GROUP BY A.COD_VENDA) TMPTABLE
    		GROUP  BY COD_VENDEDOR,
    		COD_UNIVEND
    		ORDER BY COD_UNIVEND,
    		PCT_FIDELIZADO DESC";
		} else {
			$sql = "SELECT 	A.COD_USUARIO, 
    		A.COD_UNIVEND,
    		uni.NOM_FANTASI,
    		A.COD_VENDEDOR, 
    		US.NOM_USUARIO,
    		US.COD_EXTERNO,
    		A.QTD_VENDA_CLIENTE_FUNCIONARIO,
    		A.VAL_VENDA_CLIENTE_FUNCIONARIO,
    		A.QTD_VENDA_CLIENTE_INATIVO,
    		A.VAL_VENDA_CLIENTE_INATIVO,
    		SUM(D.VAL_VINCULADO) VAL_VINCULADO,
    		SUM(A.QTD_TOTAVULSA) as QTD_TOTAVULSA, 
    		SUM(A.QTD_TOTFIDELIZ) as QTD_TOTFIDELIZ, 
    		(SUM(A.QTD_TOTAVULSA) + SUM(A.QTD_TOTFIDELIZ)) as QTD_TOTVENDAS, 
    		ROUND(((SUM(A.QTD_TOTFIDELIZ)/SUM(A.QTD_TOTVENDA))*100),2)as PCT_FIDELIZADO, 
    		SUM(A.VAL_TOTFIDELIZ) as VAL_TOTFIDELIZ, 
    		SUM(A.VAL_TOTVENDA) as VAL_TOTVENDA, 
    		SUM(D.VAL_RESGATE) VAL_RESGATE,
    		SUM(D.VAL_CREDITO_GERADO) VAL_CREDITOGERADO
    		FROM VENDAS_DIARIAS A 
    		LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA AND D.COD_UNIVEND=A.COD_UNIVEND AND D.COD_VENDEDOR=A.COD_VENDEDOR AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
    		LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
    		LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
    		WHERE A.DAT_MOVIMENTO  between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59' AND 
    		A.COD_EMPRESA = $cod_empresa AND 
    		A.COD_UNIVEND IN($lojasSelecionadas) 
    		GROUP BY A.COD_VENDEDOR,A.COD_UNIVEND 
    		ORDER BY A.COD_UNIVEND, PCT_FIDELIZADO DESC ";
		}

		//fnescreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$row['VAL_VINCULADO'] = fnValor($row['VAL_VINCULADO'], 2);
			$row['VAL_TOTFIDELIZ'] = fnValor($row['VAL_TOTFIDELIZ'], 2);
			$row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);
			$row['VAL_RESGATE'] = fnValor($row['VAL_RESGATE'], 2);
			$row['VAL_CREDITOGERADO'] = fnValor($row['VAL_CREDITOGERADO'], 2);
			$row['VAL_VENDA_CLIENTE_FUNCIONARIO'] = fnValor($row['VAL_VENDA_CLIENTE_FUNCIONARIO'], 2);
			$row['VAL_VENDA_CLIENTE_INATIVO'] = fnValor($row['VAL_VENDA_CLIENTE_INATIVO'], 2);
			$row['PCT_FIDELIZADO'] = fnvalor($row['PCT_FIDELIZADO'], 2) . "%";
			$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $textolimpo);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		break;

	case 'exportarLoja':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT 	
    	uni.NOM_FANTASI LOJA,
    	US.NOM_USUARIO,
    	US.COD_EXTERNO,
    	A.QTD_VENDA_CLIENTE_FUNCIONARIO,
    	A.VAL_VENDA_CLIENTE_FUNCIONARIO,
    	A.QTD_VENDA_CLIENTE_INATIVO,
    	A.VAL_VENDA_CLIENTE_INATIVO,
    	SUM(A.QTD_TOTAVULSA) as QTD_TOTAVULSA, 
    	SUM(A.QTD_TOTFIDELIZ) as QTD_TOTFIDELIZ, 
    	(SUM(A.QTD_TOTAVULSA) + SUM(A.QTD_TOTFIDELIZ)) as QTD_TOTVENDAS, 
    	ROUND(((SUM(A.QTD_TOTFIDELIZ)/SUM(A.QTD_TOTVENDA))*100),2)as PCT_FIDELIZADO
    	$selectValores
    	FROM VENDAS_DIARIAS A 
    	LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA AND D.COD_UNIVEND=A.COD_UNIVEND AND D.COD_VENDEDOR=A.COD_VENDEDOR AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
    	LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
    	LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
    	WHERE A.DAT_MOVIMENTO >= '$dat_ini' AND 
    	A.DAT_MOVIMENTO <= '$dat_fim' AND 
    	A.COD_EMPRESA = $cod_empresa AND 
    	A.COD_UNIVEND IN($lojasSelecionadas) 
    	GROUP BY A.COD_UNIVEND 
    	ORDER BY  A.COD_UNIVEND, PCT_FIDELIZADO DESC";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$PCT_FIDELIZADO_PARC = fnValor(((($row['QTD_TOTFIDELIZ'] - ($row['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $row['QTD_VENDA_CLIENTE_INATIVO'])) / ($row['QTD_TOTAVULSA'] + ($row['QTD_TOTFIDELIZ'] - ($row['QTD_VENDA_CLIENTE_FUNCIONARIO'] + $row['QTD_VENDA_CLIENTE_INATIVO'])))) * 100), 2);

			$row['PCT_FIDELIZADO'] = fnvalor($row['PCT_FIDELIZADO'], 2) . "% / $PCT_FIDELIZADO_PARC %";

			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');

			$QTD_TOTAVULSA += $row['QTD_TOTAVULSA'];
			$QTD_TOTFIDELIZ += $row['QTD_TOTFIDELIZ'];
			$QTD_TOTVENDAS += $row['QTD_TOTVENDAS'];
			$QTD_FUNCIONARIO += $row['QTD_VENDA_CLIENTE_FUNCIONARIO'];
			$QTD_INATIVO += $row['QTD_VENDA_CLIENTE_INATIVO'];
		}
		$PCT_FIDELIZADO = fnValor($QTD_TOTFIDELIZ / ($QTD_TOTAVULSA + $QTD_TOTFIDELIZ) * 100, 2) . "%";
		$PCT_FIDELIZ_TOTAL =  fnValor(((($QTD_TOTFIDELIZ - ($QTD_FUNCIONARIO + $QTD_INATIVO)) / ($QTD_TOTAVULSA + ($QTD_TOTFIDELIZ - ($QTD_FUNCIONARIO + $QTD_INATIVO)))) * 100), 2);

		$totais = array(
			"NOM_UNIVEND" => "Total",
			'vazio' => '',
			'vazio2' => '',
			'QTD_TOTAVULSA' => $QTD_TOTAVULSA,
			'QTD_TOTFIDELIZ' => $QTD_TOTFIDELIZ,
			'QTD_TOTVENDAS' => $QTD_TOTVENDAS,
			'PCT_FIDELIZADO' =>"$PCT_FIDELIZADO/$PCT_FIDELIZ_TOTAL"
		);

		fputcsv($arquivo, $totais, ';', '"');

		fclose($arquivo);
		break;
	case 'paginar':

		break;
}
