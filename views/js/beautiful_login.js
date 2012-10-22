$(document).ready(function() {
    
    /* fixes for people WITH javascript (people without JavaScript can also use the script) */
    $("#login_input_password").hide()
    $("#login_input_password_label").css("display", "inline");
    $("#login_input_password_new").hide()
    $("#login_input_password_new_label").css("display", "inline");
    $("#login_input_password_repeat").hide()
    $("#login_input_password_repeat_label").css("display", "inline");
    

    
    

    /* hide message/error boxes after 5 seconds */
    /*$(".login_message").delay(3000).slideUp();*/

    $(".login_input").focus(function() {

             $(this).addClass("active");
             $(this).focusout(function() {
                 $(this).removeClass("active");
             });
    });
    
    
    $('.login_input[name="user_email"]').focusout(function() {

        var email = $(this).val();                    
        var gravatarUrl = 'http://www.gravatar.com/avatar/' + $.MD5(email) + "?d=mm&s=125";
        $('#login_avatar').css("background-image", "url(" + gravatarUrl + ")");

    });
    
    
    
    /* jQuery: clear username input box text when clicked */
    $("#login_input_username").focus(function() {
        
        if ($(this).val() == "Username") {
            $(this).val("");
        }        
        
        $(this).focusout(function() {
            if ($(this).val() == "") {
                $(this).val("Username");
            }
        });
    });
    
    
    /* jQuery: change password plain text input to password input */
    $("#login_input_password_label").focus(function() {
        $(this).hide();
        $("#login_input_password").css("display", "inline");
        $("#login_input_password").focus();
        
        $("#login_input_password").focusout(function() {
            if ($(this).val() == "") {
                $("#login_input_password").hide();
                $("#login_input_password_label").css("display", "inline");
            }
        });
    });
    
    
    
    
    //alert($("#login_input_username").val());
    
/*
    $("#login_input_password").live(function() {
        
        alert($("#login_input_password").val());
        
        if ($(this).val().length > 0) {
            $("#login_input_password_label").hide();
            $("#login_input_password").show();
        }

    });    
*/

    $("#login_input_password_new_label").focus(function() {
        $(this).hide();
        $("#login_input_password_new").css("display", "inline");
        $("#login_input_password_new").focus();
        $("#login_input_password_new").focusout(function() {
            if ($(this).val() == "") {
                $("#login_input_password_new").hide();
                $("#login_input_password_new_label").css("display", "inline");
            }
        });        
    });

    $("#login_input_password_repeat_label").focus(function() {
        $(this).hide();
        $("#login_input_password_repeat").css("display", "inline");
        $("#login_input_password_repeat").focus();
        $("#login_input_password_repeat").focusout(function() {
            if ($(this).val() == "") {
                $("#login_input_password_repeat").hide();
                $("#login_input_password_repeat_label").css("display", "inline");
            }
        });            
    });
    
    
    

    /* jQuery: clear username input box text when clicked */
    $("#login_input_email").focus(function() {
        
        if ($(this).val() == "eMail") {
            $(this).val("");
        }        
        
        $(this).focusout(function() {
            if ($(this).val() == "") {
                $(this).val("eMail");
            }
        });
    });
    
    
    /**/
    
    if ($("#login_input_username").val().length > 0
            &&
        $("#login_input_username").val() != "Username"
            &&
        $("#login_input_password").val().length > 0    
        ) {
        
        $("#login_input_password_label").hide();
        $("#login_input_password").css("display", "inline");
        //$("#login_input_password").focus();
        /*
        if ($("#login_input_password").val() == '') {
            
        }
        */
    }
    
    
    
 });