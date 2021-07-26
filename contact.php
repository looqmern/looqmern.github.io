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
      $subject = 'Delawiz Studio Enquiry';
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
<title>Delawiz Studio NJ- Contact</title>
<meta name="generator" content="Delawiz Productions">
<link rel="shortcut icon" href="delstudio%20logo%20small.png">
<script type="text/javascript" src="jscookmenu.min.js"></script>
<link rel="stylesheet" href="contact.css" type="text/css">
<!--[if lt IE 7]>
<style type="text/css">
   img { behavior: url("pngfix.htc"); }
</style>
<![endif]-->
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

<div id="wb_Form1" style="position:absolute;left:600px;top:361px;width:295px;height:306px;z-index:21;">
<form name="contact" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="Form1">
<div id="wb_Text2" style="position:absolute;left:10px;top:15px;width:55px;height:18px;z-index:5;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Name:</span></div>
<input type="text" id="Editbox1" style="position:absolute;left:75px;top:15px;width:198px;height:20px;line-height:20px;z-index:6;" name="Name" value="">
<div id="wb_Text3" style="position:absolute;left:10px;top:42px;width:55px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">City:</span></div>
<input type="text" id="Editbox2" style="position:absolute;left:75px;top:42px;width:198px;height:20px;line-height:20px;z-index:8;" name="City" value="">
<div id="wb_Text4" style="position:absolute;left:10px;top:69px;width:55px;height:18px;z-index:9;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">State:</span></div>
<input type="text" id="Editbox3" style="position:absolute;left:75px;top:69px;width:198px;height:20px;line-height:20px;z-index:10;" name="State" value="">
<div id="wb_Text5" style="position:absolute;left:10px;top:96px;width:55px;height:18px;z-index:11;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Phone:</span></div>
<input type="text" id="Editbox4" style="position:absolute;left:75px;top:96px;width:198px;height:20px;line-height:20px;z-index:12;" name="Phone" value="">
<div id="wb_Text6" style="position:absolute;left:10px;top:123px;width:55px;height:18px;z-index:13;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Email:</span></div>
<input type="text" id="Editbox5" style="position:absolute;left:75px;top:123px;width:198px;height:20px;line-height:20px;z-index:14;" name="Email" value="">
<div id="wb_Text7" style="position:absolute;left:10px;top:150px;width:55px;height:18px;z-index:15;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Subject:</span></div>
<select name="Subject" size="1" id="Combobox1" style="position:absolute;left:75px;top:150px;width:200px;height:24px;z-index:16;">
<option>General Enquiry</option>
<option>Packages & Pricing</option>
<option>Session Details</option>
</select>
<div id="wb_Text8" style="position:absolute;left:10px;top:179px;width:55px;height:18px;z-index:17;text-align:left;">
<span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;">Message</span></div>
<textarea name="Message" id="TextArea1" style="position:absolute;left:75px;top:179px;width:198px;height:98px;z-index:18;" rows="4" cols="27"></textarea>
<input type="submit" id="Button1" name="" value="Send" style="position:absolute;left:75px;top:284px;width:60px;height:19px;z-index:19;">
</form>
</div>
<div id="wb_Text1" style="position:absolute;left:339px;top:422px;width:227px;height:72px;text-align:justify;z-index:22;">
<span style="color:#663300;font-family:'Trebuchet MS';font-size:13px;">Delawiz Studio<br>Photographer-</span><span style="color:#000000;font-family:'Trebuchet MS';font-size:13px;"> </span><span style="color:#E6E6FA;font-family:'Trebuchet MS';font-size:13px;">Lukman<br></span><span style="color:#663300;font-family:'Trebuchet MS';font-size:13px;">Number:</span><span style="color:#FFFFFF;font-family:'Trebuchet MS';font-size:13px;">(551)-556-5903</span><span style="color:#282828;font-family:'Trebuchet MS';font-size:13px;"><br></span><span style="color:#663300;font-family:'Trebuchet MS';font-size:13px;">Email:</span><span style="color:#282828;font-family:'Trebuchet MS';font-size:13px;"> </span><span style="color:#E6E6FA;font-family:'Trebuchet MS';font-size:13px;">info@delawiz.com</span></div>
<img src="images/contact_0012.png" align="top" alt="" border="0" width="217" height="60" style="position:absolute;left:227px;top:296px;width:217px;height:60px;z-index:23">
</div>
</body>
</html>