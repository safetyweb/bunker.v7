<?php
//inicialização
$abaAutom2091 = "";
$abaAutom2092 = "";
$abaAutom2093 = "";
$abaAutom2096 = "";
$abaAutom2102 = "";
$btnDesativa = "";
$btnProximo = "";
$btnDesativa = "<a href='#' disabled class='btn btn-primary next next1' name='next'>Próximo&nbsp;&nbsp;<i class='fas fa-arrow-right'></i></a>";
switch ($abaAtivo) {
    case 2091: //lista tour
        $abaAutom2091 = "active";
        $btnProximo = "<a href='action.do?mod=" . fnEncode(2092) . "&id=" . fnEncode($cod_empresa) . "' class='btn btn-primary next next1' name='next'>Próximo&nbsp;&nbsp;<i class='fas fa-arrow-right'></i></a>";
        break;
    case 2092: //detalhe/tela
        $abaAutom2092 = "active";
        $btnProximo = "<a href='action.do?mod=" . fnEncode(2093) . "&id=" . fnEncode($cod_empresa) . "' class='btn btn-primary next next2' name='next'>Próximo&nbsp;&nbsp;<i class='fas fa-arrow-right'></i></a>";
        break;
    case 2093: //help center
        $abaAutom2093 = "active";
        $btnProximo = "<a href='action.do?mod=" . fnEncode(2096) . "&id=" . fnEncode($cod_empresa) . "' class='btn btn-primary next next3' name='next'>Próximo&nbsp;&nbsp;<i class='fas fa-arrow-right'></i></a>";
        break;
    case 2096: //help center
        $abaAutom2096 = "active";
        $btnProximo = "<a href='action.do?mod=" . fnEncode(2102) . "&id=" . fnEncode($cod_empresa) . "' class='btn btn-primary next next4' name='next'>Próximo&nbsp;&nbsp;<i class='fas fa-arrow-right'></i></a>";
        break;
    case 2102: //help center
        $abaAutom2102 = "active";
        $btnProximo = "<a href='action.do?mod=" . fnEncode(1020) . "&id=" . fnEncode($cod_empresa) . "' class='btn btn-info' >Concluir</a>";
        break;
}

?>


<div class="col-xs-2" style="padding-left: 0;"> <!-- required for floating -->

    <?php
    $sqlAudit = "SELECT * FROM
                AUDITORIA_EMPRESA
                WHERE COD_EMPRESA = $cod_empresa";

    $queryAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

    $passoUm = "fal fa-clock";
    $passoDois = "fal fa-clock";
    $passoTres = "fal fa-clock";
    $passoQuatro = "fal fa-clock";
    $passoCinco = "fal fa-clock";
    $bgPassoUm = "bg-warning";
    $bgPassoDois = "bg-warning";
    $bgPassoTres = "bg-warning";
    $bgPassoQuatro = "bg-warning";
    $bgPassoCinco = "bg-warning";
    $actionPassoDois = "#";
    $actionPassoTres = "#";
    $actionPassoQuatro = "#";
    $actionPassoCinco = "#";

    if ($resultAudit = mysqli_fetch_assoc($queryAudit)) {
        if ($resultAudit['FASE1'] == 'S') {
            $passoUm = "fal fa-check";
            $bgPassoUm = "bg-success";
            $actionPassoDois = "action.do?mod=" . fnEncode(2092) . "&id=" . fnEncode($cod_empresa);
        } else {
            if ($abaAutom2091 != "") {
                $btnProximo = $btnDesativa;
            }
        }

        if ($resultAudit['FASE2'] == 'S') {
            $passoDois = "fal fa-check";
            $bgPassoDois = "bg-success";
            $actionPassoTres = "action.do?mod=" . fnEncode(2093) . "&id=" . fnEncode($cod_empresa);
            $actionPassoQuatro = "action.do?mod=" . fnEncode(2096) . "&id=" . fnEncode($cod_empresa);
            $actionPassoCinco = "action.do?mod=" . fnEncode(2102) . "&id=" . fnEncode($cod_empresa);
        } else {
            if ($abaAutom2092 != "") {
                $btnProximo = $btnDesativa;
            }
        }

        if ($resultAudit['FASE3'] == 'S') {
            $passoTres = "fal fa-check";
            $bgPassoTres = "bg-success";
        } else {
            if ($abaAutom2093 != "") {
                $btnProximo = $btnDesativa;
            }
        }

        if ($resultAudit['FASE4'] == 'S') {
            $passoQuatro = "fal fa-check";
            $bgPassoQuatro = "bg-success";
        } else {
            if ($abaAutom2096 != "") {
                $btnProximo = $btnDesativa;
            }
        }

        if ($resultAudit['FASE5'] == 'S') {
            $passoCinco = "fal fa-check";
            $bgPassoCinco = "bg-success";
        } else {
            if ($abaAutom2102 != "") {
                $btnProximo = $btnDesativa;
            }
        }
    } else {
        $btnProximo = $btnDesativa;
    }
    ?>
    <!-- Nav tabs -->
    <ul class="vTab nav nav-tabs tabs-left text-center">

        <li class="<?= $abaAutom2091 ?> vTab">
            <a href="action.do?mod=<?= fnEncode(2091) ?>&id=<?= fnEncode($cod_empresa) ?>">

                <div class="notify-badge text-center <?= $bgPassoUm ?>" id="notificaPasso1" style><span class="<?= $passoUm ?>"></span></div>

                <i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
                <h5 class="hidden-xs" style="margin: 3px 0 0 0">Empresa e Usuários</h5>
            </a>
        </li>

        <li class="<?= $abaAutom2092 ?> vTab">
            <a href="<?= $actionPassoDois ?>">

                <div class="notify-badge text-center <?= $bgPassoDois ?>" id="notificaPasso2"><span class="<?= $passoDois ?>"></span></div>

                <i class="fal fa-database fa-2x" style="margin: 10px 0 2px 0"></i>
                <h5 class="hidden-xs" style="margin: 3px 0 0 0">Database</h5>
            </a>
        </li>

        <li class="<?= $abaAutom2093 ?> vTab">
            <a href="<?= $actionPassoTres ?>">

                <div class="notify-badge text-center <?= $bgPassoTres ?>" id="notificaPasso3"><span class="<?= $passoTres ?>"></span></div>

                <i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
                <h5 class="hidden-xs" style="margin: 3px 0 0 0">Clientes e Hotsite</h5>
            </a>
        </li>

        <li class="<?= $abaAutom2096 ?> vTab">
            <a href="<?= $actionPassoQuatro ?>">

                <div class="notify-badge text-center <?= $bgPassoQuatro ?>" id="notificaPasso4"><span class="<?= $passoQuatro ?>"></span></div>

                <i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
                <h5 class="hidden-xs" style="margin: 3px 0 0 0">Campanhas e Comunicação</h5>
            </a>
        </li>

        <li class="<?= $abaAutom2102 ?> vTab">
            <a href="<?= $actionPassoCinco ?>">

                <div class="notify-badge text-center <?= $bgPassoCinco ?>" id="notificaPasso5"><span class="<?= $passoCinco ?>"></span></div>
                <i class="fal fa-key fa-2x" style="margin: 10px 0 2px 0"></i>
                <h5 class="hidden-xs" style="margin: 3px 0 0 0">Dados de Login</h5>
            </a>
        </li>

    </ul>
</div>