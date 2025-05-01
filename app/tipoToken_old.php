<?php 
        include 'header.php'; 
        $tituloPagina = "Token";
        include "navegacao.php";
        include "controleSession.php";
        include '_system/Lista_oferta.php';

        $cpf = $_SESSION['usuario'];

        if( $_SERVER['REQUEST_METHOD']=='POST' ){

            $des_placa = fnLimpaCampo($_REQUEST['DES_PLACA']);
            $des_tokem = fnLimpaCampo($_REQUEST['DES_TOKEM']);
            $cod_cliente = fnLimpaCampo($_REQUEST['COD_CLIENTE']);
            $des_tipo = fnLimpaCampo($_REQUEST['DES_TIPO']);

            if ($des_placa != "" && $des_tokem != "") {

?>
<link href="libs/jquery-confirm.min.css" rel="stylesheet"/>
<script src="libs/jquery-confirm.min.js"></script>
<?php 

                if($des_tipo == "desc"){

                    $gravatokem="UPDATE TOKEM SET 
                                        DES_PLACA = '$des_placa',
                                        LOG_USADO = 'S',
                                        DAT_USADO = NOW(),
                                        COD_PDV = 'EXPIRADO'
                                 WHERE DES_TOKEM = '$des_tokem' 
                                 AND COD_CLIENTE = $cod_cliente";

                }else{

                    $gravatokem="UPDATE TOKEN_RESGATE SET 
                                        DES_PLACA = '$des_placa' ,
                                        COD_VENDAPDV = 'EXPIRADO',
                                        COD_MSG = 2
                                 WHERE DES_TOKEN = '$des_tokem' 
                                 AND NUM_CGCECPF = '$cod_cliente'";

                }
                // echo "$gravatokem";
                // exit();
                mysqli_query(connTemp(19,''), $gravatokem); 

?>
<script type="text/javascript">
    $(function(){
        $.alert({
            title: 'Aviso',
            content: 'Token expirado.',
        }); 
    });
</script>
<?php 

            }

        }

        @$cod_cliente=$_SESSION["COD_RETORNO"];
        // fnEscreve($cod_cliente);  
        @$cod_entidad=$_SESSION["cod_entidad"];

        $sql2="SELECT COD_CLIENTE, NUM_CGCECPF FROM clientes WHERE NUM_CARTAO=$_SESSION[usuario] and COD_EMPRESA=$cod_empresa";
        $qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql2)); 

        $cod_cliente_consulta = $qrBuscaCliente['COD_CLIENTE'];


        $sql = "CALL total_wallet('$cod_cliente_consulta', '$cod_empresa')";
                    
        //fnEscreve($sql);
    
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
        $qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);
        
        
        if (isset($arrayQuery)){
            
            $total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
            $total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
            $credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
            $credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
            $credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
            $credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
        }else{
            
            $total_creditos = 0;
            $total_debitos = 0;
            $credito_disponivel = 0;
            $credito_aliberar = 0;
            $credito_expirados = 0;
            $credito_bloqueado = 0;
            
        }

        $casasDec = 0;

        $usuEncrypt = fnEncode($_SESSION["usuario"]);
        $tipoDesc = fnEncode("desc");
        $tipoResg = fnEncode("resg");

    ?>

<style>
    .radio-inline{
        padding-left: 0;
    }

    .placa{
        list-style-type: none!important;
    }
    
    .chec-radio .radio-inline .clab {
        cursor: pointer;
        background: #e7e7e7;
        padding: 7px 20px;
        color: #2c3e50;
    }
    .chec-radio label.radio-inline input[type="radio"] {
        display: none;
    }
    .chec-radio label.radio-inline input[type="radio"]:checked+div {
        color: #fff;
        background-color: #2c3e50;
    }
    /*.chec-radio label.radio-inline input[type="radio"]:checked+div:before {
        content: "\e013";
        margin-right: 5px;
        font-family: 'Glyphicons Halflings';
    }*/
    .shadow{
       -webkit-box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
        -moz-box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
        box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
        width: 100%;
        border-radius: 5px;
    }
    .carousel{
        border-radius: 10px 10px 10px 10px;
        overflow: hidden;
    }
    .carousel-caption{
        /*background-color: rgba(0,0,0,0.2);*/
        border-radius: 30px 30px 30px 30px;
    }
    .contorno{
      color: black;
      -webkit-text-fill-color: white; /* Will override color (regardless of order) */
      /*-webkit-text-stroke-width: 0.5px;
      -webkit-text-stroke-color: white;*/
      text-shadow: 1px 1px black;
    }

    .separador{
        border: unset;
        max-width: unset;
        width: unset;
        border-top: 1px solid <?=$cor_textfull?>; 
        margin: 0; 
        padding: 0; 
    }

    .btn-primary{
        font-weight: normal;
    }


</style>    
        
        <div class="container text-center">

            <div class="push50"></div>

            <div class="row">
                  <div class="col-xs-12 text-center">
                    <h4 style="font-weight: 900!important;">SEUS VEÍCULOS CADASTRADOS</h4>
                </div>
                <div class="col-md-12">
                    <hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
                </div>
            </div>

            <div class="push30"></div>
      
            <div class="row" id="placasConteudo">                                           

                    <?php

                        $sql = "SELECT COD_VEICULOS, DES_PLACA FROM VEICULOS WHERE COD_CLIENTE_EXT = $_SESSION[usuario] AND COD_EMPRESA = $cod_empresa AND TRIM(DES_PLACA) != '' AND DES_PLACA IS NOT NULL";
                        // fnEscreve($sql);
                        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                        // $loopH = 0;
                        $count = 0;
                        $des_placa = "";

                        while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {

                            if($count == 0){

                                $des_placa = fnEncode($qrVeic['DES_PLACA']);
                                $checked = "checked";
                            }else{
                                $checked = "";
                            }

                        ?>


                            <div class="placa col-xs-12 text-center chec-radio">
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input type="radio" id="DES_PLACA" name="DES_PLACA"  value="<?=fnEncode($qrVeic[DES_PLACA])?>" <?=$checked?> required>
                                        <div class="clab text-muted">
                                            <span class="fa fa-car"></span>
                                            &nbsp; <?=$qrVeic['DES_PLACA']?>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- <div class="col-xs-12 text-center" style="font-size: 20px!important;">
                                <p class="text-muted"><span class="fa fa-car"></span>&nbsp; <?=$qrVeic['DES_PLACA']?></p>
                            </div> -->

                        <?php

                            // if($loopH == 2){
                            //     echo "<div class='push10'></div>";
                            // }else{
                            //     $loopH++;
                            // }

                            $count++;
                        }

                    ?>

                </div>

             
            <div class="push10"></div>

            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 text-center">
                    <?php  
                        if($des_placa != ""){ 
                    ?>
                            <p><b>Para gerar o Token, selecione na lista acima o veículo que será abastecido.</b></p>
                            <?php 
                             
                                if($qrBuscaCliente['NUM_CGCECPF'] == "1734200014"){
                    ?>
                                    <div class="text-left">
                                        <div class="push20"></div>
                                        <label><b>Undade de Preferência</b></label>
                                        <div class="form-group">
                                            <select data-placeholder="Selecione um estado" name="COD_UNIVENDPREF" id="COD_UNIVENDPREF" class="chosen-select-deselect input-sm">
                                                <?php

                                                    $sqlUniv = "SELECT * FROM (

                                                              SELECT 
                                                                     v.COD_UNIVEND, 
                                                                        uni.NOM_FANTASI
                                                                        
                                                              FROM vendas v
                                                              INNER  JOIN unidadevenda uni ON uni.COD_UNIVEND = v.COD_UNIVEND
                                                              WHERE v.cod_cliente = 690594 AND v.cod_empresa = 19 
                                                              ORDER BY v.cod_venda DESC LIMIT 1
                                                            )AS ranked
                                                            UNION ALL
                                                            SELECT unid.COD_UNIVEND,unid.NOM_FANTASI FROM unidadevenda unid WHERE cod_empresa=19 AND unid.LOG_ESTATUS='S'";
                                                    $arrUniv = mysqli_query(connTemp($cod_empresa,''),$sqlUniv);
                                                    while($qrUniv = mysqli_fetch_assoc($arrUniv)){

                                                ?>
                                                        <option value="<?=$qrUniv[COD_UNIVEND]?>"><?=$qrUniv[NOM_FANTASI]?></option>

                                                <?php } ?>                 
                                                                    
                                            </select>
                                            <div class="help-block with-errors f12">Caso você esteja fora da sua unidade de preferência, selecione acima.</div>
                                        </div>
                                    </div>

                    <?php 
                                }

                            if($credito_disponivel > 0){ 

                    ?>
                                <div class="push30"></div>
                                <a href="validaDadosToken.do?key=<?=$_GET[key]?>&t=<?=$rand?>&idp=<?=$des_placa?>&idU=<?=$usuEncrypt?>&tp=<?=$tipoResg?>&cds=<?=$_SESSION[cds]?>" id="btnGeraTokenResgate" class='btn btn-block' style="background-color: #1E8449; color: #fff;"><i class="fal fa-money-bill" aria-hidden="true"></i>&nbsp;&nbsp;Gerar Token de <b>Resgate</b></a>
                    <?php 
                            } 
                    ?>
                            <div class="push20"></div>
                            <div class="push30"></div>
                            <!-- <div class="push10"></div> -->
                            <a href="validaDadosToken.do?key=<?=$_GET[key]?>&t=<?=$rand?>&idp=<?=$des_placa?>&idU=<?=$usuEncrypt?>&tp=<?=$tipoDesc?>&cds=<?=$_SESSION[cds]?>" id="btnGeraToken" class='btn btn-primary btn-block'><i class="fal fa-unlock-alt" aria-hidden="true"></i>&nbsp;&nbsp;Gerar Token de <b>Desconto</b></a>
                    <?php
                        }else{
                    ?>
                            <a href="javascript:void(0)" class='btn btn-default btn-block' disabled><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;&nbsp;Nenhum veículo cadastrado.</a>
                            <div class="push20"></div>
                            <a href="cadVeiculo.do?key=<?=$_GET[key]?>&t=<?=$rand?>&idU=<?=$usuEncrypt?>"><span class="fal fa-external-link"></span>&nbsp;&nbsp;Ir para cadastro de veículos</a>
                    <?php
                        }
                    ?>
                </div>
            </div> 

            <div class="push30"></div>
        
      </div>

    <?php include 'footer.php'; ?>

    <script>

        $(document).ready(function(){

            $('input[type=radio][name=DES_PLACA]').change(function() {
                var secur = "<?=$_GET[secur]?>",
                des_placa = $(this).val();
                $('#btnGeraToken').attr("href","validaDadosToken.do?key=<?=$_GET[key]?>&t=<?=$rand?>&idp="+des_placa+"&idU=<?=$usuEncrypt?>&tp=<?=$tipoDesc?>&cds=<?=$_SESSION[cds]?>");
                $('#btnGeraTokenResgate').attr("href","validaDadosToken.do?key=<?=$_GET[key]?>&t=<?=$rand?>&idp="+des_placa+"&idU=<?=$usuEncrypt?>&tp=<?=$tipoResg?>&cds=<?=$_SESSION[cds]?>");
                // alert("novoGeraTokem.do?secur="+secur+"&idp="+des_placa);
            });

        });

    </script>