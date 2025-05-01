<?php
//---------CLASS CONN
/*
ini_set('mysqli.allow_persistent', 1);
ini_set('mysqli.max_links',1);
ini_set('mysqli.max_persistent',2);
ini_set('mysqli.allow_local_infile',1);
ini_set('mysqli.reconnect',1);
ini_set('max_execution_time', 3);
ini_set('mysqli.connect_timeout', 3);
ini_set('mysqli.cache_size', 16000); // '2000'
ini_set('mysqli.cache_type', 0);
ini_set('default_socket_timeout', 30);
 * */
 
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
                              $this->DB,'3320'
                               );
        
        }
public function connUser () {
        return mysqli_connect($this->server,  
                              $this->usuario,  
                              $this->senha,  
                              $this->DB,'3320'
                );
        
   }
   
public function connGERADOR () {
        return mysqli_connect($this->server,  
                              $this->usuario,  
                              $this->senha,  
                              $this->DB,'3320'
                              );
        
   }
   public function connREL () {
        return mysqli_connect($this->server,  
                              $this->usuario,  
                              $this->senha,  
                              $this->DB,'3320'
                              );
        
   }
   public function connDUQUE () {
        return mysqli_connect($this->server,  
                              $this->usuario,  
                              $this->senha,  
                              $this->DB,'3320'
                              );        
   } 
   
}

//--------FIM CLASS
   
//--------CONN 
 //base de dados Geral    
$connAdm = new BD('144.217.255.136','duque','H+du29.5','webtools');

//$grduque = new BD('142.44.212.134','rededuque','H+rede29.5','grduque');
//$connDUQUE = new BD('142.44.212.134','rededuque','H+rede29.5','portalduque');
//conexão para selecionar o db 


if (!$connAdm->connAdm()) {
    echo die('Connect Error: ' . mysqli_connect_error());
}

function connTemptkt($conn,$parametro,$retornoBDNAME)
            {
              
               
                $codEmpr = "select * from tab_database
                            INNER JOIN empresas ON tab_database.COD_EMPRESA=empresas.COD_EMPRESA
                            where tab_database.COD_EMPRESA='".$parametro."'";
                            $codEmprR = mysqli_query($conn,$codEmpr) or die(mysqli_error());
                            $codEmpreretorno= mysqli_fetch_assoc($codEmprR);
                            $senha=fnDecode($codEmpreretorno['SENHADB']);
                             
                            if($retornoBDNAME=='true'){ 
                             return $codEmpreretorno['NOM_DATABASE'];
                            }else{
                                return mysqli_connect($codEmpreretorno['IP'], $codEmpreretorno['USUARIODB'],$senha, $codEmpreretorno['NOM_DATABASE'],'3320');
                            
                            }
                           
            }   

if(@$_SESSION["usuario"]=='')
    {
     
    
    }
   else{
	  
       //base de daods do cliente
      $connUser = new BD($_SESSION["servidor"],$_SESSION["userBD"],$_SESSION["SenhaBD"],$_SESSION["BD"]);
	 
			 if($_SESSION["tkt"] ==1)
			 {  
				if (!$connUser->connUser()) {
				  echo die('Connect Error: ' . mysqli_connect_error());
				}
			  }else{}	
           
   }
//Base de dados temporaria
           function connTemp($parametro,$retornoBDNAME)
            {
              
                $connAdm= new BD('144.217.255.136','duque','H+du29.5','webtools');
                $codEmpr = "select * from tab_database
                            INNER JOIN empresas ON tab_database.COD_EMPRESA=empresas.COD_EMPRESA
                            where tab_database.COD_EMPRESA='".$parametro."'";
                            $codEmprR = mysqli_query($connAdm->connAdm(),$codEmpr);
                            $codEmpreretorno= mysqli_fetch_assoc($codEmprR);
                            $senha=fnDecode($codEmpreretorno['SENHADB']);
                             
                            if($retornoBDNAME=='true'){ 
                             return $codEmpreretorno['NOM_DATABASE'];
                            }else{
                                return mysqli_connect($codEmpreretorno['IP'], $codEmpreretorno['USUARIODB'],$senha, $codEmpreretorno['NOM_DATABASE'],'3320');
                            }
                           
            }   
  //---------FIM CONN
?>