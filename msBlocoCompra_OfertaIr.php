<?php

include "_system/_functionsMain.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST["id"]));
$cod_orcamento = fnLimpaCampoZero($_REQUEST["COD_ORCAMENTO"]);
$TIP_PAGTO = $_REQUEST["TIP_PAGTO"];

$VAL_TOTAL = fnLimpaCampo($_REQUEST["VAL_TOTAL"]);

$NOM_COMPRADOR = $_REQUEST["NOM_COMPRADOR"];
$NUM_DOCUMENTO = $_REQUEST["NUM_DOCUMENTO"];
$DAT_NASCIMENTO = $_REQUEST["DAT_NASCIMENTO"];
$NUM_TELEFDDD = $_REQUEST["NUM_TELEFDDD"];
$NUM_TELEFONE = $_REQUEST["NUM_TELEFONE"];
$DES_EMAIL = $_REQUEST["DES_EMAIL"];
$NUM_CEP = $_REQUEST["NUM_CEP"];
$DES_ENDERECO = $_REQUEST["DES_ENDERECO"];
$NUM_ENDERECO = $_REQUEST["NUM_ENDERECO"];
$DES_COMPLEMENTO = $_REQUEST["DES_COMPLEMENTO"];
$NOM_BAIRRO = $_REQUEST["NOM_BAIRRO"];
$NOM_CIDADE = $_REQUEST["NOM_CIDADE"];
$ABR_UF = $_REQUEST["ABR_UF"];
$COD_PRODUTO = explode(",",$_REQUEST["COD_PRODUTO"]);
/*
echo "<pre>";
print_r($_REQUEST);
echo "</pre>";exit;
*/
switch (@$_REQUEST['acao']) {
  case "cad_pedido":
    $cardToken = htmlspecialchars($_REQUEST["cardToken"]);
    $cardHash = htmlspecialchars($_REQUEST["cardHash"]);
    $cardIsHom = $_REQUEST["cardIsHom"];

    //CADASTRA O COMPRADOR
    $sql = "";
    $sqlBus = "SELECT NOM_COMPRADOR FROM tmp_pedido_comprador 
            WHERE REPLACE(REPLACE(REPLACE(NUM_DOCUMENTO,'.',''),'-',''),'/','') 
                  = REPLACE(REPLACE(REPLACE('$NUM_DOCUMENTO','.',''),'-',''),'/','')";
    $resultBus = mysqli_query($connAdm->connAdm(), $sqlBus) or die(mysqli_error());
    if (mysqli_num_rows($resultBus) <= 0) {
      $sql = "INSERT INTO tmp_pedido_comprador ( "
              . " NOM_COMPRADOR "
              . " , NUM_DOCUMENTO "
              . " , DAT_NASCIMENTO "
              . " , NUM_TELEFDDD "
              . " , NUM_TELEFONE "
              . " , DES_EMAIL "
              . " , NUM_CEP "
              . " , DES_ENDERECO "
              . " , NUM_ENDERECO "
              . " , DES_COMPLEMENTO "
              . " , NOM_BAIRRO "
              . " , NOM_CIDADE "
              . " , ABR_UF "
              . " ) VALUES ( "
              . " '$NOM_COMPRADOR' "
              . " , '$NUM_DOCUMENTO' "
              . " , '$DAT_NASCIMENTO' "
              . " , '$NUM_TELEFDDD' "
              . " , '$NUM_TELEFONE' "
              . " , '$DES_EMAIL' "
              . " , '$NUM_CEP' "
              . " , '$DES_ENDERECO' "
              . " , '$NUM_ENDERECO' "
              . " , '$DES_COMPLEMENTO' "
              . " , '$NOM_BAIRRO' "
              . " , '$NOM_CIDADE' "
              . " , '$ABR_UF' "
              . " )";
    } else {

      $sql = "UPDATE tmp_pedido_comprador SET "
              . " NOM_COMPRADOR = '$NOM_COMPRADOR' "
              . " , NUM_DOCUMENTO = '$NUM_DOCUMENTO' "
              . " , DAT_NASCIMENTO = '$DAT_NASCIMENTO' "
              . " , NUM_TELEFDDD = '$NUM_TELEFDDD' "
              . " , NUM_TELEFONE = '$NUM_TELEFONE' "
              . " , DES_EMAIL = '$DES_EMAIL' "
              . " , NUM_CEP = '$NUM_CEP' "
              . " , DES_ENDERECO = '$DES_ENDERECO' "
              . " , NUM_ENDERECO = '$NUM_ENDERECO' "
              . " , DES_COMPLEMENTO = '$DES_COMPLEMENTO' "
              . " , NOM_BAIRRO = '$NOM_BAIRRO' "
              . " , NOM_CIDADE = '$NOM_CIDADE' "
              . " , ABR_UF = '$ABR_UF' "
              . " WHERE REPLACE(REPLACE(REPLACE(NUM_DOCUMENTO, '.', ''), '-', ''), '/', '') 
                      = REPLACE(REPLACE(REPLACE('$NUM_DOCUMENTO', '.', ''), '-', ''), '/', '')";
    }
    mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

    //CADASTRA O PEDIDO
    $arrProduto = array();
    $arrCanal = $COD_PRODUTO;
    for ($i = 0; $i < count($arrCanal); $i++) {
		if ($arrCanal[$i] <> 0){
		  $arrTmp = cadastraProdutos($connAdm, $cod_empresa, $arrCanal[$i]);
		  if (count($arrTmp) > 0) {
			array_push($arrProduto, $arrTmp);
		  }
		}
    }

    if ($cod_orcamento > 0) {
      $sql = "DELETE FROM TMP_PEDIDO_MARKA WHERE COD_ORCAMENTO = '$cod_orcamento' AND COD_EMPRESA = '$cod_empresa' ";
      mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    } else {
      $cod_orcamento = date('ymdHis');
    }

    for ($i = 0; $i < count($arrProduto); $i++) {
      $sql = "INSERT INTO TMP_PEDIDO_MARKA ("
              . " COD_ORCAMENTO"
              . " , COD_PRODUTO"
              . " , QTD_PRODUTO"
              . " , VAL_UNITARIO"
              . " , COD_EMPRESA"
              . ") VALUES ("
              . " '$cod_orcamento'"
              . " , '" . $arrProduto[$i]["COD_PRODUTO"] . "'"
              . " , '" . $arrProduto[$i]["QTD_PRODUTO"] . "'"
              . " , '" . $arrProduto[$i]["VAL_UNITARIO"] . "'"
              . " , '$cod_empresa'"
              . ")";
      mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    }

    $sql = "SELECT COD_ORCAMENTO FROM TMP_PEDIDO_MARKA WHERE COD_ORCAMENTO = '$cod_orcamento' AND COD_EMPRESA = '$cod_empresa' ";
    $result = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    if (mysqli_num_rows($result) <= 0) {
      $cod_orcamento = 0;
    }

    /*     * ****** ENVIA PAGAMENTO AO PAGSEGURO ********* */
    $tpPag = "S";
    $cdPag = "CREDITO";
    $urlBoleto = "";

    if ($_REQUEST["tpOperacao"] != "CREDITO") {
      $PAGSEGURO_API_URL = "https://ws.pagseguro.uol.com.br/v2";
      $PAGSEGURO_EMAIL = "marcelo@markafidelizacao.com.br";
      $PAGSEGURO_TOKEN = "9wv2289343757515";
      if ($cardIsHom == "S") {
        $PAGSEGURO_API_URL = "https://ws.sandbox.pagseguro.uol.com.br/v2";
        $PAGSEGURO_EMAIL = "marcelo@markafidelizacao.com.br";
        $PAGSEGURO_TOKEN = "c34a0049-6331-4c4d-945d-13762914456f141440d840509b36073e1ea6f47d256d087a-842a-4250-a331-4da464ddc12b";
        $PAGSEGURO_TOKEN = "19A6483822DC43B4A1B9AAB04DCFEFF0";
      }

      $NUM_DOCUMENTO = limpaMascara($NUM_DOCUMENTO);
      $NUM_TELEFONE = limpaMascara($NUM_TELEFONE);
      $NUM_CEP = limpaMascara($NUM_CEP);

      $params = array();
      $params["email"] = $PAGSEGURO_EMAIL;
      $params["token"] = $PAGSEGURO_TOKEN;

      if ($TIP_PAGTO == "CARTAO") {
        $params["creditCardToken"] = $cardToken;
        $params["senderHash"] = $cardHash;
      }

      $params["receiverEmail"] = $PAGSEGURO_EMAIL;
      $params["paymentMode"] = "default";
      $params["paymentMethod"] = (($TIP_PAGTO == "CARTAO") ? "creditCard" : "boleto"); # ou BOLETO ou ONLINE_DEBIT
      $params["currency"] = "BRL";
      // $params["extraAmount"] = "1.00";
      for ($i = 0; $i < count($arrProduto); $i++) {
        $tmpId = $i + 1;
        $params["itemId{$tmpId}"] = $arrProduto[$i]["COD_PRODUTO"];
        $params["itemDescription{$tmpId}"] = $arrProduto[$i]["NOM_PRODUTO"];
        $params["itemAmount{$tmpId}"] = $arrProduto[$i]["VAL_TOTALPRO"];
        $params["itemQuantity{$tmpId}"] = 1;
      }
      $params["reference"] = "$cod_orcamento";
      $params["senderName"] = "$NOM_COMPRADOR";
      $params["senderCPF"] = "$NUM_DOCUMENTO";
      $params["senderAreaCode"] = $NUM_TELEFDDD;
      $params["senderPhone"] = "$NUM_TELEFONE";
      $params["senderEmail"] = "$DES_EMAIL";
      $params["shippingAddressStreet"] = "$DES_ENDERECO";
      $params["shippingAddressNumber"] = "$NUM_ENDERECO";
      $params["shippingAddressDistrict"] = "$NOM_BAIRRO";
      $params["shippingAddressPostalCode"] = "$NUM_CEP";
      $params["shippingAddressCity"] = "$NOM_CIDADE";
      $params["shippingAddressState"] = "$ABR_UF";
      $params["shippingAddressCountry"] = "BRA";
      $params["shippingType"] = 3;
      $params["shippingCost"] = "0.00";
      $params["installmentQuantity"] = 1; # Número de parcelas
      $params["installmentValue"] = "$VAL_TOTAL"; # Valor da parcela

      if ($TIP_PAGTO == "CARTAO") {
        $params["noInterestInstallmentQuantity"] = 4;
        $params["paymentMethodGroup1"] = "CREDIT_CARD";
        $params["paymentMethodConfigKey1_1"] = "MAX_INSTALLMENTS_NO_INTEREST";
        $params["paymentMethodConfigValue1_1"] = 2;
        $params["creditCardHolderName"] = "$NOM_COMPRADOR";
        $params["creditCardHolderCPF"] = "$NUM_DOCUMENTO";
        $params["creditCardHolderBirthDate"] = "$DAT_NASCIMENTO";
        $params["creditCardHolderAreaCode"] = $NUM_TELEFDDD;
        $params["creditCardHolderPhone"] = "$NUM_TELEFONE";
        $params["billingAddressStreet"] = "$DES_ENDERECO";
        $params["billingAddressNumber"] = "$NUM_ENDERECO";
        $params["billingAddressDistrict"] = "$NOM_BAIRRO";
        $params["billingAddressPostalCode"] = "$NUM_CEP";
        $params["billingAddressCity"] = "$NOM_CIDADE";
        $params["billingAddressState"] = "$ABR_UF";
        $params["billingAddressCountry"] = "BRA";
      }

      $header = array('Content-Type' => 'application/json;charset = UTF-8;');
      $response = curlExec($PAGSEGURO_API_URL . "/transactions", $params, $header);
      //$json = json_decode(json_encode(simplexml_load_string($response)));
      $cdPag = "";
      $tpPag = "";
      $urlBoleto = "";
      if ($response) {
        $dadosJSon = json_decode(json_encode(@simplexml_load_string($response)));
        if (@$dadosJSon->type == "1") {
          $cdPag = @$dadosJSon->code;
          $urlBoleto = @$dadosJSon->paymentLink;
          switch (@$dadosJSon->status) {
            case "1": //Aguardando Confirmação
            case "2": //Analisando
              $tpPag = "N";
              break;

            case "3": //Pagamento Confirmado
            case "4": //Paga com possíveis resalvas
              $tpPag = "S";
              break;

            default:
              break;
          }
        }
      }
    }

    if ($tpPag != "") {
      $COD_UNIVEND = 1;
      $sqlIns = " INSERT INTO PEDIDO_MARKA (
                        COD_ORCAMENTO, COD_PRODUTO, QTD_PRODUTO, VAL_UNITARIO, COD_EMPRESA, COD_UNIVEND
                        , ID_SESSION_PAGSEGURO, ID_HASH_PAGSEGURO, PAG_CONFIRMACAO) 
                      SELECT 
                        COD_ORCAMENTO, COD_PRODUTO, QTD_PRODUTO, VAL_UNITARIO, COD_EMPRESA, '$COD_UNIVEND' AS COD_UNIVEND
                        , '$cdPag' AS ID_SESSION_PAGSEGURO, '$cardHash' AS ID_HASH_PAGSEGURO, '$tpPag' AS PAG_CONFIRMACAO 
                          FROM TMP_PEDIDO_MARKA WHERE COD_ORCAMENTO = '$cod_orcamento'";
      mysqli_query($connAdm->connAdm(), $sqlIns) or die(mysqli_error());
    }

    $msgReturn = "<h3>Erro ao Processar o Pagamento!</h3><h5>Pedido $cod_orcamento</h5><br><i>" . @$response . "</i>";
    if ($tpPag == "S") {
      $msgReturn = "<h3>Pagamento Efetuado com Sucesso!<h3><h5>Pedido $cod_orcamento</h5>";
    } elseif ($tpPag == "N") {
      $msgReturn = "<h3>Aguardando Confirmação de Pagamento!<h3><h5>Pedido $cod_orcamento</h5>";
    }

    /** EXIBE RESULTADO EM TELA - INICIO * */
    echo $msgReturn;
    if ($tpPag != "") {
      echo "<table class='table table-bordered table-striped table-hover'>
            <thead>
              <tr>
                <th class='text-center'>Descrição</th>
                <th class='text-center'>Qde Comprada</th>
                <th class='text-center'>Valor Unitário</th>
                <th class='text-center'>Total</th>
              </tr>
            </thead>
            <tbody align='center'>";

      $sql = "SELECT canal.DES_CANALCOM, round(pedido.QTD_PRODUTO,0) AS QTD_PRODUTO, pedido.VAL_UNITARIO, pedido.VAL_UNITARIO * pedido.QTD_PRODUTO  AS VAL_TOTAL
              FROM pedido_marka pedido
              INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO
              INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
              WHERE pedido.COD_ORCAMENTO = $cod_orcamento";

      $res = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
      while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
               <td class='text-center'>" . $row["DES_CANALCOM"] . "</td> 
               <td class='text-center'>" . $row["QTD_PRODUTO"] . "</td> 
               <td class='text-center'>" . fnValor($row["VAL_UNITARIO"], 2) . "</td> 
               <td class='text-center'>" . fnValor($row["VAL_TOTAL"], 2) . "</td>
              </tr>";
      }
      echo "<tfoot>	
             <tr>																    
              <th class='text-right' colspan='3'>TOTAL DA COMPRA</th>       
              <th class='text-center' colspan='4'>" . fnValor($VAL_TOTAL, 2) . "</th>       
             </tr>
            </tfoot>
           </table>";
    }
    /** EXIBE RESULTADO EM TELA - FIM * */
    echo "<br/>&nbsp;<br/>";
    if ($urlBoleto != "") {
      echo ""
      . "<a href='$urlBoleto' target='_blank'>Clique aqui e imprima seu boleto</a> ou copie o link abaixo e cole em seu navegador."
      . "<br>$urlBoleto"
      . "<script>window.open('$urlBoleto')</script>";
    }
    break;

  case "busca_pagador":

    $sql = "SELECT COUNT(*) AS NUM_REG, NOM_COMPRADOR, NUM_DOCUMENTO, DAT_NASCIMENTO , NUM_TELEFDDD 
                  , NUM_TELEFONE, DES_EMAIL, NUM_CEP, DES_ENDERECO, NUM_ENDERECO
                  , DES_COMPLEMENTO, NOM_BAIRRO, NOM_CIDADE, ABR_UF 
            FROM tmp_pedido_comprador 
            WHERE REPLACE(REPLACE(REPLACE(NUM_DOCUMENTO,'.',''),'-',''),'/','') 
                  = REPLACE(REPLACE(REPLACE('" . $_REQUEST["NUM_DOCUMENTO"] . "','.',''),'-',''),'/','')";
    $result = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $arrJSON = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $arrJSON[] = $row;
    }
    echo json_encode($arrJSON);

    break;






  default:
    break;
}

function limpaMascara($str) {
  $str = str_replace(".", "", $str);
  $str = str_replace("/", "", $str);
  $str = str_replace("|", "", $str);
  $str = str_replace("-", "", $str);
  $str = str_replace(" ", "", $str);
  $str = str_replace("_", "", $str);
  return $str;
}

function cadastraProdutos($connAdm, $cod_empresa, $cod_produto) {
  $arr = array();
  $qtd_produto = 1;
  if ($qtd_produto > 0) {
    $total = fnLimpaCampoZero($_REQUEST["QTD_{$cod_produto}_TOTAL"]);
    $val_uni = round($total / $qtd_produto, 2);
    $cod_produto = $cod_produto;
    $nom_produto = retornaNomProduto($connAdm, $cod_produto);
    $arr["COD_PRODUTO"] = $cod_produto;
    $arr["NOM_PRODUTO"] = $nom_produto;
    $arr["QTD_PRODUTO"] = $qtd_produto;
    $arr["VAL_UNITARIO"] = $val_uni;
    $arr["VAL_TOTALPRO"] = $total;
  }
  return $arr;
}

function retornaCodProduto($connAdm, $cod_canalcom, $cod_comfaixa, $qtd_credito, $cod_empresa, $loop = 1) {
  $cod_produto = 0;
  $sql = "SELECT COD_PRODUTO FROM produto_marka "
          . " WHERE COD_CANALCOM = $cod_canalcom "
          . "   AND COD_COMFAIXA = $cod_comfaixa "
          . "   AND QTD_CREDITO = $qtd_credito ";
  $result = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

  if (mysqli_num_rows($result) <= 0) {
    if ($loop == 1) {
      $sql_ins = "INSERT INTO produto_marka (COD_CANALCOM, COD_COMFAIXA, VAL_UNITARIO, VAL_TOTAL, QTD_CREDITO, COD_USUCADA)"
              . " VALUES ("
              . "'$cod_canalcom'"
              . " , '$cod_comfaixa'"
              . " , '0'"
              . " , '0'"
              . " , '$qtd_credito'"
              . ", " . $_SESSION["SYS_COD_USUARIO"] . ""
              . ")";
      mysqli_query($connAdm->connAdm(), $sql_ins) or die(mysqli_error());
      $cod_produto = retornaCodProduto($connAdm, $cod_canalcom, $cod_comfaixa, $qtd_credito, $cod_empresa, 2);
    }
  } else {
    $row = mysqli_fetch_assoc($result);
    $cod_produto = $row["COD_PRODUTO"];
  }
  return $cod_produto;
}

function retornaNomProduto($connAdm, $cod_produto) {
  $sql = "SELECT V.NOM_VERSAO FROM sistema_versao V
			INNER JOIN produto_marka P ON (P.COD_VERSAO=V.COD_VERSAO)
			WHERE P.COD_PRODUTO = 0{$cod_produto} ";
//		  echo $sql;
  $result = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
  $row = mysqli_fetch_assoc($result);
  return @$row["NOM_VERSAO"];
}

function curlExec($url, $post = NULL, array $header = array()) {
  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  if (count($header) > 0) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  }
  if ($post !== null) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post, '', '&'));
  }

  //Ignore SSL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}
?>


