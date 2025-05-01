<?php
function fnScan($arquivo)
{
    //testando o antivirus
    $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
    if(socket_connect($socket, '/var/run/clamav/clamd-socket')) {  
        socket_send($socket, "PING", strlen($file) + 5, 0);
        socket_recv($socket, $PING, 20000, 0);
        socket_close($socket);
       if(rtrim(trim($PING))=='PONG')
       {
            chmod($arquivo['CAMINHO_TMP'], 00644);
            $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
            if(socket_connect($socket, '/var/run/clamav/clamd-socket')) {
                $result = "";
                socket_send($socket, "SCAN ".$arquivo['CAMINHO_TMP'], strlen($arquivo['CAMINHO_TMP']) + 5, 0);
                socket_recv($socket, $result, 20000, 0);
                $quebradelina=explode(':', $result);

                if(rtrim(trim($quebradelina['1']))=='OK')
                {
                   return array('RESULTADO'=>0,
                                'MSG'=>'N');
                }else{
                   return array('RESULTADO'=>1,
                                'MSG'=>$quebradelina['1']);
                   unlink($arquivo['CAMINHO_TMP']);
                 }    
            }
            socket_close($socket);
       } 
    }
}
$arquivo=array('CAMINHO_TMP'=>$_FILES['file']['tmp_name'],
               'CONADM'=>$connAdm->connAdm()
               );
$testear=fnScan($arquivo);
echo'<pre>';
print_r($testear);
echo'<pre>';
?>

<form method="post" enctype="multipart/form-data"><input type="file" name="file"><input type="submit">


</form>
