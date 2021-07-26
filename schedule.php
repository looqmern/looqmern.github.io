<?php
if (session_id() == "")
{
   session_start();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   if (isset($_POST['captcha_code'],$_SESSION['random_txt']) && md5($_POST['captcha_code']) == $_SESSION['random_txt'])
   {
      unset($_POST['captcha_code'],$_SESSION['random_txt']);
   }
   else
   {
      $errorcode = file_get_contents('./engagements.php');
      $replace = "##error##";
      $errorcode = str_replace($replace, 'The entered code was wrong.', $errorcode);
      echo $errorcode;
      exit;
   }
}
?>
<?php
   function ValidateEmail($email)
   {
      $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
      return preg_match($pattern, $email);
   }

   if ($_SERVER['REQUEST_METHOD'] == 'POST')
   {
      $mailto = 'info@delawiz.com';
      $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
      $subject = 'Session Schedule at Delawiz Studio';
      $message = 'The details';
      $success_url = './Success.php';
      $error_url = './engagements.php';
      $error = '';
      $eol = "\n";
      $max_filesize = isset($_POST['filesize']) ? $_POST['filesize'] * 1024 : 1024000;
      $boundary = md5(uniqid(time()));

      $header  = 'From: '.$mailfrom.$eol;
      $header .= 'Reply-To: '.$mailfrom.$eol;
      $header .= 'MIME-Version: 1.0'.$eol;
      $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
      $header .= 'X-Mailer: PHP v'.phpversion().$eol;
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address is invalid!\n<br>";
      }

      if (!empty($error))
      {
         $errorcode = file_get_contents($error_url);
         $replace = "##error##";
         $errorcode = str_replace($replace, $error, $errorcode);
         echo $errorcode;
         exit;
      }

      $internalfields = array ("submit", "reset", "send", "captcha_code");
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (!is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
         }
      }

      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=ISO-8859-1'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
          foreach ($_FILES as $key => $value)
          {
             if ($_FILES[$key]['error'] == 0 && $_FILES[$key]['size'] <= $max_filesize)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      mail($mailto, $subject, $body, $header);
      header('Location: '.$success_url);
      exit;
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Delawiz Studio New Jersey- Schedule Session</title>
<meta name="generator" content="Delawiz Productions">
<link rel="shortcut icon" href="delstudio%20logo%20small.png">
<script type="text/javascript" src="jscookmenu.min.js"></script>
<link rel="stylesheet" href="cupertino/jquery.ui.all.css" type="text/css">
<link rel="stylesheet" href="wb.validation.css" type="text/css">
<link rel="stylesheet" href="schedule.css" type="text/css">
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="wb.validation.min.js"></script>
<script type="text/javascript" src="jquery.ui.core.min.js"></script>
<script type="text/javascript" src="jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="jquery.ui.datepicker.min.js"></script>
<script type="text/javascript">
<!--
function Validatesession_schedule(theForm)
{
   var regexp;
   regexp = /^[-+]?\d*\.?\d*$/;
   if (!regexp.test(theForm.Editbox2.value))
   {
      alert("Please enter only digit characters in the \"Mobile No\" field.");
      theForm.Editbox2.focus();
      return false;
   }
   if (theForm.Editbox2.value == "")
   {
      alert("Please enter a value for the \"Mobile No\" field.");
      theForm.Editbox2.focus();
      return false;
   }
   if (theForm.Editbox2.value.length < 10)
   {
      alert("Please enter at least 10 characters in the \"Mobile No\" field.");
      theForm.Editbox2.focus();
      return false;
   }
   return true;
}
//-->
</script>
<script type="text/javascript">
$(document).ready(function()
{
   $("#Form1").submit(function(event)
   {
      var isValid = $.validate.form(this);
      return isValid;
   });
   $("#Editbox3").validate(
   {
      required: false,
      type: 'email',
      color_text: '#000000',
      color_hint: '#00FF00',
      color_error: '#FF0000',
      color_border: '#808080',
      nohint: false,
      font_family: 'Arial',
      font_size: '13px',
      position: 'topleft',
      offsetx: 0,
      offsety: 0,
      effect: 'none',
      error_text: ''
   });
   var jQueryDatePicker1Opts =
   {
      dateFormat: 'dd/mm/yy',
      changeMonth: false,
      changeYear: false,
      showButtonPanel: true,
      showAnim: 'show'
   };
   $("#jQueryDatePicker1").datepicker(jQueryDatePicker1Opts);
});
</script>
<!--[if lt IE 7]>
<style type="text/css">
   img { behavior: url("pngfix.htc"); }
</style>
<![endif]-->
<link type="text/css" rel="stylesheet" href="/demo/ab3/css/calendar.css" />
<link type="text/css" rel="stylesheet" href="/demo/ab3/ajax.php?controller=calendars&action=css&cid=8071" />
<script type="text/javascript" src="/demo/ab3/js/custom.js"></script>
<script type="text/javascript" src="/demo/ab3/js/front.js"></script>

<script type="text/javascript">
   var clicktracker_url = "http://www.delawiz.com/clicktracker/click-tracker.php";
   var clicktracker_domains    =  Array("", "delawiz.com", "www.delawiz.com");
   var clicktracker_extensions =  Array("doc", "exe", "rar", "html", "php", "zip");
   </script>
<script type="text/javascript" src="http://www.delawiz.com/clicktracker/click-tracker.js"></script>

</head>
<body>
<div id="container">
<div id="wb_JavaScript1" style="position:absolute;left:689px;top:7px;width:203px;height:21px;z-index:0;">
<div style="color:#B0C4DE;font-size:12px;font-family:Comic Sans MS;font-weight:normal;font-style:normal;text-decoration:none" id="basicdate"></div>
<script type="text/javascript">
   var now = new Date();
   var days = new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
   var months = new Array('January','February','March','April','May','June','July','August','September','October','November','December');
   var date = ((now.getDate() < 10) ? "0" : "") + now.getDate();
   var year = (now.getYear() < 1000) ? now.getYear() + 1900 : now.getYear();
   today = days[now.getDay()] + ", " + months[now.getMonth()] + " " + date + ", " + year;
   var basicdate = document.getElementById('basicdate');
   basicdate.innerHTML = today;
</script>


</div>
<div id="wb_Text1" style="position:absolute;left:184px;top:806px;width:672px;height:54px;text-align:center;z-index:1;">
<span style="color:#68838B;font-family:'Trebuchet MS';font-size:12px;">Copyright 2013 Delawiz Studios<br>A Delawiz Productions company |&nbsp; All Rights Reserved&nbsp; |&nbsp; info@delawiz.com<br></span></div>
<div id="wb_Image2" style="position:absolute;left:374px;top:42px;width:186px;height:61px;z-index:2;">
<img src="images/master_0014.png" id="Image2" alt="" border="0" style="width:186px;height:61px;"></div>
<div id="wb_MenuBar1" style="position:absolute;left:327px;top:118px;width:296px;height:45px;z-index:1003;">
<div id="MenuBar1">
<ul style="display:none;">
<li><span></span><a href="./index.html" target="_self">Home</a>
</li>
<li><span></span><span>Tour</span>
<ul>
<li><span></span><a href="./weddings.php" target="_self">Weddings</a>
</li>
<li><span></span><a href="./portraits.php" target="_self">Portraits&nbsp;&amp;&nbsp;closeups</a>
</li>
<li><span></span><a href="./kidsnbabies.php" target="_self">Babies&nbsp;&amp;&nbsp;Kids</a>
</li>
<li><span></span><a href="./events.php" target="_self">Events</a>
</li>
<li><span></span><a href="./engagements.php" target="_self">Engagements</a>
</li>
</ul>
</li>
<li><span></span><a href="./schedule.php" target="_self">Schedule</a>
</li>
<li><span></span><a href="./about.php" target="_self">About</a>
</li>
<li><span></span><a href="./contact.php" target="_self">Contact</a>
</li>
</ul>
</div>
<script type="text/javascript">
<!--
var cmMenuBar1 =
{
   mainFolderLeft: '',
   mainFolderRight: '',
   mainItemLeft: '',
   mainItemRight: '',
   folderLeft: '',
   folderRight: '',
   itemLeft: '',
   itemRight: '',
   mainSpacing: 0,
   subSpacing: 0,
   delay: 100,
   offsetHMainAdjust: [0, 0],
   offsetSubAdjust: [0, 0]
};
var cmThemeMenuBar1HSplit = [_cmNoClick, '<td colspan="3" class="ThemeMenuBar1MenuSplit"><div class="ThemeMenuBar1MenuSplit"><\/div><\/td>'];
var cmThemeMenuBar1MainHSplit = [_cmNoClick, '<td colspan="3" class="ThemeMenuBar1MenuSplit"><div class="ThemeMenuBar1MenuSplit"><\/div><\/td>'];
var cmThemeMenuBar1MainVSplit = [_cmNoClick, '<div class="ThemeMenuBar1MenuVSplit">|<\/div>'];

cmMenuBar1.effect = new CMFadingEffect(30, 50);
cmDrawFromText('MenuBar1', 'hbr', cmMenuBar1, 'ThemeMenuBar1');
-->
</script>
</div>
<!-- add this -->
<div id="Html1" style="position:absolute;left:0px;top:78px;width:286px;height:28px;z-index:4">
<div class="fb-like" data-href="https://www.facebook.com/pages/Delawiz-Productions/163095943723689" data-width="200" data-colorscheme="dark" data-layout="box_count" data-show-faces="false" data-send="true"></div>
</div>

<div id="wb_Text10" style="position:absolute;left:509px;top:667px;width:280px;height:18px;z-index:24;text-align:left;">
&nbsp;</div>
<div id="wb_Form1" style="position:absolute;left:582px;top:191px;width:385px;height:457px;z-index:25;">
<form name="session_schedule" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="Form1" onsubmit="return Validatesession_schedule(this)">
<div id="wb_Text2" style="position:absolute;left:39px;top:60px;width:105px;height:18px;z-index:5;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Full Name</span></div>
<input type="text" id="Editbox1" style="position:absolute;left:154px;top:59px;width:198px;height:20px;line-height:20px;z-index:6;" name="Full Name" value="" tabindex="1">
<div id="wb_Text3" style="position:absolute;left:39px;top:125px;width:105px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Mobile No</span></div>
<input type="text" id="Editbox2" style="position:absolute;left:154px;top:119px;width:198px;height:20px;line-height:20px;z-index:8;" name="Mobile No" value="" tabindex="4">
<div id="wb_Text4" style="position:absolute;left:39px;top:152px;width:105px;height:18px;z-index:9;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Email</span></div>
<input type="text" id="Editbox3" style="position:absolute;left:154px;top:148px;width:198px;height:20px;line-height:20px;z-index:10;" name="Email" value="" tabindex="5">
<div id="wb_Text5" style="position:absolute;left:39px;top:179px;width:105px;height:18px;z-index:11;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Location Address</span></div>
<input type="text" id="Editbox4" style="position:absolute;left:154px;top:177px;width:198px;height:20px;line-height:20px;z-index:12;" name="Location Address" value="" tabindex="6">
<div id="wb_Text6" style="position:absolute;left:39px;top:206px;width:105px;height:18px;z-index:13;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Session Date</span></div>
<div id="wb_Text7" style="position:absolute;left:39px;top:233px;width:105px;height:18px;z-index:14;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Session Time</span></div>
<div id="wb_Text9" style="position:absolute;left:39px;top:291px;width:105px;height:18px;z-index:15;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Comments</span></div>
<textarea name="Comments" id="TextArea1" style="position:absolute;left:154px;top:291px;width:198px;height:98px;z-index:16;" rows="4" cols="27" tabindex="10"></textarea>
<input type="submit" id="Button1" name="submit" value="Submit" style="position:absolute;left:152px;top:427px;width:67px;height:25px;z-index:17;" tabindex="12">
<div id="wb_Captcha1" style="position:absolute;left:48px;top:382px;width:246px;height:36px;z-index:18;">
<img src="captcha1.php" alt="Click for new image" title="Click for new image" style="cursor:pointer;width:100px;height:38px;" onclick="this.src='captcha1.php?'+Math.random()">
<input type="text" id="Captcha1Edit" style="position:absolute;left:105px;top:14px;width:146px;height:22px;line-height:22px;;" name="captcha_code" value="" tabindex="11"></div>
<div id="wb_Text11" style="position:absolute;left:39px;top:90px;width:114px;height:18px;z-index:19;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Purpose</span></div>
<input type="text" id="Editbox6" style="position:absolute;left:154px;top:90px;width:198px;height:20px;line-height:20px;z-index:20;" name="Baby's Name" value="" tabindex="2">
<input type="text" id="jQueryDatePicker1" style="position:absolute;left:153px;top:205px;width:180px;height:20px;line-height:20px;z-index:21;" name="Session Date" value="mm/dd/yyyy">
<input type="text" id="Editbox5" style="position:absolute;left:153px;top:233px;width:198px;height:20px;line-height:20px;z-index:22;" name="Session time" value="" tabindex="8">
</form>
</div>
<img src="images/schedule_0003.png" align="top" alt="" border="0" width="620" height="71" style="position:absolute;left:120px;top:175px;width:620px;height:71px;z-index:26">
</div>
</body>
</html>