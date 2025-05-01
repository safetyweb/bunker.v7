<?php

require('Mailin.php');

class Email {

    public $mailin;
    public $apiKey = "ZTLCyDwvJfkGb9nS";

    function __construct() {
        $this->mailin = new Mailin("https://api.sendinblue.com/v2.0", $this->apiKey);
    }
	
    public function setApiKey($valor) {
       $this->apiKey = $valor;
	   $this->mailin = new Mailin("https://api.sendinblue.com/v2.0", $this->apiKey);
    }
}
?>

