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
					$.notify("�� ���� �������� �� ��������.", "success");

					$('#unsubscribe').attr("onclick", "");
					$('#unsubscribe').css("background-color", "#3f3f3f");
					$('#unsubscribe').css("cursor", "default");
					break;
				case "failed":
					$.notify("��������� ������. ���������� �����.", "error");
					break;
				default:
					$.notify(response, "warn");
					break;
			}
		}
	});
}