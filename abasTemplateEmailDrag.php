<?php
//inicialização
$abaInfoSuporte1411 = ""; //Aba OptOut
$abaInfoSuporte1478 = ""; //Aba Template
$abaInfoSuporte = fnDecode($_GET['mod']);
switch ($abaInfoSuporte) {
  case 1411:
    $abaInfoSuporte1411 = "active";
    break;
  case 1478:
    $abaInfoSuporte1478 = "active";
    break;
}
?>
<style>

  /* Tabs panel */
  .tabbable-panel {
    border:0;
    padding: 10px;
  }

  /* Default mode */
  .tabbable-line > .nav-tabs {
    border: none;
    margin: 0px;
  }
  .tabbable-line > .nav-tabs > li {
    margin-right: 2px;
  }
  .tabbable-line > .nav-tabs > li > a {
    border: 0;
    margin-right: 0;
    color: #737373;
    font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 15px;
    padding: 10px 15px;
  }
  .tabbable-line > .nav-tabs > li > a > i {
    color: #a6a6a6;
  }
  .tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
    border-bottom: 4px solid #fbcdcf;
  }
  .tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
    border: 0;
    background: none !important;
    color: #333333;
  }
  .tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
    color: #a6a6a6;
  }
  .tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
    margin-top: 0px;
  }
  .tabbable-line > .nav-tabs > li.active {
    border-bottom: 4px solid #18bc9c;
    position: relative;
  }
  .tabbable-line > .nav-tabs > li.active > a {
    border: 0;
    color: #333333;
  }
  .tabbable-line > .nav-tabs > li.active > a > i {
    color: #404040;
  }
  .tabbable-line > .tab-content {
    margin-top: -3px;
    background-color: #fff;
    border: 0;
    border-top: 1px solid #eee;
    padding: 15px 0;
  }
  .portlet .tabbable-line > .tab-content {
    padding-bottom: 0;
  }

  /* Below tabs mode */

  .tabbable-line.tabs-below > .nav-tabs > li {
    border-top: 4px solid transparent;
  }
  .tabbable-line.tabs-below > .nav-tabs > li > a {
    margin-top: 0;
  }
  .tabbable-line.tabs-below > .nav-tabs > li:hover {
    border-bottom: 0;
    border-top: 4px solid #fbcdcf;
  }
  .tabbable-line.tabs-below > .nav-tabs > li.active {
    margin-bottom: -2px;
    border-bottom: 0;
    border-top: 4px solid #f3565d;
  }
  .tabbable-line.tabs-below > .tab-content {
    margin-top: -10px;
    border-top: 0;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
  }

  .exc{
    color:#e74c3c!important;
  }


</style>									


<div class="tabbable-line">

  <ul class="nav nav-tabs ">
    <li class="<?php echo $abaInfoSuporte1478; ?>">
      <a href="action.do?mod=<?=fnEncode(1478)?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($cod_template)?>&idc=<?=fnEncode($cod_campanha)?>&pop=<?=$popUp?>">
        <span class="fa fa-info-circle"></span>&nbsp;&nbsp;Template</a>
    </li>
    <?php //if (!$isCad) {
      ?>
      <li class="<?php echo $abaInfoSuporte1411; ?>">
        <a  href="action.do?mod=<?=fnEncode(1411)?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($cod_template)?>&idc=<?=fnEncode($cod_campanha)?>&pop=<?=$popUp?>">
          <span class="fas fa-cog"></span>&nbsp;&nbsp;Configurações</a>
      </li>
    <?php //} ?>
  </ul>

</div>