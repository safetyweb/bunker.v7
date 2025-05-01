<?php include "_system/_functionsMain.php"; 

echo fnDebug('true');

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
$cod_propriedade = fnLimpacampo($_REQUEST['COD_PROPRIEDADE']);
$opcao = fnLimpacampo($_GET['opcao']);

// fnEscreve($cod_propriedade);

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
  case 'SubBusca':

  $sql = "SELECT DISTINCT COD_EXTERNO, NOM_QUARTO FROM adorai_chales WHERE COD_EXCLUSA = 0 AND COD_HOTEL ='".$cod_propriedade."'";
  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
  ?>
  <select data-placeholder='Selecione o sub grupo' name='COD_ACOMODACAO' id='COD_ACOMODACAO' class='chosen-select-deselect COD_ACOMODACAO'>
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


}										
?>							

<script language=javascript>
	$(".chosen-select-deselect").chosen({
		allow_single_deselect: true
	});


</script>