<?php 
require('Email.php');
//echo fnDebug('true');

	/*
	$mailin = new Mailin("https://api.sendinblue.com/v2.0","1c3P0JnNqhz2ZtpM");
	
	echo '<pre>';
	var_dump($mailin->get_account());
	echo '</pre>';
	*/
	
	//echo 'teste';
	$email = new Email();
	
	//$email->addFrom("riicardolara@gmail.com");
	//$email->addTo(array("ricardolara.ti@gmail.com" => "Ricardo"));
	//$email->setSubject("E-mail TESTE");
	//$email->setHtml("corpo e-mail");
	

	//echo $email->teste();
	
	

	 //$email->getCampanha(5);

        echo '<pre>';
        //print_r($email->getCampanhas());
        echo  '</pre>';
		
?>

<script>
window.sendinblue=window.sendinblue||[];window.sendinblue.methods=["identify","init","group","track","page","trackLink"];window.sendinblue.factory=function(e){return function(){var t=Array.prototype.slice.call(arguments);t.unshift(e);window.sendinblue.push(t);return window.sendinblue}};for(var i=0;i<window.sendinblue.methods.length;i++){var key=window.sendinblue.methods[i];window.sendinblue[key]=window.sendinblue.factory(key)}window.sendinblue.load=function(){if(document.getElementById("sendinblue-js"))return;var e=document.createElement("script");e.type="text/javascript";e.id="sendinblue-js";e.async=true;e.src=("https:"===document.location.protocol?"https://":"http://")+"s.sib.im/automation.js";var t=document.getElementsByTagName("script")[0];t.parentNode.insertBefore(e,t)};window.sendinblue.SNIPPET_VERSION="1.0";window.sendinblue.load();window.sendinblue.client_key="72ey0is6tc6j3w6swdxbz";window.sendinblue.page();

</script>


<!--

require('Mailin.php');

class Email {

    public $mailin;
    private $apiKey = "3Lm6AHVKB7hgsSEx";
	//private $apiKey = "1c3P0JnNqhz2ZtpM";
	
    private $to = array();
	private $toTemplate;
    private $from = array();
    private $subject;
    private $html;
    private $text;
    private $cc = array();
    private $bcc = array();
    private $replyTo = array();
    private $attachment = array();

    function __construct() {
        $this->mailin = new Mailin("https://api.sendinblue.com/v2.0", $this->apiKey);
    }

    public function addTo($to) {
        $this->to = array_merge($this->to, $to);
    }
	
    public function setToTemplate($toTemplate) {
        $this->toTemplate = $toTemplate;
    }	

    public function getTo() {
        return $this->to;
    }

    public function addFrom($from) {
        $this->from = array_merge($this->from, array($from));
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setHtml($html) {
        $this->html = $html;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function addCc($cc) {
        $this->cc = array_merge($this->cc, $cc);
    }

    public function addBcc($bcc) {
        $this->bcc = array_merge($this->bcc, $bcc);
    }

    public function addReplyTo($replyTo) {
        $this->replyTo = array_merge($this->replyTo, $replyTo);
    }

    public function addAttachment($attachment) {
        $this->attachment = array_merge($this->attachment, $attachment);
    }

    public function teste() {
        echo '<pre>';
        var_dump($this->mailin->get_account());
        echo '</pre>';
    }

    public function enviarEmail() {
        $data = array("to" => $this->to,
            "from" => $this->from,
            "subject" => $this->subject,
            "html" => $this->html
        );
		
        return var_dump($this->mailin->send_email($data));
    }
	
    public function enviarEmailDeTemplate($idTemplate) {
        $data = array( "id" => $idTemplate,
			"to" => "ricardolara.ti@gmail.com",
			"attr" => array("EXPEDITEUR"=>"Marka","SUBJECT"=> "TESTE")
        );
		
		print_r($data);
		
        return var_dump($this->mailin->send_transactional_template($data));
    }	

    public function getCampanhas() {
        return $this->mailin->get_campaigns_v2();
    }

    public function getCampanha($idCampanha) {
        $data = array("id" => $idCampanha);
        return var_dump($this->mailin->get_campaign_v2($data));
    }

    public function createCampanha($nomeCampanha, $emailFromCampanha, $htmlContent, $listaIdUsuario, $assunto) {
        $data = array(
			"name" => $nomeCampanha,
            "from_email" => $emailFromCampanha,
            "html_content" => $htmlContent,
            "listid" => $listaIdUsuario,
            "subject" => $assunto,
            "send_now" => 1
        );

        return var_dump($this->mailin->create_campaign($data));   
    }
	
	public function getListas(){
		$data = array(
			"page" => 1,
			"page_limit" => 10
		);

		return var_dump($this->mailin->get_lists($data));		
	}
	
	public function criarPasta($nomePasta){
		$data = array("name" => $nomePasta);
	    return $this->mailin->create_folder($data)['data']['id'];
	}
	
	public function getPasta($idPasta){
	    $data = array("id" => $idPasta);
	    return $this->mailin->get_folder($data);		
	}	
	
	public function getPastas(){
	  $data = array( "page" => 1, "page_limit" => 2);
	  return $this->mailin->get_folders($data);
	}
	
	public function criarLista($nomeLista, $idPasta){
		$data = array("list_name" => $nomeLista, "list_parent" => $idPasta);
		return $this->mailin->create_list($data)['data']['id'];		
	}
	
	public function getLista($idLista){
		$data = array("id"=> $idLista);
		return $this->mailin->get_list($data);		
	}	
	
	// Apenas usuários que já estiverem cadastrados na sendinblue
	public function addUsuariosNaLista($idLista, $emailUsuarios){
		$data = array("id" => $idLista, "users" => $emailUsuarios);
		return $this->mailin->add_users_list($data);		
	}	
	
	public function delUsuariosNaLista($idLista, $emailUsuarios){
		$data = array("id" => $idLista, "users" => $emailUsuarios);
		return $this->mailin->delete_users_list($data);		
	}	
	
	public function addUsuario($email){
		$data = array("email" => $email);
		return $this->mailin->create_update_user($data);		
	}	
	
}


 
-->