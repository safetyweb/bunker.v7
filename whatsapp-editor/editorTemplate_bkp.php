<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<?php 

    include_once "../_system/_functionsMain.php";

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="css/styles.css?t=<?=mt_rand()?>">

<style type="text/css">
    body{
        background: unset!important;
        width: 100%!important;
        overflow-x: hidden!important;
    }
</style>
    
<div class="row">
    <div class="col-md-7">

        <div class="mb-3">
            <button class="btn btn-default" onclick="addFormatting('*')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Negrito">
                <i class="fas fa-bold"></i>
            </button>
            <button class="btn btn-default" onclick="addFormatting('_')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Itálico">
                <i class="fas fa-italic"></i>
            </button>
            <button class="btn btn-default" onclick="addFormatting('~')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Riscado">
                <i class="fas fa-strikethrough"></i>
            </button>
            <button class="btn btn-default" onclick="addFormatting('```')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Monoespaçado">
                <i class="fa-solid fa-text-width"></i>
            </button>
            <button class="btn btn-default" onclick="addList('ul')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lista não ordenada">
                <i class="fas fa-list-ul"></i>
            </button>
            <button class="btn btn-default" onclick="addList('ol')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lista ordenada">
                <i class="fas fa-list-ol"></i>
            </button>
            <button type="button" class="btn btn-default" type="button" data-bs-toggle="modal" data-bs-target="#emojiModal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Emoji"> <i class="fas fa-smile"></i>
            </button>
            <button class="btn btn-default" onclick="copyText()" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Copiar mensagem">
                <i class="fas fa-copy"></i>
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
            <textarea id="inputText" placeholder="Digite seu texto aqui" cols="30" rows="10"
                class="form-control" oninput="updatePreview(); updateSaveButtonVisibility()"></textarea>
        </div>

        <div class="mb-3">

            <a href="javascript:void(0)" class="btn btn-block btn-success f14" onclick="generateMsg()">
                <img class="img-responsive" src="../media/ai_brain_sm.png" width="26px" style="margin-right:0;-webkit-filter: grayscale(1) invert(1);filter: grayscale(1) invert(1); display: inline-block;">
                &nbsp;Gerar templates com I.A.
            </a>
            
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
    <div class="col-md-5">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-view-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-view" type="button" role="tab" aria-controls="nav-view"
                    aria-selected="true">
                    Visualização
                </button>
                <button class="nav-link" id="nav-saves-tab" data-bs-toggle="tab" data-bs-target="#nav-saves"
                    type="button" role="tab" aria-controls="nav-saves" aria-selected="false">
                    Modelos Salvos
                </button>
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
                                <div class="message-img mb-2" id="previewImg">
                                    <img class="img-responsive" src="https://img.bunker.mk/media/clientes/219/Nike.jpeg" width="100%">
                                </div>
                                <div class="message-text" id="previewText">
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
                                </div>
                            </div>
                            <div class="preview-footer" style="text-align: right; margin: 0 10px;">
                                <a href="javascript:void(0)" id="saveModelButton" class="btn btn-default btn-sm
                                 d-none" onclick="saveModel()">
                                    <i class="fas fa-save"></i>
                                    Salvar modelo
                                </a>
                            </div>
                        </div>

                        <div id="mensagensGeradas">

                            

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
        </div>
    </div>
</div>

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

<script src="../js/jquery.min.js"></script>
<script src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script type="text/javascript">

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    $(function(){
        $('.dragTag').on('dragstart', function (event) {
            var tag = $(this).attr('dragTagName');
            event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
            event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
        });

            // $('.dragTag').on('dragend', function (event) {
            //     updateCount($('#DES_TEMPLATE'));
            // });

        $('.dragTag').on('click', function (event) {
            var $temp = $("<input>");
            $("#tosave").append($temp);
            $temp.val($(this).text()).select();
            document.execCommand("copy");
            $temp.remove();
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
            data: {id: "<?=fnEncode($cod_empresa)?>", DES_TEMPLATE: $("#inputText").val(), COD_TEMPLATE: $("#COD_TEMPLATE").val()},
            beforeSend:function(){
                $('#blocker').show();
            },
            success:function(data){
                $('#blocker').hide();
                $('#mensagensGeradas').html(data);
                $("#formulario").validator('destroy').validator();
                // console.log(data);
            },
            error:function(){

                console.log("erro 500");

            }
        });
    }
</script>