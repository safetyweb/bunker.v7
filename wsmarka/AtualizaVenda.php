<?php
$server->wsdl->addComplexType(
   'AtualizaVenda',
   'complexType',
   'struct',
   'all',
   '',
   array(
      'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:integer'),
      'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
   )
);

$server->register(
   'AtualizaVenda',
   array(
      'cpfcnpj'     => 'xsd:string',
      'id_vendapdv' => 'xsd:string',
      'cupomfiscal' => 'xsd:string',
      'codatendente' => 'xsd:string',
      'codvendedor' => 'xsd:string',
      'canalvendas' => 'xsd:string',
      'dadosLogin' => 'tns:LoginInfo'
   ),  //parameters
   array('AtualizaVendaResponse' => 'tns:AtualizaVenda'),  //output
   $ns,                           // namespace
   "$ns/AtualizaVenda",                       // soapaction
   'document',                         // style
   'literal',                          // use
   'AtualizaVenda'               // documentation
);


function AtualizaVenda($cpfcnpj, $id_vendapdv, $cupomfiscal, $codatendente, $codvendedor, $canalvendas, $dadosLogin)
{

   // Array com os campos e suas respectivas mensagens de erro
   $campos = array(
      'cpfcnpj' => 'CPF/CNPJ não informado',
      'id_vendapdv' => 'ID Venda PDV não informado',
      'cupomfiscal' => 'Cupom Fiscal não informado'
   );

   // Iterar pelos campos e verificar se estão vazios
   foreach ($campos as $campo => $mensagem) {
      if (empty($$campo)) { // Usar variável variável para acessar o valor
         return array(
            'AtualizaVendaResponse' => array(
               'msgerro' => $mensagem,
               'coderro' => '400' // Código de erro genérico, pode ser alterado conforme necessidade
            )
         );
      }
   }

   include_once '../_system/Class_conn.php';
   include_once 'func/function.php';
   $cannadmuser = $connAdm->connAdm();
   ob_start();
   $cartao = fnlimpaCPF($cpfcnpj);
   $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
   $row = mysqli_fetch_assoc(mysqli_query($cannadmuser, $sql));
   //$row = mysqli_fetch_assoc($buscauser);
   // Libera qualquer conjunto de resultados pendentes
   while (mysqli_more_results($cannadmuser)) {
      mysqli_next_result($cannadmuser);
      if ($result = mysqli_store_result($cannadmuser)) {
         mysqli_free_result($result);
      }
   }


   $dec = $row['NUM_DECIMAIS'];
   if ($row['TIP_RETORNO'] == 2) {
      $decimal = 2;
   } else {
      $casasDec = 0;
   }

   //verifica se a loja foi delabilitada
   $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
   $lojars = mysqli_fetch_assoc(mysqli_query($cannadmuser, $lojasql));
   if ($lojars['LOG_ESTATUS'] != 'S') {
      fngravalogMSG($cannadmuser, $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' Loja desabilidata', $row['LOG_WS']);
      return  array('AtualizaVendaResponse' => array(
         'msgerro' => 'LOJA DESABILITADA',
         'coderro' => '80'
      ));
      exit();
   }

   if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
      //conn user
      $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);
      $con_user = $connUser->connUser();
      $xmlteste = addslashes(file_get_contents("php://input"));
      $arrylog = array(
         'cod_usuario' => $row['COD_USUARIO'],
         'login' => $dadosLogin['login'],
         'cod_empresa' => $row['COD_EMPRESA'],
         'idloja' => $dadosLogin['idloja'],
         'idmaquina' => $dadosLogin['idmaquina'],
         'cpf' => $cartao,
         'xml' => $xmlteste,
         'tables' => 'origem_atualizavenda',
         'conn' => $connUser->connUser()
      );
      $cod_log = fngravalogxml($arrylog);

      $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><AtualizaVendaResponse></AtualizaVendaResponse>");
      //array_to_xml($return,$xml_user_info);
      //Grava_log($connUser->connUser(),$LOG,'Valor Resgate maior que o permitido', addslashes($xml_user_info->asXML()));


      // $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'AtualizaVenda',$dadosLogin['idcliente']);

      //================================================================================================================ 
      //VERIFICA SE A EMPRESA FOI DESABILITADA
      if ($row['LOG_ATIVO'] == 'N') {
         fngravalogMSG($cannadmuser, $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
         $return = array('AtualizaVendaResponse' => array(
            'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
            'coderro' => '6'
         ));
         array_to_xml($return, $xml_user_info);
         Grava_log_msgxml($con_user, 'msg_atualizavenda', $cod_log, 'Oh não! A empresa foi desabilitada por algum motivo ;-[!', addslashes($xml_user_info->asXML()));
         return $return;
         exit();
      }
      //VERIFICA SE O USUARIO FOI DESABILITADA
      if ($row['LOG_ESTATUS'] == 'N') {
         fngravalogMSG($cannadmuser, $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
         $return = array('AtualizaVendaResponse' => array(
            'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
            'coderro' => '5'
         ));
         array_to_xml($return, $xml_user_info);
         Grava_log_msgxml($con_user, 'msg_atualizavenda', $cod_log, 'A empresa foi desabilitada por algum motivo', addslashes($xml_user_info->asXML()));
         return $return;
         exit();
      }
      //Permite alteração de venda
      /* if($row['LOG_ALTVENDA']=='N'){
               fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaVenda','Oh não! A empresa não permite alteração na venda ;-[!',$row['LOG_WS']); 
               $return=array('AtualizaVendaResponse'=>array('msgerro'=>'Oh não! A empresa não permite alteração na venda ;-[!',
                                                           'coderro'=>'75'));
                array_to_xml($return,$xml_user_info);
                Grava_log_msgxml($connUser->connUser(),'msg_atualizavenda',$cod_log,'Oh não! A empresa não permite alteração na venda ;-[!',addslashes($xml_user_info->asXML()));  
                return $return;
               exit();
            }*/


      //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
      if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
         fngravalogMSG($cannadmuser, $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
         return  array('AtualizaVendaResponse' => array(
            'msgerro' => 'Id_cliente não confere com o cadastro!',
            'coderro' => '4'
         ));
         exit();
      }
   } else {
      fngravalogMSG($cannadmuser, $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'Usuario ou senha Inválido!', $row['LOG_WS']);
      return  array('AtualizaVendaResponse' => array(
         'msgerro' => 'Usuario ou senha Inválido!',
         'coderro' => '5'
      ));
      exit();
   }

   //busca cliente  na base de dados    
   $arraydadosbusca = array(
      'empresa' => $dadosLogin['idcliente'],
      'cartao' => $cartao,
      'cpf' => $cartao,
      'venda' => 'venda',
      'ConnB' => $connUser->connUser()
   );
   $cliente_cod = fn_consultaBase($arraydadosbusca);
   // $cliente_cod['COD_CLIENTE']

   fngravalogMSG($cannadmuser, $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Alteravenda', $xamls, $row['LOG_WS']);
   //fnmemoriafinal($connUser->connUser(),$cod_men);  

   //efetuar o update na venda com base no que estamos recvebendo
   $alterdadosvenda = "UPDATE vendas SET COD_CUPOM='$cupomfiscal' WHERE  COD_VENDAPDV='$id_vendapdv' AND COD_CLIENTE='" . $cliente_cod['COD_CLIENTE'] . "' AND COD_UNIVEND='" . $dadosLogin['idloja'] . "' AND COD_EMPRESA='" . $dadosLogin['idcliente'] . "'";
   $EXECQUERY = mysqli_query($con_user, $alterdadosvenda);
   if (!$EXECQUERY) {
      return  array('AtualizaVendaResponse' => array(
         'msgerro' => 'Problema ao processar rotina tente novamente mais tarde!',
         'coderro' => '80'
      ));
   }
   ob_end_flush();
   ob_flush();

   $return = array('AtualizaVendaResponse' => array(
      'msgerro' => 'OK',
      'coderro' => '39'
   ));
   array_to_xml($return, $xml_user_info);
   Grava_log_msgxml($con_user, 'msg_atualizavenda', $cod_log, 'OK', addslashes($xml_user_info->asXML()));
   return $return;
}
