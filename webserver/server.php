<?php

require_once('lib/nusoap.php'); 


$server = new nusoap_server;

$server->configureWSDL('servidor', 'urn:http://labs.marka.one/webserver/server.php');
$server->soap_defencoding = 'utf-8';
$server->decode_utf8 = false;
$server->wsdl->schemaTargetNamespace = 'urn:http://labs.marka.one/webserver/server.php';


$server->wsdl->addComplexType(
    'FichadeCadastro',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:int'),
        'cpf' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cpf', 'type' => 'xsd:string'),
        'rg' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'rg', 'type' => 'xsd:string'),
        'cnpj'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cnpj', 'type' => 'xsd:string'),
        'nomeportador'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomeportador', 'type' => 'xsd:string'),
        'sexo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'sexo', 'type' => 'xsd:string'),
        'datanascimento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datanascimento', 'type' => 'xsd:string'),
        'estadocivil' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estadocivil', 'type' => 'xsd:string'),
        'telresidencial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telresidencial', 'type' => 'xsd:string'),
        'telcelular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcelular', 'type' => 'xsd:string'),
        'telcomercial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcomercial', 'type' => 'xsd:string'),
        'email' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'email', 'type' => 'xsd:string'),
        'profissao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'profissao', 'type' => 'xsd:string'),
        'clientedesde' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'clientedesde', 'type' => 'xsd:string'),
        'tipocliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tipocliente', 'type' => 'xsd:string'),
        'endereco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'endereco', 'type' => 'xsd:string'),
        'numero' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'numero', 'type' => 'xsd:string'),
        'bairro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bairro', 'type' => 'xsd:string'),
        'complemento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'complemento', 'type' => 'xsd:string'),
        'cidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cidade', 'type' => 'xsd:string'),
        'estado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estado', 'type' => 'xsd:string'),
        'cep' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cep', 'type' => 'xsd:string'),
        'cartaotitular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartaotitular', 'type' => 'xsd:string'),
        'bloqueado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bloqueado', 'type' => 'xsd:string'),
        'motivo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'motivo', 'type' => 'xsd:string'),
        'dataalteracao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dataalteracao', 'type' => 'xsd:string'),
        'adesao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'adesao', 'type' => 'xsd:string'),
        'saldo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldo', 'type' => 'xsd:string'),
        'saldoresgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldoresgate', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'),
        'senha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'senha', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'urlextrato' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urlextrato', 'type' => 'xsd:string'),
        'msgcampanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgcampanha', 'type' => 'xsd:string'),
        'retornodnamais' => array('name' => 'retornodnamais', 'type' => 'xsd:string'),
        'MSG'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'MSG', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'LoginInfo',
    'complexType',
    'struct',
    'sequence',
    '',
         array('login' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'login', 'type' => 'xsd:string'),
               'senha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'senha', 'type' => 'xsd:string'),
               'idloja' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idloja', 'type' => 'xsd:string'),
               'idmaquina' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idmaquina', 'type' => 'xsd:string'),
               'idcliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idcliente', 'type' => 'xsd:string'),
               'codvendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codvendedor', 'type' => 'xsd:string'),
               'nomevendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomevendedor', 'type' => 'xsd:string')
            )
);
//this is the second webservice entry point/function 
$server->register('ConsultaCadastroPorCPF',
			array(
                              'CPF'=>'xsd:string',
                               'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:FichadeCadastro'),  //output
			'urn:http://labs.marka.one/webserver/server.php',   //namespace
			'urn:http://labs.marka.one/webserver/server.php#ConsultaCadastroPorCPF',  //soapaction
			'rpc', //document
			'literal', // literal
			'Busca CPF');  //description



//função que captura os dados da pagina "soap"
 function ConsultaCadastroPorCPF($CPF,$dadoslogin) {
    include 'conexao.php'; 
    $connAdm = new BD('149.56.22.17','adminterno','H+admin29.5','teste_marka');
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $row = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));
   // $total = mysqli_num_rows($connAdm->connAdm(),$sql);
   
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    $buscaCPF='SELECT * FROM clientes where NUM_CGCECPF="'.$CPF.'"';
    
    $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$buscaCPF));
    
  
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
        
                   
                    if($row1['NUM_CGCECPF']!=0){  
                         return array(
                                'nome'=>$row1['NOM_CLIENTE'],
                                'cartao'=>$row1['NUM_CARTAO'],
                                'cpf'=>$row1['NUM_CGCECPF'],
                                'rg'=>$row1['NUM_RGPESSO'],
                                'cnpj'=>'cnpj',
                                'nomeportador'=>'nomeportador',
                                'grupo'=>'',
                                'sexo'=>$row1['COD_SEXOPES'],
                                'datanascimento'=>$row1['DAT_NASCIME'],
                                'estadocivil'=>$row1['COD_ESTACIV'],
                                'telresidencial'=>$row1['NUM_TELEFON'],
                                'telcelular'=>$row1['NUM_CELULAR'],
                                'telcomercial'=>'',
                                'email'=>$row1['LOG_EMAIL'],
                                'profissao'=>$row1['COD_PROFISS'],
                                'clientedesde'=>'',
                                'tipocliente'=>'',
                                'endereco'=>$row1['DES_ENDEREC'],
                                'numero'=>$row1['NUM_ENDEREC'],
                                'bairro'=>$row1['DES_BAIRROC'],
                                'complemento'=>$row1['DES_COMPLEM'],
                                'cidade'=>$row1['NOM_CIDADEC'],
                                'estado'=>$row1['COD_ESTADOF'],
                                'cep'=>$row1['NUM_CEPOZOF'],
                                'cartaotitular'=>'',
                                'bloqueado'=>'',
                                'motivo'=>'',
                                'dataalteracao'=>'',
                                'adesao'=>'',
                                'saldo'=>'',
                                'saldoresgate'=>'',
                                'codatendente'=>'',
                                'senha'=>'',
                                'msgerro'=>'',
                                'urlextrato'=>'',
                                'msgcampanha'=>'',
                                'MSG'=>'OK'

                         );
                    }else{
                     //Consulta DNA+
                         //CONSULTA NA DNA MAIS
                          ini_set('default_socket_timeout', 10);
                            $client = new SoapClient('http://webservices.dnamais.com.br/online/WSIntegracaoDNAOnline.asmx?wsdl',array('trace' => 1));
                            $function = 'RastreamentoPFPrata';
                            $arguments= array('RastreamentoPFPrata' => array('loginUsuario'=>'DN102002',
                                                                             'senhaUsuario'=>'M4KM4RC@2015',
                                                                              'numeroCPF'=>$CPF));
                            $options = array('location' => 'http://webservices.dnamais.com.br/online/WSIntegracaoDNAOnline.asmx');
                             $result = $client->__soapCall($function, $arguments,$options);
                             //count endereço DNA
                            
                            if(count($result->RastreamentoPFPrataResult->Enderecos->Endereco) >=2){
                                $endereco=$result->RastreamentoPFPrataResult->Enderecos->Endereco[0];
                               
                            }else{
                               $endereco=$result->RastreamentoPFPrataResult->Enderecos->Endereco;
                            
                             }
                         //Fim count
                            
                            
				return array(
                                            'nome' => $result->RastreamentoPFPrataResult->DadosCadastrais->Nome,
                                            'cartao'=>'cartao',
                                            'cpf'=>$result->RastreamentoPFPrataResult->DadosCadastrais->CPF,
                                            'rg'=>$result->RastreamentoPFPrataResult->DadosCadastrais->RG,
                                            'cnpj'=>'cnpj',
                                            'nomeportador'=>'nomeportador',
                                            'grupo'=>'',
                                            'sexo'=>$result->RastreamentoPFPrataResult->DadosCadastrais->Sexo,
                                            'datanascimento'=>$result->RastreamentoPFPrataResult->DadosCadastrais->DataNascimento,
                                            'estadocivil'=>$result->RastreamentoPFPrataResult->DadosCadastrais->EstadoCivil,
                                            'telresidencial'=>'',
                                            'telcelular'=>'',
                                            'telcomercial'=>'',
                                            'email'=>$result->RastreamentoPFPrataResult->Emails->Email->Endereco,
                                            'profissao'=>'',
                                            'clientedesde'=>'',
                                            'tipocliente'=>'',
                                            'endereco'=>$endereco->Logradouro,
                                            'numero'=>$endereco->Numero,
                                            'bairro'=>$endereco->Bairro,
                                            'complemento'=>$endereco->Complemento,
                                            'cidade'=>$endereco->Cidade,
                                            'estado'=>$endereco->UF,
                                            'cep'=>$endereco->CEP,
                                            'cartaotitular'=>'',
                                            'bloqueado'=>'',
                                            'motivo'=>'',
                                            'dataalteracao'=>'',
                                            'adesao'=>'',
                                            'saldo'=>'',
                                            'saldoresgate'=>'',
                                            'codatendente'=>'',
                                            'senha'=>'',
                                            'msgerro'=>$result->RastreamentoPFPrataResult->Controle->Mensagem,
                                            'urlextrato'=>'',
                                            'msgcampanha'=>'',
                                            'retornodnamais'=>$result->RastreamentoPFPrataResult->ResponseStatus->Errors->ResponseError->Message
                                       
                                             );     
                                 
                        //Final da consulta
                     
                      

                         }
        }else{
            return array('MSG'=>$row['v_menssagem']);

        }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
    mysqli_free_result(); 
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

$server->service($HTTP_RAW_POST_DATA);

?>