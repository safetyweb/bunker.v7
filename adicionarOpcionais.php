<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$hashLocal = mt_rand();

$cod_erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_pedido = fnLimpaCampoZero($_REQUEST['COD_PEDIDO']);
        $cod_item_opcional = fnLimpaCampoZero($_REQUEST['COD_ITEM_OPCIONAL']);
        $cod_opcional = fnLimpaCampoZero($_REQUEST['COD_OPCIONAL']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $qtd_opcional = fnLimpaCampoZero($_REQUEST['QTD_OPCIONAL']);
        $tipo_lancamento = fnLimpaCampoZero($_REQUEST['TIPO_LANCAMENTO']);
        $valor = fnLimpaCampoZero(fnValorSql($_REQUEST['VALOR']));
        $valor_unitario = fnLimpaCampoZero(fnValorSql($_REQUEST['VALOR_UNITARIO']));

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
        $cod_usucada = $_SESSION['SYS_COD_USUARIO'];

        $nom_usuarioSESSION = $_SESSION["SYS_NOM_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = $_GET['mod'];
        $COD_MODULO = fndecode($_GET['mod']);

        if ($opcao != '') {

            switch ($opcao) {

                case 'CAD':
                    $sql = "INSERT INTO ADORAI_PEDIDO_OPCIONAIS (
                    COD_PEDIDO,
                    COD_OPCIONAL,
                    TIPO_LANCAMENTO,
                    COD_EMPRESA,
                    QTD_OPCIONAL,
                    VALOR,
                    VALOR_UNITARIO,
                    COD_USUCADA,
                    DAT_CADASTR
                    ) VALUES (
                    $cod_pedido,
                    $cod_opcional,
                    $tipo_lancamento,
                    $cod_empresa,
                    $qtd_opcional,
                    '$valor',
                    '$valor_unitario',
                    $cod_usucada,
                    NOW()
                )";

                    $array = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));


                    if (!$array) {

                        $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                    }

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;

                case 'ALT':

                    $sqlBusca = "SELECT * FROM ADORAI_PEDIDO_OPCIONAIS WHERE COD_ITEM_OPCIONAL = $cod_item_opcional AND COD_PEDIDO = $cod_pedido";
                    $query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
                    $qrResult = mysqli_fetch_assoc($query);

                    $sql = "UPDATE ADORAI_PEDIDO_OPCIONAIS SET
                        COD_OPCIONAL = $cod_opcional,
                        QTD_OPCIONAL = $qtd_opcional,
                        VALOR = '$valor',
                        VALOR_UNITARIO = '$valor_unitario',
                        COD_ALTERAC = $cod_usucada,
                        DAT_ALTERAC = NOW()
                        WHERE COD_ITEM_OPCIONAL = $cod_item_opcional AND COD_PEDIDO = $cod_pedido";

                    $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                    } else {
                        $sqlInsert = "INSERT INTO ADORAI_LOGALTOPCIONAIS(
                            COD_OPCIONAL_NOVO,
                            COD_OPCIONAL_ANTERIOR,
                            QTD_OPCIONAL_NOVO,
                            QTD_OPCIONAL_ANTERIOR,
                            VALOR_UNITARIO_NOVO,
                            VALOR_UNITARIO_ANTERIOR,
                            VALOR_NOVO,
                            VALOR_ANTERIOR,
                            COD_PEDIDO,
                            COD_USUARIO,
                            COD_ITEM_OPCIONAL,
                            DAT_REGISTR
                            )VALUES(
                            $cod_opcional,
                            " . $qrResult['COD_OPCIONAL'] . ",
                            $qtd_opcional,
                            " . $qrResult['QTD_OPCIONAL'] . ",
                            '$valor_unitario',
                            '" . $qrResult['VALOR_UNITARIO'] . "',
                            '$valor',
                            '" . $qrResult['VALOR'] . "',
                            $cod_pedido,
                            $cod_usucada,
                            $cod_item_opcional,
                            NOW()
                            )";
                        mysqli_query(connTemp($cod_empresa, ''), $sqlInsert);
                    }

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;

                case 'EXC':

                    $sql = "UPDATE ADORAI_PEDIDO_OPCIONAIS SET
                        COD_EXCLUSA = $cod_usucada,
                        DAT_EXCLUSA = NOW()
                        WHERE COD_ITEM_OPCIONAL = $cod_item_opcional AND COD_PEDIDO = $cod_pedido";

                    $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));
                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                    }

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
            }

            if ($cod_erro == "") {

                $sqlADPedido = "SELECT VALOR_PEDIDO, DESCONTO_PIX, VAL_CUPOM, COD_CUPOM FROM adorai_pedido WHERE COD_PEDIDO = $cod_pedido";

                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlADPedido);

                if ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                    $val_chale = $qrBusca['VALOR_PEDIDO'];

                    //desconto removido por solicitação da adorai
                    // $desconto_pix = 0;
                    // if (isset($qrBusca['DESCONTO_PIX'])) {
                    // 	$desconto_pix = $qrBusca['DESCONTO_PIX'];
                    // }

                    $val_cupom = 0;
                    if (isset($qrBusca['COD_CUPOM']) && $qrBusca['VAL_CUPOM'] != 0) {
                        $val_cupom = $qrBusca['VAL_CUPOM'];
                    }

                    $sqlOpcionais = "SELECT SUM(VALOR) as VAL_OPCIONAIS FROM adorai_pedido_opcionais ap
                                        INNER JOIN opcionais_adorai AS op ON op.COD_OPCIONAL = ap.COD_OPCIONAL
                                        WHERE cod_pedido = $cod_pedido AND LOG_CORTESIA = 'N'
                                        AND ap.COD_EXCLUSA IS NULL";
                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlOpcionais);

                    $val_opcionais = 0;

                    if ($qrBuscaOpc = mysqli_fetch_assoc($arrayQuery)) {
                        $val_opcionais = $qrBuscaOpc['VAL_OPCIONAIS'];
                    }

                    $val_contrato = ($val_chale + $val_opcionais) - $val_cupom;

                    $sqlLancamentosCred = "SELECT SUM(CX.VAL_CREDITO) AS VAL_CREDITO
                    FROM CAIXA AS CX
                    INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = CX.COD_TIPO
                    WHERE CX.COD_CONTRAT = $cod_pedido
                    AND TC.TIP_OPERACAO = 'C'";

                    $query = mysqli_query(connTemp($cod_empresa, ''), $sqlLancamentosCred);
                    $creditos = 0;
                    if ($qrBuscaLanca = mysqli_fetch_assoc($query)) {
                        $creditos = $qrBuscaLanca['VAL_CREDITO'];
                    }

                    if ($creditos == $val_contrato) {
                        $sqlUpdate = "UPDATE ADORAI_PEDIDO SET 
                        COD_STATUSPAG = 6
                        WHERE COD_PEDIDO = $cod_pedido";
                    } else {
                        $sqlUpdate = "UPDATE ADORAI_PEDIDO SET 
                        COD_STATUSPAG = 5
                        WHERE COD_PEDIDO = $cod_pedido";
                    }

                    // fnEscreve($sqlUpdate);

                    mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);
                }
            }
            $msgTipo = 'alert-success';
        }
    }
}

//busca dados da url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnLimpaCampo(fnDecode($_GET['id']));
    $cod_pedido = fnLimpaCampo(fnDecode($_GET['idp']));
    $tipo_lancamento = fnLimpaCampo($_GET['tpl']);

    $sqlPedido = "SELECT AP.COD_PEDIDO, API.COD_PROPRIEDADE FROM adorai_pedido AS AP
        INNER JOIN adorai_pedido_items AS API ON API.COD_PEDIDO = AP.COD_PEDIDO
        WHERE AP.COD_PEDIDO = $cod_pedido";

    $query = mysqli_query(conntemp($cod_empresa, ''), $sqlPedido);

    if ($qrResult = mysqli_fetch_assoc($query)) {
        $cod_pedido = $qrResult['COD_PEDIDO'];
        $cod_propriedade = $qrResult['COD_PROPRIEDADE'];
    } else {
        $cod_pedido = 0;
        $cod_propriedade = 0;
    }

    $sql = "SELECT EMPRESAS.NOM_FANTASI,CATEGORIA.* FROM $connAdm->DB.EMPRESAS
            left JOIN CATEGORIA ON CATEGORIA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
            where EMPRESAS.COD_EMPRESA = $cod_empresa ";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)) {
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $cod_pedido = 0;
    $tipo_lancamento = 2;
}

?>

<style>
    .table-container td {
        padding: 8px;
    }

    .table-container tbody tr:last-child td {
        border-bottom: 1px solid #dddddd;
    }

    ul.summary-list {
        display: inline-block;
        padding-left: 0;
        width: 100%;
        margin-bottom: 0;
    }

    ul.summary-list>li {
        display: inline-block;
        width: 19.5%;
        text-align: center;
    }

    ul.summary-list>li>a>i {
        display: block;
        font-size: 18px;
        padding-bottom: 5px;
    }

    ul.summary-list>li>a {
        padding: 10px 0;
        display: inline-block;
        color: #818181;
    }

    ul.summary-list>li {
        border-right: 1px solid #eaeaea;
    }

    ul.summary-list>li:last-child {
        border-right: none;
    }
</style>

<?php if ($popUp != "true") {  ?>
    <div class="push30"></div>
<?php } ?>

<div class="row">
    <div class="portlet" style="padding: 0 20px 20px 20px;">
        <div class="push10"></div>

        <div class="portlet-body">
            <?php if ($msgRetorno != '') { ?>
                <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $msgRetorno; ?>
                </div>
            <?php } ?>

            <div class="push30"></div>

            <div class="login-form">

                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
                    <div class="push10"></div>
                    <fieldset>
                        <legend>Informações Gerais</legend>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Código Pedido</label>
                                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PEDIDO" id="COD_PEDIDO" value="<?= $cod_pedido ?>" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Quantidade</label>
                                    <input type="text" class="form-control input-sm" name="QTD_OPCIONAL" id="QTD_OPCIONAL" value="1" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Opcional</label>
                                    <select data-placeholder="Selecione um estado" name="COD_OPCIONAL" id="COD_OPCIONAL" class="chosen-select-deselect" required>
                                        <option value=""></option>
                                        <?php
                                        $sql = "SELECT * FROM opcionais_adorai WHERE COD_PROPRIEDADE = $cod_propriedade AND COD_EXCLUSA IS NULL";
                                        $array = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                        while ($qr = mysqli_fetch_assoc($array)) {
                                        ?>
                                            <option value="<?= $qr['COD_OPCIONAL'] ?>"><?= $qr['ABV_OPCIONAL'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <?php
                            $sql = "SELECT * FROM opcionais_adorai WHERE COD_PROPRIEDADE = $cod_propriedade AND COD_EXCLUSA IS NULL";
                            $array = mysqli_query(connTemp($cod_empresa, ''), $sql);

                            while ($qr = mysqli_fetch_assoc($array)) {
                            ?>
                                <input type="hidden" name="VAL_OPCIONAL_<?= $qr['COD_OPCIONAL'] ?>" id="VAL_OPCIONAL_<?= $qr['COD_OPCIONAL'] ?>" value="<?= $qr['VAL_VALOR'] ?>">
                            <?php } ?>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Valor Unitário</label>
                                    <input type="text" class="form-control input-sm leitura" name="VALOR_UNITARIO" id="VALOR_UNITARIO" value="" readonly="readonly">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Valor Final</label>
                                    <input type="text" class="form-control input-sm leitura" name="VALOR" id="VALOR" value="" readonly="readonly">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>

                    </fieldset>

                    <div class="push10"></div>

                    <div class="push10"></div>
                    <hr>
                    <div class="form-group text-right col-lg-12">
                        <?php if ($tipo_lancamento == 2) { ?>
                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                        <?php } ?>
                        <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                        <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

                    </div>

                    <input type="hidden" name="opcao" id="opcao" value="">
                    <input type="hidden" name="COD_ITEM_OPCIONAL" id="COD_ITEM_OPCIONAL" value="">
                    <input type="hidden" name="TIPO_LANCAMENTO" id="TIPO_LANCAMENTO" value="<?= $tipo_lancamento ?>">
                    <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                    <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                </form>

                <div class="push50"></div>

                <div class="row">

                    <table class="table table-bordered table-hover table-sortable tablesorter">
                        <thead>
                            <th class="{sorter:false}" width="40"></th>
                            <th>Descrição</th>
                            <th class='text-center'>Quantidade</th>
                            <th class='text-right'>Valor Unitário</th>
                            <th class='text-right'>Total do Item</th>
                        </thead>
                        <tbody>
                            <?php

                            $sqlopc = "SELECT 
                        OA.COD_OPCIONAL, 
                        OA.VAL_VALOR,
                        OA.ABV_OPCIONAL,
                        OA.LOG_CORTESIA,
                        ACP.VALOR,
                        ACP.VALOR_UNITARIO,
                        ACP.QTD_OPCIONAL,
                        ACP.DES_OBSERVA,
                        OA.VAL_EFETIVO,
                        ACP.VAL_REFERENCIA_OPCIONAL,
                        ACP.COD_ITEM_OPCIONAL,
                        OA.TIP_CALCULO
                        FROM adorai_pedido_opcionais AS ACP
                        INNER JOIN opcionais_adorai as OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL AND OA.COD_EXCLUSA IS NULL
                        INNER JOIN ADORAI_PEDIDO AS AP ON AP.COD_PEDIDO = ACP.COD_PEDIDO
                        WHERE AP.COD_EMPRESA = 274 AND ACP.TIPO_LANCAMENTO = $tipo_lancamento AND ACP.COD_PEDIDO = $cod_pedido AND ACP.COD_EXCLUSA IS NULL";

                            $queryopc = mysqli_query(connTemp($cod_empresa, ''), $sqlopc);
                            $totalAdicionais = 0;
                            $count = 0;
                            if ($queryopc) {
                                while ($qrBuscaOpcionais = mysqli_fetch_assoc($queryopc)) {
                                    $count++;

                                    if ($qrBuscaOpcionais['LOG_CORTESIA'] == "S") {
                                        $val_opcionalFinal = "<span style='text-decoration: line-through'>" . fnValor($qrBuscaOpcionais['VALOR'], 2) . "</span>";
                                    } else {
                                        $val_opcionalFinal = fnValor($qrBuscaOpcionais['VALOR'], 2);
                                        $totalAdicionais += $qrBuscaOpcionais['VALOR'];
                                    }

                                    if ($qrBuscaOpcionais['VAL_REFERENCIA_OPCIONAL'] != "" && $qrBuscaOpcionais['VAL_REFERENCIA_OPCIONAL'] != 0) {
                                        $val_efetivo = $qrBuscaOpcionais['VAL_REFERENCIA_OPCIONAL'];
                                    } else {
                                        $val_efetivo = $qrBuscaOpcionais['VAL_EFETIVO'];
                                    }

                                    echo "
                                <tr>
                                <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
                                <td>" . $qrBuscaOpcionais['ABV_OPCIONAL'] . "</td>
                                <td class='text-center'>" . $qrBuscaOpcionais['QTD_OPCIONAL'] . "</td>
                                <td class='text-right'>R$ " . fnValor($qrBuscaOpcionais['VAL_VALOR'], 2) . "</td>
                                <td class='text-right'>R$ " . $val_opcionalFinal . "</td>
                                </tr>
                                <input type='hidden' id='ret_COD_ITEM_OPCIONAL_" . $count . "' value='" . $qrBuscaOpcionais['COD_ITEM_OPCIONAL'] . "'>
                                <input type='hidden' id='ret_QTD_OPCIONAL_" . $count . "' value='" . $qrBuscaOpcionais['QTD_OPCIONAL'] . "'>
                                <input type='hidden' id='ret_VALOR_" . $count . "' value='" . $qrBuscaOpcionais['VALOR'] . "'>
                                <input type='hidden' id='ret_VALOR_UNITARIO_" . $count . "' value='" . $qrBuscaOpcionais['VALOR_UNITARIO'] . "'>
                                <input type='hidden' id='ret_COD_OPCIONAL_" . $count . "' value='" . $qrBuscaOpcionais['COD_OPCIONAL'] . "'>
                                ";
                                }
                            }
                            ?>
                        </tbody>

                        <tfooter>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class='text-right'><b>Total</b></td>
                                <td class='text-right'><b>R$ <?= fnValor($totalAdicionais, 2) ?></b></td>
                            </tr>
                        </tfooter>
                    </table>
                </div>

            </div>


        </div>

    </div>

</div>

<div class="push20"></div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#COD_OPCIONAL').change(function() {
            let codOpc = $(this).val();
            let qtd = $('#QTD_OPCIONAL').val();
            if (codOpc != "" && qtd > 0) {
                calculaTotal(codOpc);
            } else {
                $('#VALOR_UNITARIO').val('');
                $('#VALOR').val('');
            }
        });

        $('#QTD_OPCIONAL').blur(function() {
            let codOpc = $('#COD_OPCIONAL').val();
            let qtd = $(this).val();
            if (codOpc != "" && qtd > 0) {
                calculaTotal(codOpc);
            } else {
                $('#VALOR_UNITARIO').val('');
                $('#VALOR').val('');
            }
        })

    });

    function calculaTotal(cod) {
        let codOpc = cod;
        let valUnit = $('#VAL_OPCIONAL_' + codOpc).val();
        let qtd = $('#QTD_OPCIONAL').val();
        let valFinal = valUnit * qtd;

        $('#VALOR_UNITARIO').val(converterValorTela(valUnit, 2));
        $('#VALOR').val(converterValorTela(valFinal, 2));
    }

    function retornaForm(index) {
        $("#formulario #COD_ITEM_OPCIONAL").val($("#ret_COD_ITEM_OPCIONAL_" + index).val());
        $("#formulario #QTD_OPCIONAL").val($("#ret_QTD_OPCIONAL_" + index).val());
        $("#formulario #VALOR").val($("#ret_VALOR_" + index).val());
        $("#formulario #VALOR_UNITARIO").val($("#ret_VALOR_UNITARIO_" + index).val());
        $("#formulario #COD_OPCIONAL").val($("#ret_COD_OPCIONAL_" + index).val()).trigger("chosen:updated");
        let codOpc = $("#ret_COD_OPCIONAL_" + index).val();
        calculaTotal(codOpc);

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>