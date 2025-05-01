<div id="filtros-mob" style="display: none;">

    <div id="close_filtros" class="margin-left-15 margin-top-100">
        <a href="javascript:void(0)" onclick="mostraFiltros('filtros-mob')" style="padding: 15px 15px 15px 0; color: #2C3E50;">
            <b><span class="far fa-arrow-left fa-2x"></span></b>
        </a>
    </div>

    <div id="sanfona" class="margin-top-40">

        <h3 class="margin-top-10 margin-bottom-30 margin-left-15"><b>Adicionar à sua busca</b></h3>

        <button class="collapsible"><b>Agenda</b></button>
        <div class="content">
          
            <ul style="list-style-type: none;">

                <?php

                    $active = "";

                    if($desafioFiltro == ""){
                        $active = "ord-active";
                    }

                ?>

                <li style="padding: 0;"><button class="ordenador" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET[idUv]?>'"><span class="<?=$active?>">Todas</span></button></li>

                <?php

                    foreach ($cod_desafios as $codDesafio) {

                        $sqlNomDesafio = "SELECT NOM_DESAFIO FROM DESAFIO_V2 WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $codDesafio";
                        // fnEscreve($sqlNomDesafio);
                        $qrDesaf = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlNomDesafio));

                        $active = "";

                        if($codDesafio == $desafioFiltro){
                            $active = "ord-active";
                        }

                ?>
                
                        <li style="padding: 0;"><button class="ordenador" onclick="window.location.href= 'https://adm.bunker.mk/action.do?mod=<?= fnEncode(1939) ?>&id=<?= fnEncode($cod_empresa) ?>&idU=<?= fnEncode($cod_responsavel) ?>&idUv=<?=$_GET[idUv]?>&fIdC=<?=fnEncode($codDesafio)?>'"><span class="<?=$active?>"><?=$qrDesaf['NOM_DESAFIO']?></span></button></li>

                <?php

                    }

                ?>
                
            </ul>
        </div>


       <!--  <button class="collapsible"><b>Marca</b></button>
        <div class="content">
  
            <ul style="list-style-type: none;">

                <li><a class="margin-left-15" href="javascript:void(0)" onclick="addFiltro('id1', $(this))" data-key="F2">Adidas</a></li>

            </ul>
        </div>

        <button class="collapsible"><b>Departamento</b></button>
        <div class="content margin-bottom-100">
          
            <ul style="list-style-type: none;">
                
                <li><a class="margin-left-15" href="javascript:void(0)" onclick="addFiltro('id2', $(this))" data-key="F3">Calçados</a></li>
                
            </ul>
        </div> -->

    </div>

</div>