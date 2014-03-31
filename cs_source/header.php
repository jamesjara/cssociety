<?php
include("custom_config.php");

// Compatibility with PHP Report Maker
if (!isset($Language)) {
	include_once "ewcfg9.php";
	include_once "ewshared9.php";
	$Language = new cLanguage();
}
?>
<!doctype html>
<html>
<head>
<script type="text/javascript" src="<?php echo conf_cdn; ?><?php echo ew_jQueryFile("jquery-%v.min.js") ?>"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo conf_brand;//$Language->ProjectPhrase("BodyTitle"); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo conf_cdn; ?><?php echo ew_YuiHost() ?>build/container/assets/skins/sam/container.css" />
<?php if (ew_IsMobile()) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo conf_cdn; ?><?php echo ew_jQueryFile("jquery.mobile-%v.min.css") ?>" />
<script type="text/javascript">
jQuery(document).bind("mobileinit", function() {
	jQuery.mobile.ajaxEnabled = false;
	jQuery.mobile.ignoreContentEnabled = true;
});
</script>
<script type="text/javascript" src="<?php echo conf_cdn; ?><?php echo ew_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo conf_cdn; ?><?php echo ew_YuiHost() ?>build/utilities/utilities.js"></script>
<script type="text/javascript" src="<?php echo conf_cdn; ?><?php echo ew_YuiHost() ?>build/json/json-min.js"></script>
<script type="text/javascript" src="<?php echo conf_cdn; ?><?php echo ew_YuiHost() ?>build/container/container-min.js"></script>
<script type="text/javascript" src="<?php echo conf_cdn; ?>phpjs/datenumber-min.js"></script>
<script type="text/javascript">
var EW_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EW_DATE_SEPARATOR = "/" || "/"; // Default date separator
var EW_DECIMAL_POINT = "<?php echo $DEFAULT_DECIMAL_POINT ?>";
var EW_THOUSANDS_SEP = "<?php echo $DEFAULT_THOUSANDS_SEP ?>";
var EW_UPLOAD_ALLOWED_FILE_EXT = "gif,jpg,jpeg,bmp,png,doc,xls,pdf,zip"; // Allowed upload file extension

// Ajax settings
var EW_RECORD_DELIMITER = "\r";
var EW_FIELD_DELIMITER = "|";
var EW_LOOKUP_FILE_NAME = "ewlookup9.php"; // Lookup file name
var EW_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EW_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries

// Common JavaScript messages
var EW_ADDOPT_BUTTON_SUBMIT_TEXT = "<?php echo ew_JsEncode2(ew_BtnCaption($Language->Phrase("AddBtn"))) ?>";
var EW_EMAIL_EXPORT_BUTTON_SUBMIT_TEXT = "<?php echo ew_JsEncode2(ew_BtnCaption($Language->Phrase("SendEmailBtn"))) ?>";
var EW_BUTTON_CANCEL_TEXT = "<?php echo ew_JsEncode2(ew_BtnCaption($Language->Phrase("CancelBtn"))) ?>";
var EW_DISABLE_BUTTON_ON_SUBMIT = false;

//var EW_IMAGE_FOLDER = "http://cdn.registrodemascotas.co.cr/"; // Image folder
var EW_IMAGE_FOLDER = "<?php echo conf_cdn.'/phpimages/'; ?>" // Image folder
</script>
<script type="text/javascript" src="<?php echo conf_cdn; ?>phpjs/jsrender.js"></script>
<script type="text/javascript" src="<?php echo conf_cdn; ?>phpjs/ewp9.js"></script>
<script type="text/javascript" src="<?php echo conf_cdn; ?>phpjs/userfn9.js"></script>
<script type="text/javascript">
<?php echo $Language->ToJSON() ?>
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<meta charset="utf-8">
<?php echo $CSS_CUSTOM; ?>
<link href="<?php echo conf_cdn; ?>more/calendar/css/no-theme/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<script src="<?php echo conf_cdn; ?>more/calendar/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo conf_cdn; ?>bootstrap/js/bootstrap.js"></script>
<link href="<?php echo conf_cdn; ?>bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<style>body { padding-top: 60px; } @media screen and (max-width: 768px) {    body { padding-top: 0px; }} .ewTemplate {	display: none;} .ewMenuColumn {background-color: #F1F1F1;color: ;width: 180px;vertical-align: top;padding: 1px;display: none;} .ewFooterText {font-family: Verdana;font-size: x-small; bottom: 0px; position: absolute;  } .ewGridUpperPanel {margin-top: 5px;margin-bottom: 4px;} .ewGridMiddlePanel{margin-top: 4px;} #xsr_1{padding-bottom: 10px;text-align: center;} #paginador{margin-top: 1px;margin-bottom: 2px;text-align: right;} .ewMessageDialog {margin-top: 20px;} .ewTableHeader .btn .ewTableHeaderBtn{text-transform:capitalize;} .ewTableHeader{ text-transform: capitalize; } .btn { text-transform: capitalize; } .ewTableHeaderBtn{ text-transform: capitalize; } .ewTable{margin-top: 10px;} .navbar .brand {padding: 0px;} .ewListOptionBody2{text-align: center;text-transform: capitalize;} .ewLayout{background-image: url('<?php echo conf_cdn;?>/tx1.png');} em {font-weight: bold;}
<?php if (!$_GET['export']){echo 'body {background: url("'.conf_cdn.conf_domain.'/bk'.rand(0,conf_max).'.png") no-repeat fixed right bottom #FBFBFB;}';} ?>
.flex-video {  position: relative;  padding-top: 25px;  padding-bottom: 67.5%;  height: 0;  margin-bottom: 16px;  overflow: hidden;} .flex-video.widescreen { padding-bottom: 57.25%; } .flex-video.vimeo { padding-top: 0; } .flex-video iframe, .flex-video object, .flex-video embed {  position: absolute;  top: 0;  left: 0;  width: 100%;  height: 100%;  }
@media only screen and (max-device-width: 800px), only screen and (device-width: 1024px) and (device-height: 600px), only screen and (width: 1280px) and (orientation: landscape), only screen and (device-width: 800px), only screen and (max-width: 767px) { .flex-video { padding-top: 0; } }
</style>
<link rel="stylesheet" href="<?php echo conf_cdn; ?>more/c/c.css" />
<script src="<?php echo conf_cdn; ?>more/c/c.js" type="text/javascript"></script>   
<script src="<?php echo conf_cdn; ?>more/d/ckeditor.js" type="text/javascript"></script>   
<meta name="generator" content="PHPMaker v9.0.4" />
</head>
<body class="container yui-skin-sam">
<?php if (  !ISSET($_GET['export'])   ) { ?>
			<!-- left column (begin) -->
<?php include_once "ewmenu.php" ?>
			<!-- left column (end) -->
<?php } ?>
<?php if (ew_IsMobile()) { ?>
<div data-role="page">
	<div data-role="header">
		<a href="mobilemenu.php"><?php echo $Language->Phrase("MobileMenu") ?></a>
		<h1 id="ewPageTitle"> </h1>
	<?php if (IsLoggedIn()) { ?>
		<a href="logout.php"><?php echo $Language->Phrase("Logout") ?></a>
	<?php } elseif (substr(ew_ScriptName(), 0 - strlen("login.php")) <> "login.php") { ?>
		<a href="login.php"><?php echo $Language->Phrase("Login") ?></a>
	<?php } ?>
	</div>
<?php } ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
<div class="ewLayout">
<?php if (!ew_IsMobile()) { ?>
	<!-- header (begin) --><!-- *** Note: Only licensed users are allowed to change the logo *** -->
 <!-- <div class="ewHeaderRow"><img src="phpimages/phpmkrlogo9.png" alt="" border="0" /></div>-->
	<!-- header (end) -->
<?php } ?>
<?php if (ew_IsMobile()) { ?>
	<div data-role="content" data-enhance="false">
	<table class="ewContentTable">
		<tr>
<?php } else { ?>
	<!-- content (begin)
	<table cellspacing="0" class="ewContentTable">
		<tr>	
			<td class="ewMenuColumn">
			</td> -->
<?php } ?>
	  <!--  <td class="ewContentColumn">
			 right column (begin) -->
			<!--	<p><span class="ewSiteTitle"> <?php echo $Language->ProjectPhrase("BodyTitle") ?></span></p>-->
<?php } ?>
