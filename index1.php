<?
session_start();
error_reporting(0);
include_once('lib/config.php');
extract($_REQUEST);

$_SESSION['alert_message'] = 1;
unset($_SESSION['tcs_userid']);

if($_SESSION['tcs_userid'] != ""){ ?>
    <script>window.location="home.php";</script>
<?php exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        <!-- META SECTION -->
        <title>Login :: Approval Desk :: <?php echo $site_title; ?></title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->
        <link rel="stylesheet" type="text/css" id="theme" href="css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->

        <script src="js/jquery.js" type="text/javascript"></script>
        <script>
        function notifyMe(subject, message) {
          if (!("Notification" in window)) {
            alert("This browser does not support desktop notification");
          }
          else if (Notification.permission === "granted") {
                var options = {
                        body: message,
                        icon: "images/notification.png",
                        dir : "ltr"
                     };
                  var notification = new Notification(subject,options);
                  setTimeout(function(){ notification.close(); },5000);
          }
          else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {
              if (!('permission' in Notification)) {
                Notification.permission = permission;
              }
            
              if (permission === "granted") {
                var options = {
                      body: message,
                      icon: "images/notification.png",
                      dir : "ltr"
                  };
                var notification = new Notification(subject,options);
                setTimeout(function(){ notification.close(); },5000);
              }
            });
          }
        }

        function call_notification()
        {
            $.ajax({
                method  : 'POST',
                url     : "desktop_notification.php",
                success : function(data){
                    var str = data;
                    var res = str.split("!!");
                    if(res[0] != 0) {
                        var msg = 'Hi, You have ' + res[0] + ' Pending Message(s) waiting for Your Approval. \nKindly login and verify';
                        notifyMe("New Notification from Approval Desk", msg);
                    }
                    if(res[1] != 0) {
                        var msg = 'Hi, You have ' + res[1] + ' Message(s) waiting for Finish & Print. \nKindly login and verify';
                        notifyMe("Print Notification from Approval Desk", msg);
                    }
                    setTimeout(function(){ call_notification(); }, 300000); // 300000 milliseconds - 5 mins once it will call this function.
                }
            })
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        }
        
        $(document).ready(function(){
              var tcs_empsrno = getCookie("cookietcs_empsrno");
              //if(tcs_empsrno != '') {
                call_notification();
              //}
        });
        
        function movetoNext(current, nextFieldID) {
            if (current.value.length >= current.maxLength) {
                document.getElementById(nextFieldID).focus();
            }
        }
        </script>
    </head>
    <body>
        <div id="load_page" style='display:block;padding:12% 40%;'></div>
        <div class="login-container">
        <form name="frm_login" id='frm_login' method="post" action="" >
            <div class="login-box animated fadeInDown">
                <div class="login-logo"></div>
                <div class="login-body">
                    <div class="login-title"><strong>Log In</strong> to your Approval Desk account</div>
                    <div id="result"></div>
                    <div class="tags_clear"></div>

                    <form action="index.html" class="form-horizontal" method="post">
                        <input type="hidden" class="form-control" name='function' id='function' tabindex="1" value='signin' />
                       
                    <div class="form-group">
                        <div class="col-md-12 has-feedback">
                            <input type="text" autofocus title='Enter your existing CENTRA Login Username' tabindex="1" name="uname" id="uname" value='<?=$_COOKIE['uname']?>' onkeyup="movetoNext(this, 'password')" class="form-control" required maxlength="7" autocomplete="on"/>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            <span class="floating-label">Usercode *</span>
                        </div>
                    </div>
                    <div class="tags_clear height10px"></div>
                    <div class="form-group has-feedback">
                        <div class="col-md-12">
                            <input type="password" title='Enter your existing CENTRA Login Password' tabindex="2" name="password" id="password" value='<?=$_COOKIE['password']?>' required maxlength="10" autocomplete="off" class="form-control"/>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <span class="floating-label">Password *</span>
                        </div>
                    </div>
                     <div class="tags_clear height10px"></div>
                    <div class="form-group has-feedback">
            <select class="form-control" tabindex="3" title='Choose Any Group' name='selected_section_group' id='selected_section_group' onchange="select_range()"> 
                <option value='PURCHASE' <? if ($_COOKIE['selected_section_group'] == "PURCHASE") { ?> selected <? } else { ?> selected <? } ?> style="color:#000000; font-weight:bold;">PURCHASE</option>
                <option value='SYSTEM' <? if ($_COOKIE['selected_section_group'] == "SYSTEM") { ?> selected <? } ?> style="color:#000000; font-weight:bold;">SYSTEM</option>
                <option value='ADMIN' <? if ($_COOKIE['selected_section_group'] == "ADMIN") { ?> selected <? } ?> style="color:#000000; font-weight:bold;">ADMIN</option>
                <option value='CRM' <? if ($_COOKIE['selected_section_group'] == "CRM") { ?> selected <? } ?> style="color:#000000; font-weight:bold;">CRM</option>
                <option value='JEWELLERY' <? if ($_COOKIE['selected_section_group'] == "JEWELLERY") { ?> selected <? } ?> style="color:#000000; font-weight:bold;">JEWELLERY</option>
                <option value='APPROVAL DESK' <? if ($_COOKIE['selected_section_group'] == "APPROVAL DESK") { ?> selected <? } ?> style="color:#000000; font-weight:bold;">APPROVAL DESK</option>
                <option value='OFFLINE REPORT' <? if ($_COOKIE['selected_section_group'] == "OFFLINE REPORT") { ?> selected <? } ?> style="color:#000000; font-weight:bold;">OFFLINE REPORT</option>
            </select>
          </div>
                    <div class="tags_clear height10px"></div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="checkbox" tabindex="4" title='REMEMBER ME' name="rememberme" <? if ($_COOKIE['loggedIn'] == "yes") { ?> checked="checked" <? } ?> id="rememberme"> REMEMBER ME
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" name="hid_action" id="hid_action" value="<?=$_GET['action']?>">
                            <button type="submit" name="submit" id='submit' title='Login' tabindex="5" onClick="return Validate()" class="btn btn-info btn-block" style='float:left; margin-right:1%;' value="Login">LOGIN</button>
                        </div>
                    </div>
                    <div class="tags_clear height10px"></div>
                    <!-- <div class="login-or">OR</div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <button class="btn btn-info btn-block btn-twitter"><span class="fa fa-twitter"></span> Twitter</button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-info btn-block btn-facebook"><span class="fa fa-facebook"></span> Facebook</button>
                        </div>
                        <div class="col-md-4">                            
                            <button class="btn btn-info btn-block btn-google"><span class="fa fa-google-plus"></span> Google</button>
                        </div>
                    </div>
                    <div class="login-subtitle">
                        Don't have an account yet? <a href="#">Create an account</a>
                    </div> -->
                    </form>
                </div>
                <div class="login-footer">
                    <div class="pull-left">
                        &copy; <? echo date("Y")." ".$site_title; ?>
                    </div>
                    <!-- <div class="pull-right">
                        <a href="#">About</a> |
                        <a href="#">Privacy</a> |
                        <a href="#">Contact Us</a>
                    </div> -->
                </div>
            </div>
        </form>
        </div>
        
    </body>
</html>

<script src="js/bootstrap.js"></script>
<script type="text/javascript">
    $("#load_page").fadeOut("slow");

    $(document).tooltip({ selector: "[title]",
                          placement: "top",
                          trigger: "hover",
                          animation: false}); 
    
    setTimeout(function(){
      $('#entry_successmsg').remove();
    }, 5000);

    function Validate(){
        var username=document.frm_login.uname;
        var password=document.frm_login.password;

        if ((username.value==null)||(username.value=="")){
            //alert(' Please enter the username ');
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the usercode";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            
            username.focus()
            return false;
        }

        if ((password.value==null)||(password.value=="")){
            //alert(' Please enter the password ');
            var ALERT_TITLE = "Message";
            var ALERTMSG = "Please enter the password";
            createCustomAlert(ALERTMSG, ALERT_TITLE);
            
            password.focus()
            return false;
        }
        return true;
    }

    function entername()
    {
        document.getElementById('uname').focus();
        return false;
    }
    
    $("#submit").click(function() { 
        //get input field values
        var uname       = $('#uname').val(); 
        var password    = $('#password').val();
        var flag = true;
        /********validate all our form fields***********/
        /* Name field validation  */
        if(uname == "") {
            $('#uname').css('border-color','red'); 
            flag = false;
        }
        /* password field validation  */
        if(password == "") {
            $('#password').css('border-color','red'); 
            flag = false;
        }
        /********Validation end here ****/
        /* If all are ok then we send ajax request to process_connect.php *******/
        if(flag) 
        {
            var data_serialize = $("#frm_login").serialize();
            $.ajax({
                type: 'post',
                url: "lib/process_connect.php", 
                dataType: 'json',
                // data: 'function=signin&uname='+uname+'&password='+password,
                data : data_serialize,
                beforeSend: function() {
                    
                    $('#submit').attr('disabled', true);
                    // $('#loader').html('<span class="wait">&nbsp;<img src="images/loading.gif" alt="" /></span>');
                    $('#load_page').show();
                },
                complete: function() {
                    
                    $('#submit').attr('disabled', false);
                    // $('.wait').remove();
                    $('#load_page').hide();
                },  
                success: function(response)
                {
                    
                    // alert("++++++"+response+"+++++++");
                    if(response.type == 'error') {
                        output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                        // output += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';  
                        output += response.msg+'</div>';
                        $('#submit').attr('disabled', false);
                        // $('#loader').html('');

                        // alert("err-" + response.info);

                        if(response.info != '' && response.info != undefined)
                            window.location = response.info;
                    } else {
                        output = '<div class="alert alert-success alert-dismissible fade in" role="alert">';
                        output += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                        output += response.msg+'</div>';
                        $('input[type=text]').val('');

                        // alert("suc-" + response.info);

                        if(response.info != '' && response.info != undefined)
                            window.location = response.info;
                    }
                    $('#load_page').hide();

                    $("#result").hide().html(output).slideDown();           
                },
                error: function(response, status, error) 
                {
                    /* for (var prop in response) {
                        console.log(prop);
                        alert("---------"+prop.msg+"---------");
                    } */

                    // var err = eval("(" + response.responseText + ")");
                    // alert(err.Message);

                    output = '<div class="alert alert-danger alert-dismissible fade in" role="alert">';
                    output += response.msg+'</div>';
                    $('#submit').attr('disabled', false);
                    // $('#loader').html('');

                    // alert(response.info + "err0r-" + response.msg);

                    if(response.info != '' && response.info != undefined)
                        window.location = response.info;
                }
            });
        }
    });

    //reset previously set border colors and hide all message on .keyup()
    $("#contactform input").keyup(function() { 
        $("#contactform input").css('border-color',''); 
        $("#result").slideUp();
    });

    
    /******************** Change Default Alert Box ***********************/
    var ALERT_BUTTON_TEXT = "OK";
    /* if(document.getElementById) {
        window.alert = function(txt) {
            var ALERT_TITLE = "GA Title";

            var tga = document.getElementById("id_ga").value;
            createCustomAlert(tga, ALERT_TITLE);
        }
    } */

    function createCustomAlert(txt, title) {
        d = document;

        if(d.getElementById("modalContainer")) return;

        mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
        mObj.id = "modalContainer";
        mObj.style.height = d.documentElement.scrollHeight + "px";
        
        alertObj = mObj.appendChild(d.createElement("div"));
        alertObj.id = "alertBox";
        if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
        alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
        alertObj.style.visiblity="visible";

        h1 = alertObj.appendChild(d.createElement("h1"));
        h1.appendChild(d.createTextNode(title));

        msg = alertObj.appendChild(d.createElement("p"));
        //msg.appendChild(d.createTextNode(txt));
        msg.innerHTML = txt;

        btn = alertObj.appendChild(d.createElement("a"));
        btn.id = "closeBtn";
        btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
        btn.href = "#";
        btn.focus();
        btn.onclick = function() { removeCustomAlert();return false; }

        alertObj.style.display = "block";
    }

    function removeCustomAlert() {
        document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
    }

    function ful(){
        //alert('Alert this pages');
    }
    /******************** Change Default Alert Box ***********************/
</script>