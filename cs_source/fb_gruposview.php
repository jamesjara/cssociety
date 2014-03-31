<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
define("EW_DEFAULT_LOCALE", "Es_es", TRUE);
@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "fb_gruposinfo.php" ?>
<?php include_once "paisesinfo.php" ?>
<?php include_once "ownersinfo.php" ?>
<?php include_once "fb_postsgridcls.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$fb_grupos_view = NULL; // Initialize page object first

class cfb_grupos_view extends cfb_grupos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'fb_grupos';

	// Page object name
	var $PageObjName = 'fb_grupos_view';

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

		// Table object (fb_grupos)
		if (!isset($GLOBALS["fb_grupos"])) {
			$GLOBALS["fb_grupos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fb_grupos"];
		}
		$KeyUrl = "";
		if (@$_GET["idfb_grupos"] <> "") {
			$this->RecKey["idfb_grupos"] = $_GET["idfb_grupos"];
			$KeyUrl .= "&idfb_grupos=" . urlencode($this->RecKey["idfb_grupos"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (paises)
		if (!isset($GLOBALS['paises'])) $GLOBALS['paises'] = new cpaises();

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_grupos', TRUE);

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
		$this->idfb_grupos->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["idfb_grupos"] <> "") {
				$this->idfb_grupos->setQueryStringValue($_GET["idfb_grupos"]);
				$this->RecKey["idfb_grupos"] = $this->idfb_grupos->QueryStringValue;
			} else {
				$sReturnUrl = $this->james_url( "fb_gruposlist.php" ); // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = $this->james_url( "fb_gruposlist.php" ); // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = $this->james_url( "fb_gruposlist.php" ); // Not page request, return to list
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
		$this->idfb_grupos->setDbValue($rs->fields('idfb_grupos'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->pais->setDbValue($rs->fields('pais'));
		$this->url->setDbValue($rs->fields('url'));
		$this->super_id->setDbValue($rs->fields('super_id'));
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
		// idfb_grupos
		// nombre
		// pais
		// url
		// super_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idfb_grupos
			$this->idfb_grupos->ViewValue = $this->idfb_grupos->CurrentValue;
			$this->idfb_grupos->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// pais
			if (strval($this->pais->CurrentValue) <> "") {
				$sFilterWrk = "`idpaises`" . ew_SearchString("=", $this->pais->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idpaises`, `nombre` AS `DispFld`, `admin` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->pais->ViewValue = $rswrk->fields('DispFld');
					$this->pais->ViewValue .= ew_ValueSeparator(1,$fb_grupos->pais) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->pais->ViewValue = $this->pais->CurrentValue;
				}
			} else {
				$this->pais->ViewValue = NULL;
			}
			$this->pais->ViewCustomAttributes = "";

			// url
			$this->url->ViewValue = $this->url->CurrentValue;
			$this->url->ViewCustomAttributes = "";

			// super_id
			$this->super_id->ViewValue = $this->super_id->CurrentValue;
			$this->super_id->ViewCustomAttributes = "";

			// idfb_grupos
			$this->idfb_grupos->LinkCustomAttributes = "";
			$this->idfb_grupos->HrefValue = "";
			$this->idfb_grupos->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// pais
			$this->pais->LinkCustomAttributes = "";
			$this->pais->HrefValue = "";
			$this->pais->TooltipValue = "";

			// url
			$this->url->LinkCustomAttributes = "";
			$this->url->HrefValue = "";
			$this->url->TooltipValue = "";

			// super_id
			$this->super_id->LinkCustomAttributes = "";
			$this->super_id->HrefValue = "";
			$this->super_id->TooltipValue = "";
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
if (!isset($fb_grupos_view)) $fb_grupos_view = new cfb_grupos_view();

// Page init
$fb_grupos_view->Page_Init();

// Page main
$fb_grupos_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_grupos_view = new ew_Page("fb_grupos_view");
fb_grupos_view.PageID = "view"; // Page ID
var EW_PAGE_ID = fb_grupos_view.PageID; // For backward compatibility

// Form object
var ffb_gruposview = new ew_Form("ffb_gruposview");

// Form_CustomValidate event
ffb_gruposview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_gruposview.ValidateRequired = true;
<?php } else { ?>
ffb_gruposview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffb_gruposview.Lists["x_pais"] = {"LinkField":"x_idpaises","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_admin","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_grupos->TableCaption() ?>&nbsp;&nbsp;</h4>
<a href="<?php echo $fb_grupos_view->ListUrl ?>" id="a_BackToList" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("BackToList") ?></a>
<?php //jamesjara
if ( count( $fb_grupos_view->ExportOptions->Items) > 0 ) {
	if(!ISSET($_GET['export'])) echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
	$fb_grupos_view->ExportOptions->Render("body"); 
	if(!ISSET($_GET['export']))  echo '</ul></div> ';
}
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($fb_grupos_view->AddUrl <> "") { ?>
<a href="<?php echo $fb_grupos_view->AddUrl ?>" id="a_AddLink" class="ewLink ewGridLink btn btn-success"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($fb_grupos_view->EditUrl <> "") { ?>
<a href="<?php echo $fb_grupos_view->EditUrl ?>" id="a_EditLink" class="ewLink btn btn-primary"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($fb_grupos_view->DeleteUrl <> "") { ?>
<a href="<?php echo $fb_grupos_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink  btn btn-danger"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<a class="btn btn-warning" href="fb_postslist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=fb_grupos&super_id=<?php echo urlencode(strval($fb_grupos->super_id->CurrentValue)) ?>"><i class="icon-list-alt icon-white"></i> <?php echo $Language->Phrase("ViewPageDetailLink") ?><?php echo $Language->TablePhrase("fb_posts", "TblCaption") ?>
</a>
&nbsp;
<?php } ?>
<?php $fb_grupos_view->ShowPageHeader(); ?>
<?php
$fb_grupos_view->ShowMessage();
?>
<form name="ffb_gruposview" id="ffb_gruposview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="fb_grupos" />
<table id="tbl_fb_gruposview" class="ewTable ewTableSeparate table table-striped ">
<?php if ($fb_grupos->idfb_grupos->Visible) { // idfb_grupos ?>
	<tr id="r_idfb_grupos"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_idfb_grupos"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_grupos->idfb_grupos->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_grupos->idfb_grupos->CellAttributes() ?>><span id="el_fb_grupos_idfb_grupos">
<span<?php echo $fb_grupos->idfb_grupos->ViewAttributes() ?>>
<?php echo $fb_grupos->idfb_grupos->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
	<tr id="r_nombre"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_nombre"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_grupos->nombre->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_grupos->nombre->CellAttributes() ?>><span id="el_fb_grupos_nombre">
<span<?php echo $fb_grupos->nombre->ViewAttributes() ?>>
<?php echo $fb_grupos->nombre->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->pais->Visible) { // pais ?>
	<tr id="r_pais"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_pais"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_grupos->pais->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_grupos->pais->CellAttributes() ?>><span id="el_fb_grupos_pais">
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->url->Visible) { // url ?>
	<tr id="r_url"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_url"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_grupos->url->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_grupos->url->CellAttributes() ?>><span id="el_fb_grupos_url">
<span<?php echo $fb_grupos->url->ViewAttributes() ?>>
<?php echo $fb_grupos->url->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->super_id->Visible) { // super_id ?>
	<tr id="r_super_id"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_super_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_grupos->super_id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_grupos->super_id->CellAttributes() ?>><span id="el_fb_grupos_super_id">
<span<?php echo $fb_grupos->super_id->ViewAttributes() ?>>
<?php echo $fb_grupos->super_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</form>
<script type="text/javascript">
ffb_gruposview.Init();
</script>
<?php
$fb_grupos_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_grupos_view->Page_Terminate();
?>
