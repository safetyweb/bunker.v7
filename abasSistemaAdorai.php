<?php
//inicialização
$abaManut2005 = "";

switch ($abaManutencaoAdorai) {
  case 2005: //reservas
    $abaManut2005 = "active";
    break;
  case 2006: //links de teste
    $abaManut2006 = "active";
    break;
  case 2007: //opcionais
    $abaManut2007 = "active";
    break;
  case 2008: //pagamento
    $abaManut2008 = "active";
    break;
  case 2009: //checkouts
    $abaManut2009 = "active";
    break;
  case 2010: //status pagamento
    $abaManut2010 = "active";
    break;
  case 2019: //Relátorios adorai
    $abaManut2019 = "active";
    break;
  case 2022: //Tipos Lançamentos adorai
    $abaManut2022 = "active";
    break;
  case 2032: //parcelas Adorai
    $abaManut2032 = "active";
    break;
  case 2035: //cancelamento Adorai
    $abaManut2035 = "active";
    break;
  case 2030: //cancelamento Adorai
    $abaManut2030 = "active";
    break;
  case 2037: //cancelamento Adorai
    $abaManut2037 = "active";
    break;
  case 2063: //cancelamento Adorai
    $abaManut2063 = "active";
    break;
}

$active = "";

?>


<div class="tabbable-line">

  <ul class="nav nav-tabs ">

    <!-- <li class="<?php echo $abaManut2006; ?>">
      <a href="action.do?mod=<?= fnEncode(2006) ?>&id=<?= fnEncode(274) ?>">
        Links de teste
      </a>
    </li> -->

    <li class="<?php echo $abaManut2007; ?>">
      <a href="action.do?mod=<?= fnEncode(2007) ?>&id=<?= fnEncode(274) ?>">
        Comodidades Opcionais
      </a>
    </li>

    <!--
    <li class="<?php echo $abaManut2008; ?>">
      <a href="action.do?mod=<?= fnEncode(2008) ?>&id=<?= fnEncode(274) ?>">
        Formas de pagamento
      </a>
    </li>
	
	
    <li class="<?php echo $abaManut2010; ?>">
      <a href="action.do?mod=<?= fnEncode(2010) ?>&id=<?= fnEncode(274) ?>">
        Status de pagamento
      </a>
    </li>
	
    <li class="<?php echo $abaManut2022; ?>">
      <a href="action.do?mod=<?= fnEncode(2022) ?>&id=<?= fnEncode(274) ?>">
        Lançamentos
      </a>
    </li>
	-->

    <li class="<?php echo $abaManut2009; ?>">
      <a href="action.do?mod=<?= fnEncode(2009) ?>&id=<?= fnEncode(274) ?>">
        Carrinho
      </a>
    </li>

    <!-- <li class="<?php echo $abaManut2005; ?>">
      <a href="action.do?mod=<?= fnEncode(2005) ?>&id=<?= fnEncode(274) ?>">
        Lista de pedidos
      </a>
    </li> -->

    <li class="<?php echo $abaManut2005; ?>">
      <a href="action.do?mod=<?= fnEncode(2005) ?>&id=<?= fnEncode(274) ?>">
        Reservas
      </a>
    </li>

    <li class="<?php echo $abaManut2019; ?>">
      <a href="action.do?mod=<?= fnEncode(2019) ?>&id=<?= fnEncode(274) ?>">
        Financeiro
      </a>
    </li>

    <li class="<?php echo $abaManut2063; ?>">
      <a href="action.do?mod=<?= fnEncode(2063) ?>&id=<?= fnEncode(274) ?>">
        Cupom
      </a>
    </li>

    <!--
    <li class="<?php echo $abaManut2032; ?>">
      <a href="action.do?mod=<?= fnEncode(2032) ?>&id=<?= fnEncode(274) ?>">
        Parcelas
      </a>
    </li>  
	
    <li class="<?php echo $abaManut2030; ?>">
      <a href="action.do?mod=<?= fnEncode(2030) ?>&id=<?= fnEncode(274) ?>">
        Cancelamentos
      </a>
    </li>  

    <li class="<?php echo $abaManut2035; ?>">
      <a href="action.do?mod=<?= fnEncode(2035) ?>&id=<?= fnEncode(274) ?>">
        Estornos
      </a>
    </li>     

    <li class="<?php echo $abaManut2037; ?>">
      <a href="action.do?mod=<?= fnEncode(2037) ?>&id=<?= fnEncode(274) ?>">
        Vouchers
      </a>
    </li>    
	-->

  </ul>
</div>

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
</style>