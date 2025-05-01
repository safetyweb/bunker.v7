//Barra de pesquisa essentials ------------------------------------------------------
$(document).ready(function(e){
	var value = $('#INPUT').val().toLowerCase().trim();
    if(value){
    	$('#CLEARDIV').show();
    }else{
    	$('#CLEARDIV').hide();
    }
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
		e.preventDefault();
		var param = $(this).attr("href").replace("#","");
		var concept = $(this).text();
		$('.search-panel span#search_concept').text(concept);
		$('.input-group #VAL_PESQUISA').val(param);
		$('#INPUT').focus();
	});

    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
	    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
    });

    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
    	$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
    });

    $('#CLEAR').click(function(){
    	$('#INPUT').val('');
    	$('#INPUT').focus();
    	$('#CLEARDIV').hide();
    	if("<?=$filtro?>" != ""){
    		location.reload();
    	}else{
    		var value = $('#INPUT').val().toLowerCase().trim();
		    if(value){
		    	$('#CLEARDIV').show();
		    }else{
		    	$('#CLEARDIV').hide();
		    }
		    $(".buscavel tr").each(function (index) {
		        if (!index) return;
		        $(this).find("td").each(function () {
		            var id = $(this).text().toLowerCase().trim();
		            var sem_registro = (id.indexOf(value) == -1);
		            $(this).closest('tr').toggle(!sem_registro);
		            return sem_registro;
		        });
		    });
    	}
    });

    // $('#SEARCH').click(function(){
    // 	$('#formulario').submit();
    // });
    	
    
});

function buscaRegistro(el){
	var filtro = $('#search_concept').text().toLowerCase();

	if(filtro == "sem filtro"){
	    var value = $(el).val().toLowerCase().trim();
	    if(value){
	    	$('#CLEARDIV').show();
	    }else{
	    	$('#CLEARDIV').hide();
	    }
	    $(".buscavel tr").each(function (index) {
	        if (!index) return;
	        $(this).find("td").each(function () {
	            var id = $(this).text().toLowerCase().trim();
	            var sem_registro = (id.indexOf(value) == -1);
	            $(this).closest('tr').toggle(!sem_registro);
	            return sem_registro;
	        });
	    });
	}
}

//-----------------------------------------------------------------------------------