<?php
// Des_CANAl = 1  mais.cash LINK SMS
// Des_CANAl = 2  mais.cash LINK EMAIL
// Des_CANAl = 3  mais.cash pdv resumido




//verificar o porque quando esta sem a placa não retorno msg 
//tratar isso.
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea
$server->wsdl->addComplexType(
	'validatokenreturn',
	'complexType',
	'struct',
	'sequence',
	'',
	array(
		'token' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'token', 'type' => 'xsd:string'),
		'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string'),
		'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:string')

	)
);
$server->register(
	'validaToken',
	array(
		'tipoGeracao' => 'xsd:string',
		'token' => 'xsd:string',
		'celular' => 'xsd:string',
		'cpf' => 'xsd:string',
		'dadosLogin' => 'tns:LoginInfo'
	),
	array('retornatoken' => 'tns:validatokenreturn'),
	$ns,
	"$ns/validaToken",
	'document',
	'literal',
	'validatoken'
);
function validaToken($tipoGeracao, $token, $celular, $cpf, $dadosLogin)
{
	include_once '../_system/Class_conn.php';
	include_once 'func/function.php';
	$celular = preg_replace("/[^0-9]/", "", $celular);

	$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
	$row = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));

	if (!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS'])) {
		return  array('retornatoken' => array(
			'token' => '0',
			'msgerro' => 'Usuario ou senha Inválido!',
			'coderro' => '5'
		));
		exit();
	}
	$connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);

	//VERIFICA SE A EMPRESA FOI DESABILITADA
	if ($row['LOG_ATIVO'] == 'N') {
		return  array('retornatoken' => array(
			'token' => '0',
			'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
			'coderro' => '6'
		));
		exit();
	}
	//VERIFICA SE O USUARIO FOI DESABILITADA
	if ($row['LOG_ESTATUS'] == 'N') {
		return  array('retornatoken' => array(
			'token' => '0',
			'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
			'coderro' => '44'
		));
		exit();
	}

	/*if(fnlimpaCPF($celular)=='48996243831')
		{	
			$ins="INSERT INTO log_teste (SQL_TESTE, PDV) VALUES ('".addslashes(file_get_contents("php://input"))."', 'qwqw');";
			mysqli_query($connUser->connUser(),$ins);
		}*/
	if ($celular != '') {
		$andcelular = "and NUM_CELULAR='" . fnlimpaCPF($celular) . "'";
	}
	if (fnlimpaCPF($cpf) != 0) {
		$andwherecpf = "and NUM_CGCECPF='" . fnlimpaCPF($cpf) . "'";
	}
	$sqlverificatoken = "SELECT *  FROM geratoken 
													   WHERE COD_EMPRESA= '" . $dadosLogin['idcliente'] . "' AND 
													         COD_EXCLUSA=0	AND 
															 TIP_TOKEN='" . $tipoGeracao . "' and 	
															 DES_TOKEN='" . $token . "'
                                                                                                                         $andcelular
                                                                                                                         $andwherecpf";
	/*if($cpf=='01734200014')
              {
                echo  $sqlverificatoken; 
              }  */
	$rsverificatoken = mysqli_query($connUser->connUser(), $sqlverificatoken);
	if ($temtoken = mysqli_num_rows($rsverificatoken) <= 0) {
		return  array('retornatoken' => array(
			'token' => '0',
			'msgerro' => 'Token inexistente!',
			'coderro' => '97'
		));
		exit();
	}

	while ($rwlogtoken = mysqli_fetch_assoc($rsverificatoken)) {

		/*if($rwlogtoken[LOG_USADO]=='')
				{
					return  array('retornatoken'=>array('token'=>'0',
														'msgerro'=>'Token inexistente!',
														'coderro'=>'97'));
					exit();
				}*/
		$rwlogtoken['NUM_CELULAR'] = preg_replace("/[^0-9]/", "", $rwlogtoken['NUM_CELULAR']);
		if ($rwlogtoken['NUM_CELULAR'] == fnlimpaCPF($celular)) {
			if ($rwlogtoken['LOG_USADO'] == '2') {
				return  array('retornatoken' => array(
					'token' => '0',
					'msgerro' => 'Token ja utilizado',
					'coderro' => '98'
				));
				exit();
			}
		}
		if (fnlimpaCPF($cpf) != '') {
			if ($rwlogtoken['NUM_CGCECPF'] != fnlimpaCPF($cpf)) {

				return  array('retornatoken' => array(
					'token' => '0',
					'msgerro' => 'Token pertence a outro cliente',
					'coderro' => '99'
				));
				exit();
			}
		}
	}

	if ($celular != '') {
		if (fnlimpaCPF($cpf) != 0) {
			$andwherecpf = "and NUM_CGCECPF='" . fnlimpaCPF($cpf) . "'";
		}
		$sqlverificatoken = "SELECT COD_TOKEN FROM geratoken 
														   WHERE COD_EMPRESA= '" . $dadosLogin['idcliente'] . "' AND 
																  COD_EXCLUSA=0	AND 
																 TIP_TOKEN='" . $tipoGeracao . "' and 	
																 DES_TOKEN='" . $token . "' and
																 NUM_CELULAR='" . fnlimpaCPF($celular) . "'
																$andwherecpf
																 ";
		$rsverificatoken = mysqli_query($connUser->connUser(), $sqlverificatoken);
		if ($temcelular = mysqli_num_rows($rsverificatoken) <= 0) {
			return  array('retornatoken' => array(
				'token' => '0',
				'msgerro' => 'Token pertence a outro cliente',
				'coderro' => '99'
			));
			exit();
		}
	}
	return  array('retornatoken' => array(
		'token' => $token,
		'msgerro' => 'OK',
		'coderro' => '39'
	));
	exit();
}
