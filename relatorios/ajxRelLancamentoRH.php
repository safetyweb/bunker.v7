
<?php 

include '../_system/_functionsMain.php'; 
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;	

	//echo fnDebug('true');

$opcao = $_GET['opcao'];
$tip_lancame = $_POST['TIP_LANCAME'];
$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);		

$itens_por_pagina = $_GET['itens_por_pagina'];	
$pagina = $_GET['idPage'];	
$cod_mes = fnLimpaCampoZero(fnDecode($_POST['COD_MES']));

$andBonifica = "AND LOG_BONIFICA = 'S' ";

$cod_tipo = $_REQUEST['COD_TIPO'];
$cod_tipo_exc = $_REQUEST['COD_TIPO_EXC'];
$andCodTipo = " ";
$Arr_COD_TIPO = $cod_tipo;
$Arr_COD_TIPO_EXC = $cod_tipo_exc;

if (isset($Arr_COD_TIPO)){
	        //array das unidades de venda
	$countUnive = 0;
	if (isset($Arr_COD_TIPO)){
		for ($i=0;$i<count($Arr_COD_TIPO);$i++) 
		{ 
			$str_univend.=$Arr_COD_TIPO[$i].',';
			$countUnive ++; 
		} 
		$str_univend = substr($str_univend,0,-1);
	}       
	$cod_tipo = $str_univend;
}else{
	$cod_tipo = "0";
}

$str_univend = "";

if (isset($cod_tipo_exc)){
	        //array das unidades de venda
	$countUnive = 0;
	if (isset($Arr_COD_TIPO_EXC)){
		for ($i=0;$i<count($Arr_COD_TIPO_EXC);$i++) 
		{ 
			$str_univend.=$Arr_COD_TIPO_EXC[$i].',';
			$countUnive ++; 
		} 
		$str_univend = substr($str_univend,0,-1);
	}       
	$cod_tipo_exc = $str_univend;
}else{
	$cod_tipo_exc = "0";
}


if($tip_lancame == 'F'){
	$andBonifica = "";
	$lancame="AND LOG_LANCAME = '$tip_lancame'";
}

$andCodTipo = " ";
$andCodTipoExc = " ";
if ($cod_tipo != 0){
	$andCodTipo = "AND COD_TIPO IN($cod_tipo) ";
}

if($cod_tipo_exc != 0){
	$andCodTipoExc = "AND COD_TIPO NOT IN($cod_tipo_exc) ";
}
	// fnEscreve($opcao);

$nomeRel = $_GET['nomeRel'];
$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

$writer = WriterFactory::create(Type::CSV);
$writer->setFieldDelimiter(';');
$writer->openToFile($arquivo);

switch ($opcao) {

	case 'exportar':

	$arrayColumnsNames = [];

	$sql= "SELECT DISTINCT CL.COD_CLIENTE, CL.NOM_CLIENTE, CL.VAL_SALBASE, CL.PCT_JURIDICO, CL.NUM_RGPESSO AS 'RG',
	CL.DES_ENDEREC AS 'LOGRADOURO',
	CL.NUM_ENDEREC AS 'NUMERO',
	CL.DES_COMPLEM AS 'COMPLEMENTO',
	CL.DES_BAIRROC AS 'BAIRRO',
	CL.NOM_CIDADEC  AS 'CIDADE',
	CL.NUM_CEPOZOF AS 'CEP',
	CL.DAT_ADMISSAO as 'DATA_ADMISSAO',
	CL.NUM_PIS     AS 'NUMERO_PIS',
	CL.DAT_DEMISSAO AS 'DATA_DEMISSAO',
	(SELECT VAL_LANCAME
		FROM lancamento_automatico,tip_credito 
		WHERE lancamento_automatico.COD_CLIENTE = CL.COD_CLIENTE AND 
		lancamento_automatico.COD_TIPO = tip_credito.COD_TIPO AND 
		lancamento_automatico.TIP_LANCAME != 'B' AND tip_credito.COD_TIPO=1) as 'SALARI0_BASE',
	CASE WHEN BC.NUM_AGENCIA>0 THEN
	CONCAT(BC.NUM_AGENCIA,' - ',NUM_CONTACO)
	ELSE 
	CASE WHEN NUM_PIX > 0 THEN
	CONCAT( 
		CASE WHEN TIP_PIX =3 THEN
		'PIX'
		WHEN TIP_PIX =2 THEN
		'PIX'
		WHEN TIP_PIX =1 THEN
		'PIX'

		END,' - ',NUM_PIX)
	END
	END AS CONTAS
	FROM CLIENTES CL 
	LEFT JOIN dados_bancarios bc ON bc.COD_CLIENTE=CL.COD_CLIENTE
	INNER JOIN CAIXA CX ON CX.COD_CONTRAT = CL.COD_CLIENTE AND CX.COD_MES = $cod_mes AND CX.TIP_LANCAME = '$tip_lancame' $andCodTipo $andCodTipoExc
	WHERE CL.COD_EMPRESA = $cod_empresa AND 
	CL.LOG_TITULAR = 'S'
	$andBonifica  
	ORDER BY CL.NOM_CLIENTE";

			// fnEscreve($sql);

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$array = array();

	$countHead = 0;

	while($qrFunc = mysqli_fetch_assoc($arrayQuery)){

		$newRow = array();
		$val_liquido = 0;

		array_push($newRow, $qrFunc[COD_CLIENTE]);
		array_push($newRow, $qrFunc[NOM_CLIENTE]);
		array_push($newRow, $qrFunc[CONTAS]);
		array_push($newRow, $qrFunc[LOGRADOURO]);
		array_push($newRow, $qrFunc[NUMERO]);
		array_push($newRow, $qrFunc[COMPLEMENTO]);
		array_push($newRow, $qrFunc[BAIRRO]);
		array_push($newRow, $qrFunc[CIDADE]);
		array_push($newRow, $qrFunc[CEP]);
		array_push($newRow, fnDataShort($qrFunc[DATA_ADMISSAO]));
		array_push($newRow, $qrFunc[NUMERO_PIS]);
		array_push($newRow, $qrFunc[DATA_DEMISSAO]);
		array_push($newRow, fnValor($qrFunc[SALARI0_BASE],2));
		if($countHead == 0){
			array_push($arrayColumnsNames, "Cód.");			
			array_push($arrayColumnsNames, "Nome");
			array_push($arrayColumnsNames, "Contas");	
			array_push($arrayColumnsNames, "LOGRADOURO");	
			array_push($arrayColumnsNames, "NUMERO");	
			array_push($arrayColumnsNames, "COMPLEMENTO");	
			array_push($arrayColumnsNames, "BAIRRO");	
			array_push($arrayColumnsNames, "CIDADE");	
			array_push($arrayColumnsNames, "CEP");	
			array_push($arrayColumnsNames, "DATA_ADMISSAO");	
			array_push($arrayColumnsNames, "NUMERO_PIS");	
			array_push($arrayColumnsNames, "DATA_DEMISSAO");
			array_push($arrayColumnsNames, "SALARI0_BASE");	
		}

				// echo "<pre>";
				// print_r($arrayColumnsNames);
				// // print_r($array);
				// echo "</pre>";		

		$sqlLink = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0  $lancame $andCodTipo $andCodTipoExc ORDER BY COD_TIPO ASC";

		$arrayLinks = mysqli_query(connTemp($cod_empresa,''),$sqlLink);

		while($qrCol2 = mysqli_fetch_assoc($arrayLinks)){

			$sqlCaixa2 = "SELECT CAIXA.VAL_CREDITO,
			CAIXA.COD_CAIXA,
			TIP_CREDITO.COD_TIPO,
			TIP_CREDITO.DES_TIPO,
			TIP_CREDITO.TIP_OPERACAO,
			CAIXA.DAT_LANCAME
			FROM CAIXA
			left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
			where CAIXA.COD_CONTRAT=$qrFunc[COD_CLIENTE]
			AND CAIXA.COD_EMPRESA=$cod_empresa 
			AND CAIXA.COD_MES = $cod_mes
			AND CAIXA.DAT_EXCLUSA IS NULL
			AND CAIXA.COD_EXCLUSA = 0
			AND CAIXA.TIP_LANCAME = '$tip_lancame'
			AND TIP_CREDITO.COD_TIPO = $qrCol2[COD_TIPO]
			GROUP BY TIP_CREDITO.COD_TIPO
			ORDER BY TIP_CREDITO.COD_TIPO DESC
			";

					// fnEscreve($sqlCaixa2);
			$arrayCaixa2 = mysqli_query(connTemp($cod_empresa,''),$sqlCaixa2);

			$qrVal = mysqli_fetch_assoc($arrayCaixa2);

			if(mysqli_num_rows($arrayCaixa2) > 0){

				$tip_operacao = $qrVal['TIP_OPERACAO'];

				if ($tip_operacao == "D") {
					$corTexto = "text-danger";
					$val_liquido -= $qrVal['VAL_CREDITO'];
				} else { 
					$corTexto = ""; 
					$val_liquido += $qrVal['VAL_CREDITO'];
				}

				array_push($newRow, fnValor($qrVal['VAL_CREDITO'],2));

			}else{

				array_push($newRow, "");

			}

			if($countHead == 0){
				array_push($arrayColumnsNames, $qrCol2[DES_TIPO]);	
			}


		}

		if ($cod_tipo == 0){

			if($countHead == 0){
				array_push($arrayColumnsNames, "Vl. Líquido");	
			}

			array_push($newRow, fnValor($val_liquido,2));

		}

		$countHead++;
		$array[] = $newRow;
		$newRow = array();

	}

	array_push($newRow, "TOTAIS");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");
	array_push($newRow, "");

	$sqlCol = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0 $lancame $andCodTipo $andCodTipoExc ORDER BY COD_TIPO ASC";
	$arrayCol = mysqli_query(connTemp($cod_empresa, ''), $sqlCol);
            // fnEscreve($sqlCol);											  

	while ($qrCol = mysqli_fetch_assoc($arrayCol)) {


		$sqlTotal = "SELECT IfNull(sum(CAIXA.VAL_CREDITO),0) VAL_TOTSALDO
		FROM CAIXA
		LEFT JOIN TIP_CREDITO ON caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
		WHERE  CAIXA.COD_EMPRESA = $cod_empresa AND 
		CAIXA.COD_MES = $cod_mes AND 
		CAIXA.DAT_EXCLUSA IS NULL AND 
		CAIXA.COD_EXCLUSA = 0 AND 
		CAIXA.TIP_LANCAME = '$tip_lancame' AND 
		TIP_CREDITO.COD_TIPO = $qrCol[COD_TIPO]
		";

		$arrayTotal = mysqli_query(connTemp($cod_empresa, ''), $sqlTotal);
		fnEscreve($sqlTotal);
				//echo($sqlTotal);

		while ($qrColTotal = mysqli_fetch_assoc($arrayTotal)) {

			$saldoTotal . $qrCol[COD_TIPO] = $qrColTotal[VAL_TOTSALDO];
		}

		array_push($newRow, fnValor($saldoTotal . $qrCol[COD_TIPO], 2));

	}

	if ($cod_tipo == 0) {

		$sqlTotalLiq = "SELECT  
		sum(case when TIP_CREDITO.tip_operacao='C' then
			CAIXA.VAL_CREDITO
			END)-
		sum(case when TIP_CREDITO.tip_operacao='D' then
			CAIXA.VAL_CREDITO
			END) VAL_LIQUIDO
		FROM CAIXA
		LEFT JOIN TIP_CREDITO ON caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
		WHERE  CAIXA.COD_EMPRESA = $cod_empresa AND 
		CAIXA.COD_MES = $cod_mes AND 
		CAIXA.DAT_EXCLUSA IS NULL AND 
		CAIXA.COD_EXCLUSA = 0 AND 
		CAIXA.TIP_LANCAME = '$tip_lancame'";

		$arrayTotalLiq = mysqli_query(connTemp($cod_empresa, ''), $sqlTotalLiq);
		$qrTotalLiq = mysqli_fetch_assoc($arrayTotalLiq);

		$val_total_liquido = $qrTotalLiq[VAL_LIQUIDO];

		array_push($newRow, fnValor($val_total_liquido, 2));

	}

	$array[] = $newRow;
	$newRow = array();

	$writer->addRow($arrayColumnsNames);

	$writer->addRows($array);

	$writer->close();



	break;

	case 'paginar':

	$sql = "SELECT DISTINCT 1
	FROM CLIENTES CL 
	INNER JOIN CAIXA CX ON CX.COD_CONTRAT = CL.COD_CLIENTE AND CX.COD_MES = $cod_mes AND CX.TIP_LANCAME = '$tip_lancame' $andCodTipo $andCodTipoExc
	WHERE CL.COD_EMPRESA = $cod_empresa
	$andBonifica
	AND CL.LOG_TITULAR = 'S'";

	$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
	$totalitens_por_pagina = mysqli_num_rows($retorno);
	$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

	$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


	$sql= "SELECT DISTINCT CL.COD_CLIENTE, CL.NOM_CLIENTE, CL.VAL_SALBASE, CL.PCT_JURIDICO, 
	CASE WHEN BC.NUM_AGENCIA>0 THEN
	CONCAT('CONTA - ',BC.NUM_AGENCIA,' - ',NUM_CONTACO)
	ELSE 
	CASE WHEN NUM_PIX > 0 THEN
	CONCAT( 
		CASE WHEN TIP_PIX =3 THEN
		'PIX-CPF/CNPJ'
		WHEN TIP_PIX =2 THEN
		'PIX-Email'
		WHEN TIP_PIX =1 THEN
		'PIX-Celular'

		END,' - ',NUM_PIX)
	END
	END AS CONTAS
	FROM CLIENTES CL 
	LEFT JOIN dados_bancarios bc ON bc.COD_CLIENTE=CL.COD_CLIENTE
	INNER JOIN CAIXA CX ON CX.COD_CONTRAT = CL.COD_CLIENTE AND CX.COD_MES = $cod_mes AND CX.TIP_LANCAME = '$tip_lancame' $andCodTipo $andCodTipoExc
	WHERE CL.COD_EMPRESA = $cod_empresa AND 
	CL.LOG_TITULAR = 'S'
	$andBonifica  
	ORDER BY CL.NOM_CLIENTE ASC LIMIT $inicio,$itens_por_pagina";


					//fnEscreve($sql);

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			//fnEscreve($sql);
	$count=0;
	while ($qrFunc = mysqli_fetch_assoc($arrayQuery)){												  
		$count++;
		$val_liquido=0;

		?>

		<tr>
			<td><small><?=$qrFunc['COD_CLIENTE']?></small></td>
			<td><small><?=$qrFunc['NOM_CLIENTE']?></small></td>
			<td><small><?=$qrFunc['NUM_PIX']?></small></td>
			<td><small><?=$qrFunc['CONTAS']?></small></td>

			<?php 

			$sqlCol2 = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa $lancame $andCodTipo $andCodTipoExc ORDER BY COD_TIPO ASC";
			$arrayCol2 = mysqli_query(connTemp($cod_empresa,''),$sqlCol2);

			while ($qrCol2 = mysqli_fetch_assoc($arrayCol2)){

				$sqlCaixa2 = "SELECT CAIXA.VAL_CREDITO,
				CAIXA.COD_CAIXA,
				TIP_CREDITO.COD_TIPO,
				TIP_CREDITO.DES_TIPO,
				TIP_CREDITO.TIP_OPERACAO,
				CAIXA.DAT_LANCAME
				FROM CAIXA
				left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
				where CAIXA.COD_CONTRAT=$qrFunc[COD_CLIENTE]
				AND CAIXA.COD_EMPRESA=$cod_empresa 
				AND CAIXA.COD_MES = $cod_mes
				AND CAIXA.DAT_EXCLUSA IS NULL
				AND CAIXA.COD_EXCLUSA = 0
				AND CAIXA.TIP_LANCAME = '$tip_lancame'
				AND TIP_CREDITO.COD_TIPO = $qrCol2[COD_TIPO]
				GROUP BY TIP_CREDITO.COD_TIPO
				ORDER BY TIP_CREDITO.COD_TIPO DESC
				";

						// fnEscreve($sqlCaixa2);
				$arrayCaixa2 = mysqli_query(connTemp($cod_empresa,''),$sqlCaixa2);

				$qrVal = mysqli_fetch_assoc($arrayCaixa2);

				if(mysqli_num_rows($arrayCaixa2) > 0){

					$tip_operacao = $qrVal['TIP_OPERACAO'];

					if ($tip_operacao == "D") {
						$corTexto = "text-danger";
						$val_liquido -= $qrVal['VAL_CREDITO'];
					} else { 
						$corTexto = ""; 
						$val_liquido += $qrVal['VAL_CREDITO'];
					}

					?>
					<td><small><?=fnValor($qrVal['VAL_CREDITO'],2)?></small></td>
					<?php 
				}else{
					?>
					<td></td>	
					<?php 
				} 

			}

			if ($cod_tipo == 0){
				?>	
				<td><small><?=fnValor($val_liquido,2)?></small></td>
				<?php
			}
			?>	
		</tr>	
		<?php
	}											
	break;

}										

?>