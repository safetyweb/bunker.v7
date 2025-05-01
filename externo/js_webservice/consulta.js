
document.write("<form method='post' id='formmarka'>");

	document.write("<div class='fbits-responsive-carrinho-desconto'>");

		document.write("<div class='text-left fbits-responsive-carrinho-desconto-texto'>");
			document.write("Consulta Fidelidade");
		document.write("</div>");

		document.write("<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 fbits-responsive-carrinho-desconto-calculo'>");
			document.write("<div class='form-inline fbits-responsive-carrinho-desconto-input'>");
				document.write("<input type='text' class='form-control' maxlength='50' name='markaCPF' id='markaCPF' value=''>");
				document.write("<input type='button' class='btn btn-success btnCalcular' id='submitFormData' onclick='SubmitFormData();' value='Consulta'>");
			document.write("</div>");
		document.write("</div>");

	document.write("</div>");

document.write("</form>");

document.write("<div id='rsMARKA_SALDO'></div>");




function SubmitFormData() {

    var markaCPF = $("#markaCPF").val();    
    var ID_MARKA = $("#ID_MARKA").val();  
    var VL_TOTALVENDA = document.getElementById('div-subtotal').innerHTML;

    $.ajax({
        cache : false,
        method: 'POST',
     	url: 'https://adm.bunker.mk/externo/js_webservice/CONSWSDL.php',
     	data: {markaCPF: markaCPF, ID_MARKA:ID_MARKA, VL_TOTALVENDA:VL_TOTALVENDA},
     	success:function(data){
     		$('#rsMARKA_SALDO').html(data);
	 	$('#formmarka')[0].reset();
        }
    });


}






