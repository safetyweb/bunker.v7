<?php
//inserir venda
//=================================================================== InserirVenda ==================================================================================
//retorno dados venda
$server->wsdl->addComplexType(
  'dadosdavenda',
  'complexType',
  'struct',
  'all',
  '',
  array(
    'nome' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'nome', 'type' => 'xsd:string'),
    'cartao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartao', 'type' => 'xsd:int'),
    'saldo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'saldo', 'type' => 'xsd:string'),
    'saldoresgate' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'saldoresgate', 'type' => 'xsd:string'),
    'comprovante' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'comprovante', 'type' => 'xsd:string'),
    'comprovante_resgate' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'comprovante_resgate', 'type' => 'xsd:string'),
    'url' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'url', 'type' => 'xsd:string'),
    'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
  )
);

// venda
$server->wsdl->addComplexType(
  'venda',
  'complexType',
  'struct',
  'sequence',
  '',
  array(
    'id_vendapdv' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'id_vendapdv', 'type' => 'xsd:string'),
    'datahora' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'datahora', 'type' => 'xsd:string'),
    'cartao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartao', 'type' => 'xsd:string'),
    'valortotal' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valortotal', 'type' => 'xsd:string'),
    'valor_resgate' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valor_resgate', 'type' => 'xsd:string'),
    'cupom' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cupom', 'type' => 'xsd:string'),
    'formapagamento' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'formapagamento', 'type' => 'xsd:string'),
    'cartaoamigo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartaoamigo', 'type' => 'xsd:string'),
    'pontosextras' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'pontosextras', 'type' => 'xsd:string'),
    'naopontuar' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'naopontuar', 'type' => 'xsd:string'),
    'codatendente' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codatendente', 'type' => 'xsd:string'),
    'codvendedor' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codvendedor', 'type' => 'xsd:string'),
    'pontostotal' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'pontostotal', 'type' => 'xsd:string'),
    'items' => array('minOccurs' => '0', 'maxOccurs' => '20', 'name' => 'items', 'type' => 'tns:items')
  )
);
//array de itens
$server->wsdl->addComplexType(
  'items',
  'complexType',
  'struct',
  'sequence',
  '',
  array('vendaitem' => array('minOccurs' => '0', 'maxOccurs' => '20', 'name' => 'vendaitem', 'type' => 'tns:vendaitem'))

);




// itens da venda array ^

$server->wsdl->addComplexType(
  'vendaitem',
  'complexType',
  'struct',
  'sequence',
  '',
  array(
    'id_item' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'id_item', 'type' => 'xsd:string'),
    'produto' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'produto', 'type' => 'xsd:string'),
    'codigoproduto' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codigoproduto', 'type' => 'xsd:string'),
    'quantidade' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'quantidade', 'type' => 'xsd:string'),
    'valor' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valor', 'type' => 'xsd:string'),
    'naopontuar' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'naopontuar', 'type' => 'xsd:string')

  )
);



//Registro para parassar os dados pra a função inserir venda
$server->register(
  'InserirVenda',
  array(
    'venda' => 'tns:venda',
    'dadosLogin' => 'tns:LoginInfo'
  ),  //parameters
  array('return' => 'tns:dadosdavenda'),  //output
  'urn:fidelidade',   //namespace
  'urn:fidelidade#InserirVenda',  //soapaction
  'rpc', //document
  'literal', // literal
  'InserirVenda'
);  //description


function InserirVenda($InserirVenda, $dadoslogin)
{

  include '../_system/Class_conn.php';
  include './func/function.php';

  //valida campos
  $msg = valida_campo_vazio($InserirVenda['formapagamento'], 'formapagamento', 'string');
  if (!empty($msg) || !empty($msg1)) {
    return array('msgerro' => $msg);
  }
  $msg = valida_campo_vazio($dadoslogin['login'], 'login', 'string');
  if (!empty($msg)) {
    return array('msgerro' => $msg);
  }
  $msg = valida_campo_vazio($dadoslogin['senha'], 'senha', 'string');
  if (!empty($msg)) {
    return array('msgerro' => $msg);
  }
  $msg = valida_campo_vazio($InserirVenda['id_vendapdv'], 'id_vendapdv', 'string');
  if (!empty($msg)) {
    return array('msgerro' => $msg);
  }
  $msg = valida_campo_vazio($dadoslogin['idloja'], 'idloja', 'numeric');
  if (!empty($msg)) {
    return array('msgerro' => $msg);
  }
  $msg = valida_campo_vazio($dadoslogin['idmaquina'], 'idmaquina', 'string');
  if (!empty($msg)) {
    return array('msgerro' => $msg);
  }
  $Cartaows = $InserirVenda['cartao'];


  // echo count($InserirVenda['items']['vendaitem']);

  $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadoslogin['login'] . "', '" . fnEncode($dadoslogin['senha']) . "','','','','','')";
  $buscauser = mysqli_query($connAdm->connAdm(), $sql);
  $row = mysqli_fetch_assoc($buscauser);
  //compara os id_cliente com o cod_empresa

  if ($row['COD_EMPRESA'] != $dadoslogin['idcliente']) {
    $passou = 1;
  } else {
  }
  //conn user
  $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);

  //memoria log
  //verifica lojas e maquinas 
  $lojas = fnconsultaLoja($connAdm->connAdm(), $connUser->connUser(), $dadoslogin['idloja'], $dadoslogin['idmaquina'], $row['COD_EMPRESA']);
  // print_r($lojas);


  // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,

  if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
    if ($row['LOG_ATIVO'] == 'S') {
      if ($passou != 1) {


        fnmemoria($connUser->connUser(), 'true', $dadoslogin['login'], 'Venda', $row['COD_EMPRESA']);

        //verifica se o profissão existe
        $formaPag = 'select *,count(COD_FORMAPA) as existe from formapagamento where DES_FORMAPA="' . $InserirVenda['formapagamento'] . '" and COD_EMPRESA ="' . $row['COD_EMPRESA'] . '"';
        $formaPagR = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $formaPag));
        if ($formaPagR['existe'] != 0) {
          $formaPagN = $formaPagR['COD_FORMAPA'];
        } else {

          $inserformpa = 'INSERT INTO formapagamento (COD_EXTERNO,DES_FORMAPA,COD_EMPRESA)
                                                                    values
                                                                  (0,"' . $InserirVenda['formapagamento'] . '","' . $row['COD_EMPRESA'] . '")';
          mysqli_query($connUser->connUser(), $inserformpa);

          $ID_FORMPA = "SELECT last_insert_id(COD_FORMAPA) as COD_FORMAPA from formapagamento ORDER by COD_FORMAPA DESC limit 1;";
          $COD_FORMAPA = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $ID_FORMPA));
          $formaPagN = $COD_FORMAPA['COD_FORMAPA'];
        }

        //inserir venda inteira na base de dados 
        $dados_login = addslashes(str_replace(array("\n", ""), array("", " "), var_export($dadoslogin, true)));
        $xamls = addslashes(str_replace(array("\n", ""), array("", " "), var_export($InserirVenda, true)));
        $inserarray = 'INSERT INTO ORIGEMVENDA (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                 ("' . date("Y-m-d H:i:s") . '","' . $_SERVER['REMOTE_ADDR'] . '","' . $_SERVER['REMOTE_PORT'] . '",
                                  "' . $row['COD_USUARIO'] . '","' . $dadoslogin['login'] . '","' . $row['COD_EMPRESA'] . '","' . $dadoslogin['idloja'] . '","' . $dadoslogin['idmaquina'] . '","' . $InserirVenda['id_vendapdv'] . '","' . $InserirVenda['cartao'] . '","' . $xamls . '","' . $dados_login . '")';

        mysqli_query($connUser->connUser(), $inserarray);
        //Pegar o id da venda para inserir as messagens no log
        $ID_LOG = "SELECT last_insert_id(COD_ORIGEM) as ID_LOG from ORIGEMVENDA ORDER by COD_ORIGEM DESC limit 1;";
        $LOG = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $ID_LOG));
        //se o cliente nao existir não gera a venda
        $dadosbase = fn_consultaBase($connUser->connUser(), $Cartaows, '', $Cartaows, '', '', $row['COD_EMPRESA']);
        if ($dadosbase[0]['contador'] == 0) {
          $msg = 'Cliente não cadastrado';
          $xamls = addslashes($msg);
          Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          return array('msgerro' => 'Cliente não cadastrado');
          exit();
        }
        ////Loja não cadastrada 
        if ($lojas[0]['msg'] != 1) {

          $msg = 'loja nao cadastrada';
          $xamls = addslashes($msg);
          Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          return array('msgerro' => 'Loja não cadastrada!');
          exit();
        }


        //calcula valor do itens + quantida e verifica se o valor total dos itens e igual  
        $retorno = fn_calValor($InserirVenda);

        //Menssagem de erro do sistema criticas de campos
        if ($retorno != 1) {
          //$retorno = 1 Valor da soma dos itens igual ao total
          $msg = ';o A soma dos itens não correspode ao valor total!';
          $xamls = addslashes($msg);
          Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          if (!empty($msg)) {
            return array('msgerro' => $msg);
          }
          exit();
        }
        //CODIGO PDV igual não passa
        $CODPDV = "SELECT COUNT(*) as venda FROM VENDAS WHERE COD_EMPRESA='" . $dadoslogin['idcliente'] . "' and COD_VENDAPDV='" . $InserirVenda['id_vendapdv'] . "'";
        $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $CODPDV));

        if ($row_CODPDV['venda'] != 0) {
          $msg = 'Oh não! Seu codigo PDV ja existe, tente com outro codigo por favor! :(  ';
          $xamls = addslashes($msg);
          Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          return array('msgerro' => $msg);
          exit();
        }

        $dadosbase = fn_consultaBase($connUser->connUser(), $Cartaows, '', $Cartaows, '', '', $row['COD_EMPRESA']);
        //verifica se a data/hora ja foi cadastrada
        $dataH = 'SELECT count(*) as DAT_HORA from vendas where  COD_EMPRESA="' . $dadoslogin['idcliente'] . '" and
                                   COD_CLIENTE=' . $dadosbase[0]['COD_CLIENTE'] . ' and 
                                   cast(DAT_CADASTR as datetime)="' . $InserirVenda['datahora'] . '"';

        $row_DATAH = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $dataH));


        if ($row_DATAH['DAT_HORA'] != 0) {
          $msg = 'Oh não! Ja existe um cadastro nesse mesmo periodo, tente outro periodo por favor! :(  ';
          $xamls = addslashes($msg);
          Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          return array('msgerro' => $msg);
          exit();
        }

        $cod_vendedor = fnVendedor($connAdm->connAdm(), $dadoslogin['nomevendedor'], $row['COD_EMPRESA'], $dadoslogin['idloja']);

        //$retorno = 1 Valor da soma dos itens igual ao total
        //$row_CODPDV['venda']== 0 não existe essa venda no banco de dados
        if ($row_CODPDV['venda'] == 0 && $retorno == 1 && $row_DATAH['DAT_HORA'] == 0) {
          //log marcando inicio da venda
          $msg = "Inicio do processo de venda.";
          $xamls = addslashes($msg);
          Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          // verifica se o dados cpf,cartão,emaile celular existe na base de dados
          //  $dadosbase=fn_consultaBase($connUser->connUser(),$Cartaows,'',$Cartaows,'','');
          //Carregar os dados do cliente
          if ($dadosbase[0]['contador'] == 1) {
            $nome = $dadosbase[0]['nome'];
            $COD_CLIENTE = $dadosbase[0]['COD_CLIENTE'];
            $datanascimento = $dadosbase[0]['datanascimento'];
            $sexo = $dadosbase[0]['sexo'];
            $cpf = $dadosbase[0]['cpf'];
            $cartao = $dadosbase[0]['cartao'];
            $telefone = $dadosbase[0]['telcelular'];
          } else {

            //se o cadastro automatico for ativo 
            if ($row['LOG_AUTOCAD'] == 'S') {
              //busca de cpf se tiver auto cad com cpf
              if ($row['LOG_CONSEXT'] == 'S') {
                if (valida_cpf($Cartaows) || $InserirVenda['cartao'] != 0) {
                  //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                  include './func/func_ifaro.php';
                  $resultIfaro = ifaro($Cartaows);
                  $nome = $resultIfaro[0]['nome'][0];
                  $cartao = $resultIfaro[0]['cpf'][0];
                  if ($resultIfaro[0]['sexo'][0] == 'M') {
                    $sexo = 1;
                  } else {
                    $sexo = 2;
                  }
                  $datanascimento = $resultIfaro[0]['datanascimento'][0];
                  $datanascimento = fnDataBR($datanascimento);
                  $sql = "insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA) value
                                                  ('" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $resultIfaro[0]['cpf'][0] . "','" . $resultIfaro[0]['nome'][0] . "','" . $row['COD_EMPRESA'] . "','" . $dadoslogin['login'] . "','" . $dadoslogin['idloja'] . "','" . $dadoslogin['idmaquina'] . "')";
                  mysqli_query($connAdm->connAdm(), $sql);
                } else {
                  $msg = 'CPF digitado e invalido!';
                  $xamls = addslashes($msg);
                  Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
                  exit();
                }
              } else {
                $cartao = $InserirVenda['cartao'];
                $datanascimento = is_Date(date('d/m/Y'));
                $sexo = 0;
                $nome = "cliente " . $cartao;
              }
              //cadastrastro de cliente que nao existe
              $cad_cliente = "CALL SP_ALTERA_CLIENTES_WS('" . $row['COD_EMPRESA'] . "',
                                                                                         '" . $nome . "',
                                                                                         '" . $row['COD_USUARIO'] . "',
                                                                                         '" . $cartao . "',
                                                                                         '" . $datanascimento . "',
                                                                                         '" . $sexo . "',
                                                                                         '" . $cartao . "',
                                                                                         'F',
                                                                                         '" . $cod_vendedor . "'
                                                                                         'CAD'
                                                                                      )";
              $row_cliente = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $cad_cliente));
              $COD_CLIENTE = $row_cliente['COD_CLIENTE'];
              $msg = 'Cliente inserido ';
              $xamls = addslashes($msg);
              Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
              $updatecartao = "update  geracartao set log_usado='S',cod_USUALTE=" . $row['COD_USUARIO'] . " where num_cartao=" . $cartao;
              mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $updatecartao));

              $msg = 'cartao alterado';
              $xamls = addslashes($msg);
              Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
              //se o cadastro automatico for inativo      
            } elseif ($row['LOG_AUTOCAD'] == 'N') {
              $COD_CLIENTE = $dadosbase[0]['COD_CLIENTE'];
            }
          }

          //Fim da carga do cliente
          //inicio do inserir venda
          if ($InserirVenda['cartao'] == 0) {
            ////////////////////////////////////////////////
            $cad_venda = "CALL SP_INSERE_VENDA_WS_AVULSO( 0,
                                                                         0,
                                                                         '" . $row['COD_EMPRESA'] . "', 
                                                                         '" . $row['COD_CLIENTE_AV'] . "',
                                                                         '1',
                                                                         '3',
                                                                         '" . $lojas[0]['COD_UNIVEND'] . "',
                                                                         '" . $formaPagN . "',
                                                                         '" . fnFormatvalor($InserirVenda['valortotal']) . "',
                                                                         0,
                                                                         '" . fnFormatvalor($InserirVenda['valor_resgate']) . "',
                                                                         0,
                                                                         '" . $InserirVenda['id_vendapdv'] . "',
                                                                        '" . $row['COD_USUARIO'] . "',
                                                                        '" . $row['TIP_CONTABIL'] . "',
                                                                        " . $lojas[0]['COD_MAQUINA'] . ",
                                                                        '" . $InserirVenda['cupom'] . "'    
                                                                         );";


            $rewsinsert = mysqli_query($connUser->connUser(), $cad_venda);
            $row_venda = mysqli_fetch_assoc($rewsinsert);
            // echo $cad_venda;
            $msg = "Processo de venda avulso concluido!";
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          } else {
            $cad_venda = "CALL SP_INSERE_VENDA_WS( 0,
                                                                         '" . $row['COD_EMPRESA'] . "', 
                                                                         '" . $COD_CLIENTE . "',
                                                                         '1',
                                                                         '3',
                                                                         '" . $lojas[0]['COD_UNIVEND'] . "',
                                                                         '" . $formaPagN . "',
                                                                         '" . fnFormatvalor($InserirVenda['valortotal']) . "',
                                                                         0,
                                                                         '" . fnFormatvalor($InserirVenda['valor_resgate']) . "',
                                                                         0,
                                                                         '" . $InserirVenda['id_vendapdv'] . "',
                                                                        '" . $row['COD_USUARIO'] . "',
                                                                         '" . $row['TIP_CONTABIL'] . "',
                                                                         " . $lojas[0]['COD_MAQUINA'] . ",
                                                                         '" . $InserirVenda['cupom'] . "',
                                                                         '" . $cod_vendedor . "'    
                                                                          );";

            $rewsinsert = mysqli_query($connUser->connUser(), $cad_venda);
            $row_venda = mysqli_fetch_assoc($rewsinsert);
            // echo $cad_venda;
            $msg = "Processo de venda concluido!";
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
            //
          }
          //fim do inserir venda

          //se item venda for menor que um.      
          if (count($InserirVenda['items']['vendaitem']['codigoproduto']) == 1) {
            //cadastro do nome/codigo produto
            $sqlProd = "select COD_PRODUTO,COD_EXTERNO from produtocliente where COD_EMPRESA= '" . $row['COD_EMPRESA'] . "' and COD_EXTERNO='" . $InserirVenda['items']['vendaitem']['codigoproduto'] . "'";
            $rowprod = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqlProd));

            if (!isset($rowprod['COD_EXTERNO'])) {

              $sqlprodinsert = "insert into produtocliente
                                                                            (COD_EXTERNO,
                                                                             COD_EMPRESA,
                                                                             DES_PRODUTO)
                                                                             values
                                                                             (
                                                                             '" . $InserirVenda['items']['vendaitem']['codigoproduto'] . "',
                                                                             '" . $row['COD_EMPRESA'] . "',
                                                                             '" . $InserirVenda['items']['vendaitem']['produto'] . "'    
                                                                             );";
              mysqli_query($connUser->connUser(), $sqlprodinsert);
              //pegar o max cod_produto
              $maxcodprod = "SELECT last_insert_id(COD_PRODUTO) as COD_PRODUTO from produtocliente ORDER by COD_PRODUTO DESC limit 1;";
              $rowmaxprdu = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $maxcodprod));
              $codprod = $rowmaxprdu['COD_PRODUTO'];
            } else {
              $codprod = $rowprod['COD_PRODUTO'];
            }
            $VAL_TOTITEM = fnFormatvalor($InserirVenda['items']['vendaitem']['quantidade']) * fnFormatvalor($InserirVenda['items']['vendaitem']['valor']);

            $itemvendainsert = "call SP_INSERE_ITENS_WS($COD_CLIENTE,
                                                                                                '" . $row['COD_EMPRESA'] . "',
                                                                                                 " . $InserirVenda['items']['vendaitem']['id_item'] . ",
                                                                                                 " . $row_venda['COD_VENDA'] . ",
                                                                                                   $codprod,
                                                                                                   0,
                                                                                                 " . fnFormatvalor($InserirVenda['items']['vendaitem']['quantidade']) . ",
                                                                                                 " . fnFormatvalor($InserirVenda['items']['vendaitem']['valor']) . ",
                                                                                                 " . fnFormatvalor($VAL_TOTITEM) . "    
                                                                                                  )";



            mysqli_query($connUser->connUser(), $itemvendainsert);
          } else {

            for ($i = 0; $i < count($InserirVenda['items']['vendaitem']); $i++) {

              //cadastro do nome/codigo produto
              $sqlProd = "select COD_PRODUTO,COD_EXTERNO from produtocliente where COD_EXTERNO='" . rtrim(trim($InserirVenda['items']['vendaitem'][$i]['codigoproduto'])) . "';";
              $rowprod = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqlProd));
              if (!isset($rowprod['COD_EXTERNO'])) {
                $NOM_PROD = addslashes($InserirVenda['items']['vendaitem'][$i]['produto']);
                $sqlprodinsert = "insert into produtocliente
                                                                                  (COD_EXTERNO,
                                                                                   COD_EMPRESA,
                                                                                   DES_PRODUTO
                                                                                   )
                                                                                   values
                                                                                   (
                                                                                   '" . $InserirVenda['items']['vendaitem'][$i]['codigoproduto'] . "',
                                                                                   '" . $row['COD_EMPRESA'] . "',
                                                                                   '" . $NOM_PROD . "'    
                                                                                   );";
                mysqli_query($connUser->connUser(), $sqlprodinsert);
                //pegar o max cod_produto
                $maxcodprod = "SELECT last_insert_id(COD_PRODUTO) as COD_PRODUTO from produtocliente ORDER by COD_PRODUTO DESC limit 1;";
                $rowmaxprdu = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $maxcodprod));
                $codprod = $rowmaxprdu['COD_PRODUTO'];
              } else {
                $codprod = $rowprod['COD_PRODUTO'];
              }



              $VAL_TOTITEM = fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['quantidade']) * fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['valor']);

              $itemvendainsert = "CALL SP_INSERE_ITENS_WS($COD_CLIENTE,
                                                                                                        '" . $row['COD_EMPRESA'] . "',
                                                                                                        '" . $InserirVenda['items']['vendaitem'][$i]['id_item'] . "',
                                                                                                        " . $row_venda['COD_VENDA'] . ",
                                                                                                          $codprod,
                                                                                                          0,
                                                                                                        " . fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['quantidade']) . ",
                                                                                                        " . fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['valor']) . ",
                                                                                                        " . $VAL_TOTITEM . "    
                                                                                                         )";
              mysqli_multi_query($connUser->connUser(), $itemvendainsert);
            }
          }
          $msg = 'Processo de itens concluido!';
          $xamls = addslashes($msg);
          Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          if ($InserirVenda['cartao'] == 0) {
            $msg = 'Venda avulso nao gerar credito!';
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
            $msg = 'VENDA AVULSA OK';
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          } else {
            //Calcula creditos e pontos extras
            $sql_credito = "CALL SP_INSERE_CREDITOS_WS('" . $row_venda['COD_VENDA'] . "',
                                                                                   0,      
                                                                                   '" . $row['COD_EMPRESA'] . "',
                                                                                   '" . $COD_CLIENTE . "',    
                                                                                   1,    
                                                                                   1,
                                                                                   '" . $row['COD_UNIVEND'] . "',
                                                                                   '" . $formaPagN . "',
                                                                                   '" . fnFormatvalor($InserirVenda['valortotal']) . "',
                                                                                   '" . fnFormatvalor($InserirVenda['valor_resgate']) . "',
                                                                                   0,
                                                                                   '" . $InserirVenda['id_vendapdv'] . "',
                                                                                   '" . $row['COD_USUARIO'] . "'  
                                                                                   )";
            //exibir saldo cliente
            $SALDO_CLIENTE = mysqli_query($connUser->connUser(), $sql_credito);

            //$procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$COD_CLIENTE.')';
            //$SALDO_CLIENTE=mysqli_query($connUser->connUser(),$procsaldo);
            $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);


            $msg = 'Processo de credito concluido!';
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
            $msg = "CREDITO OK!";
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
            $msg = "OK";
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG['ID_LOG'], $xamls);
          }
        } else {
        }

        //memoria log
        fnmemoria($connUser->connUser(), 'false', $dadoslogin['login']);

        //RETORNO DA WEB SERVICE 
        //GERA COMPROVANTE
        if ($msg == 'OK') {
          $comprovante = 'CLIENTE: ' . $nome . '
                                                  Cartão: ' . $cartao . '
                                                  DATA: ' . date("Y-m-d H:i:s") . '
                                                  SALDO ACULUMADO: ' . fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO']) . '

                                                 *. COMPROVANTE NÃO FISCAL.*';
        }
        return array(
          'nome' => $nome,
          'cartao' => $cartao,
          'saldo' => fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO']),
          'saldoresgate' => fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL']),
          'comprovante' => $comprovante,
          'url' => '01',
          'msgerro' => $msg
        );
      } else {
        return array('msgerro' => "Id_cliente não confere com o cadastro!");
      }
    } else {
      return array('msgerro' => 'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');
    }
  } else {
    $msg = 'Oh Não! Seu Usuario ou senha está errado. Se tiver a necessidade entre  em contato com o Administrador do sistema.';
    return array('msgerro' => $msg);
  }

  mysqli_close($connAdm->connAdm());
  mysqli_close($connUser->connUser());
}

//=================================================================== Fim InserirVenda =================================================================================