<?php

//echo fnDebug('false');

$hashLocal = mt_rand();

$cod_empresa = fnDecode($_GET['id']);
$cod_fluxo = fnLimpacampoZero(fnDecode($_GET['idFluxo']));
$id_node = $_GET['idNode'];

$sql = "SELECT * FROM FLUXO_DADOS WHERE COD_FLUXO=0$cod_fluxo";
$arrayQuery = mysqli_query(conntemp($cod_empresa, ""), $sql) or die(mysqli_error(conntemp($cod_empresa, "")));
$qrFluxo = mysqli_fetch_assoc($arrayQuery);

$passos = json_decode($qrFluxo["DES_ITENS"], true);
$passo = $passos[$id_node];

if (!isset($passo)) {
?>
    <div class="alert alert-danger alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        Nenhum módulo definido! Defina um módulo primeiro, antes de cadastrar as regras.
    </div>
<?php
    exit;
}
//echo "FLUXO: " . $cod_fluxo;
//echo "Nó: " . $id_node;
?>

<div class="row">
    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

                    <div id="msgErro"></div>

                    <fieldset>
                        <legend>Validação de Elementos</legend>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputName" class="control-label required">Elemento</label>
                                <input type="text" class="form-control input-sm" id="R_ELEMENTO">
                                <div class="help-block">Exemplo: "#elemento", ".elemento", "name[elemento]"</div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputName" class="control-label required">Operador</label>
                                <select data-placeholder="Selecione" id="R_OPERADOR" class="chosen-select-deselect requiredChk" required="required">
                                    <option value='='>=</option>
                                    <option value='!='>!=</option>
                                    <option value='>'>&gt;</option>
                                    <option value='<'>&lt;</option>
                                    <option value='>='>&gt;=</option>
                                    <option value='<='>&lt;=</option>
                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputName" class="control-label">Valor</label>
                                <input type="text" class="form-control input-sm" id="R_VALOR">
                                <div class="help-block with-errors">Condição para o próximo passo</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputName" class="control-label required">Mensagem</label>
                                <input type="text" class="form-control input-sm" id="R_MSG">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                    </fieldset>

                    <div class="push10"></div>
                    <hr>
                    <div class="form-group text-right col-lg-12">
                        <button class="btn btn-primary getBtn" onclick="add_regra_inputs()"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Adicionar</button>
                    </div>

                    <div class="push"></div>
                    <div class="push50"></div>

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <form name="formLista">

                                <table id="R_TABELA" class="table table-bordered table-striped table-hover table-sortable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Regra</th>
                                            <th>Mensagem</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </form>

                        </div>

                    </div>

                </div>

                <div class="push10"></div>
                <div class="form-group text-right col-lg-12">
                    <button class="btn btn-primary getBtn" onclick="save()"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Salvar</button>
                </div>
            </div>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php
        if (isset($passo["regras"])) {
            if (is_array($passo["regras"])) {
                foreach ($passo["regras"] as $item) {
                    foreach ($item as $key => $valor) {
                        $item[$key] = str_replace("'", "&apos;", $item[$key]);
                        $item[$key] = str_replace("\"", "&quot;", $item[$key]);
                        $item[$key] = str_replace(">", "&gt;", $item[$key]);
                        $item[$key] = str_replace("<", "&lt;", $item[$key]);
                    }
                    echo "add_regra('" . $item["elemento"] . "', '" . $item["operador"] . "', '" . $item["valor"] . "', '" . $item["msg"] . "');";
                }
            }
        }
        ?>

        $(".table-sortable tbody").sortable();
        $('.table-sortable tbody').sortable({
            handle: 'span'
        });
        $(".table-sortable tbody").disableSelection();

    });

    function save() {
        let regras = [];
        $('.tr_regra').each(function() {
            regras.push(JSON.parse($(this).val()));
        });

        try {
            parent.$('#REGRAS').val(JSON.stringify(regras));
        } catch (err) {}
        $(this).removeData('bs.modal');
        parent.$('#popModal').modal('hide');
    }

    function add_regra_inputs() {
        let elemento = $("#R_ELEMENTO").val();
        let operador = $("#R_OPERADOR").val();
        let valor = $("#R_VALOR").val();
        let msg = $("#R_MSG").val();

        if (elemento == "") {
            msg_erro("Coloque o nome do elemento!");
        } else if (operador == "") {
            msg_erro("Escolha o tipo de operador!");
        } else if (msg == "") {
            msg_erro("Coloque a mensagem!");
        } else {
            add_regra(elemento, operador, valor, msg);
            $("#R_ELEMENTO").val("");
            $("#R_VALOR").val("");
            $("#R_MSG").val("");
            //save();
        }

    }

    function add_regra(elemento, operador, valor, msg) {
        let html = "";
        html += "<tr>";
        html += `<td align='center'><span class='glyphicon glyphicon-move grabbable'></span></td>`;
        html += `<td><code>${elemento}</code> &nbsp; ${operador} &nbsp; "${valor}"</td>`;
        html += `<td>${msg}</td>`;
        html += `<td class='text-right'>`;
        html += `<a href="javascript:" onclick="del_tinha(this);"><i class="far fa-trash"></i></a>`;
        html += `<textarea style='display:none' class='tr_regra'>`;
        html += JSON.stringify({
            elemento,
            operador,
            valor,
            msg
        });
        html += `</textarea>`;
        html += `</td>`;
        html += "</tr>";
        $("#R_TABELA tbody").append(html);
    }

    function del_tinha(tr) {
        var linha = tr.parentNode.parentNode;
        linha.parentNode.removeChild(linha);
        //save();
    }

    function msg_erro(msg) {
        $("#msgErro").html(`<div class="alert alert-danger alert-dismissible top30 bottom30" role="alert" id="msgRetorno">` +
            `<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>` +
            msg +
            `</div>`);
    }
</script>