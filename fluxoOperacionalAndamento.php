<?php

echo fnDebug('true');

$hashLocal = mt_rand();


//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

$conn = conntemp($cod_empresa, "");


?>

<div class="push30"></div>

<div class="row">
    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg; ?>
                </div>
            </div>

            <div class="portlet-body">

                <div class="login-form">

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50"></th>
                                        <th>Código</th>
                                        <th>Data Abertura</th>
                                        <th>Cliente</th>
                                        <th>Valor Proposta</th>
                                        <th>Tipo do Fluxo</th>
                                        <th>Atividade Atual</th>
                                        <th>Usuário Etapa</th>
                                        <th>Última Interação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT FO.*,F.DES_FLUXO,M.NOM_MODULOS,U.NOM_USUARIO,C.NOM_CLIENTE,FO.NUM_CONTRATO FROM FLUXO_DADOS F"
                                        . " INNER JOIN FLUXO_OPERACIONAL FO ON FO.COD_FLUXO = F.COD_FLUXO"
                                        . " LEFT JOIN webtools.MODULOS M ON M.COD_MODULOS = FO.COD_MODULOS"
                                        . " LEFT JOIN CLIENTES C ON C.COD_CLIENTE = FO.COD_CLIENTE"
                                        . " LEFT JOIN USUARIOS U ON U.COD_USUARIO = FO.COD_USUCADA"
                                        . " WHERE F.COD_EMPRESA = 0$cod_empresa"
                                        . " ORDER BY FO.COD_FLUXO_OPER DESC";
                                    $arrayQuery = mysqli_query($conn, $sql) or die(mysqli_error($conn));

                                    //$count = 0;
                                    while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                        $count = $qrBusca['COD_FLUXO_OPER'];

                                        $params = array_merge(
                                            ['mod' => fnEncode($qrBusca["COD_MODULOS"]), 'id' => fnEncode($qrBusca["COD_EMPRESA"]), 'fluxo' => fnEncode($qrBusca["COD_FLUXO_OPER"])],
                                            json_decode($qrBusca["PARAMS"], true)
                                        );
                                        $url = "/action.do?" . http_build_query($params);

                                        echo ""
                                            . "<tr>"
                                            . "<td class='text-center'>"
                                            . "<input type='radio' name='radio1' onclick='continunaFluxo(" . $count  . ")'>"
                                            . "</td>"
                                            . "<td>" . $qrBusca['COD_FLUXO_OPER'] . "</td>"
                                            . "<td>" . fnDataFull($qrBusca['DAT_CADASTR']) . "</td>"
                                            . "<td>" . $qrBusca['NOM_CLIENTE'] . "</td>"
                                            . "<td>" . $qrBusca['NUM_CONTRATO'] . "</td>"
                                            . "<td>" . $qrBusca['COD_FLUXO'] . " - " . $qrBusca['DES_FLUXO'] . "</td>"
                                            . "<td>" . $qrBusca['COD_MODULOS'] . " - " . $qrBusca['NOM_MODULOS'] . "</td>"
                                            . "<td>" . $qrBusca['NOM_USUARIO'] . "</td>"
                                            . "<td>" . fnDataFull($qrBusca['DAT_ALTERAC']) . "</td>"
                                            . "</tr>"
                                            . "<input type='hidden' id='ret_COD_FLUXO_" . $count . "' value='" . fnEncode($qrBusca['COD_FLUXO']) . "'>"
                                            . "<input type='hidden' id='ret_COD_MODULOS_" . $count . "' value='" . fnEncode($qrBusca['COD_MODULOS']) . "'>"
                                            . "<input type='hidden' id='ret_COD_NODE_" . $count . "' value='" . $qrBusca['COD_NODE'] . "'>"
                                            . "<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($qrBusca['COD_EMPRESA']) . "'>"
                                            . "<input type='hidden' id='ret_URL_" . $count . "' value='" . $url . "'>"
                                            . "";
                                    }
                                    ?>

                                </tbody>
                            </table>

                        </div>

                    </div>

                    <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<div class="push20"></div>


<script type="text/javascript">
    function continunaFluxo(index) {

        let url = $("#ret_URL_" + index).val();
        window.open(url, "_self");
    }
</script>