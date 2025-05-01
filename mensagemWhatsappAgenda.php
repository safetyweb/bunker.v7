            
<div id="msg-wpp" style="display: none;">

    <div id="close_filtros" class="margin-left-15 margin-top-100">
        <a href="javascript:void(0)" onclick="mostraFiltros('msg-wpp')" style="padding: 15px 15px 15px 0; color: #2C3E50;">
            <b><span class="far fa-arrow-left fa-2x"></span></b>
        </a>
    </div>

    <p class="margin-top-40"><span id="CLIENTES_COUNT">0</span> pessoa(s) selecionada(s) para comunicação</p>
<?php

    $connTemp = connTemp($cod_empresa,'');

    mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
    mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
    mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");  

    // $cod_desafio = 2;
    // echo "<pre>";
    // print_r($codDesafios);
    // echo "</pre>";
    $arrDesafios = explode(",", $codDesafios);
    
    foreach ($arrDesafios as $cod_desafio) {

        $sql = "SELECT * FROM TEMPLATE_WHATSAPP 
                WHERE COD_EMPRESA = $cod_empresa 
                AND COD_DESAFIO = $cod_desafio";

        // fnEscreve($sql);

        $arrayQuery = mysqli_query($connTemp,$sql);
        $qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

        if($qrBuscaTemplate['COD_TEMPLATE'] > 0){

            $cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
            $nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
            $des_titulo = $qrBuscaTemplate['DES_TITULO'];
            $abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
            $des_template[0] = $qrBuscaTemplate['DES_TEMPLATE'];        
            $des_template[1] = $qrBuscaTemplate['DES_TEMPLATE2'];       
            $des_template[2] = $qrBuscaTemplate['DES_TEMPLATE3'];       
            $des_template[3] = $qrBuscaTemplate['DES_TEMPLATE4'];       
            $des_template[4] = $qrBuscaTemplate['DES_TEMPLATE5'];

            $template = $des_template[$mensagemEnvio];
            $templateEnvio = $mensagemEnvio;
            if($template == ""){
                $template = $des_template[0];
                $templateEnvio = 0;
            }
            // fnEscreve($nom_template);

            $msgsbtr=nl2br($template,true);

?>

                <div class=" col-xs-12 margin-top-10">

                    <h4>Mensagem</h4>
                    <p id="MSG_<?php echo fnEncode($cod_template)?>"><?=$msgsbtr?></p>
                    <textarea id="DES_TEMPLATE_<?php echo fnEncode($cod_template)?>" type="text" class="form-control input-sm" rows="6" style="display: none;"><?=$template?></textarea>
                    <div class="push10"></div>
                    <div id="SUGESTOES_<?php echo fnEncode($cod_template)?>"></div>
                    <div class="push30"></div>
                    <div class="push10"></div>
                    <div class="sticky_top_mob text-center">
                        <a href="javascript:void(0)" class="btn btn-block btn-info addBox" data-url="action.php?mod=<?php echo fnEncode(1953)?>&id=<?php echo fnEncode($cod_empresa)?>&idT=<?php echo fnEncode($cod_template)?>&pop=true" data-title="Sugerir - Busca Produtos"><span class="far fa-cart-plus"></span>&nbsp; Sugerir produtos para compra</a>
                        <input type="hidden" name="ARR_PRODUTO_<?php echo fnEncode($cod_template)?>" id="ARR_PRODUTO_<?php echo fnEncode($cod_template)?>" value="">
                        <div class="push20"></div>
                        <div id="btnCustom_<?php echo fnEncode($cod_template)?>">
                            <a href="javascript:void(0)" class="btn btn-block" style="background-color: #D1D2D4; color: #fff;" onclick='customizarMsg("<?php echo fnEncode($cod_template)?>")'><span class="fal fa-edit"></span>&nbsp; Personalizar mensagem <?=$nom_template?></a>
                            <div class="push20"></div>
                        </div>
                        <div id="btnCancel_<?php echo fnEncode($cod_template)?>" style="display: none;">
                            <a href="javascript:void(0)" class="btn btn-block" style="background-color: #FF5252; color: #fff;" onclick='cancelarMsg("<?php echo fnEncode($cod_template)?>", "<?=$templateEnvio?>")'><span class="fal fa-repeat"></span>&nbsp; Cancelar Personalização <?=$nom_template?></a>
                            <div class="push20"></div>
                        </div>
                        <a href="javascript:void(0)" id="enviarWpp_<?php echo fnEncode($cod_template)?>" class="btn btn-block" data-template="<?=$templateEnvio?>" style="background-color: green; color: #fff;" onclick='enviarWhatsapp("<?=fnEncode($cod_desafio)?>", $("#ARR_PRODUTO_<?php echo fnEncode($cod_template)?>").val(), $(this).attr("data-template"), "<?php echo fnEncode($cod_template)?>")'><span class="fab fa-whatsapp"></span>&nbsp; Enviar mensagem <?=$nom_template?></a>
                        <!-- <div class="push20"></div> -->
                        <!-- <a href="javascript:void(0)" class="btn btn-block btn-default" ><span class="far fa-edit"></span>&nbsp; Escrever mensagem manual</a> -->
                    </div>
                </div>

            <div class="push100"></div>
            <div class="push50"></div>

<?php
        }
    }
?>

</div>