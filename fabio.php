<?php
function fnAcentos($string)
{
   // matriz de entrada
    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç');

    // matriz de saída
    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C');

    // devolver a string
    return str_replace($what, $by, $string);
       
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
       <form method="POST" action="fabio.php">
           <textarea name="txtBox" cols="50" rows="10" id="txtBox"><?php echo @$_REQUEST['txtBox']?></textarea>
           <textarea name="txtBox1" cols="50" rows="10" id="txtBox1"><?php echo fnAcentos(@$_REQUEST['txtBox']);?></textarea>
           <input type="submit" name="fomat" value="format">
    </form>
    </body>
</html>  