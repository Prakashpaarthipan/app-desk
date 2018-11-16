<?
session_start();
// error_reporting(E_ALL);
header('X-UA-Compatible: IE=edge');
include_once('../lib/config.php');
include_once('../lib/function_connect.php');
extract($_REQUEST);

if(isset($_REQUEST['sbmt_signin']) && ($_REQUEST['sbmt_signin'] == "Signin"))
{
    $rememberme = strip_tags($_REQUEST['rememberme']);
    // echo "+++++".$rememberme."+++++";
    if ($rememberme) 
    {
        setcookie("loggedIn", "yes", time()+31536000);
        setcookie("cookietlu_user_mobile", $_REQUEST['tlu_txt_mobile_no'], time()+31536000);
        setcookie("cookietlu_user_password", $_REQUEST['tlu_txt_psw'], time()+31536000);
        
        $_SESSION['tlu_user_mobile']=$_REQUEST['tlu_txt_mobile_no'];
        $_SESSION['tlu_cuscode']=$_REQUEST['tlu_txt_mobile_no'];
        /* $_SESSION['tlu_txt_psw']=$_REQUEST['tlu_txt_psw'];
        $_SESSION['tlu_txt_mobile_no']=$_REQUEST['tlu_txt_mobile_no'];
        $_SESSION['rememberme']=$_REQUEST['rememberme']; */
    }
    if($_REQUEST['tlu_txt_mobile_no'] !='' and $_REQUEST['tlu_txt_psw'] !='')
    {
        // echo "select * from customers_tailyou where CUSMOBL = '".$_REQUEST['tlu_txt_mobile_no']."' and USRPASS = '".$_REQUEST['tlu_txt_psw']."'"; echo "***".$_COOKIE['cookietlu_user_mobile']."***"; 
        $sql_customer = select_query_json("select * from customers_tailyou where CUSMOBL = '".$_REQUEST['tlu_txt_mobile_no']."' and USRPASS = '".$_REQUEST['tlu_txt_psw']."'", 'Centra', 'TEST');
        // print_r($sql_customer); // exit;
        $_SESSION['tlu_brncode'] = $sql_customer[0]['BRNCODE'];
        $_SESSION['tlu_cuscode'] = $sql_customer[0]['CUSCODE'];
        $_SESSION['tlu_user_mobile'] = $sql_customer[0]['CUSMOBL'];
        $_SESSION['tlu_cusname'] = strtoupper($sql_customer[0]['CUSNAME']);
        $_SESSION['tlu_cusemal'] = strtolower($sql_customer[0]['CUSEMAL']);
        $_SESSION['tlu_csimgpt'] = $sql_customer[0]['CSIMGPT'];
        $_SESSION['tlu_cntlogn'] = $sql_customer[0]['CNTLOGN'];

        if(count($sql_customer) > 0) { ?>
            <script>window.location='home.php';</script>
            <?php exit();
        } else { ?>
            <script>alert('Invalid Access Details. Kindly contact TAILYOU Team!!'); window.location='index.php';</script>
            <?php exit();            
        }
    } else { ?>
        <script>alert('Invalid Access Details. Kindly contact TAILYOU Team!!'); window.location='index.php';</script>
        <?php exit();            
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TAILYOU :: HOME</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icon.png"  type="image/x-icon"/>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="css/progress.css" rel="stylesheet">
    <link href="css/model.css" rel="stylesheet">
    <link href="css/easy-responsive-tabs.css" rel='stylesheet' type='text/css' />
    <style>
      #otp{
        display: none;
      }
      #pwd{
        display: none;
      }
      #setpwd_btn{
        display: none;
      }
      #sign_btn{
        display: none;
      }
      .bg-white{
        background: #fff !important;
      }
      #non_user{
        display: none;
      }
    </style>

</head>

<body ng-app="TCSApp" ng-controller="tailyou_Tracking" style="background:">
  <div class="loader"></div>
    <!-- header -->
    <div class="header" id="home">
        <div class="container">
            <div class="row" style="text-align: center;">
                <!-- <li> <a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Sign In </a></li>
			<li> <a href="#" data-toggle="modal" data-target="#myModal2"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sign Up </a></li> -->
                <div class="col-sm-4" style="color:#fff"><i class="fa fa-phone" aria-hidden="true"></i> Call : 0421-2242888</div>
                <div class="col-sm-4" style="color:#fff"><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="javascript:void(0)"  style="color:#fff" class="text-white">operation@tailyou.com</a></div>
                <div class="col-sm-4" style="color:#fff"><i class="fa fa-sign-in" aria-hidden="true"></i> <a href='javascript:void(0)' onclick="document.getElementById('id01').style.display='block'" style="width:auto;color:#fff">SIGNIN</a></div>
            </div>
        </div>
    </div>

    <div id="id01" class="modal">
      <form class="modal-content animate" action="index.php">
        <div class="imgcontainer">
          <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
        </div>
        <div id="mobile_number">
          <p><b>Mobile No : </b></p>
          <input class="bg-white form-control" type="text" placeholder="Enter Your Mobile No (9999999999)" onkeyup="validateNumber(this); movetoNext(this, 'tlu_txt_psw');" name="tlu_txt_mobile_no" id='tlu_txt_mobile_no' maxlength="10" value="<?=$_COOKIE['cookietlu_user_mobile']?>" required>
          <p><b>Password / OTP : </b></p>
          <input class="bg-white form-control" type="password" placeholder="Enter Your Password / OTP" name="tlu_txt_psw" id='tlu_txt_psw' maxlength="25" value="<?=$_COOKIE['cookietlu_user_password']?>" required>
        </div>
        <div id="check_btn">
          <label><input type="checkbox" name="rememberme" <? if ($_COOKIE['loggedIn'] == "yes") { ?> checked="checked" <? } ?> id="rememberme"> Remember me </label>
          <button type="submit" name="sbmt_signin" id='sbmt_signin' value="Signin">Signin</button>
        </div>

        <? /*

        $_SESSION['tlu_txt_psw']=$_REQUEST['tlu_txt_psw'];
        $_SESSION['tlu_txt_mobile_no']=$_REQUEST['tlu_txt_mobile_no'];
        $_SESSION['rememberme']=$_REQUEST['rememberme'];

         <div id="pwd">
          <p><b>Name : </b></p>
          <input ng-repeat="x in user_status" class="bg-white form-control" type="text" value="{{x.CUSNAME}}" readonly >

          <p><b>Password : </b></p>
          <input class="form-control" type="password" placeholder="Enter Your Password" name="tlu_txt_psw" id='tlu_txt_psw' required>
          <p class="text-center">
            <input type="checkbox" checked="checked" name="remember"> Remember me
          </p>
        </div>
        <div id="non_user">
          <p class="blink-me"><b>THE USER DOES NOT EXIST. PLEASE CONTACT THE TAILYOU OFFICE. </b></p>
        </div>
        <div id="otp">
          <p><b>OTP : </b></p>
          <input class="form-control" type="password" placeholder="OTP" name="tlu_txt_psw" id='tlu_txt_psw' required>
        </div>
        <div id="setpwd_btn">
          <button type="submit">Set password</button>
        </div>
        <div id="sign_btn">
          <button type="submit">Signin</button>
        </div> */ ?>
      </form>
    </div>

    <!-- //header -->
    <!-- header-bot -->
    <div class="header-bot">
        <div class="header-bot_inner_wthreeinfo_header_mid">
            <!-- header-bot -->
            <div class="col-xs-12 logo_agile">
                <h1 class="text-center"><a href="index.php"><img src="images/tailyou-younew.png" border="0" style="box-shadow:2px 5px 20px grey;border-radius:50%;"></a></h1>
            </div>
            <!-- header-bot -->
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- //header-bot -->

    <!-- /new_arrivals -->
    <div class="new_arrivals_agile_w3ls_info">
        <div class="container">
            <h3 class="wthree_text_info"><span style="text-shadow: 0px 2px 5px #ffffff">track your order</span></h3>
            <div id="horizontalTab">
                <div class="col-md-3 header-middle"></div>
                <div class="col-md-6 header-middle">
                    <form ng-submit="orderReport(order_id)">
                        <input type="search" name="minutes" onkeyup="validateNumber(this);" ng-model="order_id" name="txt_track_order" id="txt_track_order" placeholder="Enter the Order ID eg:(2018-192)" required="" maxlength="13" autofocus="">
                        <input type="submit" name="sbmt_track_order" id="sbmt_track_order" value=" " disabled>
                        <div class="clearfix"></div>
                    </form>
                </div>
                <div class="col-md-3 header-middle"></div>
            </div>
            <div ng-view></div>
        </div>
    </div>
    <!-- //new_arrivals -->
    <!-- /we-offer -->
    <div class="sale-w3ls_new js__parallax-window">
    </div>
    <!-- //we-offer -->

    <!-- footer -->
    <div class="footer">
        <div class="footer_agile_inner_info_w3l">
            <div class="col-md-12 footer-right">
                <div class="sign-grds">
                    <div class="col-xs-6 col-xs-offset-3 sign-gd-two">
                        <h4>TAILYOU Store <span>Information</span></h4>
                        <div class="w3-address">
                            <div class="w3-address-grid">
                                <div class="w3-address-left">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                </div>
                                <div class="w3-address-right">
                                    <h6 class="text-left">Phone Number</h6>
                                    <p class="text-left">0421-2242888</p>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                            <div class="w3-address-grid">
                                <div class="w3-address-left">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                </div>
                                <div class="w3-address-right">
                                    <h6 class="text-left">Email Address</h6>
                                    <p class="text-left">Email : operation@tailyou.com</p>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                            <div class="w3-address-grid">
                                <div class="w3-address-left">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                                <div class="w3-address-right">
                                    <h6 class="text-left">Location</h6>
                                    <p class="text-left">#77, NEW MARKET STREET, TIRUPUR - 641604

                                    </p>
                                </div>
                                <div class="clearfix"> </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 sign-gd">
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <p class="copy-right">&copy;<?=date("Y")?> TAILYOU. All Rights Reserved.</p>
            </div>
            <div class="clearfix"></div>


            <div class="row">
                <a href="employee_portal/index.php" target="_blank" style="color:#000000 !important; text-decoration: underline;">Employee Portal</a>
            </div>
            <div class="clearfix"></div>

        </div>
    </div>
    <!-- //footer -->

    <script>
    function movetoNext(current, nextFieldID) {
        if (current.value.length >= current.maxLength) {
            document.getElementById(nextFieldID).focus();
        }
    }

        function validateNumber(minutes)
        {
          var maintainplus = '';
          var numval = minutes.value
          if ( numval.charAt(0)=='+' )
          {
            var maintainplus = '';
          }
          curphonevar = numval.replace(/[\\A-Za-z!"£$%^&\,*+_={};:'@#~,.Š\/<>?|`¬\]\[]/g,'');
          minutes.value = maintainplus + curphonevar;
          var maintainplus = '';
          minutes.focus;

          if ($("#txt_track_order").val().length > 7)
            {
                  $("#sbmt_track_order").prop("disabled", false);
                  $("#sbmt_track_order").trigger('change');
            }
            else
            {
                $("#sbmt_track_order").prop("disabled", true);
                $("#sbmt_track_order").trigger('change');
            }
        }
        $(document).ready(function(){
          $("#sbmt_track_order").click(function(){
              $(".show_flow").delay(10).slideDown(2000);
              $(".loader").css({"display":"block"});
                $(".loader").fadeIn();

                $(".loader").delay(100).fadeOut();
          });

          $(".confirm .imgcircle , .process .imgcircle, .quality .imgcircle").css({"background-color":"#98D091"});
          $(".confirm span.line, .process span.line").css({"background-color":"#98D091"});

       });
    </script>
    <!-- js -->
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <!-- //js -->
    <script src="js/modernizr.custom.js"></script>
    <!-- Custom-JavaScript-File-Links -->
    <!-- cart-js -->
    <script src="js/minicart.min.js"></script>
    <script>
        // Mini Cart
        paypal.minicart.render({
            action: '#'
        });

        if (~window.location.search.indexOf('reset=true')) {
            paypal.minicart.reset();
        }
    </script>

    <!-- //cart-js -->
    <!-- script for responsive tabs -->
    <script src="js/easy-responsive-tabs.js"></script>
    <script>
        $(document).ready(function() {
            $('#horizontalTab').easyResponsiveTabs({
                type: 'default', //Types: default, vertical, accordion
                width: 'auto', //auto or any width like 600px
                fit: true, // 100% fit in a container
                closed: 'accordion', // Start closed if in accordion view
                activate: function(event) { // Callback function if tab is switched
                    var $tab = $(this);
                    var $info = $('#tabInfo');
                    var $name = $('span', $info);
                    $name.text($tab.text());
                    $info.show();
                }
            });
            $('#verticalTab').easyResponsiveTabs({
                type: 'vertical',
                width: 'auto',
                fit: true
            });
        });
    </script>
    <!-- //script for responsive tabs -->
    <!-- stats -->
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.countup.js"></script>
    <script>
        $('.counter').countUp();
    </script>
    <!-- //stats -->
    <!-- start-smoth-scrolling -->
    <script type="text/javascript" src="js/move-top.js"></script>
    <script type="text/javascript" src="js/jquery.easing.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".scroll").click(function(event) {
                event.preventDefault();
                $('html,body').animate({
                    scrollTop: $(this.hash).offset().top
                }, 1000);
            });
        });
    </script>
    <!-- here stars scrolling icon -->
    <script type="text/javascript">
        $(document).ready(function() {
            /*
            	var defaults = {
            	containerID: 'toTop', // fading element id
            	containerHoverID: 'toTopHover', // fading element hover id
            	scrollSpeed: 1200,
            	easingType: 'linear'
            	};
            */

            $().UItoTop({
                easingType: 'easeOutQuart'
            });
        });

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
    </script>
    <!-- //here ends scrolling icon -->

    <!-- for bootstrap working -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/SmoothScroll.js"></script>
    <!-- angular JS -->
    <script src="js/angular.min.js"></script>
    <script src="js/angular-route.js"></script>
    <script type="text/javascript" src="app.js"></script>
</body>
</html>