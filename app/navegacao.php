<?php

    $usuario = fnDecode($_GET[idU]);

    $sqlCli = "SELECT COD_CLIENTE, LOG_TERMO FROM CLIENTES WHERE NUM_CGCECPF ='$usuario' AND COD_EMPRESA = $cod_empresa";
        $arrayQueryCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
        $qrCli = mysqli_fetch_assoc($arrayQueryCli);

        $cod_cliente = fnLimpaCampoZero($qrCli['COD_CLIENTE']);
        $log_termo = $qrCli['LOG_TERMO'];

        if($cod_empresa == 19){
            $log_termo = 'S';
        }
        
        $usuEncrypt = $_GET["idU"];
        $rand = fnEncode(microtime());
?>

<div class="row navbar-fixed-top" style="background-color: <?=$cor_backbar?>; color: <?=$cor_textfull?>" style="min-height: 65px;">
    <div class="col-xs-12">
        <div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
            <?php
                if($usuario == "" || $log_termo != 'S' || $tituloPagina == "Faça seu login" || $tituloPagina == "Recuperar Senha"){
            ?>
                    <a class="center" href="intro.do?key=<?=$_GET[key]?>&t=<?=$rand?>" style="text-decoration: none; color: <?=$cor_textfull?>"><i class="fal fa-arrow-left fa-2x" aria-hidden="true"></i></a>
            <?php 
                }else{ 
            ?>
                    <a href="novoMenu.do?key=<?=$_GET[key]?>&idU=<?=$_GET[idU]?>&t=<?=$rand?>" class="center" style="text-decoration: none; color: <?=$cor_textfull?>"><i class="fal fa-arrow-left fa-2x" aria-hidden="true"></i></a>
            <?php 
                } 
            ?>
        </div>
        <div class="col-xs-6 text-center zeraPadLateral" style="min-height: 65px;">
            <div class="menuTitulo center"><?php echo $tituloPagina; ?></div>
        </div>
        <div class="col-xs-3 text-center zeraPadLateral" style="min-height: 65px;">
            <?php if($des_logo != ""){ ?>
                <img class="img-responsive center" alt="" width="65px" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" style="padding-top: 5px; padding-bottom: 5px;">
            <?php } ?>
        </div>
    </div>
</div>
    
<div class="push30"></div>	