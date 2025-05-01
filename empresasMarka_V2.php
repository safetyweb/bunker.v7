<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$qtd_doc = 0;
$req_senha = '';
$hashLocal = mt_rand();
$cod_empresa = fnDecode($_GET['id']);
$opcao = @$_REQUEST['opcao'];

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $opcao <> "") {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
        $nom_empresa = fnLimpacampo($_REQUEST['NOM_EMPRESA']);
        $des_abrevia = fnLimpacampo($_REQUEST['DES_ABREVIA']);
        $nom_respons = fnLimpacampo($_REQUEST['NOM_RESPONS']);
        $num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
        $des_sufixo = str_replace(" ", "", fnAcentos(fnLimpacampo($_REQUEST['DES_SUFIXO'])));
        $cod_status = fnLimpacampo($_REQUEST['COD_ESTATUS']);
        if (empty($_REQUEST['LOG_ATIVO'])) {
            $log_ativo = 'N';
        } else {
            $log_ativo = $_REQUEST['LOG_ATIVO'];
        }
        if (empty($_REQUEST['LOG_PRECUNI'])) {
            $log_precuni = 'N';
        } else {
            $log_precuni = $_REQUEST['LOG_PRECUNI'];
        }
        if (empty($_REQUEST['LOG_ESTOQUE'])) {
            $log_estoque = 'N';
        } else {
            $log_estoque = $_REQUEST['LOG_ESTOQUE'];
        }
        if (empty($_REQUEST['LOG_CONFIGU'])) {
            $log_configu = 'N';
        } else {
            $log_configu = $_REQUEST['LOG_CONFIGU'];
        }
        if (empty($_REQUEST['TIP_REGVENDA'])) {
            $tip_regvenda = '1';
        } else {
            $tip_regvenda = $_REQUEST['TIP_REGVENDA'];
        }
        $tip_contabil = fnLimpacampo($_REQUEST['TIP_CONTABIL']);
        $num_escrica = fnLimpacampo($_REQUEST['NUM_ESCRICA']);
        $nom_fantasi = fnLimpacampo($_REQUEST['NOM_FANTASI']);
        $num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
        $num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
        $des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
        $num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
        $des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
        $des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
        $num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
        $nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
        $cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);
        $tip_retorno = fnLimpacampo($_REQUEST['TIP_RETORNO']);
        $tip_header = fnLimpacampo($_REQUEST['TIP_HEADER']);
        $des_alinham = fnLimpacampo($_REQUEST['DES_ALINHAM']);
        $des_logo = fnLimpaCampo($_REQUEST['DES_LOGO']);
        $des_imgback = fnLimpaCampo($_REQUEST['DES_IMGBACK']);
        $cod_plataforma = fnLimpacampoZero($_REQUEST['COD_PLATAFORMA']);
        $cod_versaointegra = fnLimpacampoZero($_REQUEST['COD_VERSAOINTEGRA']);
        $qtd_chartkn = fnLimpacampoZero($_REQUEST['QTD_CHARTKN']);
        $tip_token = fnLimpacampoZero($_REQUEST['TIP_TOKEN']);
        $num_idademin = fnLimpacampoZero($_REQUEST['NUM_IDADEMIN']);
        $pct_parceiro = fnLimpacampo($_REQUEST['PCT_PARCEIRO']);
        $tip_estorno = fnLimpacampoZero($_REQUEST['TIP_ESTORNO']);

        $filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
        $val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

        //array dos sistemas da empresas
        if (isset($_POST['COD_SISTEMAS'])) {
            $Arr_COD_SISTEMAS = $_POST['COD_SISTEMAS'];
            //print_r($Arr_COD_SISTEMAS);			 

            for ($i = 0; $i < count($Arr_COD_SISTEMAS); $i++) {
                @$cod_sistemas .= $Arr_COD_SISTEMAS[$i] . ",";
            }

            $cod_sistemas = substr($cod_sistemas, 0, -1);
        } else {
            $cod_sistemas = "";
        }
        $cod_master = fnLimpacampo($_REQUEST['COD_MASTER']);
        $cod_layout = fnLimpacampo($_REQUEST['COD_LAYOUT']);
        $cod_segment = fnLimpacampo($_REQUEST['COD_SEGMENT']);
        $tip_senha = fnLimpacampoZero($_REQUEST['TIP_SENHA']);
        $min_senha = fnLimpacampoZero($_REQUEST['MIN_SENHA']);
        $max_senha = fnLimpacampoZero($_REQUEST['MAX_SENHA']);
        $tip_envio = fnLimpacampoZero($_REQUEST['TIP_ENVIO']);

        if (isset($_REQUEST['REQ_SENHA'])) {
            $req_senha = fnLimpaArray($_REQUEST['REQ_SENHA']);
        }

        if (empty($_REQUEST['LOG_CONSEXT'])) {
            $log_consext = 'N';
        } else {
            $log_consext = $_REQUEST['LOG_CONSEXT'];
        }
        if (empty($_REQUEST['LOG_AUTOCAD'])) {
            $log_autocad = 'N';
        } else {
            $log_autocad = $_REQUEST['LOG_AUTOCAD'];
        }
        if (empty($_REQUEST['LOG_TOKEN'])) {
            $log_token = 'N';
        } else {
            $log_token = $_REQUEST['LOG_TOKEN'];
        }
        if (empty($_REQUEST['LOG_CADTOKEN'])) {
            $log_cadtoken = 'N';
        } else {
            $log_cadtoken = $_REQUEST['LOG_CADTOKEN'];
        }
        $cod_chaveco = fnLimpacampo($_REQUEST['COD_CHAVECO']);
        $tip_campanha = fnLimpacampo($_REQUEST['TIP_CAMPANHA']);

        if (empty($_REQUEST['LOG_INTEGRADORA'])) {
            $log_integradora = 'N';
        } else {
            $log_integradora = $_REQUEST['LOG_INTEGRADORA'];
        }
        $des_patharq = fnLimpacampo($_REQUEST['DES_PATHARQ']);
        $cod_integradora = fnLimpacampoZero($_REQUEST['COD_INTEGRADORA']);
        $site = fnLimpacampo($_REQUEST['SITE']);
        $des_coment = fnLimpacampo($_REQUEST['DES_COMENT']);

        $num_decimais = fnLimpacampo($_REQUEST['NUM_DECIMAIS']);
        $num_decimais_b = fnLimpacampo($_REQUEST['NUM_DECIMAIS_B']);
        $cod_dataws = fnLimpacampoZero($_REQUEST['COD_DATAWS']);

        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = $_GET['mod'];
        $COD_MODULO = fndecode($_GET['mod']);
        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];

        if (empty($_REQUEST['DAT_PRODUCAO'])) {
            $dat_producao = 'NULL';
        } else {
            $dat_producao = "'" . fnDataSql($_REQUEST['DAT_PRODUCAO']) . "'";
        }
        $cod_consultor = fnLimpacampoZero($_REQUEST['COD_CONSULTOR']);
        if (empty($_REQUEST['LOG_WS'])) {
            $log_ws = 'N';
        } else {
            $log_ws = $_REQUEST['LOG_WS'];
        }

        if (empty($_REQUEST['LOG_PONTUAR'])) {
            $log_pontuar = 'N';
        } else {
            $log_pontuar = $_REQUEST['LOG_PONTUAR'];
        }
        if (empty($_REQUEST['LOG_ATIVCAD'])) {
            $log_ativcad = 'N';
        } else {
            $log_ativcad = $_REQUEST['LOG_ATIVCAD'];
        }
        if (empty($_REQUEST['LOG_AVULSO'])) {
            $log_avulso = 'N';
        } else {
            $log_avulso = $_REQUEST['LOG_AVULSO'];
        }
        if (empty($_REQUEST['LOG_CATEGORIA'])) {
            $log_categoria = 'N';
        } else {
            $log_categoria = $_REQUEST['LOG_CATEGORIA'];
        }
        if (empty($_REQUEST['LOG_ALTVENDA'])) {
            $log_altvenda = 'N';
        } else {
            $log_altvenda = $_REQUEST['LOG_ALTVENDA'];
        }
        if (empty($_REQUEST['LOG_QUALICAD'])) {
            $log_qualicad = 'N';
        } else {
            $log_qualicad = $_REQUEST['LOG_QUALICAD'];
        }
        $log_cadvendedor = fnLimpacampoZero($_REQUEST['LOG_CADVENDEDOR']);
        if (empty($_REQUEST['LOG_PDVMANU'])) {
            $log_pdvmanu = '0';
        } else {
            $log_pdvmanu = $_REQUEST['LOG_PDVMANU'];
        }
        if (empty($_REQUEST['LOG_CREDAVULSO'])) {
            $log_credavulso = 'N';
        } else {
            $log_credavulso = $_REQUEST['LOG_CREDAVULSO'];
        }

        if (empty($_REQUEST['LOG_ALTERAHS'])) {
            $log_alterahs = 'N';
        } else {
            $log_alterahs = $_REQUEST['LOG_ALTERAHS'];
        }

        if (empty($_REQUEST['LOG_NEGATIVO'])) {
            $log_negativo = 'N';
        } else {
            $log_negativo = $_REQUEST['LOG_NEGATIVO'];
        }
        if (empty($_REQUEST['LOG_BLOQUEIAPJ'])) {
            $log_bloqueiapj = 'N';
        } else {
            $log_bloqueiapj = $_REQUEST['LOG_BLOQUEIAPJ'];
        }
        if (empty($_REQUEST['LOG_TKTUNIVEND'])) {
            $log_tktunivend = 'N';
        } else {
            $log_tktunivend = $_REQUEST['LOG_TKTUNIVEND'];
        }
        //ALTERADO POR LUCAS, REFERENTE AO CHAMADO 6045
        if (empty($_REQUEST['LOG_DAT_NASCIME'])) {
            $log_dat_nascime = 'N';
        } else {
            $log_dat_nascime = $_REQUEST['LOG_DAT_NASCIME'];
        }
        if (empty($_REQUEST['LOG_RECUPERA'])) {
            $log_recupera = 'N';
        } else {
            $log_recupera = $_REQUEST['LOG_RECUPERA'];
        }

        //fnEscreve($log_ativo);

        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
        //echo $dat_producao;
        if ($opcao != '') {

            $sql = "CALL SP_ALTERA_EMPRESAS_FULL (
            '" . $cod_empresa . "', 
            '" . $cod_cadastr . "', 
            '" . $nom_empresa . "', 
            '" . $des_abrevia . "', 
            '" . $nom_respons . "', 
            '" . fnLimpaDoc($num_cgcecpf) . "', 
            '" . $log_ativo . "', 
            '" . $cod_status . "', 
            '" . $num_escrica . "', 
            '" . $nom_fantasi . "', 
            '" . $num_telefon . "', 
            '" . $num_celular . "', 
            '" . $des_enderec . "', 
            '" . $num_enderec . "', 
            '" . $des_complem . "', 
            '" . $des_bairroc . "', 				 
            '" . $num_cepozof . "',				 
            '" . $nom_cidadec . "',    
            '" . $cod_estadof . "',    
            '" . $cod_sistemas . "',    
            '" . $cod_master . "',    
            '" . $cod_layout . "',  
            '" . $log_precuni . "',    
            '" . $log_estoque . "',    
            '" . $cod_segment . "',    
            '" . $des_sufixo . "', 
            '" . $log_consext . "', 
            '" . $log_autocad . "', 
            '" . $cod_chaveco . "', 
            '" . $tip_contabil . "', 
            '" . $log_configu . "', 
            '" . $log_integradora . "', 
            '" . $des_patharq . "', 
            '" . $cod_integradora . "', 
            '" . $site . "', 
            '" . $des_coment . "', 
            '" . $tip_regvenda . "', 
            '" . $num_decimais . "', 
            '" . $num_decimais_b . "', 
            " . $dat_producao . ", 
            '" . $cod_consultor . "', 
            '" . $log_ws . "', 
            '" . $log_pontuar . "', 
            '" . $tip_retorno . "', 
            '" . $tip_header . "', 
            '" . $des_alinham . "', 
            '" . $des_logo . "',
            '" . $des_imgback . "',
            '" . $log_ativcad . "',  
            '" . $log_avulso . "',  
            '" . $log_categoria . "',  
            '" . $log_altvenda . "',  
            '" . $cod_dataws . "', 
            '" . $cod_plataforma . "', 
            '" . $cod_versaointegra . "', 
            '" . $log_qualicad . "', 
            '" . $log_cadvendedor . "', 
            '" . $log_pdvmanu . "', 
            '" . $log_credavulso . "', 
            '" . $log_token . "', 
            '" . $log_cadtoken . "', 
            '" . $log_negativo . "', 
            '" . $log_recupera . "', 
            '" . fnValorsql($pct_parceiro) . "',
            '" . $tip_campanha . "', 
            '" . $qtd_chartkn . "', 
            '" . $tip_token . "', 
            '" . $num_idademin . "', 
            '" . $log_bloqueiapj . "', 
            '" . $log_tktunivend . "', 
            '" . $tip_senha . "', 
            '" . $min_senha . "', 
            '" . $max_senha . "', 
            '" . $req_senha . "', 
            '" . $tip_envio . "', 
            '" . $tip_estorno . "', 
            '" . $log_dat_nascime . "', 
            '" . $log_alterahs . "',
            '" . $opcao . "'    
        ) ";
            // fnEscreve($sql);
            $arrayProc = mysqli_query($adm, trim($sql));

            //mensagem de retorno
            if (!$arrayProc) {
                $cod_erro = Log_error_comand($adm, connTemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
            }

            if (@$cod_erro == 0 || @$cod_erro == "") {
                $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
            } else {
                $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
            }
            if (@$cod_erro == 0 || @$cod_erro == "") {
                $msgTipo = 'alert-success';
            } else {
                $msgTipo = 'alert-danger';
            }
        }
    }
} else {

    $sql = "SELECT 
    STATUSSISTEMA.DES_STATUS,
    empresas.*,
    (select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
    (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,
    (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
    (SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
    B.COD_DATABASE, 
    B.NOM_DATABASE 
    FROM empresas 
    LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
    LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
    WHERE empresas.COD_EMPRESA = $cod_empresa
    ORDER by NOM_FANTASI
    ";


    //fnEscreve($sql);
    $arrayQuery = mysqli_query($adm, $sql);
    $qrListaEmpresas = mysqli_fetch_assoc($arrayQuery);
    foreach ($qrListaEmpresas as $campo => $valor) {
        $campo = strtolower($campo);
        $$campo = $valor;
        //echo "$campo = $valor<br>";
    }

    $sqlDatabase = "SELECT 1 FROM tab_database WHERE cod_empresa=$cod_empresa";
    $query = mysqli_query($adm, $sqlDatabase);

    if ($query->num_rows > 0) {
        $sqlDocs = "SELECT 1 FROM DOCUMENTOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa";
        $qtd_doc = mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ""), $sqlDocs));
    }
}

//fnMostraForm();
//fnEscreve($filtro);


?>
<style>
    table a:not(.btn),
    .table a:not(.btn) {
        text-decoration: none;
    }

    table a:not(.btn):hover,
    .table a:not(.btn):hover {
        text-decoration: underline;
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
                        <?php echo $NomePg; ?>
                    </span>
                </div>
                <?php include "atalhosPortlet.php"; ?>
            </div>
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert"
                        id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <div class="push30"></div>

                <div class="login-form">
                    <form data-toggle="validator" role="form2" method="POST" id="formulario"
                        action="<?php echo $cmdPage ?>">
                        <fieldset>
                            <legend>Configurações de Acesso</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Empresa <br />Ativa </label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_ativo == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Consulta Automática<br /> de
                                            Cadastro (Externa)</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CONSEXT" id="LOG_CONSEXT"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_consext == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cadastro Automático <br />de
                                            Clientes</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_AUTOCAD" id="LOG_AUTOCAD"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_autocad == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="disabledBlock"></div>
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Controle de <br />Preço por Loja
                                        </label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_PRECUNI" id="LOG_PRECUNI"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_precuni == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="disabledBlock"></div>
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Controla <br />Estoque </label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ESTOQUE" id="LOG_ESTOQUE"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_estoque == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <!---->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Empresa <br />Integradora </label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_INTEGRADORA" id="LOG_INTEGRADORA"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_integradora == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="disabledBlock"></div>
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Banco de <br />Dados </label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="COD_DATABASE" id="COD_DATABASE"
                                                class="switch switch-small" value="S"
                                                <?= (@$cod_database > 0 ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Ativar<br />Log WS</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_WS" id="LOG_WS" class="switch switch-small"
                                                value="S" <?= (@$log_ws == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="disabledBlock"></div>
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Set Up<br />Completo</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CONFIGU" id="LOG_CONFIGU"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_configu == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Pontuar <br />Funcionários</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_PONTUAR" id="LOG_PONTUAR"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_pontuar == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Pontuar após<br />ativação de
                                            cadastro</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ATIVCAD" id="LOG_ATIVCAD"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_ativcad == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Permitir<br />venda avulsa</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_AVULSO" id="LOG_AVULSO"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_avulso == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Possui categorização<br />de
                                            clientes</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CATEGORIA" id="LOG_CATEGORIA"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_categoria == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Permite alteração<br />de
                                            venda</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ALTVENDA" id="LOG_ALTVENDA"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_altvenda == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Controle de Qualidade<br />de
                                            Cadastros</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_QUALICAD" id="LOG_QUALICAD"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_qualicad == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Créditos manuais<br />no PDV
                                            virtual</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_PDVMANU" id="LOG_PDVMANU"
                                                class="switch switch-small" value="1"
                                                <?= (@$log_pdvmanu == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Crédito<br />Avulso</label>
                                        <div class="push5"></div>
                                        <input type="hidden" class="form-control input-sm" name="LOG_CREDAVULSOTOUR" id="LOG_CREDAVULSOTOUR" maxlength="9">
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CREDAVULSO" id="LOG_CREDAVULSO"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_credavulso == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Permite<br />Saldo
                                            Negativo?</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_NEGATIVO" id="LOG_NEGATIVO"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_negativo == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cadastro<br />com Token</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CADTOKEN" id="LOG_CADTOKEN"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_cadtoken == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Resgate<br />com Token</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_TOKEN" id="LOG_TOKEN"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_token == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Recuperação de Senha<br />com
                                            Token</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_RECUPERA" id="LOG_RECUPERA"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_recupera == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Não Permitir<br />PJ</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_BLOQUEIAPJ" id="LOG_BLOQUEIAPJ"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_bloqueiapj == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">TO<br />por Unidades</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_TKTUNIVEND" id="LOG_TKTUNIVEND"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_tktunivend == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Ativar Controle <br />Data de Nascimento</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_DAT_NASCIME" id="LOG_DAT_NASCIME"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_dat_nascime == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bloquear Alteração de <br />Cliente Hotsite</label>
                                        <div class="push5"></div>
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ALTERAHS" id="LOG_ALTERAHS"
                                                class="switch switch-small" value="S"
                                                <?= (@$log_alterahs == "S" ? "checked" : "") ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly"
                                            name="ID" id="ID" value="<?= @$cod_empresa ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome da Empresa</label>
                                        <input type="text" class="form-control input-sm" name="NOM_EMPRESA"
                                            id="NOM_EMPRESA" maxlength="100" data-error="Campo obrigatório"
                                            value="<?= @$nom_empresa ?>" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome Fantasia</label>
                                        <input type="text" class="form-control input-sm" name="NOM_FANTASI"
                                            id="NOM_FANTASI" maxlength="40" data-error="Campo obrigatório"
                                            value="<?= @$nom_fantasi ?>" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Abreviação</label>
                                        <input type="text" class="form-control input-sm" name="DES_ABREVIA"
                                            id="DES_ABREVIA" maxlength="5" data-error="Campo obrigatório"
                                            value="<?= @$des_abrevia ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Idade Mínima do
                                            Programa</label>
                                        <select data-placeholder="Selecione um status" name="NUM_IDADEMIN"
                                            id="NUM_IDADEMIN" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <option value="0" <?= (@$num_idademin == "0" ? "selected" : "") ?>>Todas as
                                                Idades</option>
                                            <option value="18" <?= (@$num_idademin == "18" ? "selected" : "") ?>>18+
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Status</label>
                                        <select data-placeholder="Selecione um status" name="COD_ESTATUS"
                                            id="COD_ESTATUS" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php

                                            $sql = "SELECT * FROM STATUSSISTEMA ORDER BY DES_STATUS ";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                                        <option value='" . $qrLista['COD_STATUS'] . "' " . (@$cod_status == $qrLista['COD_STATUS'] ? "selected" : "") . ">" . $qrLista['DES_STATUS'] . "</option> 
                                        ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Sufixo da Empresa</label>
                                        <input type="text" class="form-control input-sm" name="DES_SUFIXO"
                                            id="DES_SUFIXO" maxlength="100" data-error="Campo obrigatório"
                                            value="<?= @$des_sufixo ?>" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Chave
                                            Identificação</label>
                                        <select data-placeholder="Selecione a chave de identificação" name="COD_CHAVECO"
                                            id="COD_CHAVECO" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php

                                            if ($_SESSION["SYS_COD_MASTER"] == "2") {
                                                $sql = "select * from CHAVECADASTRO order by DES_CHAVECO
                                    ";
                                            } else {
                                                $sql = "select * from CHAVECADASTRO where COD_CHAVECO <> 6 order by DES_CHAVECO
                                    ";
                                            }

                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrListaChaveCad = mysqli_fetch_assoc($arrayQuery)) {

                                                echo "
                                    <option value='" . $qrListaChaveCad['COD_CHAVECO'] . "' " . (@$cod_chaveco == $qrListaChaveCad['COD_CHAVECO'] ? "selected" : "") . ">" . $qrListaChaveCad['DES_CHAVECO'] . "</option> 
                                    ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Tipo Principal de
                                            Campanhas</label>
                                        <select data-placeholder="Selecione um tipo de vantagem" name="TIP_CAMPANHA"
                                            id="TIP_CAMPANHA" class="chosen-select-deselect requiredChk" required>
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $sql = "select * from TIPOCAMPANHA order by NUM_ORDENAC ";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrListaVantagem = mysqli_fetch_assoc($arrayQuery)) {

                                                if ($qrListaVantagem['LOG_ATIVO'] == 'N') {
                                                    $desabilitado = "disabled";
                                                } else {
                                                    $desabilitado = "";
                                                }

                                                echo "
                                <option value='" . $qrListaVantagem['COD_TPCAMPA'] . "' " . $desabilitado . " " . (@$tip_campanha == $qrListaVantagem['COD_TPCAMPA'] ? "selected" : "") . ">" . $qrListaVantagem['NOM_TPCAMPA'] . "</option> 
                                ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Contabilização do
                                            Resgate</label>
                                        <select data-placeholder="Selecione a forma de contabilização"
                                            name="TIP_CONTABIL" id="TIP_CONTABIL" class="chosen-select-deselect"
                                            required>
                                            <option value=""></option>
                                            <option value="DESC" <?= (@$tip_contabil == "DESC" ? "selected" : "") ?>>
                                                Como desconto</option>
                                            <option value="RESG" <?= (@$tip_contabil == "RESG" ? "selected" : "") ?>>
                                                Forma de pagamento (resgate)</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Regra de Entrada de Venda</label>
                                        <select data-placeholder="Tipo da entrada de venda" name="TIP_REGVENDA"
                                            id="TIP_REGVENDA" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="1" <?= (@$tip_regvenda == "1" ? "selected" : "") ?>>Crítica
                                                Padrão</option>
                                            <option value="2" <?= (@$tip_regvenda == "2" ? "selected" : "") ?>>Permitir
                                                data/hora iguais</option>
                                            <option value="3" <?= (@$tip_regvenda == "3" ? "selected" : "") ?>>Permitir
                                                PDV iguais</option>
                                            <option value="4" <?= (@$tip_regvenda == "4" ? "selected" : "") ?>>Permitir
                                                PDV iguais se Loja for Diferente</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Casas Decimais</label>
                                        <select data-placeholder="Selecione um decimal" name="NUM_DECIMAIS"
                                            id="NUM_DECIMAIS" class="chosen-select-deselect requiredChk" required>
                                            <option value="2" <?= (@$num_decimais == "2" ? "selected" : "") ?>>2
                                            </option>
                                            <option value="3" <?= (@$num_decimais == "3" ? "selected" : "") ?>>3
                                            </option>
                                            <option value="4" <?= (@$num_decimais == "4" ? "selected" : "") ?>>4
                                            </option>
                                            <option value="5" <?= (@$num_decimais == "5" ? "selected" : "") ?>>5
                                            </option>

                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Casas
                                            Decimais(Bunker)</label>
                                        <select data-placeholder="Selecione um decimal" name="NUM_DECIMAIS_B"
                                            id="NUM_DECIMAIS_B" class="chosen-select-deselect requiredChk" required>
                                            <option value="0" <?= (@$num_decimais_b == "0" ? "selected" : "") ?>>0
                                            </option>
                                            <option value="2" <?= (@$num_decimais_b == "2" ? "selected" : "") ?>>2
                                            </option>
                                            <option value="3" <?= (@$num_decimais_b == "3" ? "selected" : "") ?>>3
                                            </option>
                                            <option value="4" <?= (@$num_decimais_b == "4" ? "selected" : "") ?>>4
                                            </option>
                                            <option value="5" <?= (@$num_decimais_b == "5" ? "selected" : "") ?>>5
                                            </option>

                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Visualização / Retorno
                                        </label>
                                        <select data-placeholder="Selecione um tipo de visualização dos retornos"
                                            name="TIP_RETORNO" id="TIP_RETORNO"
                                            class="chosen-select-deselect requiredChk" required>
                                            <option value=""></option>
                                            <option value="1" <?= (@$tip_retorno == "1" ? "selected" : "") ?>>Valor
                                                inteiro</option>
                                            <option value="2" <?= (@$tip_retorno == "2" ? "selected" : "") ?>>Valor
                                                decimal</option>
                                        </select>
                                        <div class="help-block with-errors">webservices/relatórios</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Formato de Data </label>
                                        <select data-placeholder="Selecione um formato de data" name="COD_DATAWS"
                                            id="COD_DATAWS" class="chosen-select-deselect requiredChk" required>
                                            <option value=""></option>
                                            <?php

                                            $sql = "select * from DATAWS order by COD_DATAWS";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrListaTipoData = mysqli_fetch_assoc($arrayQuery)) {

                                                echo "
            <option value='" . $qrListaTipoData['COD_DATAWS'] . "' " . (@$cod_dataws == $qrListaTipoData['COD_DATAWS'] ? "selected" : "") . ">" . $qrListaTipoData['FORMATO_WEB'] . "</option> 
            ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors">entrada de webservices</div>
                                    </div>
                                </div>
                                <?php $limpaData = $dat_proLimpa = str_replace("'", "", $dat_producao); ?>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Produção</label>

                                        <div class="input-group date datePicker" id="DAT_PRODUCAO_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_PRODUCAO"
                                                id="DAT_PRODUCAO" value="<?= fnDateRetorno($dat_proLimpa) ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Integradora</label>
                                        <select data-placeholder="Selecione a integradora" name="COD_INTEGRADORA"
                                            id="COD_INTEGRADORA" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php

                                            $sql = "select * from empresas where COD_EMPRESA <> 1 and LOG_INTEGRADORA = 'S' order by NOM_FANTASI";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrListaIntegradora = mysqli_fetch_assoc($arrayQuery)) {

                                                echo "
            <option value='" . $qrListaIntegradora['COD_EMPRESA'] . "' " . (@$cod_integradora == $qrListaIntegradora['COD_EMPRESA'] ? "selected" : "") . ">" . $qrListaIntegradora['NOM_FANTASI'] . "</option> 
            ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Consultor</label>
                                        <select data-placeholder="Selecione um consultor" name="COD_CONSULTOR"
                                            id="COD_CONSULTOR" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php

                                            $sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
            where usuarios.COD_EMPRESA = 3
            and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                <option value='" . $qrLista['COD_USUARIO'] . "' " . (@$cod_consultor == $qrLista['COD_USUARIO'] ? "selected" : "") . ">" . $qrLista['NOM_USUARIO'] . "</option> 
                ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Segmento</label>
                                        <select data-placeholder="Selecione um segmento" name="COD_SEGMENT"
                                            id="COD_SEGMENT" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php

                                            $sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
            <option value='" . $qrLista['COD_SEGMENT'] . "' " . (@$cod_segment == $qrLista['COD_SEGMENT'] ? "selected" : "") . ">" . $qrLista['NOM_SEGMENT'] . "</option> 
            ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <!--sistema-->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Sistemas</label>
                                        <select data-placeholder="Selecione um sistema" name="COD_SISTEMAS[]"
                                            id="COD_SISTEMAS" multiple="multiple"
                                            class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1"
                                            required>
                                            <?php

                                            if ($_SESSION["SYS_COD_MASTER"] == "2") {

                                                $sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS";
                                            } else {

                                                $sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = 3 ";
                                                $arrayQuery = mysqli_query($adm, $sql);
                                                $qrBuscaSistema = mysqli_fetch_assoc($arrayQuery);
                                                $sistemasMarka = $qrBuscaSistema['COD_SISTEMAS'];

                                                $sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN (" . $sistemasMarka . ") order by DES_SISTEMA ";
                                            }
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery)) {
                                                if ($qrListaSistemas['COD_SISTEMA'] == 'S') {
                                                    $mostraAutoriza = '<i class="fal fa-check" aria-hidden="true"></i>';
                                                } else {
                                                    $mostraAutoriza = '';
                                                }

                                                echo "
            <option value='" . $qrListaSistemas['COD_SISTEMA'] . "' " . (in_array($qrListaSistemas['COD_SISTEMA'], explode(",", @$cod_sistemas)) ? "selected" : "") . ">" . $qrListaSistemas['DES_SISTEMA'] . "</option> 
            ";
                                            }

                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Plataforma</label>
                                        <select class="chosen-select-deselect requiredChk"
                                            data-placeholder="Selecione a plataforma" name="COD_PLATAFORMA"
                                            id="COD_PLATAFORMA">
                                            <option value=""></option>
                                            <?php

                                            $sql = "SELECT * FROM SAC_PLATAFORMA";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrPlataforma = mysqli_fetch_assoc($arrayQuery)) {
                                            ?>
                                                <option value="<?php echo $qrPlataforma['COD_PLATAFORMA']; ?>"
                                                    <?= (@$cod_plataforma == $qrPlataforma['COD_PLATAFORMA'] ? "selected" : "") ?>>
                                                    <?php echo $qrPlataforma['DES_PLATAFORMA']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Versão da
                                            Integração</label>
                                        <select class="chosen-select-deselect requiredChk"
                                            data-placeholder="Selecione a versão" name="COD_VERSAOINTEGRA"
                                            id="COD_VERSAOINTEGRA">
                                            <option value=""></option>
                                            <?php

                                            $sql = "SELECT * FROM SAC_VERSAOINTEGRA";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrIntegracao = mysqli_fetch_assoc($arrayQuery)) {
                                            ?>
                                                <option value="<?php echo $qrIntegracao['COD_VERSAOINTEGRA']; ?>"
                                                    <?= (@$cod_versaointegra == $qrIntegracao['COD_VERSAOINTEGRA'] ? "selected" : "") ?>>
                                                    <?php echo $qrIntegracao['DES_VERSAOINTEGRA']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Informação do Vendedor</label>
                                        <select class="chosen-select-deselect requiredChk"
                                            data-placeholder="Selecione a origem da informação" name="LOG_CADVENDEDOR"
                                            id="LOG_CADVENDEDOR">
                                            <option value=""></option>
                                            <option value="1" <?= (@$log_cadvendedor == "1" ? "selected" : "") ?>>tag
                                                dados login</option>
                                            <option value="2" <?= (@$log_cadvendedor == "2" ? "selected" : "") ?>>tag
                                                venda</option>

                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Qtd. Caracteres do Token</label>
                                        <select data-placeholder="Selecione a quantidade" name="QTD_CHARTKN"
                                            id="QTD_CHARTKN" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="6" <?= (@$qtd_chartkn == "6" ? "selected" : "") ?>>6</option>
                                            <option value="8" <?= (@$qtd_chartkn == "8" ? "selected" : "") ?>>8</option>
                                            <option value="10" <?= (@$qtd_chartkn == "10" ? "selected" : "") ?>>10
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Tipo de Token</label>
                                        <select data-placeholder="Selecione o tipo" name="TIP_TOKEN" id="TIP_TOKEN"
                                            class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="1" <?= (@$tip_token == "1" ? "selected" : "") ?>>Alfanumérico
                                            </option>
                                            <option value="2" <?= (@$tip_token == "2" ? "selected" : "") ?>>Numérico
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Token Senha APP</label>
                                        <select data-placeholder="Selecione o tipo" name="TIP_ENVIO" id="TIP_ENVIO"
                                            class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="1" <?= (@$tip_envio == "1" ? "selected" : "") ?>>SMS</option>
                                            <option value="2" <?= (@$tip_envio == "2" ? "selected" : "") ?>>E-mail
                                            </option>
                                            <option value="3" <?= (@$tip_envio == "3" ? "selected" : "") ?>>SMS e E-mail
                                            </option>
                                        </select>
                                        <div class="help-block with-errors">Canal de recuperação de senha</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Tipo de Senha</label>
                                        <select data-placeholder="Selecione o tipo" name="TIP_SENHA" id="TIP_SENHA"
                                            class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="1" <?= (@$tip_senha == "1" ? "selected" : "") ?>>Alfanumérico
                                            </option>
                                            <option value="2" <?= (@$tip_senha == "2" ? "selected" : "") ?>>Numérico
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Min. Senha</label>
                                        <input type="text" class="form-control input-sm" name="MIN_SENHA" id="MIN_SENHA"
                                            maxlength="10" data-error="Campo obrigatório" value="<?= @$min_senha ?>">
                                        <div class="help-block with-errors">Nro. Caracteres</div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Max. Senha</label>
                                        <input type="text" class="form-control input-sm" name="MAX_SENHA" id="MAX_SENHA"
                                            maxlength="10" data-error="Campo obrigatório" value="<?= @$max_senha ?>">
                                        <div class="help-block with-errors">Nro. Caracteres</div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Requisitos de senha</label>
                                        <select data-placeholder="Selecione um ou mais requisitos" name="REQ_SENHA[]"
                                            id="REQ_SENHA" multiple="multiple"
                                            class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
                                            <option value="1"
                                                <?= (in_array('1', explode(",", @$req_senha)) ? "selected" : "") ?>>
                                                Mínimo
                                                de caracteres</option>
                                            <option value="2"
                                                <?= (in_array('2', explode(",", @$req_senha)) ? "selected" : "") ?>>
                                                Letra
                                                maiúscula</option>
                                            <option value="3"
                                                <?= (in_array('3', explode(",", @$req_senha)) ? "selected" : "") ?>>
                                                Número
                                            </option>
                                            <option value="4"
                                                <?= (in_array('4', explode(",", @$req_senha)) ? "selected" : "") ?>>
                                                Caracter especial</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Regras de Estorno</label>
                                        <select data-placeholder="Selecione o tipo" name="TIP_ESTORNO" id="TIP_ESTORNO"
                                            class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="0" <?= (@$tip_estorno == "0" ? "selected" : "") ?>>Crítica
                                                Padrão</option>
                                            <option value="1" <?= (@$tip_estorno == "1" ? "selected" : "") ?>>Ignorar
                                                Unidade de Estorno</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Dados da Empresa</legend>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Responsável</label>
                                        <input type="text" class="form-control input-sm" name="NOM_RESPONS"
                                            id="NOM_RESPONS" maxlength="40" data-error="Campo obrigatório"
                                            value="<?= @$nom_respons ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CNPJ/CPF</label>
                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF"
                                            id="NUM_CGCECPF" maxlength="18" data-error="Campo obrigatório"
                                            value="<?= @$num_cgcecpf ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Inscrição Estadual</label>
                                        <input type="text" class="form-control input-sm" name="NUM_ESCRICA"
                                            id="NUM_ESCRICA" maxlength="20" data-error="Campo obrigatório"
                                            value="<?= @$num_escrica ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Telefone Principal</label>
                                        <input type="text" class="form-control input-sm" name="NUM_TELEFON"
                                            id="NUM_TELEFON" maxlength="20" value="<?= @$num_telefon ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Telefone Celular</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CELULAR"
                                            id="NUM_CELULAR" maxlength="20" value="<?= @$num_celular ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Endereço</label>
                                        <input type="text" class="form-control input-sm" name="DES_ENDEREC"
                                            id="DES_ENDEREC" maxlength="40" value="<?= @$des_enderec ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Número</label>
                                        <input type="text" class="form-control input-sm" name="NUM_ENDEREC"
                                            id="NUM_ENDEREC" maxlength="10" value="<?= @$num_enderec ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Complemento</label>
                                        <input type="text" class="form-control input-sm" name="DES_COMPLEM"
                                            id="DES_COMPLEM" maxlength="20" value="<?= @$des_complem ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bairro</label>
                                        <input type="text" class="form-control input-sm" name="DES_BAIRROC"
                                            id="DES_BAIRROC" maxlength="20" value="<?= @$des_bairroc ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CEP</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CEPOZOF"
                                            id="NUM_CEPOZOF" maxlength="9" value="<?= @$num_cepozof ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cidade</label>
                                        <input type="text" class="form-control input-sm" name="NOM_CIDADEC"
                                            id="NOM_CIDADEC" maxlength="40" value="<?= @$nom_cidadec ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADOF"
                                            id="COD_ESTADOF" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="AC" <?= (@$cod_estadof == "AC" ? "selected" : "") ?>>AC
                                            </option>
                                            <option value="AL" <?= (@$cod_estadof == "AL" ? "selected" : "") ?>>AL
                                            </option>
                                            <option value="AM" <?= (@$cod_estadof == "AM" ? "selected" : "") ?>>AM
                                            </option>
                                            <option value="AP" <?= (@$cod_estadof == "AP" ? "selected" : "") ?>>AP
                                            </option>
                                            <option value="BA" <?= (@$cod_estadof == "BA" ? "selected" : "") ?>>BA
                                            </option>
                                            <option value="CE" <?= (@$cod_estadof == "CE" ? "selected" : "") ?>>CE
                                            </option>
                                            <option value="DF" <?= (@$cod_estadof == "DF" ? "selected" : "") ?>>DF
                                            </option>
                                            <option value="ES" <?= (@$cod_estadof == "ES" ? "selected" : "") ?>>ES
                                            </option>
                                            <option value="GO" <?= (@$cod_estadof == "GO" ? "selected" : "") ?>>GO
                                            </option>
                                            <option value="MA" <?= (@$cod_estadof == "MA" ? "selected" : "") ?>>MA
                                            </option>
                                            <option value="MG" <?= (@$cod_estadof == "MG" ? "selected" : "") ?>>MG
                                            </option>
                                            <option value="MS" <?= (@$cod_estadof == "MS" ? "selected" : "") ?>>MS
                                            </option>
                                            <option value="MT" <?= (@$cod_estadof == "MT" ? "selected" : "") ?>>MT
                                            </option>
                                            <option value="PA" <?= (@$cod_estadof == "PA" ? "selected" : "") ?>>PA
                                            </option>
                                            <option value="PB" <?= (@$cod_estadof == "PB" ? "selected" : "") ?>>PB
                                            </option>
                                            <option value="PE" <?= (@$cod_estadof == "PE" ? "selected" : "") ?>>PE
                                            </option>
                                            <option value="PI" <?= (@$cod_estadof == "PI" ? "selected" : "") ?>>PI
                                            </option>
                                            <option value="PR" <?= (@$cod_estadof == "PR" ? "selected" : "") ?>>PR
                                            </option>
                                            <option value="RJ" <?= (@$cod_estadof == "RJ" ? "selected" : "") ?>>RJ
                                            </option>
                                            <option value="RN" <?= (@$cod_estadof == "RN" ? "selected" : "") ?>>RN
                                            </option>
                                            <option value="RO" <?= (@$cod_estadof == "RO" ? "selected" : "") ?>>RO
                                            </option>
                                            <option value="RR" <?= (@$cod_estadof == "RR" ? "selected" : "") ?>>RR
                                            </option>
                                            <option value="RS" <?= (@$cod_estadof == "RS" ? "selected" : "") ?>>RS
                                            </option>
                                            <option value="SC" <?= (@$cod_estadof == "SC" ? "selected" : "") ?>>SC
                                            </option>
                                            <option value="SE" <?= (@$cod_estadof == "SE" ? "selected" : "") ?>>SE
                                            </option>
                                            <option value="SP" <?= (@$cod_estadof == "SP" ? "selected" : "") ?>>SP
                                            </option>
                                            <option value="TO" <?= (@$cod_estadof == "TO" ? "selected" : "") ?>>TO
                                            </option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Site</label>
                                        <input type="text" class="form-control input-sm" name="SITE" id="SITE"
                                            maxlength="100" value="<?= @$site ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <div class="push20"></div>
                                        <a class="btn btn-info btn-block btn-sm" href="javascript:void(0)"
                                            target='_blank' id="BTN_DOC"><span
                                                class="fal fa-file-alt"></span>&nbsp;(<span id="QTD_DOC">
                                                <?= fnLimpaCampoZero($qtd_doc) ?>
                                            </span>)</a>
                                    </div>
                                </div>

                                <div class="col-md-11">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Comentário</label>
                                        <textarea class="form-control input-sm" rows="1" name="DES_COMENT"
                                            id="DES_COMENT"><?= $des_coment ?></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Personalização</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Layout do Sistema</label>
                                        <select data-placeholder="Selecione uma skin" name="COD_LAYOUT" id="COD_LAYOUT"
                                            class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php

                                            $sql = "select COD_LAYOUT, DES_LAYOUT from LAYOUTS order by DES_LAYOUT";
                                            $arrayQuery = mysqli_query($adm, $sql);

                                            while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                    <option value='" . $qrLayout['COD_LAYOUT'] . "' " . (@$cod_layout == $qrLayout['COD_LAYOUT'] ? "selected" : "") . ">" . $qrLayout['DES_LAYOUT'] . "</option> 
                    ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Tipo do Bloco</label>
                                        <select data-placeholder="Selecione o tipo do do bloco" name="TIP_HEADER"
                                            id="TIP_HEADER" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="wide" <?= (@$tip_header == "wide" ? "selected" : "") ?>>Wide
                                            </option>
                                            <option value="boxed" <?= (@$tip_header == "boxed" ? "selected" : "") ?>>
                                                Boxed</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Alinhamento do Logo</label>
                                        <select data-placeholder="Selecione um alinhamento" name="DES_ALINHAM"
                                            id="DES_ALINHAM" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <option value="left" <?= (@$des_alinham == "left" ? "selected" : "") ?>>
                                                Esquerda</option>
                                            <option value="center" <?= (@$des_alinham == "center" ? "selected" : "") ?>>
                                                Centro</option>
                                            <option value="right" <?= (@$des_alinham == "right" ? "selected" : "") ?>>
                                                Direita</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label">Logotipo</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;"
                                                class="btn btn-primary upload" idinput="DES_LOGO" extensao="img"><i
                                                    class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="text" name="DES_LOGO" id="DES_LOGO" class="form-control input-sm"
                                            style="border-radius: 0 3px 3px  0;" maxlength="100"
                                            value="<?php echo $des_logo; ?>">
                                    </div>
                                    <span class="help-block">(.png 300px X 80px)</span>
                                </div>

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label">Imagem de Fundo</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;"
                                                class="btn btn-primary upload" idinput="DES_IMGBACK" extensao="img"><i
                                                    class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="text" name="DES_IMGBACK" id="DES_IMGBACK"
                                            class="form-control input-sm" style="border-radius: 0 3px 3px  0;"
                                            maxlength="100" value="<?php echo $des_imgback; ?>">
                                    </div>
                                    <span class="help-block">(.jpg 1400px X 600px)</span>
                                </div>


                            </div>

                        </fieldset>

                        <?php
                        if ($_SESSION["SYS_COD_MASTER"] == "2") {
                        ?>

                            <div class="push10"></div>

                            <fieldset style="background: #F4F6F6;">
                                <legend>Dados Master</legend>

                                <div class="row">

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Avulso</label>
                                            <input type="text" class="form-control input-sm leitura" readonly="readonly"
                                                name="COD_CLIENTE_AV" id="COD_CLIENTE_AV" value="<?= @$cod_cliente_av ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Empresa Master</label>
                                            <select data-placeholder="Selecione uma empresa" name="COD_MASTER"
                                                id="COD_MASTER" class="chosen-select-deselect" required>
                                                <option value=""></option>
                                                <?php

                                                $sql = "select COD_EMPRESA, NOM_EMPRESA from empresas where COD_EMPRESA IN (1,2,3) order by NOM_EMPRESA";
                                                $arrayQuery = mysqli_query($adm, $sql);

                                                while ($qrListaEempresas = mysqli_fetch_assoc($arrayQuery)) {

                                                    echo "
                        <option value='" . $qrListaEempresas['COD_EMPRESA'] . "' " . (@$cod_master == $qrListaEempresas['COD_EMPRESA'] ? "selected" : "") . ">" . $qrListaEempresas['NOM_EMPRESA'] . "</option> 
                        ";
                                                }
                                                ?>
                                            </select>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Path Arquivos</label>
                                            <input type="text" class="form-control input-sm" name="DES_PATHARQ"
                                                id="DES_PATHARQ" maxlength="250" value="<?= @$des_patharq ?>">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Porcentagem do Parceiro</label>
                                            <input type="text" class="form-control input-sm money" name="PCT_PARCEIRO"
                                                id="PCT_PARCEIRO" value="<?= @$pct_parceiro ?>">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                            </fieldset>

                        <?php
                        } else {
                        ?>

                            <input type="hidden" name="DES_PATHARQ" id="DES_PATHARQ" value="">
                            <input type="hidden" name="COD_MASTER" id="COD_MASTER" value="3">
                            <input type="hidden" name="COD_CLIENTE_AV" id="COD_CLIENTE_AV" value="">
                            <input type="hidden" name="PCT_PARCEIRO" id="PCT_PARCEIRO" value="0">

                        <?php
                        }
                        ?>


                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i
                                    class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i
                                    class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

                        </div>



                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push5"></div>

                    </form>

                    <div class="push20"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
    $("#ALT").click(function(e) {
        e.preventDefault();
        $.confirm({
            title: "Confirmar Alteração",
            content: "Deseja Realmente fazer <b style='color:red'>ALTERAÇÕES</b> na empresa <b style='color:red'><?= $cod_empresa . ' - ' . $nom_fantasi; ?></b> ?",
            type: 'red',
            buttons: {
                "ALTERAR": {
                    btnClass: 'btn-success',
                    action: function() {
                        $("#formulario").submit();
                    }
                },
                "CANCELAR": {
                    btnClass: 'btn-default',
                    action: function() {}
                }
            }
        });
    });

    $("#CAD").click(function(e) {
        e.preventDefault();
        var nomFantasi = $("#NOM_FANTASI").val();
        $.confirm({
            title: "Confirmar CADASTRO",
            content: "Deseja realmente <b style='color:red'>CADASTRAR</b> uma nova empresa <b style='color:red'>'" +
                nomFantasi +
                "'</b> ?<br><br>Obs: Para visualizar a nova empresa cadastrada, favor solicitar a liberação para o seu usuário.",
            type: 'red',
            buttons: {
                "CADASTRAR": {
                    btnClass: 'btn-success',
                    action: function() {
                        $("#formulario").submit();
                    }
                },
                "CANCELAR": {
                    btnClass: 'btn-default',
                    action: function() {}
                }
            }
        });
    });


    $(document).ready(function() {
        // Inicializar o datepicker
        $('.datePicker').datetimepicker({
            format: 'DD/MM/YYYY'
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        // Validator para campos obrigatórios
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();

        // Exportar para CSV
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
                                icon: 'fal fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "ajxExportaEmpresas.do?opcao=exportar&nomeRel=" + nome,
                                        data: $('#formLista2').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '3_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
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
                    }
                }
            });
        });

        // Impedir colagem no campo específico
        let campo = document.querySelector('#DES_COMPLEM');
        campo.addEventListener("paste", function(e) {
            e.preventDefault();
        });

        // Busca de registros
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

        // Upload de arquivos
        $('.upload').on('click', function(e) {
            var idField = 'arqUpload_' + $(this).attr('idinput');
            var typeFile = $(this).attr('extensao');
            $.dialog({
                title: 'Arquivo',
                content: '' +
                    '<form method="POST" enctype="multipart/form-data">' +
                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                    '<div class="progress" style="display: none">' +
                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
                    '<span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                    '</div>' +
                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                    '</form>'
            });
        });

        // Função de upload de arquivos
        function uploadFile(idField, typeFile) {
            var formData = new FormData();
            var nomeArquivo = $('#' + idField)[0].files[0]['name'];
            formData.append('arquivo', $('#' + idField)[0].files[0]);
            formData.append('diretorio', '../media/clientes/');
            formData.append('id', 123); // Substituir pelo valor correto
            formData.append('typeFile', typeFile);
            $('.progress').show();
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    $('#btnUploadFile').addClass('disabled');
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            if (percentComplete !== 100) {
                                $('.progress-bar').css('width', percentComplete + "%");
                                $('.progress-bar > span').html(percentComplete + "%");
                            }
                        }
                    }, false);
                    return xhr;
                },
                url: '../uploads/uploaddoc.php',
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(data) {
                    $('.jconfirm-open').fadeOut(300, function() {
                        $(this).remove();
                    });
                    if (!data.trim()) {
                        $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                        $.alert({
                            title: "Mensagem",
                            content: "Upload feito com sucesso",
                            type: 'green'
                        });
                    } else {
                        $.alert({
                            title: "Erro ao efetuar o upload",
                            content: data,
                            type: 'red'
                        });
                    }
                }
            });
        }
    });
</script>