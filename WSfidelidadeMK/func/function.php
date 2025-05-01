<?php
ini_set('output_buffering',4092);
ini_set('memory_limit', '2096M');
ini_set('post_max_size', '512M');
ini_set('max_execution_time', '4');
ini_set('max_input_vars', '30000');
date_default_timezone_set('America/Sao_Paulo');
ini_set('default_charset','UTF-8');
ini_set('default_socket_timeout', 03);
ini_set('soap.wsdl_cache_enabled', '0'); 
ini_set('soap.wsdl_cache_ttl', '0');

function fnEncode($pure_string) {
    $dirty = array("+", "/", "=");
    $clean = array("p£", "s£", "¢");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $_SESSION['iv'] = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH,'123456', utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv_size);
    $encrypted_string = base64_encode($encrypted_string);
    return trim(str_replace($dirty, $clean, $encrypted_string));
}

//-----------FIM
//
//-------------DECRYPT       
function fnDecode($encrypted_string) { 
    $dirty = array("+", "/", "=");
    $clean = array("p£", "s£", "¢");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $string = base64_decode(str_replace($clean, $dirty, $encrypted_string));
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, '123456',$string, MCRYPT_MODE_ECB, $iv_size);
    return trim($decrypted_string);
}

function fnMemInicial($conn,$opcao,$user) { 
        $datahora=DATE("d/m/Y H:i:s");
        IF($opcao=="true"){

          $mem_usage = memory_get_usage(true); 

          if ($mem_usage < 1024)
          {    

              $logqueryinsert='insert into teste_marka.log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("'.$mem_usage." bytes".'","'.$_GET['mod'].'","'.$datahora.'","'.$user.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());

          }
          elseif ($mem_usage < 1048576)
          {    

              $logqueryinsert='insert into teste_marka.log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("'.round($mem_usage/1024,2)." kilobytes".'","'.$_GET['mod'].'","'.$datahora.'","'.$user.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());

          }    
          else
          {    

           $logqueryinsert='insert into teste_marka.log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("'.round($mem_usage/1048576,2)." megabytes".'","'.$_GET['mod'].'","'.$datahora.'","'.$user.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());


          }
}
}
function fnLimpaCampoZero($campo)
{
    
    if($campo=="" || is_int($campo)|| empty($campo)|| !is_numeric($campo) || is_null($campo))
    {
        
       //return $campo= (int)0;
        
        $campo= 0;
        return $campo;
    }
    else
    {
        
        return $campo;
    }
    
}

function fnformatavalorretorno($Num)
{
  if (empty($Num) || is_null($Num) ) {$Numero = 000;} else {$Numero = $Num;}  
  $valor = str_replace(".", ",", $Numero); 
  return $valor; //retorna o valor formatado para gravar no banco 
}   
function fnFormatvalor($Num)
{
  if (empty($Num) || is_null($Num) ) {$Numero = 0;} else {$Numero = $Num;}		
  $valor = str_replace(".", "", $Numero);
  $valor = str_replace(",", ".", $Numero); 
  return $valor; //retorna o valor formatado para gravar no banco 
}    
function fnlimpaCPF($valor)
{
 $valor = trim($valor);
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace("/", "", $valor);
 return $valor; 
 
}   


    

function fn_calValor($arrayiten){
 
    if (count($arrayiten['items']['vendaitem']['codigoproduto'])==1){
           $vltotal=(float)fnFormatvalor($arrayiten['valortotal']);
           
            $quantidade=fnFormatvalor($arrayiten['items']['vendaitem']['quantidade']);
            $valor=fnFormatvalor($arrayiten['items']['vendaitem']['valor']);
            $vl=$valor * $quantidade;
                if(trim($vltotal) == trim($vl)) 
                {  
                    $retorno = 1;
                    return $retorno;
                    
                }else{
                   
                    $retorno = 0;
                    return $retorno;
                   
                }
        
        
    }else{
     $vltotal=(float)fnFormatvalor($arrayiten['valortotal']);
        for ($i=0;$i <= count($arrayiten['items']['vendaitem'])-1; $i++){
                $quantidade=$arrayiten['items']['vendaitem'][$i]['quantidade'];
                $valor=$arrayiten['items']['vendaitem'][$i]['valor'];
                $result=fnFormatvalor($valor) * fnFormatvalor($quantidade);
                $vl=$vl+$result; 
                  
                  
        }
       
        if(trim($vltotal) == trim($vl)) 
        {  
            $retorno = 1;
            return $retorno;
            
        }else{}
       
    }
    
   
}
function fnmemoria($conn,$opcao,$user,$pagina) { 
              
            
        $datahora=DATE("d/m/Y H:i:s");
            
        $mem_usage = memory_get_usage(true); 
        IF($opcao=="true"){
            
          $mtimei = time();   
            
          $mem_usage = memory_get_usage(true); 

          if ($mem_usage < 1024)
          {    

              $logqueryinsert='insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("'.$mem_usage." bytes".'","'.$pagina.'","'.$datahora.'","'.$user.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());

          }
          elseif ($mem_usage < 1048576)
          {    

              $logqueryinsert='insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("'.round($mem_usage/1024,2)." kilobytes".'","'.$pagina.'","'.$datahora.'","'.$user.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());

          }    
          else
          {    

           $logqueryinsert='insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("'.round($mem_usage/1048576,2)." megabytes".'","'.$pagina.'","'.$datahora.'","'.$user.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());


          }
          
}  
elseif($opcao='false'){
    $mtimef = time();
   
    
    $finaltime = $mtimef - $mtimei;
   // $finaltime1=(microtime(TRUE) - $time);
       
     $tempo_carregamento = round((microtime(true) - $_SERVER['REQUEST_TIME']),5);
     
  
        $mem_usage = memory_get_usage(true); 
   
         if ($mem_usage < 1024)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_FINAL='".$mem_usage."',ativo=1 WHERE  ativo='0'";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
          
        }
        elseif ($mem_usage < 1048576)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_FINAL='".round($mem_usage/1024,2)." kilobytes"."',ativo=1 WHERE ativo=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
                  }    
        else
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_FINAL='".round($mem_usage/1048576,2)." megabytes"."',ativo=1 WHERE  ativo=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
        }
        //Picos de memoria
         $mem_usage = memory_get_peak_usage(true); 
         
         if ($mem_usage < 1024)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_PICO='".$mem_usage."',MEN_PICO=1 WHERE  MEN_PICO='0'";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
          
        }
        elseif ($mem_usage < 1048576)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_PICO='".round($mem_usage/1024,2)." kilobytes"."',MEN_PICO=1 WHERE MEN_PICO=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
                  }    
        else
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_PICO='".round($mem_usage/1048576,2)." megabytes"."',MEN_PICO=1 WHERE   MEN_PICO=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
        }  
        
    }    
 
}

function fn_consultaBase($conn,$CPF,$CNPJ,$cartao,$email,$telcelular){
   if($CPF!=="")
   {
       $sql="SELECT * FROM clientes where NUM_CGCECPF=".$CPF; 
       $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
        $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                     
                                    'COD_CLIENTE'=>$row1['COD_CLIENTE'],    
                                    'cartao'=>$row1['NUM_CARTAO'],
                                    'tipocliente'=>$row1['TIP_CLIENTE'],
                                    'nome' => $row1['NOM_CLIENTE'],
                                    'cpf' => $row1['NUM_CGCECPF'],
                                    'cnpj'=>$row1['NUM_CGCECPF'],
                                    'rg'=>$row1['NUM_RGPESSO'],
                                    'sexo'=>$row1['COD_SEXOPES'],
                                    'datanascimento'=>$row1['DAT_NASCIME'],
                                    'estadocivil'=>$row1['COD_ESTACIV'],
                                    'email'=>$row1['DES_EMAILUS'],
                                    'dataalteracao'=>$row1['DAT_ALTERAC'],
                                    'cartaotitular'=>$row1['NUM_CARTAO'],
                                    'nomeportador'=>$row1['NOM_CLIENTE'],
                                    'grupo'=>'',
                                    'profissao'=>$row1['COD_PROFISS'],
                                    'clientedesde'=>$row1['DAT_CADASTR'],
                                    'endereco'=>$row1['DES_ENDEREC'],
                                    'numero'=>$row1['NUM_ENDEREC'],
                                    'complemento'=>$row1['DES_COMPLEM'],
                                    'bairro'=>$row1['DES_BAIRROC'],
                                    'cidade'=>$row1['NOM_CIDADEC'],
                                    'estado'=>$row1['COD_ESTADOF'],
                                    'cep'=>$row1['NUM_CEPOZOF'],
                                    'telresidencial'=>$row1['NUM_TELEFON'],
                                    'telcelular'=>$row1['NUM_CELULAR'],
                                    'telcomercial'=>'',
                                    'saldo' =>'',
                                    'saldoresgate' =>'',  
                                    'msgerro' => '',
                                    'msgcampanha' =>'',
                                    'url' =>'',
                                    'ativacampanha' => '',
                                    'dadosextras' => ''
            
            ));
        return $arraydadosBase;  
              
     
   }   
   if($CNPJ!=='')
   {
       $sql="SELECT * FROM clientes where NUM_CGCECPF=".$CNPJ; 
       $row1 =   $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
        $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                    
                                    'COD_CLIENTE'=>$row1['COD_CLIENTE'],    
                                    'cartao'=>$row1['NUM_CARTAO'],
                                    'tipocliente'=>$row1['TIP_CLIENTE'],
                                    'nome' => $row1['NOM_CLIENTE'],
                                    'cpf' => $row1['NUM_CGCECPF'],
                                    'cnpj'=>$row1['NUM_CGCECPF'],
                                    'rg'=>$row1['NUM_RGPESSO'],
                                    'sexo'=>$row1['COD_SEXOPES'],
                                    'datanascimento'=>$row1['DAT_NASCIME'],
                                    'estadocivil'=>$row1['COD_ESTACIV'],
                                    'email'=>$row1['DES_EMAILUS'],
                                    'dataalteracao'=>$row1['DAT_ALTERAC'],
                                    'cartaotitular'=>$row1['NUM_CARTAO'],
                                    'nomeportador'=>$row1['NOM_CLIENTE'],
                                    'grupo'=>'',
                                    'profissao'=>$row1['COD_PROFISS'],
                                    'clientedesde'=>$row1['DAT_CADASTR'],
                                    'endereco'=>$row1['DES_ENDEREC'],
                                    'numero'=>$row1['NUM_ENDEREC'],
                                    'complemento'=>$row1['DES_COMPLEM'],
                                    'bairro'=>$row1['DES_BAIRROC'],
                                    'cidade'=>$row1['NOM_CIDADEC'],
                                    'estado'=>$row1['COD_ESTADOF'],
                                    'cep'=>$row1['NUM_CEPOZOF'],
                                    'telresidencial'=>$row1['NUM_TELEFON'],
                                    'telcelular'=>$row1['NUM_CELULAR'],
                                    'telcomercial'=>'',
                                    'saldo' =>'',
                                    'saldoresgate' =>'',  
                                    'msgerro' => '',
                                    'msgcampanha' =>'',
                                    'url' =>'',
                                    'ativacampanha' => '',
                                    'dadosextras' => ''
            
            ));
        return $arraydadosBase;  
      
   }    
    if($cartao!=='')
   {
        
       $sql="SELECT * FROM clientes where NUM_CARTAO='".$cartao."'"; 
       $row1 =   $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
        $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                            
                                    'COD_CLIENTE'=>$row1['COD_CLIENTE'],
                                    'cartao'=>$row1['NUM_CARTAO'],
                                    'tipocliente'=>$row1['TIP_CLIENTE'],
                                    'nome' => $row1['NOM_CLIENTE'],
                                    'cpf' => $row1['NUM_CGCECPF'],
                                    'cnpj'=>$row1['NUM_CGCECPF'],
                                    'rg'=>$row1['NUM_RGPESSO'],
                                    'sexo'=>$row1['COD_SEXOPES'],
                                    'datanascimento'=>$row1['DAT_NASCIME'],
                                    'estadocivil'=>$row1['COD_ESTACIV'],
                                    'email'=>$row1['DES_EMAILUS'],
                                    'dataalteracao'=>$row1['DAT_ALTERAC'],
                                    'cartaotitular'=>$row1['NUM_CARTAO'],
                                    'nomeportador'=>$row1['NOM_CLIENTE'],
                                    'grupo'=>'',
                                    'profissao'=>$row1['COD_PROFISS'],
                                    'clientedesde'=>$row1['DAT_CADASTR'],
                                    'endereco'=>$row1['DES_ENDEREC'],
                                    'numero'=>$row1['NUM_ENDEREC'],
                                    'complemento'=>$row1['DES_COMPLEM'],
                                    'bairro'=>$row1['DES_BAIRROC'],
                                    'cidade'=>$row1['NOM_CIDADEC'],
                                    'estado'=>$row1['COD_ESTADOF'],
                                    'cep'=>$row1['NUM_CEPOZOF'],
                                    'telresidencial'=>$row1['NUM_TELEFON'],
                                    'telcelular'=>$row1['NUM_CELULAR'],
                                    'telcomercial'=>'',
                                    'saldo' =>'',
                                    'saldoresgate' =>'',  
                                    'msgerro' => '',
                                    'msgcampanha' =>'',
                                    'url' =>'',
                                    'ativacampanha' => '',
                                    'dadosextras' => ''
            
            ));
        return $arraydadosBase;  

   } 
    if($email!=='')
   {
       $sql="SELECT * FROM clientes where DES_EMAILUS='".$email."'"; 
       $row1 =  $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
        $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                               
                                    'COD_CLIENTE'=>$row1['COD_CLIENTE'],
                                    'cartao'=>$row1['NUM_CARTAO'],
                                    'tipocliente'=>$row1['TIP_CLIENTE'],
                                    'nome' => $row1['NOM_CLIENTE'],
                                    'cpf' => $row1['NUM_CGCECPF'],
                                    'cnpj'=>$row1['NUM_CGCECPF'],
                                    'rg'=>$row1['NUM_RGPESSO'],
                                    'sexo'=>$row1['COD_SEXOPES'],
                                    'datanascimento'=>$row1['DAT_NASCIME'],
                                    'estadocivil'=>$row1['COD_ESTACIV'],
                                    'email'=>$row1['DES_EMAILUS'],
                                    'dataalteracao'=>$row1['DAT_ALTERAC'],
                                    'cartaotitular'=>$row1['NUM_CARTAO'],
                                    'nomeportador'=>$row1['NOM_CLIENTE'],
                                    'grupo'=>'',
                                    'profissao'=>$row1['COD_PROFISS'],
                                    'clientedesde'=>$row1['DAT_CADASTR'],
                                    'endereco'=>$row1['DES_ENDEREC'],
                                    'numero'=>$row1['NUM_ENDEREC'],
                                    'complemento'=>$row1['DES_COMPLEM'],
                                    'bairro'=>$row1['DES_BAIRROC'],
                                    'cidade'=>$row1['NOM_CIDADEC'],
                                    'estado'=>$row1['COD_ESTADOF'],
                                    'cep'=>$row1['NUM_CEPOZOF'],
                                    'telresidencial'=>$row1['NUM_TELEFON'],
                                    'telcelular'=>$row1['NUM_CELULAR'],
                                    'telcomercial'=>'',
                                    'saldo' =>'',
                                    'saldoresgate' =>'',  
                                    'msgerro' => '',
                                    'msgcampanha' =>'',
                                    'url' =>'',
                                    'ativacampanha' => '',
                                    'dadosextras' => ''
            
            ));
        return $arraydadosBase;  
       
   } 
   if($telcelular!=='')
   {
       $sql="SELECT * FROM clientes where NUM_CELULAR='".$telcelular."'"; 
       $row1 =  $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
       $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                        
                                    'COD_CLIENTE'=>$row1['COD_CLIENTE'],
                                    'cartao'=>$row1['NUM_CARTAO'],
                                    'tipocliente'=>$row1['TIP_CLIENTE'],
                                    'nome' => $row1['NOM_CLIENTE'],
                                    'cpf' => $row1['NUM_CGCECPF'],
                                    'cnpj'=>$row1['NUM_CGCECPF'],
                                    'rg'=>$row1['NUM_RGPESSO'],
                                    'sexo'=>$row1['COD_SEXOPES'],
                                    'datanascimento'=>$row1['DAT_NASCIME'],
                                    'estadocivil'=>$row1['COD_ESTACIV'],
                                    'email'=>$row1['DES_EMAILUS'],
                                    'dataalteracao'=>$row1['DAT_ALTERAC'],
                                    'cartaotitular'=>$row1['NUM_CARTAO'],
                                    'nomeportador'=>$row1['NOM_CLIENTE'],
                                    'grupo'=>'',
                                    'profissao'=>$row1['COD_PROFISS'],
                                    'clientedesde'=>$row1['DAT_CADASTR'],
                                    'endereco'=>$row1['DES_ENDEREC'],
                                    'numero'=>$row1['NUM_ENDEREC'],
                                    'complemento'=>$row1['DES_COMPLEM'],
                                    'bairro'=>$row1['DES_BAIRROC'],
                                    'cidade'=>$row1['NOM_CIDADEC'],
                                    'estado'=>$row1['COD_ESTADOF'],
                                    'cep'=>$row1['NUM_CEPOZOF'],
                                    'telresidencial'=>$row1['NUM_TELEFON'],
                                    'telcelular'=>$row1['NUM_CELULAR'],
                                    'telcomercial'=>'',
                                    'saldo' =>'',
                                    'saldoresgate' =>'',  
                                    'msgerro' => '',
                                    'msgcampanha' =>'',
                                    'url' =>'',
                                    'ativacampanha' => '',
                                    'dadosextras' => ''
            
            ));
        return $arraydadosBase;  
       
   }
        
    
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
function fnDataSql($str){
    
    $data = str_replace("/", "-",$str);
    $strcount= date('Y-m-d', strtotime($data));
    return $strcount;    
    
    
}
function fnDataBR($str){
    
    $data = str_replace("-", "/",$str);
    $strcount= date('d/m/Y', strtotime($data));
    return $strcount;    
    
    
}
function is_Date($str){ 
        if (is_numeric($str) ||  preg_match('^[0-9]^', $str)){  
            return $str;
            
        } else{
             $str=date('Y-m-d H:m:s');
             
             return $str;
        }
        return false; 
}
function fnTestesql($conn,$sql,$id_log){
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {
          mysqli_query($conn,$sql);
          
           $msg1='INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   ('.$id_log.',"'.date("Y-m-d H:i:s").'","Teste no sql OK")';
            mysqli_query($conn,$msg1);
          
         } catch (mysqli_sql_exception $e) {
         
             $xamls= addslashes($e);
             $msg1='INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   ('.$id_log.',"'.date("Y-m-d H:i:s").'","'.$xamls.'")';
            mysqli_query($conn,$msg1);    
             
         }
  
}
function Grava_log($conn,$id_log,$MSG){
  $msg1='INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   ('.$id_log.',"'.date("Y-m-d H:i:s").'","'.$MSG.'")';
    mysqli_query($conn,$msg1);  
    
       
}


function valida_campo_vazio($campo,$nomecampo,$fromato){
    if ((rtrim(trim($campo)) == "") && ($nomecampo != 'email' )) {
        $msg = 'Campo '.$nomecampo.' precisa ser preenchido!';
        return $msg;
    }
    
    if($nomecampo == 'email'){
        if($campo!=""){
            if (!filter_var($campo, FILTER_VALIDATE_EMAIL)) {
                $msg = 'O Campo '.$nomecampo.' esta com o formato invalido !';
                return $msg; 
            }
        }
    }
	
    if($nomecampo=='nome'){
		
		$count=strlen($campo);
		if($count >= 3)
		{
			
		}else{
			 $msg = 'O texto no campo  '.$nomecampo.' está menor que o esperado, por favor digitar o nome completo!';
                        return $msg;
		}	
		/*
        $regex = "/([A-Z]{1}[A-Z]+\s{0,2}$)+/";
        if (preg_match($regex, $campo)) {}
        else {
            $msg = 'O texto no campo  '.$nomecampo.' está menor que o esperado, por favor digitar o nome completo!';
            return $msg; 
        }
        */
    }
    if($fromato=='DATA_BR'){
      //aqui eu preciso ver uma chave para ver se critiva ou nao 
        if($campo == ""){
            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $campo)) {
                $msg = 'O campo  '.$nomecampo.' data esta invalida digite DD/MM/AAAA!';
                return $msg;
            } 
        }else{
            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $campo)) {
                $msg = 'O campo  '.$nomecampo.' data esta invalida digite DD/MM/AAAA!';
                return $msg;
            }
        }
		
			 
    }    
		/*
		//validando telefone residencial
		$telefone = "(82) 5555-5555";
		if(!preg_match('^\(+[0-9]{2,3}\) [0-9]{4}-[0-9]{4}$^', $telefone)){
		echo "Telefone inváildo.";
		$msg = 'O campo  '.$nomecampo.' Telefone invalido (XX) 5555-5555';
		} 
		
		$celular = '(21) 98765-4321';

		if (preg_match('#^\(\d{2}\) (9|)[6789]\d{3}-\d{4}$#', $celular) > 0) {
			 echo 'Validou';
		} else {
			 echo 'Não validou';
		}
		*/
	
    if($fromato=='DATA_US'){
        //aqui eu preciso ver uma chave para ver se critiva ou nao 
        if($campo == ""){
                if (!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $campo)) {
                    $msg = 'O campo  '.$nomecampo.' data esta invalida digite AAAA/MM/DD!';
                    return $msg;
                }  
        }else{
            
                if (!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $campo)) {
                    $msg = 'O campo  '.$nomecampo.' data esta invalida digite AAAA/MM/DD!';
                    return $msg;
                }else{
                   

                }
        }
    }
       
    
    
    if($fromato == 'numeric')
    {    
            if(is_numeric($campo))
            {

            }
            else 
            {
                if($nomecampo =='sexo')
                {
                    $msg = 'O Campo '.$nomecampo.' precisa ser só numero ex 1 para masculino || 2 para feminino !';
                    return $msg;
                }else{   
                    $msg = 'O Campo '.$nomecampo.' precisa ser só numero!';
                    return $msg;
                }
            }
            
        
      
    }
        
 
}
