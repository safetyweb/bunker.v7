<ul class="nav nav-tabs">
    <li class="<?= ($abaBens == 1935 ? "active" : "") ?>">
        <a href="javascript:" onclick="window.location.href = 'action.do?mod=<?php echo fnEncode(1935) ?>&id=<?= $_GET['id'] ?>&idFluxo='+$('#formulario #COD_FLUXO_ENCODE').val()">Cadastro</a>
    </li>
    <li class="<?= ($abaBens == 1932 ? "active" : "") ?>">
        <a href="javascript:" onclick="window.location.href = 'action.do?mod=<?php echo fnEncode(1932) ?>&id=<?= $_GET['id'] ?>&idFluxo='+$('#formulario #COD_FLUXO_ENCODE').val()">Fluxo de Dados</a>
    </li>
</ul>