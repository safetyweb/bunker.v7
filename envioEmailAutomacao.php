<?php

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function write_log($message, $debug_level)
{
  $timestamp = date("Y-m-d H:i:s");
  if ($debug_level > 0) {
    echo "[$timestamp] $message<br>";
  }
  // Exibir no console/navegador se o nível de debug for maior que 0
  if ($debug_level > 0) {
    echo "[$timestamp] $message\n" . "<br>";
  }
}

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

function fnAutommail($email, $nome, $Subject, $FromName, $conAdm, $conntemp, $cod_empresa, $caminhoanexo = FALSE, $nom_arquivo = FALSE)
{
  $msg = "";

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

    // Envio via msmtp para cod_empresa = 3
    $msmtp_check = shell_exec('which msmtp');
    if (empty($msmtp_check)) {
      $msg = "ERRO: msmtp não está instalado. Instale com 'sudo apt-get install msmtp' ou equivalente.";
      write_log($msg, $TIP_DEBUG);
      $logOK = "INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG)
                                VALUES ('$cod_empresa', '" . $email['email1'] . "', '" . date('Y-m-d H:i:s') . "', '$msg')";
      mysqli_query($conntemp, $logOK);
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

    $sqlEmp = "SELECT NOM_EMPRESA, NUM_CGCECPF FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

    $rsEmp = mysqli_query($conAdm, $sqlEmp);
    $qrEmp = mysqli_fetch_assoc($rsEmp);
    $razaosocial = $qrEmp['NOM_EMPRESA'];
    $cnpj = $qrEmp['NUM_CGCECPF'];

    $corpoMsg = "	<table class='nl-container' width='100%' border='0' cellpadding='0' cellspacing='0' role='presentation'
                  style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;  background-image: none; background-position: top left; background-repeat: no-repeat;'>
                  <tbody>
                    <tr>
                      <td>
                
                        <table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0'
                          role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                          <tbody>
                            <tr>
                              <td>
                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0'
                                  role='presentation'
                                  style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;'
                                  width='600.00'>
                                  <tbody>
                                    <tr>
                                      <td class='column column-1' width='100%'
                                        style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 0px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                        <table class='text_block block-1' width='100%' border='0' cellpadding='15' cellspacing='0'
                                          role='presentation'
                                          style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                          <tbody>
                                            <tr>
                                              <td class='pad'>
                                                <div style='font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif'>
                                                  <div class=''
                                                    style='font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;'>
                                                    <p
                                                      style='margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px; letter-spacing: normal; line-height: 1.2;'>
                                                      <span style='font-size:22px;'>Ativação da integração</span>
                                                    </p>
                                                  </div>
                                                </div>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                
                        <br>
                
                        <table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0'
                          role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                          <tbody>
                            <tr>
                              <td>
                                <table class='row-content stack' align='center' border='0' cellpadding='0' cellspacing='0'
                                  role='presentation'
                                  style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;'
                                  width='600.00'>
                                  <tbody>
                                    <tr>
                                      <td class='column column-1' width='100%'
                                        style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;'>
                                        <table class='text_block block-1' width='100%' border='0' cellpadding='15' cellspacing='0'
                                          role='presentation'
                                          style='mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;'>
                                          <tbody>
                                            <tr>
                                              <td class='pad'>
                                                <div style='font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif'>
                                                  <div class=''
                                                    style='font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;'>
                                                    <p
                                                      style='margin: 0; font-size: 14px; mso-line-height-alt: 16.8px; letter-spacing: normal;'>
                                                      <span style='font-size:18px;'>
                                                        Olá,
                                                        <br>
                                                        <br>
                                                        Solicito a ativação do programa de fidelidade para a empresa $razaosocial - CNPJ: $cnpj. <br> <br>Segue em anexo a planilha contendo os dados de login.
                                                      </span>
                                                    </p>
                                                  </div>
                                                </div>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                
                        <table class='row row-7' align='center' width='100%' border='0' cellpadding='0' cellspacing='0'
                          role='presentation' style='mso-table-lspace: 0pt; mso-table-rspace: 0pt;'>
                          <tbody>
                            <tr>
                              <td style='padding:0 10px'>
                                <table width='100%' border='0'>
                                  <tbody>
                                    <tr>
                                      <td
                                        style='font-family: 'Verdana', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif';font-size: 18px; color:#616160;line-height: 20px'>
                                        <strong>MARKA COMUNICAÇÃO</strong><br>
                                      </td>
                                      <td style='text-align: right;'><a href='https://www.marka.mk'><img
                                            src='http://marka.mk/assinaturas/2019/logomarka.png'></a></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td style='border-top:1px solid #ccc; text-align: center'>
                                <table width='100%' border='0'>
                                  <tbody style='border-top:1px solid #ccc'>
                                    <tr style='align-items: center; text-align: center;'>
                                      <td
                                        style='font-family: 'Verdana', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif';font-size: 14px;'>
                                        <img src='http://marka.mk/assinaturas/2019/phone.png'
                                          style='position: relative;top:5px;margin-right: 5px'><a href='tel:1199839-0118'
                                          style='font-family: 'Verdana', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif';font-size: 14px; color:#616160; text-decoration:none;'>(11)
                                          92033-5834</a>
                                      </td>
                                      <td><img src='http://marka.mk/assinaturas/2019/mail.png'
                                          style='position: relative;top:5px;margin-right: 5px'><a
                                          href='mailto:coordenacaoti@markafidelizacao.com.br'
                                          style='font-family: 'Verdana', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif';font-size: 14px; color:#616160; text-decoration:none;'>coordenacaoti@markafidelizacao.com.br</a>
                                      </td>
                                      <td><img src='http://marka.mk/assinaturas/2019/site.png'
                                          style='position: relative;top:5px;margin-right: 5px'><a href='http://www.marka.mk'
                                          style='font-family: 'Verdana', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, 'sans-serif';font-size: 14px; color:#616160; text-decoration:none;'>www.marka.mk</a>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td style='padding:10px 0'><img width='100%' src='http://marka.mk/assinaturas/2019/linha.png'></td>
                            </tr>
                          </tbody>
                        </table>";

    // Definir o boundary para separar as partes do e-mail
    $boundary = "boundary_" . md5(time());

    // Atualizar os cabeçalhos do e-mail para usar multipart/mixed
    $email_content = "To: " . implode(', ', $recipients) . "\n";
    if (!empty($cc_recipients)) {
      $email_content .= "Cc: " . implode(', ', $cc_recipients) . "\n";
    }
    $email_content .= "From: \"$nome\" <$DES_EMAIL>\n" .
      "Subject: $Subject\n" .
      "MIME-Version: 1.0\n" .
      "Content-Type: multipart/mixed; boundary=\"$boundary\"\n\n";

    // Adicionar o corpo do e-mail como a primeira parte
    $email_content .= "--$boundary\n";
    $email_content .= "Content-Type: text/html; charset=UTF-8\n";
    $email_content .= "Content-Transfer-Encoding: 7bit\n\n";
    $email_content .= $corpoMsg . "\n\n";

    // Adicionar o anexo PNG, se existir
    if (!empty($caminhoanexo) && !empty($nom_arquivo)) {
      $full_path = realpath($caminhoanexo . DIRECTORY_SEPARATOR . $nom_arquivo);
      if ($full_path && file_exists($full_path)) {
        $file_content = file_get_contents($full_path);
        if ($file_content !== false) {
          $base64_content = base64_encode($file_content);

          $email_content .= "--$boundary\n";
          $email_content .= "Content-Type: application/octet-stream; name=\"" . basename($nom_arquivo) . "\"\n";
          $email_content .= "Content-Transfer-Encoding: base64\n";
          $email_content .= "Content-Disposition: attachment; filename=\"" . basename($nom_arquivo) . "\"\n\n";
          $email_content .= chunk_split($base64_content) . "\n";
        }
      }
    }

    // Adicionar o anexo CSV, se existir
    $csv_file_path = geraCsv($cod_empresa, $conAdm); // Esta função retorna o caminho completo do arquivo CSV gerado

    if (!empty($csv_file_path) && file_exists($csv_file_path)) {
      $csv_content = file_get_contents($csv_file_path);
      if ($csv_content !== false) {
        $base64_csv_content = base64_encode($csv_content);

        $email_content .= "--$boundary\n";
        $email_content .= "Content-Type: text/csv; name=\"" . basename($csv_file_path) . "\"\n";
        $email_content .= "Content-Transfer-Encoding: base64\n";
        $email_content .= "Content-Disposition: attachment; filename=\"" . basename($csv_file_path) . "\"\n\n";
        $email_content .= chunk_split($base64_csv_content) . "\n";
      }
    }

    // Finalizar o e-mail com o boundary de encerramento
    $email_content .= "--$boundary--\n";

    // Salvar o conteúdo final em um arquivo temporário
    $final_temp_file_for_msmtp = tempnam(sys_get_temp_dir(), 'final_email_');
    file_put_contents($final_temp_file_for_msmtp, $email_content);
    $temp_files_to_unlink[] = $final_temp_file_for_msmtp;

    // O comando msmtp espera que o e-mail completo (cabeçalhos, corpo, anexos) seja passado via STDIN
    $command = "cat " . escapeshellarg($final_temp_file_for_msmtp) . " | msmtp " .
      "--host=" . escapeshellarg($DES_SMTP) . " " .
      "--port=" . escapeshellarg($DES_PORT) . " " .
      "--auth=login " .
      "--user=" . escapeshellarg($DES_USER) . " " .
      "--passwordeval='echo " . escapeshellarg($DES_SENHA) . "' " .
      // As opções TLS podem precisar de ajuste dependendo do servidor. --tls-certcheck=off é inseguro.
      "--tls=on " .
      "--tls-starttls=on " .
      "--tls-certcheck=off " . // ATENÇÃO: Inseguro para produção
      "--from=" . escapeshellarg($DES_EMAIL) . " " .
      implode(' ', array_map('escapeshellarg', array_unique(array_filter(array_merge($recipients, $cc_recipients))))) . " 2>&1";

    write_log("Comando msmtp: $command", $TIP_DEBUG);

    // Executar o comando
    $output = [];
    $return_var = -1; // Inicializa com um valor que indica que não foi executado

    // !!! DESCOMENTE A LINHA ABAIXO PARA ENVIAR O E-MAIL !!!
    exec($command, $output, $return_var);

    // Remover arquivos temporários
    // foreach ($temp_files_to_unlink as $temp_file_to_remove) {
    //   if (file_exists($temp_file_to_remove)) {
    //     unlink($temp_file_to_remove);
    //     echo "Arquivo temporário removido: $temp_file_to_remove";
    //   }
    // }
    // Se o arquivo CSV original foi gerado e você deseja removê-lo após o envio:
    // if (!empty($csv_file_path) && file_exists($csv_file_path)) {
    //     unlink($csv_file_path);
    // }

    // Registrar log no banco
    $logOK = "INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG)
                            VALUES ('3', '" . $email['email1'] . "', '" . date('Y-m-d H:i:s') . "', '$msg')";
    mysqli_query($conntemp, $logOK);
  }

  if (mysqli_num_rows($rsSmtp) == false) {
    $logOK = " INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG) 
                            VALUES ('3', 
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
