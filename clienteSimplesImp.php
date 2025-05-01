<?php
/* function fnConsoleLog($var){
  ?>
  <script>
  var variavel = ('<?=$var?>').replace(/(\r\n\t|\n|\r\t)/gm,"");
  if(variavel == ''){
  variavel = '__';
  }
  console.log(variavel);
  </script>
  <?php
  }
 */
//echo fnDebug('true');

$hashLocal = mt_rand();

//inicialização das variáveis
@$cod_multemp = "0";
@$countFiltros = "";

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_usuario = fnLimpacampoZero($_REQUEST['COD_USUARIO']);
        $cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
        $nom_usuario = fnLimpacampo($_REQUEST['NOM_USUARIO']);
        $des_senhaus = fnEncode(fnLimpacampo($_REQUEST['DES_SENHAUS']));
        $log_usuario = fnLimpacampo($_REQUEST['LOG_USUARIO']);
        $des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);
        if (empty($_REQUEST['LOG_ESTATUS'])) {
            $log_estatus = 'N';
        } else {
            $log_estatus = $_REQUEST['LOG_ESTATUS'];
        }
        if (empty($_REQUEST['LOG_TROCAPROD'])) {
            $log_trocaprod = 'N';
        } else {
            $log_trocaprod = $_REQUEST['LOG_TROCAPROD'];
        }
        $num_rgpesso = fnLimpacampo($_REQUEST['NUM_RGPESSO']);
        $dat_nascime = fnLimpacampo($_REQUEST['DAT_NASCIME']);
        $cod_estaciv = fnLimpaCampoZero($_REQUEST['COD_ESTACIV']);
        $cod_sexopes = fnLimpacampoZero($_REQUEST['COD_SEXOPES']);
        $num_tentati = fnLimpacampoZero($_REQUEST['NUM_TENTATI']);
        $num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
        $num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
        $num_comercial = fnLimpacampo($_REQUEST['NUM_COMERCIAL']);
        $cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
        $num_cartao = fnLimpacampoZero($_REQUEST['NUM_CARTAO']);
        $num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
        if ($num_cartao == 0 || $num_cartao == "") {
            $num_cartao = fnLimpacampoZero(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
        }
        $des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
        $num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
        $des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
        $des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
        $num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
        $nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
        $cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);
        $cod_tpcliente = fnLimpacampoZero($_REQUEST['COD_TPCLIENTE']);
        $count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);

        //array dos sistemas da empresas
        if (isset($_POST['COD_PERFILS'])) {
            $Arr_COD_PERFILS = $_POST['COD_PERFILS'];
            //print_r($Arr_COD_SISTEMAS);			 

            for ($i = 0; $i < count($Arr_COD_PERFILS); $i++) {
                $cod_perfils = $cod_perfils . $Arr_COD_PERFILS[$i] . ",";
            }

            $cod_perfils = substr($cod_perfils, 0, -1);
        } else {
            $cod_perfils = "0";
        }


        //array das empresas multiacesso
        if (isset($_POST['COD_MULTEMP'])) {
            $Arr_COD_MULTEMP = $_POST['COD_MULTEMP'];
            //print_r($Arr_COD_MULTEMP);			 

            for ($i = 0; $i < count($Arr_COD_MULTEMP); $i++) {
                $cod_multemp = $cod_multemp . $Arr_COD_MULTEMP[$i] . ",";
            }

            $cod_multemp = substr($cod_multemp, 0, -1);
        } else {
            $cod_multemp = "0";
        }


        //fnEscreve($cod_perfils);

        $des_apelido = fnLimpacampo($_REQUEST['DES_APELIDO']);
        $cod_profiss = fnLimpacampoZero($_REQUEST['COD_PROFISS']);
        $cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
        $des_contato = fnLimpacampo($_REQUEST['DES_CONTATO']);
        if (empty($_REQUEST['LOG_EMAIL'])) {
            $log_email = 'N';
        } else {
            $log_email = $_REQUEST['LOG_EMAIL'];
        }
        if (empty($_REQUEST['LOG_SMS'])) {
            $log_sms = 'N';
        } else {
            $log_sms = $_REQUEST['LOG_SMS'];
        }
        if (empty($_REQUEST['LOG_TELEMARK'])) {
            $log_telemark = 'N';
        } else {
            $log_telemark = $_REQUEST['LOG_TELEMARK'];
        }
        if (empty($_REQUEST['LOG_FUNCIONA'])) {
            $log_funciona = 'N';
        } else {
            $log_funciona = $_REQUEST['LOG_FUNCIONA'];
        }
        $nom_pai = fnLimpacampo($_REQUEST['NOM_PAI']);
        $nom_mae = fnLimpacampo($_REQUEST['NOM_MAE']);
        $cod_chaveco = fnLimpacampo($_REQUEST['COD_CHAVECO']);
        $key_externo = fnLimpacampo($_REQUEST['KEY_EXTERNO']);
        $tip_cliente = fnLimpacampo($_REQUEST['TIP_CLIENTE']);
        $des_coment = fnLimpacampo($_REQUEST['DES_COMENT']);
        // fnEscreve($num_cartao);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = 1;

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    //verifica 
                    switch ($cod_chaveco) {

                        case 1: //cpf
                            $num_cartao = fnLimpaDoc($num_cgcecpf);
                            break;
                        case 2: //cartao pre cadastrado
                            //$num_cartao = "active";
                            $num_cartao = $num_cartao;
                            break;
                        case 3: //telefone
                            $num_cartao = fnLimpaDoc($num_celular);
                            break;
                        case 4: //código externo
                            $num_cartao = $num_cartao;
                            break;
                        case 5: //cartao + cpf
                            $num_cartao = $num_cartao;
                            break;
                        case 6: //CPF/CNPJ/NASC/CEL/EMAIL
                            $num_cartao = "0";
                            break;
                    }

                    if (strlen(fnLimpaDoc($num_cgcecpf)) == '11') {
                        $tip_cliente = "F";
                    }

                    //RICARDO APOS AQUI - VAI TER TODAS AS CRÍTICIAS SE FOR TIPO COM CARTAO  
                    //$cod_chaveco = 2 ou 5

                    $sql1 = "CALL SP_ALTERA_CLIENTES(
							'" . $cod_usuario . "',
							'" . $cod_empresa . "',
							'" . $nom_usuario . "',
							'" . $des_senhaus . "',
							'" . $log_usuario . "',
							'" . $des_emailus . "',
							'" . $_SESSION["SYS_COD_USUARIO"] . "',    
							'" . fnLimpaDoc($num_cgcecpf) . "',
							'" . $log_estatus . "',
							'" . $log_trocaprod . "',
							'" . $num_rgpesso . "',
							'" . $dat_nascime . "',
							'" . $cod_estaciv . "',
							'" . $cod_sexopes . "',
							'" . $num_telefon . "',
							'" . $num_celular . "',
							'" . $num_comercial . "',
							'" . $cod_externo . "',
							'" . fnLimpaDoc($num_cartao) . "',
							'" . $num_tentati . "',
							'" . $des_enderec . "',
							'" . $num_enderec . "',
							'" . $des_complem . "',
							'" . $des_bairroc . "',
							'" . $num_cepozof . "',
							'" . $nom_cidadec . "',
							'" . $cod_estadof . "',
							'" . $des_apelido . "',
							'" . $cod_profiss . "',
							" . $cod_univend . ",
							'" . $tip_cliente . "',
							'" . $des_contato . "',
							'" . $log_email . "',
							'" . $log_sms . "',
							'" . $log_telemark . "',
							'" . $nom_pai . "',
							'" . $nom_mae . "',
							'" . $cod_chaveco . "',
							'" . $cod_multemp . "',
							'" . $key_externo . "',
							'" . $cod_tpcliente . "',
							'" . $log_funciona . "',
							'" . $des_coment . "',
							'" . $opcao . "'   
						);";

                    //fnEscreve($sql1);

                    if ($cod_chaveco == 6) {
                        $semCPF = "S";
                    } else {
                        $semCPF = "N";
                    }

                    //if($num_cgcecpf != "" && $num_cgcecpf != 0){
                    if ($num_cgcecpf != "" || ($num_cgcecpf != 0 && $semCPF = "N")) {

                        $execCliente = mysqli_query(connTemp($cod_empresa, ''), $sql1);
                        $qrGravaCliente = mysqli_fetch_assoc($execCliente);
                        $cod_clienteRetorno = $qrGravaCliente['COD_CLIENTE'];
                        $mensagem = $qrGravaCliente['MENSAGEM'];
                        $msgTipo = 'alert-success';

                        if ($count_filtros != "") {

                            $sql = "";
                            $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

                            for ($i = 0; $i < $count_filtros; $i++) {

                                $cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
                                $cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

                                if ($cod_filtro != 0) {
                                    $sql .= "INSERT INTO CLIENTE_FILTROS(
														COD_EMPRESA,
														COD_TPFILTRO,
														COD_FILTRO,
														COD_CLIENTE,
														COD_USUCADA
														)VALUES(
														$cod_empresa,
														$cod_tpfiltro,
														$cod_filtro,
														$cod_clienteRetorno,
														$cod_usucada
														);";
                                }
                            }

                            //fnEscreve($sql);
                            if ($sql != "") {
                                mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
                            }
                        }
                    } else {

                        $cod_clienteRetorno = 0;
                        $mensagem = "Apoiador avulso não pode ser alterado!";
                        $msgTipo = 'alert-danger';
                    }

                    //fnEscreve($cod_clienteRetorno);
                    //fnEscreve($mensagem);
                    if ($mensagem == "Este apoiador já existe!") {

                        $msgRetorno = $mensagem;
                        $msgTipo = 'alert-danger';
                    } else if ($mensagem == "Novo apoiador cadastrado com <strong> sucesso! </strong>") {
                        $cod_empresa = fnEncode($cod_empresa);
                        $cod_cliente = fnEncode($cod_clienteRetorno);
                        ?>
                        <script>
                            window.location.replace("action.php?mod=PvUR9sokXEM¢&id=<?= $cod_empresa ?>&idC=<?= $cod_cliente ?>");
                        </script>
                        <?php
                    } else {

                        $msgRetorno = $mensagem;
                    }

                    break;

                case 'ALT':
                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    $msgTipo = 'alert-success';

                    $sql2 = "CALL SP_ALTERA_CLIENTES(
							'" . $cod_usuario . "',
							'" . $cod_empresa . "',
							'" . $nom_usuario . "',
							'" . $des_senhaus . "',
							'" . $log_usuario . "',
							'" . $des_emailus . "',
							'" . $_SESSION["SYS_COD_USUARIO"] . "',    
							'" . fnLimpaDoc($num_cgcecpf) . "',
							'" . $log_estatus . "',
							'" . $log_trocaprod . "',
							'" . $num_rgpesso . "',
							'" . $dat_nascime . "',
							'" . $cod_estaciv . "',
							'" . $cod_sexopes . "',
							'" . $num_telefon . "',
							'" . $num_celular . "',
							'" . $num_comercial . "',
							'" . $cod_externo . "',
							'" . $num_cartao . "',
							'" . $num_tentati . "',
							'" . $des_enderec . "',
							'" . $num_enderec . "',
							'" . $des_complem . "',
							'" . $des_bairroc . "',
							'" . $num_cepozof . "',
							'" . $nom_cidadec . "',
							'" . $cod_estadof . "',
							'" . $des_apelido . "',
							'" . $cod_profiss . "',
							" . $cod_univend . ",
							'" . $tip_cliente . "',
							'" . $des_contato . "',
							'" . $log_email . "',
							'" . $log_sms . "',
							'" . $log_telemark . "',
							'" . $nom_pai . "',
							'" . $nom_mae . "',
							'" . $cod_chaveco . "',
							'" . $cod_multemp . "',
							'" . $key_externo . "',
							'" . $cod_tpcliente . "',
							'" . $log_funciona . "',
							'" . $des_coment . "',
							'" . $opcao . "'   
								
						);";

                    //fnEscreve($sql2);
                    //if($num_cgcecpf != "" && $num_cgcecpf != 0){
                    if ($num_cgcecpf != "" || ($num_cgcecpf != 0 && $semCPF = "N")) {
                        mysqli_query(connTemp($cod_empresa, ''), $sql2);

                        if ($count_filtros != "") {

                            $sql = "DELETE FROM CLIENTE_FILTROS WHERE COD_CLIENTE = $cod_usuario;";
                            $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

                            for ($i = 0; $i < $count_filtros; $i++) {

                                $cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
                                $cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

                                if ($cod_filtro != 0) {
                                    $sql .= "INSERT INTO CLIENTE_FILTROS(
														COD_EMPRESA,
														COD_TPFILTRO,
														COD_FILTRO,
														COD_CLIENTE,
														COD_USUCADA
														)VALUES(
														$cod_empresa,
														$cod_tpfiltro,
														$cod_filtro,
														$cod_usuario,
														$cod_usucada
														);";
                                }
                            }

                            //fnEscreve($sql);
                            if ($sql != "") {
                                mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
                            }
                        }
                    } else {
                        $msgRetorno = "Apoiador avulso não pode ser alterado!";
                        $msgTipo = 'alert-danger';
                    }

                    break;

                case 'EXC':
                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    $msgTipo = 'alert-success';

                    break;
            }
        }

        $newDate = explode('/', $dat_nascime);
        $dia = $newDate[0];
        $mes = $newDate[1];
        $ano = $newDate[2];

        $sql = "UPDATE CLIENTES SET DIA = $dia, MES = $mes, ANO = $ano WHERE NUM_CGCECPF = " . fnLimpaDoc($num_cgcecpf);
        //fnEscreve($sql);
        mysqli_query(connTemp($cod_empresa, ''), $sql);
    }
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

    $cod_empresa = fnDecode($_GET['id']);
    if (empty($cod_clienteRetorno)) {
        //fnEscreve("if");
        if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {
            //fnEscreve("if1");
            $cod_cliente = fnDecode($_GET['idC']);
            //fnEscreve($cod_cliente);		
        } else {
            //fnEscreve("if2");
            $cod_cliente = 0;
        }
    } else {
        //fnEscreve("else");
        $cod_cliente = $cod_clienteRetorno;
    }

    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
			  FROM empresas WHERE COD_EMPRESA=$cod_empresa";

    //fnEscreve($sql);		
    $qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sql)));
    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    $cod_chaveco = $qrBuscaEmpresa['COD_CHAVECO'];
    $log_categoria = $qrBuscaEmpresa['LOG_CATEGORIA'];
    $log_autocad = $qrBuscaEmpresa['LOG_AUTOCAD'];

    //categoria de clientes		
    $sql2 = "SELECT B.NOM_FAIXACAT,A.* 
				FROM clientes A
				left join categoria_cliente B ON B.COD_CATEGORIA=A.COD_CATEGORIA
				WHERE A.COD_CLIENTE = $cod_cliente and 
				A.COD_EMPRESA = $cod_empresa";

    $qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql2));
    //fnEscreve($sql2);	

    if (isset($qrBuscaCliente)) {

        if ($cod_cliente != 0) {
            $cod_usuario = $qrBuscaCliente['COD_CLIENTE'];
            $cod_externo = $qrBuscaCliente['COD_EXTERNO'];
            $nom_usuario = $qrBuscaCliente['NOM_CLIENTE'];
            $num_cartao = $qrBuscaCliente['NUM_CARTAO'];
            $num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
            $num_rgpesso = $qrBuscaCliente['NUM_RGPESSO'];
            $dat_nascime = $qrBuscaCliente['DAT_NASCIME'];
            $cod_estaciv = $qrBuscaCliente['COD_ESTACIV'];
            $cod_sexopes = $qrBuscaCliente['COD_SEXOPES'];
            $des_emailus = $qrBuscaCliente['DES_EMAILUS'];
            $num_telefon = $qrBuscaCliente['NUM_TELEFON'];
            $num_celular = $qrBuscaCliente['NUM_CELULAR'];
            $num_comercial = $qrBuscaCliente['NUM_COMERCI'];
            $des_enderec = $qrBuscaCliente['DES_ENDEREC'];
            $num_enderec = $qrBuscaCliente['NUM_ENDEREC'];
            $des_complem = $qrBuscaCliente['DES_COMPLEM'];
            $des_bairroc = $qrBuscaCliente['DES_BAIRROC'];
            $num_cepozof = $qrBuscaCliente['NUM_CEPOZOF'];
            $nom_cidadec = $qrBuscaCliente['NOM_CIDADEC'];
            $cod_estadof = $qrBuscaCliente['COD_ESTADOF'];
            $dat_cadastr = fnFormatDateTime($qrBuscaCliente['DAT_CADASTR']);
            $log_usuario = $qrBuscaCliente['LOG_USUARIO'];
            if ($qrBuscaCliente['LOG_ESTATUS'] == 'S') {
                $check_ativo = 'checked';
            } else {
                $check_ativo = '';
            }
            if ($qrBuscaCliente['LOG_TROCAPROD'] == 'S') {
                $check_troca = 'checked';
            } else {
                $check_troca = '';
            }
            $des_senhaus = fnDecode($qrBuscaCliente['DES_SENHAUS']);
            $num_tentati = $qrBuscaCliente['NUM_TENTATI'];
            $des_apelido = $qrBuscaCliente['DES_APELIDO'];
            $cod_profiss = $qrBuscaCliente['COD_PROFISS'];
            $cod_univend = $qrBuscaCliente['COD_UNIVEND'];
            $cod_tpcliente = $qrBuscaCliente['COD_TPCLIENTE'];
            $tip_cliente = $qrBuscaCliente['TIP_CLIENTE'];
            $des_contato = $qrBuscaCliente['DES_CONTATO'];
            if ($qrBuscaCliente['LOG_FUNCIONA'] == 'S') {
                $check_funciona = 'SIM';
            } else {
                $check_funciona = 'NÃO';
            }
            if ($qrBuscaCliente['LOG_EMAIL'] == 'S') {
                $check_mail = 'SIM';
            } else {
                $check_mail = 'NÃO';
            }
            if ($qrBuscaCliente['LOG_SMS'] == 'S') {
                $check_sms = 'SIM';
            } else {
                $check_sms = 'NÃO';
            }
            if ($qrBuscaCliente['LOG_TELEMARK'] == 'S') {
                $check_telemark = 'SIM';
            } else {
                $check_telemark = 'NÃO';
            }
            $nom_pai = $qrBuscaCliente['NOM_PAI'];
            $nom_mae = $qrBuscaCliente['NOM_MAE'];
            $cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];
            $cod_multemp = $qrBuscaCliente['COD_MULTEMP'];
            if (empty($cod_multemp)) {
                $cod_multemp = "0";
            }
            $key_externo = $qrBuscaCliente['KEY_EXTERNO'];
            $cod_categoria = $qrBuscaCliente['COD_CATEGORIA'];
            $nom_faixacat = $qrBuscaCliente['NOM_FAIXACAT'];
            $cod_indicad = $qrBuscaCliente['COD_INDICAD'];
            $dat_indicad = $qrBuscaCliente['DAT_INDICAD'];
            $des_coment = $qrBuscaCliente['DES_COMENT'];
            $cod_usucada = $qrBuscaCliente['COD_USUCADA'];
            $cod_municipio = $qrBuscaCliente['COD_MUNICIPIO'];
        }
    }
}

if ($cod_indicad != 0) {
    $sql = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $cod_indicad";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

    $qrIndicad = mysqli_fetch_assoc($arrayQuery);
    $nom_indicad = $qrIndicad['NOM_CLIENTE'];
}

$sqlEleicao = "SELECT
					(SELECT COUNT(*) FROM CLIENTES E WHERE  E.COD_MUNICIPIO=B.COD_MUNICIPIO) AS QTD_CADASTRO,
					(SELECT  SUM(QT_VOTOS_NOMINAIS) FROM ELEICOES F WHERE  F.CD_MUNICIPIO=B.COD_MUNICIPIO_E AND ANO_ELEICAO=2018 AND NR_CANDIDATO=31031) AS QTD_VOTOS
					FROM MUNICIPIOS B WHERE B.COD_MUNICIPIO = $cod_municipio";
$qrEleicao = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), trim($sqlEleicao)));

$tot_votos = $qrEleicao['QTD_VOTOS'];
$tot_apoiadores = $qrEleicao['QTD_CADASTRO'];
?>

<style>

    .alert .alert-link {
        text-decoration: none;
    }
    .alert:hover .alert-link:hover {
        text-decoration: underline;
    }

    body{
        overflow-x: hidden;
    }

    legend {
        width: auto;
        padding: 0 5px 0 5px;
        font-size: 14px;
        font-weight: bold;
        border: 0;
    }

    fieldset {
        border-left-style: none;
        border-right-style: none;
        border-bottom-style: none;
    }

    .foto {
        width: 190px;
        height: 190px;
        margin-left: auto!important;
        margin-right: auto!important;
        border: 1px solid #dce4ec;       
    }

    @media print 
    {	

        a[href]:after {
            content: none !important;
        }

        @page {
            size: A4; /* DIN A4 standard, Europe */
            margin:0;
        }
        html, body {
            width: 210mm;
            /* height: 297mm; */
            height: 282mm;
            font-size: 11px;
            background: #FFF;
            overflow:visible;
        }
        body {
            padding-top:7mm;
        }

        .hidden-print{
            display: none;
        }
    }

    .control-label{
        font-size: 11px;
    }

    .registro{
        font-size: 13px;
    }

</style>



<div class="row">				

    <div class="col-md12 margin-bottom-30">

        <!-- Portlet -->
<?php if ($popUp != "true") { ?>							
            <div class="portlet portlet-bordered">
                    <?php } else { ?>
                <div class="portlet" style="padding: 0 20px 20px 20px;" >
                        <?php } ?>

                    <?php if ($popUp != "true") { ?>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="glyphicon glyphicon-calendar"></i>
                            <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                        </div>

                        <?php
                        switch ($_SESSION["SYS_COD_SISTEMA"]) {
                            case 16: //gerenciador social
                                $formBack = "1102";
                                break;
                            default;
                                $formBack = "1015";
                                break;
                        }
                        include "atalhosPortlet.php";
                        ?>	

                    </div>
                    <?php } ?>	

                <div class="portlet-body">

<?php if ($msgRetorno <> '') { ?>	
                        <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30" role="alert" id="msgRetorno">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                        </div>
                    <?php } ?>

                    <?php
                    //verifica se tem bloqueio
                    $sql4 = "SELECT COUNT(*) as TEM_BLOQUEIO
											FROM CLIENTES A, VENDAS B
											LEFT JOIN $connAdm->DB.unidadevenda d ON d.cod_univend = b.cod_univend 
											WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
											B.COD_STATUSCRED=3 AND 
                                            B.cod_avulso!=1 AND
											A.COD_EMPRESA = $cod_empresa and
											A.COD_CLIENTE = $cod_cliente ";

                    //fnEscreve($sql4);
                    $qrBuscaBloqueio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql4));
                    //fnEscreve($sql4);

                    $tem_bloqueio = $qrBuscaBloqueio['TEM_BLOQUEIO'];

                    if ($tem_bloqueio > 0) {
                        ?>

                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            Apoiador possui vendas bloqueadas. <br/> 
                            <a href="action.do?mod=<?php echo fnEncode(1099); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
                        </div>
<?php } ?>

<?php
//menu superior - cliente
$abaEmpresa = 1020;
$abaCli = 1423;
if ($popUp != "true") {
    switch ($_SESSION["SYS_COD_SISTEMA"]) {
        case 14: //rede duque
            include "abasClienteDuque.php";
            break;
        default;
            include "abasClienteConfig.php";
            break;
    }
}
?>

                    <div class="login-form" style="padding: 0; margin: 0;">

                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=<?php echo fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">

                            <fieldset>
                                <legend>Dados Gerais</legend>

                                <div class="row">

                                    <!-- fim bloco dados basicos -->
                                    <div class="col-xs-9">

                                        <!--Apoiador é Funcionário / Permite Troca de Produtos -->
                                        <input type="hidden" name="LOG_ESTATUS" id="LOG_ESTATUS" value="N" />
                                        <input type="hidden" name="LOG_FUNCIONA" id="LOG_FUNCIONA" value="N" />
                                        <input type="hidden" name="LOG_TROCAPROD" id="LOG_TROCAPROD" value="N" />

<?php if ($log_categoria == "S") { ?>
                                            <div class="row">
                                                <div class="col-xs-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label"><b>Categoria do Apoiador</b></label>
                                                        <div class="push5"></div>
                                                        <span class="label label-pill label-info f14"><i class="fa fa-bookmark"></i> &nbsp; <?php echo $nom_faixacat; ?></span>															
                                                    </div>				
                                                </div>
                                            </div>
                                            <div class="push10"></div>
<?php } ?>				

                                        <div class="row">

                                            <div class="col-xs-2">
                                                <label for="inputName" class="control-label"><b>Código</b></label><br/>
                                                <span class="registro"><?php echo $cod_cliente; ?></span>
                                            </div>

                                            <div class="col-xs-8">
                                                <label for="inputName" class="control-label"><b>Nome do Apoiador</b></label><br/>
                                                <span class="registro"><?php echo $nom_usuario; ?></span>
                                            </div>

                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Apelido</b></label><br/>
                                                    <span class="registro"><?php echo $des_apelido; ?></span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="push10"></div>

                                        <div class="row">

                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>CNPJ/CPF</b></label><br/>
                                                    <span class="registro"><?php echo fnCompletaDoc($num_cgcecpf, 'F'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>RG</b></label><br/>
                                                    <span class="registro"><?php echo $num_rgpesso; ?></span>
                                                </div>
                                            </div>					

                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Nascimento</b></label><br/>
                                                    <span class="registro"><?php echo $dat_nascime; ?></span>
                                                </div>
                                            </div>													

                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Estado Civil</b></label><br/>
                                                    <span class="registro">
<?php
$sql = "select DES_ESTACIV from estadocivil where cod_estaciv = $cod_estaciv ";
$qrListaEstCivil = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
$des_estaciv = $qrListaEstCivil['DES_ESTACIV'];
echo $des_estaciv;
?>
                                                    </span>
                                                </div>
                                            </div>													

                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Sexo</b></label><br/>
                                                    <span class="registro">
<?php
$sql = "select COD_SEXOPES, DES_SEXOPES from sexo where COD_SEXOPES = $cod_sexopes ";
$qrListaSexo = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
$des_sexopes = $qrListaSexo['DES_SEXOPES'];
echo $des_sexopes;
?>
                                                    </span>	
                                                </div>
                                            </div>

                                        </div>

                                        <div class="push10"></div>

                                        <div class="row">

                                            <!-- <div class="col-xs-2" hidden="">
                                                    <div class="form-group">
                                                            <label for="inputName" class="control-label"><b>Tipo do Cliente </b></label><br/>
                                                                    <span class="registro">
<?php
$sql = "select DES_TIPOCLI from sexo where COD_TIPOCLI = $cod_tipocli ";
$qrListaTipoCli = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
$des_tipocli = $qrListaTipoCli['DES_TIPOCLI'];
echo $des_tipocli;
?>
                                                                    </span>
                                                    </div>
                                            </div> -->													

                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Profissão </b></label><br/>
                                                    <span class="registro">
<?php
$sql = "select DES_PROFISS from PROFISSOES where cod_profiss = $cod_profiss ";
$qrListaProfi = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
$des_profiss = $qrListaProfi['DES_PROFISS'];
echo $des_profiss;
?>
                                                    </span>
                                                </div>
                                            </div>	

                                            <div class="col-xs-5">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Nome do Pai</b></label><br/>
                                                    <span class="registro"><?php echo $nom_pai; ?></span>															
                                                </div>
                                            </div>

                                            <div class="col-xs-5">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Nome da Mãe</b></label><br/>
                                                    <span class="registro"><?php echo $nom_mae; ?></span>															
                                                </div>
                                            </div>

                                        </div>

                                        <div class="push10"></div>

                                        <div class="row">

                                            <div class="col-xs-5">
                                                <label for="inputName" class="control-label"><b>Nome do Apoiador</b></label><br/>
                                                <span class="registro"><?php echo $nom_indicad; ?></span>																												
                                            </div>

                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b>Data da Indicação</b></label><br/>
                                                    <span class="registro"></span>															
                                                </div>
                                            </div>
                                            <?php
                                            if($cod_empresa == 136){
                                            ?>
                                            <div class="col-xs-2">
                                                <label for="inputName" class="control-label"><b>Apoiadores no Município</b></label><br/>
                                                <span class="registro"><?= fnValor($tot_apoiadores, 0) ?></span>																												
                                            </div>

                                            <div class="col-xs-2">
                                                <label for="inputName" class="control-label"><b>Votos no Município</b></label><br/>
                                                <span class="registro"><?= fnValor($tot_votos, 0) ?></span>																												
                                            </div>
                                            <?php
                                            }
                                            ?>

                                        </div>

                                        <!-- fim bloco dados basicos -->
                                    </div>

                                    <!-- bloco foto  -->
                                    <div class="col-xs-3 text-center">

<?php
$sql = "SELECT * FROM FOTO_APOIADOR WHERE COD_CLIENTE = $cod_cliente";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$qrFoto = mysqli_fetch_assoc($arrayQuery);

if (isset($qrFoto)) {
    $nom_arquivo = 'media/clientes/' . $cod_empresa . '/perfil/' . $qrFoto['NOM_ARQUIVO'] . '?rnd=';
} else {
    $nom_arquivo = "media/clientes/" . $cod_empresa . "/default-user-avatar.png?rnd=";
}
?>
                                        <div id="div_perfil">
                                            <img id="foto_perfil" class="foto" alt="Sem imagem">
                                        </div>
                                        <script type="text/javascript">
                                            var url = "<?= $nom_arquivo ?>" + Math.random();
                                            $('#foto_perfil').attr('src', url);
                                        </script>

                                    </div>
                                    <!-- fim bloco foto  -->

                                </div>												

                            </fieldset>	

                            <!-- <div class="push10"></div> -->

                            <fieldset>
                                <legend>Comunicação</legend> 

                                <div class="row">

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>e-Mail</b></label><br/>
                                            <span class="registro"><?php echo $des_emailus; ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Contato</b></label><br/>
                                            <span class="registro"><?php echo $des_contato; ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Telefone Principal</b></label><br/>
                                            <span class="fone"><?php fnCorrigeTelefone($num_telefon); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Telefone Celular</b></label><br/>
                                            <span class="sp_celphones"><?php fnCorrigeTelefone($num_celular); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Telefone Comercial</b></label><br/>
                                            <span class="fone"><?php fnCorrigeTelefone($num_comercial); ?></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="push10"></div>

                                <div class="row">

                                    <div class="col-xs-1">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>e-Mail</b></label><br/>
                                            <span class="registro"><?php echo $check_mail; ?></span>
                                        </div>				
                                    </div>												

                                    <div class="col-xs-1">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>SMS</b></label><br/>
                                            <span class="registro"><?php echo $check_sms; ?></span>
                                        </div>				
                                    </div>

                                    <div class="col-xs-1">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Telemarketing</b></label><br/>
                                            <span class="registro"><?php echo $check_telemark; ?></span>
                                        </div>				
                                    </div>

                                    <div class="col-xs-1">
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Data de Cadastro</b></label><br/>
                                            <span class="registro"><?php echo $dat_cadastr; ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Colaborador que Cadastrou</b></label><br/>
<?php
$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
?>
                                            <span class="registro"><?php echo $qrUsu['NOM_USUARIO']; ?></span>
                                        </div>
                                    </div>

                            </fieldset>

                            <!-- <div class="push10"></div> -->

                            <?php if ($des_coment != "") { ?>

                                <fieldset>
                                    <legend>Observação</legend>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group"><br/>
                                                <span class="registro"><?= $des_coment ?></span>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

<?php } ?>

                            <!-- <div class="push10"></div> -->

<?php
$sql = "SELECT TF.DES_TPFILTRO, FC.DES_FILTRO FROM CLIENTE_FILTROS CF 
													LEFT JOIN FILTROS_CLIENTE FC ON FC.COD_FILTRO = CF.COD_FILTRO
													LEFT JOIN TIPO_FILTRO TF ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
													WHERE CF.COD_CLIENTE = $cod_usuario
													ORDER BY TF.NUM_ORDENAC";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

if (mysqli_num_rows($arrayQuery) > 0) {
    $countFiltros = 0;
    ?>
                                <fieldset>
                                    <legend>Filtros</legend>
                                    <div class="row">
                                <?php
                                while ($qrFiltro = mysqli_fetch_assoc($arrayQuery)) {
                                    ?>

                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label"><b><?= $qrFiltro['DES_TPFILTRO'] ?></b></label><br/>
                                                    <span class="registro"><?= $qrFiltro['DES_FILTRO'] ?></span>
                                                </div>
                                            </div>

        <?php
        if ($countFiltros == 3) {
            echo '<div class="push10"></div>';
            $countFiltros = 0;
        } else {
            $countFiltros++;
        }
    }
    ?>
                                    </div>
                                </fieldset>

                                <!-- <div class="push10"></div> -->
    <?php
}
?>	

                            <fieldset>
                                <legend>Localização</legend> 

                                <div class="row">									


                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Endereço</b></label><br/>
                                            <span class="registro"><?php echo $des_enderec; ?></span>
                                        </div>
                                    </div>	

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Número</b></label><br/>
                                            <span class="registro"><?php echo $num_enderec; ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Complemento</b></label><br/>
                                            <span class="registro"><?php echo $des_complem; ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Bairro</b></label><br/>
                                            <span class="registro"><?php echo $des_bairroc; ?></span>
                                        </div>
                                    </div>

                                    <div class="push10"></div>

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>CEP</b></label><br/>
                                            <span class="registro"><?php echo $num_cepozof; ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Cidade</b></label><br/>
                                            <span class="registro"><?php echo $nom_cidadec; ?></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label"><b>Estado</b></label><br/>
                                            <span class="registro"><?php echo $cod_estadof; ?></span>
                                        </div>
                                    </div>


                                </div>			

                            </fieldset>	

                            <!-- <div class="push10"></div>	 -->

                            <fieldset>
                                <legend>Follow Up</legend> 

                                <div class="row">

                                    <div class="col-xs-12">

                                        <table class="table ">
                                            <thead>
                                                <tr>
                                                    <th class="control-label">Data</th>
                                                    <th class="control-label">Título</th>
                                                    <th class="control-label">Descrição</th>
                                                </tr>
                                            </thead>
                                            <tbody id="relatorioConteudo">

                                                <?php
                                                //setando locale da data
                                                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                                date_default_timezone_set('America/Sao_Paulo');

                                                $sql2 = "SELECT FC.*, CA.DES_CLASSIFICA FROM FOLLOW_CLIENTE FC 
															LEFT JOIN CLASSIFICA_ATENDIMENTO CA ON CA.COD_CLASSIFICA = FC.COD_CLASSIFICA 
															WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = $cod_cliente
															ORDER BY FC.DAT_CADASTR DESC";

                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
                                                while ($qrFollow = mysqli_fetch_assoc($arrayQuery)) {

                                                    if ($qrFollow['COD_DESAFIO'] != 0) {
                                                        $titulo = $qrFollow['DES_CLASSIFICA'];
                                                    } else {
                                                        $titulo = $qrFollow['NOM_FOLLOW'];
                                                    }

                                                    $mes = strtoupper(strftime('%B', strtotime($qrFollow['DAT_CADASTR'])));
                                                    $mes = substr("$mes", 0, 3);
                                                    ?>

                                                    <tr>
                                                        <td class="registro"><time><small><?php echo fnDataFull($qrFollow['DAT_CADASTR']); ?></small></time></td>
                                                        <td class="registro"><?= $titulo ?></td>
                                                        <td class="registro"><?= $qrFollow['DES_COMENT'] ?></td>
                                                    </tr>

    <?php
}
?>

                                            </tbody>
                                        </table>

                                    </div>

                                </div>			

                            </fieldset>

                            <fieldset>
                                <legend>Atendimento do Apoiador</legend>

                                <div class="push20"></div>

                                <div class="col-lg-12" style="padding:0;">

                                    <div class="no-more-tables">

                                        <form name="formLista">

                                            <table class="table table-bordered table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th><small>Atendimento</small></th>
                                                        <th><small>Título</small></th>
                                                        <th><small>Solicitante</small></th>
                                                        <th><small>Solicitação</small></th>
                                                        <th><small>Prioridade</small></th>
                                                        <th><small>Status</small></th>
                                                        <th><small>Cadastro</small></th>
                                                        <th><small>Prazo</small></th>
                                                        <th><small>Atualizado</small></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="relatorioConteudo">

                                                    <?php
                                                    // $sqlCount = "SELECT COD_ATENDIMENTO FROM ATENDIMENTO_CHAMADOS AC 
                                                    //  				WHERE AC.COD_EMPRESA = $cod_empresa
                                                    //  				AND (AC.COD_SOLICITANTE = $cod_cliente OR AC.COD_USURES = $cod_cliente OR AC.COD_USUARIOS_ENV IN($cod_cliente))									  				
                                                    // 			";
                                                    // //fnEscreve($sqlCount);
                                                    // $retorno = mysqli_query(connTemp($cod_empresa,''),$sqlCount);
                                                    // $total_itens_por_pagina = mysqli_num_rows($retorno);
                                                    // $numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
                                                    // //variavel para calcular o início da visualização com base na página atual
                                                    // $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													

                                                    $sqlSac = "SELECT AC.*, AT.DES_TPSOLICITACAO, 
																AP.DES_PRIORIDADE, AP.DES_COR AS COR_PRIORIDADE, AP.DES_ICONE AS ICO_PRIORIDADE,
																AST.ABV_STATUS, AST.DES_COR AS COR_STATUS, AST.DES_ICONE AS ICO_STATUS 
																FROM ATENDIMENTO_CHAMADOS AC
																LEFT JOIN ATENDIMENTO_PRIORIDADE AP ON AP.COD_PRIORIDADE = AC.COD_PRIORIDADE
																LEFT JOIN ATENDIMENTO_STATUS AST ON AST.COD_STATUS = AC.COD_STATUS
																LEFT JOIN ATENDIMENTO_TPSOLICITACAO AT ON AT.COD_TPSOLICITACAO = AC.COD_TPSOLICITACAO
																WHERE AC.COD_EMPRESA = $cod_empresa
												  				AND FIND_IN_SET('$cod_cliente', AC.COD_CLIENTES_ENV)
                                                                AND AC.COD_CLIENTES_ENV != 0
																ORDER BY AC.COD_ATENDIMENTO DESC
																";
                                                    // fnEscreve($sqlSac);

                                                    $arrayQuerySac = mysqli_query(connTemp($cod_empresa, ''), $sqlSac);

                                                    $count = 0;
                                                    $adm = "";
                                                    $entrega = "";
                                                    while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {

                                                        if ($qrSac['LOG_ADM'] == 'S') {
                                                            $adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
                                                        } else {
                                                            $adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
                                                        }

                                                        $count++;

                                                        $sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_SOLICITANTE]) AS NOM_SOLICITANTE,
																				(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
                                                        $qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
                                                        //fnEscreve($sqlUsuarios);										  

                                                        if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
                                                            $entrega = "";
                                                        } else {
                                                            $entrega = fnDataShort($qrSac['DAT_ENTREGA']);
                                                        }

                                                        if ($qrSac['DAT_INTERAC'] != "") {
                                                            if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
                                                                $atualizado = "Hoje";
                                                            } else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
                                                                $atualizado = "Ontem";
                                                            } else {
                                                                $atualizado = fnDataFull($qrSac['DAT_INTERAC']);
                                                            }
                                                        } else {
                                                            $atualizado = "";
                                                        }

                                                        //$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
                                                        // fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
                                                        ?>

                                                        <tr>
                                                            <td class="text-center">
                                                                <small>
    <?= $qrSac['COD_ATENDIMENTO'] ?>
                                                                </small>
                                                            </td>
                                                            <td><small><?= $qrSac['NOM_CHAMADO'] ?></small></td>
                                                            <td><small><?= $qrNomUsu['NOM_SOLICITANTE'] ?></small></td>
                                                            <td><small><?= $qrSac['DES_TPSOLICITACAO'] ?></small></td>

                                                            <td class="text-center">
                                                                <small>
                                                                    <p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>"> 
                                                                        <span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
                                                                        <!-- &nbsp; <?php echo $qrSac['DES_PRIORIDADE']; ?> -->
                                                                    </p>
                                                                </small>
                                                            </td>

                                                            <td class="text-center">
                                                                <small>
                                                                    <p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>"> 
                                                                        <span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
                                                                        &nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
                                                                    </p>
                                                                </small>
                                                            </td>

                                                            <td class="text-center"><small><?= fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
                                                            <td class="text-center"><small><?= $entrega ?></small></td>
                                                            <td class="text-center"><small><?= $atualizado ?></small></td>

                                                        </tr>
    <?php
}
?> 

                                                </tbody>
                                                <!-- <tfoot>
                                                        <tr>
                                                          <th class="" colspan="100">
                                                                <center><ul id="paginacao" class="pagination-sm"></ul></center>
                                                          </th>
                                                        </tr>
                                                </tfoot> -->												
                                            </table>



                                        </form>

                                        <div class="push10"></div>	

                                    </div>

                                </div>

                            </fieldset>


                            <div class="hidden-print">

                                <div class="push10"></div>
                                <hr>	
                                <div class="form-group text-right col-lg-12">

                                    <a href="javascript:window.print();" class="btn btn-info" ><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir Cadastro </a>

                                </div>

                            </div>

                            <input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
                            <input type="hidden" name="REFRESH_FILTRO" id="REFRESH_FILTRO" value="N">
                            <input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
                            <input type="hidden" name="COD_TPFILTRO" id="COD_TPFILTRO" value="">
                            <input type="hidden" name="idS" id="idS" value="">

                            <input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?= $countFiltros ?>">
                            <input type="hidden" name="TIP_CLIENTE" id="TIP_CLIENTE" value="<?php echo $tip_cliente; ?>">
                            <input type="hidden" name="COD_CHAVECO" id="COD_CHAVECO" value="<?php echo $cod_chaveco; ?>">
                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

                        </form>

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

    <script type="text/javascript">

        $(document).ready(function () {

            var SPMaskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
                    spOptions = {
                        onKeyPress: function (val, e, field, options) {
                            field.mask(SPMaskBehavior.apply({}, arguments), options);
                        }
                    };

            $('.sp_celphones').mask(SPMaskBehavior, spOptions);

            //mascaraCpfCnpj($("#formulario #NUM_CGCECPF"));
            //chosen
            $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
            $('#formulario').validator();

            //modal close
            $('.modal').on('hidden.bs.modal', function () {

                if ($('#REFRESH_CLIENTE').val() == "S") {
                    var newCli = $('#NOVO_CLIENTE').val();
                    window.location.href = "action.php?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
                    $('#REFRESH_PRODUTOS').val("N");
                }

                if ($('#REFRESH_FILTRO').val() == "S") {

                    $.ajax({
                        method: 'POST',
                        url: 'ajxTipoFiltro.php?idS=' + $('#idS').val(),
                        data: {COD_EMPRESA:<?= $cod_empresa ?>, COD_TPFILTRO: $('#COD_TPFILTRO').val()},
                        beforeSend: function () {
                            $('#relatorioFiltro_' + $('#idS').val()).html('<div class="loading" style="width: 100%;"></div>');
                        },
                        success: function (data) {
                            // console.log(data);
                            $('#relatorioFiltro_' + $('#idS').val()).html(data);
                            $('#REFRESH_FILTRO').val("N");
                        }
                    });

                }

            });

        });

        //retorno combo multiplo - master
        $("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
        var sistemasMst = "<?php echo $cod_multemp; ?>";
        var sistemasMstArr = sistemasMst.split(',');
        //opções multiplas
        for (var i = 0; i < sistemasMstArr.length; i++) {
            $("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
        }
        $("#formulario #COD_MULTEMP").trigger("chosen:updated");


    </script>	
