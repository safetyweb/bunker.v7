<?php
$server->wsdl->addComplexType(
  'DadosBusca',
  'complexType',
  'struct',
  'all',
  '',
  array(
    'cartao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartao', 'type' => 'xsd:string'),
    'cpf' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cpf', 'type' => 'xsd:string'),
    'cnpj' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cnpj', 'type' => 'xsd:string'),
    'email' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'email', 'type' => 'xsd:string'),
    'telefone' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'telefone', 'type' => 'xsd:string'),
    'codcliente' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codcliente', 'type' => 'xsd:integer'),
    'loginfacebook' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'loginfacebook', 'type' => 'xsd:string'),
    'logingoogle' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'logingoogle', 'type' => 'xsd:string'),
    'genericobuscaconsumidor' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'genericobuscaconsumidor', 'type' => 'xsd:string')
  )
);

$server->register(
  'BuscaConsumidor',
  array(
    'fase' => 'xsd:string',
    'opcoesbuscaconsumidor' => 'tns:DadosBusca',
    'dadosLogin' => 'tns:LoginInfo'
  ),  //parameters
  array('BuscaConsumidorResponse' => 'tns:acao'),  //output
  $ns,                     // namespace
  "$ns/BuscaConsumidor",                 // soapaction
  'document',                         // style
  'literal',                          // use
  'BuscaConsumidor'             // documentation
);


function BuscaConsumidor($fase, $opcoesbuscaconsumidor, $dadosLogin)
{
  ob_start('ob_gzhandler');
  include_once  '../_system/Class_conn.php';
  include_once  'func/function.php';


  // Define o diretório onde o arquivo será salvo
  $cacheDir = '/srv/www/htdocs/wsmarka/config_empresa';

  // Verifica se o diretório existe; se não, cria-o (com permissões 0755)
  if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
  }

  // Define o caminho completo do arquivo de cache, usando o idcliente para personalizar o nome
  $cacheFile = $cacheDir . "/config_empresa_" . $dadosLogin['idcliente'] . ".txt";

  // Define o tempo de validade do cache: 15 minutos (15 * 60 = 900 segundos)
  $cacheTime = 900;

  // Verifica se o arquivo de cache existe e se ainda está dentro do período válido
  if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    // O arquivo existe e não expirou: carrega os dados salvos
    $row = json_decode(file_get_contents($cacheFile), true);
  } else {
    // O arquivo não existe ou expirou: executa a query para obter as informações atualizadas
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $buscauser = mysqli_query($connAdm->connAdm(), $sql);
    // Obtém o resultado da query
    $row = mysqli_fetch_assoc($buscauser);

    // Salva os dados obtidos no arquivo em formato JSON (sobrescrevendo o que estava lá)
    //file_put_contents($cacheFile, json_encode($row));
    // Só salva os dados no arquivo se o retorno não for nulo
    if ($row !== null) {
      file_put_contents($cacheFile, json_encode($row));
    } else {
      return  array('BuscaConsumidorResponse' => array(
        'msgerro' => 'Dados Login Invalidos!',
        'coderro' => '80'
      ));
    }
  }

  /* $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);*/

  //compara os id_cliente com o cod_empresa
  if ($opcoesbuscaconsumidor['cpf'] != '') {
    $logcpf = fnlimpaCPF($opcoesbuscaconsumidor['cpf']);
  } else {
    $logcpf = fnlimpaCPF($opcoesbuscaconsumidor['cartao']);
  }

  if ($opcoesbuscaconsumidor['cpf'] == '' && $opcoesbuscaconsumidor['cartao'] == '' && $opcoesbuscaconsumidor['telefone'] != '') {
    $telbunsca = preg_replace('/[^0-9]/', '', $opcoesbuscaconsumidor['telefone']);
    $logcpf = $telbunsca;
  }

  if ($opcoesbuscaconsumidor['cpf'] == '' && $opcoesbuscaconsumidor['cartao'] == '' && $opcoesbuscaconsumidor['telefone'] == '') {
    $logcpf = fnlimpaCPF($opcoesbuscaconsumidor['cnpj']);
  }
  if ($dadosLogin['idloja'] != '') {

    if ($dadosLogin['idloja'] != '0') {
      $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                         WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
      $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
      if ($lojars['LOG_ESTATUS'] != 'S') {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', ' Loja desabilidata', $row['LOG_WS']);
        return  array('BuscaConsumidorResponse' => array(
          'msgerro' => 'LOJA DESABILITADA',
          'coderro' => '80'
        ));
        exit();
      }
    }
  }
  //conn user


  $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);




  /*if(mysqli_errno($connUser->connUser())!='0')
        {
            $testecon='EMPRESA:'.$dadosLogin['idcliente'].' LOJA:'.$dadosLogin['idloja'].' IP:'.$row['IP']. 'Usuario:'.$row['USUARIODB'].' SENHA:'.fnDecode($row['SENHADB']).'DATABaSE: '.$row['NOM_DATABASE'];
            $arquivo = './log_txt/'.$dadosLogin['idloja'].'_con_arquivo.txt';
            // Cria o arquivo e escreve o conteúdo nele
            file_put_contents($arquivo, $testecon);
        }
        
        if (!$connUser->connUser()) {
    
               $testecon='EMPRESA:'.$dadosLogin['idcliente'].' LOJA:'.$dadosLogin['idloja'].' IP:'.$row['IP']. 'Usuario:'.$row['USUARIODB'].' SENHA:'.fnDecode($row['SENHADB']).'DATABaSE: '.$row['NOM_DATABASE'];
                $arquivo = './log_txt/'.$dadosLogin['idloja'].'_con_arquivo.txt';
                // Cria o arquivo e escreve o conteúdo nele
                file_put_contents($arquivo, $testecon);

            }
            if (!$connAdm->connAdm()) {
    
               $testecon='EMPRESA:'.$dadosLogin['idcliente'].' LOJA:'.$dadosLogin['idloja'].' IP:'.$row['IP']. 'Usuario:'.$row['USUARIODB'].' SENHA:'.fnDecode($row['SENHADB']).'DATABaSE: '.$row['NOM_DATABASE'];
                $arquivo = './log_txt/'.$dadosLogin['idloja'].'_con_arquivo.txt';
                // Cria o arquivo e escreve o conteúdo nele
                file_put_contents($arquivo, $testecon);

            }*/


  //nova regra de casas decimais
  $CONFIGUNI = "SELECT * FROM unidades_parametro WHERE 
                                            COD_EMPRESA=" . $dadosLogin['idcliente'] . " AND 
                                            COD_UNIVENDA=" . $dadosLogin['idloja'] . " AND LOG_STATUS='S'";
  $RSCONFIGUNI = mysqli_query($connUser->connUser(), $CONFIGUNI);
  if (!$RSCONFIGUNI) {
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'erro na pre-venda dados unidade', $row['LOG_WS']);
  } else {
    if ($RCCONFIGUNI = mysqli_num_rows($RSCONFIGUNI) > 0) {
      //aqui pega da unidade
      $RWCONFIGUNI = mysqli_fetch_assoc($RSCONFIGUNI);
      $dec = $RWCONFIGUNI['NUM_DECIMAIS'];
      if ($RWCONFIGUNI['TIP_RETORNO'] == 2) {
        $decimal = '2';
      } else {
        $decimal = '0';
      }

      $LOG_CADVENDEDOR = $RWCONFIGUNI['LOG_CADVENDEDOR'];
    } else {
      //aqui pega da controle de licença
      $dec = $row['NUM_DECIMAIS'];
      if ($row['TIP_RETORNO'] == 2) {
        $decimal = '2';
      } else {
        $decimal = '0';
      }
      $LOG_CADVENDEDOR = $row['LOG_CADVENDEDOR'];
    }
  }
  /*	if($logcpf=='35196685804')
	   {	
	   $return=array('BuscaConsumidorResponse'=>array('msgerro'=> $decimal,
                                                            'coderro'=>'4')); 
	   }
		*/
  //log

  $xmlteste = addslashes(file_get_contents("php://input"));
  $arrylog = array(
    'cod_usuario' => $row['COD_USUARIO'],
    'login' => $dadosLogin['login'],
    'cod_empresa' => $row['COD_EMPRESA'],
    'idloja' => $dadosLogin['idloja'],
    'idmaquina' => $dadosLogin['idmaquina'],
    'cpf' => $logcpf,
    'xml' => $xmlteste,
    'tables' => 'origembusca',
    'conn' => $connUser->connUser(),
    'pdv' => '0'
  );

  $cod_log = fngravalogxml($arrylog);
  /* if($opcoesbuscaconsumidor['cpf']=='35196685804')
	   {	
	     return array('BuscaConsumidorResponse'=>array('msgerro'=> 'diogo'.$decimal,
                                                            'coderro'=>'4')); 
	   }*/

  //////////////////////////////////////////////////////
  if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
    //  $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'BuscaConsumidor',$dadosLogin['idcliente']);


    //grava retorno ws    
    $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><BuscaConsumidorResponse></BuscaConsumidorResponse>");
    //array_to_xml($return,$xml_user_info);
    //Grava_log($connUser->connUser(),$LOG,'Valor Resgate maior que o permitido', addslashes($xml_user_info->asXML()));


    //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
    if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $logcpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'Id_cliente não confere com o cadastro......', $row['LOG_WS']);
      $return = array('BuscaConsumidorResponse' => array(
        'msgerro' => 'Id_cliente não confere com o cadastro!',
        'coderro' => '4'
      ));
      array_to_xml($return, $xml_user_info);
      Grava_log_msgxml($connUser->connUser(), 'msg_busca', $cod_log, 'Id_cliente não confere com o cadastro!', addslashes($xml_user_info->asXML()));
      return $return;
      exit();
    }
    //VERIFICA SE A EMPRESA FOI DESABILITADA
    if ($row['LOG_ATIVO'] == 'N') {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $logcpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);

      $return = array('BuscaConsumidorResponse' => array(
        'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
        'coderro' => '37'
      ));
      array_to_xml($return, $xml_user_info);
      Grava_log_msgxml($connUser->connUser(), 'msg_busca', $cod_log, 'Oh não! A empresa foi desabilitada por algum motivo ;-[!', addslashes($xml_user_info->asXML()));
      return $return;
      exit();
    }
    //VERIFICA SE O USUARIO FOI DESABILITADA
    if ($row['LOG_ESTATUS'] == 'N') {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);

      $return = array('BuscaConsumidorResponse' => array(
        'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
        'coderro' => '44'
      ));
      array_to_xml($return, $xml_user_info);
      Grava_log_msgxml($connUser->connUser(), 'msg_busca', $cod_log, 'Oh não! Usuario foi desabilitado ;-[!', addslashes($xml_user_info->asXML()));
      return $return;
      exit();
    }
    //////////////////////=================================================================================================================

  } else {

    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $logcpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'Usuario ou senha Inválido!', 'S');

    return  array('BuscaConsumidorResponse' => array(
      'msgerro' => 'Usuario ou senha Inválido!',
      'coderro' => '5'
    ));
    exit();
  }
  if (strlen(fnlimpaCPF($opcoesbuscaconsumidor['cpf'])) >= 7) {
    if (valida_cpf(fncompletadoc(fnlimpaCPF($opcoesbuscaconsumidor['cpf']), 'F')) != 1 && fncompletadoc(fnlimpaCPF($opcoesbuscaconsumidor['cpf']), 'F') != 0) {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'CPF digitado é inválido!', $row['LOG_WS']);

      $cod = 33;
      $msg = 'CPF digitado é inválido!';
      $return = array('BuscaConsumidorResponse' => array(
        'msgerro' => 'CPF digitado é inválido!',
        'coderro' => '33'
      ));
      array_to_xml($return, $xml_user_info);
      Grava_log_msgxml($connUser->connUser(), 'msg_busca', $cod_log, $msg, addslashes($xml_user_info->asXML()));
      return $return;
      exit();
    } else {
      $cod = 39;
      $msg = 'OK';
    }
    //só pra vida e saude , pois a trier esta enviando cartão no campo de cpf    
  }
  if ($row['COD_EMPRESA'] == '37') {
    if (valida_cpf(fncompletadoc(fnlimpaCPF($opcoesbuscaconsumidor['cpf']), 'F')) != 1 && fncompletadoc(fnlimpaCPF($opcoesbuscaconsumidor['cpf']), 'F') != 0) {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'CPF digitado é inválido!', $row['LOG_WS']);

      $cod = 33;
      $msg = 'CPF digitado é inválido!';
      $return = array('BuscaConsumidorResponse' => array(
        'msgerro' => 'CPF digitado é inválido!',
        'coderro' => '33'
      ));
      array_to_xml($return, $xml_user_info);
      Grava_log_msgxml($connUser->connUser(), 'msg_busca', $cod_log, $msg, addslashes($xml_user_info->asXML()));
      return $return;
      exit();
    } else {
      $cod = 39;
      $msg = 'OK';
    }
  }
  // return  array('BuscaConsumidorResponse'=>array( 'msgerro' => $LOG_CONSEXT));
  if ($opcoesbuscaconsumidor['codcliente'] == 0 || $opcoesbuscaconsumidor['codcliente'] == '') {
    $cod_clientes = '';
  } else {
    $cod_clientes = $opcoesbuscaconsumidor['codcliente'];
  }
  $arrayconsulta = array(
    'ConnB' => $connUser->connUser(),
    'conn' => $connAdm->connAdm(),
    'database' => $row['NOM_DATABASE'],
    'cod_cliente' => $cod_clientes,
    'empresa' => $row['COD_EMPRESA'],
    'fase' => $fase,
    'cartao' =>  fnlimpaCPF($opcoesbuscaconsumidor['cartao']),
    'cpf' => fnlimpaCPF($opcoesbuscaconsumidor['cpf']),
    'cnpj' => fnlimpaCPF($opcoesbuscaconsumidor['cnpj']),
    'email' =>  $opcoesbuscaconsumidor['email'],
    'telefone' =>  $telbunsca,
    'consultaativa' => $row['LOG_CONSEXT'],
    'login' => $dadosLogin['login'],
    'senha' => $dadosLogin['senha'],
    'idloja' => $dadosLogin['idloja'],
    'idmaquina' => $dadosLogin['idmaquina'],
    'codvendedor' => $dadosLogin['codvendedor'],
    'nomevendedor' => $dadosLogin['nomevendedor'],
    'COD_USUARIO' => $row['COD_USUARIO'],
    'pagina' => 'BuscaConsumidor',
    'COD_UNIVEND' => $dadosLogin['idloja'],
    'venda' => 'nao',
    'coderro' => $cod,
    'menssagem' => $msg,
    'generico' => $opcoesbuscaconsumidor['genericobuscaconsumidor'],
    'LOG_WS' => $row['LOG_WS'],
    'dec' => $dec,
    'decimal' => $decimal,
    'LOG_CADTOKEN' => $row['LOG_CADTOKEN']
  );

  //atualizar os campos e libera os pontos. 
  if ($row['LOG_ATIVCAD'] == 'S') {
    $arraybusca[] = fn_consultaBase($arrayconsulta);
    $CAMPOSSQL = "select KEY_CAMPOOBG  from matriz_campo_integracao                         
			inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=matriz_campo_integracao.COD_CAMPOOBG                         
			where matriz_campo_integracao.COD_EMPRESA=" . $dadosLogin['idcliente'] . "
			and matriz_campo_integracao.TIP_CAMPOOBG IN('OBG','OPC') GROUP BY KEY_CAMPOOBG;
		 ";

    $CAMPOQUERY = mysqli_query($connAdm->connAdm(), $CAMPOSSQL);
    while ($CAMPOROW = mysqli_fetch_assoc($CAMPOQUERY)) {

      if (array_search('', array_column($arraybusca, $CAMPOROW['KEY_CAMPOOBG'])) !== false) {
        $selecionado .= 'yes,';
      } else {
        $selecionado .= "no,";
      }
    }
    $camposvazios[] = explode(',',  substr($selecionado, 0, -1));

    if (recursive_array_search('yes', $camposvazios) !== false) {
    } else {

      //VERIFICAR SE  O CLIENTE ACEITOU OS TERMOS
      $SQLTERMOSS = "SELECT COD_CLIENTE from clientes WHERE
													 LOG_TERMO='S' and    						
													 COD_CLIENTE='" . $arraybusca[0][COD_CLIENTE] . "' and
													 COD_EMPRESA='" . $dadosLogin['idcliente'] . "'";

      $RWTERMOSS = mysqli_query($connUser->connUser(), $SQLTERMOSS);
      if ($temtermo = mysqli_num_rows($RWTERMOSS) > 0) {
        $dadosOK = "UPDATE clientes SET LOG_ATIVCAD='S', LOG_CADOK='S' WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and COD_CLIENTE='" . $arraybusca[0][COD_CLIENTE] . "';";
        $rwdadosOK = mysqli_query($connUser->connUser(), $dadosOK);
        $upcredito = "UPDATE  creditosdebitos SET cod_statuscred=1
																WHERE 
																	cod_statuscred=7 AND 
																	cod_cliente='" . $arraybusca[0][COD_CLIENTE] . "' AND 
																	cod_empresa='" . $dadosLogin['idcliente'] . "'";
        mysqli_query($connUser->connUser(), $upcredito);
      }
    }
  }
  //fnmemoriafinal($connUser->connUser(),$cod_men);

  $return = array('BuscaConsumidorResponse' => fnreturn($arrayconsulta));


  array_to_xml($return, $xml_user_info);
  Grava_log_msgxml($connUser->connUser(), 'msg_busca', $cod_log, 'OK', addslashes($xml_user_info->asXML()));
  return $return;
  ob_end_flush();
}
