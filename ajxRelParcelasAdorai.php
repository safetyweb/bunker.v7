<?php include "_system/_functionsMain.php";

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
$cod_parcela = fnLimpaCampoZero($_GET['cdp']);
$cod_pedido = fnLimpaCampoZero($_GET['idp']);
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$parcelas = json_decode(file_get_contents("php://input"), true);
$opcao = fnLimpacampo($_GET['opcao']);

switch ($opcao) {
	case 'PAG':

	$sqlUpdate = "UPDATE ADORAI_PARCELAS SET 
	TIP_PARCELA = 'L',
	COD_ALTERAC = $cod_usucada,
	DAT_ALTERAC = NOW() 
	WHERE COD_PARCELA = $cod_parcela AND COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa";

	mysqli_query(connTemp($cod_empresa,''),trim($sqlUpdate));

	$sql = "SELECT * FROM ADORAI_PARCELAS WHERE COD_PARCELA = $cod_parcela AND COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa";
	$query = mysqli_query(connTemp($cod_empresa,''),trim($sql));
	$qrResult = mysqli_fetch_assoc($query);

	$valor = $qrResult['VAL_PARCELA'];

	if($qrResult){
		$sqlInsert = "INSERT INTO caixa (
			COD_EMPRESA,
			COD_TIPO,
			VAL_CREDITO,
			DAT_LANCAME,
			DAT_CADASTR,
			COD_USUCADA,
			COD_PARCELA,
			COD_CONTRAT
			)VALUES(
			$cod_empresa,
			1,
			$valor,
			NOW(),
			NOW(),
			$cod_usucada,
			$cod_parcela,
			$cod_pedido
		)";

			mysqli_query(connTemp($cod_empresa,''),trim($sqlInsert));
		}

		break;

		case 'CNL':

		$sqlUpdate = "UPDATE ADORAI_PARCELAS SET 
		TIP_PARCELA = 'C',
		COD_ALTERAC = $cod_usucada,
		DAT_ALTERAC = NOW() 
		WHERE COD_PARCELA = $cod_parcela AND COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa";

		mysqli_query(connTemp($cod_empresa,''),trim($sqlUpdate));

		break;


		case 'LTPAG':

		foreach ($parcelas as $itens) {

			$cod_parcela = $itens['cod_parcela'];
			$cod_pedido = $itens['cod_pedido'];

			$sqlUpdate = "UPDATE ADORAI_PARCELAS SET 
			TIP_PARCELA = 'L',
			COD_ALTERAC = $cod_usucada,
			DAT_ALTERAC = NOW() 
			WHERE COD_PARCELA = $cod_parcela AND COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa";
			
			mysqli_query(connTemp($cod_empresa,''),trim($sqlUpdate));

			$sql = "SELECT * FROM ADORAI_PARCELAS WHERE COD_PARCELA = $cod_parcela AND COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa";
			$query = mysqli_query(connTemp($cod_empresa,''),trim($sql));
			$qrResult = mysqli_fetch_assoc($query);

			$valor = $qrResult['VAL_PARCELA'];

			if($qrResult){
				$sqlInsert = "INSERT INTO caixa (
					COD_EMPRESA,
					COD_TIPO,
					VAL_CREDITO,
					DAT_LANCAME,
					DAT_CADASTR,
					COD_USUCADA,
					COD_PARCELA,
					COD_CONTRAT
					)VALUES(
					$cod_empresa,
					1,
					$valor,
					NOW(),
					NOW(),
					$cod_usucada,
					$cod_parcela,
					$cod_pedido
				)";
					mysqli_query(connTemp($cod_empresa,''),trim($sqlInsert));
				}
			}
			break;

			case 'CNLOTE':

			foreach ($parcelas as $itens) {

				$cod_parcela = $itens['cod_parcela'];
				$cod_pedido = $itens['cod_pedido'];

				$sqlUpdate = "UPDATE ADORAI_PARCELAS SET 
				TIP_PARCELA = 'C',
				COD_ALTERAC = $cod_usucada,
				DAT_ALTERAC = NOW() 
				WHERE COD_PARCELA = $cod_parcela AND COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa";

				mysqli_query(connTemp($cod_empresa,''),trim($sqlUpdate));
			}
			break;
		}


	?>