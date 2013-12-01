$(document).ready(function() {
	
    // your stuff here
    // ...
    
    // the simple countdown that happens after your tried to login with a wrong password 3+ times:
    if ($('#failed-login-countdown-value').length > 0) {
        seconds = $('#failed-login-countdown-value').text();
        setInterval( function() {            
            if (seconds > 0 ) {                
                seconds--;
                $('#failed-login-countdown-value').text(seconds);                                
            } else {
                $('#failed-login-countdown-value').parent().slideUp();
            }            
        }, 1000);
    }

});
