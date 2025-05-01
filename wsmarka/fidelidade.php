<?php
require_once('lib/nusoap.php'); 

$server = new soap_server();
$ns  = 'fidelidade'; //or any test url
$ns1  = 'fidelidade';
//$server->debug_flag=false;
$server->configureWSDL('fidelidade', $ns1, false, 'document');
$server->soap_defencoding = 'UTF-8';
$server->encode_utf8 = true;
$server->decode_utf8 = false;
$server->wsdl->schemaTargetNamespace = $ns1;
//$server->xml_encoding = "utf-8";



$server->wsdl->addComplexType(
    'LoginInfo',
    'complexType',
    'struct',
    'all',
    '',
         array('login' => array('name' => 'login', 'type' => 'xsd:string'),
               'senha' => array('name' => 'senha', 'type' => 'xsd:string'),
               'idloja' => array('name' => 'idloja', 'type' => 'xsd:string'),
               'idmaquina' => array('name' => 'idmaquina', 'type' => 'xsd:string'),
               'idcliente' => array('name' => 'idcliente', 'type' => 'xsd:string'),
               'codvendedor' => array('name' => 'codvendedor', 'type' => 'xsd:string'),
               'nomevendedor' => array('name' => 'nomevendedor', 'type' => 'xsd:string')
            )
);

$server->wsdl->addComplexType(
    'acao',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'acoesfidelizacao'=> array('name' => 'acoesfidelizacao', 'type' => 'xsd:string'),
        'acao_A_cadastro' => array('name' => 'acao_A_cadastro', 'type' => 'tns:acao_A_cadastro'),
        'acao_B_Ticket_de_Ofertas' => array('name' => 'acao_B_Ticket_de_Ofertas', 'type' => 'tns:acao_B_Ticket_de_Ofertas'),
        'acao_C_campanha' => array('name' => 'acao_C_campanha', 'type' => 'tns:acao_C_campanha'),
        'acao_D_mensagem' => array('name' => 'acao_D_mensagem', 'type' => 'tns:acao_D_mensagem'),
        'acao_E_ListadeOfertas' => array('name' => 'listaoferta', 'type' => 'tns:listaoferta'),
        'acao_F_desconto' => array('name' => 'desconto', 'type' => 'tns:desconto'),
        'acao_G_Cupomdesconto' => array('name' => 'cupomdesconto', 'type' => 'tns:cupomdesconto'),
        'acao_H_saldo' => array('name' => 'acao_H_saldo', 'type' => 'tns:acao_H_saldo'),
        'retornoGenerico' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'retornoGenerico', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
        )
 );       
        
$server->wsdl->addComplexType(
    'acao_A_cadastro',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
        'cpf' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cpf', 'type' => 'xsd:string'),
        'sexo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'sexo', 'type' => 'xsd:string'),
        'rg' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'rg', 'type' => 'xsd:string'),
        'cnpj' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cnpj', 'type' => 'xsd:string'),
        'nomeportador' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomeportador', 'type' => 'xsd:string'),
        'grupo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'grupo', 'type' => 'xsd:string'),
        'datanascimento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datanascimento', 'type' => 'xsd:string'),
        'estadocivil' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estadocivil', 'type' => 'xsd:string'),
        'telresidencial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telresidencial', 'type' => 'xsd:string'),
        'telcomercial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcomercial', 'type' => 'xsd:string'),
        'telcelular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcelular', 'type' => 'xsd:string'),
        'email' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'email', 'type' => 'xsd:string'),
        'profissao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'profissao', 'type' => 'xsd:string'),
        'clientedesde' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'clientedesde', 'type' => 'xsd:string'),
        'tipocliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tipocliente', 'type' => 'xsd:string'),
        'endereco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'endereco', 'type' => 'xsd:string'),
        'numero' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'numero', 'type' => 'xsd:string'),
        'bairro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bairro', 'type' => 'xsd:string'),
        'complemento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'complemento', 'type' => 'xsd:string'),
        'cidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cidade', 'type' => 'xsd:string'),
        'estado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estado', 'type' => 'xsd:string'),
        'cep' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cep', 'type' => 'xsd:string'),
        'cartaotitular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartaotitular', 'type' => 'xsd:string'),
        'bloqueado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bloqueado', 'type' => 'xsd:string'),
        'motivo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'motivo', 'type' => 'xsd:string'),
        'dataalteracao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dataalteracao', 'type' => 'xsd:string'),
        'adesao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'adesao', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'),
        'senha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'senha', 'type' => 'xsd:string'),
        'fontedados' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'fontedados', 'type' => 'xsd:string'),
        'retornoGenerico' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'retornoGenerico', 'type' => 'xsd:string'),
        'urltotem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltotem', 'type' => 'xsd:string'),
		'participafidelidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'participafidelidade', 'type' => 'xsd:string'),
		'conformidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'conformidade', 'type' => 'xsd:string'),
		'tokenvalido' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tokenvalido', 'type' => 'xsd:string'),
		'msgconformidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgconformidade', 'type' => 'xsd:string'),
		'tokencadastro'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tokencadastro', 'type' => 'xsd:string'),
		'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
        )
);
$server->wsdl->addComplexType(
    'acao_cadastro',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
        'cpf' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cpf', 'type' => 'xsd:string'),
        'sexo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'sexo', 'type' => 'xsd:string'),
        'rg' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'rg', 'type' => 'xsd:string'),
        'cnpj' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cnpj', 'type' => 'xsd:string'),
        'nomeportador' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomeportador', 'type' => 'xsd:string'),
        'grupo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'grupo', 'type' => 'xsd:string'),
        'datanascimento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datanascimento', 'type' => 'xsd:string'),
        'estadocivil' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estadocivil', 'type' => 'xsd:string'),
        'telresidencial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telresidencial', 'type' => 'xsd:string'),
        'telcomercial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcomercial', 'type' => 'xsd:string'),
        'telcelular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcelular', 'type' => 'xsd:string'),
        'email' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'email', 'type' => 'xsd:string'),
        'profissao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'profissao', 'type' => 'xsd:string'),
        'clientedesde' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'clientedesde', 'type' => 'xsd:string'),
        'tipocliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tipocliente', 'type' => 'xsd:string'),
        'endereco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'endereco', 'type' => 'xsd:string'),
        'numero' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'numero', 'type' => 'xsd:string'),
        'bairro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bairro', 'type' => 'xsd:string'),
        'complemento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'complemento', 'type' => 'xsd:string'),
        'cidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cidade', 'type' => 'xsd:string'),
        'estado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estado', 'type' => 'xsd:string'),
        'cep' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cep', 'type' => 'xsd:string'),
        'cartaotitular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartaotitular', 'type' => 'xsd:string'),
        'bloqueado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bloqueado', 'type' => 'xsd:string'),
        'motivo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'motivo', 'type' => 'xsd:string'),
        'dataalteracao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dataalteracao', 'type' => 'xsd:string'),
        'adesao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'adesao', 'type' => 'xsd:string'),
        'codIndicador' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codIndicador', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'),
        'tokencadastro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tokencadastro', 'type' => 'xsd:string'),
        'funcionario'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'funcionario', 'type' => 'xsd:string'),
        'senha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'senha', 'type' => 'xsd:string'),
        'fontedados' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'fontedados', 'type' => 'xsd:string'),
	'canal' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'canal', 'type' => 'xsd:string'),
        'retornoGenerico' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'retornoGenerico', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
        )
);

$server->wsdl->addComplexType(
    'acao_B_Ticket_de_Ofertas',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'url_ticketdeofertas' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url_ticketdeofertas', 'type' => 'xsd:string'),
        'urltotem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltotem', 'type' => 'xsd:string'),
        'regrapreco'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'regrapreco', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'ofertasTicket' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'produtoTicket', 'type' => 'tns:produtoTicket'),
        'ofertasHabito' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'produtoHabito', 'type' => 'tns:produtoHabito'),
        'ofertasPromocao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'produtoPromocao', 'type' => 'tns:produtoPromocao')
        )
);//===============================================================================================================
//=======================================================
//Ofertas em destaque retorno
$server->wsdl->addComplexType(
    'produtoPromocao',
    'complexType',
    'struct',
    'sequence',
    '',
    array('produtoPromocao' => array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'RetornoProdutosOfertas', 'type' => 'tns:RetornoProdutosOfertas'))
);
$server->wsdl->addComplexType(
    'RetornoProdutosOfertas',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoexterno'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoexterno', 'type' => 'xsd:integer'),
        'codigointerno' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cdigointerno', 'type' => 'xsd:integer'),
        'ean' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ean', 'type' => 'xsd:integer'),
        'descricao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descricao', 'type' => 'xsd:string'),
        'preco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'preco', 'type' => 'xsd:string'),
        'valorcomdesconto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorcomdesconto', 'type' => 'xsd:string'),
        'desconto'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'desconto', 'type' => 'xsd:string'),
        'descontopctgeral'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontopctgeral', 'type' => 'xsd:string'),
        'imagem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'imagem', 'type' => 'xsd:string'),
        'msgpromocional' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgpromocional', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        
        )
);
//============================
//HABITO DE COMPRAS retorno
$server->wsdl->addComplexType(
    'produtoHabito',
    'complexType',
    'struct',
    'sequence',
    '',
    array('produtoHabito' => array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'RetornoHabitos', 'type' => 'tns:RetornoHabitos'))
);
$server->wsdl->addComplexType(
    'RetornoHabitos',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoexterno'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoexterno', 'type' => 'xsd:integer'),
        'codigointerno' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigointerno', 'type' => 'xsd:integer'),
        'ean' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ean', 'type' => 'xsd:integer'),
        'descricao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descricao', 'type' => 'xsd:string'),
        'preco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'preco', 'type' => 'xsd:string'),
        'valorcomdesconto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorcomdesconto', 'type' => 'xsd:string'),
        'imagem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'imagem', 'type' => 'xsd:string'),
        'msgpromocional' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgpromocional', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
         
        )
);
//========================
//Retorno tkt
$server->wsdl->addComplexType(
    'produtoTicket',
    'complexType',
    'struct',
    'sequence',
    '',
    array('produtoTicket' => array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'RetornoTKT', 'type' => 'tns:RetornoTKT'))
);
$server->wsdl->addComplexType(
    'RetornoTKT',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoexterno'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoexterno', 'type' => 'xsd:integer'),
        'codigointerno' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cdigointerno', 'type' => 'xsd:integer'),
        'ean' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ean', 'type' => 'xsd:integer'),
        'descricao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descricao', 'type' => 'xsd:string'),
        'preco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'preco', 'type' => 'xsd:string'),
        'precopromocional' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'precopromocional', 'type' => 'xsd:string'),
        'valorcomdesconto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorcomdesconto', 'type' => 'xsd:string'),
        'desconto'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'desconto', 'type' => 'xsd:string'),
        'descontopctgeral'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontopctgeral', 'type' => 'xsd:string'),
        'imagem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'imagem', 'type' => 'xsd:string'),
        'msgpromocional' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgpromocional', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        
        )
);

//==============================================================



//=============================================================================================================

$server->wsdl->addComplexType(
    'acao_C_campanha',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'url_campanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url_campanha', 'type' => 'xsd:string'),
        'urltotem' => array('name' => 'urltotem', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url_ticketdeofertas', 'type' => 'xsd:string')
        
        )
);
$server->wsdl->addComplexType(
    'acao_D_mensagem',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'txtmensagem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'txtmensagem', 'type' => 'xsd:string'),
        'urltotem' => array('name' => 'urltotem', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url_ticketdeofertas', 'type' => 'xsd:string')
        
        )
);

$server->wsdl->addComplexType(
    'listaoferta',
    'complexType',
    'struct',
    'sequence',
    '',
     array('urltotem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltotem', 'type' => 'xsd:string'), 
           'listaoferta' => array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'acao_E_ListadeOfertas', 'type' => 'tns:acao_E_ListadeOfertas'))
);
 
$server->wsdl->addComplexType(
    'acao_E_ListadeOfertas',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoexterno'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoexterno', 'type' => 'xsd:integer'),
        'codigointerno' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigointerno', 'type' => 'xsd:integer'),
        'ean' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ean', 'type' => 'xsd:integer'),
        'descricao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descricao', 'type' => 'xsd:string'),
        'preco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'preco', 'type' => 'xsd:string'),
        'valorcomdesconto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorcomdesconto', 'type' => 'xsd:string'),
        'desconto'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'desconto', 'type' => 'xsd:string'),
        'imagem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'imagem', 'type' => 'xsd:string'),
        'msgpromocional' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgpromocional', 'type' => 'xsd:string'),
        'regrapreco'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'regrapreco', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
         )
);

$server->wsdl->addComplexType(
    'desconto',
    'complexType',
    'struct',
    'sequence',
    '',
   
         array('urltotem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltotem', 'type' => 'xsd:string'), 
              'desconto' =>array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'acao_F_desconto', 'type' => 'tns:acao_F_desconto'))
);
$server->wsdl->addComplexType(
    'acao_F_desconto',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
	    'cod_interno'=> array('name' => 'cod_interno', 'type' => 'xsd:string'),
        'cod_externo'=> array('name' => 'cod_externo', 'type' => 'xsd:string'),
        'descontosobrepercentual' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontosobrepercentual', 'type' => 'xsd:string'),
        'descontosobrevalor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontosobrevalor', 'type' => 'xsd:string'),
        'regrapreco'=> array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'regrapreco', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
          )
);

$server->wsdl->addComplexType(
    'cupomdesconto',
    'complexType',
    'struct',
    'sequence',
    '',
   
         array('urltotem' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltotem', 'type' => 'xsd:string'), 
              'cupomdesconto' =>array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'acao_G_Cupomdesconto', 'type' => 'tns:acao_G_Cupomdesconto'))
);
$server->wsdl->addComplexType(
    'acao_G_Cupomdesconto',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'cod_interno'=> array('name' => 'cod_interno', 'type' => 'xsd:string'),
        'cod_externo'=> array('name' => 'cod_externo', 'type' => 'xsd:string'),
        'numcupom' => array('name' => 'numcupom', 'type' => 'xsd:string'),
        'descontosobrepercentual' => array('name' => 'descontosobrepercentual', 'type' => 'xsd:string'),
        'descontosobrevalor' => array('name' => 'descontosobrevalor', 'type' => 'xsd:string'),
        'coderro' => array('name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('name' => 'msgerro', 'type' => 'xsd:string')       
          )
);
$server->wsdl->addComplexType(
    'acao_H_saldo',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'saldodisponivel' => array('name' => 'numcupom', 'type' => 'xsd:string'),
        'saldototal' => array('name' => 'descontosobrepercentual', 'type' => 'xsd:string'),
        'creditovenda'=>array('name' => 'creditovenda', 'type' => 'xsd:string'),
        'vantagemacumulada' => array('name' => 'descontosobrevalor', 'type' => 'xsd:string'),
        'urltotem' => array('name' => 'urltotem', 'type' => 'xsd:string'),
        'urlsaldo'=>array('name' => 'urlsaldo', 'type' => 'xsd:string'),
        'coderro' => array('name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('name' => 'msgerro', 'type' => 'xsd:string')       
        )
);
$server->wsdl->addComplexType(
    'acao_estorno',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
    
        'id_vendapdv' => array('name' => 'id_vendapdv', 'type' => 'xsd:string'),
        'cartao' => array('name' => 'acao_A_cadastro', 'type' => 'xsd:string'),
        'itens' => array('name' => 'itens', 'type' => 'tns:itens')
      
        )
 ); 
//itens do produto
$server->wsdl->addComplexType(
    'venda',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'id_vendapdv' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'id_vendapdv', 'type' => 'xsd:string'),
        'datahora' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datahora', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
        'valortotalbruto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valortotalbruto', 'type' => 'xsd:string'),
        'descontototalvalor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontototalvalor', 'type' => 'xsd:string'),
        'valortotalliquido' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valortotalliquido', 'type' => 'xsd:string'),
        'valor_resgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valor_resgate', 'type' => 'xsd:string'),
        'cupomfiscal' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cupomfiscal', 'type' => 'xsd:string'),
        'cupomdesconto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cupomdesconto', 'type' => 'xsd:string'),
        'formapagamento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'formapagamento', 'type' => 'xsd:string'),
        'indicador' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'indicador', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'),
        'codvendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codvendedor', 'type' => 'xsd:string'),
        'idcliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idcliente', 'type' => 'xsd:string'),
        'pontostotal' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'pontostotal', 'type' => 'xsd:string'),
        'pontuar'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'pontuar', 'type' => 'xsd:string'),        
        'canalvendas'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'canalvendas', 'type' => 'xsd:string'), 
	    'token_resgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'token_resgate', 'type' => 'xsd:string'),
        'itens' => array('name' => 'itens', 'type' => 'tns:itens')
        
        )
);



$server->wsdl->addComplexType(
    'itens',
    'complexType',
    'struct',
    'sequence',
    '',
    array('vendaitem' => array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'vendaitem', 'type' => 'tns:vendaitem'))
);
$server->wsdl->addComplexType(
    'vendaitem',
    'complexType',
    'struct',
    'all',
    '',
    array(  'id_item' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'id_item', 'type' => 'xsd:integer'),
            'produto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'produto', 'type' => 'xsd:string'),
            'codigoproduto'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigoproduto', 'type' => 'xsd:integer'),
            'quantidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'quantidade', 'type' => 'xsd:string'),
            'valorbruto' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorbruto', 'type' => 'xsd:string'),
            'descontovalor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descontovalor', 'type' => 'xsd:string'),
            'valorliquido' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'valorliquido', 'type' => 'xsd:string'),
            'ean' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ean', 'type' => 'xsd:integer'),
            'estoque'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estoque', 'type' => 'xsd:integer'),        
            'pontuar'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'pontuar', 'type' => 'xsd:string'),        
            'atributo1' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo 1', 'type' => 'xsd:string'),
            'atributo2' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo2', 'type' => 'xsd:string'),
            'atributo3' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo3', 'type' => 'xsd:string'),
            'atributo4' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo4', 'type' => 'xsd:string'),
            'atributo5' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo5', 'type' => 'xsd:string'),
            'atributo6' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo6', 'type' => 'xsd:string'),
            'atributo7' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo7', 'type' => 'xsd:string'),
            'atributo8' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo8', 'type' => 'xsd:string'),
            'atributo9' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo9', 'type' => 'xsd:string'),
            'atributo10' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo10', 'type' => 'xsd:string'),
            'atributo11' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo11', 'type' => 'xsd:string'),
            'atributo12' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo12', 'type' => 'xsd:string'),
            'atributo13' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'atributo13', 'type' => 'xsd:string'),
            'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'), 
            'envioGenerico' => array('name' => 'envioGenerico', 'type' => 'tns:envioGenerico')
            )   
   
);

$server->wsdl->addComplexType(
    'envioGenerico',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'param1' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'param1', 'type' => 'xsd:string'),
        'param2' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'param2', 'type' => 'xsd:string'),
        'param3'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'param3', 'type' => 'xsd:string'),
        'param4' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'param4', 'type' => 'xsd:string'),
        'param5' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'param5', 'type' => 'xsd:string')
      )   
);

////////////////////////

require_once 'BuscaConsumidor.php';
require_once 'AtualizaCadastro.php';
require_once 'OfertaProduto.php';
require_once 'InsereVenda.php';
require_once 'InserePreVenda.php';
require_once 'EstornaVenda.php';
require_once 'EstornaVendaParcial.php';
require_once 'ListaProfissoes.php';
require_once 'ListaOcorrencia.php';
require_once 'CamposObrigatorios.php';
require_once 'listaEstadoCivil.php';
require_once 'CadastrarProduto.php';
require_once 'verificavenda.php';
require_once 'token.php';
require 'ValidaDescontos.php';
require_once 'AtualizaVenda.php';
require 'IndicacaoProduto.php';
require 'token_resgate.php';
require 'ValidaDescontosItem.php';
require 'InsereVendedor.php';
require 'Geratoken.php';
require 'validaToken.php';
require 'Listacadastros.php';
//require 'geracontrole.php';
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

$server->service($HTTP_RAW_POST_DATA);
?>