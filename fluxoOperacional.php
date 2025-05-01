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
                                        <th>Descrição</th>
                                        <th>Módulo Inicial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM FLUXO_DADOS F"
                                        . " INNER JOIN webtools.MODULOS M ON M.COD_MODULOS = F.COD_MODULOS"
                                        . " WHERE F.COD_EMPRESA = 0$cod_empresa"
                                        . " ORDER BY F.DES_FLUXO";
                                    $arrayQuery = mysqli_query($conn, $sql) or die(mysqli_error($conn));

                                    //$count = 0;
                                    while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                        $count = $qrBusca['COD_FLUXO'];
                                        echo ""
                                            . "<tr>"
                                            . "<td class='text-center'>"
                                            . "<input type='radio' name='radio1' onclick='abreFluxo(" . $count  . ")'>"
                                            . "</td>"
                                            . "<td style='text-align:right'>" . $qrBusca['COD_FLUXO'] . "</td>"
                                            . "<td>" . $qrBusca['DES_FLUXO'] . "</td>"
                                            . "<td>" . $qrBusca['COD_MODULOS'] . " - " . $qrBusca['NOM_MODULOS'] . "</td>"
                                            . "</tr>"
                                            . "<input type='hidden' id='ret_COD_FLUXO_" . $count . "' value='" . fnEncode($qrBusca['COD_FLUXO']) . "'>"
                                            . "<input type='hidden' id='ret_DES_FLUXO_" . $count . "' value='" . $qrBusca['DES_FLUXO'] . "'>"
                                            . "<input type='hidden' id='ret_COD_MODULOS_" . $count . "' value='" . fnEncode($qrBusca['COD_MODULOS']) . "'>"
                                            . "<input type='hidden' id='ret_COD_NODE_" . $count . "' value='" . $qrBusca['COD_NODE'] . "'>"
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
    function abreFluxo(index) {

        let COD_FLUXO = $("#ret_COD_FLUXO_" + index).val();
        let url = `/action.do?mod=<?= fnEncode(1985) ?>&id=<?= $_GET["id"] ?>&novo_fluxo=${COD_FLUXO}`;

        window.open(url, "_self");
    }
</script>