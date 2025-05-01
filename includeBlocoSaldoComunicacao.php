<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
  echo fnDebug('true');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}
$arrayQuery = [];
$qrLista = "";
$qtd_sms = 0;
$qtd_wpp = 0;
$qtd_email = 0;


$sql = "SELECT case 
                   when   SUM(PM.QTD_SALDO_ATUAL) <=   SUM(PM.QTD_PRODUTO)
                      then 
                         SUM(PM.QTD_SALDO_ATUAL) 
                      ELSE 
                       SUM(PM.QTD_PRODUTO) - SUM(PM.QTD_SALDO_ATUAL) end QTD_PRODUTO ,
                 PM.TIP_LANCAMENTO,
                 CC.DES_CANALCOM 
          FROM PEDIDO_MARKA PM
          INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
          INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM 
          WHERE PM.COD_ORCAMENTO > 0 
          AND PM.PAG_CONFIRMACAO='S'
          AND  PM.TIP_LANCAMENTO='C'
          AND PM.COD_EMPRESA = $cod_empresa
          AND  PM.QTD_SALDO_ATUAL > 0
          GROUP BY CC.COD_TPCOM";

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

  // fnEscreve($qrLista['QTD_PRODUTO']);

  $count++;

  switch ($qrLista['DES_CANALCOM']) {

    case 'SMS':
      if ($qrLista['TIP_LANCAMENTO'] == 'D') {
        $qtd_sms = $qtd_sms - $qrLista['QTD_PRODUTO'];
      } else {
        $qtd_sms = $qtd_sms + $qrLista['QTD_PRODUTO'];
      }
      break;

    case 'WhatsApp':
      if ($qrLista['TIP_LANCAMENTO'] == 'D') {
        $qtd_wpp = $qtd_wpp - $qrLista['QTD_PRODUTO'];
      } else {
        $qtd_wpp = $qtd_wpp + $qrLista['QTD_PRODUTO'];
      }
      break;

    default:
      if ($qrLista['TIP_LANCAMENTO'] == 'D') {
        $qtd_email = $qtd_email - $qrLista['QTD_PRODUTO'];
      } else {
        $qtd_email = $qtd_email + $qrLista['QTD_PRODUTO'];
      }
      break;
  }
}

?>

<!-- Portlet -->
<div class="portlet portlet-bordered">

  <div class="portlet-body">

    <div class="row text-center">

      <div class="form-group text-center col-md-4 col-lg-4">

        <div class="push20"></div>

        <p><span id="QTD_SALDO_EMAIL"><?= fnValor($qtd_email, 0) ?></span></p>
        <p><b>Saldo Email</b></p>

        <div class="push20"></div>

      </div>

      <div class="form-group text-center col-md-4 col-lg-4">

        <div class="push20"></div>

        <p><span id="QTD_SALDO_SMS"><?= fnValor($qtd_sms, 0) ?></span></p>
        <p><b>Saldo SMS</b></p>

        <div class="push20"></div>

      </div>

      <div class="form-group text-center col-md-4 col-lg-4">

        <div class="push20"></div>

        <p><span id="QTD_SALDO_WPP"><?= fnValor($qtd_wpp, 0) ?></span></p>
        <p><b>Saldo WhatsApp</b></p>

        <div class="push20"></div>

      </div>

    </div>


  </div>

</div>