<?php include "_system/_functionsMain.php";

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
$cod_lote = fnLimpaCampoZero($_GET['cdl']);
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));

$sqldelCategor = "UPDATE categoria
SET COD_EXCLUSA = $cod_usucada,
DAT_EXCLUSA = NOW()
WHERE cod_categor IN(
SELECT DISTINCT cod_categor FROM produtocliente
WHERE cod_lote=$cod_lote AND COD_EMPRESA = $cod_empresa)";
mysqli_query(connTemp($cod_empresa, ''), $sqldelCategor);

$sqldelSubcate = "UPDATE subcategoria
SET COD_EXCLUSA = $cod_usucada,
DAT_EXCLUSA = NOW()
WHERE cod_subcate IN(
SELECT DISTINCT cod_subcate FROM produtocliente
WHERE cod_lote=$cod_lote AND COD_EMPRESA = $cod_empresa)";
mysqli_query(connTemp($cod_empresa, ''), $sqldelSubcate);

$sqldelFornece = "DELETE FROM fornecedormrka
WHERE cod_fornecedor IN(
SELECT DISTINCT cod_fornecedor FROM produtocliente
WHERE cod_lote=$cod_lote AND COD_EMPRESA = $cod_empresa)";
mysqli_query(connTemp($cod_empresa, ''), $sqldelFornece);

$sqldelProd = "DELETE  FROM produtocliente
WHERE cod_lote=$cod_lote AND COD_EMPRESA = $cod_empresa";
mysqli_query(connTemp($cod_empresa, ''), $sqldelProd);

$sqldelImport = "DELETE FROM lote_importprod
WHERE cod_lote=$cod_lote AND COD_EMPRESA = $cod_empresa";
mysqli_query(connTemp($cod_empresa, ''), $sqldelImport);
