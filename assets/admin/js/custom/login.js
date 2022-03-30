/***********************************************************************************
*****************To show/clear toaster messages start ******************************
************************************************************************************/

function showToaster(msgType,title,msg)
{
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "rtl": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": 300,
    "hideDuration": 1000,
    "timeOut": 5000,
    "extendedTimeOut": 1000,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
  toastr[msgType](msg, title);
}

function clearToaster()
{
  toastr.clear();
}

/***********************************************************************************
*****************To show/clear toaster messages end ********************************
************************************************************************************/


/***********************************************************************************
***************************Show/hide progress bar start ****************************
************************************************************************************/

function showProgressBar()
{
  NProgress.start();
}

function hideProgressBar()
{
  NProgress.done();
}

/***********************************************************************************
***************************Show/hide progress bar end ******************************
************************************************************************************/


/*****************************************************************************
****************To get query string from url start ***************************
******************************************************************************/

function getQueryStringValue(key)
{  
  var vars = [], hash;
  var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
  for(var i = 0; i < hashes.length; i++)
  {
    hash = hashes[i].split('=');
    vars.push(hash[0]);
    vars[hash[0]] = hash[1];
  }
  return (!vars[key]) ? '' : vars[key];
}

/*****************************************************************************
****************To get query string from url end *****************************
******************************************************************************/


/*****************************************************************************
******************** To manage ajax error start ******************************
******************************************************************************/

function manageAjaxError(jqXHR,exception) 
{
    if (jqXHR.status === 0) {
        showToaster('error','Error !','Not connect.\n Verify Network.');
    } else if (jqXHR.status == 404) {
        showToaster('error','Error !','Requested page not found. [404]');
    } else if (jqXHR.status == 500) {
        showToaster('error','Error !','Internal Server Error [500].');
    } else if (exception === 'parsererror') {
        showToaster('error','Error !','Requested JSON parse failed.');
    } else if (exception === 'timeout') {
        showToaster('error','Error !','Time out error.');
    } else if (exception === 'abort') {
        showToaster('error','Error !','Ajax request aborted.');
    } else {
        showToaster('error','Error !','Uncaught Error.\n' + jqXHR.responseText);
    }
}

/*****************************************************************************
******************** To manage ajax error end ********************************
******************************************************************************/

$(document).ready(function(){

showProgressBar();
setTimeout(function(){
  hideProgressBar();
},1000);

/*****************************************************************************
***********************Prevent Copy/Paste value start ************************
******************************************************************************/

var class_exist = $('input').hasClass('prevent-copy-paste');
if (class_exist == true) {
  $('.prevent-copy-paste').bind('cut copy paste contextmenu',function(e){
    e.preventDefault();
  });
}

/*****************************************************************************
***********************Prevent Copy/Paste value end **************************
******************************************************************************/

/**************** Admin Login Script Start *************/

$("#login-form").submit(function(event) {
	event.preventDefault();
	var form_data = new FormData($('#login-form')[0]);
		form_data.append('redirect_uri',getQueryStringValue('redirect_uri'));
	$.ajax({
            url  : api_url + "login",
            type : "POST",
            data : form_data,   
            dataType : "JSON",   
            cache: false,
            contentType: false,
            processData: false,   
            beforeSend:function(){
              $('.login-btn').attr('disabled',true).text(loading);
            },       
            success: function(resp){
	           if(resp.status == 200){
		            showToaster('success',success,resp.message);
                    localStorage.setItem('login_session_key',resp.data.login_session_key);
                    localStorage.setItem('user_guid',resp.data.user_guid);
		            setTimeout(function(){
		            	window.location.href = resp.data.redirect_uri;
		            },1000);
		        }else{
	            	showToaster('error',error,resp.message);  
	            }
	            $('.login-btn').attr('disabled',false).text(login);
	        },
            error:function(jqXHR, exception){
            	manageAjaxError(jqXHR,exception);
                $('.login-btn').attr('disabled',false).text(login);
            }
        });
});

// $("body").on("keyup blur","#email",function() {
//     var email = $(this).val();
//     var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//     if (regex.test(email)) {
//         $(this).removeClass("p_error");
//     } else {
//         $(this).addClass("p_error");
//     }
// });

$("body").on("keyup blur", "#password", function(){
	$(this).removeClass("p_error");
});

$("#email,#password").keyup(function(event){
    if(event.keyCode == 13){
        $(".submit_login").trigger("click");
    }
});


/**************** Admin Login Script End **************/

/************ Admin Forgot Password Script Start ******/

$("#forgot-form").submit(function(event) {
  event.preventDefault();
  $.ajax({
            url  : api_url + "login/forgot_password",
            type : "POST",
            data : new FormData($('#forgot-form')[0]),   
            dataType : "JSON",   
            cache: false,
            contentType: false,
            processData: false,   
            beforeSend:function(){
              $('.forgot-btn').attr('disabled',true).text(loading);
            },       
            success: function(resp){
             if(resp.status == 200){
                $('#forgot-form')[0].reset();
                showToaster('success','Success',resp.message);
            }else{
                showToaster('error','Error !',resp.message);  
              }
              $('.forgot-btn').attr('disabled',false).text('Forgot Passwrod');
          },
            error:function(jqXHR, exception){
              manageAjaxError(jqXHR,exception);
                $('.forgot-btn').attr('disabled',false).text('Forgot Passwrod');
            }
        });
});

/************ Admin Forgot Password Script End *******/

/************ Admin Reset Password Script Start ******/

$("#reset-password-form").submit(function(event) {
  event.preventDefault();
  $.ajax({
            url  : api_url + "login/reset_password",
            type : "POST",
            data : new FormData($('#reset-password-form')[0]),   
            dataType : "JSON",   
            cache: false,
            contentType: false,
            processData: false,   
            beforeSend:function(){
              $('.reset-password-btn').attr('disabled',true).text(loading);
            },       
            success: function(resp){
             if(resp.status == 200){
                $('#reset-password-form')[0].reset();
                showToaster('success','Success',resp.message);
                setTimeout(function(){
                  window.location.href = '../admin/login';
                },500);
            }else{
                showToaster('error','Error !',resp.message);  
              }
              $('.reset-password-btn').attr('disabled',false).text('Reset Passwrod');
          },
            error:function(jqXHR, exception){
              manageAjaxError(jqXHR,exception);
                $('.reset-password-btn').attr('disabled',false).text('Reset Passwrod');
            }
        });
});

/************ Admin Reset Password Script End *******/

});
