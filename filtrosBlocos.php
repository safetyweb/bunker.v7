<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

?>

<style>
    .bg {
        background-size: 25px 25px;
        background-image: linear-gradient(to right, #f1f1f1 1px, transparent 1px), linear-gradient(to bottom, #f1f1f1 1px, transparent 1px);
    }

    .drawflow-node .el_child {
        display: none;
    }

    .drawflow-node.child .el_child {
        display: block;
    }

    .drawflow-node .title-box {
        margin: -15px -15px 0 -15px;
        background: #f7f7f7;
        border-bottom: 1px solid #e9e9e9;
        border-radius: 4px 4px 0px 0px;
        text-align: center;
        font-weight: bold;
        padding: 5px;
    }

    .drawflow-node.child .title-box {
        background: #95fff1;
    }

    .drawflow-node .box {
        padding: 5px 10px;
        font-size: 14px;
        margin: 0 -15px -15px -15px;
        background: #FFF;
    }

    .drawflow-node .opcoes {
        height: 70px;
        overflow: auto;
    }

    .drawflow-node .opcoes ul {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .drawflow-node .opcoes ul li {
        background: #EEE;
        border: 1px solid #CCC;
        padding: 3px;
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
        width: 250px !important;
    }

    .drawflow .drawflow-node .input,
    .drawflow .drawflow-node .output {
        background: #EEE !important;
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
                        <div class="drag-drawflow" draggable="true" ondragstart="drag(event)" data-node="filter">
                            <i class="fal fa-terminal"></i>
                            <span>&nbsp;Novo Filtro</span>
                        </div>
                    </div>

                    <div class="col-md-10">

                        <div class="row">
                            <div class="text-right col-sm-12">
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


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.css">
    <script src="https://cdn.jsdelivr.net/gh/jerosoler/Drawflow/dist/drawflow.min.js"></script>

</div>
<script type="text/javascript">
    var editor;
    $(document).ready(function() {
        var id = document.getElementById("drawflow");
        editor = new Drawflow(id);
        editor.reroute = true;
        const dataToImport = {
            "drawflow": {
                "Home": {
                    data: {}
                }
            }
        }


        editor.start();
        editor.import(dataToImport);

        editor.on('nodeCreated', function(id) {
            console.log("Node created " + id);
            checkBlocos();
            exportDrawFlow();
        })

        editor.on('nodeRemoved', function(id) {
            console.log("Node removed " + id);
            checkBlocos();
            exportDrawFlow();
        })

        editor.on('nodeSelected', function(id) {
            console.log("Node selected " + id);
        })

        editor.on('moduleCreated', function(name) {
            console.log("Module Created " + name);
            checkBlocos();
            exportDrawFlow();
        })

        editor.on('moduleChanged', function(name) {
            console.log("Module Changed " + name);
            checkBlocos();
            exportDrawFlow();
        })

        editor.on('connectionCreated', function(connection) {
            console.log('Connection created');
            console.log(connection);
            checkBlocos();
            exportDrawFlow();
        })

        editor.on('connectionRemoved', function(connection) {
            console.log('Connection removed');
            console.log(connection);
            checkBlocos();
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

        addNodes();


    });


    var mobile_last_move = null;

    function positionMobile(ev) {
        mobile_last_move = ev;
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        if (ev.type === "touchstart") {

        } else {
            ev.dataTransfer.setData("node", ev.target.getAttribute('data-node'));
        }
    }

    function drop(ev) {
        if (ev.type === "touchend") {
            var parentdrawflow = document.elementFromPoint(mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY).closest("#drawflow");
            if (parentdrawflow != null) {
                addNodeToDrawFlow(0, "Novo Filtro", mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY);
            }
        } else {
            ev.preventDefault();
            var data = ev.dataTransfer.getData("node");
            addNodeToDrawFlow(0, "Novo Filtro", ev.clientX, ev.clientY);
        }
        exportDrawFlow();
    }

    function addNodeToDrawFlow(cod, name, pos_x, pos_y) {
        if (editor.editor_mode === 'fixed') {
            return false;
        }
        pos_x = pos_x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)) - (editor.precanvas.getBoundingClientRect().x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)));
        pos_y = pos_y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)) - (editor.precanvas.getBoundingClientRect().y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)));

        let id = 'el_' + Math.random().toString(36).substr(2, 9);

        let mod = module(cod, name, id);
        editor.addNode(name, 1, 1, pos_x, pos_y, "home", {}, mod);
    }

    function module(cod, name, id) {
        return '' +
            `<div id="${id}" data-id="${cod}">` +
            '   <div class="title-box">' +
            `       <span class="DES_TPFILTRO">${name}</span>` +
            '   </div>' +
            '   <div class="box">' +
            '       <span>Adicionar Opção</span>' +
            '       <div class="el_child">' +
            '           <select class="form-control input-sm">' +
            '               <option value="">[ Vincular ]</option>' +
            '               <option value="1">Opção 1</option>' +
            '               <option value="2">Opção 2</option>' +
            '               <option value="3">Opção 3</option>' +
            '           </select>' +
            '       </div>' +
            '       <div>' +
            '           <input type="text" class="form-control input-sm">' +
            '       </div>' +
            '       <div class="opcoes">' +
            '           <ul>' +
            '           <li>Opção 1</li>' +
            '           <li>Opção 2</li>' +
            '           <li>Opção 3</li>' +
            '           <li>Opção 4</li>' +
            '           <li>Opção 5</li>' +
            '           <li>Opção 6</li>' +
            '           </li>' +
            '       </div>' +
            '   </div>' +
            '</div>';
    }

    var transform = '';


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



    function exportDrawFlow() {

        return true;
    }

    function addNodes() {
        const drawflowElement = document.getElementById('drawflow');

        let centerX = drawflowElement.getBoundingClientRect().left + 50;
        let centerY = drawflowElement.getBoundingClientRect().top + 20;

        <?php
        $sql = "SELECT * FROM tipo_filtro;";
        $rs = mysqli_query($adm, $sql);
        while ($linha = mysqli_fetch_assoc($rs)) {
            echo "addNodeToDrawFlow('" . $linha["COD_TPFILTRO"] . "','" . $linha["DES_TPFILTRO"] . "', centerX, centerY);";
            echo "centerY = centerY + 180;";
        }
        ?>
    }

    function checkBlocos() {
        let elements = $(".parent-node .drawflow-node");
        let i = 0;

        while (i < elements.length) {
            let id = elements[i].id;
            if ($(`svg.node_in_${id}`).length > 0) {
                console.log(id);
                $(`#${id}`).addClass("child");
            } else {
                $(`#${id}`).removeClass("child");
            }
            i++;
        }
    }
</script>

node_in_node-1