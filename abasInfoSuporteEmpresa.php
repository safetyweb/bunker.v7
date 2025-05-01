<?php
//inicialização
$abaInfoSuporte1278 = "";
$abaInfoSuporte1280 = "";
$abaInfoSuporte1288 = "";

switch ($abaInfoSuporte) {
  case 1278:
        $abaInfoSuporte1278 = "active";

        ?>
        <div class="tabbable-line">

          <ul class="nav nav-tabs ">

            <li class="<?php echo $abaInfoSuporte1280; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1280)."&id=".fnEncode($cod_empresa); ?>"><span class="fa fa-list"></span>&nbsp;
              Lista de Chamados</a>
            </li>

            <?php if(isset($_GET['idC'])){ ?>

            <li class="<?php echo $abaInfoSuporte1288; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1288)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrSac['COD_CHAMADO']); ?>"><span class="fa fa-info-circle"></span>&nbsp&nbsp
              Informações</a>
    

            <li class="<?php echo $abaInfoSuporte12; ?>">
              <a href="#"><span class="fa fa-comment"></span>&nbsp&nbsp
              Comentar</a>
            </li>

            <li class="<?php echo $abaInfoSuporte12; ?>">
              <a href="#?>"><span class="fa fa-user"></span>&nbsp&nbsp
              Responsável</a>
            </li>

            <li class="<?php echo $abaInfoSuporte12; ?>">
              <a href="#"><span class="fa fa-reply"></span>&nbsp&nbsp
              Responder</a>
            </li>

            <li>
              <a href="action.do?mod=<?php echo fnEncode(1278)."&id=".fnEncode($cod_empresa); ?>"><span class="fa fa-plus"></span>&nbsp;
              Criar Chamado </a>
            </li>

          </ul>

      </div>
            <?php } else{ ?>

            <li class="<?php echo $abaInfoSuporte1278; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1278)."&id=".fnEncode($cod_empresa); ?>"><span class="fa fa-plus"></span>&nbsp;
              Criar Chamado </a>
            </li>
            
          </ul>
  
        </div>
        <?php }

  break;
	case 1280:
        $abaInfoSuporte1280 = "active";

        ?>
          <div class="tabbable-line">
            
            <ul class="nav nav-tabs ">
              <li class="<?php echo $abaInfoSuporte1280; ?>">
                <a href="action.do?mod=<?php echo fnEncode(1280)."&id=".fnEncode($cod_empresa); ?>"><span class="fa fa-list"></span>&nbsp;
                Lista de Chamados</a>
              </li>
              
              <li class="<?php echo $abaInfoSuporte12; ?>">
                <a href="action.do?mod=<?php echo fnEncode(1278)."&id=".fnEncode($cod_empresa); ?>"><span class="fa fa-plus"></span>&nbsp;
                Criar Chamado </a>
              </li>
              
            </ul>
          </div>
        <?php 

  break;
  case 1288:
        $abaInfoSuporte1288 = "active";

        ?>
        <div class="tabbable-line">

          <ul class="nav nav-tabs ">

            <li class="<?php echo $abaInfoSuporte1280; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1280)."&id=".fnEncode($cod_empresa); ?>"><span class="fa fa-list"></span>&nbsp;
              Lista de Chamados</a>
            </li>

            <li class="<?php echo $abaInfoSuporte1288; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1288)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrSac['COD_CHAMADO']); ?>"><span class="fa fa-info-circle"></span>&nbsp;
              Informações</a>
            </li>

            <li class="<?php echo $abaInfoSuporte12; ?>">
              <a href="action.do?mod=<?php echo fnEncode(1278)."&id=".fnEncode($cod_empresa); ?>"><span class="fa fa-plus"></span>&nbsp;
              Criar Chamado </a>
            </li>
            
          </ul>
  
        </div>
        <?php 

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


</style>									