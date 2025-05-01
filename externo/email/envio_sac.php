<?php
function fnsacmail($email, $nome, $texto, $Subject, $FromName, $conAdm, $conntemp, $cod_empresa, $caminhoanexo = FALSE, $nom_arquivo = FALSE)
{
    // Função para exibir mensagens na tela, se TIP_DEBUG > 0
    function write_log($message, $debug_level)
    {
        $timestamp = date("Y-m-d H:i:s");
        if ($debug_level > 0) {
            echo "[$timestamp] $message<br>";
        }
    }

    $confSmtp = "SELECT * from SENHAS_SMTP WHERE
                 case when cod_empresa=3 then cod_empresa
                      when cod_empresa=$cod_empresa then cod_empresa END IN (cod_empresa)
                 and LOG_ATIVO='S' ORDER BY COD_SMTP DESC LIMIT 1";

    $rsSmtp = mysqli_query($conAdm, $confSmtp);
    $msg = '';
    $array = [];

    while ($resultSmtp = mysqli_fetch_assoc($rsSmtp)) {
        $DES_EMAIL = !empty($resultSmtp['DES_FROM']) ? $resultSmtp['DES_FROM'] : $resultSmtp['DES_EMAIL'];
        $DES_USER = $resultSmtp['DES_EMAIL'];
        $DES_PORT = $resultSmtp['DES_PORT'];
        $DES_CERTIFICADO = $resultSmtp['DES_CERTIFICADO'];
        $TIP_DEBUG = $resultSmtp['TIP_DEBUG'];
        $DES_SENHA = $resultSmtp['DES_SENHA'];
        $DES_SMTP = $resultSmtp['DES_SMTP'];

        if ($cod_empresa == 3) {
            // Envio via msmtp para cod_empresa = 3
            $msmtp_check = shell_exec('which msmtp');
            if (empty($msmtp_check)) {
                $msg = "ERRO: msmtp não está instalado. Instale com 'sudo apt-get install msmtp' ou equivalente.";
                write_log($msg, $TIP_DEBUG);
                $logOK = "INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG)
                          VALUES ('$cod_empresa', '" . $email['email1'] . "', '" . date('Y-m-d H:i:s') . "', '$msg')";
                mysqli_query($conntemp, $logOK);
                continue;
            }

            write_log("msmtp encontrado: $msmtp_check", $TIP_DEBUG);

            // Montar lista de destinatários
            $recipients = [$email['email1']];
            if (!empty($email['email2'])) $recipients[] = $email['email2'];
            if (!empty($email['email3'])) $recipients[] = $email['email3'];
            if (!empty($email['email4'])) $recipients[] = $email['email4'];
            $mailarray6 = !empty($email['email6']) ? explode(';', $email['email6']) : [];
            $recipients = array_merge($recipients, $mailarray6);
            $cc_recipients = !empty($email['email5']) ? explode(';', $email['email5']) : [];

            // Montar o conteúdo do e-mail
            $email_content = "To: " . implode(', ', $recipients) . "\n";
            if (!empty($cc_recipients)) {
                $email_content .= "Cc: " . implode(', ', $cc_recipients) . "\n";
            }
            $email_content .= "From: $DES_EMAIL\n" .
                "Subject: $Subject\n" .
                "MIME-Version: 1.0\n" .
                "Content-Type: text/html; charset=UTF-8\n" .
                "\n" .
                $texto;

            // Criar arquivo temporário para o conteúdo
            $temp_file = tempnam(sys_get_temp_dir(), 'email_');
            file_put_contents($temp_file, $email_content);

            // Adicionar anexo, se existir
            $attachment_file = null;
            if (!empty($caminhoanexo) && !empty($nom_arquivo) && file_exists($caminhoanexo)) {
                $attachment_file = tempnam(sys_get_temp_dir(), 'attach_');
                copy($caminhoanexo, $attachment_file);
                $email_content_with_attachment = shell_exec("uuencode $attachment_file " . escapeshellarg($nom_arquivo) . " < $temp_file");
                file_put_contents($temp_file, $email_content_with_attachment);
            }

            // Comando msmtp
            $command = "cat $temp_file | msmtp " .
                "--host=$DES_SMTP " .
                "--port=$DES_PORT " .
                "--auth=login " .
                "--user=$DES_USER " .
                "--passwordeval='echo " . escapeshellarg($DES_SENHA) . "' " .
                "--tls=on " .
                "--tls-starttls=on " .
                "--tls-certcheck=off " .
                "--from=$DES_EMAIL " .
                implode(' ', array_map('escapeshellarg', array_merge($recipients, $cc_recipients))) . " 2>&1";

            // Executar o comando
            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);

            // Remover arquivos temporários
            unlink($temp_file);
            if (isset($attachment_file) && file_exists($attachment_file)) {
                unlink($attachment_file);
            }

            // Verificar resultado
            if ($return_var === 0) {
                $msg = "Seu email foi enviado com sucesso!";
                write_log($msg, $TIP_DEBUG);
            } else {
                $msg = "Houve um erro enviando o email: " . implode("; ", $output);
                write_log("ERRO: $msg", $TIP_DEBUG);
            }

            // Registrar log no banco
            $logOK = "INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG)
                      VALUES ('$cod_empresa', '" . $email['email1'] . "', '" . date('Y-m-d H:i:s') . "', '$msg')";
            mysqli_query($conntemp, $logOK);
        } else {
            // Envio via PHPMailer para outros cod_empresa
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host = "$DES_SMTP";
            $mail->Port = "$DES_PORT";
            $mail->SMTPSecure = "$DES_CERTIFICADO";
            $mail->SMTPAuth = true;
            $mail->Timeout = 30;
            $mail->Username = "$DES_USER";
            $mail->Password = "$DES_SENHA";
            $mail->SMTPDebug = "$TIP_DEBUG";
            $mail->From = "$DES_EMAIL";
            $mail->FromName = "$FromName";

            // Adiciona os emails
            $mail->AddAddress($email['email1'], "$nome");
            $mail->AddAddress(isset($email['email2']) ? $email['email2'] : '', "");
            $mail->AddAddress(isset($email['email3']) ? $email['email3'] : '', "");
            $mail->AddAddress(isset($email['email4']) ? $email['email4'] : '', "");

            $mailarray6 = !empty($email['email6']) ? explode(';', $email['email6']) : [];
            foreach ($mailarray6 as $dados6) {
                $mail->AddAddress($dados6, "$nome");
            }

            $mailarray = !empty($email['email5']) ? explode(';', $email['email5']) : [];
            foreach ($mailarray as $dados) {
                $mail->AddCC($dados, "");
            }

            $mail->IsHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Priority = 3;
            $mail->Subject = "$Subject";
            $mail->Body = "$texto";

            // Adiciona anexo se existir
            if (!empty($caminhoanexo) && !empty($nom_arquivo)) {
                $mail->AddAttachment("$caminhoanexo", "$nom_arquivo");
            }

            // Envia o e-mail
            $enviado = $mail->Send();

            // Registra log no banco de dados
            $msg = $enviado ? "Seu email foi enviado com sucesso!" : "Houve um erro enviando o email: " . $mail->ErrorInfo;
            $logOK = "INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG)
                      VALUES ('$cod_empresa', '" . $email['email1'] . "', '" . date('Y-m-d H:i:s') . "', '$msg')";
            mysqli_query($conntemp, $logOK);
        }
    }

    if (mysqli_num_rows($rsSmtp) == false) {
        $logOK = "INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG)
                  VALUES ('$cod_empresa', '" . $email['email1'] . "', '" . date('Y-m-d H:i:s') . "', 
                          'Conta SMTP não cadastrada ou iniciada!')";
        mysqli_query($conntemp, $logOK);
    }

    return array(
        'sql' => $confSmtp,
        'SQLLOG' => $logOK,
        'msg' => @$msg,
        'COnn' => $conntemp,
        'array' => $array
    );
}
