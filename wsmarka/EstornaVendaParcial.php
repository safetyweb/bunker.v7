<?php


$server->register(
  'EstornaVendaParcial',
  array(
    'fase' => 'xsd:string',
    'estorno' => 'tns:acao_estorno',
    'dadosLogin' => 'tns:LoginInfo'
  ),  //parameters
  array('EstornaVendaParcialResponse' => 'tns:acao'),  //output
  $ns,                     // namespace
  "$ns/EstornaVendaParcial",                 // soapaction
  'document',                         // style
  'literal',                          // use
  'EstornaVendaParcial'             // documentation
);


function EstornaVendaParcial($fase, $estorno, $dadosLogin)
{
  include_once '../_system/Class_conn.php';
  include_once 'func/function.php';
  ob_start();
  $cartao = fnlimpaCPF($estorno['cartao']);

  /*  $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);*/

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

  //compara os id_cliente com o cod_empresa
  /*  $dec=$row['NUM_DECIMAIS'];
       if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}*/

  //verifica se a loja foi delabilitada
  $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
  $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
  if ($lojars['LOG_ESTATUS'] != 'S') {
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' Loja desabilidata', $row['LOG_WS']);
    return  array('EstornaVendaParcialResponse' => array(
      'msgerro' => 'LOJA DESABILITADA',
      'coderro' => '80'
    ));
    exit();
  }
  //conn user
  $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);

  //nova regra de casas decimais 
  $CONFIGUNI = "SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=" . $dadosLogin['idcliente'] . " AND 
														  COD_UNIVENDA=" . $dadosLogin['idloja'] . " AND LOG_STATUS='S'";
  $RSCONFIGUNI = mysqli_query($connUser->connUser(), $CONFIGUNI);
  if (!$RSCONFIGUNI) {
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'erro na pre-venda dados unidade', $row['LOG_WS']);
  } else {
    if ($RCCONFIGUNI = mysqli_num_rows($RSCONFIGUNI) > 0) {
      //aqui pega da unidade
      $RWCONFIGUNI = mysqli_fetch_assoc($RSCONFIGUNI);
      $dec = $RWCONFIGUNI['NUM_DECIMAIS'];
      if ($RWCONFIGUNI['TIP_RETORNO'] == 2) {
        $decimal = '2';
      } else {
        $casasDec = '0';
      }

      $LOG_CADVENDEDOR = $RWCONFIGUNI['LOG_CADVENDEDOR'];
    } else {
      //aqui pega da controle de licença
      $dec = $row['NUM_DECIMAIS'];
      if ($row['TIP_RETORNO'] == 2) {
        $decimal = '2';
      } else {
        $casasDec = '0';
      }
      $LOG_CADVENDEDOR = $row['LOG_CADVENDEDOR'];
    }
  }





  if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
    //  $cod_men= fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'EstornaVendaParcial',$dadosLogin['idcliente']);

    //grava og
    $xmlteste = addslashes(file_get_contents("php://input"));
    $saida = preg_replace('/\s+/', ' ', $xmlteste);
    /*$inserarray='INSERT INTO origemestornavenda (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
            ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
             "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$estorno['id_vendapdv'].'","'.$estorno['cartao'].'","'.$saida.'","'.$arralogin.'")';
 mysqli_query($connUser->connUser(),$inserarray);*/
    ///=========================        
    $arrylog = array(
      'cod_usuario' => $row['COD_USUARIO'],
      'login' => $dadosLogin['login'],
      'cod_empresa' => $row['COD_EMPRESA'],
      'idloja' => $dadosLogin['idloja'],
      'idmaquina' => $dadosLogin['idmaquina'],
      'cpf' => '0',
      'xml' => $saida,
      'tables' => 'origemestornavenda',
      'conn' => $connUser->connUser(),
      'pdv' => $id_vendapdv
    );

    $cod_log = fngravalogxml($arrylog);
    $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><EstornaVendaParcialResponse></EstornaVendaParcialResponse>");


    //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
    if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'EstornaVendaParcial', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
      return  array('EstornaVendaParcialResponse' => array(
        'msgerro' => 'Id_cliente não confere com o cadastro!',
        'coderro' => '4'
      ));
      exit();
    }
    //VERIFICA SE A EMPRESA FOI DESABILITADA
    if ($row['LOG_ATIVO'] == 'N') {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'EstornaVendaParcial', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
      return  array('EstornaVendaParcialResponse' => array(
        'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
        'coderro' => '38'
      ));
      exit();
    }
    //VERIFICA SE O USUARIO FOI DESABILITADA
    if ($row['LOG_ESTATUS'] == 'N') {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
      return  array('EstornaVendaParcialResponse' => array(
        'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
        'coderro' => '44'
      ));
      exit();
    }
    //////////////////////=================================================================================================================

  } else {
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'EstornaVendaParcial', 'Usuario ou senha Inválido!', $row['LOG_WS']);
    return  array('EstornaVendaParcialResponse' => array(
      'msgerro' => 'Usuario ou senha Inválido!',
      'coderro' => '5'
    ));
    exit();
  }

  //loop de excluir venda
  if (count($estorno['itens']['vendaitem']['quantidade']) == 1) {

    $cad_venda = "CALL SP_EXCLUI_ITEM_WS('" . $row['COD_EMPRESA'] . "',
                                             '" . $estorno['id_vendapdv'] . "',
                                             '" . $estorno['itens']['vendaitem']['id_item'] . "',  
                                              '" . $estorno['itens']['vendaitem']['codigoproduto'] . "',    
                                             '" . fnFormatvalor($estorno['itens']['vendaitem']['quantidade'], $dec) . "', 
                                             '" . $row['COD_USUARIO'] . "',
                                             '" . $dadosLogin['idloja'] . "',    
                                             'EXC'     
                                           );";
    //verificar se o diretorio existe
    $cad_venda_sem_quebras = str_replace(array("\r", "\n"), '', $cad_venda);
    $cad_venda_sem_espacos_extras = preg_replace('/\s+/', ' ', $cad_venda_sem_quebras);
    $arquivo = './rot_sql/' . $row['COD_EMPRESA'] . '__' . $estorno['id_vendapdv'] . '.sql';
    $myfile = fopen($arquivo, "a+");
    fwrite($myfile, $cad_venda_sem_espacos_extras);
    // Trunca o arquivo para remover a última linha em branco, se existir
    ftruncate($myfile, ftell($myfile));
    fclose($myfile);
    //  $dadoscliarr=mysqli_query($connUser->connUser(),$cad_venda); 
    //  $dadoscli=mysqli_fetch_assoc($dadoscliarr);

  } else {
    for ($i = 0; $i < count($estorno['itens']['vendaitem']); $i++) {

      $cad_venda .= "CALL SP_EXCLUI_ITEM_WS('" . $row['COD_EMPRESA'] . "',
                                             '" . $estorno['id_vendapdv'] . "',
                                             '" . $estorno['itens']['vendaitem'][$i]['id_item'] . "',
                                             '" . $estorno['itens']['vendaitem'][$i]['codigoproduto'] . "',      
                                             '" . fnFormatvalor($estorno['itens']['vendaitem'][$i]['quantidade'], $dec) . "', 
                                             '" . $row['COD_USUARIO'] . "',
                                             '" . $dadosLogin['idloja'] . "',
                                             'EXC'     
                                           );";
      //COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,MENSSAGEM
    }
    $cad_venda_sem_quebras = str_replace(array("\r", "\n"), '', $cad_venda);
    $cad_venda_sem_espacos_extras = preg_replace('/\s+/', ' ', $cad_venda_sem_quebras);
    $arquivo = './rot_sql/' . $row['COD_EMPRESA'] . '__' . $estorno['id_vendapdv'] . '.sql';
    $myfile = fopen($arquivo, "a+");
    fwrite($myfile, $cad_venda_sem_espacos_extras);
    // Trunca o arquivo para remover a última linha em branco, se existir
    ftruncate($myfile, ftell($myfile));
    fclose($myfile);

    //  $dadoscliarr= mysqli_multi_query($connUser->connUser(),$cad_venda); 
    // $dadoscli=mysqli_fetch_assoc($dadoscliarr);
    $contmp = $connUser->connUser();
    // Execute as chamadas de procedimento armazenado

    /*    if (mysqli_multi_query($contmp,$cad_venda)) {
            do{
                if ($dadoscli = mysqli_store_result($contmp)) {
                    while ($dadoscli=mysqli_fetch_assoc($dadoscliarr))
                    {
                        
                        $COD_CLIENTE= $dadoscli['COD_CLIENTE'];     
                    }
                    mysqli_free_result($contmp);
                    
                }
            } while (mysqli_next_result($contmp));
        }*/
  }
  //Executa query


  try {


    $menssagem = "Estorno Item OK!";
    $msg = 'Estorno Item OK!';
    $coderro = '39';
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $COD_CLIENTE, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Estorna Item Venda', $menssagem, $row['LOG_WS']);
  } catch (mysqli_sql_exception $e) {
    $xamls = addslashes($e);
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $COD_CLIENTE, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Estorna Item Venda', $xamls, $row['LOG_WS']);
    $menssagem = "Falha no Estorno!";
    $msg = 'Falha no Estorno!';
    $coderro = '500';
  }

  if ($dadosLogin['idloja'] == '97397') {
    $desconto_venda = "UPDATE desconto_venda SET LOG_VENDA='E'  WHERE COD_VENDAPDV='" . $estorno['id_vendapdv'] . "' and COD_EMPRESA=" . $row['COD_EMPRESA'] . ";";
    $rsdesconto = mysqli_query($connUser->connUser(), $desconto_venda);
  }

  $arrayconsulta = array(
    'conn' => $connAdm->connAdm(),
    'ConnB' => $connUser->connUser(),
    'database' => $row['NOM_DATABASE'],
    'cod_cliente' => $COD_CLIENTE,
    'empresa' => $row['COD_EMPRESA'],
    'fase' => $fase,
    'cpf' => $cartao,
    'cnpj' => $cliente['cnpj'],
    'cartao' =>  $cartao,
    'email' =>  $cliente['email'],
    'telefone' =>  $cliente['telefone'],
    'consultaativa' => $row['LOG_CONSEXT'],
    'login' => $dadosLogin['login'],
    'senha' => $dadosLogin['senha'],
    'idloja' => $dadosLogin['idloja'],
    'idmaquina' => $dadosLogin['idmaquina'],
    'codvendedor' => $dadosLogin['codvendedor'],
    'nomevendedor' => $dadosLogin['nomevendedor'],
    'COD_USUARIO' => $row['COD_USUARIO'],
    'pagina' => 'EstornaItemvenda',
    'venda' => 'nao',
    'menssagem' => $msg,
    'LOG_WS' => $row['LOG_WS'],
    'dec' => $dec,
    'decimal' => $decimal,
    'coderro' => $coderro
  );

  ob_end_flush();
  ob_flush();
  //fnmemoriafinal($connUser->connUser(),$cod_men);
  $return =  array('EstornaVendaParcialResponse' => fnreturn($arrayconsulta));
  array_to_xml($return, $xml_user_info);
  Grava_log_msgxml($connUser->connUser(), 'msg_estornavenda', $cod_log, 'Estorno de VendaParcial Concluido!', addslashes($xml_user_info->asXML()));
  return $return;
}
