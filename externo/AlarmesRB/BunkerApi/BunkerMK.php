<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * @autho Franklin de Paula Gonçalves <franklinpgoncalves@gmail.com>
 */

class BunkerMK {
    
    private $oSoapClient = NULL;
    private $pdoDbControle = NULL;
    
    public function __construct() {
        
        $this->pdoDbControle = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USUARIO, DB_SENHA); 
        
        if (!class_exists("SoapClient")) {
           $msg = "A classe SOAP não está disponível no PHP, veja a configuração.";
           throw new Exception\RuntimeException($msg);
           die($msg);
        }

        ini_set("soap.wsdl_cache_enabled", "0");

        // A seguir você devera informar a URL do webservice.
        switch (USAR_WEBSERVICE){
            case 'soap':
                $url_webservice = URL_WEBSERVICE_SOAP;
            break;
            case 'ws':
                $url_webservice = URL_WEBSERVICE_WS;
            break;
        }
        $this->oSoapClient = new SoapClient($url_webservice, array('trace' => 1));

        $aOptions = array (
              "start_debug"=> "1",
              "debug_port"=> "10000",
              "debug_host"=> "localhost",
              "debug_stop"=> "1");

        foreach($aOptions as $key => $val) {
               $this->oSoapClient->__setCookie($key,$val);
        }
    }
    
    /*
     * Enviar informações para o WebService
     * @access public 
     * @param Array $dados
     * @param String $metodo
     * @return void 
     */
    
    public function enviar ($dados, $metodo){
        switch(USAR_WEBSERVICE){
            case 'soap':
                $dadoslogin = array('dadosLogin' => array('login' => LOGIN,
                        'senha' => SENHA
                      ,'idloja' => IDLOJA
                      ,'idmaquina' => IDMAQUINA
                      ,'idcliente' => IDCLIENTE
                      ,'codvendedor' => CODVENDEDOR
                      ,'nomevendedor' =>NOMEVENDEDOR ));
            break;
            default:
                $dadoslogin = array('dadosLogin' => array('login' => LOGIN,
                        'senha' => SENHA
                      ,'idloja' => IDLOJA
                      ,'idmaquina' => IDMAQUINA
                      ,'idcliente' => IDCLIENTE));
            break;
        }
        
    
        $parametros = array_merge($dados, $dadoslogin);
        $retorno = $this->oSoapClient->$metodo($parametros);
        return $retorno;
    }
    
    /*
     * Busca todas Funções (Métodos) disponíves no WebSerivce
     * @access public 
     * @return void 
     */
    public function getFuncoes (){
        $this->debug($this->oSoapClient->__getFunctions());
    }
    
    /*
     * Imprime o XML do último de envio para o webService
     * @access public 
     * @return Xml 
     */
    public function getXmlEnvio (){
        $this->echoXML($this->oSoapClient->__getLastRequest()); 
    }
    
    /*
     * Imprime o XML do último de retorno recuperado do webService
     * @access public 
     * @return Xml 
     */
    public function getXmlRetorno (){
        $this->echoXML($this->oSoapClient->__getLastResponse());
    }
    
    /*
     * Imprime o XML do último envio para o webService
     * @access public 
     * @param String $nome
     * @return Array or Print 
     */
    public function getEstrutura($nome = 'todas', $return = false){
        $types = $this->oSoapClient->__getTypes();
        foreach ($types as $type) {
            $tipo = explode(' ', $type);
            if($nome == $tipo[1] || $nome == 'todas'){
                if ($return){
                    $retorno = array();
                    $estrutura = array();
                    $campos = explode(';', $type);
                    
                    $ultima_posicao = count( $campos)-1;
                    $count = 0;
                    
                    foreach ($campos as $campo){
                        if($count < $ultima_posicao){
                            $campo = explode('string ', $campo);
                            $estrutura[$campo[1]] = NULL;
                        }
                        $count ++;
                    }
                    $retorno[$nome] = $estrutura;
                    return $retorno;
                }else{
                    echo '<pre>';
                    $type = preg_replace(
                        array('/(\w+) ([a-zA-Z0-9]+)/', '/\n /'),
                        array('<font color="green">${1}</font> <font color="blue">${2}</font>', "\n\t"),
                        $type
                    );
                    echo $type;
                    echo "\n\n";
                }
            }
            
        }
    }
    
    /*
     * Função para debug de código, exibe informaçções na tela e para a execução
     * @access public 
     * @return void 
     */
    public function debug ($variavel){
        echo '<pre>';
        var_export($variavel);
        die();
    }
    
    /*
     * Função que imprime XML na tela no forma text/xml
     * @access public 
     * @return Xml 
     */
    public function echoXML ($variavel){
        header("Content-type: text/xml");
        echo $variavel;
        die();
    }
    
    /*
     * Função que pega último ID de venda importado
     * @access public 
     * @return varchar 
     */
    public function getUltimoIDVenda (){
        $sql = "SELECT IDULTIMAVENDA FROM controle_integracao_vendas WHERE IDLOJA = '".IDLOJA."' AND IDCLIENTE = '".IDCLIENTE."'";
        foreach ($this->pdoDbControle->query($sql) as $row) {
             $ID_ULTIMA_VENDA = $row['IDULTIMAVENDA'];
        }
        return $ID_ULTIMA_VENDA;
    }
    
    public function getUltimoIDVenda2 (){
        $sql = "SELECT IDULTIMAVENDA2 FROM controle_integracao_vendas WHERE IDLOJA = '".IDLOJA."' AND IDCLIENTE = '".IDCLIENTE."'";
        foreach ($this->pdoDbControle->query($sql) as $row) {
             $ID_ULTIMA_VENDA = $row['IDULTIMAVENDA2'];
        }
        return $ID_ULTIMA_VENDA;
    }
    
    /*
     * Função que atualiza último ID de venda importado
     * @access public 
     * @return varchar 
     */
    public function updateUltimoIDVenda ($ID){
        $sql = "UPDATE controle_integracao_vendas SET IDULTIMAVENDA='".$ID."' WHERE IDLOJA = '".IDLOJA."' AND IDCLIENTE = '".IDCLIENTE."' ";
        $this->pdoDbControle->query($sql);
        return true;
    }
    
    public function updateUltimoIDVenda2 ($ID){
        $sql = "UPDATE controle_integracao_vendas SET IDULTIMAVENDA2='".$ID."' WHERE IDLOJA = '".IDLOJA."' AND IDCLIENTE = '".IDCLIENTE."' ";
        $this->pdoDbControle->query($sql);
        return true;
    }
    
}
