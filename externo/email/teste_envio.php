<?php
/*
// Configurações do SMTP
$server = "pro1.mail.ovh.net";
$port = 587;
$username = "comunicacao@bunker.mk";
$password = "egeFJ62g02w7";

// Detalhes do e-mail
$to = "diogo_tank@hotmail.com";
$from = "comunicacao@bunker.mk";
$subject = "Assunto do E-mail";
$body = "Olá, este é um e-mail de teste enviado via msmtp!";

// Função para exibir mensagens na tela
function write_log($message)
{
    $timestamp = date("Y-m-d H:i:s");
    echo "[$timestamp] $message\n";
}

// Verificar se o msmtp está instalado
$msmtp_check = shell_exec('which msmtp');
if (empty($msmtp_check)) {
    write_log("ERRO: msmtp não está instalado. Instale com 'sudo apt-get install msmtp' ou equivalente.");
    die();
} else {
    write_log("msmtp encontrado: $msmtp_check");
}

// Montar o conteúdo do e-mail
$email_content = <<<EOD
To: $to
From: $from
Subject: $subject
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8

$body
EOD;

// Criar um arquivo temporário para o conteúdo do e-mail
$temp_file = tempnam(sys_get_temp_dir(), 'email_');
file_put_contents($temp_file, $email_content);

// Comando msmtp com configurações passadas diretamente
$command = "cat $temp_file | msmtp " .
    "--host=$server " .
    "--port=$port " .
    "--auth=login " .
    "--user=$username " .
    "--passwordeval='echo $password' " .
    "--tls=on " .
    "--tls-starttls=on " .
    "--tls-certcheck=off " .
    "--from=$from " .
    "$to 2>&1";

// Executar o comando
$output = [];
$return_var = 0;
exec($command, $output, $return_var);

// Remover o arquivo temporário
unlink($temp_file);

// Verificar o resultado
if ($return_var === 0) {
    write_log("E-mail enviado com sucesso!");
} else {
    write_log("ERRO: Falha ao enviar o e-mail.");
    write_log("Saída do msmtp: " . implode("\n", $output));
    die();
}
*/
include "envio_sac.php";
include "../../_system/_functionsMain.php";

$cod_empresa = '3';
$emailDestino = array('email1' => 'diogo_tank@hotmail.com');
$aqui = fnsacmail(
    $emailDestino,
    "Suporte Marka",
    "</html>teste</html>",
    "[TESTE] A Loja 18 tem um presente para você!!",
    "Teste de Envio",
    $connAdm->connAdm(),
    connTemp($cod_empresa, ""),
    "3"
);
echo '<pre>';
print_r($aqui);
echo '</pre>';
