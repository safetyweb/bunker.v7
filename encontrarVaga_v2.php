
<div class="section-title w-50 mx-auto mb-0 text-center">
    <h3 class="mb-2"><span class="theme">Vamos lhe ajudar a encontrar uma data livre. Siga os passos abaixo:</span></h3>
    <h3 class="mb-1"><span class="theme"><span class='badge text-center' style='background: #00491F;'><span class='txtBadge' style="color: #fff;">1</span></span> Escolha uma localidade</span></h3>
</div>

<div class="row d-flex align-items-center justify-content-between">
    <div class="col-lg-12 mb-2 text-center">
        <ul class="pb-2 mb-2 border-b">
        <?php 

            $arrChale = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=3");
            $qrHotel = json_decode($arrChale,true);
            $count = 0;
            foreach ($qrHotel as $hotel) {

                

        ?>
                <li class="me-1 mb-1 p-2 bg-grey d-block rounded"><input type="radio" id="RAD_HOTEL_<?=$count?>" name="RAD_HOTEL" value="<?=$hotel[COD_EXTERNO]?>" <?=$checked?>> <label for="RAD_HOTEL_<?=$count?>"><?=$hotel[NOM_FANTASI]?></label></li>

        <?php 

                $count++;

            }

        ?>
        </ul>
    </div>
</div>

<div class="section-title w-50 mx-auto text-center">
    <h3 class="mb-1"><span class="theme"><span class='badge text-center' style='background: #00491F;'><span class='txtBadge' style="color: #fff;">2</span></span> <b>Essas são as próximas datas disponíveis</b>. Selecione um período e veja as acomodações <b>disponíveis</b></span></h3>
</div>

<div class="row d-flex align-items-center justify-content-between">
    <div class="col-lg-12 mb-2 text-center" id="proxDatas">
        <ul class="pb-2 mb-2 border-b">
        <?php 

            $arrChale = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?COD_CHALE=$cod_chale&COD_HOTEL=$cod_hotel_busca&tipo=5");
            $qrHotel = json_decode($arrChale,true);
            $countDatas = 0;
            foreach ($qrHotel as $diaReserva) {

                $diarias = $diaReserva[minDiarias];

                if($diarias == 1){
                    $diarias = 2;
                }

                $datIniLink = $diaReserva[DataInicio];
                $datFimLink = date("Y-m-d",strtotime($diaReserva[DataInicio]." + $diarias days"));
                
                if($countDatas == 5){
                    break;
                }

        ?>
                <!-- <li class="me-1 mb-1 p-2 bg-grey d-inline-block rounded"><input type="radio" id="RAD_DATAS_<?=$countDatas?>" name="RAD_DATAS" value="<?=$diaReserva[DataInicio]?>"> <label for="RAD_HOTEL_<?=$countDatas?>"><?=date("d/m",strtotime($diaReserva[DataInicio]))?> a <?=date("d/m",strtotime($diaReserva[DataFim]))?></label></li> -->
                <li class="me-1 mb-1 p-2 bg-grey d-block rounded"><a href="busca.php?datI=<?=fnDataShort($datIniLink)?>&datF=<?=fnDataShort($datFimLink)?>&ccm=<?=$cod_comod?>&idh=<?=$cod_hotel_busca?>&idc=&numC=<?=$_SESSION['numC']?>" target="_blank"><input type="radio" name="RAD_DATA" onclick='window.open("busca.php?datI=<?=fnDataShort($datIniLink)?>&datF=<?=fnDataShort($datFimLink)?>&ccm=<?=$cod_comod?>&idh=<?=$cod_hotel_busca?>&idc=&numC=<?=$_SESSION['numC']?>", "_blank")'> <?=date("d/m",strtotime($datIniLink))?> <?=utf8_encode(strftime("%A", strtotime($datIniLink)))?> a <?=date("d/m",strtotime($datFimLink))?> <?=utf8_encode(strftime("%A", strtotime($datFimLink)))?></a></li>

        <?php 

                $countDatas++;

            }

        ?>  
            <div id="loadMore"></div>
            <li class="me-1 mb-1 p-2 bg-grey d-block rounded" id="btnLoadMore"><a href="javascript:void(0)"><b>Mais datas</b></a></li>
        </ul>
    </div>
</div>

<script>
    
    $(function(){

        let idh = "",
            cont = 0;

        $('input[type=radio][name=RAD_HOTEL]').change(function() {

            idh = $(this).val();

            $.ajax({
                url: 'ajxProximaData.php?idh='+idh+'&idc=<?=$cod_chale?>&ccm=<?=$cod_comod?>&numC=<?=$_SESSION["numC"]?>&opcao=proxData&dev=<?=$_GET[dev]?>',
                method: 'GET', // Send post data,
                beforeSend:function(){
                    // $('#chalesBusca').html('<center><img src="images/loading.gif" width="30px"></center>');
                    $('#proxDatas').html('<center><img src="images/loading.gif" width="30px"></center>');
                },
                success: function(data){
                    console.log(data);
                    $("#proxDatas").html(data);
                },
                error: function(data) {
                    console.log(data);
                }
            });

        });

       $("#btnLoadMore").click(function(){

            cont +=5;
            idh = $('input[type=radio][name="RAD_HOTEL"]:checked').val();;

            $.ajax({
                url: 'ajxProximaData.php?idh='+idh+'&idc=<?=$cod_chale?>&ccm=<?=$cod_comod?>&numC=<?=$_SESSION["numC"]?>&opcao=loadMore&count='+cont+'&dev=<?=$_GET[dev]?>',
                method: 'GET', // Send post data,
                beforeSend:function(){
                    // $('#chalesBusca').html('<center><img src="images/loading.gif" width="30px"></center>');
                    $("#btnLoadMore").html('<center><img src="images/loading.gif" width="30px"></center>');
                },
                success: function(data){
                    console.log(data);
                    $("#loadMore").append(data);
                    $("#btnLoadMore").html('<a href="javascript:void(0)"><b>Carregar Mais</b></a>');
                },
                error: function(data) {
                    console.log(data);
                }
            });

            // $("#load_more_"+tipo).fadeIn("fast");
            // $(el).fadeOut("fast");
        });

    });


</script>