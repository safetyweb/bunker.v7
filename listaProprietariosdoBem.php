<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $num_contrato = fnLimpaCampoZero($_REQUEST['NUM_CONTRATO']);
        $qtd_hect_uso = fnLimpaCampoZero($_REQUEST['QTD_HECT_USO']);
        $qtd_hect_bem = fnLimpaCampoZero($_REQUEST['QTD_AREATOT']);
        $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_status = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
    }
}

//busca dados da url    
if (is_numeric(fnLimpaCampoZero(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);

    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}

if(is_numeric(fnLimpaCampoZero(fnDecode($_GET['idC'])))){

    $cod_cliente = fnDecode($_GET['idC']);
    //fnEscreve2($cod_cliente);
    $sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES where COD_CLIENTE = '". $cod_cliente . "' AND COD_EMPRESA = '". $cod_empresa . "' ";

    //fnEscreve2($sql);
    $query = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $qrBuscaCliente = mysqli_fetch_assoc($query);

    if(isset($query)) {
        $cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
        $nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
    }else{
        $cod_cliente = 0;
    }
}

if(is_numeric(fnLimpaCampoZero(fnDecode($_GET['idCt'])))){

    $num_contrato = fnDecode($_GET['idCt']);

    $sql = "SELECT NUM_CONTRATO, COD_STATUS, COD_TIPOBEM FROM CONTRATO_BLOCK where NUM_CONTRATO = '". $num_contrato . "' AND COD_CLIENTE = '". $cod_cliente . "' AND COD_EMPRESA = '". $cod_empresa . "' ";

    $query = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $qrBuscaContrato = mysqli_fetch_assoc($query);

    if(isset($query)) {
        $num_contrato = $qrBuscaContrato['NUM_CONTRATO'];
        $cod_tipobem = $qrBuscaContrato['COD_TIPOBEM'];
        $cod_status = $qrBuscaContrato['COD_STATUS'];

        if($cod_status == 1){
            $status = "Proposta";
        }else{
            $status = "";
        }
    }else{
        $cod_status = 0;
    }
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
                    <span class="text-primary"><?php echo $NomePg; ?></span>
                </div>
            </div>

            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <div class="push30"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <?php include "bensHeader.php"; ?>

                        <div class="push10"></div>

                        <div class="portlet-body">

                            <div class="login-form">
                                <div class="col-lg-12">

                                    <div class="no-more-tables">

                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="50"></th>
                                                    <th class='text-right'>Cód. Bem</th>
                                                    <th class='text-right'>Nome</th>
                                                    <th class='text-right'>Área Informada</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                $lista_bem = explode(",",$_GET['idBem']);

                                                if (end($lista_bem) === '') {
                                                    array_pop($lista_bem);
                                                }

                                                $cod_bem = "";

                                                foreach ($lista_bem as $itens) {
                                                    $cod_bem .= "'" . fnDecode($itens) . "',";
                                                }

                                                $cod_bem = rtrim($cod_bem, ',');

                                                $sql = "SELECT * FROM BENS_CLIENTE WHERE 
                                                COD_EMPRESA = '$cod_empresa' AND 
                                                COD_CLIENTE = '$cod_cliente' AND
                                                COD_BEM IN ($cod_bem)";

                                                $query = mysqli_query($conn, $sql);

                                                $count = 0;
                                                while ($result = mysqli_fetch_assoc($query)) {
                                                    $count++;

                                                    $bem = $result['COD_BEM'];

                                                    ?>

                                                    <thead>
                                                        <tr id="bloco_<?php echo $result['COD_BEM']; ?>">
                                                            <td width="5%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $result['COD_BEM']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                                            </td>
                                                            <td width="5%" class='text-right'><?php echo $result['COD_BEM']; ?></td>
                                                            <td width="5%" class='text-right'><?php echo $result['DES_NOMEBEM']; ?></td>
                                                            <td width="5%" class='text-right'><?php echo $result['VAL_INFORMADO']; ?></td>
                                                        </tr>

                                                        <thead class='no-weight' style='display:none; background-color: #fff;' id='abreDetail_<?php echo $result['COD_BEM']; ?>'>
                                                        </thead>
                                                    </thead>
                                                </tbody>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>

                                </div>

                                <input type="hidden" name="opcao" id="opcao" value="">
                                <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                                <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
                                <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente;?>">
                                <input type="hidden" name="NUM_CONTRATO" id="NUM_CONTRATO" value="<?php echo $num_contrato; ?>">
                                <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

                                <div class="push5"></div>
                            </div>
                        </div>
                    </form>

                    <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<div class="push20"></div>

<script>

    function abreDetail(codBem) {
        RefreshCampanha(<?=$cod_empresa;?>, codBem);
    }

    function RefreshCampanha(idEmp, codBem) {
        var idItem = $('#abreDetail_' + codBem);
        console.log(idItem);

        if (!idItem.is(':visible')) {
            $.ajax({
                type: "POST",
                url: "relatorios/ajxListaProprietariosBem.do?codBem="+codBem+"&id=<?php echo fnEncode($cod_empresa); ?>",
                data: $("#formulario").serialize(),
                beforeSend: function() {
                    $("#abreDetail_" + codBem).html('<div class="loading" style="width: 100%;"></div>');


                },
                success: function(data) {
                    $("#abreDetail_" + codBem).html(data);
                //console.log(data);
                },
                error: function(data) {
                    $("#abreDetail_" + codBem).html(data);
                }
            });

            idItem.show();
            $('#bloco_' + codBem).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
            idItem.hide();
            $('#bloco_' + codBem).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
        }
    }

</script>