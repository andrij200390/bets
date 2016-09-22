var $ = jQuery.noConflict();
$( document ).ready(function() {
    $("#btn").click(
		function(){
			sendAjaxForm('result_form', 'myform', 'action_ajax_form.php');
			return false; 
		}
	);
});
 
function sendAjaxForm(result_form, myform, url) {
    jQuery.ajax({
        url:     "/wp-content/plugins/myform_ajax/action_ajax_form.php", //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        dataType: "html", //формат данных
        data: jQuery("#"+myform).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
        	result = jQuery.parseJSON(response);
        	document.getElementById(result_form).innerHTML = "<span>Успешно отправленны</span><br />"+ "Имя: "+result.name+"<br />Телефон: "+result.phone + "<br />Email: " + result.email;
    	},
    	error: function(response) { // Данные не отправлены
    		document.getElementById(result_form).innerHTML = "Ошибка. Данные не отправленны.";
    	}
 	});
	$('#myform').find('input[type=text]').val('');
	document.getElementById(result_form).innerHTML = "";
}