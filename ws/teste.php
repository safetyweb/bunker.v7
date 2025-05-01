<?php

require_once ("./lib/nusoap.php");


// Create the server instance
$server = new soap_server();

$ns = "http://bunker.mk/ws/teste.php";

// Initialize WSDL support
$server->configureWSDL('MathOperationService', $ns,'','document');

// Register the method to expose
$server->register('add',                // method name
                        array('param1' => 'xsd:string',
                              'param2' => 'xsd:string'
                             ),    // input parameters
                        array('return' => 'xsd:string'),    // output parameters
                        $ns,         						// namespace
                        "$ns#add",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'Add Parameters'            		// documentation
                    );

// Register the method to expose
$server->register('multiply',               // method name
    array('param1' => 'xsd:string',
          'param2' => 'xsd:string'
         ),    	// input parameters
    array('return' => 'xsd:string'),      	// output parameters
    $ns,             						// namespace
    "$ns#multiply",    						// soapaction
    'document',                             // style
    'literal',                            	// use
    'Multiply Parameters'            		// documentation
);
function add($param1, $param2) {	
	return array('return'=>$param1+$param2);
}

function multiply($param1, $param2){	
	return array('return'=>$param1*$param2);
}
// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>