<?php
include '../_system/_functionsMain.php';
function fncompletadoc1($cpfcnpj)
{    
$tipo=strtoupper($tipo);    
         
        $retun=str_pad($cpfcnpj, 11, '0', STR_PAD_LEFT); // Resultado: 00009   
        return $retun;
 } 

function valida_cpf( $cpf = false ) {
    // Exemplo de CPF: 025.462.884-23
    
    /**
     * Multiplica dígitos vezes posições 
     *
     * @param string $digitos Os digitos desejados
     * @param int $posicoes A posição que vai iniciar a regressão
     * @param int $soma_digitos A soma das multiplicações entre posições e dígitos
     * @return int Os dígitos enviados concatenados com o último dígito
     *
     */
    if ( ! function_exists('calc_digitos_posicoes') ) {
        function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
            // Faz a soma dos dígitos com a posição
            // Ex. para 10 posições: 
            //   0    2    5    4    6    2    8    8   4
            // x10   x9   x8   x7   x6   x5   x4   x3  x2
            //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
            for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
                $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
                $posicoes--;
            }
     
            // Captura o resto da divisão entre $soma_digitos dividido por 11
            // Ex.: 196 % 11 = 9
            $soma_digitos = $soma_digitos % 11;
     
            // Verifica se $soma_digitos é menor que 2
            if ( $soma_digitos < 2 ) {
                // $soma_digitos agora será zero
                $soma_digitos = 0;
            } else {
                // Se for maior que 2, o resultado é 11 menos $soma_digitos
                // Ex.: 11 - 9 = 2
                // Nosso dígito procurado é 2
                $soma_digitos = 11 - $soma_digitos;
            }
     
            // Concatena mais um dígito aos primeiro nove dígitos
            // Ex.: 025462884 + 2 = 0254628842
            $cpf = $digitos . $soma_digitos;
            
            // Retorna
            return $cpf;
        }
    }
    
    // Verifica se o CPF foi enviado
    if ( ! $cpf ) {
        return false;
    }
 
    // Remove tudo que não é número do CPF
    // Ex.: 025.462.884-23 = 02546288423
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
 
    // Verifica se o CPF tem 11 caracteres
    // Ex.: 02546288423 = 11 números
    if ( strlen( $cpf ) != 11 ) {
        return false;
    }   
 
    // Captura os 9 primeiros dígitos do CPF
    // Ex.: 02546288423 = 025462884
    $digitos = substr($cpf, 0, 9);
    
    // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
    $novo_cpf = calc_digitos_posicoes( $digitos );
    
    // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
    $novo_cpf = calc_digitos_posicoes( $novo_cpf, 11 );
    
    // Verifica se o novo CPF gerado é idêntico ao CPF enviado
    if ( $novo_cpf === $cpf ) {
        // CPF válido
        return true;
    } else {
        // CPF inválido
        return false;
    }
}

 $cabecalho='CPF_VALIDO;NOME_RECEITA;NOME_CLIENTE;NASCIMEMTO;CPF \n';
 $conncliente= connTemp('332', '');
$sqlq="SELECT * from clientes  WHERE cod_empresa=332 AND   cod_externo>0";

$rwsql=mysqli_query($conncliente, $sqlq);
while ($rssql= mysqli_fetch_assoc($rwsql))
{
  /*   $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://ws.ifaro.com.br/WSDados.svc?wsdl=",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                           xmlns:tem=\"http://tempuri.org/\" >\r\n   
                           <soapenv:Header/>\r\n   <soapenv:Body>\r\n     
                           <tem:ConsultaPessoaSimplificado>\r\n         
                           <tem:cpf>".fncompletadoc1($rssql['NUM_CGCECPF'])."</tem:cpf>\r\n         
                           <tem:login>TUFSS0E=</tem:login>\r\n         
                           <tem:senha>c21hZWJSQXExNw==</tem:senha>\r\n     
                           </tem:ConsultaPessoaSimplificado>\r\n   
                           </soapenv:Body>\r\n
                           </soapenv:Envelope>",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: text/xml",
      "postman-token: fca2049c-8e80-9cd1-a290-bed88bcf2c4e",
      "soapaction: http://tempuri.org/IWSDados/ConsultaPessoaSimplificado"
    ),
  ));

  
  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);
 
  if ($err) {
   // $msg="cURL Error #:" . $err;
     $msg= "Consulta automatica indisponivel!";
   
     
  } else {
   //  $response;
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $NOME = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->nome;
          $CPF = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->cpf;
          $sexor = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->sexo;
          $datanascimento = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->datanascimento;
         
  }
  */  
  if (valida_cpf(fncompletadoc1($rssql['NUM_CGCECPF']))) 
    {
        $texto.=  '<br>SIM;SIM;'.$rssql['NOM_CLIENTE'].';'.$rssql['DAT_NASCIME'].';'.fncompletadoc1($rssql['NUM_CGCECPF']).' \n';
    } else {         
        $texto.=  '<br>NAO;NAO;'.$rssql['NOM_CLIENTE'].';'.$rssql['DAT_NASCIME'].';'.fncompletadoc1($rssql['NUM_CGCECPF']).' \n';
    }
    
}   
echo   $cabecalho.$texto;   