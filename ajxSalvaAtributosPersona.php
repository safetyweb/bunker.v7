<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_persona = fnLimpaCampoZero(fnDecode($_REQUEST['COD_PERSONA']));
$tip_filtro = fnLimpaCampo($_REQUEST['TIP_FILTRO']);
if (is_array($_REQUEST['COD_ATRIBUTO'])) {
    $cods_atributo = $_REQUEST['COD_ATRIBUTO'];
} else {
    $cods_atributo[] = fnLimpaCampoZero($_REQUEST['COD_ATRIBUTO']);
}
$tip_atributo = fnLimpaCampo($_REQUEST['TIP_ATRIBUTO']);

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

if($tip_filtro == ""){
	$tip_filtro = 1;
}

if ($tip_atributo == "fil") {

	$sql = "UPDATE atributos_produtopersona SET TIP_FILTRO='".$tip_filtro."' where COD_PERSONA = '".$cod_persona."' ";

    fnEscreve($sql);
    mysqli_multi_query(connTemp($cod_empresa,''),$sql);
    exit;

} elseif ($tip_atributo == "exc") {

    $sql = "DELETE FROM ATRIBUTOS_PRODUTOPERSONA WHERE COD_PERSONA = $cod_persona;";

} else {

    $sql = "DELETE FROM ATRIBUTOS_PRODUTOPERSONA WHERE COD_PERSONA = $cod_persona AND TIP_ATRIBUTO = $tip_atributo;";

}

foreach ($cods_atributo as $cod_atributo) {

    if ($cod_atributo != 0) {
        $sql .= "INSERT INTO ATRIBUTOS_PRODUTOPERSONA(
							COD_EMPRESA,
							COD_PERSONA,
							COD_ATRIBUTO,
							TIP_ATRIBUTO,
							COD_USUCADA,
							TIP_FILTRO
							)VALUES(
							$cod_empresa,
							$cod_persona,
							$cod_atributo,
							$tip_atributo,
							$cod_usucada,
							$tip_filtro
							);
					";

        // fnEscreve($sql);
    }

}

fnEscreve($sql);
mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
