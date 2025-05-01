<?php 
    include 'header.php'; 
    $tituloPagina = "Termos LGPD";
    include "navegacao.php";
    $hashLocal = mt_rand();
    $msgRetorno = "";

    $key_url = $_GET['key'];

    if(is_numeric(fnDecode($key_url))){
      $key_url = base64_encode($key_url);
    }

    $cod_cliente = fnLimpaCampoZero($_GET["idc"]);
    $cod_empresa = fnDecode(base64_decode($key_url));

    if( $_SERVER['REQUEST_METHOD']=='POST' ){

        if($cod_cliente != 0 && $cod_cliente != ""){

            $sql_termos="SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' ORDER BY NUM_ORDENAC;";
            $rwtermos=mysqli_query(conntemp($cod_empresa,''),$sql_termos);
            while($rsternos=mysqli_fetch_assoc($rwtermos))
            {
                        //inert into 
                $valuestermos.="($cod_empresa,
                $cod_cliente,
                '".$rsternos[COD_BLOCO]."',
                '".$rsternos[COD_TERMO]."'
            ),";
            $COD_TERMO.= $rsternos[COD_TERMO].',';              
        }
        //bulking insert
        $valuestermos=rtrim($valuestermos,',');

        $sqlDelTermos = "DELETE FROM CLIENTES_TERMOS
                         WHERE COD_EMPRESA = $cod_empresa
                         AND COD_CLIENTE = $cod_cliente";
        mysqli_query(conntemp($cod_empresa,''),$sqlDelTermos);

        $instermos="INSERT INTO CLIENTES_TERMOS(
            COD_EMPRESA,
            COD_CLIENTE,
            COD_BLOCO,
            COD_TERMOS
        ) VALUES $valuestermos";
        $rwtermos=mysqli_query(conntemp($cod_empresa,''),$instermos);   
        //alterar os aceites de comunicação do cliente.

        // echo "CHEGOU AQUI";
        // exit();


        $COD_TERMO=rtrim($COD_TERMO,',');
        $tipoaceite="SELECT 
        SUM(email) email,
        SUM(sms) sms,
        SUM(WhatsApp) WhatsApp,
        SUM(Push) Push,
        SUM(Ofertas) Ofertas,
        SUM(Telemarketing) Telemarketing
        FROM (SELECT                            
           case when COD_TIPO = 2 then '1' ELSE '0' END email,
           case when COD_TIPO = 3 then '1' ELSE '0' END sms,
           case when COD_TIPO = 4 then '1' ELSE '0' END WhatsApp,
           case when COD_TIPO = 5 then '1' ELSE '0' END Push, 
           case when COD_TIPO = 6 then '1' ELSE '0' END Ofertas,
           case when COD_TIPO = 7 then '1' ELSE '0' END Telemarketing
           FROM    termos_empresa
           WHERE COD_EMPRESA = $cod_empresa
           AND LOG_ATIVO='S'
           AND COD_TIPO IN (2,3,4,5,6,7)      
           AND COD_TERMO IN($COD_TERMO))tmptermos";
           $rwtipoaceite=mysqli_fetch_assoc(mysqli_query(conntemp($cod_empresa,''),$tipoaceite));
           if($rwtipoaceite[email]=='1'){$aceite_email='S';}else{$aceite_email='N';}
           if($rwtipoaceite[sms]=='1'){$aceite_sms='S';}else{$aceite_sms='N';}
           if($rwtipoaceite[WhatsApp]=='1'){$aceite_WhatsApp='S';}else{$aceite_WhatsApp='N';}
           if($rwtipoaceite[Push]=='1'){$aceite_Push='S';}else{$aceite_Push='N';}
           if($rwtipoaceite[Ofertas]=='1'){$aceite_Ofertas='S';}else{$aceite_Ofertas='N';}
           if($rwtipoaceite[Telemarketing]=='1'){$aceite_Telemarketing='S';}else{$aceite_Telemarketing='N';}


                    //update para o cliente em conformidade
           $sqlaceites="UPDATE clientes SET 
                                       LOG_TERMO='S',
                                       LOG_EMAIL='".$aceite_email."',
                                       LOG_SMS='".$aceite_sms."',
                                       LOG_TELEMARK='".$aceite_Telemarketing."',
                                       LOG_WHATSAPP='".$aceite_WhatsApp."',
                                       LOG_PUSH='".$aceite_Push."',
                                       LOG_OFERTAS='".$aceite_Ofertas."',
                                       DAT_ALTERAC = NOW()       
                                       WHERE 
                                       COD_CLIENTE= $cod_cliente and 
                                       COD_EMPRESA = $cod_empresa"; 

           mysqli_query(conntemp($cod_empresa,''), $sqlaceites);

           ?>
                <script>
                    window.location.replace("https://<?=$_SERVER['SERVER_NAME']?>/app/novoMenu.do?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>");
                </script>
           <?php

           exit();

        }else{

            echo "else";
            exit();

        }

    }
?>  

    <style>

        #blocker
        {
            display:none; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: .8;
            background-color: #fff;
            z-index: 1000;
        }
            
        #blocker div
        {
            position: absolute;
            top: 30%;
            left: 48%;
            width: 200px;
            height: 2em;
            margin: -1em 0 0 -2.5em;
            color: #000;
            font-weight: bold;
        }

    </style>
    <div id="blocker">
        <div style="text-align: center;"><img src="img/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
    </div>
    
    <div class="container">


        <form id="formulario" class="form-signin" method="POST" action="aceiteLGPD.do?key=<?=fnEncode($cod_empresa)?>&idc=<?=$cod_cliente?>">

            <div class="push100"></div>

            <div class="col-xs-12 text-center">

                <h4 style="white-space: normal !important;">Atualizamos nosso regulamento, aviso de privacidade e termos de consentimento</h4>

            </div>

            <div class="push20"></div>

            <div class="row">

                <div class="col-xs-8 col-xs-offset-2 text-center">
                    <a type="submit" id="CAD" class="btn btn-success">Aceitar e ler mais tarde</a>
                </div>

            </div>

            <div class="push10"></div>

            <div class="row">

                <div class="col-xs-8 col-xs-offset-2 text-center">
                    <a href="https://adm.bunker.mk/app/cadastro_V2.do?key=<?=$_GET['key']?>&t=<?=$rand?>" class="btn btn-sm btn-info">Ler agora</a>
                </div>

            </div>

        </form> 
   
   
    </div> <!-- /container -->

<?php include 'footer.php'; ?>

<script type="text/javascript">
    $("#CAD").click(function(e){
        $("#blocker").show();
        document.getElementById("formulario").submit();
    });
</script>