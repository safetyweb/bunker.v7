<?php


$server->register(
  'InserePreVenda',
  array(
    'fase' => 'xsd:string',
    'prevenda' => 'tns:venda',
    'dadosLogin' => 'tns:LoginInfo'
  ),  //parameters
  array('InserePreVendaResponse' => 'tns:acao'),  //output
  $ns,                     // namespace
  "$ns/InserePreVenda",                 // soapaction
  'document',                         // style
  'literal',                          // use
  'InserePreVenda'             // documentation
);


function InserePreVenda($fase, $prevenda, $dadosLogin)
{
  include_once '../_system/Class_conn.php';
  include_once 'func/function.php';
  $cartao = fnlimpaCPF($prevenda['cartao']);

  /* $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
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



  //conn user
  $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);
  //mysqli_options($connUser->connUser(), MYSQLI_OPT_CONNECT_TIMEOUT, 5);

  $CONFIGUNI = "SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=" . $dadosLogin['idcliente'] . " AND 
														  COD_UNIVENDA=" . $dadosLogin['idloja'] . " AND LOG_STATUS='S'";
  $RSCONFIGUNI = mysqli_query($connUser->connUser(), $CONFIGUNI);
  if (!$RSCONFIGUNI) {
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InserePreVenda', 'erro na pre-venda dados unidade', $row['LOG_WS']);
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
  /*$dec=$row['NUM_DECIMAIS']; 
    if ($row['TIP_RETORNO']== 2){$decimal = '2';}else {$casasDec = '0';}*/

  $valortotalbruto = fnFormatvalor($prevenda['valortotalbruto'], $dec);
  $DescontoTotalvalor = fnFormatvalor($prevenda['descontototalbruto'], $dec);
  $ValorTotalLiquido = fnFormatvalor($prevenda['valortotalLiquido'], $dec);
  $valor_resgate = fnFormatvalor($prevenda['valor_resgate'], $dec);


  //file_get_contents("php://input")  
  $cod_log = fnGravaArrayvenda($connUser->connUser(), $connAdm->connAdm(), $prevenda, $dadosLogin, $row['COD_USUARIO'], file_get_contents("php://input"), $row['LOG_WS']);

  if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
    // $cod_men= fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'InserePreVenda',$dadosLogin['idcliente']);

    //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
    if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InserePreVenda', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
      return  array('InserePreVendaResponse' => array(
        'msgerro' => 'Id_cliente não confere com o cadastro!',
        'coderro' => '4'
      ));
      exit();
    }
    //VERIFICA SE A EMPRESA FOI DESABILITADA
    if ($row['LOG_ATIVO'] == 'N') {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InserePreVenda', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
      return  array('InserePreVendaResponse' => array(
        'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
        'coderro' => '6'
      ));
      exit();
    }
    //VERIFICA SE O USUARIO FOI DESABILITADA
    if ($row['LOG_ESTATUS'] == 'N') {
      fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
      return  array('InserePreVendaResponse' => array(
        'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
        'coderro' => '44'
      ));
      exit();
    }
    //////////////////////=================================================================================================================


  } else {
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InserePreVenda', 'Usuario ou senha Inválido!', $row['LOG_WS']);
    return  array('InserePreVendaResponse' => array(
      'msgerro' => 'Usuario ou senha Inválido!',
      'coderro' => '5'
    ));
    exit();
  }
  if (
    $prevenda['itens']['vendaitem']['estoque'] == '' ||
    $prevenda['itens']['vendaitem']['estoque'] == '?'
  ) {
    $estoque = 0;
  } else {
    $estoque = $prevenda['itens']['vendaitem']['estoque'];
  }
  $arraydadosbusca = array(
    'empresa' => $dadosLogin['idcliente'],
    'cartao' => $cartao,
    'cpf' => $cartao,
    'venda' => 'venda',
    'ConnB' => $connUser->connUser()
  );
  $cliente_cod = fn_consultaBase($arraydadosbusca);
  $cod_atendente = fnatendente($connAdm->connAdm(), $prevenda['codatendente'], $dadosLogin['idcliente'], $dadosLogin['idloja'], $prevenda['codatendente']);
  $NOM_USUARIO = utf8_encode(fnAcentos(rtrim(trim($nomevendedor))));
  $NOM_USUARIO = str_replace("'", "", $NOM_USUARIO);
  $cod_vendedor = fnVendedor(
    $connAdm->connAdm(),
    $NOM_USUARIO,
    $row['COD_EMPRESA'],
    $dadosLogin['idloja'],
    $codvendedor_externo
  );


  $lojas = fnconsultaLoja($connAdm->connAdm(), $connUser->connUser(), $dadosLogin['idloja'], $dadosLogin['idmaquina'], $row['COD_EMPRESA']);
  if ($lojas['msg'] != 1) {

    $msg = 'loja nao cadastrada';
    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
    $xamls = addslashes($msg);
    Grava_log($connUser->connUser(), $cod_log, $xamls);
    return array('InserePreVendaResponse' => array(
      'msgerro' => 'Loja não cadastrada!',
      'coderro' => '11'
    ));
    exit();
  }
  ////////////forma de pagamento
  $formapag = fnFormaPAG($connUser->connUser(), fnAcentos($prevenda['formapagamento']), $row['COD_EMPRESA']);
  //inserir cupom 

  $cadvendacupom = "CALL SP_INSERE_VENDA_DESC( 0,
                                        '" . $row['COD_EMPRESA'] . "', 
                                        '" . $cliente_cod['COD_CLIENTE'] . "',
                                        '1',
                                        '3',
                                        '" . $lojas['COD_UNIVEND'] . "',
                                        '" . $formapag . "',
                                        '" . $valortotalbruto . "',
                                        0,
                                        '" . $valor_resgate . "',
                                        '" . $DescontoTotalvalor . "',
                                        '" . $prevenda['id_vendapdv'] . "',
                                        '" . $row['COD_USUARIO'] . "',
                                        '" . $row['TIP_CONTABIL'] . "',
                                        " . $lojas['COD_MAQUINA'] . ",
                                        '" . $prevenda['cupomfiscal'] . "',
                                        '" . $cod_vendedor . "',
                                        '" . $prevenda['datahora'] . "',
                                         '" . $cod_atendente . "'    
                                        );";

  $rewsinsert = mysqli_query($connUser->connUser(), $cadvendacupom);
  if (!$rewsinsert) {
    Grava_log($connUser->connUser(), $cod_log, addslashes('Erro na prevenda!'));
    return array('InserePreVendaResponse' => array(
      'msgerro' => 'Erro',
      'coderro' => '0'
    ));
    exit();
  } else {
    $row_venda = mysqli_fetch_assoc($rewsinsert);
  }


  foreach ($prevenda['itens'] as $dadositemP) {

    if (count($dadositemP['id_item']) >= '1') {
      /*  if($cliente_cod['COD_CLIENTE']=='421372')
         {
                 $VAL_TOTITEM=fnFormatvalor($dadositemP['quantidade'],$dec)* fnFormatvalor($dadositemP['valorliquido'],$dec);
                 return  array('InserePreVendaResponse'=>array('msgerro'=>fnValorSQL($dadositemP['quantidade'],$dec).' pro'.fnValorSQL($dadositemP['valorliquido'],$dec),
                                                     'coderro'=>'5'));
                exit();
              
         }*/
      $VAL_TOTITEM = fnFormatvalor($dadositemP['quantidade'], $dec) * fnFormatvalor($dadositemP['valorliquido'], $dec);
      $NOM_PROD = addslashes($dadositemP['produto']);
      $NOM_PROD = limitarTexto($NOM_PROD, 150);
      $itemvendainsert = "call SP_INSERE_ITENS_DESC('" . $cliente_cod['COD_CLIENTE'] . "',
                                                        '" . $row['COD_EMPRESA'] . "',
                                                         '" . $dadositemP['id_item'] . "',
                                                         '" . $row_venda['COD_VENDA'] . "',
                                                         '" . $dadositemP['codigoproduto'] . "',
                                                         '" . fnAcentos($NOM_PROD) . "',     
                                                          0,
                                                         '" . fnFormatvalor($dadositemP['quantidade'], $dec) . "',
                                                         '" . fnFormatvalor($dadositemP['valorbruto'], $dec) . "',
                                                         '" . fnFormatvalor($VAL_TOTITEM, $dec) . "',
                                                         '" . fnFormatvalor($dadositemP['descontovalor'], $dec) . "',
                                                         '" . fnFormatvalor($dadositemP['valorliquido'], $dec) . "',  
                                                         '" . $dadositemP['atributo1'] . "',
                                                         '" . $dadositemP['atributo2'] . "', 
                                                         '" . $dadositemP['atributo3'] . "', 
                                                         '" . $dadositemP['atributo4'] . "', 
                                                         '" . $dadositemP['atributo5'] . "', 
                                                         '" . $dadositemP['atributo6'] . "', 
                                                         '" . $dadositemP['atributo7'] . "', 
                                                         '" . $dadositemP['atributo8'] . "', 
                                                         '" . $dadositemP['atributo9'] . "', 
                                                         '" . $dadositemP['atributo10'] . "', 
                                                         '" . $dadositemP['atributo11'] . "', 
                                                         '" . $dadositemP['atributo12'] . "',
                                                         '" . $dadositemP['atributo13'] . "',
                                                         '" . $dadosLogin['idloja'] . "',
                                                          '" . $estoque . "'     
                                                          )";
      $itens = mysqli_query($connUser->connUser(), $itemvendainsert);
    } else {
      foreach ($dadositemP as $dadosSecundarios => $dados) {
        $VAL_TOTITEM = fnFormatvalor($dados['quantidade'], $dec) * fnFormatvalor($dados['valorliquido'], $dec);
        $NOM_PROD = addslashes($dados['produto']);
        $NOM_PROD = limitarTexto($NOM_PROD, 150);
        $itemvendainsert = "call SP_INSERE_ITENS_DESC('" . $cliente_cod['COD_CLIENTE'] . "',
                                                        '" . $row['COD_EMPRESA'] . "',
                                                         '" . $dados['id_item'] . "',
                                                         '" . $row_venda['COD_VENDA'] . "',
                                                         '" . $dados['codigoproduto'] . "',
                                                         '" . fnAcentos($NOM_PROD) . "',     
                                                          0,
                                                         '" . fnFormatvalor($dados['quantidade'], $dec) . "',
                                                         '" . fnFormatvalor($dados['valorbruto'], $dec) . "',
                                                         '" . fnFormatvalor($VAL_TOTITEM, $dec) . "',
                                                         '" . fnFormatvalor($dados['descontovalor'], $dec) . "',
                                                         '" . fnFormatvalor($dados['valorliquido'], $dec) . "',  
                                                         '" . $dados['atributo1'] . "',
                                                         '" . $dados['atributo2'] . "', 
                                                         '" . $dados['atributo3'] . "', 
                                                         '" . $dados['atributo4'] . "', 
                                                         '" . $dados['atributo5'] . "', 
                                                         '" . $dados['atributo6'] . "', 
                                                         '" . $dados['atributo7'] . "', 
                                                         '" . $dados['atributo8'] . "', 
                                                         '" . $dados['atributo9'] . "', 
                                                         '" . $dados['atributo10'] . "', 
                                                         '" . $dados['atributo11'] . "', 
                                                         '" . $dados['atributo12'] . "',
                                                         '" . $dados['atributo13'] . "',
                                                         '" . $dadosLogin['idloja'] . "',
                                                          '" . $estoque . "'     
                                                          )";
        $itens = mysqli_query($connUser->connUser(), $itemvendainsert);
      }
    }
  }

  //============================    
  //insert pre venda
  //valida compo
  /* $msg=validaCampo($dadosLogin['idloja'],'idloja','numeric');
   if(!empty($msg)){
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg);
       return array('AtualizaCadastroResponse'=>array('msgerro' => $msg,
                                                     'coderro'=>'20')); exit();}
   */




  $arrayconsulta = array(
    'conn' => $connAdm->connAdm(),
    'ConnB' => $connUser->connUser(),
    'cod_cliente' => $cliente_cod['COD_CLIENTE'],
    'empresa' => $row['COD_EMPRESA'],
    'fase' => $fase,
    'cpf' => $prevenda['cartao'],
    'cnpj' => $prevenda['cartao'],
    'cartao' =>  $prevenda['cartao'],
    'email' =>  '',
    'telefone' => '',
    'consultaativa' => $row['LOG_CONSEXT'],
    'login' => $dadosLogin['login'],
    'senha' => $dadosLogin['senha'],
    'idloja' => $dadosLogin['idloja'],
    'idmaquina' => $dadosLogin['idmaquina'],
    'codvendedor' => $dadosLogin['codvendedor'],
    'nomevendedor' => $dadosLogin['nomevendedor'],
    'COD_USUARIO' => $row['COD_USUARIO'],
    'pagina' => 'InserirPrevenda',
    'venda' => 'venda',
    'dec' => $dec,
    'decimal' => $decimal,
    'LOG_WS' => $row['LOG_WS'],
    'cupomdesconto' => $prevenda['cupomdesconto'],
    'CODIGOVENDA' => $row_venda['COD_VENDA'],
    'LOG_CADTOKEN' => $row['LOG_CADTOKEN']
  );
  //fnmemoriafinal($connUser->connUser(),$cod_men); 
  return  array('InserePreVendaResponse' => fnreturn($arrayconsulta));
}
