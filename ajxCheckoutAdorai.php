<?php include "_system/_functionsMain.php"; 

echo fnDebug('true');

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
    $and_propriedade = "AND ACI.COD_PROPRIEDADE = $cod_propriedade";

}
if ($cod_chale != ""){
    $and_chale = "AND ACI.COD_CHALE = $cod_chale";
}else{
    $and_chale = " ";
}

if($filtro_data == "ALTERACAO"){
    $andDat = "AND ACI.DAT_ALTERAC >= '$dat_alterac 00:00:00'
    AND ACI.DAT_ALTERAC >= '$dat_alterac 23:59:59'";

}else if($filtro_data == "DEFAULT"){
    $andDat = " AND ACI.DAT_INICIAL >= '$dat_ini 00:00:00'
    AND ACI.DAT_FINAL <= '$dat_fim 23:59:59'";

}else{
    $andDat = "AND ACI.DAT_CADASTR >= '$dat_ini 00:00:00'
    AND ACI.DAT_CADASTR <= '$dat_fim 23:59:59'";

}

if($cod_statuspag != ""){
    $andStatusPag = "AND AC.COD_STATUSPAG = $cod_statuspag";
}else{
    $andStatusPag ="";
}   

if($cod_formapag != ""){
    $andFormaPag = "AND AC.COD_FORMAPAG = $cod_formapag";
}else{
    $andFormaPag ="";
}

switch ($opcao) {
	case 'EXC':
  $sql = "DELETE FROM ADORAI_CARRINHO_ITEMS WHERE 
  COD_ITEM = $cod_item AND COD_PROPRIEDADE = $cod_propriedade AND COD_CARRINHO = $cod_carrinho AND COD_EMPRESA = $cod_empresa
  ";

  $sql2 = "DELETE FROM ADORAI_CARRINHO WHERE COD_CARRINHO = $cod_carrinho AND COD_EMPRESA = $cod_empresa";
  $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
  $arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

  break;	
  case 'SubBusca':

  $sql = "select * from adorai_chales where COD_HOTEL ='".$cod_propriedade."'AND COD_EMPRESA = '" . $cod_empresa . "' AND COD_EXCLUSA != 0";
  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
  ?>
  <select data-placeholder='Selecione o sub grupo' name='COD_CHALE' id='COD_CHALE' class='chosen-select-deselect COD_CHALE'>
    <option value=''>&nbsp;</option>
    <?php
    while ($qrListaChales = mysqli_fetch_assoc($arrayQuery)) {
       ?>
       <option value='<?=$qrListaChales['COD_EXTERNO']?>'><?=$qrListaChales['COD_EXTERNO']?> - <?=$qrListaChales['NOM_QUARTO']?></option> 
       <?php
   }
   ?>
</select> 
<?php

break;

case 'exportar':

$nomeRel = $_GET['nomeRel'];
$arquivoCaminho = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

$sql = "
SELECT DISTINCT 
AC.COD_CARRINHO,
AC.TELEFONE,
ACI.DAT_INICIAL,
ACI.DAT_FINAL,
ACI.VALOR,
UNI.NOM_FANTASI,
ACI.COD_PROPRIEDADE,
ACI.COD_ITEM,
CH.NOM_QUARTO
FROM adorai_carrinho AS AC
INNER JOIN adorai_carrinho_items AS ACI ON ACI.COD_CARRINHO = ac.COD_CARRINHO
LEFT JOIN unidadevenda AS UNI ON UNI.cod_externo = ACI.COD_PROPRIEDADE
LEFT JOIN adorai_chales AS CH ON CH.cod_externo = ACI.COD_CHALE
WHERE
AC.COD_EMPRESA = $cod_empresa 
$andDat
$andStatusPag
$andFormaPag
$and_propriedade
$and_chale
ORDER BY AC.COD_CARRINHO 
";

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));       

$arquivo = fopen($arquivoCaminho, 'w',0);

while($headers=mysqli_fetch_field($arrayQuery)){
    $CABECHALHO[]=$headers->name;
}
fputcsv ($arquivo,$CABECHALHO,';','"','\n');

while ($row=mysqli_fetch_assoc($arrayQuery)){   

    $row[TELEFONE] = fnmasktelefone($row['TELEFONE']);
    $row[DAT_INICIAL] = fnDataShort($row['DAT_INICIAL']);
    $row[DAT_FINAL] = fnDataShort($row['DAT_FINAL']);
    $row[VALOR] = fnValor($row['VALOR'],2);
    
    $array = array_map("utf8_decode", $row);
    fputcsv($arquivo, $array, ';', '"', '\n');  
}
fclose($arquivo);

break;

}										
?>							

<script language=javascript>
	$(".chosen-select-deselect").chosen({
		allow_single_deselect: true
	});


</script>