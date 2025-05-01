<?php

//verifica se monta link para empresa
if ($_SESSION["SYS_LOG_MULTEMPRESA"] == "N") {
    $addLinkEmpresa = "&id=" . fnEncode($_SESSION["SYS_COD_EMPRESA"]);
} else {
    $addLinkEmpresa = "";
}

//busca o json atualizado da base (MENU PRINCIPAL)
flush();
$sql = "select * from menuprincipal where COD_SISTEMA=" . $_SESSION["SYS_COD_SISTEMA"];
$jsonAtual = mysqli_query($connAdm->connAdm(), $sql);
$retQueryJsonAtual = mysqli_fetch_assoc($jsonAtual);

//carrega json da tabela
$ARRAY = REPLACE_STD_SET($retQueryJsonAtual['DES_MENUPRI']);
$menuJson = json_decode($ARRAY, true);

$sqlPerfil = "CALL SP_BUSCA_MODULOS_PERFIL(" . $_SESSION["SYS_COD_USUARIO"] . "," . $_SESSION["SYS_COD_SISTEMA"] . ");";
//fnEscreve($sqlPerfil);
// echo $sqlPerfil;

$arrayQueryPerfil = mysqli_query($connAdm->connAdm(), $sqlPerfil);
while ($qrBuscaModuloP = mysqli_fetch_assoc($arrayQueryPerfil)) {
    $array = $qrBuscaModuloP['USU_MODULOS'];
    $arrayPerfil = explode(",", $array);
}


// itens do menu	
$arrMenu = array();
$arrMenuP = array();
$sql1 = "select * from menus order by NOM_MENUSIS";
$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);
$count = 0;
while ($qrBuscaMenu = mysqli_fetch_assoc($arrayQuery1)) {
    array_push($arrMenu, array("cod_menu" => $qrBuscaMenu['COD_MENUSIS'], "nom_menu" => $qrBuscaMenu['NOM_MENUSIS']));
}
for ($cargaM = 0; $cargaM <= count($arrMenu) - 1; $cargaM++) {
    $tipoMenu1 = $arrMenu[$cargaM]['nom_menu'];
    $codMenu1 = $arrMenu[$cargaM]['cod_menu'];
    $modbusca2 = 'MEN_' . $codMenu1;
    $mod = 'dd-handle';
}


// itens do submenu 
$arrSub = array();
$sql2 = "select * from submenus order by nom_submenus";
$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);
$count = 0;
while ($qrBuscaSubMenu = mysqli_fetch_assoc($arrayQuery2)) {
    array_push($arrSub, array("cod_sub" => $qrBuscaSubMenu['COD_SUBMENUS'], "nom_sub" => $qrBuscaSubMenu['NOM_SUBMENUS'], "fim_sub" => $qrBuscaSubMenu['LOG_FINALIZA']));
}
for ($cargaS = 0; $cargaS <= count($arrSub) - 1; $cargaS++) {
    $tipoSUB = $arrSub[$cargaS]['nom_sub'];
    $codSUB = $arrSub[$cargaS]['cod_sub'];
    $fimSUB = $arrSub[$cargaS]['fim_sub'];
    $modbusca1 = 'SUB_' . $codSUB;
}

//módulos
$arrMod = array();
$sql3 = "select * from modulos order by DES_MODULOS";
$arrayQuery3 = mysqli_query($connAdm->connAdm(), $sql3);

$count = 0;
while ($qrBuscaModulo = mysqli_fetch_assoc($arrayQuery3)) {
    array_push($arrMod, array("cod_mod" => $qrBuscaModulo['COD_MODULOS'], "nom_mod" => $qrBuscaModulo['NOM_MODULOS']));
}
for ($cargaM = 0; $cargaM <= count($arrMod) - 1; $cargaM++) {
    $tipoM = $arrMod[$cargaM]['nom_mod'];
    $codM = $arrMod[$cargaM]['cod_mod'];
    $modbusca = 'MOD_' . $codM;
    $mod = 'dd-handle';
}


?>

<style>
    @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
        #menu {
            left: 0;
            z-index: 1;
        }

        /*.navbar-brand {
                float:right;
            }*/
        .navbar-brand-menu {
            float: left;
        }
    }
</style>
<!-- left nav bar -->
<div id="menuLateral">
    <div class="navbar-default navbar-fixed-left">

        <?php
        //verifica menu ativo
        if ($_SESSION["SYS_COD_HOME"] == fnDecode(@$_GET['mod'])) {
            $menuBarMenu = "";
            $menuBarHome = "active";
        } else {
            $menuBarMenu = "active";
            $menuBarHome = "";
        }
        ?>

        <a class="<?php echo $menuBarMenu; ?> navbar-brand btnMenu" href="#menu" title="Menu">
            <i class="fal fa-bars" aria-hidden="true"></i>
            <div class="menuLateralText">MENU</div>
        </a>

        <?php

        if ($_SESSION["SYS_COD_SISTEMA"] == 4) {

            if ($_SESSION["SYS_COD_HOME"] != 0) {

        ?>
                <a class="<?php echo $menuBarHome; ?> navbar-brand" href="action.do?mod=<?php echo fnEncode($_SESSION["SYS_COD_HOME"]) ?>&id=<?php echo fnEncode($_SESSION["SYS_COD_EMPRESA"]) ?>" title="Página Home">
                    <i class="fal fa-rocket" aria-hidden="true"></i>
                    <div class="menuLateralText">Motor de Promoção e Fidelização</div>
                </a>
        <?php
            }
        }
        ?>

        <!--
                            <a class="navbar-brand" href="#" title="Calendário">
                                    <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                    <div class="menuLateralText">Agenda</div>
                            </a>
                            <a class="navbar-brand" href="#" title="Gráficos">
                                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                                    <div class="menuLateralText">Dash Board</div>
                            </a>                 
                            <a class="navbar-brand" href="#" title="Configurações">
                                    <i class="fa fa-cogs" aria-hidden="true"></i>
                                    <div class="menuLateralText">Configurações</div>
                            </a>
							-->
    </div>

</div>
<!-- end left nav bar -->

<!-- menu -->
<nav id="menu" class="navbar-default">
    <ul>
        <?php
        // fnEscreve('testelogin');
        //nivel 1 loop

        //echo "<pre>";
        // print_r($menuJson);
        //echo"<pre>";

        //nivel principal
        for ($i = 0; $i <= count($menuJson) - 1; $i++) {
            $tipoMenu = substr($menuJson[$i]['id'], 0, 3);
            $codMenu = substr($menuJson[$i]['id'], 4, 5);
            $idMenu = $menuJson[$i]['id'];

            //menu primeiro nivel
            if ($tipoMenu == "MEN") {
                $vl = (array_search($codMenu, array_column($arrMenu, 'cod_menu')));
                $menuV = $arrMenu[$vl]['nom_menu'];
                //Verificar se tem sub modulo no menu princiapl
                for ($sub = 0; $sub <= count($menuJson[$i]['children']) - 1; $sub++) {
                    $tipoMenu2 = substr($menuJson[$i]['children'][$sub]['id'], 0, 3);
                    $codMenu2 = substr($menuJson[$i]['children'][$sub]['id'], 4, 5);
                    $idMenu1 = $menuJson[$i]['children'][$sub]['id'];
                    $tem = 'style="display:block;"';
                    if (recursive_array_search($codMenu2, $arrayPerfil) !== false) {
                        $tem = 'style="display:block;"';
                    } else {
                        $tem = 'style="display:none;"';
                    }

                    if (!empty($menuJson[$i]['children'])) {
                        //menu tem filho
                        echo '<li ' . $tem . '><span>' . $menuV . '</span>';
                        echo '<ul>';
                        //echo'<li><a href="#">fff'.$menuV.'</a></li>';

                        //segundo nivel
                        for ($sub = 0; $sub <= count($menuJson[$i]['children']) - 1; $sub++) {
                            $tipoMenu2 = substr($menuJson[$i]['children'][$sub]['id'], 0, 3);
                            $codMenu2 = substr($menuJson[$i]['children'][$sub]['id'], 4, 5);
                            $idMenu1 = $menuJson[$i]['children'][$sub]['id'];

                            //sub menu - nivel 2
                            if ($tipoMenu2 == "SUB") {
                                $vl = (array_search($codMenu2, array_column($arrSub, 'cod_sub')));
                                $menuVs = $arrSub[$vl]['nom_sub'];
                                $fim_sub = $arrSub[$vl]['fim_sub'];



                                if ($fim_sub == 'N') {
                                    $tem = 'style="display:block;"';
                                } else {
                                    $tem = 'style="display:none;"';
                                }

                                echo '<ul ' . $tem . ' >';
                                echo '<li><span>' . $menuVs . '</span>';

                                //modulo - nivel 3
                                echo '<ul>';
                                for ($submod = 0; $submod <= count($menuJson[$i]['children'][$sub]['children']) - 1; $submod++) {
                                    $tipoMenu3 = substr($menuJson[$i]['children'][$sub]['children'][$submod]['id'], 0, 3);
                                    $codMenu3 = substr($menuJson[$i]['children'][$sub]['children'][$submod]['id'], 4, 5);
                                    $idMenu3 = $menuJson[$i]['children'][$sub]['children'][$submod]['id'];
                                    if ($tipoMenu3 == "MOD") {
                                        $vl = (array_search($codMenu3, array_column($arrMod, 'cod_mod')));
                                        $menuV = $arrMod[$vl]['nom_mod'];
                                        if (recursive_array_search($codMenu3, $arrayPerfil) !== false) {
                                            echo '<li><a href="action.do?mod=' . fnEncode($codMenu3) . $addLinkEmpresa . '">' . $menuV . '</a></li>';
                                        } else {
                                        }
                                    }
                                }
                                echo '</ul>';

                                echo '</li>';
                                echo '</ul>';
                            }

                            //modulo - nivel 2
                            if ($tipoMenu2 == "MOD") {

                                $vl = (array_search($codMenu2, array_column($arrMod, 'cod_mod')));
                                $menuVs = $arrMod[$vl]['nom_mod'];
                                if (recursive_array_search($codMenu2, $arrayPerfil) !== false) {
                                    echo '<li><a href="action.do?mod=' . fnEncode($codMenu2) . $addLinkEmpresa . '">' . $menuVs . '</a></li>';
                                } else {
                                }
                            }
                        }
                        echo '</ul>';
                        echo '</li>';
                    } else {
                        echo '<li><span>Nível proibido (menu) </span></li>';
                    }
                    //fim da verificação do sub modulo no menu princiapl
                }
            }

            //sub primeiro nivel
            if ($tipoMenu == "SUB") {
                echo '<li><span>Nível proibido (submenu) </span></li>';
            }
            //modulo primeiro nivel
            if ($tipoMenu == "MOD") {
                $vl = (array_search($codMenu, array_column($arrMod, 'cod_mod')));
                $menuV = $arrMod[$vl]['nom_mod'];
                //mod não tem filho - nivel 1
                if (recursive_array_search($codMenu, $arrayPerfil) !== false) {
                    echo '<li><a href="action.do?mod=' . fnEncode($codMenu) . $addLinkEmpresa . '">' . $menuV . '</a></li>';
                }
            }
        }

        if ($_SESSION["SYS_COD_EMPRESA"] == 2) {
        ?>
            <li style="display:block;"><span>Acesso Master</span>
                <ul>
                    <li><a href="action.do?mod=rXujGcTigxw¢">Módulos</a></li>
                    <li><a href="action.do?mod=3p£etHMlnSEw¢">Labs</a></li>
                    <li><a href="action.do?mod=QGH3EIP9q4M¢">Controle de Horas</a></li>
                </ul>
            </li>
        <?php
        }

        ?>
    </ul>
</nav>
<?php if (isset($_GET['notice']) && $_SESSION["SYS_COD_SISTEMA"] != 16 && $_SESSION["SYS_COD_SISTEMA"] != 19 && $_SESSION["SYS_COD_SISTEMA"] != 12 && $_SESSION["SYS_COD_EMPRESA"] != 332) {
?>
    <script>
        $(function() {
            $(window).on('load', function() {
                // $('#popModalNotice .modal-title').text('Aviso');
                // $('#popModalNotice').modal();
                // alert();
            });
        });
    </script>
    <style type="text/css">
        body #popModalNotice .modal-dialog {
            /* Width */
            max-width: 100%;
            width: auto !important;
            margin-left: auto;
            margin-right: auto;
            display: inline-block;
        }
    </style>
    <!-- modal -->
    <div class="modal fade" id="popModalNotice" tabindex='-1'>
        <div class="col-xs-8 col-xs-offset-2 text-center">
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <center><img class="img-responsive" src="https://img.bunker.mk/media/clientes/7/documentos/eNpLSszLSy2KL0pNTi0uzgcAKtIFyg==.png?rand=<?= rand() ?>" height="70%"></center>



                        <div class="push10"></div>
                        <div class="col-xs-8 col-xs-offset-2 text-center">
                        </div>
                        <div class="push5"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
} ?>

<style>
    .barraLogo {
        border: none;
        background: unset;
        margin: 0;
        padding-bottom: 0;
        padding-top: 0;
    }

    @media screen and (max-width: 1024px) {
        .barraLogo {
            margin: 0 20px;
        }
    }
</style>

<div class="outContainer mm-page mm-slideout">
    <?php

    $cod_empresa = fnLimpaCampoZero(fnDecode(@$_GET['id']));

    // fnEscreve($cod_empresa);

    if ($cod_empresa != '') {

        $sqlBanner = "SELECT DES_IMGBACK FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
        $qrImg = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlBanner));
        $banner = $qrImg['DES_IMGBACK'];
    } else {
        $banner = '';
    }
    // && $cod_empresa == 7

    if ($banner != "") {

    ?>

        <div class="containerfluid" style="margin-bottom: 0;">

            <div class="row" style="margin-bottom: 0;">

                <div class="col-md12" style="margin-bottom: 0;">

                    <div class="barraLogo">

                        <div class="push30"></div>
                        <div style="background: url('https://img.bunker.mk/media/clientes/<?= $cod_empresa ?>/logotipo/<?= $banner ?>?rand=<?= rand() ?>') no-repeat; width: 100%; height: 150px; border-radius: 5px;"></div>


                    </div>

                </div>

            </div>

        </div>

    <?php
    }
    ?>
</div>
<?php

$dadosvir = fnscanV($_REQUEST);
if (strpos(@$dadosvir[0], "FOUND") !== false) {
    echo "<br>";
    echo "<br>";
    echo "<center>";
    echo 'Um de seus textos contém conteúdo malicioso! Por favor, revise seus dados.';
    echo "</center>";
    exit();
}
