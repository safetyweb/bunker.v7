<?php
//inicialização
$aba1470 = "";
$aba1471 = "";
$aba1479 = '';
$aba1479 = '';
$aba1482 = '';

switch ($abaTutorial) {
    case 1470: //artigo
        $aba1470 = "active";
        break;
    case 1471: //categoria
        $aba1471 = "active";
        break;
    case 1482: //categoria
        $aba1482 = "active";
        break;
}

?>

<ul class="nav nav-tabs">
    <li class="<?php echo $aba1471; ?>"><a href="action.do?mod=<?php echo fnEncode(1471); ?>">Categorias Artigo/Tutorial</a></li>
    <li class="<?php echo $aba1482; ?>"><a href="action.do?mod=<?php echo fnEncode(1482); ?>">Subcategorias Artigo/Tutorial</a></li>
    <li class="<?php echo $aba1470; ?>"><a href="action.do?mod=<?php echo fnEncode(1470); ?>">Artigo/Tutorial</a></li>
    <li class="<?php echo $aba1479; ?>"><a href="action.do?mod=<?php echo fnEncode(1479); ?>" target="_blank">Lista Tutorial</a></li>

</ul>