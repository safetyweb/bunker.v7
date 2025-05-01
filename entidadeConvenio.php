    <?php

//echo fnDebug('true');

    $hashLocal = mt_rand(); 
    $itens_por_pagina = 50;
    $pagina = 1;

    if( $_SERVER['REQUEST_METHOD']=='POST' )
    {
        $request = md5( implode( $_POST ) );

        if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
        {
            $msgRetorno = 'Essa página já foi utilizada!';
            $msgTipo = 'alert-warning';
        }
        else
        {
            $_SESSION['last_request']  = $request;
            $filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);         
            $val_pesquisa = fnLimpaCampo($_POST['INPUT']);

            $cod_registro = fnLimpaCampoZero($_REQUEST['COD_REGISTRO']);
            $cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
            $cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
            

            $opcao = $_REQUEST['opcao'];
            $hHabilitado = $_REQUEST['hHabilitado'];
            $hashForm = $_REQUEST['hashForm'];

            $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

            if ($opcao != ''){

                // CREATE TABLE ENTIDADE_CONVENIO(
                // COD_REGISTRO INT PRIMARY KEY AUTO_INCREMENT,
                // COD_EMPRESA INT,
                // COD_ENTIDAD INT,
                // COD_CONVENI INT,
                // COD_USUCADA INT,
                // DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                // COD_ALTERAC INT,
                // DAT_ALTERAC DATETIME,
                // COD_EXCLUSA INT,
                // DAT_ALTERAC DATETIME
                // );

                //mensagem de retorno
                switch ($opcao)
                {
                    case 'CAD':

                        $sql = "INSERT INTO ENTIDADE_CONVENIO(
                                                COD_EMPRESA,
                                                COD_ENTIDAD,
                                                COD_CONVENI,
                                                COD_USUCADA
                                            ) VALUES(
                                                $cod_empresa,
                                                $cod_entidad,
                                                $cod_conveni,
                                                $cod_usucada
                                            )";

                        // fnEscreve($sql);

                        mysqli_query(connTemp($cod_empresa,''),$sql);

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

                    break;

                    case 'ALT':

                        $sql = "UPDATE ENTIDADE_CONVENIO SET
                                                COD_ENTIDAD = $cod_entidad,
                                                COD_CONVENI = $cod_conveni,
                                                COD_ALTERAC = $cod_usucada,
                                                DAT_ALTERAC = NOW()
                                WHERE COD_EMPRESA = $cod_empresa
                                AND COD_REGISTRO = $cod_registro";

                        mysqli_query(connTemp($cod_empresa,''),$sql);

                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>"; 

                    break;

                    case 'EXC':

                         $sql = "UPDATE ENTIDADE_CONVENIO SET
                                                COD_EXCLUSA = $cod_usucada,
                                                DAT_EXCLUSA = NOW()
                                WHERE COD_EMPRESA = $cod_empresa
                                AND COD_REGISTRO = $cod_registro";

                        mysqli_query(connTemp($cod_empresa,''),$sql);

                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>"; 

                    break;
                }           
                $msgTipo = 'alert-success';

            }                
        }
    }

//busca dados da url    
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){

        //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);   
    $sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;    

        //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)){
        $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
    }

}else { 
    $nom_empresa = "";
}

if($val_pesquisa != ""){
    $esconde = " ";
}else{
    $esconde = "display: none;";
}



if(isset($_GET['idC'])){
    if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){

                //busca dados do convÃªnio
        $cod_conveni = fnLimpaCampoZero(fnDecode($_GET['idC']));    
        $sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;    

                //fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
        $qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

        if (isset($qrBuscaTemplate)){
            $nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];

        }           

    }
}else{
 $cod_conveni = 0; 
}   

if(isset($_GET['idE'])){
    $cod_entidad = fnLimpaCampoZero(fnDecode($_GET['idE']));

    $sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad AND COD_EMPRESA = $cod_empresa";
        //fnEscreve($sql);

    $arrayEntidad = mysqli_query(connTemp($cod_empresa,''),$sql);
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
    }else{
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

//fnMostraForm();
//fnEscreve($cod_entidad);
//fnEscreve($cod_empresa);

?>
<!--<style>
    #ESSENTIALS {
        position: relative;
        left: 350px;
    }
</style>--> 
<?php if ($popUp != "true"){  ?>                            
    <div class="push30"></div> 
<?php } ?>

<div class="row">               

    <!-- Portlet -->
    <?php if ($popUp != "true"){  ?>                            
        <div class="portlet portlet-bordered">
        <?php } else { ?>
            <div class="portlet" style="padding: 0 20px 20px 20px;" >
            <?php } ?>

            <?php if ($popUp != "true"){  ?>
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
                if(isset($_GET['idE'])){

                    $abaConvenio = 1075;                                        
                    include "abasConvenio.php"; 

                }

                $sqlfiltro = "SELECT des_filtro FROM filtros_cliente
                WHERE cod_empresa=$cod_empresa AND 
                cod_tpfiltro=28 AND 
                cod_filtro IN(

                  SELECT cod_regitra FROM entidade_grupo
                  WHERE cod_empresa=$cod_empresa AND 
                  cod_grupoent=$cod_grupoent
              )";

                $resultadosql = mysqli_query(connTemp($cod_empresa,''),$sqlfiltro);
                while($resultset = mysqli_fetch_assoc($resultadosql)){
                    $resultado= $resultset['des_filtro'];

                }


                if ($_SESSION["SYS_COD_SISTEMA"] == 12)
                {
                    ?>



                    <?php
                }
                ?>

                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li>
                            <a href="action.do?mod=<?php echo fnEncode(1563)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>" style="text-decoration: none;">
                                <span class="fal fa-arrow-circle-left fa-2x"></span>
                            </a>
                        </li>
                    </ul>
                </div>  

                    <div class="push20"></div>              

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend> 

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_REGISTRO" id="COD_REGISTRO" value="">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div> 

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Convênio</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
                                    </div>                                                      
                                </div>

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label required">Nome da Entidade</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1784)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_conveni)?>&pop=true" data-title="Busca Entidades"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
                                        </span>
                                        <input type="text" name="NOM_ENTIDAD" id="NOM_ENTIDAD" value="<?php echo $nom_entidad; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
                                        <input type="hidden" name="COD_ENTIDAD" id="COD_ENTIDAD" value="">
                                    </div>
                                    <div class="help-block with-errors"></div>                                                                                                              
                                </div>

                            </div> 

                            <div class="push10"></div>

                        </fieldset>


                        <div class="push10"></div>

                        <hr>

                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
                        
                        </div>

                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                        <input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?php echo $cod_conveni ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">     

                        <div class="push5"></div> 

                    </form>
                    <div class="row">
                        <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">
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
                                        <input type="hidden" name="VAL_PESQUISA" value="<?=$filtro?>" id="VAL_PESQUISA">         
                                        <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
                                        <div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
                                            <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                        </div>
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="push50"></div>

                                                    <div class="col-lg-12">

                                                        <div class="no-more-tables">

                                                            <form name="formLista">

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
                                                                    if($filtro != ""){
                                                                        $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
                                                                    }else{
                                                                        $andFiltro = " ";
                                                                    }
                                                                    if ($num_cgcecpf!=''){ 

                                                                        $andCpf = 'and num_cgcecpf ='.$num_cgcecpf; 
                                                                    }else {
                                                                        $andCpf = ' '; 

                                                                    }

                                                                    if($cod_conveni != "" && $cod_conveni != "0"){
                                                                        $andConveni = " AND EC.COD_CONVENI = $cod_conveni ";
                                                                    }else{
                                                                        $andConveni = " ";
                                                                    }
                                                                    //fnEscreve($andFiltro);

                                                                    $sql = "SELECT 1 from ENTIDADE EN
                                                                    inner join ENTIDADE_CONVENIO EC ON EC.COD_ENTIDAD = EN.COD_ENTIDAD 
                                                                    left join webtools.empresas ON EN.COD_EMPRESA = webtools.empresas.COD_EMPRESA
                                                                    where webtools.empresas.COD_EMPRESA = $cod_empresa
                                                                    AND (EC.COD_EXCLUSA IS NULL OR EC.COD_EXCLUSA = 0)
                                                                    $andFiltro
                                                                    $andConveni";
                                                                      //echo $sql;    
                                                                    $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
                                                                    $totalitens_por_pagina = mysqli_num_rows($retorno);
                                                                    $numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

                                                                      // fnEscreve($numPaginas);

                                                                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;



                                                                    $sql = "SELECT EN.COD_ENTIDAD,
                                                                                    EC.COD_REGISTRO,
                                                                                    EN.COD_GRUPOENT,
                                                                                    EN.COD_TPENTID,
                                                                                    EN.COD_EXTERNO,
                                                                                    EN.COD_EMPRESA,
                                                                                    EN.COD_MUNICIPIO,
                                                                                    EN.COD_ESTADO,
                                                                                    EN.NOM_ENTIDAD,
                                                                                    EN.NUM_CGCECPF,
                                                                                    EN.DES_ENDERC,
                                                                                    EN.NUM_ENDEREC,
                                                                                    EN.DES_BAIRROC,
                                                                                    EN.NUM_CEPOZOF,
                                                                                    EN.NOM_CIDADES,
                                                                                    EN.NOM_ESTADOS,
                                                                                    EN.NUM_TELEFONE,
                                                                                    EN.NUM_CELULAR,
                                                                                    EN.EMAIL,
                                                                                    EN.NOM_RESPON,
                                                                                    EN.QTD_MEMBROS,
                                                                                    TIPOENTIDADE.DES_TPENTID,
                                                                                    EMPRESAS.NOM_EMPRESA,
                                                                                    A.DES_GRUPOENT
                                                                            from ENTIDADE EN
                                                                            inner join ENTIDADE_CONVENIO EC ON EC.COD_ENTIDAD = EN.COD_ENTIDAD 
                                                                            left join webtools.empresas ON EN.COD_EMPRESA = webtools.empresas.COD_EMPRESA 
                                                                            left join webtools.tipoentidade ON EN.COD_TPENTID = webtools.tipoentidade.COD_TPENTID
                                                                            left join entidade_grupo A ON A.COD_GRUPOENT = EN.COD_GRUPOENT 
                                                                            where webtools.empresas.COD_EMPRESA = $cod_empresa 
                                                                            AND (EC.COD_EXCLUSA IS NULL OR EC.COD_EXCLUSA = 0)
                                                                            $andConveni
                                                                            $andFiltro
                                                                            order by COD_ENTIDAD
                                                                            limit $inicio,$itens_por_pagina";
                                                                    
                                                                    // fnEscreve($sql);
                                                                    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                                                                    $count=0;
                                                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                                                        if (strlen($qrBuscaModulos['NUM_CGCECPF']) <= 11) {

                                                                            $retun = fnCompletaDoc($qrBuscaModulos['NUM_CGCECPF'],"F");
                                                                            //$retun = str_pad($qrBuscaModulos['NUM_CGCECPF'], 11, '0', STR_PAD_LEFT); // Resultado: 00009
                                                                        } else {
                                                                           $retun = fnCompletaDoc($qrBuscaModulos['NUM_CGCECPF'],"J");
                                                                       }

                                                                        // $municipio = "";
                                                                       $estado = "";

                                                                        //  if($qrBuscaModulos[COD_MUNICIPIO] != ""){
                                                                        //      $sqlCidade = "SELECT NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO = $qrBuscaModulos[COD_MUNICIPIO]";
                                                                        //      $arrayMunicipio = mysqli_query(connTemp($cod_empresa,''),$sqlCidade);       
                                                                        //      $qrMunicipio = mysqli_fetch_assoc($arrayMunicipio);
                                                                        //      $municipio = $qrMunicipio[NOM_MUNICIPIO];
                                                                        // }

                                                                       if ($qrBuscaModulos[COD_ESTADO] != "") {

                                                                        $sqlEstado = "SELECT UF FROM ESTADO WHERE COD_ESTADO = $qrBuscaModulos[COD_ESTADO]";
                                                                        $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sqlEstado);
                                                                        $qrEstado = mysqli_fetch_assoc($arrayEstado);
                                                                        $estado = $qrEstado[UF];
                                                                    }

                                                                    $count++;
                                                                    echo"
                                                                    <tr>
                                                                    <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                                    <td>" . $qrBuscaModulos['COD_REGISTRO'] . "</td>
                                                                    <td>" . $qrBuscaModulos['NOM_ENTIDAD'] . "</td>
                                                                    <td>" . $qrBuscaModulos['NOM_RESPON'] . "</td>
                                                                    <td>" . $qrBuscaModulos['NOM_CIDADES']. "</td>
                                                                    <td>" . $estado . "</td>    
                                                                    </tr>

                                                                    <input type='hidden' id='ret_COD_REGISTRO_" . $count . "' value='" . $qrBuscaModulos['COD_REGISTRO'] . "'>
                                                                    <input type='hidden' id='ret_COD_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['COD_ENTIDAD'] . "'>
                                                                    <input type='hidden' id='ret_COD_GRUPOENT_" . $count . "' value='" . $qrBuscaModulos['COD_GRUPOENT'] . "'>
                                                                    <input type='hidden' id='ret_COD_TPENTID_" . $count . "' value='" . $qrBuscaModulos['COD_TPENTID'] . "'>
                                                                    <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
                                                                    <input type='hidden' id='ret_des_filtro_" . $count . "' value='" . $resultset['des_filtro'] . "'>
                                                                    <input type='hidden' id='ret_NOM_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['NOM_ENTIDAD'] . "'>
                                                                    <input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . $qrBuscaModulos['NUM_CGCECPF'] . "'>
                                                                    <input type='hidden' id='ret_DES_ENDERC_" . $count . "' value='" . $qrBuscaModulos['DES_ENDERC'] . "'>
                                                                    <input type='hidden' id='ret_NUM_ENDEREC_" . $count . "' value='" . $qrBuscaModulos['NUM_ENDEREC'] . "'>
                                                                    <input type='hidden' id='ret_DES_BAIRROC_" . $count . "' value='" . $qrBuscaModulos['DES_BAIRROC'] . "'>
                                                                    <input type='hidden' id='ret_NUM_CEPOZOF_" . $count . "' value='" . $qrBuscaModulos['NUM_CEPOZOF'] . "'>
                                                                    <input type='hidden' id='ret_COD_MUNICIPIO_" . $count . "' value='" . $qrBuscaModulos['COD_MUNICIPIO'] . "'>
                                                                    <input type='hidden' id='ret_NOM_CIDADES_".$count."' value='".$qrBuscaModulos['NOM_CIDADES']."'>
                                                                    <input type='hidden' id='ret_COD_ESTADO_" . $count . "' value='" . $qrBuscaModulos['COD_ESTADO'] . "'>
                                                                    <input type='hidden' id='ret_NUM_TELEFONE_" . $count . "' value='" . $qrBuscaModulos['NUM_TELEFONE'] . "'>
                                                                    <input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . $qrBuscaModulos['NUM_CELULAR'] . "'>
                                                                    <input type='hidden' id='ret_EMAIL_" . $count . "' value='" . $qrBuscaModulos['EMAIL'] . "'>
                                                                    <input type='hidden' id='ret_NOM_RESPON_" . $count . "' value='" . $qrBuscaModulos['NOM_RESPON'] . "'>
                                                                    <input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaModulos['COD_EXTERNO'] . "'>
                                                                    <input type='hidden' id='ret_QTD_MEMBROS_" . $count . "' value='" . $qrBuscaModulos['QTD_MEMBROS'] . "'>
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
                                                                <center><ul id="paginacao" class="pagination-sm"></ul></center>
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
$(document).ready(function(e){
    var value = $('#INPUT').val().toLowerCase().trim();
    if(value){
        $('#CLEARDIV').show();
    }else{
        $('#CLEARDIV').hide();
    }
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var param = $(this).attr("href").replace("#","");
        var concept = $(this).text();
        $('.search-panel span#search_concept').text(concept);
        $('.input-group #VAL_PESQUISA').val(param);
        $('#INPUT').focus();
    });

    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
    });

    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
    });

    $('#CLEAR').click(function(){
        $('#INPUT').val('');
        $('#INPUT').focus();
        $('#CLEARDIV').hide();
        if("<?=$filtro?>" != ""){
            location.reload();
        }else{
            var value = $('#INPUT').val().toLowerCase().trim();
            if(value){
                $('#CLEARDIV').show();
            }else{
                $('#CLEARDIV').hide();
            }
            $(".buscavel tr").each(function (index) {
                if (!index) return;
                $(this).find("td").each(function () {
                    var id = $(this).text().toLowerCase().trim();
                    var sem_registro = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!sem_registro);
                    return sem_registro;
                });
            });
        }
    });

    // $('#SEARCH').click(function(){
    //  $('#formulario').submit();
    // });


});

function buscaRegistro(el){
    var filtro = $('#search_concept').text().toLowerCase();

    if(filtro == "sem filtro"){
        var value = $(el).val().toLowerCase().trim();
        if(value){
            $('#CLEARDIV').show();
        }else{
            $('#CLEARDIV').hide();
        }
        $(".buscavel tr").each(function (index) {
            if (!index) return;
            $(this).find("td").each(function () {
                var id = $(this).text().toLowerCase().trim();
                var sem_registro = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!sem_registro);
                return sem_registro;
            });
        });
    }
}


function agrupaEntidade(selectObject){
    var value = selectObject;
    $.ajax({
        type: "POST",
        url: "ajxEntidade.do?id=<?=fnEncode($cod_empresa)?>&opcao=retornar",
        data: {COD_AGRUPADOR:value},
        beforeSend:function(){
            $('#campogrupo').html('<div class="col-md-12"><center><div class="loading" style="width: 100%;"></div></center></div>');
        },
        success:function(data){
            $("#campogrupo").html(data);

        }

    });
}

$(function(){

    var numPaginas = <?php echo $numPaginas; ?>;
    if(numPaginas != 0){
        carregarPaginacao(numPaginas);
    }

        // carregaComboCidades('<?=$cod_estado?>');

        // $("#formulario #COD_ESTADOF").val($("#COD_ESTADO option:selected").text());

        $("#COD_ESTADO").change(function(){
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
                     action: function () {
                        var nome = this.$content.find('.nome').val();
                        if(!nome){
                           $.alert('Por favor, insira um nome');
                           return false;
                       }

                       $.confirm({
                           title: 'Mensagem',
                           type: 'green',
                           icon: 'fa fa-check-square-o',
                           content: function(){
                              var self = this;
                              return $.ajax({
                                 url: "ajxEntidade.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
                                 data: $('#formulario').serialize(),
                                 method: 'POST'
                             }).done(function (response) {
                                 self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                 var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                 SaveToDisk('media/excel/' + fileName, fileName);
                                 console.log(response);
                             }).fail(function(){
                                 self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                             });
                         },                         
                         buttons: {
                          fechar: function () {
                                            //close
                                        }                                   
                                    }
                                });                             
                   }
               },
               cancelar: function () {
                            //close
                        },
                    }
                });             
        });
    }); 
    
$("#btnBusca").click(function(){
    $("#NOM_ENTIDAD").removeAttr("required");
})

function reloadPage(idPage) {
    $.ajax({
        type: "POST",
        url: "ajxEntidade.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
        data: $('#formulario').serialize(),
        beforeSend:function(){
            $('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
        },
        success:function(data){
            $("#relatorioConteudo").html(data);
            $(".tablesorter").trigger("updateAll");                                     
        },
        error:function(){
            $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina nÃ£o encontrados...</p>');
        }
    });     
}

function carregaComboCidades(cod_estado){
    $.ajax({
        method: 'POST',
        url: "ajxComboMunicipio.php?id=<?=fnEncode($cod_empresa)?>",
        data:{COD_ESTADO:cod_estado},
        beforeSend:function(){
            $('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
        },
        success:function(data){
            $("#relatorioCidade").html(data);
            if($("#formulario #COD_MUNICIPIO_AUX").val() != ''){
                $("#formulario #COD_MUNICIPIO").val($("#COD_MUNICIPIO_AUX").val()).trigger("chosen:updated");
            }else{
                $("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
            }
                        // $("#formulario #NOM_CIDADEC").val($("#COD_MUNICIPIO option:selected").text());
                        // $('#formulario').validator('validate');
                    }
                });
}

function carregaComboCidadesON(cod_estado,cod_municipio){
    $.ajax({
        method: 'POST',
        url: "ajxComboMunicipio.php?id=<?=fnEncode($cod_empresa)?>",
        data:{COD_ESTADO:cod_estado,COD_MUNICIPIO:cod_municipio},
        beforeSend:function(){
            $('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
        },
        success:function(data){
            $("#relatorioCidade").html(data);
            $("#formulario #COD_MUNICIPIO").val(cod_municipio).trigger("chosen:updated");
        }
    });
}
function retornaForm(index){
    $("#formulario #COD_REGISTRO").val($("#ret_COD_REGISTRO_"+index).val());
    $("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val());
    $("#formulario #NOM_ENTIDAD").val($("#ret_NOM_ENTIDAD_"+index).val());
        
    $('#formulario').validator('validate');         
    $("#formulario #hHabilitado").val('S');         
}

</script>   