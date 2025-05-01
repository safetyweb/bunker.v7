<?php
//---------CLASS CONN
class BD {
    protected $server;
    protected $usuario;
    protected $senha;
    Public $DB;
    
    
Public function __construct($server,$usuario,$senha,$DB){
	$this-> server = $server;
	$this-> usuario = $usuario;
	$this-> senha = $senha;
	$this-> DB = $DB;
   
}
public function connAdm () {
        return mysqli_connect($this->server,  
                              $this->usuario,  
                              $this->senha,  
                              $this->DB);
        
        }
public function connUser () {
        return mysqli_connect($this->server,  
                              $this->usuario,  
                              $this->senha,  
                              $this->DB);
        
   }
   
}
//--------FIM CLASS
   
//--------CONN 
 //base de dados Geral    



    
 
  //---------FIM CONN
 

//---------LOG DATABASE

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

