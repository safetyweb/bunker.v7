<?php
include '../../_system/_functionsMain.php';
$JSONRETORNO=file_get_contents("php://input");
parse_str($JSONRETORNO, $json_arrayP);
$uri= base64_decode($_REQUEST['ID']);
$dadoscampanha=explode('||',$uri);
$EMPRESA=$dadoscampanha[1];
$CAMPANHA=$dadoscampanha[0];
$testeinsert="INSERT INTO log_nuxux (COD_CAMPANHA,COD_EMPRESA, TIP_LOG, LOG_JSON,DAT_CADASTR,CHAVE_GERAL,CHAVE_CLIENTE) VALUES ('$CAMPANHA','$EMPRESA', '22', '". addslashes($JSONRETORNO)."','".date('Y-m-d')."','".$_REQUEST['ID']."','".$json_arrayP['SmsSid']."');";
$t=mysqli_query(connTemp($EMPRESA, ''),$testeinsert);


if(!$t)
{     
    parse_str($JSONRETORNO, $json_array);
    // Caminho do arquivo
    $arquivo = './COMANDINSERT/'.$EMPRESA.'_'.$json_array[SmsSid].'_'.$_REQUEST['ID'].'_arquivo.txt';
    // Conteúdo a ser acrescentado
    // Verifica se o arquivo existe

    if (file_exists($arquivo)) {
        // Obtém o conteúdo atual do arquivo
        $conteudoAtual = file_get_contents($arquivo);

        // Acrescenta o novo conteúdo na última linha
        $novoConteudo = $conteudoAtual . PHP_EOL . $testeinsert;

        // Escreve o novo conteúdo no arquivo
        file_put_contents($arquivo, $novoConteudo);
    } else {
        // Cria o arquivo e escreve o conteúdo nele
        file_put_contents($arquivo, $testeinsert);
    }
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++    
mysqli_close( $contemporaria);
