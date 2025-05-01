<?php
include '../_system/_functionsMain.php';
$contemporaria=connTemp(405,'');
$medi="SELECT * FROM import.crocodilo cro
INNER JOIN db_host25.clientes c ON c.num_cgcecpf=cro.cpf AND c.COD_EMPRESA='405' AND c.LOG_TERMO='N'";
$medrs=mysqli_query(connTemp(405,''), $medi);

while ($rw= mysqli_fetch_assoc($medrs))
{
    sleep(1);
    $mysqli="DELETE FROM CLIENTES_TERMOS WHERE cod_empresa=405 AND COD_ACEITE=".$rw['COD_CLIENTE'];
    mysqli_query(connTemp(405,''), $mysqli);
 
  $xml= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
    <soapenv:Header/>
    <soapenv:Body>
        <fid:AtualizaCadastro>
            <fase>fase1</fase>
            <cliente>
                <nome>'.$rw['NOM_CLIENTE'].'</nome>
                <cartao>'.$rw['NUM_CGCECPF'].'</cartao>
                <cpf>'.$rw['NUM_CGCECPF'].'</cpf>
                <sexo>'.$rw['COD_SEXOPES'].'</sexo>
                <cnpj>'.$rw['NUM_CGCECPF'].'</cnpj>
                <datanascimento>'.$rw['DAT_NASCIME'].'</datanascimento>
                <telcelular>'.$rw['dddcelularsms'].''.$rw['celularsms'].'</telcelular>
                <tipocliente>'.$rw['TIP_CLIENTE'].'</tipocliente>
                <tokencadastro>'.$rw['tokensms'].'</tokencadastro>
                <canal>1</canal>
            </cliente>
            <dadosLogin xmlns="">
                <login xmlns:ns11="Linker20">ws.medfacil</login>
                <senha xmlns:ns12="Linker20">MedFac@Mk</senha>
                <idloja xmlns:ns13="Linker20">'.$rw['COD_UNIVEND'].'</idloja>
                <idmaquina xmlns:ns14="Linker20">850001311001</idmaquina>
                <idcliente xmlns:ns15="Linker20">405</idcliente>
            </dadosLogin>
        </fid:AtualizaCadastro>
    </soapenv:Body>
</soapenv:Envelope>';
  
  
              $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xml,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
        }else{ 
                 echo $response;
               }  

}        