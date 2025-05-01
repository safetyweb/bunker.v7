<?php

require_once '../../_system/_functionsMain.php';
date_default_timezone_set('Etc/GMT+3');


if (file_exists('./COMANDINSERT/Primeiravenda.txt')) {
  if ((time() - filemtime('./COMANDINSERT/Primeiravenda.txt')) >= 3 * 60 * 60) {

    $curl = curl_init();
    curl_setopt_array(
      $curl,
      array(
        CURLOPT_URL => 'http://externo.bunker.mk/Schedule/Primeiravenda.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 100,
        CURLOPT_TIMEOUT => 200,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
          "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
          "cache-control: no-cache"
        ),
      )
    );

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
    file_put_contents('./COMANDINSERT/Primeiravenda.txt', time());
  } else {
    echo 'fora do time envio de saldo';
  }
} else {
  $curl = curl_init();
  curl_setopt_array(
    $curl,
    array(
      CURLOPT_URL => 'http://externo.bunker.mk/Schedule/Primeiravenda.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 200,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    )
  );

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
  file_put_contents('./COMANDINSERT/Primeiravenda.txt', time());
}

//envio de saldo da comunicação
/*if(file_exists('./COMANDINSERT/EnvioSaldo.txt'))
{    
    if ((time() - filemtime('./COMANDINSERT/EnvioSaldo.txt')) >= 8 * 60 * 60) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://externo.bunker.mk/twilo/EnvioSaldo.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 100,
        CURLOPT_TIMEOUT => 180000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
                                    "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
                                    "cache-control: no-cache"
                                    ),
            )
        );

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }    
       file_put_contents('./COMANDINSERT/EnvioSaldo.txt', time());
    }else{
        echo 'fora do time envio de saldo';
    }
}else{
     $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://externo.bunker.mk/twilo/EnvioSaldo.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 100,
        CURLOPT_TIMEOUT => 180000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
                                    "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
                                    "cache-control: no-cache"
                                    ),
            )
        );

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }    
       file_put_contents('./COMANDINSERT/EnvioSaldo.txt', time());
}    */
//envio de saldo de sms por emailproxima atualização é enviar por sms
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$caminhoArquivo = './COMANDINSERT/EnvioSaldo.csv';
$intervaloTempo = 8 * 60 * 60; // 8 horas

include_once '../email/envio_sac.php';
$conadmmysql = $connAdm->connAdm();
function lerArquivoCsv($caminhoArquivo)
{
  $dados = [];
  if (file_exists($caminhoArquivo)) {
    $arquivo = fopen($caminhoArquivo, 'r');
    while (($linha = fgetcsv($arquivo)) !== false) {
      $dados[$linha[0]] = $linha[1];
    }
    fclose($arquivo);
  }
  return $dados;
}

function atualizarArquivoCsv($caminhoArquivo, $dados)
{
  $arquivo = fopen($caminhoArquivo, 'w');
  foreach ($dados as $codigoEmpresa => $time) {
    fputcsv($arquivo, [$codigoEmpresa, $time]);
  }
  fclose($arquivo);
}

function verificarEEnviarSaldo($caminhoArquivo, $intervaloTempo)
{
  global $conadmmysql;

  // Lê os dados do CSV
  $dados = lerArquivoCsv($caminhoArquivo);

  // Executa a consulta SQL para obter as empresas
  $empresas = "SELECT 
                    res.DES_GATILHO,
                    res.COD_EMPRESA, 
                    res.TIP_RESTRIC,
                    GROUP_CONCAT(DISTINCT res.COD_USUARIO SEPARATOR ',') AS COD_USUARIO,  
                    GROUP_CONCAT(DISTINCT us.DES_EMAILUS SEPARATOR ';') AS DES_EMAILUS 
                 FROM usuarios_restritos res 
                 INNER JOIN usuarios us ON us.COD_USUARIO = res.COD_USUARIO
                 WHERE 
                    res.tip_restric = 'SLD' AND    
                    us.LOG_ESTATUS = 'S' AND
                    us.DES_EMAILUS REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9._-]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9].[a-zA-Z]{2,63}$'  
                 GROUP BY res.COD_EMPRESA, res.TIP_RESTRIC";
  $rwempresa = mysqli_query($conadmmysql, $empresas);

  $tempoAtual = time();
  $retornoarray = [];

  while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
    // Verifica o saldo
    $saldo = "SELECT        
                    CASE WHEN ROUND(SUM(QTD_SALDO_ATUAL),0) <= " . $rsempresa['DES_GATILHO'] . " THEN 'saldo baixo' ELSE 'Saldo OK' END AS QTD_PRODUTO_saldo,
                    ROUND(SUM(QTD_SALDO_ATUAL),0) AS QTD_SALDO_ATUAL
                  FROM pedido_marka pedido 
                  INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                  INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                  INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                  WHERE 
                      pedido.COD_ORCAMENTO > 0 AND 
                      pedido.QTD_SALDO_ATUAL > 0 AND
                      pedido.COD_EMPRESA = " . $rsempresa['COD_EMPRESA'] . " AND
                      PAG_CONFIRMACAO = 'S' AND
                      pedido.TIP_LANCAMENTO = 'C' 
                  GROUP BY canal.COD_TPCOM, pedido.TIP_LANCAMENTO
                  ORDER BY pedido.TIP_LANCAMENTO DESC";
    $rwsaldo = mysqli_fetch_assoc(mysqli_query($conadmmysql, $saldo));

    $ultimoEnvio = isset($dados[$rsempresa['COD_EMPRESA']]) ? $dados[$rsempresa['COD_EMPRESA']] : null;
    if (!$ultimoEnvio || ($tempoAtual - $ultimoEnvio) >= $intervaloTempo) {
      if ($rwsaldo['QTD_PRODUTO_saldo'] != 'Saldo OK') {
        // Envia email se necessário
        $lines = file("https://adm.bunker.mk/templateEmail/template_emailSaldo?id=" . fnEncode($rsempresa['COD_EMPRESA']));
        $htmle = '';
        foreach ($lines as $line_num => $line) {
          $htmle .= $line;
        }
        $email = [];
        unset($email['email6']);
        $email['email6'] = $rsempresa['DES_EMAILUS'];
        //  $email['email6']='diogo_tank@hotmail.com;ricardoaugusto6693@gmail.com';
        // $email['email5'] ='diogo_tank@hotmail.com';
        //  $email['email5'] = 'diogo_tank@hotmail.com';

        $retorno = fnsacmail(
          $email,
          'Suporte Marka_' . $rsempresa['COD_EMPRESA'],
          $htmle,
          "Seu saldo disponivel: " . $rwsaldo['QTD_SALDO_ATUAL'],
          "Marka Fidelização_" . $rsempresa['COD_EMPRESA'],
          $conadmmysql,
          connTemp(7, ""),
          7
        );
        unset($htmle);
      }
      // Atualiza o arquivo CSV
      $dados[$rsempresa['COD_EMPRESA']] = $tempoAtual;
      $retornoarray[] = $rsempresa['COD_EMPRESA'];
    } else {
      $tempoRestante = $intervaloTempo - ($tempoAtual - $ultimoEnvio);
      echo "Fora do intervalo de envio de saldo para a empresa " . $rsempresa['COD_EMPRESA'] . ". Tempo restante: " . $tempoRestante . " segundos.\n";
    }
  }

  atualizarArquivoCsv($caminhoArquivo, $dados);
  echo json_encode($retornoarray, true);
}

// Chamar a função para verificar e enviar saldo
verificarEEnviarSaldo($caminhoArquivo, $intervaloTempo);

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
/*
		$filePath = './COMANDINSERT/EnvioSaldo.txt';
		$timeInterval = 8 * 60 * 60; // 8 hours

		function makeCurlRequest() {
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'http://externo.bunker.mk/twilo/EnvioSaldo.php',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 100,
				CURLOPT_TIMEOUT => 180, // 180 seconds (3 minutes)
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "",
				CURLOPT_HTTPHEADER => array(
					"Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
					"cache-control: no-cache"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				echo $response;
			}

			file_put_contents($GLOBALS['filePath'], time());
		}

		if (file_exists($filePath)) {
			if ((time() - filemtime($filePath)) >= $timeInterval) {
				makeCurlRequest();
			} else {
				echo 'fora do time envio de saldo';
			}
		} else {
			makeCurlRequest();
		}
*/


$smsv1 = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
				WHERE par.COD_TPCOM in ('2','5','1') AND apar.LOG_ATIVO='S' ORDER BY apar.cod_empresa ASC limit 1";
$rwv = mysqli_query($connAdm->connAdm(), $smsv1);
while ($rsv = mysqli_fetch_assoc($rwv)) {
  $vproc = "SELECT * FROM controle_envio where COD_COMUNICACAO=998";
  $rwvproc = mysqli_query($connAdm->connAdm(), $vproc);

  if ($rwvproc->num_rows <= 0) {

    $inproc = "INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES ($rsv[COD_EMPRESA], 1, 998);";
    $rwvproc = mysqli_query($connAdm->connAdm(), $inproc);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/Schedule/remove_blacklist.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 180000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }

    //delete o processo
    $procd = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=998";
    $rwvproc = mysqli_query($connAdm->connAdm(), $procd);
  } else {
    echo 'não executou';
    break;
  }
}


$smsv2 = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
				WHERE par.COD_TPCOM in ('2','5','1') AND apar.LOG_ATIVO='S' ORDER BY apar.cod_empresa ASC limit 1";
$rwv = mysqli_query($connAdm->connAdm(), $smsv2);
while ($rsv = mysqli_fetch_assoc($rwv)) {
  $vproc = "SELECT * FROM controle_envio where COD_COMUNICACAO=999";
  $rwvproc = mysqli_query($connAdm->connAdm(), $vproc);

  if ($rwvproc->num_rows <= 0) {

    $inproc = "INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES ($rsv[COD_EMPRESA], 1, 999);";
    $rwvproc = mysqli_query($connAdm->connAdm(), $inproc);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/Schedule/blk_sms.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 180000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }

    //delete o processo
    $procd = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=999";
    $rwvproc = mysqli_query($connAdm->connAdm(), $procd);
  } else {
    echo 'não executou';
    break;
  }
}

//restrição de envio 
$smsv = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
				WHERE par.COD_TPCOM='2'  AND apar.LOG_ATIVO='S' ORDER BY apar.cod_empresa ASC";
$rwv = mysqli_query($connAdm->connAdm(), $smsv);
while ($rsv = mysqli_fetch_assoc($rwv)) {
  $vproc = "SELECT * FROM controle_envio where COD_COMUNICACAO=2 and cod_empresa=" . $rsv['COD_EMPRESA'];
  $rwvproc = mysqli_query($connAdm->connAdm(), $vproc);

  if ($rwvproc->num_rows <= 0) {
    $inproc = "INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES ($rsv[COD_EMPRESA], 1, 2);";
    $rwvproc = mysqli_query($connAdm->connAdm(), $inproc);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/dinamize/ENVIO_SMS_TWILO.php?COD_EMPRESA=' . $rsv['COD_EMPRESA'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 180000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }

    //delete o processo
    $procd = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=2 and  cod_empresa=" . $rsv['COD_EMPRESA'];
    $rwvproc = mysqli_query($connAdm->connAdm(), $procd);
  }
}

$smsv = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
				WHERE par.COD_TPCOM='5' AND apar.COD_PARCOMU='18' AND apar.LOG_ATIVO='S'";
$rwv = mysqli_query($connAdm->connAdm(), $smsv);
while ($rsv = mysqli_fetch_assoc($rwv)) {
  $vproc = "SELECT * FROM controle_envio where COD_COMUNICACAO=18 and cod_empresa=" . $rsv['COD_EMPRESA'];
  $rwvproc = mysqli_query($connAdm->connAdm(), $vproc);

  if ($rwvproc->num_rows <= 0) {
    $inproc = "INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES ($rsv[COD_EMPRESA], 1, 18);";
    $rwvproc = mysqli_query($connAdm->connAdm(), $inproc);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/dinamize/ENVIO_PUSH.php?COD_EMPRESA=' . $rsv['COD_EMPRESA'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 180000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }

    //delete o processo
    $procd = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=18 and  cod_empresa=" . $rsv['COD_EMPRESA'];
    $rwvproc = mysqli_query($connAdm->connAdm(), $procd);
  }
}
//sleep(2);

$smsv = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
				WHERE par.COD_TPCOM='5' AND apar.COD_PARCOMU='18' AND apar.LOG_ATIVO='S'";
$rwv = mysqli_query($connAdm->connAdm(), $smsv);
while ($rsv = mysqli_fetch_assoc($rwv)) {
  $vproc = "SELECT * FROM controle_envio where COD_COMUNICACAO=18 and cod_empresa=" . $rsv['COD_EMPRESA'];
  $rwvproc = mysqli_query($connAdm->connAdm(), $vproc);

  if ($rwvproc->num_rows <= 0) {
    $inproc = "INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES ($rsv[COD_EMPRESA], 1, 18);";
    $rwvproc = mysqli_query($connAdm->connAdm(), $inproc);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/dinamize/ENVIO_PUSH_LOTE.php?COD_EMPRESA=' . $rsv['COD_EMPRESA'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 180000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }

    //delete o processo
    $procd = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=18 and  cod_empresa=" . $rsv['COD_EMPRESA'];
    $rwvproc = mysqli_query($connAdm->connAdm(), $procd);
  }
}
//sleep(2);


$smsv = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
				WHERE par.COD_TPCOM='5' AND apar.COD_PARCOMU='18' AND apar.LOG_ATIVO='S'";
$rwv = mysqli_query($connAdm->connAdm(), $smsv);
while ($rsv = mysqli_fetch_assoc($rwv)) {
  $vproc = "SELECT * FROM controle_envio where COD_COMUNICACAO=18 and cod_empresa=" . $rsv['COD_EMPRESA'];
  $rwvproc = mysqli_query($connAdm->connAdm(), $vproc);

  if ($rwvproc->num_rows <= 0) {
    $inproc = "INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES ($rsv[COD_EMPRESA], 1, 18);";
    $rwvproc = mysqli_query($connAdm->connAdm(), $inproc);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/dinamize/ENVIO_PUSH_GENERICO.php?COD_EMPRESA=' . $rsv['COD_EMPRESA'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 180000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }

    //delete o processo
    $procd = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=18 and  cod_empresa=" . $rsv['COD_EMPRESA'];
    $rwvproc = mysqli_query($connAdm->connAdm(), $procd);
  }
}
// envio whatsapp
$smsvw = "SELECT * FROM senhas_whatsapp apar
                INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU WHERE par.COD_TPCOM='6' AND apar.COD_PARCOMU='21' AND apar.LOG_ATIVO='S'";
$rwv = mysqli_query($connAdm->connAdm(), $smsvw);
while ($rsv = mysqli_fetch_assoc($rwv)) {
  $vproc = "SELECT * FROM controle_envio where COD_COMUNICACAO=21 and cod_empresa=" . $rsv['COD_EMPRESA'];
  $rwvproc = mysqli_query($connAdm->connAdm(), $vproc);

  if ($rwvproc->num_rows <= 0) {
    $inproc = "INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES ($rsv[COD_EMPRESA], 1, 21);";
    $rwvproc = mysqli_query($connAdm->connAdm(), $inproc);
    // 'http://externo.bunker.mk/dinamize/ENVIO_WHATSAPP.php?COD_EMPRESA=' . $rsv['COD_EMPRESA'] 
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/dinamize/ENVIO_WHATSAPP.php?COD_EMPRESA=' . $rsv['COD_EMPRESA'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 120,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);


    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }

    //delete o processo
    $procd = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=21 and  cod_empresa=" . $rsv['COD_EMPRESA'];
    $rwvproc = mysqli_query($connAdm->connAdm(), $procd);
  }
}
