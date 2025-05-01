<?php
include "_system/EMAIL/PHPMailer/PHPMailerAutoload.php";

function geraCsv($cod_empresa, $conAdm)
{
    // BUSCA USUARIO WS
    $sql = "SELECT LOG_USUARIO, DES_SENHAUS FROM usuarios WHERE COD_EMPRESA = $cod_empresa AND COD_TPUSUARIO = 10";
    $arrayQuery = mysqli_query($conAdm, $sql);
    $qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
    $log_usuario = $qrBuscaUsuario['LOG_USUARIO'];
    $des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];

    // Prepara o nome do arquivo CSV
    $directory = 'media/clientes/3/';

    $fileName = $directory . "dados_usuarios_$cod_empresa.csv";
    // Cria o arquivo CSV e abre para escrita
    $file = fopen($fileName, 'w');

    // Adiciona cabeçalho no CSV
    $header = ['ID_LOJA', 'CNPJ', 'NOMA_UNIDADE', 'ID_EMPRESA', 'LOGIN', 'SENHA', 'TOKEN'];
    fputcsv($file, $header);

    // Busca as unidades de venda
    $sqlBusca = "SELECT COD_UNIVEND, NOM_FANTASI, NUM_CGCECPF FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa";
    $query = mysqli_query($conAdm, $sqlBusca);
    $webhook = 'webhook';

    // Preenche o CSV com os dados das unidades
    while ($row = mysqli_fetch_assoc($query)) {
        $codUnivend = $row['COD_UNIVEND'];
        $nomeFantasia = $row['NOM_FANTASI'];
        $numCnpj = fnformatCnpjCpf($row['NUM_CGCECPF']);

        //MONTA BASE64
        $usuarioEncode = $log_usuario . ';' . fnDecode($des_senhaus) . ';' . fnDecode($codUnivend) . ';' . $webhook . ';' . fnDecode($cod_empresa);
        $autoriz = base64_encode(fnEncode($usuarioEncode));

        // Dados para cada linha do CSV
        $data = [$codUnivend, $numCnpj, $nomeFantasia, $cod_empresa, $log_usuario, fnDecode($des_senhaus), $autoriz];
        fputcsv($file, $data);
    }

    // Fecha o arquivo CSV
    fclose($file);

    // Retorna o nome do arquivo gerado
    return $fileName;
}

function fnAutommail($email, $nome, $texto, $Subject, $FromName, $conAdm, $conntemp, $cod_empresa, $caminhoanexo = FALSE, $nom_arquivo = FALSE)
{

    $confSmtp = "SELECT * from SENHAS_SMTP WHERE
        case when cod_empresa=3 then cod_empresa
            when cod_empresa=3 then cod_empresa END IN (cod_empresa)
        and LOG_ATIVO='S' ORDER BY COD_SMTP DESC LIMIT 1";

    $rsSmtp = mysqli_query($conAdm, $confSmtp);
    if ($resultSmtp = mysqli_fetch_assoc($rsSmtp)) {

        $DES_EMAIL = !empty($resultSmtp['DES_FROM']) ? $resultSmtp['DES_FROM'] : $resultSmtp['DES_EMAIL'];
        $DES_USER = $resultSmtp['DES_EMAIL'];
        $DES_PORT = $resultSmtp['DES_PORT'];
        $DES_CERTIFICADO = $resultSmtp['DES_CERTIFICADO'];
        $TIP_DEBUG = $resultSmtp['TIP_DEBUG'];
        $DES_SENHA = $resultSmtp['DES_SENHA'];
        $DES_SMTP = $resultSmtp['DES_SMTP'];

        // Inicia a classe PHPMailer 
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


        // Verifica se email6 existe antes de explodir
        $mailarray6 = !empty($email['email6']) ? explode(';', $email['email6']) : [];
        foreach ($mailarray6 as $dados6) {
            $mail->AddAddress($dados6, "$nome");
        }

        // Verifica se email5 existe antes de explodir
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

        $arquivo = geraCsv($cod_empresa, $conAdm);

        // Adiciona o segundo anexo (o arquivo CSV gerado)
        if (!empty($arquivo)) {
            $mail->AddAttachment("./media/clientes/3/dados_usuarios_$cod_empresa.csv", "dados_usuarios_$cod_empresa.csv");
        }

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

    if (mysqli_num_rows($rsSmtp) == false) {
        $logOK = " INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG) 
                            VALUES ('$cod_empresa', 
                                    '" . $email['email1'] . "', 
                                    '" . date('Y-m-d H:i:s') . "', 
                                    'Conta SMTP não cadastrada ou iniciada!')";
        mysqli_query($conntemp, $logOK);
    }
    $array = array();
    return array(
        'sql' => $confSmtp,
        'SQLLOG' => $logOK,
        'msg' => @$msg,
        'COnn' => $conntemp,
        'array' => $array
    );
}
