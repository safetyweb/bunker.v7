<?php
//inicialização

switch ($abaManutTkt) {
    case 1106: //reservas
        $abaManut1106 = "active";
        break;
    case 2108: //links de teste
        $abaManut2108 = "active";
        break;
}

?>


<div class="tabbable-line">

    <ul class="nav nav-tabs ">

        <li class="<?php echo $abaManut2108; ?>">
            <a href="action.do?mod=<?= fnEncode(2108) ?>">
                Categorias
            </a>
        </li>

        <li class="<?php echo $abaManut1106; ?>">
            <a href="action.do?mod=<?= fnEncode(1106) ?>">
                Blocos
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