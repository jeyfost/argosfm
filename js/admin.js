$(document).ready(function() {
    $('#categorySelect').change(function() {
		$('#categorySelectForm').submit();
	});
	$('#modeSelect').change(function() {
		$('#modeSelectForm').submit();
	});
	$('#typeSelect').change(function() {
		$('#typeSelectForm').submit();
	});
	$('#goodsTypeSelect').change(function() {
		$('#goodsTypeSelectForm').submit();
	});
	$('#catalogueCategorySelect').change(function() {
		$('#catalogueCategorySelectForm').submit();
	});
	$('#catalogueSubcategorySelect').change(function() {
		$('#catalogueSubcategorySelectForm').submit();
	});
	$('#catalogueSubcategory2Select').change(function() {
		$('#catalogueSubcategory2SelectForm').submit();
	});
	$('#goodCategoryEditSelectForm').change(function() {
        $('#goodCategoryEditSelectForm').submit();
    });
	$('#goodSubcategory2EditSelectForm').change(function() {
        $('#goodSubcategory2EditSelectForm').submit();
    });
	$('#goodCategorySelect').change(function() {
        $('#goodCategoryEditSelectForm').submit();
    });$('#goodCategoryEditSelect').change(function() {
        $('#goodCategoryEditSelectForm').submit();
    });
});