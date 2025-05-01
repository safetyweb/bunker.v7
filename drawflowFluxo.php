<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();


$cod_fluxo = fnLimpacampoZero(fnDecode($_GET['idFluxo']));
$sql = "SELECT * FROM FLUXO_DADOS WHERE COD_FLUXO=0$cod_fluxo";
$arrayQuery = mysqli_query(conntemp($cod_empresa, ""), $sql) or die(mysqli_error(conntemp($cod_empresa, "")));
if (mysqli_num_rows($arrayQuery) <= 0) {
    echo "<meta http-equiv='refresh' content='0;url=?mod=" . fnEncode(1935) . "'>";
    exit;
}
$qrFluxo = mysqli_fetch_assoc($arrayQuery);

$des_fluxo = $qrFluxo["DES_FLUXO"];
$jsn_fluxo_export = $qrFluxo["JSN_FLUXO_EXPORT"];
$des_itens = $qrFluxo["DES_ITENS"];
?>

<style>
    .bg {
        background-size: 25px 25px;
        background-image: linear-gradient(to right, #f1f1f1 1px, transparent 1px), linear-gradient(to bottom, #f1f1f1 1px, transparent 1px);
    }

    .drag-drawflow {
        line-height: 50px;
        border: 1px solid #CCC;
        padding-left: 20px;
        cursor: move;
        user-select: none;
        margin-bottom: 5px;
        border-radius: 8px;
        width: 100%;
    }

    .drawflow .drawflow-node {
        width: 180px !important;
    }

    .drawflow .drawflow-node .input,
    .drawflow .drawflow-node .output {
        background: #EEE !important;
    }

    .drawflow .drawflow-node.modulo {
        background: #FFF !important;
    }

    .drawflow .drawflow-node.decision {
        background: #ffea84 !important;
        width: 200px !important;
    }

    .drawflow .drawflow-node.comunicacao {
        background: #a2b6ff !important;
    }

    .drawflow .box {
        font-weight: bold !important;
        font-size: 13px !important;
        padding-top: 8px !important;
    }
</style>

<div class="push30"></div>

<div class="row">
    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary">
                        <?php echo $NomePg; ?> / <?= $des_fluxo ?>
                    </span>
                </div>
            </div>

            <?php
            $abaBens = 1932;
            include "abasDataFlow.php";
            ?>
            <div class="push10"></div>
            <div class="push10"></div>

            <div class="portlet-body">

                <?php if ($msgRetorno != '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <div class="row" style="display: flex;height: calc(100vh - 230px);">
                    <div class="col-md-2">
                        <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="modulo">
                            <i class="fal fa-terminal"></i>
                            <span>&nbsp;Módulo</span>
                        </div>
                        <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="decision">
                            <i class="fas fa-cogs"></i>
                            <span>&nbsp;Data Driven Decision</span>
                        </div>
                        <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="comunicacao">
                            <i class="fas fa-rss"></i>
                            <span>&nbsp;Comunicação</span>
                        </div>

                    </div>

                    <div class="col-md-10">

                        <div class="row">
                            <div class="text-left col-sm-4" style="height: 35px;display: flex;align-items: center;">
                                <small id="saving-text" style="display:none;">Salvando... &nbsp;&nbsp;</small>
                            </div>
                            <div class="text-right col-sm-8">
                                <a class="btn btn-sm btn-primary" href="javascript:" onclick="limpar()">Limpar</a>
                                <span>&nbsp;</span>
                                <a class="btn btn-sm btn-primary" href="javascript:" onclick="editor.zoom_out()"><i class="fas fa-search-minus"></i>&nbsp;</a>
                                <a class="btn btn-sm btn-primary" href="javascript:" onclick="editor.zoom_reset()"><i class="fas fa-search"></i>&nbsp;</a>
                                <a class="btn btn-sm btn-primary" href="javascript:" onclick="editor.zoom_in()"><i class="fas fa-search-plus"></i>&nbsp;</a>
                                <span>&nbsp;</span>
                                <a id="lock" class="btn btn-sm btn-primary" href="javascript:" onclick="changeMode();"><i class="fas fa-lock"></i>&nbsp;</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="margin-top:5px;">

                                <div id="drawflow" class="bg" ondrop="drop(event)" ondragover="allowDrop(event)" style="height: 100%">
                                </div>

                            </div>
                        </div>
                    </div>


                </div>


            </div>
            <!-- fim Portlet -->
        </div>

    </div>

    <div class="push20"></div>



    <div style="display:none">
        <a type="button" name="btnBuscaModulo" id="btnBuscaModulo" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1477) ?>&id=<?php echo fnEncode($cod_modulos) ?>&pop=true" data-title="Busca Módulo"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
        <a type="button" name="btnRegrasModulo" id="btnRegrasModulo" style="height:35px;" class="btn btn-primary btn-sm addBox" data-title="Regras do Módulo"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
        <a type="button" name="btnDataDriven" id="btnDataDriven" style="height:35px;" class="btn btn-primary btn-sm addBox" data-title="Data Driven Decision"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
        <input type="text" name="NOM_MODULOS" id="NOM_MODULOS" value="" maxlength="50" class="form-control input-sm" readonly style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
        <input type="text" name="COD_MODULOS" id="COD_MODULOS" value="">
        <input type="text" name="REGRAS" id="REGRAS" value="">
        <input type="text" name="REGRAS_NOME" id="REGRAS_NOME" value="">
        <input type="text" name="REGRAS_ORIGEM" id="REGRAS_ORIGEM" value="">

        <a type="button" name="btnBuscaDataDriven" id="btnBuscaDataDriven" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1934) ?>&pop=true" data-title="Busca Data Driven Decision"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
        <input type="text" name="DES_FLUXO" id="DES_FLUXO" value="" maxlength="50" class="form-control input-sm" readonly style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
        <input type="text" name="COD_DATA_DRIVEN" id="COD_DATA_DRIVEN" value="">
    </div>

    <!-- modal -->
    <div class="modal fade" id="popModal" tabindex='-1'>
        <div class="modal-dialog" style="">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" id="mymodal" class="btn btn-default" data-dismiss="modal">Close</button>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <form role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>" onsubmit="return exportDrawFlow();">
        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_FLUXO" id="COD_FLUXO" value="<?= $cod_fluxo ?>">
        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_FLUXO_ENCODE" id="COD_FLUXO_ENCODE" value="<?= $_GET['idFluxo'] ?>">

        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="JSN_FLUXO_EXPORT" id="JSN_FLUXO_EXPORT" value="">
        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="DES_ITENS" id="DES_ITENS" value="">
        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="DES_FLUXO_MODULOS" id="DES_FLUXO_MODULOS" value="">

        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_MODULOS" id="COD_MODULOS" value="">
        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_NODE" id="COD_NODE" value="">

        <input type="hidden" name="opcao" id="opcao" value="">
        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

        <button style="display:none;" type="submit" name="GRV" id="GRV" class="btn btn-sm btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Gravar</button>
    </form>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.css">
    <script src="https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.js"></script>

    <script type="text/javascript">
        var editor;
        $(document).ready(function() {
            var id = document.getElementById("drawflow");
            editor = new Drawflow(id);
            editor.reroute = true;
            const dataToImport = {
                "drawflow": {
                    "Home": <?= trim(@$jsn_fluxo_export) != "" ? $jsn_fluxo_export : "{ data: {} }" ?>
                }
            }

            Object.keys(dataToImport.drawflow.Home.data).map(function(id) {
                let html = dataToImport.drawflow.Home.data[id].html;
                let div = $("<div>").html(html);
                let node_id = div.children().first().attr("id");
                let node_type = div.children().first().data("type");
                dataToImport.drawflow.Home.data[id].html = module(node_type ?? "home", node_id);
            });

            editor.start();
            editor.import(dataToImport);

            let itens = <?= (trim($des_itens) != "" ? $des_itens : "{}") ?>;
            Object.keys(itens).map(item => {
                if (itens[item]["type"] == "modulo" || itens[item]["type"] == "home" || itens[item]["type"] == undefined) {
                    $.ajax({
                        url: "ajxGetModule.php?id=" + itens[item]["cod"],
                        type: "GET",
                        success: function(response) {
                            console.log(response);
                            setInfo(item, itens[item]["cod"], response["NOM_MODULOS"], itens[item]["regras"], itens[item]["origem"]);
                            exportDrawFlow();
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr, status, error);
                        }
                    });
                }
                setInfo(item, itens[item]["cod"], itens[item]["desc"], itens[item]["regras"], itens[item]["origem"]);
            });

            editor.on('nodeCreated', function(id) {
                console.log("Node created " + id);
                exportDrawFlow();
            })

            editor.on('nodeRemoved', function(id) {
                console.log("Node removed " + id);
                exportDrawFlow();
            })

            editor.on('nodeSelected', function(id) {
                console.log("Node selected " + id);
            })

            editor.on('moduleCreated', function(name) {
                console.log("Module Created " + name);
                exportDrawFlow();
            })

            editor.on('moduleChanged', function(name) {
                console.log("Module Changed " + name);
                exportDrawFlow();
            })

            editor.on('connectionCreated', function(connection) {
                console.log('Connection created');
                console.log(connection);
                exportDrawFlow();
            })

            editor.on('connectionRemoved', function(connection) {
                console.log('Connection removed');
                console.log(connection);
                exportDrawFlow();
            })

            editor.on('mouseMove', function(position) {
                //console.log('Position mouse x:' + position.x + ' y:' + position.y);
            })

            editor.on('nodeMoved', function(id) {
                console.log("Node moved " + id);
                exportDrawFlow();
            })

            editor.on('zoom', function(zoom) {
                console.log('Zoom level ' + zoom);
            })

            editor.on('translate', function(position) {
                console.log('Translate x:' + position.x + ' y:' + position.y);
            })

            editor.on('addReroute', function(id) {
                console.log("Reroute added " + id);
                exportDrawFlow();
            })

            editor.on('removeReroute', function(id) {
                console.log("Reroute removed " + id);
                exportDrawFlow();
            })

            /* DRAG EVENT */

            /* Mouse and Touch Actions */

            var elements = document.getElementsByClassName('drag-drawflow');
            for (var i = 0; i < elements.length; i++) {
                elements[i].addEventListener('touchend', drop, false);
                elements[i].addEventListener('touchmove', positionMobile, false);
                elements[i].addEventListener('touchstart', drag, false);
            }

            addNodeHome();


        });


        var mobile_item_selec = '';
        var mobile_last_move = null;

        function positionMobile(ev) {
            mobile_last_move = ev;
        }

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            if (ev.type === "touchstart") {
                mobile_item_selec = ev.target.closest(".drag-drawflow").getAttribute('data-node');
            } else {
                ev.dataTransfer.setData("node", ev.target.getAttribute('data-node'));
            }
        }

        function drop(ev) {
            if (ev.type === "touchend") {
                var parentdrawflow = document.elementFromPoint(mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY).closest("#drawflow");
                if (parentdrawflow != null) {
                    addNodeToDrawFlow(mobile_item_selec, mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY);
                }
                mobile_item_selec = '';
            } else {
                ev.preventDefault();
                var data = ev.dataTransfer.getData("node");
                addNodeToDrawFlow(data, ev.clientX, ev.clientY);
            }
            exportDrawFlow();
        }

        function addNodeToDrawFlow(name, pos_x, pos_y) {
            if (editor.editor_mode === 'fixed') {
                return false;
            }
            pos_x = pos_x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)) - (editor.precanvas.getBoundingClientRect().x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)));
            pos_y = pos_y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)) - (editor.precanvas.getBoundingClientRect().y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)));

            let id = 'el_' + Math.random().toString(36).substr(2, 9);

            let mod = module(name, id);
            editor.addNode(name, (name != 'home' ? 1 : 0), 1, pos_x, pos_y, name, {}, mod);
            if (name == 'modulo') {
                buscaModulo(id);
            }

        }

        function module(name, id) {
            let mod = '';
            switch (name) {
                case 'home':
                    mod = `<div id="${id}" data-type="home" class="datamodulo home" ondblclick="buscaModulo('${id}')">` +
                        '<div style="float: right;position: absolute;top: 4px;right: 6px;">' +
                        `<a href="javascript:" onclick="regrasModulo('${id}')" class="text-primary"><i class="fas fa-tasks"></i></a>` +
                        '</div>' +
                        '<div class="title-box">' +
                        '<i class="fas fa-home"></i>' +
                        '&nbsp;Módulo Inicial' +
                        '</div>' +
                        '<div class="box">' +
                        '[ Selecione o Módulo ]' +
                        '</div>' +
                        '</div>';
                    break;
                case 'modulo':
                    mod = `<div id="${id}" data-type="modulo" class="datamodulo modulo" ondblclick="buscaModulo('${id}')">` +
                        '<div style="float: right;position: absolute;top: 4px;right: 6px;">' +
                        `<a href="javascript:" onclick="regrasModulo('${id}')" class="text-primary"><i class="fas fa-tasks"></i></a>` +
                        '</div>' +
                        '<div class="title-box">' +
                        '<i class="fal fa-terminal"></i>' +
                        '&nbsp;Módulo' +
                        '</div>' +
                        '<div class="box">' +
                        '[ Selecione o Módulo ]' +
                        '</div>' +
                        '</div>';
                    break;
                case 'decision':
                    mod = `<div id="${id}" data-type="decision" class="datamodulo decision" ondblclick="regrasDriven('${id}')">` +
                        '<div class="title-box">' +
                        '<i class="fas fa-cogs"></i>' +
                        '&nbsp;Data Driven Decision' +
                        '</div>' +
                        '<div class="box">' +
                        '&nbsp;' +
                        '</div>' +
                        '</div>';
                    break;
                case 'comunicacao':
                    mod = '<div class="datamodulo comunicacao" data-type="comunicacao" >' +
                        '<div class="title-box">' +
                        '<i class="fas fa-rss"></i>' +
                        '&nbsp;Comunicação' +
                        '</div>' +
                        '</div>';
                    break;

                default:
            }

            return mod;
        }

        var transform = '';

        function showpopup(e) {
            e.target.closest(".drawflow-node").style.zIndex = "9999";
            e.target.children[0].style.display = "block";
            //document.getElementById("modalfix").style.display = "block";

            //e.target.children[0].style.transform = 'translate('+translate.x+'px, '+translate.y+'px)';
            transform = editor.precanvas.style.transform;
            editor.precanvas.style.transform = '';
            editor.precanvas.style.left = editor.canvas_x + 'px';
            editor.precanvas.style.top = editor.canvas_y + 'px';
            console.log(transform);

            //e.target.children[0].style.top  =  -editor.canvas_y - editor.container.offsetTop +'px';
            //e.target.children[0].style.left  =  -editor.canvas_x  - editor.container.offsetLeft +'px';
            editor.editor_mode = "fixed";

        }

        function closemodal(e) {
            e.target.closest(".drawflow-node").style.zIndex = "2";
            e.target.parentElement.parentElement.style.display = "none";
            //document.getElementById("modalfix").style.display = "none";
            editor.precanvas.style.transform = transform;
            editor.precanvas.style.left = '0px';
            editor.precanvas.style.top = '0px';
            editor.editor_mode = "edit";
        }

        function changeMode(option) {
            $("#lock i").removeClass();
            if (editor.editor_mode == 'edit') {
                editor.editor_mode = 'fixed';
                $("#lock i").addClass("fas fa-lock-open")
            } else {
                editor.editor_mode = 'edit';
                $("#lock i").addClass("fas fa-lock")
            }

        }

        function buscaModulo(id) {
            $("#COD_MODULOS").val("");

            $("#btnBuscaModulo").click();
            setTimeout(function() {
                let tmr = setInterval(function() {
                    console.log("TMR", $("#popModal").is(":visible"))
                    if (!$("#popModal").is(":visible")) {
                        if ($("#COD_MODULOS").val() != "") {
                            setInfo(id, $("#COD_MODULOS").val(), $("#NOM_MODULOS").val());
                            exportDrawFlow();
                        }

                        clearInterval(tmr);
                    }
                }, 100);
            }, 1000)
        }

        function regrasModulo(id) {
            $("#REGRAS").val("");

            $("#btnRegrasModulo").attr("data-url", "action.php?mod=<?php echo fnEncode(1980) ?>&id=<?= $_GET["id"] ?>&idFluxo=<?php echo fnEncode($cod_fluxo) ?>&idNode=" + id + "&pop=true");
            $("#btnRegrasModulo").click();
            setTimeout(function() {
                let tmr = setInterval(function() {
                    console.log("TMR", $("#popModal").is(":visible"))
                    if (!$("#popModal").is(":visible")) {
                        if ($("#REGRAS").val() != "") {
                            setInfo(id, null, null, $("#REGRAS").val());
                            exportDrawFlow();
                        }
                        clearInterval(tmr);
                    }
                }, 100);
            }, 1000)
        }

        function regrasDriven(id) {
            $("#REGRAS").val("");

            $("#btnDataDriven").attr("data-url", "action.php?mod=<?php echo fnEncode(1984) ?>&id=<?= $_GET["id"] ?>&idFluxo=<?php echo fnEncode($cod_fluxo) ?>&idNode=" + id + "&pop=true");
            $("#btnDataDriven").click();
            setTimeout(function() {
                let tmr = setInterval(function() {
                    console.log("TMR", $("#popModal").is(":visible"))
                    if (!$("#popModal").is(":visible")) {
                        if ($("#REGRAS").val() != "") {
                            setInfo(id, null, $("#REGRAS_NOME").val(), $("#REGRAS").val(), $("#REGRAS_ORIGEM").val());
                            exportDrawFlow();
                        }
                        clearInterval(tmr);
                    }
                }, 100);
            }, 1000)
        }

        /*
        function buscaDataDriven(id) {
            $("#COD_DATA_DRIVEN").val("");

            $("#btnBuscaDataDriven").click();
            setTimeout(function() {
                let tmr = setInterval(function() {
                    console.log("TMR", $("#popModal").is(":visible"))
                    if (!$("#popModal").is(":visible")) {
                        if ($("#COD_DATA_DRIVEN").val() != "") {
                            setInfo(id, $("#COD_DATA_DRIVEN").val(), $("#DES_FLUXO").val());
                            exportDrawFlow();
                        }

                        clearInterval(tmr);
                    }
                }, 100);
            }, 1000)
        }*/

        function setInfo(id, valor, descricao, regras, origem) {
            valor = (valor == undefined ? null : valor);
            descricao = (descricao == undefined ? null : descricao);
            regras = (regras == undefined ? null : regras);
            origem = (origem == undefined ? null : origem);

            console.log(id, valor, descricao, regras);
            if (valor && descricao) {
                $("#" + id).find(".box").html(valor + " - " + descricao);
            } else if (descricao) {
                $("#" + id).find(".box").html(descricao);
            }
            if (valor) {
                $("#" + id).attr("data-cod", valor);
            }
            if (origem) {
                $("#" + id).attr("data-origem", origem);
            }
            if (descricao) {
                $("#" + id).attr("data-desc", descricao);
            }
            if (regras) {
                if (typeof(regras) != "string") {
                    regras = JSON.stringify(regras);
                }
                $("#" + id).attr("data-regras", regras);
            }
        }

        function limpar() {
            $.confirm({
                title: 'Atenção!',
                animation: 'opacity',
                closeAnimation: 'opacity',
                content: 'Esta operação é irreversível. Deseja realmente limpar todo o fluxo de dados?',
                buttons: {
                    confirmar: function() {
                        editor.clearModuleSelected();
                        exportDrawFlow();
                        addNodeHome();
                    },
                    cancelar: function() {

                    },
                }
            });
        }

        function exportDrawFlow() {
            $("#saving-text").show();
            $("#JSN_FLUXO_EXPORT").val(JSON.stringify(editor.export().drawflow.Home));

            let items = {};
            $(".drawflow .datamodulo").each(function() {
                let id = $(this).attr("id");
                let cod = $(this).attr("data-cod");
                let desc = $(this).attr("data-desc");
                let type = $(this).attr("data-type");
                let origem = $(this).attr("data-origem");
                let regras = [];
                if ($(this).attr("data-regras") && $(this).attr("data-regras") != "") {
                    console.log("PRE", id, cod, desc, $(this).attr("data-regras"))
                    regras = JSON.parse($(this).attr("data-regras"));
                    console.log("REGRA", regras)
                }

                items[id] = {
                    cod: +cod,
                    desc,
                    regras,
                    origem,
                    type,
                };
                console.log("ITEM", items[id]);
            });
            console.log("**********");
            console.log(items);
            console.log("**********");
            $("#formulario #DES_ITENS").val(JSON.stringify(items));

            items = {};
            Object.keys(editor.export().drawflow.Home.data).map(function(key) {
                let item = editor.export().drawflow.Home.data[key];

                let html = item.html;
                let id = $(html).attr("id");
                let cod = $("#" + id).attr("data-cod") ?? 0;
                let desc = $("#" + id).attr("data-desc") ?? '';
                let regras = $("#" + id).attr("data-regras") ?? '';

                console.log("regras,", regras)

                let next = item.outputs.output_1.connections.map(function(ret) {
                    let html = editor.export().drawflow.Home.data[ret['node']].html;
                    return $(html).attr("id");
                });

                let prev = item.inputs.input_1 ? item.inputs.input_1.connections.map(function(ret) {
                    let html = editor.export().drawflow.Home.data[ret['node']].html;
                    return $(html).attr("id");
                }) : [];

                items[id] = {
                    cod: +cod,
                    desc,
                    id,
                    type: item.name,
                    prev,
                    next,
                };
            });
            $("#formulario #DES_FLUXO_MODULOS").val(JSON.stringify(items));


            $("#formulario #COD_MODULOS").val($(".drawflow-node.home .home").attr("data-cod") ?? '');
            $("#formulario #COD_NODE").val($(".drawflow-node.home .home").attr("id") ?? '');

            $.ajax({
                url: "ajxDrawFlow.php?id=<?= $_GET["id"] ?>",
                type: "POST",
                data: $("#formulario").serialize(),
                success: function(response) {
                    console.log(response);
                    if (!response.success) {
                        $(".portlet-body").prepend(`
                        <div class="alert alert-danger alert-dismissible top30 bottom30" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            ${response.error}
                        </div>
                    `);
                    }
                    $("#saving-text").hide();
                },
                error: function(xhr, status, error) {
                    console.log(xhr, status, error);
                    $(".portlet-body").prepend(`
                        <div class="alert alert-danger alert-dismissible top30 bottom30" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            ${error}
                        </div>
                    `);
                    $("#saving-text").hide();
                }
            });

            return true;
        }

        function addNodeHome() {
            if ($(".drawflow-node.home").length <= 0) {
                const drawflowElement = document.getElementById('drawflow');

                const centerX = drawflowElement.getBoundingClientRect().left + 50;
                const centerY = drawflowElement.getBoundingClientRect().top + 50;

                addNodeToDrawFlow('home', centerX, centerY);
            }
        }
    </script>