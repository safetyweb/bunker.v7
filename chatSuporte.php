<?php

//echo fnDebug('true');

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

$hashLocal = mt_rand();
$cod_usucada = fnLimpacampoZero($_SESSION["SYS_COD_USUARIO"]);
$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_usuario = fnLimpacampoZero($_REQUEST['COD_USUARIO']);
        $cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);

        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = $_GET['mod'];
        $COD_MODULO = fndecode($_GET['mod']);


        $sqlAgenda = "";
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
    }
}

//busca dados da url    
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, DES_SUFIXO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
}
?>
<link rel="stylesheet" href="js/live-chat-bot/jquery.convform.css">

<style>
    html {
        font-size: 1rem !important;
    }

    section#demo {
        background: #fbfbfb;
        position: relative;
        padding: 0;
        min-height: 100vh !important;
        height: 100vh !important;
        transition: height 9999s;
    }

    .vertical-align {
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        left: 0;
        width: 100%;
    }

    .card {
        /* width: 100vw !important; */
        min-height: 95% !important;
        box-shadow: 13px 13px 28px 2px rgba(0, 0, 0, 0.035);
        padding: 7px 15px;
    }

    .conv-form-wrapper {
        min-height: 95% !important;
        background-color: #fefefe !important;
    }

    .conv-form-wrapper:before {
        display: none !important;
    }

    .wrapper-messages {
        margin: 0 1.5rem;
    }

    .convFormDynamic {
        display: inline-block;
        width: 100% !important;
        position: absolute !important;
        bottom: 0 !important;
        background-color: #fefefe !important;
    }

    textarea {
        max-width: 100% !important;
        background-color: #fff !important;
        box-shadow: 0 0 5px 5px rgba(222, 222, 222, 0.3) !important;
        margin-right: 0 !important;
    }

    .submit {
        margin-left: 10px !important;
    }

    #messages {
        overflow: scroll !important;
        padding-bottom: 35px !important;
    }

    @media screen and (min-width:500px) {
        .options {
            all: unset !important;
        }
    }
</style>
<section id="demo">
    <div class="vertical-align">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1 col-xs-offset-0 ">
                    <div class="card no-border">
                        <div id="chat" class="conv-form-wrapper">
                            <form action="" method="GET" class="hidden">
                                <input type="text" name="name"
                                    data-conv-question="Olá! <?= $nom_usuario ?>, bem-vindo  ao suporte Marka, vou te fazer algumas perguntas para resolver seu problema ou registrar um novo chamado para a empresa <?= $nom_empresa ?>."
                                    data-no-answer="true">
                                <input type="text" name="numeroContato" data-mask class="numeroContato" inputIdName="numeroContato" data-conv-question="Informe o número de telefone para contato.">
                                <!-- 

                                <?php
                                $sql = "SELECT UV.* FROM usuarios U 
                                    INNER JOIN UNIDADEVENDA UV ON FIND_IN_SET(UV.COD_UNIVEND, U.COD_UNIVEND)
                                    WHERE U.COD_EMPRESA = '$cod_empresa'
                                    AND U.DAT_EXCLUSA IS NULL
                                    AND U.COD_USUARIO='$cod_usucada'
                                    ";

                                $query = mysqli_query($adm, $sql);

                                while ($qrResult = mysqli_fetch_assoc($query)) {
                                    echo "<option value='" . $qrResult['COD_UNIVEND'] . "'>" . $qrResult['COD_UNIVEND'] . ' - ' . $qrResult['NOM_FANTASI'] . "</option>";
                                }

                                //fnEscreve($sql);
                                ?>
                                </select> -->
                                <!-- option-condicional -->
                                <select name="mult1" data-callback="storeState"
                                    data-conv-question="Qual o tipo de problema você está enfrentando?">
                                    <option value="1">1-Dúvida</option>
                                    <option value="2">2-Migração</option>
                                    <option value="3">3-Desenvolvimento</option>
                                    <option value="4">4-Suporte</option>
                                    <option value="5">5-Falhas Sistema</option>
                                    <option value="6">6-Implantação</option>
                                    <option value="7">7-Melhoria</option>
                                    <option value="8">8-Solicitação de Reunião</option>
                                    <option value="9">9-Backlog</option>
                                    <option value="10">10-Adm/Financeiro</option>
                                    <option value="11">11-Relacionamento</option>
                                </select>
                                <div data-conv-fork="mult1">
                                    <div data-conv-case="4">
                                        <!-- option-condicional -->
                                        <select name="tipoSuporte" data-callback="storeState"
                                            data-conv-question="Que tipo de suporte podemos te oferecer?">
                                            <option value="1">Venda ou créditos</option>
                                            <option value="2">Cadastro de cliente</option>
                                            <option value="3">Comunicação / SMS</option>
                                            <option value="4">Relatórios</option>
                                            <option value="5">Lentidão / Timeout</option>
                                            <option value="6">Ticket de Ofertas</option>
                                            <option value="7">Sistema Bunker</option>
                                            <option value="8">Implantação</option>
                                        </select>

                                        <div data-conv-fork="tipoSuporte">
                                            <div data-conv-case="1">
                                                <input type="text" typeInputUi="text"
                                                    name="credVenda_cpf" class="cpf-mask" data-mask
                                                    data-conv-question="Por favor, informe o CPF no qual foi realizado a venda">
                                                <input type="date" name="credVenda_data" class="data-hora" id="credVenda_data" data-mask
                                                    data-conv-question="Por favor, informe a data e hora da venda">
                                                <input type="number" name="credVenda_valor" class="valor"
                                                    data-conv-question="Por favor, informe o valor da venda">
                                                <!-- option-condicional -->
                                                <select name="credVenda_cupom" class="cupom-print" data-callback="storeState"
                                                    data-conv-question="Possuí o número do cupom ou um print da tela?">
                                                    <option value="1">sim</option>
                                                    <option value="2">não</option>
                                                </select>
                                                <div data-conv-fork="credVenda_cupom">
                                                    <div data-conv-case="1">
                                                        <!-- <input type="text" name="credVenda_cupominfo"
                                                            data-conv-question="Favor informar."> -->
                                                        <input type="text" name="credVenda_cupominfo"
                                                            data-conv-question="Favor informar.">
                                                    </div>
                                                </div>
                                                <!-- EndOption -->
                                                <input type="text" name="credVenda_msg"
                                                    data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                            </div>
                                        </div>
                                        <div data-conv-fork="tipoSuporte">
                                            <div data-conv-case="2">
                                                <input type="text" typeInputUi="text"
                                                    name="credVenda_cpf" class="cpf-mask" data-mask
                                                    data-conv-question="Por favor, informe o CPF no qual foi realizado a venda">
                                                <input type="date" name="cadastro_data"
                                                    data-conv-question="Por favor, informe a data do cadastro">
                                                <!-- option-condicional -->
                                                <select name="tokenSuport" id="tokensuport" data-callback="storeState"
                                                    data-conv-question="Utiliza Token?">
                                                    <option value="1">sim</option>
                                                    <option value="2">não</option>
                                                </select>
                                                <div data-conv-fork="tokenSuport">
                                                    <div data-conv-case="1">
                                                        <input type="number" name="cadastro_numCel_cliente"
                                                            data-conv-question="Informe o número do celular do cliente:">
                                                    </div>
                                                </div>
                                                <input type="text" name="cadastro_numCel_cliente"
                                                    data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                                <!-- EndOption -->
                                            </div>
                                            <div data-conv-fork="tipoSuporte">
                                                <div data-conv-case="3">
                                                    <!-- option -->
                                                    <select name="tipoComunicado"
                                                        data-conv-question="Tipo de Comunicação">
                                                        <option value="1-SMS">SMS</option>
                                                        <option value="2-E-mail">E-mail</option>
                                                        <option value="3-WhatsApp">E-mail</option>
                                                    </select>
                                                    <input type="text" typeInputUi="text"
                                                        name="tipComunic_CPFCliente" class="cpf-mask" data-mask
                                                        data-conv-question="Informe o CPF do cliente:">
                                                    <input type="text" name="tipComunic_data" class="data-hora" data-mask
                                                        data-conv-question="Qual a data e hora do envio?">
                                                    <input type="text" name="tipComunic_Campanha"
                                                        data-conv-question="Qual a campanha de comunicação?">
                                                    <input type="text" name="tipComunic_msg"
                                                        data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                                </div>
                                            </div>
                                            <!-- EndOption -->
                                            <div data-conv-fork="tipoSuporte">
                                                <div data-conv-case="4">
                                                    <input type="text" name="tipoRelatorio_titulo"
                                                        data-conv-question="Informe o título do relatório:">
                                                    <input type="text" name="tipoRelatorio_url"
                                                        data-conv-question="Informe a URL (link) do relatório:">
                                                    <input type="text" name="tipoRelatorio_msg"
                                                        data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                                </div>
                                            </div>
                                            <div data-conv-fork="tipoSuporte">
                                                <div data-conv-case="5">
                                                    <select name="ReclameNet_conect" data-callback="storeState"
                                                        data-conv-question="Já verificou sua conexão com seu provedor de internet?">
                                                        <option value="sim">sim</option>
                                                        <option value="nao">não</option>
                                                    </select>
                                                    <input type="text" name="ReclameNet_fast"
                                                        data-conv-question="Acesse: https://fast.com/pt/ e anexe o print do resultado:">
                                                    <select name="ReclameNet_firewall" data-callback="storeState"
                                                        data-conv-question="Existe alguma configuração de restrição (firewall)?">
                                                        <option value="1">sim</option>
                                                        <option value="2">não</option>
                                                    </select>
                                                    <!-- option-condicional -->
                                                    <select name="ReclameNet_erroFreq" data-callback="storeState"
                                                        data-conv-question="O erro é intermitente? ">
                                                        <option value="1">sim</option>
                                                        <option value="2">não
                                                        </option>
                                                    </select>
                                                    <div data-conv-fork="ReclameNet_erroFreq">
                                                        <div data-conv-case="1">
                                                            <input type="text" name="ReclameNet_erroFreq"
                                                                data-conv-question="Ocorre em qual horário?">
                                                        </div>
                                                    </div>
                                                    <!-- EndOption -->

                                                    <input type="text" name="ReclameNet_msg"
                                                        data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                                </div>
                                            </div>
                                            <div data-conv-fork="tipoSuporte">
                                                <div data-conv-case="6">
                                                    <input type="text" typeInputUi="text" data-mask class="cpf-mask" name="sistemaBunker_CPF" data-conv-question="Informe o CPF do cliente:">
                                                    <input type="text" name="tipoRelatorio_msg"
                                                        data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                                </div>
                                            </div>
                                            <div data-conv-fork="tipoSuporte">
                                                <div data-conv-case="7">
                                                    <input type="text" name="sistemaBunker_login"
                                                        data-conv-question="Informe o login do usuário:">
                                                    <input type="text" name="tipoRelatorio_msg"
                                                        data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                                </div>
                                            </div>
                                            <div data-conv-fork="tipoSuporte">
                                                <div data-conv-case="8">
                                                    <!-- option-condicional -->
                                                    <select name="tokenSuport" id="tokensuport" data-callback="storeState"
                                                        data-conv-question="Qual o tipo de implantação?">
                                                        <option value="1-Gerar_Base?">Gerar Base?</option>
                                                        <option value="2-Gerar_Dados_Login?">Gerar Dados Login?</option>
                                                        <option value="3-Criar_Usuários_WS?">Criar Usuários WS?</option>
                                                        <option value="4-Habilitar_Comunicação_SMS">Habilitar Comunicação SMS</option>
                                                        <option value="5-Habilitar_Comunicação_E-mail">Habilitar Comunicação E-mail</option>
                                                    </select>
                                                    <input type="text" name="cadastro_numCel_cliente"
                                                        data-conv-question="Descreva detalhadamente o que ocorreu e qual sua solicitação?">
                                                    <!-- EndOption -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- EndOption -->
                                    <select name="callbackTest"
                                        data-conv-question="Gostaria de tratar de outro assunto? ">
                                        <option value="1" data-callback="rollback">Sim</option>
                                        <option value="2" data-callback="restore">Não</option>
                                    </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<span class="attMask"></span>
<script src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="js/live-chat-bot/autosize.min.js"></script>
<script type="text/javascript" src="js/live-chat-bot/jquery.convform.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>


<script>
    // var answer = storeState.test;
    // var contato =  document.querySelector('.test')
    // contato.addEventListener('change', (e)=>{
    //     console.log(e);
    // })

    var rollbackTo = false;
    var originalState = false;
    var initialState = false;

    function storeState(stateWrapper, ready) {
        if (!initialState) {
            initialState = stateWrapper.current;
        }

        // console.log(stateWrapper.current);
        rollbackTo = stateWrapper.current;
        // console.log("storeState called: ", rollbackTo);

        ready();
    }


    function rollback(stateWrapper, ready) {
        // stateWrapper.current.answer
        // console.log('storeState', storeState);
        // console.log('statewrapper.current: ', stateWrapper.current);
        // console.log("rollback called: ", 'rollback', rollbackTo,'originalstate', originalState);
        // console.log("answers at the time of user input: ", stateWrapper.answers);
        if (rollbackTo != false) {
            if (originalState == false) {
                originalState = stateWrapper.current;
                // console.log('stored original state');
            }
            if (initialState != false) {
                stateWrapper.current.next = initialState;
                // console.log('Rolling back to the initial state');
            } else {
                stateWrapper.current.next = rollbackTo;
                // console.log('changed current.next to rollbackTo');
            }
        }
        ready();
    }

    function restore(stateWrapper, ready) {
        if (originalState != false) {
            stateWrapper.current.next = originalState;
            // console.log('changed current.next to originalState');
        }
        ready();
    }

    jQuery(function($) {
        convForm = $('#chat').convform({
            selectInputStyle: 'disable'
        });
        // console.log(convForm);
    });
    var datamask = document.querySelectorAll("[data-mask]");
    var contatosmask = document.querySelectorAll(".numeroContato")
    var cpfmask = document.querySelectorAll(".cpf-mask")
    var dataHoraMask = document.querySelectorAll(".data-hora")
    var submit = document.querySelectorAll(".submit")
    var spamMask = document.querySelector('.attMask');
    var campvazio = '';
    var maskMsg = ''

    $(document).ready(() => {

        setInterval(() => {
            /************************************************************
                Sempre vai executar a Maskara de contato no inicio
            ************************************************************/
            if (!spamMask.hasAttribute('cpfMaskared', '') && !spamMask.hasAttribute('contatoMaskared', '')) {
                $("#userInput").on("input", maskContatoSubmit)
                $("#input_2").on("keydown", maskContatoOnEnter)
            }
            /************************************************************
                Executar esse processo Se o Suporte selecionado for 
                Vendas de Credito
            ************************************************************/
            if (!spamMask.hasAttribute('cpfMaskared', '') && spamMask.hasAttribute('contatoMaskared', '')) {
                // MASKARA CPF
                $(".userInputDynamic").on("input", maskcpfclickSubmit);
                // $(".userInputDynamic").on("keydown", maskcpfOnEnter)
                // }
                // if(!spamMask.hasAttribute('DataMaskared','') && spamMask.hasAttribute('cpfMaskared','')) {
                // //MASKARA DATA
                // $(".userInputDynamic").on("input", maskDataSubmit)
                // $(".userInputDynamic").on("keydown", maskDataOnEnter)
            }
            /************************************************************
                                    FIM DO PROOCESSO
            ************************************************************/

            /* 
                *********************************************************
                Adicionar esse Codigo Ao Clicar em retornar para o inicio
                *********************************************************
                if (spamMask.hasAttribute('cpfMaskared','')) {
                    spamMask.removeAttribute('contatoMaskared','')
                }
                if (spamMask.hasAttribute('DataMaskared','')) {
                    spamMask.removeAttribute('cpfMaskared','')
                }   
            */
            function maskContatoSubmit(event) {
                if (!spamMask.hasAttribute('cpfMaskared', '') && !spamMask.hasAttribute('contatoMaskared', '')) {
                    // spamMask.setAttribute('contatoMaskared','')
                    contatosmask.forEach(contatoval => {
                        var target = event.target.value.replace(/\D/g, '');
                        target = target.substring(0, 11)
                        if (target.length >= 11) {
                            // console.log(inputContato.length )
                            contatoval.value = target;
                            maskMsg = target.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
                            // console.log(maskMsg);
                            if (contatoval.value.length >= 11) {
                                event.target.value = maskMsg
                                $(".submit").on("click", function(event) {
                                    spamMask.setAttribute('contatoMaskared', '')
                                    $("#userInput").attr('id', 'input_2')
                                    $("#userInput").off("input", maskContatoSubmit)
                                })
                                // console.log(e);
                            }
                        }
                    });
                }
            }

            function maskContatoOnEnter(event) {
                if (!spamMask.hasAttribute('cpfMaskared', '') && !spamMask.hasAttribute('contatoMaskared', '')) {
                    contatosmask.forEach(contatoval => {
                        if (contatoval.value.length >= 11) {
                            if (event.key === 'Enter') {
                                console.log("contato", spamMask);
                                spamMask.setAttribute('contatoMaskared', '')
                            }
                            // console.log(e);
                        }
                    })
                }
            }

            function maskcpfclickSubmit(event) {
                if (!spamMask.hasAttribute('cpfMaskared', '') && spamMask.hasAttribute('contatoMaskared', '')) {

                    console.log(contatosmask[0].value.length);
                    // console.log(spamMask.hasAttribute('cpfMaskared',''));
                    cpfmask.forEach(cpfval => {
                        if (contatosmask[0].value.length >= 11) {
                            var target = event.target.value.replace(/\D/g, '');
                            target = target.substring(0, 11)
                            if (target.length >= 11) {
                                var maskMsg = ''
                                cpfval.value = target;
                                maskMsg = target.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');

                                if (cpfval.value.length >= 11) {
                                    event.target.value = maskMsg;
                                    $(".submit").on("click", function(event) {
                                        spamMask.setAttribute('cpfMaskared', '')
                                        $("#input_2").attr('id', 'input_3')
                                        $("#userInput").off("input", maskcpfclickSubmit)
                                    })
                                }
                            }

                        }
                    });
                }
            }

            function maskcpfOnEnter(event) {
                if (!spamMask.hasAttribute('cpfMaskared', '') && spamMask.hasAttribute('contatoMaskared', '')) {
                    cpfmask.forEach(cpfval => {
                        if (cpfval.value.length >= 11) {
                            if (event.key === 'Enter') {
                                spamMask.setAttribute('cpfMaskared', '')
                            }
                            // console.log(e);
                        }
                    })
                }
            }

            function maskDataSubmit(event) {
                if (!spamMask.hasAttribute('DataMaskared', '') && spamMask.hasAttribute('cpfMaskared', '')) {
                    // console.log(e);
                    // console.log(event.target.value);
                    dataHoraMask.forEach(dataHoraVal => {
                        // console.log('tdcerto');
                        if (cpfmask[0].value.length >= 11) {
                            var target = event.target.value.replace(/\D/g, '');
                            target = target.substring(0, 12)
                            if (target.length <= 16) {
                                target = target.toString();
                                dataHoraVal.value = target;
                                var maskMsg = ''
                                if (target.length >= 2) {
                                    target = target.substring(0, 2) + '/' + target.substring(2);
                                }
                                if (target.length >= 5) {
                                    target = target.substring(0, 5) + '/' + target.substring(5);
                                }
                                if (target.length >= 10) {
                                    target = target.substring(0, 10) + ' ' + target.substring(10);
                                }
                                if (target.length >= 13) {
                                    target = target.substring(0, 13) + ':' + target.substring(13, 15);
                                }
                                maskMsg = target
                                event.target.value = maskMsg

                                if (dataHoraVal.value.length >= 11) {
                                    $(".submit").on("click", function(event) {
                                        spamMask.setAttribute('DataMaskared', '')
                                    })
                                }
                            }
                        }
                    });
                    if (spamMask.hasAttribute('DataMaskared', '')) {
                        spamMask.removeAttribute('cpfMaskared', '')
                    }
                }
            }

            function maskDataOnEnter(event) {
                if (!spamMask.hasAttribute('DataMaskared', '') && spamMask.hasAttribute('cpfMaskared', '')) {
                    dataHoraMask.forEach(dataHoraVal => {
                        if (dataHoraVal.value.length >= 12) {
                            if (event.key === 'Enter') {
                                spamMask.setAttribute('DataMaskared', '')
                            }
                            // console.log(e);
                        }
                    })
                    if (spamMask.hasAttribute('DataMaskared', '')) {
                        spamMask.removeAttribute('cpfMaskared', '')
                    }
                }
            }
        }, 1000);

    })

    const setBold = {
        Boldnumber: function() {
            // Seleciona o elemento input
            const inputNumber = document.querySelectorAll('.numeroContato');
            inputNumber.forEach(input => {
                let question = input.getAttribute('data-conv-question');
                // Substitui "Valor" por "<strong>Valor</strong>"
                let modifiedQuestion = question.replace(/número de telefone/g, '<strong>número de telefone</strong>');
                return input.setAttribute('data-conv-question', modifiedQuestion);
            });
        },
        BoldCPF: function() {
            const inputCpf = document.querySelectorAll('.cpf-mask');
            inputCpf.forEach(input => {
                let question = input.getAttribute('data-conv-question');
                let modifiedQuestion = question.replace(/informe o CPF/g, '<strong>informe o CPF</strong>');
                return input.setAttribute('data-conv-question', modifiedQuestion);
            });
        },
        BoldData: function() {
            const inputData = document.querySelectorAll('.data-hora');
            inputData.forEach(input => {
                let question = input.getAttribute('data-conv-question');
                let modifiedQuestion = question.replace(/data e hora/g, '<strong>data</strong> e <strong>hora</strong>');
                return input.setAttribute('data-conv-question', modifiedQuestion);
            });
        } //,
        // BoldData: function(){
        //     const inputData = document.querySelectorAll('.data-hora');
        //     inputData.forEach(input => {
        //         let question = input.getAttribute('data-conv-question');
        //         let modifiedQuestion = question.replace(/data e hora/g, '<strong>data e hora</strong>');
        //         return input.setAttribute('data-conv-question', modifiedQuestion);
        //     });
        // }
    };

    setBold.Boldnumber()
    setBold.BoldCPF();
    setBold.BoldData();
</script>