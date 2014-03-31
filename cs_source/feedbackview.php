<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
define("EW_DEFAULT_LOCALE", "Es_es", TRUE);
@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "feedbackinfo.php" ?>
<?php include_once "ownersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$feedback_view = NULL; // Initialize page object first

class cfeedback_view extends cfeedback {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'feedback';

	// Page object name
	var $PageObjName = 'feedback_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display

			///$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			//$html .= "<div class=\"ewWarningMessage\">" . $sWarningMessage . "</div>";

			$html .= '<div class="alert alert-info alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong> Advertencia!</strong> ' . $sWarningMessage . '</div>';
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display

			//$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$html .= '<div class="alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button><strong> Felicidades!</strong> ' . $sSuccessMessage . '</div>';
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display

			//$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$html .= '<div class="alert  alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong> Error!</strong> ' . $sErrorMessage . '</div>';
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (feedback)
		if (!isset($GLOBALS["feedback"])) {
			$GLOBALS["feedback"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["feedback"];
		}
		$KeyUrl = "";
		if (@$_GET["idfeedback"] <> "") {
			$this->RecKey["idfeedback"] = $_GET["idfeedback"];
			$KeyUrl .= "&idfeedback=" . urlencode($this->RecKey["idfeedback"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'feedback', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "li";//jamesjara
		$this->ExportOptions->TagClassName = "ewExportOptionIgnore";
	}

			function james_url( $PageName ){
	$PageName_O = $PageName ;	$parts = parse_url($PageName);$PageName = $parts['path'];

	//todo, hot fixes too view urls	
	$PageName = str_ireplace( 'view1' , 'xxx1', $PageName);
	$PageName = str_ireplace( 'view2' , 'xxx2', $PageName);
	$PageName = str_ireplace( 'view3' , 'xxx3', $PageName);	

	//jamesjara , funciona en FORM Y EXPORTS
	$buscar = array('list','add','view','delete','info','edit','.php');	
	$pagina = str_ireplace( $buscar , '', $PageName, $encontrado);
	if( $encontrado > 0 ){

		//obtener la accion
		$buscar = array( $pagina , '.php');
		$accion = str_ireplace( $buscar , '', $PageName);	

		//hot fixed
		$pagina = str_ireplace( 'xxx1' , 'view1', $pagina);
		$pagina = str_ireplace( 'xxx2' , 'view2', $pagina);
		$pagina = str_ireplace( 'xxx3' , 'view3', $pagina);	
		return  $pagina.'-'.$accion ;
	}
	return $PageName_O;
}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();
		$UserProfile->LoadProfile(@$_SESSION[EW_SESSION_USER_PROFILE]);

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->idfeedback->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idfeedback"] <> "") {
				$this->idfeedback->setQueryStringValue($_GET["idfeedback"]);
				$this->RecKey["idfeedback"] = $this->idfeedback->QueryStringValue;
			} else {
				$sReturnUrl = $this->james_url( "feedbacklist.php" ); // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = $this->james_url( "feedbacklist.php" ); // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = $this->james_url( "feedbacklist.php" ); // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->idfeedback->setDbValue($rs->fields('idfeedback'));
		$this->Titulo->setDbValue($rs->fields('Titulo'));
		$this->Descripcion->setDbValue($rs->fields('Descripcion'));
		$this->Url->setDbValue($rs->fields('Url'));
		$this->autor->setDbValue($rs->fields('autor'));
		$this->paises_target_blogs->setDbValue($rs->fields('paises_target_blogs'));
		$this->paises_target_fbg->setDbValue($rs->fields('paises_target_fbg'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->ejecutado->setDbValue($rs->fields('ejecutado'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idfeedback
		// Titulo
		// Descripcion
		// Url
		// autor
		// paises_target_blogs
		// paises_target_fbg
		// fecha
		// ejecutado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idfeedback
			$this->idfeedback->ViewValue = $this->idfeedback->CurrentValue;
			$this->idfeedback->ViewCustomAttributes = "";

			// Titulo
			$this->Titulo->ViewValue = $this->Titulo->CurrentValue;
			$this->Titulo->ViewCustomAttributes = "";

			// Descripcion
			$this->Descripcion->ViewValue = $this->Descripcion->CurrentValue;
			$this->Descripcion->ViewCustomAttributes = "";

			// Url
			$this->Url->ViewValue = $this->Url->CurrentValue;
			$this->Url->ViewCustomAttributes = "";

			// autor
			$this->autor->ViewValue = $this->autor->CurrentValue;
			$this->autor->ViewCustomAttributes = "";

			// paises_target_blogs
			$this->paises_target_blogs->ViewValue = $this->paises_target_blogs->CurrentValue;
			$this->paises_target_blogs->ViewCustomAttributes = "";

			// paises_target_fbg
			$this->paises_target_fbg->ViewValue = $this->paises_target_fbg->CurrentValue;
			$this->paises_target_fbg->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 5);
			$this->fecha->ViewCustomAttributes = "";

			// ejecutado
			$this->ejecutado->ViewValue = $this->ejecutado->CurrentValue;
			$this->ejecutado->ViewCustomAttributes = "";

			// idfeedback
			$this->idfeedback->LinkCustomAttributes = "";
			$this->idfeedback->HrefValue = "";
			$this->idfeedback->TooltipValue = "";

			// Titulo
			$this->Titulo->LinkCustomAttributes = "";
			$this->Titulo->HrefValue = "";
			$this->Titulo->TooltipValue = "";

			// Descripcion
			$this->Descripcion->LinkCustomAttributes = "";
			$this->Descripcion->HrefValue = "";
			$this->Descripcion->TooltipValue = "";

			// Url
			$this->Url->LinkCustomAttributes = "";
			$this->Url->HrefValue = "";
			$this->Url->TooltipValue = "";

			// autor
			$this->autor->LinkCustomAttributes = "";
			$this->autor->HrefValue = "";
			$this->autor->TooltipValue = "";

			// paises_target_blogs
			$this->paises_target_blogs->LinkCustomAttributes = "";
			$this->paises_target_blogs->HrefValue = "";
			$this->paises_target_blogs->TooltipValue = "";

			// paises_target_fbg
			$this->paises_target_fbg->LinkCustomAttributes = "";
			$this->paises_target_fbg->HrefValue = "";
			$this->paises_target_fbg->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// ejecutado
			$this->ejecutado->LinkCustomAttributes = "";
			$this->ejecutado->HrefValue = "";
			$this->ejecutado->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($feedback_view)) $feedback_view = new cfeedback_view();

// Page init
$feedback_view->Page_Init();

// Page main
$feedback_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var feedback_view = new ew_Page("feedback_view");
feedback_view.PageID = "view"; // Page ID
var EW_PAGE_ID = feedback_view.PageID; // For backward compatibility

// Form object
var ffeedbackview = new ew_Form("ffeedbackview");

// Form_CustomValidate event
ffeedbackview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffeedbackview.ValidateRequired = true;
<?php } else { ?>
ffeedbackview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $feedback->TableCaption() ?>&nbsp;&nbsp;</h4>
<a href="<?php echo $feedback_view->ListUrl ?>" id="a_BackToList" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("BackToList") ?></a>
<?php //jamesjara
if ( count( $feedback_view->ExportOptions->Items) > 0 ) {
	if(!ISSET($_GET['export'])) echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
	$feedback_view->ExportOptions->Render("body"); 
	if(!ISSET($_GET['export']))  echo '</ul></div> ';
}
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($feedback_view->AddUrl <> "") { ?>
<a href="<?php echo $feedback_view->AddUrl ?>" id="a_AddLink" class="ewLink ewGridLink btn btn-success"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($feedback_view->EditUrl <> "") { ?>
<a href="<?php echo $feedback_view->EditUrl ?>" id="a_EditLink" class="ewLink btn btn-primary"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($feedback_view->CopyUrl <> "") { ?>
<a href="<?php echo $feedback_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($feedback_view->DeleteUrl <> "") { ?>
<a href="<?php echo $feedback_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink  btn btn-danger"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>
<?php } ?>
<?php } ?>
<?php $feedback_view->ShowPageHeader(); ?>
<?php
$feedback_view->ShowMessage();
?>
<form name="ffeedbackview" id="ffeedbackview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="feedback" />
<table id="tbl_feedbackview" class="ewTable ewTableSeparate table table-striped ">
<?php if ($feedback->idfeedback->Visible) { // idfeedback ?>
	<tr id="r_idfeedback"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_idfeedback"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->idfeedback->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->idfeedback->CellAttributes() ?>><span id="el_feedback_idfeedback">
<span<?php echo $feedback->idfeedback->ViewAttributes() ?>>
<?php echo $feedback->idfeedback->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->Titulo->Visible) { // Titulo ?>
	<tr id="r_Titulo"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_Titulo"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->Titulo->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->Titulo->CellAttributes() ?>><span id="el_feedback_Titulo">
<span<?php echo $feedback->Titulo->ViewAttributes() ?>>
<?php echo $feedback->Titulo->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->Descripcion->Visible) { // Descripcion ?>
	<tr id="r_Descripcion"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_Descripcion"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->Descripcion->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->Descripcion->CellAttributes() ?>><span id="el_feedback_Descripcion">
<span<?php echo $feedback->Descripcion->ViewAttributes() ?>>
<?php echo $feedback->Descripcion->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->Url->Visible) { // Url ?>
	<tr id="r_Url"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_Url"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->Url->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->Url->CellAttributes() ?>><span id="el_feedback_Url">
<span<?php echo $feedback->Url->ViewAttributes() ?>>
<?php echo $feedback->Url->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->autor->Visible) { // autor ?>
	<tr id="r_autor"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_autor"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->autor->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->autor->CellAttributes() ?>><span id="el_feedback_autor">
<span<?php echo $feedback->autor->ViewAttributes() ?>>
<?php echo $feedback->autor->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->paises_target_blogs->Visible) { // paises_target_blogs ?>
	<tr id="r_paises_target_blogs"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_paises_target_blogs"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->paises_target_blogs->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->paises_target_blogs->CellAttributes() ?>><span id="el_feedback_paises_target_blogs">
<span<?php echo $feedback->paises_target_blogs->ViewAttributes() ?>>
<?php echo $feedback->paises_target_blogs->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->paises_target_fbg->Visible) { // paises_target_fbg ?>
	<tr id="r_paises_target_fbg"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_paises_target_fbg"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->paises_target_fbg->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->paises_target_fbg->CellAttributes() ?>><span id="el_feedback_paises_target_fbg">
<span<?php echo $feedback->paises_target_fbg->ViewAttributes() ?>>
<?php echo $feedback->paises_target_fbg->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->fecha->Visible) { // fecha ?>
	<tr id="r_fecha"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_fecha"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->fecha->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->fecha->CellAttributes() ?>><span id="el_feedback_fecha">
<span<?php echo $feedback->fecha->ViewAttributes() ?>>
<?php echo $feedback->fecha->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($feedback->ejecutado->Visible) { // ejecutado ?>
	<tr id="r_ejecutado"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_ejecutado"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $feedback->ejecutado->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $feedback->ejecutado->CellAttributes() ?>><span id="el_feedback_ejecutado">
<span<?php echo $feedback->ejecutado->ViewAttributes() ?>>
<?php echo $feedback->ejecutado->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</form>
<script type="text/javascript">
ffeedbackview.Init();
</script>
<?php
$feedback_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$feedback_view->Page_Terminate();
?>
