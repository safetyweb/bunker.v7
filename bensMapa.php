<?php

// echo fnDebug('true');

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));
        $des_coorden = fnLimpaCampo($_REQUEST['DES_COORDEN']);
        $num_area = fnLimpaCampo($_REQUEST['NUM_AREA']);
        $num_perimetro = fnLimpaCampo($_REQUEST['NUM_PERIMETRO']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'ALT':
                    $sql = "UPDATE BENS_IMOVEIS SET
                                DES_COORDEN = '" . $des_coorden . "',
                                NUM_AREA = '" . $num_area . "',
                                NUM_PERIMETRO = '" . $num_perimetro . "',
								COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',
								DAT_ALTERAC = NOW()
							WHERE COD_BEM = '" . $cod_bem . "'";

                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}

//busca dados da url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));
    $cod_cliente = fnLimpacampoZero(fnDecode($_GET['idC']));
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
}


//busca dados do bem
if (is_numeric(fnLimpaCampoZero(fnDecode($_GET['idBem'])))) {

    $sql = "SELECT BENS_CLIENTE.*,TIPO_BEM.DES_MEDIDA FROM BENS_CLIENTE LEFT JOIN TIPO_BEM ON TIPO_BEM.COD_TIPOBEM = BENS_CLIENTE.COD_TIPO  WHERE BENS_CLIENTE.COD_BEM = $cod_bem";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
    $qrBuscaBem = mysqli_fetch_assoc($arrayQuery);

    $cod_tipobem = $qrBuscaBem['COD_TIPO'];
    $cod_cliente = $qrBuscaBem['COD_CLIENTE'];
    $des_nomebem = $qrBuscaBem['DES_NOMEBEM'];
    $des_medida = $qrBuscaBem['DES_MEDIDA'];

    $val_informado = fnValor($qrBuscaBem['VAL_INFORMADO'], 2);
    $val_efetivo = fnValor($qrBuscaBem['VAL_EFETIVO'], 2);

    $sql2 = "SELECT * FROM BENS_IMOVEIS WHERE COD_BEM = $cod_bem AND COD_EMPRESA = $cod_empresa";
    $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2) or die(mysqli_error(connTemp($cod_empresa, '')));
    $qrBuscaBemImoveis = mysqli_fetch_assoc($arrayQuery2);

    $qtd_areatot = $qrBuscaBemImoveis['QTD_AREATOT'];
    $qtd_produti = $qrBuscaBemImoveis['QTD_PRODUTI'];
    $num_cepbemu = $qrBuscaBemImoveis['NUM_CEPBEMU'];
    $des_endereco = $qrBuscaBemImoveis['DES_ENDERECO'];
    $num_endereco = $qrBuscaBemImoveis['NUM_ENDERECO'];
    $des_complem = $qrBuscaBemImoveis['DES_COMPLEM'];
    $des_bairroc = $qrBuscaBemImoveis['DES_BAIRROC'];
    $cod_municipio = $qrBuscaBemImoveis['COD_MUNICIPIO'];
    $cod_estado = $qrBuscaBemImoveis['COD_ESTADO'];
    $des_roteiro = $qrBuscaBemImoveis['DES_ROTEIRO'];
    $des_cartorio = $qrBuscaBemImoveis['DES_CARTORIO'];
    $num_matricu = $qrBuscaBemImoveis['NUM_MATRICU'];
    $num_folhama = $qrBuscaBemImoveis['NUM_FOLHAMA'];
    $num_livroma = $qrBuscaBemImoveis['NUM_LIVROMA'];
    $cod_estadocar = $qrBuscaBemImoveis['COD_ESTADOCAR'];
    $cod_municar = $qrBuscaBemImoveis['COD_MUNICAR'];
    $num_nirfima = $qrBuscaBemImoveis['NUM_NIRFIMA'];
    $num_circmat = $qrBuscaBemImoveis['NUM_CIRCMAT'];
    $num_carimov = $qrBuscaBemImoveis['NUM_CARIMOV'];
    $num_escricao = $qrBuscaBemImoveis['NUM_ESCRICAO'];
    $dat_verifica = $qrBuscaBemImoveis['DAT_VERIFICA'];
    $log_amazonia = $qrBuscaBemImoveis['LOG_AMAZONIA'];
    $log_carvali = $qrBuscaBemImoveis['LOG_CARVALI'];
    $log_statusc = $qrBuscaBemImoveis['LOG_STATUSC'];
    $log_areaemb = $qrBuscaBemImoveis['LOG_AREAEMB'];
    $log_indigin = $qrBuscaBemImoveis['LOG_INDIGIN'];
    $log_conserv = $qrBuscaBemImoveis['LOG_CONSERV'];
    $log_usosust = $qrBuscaBemImoveis['LOG_USOSUST'];
    $log_quilomb = $qrBuscaBemImoveis['LOG_QUILOMB'];
    $log_frontei = $qrBuscaBemImoveis['LOG_FRONTEI'];
    $log_assento = $qrBuscaBemImoveis['LOG_ASSENTO'];
    $log_marinha = $qrBuscaBemImoveis['LOG_MARINHA'];
    $des_coorden = $qrBuscaBemImoveis['DES_COORDEN'];
    $num_area = $qrBuscaBemImoveis['NUM_AREA'];
    $num_perimetro = $qrBuscaBemImoveis['NUM_PERIMETRO'];
}

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

            <?php
            $abaBens = 1922;
            include "abasBens.php";
            ?>
            <div class="push10"></div>

            <div class="portlet-body">

                <?php if ($msgRetorno != '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>


                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>" onsubmit="return validateForm()">
                        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_MUNICIPIO" id="COD_MUNICIPIO" value="">

                        <?php include "bensHeader.php"; ?>

                        <fieldset>
                            <legend>Mapa</legend>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Área Total</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="QTD_AREATOT" id="QTD_AREATOT" value="<?= number_format($qtd_areatot, 2, ',', '.') ?> <?= $des_medida ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Área Selecionada</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_AREA_PREV" id="NUM_AREA_PREV" value="">
                                        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="NUM_AREA" id="NUM_AREA" value="<?= $num_area ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Perímetro</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_PERIMETRO_PREV" id="NUM_PERIMETRO_PREV" value="">
                                        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="NUM_PERIMETRO" id="NUM_PERIMETRO" value="<?= $num_perimetro ?>">
                                    </div>
                                </div>
                            </div>

                            <div id="map" style="height: 75vh;width: 100%;"></div>
                            <div class="push5"></div>
                            <div class="form-group text-right col-lg-12">
                                <a class="btn btn-primary getBtn" onclick='validateForm();$(".leaflet-draw-draw-polygon")[0].click()'>
                                    <i class="fas fa-draw-polygon" aria-hidden="true"></i>
                                    &nbsp; Adicionar Área
                                </a>
                                <a class="btn btn-primary getBtn" onclick='validateForm();$(".leaflet-draw-edit-edit")[0].click()'>
                                    <i class="fas fa-layer-group" aria-hidden="true"></i>
                                    &nbsp; Editar Áreas
                                </a>
                                <a class="btn btn-primary getBtn" onclick='validateForm();$(".leaflet-draw-edit-remove")[0].click()'>
                                    <i class="far fa-trash-alt" aria-hidden="true"></i>
                                    &nbsp; Remover Área
                                </a>
                                <a class="btn btn-primary getBtn" onclick="validateForm();centerMap()">
                                    <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                                    &nbsp; Centralizar
                                </a>

                                <input type="file" id="fileInputMap" accept=".kml,.kmz" style="display: none;">
                                <a class="btn btn-primary getBtn" onclick="validateForm();$('#fileInputMap').click()">
                                    <i class="fas fa-file-import" aria-hidden="true"></i>
                                    &nbsp; Importar Arquivo
                                </a>

                            </div>

                            <input type="hidden" name="DES_COORDEN" id="DES_COORDEN" value='<?= $des_coorden ?>'>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <!-- <button type="reset" class="btn btn-default" onclick="resetForm()"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>

                        </div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push5"></div>

                    </form>

                    <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<div class="push20"></div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/leaflet.css" integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ==" crossorigin="" />
<script src="js/plugins/leaflet.markercluster-master/dist/leaflet-src.js" integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA==" crossorigin=""></script>

<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.css" />
<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.Default.css" />
<script src="js/plugins/leaflet.markercluster-master/dist/leaflet.markercluster-src.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js"></script>


<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

<!-- Calculo de Area e Perimetro -->
<script src='https://unpkg.com/@turf/turf@6/turf.min.js'></script>


<script type="text/javascript">
    $(document).ready(function() {
        initMap();

        $(".addBox").click(function(e) {
            if ($(this).attr("disabled")) {
                e.stopPropagation();
            }
        });

        carregaComboCidades(0);
        carregaComboCidadesCar(0);

        $("#COD_ESTADO").change(function() {
            cod_estado = $(this).val();
            carregaCidadeMapa();
            carregaComboCidades(cod_estado);
        });
        $("#COD_ESTADOCAR").change(function() {
            cod_estado = $(this).val();
            carregaComboCidadesCar(cod_estado);
        });


        $('#fileInputMap').change(function(event) {
            let file = event.target.files[0];
            let type = file.name.match(/\.(.+)$/)[1];
            if (file) {

                if (type == "kml") {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        processaKML(e.target.result);
                    };
                    reader.readAsText(file);
                } else if (type == "kmz") {
                    var zip = new JSZip();
                    zip.loadAsync(file)
                        .then(function(zip) {
                            // Verifique se o arquivo KML existe dentro do KMZ
                            if (zip.files['doc.kml']) {
                                // Leia o arquivo KML
                                return zip.files['doc.kml'].async('string');
                            } else {
                                alert('O arquivo KML não foi encontrado no KMZ.');
                            }
                        })
                        .then(function(kmlContent) {
                            processaKML(kmlContent);
                        })
                        .catch(function(error) {
                            alert('Ocorreu um erro ao processar o arquivo KMZ:', error);
                        });
                } else {
                    alert("Formato " + type + " não suportado!");
                }
            }

            $('#fileInputMap').val("");
        });

    });


    function carregaComboCidades(cod_estado, cod_municipio) {
        if (cod_municipio == undefined) {
            cod_municipio = 0;
        }

        $.ajax({
            method: 'POST',
            url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
            data: {
                COD_ESTADO: cod_estado
            },
            beforeSend: function() {
                $('#listaCidades').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#listaCidades").html(data);
                $("#formulario #COD_MUNICIPIO").val(cod_municipio).trigger("chosen:updated");

                $("#COD_MUNICIPIO").change(function() {
                    carregaCidadeMapa();
                });
                carregaCidadeMapa();
            }
        });
    }

    function carregaComboCidadesCar(cod_estado, cod_municipio) {
        if (cod_municipio == undefined) {
            cod_municipio = 0;
        }

        $.ajax({
            method: 'POST',
            url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
            data: {
                COD_ESTADO: cod_estado
            },
            beforeSend: function() {
                $('#listaCidadesCar').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                data = data.replaceAll("COD_MUNICIPIO", "COD_MUNICAR")
                $("#listaCidadesCar").html(data);
                $("#formulario #COD_MUNICAR").val(cod_municipio).trigger("chosen:updated");
            }
        });
    }



    /* ********************************************************* */

    var map;
    var defaultLatLng = [-15.779720, -47.929720];
    var drawnItems = null;
    var streetMap
    var topoMap
    var satelliteMap

    function initMap() {
        streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        });

        topoMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 16,
        });

        satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 17,
        });

        latlng = L.latLng(defaultLatLng[0], defaultLatLng[1]);

        map = L.map('map', {
            center: latlng,
            zoom: 4,
            layers: [satelliteMap]
        });

        map.on('zoomend', function() {
            var currentZoom = map.getZoom();
            console.log('Zoom:', currentZoom);
        });

        L.control.layers({
            "Satélite": satelliteMap,
            "Topográfico": topoMap,
            "Mapa de Ruas": streetMap,
        }, null, {
            collapsed: false
        }).addTo(map);

        centerMap();
        carregaCidadeMapa();


        drawnItems = new L.FeatureGroup().addTo(map);
        var drawControl = new L.Control.Draw({
            draw: {
                polygon: true,
                marker: false,
                circle: false,
                circlemarker: false,
                polyline: false
            },
            edit: {
                featureGroup: drawnItems
            }
        }).addTo(map);

        map.addLayer(drawnItems);

        addJSONMap();
        centerMap();
        saveJSONMap();

        setTimeout(function() {
            addJSONMap();
            centerMap();
            saveJSONMap();
        }, 1000);


        map.on('draw:created', function(event) {
            var layer = event.layer;
            drawnItems.addLayer(layer);

            layer.on('edit', function(event) {
                saveJSONMap();
            });

            saveJSONMap();
        });

        map.on('draw:edited', function(event) {
            saveJSONMap();
        });

        map.on('draw:deleted', function(event) {
            console.log("DELETED")
            saveJSONMap();
        });
    }

    function centerMap() {
        function center() {
            if (drawnItems && drawnItems.getLayers().length > 0) {
                map.fitBounds(drawnItems.getBounds()); // Centralizar nas Marcações
            } else if (cityMap) {
                map.fitBounds(cityMap.getBounds()); // Centralizar na Cidade
            } else {
                map.setView(defaultLatLng, 4); // Centralizar no Brasil
            }
        }

        center();
    }


    var cityMap = null;

    function addCidadeMapa(coordinatesString) {
        var coordinatesArray = str2coordinates(coordinatesString, true);

        cityMap = L.polygon(coordinatesArray, {
            color: '#a30a00',
            fillOpacity: 0,
            dashArray: "5, 5"
        });


        cityMap.addTo(map);
        centerMap();
    }

    function removeCidadeMapa() {
        if (cityMap) {
            map.removeLayer(cityMap);
            cityMap = null;
        }
        centerMap();
    }

    function carregaCidadeMapa() {
        removeCidadeMapa();

        let cidade = "<?= $cod_municipio ?>";
        if (!cidade) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'ajxMunicipioCoord.php?id=<?= fnEncode($cod_empresa) ?>&cidade=' + cidade,
            success: function(data) {
                console.log('https://bunker.mk/ajxMunicipioCoord.php?id=<?= fnEncode($cod_empresa) ?>&cidade=' + cidade)
                if (data) {
                    addCidadeMapa(data["DS_COORDEN"]);
                } else {
                    console.log(data)
                }
            }
        });

        console.log("Cidade", cidade)
    }

    function saveJSONMap() {
        let geoJSONData = [];

        let area = 0;
        let perimeter = 0;

        drawnItems.eachLayer(function(layer) {
            if (layer instanceof L.Polygon) {
                let polygon = layer.toGeoJSON();
                geoJSONData.push(polygon);

                let calc = calculateArea(polygon);
                area += calc.area;
                perimeter += calc.perimeter;
            }
        });

        $("#DES_COORDEN").val(JSON.stringify(geoJSONData));
        setArea(area, perimeter);

        console.log(geoJSONData);
    }

    function addJSONMap() {
        let geojsonData = JSON.parse($("#DES_COORDEN").val() || "[]");

        let area = 0;
        let perimeter = 0;

        console.log("ADD", geojsonData);

        drawnItems.clearLayers();
        geojsonData.forEach(function(feature) {
            if (feature.geometry.type === "Polygon") {
                let coordinates = feature.geometry.coordinates[0];
                var correctedCoordinates = coordinates.map(function(coord) {
                    return [coord[1], coord[0]];
                });
                var polygon = L.polygon(correctedCoordinates);
                drawnItems.addLayer(polygon);

                polygon.on('edit', function(event) {
                    saveJSONMap();
                });

                let calc = calculateArea(feature);
                area += calc.area;
                perimeter += calc.perimeter;
            }
        });

        centerMap();
        setArea(area, perimeter);
    }

    function str2coordinates(coordinatesString, invert) {
        return coordinatesString.split(" ").map(function(coordinate) {
            var parts = coordinate.split(",");
            if (parts.length > 1) {
                if (invert) {
                    return [parseFloat(parts[1]), parseFloat(parts[0])];
                } else {
                    return [parseFloat(parts[0]), parseFloat(parts[1])];
                }
            }
        }).filter(function(coordinate) {
            return coordinate !== undefined;
        });
    }


    function getLatLngAddress(address, callback = () => {}) {
        // Use a API de geocodificação do OpenStreetMap (Nominatim)
        var geocodingUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address);

        // Faz uma requisição AJAX para obter os resultados de geocodificação
        $.getJSON(geocodingUrl, function(data) {
            if (data && data.length > 0) {
                var lat = parseFloat(data[0].lat);
                var lon = parseFloat(data[0].lon);

                callback(true, data[0]);
            } else {
                callback(false);
            }
        });
    }

    function getLayerOptions() {
        let activeLayer = map.hasLayer(satelliteMap) ? satelliteMap :
            map.hasLayer(streetMap) ? streetMap :
            map.hasLayer(topoMap) ? topoMap :
            null;

        if (activeLayer) {
            return activeLayer.options;
        } else {
            return {};
        }
    }


    function calculateArea(coodinates) {
        var area = turf.area(coodinates);
        var perimeter = turf.length(coodinates, {
            units: 'meters'
        });

        <?php if ($des_medida == "ha") { ?>
            area = turf.convertArea(area, 'hectares') / 100;
        <?php } ?>

        return {
            area,
            perimeter
        };
    }

    function setArea(area, perimeter) {
        $("#NUM_AREA_PREV").val(area.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + " <?= $des_medida ?>");
        $("#NUM_PERIMETRO_PREV").val(perimeter.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + " m");

        $("#NUM_AREA").val(area);
        $("#NUM_PERIMETRO").val(perimeter);
    }



    function processaKML(kmlData) {
        $(kmlData).find("Placemark Polygon").each(function() {
            let coordinates = str2coordinates($(this).find("coordinates").text());

            let newPolygon = {
                type: "Feature",
                properties: {},
                geometry: {
                    type: "Polygon",
                    coordinates: [coordinates]
                }
            };

            // Adicionar o novo polígono à matriz existente
            let geoJSONData = JSON.parse($("#DES_COORDEN").val() || "[]");
            geoJSONData.push(newPolygon);

            $("#DES_COORDEN").val(JSON.stringify(geoJSONData));
            addJSONMap();
        });
    }



    function validateForm() {
        //Salva os pontos pendentes
        try {
            $(".leaflet-draw ul").each(function() {
                var firstLink = $(this).find("li a")[0];
                if (firstLink) {
                    firstLink.click();
                }
            });
        } catch (error) {}

        return true;
    }
</script>

<style>
    .leaflet-marker-icon {
        width: 10px !important;
        height: 10px !important;
        margin-left: -5px !important;
        margin-top: -5px !important;
    }
</style>