<?php
//inicialização
$abaInfoSuporte1267 = "";
$abaInfoSuporte1282 = "";
$abaInfoSuporte1285 = "";
$abaInfoSuporte1440 = "";

switch ($abaInfoAtendimento) {
  case 1435:
        $abaInfoAtendimento1435 = "active";
  break;
	case 1436:
        $abaInfoAtendimento1436 = "active";
  break;
  case 1440:
        $abaInfoAtendimento1440 = "active";
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

            <li class="<?php echo $abaInfoAtendimento1435; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1435)."&id=".fnEncode($cod_empresa); ?>"><span class="fas fa-list"></span>&nbsp;&nbsp;
              Lista de Atendimentos </a>
            </li>

            <?php if(isset($_GET['idC'])){ ?>
              <li class="<?php echo $abaInfoAtendimento1440; ?>">
                <a href="action.do?mod=<?php echo fnEncode(1440)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_atendimento); ?>"><span class="fa fa-info-circle"></span>&nbsp;&nbsp;
                Informações</a>
              </li>

              <li class="<?php echo $abaInfoAtendimento1436; ?>">
                <a href="action.do?mod=<?php echo fnEncode(1436)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_atendimento); ?>"><span class="fa fa-pencil"></span>&nbsp;&nbsp;
                Editar Atendimento</a>
              </li>

              <!-- <li>
                <a href="action.do?mod=<?php echo fnEncode(1436); ?>"><span class="fas fa-plus"></span>&nbsp;&nbsp;
                Criar Chamado 333 </a>
              </li> -->

              <li>
                <a href="javascript:void(0)" id="EXC" class="exc"><span class="fas fa-trash"></span>&nbsp;&nbsp;
                Excluir Atendimento </a>
              </li>
            <?php }else{ ?>

            <li class="<?php echo $abaInfoAtendimento1436; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1436)."&id=".fnEncode($cod_empresa); ?>"><span class="fas fa-plus"></span>&nbsp;&nbsp;
              Criar Novo Atendimento </a>
            </li>

            <?php } ?>

        </ul>

      </div>