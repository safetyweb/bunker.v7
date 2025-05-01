<?php
include '../_system/_functionsMain.php';
//fnDebug('true');
$contemporaria=connTemp(85,'');
$cliente="SELECT * FROM farmamed1";
$rs=mysqli_query($contemporaria, $cliente);
while ($row = mysqli_fetch_assoc($rs)) 
{   
   sleep(2);
    if($row[data_nasc]=='')
    {
        $datnasc='2000-01-01';
    }else{
        
       $datnasc = date("Y-m-d", strtotime($row[data_nasc]) );
    }    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
         // CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                              <SOAP-ENV:Body>
                              <AtualizaCadastro xmlns=\"Linker20\">
                                  <cliente xmlns=\"\">
                                           <cartao xmlns=\"Linker20\">".fncompletadoc($row[cpf])."</cartao>
                                           <tipocliente>PF</tipocliente>    
                                           <nome xmlns=\"Linker20\">".$row[nome]."</nome>
                                           <cpf xmlns=\"Linker20\">".$row[cpf]."</cpf>
                                           <sexo xmlns=\"Linker20\">".$row[sexo]."</sexo>
                                            <datanascimento xmlns=\"Linker20\">".$datnasc."</datanascimento>
                                           <telcelular xmlns=\"Linker20\">".$row[celular]."</telcelular>
                                    </cliente>
                                  <dadoslogin>
                                        <login>ws.Farmamed</login>
                                        <senha>ws@2017</senha>
                                        <idloja>96252</idloja>
                                        <idmaquina>migracaoF</idmaquina>
                                        <idcliente>85</idcliente>
                                        <codvendedor>??</codvendedor>
                                        <nomevendedor>??</nomevendedor>
                                  </dadoslogin>
                                  </AtualizaCadastro>
                                 </SOAP-ENV:Body>
                               </SOAP-ENV:Envelope>",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
          ),
        ));
  
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
         echo '<br>cpf: '. $row[cpf].'<br> /n/n';
         //  echo $response.'<br>';
        }
       
       
       
 ob_end_flush();
ob_flush();
flush();
}