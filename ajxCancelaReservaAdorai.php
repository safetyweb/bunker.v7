<?php
include '_system/_functionsMain.php';

$id_reserva = $_GET['id'];
$cod_empresa = 274;
$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
$des_observa = $_GET['obs'];
$cod_pedido = $_GET['idc'];
$data_atual = date("Y-m-d");


$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://api.soufoco.com.br/v1/booking/ota/OTA_CancelRQ',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8" ?>
	<OTA_CancelRQ
	xmlns="http://www.opentravel.org/OTA/2003/05"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_CancelRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1" CancelType="Book">
	<UniqueID ID="' . $id_reserva . '" Type="10"/>
	</OTA_CancelRQ>',
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/xml',
		'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
		'Cookie: foco_api_connectivity_session=eyJpdiI6ImZFTTVwL2tnUFB5dllycFRiWWpqR0E9PSIsInZhbHVlIjoiUXB6clZjTDZHbk01dGU2SThFQjRJUWZjNzNWbStUdTk1RCtFMHg3ajhGQVIxUWRWd0JIaXNMV2orRHNlU3lDeWxDOU9hLzRmd1JkTWNtMFcwSmdqVzUwTDliQmowZjZMMkRMdmtKZU1USmVyamFrZkI4QVFqRUlaek4zY081MlMiLCJtYWMiOiI1YTExMWQ4YWFiNDI0ZjIwOTIyOWE0MTFlNjEwYTQxMjJhN2JhY2QwNzdlMzJmNzg5Nzk1NzA5OWUwZmYwM2I1IiwidGFnIjoiIn0%3D'
	),
));

echo '<?xml version="1.0" encoding="UTF-8" ?>
	<OTA_CancelRQ
	xmlns="http://www.opentravel.org/OTA/2003/05"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_CancelRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1" CancelType="Book">
	<UniqueID ID="' . $id_reserva . '" Type="10"/>
	</OTA_CancelRQ>';

$response = curl_exec($curl);

curl_close($curl);

$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$jsonHotel = json_encode($xml);
$hotel = json_decode($jsonHotel, TRUE);

echo "<pre>";
print_r($response);
echo "</pre>";


if ($hotel['Success'] == 'ok') {

	$sqlCancela = "INSERT INTO ADORAI_CANCELAMENTOS(
		ID_RESERVA, 
		COD_USUCADA,
		DES_OBSERVA,
		COD_PEDIDO,
		COD_EMPRESA,
		COD_STATUS
		) VALUES(
		$id_reserva,
		$cod_usucada,
		'$des_observa',
		$cod_pedido,
		$cod_empresa,
		8
	)";
	mysqli_query(connTemp($cod_empresa, ''), $sqlCancela);

	$sqlStatus = "UPDATE ADORAI_PEDIDO
				SET LOG_STATUSRESERVA = 'Cancelado'
				WHERE COD_PEDIDO = $cod_pedido
				";
	mysqli_query(connTemp($cod_empresa, ''), $sqlStatus);

	//se a data for maior que 7 gera voucher
	$sql = "SELECT AP.DAT_CADASTR,
		SUM(DISTINCT CX.VAL_CREDITO) AS VAL_CREDITO
		FROM ADORAI_PEDIDO AS AP
		INNER JOIN CAIXA AS CX ON CX.COD_CONTRAT = AP.COD_PEDIDO
		INNER JOIN TIP_CREDITO AS TC ON TC.TIP_OPERACAO = 'C'
		WHERE AP.COD_PEDIDO = $cod_pedido";

	$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBusca = mysqli_fetch_assoc($query);

	$val = fnValor($qrBusca['VAL_CREDITO'], 2);
	$vl_voucher =  fnValorSql($val);

	$dat = fnDataSql($qrBusca['DAT_CADASTR']);
	$dif = fnDateDif($dat, $data_atual);

	$dat_expira = date("Y-m-d", strtotime("$data_atual +12 month"));

	if ($dif >= 7) {
		$des_voucher = generateUniqueVoucherCSV(8);
		$sqlVoucher = "INSERT INTO ADORAI_VOUCHER(
				DES_VOUCHER,
				LOG_STATUS,
				VL_VOUCHER,
				COD_PEDIDO,
				DAT_CADASTR,
				DAT_EXPIRA,
				COD_EMPRESA
				)VALUES(
				'$des_voucher',
				'D',
				'$vl_voucher',
				$cod_pedido,
				NOW(),
				'$dat_expira',
				$cod_empresa
			)";
		mysqli_query(connTemp($cod_empresa, ''), $sqlVoucher);

		$sql = "UPDATE ADORAI_CANCELAMENTOS SET
				COD_TIPDEVO = 3,
				COD_STATUS = 4
				WHERE COD_PEDIDO = $cod_pedido";

		mysqli_query(connTemp($cod_empresa, ''), $sql);

		$sqlUpdate = "UPDATE ADORAI_PARCELAS SET
				TIP_PARCELA = 'C',
				DAT_ALTERAC = NOW()
				WHERE COD_PEDIDO = $cod_pedido AND TIP_PARCELA ='A'";

		mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);
	}
}
