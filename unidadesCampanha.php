<?php
//fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
        $val_pesquisa = fnLimpaCampo($_POST['INPUT']);

        $cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
        $cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
        $nom_univend = fnLimpacampo($_REQUEST['NOM_UNIVEND']);
        $num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
        //$log_estatus = fnLimpacampo($_REQUEST['LOG_ESTATUS']);
        $num_escrica = fnLimpacampo($_REQUEST['NUM_ESCRICA']);
        $nom_fantasi = fnLimpacampo($_REQUEST['NOM_FANTASI']);
        $cod_tpunive = fnLimpacampoZero($_REQUEST['COD_TPUNIVE']);
        $des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
        $num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
        $des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
        $des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
        $num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
        $nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
        $cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);;
        $cod_externo = fnLimpacampoZero($_REQUEST['COD_EXTERNO']);
        $cod_fantasi = fnLimpacampo($_REQUEST['COD_FANTASI']);
        $lat = fnLimpacampoZero($_REQUEST['lat']);
        $lng = fnLimpacampoZero($_REQUEST['lng']);
        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = $_GET['mod'];
        $COD_MODULO = fndecode($_GET['mod']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {
            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                $sql = "INSERT INTO UNIDADEVENDA(
                    COD_EMPRESA,
                    COD_EXTERNO,
                    NOM_FANTASI,
                    NOM_UNIVEND,
                    NUM_CGCECPF,
                    NUM_ESCRICA,
                    DES_ENDEREC,
                    NUM_ENDEREC,
                    DES_BAIRROC,
                    DES_COMPLEM,
                    NUM_CEPOZOF,
                    NOM_CIDADEC,
                    COD_ESTADOF,
                    DAT_CADASTR,
                    COD_CADASTR,
                    LOG_ESTATUS,
                    COD_TPUNIVE,
                    lat,
                    lng
                    )VALUES(
                    $cod_empresa,
                    $cod_externo,
                    '$nom_fantasi',
                    '$nom_univend',
                    '$num_cgcecpf',
                    '$num_escrica',
                    '$des_enderec',
                    '$num_enderec',
                    '$des_bairroc',
                    '$des_complem',
                    '$num_cepozof',
                    '$nom_cidadec',
                    '$cod_estadof',
                    NOW(),
                    $cod_usucada,
                    'S',
                    $cod_tpunive,
                    '$lat',
                    '$lng'
                )";

                    //fnEscreve($sql);

                    $arrayCad = mysqli_query($connAdm->connAdm(), $sql);

                    if (!$arrayCad) {

                        $cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }
                    break;

                    case "ALT":
                    $sqlAlt = "UPDATE UNIDADEVENDA SET 
                    COD_EXTERNO = '$cod_externo',
                    COD_EMPRESA = $cod_empresa,
                    NOM_FANTASI = '$nom_fantasi',
                    NOM_UNIVEND = '$nom_univend',
                    NUM_CGCECPF = '$num_cgcecpf',
                    NUM_ESCRICA = '$num_escrica',
                    DES_ENDEREC = '$des_enderec',
                    NUM_ENDEREC = '$num_enderec',
                    DES_COMPLEM = '$des_complem',
                    DES_BAIRROC = '$des_bairroc',
                    NUM_CEPOZOF = '$num_cepozof',
                    NOM_CIDADEC = '$nom_cidadec',
                    COD_ESTADOF = '$cod_estadof',
                    COD_TPUNIVE = '$cod_tpunive',
                    DAT_ALTERAC = NOW(),
                    COD_ALTERAC = $cod_usucada,
                    lat = $lat,
                    lng = $lng
                    WHERE COD_EMPRESA = $cod_empresa
                    AND COD_UNIVEND = $cod_univend";
                    

                    // echo $sqlAlt;


                    $arrayAlt = mysqli_query($connAdm->connAdm(), $sqlAlt);

                    if (!$arrayAlt) {

                        //$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAlt, $nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                    }
                    break;
                }
            }
        }
    }





//busca dados da url	
    if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
        $cod_empresa = fnDecode($_GET['id']);
        $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
        $arrayQuery = mysqli_query($adm, $sql);
        $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

        if (isset($arrayQuery)) {
            $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
            $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
        }
    } else {
        $cod_empresa = 0;
    //fnEscreve('entrou else');
    }

    if ($val_pesquisa != "") {
        $esconde = " ";
    } else {
        $esconde = "display: none;";
    }

//fnEscreve($qrBuscaEmpresa['COD_MASTER']

    ?>

    <div class="push30"></div>

    <div class="row">

        <div class="col-md12 margin-bottom-30">
            <!-- Portlet -->
            <div class="portlet portlet-bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fal fa-terminal"></i>
                        <span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                    </div>

                    <?php
                    include "atalhosPortlet.php";
                    ?>

                </div>
                <div class="portlet-body">

                    <?php if ($msgRetorno <> '') { ?>
                        <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $msgRetorno; ?>
                        </div>
                    <?php } ?>

                    <div class="push10"></div>

                    <?php
                //menu superior - empresas
                    $abaEmpresa = 1960;

				//menu abas
                    include "abasEmpresas.php";
                    ?>

                    <div class="push30"></div>

                    <?php
				//sistema de campanha
                    if ($_SESSION["SYS_COD_SISTEMA"] != 20) {
                       $abaUniv = fnDecode($_GET['mod']);
					//echo $abaUsuario;
                       include "abasUnidadesEmpresa.php";
                   }	
                   ?>

                   <div class="push30"></div>

                   <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <?php if($cod_empresa == 332){ ?>
                                <div class="row" id="blocoCandidato" style="display: none;"> 
                                    <div class="col-xs-2">
                                        <a class=" btn btn-block btn-xs btn-info addBox" name="btn_candidato" id="btn_candidato" class="addBox" data-url="" data-title="Registro de Candidato">Registro de Candidato</a>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_UNIVEND" id="COD_UNIVEND" value="<?=$cod_univend?>">
                                    </div>								                                                
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Código Externo</label>
                                        <input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="18" data-error="Campo obrigatório" value="<?=$cod_externo?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome da Unidade</label>
                                        <input type="text" class="form-control input-sm" name="NOM_UNIVEND" id="NOM_UNIVEND" maxlength="100" value="<?=$nom_univend?>" data-error="Campo obrigatório" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome Fantasia</label>
                                        <input type="text" class="form-control input-sm" name="NOM_FANTASI" id="NOM_FANTASI" maxlength="249" data-error="Campo obrigatório" value="<?=$nom_fantasi?>"  required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CNPJ/CPF</label>
                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" value="<?=$num_cgcecpf?>" data-error="Campo obrigatório">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Inscrição Estadual</label>
                                        <input type="text" class="form-control input-sm" name="NUM_ESCRICA" id="NUM_ESCRICA" maxlength="20" value="<?=$num_escrica?>" data-error="Campo obrigatório">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Tipo da Unidade</label>
                                        <select data-placeholder="Selecione o tipo da unidade" name="COD_TPUNIVE" id="COD_TPUNIVE" class="chosen-select-deselect">
                                            <option value=""></option>                  
                                            <?php                                                                   
                                            //opções para empresa do blockchain
                                            
                                            if ( ($_SESSION["SYS_COD_SISTEMA"] == "21") or ($_SESSION["SYS_COD_MASTER"] == "2") ) {
                                                $sql = "select COD_TPUNIVE, NOM_TPUNIVE from tpunidadevenda WHERE cod_tpunive in (6,7,8,9) ORDER BY NOM_TPUNIVE";
                                            }else {
                                                $sql = "select COD_TPUNIVE, NOM_TPUNIVE from tpunidadevenda WHERE cod_tpunive not in (6,7,8,9) ORDER BY NOM_TPUNIVE";
                                            }
                                            
                                            $arrayQuery = mysqli_query($adm,$sql);

                                            while ($qrListaTpUnidade = mysqli_fetch_assoc($arrayQuery))
                                            {                                                       
                                                echo"
                                                <option value='".$qrListaTpUnidade['COD_TPUNIVE']."'>".$qrListaTpUnidade['NOM_TPUNIVE']."</option> 
                                                "; 
                                            }                                           
                                            ?>  
                                        </select>   
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Localização</legend>

                            <div class="row">

                               <div class="col-xs-1">

                                  <div class="push15"></div>
                                  <a href="javascript:void(0)" class="btn btn-info btn-block btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1444) ?>&id=<?php echo fnEncode($cod_empresa); ?>&pop=true" data-title="Busca CEP/Logradouro" data-toggle='tooltip' data-placement='top' data-original-title='Busca CEP/Logradouro'><i class="fal fa-map-marked-alt f16" aria-hidden="true"></i></a>

                              </div>

                              <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Endereço</label>
                                    <input type="text" class="form-control input-sm" name="DES_ENDEREC" id="DES_ENDEREC" value="<?=$des_enderec?>" maxlength="40">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Número</label>
                                    <input type="text" class="form-control input-sm" name="NUM_ENDEREC" id="NUM_ENDEREC" value="<?=$num_enderec?>"  maxlength="10">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Complemento</label>
                                    <input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" value="<?=$des_complem?>" maxlength="99">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Bairro</label>
                                    <input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" value="<?=$des_bairroc?>" maxlength="20" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">CEP</label>
                                    <input type="text" class="form-control input-sm" name="NUM_CEPOZOF" id="NUM_CEPOZOF" value="<?=$num_cepozof?>" maxlength="9">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Cidade</label>
                                    <input type="text" class="form-control input-sm" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?=$nom_cidadec?>" maxlength="40">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Estado</label>
                                    <select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" value="<?=$cod_estadof?>" class="chosen-select-deselect">
                                        <option value=""></option>
                                        <option value="AC">AC</option>
                                        <option value="AL">AL</option>
                                        <option value="AM">AM</option>
                                        <option value="AP">AP</option>
                                        <option value="BA">BA</option>
                                        <option value="CE">CE</option>
                                        <option value="DF">DF</option>
                                        <option value="ES">ES</option>
                                        <option value="GO">GO</option>
                                        <option value="MA">MA</option>
                                        <option value="MG">MG</option>
                                        <option value="MS">MS</option>
                                        <option value="MT">MT</option>
                                        <option value="PA">PA</option>
                                        <option value="PB">PB</option>
                                        <option value="PE">PE</option>
                                        <option value="PI">PI</option>
                                        <option value="PR">PR</option>
                                        <option value="RJ">RJ</option>
                                        <option value="RN">RN</option>
                                        <option value="RO">RO</option>
                                        <option value="RR">RR</option>
                                        <option value="RS">RS</option>
                                        <option value="SC">SC</option>
                                        <option value="SE">SE</option>
                                        <option value="SP">SP</option>
                                        <option value="TO">TO</option>
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Latitude</label>
                                    <input type="text" class="form-control input-sm" name="lat" id="lat" value="<?=$lat?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Longitude</label>
                                    <input type="text" class="form-control input-sm" name="lng" id="lng" value="<?=$lng?>">
                                </div>
                            </div>

                        </div>

                    </fieldset>

                    <div class="push10"></div>

                    <hr>
                    <div class="col-lg-4">
                        <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar Unidades</a>
                    </div>
                    <div class="form-group text-right col-lg-8">

                        <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                        <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                        <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                        <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

                    </div>

                    <input type="hidden" name="opcao" id="opcao" value="">
                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                    <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
                    <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

                    <div class="push5"></div>

                </form>

                <div class="push30"></div>

                <div class="row">
                    <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

                        <div class="col-xs-4 col-xs-offset-4">
                            <div class="input-group activeItem">
                                <div class="input-group-btn search-panel">
                                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                        <span id="search_concept">Sem filtro</span>&nbsp;
                                        <span class="far fa-angle-down"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li class="divisor"><a href="#">Sem filtro</a></li>
                                        <!-- <li class="divider"></li> -->
                                        <li><a href="#NOM_UNIVEND">Nome Empresa</a></li>
                                        <li><a href="#NOM_FANTASI">Nome Fantasia</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
                                <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
                                <div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
                                    <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                </div>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="push20"></div>

    <div class="portlet portlet-bordered">

        <div class="portlet-body">

            <div class="login-form">

                <div class="push30"></div>

                <div class="col-lg-12">

                    <div class="no-more-tables">

                        <form name="formLista">

                            <table class="table table-bordered table-striped table-hover tableSorter buscavel">
                                <thead>
                                    <tr>
                                        <th class="{ sorter: false }" width="40"></th>
                                        <th>Código</th>
                                        <th>Cód. Externo</th>
                                        <th>Nome da Unidade</th>
                                        <th>Nome Fantasia</th>
                                        <th>Dt. Cadastro</th>
                                        <th>Geolocalização</th>
                                        <th>Ativo</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php

                                    if ($filtro != "") {
                                        $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
                                    } else {
                                        $andFiltro = " ";
                                    }

                                    $sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa
                                    $andFiltro";
                                    $arrayQuery = mysqli_query($adm, $sql);
                                        // fnEscreve($sql);

                                    $count = 0;
                                    while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) {
                                        $count++;

                                        if ($qrListaUniVendas[lat] != "" && $qrListaUniVendas[lat] != "0.0000000" && $qrListaUniVendas[lng] != "" && $qrListaUniVendas[lng] != "0.0000000") {
                                            $mostraAtivoGeo = '<i class="fal fa-check" aria-hidden="true"></i>';
                                        } else {
                                            $mostraAtivoGeo = '';
                                        }

                                        $sqlUni = "SELECT * FROM unidades_parametro WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVENDA = $qrListaUniVendas[COD_UNIVEND] ";
                                            //fnEscreve($sqlUni);
                                        $arrayQueryUni =  mysqli_query($conn, $sqlUni);
                                        $qrControleUnidade = mysqli_fetch_assoc($arrayQueryUni);

                                            //fnEscrevearray($qrControleUnidade);

                                        echo "
                                        <tr>
                                        <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                        <td><small>" . $qrListaUniVendas['COD_UNIVEND'] . "</td>
                                        <td><small>" . $qrListaUniVendas['COD_EXTERNO'] . "</td>
                                        <td><small>" . $qrListaUniVendas['NOM_UNIVEND'] . "</td>
                                        <td><small>" . $qrListaUniVendas['NOM_FANTASI'] . "</td>
                                        <td><small>" . fnDataFull($qrListaUniVendas['DAT_CADASTR']) . "</td>
                                        <td align='center'><small>" . $mostraAtivoGeo . "</td>
                                        </tr>
                                        <input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrListaUniVendas['COD_UNIVEND'] . "'>
                                        <input type='hidden' id='ret_COD_UNIVEND_URL_" . $count . "' value='" . fnEncode($qrListaUniVendas['COD_UNIVEND']) . "'>
                                        <input type='hidden' id='ret_COD_BANDEIRA_" . $count . "' value='" . $qrListaUniVendas['COD_BANDEIRA'] . "'>
                                        <input type='hidden' id='ret_NOM_UNIVEND_" . $count . "' value='" . $qrListaUniVendas['NOM_UNIVEND'] . "'>
                                        <input type='hidden' id='ret_NOM_FANTASI_" . $count . "' value='" . $qrListaUniVendas['NOM_FANTASI'] . "'>
                                        <input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . $qrListaUniVendas['NUM_CGCECPF'] . "'>
                                        <input type='hidden' id='ret_NUM_ESCRICA_" . $count . "' value='" . $qrListaUniVendas['NUM_ESCRICA'] . "'>
                                        <input type='hidden' id='ret_NOM_RESPONS_" . $count . "' value='" . $qrListaUniVendas['NOM_RESPONS'] . "'>
                                        <input type='hidden' id='ret_LOG_ESTATUS_" . $count . "' value='" . $qrListaUniVendas['LOG_ESTATUS'] . "'>
                                        <input type='hidden' id='ret_NUM_TELEFON_" . $count . "' value='" . $qrListaUniVendas['NUM_TELEFON'] . "'>
                                        <input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . $qrListaUniVendas['NUM_CELULAR'] . "'>
                                        <input type='hidden' id='ret_NUM_WHATSAPP_" . $count . "' value='" . $qrListaUniVendas['NUM_WHATSAPP'] . "'>
                                        <input type='hidden' id='ret_DES_HORATEND_" . $count . "' value='" . $qrListaUniVendas['DES_HORATEND'] . "'>
                                        <input type='hidden' id='ret_DES_ENDEREC_" . $count . "' value='" . $qrListaUniVendas['DES_ENDEREC'] . "'>
                                        <input type='hidden' id='ret_NUM_ENDEREC_" . $count . "' value='" . $qrListaUniVendas['NUM_ENDEREC'] . "'>
                                        <input type='hidden' id='ret_DES_COMPLEM_" . $count . "' value='" . $qrListaUniVendas['DES_COMPLEM'] . "'>
                                        <input type='hidden' id='ret_DES_BAIRROC_" . $count . "' value='" . $qrListaUniVendas['DES_BAIRROC'] . "'>
                                        <input type='hidden' id='ret_NUM_CEPOZOF_" . $count . "' value='" . $qrListaUniVendas['NUM_CEPOZOF'] . "'>
                                        <input type='hidden' id='ret_NOM_CIDADEC_" . $count . "' value='" . $qrListaUniVendas['NOM_CIDADEC'] . "'>
                                        <input type='hidden' id='ret_COD_ESTADOF_" . $count . "' value='" . $qrListaUniVendas['COD_ESTADOF'] . "'>
                                        <input type='hidden' id='ret_COD_TPUNIVE_" . $count . "' value='" . $qrListaUniVendas['COD_TPUNIVE'] . "'>
                                        <input type='hidden' id='ret_COD_GRUPOTR_" . $count . "' value='" . $qrListaUniVendas['COD_GRUPOTR'] . "'>
                                        <input type='hidden' id='ret_COD_PROPRIEDADE_" . $count . "' value='" . $qrListaUniVendas['COD_PROPRIEDADE'] . "'>
                                        <input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrListaUniVendas['COD_EXTERNO'] . "'>
                                        <input type='hidden' id='ret_COD_FANTASI_" . $count . "' value='" . $qrListaUniVendas['COD_FANTASI'] . "'>
                                        <input type='hidden' id='ret_NOM_EMAIL_" . $count . "' value='" . $qrListaUniVendas['NOM_EMAIL'] . "'>															
                                        <input type='hidden' id='ret_COD_TIPOREG_" . $count . "' value='" . $qrListaUniVendas['COD_TIPOREG'] . "'>															
                                        <input type='hidden' id='ret_LOG_ATIVOHS_" . $count . "' value='" . $qrListaUniVendas['LOG_ATIVOHS'] . "'>															
                                        <input type='hidden' id='ret_LOG_DELIVERY_" . $count . "' value='" . $qrListaUniVendas['LOG_DELIVERY'] . "'>															
                                        <input type='hidden' id='ret_LOG_ESPECIAL_" . $count . "' value='" . $qrListaUniVendas['LOG_ESPECIAL'] . "'>															
                                        <input type='hidden' id='ret_LOG_COBRANCA_" . $count . "' value='" . $qrListaUniVendas['LOG_COBRANCA'] . "'>															
                                        <input type='hidden' id='ret_LOG_UNIPREF_" . $count . "' value='" . $qrListaUniVendas['LOG_UNIPREF'] . "'>															
                                        <input type='hidden' id='ret_lat_" . $count . "' value='" . $qrListaUniVendas['lat'] . "'>															
                                        <input type='hidden' id='ret_lng_" . $count . "' value='" . $qrListaUniVendas['lng'] . "'>

                                        <input type='hidden' id='ret_LOG_STATUS_" . $count . "' value='" . $qrControleUnidade['LOG_STATUS'] . "'>
                                        <input type='hidden' id='ret_COD_INTEGRADORA_" . $count . "' value='" . $qrControleUnidade['COD_INTEGRADORA'] . "'>
                                        <input type='hidden' id='ret_COD_VERSAOINTEGRA_" . $count . "' value='" . $qrControleUnidade['COD_VERSAOINTEGRA'] . "'>
                                        <input type='hidden' id='ret_NUM_DECIMAIS_" . $count . "' value='" . $qrControleUnidade['NUM_DECIMAIS'] . "'>
                                        <input type='hidden' id='ret_TIP_RETORNO_" . $count . "' value='" . $qrControleUnidade['TIP_RETORNO'] . "'>
                                        <input type='hidden' id='ret_COD_DATAWS_" . $count . "' value='" . $qrControleUnidade['COD_DATAWS'] . "'>
                                        <input type='hidden' id='ret_LOG_CADVENDEDOR_" . $count . "' value='" . $qrControleUnidade['LOG_CADVENDEDOR'] . "'>
                                        ";
                                    }

                                    ?>

                                </tbody>

                            </table>

                        </form>

                    </div>

                </div>

                <span style="color:#fff;"><?php echo ($count); ?></span>

                <div class="push10"></div>

            </div>

        </div>
    </div>
    <!-- fim Portlet -->
</div>

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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>

<script type="text/javascript">
    //Barra de pesquisa essentials ------------------------------------------------------
    $(document).ready(function(e) {
        var value = $('#INPUT').val().toLowerCase().trim();
        if (value) {
            $('#CLEARDIV').show();
        } else {
            $('#CLEARDIV').hide();
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#", "");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #VAL_PESQUISA').val(param);
            $('#INPUT').focus();
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
        });

        $('#CLEAR').click(function() {
            $('#INPUT').val('');
            $('#INPUT').focus();
            $('#CLEARDIV').hide();
            if ("<?= $filtro ?>" != "") {
                location.reload();
            } else {
                var value = $('#INPUT').val().toLowerCase().trim();
                if (value) {
                    $('#CLEARDIV').show();
                } else {
                    $('#CLEARDIV').hide();
                }
                $(".buscavel tr").each(function(index) {
                    if (!index) return;
                    $(this).find("td").each(function() {
                        var id = $(this).text().toLowerCase().trim();
                        var sem_registro = (id.indexOf(value) == -1);
                        $(this).closest('tr').toggle(!sem_registro);
                        return sem_registro;
                    });
                });
            }
        });

        // $('#SEARCH').click(function(){
        // 	$('#formulario').submit();
        // });


    });

    function buscaRegistro(el) {
        var filtro = $('#search_concept').text().toLowerCase();

        if (filtro == "sem filtro") {
            var value = $(el).val().toLowerCase().trim();
            if (value) {
                $('#CLEARDIV').show();
            } else {
                $('#CLEARDIV').hide();
            }
            $(".buscavel tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var sem_registro = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!sem_registro);
                    return sem_registro;
                });
            });
        }
    }

    //-----------------------------------------------------------------------------------

    $(document).ready(function() {

        //chosen
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();

        var SPMaskBehavior = function(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

        $('.sp_celphones').mask(SPMaskBehavior, spOptions);

        $('#formulario input').keydown(function(e) {
            if (e.keyCode == 13) {
                var inputs = $(this).parents("#formulario").eq(0).find(":input");
                if (inputs[inputs.index(this) + 1] != null) {
                    inputs[inputs.index(this) + 1].focus();
                }
                e.preventDefault();
                return false;
            }
        });

        $(".exportarCSV").click(function() {
            $.confirm({
                title: 'Exportação',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Insira o nome do arquivo:</label>' +
                '<input type="text" placeholder="Nome" class="nome form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gerar',
                        btnClass: 'btn-blue',
                        action: function() {
                            var nome = this.$content.find('.nome').val();
                            if (!nome) {
                                $.alert('Por favor, insira um nome');
                                return false;
                            }

                            $.confirm({
                                title: 'Mensagem',
                                type: 'green',
                                icon: 'fa fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "ajxUnidades.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log('media/excel/' + fileName);
                                        console.log(response);
                                    }).fail(function() {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                    });
                                },
                                buttons: {
                                    fechar: function() {
                                        //close
                                    }
                                }
                            });
                        }
                    },
                    cancelar: function() {
                        //close
                    },
                }
            });
        });

    });

    function retornaForm(index) {
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val());
        $("#formulario #NOM_UNIVEND").val($("#ret_NOM_UNIVEND_" + index).val());
        $("#formulario #NOM_RESPONS").val($("#ret_NOM_RESPONS_" + index).val());
        $("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_" + index).val());
        if ($("#ret_LOG_ESTATUS_" + index).val() == 'S') {
            $('#formulario #LOG_ESTATUS').prop('checked', true);
        } else {
            $('#formulario #LOG_ESTATUS').prop('checked', false);
        }
        if ($("#ret_LOG_DELIVERY_" + index).val() == 'S') {
            $('#formulario #LOG_DELIVERY').prop('checked', true);
        } else {
            $('#formulario #LOG_DELIVERY').prop('checked', false);
        }
        if ($("#ret_LOG_ESPECIAL_" + index).val() == 'S') {
            $('#formulario #LOG_ESPECIAL').prop('checked', true);
        } else {
            $('#formulario #LOG_ESPECIAL').prop('checked', false);
        }
        if ($("#ret_LOG_UNIPREF_" + index).val() == 'S') {
            $('#formulario #LOG_UNIPREF').prop('checked', true);
        } else {
            $('#formulario #LOG_UNIPREF').prop('checked', false);
        }
        <?php if ($_SESSION[SYS_COD_EMPRESA] == 2 || $_SESSION[SYS_COD_EMPRESA] == 3) { ?>
            if ($("#ret_LOG_COBRANCA_" + index).val() == 'S') {
                $('#formulario #LOG_COBRANCA').prop('checked', true);
            } else {
                $('#formulario #LOG_COBRANCA').prop('checked', false);
            }
        <?php } else { ?>
            $('#formulario #LOG_COBRANCA').val($("#ret_LOG_COBRANCA_" + index).val());
        <?php } ?>
        $("#formulario #NUM_ESCRICA").val($("#ret_NUM_ESCRICA_" + index).val());
        $("#formulario #NOM_FANTASI").val($("#ret_NOM_FANTASI_" + index).val());
        $("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_" + index).val());
        $("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_" + index).val());
        $("#formulario #NUM_WHATSAPP").val($("#ret_NUM_WHATSAPP_" + index).val());
        $("#formulario #DES_HORATEND").val($("#ret_DES_HORATEND_" + index).val());
        $("#formulario #DES_ENDEREC").val($("#ret_DES_ENDEREC_" + index).val());
        $("#formulario #NUM_ENDEREC").val($("#ret_NUM_ENDEREC_" + index).val());
        $("#formulario #DES_COMPLEM").val($("#ret_DES_COMPLEM_" + index).val());
        $("#formulario #DES_BAIRROC").val($("#ret_DES_BAIRROC_" + index).val());
        $("#formulario #NUM_CEPOZOF").val($("#ret_NUM_CEPOZOF_" + index).val());
        $("#formulario #NOM_CIDADEC").val($("#ret_NOM_CIDADEC_" + index).val());
        $("#formulario #COD_BANDEIRA").val($("#ret_COD_BANDEIRA_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_ESTADOF").val($("#ret_COD_ESTADOF_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_TPUNIVE").val($("#ret_COD_TPUNIVE_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_PROPRIEDADE").val($("#ret_COD_PROPRIEDADE_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
        $("#formulario #COD_FANTASI").val($("#ret_COD_FANTASI_" + index).val());
        $("#formulario #NOM_EMAIL").val($("#ret_NOM_EMAIL_" + index).val());
        $("#formulario #COD_TIPOREG").val($("#ret_COD_TIPOREG_" + index).val()).trigger("chosen:updated");
        if ($("#ret_LOG_ATIVOHS_" + index).val() == 'S') {
            $('#formulario #LOG_ATIVOHS').prop('checked', true);
        } else {
            $('#formulario #LOG_ATIVOHS').prop('checked', false);
        }
        $("#formulario #lat").val($("#ret_lat_" + index).val());
        $("#formulario #lng").val($("#ret_lng_" + index).val());

        if ($("#ret_LOG_STATUS_" + index).val() == 'S') {
            $('#formulario #LOG_STATUS').prop('checked', true);
        } else {
            $('#formulario #LOG_STATUS').prop('checked', false);
        }
        $("#formulario #COD_INTEGRADORA").val($("#ret_COD_INTEGRADORA_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_VERSAOINTEGRA").val($("#ret_COD_VERSAOINTEGRA_" + index).val()).trigger("chosen:updated");
        $("#formulario #NUM_DECIMAIS").val($("#ret_NUM_DECIMAIS_" + index).val()).trigger("chosen:updated");
        $("#formulario #TIP_RETORNO").val($("#ret_TIP_RETORNO_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_DATAWS").val($("#ret_COD_DATAWS_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_TPUNIVE").val($("#ret_COD_TPUNIVE_" + index).val()).trigger("chosen:updated");
        $("#formulario #LOG_CADVENDEDOR").val($("#ret_LOG_CADVENDEDOR_" + index).val()).trigger("chosen:updated");

        $("#blocoCandidato").fadeIn("fast",function(){
            $("#btn_candidato").attr("data-url","action.php?mod=<?php echo fnEncode(1819) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idu="+$("#ret_COD_UNIVEND_URL_" + index).val()+"&pop=true");
        });


        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>