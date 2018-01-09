/**
 * Created by jeyfost on 09.01.2018.
 */

function unsubscribe(id) {
	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "../scripts/ajaxUnsubscribe.php",
		success: function (response) {
			switch (response) {
				case "ok":
					$.notify("Вы были отписаны от рассылки.", "success");

					$('#unsubscribe').attr("onclick", "");
					$('#unsubscribe').css("background-color", "#3f3f3f");
					$('#unsubscribe').css("cursor", "default");
					break;
				case "failed":
					$.notify("Произошла ошибка. Попробуйте снова.", "error");
					break;
				default:
					$.notify(response, "warn");
					break;
			}
		}
	});
}