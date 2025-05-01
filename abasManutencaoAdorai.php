<?php
//inicialização
$abaManut1023 = "";
$abaManut1833 = "";
$abaManut1840 = "";
$abaManut1866 = "";
$abaManut1858 = "";
$abaManut1860 = "";
$abaManut1864 = "";
$abaManut1867 = "";
$abaManut1971 = "";
$abaManut2028 = "";

switch ($abaManutencaoAdorai) {
  case 1023: //unidades
  $abaManut1023 = "active";
  break;
  case 1833: //chales
  $abaManut1833 = "active";
  break;
  case 1844: //comodidades
  $abaManut1844 = "active";
  break;
  case 1853: //faq
  $abaManut1853 = "active";
  break;
  case 1840: //templates
  $abaManut1840 = "active";
  break;
  case 1867: //template. Avulsa
  $abaManut1867 = "active";
  break;
  case 1866: //msg. Avulsa
  $abaManut1866 = "active";
  break;
  case 1858: //propriedades
  $abaManut1858 = "active";
  break;
  case 1860: //CANAL
  $abaManut1860 = "active";
  break;
  case 1971: //RELATORIO CONSULTAS
  $abaManut1971 = "active";
  break;
  case 2028: //RELATORIO CONSULTAS
  $abaManut2028 = "active";
  break;
}

$active = "";

?>


<div class="tabbable-line">

  <ul class="nav nav-tabs ">
    <li class="<?php echo $abaManut1023; ?>">
      <a href="action.do?mod=<?=fnEncode(1023)?>&id=<?=fnEncode(274)?>&popUp=true">
        Hotéis
      </a>
    </li>

    <li class="<?php echo $abaManut1833; ?>">
      <a href="action.do?mod=<?=fnEncode(1833)?>">
        Chalés
      </a>
    </li>

    <li class="<?php echo $abaManut1858; ?>">
      <a href="action.do?mod=<?=fnEncode(1858)?>">
        Propriedades
      </a>
    </li>

    <li class="<?php echo $abaManut1844; ?>">
      <a href="action.do?mod=<?=fnEncode(1844)?>">
        Comodidades
      </a>
    </li>

    <li class="<?php echo $abaManut1853; ?>">
      <a href="action.do?mod=<?=fnEncode(1853)?>">
        Faq Geral
      </a>
    </li>

    <li class="<?php echo $abaManut2028; ?>">
      <a href="action.do?mod=<?=fnEncode(2028)?>">
        Faq Propriedades
      </a>
    </li>

    <li class="<?php echo $abaManut1860; ?>">
      <a href="action.do?mod=<?=fnEncode(1860)?>">
        Canal Comunicação
      </a>
    </li>

<!--     <li class="<?php echo $abaManut1840; ?>">
      <a href="action.do?mod=<?=fnEncode(1840)?>">
        Templates Comunicação
      </a>
    </li> -->

    <!-- <li class="<?php echo $abaManut1867; ?>">
      <a href="action.do?mod=<?=fnEncode(1867)?>">
        Template Msg. Avulsa
      </a>
    </li> -->

<!--     <li class="<?php echo $abaManut1866; ?>">
      <a href="action.do?mod=<?=fnEncode(1866)?>">
        Mensagem Avulsa
      </a>
    </li> -->

    <li class="<?php echo $abaManut1864; ?>">
      <a href="action.do?mod=<?=fnEncode(1864)?>&id=<?=fnEncode(274)?>">
        Relatórios
      </a>
    </li>

    <li class="<?php echo $abaManut1971; ?>">
      <a href="action.do?mod=<?=fnEncode(1971)?>">
        API Kommo
      </a>
    </li>
    
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