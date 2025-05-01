<?php

//menu superior - cliente
$abaEmpresa = 1020;
$abaCli = 1920;
// echo $_SESSION["SYS_COD_SISTEMA"];                               
?>

<!-- <div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="<?= ($abaAvaliaBens == 1927 ? "active" : "") ?>" style="min-height: 42px; margin-bottom: 5px"><a href="javascript:" onclick="window.location.href = 'action.do?mod=<?=fnEncode(1927)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=$_GET['idC']?>&idBem=<?=fnEncode($cod_bem)?>>Avaliação do Bem</a></li>
        <li class="<?= ($abaAvaliaBens == 1928 ? "active" : "") ?>" style="min-height: 42px; margin-bottom: 5px"><a href="javascript:" onclick="window.location.href = 'action.do?mod=<?=fnEncode(1928)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=$_GET['idC']?>&idBem=<?=fnEncode($cod_bem)?>>Atividade de Valoração do Bem</a></li>
        <li class="<?= ($abaAvaliaBens == 1929 ? "active" : "") ?>" style="min-height: 42px; margin-bottom: 5px"><a href="javascript:" onclick="window.location.href = 'action.do?mod=<?=fnEncode(1929)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=$_GET['idC']?>&idBem=<?=fnEncode($cod_bem)?>>Anexar Arquivo</a></li>
    </ul>
</div> -->

<ul class="nav nav-tabs">

    <?php // if(fnControlaAcesso("1024",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
    <li class="<?= ($abaAvaliaBens == 1927 ? "active" : "") ?>"><a href="action.do?mod=<?=fnEncode(1927)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=$_GET['idC']?>&idBem=<?=fnEncode($cod_bem)?>">Avaliação do Bem</a></li>
    <?php // } ?>

    <?php // if(fnControlaAcesso("1920",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
    <li class="<?= ($abaAvaliaBens == 1928 ? "active" : "") ?>"><a href="action.do?mod=<?=fnEncode(1928)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=$_GET['idC']?>&idBem=<?=fnEncode($cod_bem)?>">Atividade de Valoração do Bem</a></li>
    <?php // } ?>

    <?php // if(fnControlaAcesso("1253",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
    <!-- <li class="<?= ($abaAvaliaBens == 1929 ? "active" : "") ?>"><a href="action.do?mod=<?=fnEncode(1929)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=$_GET['idC']?>&idBem=<?=fnEncode($cod_bem)?>">Anexar Arquivo</a></li> -->
    <?php // } ?>

    <?php // if(fnControlaAcesso("1253",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
    <li class="<?= ($abaAvaliaBens == 1989 ? "active" : "") ?>"><a href="action.do?mod=<?=fnEncode(1989)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=$_GET['idC']?>&idBem=<?=fnEncode($cod_bem)?>">Anexar Arquivo</a></li>
    <?php // } ?>

    

</ul>