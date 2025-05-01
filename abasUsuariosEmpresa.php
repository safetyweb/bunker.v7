<?php
//inicialização
$abaUsu1017 = "";
$abaUsu1185 = "";
$abaUsu1252 = "";
$abaUsu1687 = "";

switch ($abaUsuario) {
  case 1017: //usuarios administrativos
    $abaUsu1017 = "active";
    $tipoUsuario = "16,15,9,3,6,1";
    break;
  case 1185: //oper. e vendedores
    $abaUsu1185 = "active";
    $tipoUsuario = "8,7,11,2";
    break;
  case 1252: //webservices
    $abaUsu1252 = "active";
    $tipoUsuario = "10,12";
    break;
  case 1687: //Turnos
    $abaUsu1687 = "active";
    break;
}
/*
adm
9	Administrador
3	Sistema
6	Consultor
1	Gerente
op
8	Vendedor
7	Vendedor (WS /Aut.)
11	Atendente (WS /Aut.)
2	Operador
ws
10	Webservice
12	Webservice Hotsite
*/

if ($cod_empresa == 136 || $cod_empresa == 224 || $cod_empresa == 311) {
  $active = "hidden";
} else {
  $active = "";
}

?>


<div class="tabbable-line">

  <ul class="nav nav-tabs ">
    <li class="<?php echo $abaUsu1017; ?>">
      <a href="action.do?mod=<?php echo fnEncode(1017) . "&id=" . fnEncode($cod_empresa); ?>">
        Usuários Administrativos</a>
    </li>
    <?php
    if ($active == "") {
    ?>
      <li class="<?php echo $abaUsu1185; ?>">
        <a href="action.do?mod=<?php echo fnEncode(1185) . "&id=" . fnEncode($cod_empresa); ?>">
          Operadores, Vendedores e Atendentes </a>
      </li>

      <li class="<?php echo $abaUsu1252; ?>">
        <a href="action.do?mod=<?php echo fnEncode(1252) . "&id=" . fnEncode($cod_empresa); ?>">
          Webservices e Sistema </a>
      </li>

      <li class="<?php echo $abaUsu1687; ?>">
        <a href="action.do?mod=<?php echo fnEncode(1687) . "&id=" . fnEncode($cod_empresa); ?>">
          Turnos </a>
      </li>
  </ul>
<?php
    }
?>
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