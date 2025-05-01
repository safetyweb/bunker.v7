<?php
//inicialização
$abasCadastroHotsite1 = "";
$abasCadastroHotsite2 = "";

switch ($abasCadastroHotsite) {
  case 1:
    $abasCadastroHotsite1 = "active";
    break;
  case 2:
    $abasCadastroHotsite2 = "active";
    break;
}

$sql = "SELECT DES_DOMINIO, COD_DOMINIO from SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaDesEmpresa = mysqli_fetch_assoc($arrayQuery);
//fnEscreve($qrBuscaCodEmpresa['COD_EMPRESA']);                
$des_dominio = $qrBuscaDesEmpresa['DES_DOMINIO'];

if ($qrBuscaDesEmpresa['COD_DOMINIO'] == 2) {
  $extensaoDominio = ".fidelidade.mk";
} else {
  $extensaoDominio = ".mais.cash";
}

?>

<style>
  /* Tabs panel */
  .tabbable-panel {
    border: 0;
    padding: 10px;
  }

  /* Default mode */
  .tabbable-line>.nav-tabs {
    border: none;
    margin: 0px;
  }

  .tabbable-line>.nav-tabs>li {
    margin-right: 2px;
  }

  .tabbable-line>.nav-tabs>li>a {
    border: 0;
    margin-right: 0;
    color: #737373;
  }

  .tabbable-line>.nav-tabs>li>a>i {
    color: #a6a6a6;
  }

  .tabbable-line>.nav-tabs>li.open,
  .tabbable-line>.nav-tabs>li:hover {
    border-bottom: 4px solid #fbcdcf;
  }

  .tabbable-line>.nav-tabs>li.open>a,
  .tabbable-line>.nav-tabs>li:hover>a {
    border: 0;
    background: none !important;
    color: #333333;
  }

  .tabbable-line>.nav-tabs>li.open>a>i,
  .tabbable-line>.nav-tabs>li:hover>a>i {
    color: #a6a6a6;
  }

  .tabbable-line>.nav-tabs>li.open .dropdown-menu,
  .tabbable-line>.nav-tabs>li:hover .dropdown-menu {
    margin-top: 0px;
  }

  .tabbable-line>.nav-tabs>li.active {
    border-bottom: 4px solid #18bc9c;
    position: relative;
  }

  .tabbable-line>.nav-tabs>li.active>a {
    border: 0;
    color: #333333;
  }

  .tabbable-line>.nav-tabs>li.active>a>i {
    color: #404040;
  }

  .tabbable-line>.tab-content {
    margin-top: -3px;
    background-color: #fff;
    border: 0;
    border-top: 1px solid #eee;
    padding: 15px 0;
  }

  .portlet .tabbable-line>.tab-content {
    padding-bottom: 0;
  }

  /* Below tabs mode */

  .tabbable-line.tabs-below>.nav-tabs>li {
    border-top: 4px solid transparent;
  }

  .tabbable-line.tabs-below>.nav-tabs>li>a {
    margin-top: 0;
  }

  .tabbable-line.tabs-below>.nav-tabs>li:hover {
    border-bottom: 0;
    border-top: 4px solid #fbcdcf;
  }

  .tabbable-line.tabs-below>.nav-tabs>li.active {
    margin-bottom: -2px;
    border-bottom: 0;
    border-top: 4px solid #f3565d;
  }

  .tabbable-line.tabs-below>.tab-content {
    margin-top: -10px;
    border-top: 0;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
  }

  .exc {
    color: #e74c3c !important;
  }
</style>

<div class="row">

  <div class="col-xs-8">


    <div class="tabbable-line">

      <ul class="nav nav-tabs ">

        <li class="<?php echo $abasCadastroHotsite1; ?>">
          <a href="https://<?= $des_dominio . $extensaoDominio ?>/historicoComprasHS.do?id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode($cod_cliente) ?>&pop=true">
            Meu Histórico</a>
        </li>

        <li class="<?php echo $abasCadastroHotsite2; ?>">
          <a href="https://<?= $des_dominio . $extensaoDominio ?>/active.do?idC=<?= fnEncode($cod_cliente) ?>&pop=true">
            Meu Cadastro</a>
        </li>

      </ul>

    </div>

  </div>

  <div class="col-xs-4">

    <div class="push5"></div>
    <a href="https://<?= $des_dominio . $extensaoDominio ?>/" class="btn btn-primary pull-right" onclick="parent.location.reload();"><span class="fal fa-sign-out"></span>&nbsp;Sair</a>
  </div>

</div>