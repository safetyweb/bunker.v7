<?php include "../_system/_functionsMain.php"; 

//echo fnDebug('true');

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
$cod_propriedade = fnLimpacampo($_REQUEST['COD_PROPRIEDADE']);
$cod_chale = fnLimpacampo($_REQUEST['COD_CHALE']);
$cod_item = fnLimpacampo($_REQUEST['COD_ITEM']);
$cod_carrinho = fnLimpacampo($_REQUEST['COD_CARRINHO']);
$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);
$opcao = fnLimpacampo($_GET['opcao']);
$filtro_data = $_POST['FILTRO_DATA'];
$cod_statuspag = $_POST['COD_STATUSPAG'];
$cod_formapag = $_POST['COD_FORMAPAG'];

// fnEscreve($cod_empresa);

if ($cod_propriedade == "" OR $cod_propriedade == 9999){
    $and_propriedade = " ";
}else{
    $and_propriedade = "AND AI.COD_PROPRIEDADE = $cod_propriedade";

}
if ($cod_chale != ""){
    $and_chale = "AND AI.COD_CHALE = $cod_chale";
}else{
    $and_chale = " ";
}

if($filtro_data == "ALTERACAO"){
    $andDat = "AND AI.DAT_ALTERAC >= '$dat_ini 00:00:00'
    AND AI.DAT_ALTERAC >= '$dat_fim 23:59:59'";

}else if($filtro_data == "DEFAULT"){
    $andDat = "AI.DAT_INICIAL >= '$dat_ini 00:00:00'
    AND AI.DAT_FINAL <= '$dat_fim 23:59:59'";

}else{
    $andDat = "AI.DAT_CADASTR >= '$dat_ini 00:00:00'
    AND AI.DAT_CADASTR <= '$dat_fim 23:59:59'";

}

if($cod_statuspag != ""){
    $andStatusPag = "AND AP.COD_STATUSPAG = $cod_statuspag";
}else{
    $andStatusPag ="";
}   

if($cod_formapag != ""){
    $andFormaPag = "AND AP.COD_FORMAPAG = $cod_formapag";
}else{
    $andFormaPag ="";
}

switch ($opcao) {

case 'exportar':

$nomeRel = $_GET['nomeRel'];
$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

$sql = "
SELECT
AP.ID_RESERVA,
AP.NOME,
AP.SOBRENOME,
AP.TELEFONE,
AC.NOM_QUARTO,
UNV.NOM_UNIVEND,
AI.DAT_CADASTR,
AI.DAT_INICIAL,
AI.DAT_FINAL,
FP.DES_FORMAPAG,
AP.COD_CUPOM,
CP.TIP_DESCONTO,
CP.VAL_DESCONTO,
AP.VALOR_COBRADO,
AP.VALOR,
AP.VALOR_PEDIDO,
FP.COD_FORMAPAG,
(
    SELECT SUM(ADO.VALOR) 
    FROM adorai_pedido_opcionais AS ADO
    INNER JOIN opcionais_adorai AS APA ON APA.COD_OPCIONAL = ADO.COD_OPCIONAL
    WHERE ADO.COD_PEDIDO = AP.COD_PEDIDO
    AND APA.LOG_CORTESIA != 'S'
) AS VALOR_OPCIONAIS,
(
    SELECT SUM(CX.VAL_CREDITO) FROM caixa AS CX
    WHERE CX.COD_CONTRAT = AP.COD_PEDIDO
) AS TOT_PAGO
FROM adorai_pedido AS AP
INNER JOIN adorai_pedido_items AS AI ON AI.COD_PEDIDO = AP.COD_PEDIDO
INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AI.COD_CHALE
LEFT JOIN adorai_pedido_opcionais AS ACP ON ACP.COD_PEDIDO = AP.COD_PEDIDO
INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AI.COD_PROPRIEDADE
INNER JOIN adorai_formapag AS FP ON FP.COD_FORMAPAG = AP.COD_FORMAPAG
LEFT JOIN CUPOM_ADORAI AS CP ON CP.DES_CHAVECUPOM = AP.COD_CUPOM
LEFT JOIN opcionais_adorai AS OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL
WHERE
$andDat
$andFormaPag
$and_propriedade
$and_chale
$andreserva
GROUP BY AP.COD_PEDIDO
ORDER BY AP.DAT_CADASTR DESC
";
echo $sql;
$arrayQuery = mysqli_query(connTemp($cod_empresa,''), trim($sql));       

$arquivo = fopen($arquivoCaminho, 'w', 0);

// Cabeçalho personalizado
$CABECHALHO = [
    'ID DA RESERVA',
    'CLIENTE',
    'TELEFONE',
    'CHALE / PROPRIEDADE',
    'DATA DE CADASTRO',
    'CHECK-IN',
    'CHECK-OUT',
    'TOTAL PAGO',
    'VALOR A RECEBER',
    'FORMA DE PAGAMENTO'
];

fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

$arrayItens = []; 
$arraysecu = []; 
while ($row = mysqli_fetch_assoc($arrayQuery)) {   

    $val_cobrado = $row['VALOR_COBRADO'];
    $valor = $row['VALOR'];
    $pct = $valor / 2;
    $tot_reserva = $row['VALOR_PEDIDO'] + $row['VALOR_OPCIONAIS'];
    $cod_cupom = $row['COD_CUPOM'];

    $descCupom = 0;
    if ($cod_cupom != "") {
        $qtd_diarias = fnDateDif($row['DAT_INICIAL'], $row['DAT_FINAL']);
        $tip_desconto = $row['TIP_DESCONTO'];
        $val_desconto = $row['VAL_DESCONTO'];

        switch ($tip_desconto) {
            case '1':
                $descCupom = $val_desconto * $qtd_diarias;
                break;
            case '2':
                $pct_desc = $val_desconto / 100;
                $val_diaria = $valor / $qtd_diarias;
                $desc = $val_diaria * $pct_desc;
                $descCupom = $desc * $qtd_diarias;
                break;
            case '3':
                $pct_desc = $val_desconto / 100;
                $descCupom = $tot_reserva * $pct_desc;
                break;
            case '4':
                $descCupom = $val_desconto;
                break;
        }
    }

    $val_descPix = 0;
    if ($row['COD_FORMAPAG'] == 1 && $descCupom == "" && $pct != $val_cobrado) {
        $desc = $tot_reserva - $descCupom;
        $val_descPix = $desc * 0.05;
    }

    if ($descCupom == "") {
        $descCupom = 0;
    }

    $reserva = $tot_reserva - $descCupom - $val_descPix;
    $restaPag = $reserva - $row['TOT_PAGO'];

    // Alterar as chaves para strings com aspas
    $arrayItens['ID DA RESERVA'] = $row['ID_RESERVA'];
    $arrayItens['CLIENTE'] = $row['NOME'] . " " . $row['SOBRENOME'];
    $arrayItens['TELEFONE'] = $row['TELEFONE'];
    $arrayItens['CHALE / PROPRIEDADE'] = $row['NOM_QUARTO'] . " - " . $row['NOM_UNIVEND'];
    $arrayItens['DATA DE CADASTRO'] = fnDataShort($row['DAT_CADASTR']);
    $arrayItens['CHECK-IN'] = fnDataShort($row['DAT_INICIAL']);
    $arrayItens['CHECK-OUT'] = fnDataShort($row['DAT_FINAL']);
    $arrayItens['TOTAL PAGO'] = fnValor($row['TOT_PAGO'], 2);
    $arrayItens['VALOR A RECEBER'] = fnValor($restaPag, 2);
    $arrayItens['FORMA DE PAGAMENTO'] = $row['DES_FORMAPAG'];
    $arraysecu[] = $arrayItens;
    // Converter os dados para UTF-8 e gravar no arquivo CSV
    $array = array_map("utf8_decode", $arrayItens);
    fputcsv($arquivo, $array, ';', '"', '\n');  
}

fclose($arquivo);
print_r($arraysecu);

break;

}										
?>							