    <?php

    //echo fnDebug('true');

    $hashLocal = mt_rand();
    $itens_por_pagina = 50;
    $pagina = 1;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $request = md5(implode($_POST));

        if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
            $msgRetorno = 'Essa página já foi utilizada!';
            $msgTipo = 'alert-warning';
        } else {
            $_SESSION['last_request']  = $request;
            $filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
            $val_pesquisa = fnLimpaCampo($_POST['INPUT']);

            $cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
            $cod_grupoent = fnLimpaCampoZero($_REQUEST['COD_GRUPOENT']);
            $cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
            $qtd_membros = fnLimpaCampoZero($_REQUEST['QTD_MEMBROS']);
            $cod_tpentid = fnLimpaCampoZero($_REQUEST['COD_TPENTID']);
            $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
            $nom_entidad = fnLimpaCampo($_REQUEST['NOM_ENTIDAD']);
            $num_cgcecpf = fnLimpaCampoZero(LIMPA_DOC($_REQUEST['NUM_CGCECPF']));
            $des_enderc = fnLimpaCampo($_REQUEST['DES_ENDERC']);
            $num_enderec = fnLimpaCampo($_REQUEST['NUM_ENDEREC']);
            $des_bairroc = fnLimpaCampo($_REQUEST['DES_BAIRROC']);
            $num_cepozof = fnLimpaCampo(LIMPA_DOC($_REQUEST['NUM_CEPOZOF']));
            $cod_estado = fnLimpaCampoZero($_REQUEST['COD_ESTADO']);
            $cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);
            $num_telefone = fnLimpaCampo(LIMPA_DOC($_REQUEST['NUM_TELEFONE']));
            $num_celular = fnLimpaCampo(LIMPA_DOC($_REQUEST['NUM_CELULAR']));
            $email = fnLimpaCampo($_REQUEST['EMAIL']);
            $nom_respon = fnLimpaCampo($_REQUEST['NOM_RESPON']);
            $cod_regiao = fnLimpaCampoZero  ($_REQUEST['COD_REGIAO']);
            
            if (empty($_REQUEST['LOG_STATUS'])) {$log_status='N';}else{$log_status=$_REQUEST['LOG_STATUS'];}

            $opcao = $_REQUEST['opcao'];
            $hHabilitado = $_REQUEST['hHabilitado'];
            $hashForm = $_REQUEST['hashForm'];

            $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

            if (isset($_POST['COD_USUARIO_RESP'])) {

                $Arr_COD_USUARIO_RESP = $_POST['COD_USUARIO_RESP'];

                for ($i = 0; $i < count($Arr_COD_USUARIO_RESP); $i++) {
					$cod_responsavel = $cod_responsavel . $Arr_COD_USUARIO_RESP[$i] . ",";
				}

                $cod_responsavel = substr($cod_responsavel, 0, -1);
            }else{
                $cod_responsavel = 0;
            }

            if (isset($_POST['COD_CLIENTE_MULT'])) {
                $Arr_APOIADORES_ENV = $_POST['COD_CLIENTE_MULT'];
    
                for ($i = 0; $i < count($Arr_APOIADORES_ENV); $i++) {
                    $apoiadores_envolvidos = $apoiadores_envolvidos . $Arr_APOIADORES_ENV[$i] . ",";
                }
                $apoiadores_envolvidos = substr($apoiadores_envolvidos, 0, -1);
            } else {
                $apoiadores_envolvidos = "0";
            }

            //echo $cod_responsavel;


            if ($opcao != '') {

                //     $sql = "CALL SP_ALTERA_ENTIDADE (
                //     '".$cod_entidad."', 
                //     '".$cod_grupoent."',
                //     '".$cod_tpentid."',
                //     '".$cod_empresa."', 
                //     '".$cod_conveni."', 
                //     '".$nom_entidad."', 
                //     '".$num_cgcecpf."', 
                //     '".$des_enderc."', 
                //     '".$num_enderec."', 
                //     '".$des_bairroc."',
                //     '".$num_cepozof."',
                //     '".$cod_municipio."',
                //     '".$cod_estado."',
                //     '".$num_telefone."',
                //     '".$num_celular."',
                //     '".$email."',
                //     '".$nom_respon."',
                //     '".$cod_usucada."',
                //     '".$qtd_membros."',
                //     '".$opcao."'    
                // );";

                $sql = "CALL SP_ALTERA_ENTIDADE (
                    '" . $cod_entidad . "', 
                    '" . $cod_grupoent . "',
                    '" . $cod_tpentid . "',
                    '" . $cod_empresa . "', 
                    '0', 
                    '" . $nom_entidad . "', 
                    '" . $num_cgcecpf . "', 
                    '" . $des_enderc . "', 
                    '" . $num_enderec . "', 
                    '" . $des_bairroc . "',
                    '" . $num_cepozof . "',
                    '" . $cod_municipio . "',
                    '" . $cod_estado . "',
                    '" . $num_telefone . "',
                    '" . $num_celular . "',
                    '" . $email . "',
                    '" . $nom_respon . "',
                    '" . $cod_usucada . "',
                    '" . $qtd_membros . "',
                    '" . $cod_responsavel. "',
                    '" . $cod_regiao . "',
                    '" . $apoiadores_envolvidos . "',
                    '" . $log_status . "',
                    '" . $opcao . "'    
                    
                );";

                mysqli_query(connTemp($cod_empresa, ''), $sql);
                //echo $sql; 

                //mensagem de retorno
                switch ($opcao) {
                    case 'CAD':
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                        break;
                    case 'ALT':
                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                        break;
                    case 'EXC':
                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
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
        $sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

        //fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
        $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

        if (isset($qrBuscaEmpresa)) {
            $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
        }
    } else {
        $nom_empresa = "";
    }

    if ($val_pesquisa != "") {
        $esconde = " ";
    } else {
        $esconde = "display: none;";
    }



    if (isset($_GET['idC'])) {
        if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {

            //busca dados do convÃªnio
            $cod_conveni = fnLimpaCampoZero(fnDecode($_GET['idC']));
            $sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = " . $cod_conveni;

            //fnEscreve($sql);
            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
            $qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

            if (isset($qrBuscaTemplate)) {
                $nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
            }
        }
    } else {
        $cod_conveni = 0;
    }

    if (isset($_GET['idE'])) {
        $cod_entidad = fnLimpaCampoZero(fnDecode($_GET['idE']));

        $sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad AND COD_EMPRESA = $cod_empresa";
        //fnEscreve($sql);

        $arrayEntidad = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $qrEntidad = mysqli_fetch_assoc($arrayEntidad);
        if (isset($qrEntidad)) {
            $cod_entidad = $qrEntidad['COD_ENTIDAD'];
            $cod_conveni = $qrEntidad['COD_CONVENI'];
            $cod_grupoent = $qrEntidad['COD_GRUPOENT'];
            $cod_tpentid = $qrEntidad['COD_TPENTID'];
            $nom_entidad = $qrEntidad['NOM_ENTIDAD'];
            $num_cgcecpf = $qrEntidad['NUM_CGCECPF'];
            $des_enderc = $qrEntidad['DES_ENDERC'];
            $num_enderc = $qrEntidad['NUM_ENDERC'];
            $des_bairroc = $qrEntidad['DES_BAIRROC'];
            $cod_estado = $qrEntidad['COD_ESTADO'];
            $cod_municipio = $qrEntidad['COD_MUNICIPIO'];
            $num_cepozof = $qrEntidad['NUM_CEPOZOF'];
            $num_telefone = $qrEntidad['NUM_TELEFONE'];
            $num_celular = $qrEntidad['NUM_CELULAR'];
            $email = $qrEntidad['EMAIL'];
            $nom_respon = $qrEntidad['NOM_RESPON'];
            $qtd_membros = $qrEntidad['QTD_MEMBROS'];
            $cod_externo = $qrEntidad['COD_EXTERNO'];
        } else {
            $cod_entidad = "";
            $cod_grupoent = "";
            $cod_tpentidad = "";
            $nom_entidad = "";
            $num_cgcecpf = "";
            $num_cepozof = "";
            $des_enderc = "";
            $num_enderc = "";
            $des_bairroc = "";
            $cod_estado = "";
            $cod_municipio = "";
            $num_telefone = "";
            $num_celular = "";
            $email = "";
            $nom_respon = "";
            $qtd_membros = "";
            $cod_externo = "";
        }
    }

    include "labelLibrary.php";

    //fnMostraForm();
    //fnEscreve($cod_entidad);
    //fnEscreve($cod_empresa);

    if ($filtro != "") {
        $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
    } else {
        $andFiltro = " ";
    }

    if($log_status == 'S'){
        $checkInativos = "checked";
		$andStatus = "AND LOG_STATUS = 'S'";
	}else{
        $checkInativos = "checked";
		$andStatus = "";
	}
    ?>
    <!--<style>
    #ESSENTIALS {
        position: relative;
        left: 350px;
    }
</style>-->
    <?php if ($popUp != "true") {  ?>
        <div class="push30"></div>
    <?php } ?>

    <div class="row">

        <!-- Portlet -->
        <?php if ($popUp != "true") {  ?>
            <div class="portlet portlet-bordered">
            <?php } else { ?>
                <div class="portlet" style="padding: 0 20px 20px 20px;">
                <?php } ?>

                <?php if ($popUp != "true") {  ?>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fal fa-terminal"></i>
                            <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                        </div>
                        <?php include "atalhosPortlet.php"; ?>
                    </div>
                <?php } ?>
                <div class="portlet-body">

                    <?php if ($msgRetorno <> '') { ?>
                        <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $msgRetorno; ?>
                        </div>
                    <?php } ?>

                    <?php
                    // if(isset($_GET['idE'])){

                    if ($popUp != "true") {

                        $abaEmpresa = 1075;

                        switch ($_SESSION["SYS_COD_SISTEMA"]) {
                            case 14: //rede duque
                                include "abasEmpresaDuque.php";
                                break;
                            case 15: //quiz
                                include "abasEmpresaQuiz.php";
                                break;
                            case 16: //gabinete
                                include "abasGabinete.php";
                                break;
                            case 18: //mais cash
                                include "abasMaisCash.php";
                                break;
                            case 19: //rh
                                include "abasRH.php";
                                break;
                            default;
                                include "abasGabinete.php";
                                // include "abasEmpresaConfig.php";
                                break;
                        }
                    }

                    // }else{

                    //     if ($popUp != "true"){

                    //         $abaConvenio = 1075;                                        
                    //         include "abasConvenio.php"; 

                    //     }

                    // }

                    $sqlfiltro = "SELECT des_filtro FROM filtros_cliente
                WHERE cod_empresa=$cod_empresa AND 
                cod_tpfiltro=28 AND 
                cod_filtro IN(

                  SELECT cod_regitra FROM entidade_grupo
                  WHERE cod_empresa=$cod_empresa AND 
                  cod_grupoent=$cod_grupoent
              )";

                    $resultadosql = mysqli_query(connTemp($cod_empresa, ''), $sqlfiltro);
                    while ($resultset = mysqli_fetch_assoc($resultadosql)) {
                        $resultado = $resultset['des_filtro'];
                    }
                    if ($_SESSION["SYS_COD_SISTEMA"] == 12) {
                    ?>
                    <?php
                    }
                    ?>

                    <div class="push20"></div>

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ENTIDAD" id="COD_ENTIDAD" value="<?= $cod_entidad ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-1">   
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Status</label> 
                                        <div class="push5"></div>
                                            <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_STATUS" id="LOG_STATUS" class="switch" value="S" <?=$checkInativos?>>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="push10"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Código Externo</label>
                                        <input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="60" value="<?= $cod_externo ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Tipo da Entidade</label>
                                        <select data-placeholder="Selecione uma entidade" name="COD_TPENTID" id="COD_TPENTID" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <?php
                                            $sql = "select * from TIPOENTIDADE order by cod_tpentid ";
                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                            while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                                                <option value='" . $qrListaTipoEntidade['COD_TPENTID'] . "'>" . $qrListaTipoEntidade['DES_TPENTID'] . "</option> 
                                                ";
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            $("#formulario #COD_TPENTID").val("<?php echo $cod_tpentid; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nome da Entidade</label>
                                        <input type="text" class="form-control input-sm" name="NOM_ENTIDAD" id="NOM_ENTIDAD" maxlength="100" value="<?= $nom_entidad ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CPF/CNPJ</label>
                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nome do Responsável</label>
                                        <input type="text" class="form-control input-sm" name="NOM_RESPON" id="NOM_RESPON" maxlength="60" value="<?= $nom_respon ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Endereço</label>
                                        <input type="text" class="form-control input-sm" name="DES_ENDERC" id="DES_ENDERC" maxlength="60" value="<?= $des_enderc ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Número</label>
                                        <input type="text" class="form-control input-sm" name="NUM_ENDEREC" id="NUM_ENDEREC" maxlength="20" value="<?= $num_enderec ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bairro</label>
                                        <input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" maxlength="20" value="<?= $des_bairroc ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CEP</label>
                                        <input type="text" class="form-control input-sm cep" name="NUM_CEPOZOF" id="NUM_CEPOZOF" maxlength="10" value="<?= $num_cepozof ?>" data-mask="00000-000">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php

                                            $sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
                                            $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
                                            ?>
                                                <option value="<?= $qrEstado['COD_ESTADO'] ?>"><?= $qrEstado['UF'] ?></option>
                                            <?php
                                            }

                                            ?>
                                        </select>
                                        <script>
                                            $("#formulario #COD_ESTADO").val("<?php echo $cod_estado; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-xs-3" id="relatorioCidade">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cidade</label>
                                        <select data-placeholder="Selecione uma cidade" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect">
                                            <option value=""></option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Telefone</label>
                                        <input type="text" class="form-control input-sm fone" name="NUM_TELEFONE" id="NUM_TELEFONE" maxlength="11" value="<?= $num_telefone ?>" data-mask="(00) 0000-0000">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Celular</label>
                                        <input type="text" class="form-control input-sm celular" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="11" value="<?= $num_celular ?>" data-mask="(00) 00000-0000">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">E-mail</label>
                                        <input type="text" class="form-control input-sm" name="EMAIL" id="EMAIL" maxlength="60" value="<?= $email ?>">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <?php if ($cod_empresa == 136 || $cod_empresa == 311) {  ?>

                                    <div class="col-md-2" id="retornaDados">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Agrupador da Entidade</label>
                                            <select onchange="agrupaEntidade(this.value)" data-placeholder="Selecione o agrupador da entidade" name="COD_GRUPOENT" id="COD_GRUPOENT" class="chosen-select-deselect">
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT COD_GRUPOENT,DES_GRUPOENT FROM Entidade_Grupo WHERE COD_EMPRESA = $cod_empresa ";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                while ($qrListaGrupoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                                    echo "
                                                    <option value='" . $qrListaGrupoEntidade['COD_GRUPOENT'] . "'>" . $qrListaGrupoEntidade['DES_GRUPOENT'] . "</option> 
                                                    ";
                                                }
                                                ?>
                                            </select>
                                            <script>
                                                $("#formulario #COD_TPENTID").val("<?php echo $cod_tpentid; ?>").trigger("chosen:updated");
                                            </script>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($cod_empresa == 136){
                                ?>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Região de Trabalho</label>
                                            <div id="campogrupo"></div>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Qtd. Membros</label>
                                            <input type="text" class="form-control input-sm int" name="QTD_MEMBROS" id="QTD_MEMBROS" maxlength="11" value="<?= $qtd_membros ?>">
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                            </div>
                            <div class="push10"></div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Responsável Gabinete</label>
                                        <select data-placeholder="Selecione o Resposável" name="COD_USUARIO_RESP[]" id="COD_USUARIO_RESP" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
                                            <?php

                                            $sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
                                                        where COD_EMPRESA = $cod_empresa AND usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                            while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                                                    <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
                                                ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Região</label>
                                        <select data-placeholder="Selecione o Resposável" name="COD_REGIAO" id="COD_REGIAO" class="chosen-select-deselect" style="width:100%;" tabindex="1">
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT COD_REGIAO,DES_REGIAO FROM REGIAO";
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                                                    <option value='" . $qrLista['COD_REGIAO'] . "'>" . $qrLista['DES_REGIAO'] . "</option> 
                                                ";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label required"><?=$envolvidos?> Envolvidos</label>
                                    <div class="input-group" id="relatorioApoiadores">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&op=AGE&pop=true" data-title="Busca <?=$cliente?>"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
                                        </span>
                                        <select data-placeholder="Nenhum Selecionado" name="COD_CLIENTE_MULT[]" id="COD_CLIENTE_MULT" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">

                                        </select>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <?php
                                        }
                                ?>

                        

                <div class="push10"></div>

                </fieldset>

                <div class="push10"></div>
                <hr>
                <div class="form-group text-right col-lg-12">

                    <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                    <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                    <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

                </div>

                <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                <input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?= $cod_conveni ?>">
                <input type="hidden" name="AND_FILTRO" id="AND_FILTRO" value="<?= fnEncode($andFiltro) ?>">
                <input type="hidden" name="AND_STATUS" id="AND_STATUS" value="<?= $log_status ?>">
                <input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
                <input type="hidden" name="COD_CLIENTE_ENV" id="COD_CLIENTE_ENV" value="">
                <input type="hidden" name="NOM_CLIENTE_ENV" id="NOM_CLIENTE_ENV" value="">
                <input type="hidden" name="opcao" id="opcao" value="">
                <input type="hidden" name="hashForm" id="hashForm" value="<?= $hashLocal; ?>" />
                <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                <div class="push5"></div>

                </form>

                <div class="row">
                    <form name="formLista2" id="formLista2" method="post" action="<?= $cmdPage; ?>">
                        <div class="container">
                            <div class="col-xs-4 col-xs-offset-4">
                                <div class="input-group activeItem">
                                    <div class="input-group-btn search-panel">
                                        <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                            <span id="search_concept">Sem filtro</span>&nbsp;
                                            <span class="fal fa-angle-down"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="divisor"><a href="#">Sem filtro</a></li>
                                            <!-- <li class="divider"></li> -->
                                            <li><a href="#entidade.NOM_ENTIDAD">Nome da Entidade</a></li>
                                            <li><a href="#entidade.COD_ENTIDAD">Código</a></li>
                                            <li><a href="#A.DES_GRUPOENT">Agrupador da Entidade</a></li>

                                            <!--<li><a href="#entidade.NOM_CIDADES">Agrupador Entidade</a></li>-->
                                        </ul>
                                    </div>
                                    <input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
                                    <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
                                    <div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
                                        <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                    </div>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="push10"></div>
    
    <div class="portlet portlet-bordered">
        
        <div class="portlet-body">
            
            <div class="login-form">

                <div class="push20"></div>

                <div class="col-lg-12">

                    <div class="no-more-tables">

                        <table class="table table-bordered table-striped table-hover tablesorter buscavel">
                            <thead>
                                <tr>
                                    <th width="40"></th>
                                    <th>Código</th>
                                    <th>Nome da Entidade</th>
                                    <th>Nome do Responsável</th>
                                    <th>Cidade</th>
                                    <th>Estado</th>

                                </tr>
                            </thead>
                            <tbody id="relatorioConteudo">

                                <?php
                                
                                if ($num_cgcecpf != '') {

                                    $andCpf = 'and num_cgcecpf =' . $num_cgcecpf;
                                } else {
                                    $andCpf = ' ';
                                }

                                if ($cod_conveni != "" && $cod_conveni != "0") {
                                    $andConveni = " AND COD_CONVENI = $cod_conveni ";
                                } else {
                                    $andConveni = " ";
                                }
                                //fnEscreve($andFiltro);

                                $sql = "SELECT 1 from ENTIDADE 
                                left join webtools.empresas ON ENTIDADE.COD_EMPRESA = webtools.empresas.COD_EMPRESA
                                where webtools.empresas.COD_EMPRESA = $cod_empresa
                                $andStatus
                                $andFiltro";
                                //echo $sql;    
                                $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                $totalitens_por_pagina = mysqli_num_rows($retorno);
                                $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                // fnEscreve($numPaginas);

                                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                $sql = "SELECT ENTIDADE.COD_ENTIDAD,
                                                ENTIDADE.COD_GRUPOENT,
                                                ENTIDADE.COD_TPENTID,
                                                ENTIDADE.COD_EXTERNO,
                                                ENTIDADE.COD_EMPRESA,
                                                ENTIDADE.COD_MUNICIPIO,
                                                ENTIDADE.COD_ESTADO,
                                                ENTIDADE.NOM_ENTIDAD,
                                                ENTIDADE.NUM_CGCECPF,
                                                ENTIDADE.DES_ENDERC,
                                                ENTIDADE.NUM_ENDEREC,
                                                ENTIDADE.DES_BAIRROC,
                                                ENTIDADE.NUM_CEPOZOF,
                                                ENTIDADE.NOM_CIDADES,
                                                ENTIDADE.NOM_ESTADOS,
                                                ENTIDADE.NUM_TELEFONE,
                                                ENTIDADE.NUM_CELULAR,
                                                ENTIDADE.EMAIL,
                                                ENTIDADE.NOM_RESPON,
                                                ENTIDADE.QTD_MEMBROS,
                                                TIPOENTIDADE.DES_TPENTID,
                                                EMPRESAS.NOM_EMPRESA,
                                                A.DES_GRUPOENT,
                                                ENTIDADE.COD_USUARIO_RESP,
                                                ENTIDADE.COD_REGIAO,
                                                ENTIDADE.COD_CLIENTE_MULT,
                                                ENTIDADE.LOG_STATUS
                                        from ENTIDADE  
                                        left join webtools.empresas ON ENTIDADE.COD_EMPRESA = webtools.empresas.COD_EMPRESA 
                                        left join webtools.tipoentidade ON entidade.COD_TPENTID = webtools.tipoentidade.COD_TPENTID
                                        left join entidade_grupo A ON A.COD_GRUPOENT = ENTIDADE.COD_GRUPOENT
                                        where webtools.empresas.COD_EMPRESA = $cod_empresa 
                                        $andFiltro
                                        $andStatus
                                        GROUP BY ENTIDADE.COD_ENTIDAD
                                        limit $inicio,$itens_por_pagina";

                                // echo($sql);
                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                $count = 0;
                                while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                    if (strlen($qrBuscaModulos['NUM_CGCECPF']) <= 11) {

                                        $retun = fnCompletaDoc($qrBuscaModulos['NUM_CGCECPF'], "F");
                                        //$retun = str_pad($qrBuscaModulos['NUM_CGCECPF'], 11, '0', STR_PAD_LEFT); // Resultado: 00009
                                    } else {
                                        $retun = fnCompletaDoc($qrBuscaModulos['NUM_CGCECPF'], "J");
                                    }

                                    // $municipio = "";
                                    $estado = "";

                                    // 	if($qrBuscaModulos[COD_MUNICIPIO] != ""){
                                    //  	$sqlCidade = "SELECT NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO = $qrBuscaModulos[COD_MUNICIPIO]";
                                    //  	$arrayMunicipio = mysqli_query(connTemp($cod_empresa,''),$sqlCidade);		
                                    //  	$qrMunicipio = mysqli_fetch_assoc($arrayMunicipio);
                                    //  	$municipio = $qrMunicipio[NOM_MUNICIPIO];
                                    // }
                                    

                                    if ($qrBuscaModulos[COD_ESTADO] != "") {

                                        $sqlEstado = "SELECT UF FROM ESTADO WHERE COD_ESTADO = $qrBuscaModulos[COD_ESTADO]";
                                        $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sqlEstado);
                                        $qrEstado = mysqli_fetch_assoc($arrayEstado);
                                        $estado = $qrEstado[UF];
                                    }

                                    $count++;
                                    echo "
                                <tr>
                                <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                <td>" . $qrBuscaModulos['COD_ENTIDAD'] . "</td>
                                <td>" . $qrBuscaModulos['NOM_ENTIDAD'] . "</td>
                                <td>" . $qrBuscaModulos['NOM_RESPON'] . "</td>
                                <td>" . $qrBuscaModulos['NOM_CIDADES'] . "</td>
                                <td>" . $estado . "</td>    
                                </tr>

                                <input type='hidden' id='ret_COD_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['COD_ENTIDAD'] . "'>
                                <input type='hidden' id='ret_COD_GRUPOENT_" . $count . "' value='" . $qrBuscaModulos['COD_GRUPOENT'] . "'>
                                <input type='hidden' id='ret_COD_CLIENTE_MULT_" . $count . "' value='" . $qrBuscaModulos['COD_CLIENTE_MULT'] . "'>
                                <input type='hidden' id='ret_NOM_CLIENTE_" . $count . "' value='" . $qrBuscaModulos['NOM_CLIENTE'] . "'>
                                <input type='hidden' id='ret_COD_TPENTID_" . $count . "' value='" . $qrBuscaModulos['COD_TPENTID'] . "'>
                                <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
                                <input type='hidden' id='ret_des_filtro_" . $count . "' value='" . $resultset['des_filtro'] . "'>
                                <input type='hidden' id='ret_NOM_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['NOM_ENTIDAD'] . "'>
                                <input type='hidden' id='ret_LOG_STATUS_" . $count . "' value='" . $qrBuscaModulos['LOG_STATUS'] . "'>
                                <input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . $qrBuscaModulos['NUM_CGCECPF'] . "'>
                                <input type='hidden' id='ret_DES_ENDERC_" . $count . "' value='" . $qrBuscaModulos['DES_ENDERC'] . "'>
                                <input type='hidden' id='ret_NUM_ENDEREC_" . $count . "' value='" . $qrBuscaModulos['NUM_ENDEREC'] . "'>
                                <input type='hidden' id='ret_DES_BAIRROC_" . $count . "' value='" . $qrBuscaModulos['DES_BAIRROC'] . "'>
                                <input type='hidden' id='ret_NUM_CEPOZOF_" . $count . "' value='" . $qrBuscaModulos['NUM_CEPOZOF'] . "'>
                                <input type='hidden' id='ret_COD_MUNICIPIO_" . $count . "' value='" . $qrBuscaModulos['COD_MUNICIPIO'] . "'>
                                <input type='hidden' id='ret_NOM_CIDADES_" . $count . "' value='" . $qrBuscaModulos['NOM_CIDADES'] . "'>
                                <input type='hidden' id='ret_COD_ESTADO_" . $count . "' value='" . $qrBuscaModulos['COD_ESTADO'] . "'>
                                <input type='hidden' id='ret_NUM_TELEFONE_" . $count . "' value='" . $qrBuscaModulos['NUM_TELEFONE'] . "'>
                                <input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . $qrBuscaModulos['NUM_CELULAR'] . "'>
                                <input type='hidden' id='ret_EMAIL_" . $count . "' value='" . $qrBuscaModulos['EMAIL'] . "'>
                                <input type='hidden' id='ret_NOM_RESPON_" . $count . "' value='" . $qrBuscaModulos['NOM_RESPON'] . "'>
                                <input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaModulos['COD_EXTERNO'] . "'>
                                <input type='hidden' id='ret_QTD_MEMBROS_" . $count . "' value='" . $qrBuscaModulos['QTD_MEMBROS'] . "'>
                                <input type='hidden' id='ret_COD_USUARIO_RESP_" . $count . "' value='" . $qrBuscaModulos['COD_USUARIO_RESP'] . "'>
                                <input type='hidden' id='ret_COD_REGIAO_" . $count . "' value='" . $qrBuscaModulos['COD_REGIAO'] . "'>
                                ";
                                }
                                ?>

                            </tbody>

                            <tfoot>
                                <div class="push20"></div>
                                <tr>
                                    <th colspan="100">
                                        <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="" colspan="100">
                                        <center>
                                            <ul id="paginacao" class="pagination-sm"></ul>
                                        </center>
                                    </th>
                                </tr>
                            </tfoot>

                        </table>

                        </form>

                    </div>

                </div>

                <div class="push"></div>

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
        //Barra de pesquisa Essentials
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

            if ("<?= $cod_entidad ?>" != 0) {

                $("#formulario #COD_ENTIDAD").val("<?= $cod_entidad ?>");
                $("#formulario #COD_GRUPOENT").val("<?= $cod_grupoent ?>").trigger("chosen:updated");
                $("#formulario #COD_TPENTID").val("<?= $cod_tpentid ?>").trigger("chosen:updated");
                $("#formulario #COD_EMPRESA").val("<?= $cod_empresa ?>");
                $("#formulario #NOM_ENTIDAD").val("<?= $nom_entidad ?>");
                //$("#formulario #NUM_CGCECPF").unmask().val("<?= $num_cgcecpf ?>");
                $("#formulario #NUM_CGCECPF").unmask().val("<?= $num_cgcecpf ?>");
                $("#formulario #DES_ENDERC").val("<?= $des_enderc ?>");
                $("#formulario #NUM_ENDEREC").val("<?= $num_enderec ?>");
                $("#formulario #DES_BAIRROC").val("<?= $des_bairroc ?>");
                $("#formulario #NUM_CEPOZOF").unmask().val("<?= $num_cepozof ?>");
                $("#formulario #NOM_CIDADES").val("<?= $nom_cidades ?>").trigger("chosen:updated");
                $("#formulario #NOM_ESTADOS").val("<?= $nom_estados ?>").trigger("chosen:updated");
                $("#formulario #COD_ESTADO").val("<?= $cod_estado ?>").trigger("chosen:updated");
                $("#formulario #NUM_TELEFONE").unmask().val("<?= $num_telefone ?>");
                $("#formulario #NUM_CELULAR").unmask().val("<?= $num_celular ?>");
                $("#formulario #EMAIL").val("<?= $email ?>");
                $("#formulario #NOM_RESPON").val("<?= $nom_respon ?>");
                $("#formulario #COD_EXTERNO").val("<?= $cod_externo ?>");
                $("#formulario #QTD_MEMBROS").val("<?= $qtd_membros ?>");
                agrupaEntidade("<?= $cod_grupoent ?>");
                carregaComboCidadesON("<?= $cod_estado ?>", "<?= $cod_municipio ?>");
                //carregaComboApoiadores("<?= $cod_estado ?>", "<?= $cod_municipio ?>");
                $('#formulario').validator('validate');
                $("#formulario #hHabilitado").val('S');

            }


            $('.modal').on('hidden.bs.modal', function () {
			  
              if ($('#REFRESH_CLIENTE').val() == "S"){
  
                  $("#COD_CLIENTE_MULT").append('<option value="'+$("#COD_CLIENTE_ENV").val()+'">'+$("#NOM_CLIENTE_ENV").val()+'</option>').trigger("chosen:updated");
  
                  var sistemasUniArr = $("#COD_CLIENTE_ENV").val();
  
                  // alert(sistemasUniArr);
  
                  if(sistemasUniArr){	
                      
                      //opções multiplas
                      for (var i = 0; i < sistemasUniArr.length; i++) {
                        $("#formulario #COD_CLIENTE_MULT option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
                      }
  
                  }
  
                  $("#formulario #COD_CLIENTE_MULT option[value=" + $("#COD_CLIENTE_ENV").val() + "]").prop("selected", "true").trigger("chosen:updated");
                  $('#REFRESH_CLIENTE').val('N');
                  
              }
  
          });

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


        function agrupaEntidade(selectObject) {
            var value = selectObject;
            $.ajax({
                type: "POST",
                url: "ajxEntidade.do?id=<?= fnEncode($cod_empresa) ?>&opcao=retornar",
                data: {
                    COD_AGRUPADOR: value
                },
                beforeSend: function() {
                    $('#campogrupo').html('<div class="col-md-12"><center><div class="loading" style="width: 100%;"></div></center></div>');
                },
                success: function(data) {
                    $("#campogrupo").html(data);

                }

            });
        }

        $(function() {

            var numPaginas = <?php echo $numPaginas; ?>;
            if (numPaginas != 0) {
                carregarPaginacao(numPaginas);
            }

            // carregaComboCidades('<?= $cod_estado ?>');

            // $("#formulario #COD_ESTADOF").val($("#COD_ESTADO option:selected").text());

            $("#COD_ESTADO").change(function() {
                cod_estado = $(this).val();
                carregaComboCidades(cod_estado);
                estado = $("#COD_ESTADO option:selected").text();
                // $('#COD_ESTADOF').val(estado);
                // $('#NOM_CIDADEC').val('');
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
                                            url: "ajxEntidade.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                            data: $('#formulario').serialize(),
                                            method: 'POST'
                                        }).done(function(response) {
                                            self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                            var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
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
                        },
                    }
                });
            });
        });

        function reloadPage(idPage) {
            $.ajax({
                type: "POST",
                url: "ajxEntidade.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
                data: $('#formulario').serialize(),
                beforeSend: function() {
                    $('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
                },
                success: function(data) {
                    $("#relatorioConteudo").html(data);
                    $(".tablesorter").trigger("updateAll");
                },
                error: function() {
                    $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina nÃ£o encontrados...</p>');
                }
            });
        }

        function carregaComboCidades(cod_estado) {
            $.ajax({
                method: 'POST',
                url: "ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>",
                data: {
                    COD_ESTADO: cod_estado
                },
                beforeSend: function() {
                    $('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#relatorioCidade").html(data);
                    if ($("#formulario #COD_MUNICIPIO_AUX").val() != '') {
                        $("#formulario #COD_MUNICIPIO").val($("#COD_MUNICIPIO_AUX").val()).trigger("chosen:updated");
                    } else {
                        $("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
                    }
                    // $("#formulario #NOM_CIDADEC").val($("#COD_MUNICIPIO option:selected").text());
                    // $('#formulario').validator('validate');
                }
            });
        }

        function carregaComboCidadesON(cod_estado, cod_municipio) {
            $.ajax({
                method: 'POST',
                url: "ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>",
                data: {
                    COD_ESTADO: cod_estado,
                    COD_MUNICIPIO: cod_municipio
                },
                beforeSend: function() {
                    $('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#relatorioCidade").html(data);
                    $("#formulario #COD_MUNICIPIO").val(cod_municipio).trigger("chosen:updated");
                }
            });
        }

        function carregaComboApoiadores(cod_clientes) {
            $.ajax({
                method: 'POST',
                url: "ajxComboApoiadores.php?id=<?= fnEncode($cod_empresa) ?>",
                data: {
                    COD_CLIENTES: cod_clientes
                },
                beforeSend: function() {
                    $('#relatorioApoiadores').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#relatorioApoiadores").html(data);
                    //$("#formulario #COD_MUNICIPIO").val(cod_municipio).trigger("chosen:updated");
                }
            });
        }

        function retornaForm(index) {

            $("#formulario #COD_CLIENTE_MULT").val('').trigger("chosen:updated");

			// var sistemasUni = $("#ret_NOM_CLIENTE_" + index).val();
			// var sistemasUniArr = sistemasUni.split("|");
   //          console.log(sistemasUniArr.length)
			// //opções multiplas
			// for (var i = 0; i < sistemasUniArr.length; i++) {
			// 	$("#formulario #COD_CLIENTE_MULT option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			// }
			// $("#formulario #COD_CLIENTE_MULT").trigger("chosen:updated");

            $("#formulario #COD_USUARIO_RESP").val('').trigger("chosen:updated");
			if ($("#ret_COD_USUARIO_RESP_" + index).val() != "") {
				var sistemasUni = $("#ret_COD_USUARIO_RESP_" + index).val();
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_USUARIO_RESP option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_USUARIO_RESP").trigger("chosen:updated");
			} else {
				$("#formulario #COD_USUARIO_RESP").val('').trigger("chosen:updated");
			}

            console.log($("#ret_LOG_STATUS_" + index).val());

            if ($("#ret_LOG_STATUS_" + index).val() == 'S') {
				$('#formulario #LOG_STATUS').prop('checked', true);
			} else {
				$('#formulario #LOG_STATUS').prop('checked', false);
			}

            
            $("#formulario #COD_REGIAO").val($("#ret_COD_REGIAO_" + index).val()).trigger("chosen:updated");
            $("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val());
            $("#formulario #COD_GRUPOENT").val($("#ret_COD_GRUPOENT_" + index).val()).trigger("chosen:updated");
            $("#formulario #COD_TPENTID").val($("#ret_COD_TPENTID_" + index).val()).trigger("chosen:updated");
            $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val());
            $("#formulario #NOM_ENTIDAD").val($("#ret_NOM_ENTIDAD_" + index).val());
            //$("#formulario #NUM_CGCECPF").unmask().val($("#ret_NUM_CGCECPF_"+index).val());
            $("#formulario #NUM_CGCECPF").unmask().val($("#ret_NUM_CGCECPF_" + index).val());
            $("#formulario #DES_ENDERC").val($("#ret_DES_ENDERC_" + index).val());
            $("#formulario #NUM_ENDEREC").val($("#ret_NUM_ENDEREC_" + index).val());
            $("#formulario #DES_BAIRROC").val($("#ret_DES_BAIRROC_" + index).val());
            $("#formulario #NUM_CEPOZOF").unmask().val($("#ret_NUM_CEPOZOF_" + index).val());
            $("#formulario #NOM_CIDADES").val($("#ret_NOM_CIDADES_" + index).val()).trigger("chosen:updated");
            $("#formulario #NOM_ESTADOS").val($("#ret_NOM_ESTADOS_" + index).val()).trigger("chosen:updated");
            $("#formulario #COD_ESTADO").val($("#ret_COD_ESTADO_" + index).val()).trigger("chosen:updated");
            $("#formulario #NUM_TELEFONE").unmask().val($("#ret_NUM_TELEFONE_" + index).val());
            $("#formulario #NUM_CELULAR").unmask().val($("#ret_NUM_CELULAR_" + index).val());
            $("#formulario #EMAIL").val($("#ret_EMAIL_" + index).val());
            $("#formulario #NOM_RESPON").val($("#ret_NOM_RESPON_" + index).val());
            $("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
            $("#formulario #QTD_MEMBROS").val($("#ret_QTD_MEMBROS_" + index).val());
            agrupaEntidade($("#ret_COD_GRUPOENT_" + index).val());
            carregaComboCidadesON($("#ret_COD_ESTADO_" + index).val(), $("#ret_COD_MUNICIPIO_" + index).val());
            carregaComboApoiadores($("#ret_COD_CLIENTE_MULT_" + index).val());
            // $("#formulario #COD_MUNICIPIO").val($("#ret_COD_MUNICIPIO_"+index).val()).trigger("chosen:updated");
            $('#formulario').validator('validate');
            $("#formulario #hHabilitado").val('S');
        }
    </script>