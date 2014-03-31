<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
define("EW_DEFAULT_LOCALE", "Es_es", TRUE);
@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "fb_postsinfo.php" ?>
<?php include_once "fb_gruposinfo.php" ?>
<?php include_once "ownersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$fb_posts_view = NULL; // Initialize page object first

class cfb_posts_view extends cfb_posts {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'fb_posts';

	// Page object name
	var $PageObjName = 'fb_posts_view';

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

		// Table object (fb_posts)
		if (!isset($GLOBALS["fb_posts"])) {
			$GLOBALS["fb_posts"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fb_posts"];
		}
		$KeyUrl = "";
		if (@$_GET["idfb_posts"] <> "") {
			$this->RecKey["idfb_posts"] = $_GET["idfb_posts"];
			$KeyUrl .= "&idfb_posts=" . urlencode($this->RecKey["idfb_posts"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (fb_grupos)
		if (!isset($GLOBALS['fb_grupos'])) $GLOBALS['fb_grupos'] = new cfb_grupos();

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_posts', TRUE);

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
		$this->idfb_posts->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idfb_posts"] <> "") {
				$this->idfb_posts->setQueryStringValue($_GET["idfb_posts"]);
				$this->RecKey["idfb_posts"] = $this->idfb_posts->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate($this->james_url( "fb_postslist.php" )); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->idfb_posts->CurrentValue) == strval($this->Recordset->fields('idfb_posts'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = $this->james_url( "fb_postslist.php" ); // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}
		} else {
			$sReturnUrl = $this->james_url( "fb_postslist.php" ); // Not page request, return to list
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

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->idfb_posts->setDbValue($rs->fields('idfb_posts'));
		$this->id->setDbValue($rs->fields('id'));
		$this->created_time->setDbValue($rs->fields('created_time'));
		$this->actions->setDbValue($rs->fields('actions'));
		$this->icon->setDbValue($rs->fields('icon'));
		$this->is_published->setDbValue($rs->fields('is_published'));
		$this->message->setDbValue($rs->fields('message'));
		$this->link->setDbValue($rs->fields('link'));
		$this->object_id->setDbValue($rs->fields('object_id'));
		$this->picture->setDbValue($rs->fields('picture'));
		$this->privacy->setDbValue($rs->fields('privacy'));
		$this->promotion_status->setDbValue($rs->fields('promotion_status'));
		$this->timeline_visibility->setDbValue($rs->fields('timeline_visibility'));
		$this->type->setDbValue($rs->fields('type'));
		$this->updated_time->setDbValue($rs->fields('updated_time'));
		$this->caption->setDbValue($rs->fields('caption'));
		$this->description->setDbValue($rs->fields('description'));
		$this->name->setDbValue($rs->fields('name'));
		$this->source->setDbValue($rs->fields('source'));
		$this->from->setDbValue($rs->fields('from'));
		$this->to->setDbValue($rs->fields('to'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->id_grupo->setDbValue($rs->fields('id_grupo'));
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
		// idfb_posts
		// id
		// created_time
		// actions
		// icon
		// is_published
		// message
		// link
		// object_id
		// picture
		// privacy
		// promotion_status
		// timeline_visibility
		// type
		// updated_time
		// caption
		// description
		// name
		// source
		// from
		// to
		// comments
		// id_grupo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idfb_posts
			$this->idfb_posts->ViewValue = $this->idfb_posts->CurrentValue;
			$this->idfb_posts->ViewCustomAttributes = "";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// created_time
			$this->created_time->ViewValue = $this->created_time->CurrentValue;
			$this->created_time->ViewCustomAttributes = "";

			// actions
			$this->actions->ViewValue = $this->actions->CurrentValue;
			$this->actions->ViewCustomAttributes = "";

			// icon
			$this->icon->ViewValue = $this->icon->CurrentValue;
			$this->icon->ViewCustomAttributes = "";

			// is_published
			$this->is_published->ViewValue = $this->is_published->CurrentValue;
			$this->is_published->ViewCustomAttributes = "";

			// message
			$this->message->ViewValue = $this->message->CurrentValue;
			$this->message->ViewCustomAttributes = "";

			// link
			$this->link->ViewValue = $this->link->CurrentValue;
			$this->link->ViewCustomAttributes = "";

			// object_id
			$this->object_id->ViewValue = $this->object_id->CurrentValue;
			$this->object_id->ViewCustomAttributes = "";

			// picture
			$this->picture->ViewValue = $this->picture->CurrentValue;
			$this->picture->ViewCustomAttributes = "";

			// privacy
			$this->privacy->ViewValue = $this->privacy->CurrentValue;
			$this->privacy->ViewCustomAttributes = "";

			// promotion_status
			$this->promotion_status->ViewValue = $this->promotion_status->CurrentValue;
			$this->promotion_status->ViewCustomAttributes = "";

			// timeline_visibility
			$this->timeline_visibility->ViewValue = $this->timeline_visibility->CurrentValue;
			$this->timeline_visibility->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// updated_time
			$this->updated_time->ViewValue = $this->updated_time->CurrentValue;
			$this->updated_time->ViewCustomAttributes = "";

			// caption
			$this->caption->ViewValue = $this->caption->CurrentValue;
			$this->caption->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// source
			$this->source->ViewValue = $this->source->CurrentValue;
			$this->source->ViewCustomAttributes = "";

			// from
			$this->from->ViewValue = $this->from->CurrentValue;
			$this->from->ViewCustomAttributes = "";

			// to
			$this->to->ViewValue = $this->to->CurrentValue;
			$this->to->ViewCustomAttributes = "";

			// comments
			$this->comments->ViewValue = $this->comments->CurrentValue;
			$this->comments->ViewCustomAttributes = "";

			// id_grupo
			$this->id_grupo->ViewValue = $this->id_grupo->CurrentValue;
			$this->id_grupo->ViewCustomAttributes = "";

			// idfb_posts
			$this->idfb_posts->LinkCustomAttributes = "";
			$this->idfb_posts->HrefValue = "";
			$this->idfb_posts->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// created_time
			$this->created_time->LinkCustomAttributes = "";
			$this->created_time->HrefValue = "";
			$this->created_time->TooltipValue = "";

			// actions
			$this->actions->LinkCustomAttributes = "";
			$this->actions->HrefValue = "";
			$this->actions->TooltipValue = "";

			// icon
			$this->icon->LinkCustomAttributes = "";
			$this->icon->HrefValue = "";
			$this->icon->TooltipValue = "";

			// is_published
			$this->is_published->LinkCustomAttributes = "";
			$this->is_published->HrefValue = "";
			$this->is_published->TooltipValue = "";

			// message
			$this->message->LinkCustomAttributes = "";
			$this->message->HrefValue = "";
			$this->message->TooltipValue = "";

			// link
			$this->link->LinkCustomAttributes = "";
			$this->link->HrefValue = "";
			$this->link->TooltipValue = "";

			// object_id
			$this->object_id->LinkCustomAttributes = "";
			$this->object_id->HrefValue = "";
			$this->object_id->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->TooltipValue = "";

			// privacy
			$this->privacy->LinkCustomAttributes = "";
			$this->privacy->HrefValue = "";
			$this->privacy->TooltipValue = "";

			// promotion_status
			$this->promotion_status->LinkCustomAttributes = "";
			$this->promotion_status->HrefValue = "";
			$this->promotion_status->TooltipValue = "";

			// timeline_visibility
			$this->timeline_visibility->LinkCustomAttributes = "";
			$this->timeline_visibility->HrefValue = "";
			$this->timeline_visibility->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// updated_time
			$this->updated_time->LinkCustomAttributes = "";
			$this->updated_time->HrefValue = "";
			$this->updated_time->TooltipValue = "";

			// caption
			$this->caption->LinkCustomAttributes = "";
			$this->caption->HrefValue = "";
			$this->caption->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// source
			$this->source->LinkCustomAttributes = "";
			$this->source->HrefValue = "";
			$this->source->TooltipValue = "";

			// from
			$this->from->LinkCustomAttributes = "";
			$this->from->HrefValue = "";
			$this->from->TooltipValue = "";

			// to
			$this->to->LinkCustomAttributes = "";
			$this->to->HrefValue = "";
			$this->to->TooltipValue = "";

			// comments
			$this->comments->LinkCustomAttributes = "";
			$this->comments->HrefValue = "";
			$this->comments->TooltipValue = "";

			// id_grupo
			$this->id_grupo->LinkCustomAttributes = "";
			$this->id_grupo->HrefValue = "";
			$this->id_grupo->TooltipValue = "";
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
if (!isset($fb_posts_view)) $fb_posts_view = new cfb_posts_view();

// Page init
$fb_posts_view->Page_Init();

// Page main
$fb_posts_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_posts_view = new ew_Page("fb_posts_view");
fb_posts_view.PageID = "view"; // Page ID
var EW_PAGE_ID = fb_posts_view.PageID; // For backward compatibility

// Form object
var ffb_postsview = new ew_Form("ffb_postsview");

// Form_CustomValidate event
ffb_postsview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_postsview.ValidateRequired = true;
<?php } else { ?>
ffb_postsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_posts->TableCaption() ?>&nbsp;&nbsp;</h4>
<a href="<?php echo $fb_posts_view->ListUrl ?>" id="a_BackToList" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("BackToList") ?></a>
<?php //jamesjara
if ( count( $fb_posts_view->ExportOptions->Items) > 0 ) {
	if(!ISSET($_GET['export'])) echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
	$fb_posts_view->ExportOptions->Render("body"); 
	if(!ISSET($_GET['export']))  echo '</ul></div> ';
}
?>
<?php $fb_posts_view->ShowPageHeader(); ?>
<?php
$fb_posts_view->ShowMessage();
?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($fb_posts_view->Pager)) $fb_posts_view->Pager = new cNumericPager($fb_posts_view->StartRec, $fb_posts_view->DisplayRecs, $fb_posts_view->TotalRecs, $fb_posts_view->RecRange) ?>
<?php if ($fb_posts_view->Pager->RecordCount > 0) { ?>
	<ul><?php if ($fb_posts_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_view->PageUrl() ?>start=<?php echo $fb_posts_view->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_posts_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_view->PageUrl() ?>start=<?php echo $fb_posts_view->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($fb_posts_view->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $fb_posts_view->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($fb_posts_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_view->PageUrl() ?>start=<?php echo $fb_posts_view->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_posts_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_view->PageUrl() ?>start=<?php echo $fb_posts_view->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
<?php } else { ?>	
	<?php if ($fb_posts_view->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</div>
</form>
<div>
<form name="ffb_postsview" id="ffb_postsview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="fb_posts" />
<table id="tbl_fb_postsview" class="ewTable ewTableSeparate table table-striped ">
<?php if ($fb_posts->idfb_posts->Visible) { // idfb_posts ?>
	<tr id="r_idfb_posts"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_idfb_posts"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->idfb_posts->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->idfb_posts->CellAttributes() ?>><span id="el_fb_posts_idfb_posts">
<span<?php echo $fb_posts->idfb_posts->ViewAttributes() ?>>
<?php echo $fb_posts->idfb_posts->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->id->CellAttributes() ?>><span id="el_fb_posts_id">
<span<?php echo $fb_posts->id->ViewAttributes() ?>>
<?php echo $fb_posts->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->created_time->Visible) { // created_time ?>
	<tr id="r_created_time"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_created_time"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->created_time->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->created_time->CellAttributes() ?>><span id="el_fb_posts_created_time">
<span<?php echo $fb_posts->created_time->ViewAttributes() ?>>
<?php echo $fb_posts->created_time->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->actions->Visible) { // actions ?>
	<tr id="r_actions"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_actions"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->actions->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->actions->CellAttributes() ?>><span id="el_fb_posts_actions">
<span<?php echo $fb_posts->actions->ViewAttributes() ?>>
<?php echo $fb_posts->actions->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->icon->Visible) { // icon ?>
	<tr id="r_icon"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_icon"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->icon->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->icon->CellAttributes() ?>><span id="el_fb_posts_icon">
<span<?php echo $fb_posts->icon->ViewAttributes() ?>>
<?php echo $fb_posts->icon->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->is_published->Visible) { // is_published ?>
	<tr id="r_is_published"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_is_published"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->is_published->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->is_published->CellAttributes() ?>><span id="el_fb_posts_is_published">
<span<?php echo $fb_posts->is_published->ViewAttributes() ?>>
<?php echo $fb_posts->is_published->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->message->Visible) { // message ?>
	<tr id="r_message"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_message"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->message->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->message->CellAttributes() ?>><span id="el_fb_posts_message">
<span<?php echo $fb_posts->message->ViewAttributes() ?>>
<?php echo $fb_posts->message->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->link->Visible) { // link ?>
	<tr id="r_link"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_link"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->link->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->link->CellAttributes() ?>><span id="el_fb_posts_link">
<span<?php echo $fb_posts->link->ViewAttributes() ?>>
<?php echo $fb_posts->link->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->object_id->Visible) { // object_id ?>
	<tr id="r_object_id"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_object_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->object_id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->object_id->CellAttributes() ?>><span id="el_fb_posts_object_id">
<span<?php echo $fb_posts->object_id->ViewAttributes() ?>>
<?php echo $fb_posts->object_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->picture->Visible) { // picture ?>
	<tr id="r_picture"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_picture"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->picture->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->picture->CellAttributes() ?>><span id="el_fb_posts_picture">
<span<?php echo $fb_posts->picture->ViewAttributes() ?>>
<?php echo $fb_posts->picture->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->privacy->Visible) { // privacy ?>
	<tr id="r_privacy"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_privacy"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->privacy->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->privacy->CellAttributes() ?>><span id="el_fb_posts_privacy">
<span<?php echo $fb_posts->privacy->ViewAttributes() ?>>
<?php echo $fb_posts->privacy->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->promotion_status->Visible) { // promotion_status ?>
	<tr id="r_promotion_status"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_promotion_status"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->promotion_status->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->promotion_status->CellAttributes() ?>><span id="el_fb_posts_promotion_status">
<span<?php echo $fb_posts->promotion_status->ViewAttributes() ?>>
<?php echo $fb_posts->promotion_status->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->timeline_visibility->Visible) { // timeline_visibility ?>
	<tr id="r_timeline_visibility"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_timeline_visibility"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->timeline_visibility->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->timeline_visibility->CellAttributes() ?>><span id="el_fb_posts_timeline_visibility">
<span<?php echo $fb_posts->timeline_visibility->ViewAttributes() ?>>
<?php echo $fb_posts->timeline_visibility->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_type"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->type->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->type->CellAttributes() ?>><span id="el_fb_posts_type">
<span<?php echo $fb_posts->type->ViewAttributes() ?>>
<?php echo $fb_posts->type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->updated_time->Visible) { // updated_time ?>
	<tr id="r_updated_time"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_updated_time"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->updated_time->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->updated_time->CellAttributes() ?>><span id="el_fb_posts_updated_time">
<span<?php echo $fb_posts->updated_time->ViewAttributes() ?>>
<?php echo $fb_posts->updated_time->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->caption->Visible) { // caption ?>
	<tr id="r_caption"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_caption"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->caption->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->caption->CellAttributes() ?>><span id="el_fb_posts_caption">
<span<?php echo $fb_posts->caption->ViewAttributes() ?>>
<?php echo $fb_posts->caption->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->description->Visible) { // description ?>
	<tr id="r_description"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_description"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->description->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->description->CellAttributes() ?>><span id="el_fb_posts_description">
<span<?php echo $fb_posts->description->ViewAttributes() ?>>
<?php echo $fb_posts->description->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_name"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->name->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->name->CellAttributes() ?>><span id="el_fb_posts_name">
<span<?php echo $fb_posts->name->ViewAttributes() ?>>
<?php echo $fb_posts->name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->source->Visible) { // source ?>
	<tr id="r_source"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_source"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->source->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->source->CellAttributes() ?>><span id="el_fb_posts_source">
<span<?php echo $fb_posts->source->ViewAttributes() ?>>
<?php echo $fb_posts->source->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->from->Visible) { // from ?>
	<tr id="r_from"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_from"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->from->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->from->CellAttributes() ?>><span id="el_fb_posts_from">
<span<?php echo $fb_posts->from->ViewAttributes() ?>>
<?php echo $fb_posts->from->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->to->Visible) { // to ?>
	<tr id="r_to"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_to"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->to->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->to->CellAttributes() ?>><span id="el_fb_posts_to">
<span<?php echo $fb_posts->to->ViewAttributes() ?>>
<?php echo $fb_posts->to->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->comments->Visible) { // comments ?>
	<tr id="r_comments"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_comments"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->comments->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->comments->CellAttributes() ?>><span id="el_fb_posts_comments">
<span<?php echo $fb_posts->comments->ViewAttributes() ?>>
<?php echo $fb_posts->comments->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->id_grupo->Visible) { // id_grupo ?>
	<tr id="r_id_grupo"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_id_grupo"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_posts->id_grupo->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_posts->id_grupo->CellAttributes() ?>><span id="el_fb_posts_id_grupo">
<span<?php echo $fb_posts->id_grupo->ViewAttributes() ?>>
<?php echo $fb_posts->id_grupo->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</form>
<script type="text/javascript">
ffb_postsview.Init();
</script>
<?php
$fb_posts_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_posts_view->Page_Terminate();
?>
