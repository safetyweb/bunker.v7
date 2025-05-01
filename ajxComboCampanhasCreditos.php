<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);

// ALTERAÇÃO PEDIDA POR MAURICE, FEITA DIA 07/03/2022 (LIMITE DE CAMPANHAS POR DATA NA COMBO)

$andDatIni = "";
$andDatFim = "";

if ($dat_ini != "") {
  $andDatIni = "AND PM.DAT_CADASTR >= '$dat_ini'";
}

if ($dat_fim != "") {
  $andDatFim = "AND PM.DAT_CADASTR <= '$dat_fim'";
}

?>



<select data-placeholder="Selecione a campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
  <option value=""></option>
  <?php

  $sqlCamp = "SELECT DISTINCT COD_CAMPANHA FROM PEDIDO_MARKA
                WHERE COD_EMPRESA = $cod_empresa
                AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";

  $arrayData = mysqli_query($connAdm->connAdm(), trim($sqlCamp));
  $cod_campanhas = "";

  while ($qrCamp = mysqli_fetch_assoc($arrayData)) {
    $cod_campanhas .= $qrCamp['COD_CAMPANHA'] . ",";
  }

  $cod_campanhas = rtrim(trim($cod_campanhas), ',');

  $sql = "SELECT COD_CAMPANHA, DES_CAMPANHA FROM CAMPANHA 
            WHERE COD_EMPRESA = $cod_empresa 
          -- AND COD_EXT_CAMPANHA IS NOT NULL
            AND COD_CAMPANHA IN($cod_campanhas)
            AND (LOG_PROCESSA = 'S' OR LOG_PROCESSA_SMS = 'S')
            AND LOG_ATIVO = 'S'";

  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
  while ($qrCamp = mysqli_fetch_assoc($arrayQuery)) {
  ?>

    <option value="<?= $qrCamp['COD_CAMPANHA'] ?>"><?= $qrCamp['DES_CAMPANHA'] ?></option>

  <?php
  }

  ?>
</select>
<?php // fnEscreve($sql); 
?>
<script type="text/javascript">
  $(".chosen-select-deselect").chosen();
</script>