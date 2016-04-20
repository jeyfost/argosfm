var hBlock = 0;

$(document).ready(function() {
    $('#goodCodeInput').keyup(function() {
       if($('#goodCodeInput').val().length < 4 ) {
       	$('#goodCodeInput').css('border', '1px solid #df4e47');
       	$('#goodCodeInput').css('backgroundColor', '#ffb1ad');
       }
       else {
       	var goodCode = $('#goodCodeInput').val();
       	$.ajax({
       		type: 'POST',
       		url: '../scripts/admin/ajaxCode.php',
       		data: {"goodCode": goodCode},
       		cache: false,
       		success: function(response) {
       			if(response == "a") {
              $('#goodCodeInput').css('border', 'none');
              $('#goodCodeInput').css('backgroundColor', '#dddddd');
            }
            if(response == "b") {
              $('#goodCodeInput').css('border', '1px solid #df4e47');
              $('#goodCodeInput').css('backgroundColor', '#ffb1ad');
            }
       		}
       	});
       	return false;
       }
    });
});

$(document).ready(function() {
  $('#categoryNameInput').keyup(function() {
      var categoryName = $('#categoryNameInput').val();
      if(categoryName.length == 0) {
        $('#categoryNameInput').css('border', '1px solid #df4e47');
        $('#categoryNameInput').css('backgroundColor', '#ffb1ad');
      }
      else {
        $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxCategory.php',
          data: {"categoryName": categoryName},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#categoryNameInput').css('border', 'none');
                $('#categoryNameInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#categoryNameInput').css('border', '1px solid #df4e47');
                $('#categoryNameInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
      }
    });
});

$(document).ready(function() {
  $('#subcategoryNameInput').keyup(function() {
    var subcategoryName = $('#subcategoryNameInput').val();
    if(subcategoryName.length == 0) {
      $('#subcategoryNameInput').css('border', '1px solid #df4e47');
      $('#subcategoryNameInput').css('backgroundColor', '#ffb1ad');
    }
    else {
      $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxSubcategory.php',
          data: {"subcategoryName": subcategoryName},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#subcategoryNameInput').css('border', 'none');
                $('#subcategoryNameInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#subcategoryNameInput').css('border', '1px solid #df4e47');
                $('#subcategoryNameInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
    }
  });
});

$(document).ready(function() {
  $('#subcategory2NameInput').keyup(function() {
    var subcategory2Name = $('#subcategory2NameInput').val();
    if(subcategory2Name.length == 0) {
      $('#subcategory2NameInput').css('border', '1px solid #df4e47');
      $('#subcategory2NameInput').css('backgroundColor', '#ffb1ad');
    }
    else {
      $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxSubcategory2.php',
          data: {"subcategory2Name": subcategory2Name},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#subcategory2NameInput').css('border', 'none');
                $('#subcategory2NameInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#subcategory2NameInput').css('border', '1px solid #df4e47');
                $('#subcategory2NameInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
    }
  });
});

$(document).ready(function() {
  $('#newAddressInput').keyup(function() {
    var newAddress = $('#newAddressInput').val();
    if(newAddress.length == 0) {
      $('#newAddressInput').css('border', '1px solid #df4e47');
      $('#newAddressInput').css('backgroundColor', '#ffb1ad');
    }
    else {
      $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxAddress.php',
          data: {"newAddress": newAddress},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#newAddressInput').css('border', 'none');
                $('#newAddressInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#newAddressInput').css('border', '1px solid #df4e47');
                $('#newAddressInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
    }
  });
});

$(document).ready(function() {
  $('#userLoginInput').keyup(function() {
    var userLogin = $('#userLoginInput').val();
    if(userLogin.length == 0) {
      $('#userLoginInput').css('border', '1px solid #df4e47');
      $('#userLoginInput').css('backgroundColor', '#ffb1ad');
    }
    else {
      $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxUserLogin.php',
          data: {"userLogin": userLogin},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#userLoginInput').css('border', 'none');
                $('#userLoginInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#userLoginInput').css('border', '1px solid #df4e47');
                $('#userLoginInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
    }
  });
});

$(document).ready(function() {
  $('#userEmailInput').keyup(function() {
    var userEmail = $('#userEmailInput').val();
    if(userEmail.length == 0) {
      $('#userEmailInput').css('border', '1px solid #df4e47');
      $('#userEmailInput').css('backgroundColor', '#ffb1ad');
    }
    else {
      $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxUserEmail.php',
          data: {"userEmail": userEmail},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#userEmailInput').css('border', 'none');
                $('#userEmailInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#userEmailInput').css('border', '1px solid #df4e47');
                $('#userEmailInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
    }
  });
});

$(document).ready(function() {
  $('#userPersonInput').keyup(function() {
    var userPerson = $('#userPersonInput').val();
    if(userPerson.length == 0) {
      $('#userPersonInput').css('border', '1px solid #df4e47');
      $('#userPersonInput').css('backgroundColor', '#ffb1ad');
    }
    else {
      $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxUserPerson.php',
          data: {"userPerson": userPerson},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#userPersonInput').css('border', 'none');
                $('#userPersonInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#userPersonInput').css('border', '1px solid #df4e47');
                $('#userPersonInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
    }
  });
});

$(document).ready(function() {
  $('#userPhoneInput').keyup(function() {
    var userPhone = $('#userPhoneInput').val();
    if(userPhone.length == 0) {
      $('#userPhoneInput').css('border', '1px solid #df4e47');
      $('#userPhoneInput').css('backgroundColor', '#ffb1ad');
    }
    else {
      $.ajax({
          type: 'POST',
          url: '../scripts/admin/ajaxUserPhone.php',
          data: {"userPhone": userPhone},
          cache: false,
          success: function(response) {
            if(response == "a") {
                $('#userPhoneInput').css('border', 'none');
                $('#userPhoneInput').css('backgroundColor', '#dddddd');
              }
            if(response == "b") {
                $('#userPhoneInput').css('border', '1px solid #df4e47');
                $('#userPhoneInput').css('backgroundColor', '#ffb1ad');
              }
          }
        });
        return false;
    }
  });
});

$(document).ready(function() {
  $('#userSearchInput').keyup(function() {
    var search = $('#userSearchInput').val();
    if(search != "Поиск..." && search.length > 0) {
      $('#admSearchResult').css('display', 'inline-block');
      $.ajax({
        type: 'POST',
        url: '../scripts/admin/ajaxUserSearch.php',
        data: {"userSearch": search},
        cache: false,
        success: function(response) {
          $('#admSearchResult').html(response);
        }
      });
    }
    else {
      $('#admSearchResult').html();
      $('#admSearchResult').css('display', 'none');
    }
  });
});

$(document).ready(function() {
  $('#userSearchInput').blur(function() {
    if(hBlock == 0) {
      $('#admSearchResult').html();
      $('#admSearchResult').css('display', 'none');
    }
  });
});

$(document).ready(function() {
  $('#userSearchInput').focus(function() {
    var search = $('#userSearchInput').val();
    if(search != "Поиск..." && search.length > 0) {
      $('#admSearchResult').css('display', 'inline-block');
      $.ajax({
        type: 'POST',
        url: '../scripts/admin/ajaxUserSearch.php',
        data: {"userSearch": search},
        cache: false,
        success: function(response) {
          $('#admSearchResult').html(response);
        }
      });
    }
  });
});

function hoverBlock(action) {
  if(action == '1') {
    hBlock = 1;
  }

  if(action == '0') {
    hBlock = 0;
  }
}

$(document).ready(function() {
  $('#newsSearchInput').keyup(function() {
    var search = $('#newsSearchInput').val();
    if(search != "Поиск..." && search.length > 0) {
      $('#admSearchResult').css('display', 'inline-block');
      $.ajax({
        type: 'POST',
        url: '../scripts/admin/ajaxNewsSearch.php',
        data: {"newsSearch": search},
        cache: false,
        success: function(response) {
          $('#admSearchResult').html(response);
        }
      });
    }
    else {
      $('#admSearchResult').html();
      $('#admSearchResult').css('display', 'none');
    }
  });
});

$(document).ready(function() {
  $('#newsSearchInput').blur(function() {
    if(hBlock == 0) {
      $('#admSearchResult').html();
      $('#admSearchResult').css('display', 'none');
    }
  });
});

$(document).ready(function() {
  $('#newsSearchInput').focus(function() {
    var search = $('#newsSearchInput').val();
    if(search != "Поиск..." && search.length > 0) {
      $('#admSearchResult').css('display', 'inline-block');
      $.ajax({
        type: 'POST',
        url: '../scripts/admin/ajaxNewsSearch.php',
        data: {"newsSearch": search},
        cache: false,
        success: function(response) {
          $('#admSearchResult').html(response);
        }
      });
    }
  });
});

$(document).ready(function() {
  $('#setCode').click(function() {
    $.ajax({
      type: 'POST',
      url: '../scripts/admin/ajaxSetCode.php',
      cache: false,
      success: function(response) {
        if(response.length < 4) {
          var zero = parseInt(4 - response.length);
          var add = "";

          for(var i = 0; i < zero; i++) {
            add += '0';
          }

          response = add += response;
        }
        
        $('#goodCodeInput').val(response);
      }
    });
  });
});

$(document).ready(function() {
  $('#addressSearchInput').keyup(function() {
    var query = $('#addressSearchInput').val();

    if(query.length > 0) {
      $.ajax({
        type: 'POST',
        url: '../scripts/admin/ajaxSearchAddress.php',
        cache: false,
        data: {"searchQuery": query},
        success: function(response) {
          $('#addressSearchResult').css('display', 'block');
          $('#addressSearchResult').html(response);
        }
      });
    }
    else {
      $('#addressSearchResult').html('');
      $('#addressSearchResult').css('display', 'none');
    }
  });
});

$(document).ready(function() {
  $('#addressSearchInput').click(function() {
    var query = $('#addressSearchInput').val();

    if(query.length > 0) {
      $.ajax({
        type: 'POST',
        url: '../scripts/admin/ajaxSearchAddress.php',
        cache: false,
        data: {"searchQuery": query},
        success: function(response) {
          $('#addressSearchResult').css('display', 'block');
          $('#addressSearchResult').html(response);
        }
      });
    }
    else {
      $('#addressSearchResult').html('');
      $('#addressSearchResult').css('display', 'none');
    }
  });
});

$(document).ready(function() {
   $('#emailThemeInput').keyup(function() {
       var theme = $('#emailThemeInput').val();

       if(theme > 0) {
           $('#emailThemeInput').css('border', 'none');
           $('#emailThemeInput').css('background-color', '#ddd');
       } else {
           $('#emailThemeInput').css('border', '1px solid #df4e47');
           $('#emailThemeInput').css('background-color', '#ffb1ad');
       }
   });
});

function validateClientEmail() {
    var address = $('#addressFieldInput').val();
    if (address.length > 0) {
        $.ajax({
            type: 'POST',
            data: {"email": address},
            url: '../scripts/admin/ajaxValidateEmail.php',
            success: function (response) {
                if (response == "a") {
                    $('#addressFieldInput').css('border', 'none');
                    $('#addressFieldInput').css('background-color', '#ddd');
                }

                if (response == "b") {
                    $('#addressFieldInput').css('border', '1px solid #df4e47');
                    $('#addressFieldInput').css('background-color', '#ffb1ad');
                }
            }
        });
    } else {
        $('#addressFieldInput').css('border', '1px solid #df4e47');
        $('#addressFieldInput').css('background-color', '#ffb1ad');
    }
}

function editClientEmail() {
    var address = $('#editEmailInput').val();

    if(address.length > 0) {
        $.ajax({
            type: 'POST',
            data: {"email": address},
            url: '../scripts/admin/ajaxValidateEmailAdmin.php',
            success: function(response) {
                if(response == "a") {
                    $('#editEmailInput').css('border', 'none');
                    $('#editEmailInput').css('background-color', '#ddd');
                }

                if(response == "b") {
                    $('#editEmailInput').css('border', '1px solid #df4e47');
                    $('#editEmailInput').css('background-color', '#ffb1ad');
                }
            }
        });
    } else {
        $('#editEmailInput').css('border', '1px solid #df4e47');
        $('#editEmailInput').css('background-color', '#ffb1ad');
    }
}

function editClientName() {
    var name = $('#editNameInput').val();

    if(name.length > 0) {
        $('#editNameInput').css('border', 'none');
        $('#editNameInput').css('background-color', '#ddd');
    } else {
        $('#editNameInput').css('border', '1px solid #df4e47');
        $('#editNameInput').css('background-color', '#ffb1ad');
    }
}

$(document).mouseup(function (e) {
    var container = $('#addressSearchResult');
    if (container.has(e.target).length === 0){
        container.hide();
    }
});