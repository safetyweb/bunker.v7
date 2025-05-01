<?php

$rest=array('ConsultaSlado'=>array('consulta_cliente'=>array('cpf'=>'01734200014',
							                         'DIAS_EXPIRA'=>'30'		
                                ),
			'dadoslogin'=>array(
							'login'=>'ws.rededuque',
							'senha'=>'marka',
							'idloja'=>'669',
							'idcliente'=>'19',
							'codvendedor'=>'',
							'nomevendedor'=>'lzt',
							'idmaquina'=>'BLUNOTFS020175'
			    )						
            ));
echo '<pre>';
print_r($rest);
echo '</pre>';

$teste=json_encode($rest,JSON_PRETTY_PRINT);
echo $teste;	

?>