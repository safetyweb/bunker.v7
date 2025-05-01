<?php
ini_set('output_buffering',4092);
ini_set('memory_limit', '4196M');
ini_set('post_max_size', '512M');
ini_set('max_execution_time', '6');
//ini_set('max_input_vars', '30000');
date_default_timezone_set('America/Sao_Paulo');
ini_set('default_charset','UTF-8');
ini_set('default_socket_timeout', 30);
ini_set('soap.wsdl_cache_enabled', '0'); 
ini_set('soap.wsdl_cache_ttl', '0');
  

function fnEncode($pure_string) {
    $dirty = array("+", "/","=");
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
function fnAcentos($string)
{
   // matriz de entrada
    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç');

    // matriz de saída
    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C');

    // devolver a string
    return str_replace($what, $by, $string);
       
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
function fnValor($Num,$Dec)
{
  
  $valor = str_replace(".", "", $Num);
  $valor = str_replace(",", ".", $valor); 
  $valor = number_format ($valor,$Dec,",",".");
  //echo $valor; //retorna o valor formatado para apresentação em tela  
  return $valor;
}
function fnformatavalorretorno($Num)
{
  if (empty($Num) || is_null($Num) ) {$Numero = 000;} else {$Numero = $Num;}  
  $valor = str_replace(".", ",", $Numero); 
  return $valor; //retorna o valor formatado para gravar no banco 
}
/*
function fnFormatvalor($Num)
{
  if (empty($Num) || is_null($Num) ) {$Numero = 0;} else {$Numero = $Num;}		
  $valor = str_replace(".", "", $Numero);
  $valor = str_replace(",", ".", $Numero); 
  $valor=number_format ($valor,4,".",".");
  return $valor; //retorna o valor formatado para gravar no banco 
}  
 * 
 */
function fnFormatvalor($Num)
{ 
  //if (empty($Num) || is_null($Num) ) {$Numero = 0;} else {$Numero = $Num;}
  
  $valor = str_replace(".", "", $Num);
  $valor = str_replace(",", ".", $Num); 
 // $valor=number_format ($valor,3,".",".");
 $valor = bcmul($valor, '100', 4); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
 $valor = bcdiv($valor, '100', 4); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
 //$valor=number_format ($valor,2,".","");
 return $valor; //retorna o valor formatado para gravar no banco 
}  
function fnlimpaCPF($valor)
{
 $valor = rtrim(trim($valor));
 $valor = str_replace(".", "", $valor);
 $valor = str_replace(",", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace("/", "", $valor);
 return $valor; 
  
}   
function fnlimpatel($valor)
{
 $valor = trim($valor);
 $valor = str_replace("(", "", $valor);
 $valor = str_replace(")", "", $valor);
 $valor = str_replace("-", "", $valor);
 $valor = str_replace(" ", "", $valor);
 return $valor; 
 
}   


function fnlimpaCEP($valor)
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
           $vltotal=fnFormatvalor(trim($arrayiten['valortotal']));
           
            $quantidade=fnFormatvalor($arrayiten['items']['vendaitem']['quantidade']);
            $valor=fnFormatvalor($arrayiten['items']['vendaitem']['valor']);
            $vl=$valor * $quantidade;
                if(trim(fnFormatvalor($vltotal)) == trim(fnFormatvalor($vl))) 
                {  
                    $retorno = 1;
                    return $retorno;
                    
                }else{
                   
                    $retorno = 0;
                    return $retorno;
                   
                }
        
         
    }else{
     $vltotal=fnFormatvalor(trim($arrayiten['valortotal']));
        for ($i=0;$i <= count($arrayiten['items']['vendaitem'])-1; $i++){
                $quantidade=$arrayiten['items']['vendaitem'][$i]['quantidade'];
                $valor=$arrayiten['items']['vendaitem'][$i]['valor'];
                $result=fnFormatvalor($valor) * fnFormatvalor($quantidade);
                $vl=$vl+$result; 
                  
                  
        }
       
        if(trim(fnFormatvalor($vltotal)) == trim(fnFormatvalor($vl))) 
        {  
            $retorno = 1;
            return $retorno;
            
        }else{
           
          
        }
       
    }
    
   
}
function fnmemoria($conn,$opcao,$user,$pagina,$empresa) { 
              
            
        $datahora=DATE("d/m/Y H:i:s");
            
        $mem_usage = memory_get_usage(true); 
        IF($opcao=="true"){
            
          $mtimei = time();   
            
          $mem_usage = memory_get_usage(true); 

          if ($mem_usage < 1024)
          {    

              $logqueryinsert='insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("'.$mem_usage." bytes".'","'.$pagina.'","'.$datahora.'","'.$user.'","'.$empresa.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());
             
              

          }
          elseif ($mem_usage < 1048576)
          {    

              $logqueryinsert='insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("'.round($mem_usage/1024,2)." kilobytes".'","'.$pagina.'","'.$datahora.'","'.$user.'","'.$empresa.'");';
              mysqli_query($conn,$logqueryinsert) or die(mysqli_error());
            
         

          }    
          else
          {
            $logqueryinsert='insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("'.round($mem_usage/1048576,2)." megabytes".'","'.$pagina.'","'.$datahora.'","'.$user.'","'.$empresa.'");';
            mysqli_query($conn,$logqueryinsert) or die(mysqli_error());
             

          }
     $COD_log= mysqli_insert_id($conn); 
     return $COD_log;
    } 
}
function fnmemoriafinal($conn,$ID)
{
    $mtimef = time();
    
    $finaltime = $mtimef - $mtimei;
   // $finaltime1=(microtime(TRUE) - $time);
       
     $tempo_carregamento = round((microtime(true) - $_SERVER['REQUEST_TIME']),5);
     
  
        $mem_usage = memory_get_usage(true); 
   
         if ($mem_usage < 1024)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_FINAL='".$mem_usage."',ativo=1 WHERE ID=$ID and ativo='0'";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
           
        }
        elseif ($mem_usage < 1048576)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_FINAL='".round($mem_usage/1024,2)." kilobytes"."',ativo=1 WHERE ID=$ID and ativo=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
             
                  }    
        else
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_FINAL='".round($mem_usage/1048576,2)." megabytes"."',ativo=1 WHERE ID=$ID and  ativo=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
          
        }
        //Picos de memoria
         $mem_usage = memory_get_peak_usage(true); 
         
         if ($mem_usage < 1024)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_PICO='".$mem_usage."',MEN_PICO=1 WHERE ID=$ID and MEN_PICO='0'";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
           
          
        }
        elseif ($mem_usage < 1048576)
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_PICO='".round($mem_usage/1024,2)." kilobytes"."',MEN_PICO=1 WHERE ID=$ID and MEN_PICO=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
             
                  }    
        else
        {    
            $SqlUpdate="UPDATE log_men SET TP_CARGA_PAGINA='".$tempo_carregamento."', TP_CARGA='".$finaltime."',NEM_PICO='".round($mem_usage/1048576,2)." megabytes"."',MEN_PICO=1 WHERE  ID=$ID and MEN_PICO=0";
            mysqli_query($conn,$SqlUpdate) or die(mysqli_error()); 
              
        } 
        return $SqlUpdate;
}
 
function fn_consultaBase($conn,$CPF,$CNPJ,$cartao,$email,$telcelular,$empresa){
   if($CPF!="")
   {
       if($cartao !=''){$andcartao='or NUM_CARTAO='.$cartao;}
       $sql="SELECT count(COD_CLIENTE) as contador,clientes.* FROM clientes where COD_EMPRESA=$empresa and (NUM_CGCECPF=".$CPF." $andcartao".")"; 
       $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
        $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                    'contador'=> $row1['contador'],
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
   if($CNPJ!='')
   {
       $sql="SELECT count(COD_CLIENTE) as contador,clientes.* FROM clientes where COD_EMPRESA=$empresa and NUM_CGCECPF=".$CNPJ; 
       $row1 =   $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
        $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                    'contador'=> $row1['contador'],
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
    if($cartao!='')
   {
       if($CPF !=''){$cpf='or NUM_CGCECPF='.$CPF;} 
       $sql="SELECT count(COD_CLIENTE) as contador,clientes.*  FROM clientes where COD_EMPRESA=$empresa and (NUM_CARTAO='".$cartao." $cpf".")" ; 
       $row1 =   $row1 = mysqli_fetch_assoc(mysqli_query($conn,$sql)); 
        $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                    'contador'=> $row1['contador'],        
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
    if($email!='')
   {
       $sql="SELECT * FROM clientes where COD_EMPRESA=$empresa and DES_EMAILUS='".$email."'"; 
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
   if($telcelular!='')
   {
       $sql="SELECT * FROM clientes where COD_EMPRESA=$empresa and NUM_CELULAR='".$telcelular."'"; 
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
             $str=date('Y-m-d H:i:s');
             
             return $str;
        }
        return false; 
}
function fnTestesql($conn,$sql){
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {
          mysqli_query($conn,$sql);
          
          
         } catch (mysqli_sql_exception $e) {
         
             return $e;    
             
         }
  
}
function Grava_log($conn,$id_log,$MSG){
    
  $msg1='INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   ('.$id_log.',"'.date("Y-m-d H:i:s").'","'.$MSG.'")';
     
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {
         mysqli_query($conn,$msg1);
          
          
         } catch (mysqli_sql_exception $e) {
             echo $e;
             return $e;    
             
         }
  
       
}
function Grava_log_consulta($conn,$id_log,$MSG){
  $msg1='INSERT INTO msg_busca (ID,DATA_HORA,MSG)values
                   ('.$id_log.',"'.date("Y-m-d H:i:s").'","'.$MSG.'")';
    mysqli_query($conn,$msg1);  
}
function Grava_log_Produto($conn,$id_log,$MSG){
  $msg1='INSERT INTO msg_produto (ID,DATA_HORA,MSG)values
                   ('.$id_log.',"'.date("Y-m-d H:i:s").'","'.$MSG.'");';
    mysqli_query($conn,$msg1);  
}
function Grava_log_cad($conn,$id_log,$MSG){
  $msg1='INSERT INTO msg_cadastra (ID,DATA_HORA,MSG)values
                   ('.$id_log.',"'.date("Y-m-d H:i:s").'","'.$MSG.'")';
    mysqli_query($conn,$msg1);  
    
       
}
function fngravaxmlatualiza($arraydados)
{
   $inserarray='INSERT INTO origemcadastro (DAT_CADASTR,
                                                    IP,
                                                    PORTA,
                                                    COD_USUARIO,
                                                    NOM_USUARIO,
                                                    COD_EMPRESA,
                                                    COD_UNIVEND,
                                                    ID_MAQUINA,
                                                    NUM_CGCECPF,
                                                    DES_VENDA)
                                                    values
                                                   ("'.$arraydados['DATA_HORA'].'",
                                                    "'.$arraydados['IP'].'",
                                                    "'.$arraydados['PORT'].'",
                                                    "'.$arraydados['COD_USUARIO'].'",
                                                    "'.$arraydados['LOGIN'].'",
                                                    "'.$arraydados['COD_EMPRESA'].'",
                                                    "'.$arraydados['IDLOJA'].'",
                                                    "'.$arraydados['IDMAQUINA'].'",
                                                    "'.$arraydados['CPF'].'",
                                                    "'.addslashes($arraydados['XML']).'"    
                                                   )';
  mysqli_query($arraydados['CONN'],$inserarray);
  //Pegar o id da venda para inserir as messagens no log
 $COD_log= mysqli_insert_id($arraydados['CONN']);
  //$COD_log='diogo';
        
  return  $COD_log;
}
function fngravaxmlbusca($arraydados)
{
   $inserarray='INSERT INTO origembusca (DAT_CADASTR,
                                                    IP,
                                                    PORTA,
                                                    COD_USUARIO,
                                                    NOM_USUARIO,
                                                    COD_EMPRESA,
                                                    COD_UNIVEND,
                                                    ID_MAQUINA,
                                                    NUM_CGCECPF,
                                                    DES_VENDA)
                                                    values
                                                   ("'.$arraydados['DATA_HORA'].'",
                                                    "'.$arraydados['IP'].'",
                                                    "'.$arraydados['PORT'].'",
                                                    "'.$arraydados['COD_USUARIO'].'",
                                                    "'.$arraydados['LOGIN'].'",
                                                    "'.$arraydados['COD_EMPRESA'].'",
                                                    "'.$arraydados['IDLOJA'].'",
                                                    "'.$arraydados['IDMAQUINA'].'",
                                                    "'.$arraydados['CPF'].'",
                                                    "'.addslashes($arraydados['XML']).'"    
                                                   )';
  mysqli_query($arraydados['CONN'],$inserarray);
  //Pegar o id da venda para inserir as messagens no log
 $COD_log= mysqli_insert_id($arraydados['CONN']);
  //$COD_log='diogo';
        
  return  $COD_log;
}
//xml venda
function fngravaxmlvendas($arraydados)
{
   $inserarray="INSERT INTO ORIGEMVENDA (DAT_CADASTR,
                                         IP,
                                         PORTA,
                                         COD_USUARIO,
                                         NOM_USUARIO,
                                         COD_EMPRESA,
                                         COD_UNIVEND,
                                         ID_MAQUINA,
                                         COD_PDV,
                                         NUM_CGCECPF,
                                         DES_VENDA)
                                        values
                                       ('".$arraydados['DATA_HORA']."',
                                        '".$arraydados['IP']."',
                                        '".$arraydados['PORT']."',
                                        '".$arraydados['COD_USUARIO']."',
                                        '".$arraydados['LOGIN']."',
                                        '".$arraydados['COD_EMPRESA']."',
                                        '".$arraydados['IDLOJA']."',
                                        '".$arraydados['IDMAQUINA']."',
                                        '".$arraydados['PDV']."',    
                                        '".$arraydados['CPF']."',
                                        '".addslashes($arraydados['XML'])."'    
                                       )";
  mysqli_query($arraydados['CONN'],$inserarray);
  //Pegar o id da venda para inserir as messagens no log
 $COD_log= mysqli_insert_id($arraydados['CONN']);
  //$COD_log='diogo';
         
  return  $COD_log;
}
//==================================================================================
//inserir venda inteira na base de dados 
function fngravaxml($arraydados)
{
   $inserarray='INSERT INTO origembuscafidelizados (DAT_CADASTR,
                                                    IP,
                                                    PORTA,
                                                    COD_USUARIO,
                                                    NOM_USUARIO,
                                                    COD_EMPRESA,
                                                    COD_UNIVEND,
                                                    ID_MAQUINA,
                                                    NUM_CGCECPF,
                                                    DES_VENDA)
                                                    values
                                                   ("'.$arraydados['DATA_HORA'].'",
                                                    "'.$arraydados['IP'].'",
                                                    "'.$arraydados['PORT'].'",
                                                    "'.$arraydados['COD_USUARIO'].'",
                                                    "'.$arraydados['LOGIN'].'",
                                                    "'.$arraydados['COD_EMPRESA'].'",
                                                    "'.$arraydados['IDLOJA'].'",
                                                    "'.$arraydados['IDMAQUINA'].'",
                                                    "'.$arraydados['CPF'].'",
                                                    "'.addslashes($arraydados['XML']).'"    
                                                   )';
  mysqli_query($arraydados['CONN'],$inserarray);
  //Pegar o id da venda para inserir as messagens no log
 $COD_log= mysqli_insert_id($arraydados['CONN']);
  //$COD_log='diogo';
   
  return  $COD_log;
}  
function Grava_log_fidelizados($conn,$id_log,$MSG){
  $msg1='INSERT INTO msg_origembuscafidelizados (ID,DATA_HORA,MSG)values
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
                    $msg = 'O campo  '.$nomecampo.' data esta invalida digite AAAA-MM-DD!';
                    return $msg;
                }  
        }else{
            
                if (!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $campo)) {
                    $msg = 'O campo  '.$nomecampo.' data esta invalida digite AAAA-MM-DD!';
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
function fnconsultaLoja($CONN1,$CONN2,$ID_LOJA,$ID_MAQUINA,$COD_EMPRESA)
{
   if($ID_LOJA=='725' && $COD_EMPRESA='42'){$ID_LOJA='1000';}else{$ID_LOJA=$ID_LOJA;}
    //unidade de venda tem que existir
   $sql ="select count(COD_UNIVEND) as existe, COD_UNIVEND  from unidadevenda where COD_EMPRESA=$COD_EMPRESA AND COD_UNIVEND='".$ID_LOJA."'";
   $retIDLOJA=mysqli_fetch_assoc(mysqli_query($CONN1,$sql));
   if($retIDLOJA['existe'] !=0)
   { 
        $MSG='1'; 
        //PROCURA POR MAQUINA 
        $sqlMAQUINA ="select count(*) as DES_MAQUINA, maquinas.COD_MAQUINA from maquinas where COD_EMPRESA=$COD_EMPRESA AND DES_MAQUINA='".$ID_MAQUINA."'";
        $retIDMAQUINA=mysqli_fetch_assoc(mysqli_query($CONN2,$sqlMAQUINA));
            if($retIDMAQUINA['DES_MAQUINA']== 0)
            {
             $sqlinsert="insert into maquinas (DES_MAQUINA,
                                               COD_EMPRESA,
                                               COD_UNIVEND
                                               )
                                               values
                                               (
                                                '".$ID_MAQUINA."',
                                                '".$COD_EMPRESA."',
                                                '".$retIDLOJA['COD_UNIVEND']."'   
                                                )";
              mysqli_query($CONN2,$sqlinsert);
              //codigo de inserção
              $ID_MAQUINA="SELECT last_insert_id(COD_MAQUINA) as COD_MAQUINA from maquinas ORDER by COD_MAQUINA DESC limit 1;";
              $id_return= mysqli_fetch_assoc(mysqli_query($CONN2,$ID_MAQUINA));
              $idmaquina=$id_return['COD_MAQUINA'];
              
            }
            else
            {
               //codigo de inserção
              $idmaquina=$retIDMAQUINA['COD_MAQUINA'];
            }    
}
   
    
  
    $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                        
                                            'msg'=>$MSG,
                                            'COD_MAQUINA'=>$idmaquina,
                                            'COD_UNIVEND'=>$retIDLOJA['COD_UNIVEND']     
                                          )
                    );
    return $arraydadosBase;  
   
   
    
}
function fnConsultaLojaGET($CONN1,$ID_LOJA)
{
   
    //unidade de venda tem que existir
   $sql ="select count(COD_UNIVEND) as existe, COD_UNIVEND  from unidadevenda where COD_UNIVEND=".$ID_LOJA;
   $retIDLOJA=mysqli_fetch_assoc(mysqli_query($CONN1,$sql));
   if($retIDLOJA['existe'] !=0)
   {
     $MSG='OK';    
   }
   else
   {
    $MSG='ERRO';   
   }    
   
  
    $arraydadosBase=array();
        array_push($arraydadosBase, array(
                                        
                                            'msg'=>$MSG,
                                            'COD_UNIVEND'=>$retIDLOJA['COD_UNIVEND']     
                                          )
                    );
    return $arraydadosBase;  
}
function fnSQLLOG($conn,$sql,$codogi){
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {
         // mysqli_query($conn,$sql);
         
          $msg1='INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   ('.$codogi.',"'.date("Y-m-d H:i:s").'","Comando SQL VALIDADO")';
              mysqli_query($conn,$msg1);
              return $msg1;  
         
         } catch (mysqli_sql_exception $e) {
             $msg1='INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   ('.$codogi.',"'.date("Y-m-d H:i:s").'","COMANDO SQL INVALIDO!")';
              mysqli_query($conn,$msg1);
              return $msg1; 
            
         }
  
}
function fnVendedor ($conn,$NOM_USUARIO,$COD_MULTEMP,$COD_UNIVEND)
{
    
    if(rtrim(trim($NOM_USUARIO))!='')
    {    
   
            $sqlbusca="select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST 
                       from usuarios where COD_EMPRESA=$COD_MULTEMP and COD_MULTEMP='$COD_MULTEMP' and NOM_USUARIO='Vendedor:".$NOM_USUARIO."'";
           
           
                
            $result=mysqli_fetch_assoc(mysqli_query($conn, $sqlbusca));
            
            if($result['exist']==0){

             $sql='insert into usuarios (COD_EMPRESA,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST,DAT_CADASTR)
                                        values
                                        (
                                        "'.$COD_MULTEMP.'",
                                        "Vendedor:'.$NOM_USUARIO.'",
                                        "7",
                                        "'.$COD_MULTEMP.'",
                                        "'.$COD_UNIVEND.'",
                                        "7",
                                        "'.DATE('Y-m-d H:i:s').'"
                                        ) ';
             
                $arraP=mysqli_query($conn, $sql);
                $COD_VENDEDOR= mysqli_insert_id($conn);
                
                return $COD_VENDEDOR;
            }
            else
            {
             $COD_VENDEDOR=$result['COD_USUARIO'];
             return $COD_VENDEDOR;
            }
            
    }else{
            
        $COD_VENDEDOR=0;
        return $COD_VENDEDOR;
    }
        
} 
//function vondedor
function fnatendente ($conn,$NOM_USUARIO,$COD_MULTEMP,$COD_UNIVEND)
{
    
    if(rtrim(trim($NOM_USUARIO))!='')
    {    
   
            $sqlbusca="select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST 
                       from usuarios where COD_EMPRESA=$COD_MULTEMP and COD_MULTEMP='$COD_MULTEMP' and NOM_USUARIO='Atendente:".$NOM_USUARIO."'";
               
            $result=mysqli_fetch_assoc(mysqli_query($conn, $sqlbusca));
            
            if($result['exist']==0){

             $sql='insert into usuarios (COD_EMPRESA,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST,DAT_CADASTR)
                                        values
                                        (
                                        "'.$COD_MULTEMP.'",
                                        "Atendente:'.$NOM_USUARIO.'",
                                        "11",
                                        "'.$COD_MULTEMP.'",
                                        "'.$COD_UNIVEND.'",
                                        "11",
                                        "'.DATE('Y-m-d H:i:s').'"
                                        ) ';
             
                $arraP=mysqli_query($conn, $sql);
                $COD_VENDEDOR= mysqli_insert_id($conn);
                
                return $COD_VENDEDOR;
            }
            else
            {
              
             $COD_VENDEDOR=$result['COD_USUARIO'];
             return $COD_VENDEDOR;
            }
            
    }else{
            
        $COD_VENDEDOR=0;
        return $COD_VENDEDOR;
    }
        
} 
//=========================


function fnDataFull($str){
    if (($timestamp = strtotime($str)) === false) 
    {
      $date='';
      return $date;
          
    } else { return date('d/m/Y H:i:s', $timestamp);}
     
                
}
function calc_idade($data_nasc) {

$data_nasc=explode("-",$data_nasc);

$data=date("Y-m-d");

$data=explode("-",$data);

$anos=$data[0]-$data_nasc[0];

if ($data_nasc[1] > $data[1]) {

return $anos-1;

} if ($data_nasc[1] == $data[1]) {

if ($data_nasc[2] <= $data[2]) {

return $anos;



} else {

return $anos-1;



}

} if ($data_nasc[1] < $data[1]) {

return $anos;

}

}
//inicio gera tkt
 
                                   
function fngeratkt($arrayDados)
{
   
   ////////////ofertas
   //=========================
    
    // flag da pergunta se vai ou nao exibir a lista
    //Select busca configuração TKT
    $selconfig="SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =".$arrayDados['cod_empresa']."   and LOG_ATIVO_TKT = 'S'";
    $conf=mysqli_query($arrayDados['connempresa'], $selconfig);
    $rwconfig= mysqli_fetch_assoc($conf);
    //select codigo blacklist
   $blacklist="select * from 	blacklisttkt where COD_BLKLIST='".$rwconfig['COD_BLKLIST']."'";
    $confblacklist=mysqli_query($arrayDados['connempresa'], $blacklist);
    $rsblk= mysqli_fetch_assoc($confblacklist);

     $arraydia=explode(";", $rwconfig['NUM_HISTORICO_TKT']);
     $max_historico_tkt=$arraydia[1];
     $min_historico_tkt=$arraydia[0];
     $qtd_compras_tkt=$rwconfig['QTD_COMPRAS_TKT'];
     $cod_categorBlk=$rsblk['COD_CATEGOR'];
     $cod_empresa=$arrayDados['cod_empresa'];
     $cod_loja=$dadosLogin['idloja'];
     $regrapreco=$rwconfig['DES_PRATPRC'];
     //$DES_VALIDADE=$rwconfig['DES_VALIDADE'] - 1;
     if($rwconfig['DES_VALIDADE']=='')
     {$DES_VALIDADE=0;}else{$DES_VALIDADE=$rwconfig['DES_VALIDADE'] - 1;}
     ///
     $LOG_EMISDIA=$rwconfig['LOG_EMISDIA'];
     $cod_template_tkt=$rwconfig['COD_TEMPLATE_TKT'];
     ////
     $qtd_ofertas_tkt=$rwconfig['QTD_OFERTAS_TKT'];
     $qtd_produtos_tkt=$rwconfig['QTD_PRODUTOS_TKT'];
     $cod_loja=$arrayDados['idloja'];
    if (!$conf || !$confblacklist)
    {
        //$xamls= addslashes("Não existe configuração no TICKET!");
       mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {mysqli_query($arrayDados['connempresa'],$blacklist);} 
        catch (mysqli_sql_exception $e) {$msgsql= $e;} 
        $msg="ofertasTicket : $msgsql";
        $xamls= addslashes($msg);
         return  array('msgerro'=>$xamls);
        
        
    } else {
        
    }

	
	//busca personas do cliente - PERFIL
	$sqlPersonaCli = "SELECT  A.COD_PERSONA 
	FROM PERSONACLASSIFICA A
	WHERE A.COD_CLIENTE = ".$arrayDados['id_cliente']." ";	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonaCli) or die(mysqli_error());	
	$qtdPersonaOn = mysqli_num_rows($arrayQuery);
	$contaLinha = 1;
	if ($qtdPersonaOn > 0) {
		while ($qrPersonaCli = mysqli_fetch_assoc($arrayQuery))
		  {
			if ( $contaLinha !=  (int) ($qtdPersonaOn) ){$addOr = " OR ";} else {$addOr = " ";}
			$personaProduto .=" FIND_IN_SET('".$qrPersonaCli['COD_PERSONA']."',A.COD_PERSONA_TKT) $addOr ";  
			$contaLinha++;
		  }															
		  $personaProduto  = "AND ( ".$personaProduto." )";	
	}else{
		$personaProduto  = " ";		
	}
	

	//Select Habitos de compra
       if($rsblk['COD_CATEGOR']!='')
       {
       $cod_categorBlkand= "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";  
       }    

       $sqlhabitos="SELECT  DISTINCT  C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
                   FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
                   WHERE A.COD_CLIENTE = ".$arrayDados['id_cliente']." AND
                   A.COD_VENDA=B.COD_VENDA AND
                   B.COD_PRODUTO=C.COD_PRODUTO AND
                   C.COD_EMPRESA=$cod_empresa  AND
                   A.DAT_CADASTR >= ADDDATE( NOW(), INTERVAL - $max_historico_tkt DAY) AND
                   A.DAT_CADASTR <= ADDDATE( NOW(), INTERVAL - $min_historico_tkt DAY) 
                   $cod_categorBlkand
                   ORDER BY rand(DES_PRODUTO) LIMIT $qtd_compras_tkt";

       $habitosexec=mysqli_query($arrayDados['connempresa'],$sqlhabitos);

       if (!$habitosexec)
       {
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],$xamls);
          
            $habitos[]= array('msgerro'=>'Cliente que nao for cadastrado não gera habito de compra!');
     
       } else {
           //verifica se tem itens na lista de produtos
           if( mysqli_num_rows($habitosexec) == 0){ 
                   $msghab='Não há Habito de compras!';
                    $habitos[]=  array('msgerro'=>$msghab);
     
            }
           // exibi itens na lista de ws    
           while ($rwhabitos= mysqli_fetch_assoc($habitosexec))
           {
               $cod_habito.=$rwhabitos['COD_PRODUTO'].','; 
               $habitos[]=array('codigoexterno'=>$rwhabitos['COD_EXTERNO'],
                                'codigointerno'=>$rwhabitos['COD_PRODUTO'],
                                'descricao'=>$rwhabitos['DES_PRODUTO']); 
           }
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'HABITO DE COMPRAS OK');

       }

//=========================================FIM DO HABITO DE COMPRAS

//ofertasTicket 
		
    $sqltkt="SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
               where  A.COD_EMPRESA = $cod_empresa AND
                  A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                  A.COD_PRODUTO = C.COD_PRODUTO AND										   
                   A.LOG_ATIVOTK = 'S' AND
                   A.LOG_PRODTKT = 'S' AND
		   A.LOG_OFERTAS = 'N' AND
                  ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                  ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                  ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                  (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))
				  $personaProduto
                  ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_produtos_tkt"; 
    
    $tktexec=mysqli_query($arrayDados['connempresa'], $sqltkt);
	
     
    if (!$tktexec)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {mysqli_query($arrayDados['connempresa'],$sqltkt);} 
        catch (mysqli_sql_exception $e) {$msgsql= $e;} 
        $msg="ofertasTicket : $msgsql";
        $xamls= addslashes($msg);
         $ofertasTicket[]= array('msgerro'=>$xamls);
    } else {
        //verifica se tem itens na lista de produtos
        if( mysqli_num_rows($tktexec) == 0){ 
            $msgtkt='Não há Produtos no ticket!';
              $ofertasTicket[]=array('msgerro'=>$msgtkt); 
        } else {
         // exibi itens na lista de ws    
            while ($rwtkt= mysqli_fetch_assoc($tktexec))
            {
              IF($rwtkt['DES_IMAGEM']!="")
              {
               $IMG="http://img.bunker.mk/media/clientes/$cod_empresa/produtos/".$rwtkt['DES_IMAGEM']."";   
              }   
                $cod_tkt.=$rwtkt['COD_PRODUTO'].',';
                $ofertasTicket[]=array( 'codigoexterno'=>$rwtkt['COD_EXTERNO'],
                                        'codigointerno'=>$rwtkt['COD_PRODUTO'],
                                        'descricao'=>$rwtkt['NOM_PRODTKT'],
                                        'preco'=>fnFormatvalor($rwtkt['VAL_PRODTKT']),
                                        'valorcomdesconto'=>fnFormatvalor($rwtkt['VAL_PROMTKT']),
                                        'desconto'=>'0.00',
                                        'imagem'=> $IMG
                                ); 
            }
          // fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'OFERTASTICKET OK......');

        }  

    }

    //================================================FIM DAS OFERTAS DO TKT
    //ofertas destaque
 // todoso * Ajustar DE
 // ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
 //(A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))===========================================================
 
//ajustar para  ((A.DAT_INIPTKT <= NOW()) AND (A.DAT_FIMPTKT >= NOW()) )   
    $sqldestaque="SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
                   where  A.COD_EMPRESA = $cod_empresa AND
                      A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                      A.COD_PRODUTO = C.COD_PRODUTO AND										   
                      A.LOG_ATIVOTK = 'S' AND					  
                      A.LOG_OFERTAS = 'S' AND 
                      ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                      ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                      ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                      (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))
					  $personaProduto					  
                      ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_ofertas_tkt"; 
    $descexec=mysqli_query($arrayDados['connempresa'], $sqldestaque);
    if($cod_empresa=='62')
    {
     $testesql="INSERT INTO `db_host1`.`log_teste` (`SQL_TESTE`) VALUES ('". addslashes($sqldestaque)."');";
      mysqli_query($arrayDados['connempresa'], $testesql);
    }
        if (!$descexec)
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
            try {mysqli_query($arrayDados['connempresa'],$sqldestaque);} 
            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
            $msg="ofertas destaque: $msgsql";
            $xamls= addslashes($msg);
           $ofertapromocao[]= array('msgerro'=>$xamls); 
        } else {
            //verifica se tem itens na lista de produtos
            if( mysqli_num_rows($descexec) == 0)
            { 
                    $msgP='Não há produtos em promoção!';
                    $ofertapromocao[]=array('msgerro'=>$msgP); 
            }else{
                 // exibi itens na lista de ws    
                while ($rwdesc= mysqli_fetch_assoc($descexec))
                {
                  IF($rwdesc['DES_IMAGEM']!="")
                  {
                   $IMG="http://img.bunker.mk/media/clientes/$cod_empresa/produtos/".$rwdesc['DES_IMAGEM']."";   
                  }  
                  $cod_oferta.=$rwdesc['COD_PRODUTO'].',';
                    $ofertapromocao[]=array('codigoexterno'=>$rwdesc['COD_EXTERNO'],
                                            'codigointerno'=>$rwdesc['COD_PRODUTO'],
                                            'descricao'=>$rwdesc['NOM_PRODTKT'],
                                            'preco'=>fnFormatvalor($rwdesc['VAL_PRODTKT']),
                                            'valorcomdesconto'=>fnFormatvalor($rwdesc['VAL_PROMTKT']),
                                            'imagem'=> $IMG
                                             ); 
                }
           }
    }
            
    //===================================FIM ofertas destaque  
    



//se cod cliente = vazio passa zero pra nao dar erro no insert
    if($arrayDados['id_cliente']==''){$cod_client=0;}else{$cod_client=$arrayDados['id_cliente'];}
    if($arrayDados['idmaquina']=='?' || $arrayDados['idmaquina']==''){$idmaquina=0;}else{$idmaquina=$arrayDados['idmaquina'];}
    //=================================================================================================
   /////////ARRAY PARA GRAVA TKT
    $lojas=fnconsultaLoja($arrayDados['connadm'],$arrayDados['connempresa'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['cod_empresa']);
  
   $todosProdutos = substr($cod_oferta.$cod_tkt.$cod_habito,0,-1);
    $sql1 = "CALL SP_ALTERA_TICKET (
				0, 
				'".$cod_client."', 
				'".$arrayDados['cod_empresa']."', 
				'".$lojas[0]['COD_UNIVEND']."', 
				'".$lojas[0]['COD_MAQUINA']."', 
				'".$arrayDados['cod_user']."', 
				'".$todosProdutos."', 
				'CAD'    
				) ";

  $ROWsql= mysqli_query($arrayDados['connempresa'],$sql1);
  $arrayretorno= mysqli_fetch_assoc($ROWsql);    
  mysqli_free_result($arrayretorno);
  mysqli_next_result($arrayDados['connempresa']);
   
    
      $ofertapromocao1= addslashes(str_replace(array("\n",""),array(""," "), serialize($ofertapromocao)));
       $ofertasTicket1= addslashes(str_replace(array("\n",""),array(""," "), serialize($ofertasTicket)));
       $habitos1= addslashes(str_replace(array("\n",""),array(""," "), serialize($habitos)));
   
    
    if($LOG_EMISDIA=="S"){
            $dtdevolucao = "'".date('Y-m-d', strtotime("+$DES_VALIDADE days"))."',";
           //pegar sempre o ultimo tkt pelo codempresa e codcliente
          $sql="SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* 
                from ticket_dados where COD_CLIENTE=$cod_client 
                 and COD_EMPRESA=".$arrayDados['cod_empresa']." 
                 and LOG_EMISDIA='S' and DAT_VALIDADE >= '".date('Y-m-d')."'   
                ORDER by COD_GERAL DESC limit 1;";
          $misdiatkt=mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'],$sql));
          //SE DATA DA VALIDADE ULTRAPASSAR INSERIR UM NOVO
                if($misdiatkt['COD_GERAL']=='')
                {
                  $insert="INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(".$arrayretorno['COD_TICKET'].",'".$ofertapromocao1."','".$ofertasTicket1."','".$habitos1."', ".$arrayDados['cod_empresa'].",".$cod_client.",".$cod_loja.", $dtdevolucao'".$LOG_EMISDIA."' )";
                  mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));
                   
                  //depois da validade terminar busco de novo     
                   $sql="SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and COD_EMPRESA=".$arrayDados['cod_empresa']." and LOG_EMISDIA='S' and DAT_VALIDADE >= '".date('Y-m-d')."' ORDER by COD_GERAL DESC limit 1;";
                   $misdiatkt=mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'],$sql));  
                }    
          //===============================================================
          //retorno da array      
          if(date('Y-m-d') <= $misdiatkt['DAT_VALIDADE'])
          {
              if($rwconfig['LOG_LISTAWS']=='S')
              { 
                $acao2= array('produtoHabito'=> unserialize($misdiatkt['DES_HABITOS']),
                              'produtoTicket'=>unserialize($misdiatkt['DES_TICKET']), 
                              'produtoPromocao'=>unserialize($misdiatkt['DES_PROMOCAO']),
                              );    
              } 
          } 
          //====================================================================
    //se a emissão nao for diaria      
    }else{
        $dtdevolucao='NULL,';
        $insert="INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(".$arrayretorno['COD_TICKET'].",'".$ofertapromocao1."','".$ofertasTicket1."','".$habitos1."', ".$arrayDados['cod_empresa'].",".$cod_client.",".$cod_loja.", $dtdevolucao'".$LOG_EMISDIA."' )";
        mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));
        
        
        
        //depois da validade terminar busco de novo     
        $sql="SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and  LOG_EMISDIA='N' and COD_EMPRESA=".$arrayDados['cod_empresa']." ORDER by COD_GERAL DESC limit 1;";
        $misdiatkt=mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'],$sql));  
            
                $acao2= array('produtoHabito'=> unserialize($misdiatkt['DES_HABITOS']),
                              'produtoTicket'=>unserialize($misdiatkt['DES_TICKET']), 
                              'produtoPromocao'=>unserialize($misdiatkt['DES_PROMOCAO']),
                               );
           
        
        }
    //===================================================== 

 
//FIM DO IF DA FLAG ATIVA OU DESATIVA  
  
 return $acao2; 
  
   } 
//=====fim gera tkt
//gera tkt lista 
function fngeratktlista($arrayDados)
{
   
   ////////////ofertas
   //=========================
    
    // flag da pergunta se vai ou nao exibir a lista
    //Select busca configuração TKT
    $selconfig="SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =".$arrayDados['cod_empresa']."   and LOG_ATIVO_TKT = 'S'";
    $conf=mysqli_query($arrayDados['connempresa'], $selconfig);
    $rwconfig= mysqli_fetch_assoc($conf);
    //select codigo blacklist
   $blacklist="select * from 	blacklisttkt where COD_BLKLIST='".$rwconfig['COD_BLKLIST']."'";
    $confblacklist=mysqli_query($arrayDados['connempresa'], $blacklist);
    $rsblk= mysqli_fetch_assoc($confblacklist);

     $arraydia=explode(";", $rwconfig['NUM_HISTORICO_TKT']);
     $max_historico_tkt=$arraydia[1];
     $min_historico_tkt=$arraydia[0];
     $qtd_compras_tkt=$rwconfig['QTD_COMPRAS_TKT'];
     $cod_categorBlk=$rsblk['COD_CATEGOR'];
     $cod_empresa=$arrayDados['cod_empresa'];
     $cod_loja=$dadosLogin['idloja'];
     $regrapreco=$rwconfig['DES_PRATPRC'];
     //$DES_VALIDADE=$rwconfig['DES_VALIDADE'] - 1;
     if($rwconfig['DES_VALIDADE']=='')
     {$DES_VALIDADE=0;}else{$DES_VALIDADE=$rwconfig['DES_VALIDADE'] - 1;}
     ///
     $LOG_EMISDIA=$rwconfig['LOG_EMISDIA'];
     $cod_template_tkt=$rwconfig['COD_TEMPLATE_TKT'];
     ////
     $qtd_ofertas_tkt=$rwconfig['QTD_OFERTAS_TKT'];
     $qtd_produtos_tkt=$rwconfig['QTD_PRODUTOS_TKT'];
     $cod_loja=$arrayDados['idloja'];
    if (!$conf || !$confblacklist)
    {
        //$xamls= addslashes("Não existe configuração no TICKET!");
       mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {mysqli_query($arrayDados['connempresa'],$blacklist);} 
        catch (mysqli_sql_exception $e) {$msgsql= $e;} 
        $msg="ofertasTicket : $msgsql";
        $xamls= addslashes($msg);
         return  array('msgerro'=>$xamls);
        
        
    } else {
        
    }

	
	//busca personas do cliente - PERFIL
	$sqlPersonaCli = "SELECT  A.COD_PERSONA 
	FROM PERSONACLASSIFICA A
	WHERE A.COD_CLIENTE = ".$arrayDados['id_cliente']." ";	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonaCli) or die(mysqli_error());	
	$qtdPersonaOn = mysqli_num_rows($arrayQuery);
	$contaLinha = 1;
	if ($qtdPersonaOn > 0) {
		while ($qrPersonaCli = mysqli_fetch_assoc($arrayQuery))
		  {
			if ( $contaLinha !=  (int) ($qtdPersonaOn) ){$addOr = " OR ";} else {$addOr = " ";}
			$personaProduto .=" FIND_IN_SET('".$qrPersonaCli['COD_PERSONA']."',A.COD_PERSONA_TKT) $addOr ";  
			$contaLinha++;
		  }															
		  $personaProduto  = "AND ( ".$personaProduto." )";	
	}else{
		$personaProduto  = " ";		
	}
	

	//Select Habitos de compra
       if($rsblk['COD_CATEGOR']!='')
       {
       $cod_categorBlkand= "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";  
       }    

       $sqlhabitos="SELECT  DISTINCT  C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
                   FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
                   WHERE A.COD_CLIENTE = ".$arrayDados['id_cliente']." AND
                   A.COD_VENDA=B.COD_VENDA AND
                   B.COD_PRODUTO=C.COD_PRODUTO AND
                   C.COD_EMPRESA=$cod_empresa  AND
                   A.DAT_CADASTR >= ADDDATE( NOW(), INTERVAL - $max_historico_tkt DAY) AND
                   A.DAT_CADASTR <= ADDDATE( NOW(), INTERVAL - $min_historico_tkt DAY) 
                   $cod_categorBlkand
                   ORDER BY rand(DES_PRODUTO) LIMIT $qtd_compras_tkt";

       $habitosexec=mysqli_query($arrayDados['connempresa'],$sqlhabitos);

       if (!$habitosexec)
       {
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],$xamls);
          
            $habitos[]= array('msgerro'=>'Cliente que nao for cadastrado não gera habito de compra!');
     
       } else {
           //verifica se tem itens na lista de produtos
           if( mysqli_num_rows($habitosexec) == 0){ 
                   $msghab='Não há Habito de compras!';
                    $habitos[]=  array('msgerro'=>$msghab);
     
            }
           // exibi itens na lista de ws    
           while ($rwhabitos= mysqli_fetch_assoc($habitosexec))
           {
               $cod_habito.=$rwhabitos['COD_PRODUTO'].','; 
               $habitos[]=array('codigoexterno'=>$rwhabitos['COD_EXTERNO'],
                                'codigointerno'=>$rwhabitos['COD_PRODUTO'],
                                'descricao'=>$rwhabitos['DES_PRODUTO']); 
           }
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'HABITO DE COMPRAS OK');

       }

//=========================================FIM DO HABITO DE COMPRAS

//ofertasTicket 
		
    $sqltkt="SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
               where  A.COD_EMPRESA = $cod_empresa AND
                  A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                  A.COD_PRODUTO = C.COD_PRODUTO AND										   
                   A.LOG_ATIVOTK = 'S' AND
                   A.LOG_PRODTKT = 'S' AND
				   A.LOG_OFERTAS = 'N' AND
                  ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                  ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                  ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                  (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))
				  $personaProduto
                  ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_produtos_tkt"; 
    
    $tktexec=mysqli_query($arrayDados['connempresa'], $sqltkt);
	
      //$testesql="INSERT INTO `db_ultrafarma`.`log_teste` (`SQL_TESTE`) VALUES ('". addslashes($sqltkt)."');";
      //mysqli_query($arrayDados['connempresa'], $testesql);
    if (!$tktexec)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {mysqli_query($arrayDados['connempresa'],$sqltkt);} 
        catch (mysqli_sql_exception $e) {$msgsql= $e;} 
        $msg="ofertasTicket : $msgsql";
        $xamls= addslashes($msg);
         $ofertasTicket[]= array('msgerro'=>$xamls);
    } else {
        //verifica se tem itens na lista de produtos
        if( mysqli_num_rows($tktexec) == 0){ 
            $msgtkt='Não há Produtos no ticket!';
              $ofertasTicket[]=array('msgerro'=>$msgtkt); 
        } else {
         // exibi itens na lista de ws    
            while ($rwtkt= mysqli_fetch_assoc($tktexec))
            {
              IF($rwtkt['DES_IMAGEM']!="")
              {
               $IMG="http://img.bunker.mk/media/clientes/$cod_empresa/produtos/".$rwtkt['DES_IMAGEM']."";   
              }   
                $cod_tkt.=$rwtkt['COD_PRODUTO'].',';
                $ofertasTicket[]=array( 'codigoexterno'=>$rwtkt['COD_EXTERNO'],
                                        'codigointerno'=>$rwtkt['COD_PRODUTO'],
                                        'descricao'=>$rwtkt['NOM_PRODTKT'],
                                        'preco'=>fnFormatvalor($rwtkt['VAL_PRODTKT']),
                                        'valorcomdesconto'=>fnFormatvalor($rwtkt['VAL_PROMTKT']),
                                        'desconto'=>'0.00',
                                        'imagem'=> $IMG
                                ); 
            }
          // fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'OFERTASTICKET OK......');

        }  

    }

    //================================================FIM DAS OFERTAS DO TKT
    //ofertas destaque
 
    $sqldestaque="SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
                   where  A.COD_EMPRESA = $cod_empresa AND
                      A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                      A.COD_PRODUTO = C.COD_PRODUTO AND										   
                      A.LOG_ATIVOTK = 'S' AND					  
                      A.LOG_OFERTAS = 'S' AND 
                      ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                      ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                      ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                      (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))
					  $personaProduto					  
                      ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_ofertas_tkt"; 
    $descexec=mysqli_query($arrayDados['connempresa'], $sqldestaque);

        if (!$descexec)
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
            try {mysqli_query($arrayDados['connempresa'],$sqldestaque);} 
            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
            $msg="ofertas destaque: $msgsql";
            $xamls= addslashes($msg);
           $ofertapromocao[]= array('msgerro'=>$xamls); 
        } else {
            //verifica se tem itens na lista de produtos
            if( mysqli_num_rows($descexec) == 0)
            { 
                    $msgP='Não há produtos em promoção!';
                    $ofertapromocao[]=array('msgerro'=>$msgP); 
            }else{
                 // exibi itens na lista de ws    
                while ($rwdesc= mysqli_fetch_assoc($descexec))
                {
                  IF($rwdesc['DES_IMAGEM']!="")
                  {
                   $IMG="http://img.bunker.mk/media/clientes/$cod_empresa/produtos/".$rwdesc['DES_IMAGEM']."";   
                  }  
                  $cod_oferta.=$rwdesc['COD_PRODUTO'].',';
                    $ofertapromocao[]=array('codigoexterno'=>$rwdesc['COD_EXTERNO'],
                                            'codigointerno'=>$rwdesc['COD_PRODUTO'],
                                            'descricao'=>$rwdesc['NOM_PRODTKT'],
                                            'preco'=>fnFormatvalor($rwdesc['VAL_PRODTKT']),
                                            'valorcomdesconto'=>fnFormatvalor($rwdesc['VAL_PROMTKT']),
                                            'imagem'=> $IMG
                                             ); 
                }
           }
    }
            
    //===================================FIM ofertas destaque  
    



//se cod cliente = vazio passa zero pra nao dar erro no insert
    if($arrayDados['id_cliente']==''){$cod_client=0;}else{$cod_client=$arrayDados['id_cliente'];}
    if($arrayDados['idmaquina']=='?' || $arrayDados['idmaquina']==''){$idmaquina=0;}else{$idmaquina=$arrayDados['idmaquina'];}
    //=================================================================================================
   /////////ARRAY PARA GRAVA TKT
    $lojas=fnconsultaLoja($arrayDados['connadm'],$arrayDados['connempresa'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['cod_empresa']);
  
   $todosProdutos = substr($cod_oferta.$cod_tkt.$cod_habito,0,-1);
    $sql1 = "CALL SP_ALTERA_TICKET (
				0, 
				'".$cod_client."', 
				'".$arrayDados['cod_empresa']."', 
				'".$lojas[0]['COD_UNIVEND']."', 
				'".$lojas[0]['COD_MAQUINA']."', 
				'".$arrayDados['cod_user']."', 
				'".$todosProdutos."', 
				'CAD'    
				) ";

  $ROWsql= mysqli_query($arrayDados['connempresa'],$sql1);
  $arrayretorno= mysqli_fetch_assoc($ROWsql);    
  mysqli_free_result($arrayretorno);
  mysqli_next_result($arrayDados['connempresa']);
   
    
      $ofertapromocao1= addslashes(str_replace(array("\n",""),array(""," "), serialize($ofertapromocao)));
       $ofertasTicket1= addslashes(str_replace(array("\n",""),array(""," "), serialize($ofertasTicket)));
       $habitos1= addslashes(str_replace(array("\n",""),array(""," "), serialize($habitos)));
   
    
    if($LOG_EMISDIA=="S"){
            $dtdevolucao = "'".date('Y-m-d', strtotime("+$DES_VALIDADE days"))."',";
           //pegar sempre o ultimo tkt pelo codempresa e codcliente
          $sql="SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* 
                from ticket_dados where COD_CLIENTE=$cod_client 
                 and COD_EMPRESA=".$arrayDados['cod_empresa']." 
                 and LOG_EMISDIA='S' and DAT_VALIDADE >= '".date('Y-m-d')."'   
                ORDER by COD_GERAL DESC limit 1;";
          $misdiatkt=mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'],$sql));
          //SE DATA DA VALIDADE ULTRAPASSAR INSERIR UM NOVO
                if($misdiatkt['COD_GERAL']=='')
                {
                  $insert="INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(".$arrayretorno['COD_TICKET'].",'".$ofertapromocao1."','".$ofertasTicket1."','".$habitos1."', ".$arrayDados['cod_empresa'].",".$cod_client.",".$cod_loja.", $dtdevolucao'".$LOG_EMISDIA."' )";
                  mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));
                   
                  //depois da validade terminar busco de novo     
                   $sql="SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and COD_EMPRESA=".$arrayDados['cod_empresa']." and LOG_EMISDIA='S' and DAT_VALIDADE >= '".date('Y-m-d')."' ORDER by COD_GERAL DESC limit 1;";
                   $misdiatkt=mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'],$sql));  
                }    
          //===============================================================
          //retorno da array      
          if(date('Y-m-d') <= $misdiatkt['DAT_VALIDADE'])
          {
              if($rwconfig['LOG_LISTAWS']=='S')
              { 
                $acao2= array('produtoHabito'=> unserialize($misdiatkt['DES_HABITOS']),
                              'produtoTicket'=>unserialize($misdiatkt['DES_TICKET']), 
                              'produtoPromocao'=>unserialize($misdiatkt['DES_PROMOCAO']),
                              );    
              } 
          } 
          //====================================================================
    //se a emissão nao for diaria      
    }else{
        $dtdevolucao='NULL,';
        $insert="INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(".$arrayretorno['COD_TICKET'].",'".$ofertapromocao1."','".$ofertasTicket1."','".$habitos1."', ".$arrayDados['cod_empresa'].",".$cod_client.",".$cod_loja.", $dtdevolucao'".$LOG_EMISDIA."' )";
        mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));
        
        
        
        //depois da validade terminar busco de novo     
        $sql="SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and  LOG_EMISDIA='N' and COD_EMPRESA=".$arrayDados['cod_empresa']." ORDER by COD_GERAL DESC limit 1;";
        $misdiatkt=mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'],$sql));  
            
                $acao2= array('produtoHabito'=> unserialize($misdiatkt['DES_HABITOS']),
                              'produtoTicket'=>unserialize($misdiatkt['DES_TICKET']), 
                              'produtoPromocao'=>unserialize($misdiatkt['DES_PROMOCAO']),
                               );
           
        
        }
    //===================================================== 

 
//FIM DO IF DA FLAG ATIVA OU DESATIVA  
  
 return $acao2; 
  
   } 
//=== fim lista   
   
   
function limitarTexto($texto, $limite){
    //$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
   $texto= mb_strimwidth($texto, 0,$limite, "...");
    return $texto;
}
function fnVerificasaldo($arrayvalorres)
{
 // =H22/G22*100;
  $percentual=($arrayvalorres['vl_venda']*$arrayvalorres['PCT_MAXRESG'])/100;
  return $percentual;
  
    
}
function fnLimpaCampo($campo,$adicionaBarras = false){
    $campo = preg_replace("/(from|alter table|select|drop|insert|delete|update|where|drop table|show tables|#|\*|--|\\\\)/i","",$campo);
    $campo = trim($campo);//limpa espaços vazio
    $campo = strip_tags($campo);//tira tags html e php
    if($adicionaBarras || !get_magic_quotes_gpc())
    $campo = addslashes($campo);
    return $campo;
}
