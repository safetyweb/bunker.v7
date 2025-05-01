<?php

echo fnDebug('false');

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

$fluxos = json_decode($qrFluxo["DES_FLUXO_MODULOS"], true);
$fluxo = $fluxos[$id_node];

$lista_fluxos_seg = [];
foreach ($fluxo['next'] as $f) {
    $lista_fluxos_seg[$f] = $passos[$f];
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
                        <legend>Dados Gerais</legend>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="inputName" class="control-label required">Nome</label>
                                <input type="text" class="form-control input-sm" id="R_NOME" value="<?= $passo["desc"] ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputName" class="control-label required">Tipo de Decisão</label>
                                <select data-placeholder="Selecione" id="R_ORIGEM" class="chosen-select-deselect requiredChk" required="required" onchange="tipo_decisao()">
                                    <option value=''></option>
                                    <option value='url' <?= $passo["origem"] == "url" ? "selected" : "" ?>>Parâmetro de URL</option>
                                    <option value='sql' <?= $passo["origem"] == "sql" ? "selected" : "" ?>>Objeto SQL [não implementado]</option>
                                    <option value='api' <?= $passo["origem"] == "api" ? "selected" : "" ?>>API [não implementado]</option>
                                </select>
                            </div>
                        </div>

                    </fieldset>

                    <div class="push10"></div>

                    <fieldset class="decision-group decision-url">
                        <legend>Parâmetros de URL</legend>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputName" class="control-label required">Parâmetro</label>
                                <input type="text" class="form-control input-sm" id="R_ELEMENTO">
                                <div class="help-block"></div>
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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputName" class="control-label">Valor</label>
                                <input type="text" class="form-control input-sm" id="R_VALOR">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputName" class="control-label">Criptografado?</label><br />
                                <label class="switch switch-small">
                                    <input type="checkbox" id="R_CRIPTO" class="switch" value="S" />
                                    <span></span>
                                </label>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputName" class="control-label required">Passo Seguinte</label>
                                <select data-placeholder="Selecione" id="R_FLUXO" class="chosen-select-deselect requiredChk" required="required">
                                    <option value=''></option>
                                    <?php
                                    foreach ($lista_fluxos_seg as $k => $f) {
                                        echo "<option value='" . $k . "'>";
                                        echo ($f["cod"] <> "" ? $f["cod"] . " - " : "");
                                        echo ($f["desc"] <> "" ? $f["desc"] : "[ Módulo não definido ]");
                                        echo  "</option>";
                                    }
                                    ?>
                                </select>
                                <div class="help-block"></div>
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

                    <div class="col-lg-12 decision-group decision-url">

                        <div class="no-more-tables">

                            As regras são executadas na sequência. Quando uma regra for verdadeira, o sistema para de processar as restantes.
                            <form name="formLista">
                                <table id="R_TABELA" class="table table-bordered table-striped table-hover table-sortable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Regra</th>
                                            <th>Passo Seguinte</th>
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
    var lista_fluxos_seg = <?= json_encode($lista_fluxos_seg) ?>;

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
                    echo "add_regra('" . $item["elemento"] . "', '" . $item["operador"] . "', '" . $item["valor"] . "', '" . $item["cripto"] . "', '" . $item["fluxo"] . "');";
                }
            }
        }
        ?>

        $(".table-sortable tbody").sortable();
        $('.table-sortable tbody').sortable({
            handle: 'span'
        });
        $(".table-sortable tbody").disableSelection();

        tipo_decisao();
    });

    function save() {
        let orig = $("#R_ORIGEM").val();

        let regras = [];
        if (orig == "url") {
            $('.tr_regra').each(function() {
                regras.push(JSON.parse($(this).val()));
            });
        }

        try {
            parent.$('#REGRAS_NOME').val($("#R_NOME").val());
            parent.$('#REGRAS_ORIGEM').val($("#R_ORIGEM").val());
            console.log($("#R_ORIGEM").val());
            parent.$('#REGRAS').val(JSON.stringify(regras));
        } catch (err) {}
        $(this).removeData('bs.modal');
        parent.$('#popModal').modal('hide');
    }

    function add_regra_inputs() {
        let elemento = $("#R_ELEMENTO").val();
        let operador = $("#R_OPERADOR").val();
        let valor = $("#R_VALOR").val();
        let cripto = $("#R_CRIPTO").prop('checked');
        let fluxo = $("#R_FLUXO").val();

        if (elemento == "") {
            msg_erro("Coloque o nome do elemento!");
        } else if (operador == "") {
            msg_erro("Escolha o tipo de operador!");
        } else if (fluxo == "") {
            msg_erro("Escolha o fluxo!");
        } else {
            add_regra(elemento, operador, valor, cripto, fluxo);
            $("#R_ELEMENTO").val("");
            $("#R_VALOR").val("");
            $("#R_FLUXO").val("");
            //save();
        }

    }

    function add_regra(elemento, operador, valor, cripto, fluxo) {
        let html = "";
        html += "<tr>";
        html += `<td align='center'><span class='glyphicon glyphicon-move grabbable'></span></td>`;
        html += "<td>";
        html += `<code>${elemento}</code> &nbsp; ${operador} &nbsp; "${valor}" &nbsp; <i class="${cripto == true || cripto == '1'?'far fa-lock text-warning':'far fa-lock-open text-success'}"></i>`;
        html += "</td>";
        html += "<td>";
        if (lista_fluxos_seg[fluxo]) {
            html += (lista_fluxos_seg[fluxo]["cod"] ? lista_fluxos_seg[fluxo]["cod"] + " - " : "");
            html += (lista_fluxos_seg[fluxo]["desc"] ? lista_fluxos_seg[fluxo]["desc"] : "[ Módulo não definido ]");
        } else {
            html += "<span class='text-danger'>Módulo inexistente! Este módulo parece ter sido removido. Remova-o desta lista.</span>";
        }
        html += "</td>";
        html += `<td class='text-right'>`;
        html += `<a href="javascript:" onclick="del_tinha(this);"><i class="far fa-trash"></i></a>`;
        html += `<textarea style='display:none' class='tr_regra'>`;
        html += JSON.stringify({
            elemento,
            operador,
            valor,
            cripto,
            fluxo
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

    function tipo_decisao() {
        $(".decision-group").hide();
        let val = $("#R_ORIGEM").val();

        $(".decision-" + val).show();
    }
</script>