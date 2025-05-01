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

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao == 'CAD') {

            $sql3 = "SELECT COD_BEM FROM CONTRATO_BLOCK_COMPL WHERE COD_EMPRESA = '$cod_empresa' AND COD_CLIENTE = '$cod_cliente' AND NUM_CONTRATO = '$num_contrato' AND DAT_EXCLUSA IS NULL";
            $arrayQuery3 = mysqli_query(connTemp($cod_empresa,''),$sql3);

            $bens_existem = array();
            while ($qrBusca2 = mysqli_fetch_assoc($arrayQuery3)) {
                $bens_existem[] = $qrBusca2['COD_BEM'];
            }

            $bem_delete = array_diff($bens_existem, $_POST['checkboxGroup']);

            if($bem_delete){
                foreach($bem_delete as $cod_bem) {
                    $sql = "DELETE FROM CONTRATO_BLOCK_COMPL WHERE COD_CLIENTE = '$cod_cliente' AND NUM_CONTRATO = '$num_contrato' AND COD_BEM = '$cod_bem'";
                    mysqli_query($conn, $sql);
                }
            }
            
            $cod_bens_ativo = "";
            foreach ($_POST['checkboxGroup'] as $cod_bem) {

                $qtd_hect_uso = fnLimpaCampoZero(fnValorSql($_POST['QTD_HECT_USO_'.$cod_bem]));

                $qtd_hect_bem = fnLimpaCampoZero($_POST['QTD_AREATOT_'.$cod_bem]);
                $cod_bem = fnLimpaCampoZero($cod_bem);
                $cod_bens_ativo .= fnEncode($cod_bem) . ",";

                $sql2 = "SELECT * FROM CONTRATO_BLOCK_COMPL WHERE COD_EMPRESA = '$cod_empresa' AND COD_BEM = '$cod_bem' AND COD_CLIENTE = '$cod_cliente' AND NUM_CONTRATO = '$num_contrato' AND DAT_EXCLUSA IS NULL LIMIT 1";
                $arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

                $qrBuscaContrato = mysqli_fetch_assoc($arrayQuery2);

                if($qrBuscaContrato){
                    $cod_infocpl = $qrBuscaContrato['COD_INFOCPL'];

                    $sql = "UPDATE CONTRATO_BLOCK_COMPL SET
                    QTD_HECT_USO = '$qtd_hect_uso',
                    COD_ALTERAC = '$cod_usucada',
                    DAT_ALTERAC = NOW()
                    WHERE COD_INFOCPL = '$cod_infocpl' AND COD_EMPRESA = '$cod_empresa' AND COD_BEM = '$cod_bem' AND COD_CLIENTE = '$cod_cliente' AND NUM_CONTRATO = '$num_contrato'";

                }else{
                   $sql = "INSERT INTO CONTRATO_BLOCK_COMPL (
                    COD_CLIENTE,
                    NUM_CONTRATO,
                    COD_EMPRESA,
                    QTD_HECT_USO,
                    QTD_HECT_BEM,
                    COD_BEM,
                    COD_USUCADA
                    ) VALUES (
                    '$cod_cliente',
                    '$num_contrato',
                    '$cod_empresa',
                    '$qtd_hect_uso',
                    '$qtd_hect_bem',
                    '$cod_bem',
                    '$cod_usucada'
                )";
                }

                $arrayInsert =  mysqli_query(connTemp($cod_empresa, ''), $sql);

                if (!$arrayInsert) {

                    $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAgenda, $nom_usuarioSESSION);
                }

                if ($cod_erro == 0 || $cod_erro ==  "") {
                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                } else {
                    $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                }
            }

            if($cod_bens_ativo){
                $num_contrato = fnEncode($num_contrato);
                $cod_cliente = fnEncode($cod_cliente);
                ?>

                <script>
                    window.location.href="https://<?=$_SERVER[HTTP_HOST]?>/action.do?mod=<?=$_GET["mod"]?>&id=<?=$_GET["id"]?>&idC=<?=$cod_cliente?>&idCt=<?=$num_contrato?>&idBem=<?=$cod_bens_ativo?>&fluxo=<?=$_GET["fluxo"]?>&passo=<?=$_GET["passo"]?>";
                </script>

                <?php
            }
        }

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

    //fnEscreve($sql);

        //fnEscreve($sql);
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
                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50"></th>
                                            <th>Cód. Imovel</th>
                                            <th>Matrícula</th>
                                            <th>Nome</th>
                                            <th>Localização</th>
                                            <th class='text-right'>Área Total (ha)</th>
                                            <th class='text-right'>Área Garantia (ha)</th>
                                            <th class='text-right'>Área Disponível (ha)</th>
                                            <th class='text-center'>Área a Utilizar (ha)</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $sql = "SELECT 
                                        BC.QTD_AREATOT,
                                        IFNULL(SUM(CC.QTD_HECT_USO),0) AREA_GARANTIA,
                                        BC.QTD_AREATOT-IFNULL(SUM(CC.QTD_HECT_USO),0) AS AREA_DISPONIVEL,
                                        BC.NUM_MATRICU,
                                        BC.UF,
                                        BC.NOM_MUNICIPIO,
                                        BC.COD_MUNICIPIO,
                                        BC.COD_BEM,
                                        BC.DES_NOMEBEM
                                        FROM (
                                            SELECT DISTINCT BC.*,
                                            CL.QTD_AREATOT,                                  
                                            CL.NUM_MATRICU,
                                            ESTADO.UF,
                                            MN.NOM_MUNICIPIO,
                                            CL.COD_MUNICIPIO                                       
                                            FROM BENS_CLIENTE AS BC 
                                            inner JOIN BENS_IMOVEIS AS CL ON BC.COD_BEM = CL.COD_BEM                
                                            inner JOIN ESTADO AS ESTADO ON CL.COD_ESTADO = ESTADO.COD_ESTADO
                                            LEFT  JOIN MUNICIPIOS AS MN ON CL.COD_MUNICIPIO = MN.COD_MUNICIPIO
                                            WHERE BC.COD_EMPRESA = '$cod_empresa'
                                            AND BC.COD_TIPO = '$cod_tipobem'
                                            AND BC.DAT_EXCLUSA IS NULL
                                            AND BC.COD_CLIENTE = '$cod_cliente'
                                            ORDER BY BC.COD_BEM
                                            )BC
                                        LEFT  JOIN contrato_block_compl CC ON CC.COD_BEM=BC.COD_BEM AND CC.COD_CLIENTE=BC.COD_CLIENTE  AND  CC.QTD_HECT_USO > 0 
                                        AND CC.DAT_EXCLUSA IS NULL GROUP BY BC.COD_BEM ";

                                        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                                        $count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            $count++;

                                            $sql2 = "SELECT * FROM CONTRATO_BLOCK_COMPL WHERE COD_EMPRESA = $cod_empresa AND COD_BEM = ".$qrBusca['COD_BEM']." AND COD_CLIENTE = $cod_cliente AND NUM_CONTRATO = $num_contrato AND DAT_EXCLUSA IS NULL LIMIT 1";

                                            $arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);

                                            $qrBuscaContrato = mysqli_fetch_assoc($arrayQuery2);

                                            if($qrBuscaContrato){
                                                $hect_uso = $qrBuscaContrato['QTD_HECT_USO'];
                                                $ativo = "checked";
                                            }else{
                                                $hect_uso = 0;
                                                $ativo = "";
                                            }

                                            if ($arrayQuery3) {
                                                $areaDisponivel = $qrBusca['AREA_DISPONIVEL'];
                                            } else {
                                                $areaDisponivel = $qrBusca['QTD_AREATOT'];

                                            }

                                            echo "
                                            <tr>
                                            <td class='text-center'><input type='checkbox' name='checkboxGroup[]' $ativo value='".$qrBusca['COD_BEM']."' style='width: 15px; height: 15x;'></td>
                                            <td id='COD_BEM_".$qrBusca['COD_BEM']."'>".$qrBusca['COD_BEM']."</td>
                                            <td>".$qrBusca['NUM_MATRICU']."</td>                                                     
                                            <td>".$qrBusca['DES_NOMEBEM']."</td>                                                   
                                            <td>".$qrBusca['NOM_MUNICIPIO']. ", " . $qrBusca['UF']."</td>                                                     
                                            <td class='text-right' id='QTD_AREATO_".$qrBusca['COD_BEM']."'>".fnValor($qrBusca['QTD_AREATOT'],2)."</td>                                                     
                                            <td class='text-right'>".fnValor($qrBusca['AREA_GARANTIA'],2)."</td>                                                   
                                            <td class='text-right'>".fnValor($areaDisponivel,2)."</td>                                                   
                                            <td class='text-right'>
                                            <input type='text' class='form-control input-sm text-right money' name='QTD_HECT_USO_".$qrBusca['COD_BEM']."' id='QTD_HECT_USO_".$qrBusca['COD_BEM']."' style='width: 130px; margin: 0 auto;' value='".fnValor($hect_uso,2)."' oninput='validarQtdHectUso(".$qrBusca['COD_BEM'].")'>
                                            </td>
                                            <input type='hidden' name='QTD_AREATOT_".$qrBusca['COD_BEM']."' id='QTD_AREATOT_".$qrBusca['COD_BEM']."' value='".$qrBusca['QTD_AREATOT']."'>

                                            <input type='hidden' name='AREA_DISPONIVEL_".$qrBusca['COD_BEM']."' id='AREA_DISPONIVEL_".$qrBusca['COD_BEM']."' value='".fnValor($areaDisponivel,2)."'>                                                    
                                            </tr>
                                            ";

                                            $total_area_bens += $qrBusca['QTD_AREATOT'];
                                            $total_area_garantia += $qrBusca['AREA_GARANTIA'];
                                            $total_area_disponivel += $areaDisponivel;
                                            $total_uso_contrato += $hect_uso;
                                        }
                                        ?>

                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th class="text-center"><b><small></small></b></th>
                                                <th class="text-center"><b><small></small></b></th>
                                                <th class="text-center"><b><small></small></b></th>
                                                <th class="text-center"><b><small></small></b></th>
                                                <th class="text-right"><b><small><?php echo fnValor($total_area_bens, 2); ?></small></b></th>
                                                <th class="text-right"><b><small><?php echo fnValor($total_area_garantia, 2); ?></small></b></th>
                                                <th class="text-right"><b><small><?php echo fnValor($total_area_disponivel, 2); ?></small></b></th>
                                                <th class="text-center"><b><small><?php echo fnValor($total_uso_contrato, 2); ?></small></b></th>
                                            </tr>
                                        </tfoot>

                                    </tbody>
                                </table>
                            </div>

                        </div>



                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

                        </div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
                        <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente;?>">
                        <input type="hidden" name="NUM_CONTRATO" id="NUM_CONTRATO" value="<?php echo $num_contrato; ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

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

<script>

    function converterParaNumero(stringNumero) {
        let numeroSemFormato = stringNumero.replace(/\./g, '').replace(',', '.');

        return parseFloat(numeroSemFormato);
    }

    var timeoutId;

    function validarQtdHectUso(index) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(function() {
            var qtdHectUsoElement = document.getElementById('QTD_HECT_USO_' + index);
            var qtdAreaTotElement = document.getElementById('AREA_DISPONIVEL_' + index);

            var qtdHectUso = converterParaNumero(qtdHectUsoElement.value);
            var qtdAreaDispo = converterParaNumero(qtdAreaTotElement.value);

            if (isNaN(qtdHectUso) || qtdHectUso > qtdAreaDispo) {
                alert('A quantidade de hectares em uso não pode ser maior que a área disponível.');
        // Limpar o campo apenas se a condição for verdadeira
                qtdHectUsoElement.value = '';
            } else {
        // Lógica adicional, se necessário
            }
        }, 500)
    };


</script>