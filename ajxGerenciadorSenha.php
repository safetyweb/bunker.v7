<?php
include './_system/_functionsMain.php';

$cod_empresa = $_POST['ajxEmp'];
$index = $_POST['ajxIndex'];

if (!isset($cod_empresa)) {
  $cod_empresa = 0;
}

$sqlEmpresa = "SELECT COD_UNIVEND FROM SENHAS_WHATSAPP WHERE COD_EMPRESA = $cod_empresa";
$queryEmp = mysqli_query($connAdm->connAdm(), $sqlEmpresa);

$arrayUnv = [];
while ($qrResult = mysqli_fetch_assoc($queryEmp)) {
  $arrayUnv[] = $qrResult['COD_UNIVEND'];
}

?>

<select data-placeholder="Selecione uma unidade" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect">
  <option value=""></option>
  <?php
  $disabled9999 = in_array(9999, $arrayUnv) ? 'disabled' : '';
  ?>

  <option value="9999" <?php echo $disabled9999; ?>>TODAS</option>
  <?php
  $sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa  ORDER BY NOM_FANTASI ";
  $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
  while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {

    $disabled = in_array($qrListaUnive['COD_UNIVEND'], $arrayUnv) ? 'disabled' : '';

    echo "
                  <option value='" . $qrListaUnive['COD_UNIVEND'] . "' $disabled>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
                ";
  }
  ?>
</select>