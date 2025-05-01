<?php
include "_system/_functionsMain.php";
include '_system/_FUNCTION_WS.php';

//echo fnDebug('true');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //fnEscreve($_GET['NOM_USUARIO']);
    //fnEscreve($_GET['DAT_NASCIME']);
    //fnEscreve($_GET['COD_SEXOPES']);
}

$cod_univend = $_REQUEST['COD_UNIVEND'];

$id = fnEncode($_REQUEST['COD_EMPRESA'] . ';' . LIMPA_DOC($_REQUEST['NUM_CGCECPF']) . ';' . $_REQUEST['COD_UNIVEND']);

//inserir cliente
//verificar se existe na base de dados 
$busca = "select count(*) as tembd from clientes where NUM_CGCECPF='" . fnLimpaDoc($_REQUEST['NUM_CGCECPF']) . "' and COD_EMPRESA=" . $_REQUEST['COD_EMPRESA'];
$retorno = mysqli_fetch_assoc(mysqli_query(connTemp($_REQUEST['COD_EMPRESA'], ''), $busca));

$cod_empresa = $_REQUEST['COD_EMPRESA'];
//busca usuário modelo 

$bsusr = "SELECT * FROM  USUARIOS
              WHERE LOG_ESTATUS='S' AND
               COD_EMPRESA = $cod_empresa AND
                COD_UNIVEND > 0 AND
                  COD_TPUSUARIO =10 limit 1";
//fnEscreve($bsusr); 
$arrayQuery = mysqli_query($connAdm->connAdm(), $bsusr);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
    $des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
    $COD_UNIVEND = explode(',', $qrBuscaUsuTeste['COD_UNIVEND']);
    $COD_UNIVEND = $COD_UNIVEND[0];
}

$arraydadosCli = array(
    'NOM_CLIENTE' => $_REQUEST['NOM_USUARIO'],
    'NUM_CGCECPF' => fnLimpaDoc($_REQUEST['NUM_CGCECPF']),
    'NUM_CARTAO' => fnLimpaDoc($_REQUEST['NUM_CGCECPF']),
    'COD_SEXOPES' => $_REQUEST['COD_SEXOPES'],
    'DAT_NASCIME' => fnDataSql($_REQUEST['DAT_NASCIME']),
    'DES_EMAILUS' => $_REQUEST['DES_EMAILUS'],
    'NUM_CELULAR' => $_REQUEST['NUM_CELULAR'],
    'COD_EMPRESA' => $cod_empresa,
    'COD_UNIVEND' => $cod_univend,
    'login' => utf8_encode($log_usuario),
    'senha' => $des_senhaus,
    'senha_cli' => ''
);


$vamosver = atualizacadastro($arraydadosCli);
// echo '<pre>';
// fnEscreve($cod_univend);
// print_r($vamosver);
// echo '</pre>';


$arraygeratkt = array(
    'cpf' => $_REQUEST['NUM_CGCECPF'],
    'cod_empresa' => rtrim(trim($cod_empresa)),
    'login' => rtrim(trim($log_usuario)),
    'senha' => rtrim(trim($des_senhaus)),
    'loja' => rtrim(trim($cod_univend))
);


GetURLTktMania($arraygeratkt);


//======================================================================================== 

//echo $id;   
//echo fnDecode(kqWQhzA1mMh4Juevbl0a2Q¢¢);
//http://adm.bunker.mk/ticket/?tkt=0dZNjqJqwg740eZxaPjrBP9sAIdp%C2%A3Kcp%C2%A3h&nome=<?php echo $_GET['NOM_USUARIO'];

echo "<h5>Cód.: <a href='https://adm.bunker.mk/ticket/?tkt=$id&print=no' target='_blank'>" . $id . "</a><h5>";

//fnEscreve(fndecode($id));
?>

<div class="push50"></div>
<div class="push20"></div>

<div id="containerCupom" style="position: relative; width: 802px; margin: 0 auto; overflow: hidden">
    <img id="top" src="images/print_top.png" />

    <iframe id="paper" src="https://adm.bunker.mk/ticket/?tkt='<?= $id; ?>'&nome=<?= $_GET['NOM_USUARIO'];  ?>&print=no" onLoad="resizeFrame()" scrolling="no" frameborder="0" height="500"></iframe>
    <img id="bottom" src="images/print_bottom.png" />
</div>
<div class="push50"></div>
<div class="push20"></div>
<hr>
<div class="form-group text-right col-lg-12">
    <button id="imprimirCupom" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"></i> Imprimir Ticket</button>
</div>

<div class="push10"></div>


<script type="text/javascript">
    var tmr;
    $(document).ready(function() {
        $("#imprimirCupom").click(function() {
            $('#paper').animate({
                'top': '120px'
            }, 6000, function() {
                ajustaContainerCupom();
                clearInterval(tmr);
            });
            ajustaContainerCupom();
            tmr = setInterval(ajustaContainerCupom, 100);
        });
    });

    function ajustaContainerCupom() {
        $('#containerCupom').height($('#paper').contents().height() + 200);
    }
</script>