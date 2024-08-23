function add_css_tab(element) 
{
    
    jQuery(".mo_nav_tab_active").removeClass("mo_nav_tab_active");
    jQuery(element).addClass("mo_nav_tab_active");

} 
function cancel_form() 
{
    jQuery('#oauth_cancel_form').submit();
}

function back_btn(){

    jQuery('#mo_otp_cancel_form').submit();
}

function resend_otp(){
    jQuery('#resend_otp_form').submit();
}

function oauth_account_exist(){
    jQuery('#resend_otp_form').submit();
}

function copyToClipboard(element) 
{
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
    temp.val(jQuery(element).text()).select();
    document.execCommand("copy");
    temp.remove();  
}

function validateEmail(emailField) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

    if (reg.test(emailField.value) == false) {
        document.getElementById("email_error").style.display = "block";
        document.getElementById("submit_button").disabled = true;
    } else {
        document.getElementById("email_error").style.display = "none";
        document.getElementById("submit_button").disabled = false;
    }
    
}
function cancel_update(){
    jQuery("#cancelUpdate").submit();
}