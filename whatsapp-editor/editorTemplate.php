<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<?php 

include_once "../_system/_functionsMain.php";

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
    $request = md5( implode( $_POST ) );

    if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
    {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    }
    else
    {
        $_SESSION['last_request']  = $request;
        $cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_template = $_REQUEST['inputText'];
        $des_template2 = addslashes($_REQUEST['DES_TEMPLATE2']);
        $des_template3 = addslashes($_REQUEST['DES_TEMPLATE3']);
        $des_template4 = addslashes($_REQUEST['DES_TEMPLATE4']);
        $des_template5 = addslashes($_REQUEST['DES_TEMPLATE5']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        if ($opcao != ''){

            $connTemp = connTemp($cod_empresa, "");

            mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
            mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
            mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");          

                //mensagem de retorno
            switch ($opcao)
            {
                case 'ALT':

                $sql = "UPDATE TEMPLATE_WHATSAPP SET
                DES_TEMPLATE='$des_template',
                DAT_ALTERAC=NOW(),
                COD_ALTERAC=$cod_usucada
                WHERE COD_EMPRESA = $cod_empresa
                AND COD_TEMPLATE=$cod_template";

                fnTestesql($connTemp,$sql);

                $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

                break;

                case 'EXC':
                $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";        
                break;
            }
                //atualiza lista iframe                      
            $msgTipo = 'alert-success';
        }                
    }
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){

        //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $cod_desafio = fnDecode($_GET['idc']); 
    $cod_tipo = fnDecode($_GET['tipo']);
    $agenda = fnLimpaCampo($_GET['agenda']);
    // fnEscreve($agenda);

    $connTemp = connTemp($cod_empresa, "");

    mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
    mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
    mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

    $sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;    

        //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)){
        $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];

    }

}else { 
    $nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['idT'])))){
    $cod_template = fnDecode($_GET['idT']); 

    $sql = "SELECT * FROM TEMPLATE_WHATSAPP WHERE COD_TEMPLATE = $cod_template AND COD_EMPRESA = $cod_empresa";

    // fnEscreve($sql);
    $query = mysqli_query($connTemp,$sql);

    if($qrResult = mysqli_fetch_assoc($query)){
        $cod_template = $qrResult['COD_TEMPLATE'];
        $des_template = $qrResult['DES_TEMPLATE'];
        $des_imagem = $qrResult['DES_IMAGEM'];
        $templatesIa = array($qrResult['DES_TEMPLATE2'],$qrResult['DES_TEMPLATE3'],$qrResult['DES_TEMPLATE4'],$qrResult['DES_TEMPLATE5']);
    }else{
        $cod_template = "";
        $des_template = "";
        $des_imagem = "";
        $templatesIa = "";
    }
}

// echo "<pre>";
// print_r($templatesIa);
// echo "</pre>";
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="css/styles.css?t=<?=mt_rand()?>">
<link href="../css/jquery-confirm.min.css" rel="stylesheet"/>

<style type="text/css">
    body{
        background: unset!important;
        width: 100%!important;
        overflow-x: hidden!important;
    }

    .message-text textarea {
        width: 100%;
        background-color: transparent;
        border: 1px solid #ccc;
        padding: 5px;
        box-sizing: border-box;
    }

    .autoresizing {
        display: block;
        overflow: hidden;
        resize: none;
    }

    #blocker, #blocker2
    {
        display:none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: .8;
        background-color: #f2f2f2;
        z-index: 1000;
    }

    #blocker div, #blocker2 div
    {
        position: absolute;
        top: 30%;
        left: 48%;
        width: 200px;
        height: 2em;
        margin: -1em 0 0 -2.5em;
        color: #000;
        font-weight: bold;
    }
</style>

<div id="blocker">
    <center>
        <img src="../media/ai_loader_globe.gif" style="margin-left: auto; margin-right:auto; -webkit-filter: grayscale(1) invert(1);filter: grayscale(1) invert(1);" width="400px" />
        <br />
        <p class="f16">Por favor, aguarde... Gerando novas mensagens utilizando inteligência artificial.</p>
    </center>
</div>

<div id="blocker2">
    <center>
        <img src="../media/spinnerBlue.gif" width="200px" />
        <br />
        <p class="f16">Aguarde... Carregando as informações agenda.</p>
    </center>
</div>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
    <div class="row">
        <div class="col-md-8 mt-3">

            <?php if($agenda == "true"){ ?>
                <div class="mb-4">
                    <a href="https://adm.bunker.mk/action.php?mod=<?php echo fnEncode(1937)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_desafio)?>&pop=true" class="btn btn-info pull-left text-white" onclick="window.jQuery('#blocker2').show();"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp; Voltar à Agenda</a>
                </div>
            <?php } ?>

            <div class="mb-3">
                <button type="button" class="btn btn-default" onclick="addFormatting('*')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Negrito">
                    <i class="fas fa-bold"></i>
                </button>
                <button type="button" class="btn btn-default" onclick="addFormatting('_')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Itálico">
                    <i class="fas fa-italic"></i>
                </button>
                <button type="button" class="btn btn-default" onclick="addFormatting('~')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Riscado">
                    <i class="fas fa-strikethrough"></i>
                </button>
                <button type="button" class="btn btn-default" onclick="addFormatting('```')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Monoespaçado">
                    <i class="fa-solid fa-text-width"></i>
                </button>
                <button type="button" class="btn btn-default" onclick="addList('ul')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lista não ordenada">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button type="button" class="btn btn-default" onclick="addList('ol')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lista ordenada">
                    <i class="fas fa-list-ol"></i>
                </button>
                <button type="button" type="button" class="btn btn-default" type="button" data-bs-toggle="modal" data-bs-target="#emojiModal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Emoji"> <i class="fas fa-smile"></i>
                </button>
                <button type="button" class="btn btn-default" onclick="copyText()" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Copiar mensagem">
                    <i class="fas fa-copy"></i>
                </button>
                <button type="button" class="btn btn-info" id="enviarTesteSimples" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Quick Test">
                    <i class="fas fa-paper-plane text-white"></i>
                </button>
            </div>

            <div class="help-text">
                <p>Para formatar o texto, selecione a parte desejada e clique nos botões acima.</p>
                <p>Para criar uma lista, coloque cada item em uma nova linha e clique no botão correspondente.</p>
            </div>

            <div class="mb-3">
                <p>
                    <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <span class="fas fa-caret-right f14"></span> Banco de Variáveis 
                        <small>(<b>Clique e arraste</b> a tag na área desejada ou <b>clique na tag para copiar</b>)</small>
                    </a>
                </p>
                <div class="collapse" id="collapseExample">

                    <?php
                    $sql = "SELECT * FROM VARIAVEIS WHERE LOG_SMS = 'S' order by NUM_ORDENAC";
                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                    while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
                        ?>
                        <a href="javascript:void(0)" class="btn btn-default btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; color: #fff; padding: 2px 4px; font-size: 14px;"
                        dragTagName="<?=$qrBuscaFases[KEY_BANCOVAR]?>"
                        tamanho="<?=$qrBuscaFases["NUM_TAMSMS"]?>"
                        onclick="quickCopy('<?=$qrBuscaFases[KEY_BANCOVAR]?>');">
                        <span><?=$qrBuscaFases['ABV_BANCOVAR']?></span>
                    </a>
                    <?php
                }
                ?>

            </div>
            <textarea id="inputText" name="inputText" placeholder="Digite seu texto aqui" rows="2"
            class="form-control autoresizing" oninput="updatePreview(); updateSaveButtonVisibility()"><?=$des_template?></textarea>
        </div>

        <div class="mb-3">

            <a href="javascript:void(0)" class="btn btn-block btn-success f14" onclick="generateMsg()">
                <img class="img-responsive" src="../media/ai_brain_sm.png" width="26px" style="margin-right:0;-webkit-filter: grayscale(1) invert(1);filter: grayscale(1) invert(1); display: inline-block;">
                &nbsp;Gerar templates com I.A.
            </a>

            <input type="hidden" name="opcao" id="opcao" value="">
            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
            <input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?php echo $cod_template ?>">
            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S"> 

            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn float-end" onclick="setOpcaoALT()"><i class="fas fa-save" aria-hidden="true"></i>&nbsp; Salvar Template</button>

            <!-- <button class="btn btn-default" onclick="eraseText()" title="Apagar texto">
                <i class="fas fa-eraser"></i>
                Apagar texto
            </button>
            <button class="btn btn-default" onclick="clearFormatting()">
                <i class="fas fa-eraser"></i>
                Limpar formatação
            </button> -->

            <!--button type="button" class="btn btn-default btn-highlight" data-bs-toggle="modal"
                data-bs-target="#IAModal">
                <i class="fas fa-magic"></i>
                Quero ajuda para escrever!
            </button>

            <div class="modal fade" id="IAModal" tabindex="-1" aria-labelledby="IAModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="IAModalLabel">
                                <i class="fas fa-magic"></i>
                                Inteligência Artificial
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="questionForm">
                                <div class="mb-3">
                                    <textarea name="text" id="inputTextIA" class="form-control" cols="30"
                                        rows="10"
                                        placeholder="Ex: Escreva uma mensagem convidando meus clientes para a Black Friday."></textarea>
                                </div>
                                <div class="mt-3">
                                    <h5>Tom:</h5>
                                    <div class="btn-group" role="group" aria-label="Voice tone options">
                                        <input type="radio" class="btn-check" name="voice"
                                            id="friendly-outlined" value="Amigável" autocomplete="off" checked>
                                        <label class="btn btn-outline-success"
                                            for="friendly-outlined">Amigável</label>

                                        <input type="radio" class="btn-check" name="voice" id="formal-outlined"
                                            value="Formal" autocomplete="off">
                                        <label class="btn btn-outline-success"
                                            for="formal-outlined">Formal</label>

                                        <input type="radio" class="btn-check" name="voice" id="neutral-outlined"
                                            value="Neutro" autocomplete="off">
                                        <label class="btn btn-outline-success"
                                            for="neutral-outlined">Neutro</label>

                                        <input type="radio" class="btn-check" name="voice" id="humor-outlined"
                                            value="Humor" autocomplete="off">
                                        <label class="btn btn-outline-success"
                                            for="humor-outlined">Humor</label>

                                        <input type="radio" class="btn-check" name="voice"
                                            id="informative-outlined" value="Informativo" autocomplete="off">
                                        <label class="btn btn-outline-success"
                                            for="informative-outlined">Informativo</label>
                                    </div>
                                </div>
                            </form>
                            <div id="responseContainer" class="mt-3">
                                
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Fechar
                            </button>
                            <button type="button" class="btn btn-success" id="submitQuestion">
                                <i class="fas fa-magic"></i>
                                Gerar texto
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        -->
    
    </div>
</div>
<div class="col-md-4">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-view-tab" data-bs-toggle="tab"
                data-bs-target="#nav-view" type="button" role="tab" aria-controls="nav-view"
                aria-selected="true">
                Visualização
            </button>

            <button class="nav-link" id="nav-message-tab" data-bs-toggle="tab" data-bs-target="#nav-message"
                type="button" role="tab" aria-controls="nav-message" aria-selected="false">
                Mensagens Geradas com I.A
            </button>

            <!-- <button class="nav-link" id="nav-saves-tab" data-bs-toggle="tab" data-bs-target="#nav-saves"
                type="button" role="tab" aria-controls="nav-saves" aria-selected="false">
                Modelos Salvos
            </button> -->
        </div>
    </nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-view" role="tabpanel" aria-labelledby="nav-view-tab"
    tabindex="0">
    <div class="card mt-3">
        <div class="card-header">
            <img src="https://img.bunker.mk/whatsapp-editor/img/blank-profile-picture.jpg?t=<?=mt_rand()?>"
            alt="<?=$_SESSION['SYS_NOM_USUARIO']?>" class="contact-image">
            <a href="javascript:void(0)" 
            class="text-decoration-none text-dark">
            <span class="contact-name">
                <?=$_SESSION['SYS_NOM_USUARIO']?>
            </span>
        </a>
    </div>

    <div class="pb-3 pt-3" id="janelaChat">

        <div class="card-body" id="chat">
            <div class="message sent preview-card">
<?php 
                if($des_imagem != ""){ 
                    $ext = explode('.', $des_imagem);
                    $ext = end($ext);
?>
                <div class="message-img mb-2" id="previewImg">
<?php 
                    if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){
                        
?>
                        <img class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" width="100%">
<?php 
                    }else{
?>
                    <video width="100%"controls>
                        <source class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" type="video/<?=$ext?>" width="100%">
                    </video>
<?php 
                    }
?>
                </div>
<?php 
                } 
?>
                <div class="message-text" id="previewText">
                <?php 
                    if ($des_template != ""){ 
                        $msgsbtr=nl2br($des_template,true);
                        // $msgsbtr= str_replace('<br />','\n', $msgsbtr);
                        // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
                ?>
                    <p>
                        <?=$msgsbtr?>
                    </p>
                <?php 
                    }else{ 
                ?>
                    <p>
                        <strong>Transforme suas mensagens no WhatsApp em verdadeiras obras de arte!
                        🤩</strong>
                    </p>
                    <p>
                        <i>Com o Editor de Texto para WhatsApp, você pode:</i>
                    </p>
                    <p>
                        ✏️ Criar listas organizadas em segundos
                        <br>
                        💫 Adicionar formatações em negrito, itálico e riscado com um clique
                        <br>
                        🎨 Personalizar suas mensagens de forma simples e intuitiva
                        <br>
                        🪄 Usar Inteligência Artificial para te ajudar a escrever!
                    </p>
                    <p>
                        <strong>Experimente agora mesmo!</strong>
                    </p>
                <?php 
                    } 
                ?>
                </div>
            </div>
            <!-- <div class="preview-footer" style="text-align: right; margin: 0 10px;">
                <a href="javascript:void(0)" id="saveModelButton" class="btn btn-default btn-sm
                d-none" onclick="saveModel()">
                <i class="fas fa-save"></i>
                Salvar modelo
                </a>
            </div> -->
    </div>


    <div id="mensagensGeradas">

    <?php
        if (!empty($templatesIa)) {
    ?>
            <div class="text-center mt-3 mb-3">
                <A href="javascript:void(0)" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13); text-decoration: none; pointer-events: none;"> MENSAGENS GERADAS COM I.A.</A>
            </div>
    <?php
            foreach ($templatesIa as $key => $value) { 
                // fnEscreve($key);
                 if($value != ""){ 
    ?>
                <div class="card-body">
                    <div class="message sent preview-card">
<?php 
                        if($des_imagem != ""){ 
                        $ext = explode('.', $des_imagem);
                        $ext = end($ext);
?>
                        <div class="message-img mb-2" id="previewImg">
<?php 
                            if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){
                        
?>
                                <img class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" width="100%">
<?php 
                            }else{
?>
                            <video width="100%" controls>
                                <source class="img-responsive" src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/wpp/<?=$des_imagem?>" type="video/<?=$ext?>" width="100%">
                            </video>
<?php 
                            }
?>
                        </div>
<?php 
                        } 
?>
                        <div class="message-text">
                            <p>
                                <?php 
                                    $msgsbtr=nl2br($value,true);                                
                                    // $msgsbtr= str_replace('<br />','\n', $value);
                                    // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
                                    echo $msgsbtr; 
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

    <?php
                }
            }
        } 
    ?>

    </div>

</div>
<div class="card-footer">
    <input type="text" class="form-control" placeholder="Digite uma mensagem..." disabled>
</div>
</div>
</div>

<div class="tab-pane fade" id="nav-saves" role="tabpanel" aria-labelledby="nav-saves-tab"
tabindex="0">
    <div class="card mt-3">
        <div class="card-body">
            <div id="modelList" class="model-list"></div>
        </div>
    </div>
</div>

<div class="tab-pane fade" id="nav-message" role="tabpanel" aria-labelledby="nav-message-tab"
tabindex="0">
    <div class="card mt-3" id="tabGeradas">
        <div class="card-body">
<?php
            if (!empty($templatesIa)) {
                ?>
                <div class="text-center mt-3 mb-3">
                    <a href="javascript:void(0)" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13); text-decoration: none; pointer-events: none;">ALTERAÇÕES SALVAS AUTOMATICAMENTE</a>
                </div>
<?php
                $count = 1;
                foreach ($templatesIa as $key => $value) { 

                    $msgsbtr=$value;                                
                    // $msgsbtr= str_replace('<br />','\n', $value);
                    // $msgsbtr = str_replace(array("\r", "\n",'\n',"'"), '', $msgsbtr);
?>
                    <div class="text-center mt-3 mb-3">
                        <a href="javascript:void(0)" style="color: #54656f; padding: 8px 22px 10px 22px; font-size: 12.5px; line-height: 21px; border-radius: 7.5px; background: rgba(255, 255, 255, 0.95); box-shadow: 0 1px 0.5px rgba(11,20,26,0.13); text-decoration: none; pointer-events: none;"> MENSAGEM I.A. <?=$count?></a>
                    </div>
                    <div class="card-body">
                        <div class="message sent preview-card">
                            <div class="message-text">

                                <textarea rows="2" name="DES_TEMPLATE_<?=$key?>" id="DES_TEMPLATE_<?=$key?>" class="form-control autoresizing" onfocusout="salvaTemplateIA(this);" onclick='this.style.height = this.scrollHeight + "px";'><?=$msgsbtr?></textarea>
                                
                            </div>
                        </div>
                    </div>

<?php
                $count++;
            }
        } 
?>
        </div>
    </div>
</div>

</div>
</div>
</div>
</form>

<div class="modal fade" id="emojiModal" tabindex="-1" aria-labelledby="emojiModalLabel"
aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="emojiModalLabel">Emojis</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="emojiSearch" placeholder="Pesquisar emoji...">
                <div id="emojiContainer" class="emoji-container"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->                  
<div class="modal fade" id="popModalEnvio" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="envioTeste" action="">
                    <fieldset>
                        <!-- <legend>Dados do envio</legend>  -->

                        <div class="row">

                            <div class="col-sm-10">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Celulares para envio (com DDD)</label>
                                    <input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="400">
                                    <div class="help-block with-errors">Separar múltiplos celulares com ";"</div>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <a href="javascript:void(0)" id="dispararTeste" class="btn btn-primary btn-block getBtn mt-4" style="margin-top: 2px; width: 100%"><i class="far fa-paper-plane" aria-hidden="true"></i>&nbsp;</a>
                            </div>

                            <input type="hidden" name="COD_TEMPLATE_ENVIO" id="COD_TEMPLATE_ENVIO" value="<?=$cod_template?>">
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S"> 

                        </div>

                    </fieldset>
                </form>
            </div>    
        </div>
    </div>
</div> 

<script src="../js/jquery.min.js"></script>
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous"></script>
<script src="../js/jquery-confirm.min.js"></script>
<script type="text/javascript">

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });

     function setOpcaoALT() {
        document.getElementById('opcao').value = 'ALT';
    }

    $(function(){

        $('.autoresizing').on('input', function () {
            this.style.height = 'auto';
 
            this.style.height =
                (this.scrollHeight) + 'px';
        });

        $("#nav-message-tab").click(function(){
            document.getElementById("DES_TEMPLATE_0").style.height = document.getElementById("DES_TEMPLATE_0").scrollHeight + 'px';  
            document.getElementById("DES_TEMPLATE_1").style.height = document.getElementById("DES_TEMPLATE_1").scrollHeight + 'px';  
            document.getElementById("DES_TEMPLATE_2").style.height = document.getElementById("DES_TEMPLATE_2").scrollHeight + 'px';  
            document.getElementById("DES_TEMPLATE_3").style.height = document.getElementById("DES_TEMPLATE_3").scrollHeight + 'px';  
        });

        document.getElementById("inputText").style.height = document.getElementById("inputText").scrollHeight + 'px';      

        $('.dragTag').on('dragstart', function (event) {
            var tag = $(this).attr('dragTagName');
            event.originalEvent.dataTransfer.setData("text", tag+' ');
            event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
        });

        $('.dragTag').on('click', function (event) {
            var $temp = $("<input>");
            $("#tosave").append($temp);
            $temp.val($(this).text()).select();
            document.execCommand("copy");
            $temp.remove();
        });

        $("#enviarTesteSimples").click(function(){

            $("#popModalEnvio").modal('show');

        });

        $("#dispararTeste").click(function(){
            $("#envioTeste #DES_TEMPLATE_ENVIO").val($("#inputText").val());
            if($("#NUM_CELULAR").val().trim() != ""){

                envioTeste();

            }else{

                $.alert({
                    title: "Aviso",
                    content: "O campo de celulares não pode ser vazio",
                    type: 'orange',
                    buttons: {
                        "OK": {
                            btnClass: 'btn-blue',
                            action: function(){

                            }
                        }
                    },
                    backgroundDismiss: true
                });

            }
        });
    });

    function quickCopy(tag) {
        var dummyContent = tag;
        var dummy = $('<input>').val(dummyContent).appendTo('body');
        dummy.select();
        document.execCommand('copy');
        dummy.remove();
    }

    function generateMsg(){
        $.ajax({
            method: 'POST',
            url: 'ajxGeraMsgIAEditor.do?opcao=gerar',
            data: {id: "<?=fnEncode($cod_empresa)?>", DES_TEMPLATE: $("#inputText").val(), COD_TEMPLATE: $("#COD_TEMPLATE").val(), IMG: "<?=$des_imagem?>"},
            beforeSend:function(){
                $('#blocker').show();
            },
            success:function(data){
                $('#blocker').hide();
                $('#mensagensGeradas').html($("#previewGeradas",data));
                $('#tabGeradas').html($("#editGeradas",data));
                // databaseIA('loadmsg', 'tabGeradas');
            },
            error:function(){

                console.log("erro 500");

            }
        });
    }

    function databaseIA(opcao, objDiv, idTemplate=0, img="", des_template=""){
        $.ajax({
            url: 'ajxGeraMsgIAEditor.do?opcao='+opcao,
            method: 'POST',
            data: {id: "<?=fnEncode($cod_empresa)?>", COD_TEMPLATE: $("#COD_TEMPLATE").val(), ID_TEMPLATE: idTemplate, DES_TEMPLATE: des_template},
            success: function(data) {
                $('#'+objDiv).html(data);
            },
        });
    }

    function salvaTemplateIA(campo) {
        let inputId = $(campo).attr('id'),
            inputValue = $(campo).val(),
            id = inputId.split('_').pop(),
            idTemplate = 0;

        idTemplate = Number(id)+2;

        databaseIA('editmsg', 'mensagensGeradas', idTemplate, '', inputValue);
    }

    function envioTeste(){
        $.ajax({
            method: 'POST',
            url: '../ajxEnvioTesteSimplesWhats.do?id=<?=fnEncode($cod_empresa)?>&idD=<?=fnEncode($cod_desafio)?>',
            data: {DES_TEMPLATE: $("#inputText").val(), COD_TEMPLATE: $("#COD_TEMPLATE").val(), NUM_CELULAR: $("#NUM_CELULAR").val()},
            beforeSend:function(){
                $("#dispararTeste").html("<span class='fas fa-hourglass'></span>&nbsp;")
                                    .removeClass("btn-primary")
                                    .addClass("btn-default")
                                    .attr('disabled',true);
            },
            success:function(data){

                $("#dispararTeste").html("<span class='fas fa-check'></span>&nbsp;")
                .removeClass("btn-default")
                .addClass("btn-success")
                .attr('id','disparadoTeste');

                setInterval(function(){
                    $("#disparadoTeste").fadeOut('fast')
                    .html("<span class='fas fa-paper-plane'></span>&nbsp;")
                    .removeClass("btn-success")
                    .addClass("btn-primary")
                    .attr('disabled',false)
                    .attr('id','dispararTeste')
                    .fadeIn('fast');
                },15000);

                $.alert({
                    title: "Sucesso",
                    content: "O seu teste foi enviado! Verifique seu WhatsApp (essa operação pode levar alguns minutos).",
                    type: 'green',
                    buttons: {
                        "OK": {
                            btnClass: 'btn-blue',
                            action: function(){

                            }
                        }
                    },
                    backgroundDismiss: true
                });

                console.log(data);

            },
            error:function(){

                console.log("erro 500");

            }
        });
    }   

</script>