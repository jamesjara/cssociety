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

$feedback_list = NULL; // Initialize page object first

class cfeedback_list extends cfeedback {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'feedback';

	// Page object name
	var $PageObjName = 'feedback_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this->james_url("feedbackadd.php");
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "feedbackdelete.php";
		$this->MultiUpdateUrl = "feedbackupdate.php";

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'feedback', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall")
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search") {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->idfeedback->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idfeedback->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->Titulo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->Descripcion, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->Url, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->paises_target_blogs, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->paises_target_fbg, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idfeedback); // idfeedback
			$this->UpdateSort($this->Titulo); // Titulo
			$this->UpdateSort($this->Url); // Url
			$this->UpdateSort($this->autor); // autor
			$this->UpdateSort($this->paises_target_blogs); // paises_target_blogs
			$this->UpdateSort($this->paises_target_fbg); // paises_target_fbg
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->ejecutado); // ejecutado
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idfeedback->setSort("");
				$this->Titulo->setSort("");
				$this->Url->setSort("");
				$this->autor->setSort("");
				$this->paises_target_blogs->setSort("");
				$this->paises_target_fbg->setSort("");
				$this->fecha->setSort("");
				$this->ejecutado->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink label label-success\" href=\"" . $this->ViewUrl . "\"><i class='icon-search icon-white'></i> " . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink label label-info\" href=\"" . $this->EditUrl . "\"><i class='icon-pencil icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink  label label-info\" href=\"" . $this->CopyUrl . "\">" . $Language->Phrase("CopyLink") . "</a>";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink  label label-important\"" . "" . " href=\"" . $this->DeleteUrl . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idfeedback")) <> "")
			$this->idfeedback->CurrentValue = $this->getKey("idfeedback"); // idfeedback
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($feedback_list)) $feedback_list = new cfeedback_list();

// Page init
$feedback_list->Page_Init();

// Page main
$feedback_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var feedback_list = new ew_Page("feedback_list");
feedback_list.PageID = "list"; // Page ID
var EW_PAGE_ID = feedback_list.PageID; // For backward compatibility

// Form object
var ffeedbacklist = new ew_Form("ffeedbacklist");

// Form_CustomValidate event
ffeedbacklist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffeedbacklist.ValidateRequired = true;
<?php } else { ?>
ffeedbacklist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var ffeedbacklistsrch = new ew_Form("ffeedbacklistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$feedback_list->TotalRecs = $feedback->SelectRecordCount();
	} else {
		if ($feedback_list->Recordset = $feedback_list->LoadRecordset())
			$feedback_list->TotalRecs = $feedback_list->Recordset->RecordCount();
	}
	$feedback_list->StartRec = 1;
	if ($feedback_list->DisplayRecs <= 0 || ($feedback->Export <> "" && $feedback->ExportAll)) // Display all records
		$feedback_list->DisplayRecs = $feedback_list->TotalRecs;
	if (!($feedback->Export <> "" && $feedback->ExportAll))
		$feedback_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$feedback_list->Recordset = $feedback_list->LoadRecordset($feedback_list->StartRec-1, $feedback_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $feedback->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php /*jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar3<span class="caret"></span></button><ul class="dropdown-menu">';
$feedback_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($feedback->Export == "" && $feedback->CurrentAction == "") { ?>
<div class="accordion" id="accordion2">
<div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        <?php echo $Language->Phrase("Search") ?>
      </a>
    </div>
<div id="collapseOne" class="accordion-body collapse">
<div class="accordion-inner">
<form onsubmit="return ewForms[this.id].Submit();" name="ffeedbacklistsrch" id="ffeedbacklistsrch" class="ewForm navbar-form pull-left" action="<?php echo ew_CurrentPage() ?>">
<!--
<a href="javascript:ffeedbacklistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="ffeedbacklistsrch_SearchImage" src="http://cdn.registrodemascotas.co.cr/collapse.gif" alt="" width="9" height="9" style="border: 0;" /></a><span class="phpmaker"><?php echo $Language->Phrase("Search") ?></span><br />
-->
<div id="ffeedbacklistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search" />
<input type="hidden" name="t" value="feedback" />
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>"  class="span2"  value="<?php echo ew_HtmlEncode($feedback_list->BasicSearch->getKeyword()) ?>" />
	<input type="submit" class="btn" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>" />&nbsp;
	<a class="btn btn-warning" href="<?php echo $feedback_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($feedback_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($feedback_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($feedback_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</form>
     </div>
    </div>
 </div>
 </div>
</div>
<?php } ?>
<?php } ?>
<?php $feedback_list->ShowPageHeader(); ?>
<?php
$feedback_list->ShowMessage();
?>
<form name="ffeedbacklist" id="ffeedbacklist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="feedback" />
<div id="gmp_feedback" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($feedback_list->TotalRecs > 0) { ?>
<table id="tbl_feedbacklist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $feedback->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$feedback_list->RenderListOptions();

// Render list options (header, left)
$feedback_list->ListOptions->Render("header", "left");
?>
<?php if ($feedback->idfeedback->Visible) { // idfeedback ?>
	<?php if ($feedback->SortUrl($feedback->idfeedback) == "") { ?>
		<th><span id="elh_feedback_idfeedback" class="feedback_idfeedback">
		<div class="ewTableHeaderBtn"><?php echo $feedback->idfeedback->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->idfeedback) ?>',1);"><span id="elh_feedback_idfeedback" class="feedback_idfeedback">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->idfeedback->FldCaption() ?>
			<?php if ($feedback->idfeedback->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->idfeedback->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($feedback->Titulo->Visible) { // Titulo ?>
	<?php if ($feedback->SortUrl($feedback->Titulo) == "") { ?>
		<th><span id="elh_feedback_Titulo" class="feedback_Titulo">
		<div class="ewTableHeaderBtn"><?php echo $feedback->Titulo->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->Titulo) ?>',1);"><span id="elh_feedback_Titulo" class="feedback_Titulo">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->Titulo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($feedback->Titulo->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->Titulo->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($feedback->Url->Visible) { // Url ?>
	<?php if ($feedback->SortUrl($feedback->Url) == "") { ?>
		<th><span id="elh_feedback_Url" class="feedback_Url">
		<div class="ewTableHeaderBtn"><?php echo $feedback->Url->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->Url) ?>',1);"><span id="elh_feedback_Url" class="feedback_Url">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->Url->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($feedback->Url->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->Url->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($feedback->autor->Visible) { // autor ?>
	<?php if ($feedback->SortUrl($feedback->autor) == "") { ?>
		<th><span id="elh_feedback_autor" class="feedback_autor">
		<div class="ewTableHeaderBtn"><?php echo $feedback->autor->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->autor) ?>',1);"><span id="elh_feedback_autor" class="feedback_autor">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->autor->FldCaption() ?>
			<?php if ($feedback->autor->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->autor->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($feedback->paises_target_blogs->Visible) { // paises_target_blogs ?>
	<?php if ($feedback->SortUrl($feedback->paises_target_blogs) == "") { ?>
		<th><span id="elh_feedback_paises_target_blogs" class="feedback_paises_target_blogs">
		<div class="ewTableHeaderBtn"><?php echo $feedback->paises_target_blogs->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->paises_target_blogs) ?>',1);"><span id="elh_feedback_paises_target_blogs" class="feedback_paises_target_blogs">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->paises_target_blogs->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($feedback->paises_target_blogs->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->paises_target_blogs->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($feedback->paises_target_fbg->Visible) { // paises_target_fbg ?>
	<?php if ($feedback->SortUrl($feedback->paises_target_fbg) == "") { ?>
		<th><span id="elh_feedback_paises_target_fbg" class="feedback_paises_target_fbg">
		<div class="ewTableHeaderBtn"><?php echo $feedback->paises_target_fbg->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->paises_target_fbg) ?>',1);"><span id="elh_feedback_paises_target_fbg" class="feedback_paises_target_fbg">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->paises_target_fbg->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($feedback->paises_target_fbg->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->paises_target_fbg->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($feedback->fecha->Visible) { // fecha ?>
	<?php if ($feedback->SortUrl($feedback->fecha) == "") { ?>
		<th><span id="elh_feedback_fecha" class="feedback_fecha">
		<div class="ewTableHeaderBtn"><?php echo $feedback->fecha->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->fecha) ?>',1);"><span id="elh_feedback_fecha" class="feedback_fecha">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->fecha->FldCaption() ?>
			<?php if ($feedback->fecha->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->fecha->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($feedback->ejecutado->Visible) { // ejecutado ?>
	<?php if ($feedback->SortUrl($feedback->ejecutado) == "") { ?>
		<th><span id="elh_feedback_ejecutado" class="feedback_ejecutado">
		<div class="ewTableHeaderBtn"><?php echo $feedback->ejecutado->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $feedback->SortUrl($feedback->ejecutado) ?>',1);"><span id="elh_feedback_ejecutado" class="feedback_ejecutado">
			<div class="ewTableHeaderBtn">			
			<?php echo $feedback->ejecutado->FldCaption() ?>
			<?php if ($feedback->ejecutado->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($feedback->ejecutado->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$feedback_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($feedback->ExportAll && $feedback->Export <> "") {
	$feedback_list->StopRec = $feedback_list->TotalRecs;
} else {

	// Set the last record to display
	if ($feedback_list->TotalRecs > $feedback_list->StartRec + $feedback_list->DisplayRecs - 1)
		$feedback_list->StopRec = $feedback_list->StartRec + $feedback_list->DisplayRecs - 1;
	else
		$feedback_list->StopRec = $feedback_list->TotalRecs;
}
$feedback_list->RecCnt = $feedback_list->StartRec - 1;
if ($feedback_list->Recordset && !$feedback_list->Recordset->EOF) {
	$feedback_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $feedback_list->StartRec > 1)
		$feedback_list->Recordset->Move($feedback_list->StartRec - 1);
} elseif (!$feedback->AllowAddDeleteRow && $feedback_list->StopRec == 0) {
	$feedback_list->StopRec = $feedback->GridAddRowCount;
}

// Initialize aggregate
$feedback->RowType = EW_ROWTYPE_AGGREGATEINIT;
$feedback->ResetAttrs();
$feedback_list->RenderRow();
while ($feedback_list->RecCnt < $feedback_list->StopRec) {
	$feedback_list->RecCnt++;
	if (intval($feedback_list->RecCnt) >= intval($feedback_list->StartRec)) {
		$feedback_list->RowCnt++;

		// Set up key count
		$feedback_list->KeyCount = $feedback_list->RowIndex;

		// Init row class and style
		$feedback->ResetAttrs();
		$feedback->CssClass = "";
		if ($feedback->CurrentAction == "gridadd") {
		} else {
			$feedback_list->LoadRowValues($feedback_list->Recordset); // Load row values
		}
		$feedback->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$feedback->RowAttrs = array_merge($feedback->RowAttrs, array('data-rowindex'=>$feedback_list->RowCnt, 'id'=>'r' . $feedback_list->RowCnt . '_feedback', 'data-rowtype'=>$feedback->RowType));

		// Render row
		$feedback_list->RenderRow();

		// Render list options
		$feedback_list->RenderListOptions();
?>
	<tr<?php echo $feedback->RowAttributes() ?>>
<?php

// Render list options (body, left)
$feedback_list->ListOptions->Render("body", "left", $feedback_list->RowCnt);
?>
	<?php if ($feedback->idfeedback->Visible) { // idfeedback ?>
		<td<?php echo $feedback->idfeedback->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_idfeedback" class="feedback_idfeedback">
<span<?php echo $feedback->idfeedback->ViewAttributes() ?>>
<?php echo $feedback->idfeedback->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $feedback_list->PageObjName . "_row_" . $feedback_list->RowCnt ?>"></a>
	<?php if ($feedback->Titulo->Visible) { // Titulo ?>
		<td<?php echo $feedback->Titulo->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_Titulo" class="feedback_Titulo">
<span<?php echo $feedback->Titulo->ViewAttributes() ?>>
<?php echo $feedback->Titulo->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($feedback->Url->Visible) { // Url ?>
		<td<?php echo $feedback->Url->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_Url" class="feedback_Url">
<span<?php echo $feedback->Url->ViewAttributes() ?>>
<?php echo $feedback->Url->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($feedback->autor->Visible) { // autor ?>
		<td<?php echo $feedback->autor->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_autor" class="feedback_autor">
<span<?php echo $feedback->autor->ViewAttributes() ?>>
<?php echo $feedback->autor->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($feedback->paises_target_blogs->Visible) { // paises_target_blogs ?>
		<td<?php echo $feedback->paises_target_blogs->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_paises_target_blogs" class="feedback_paises_target_blogs">
<span<?php echo $feedback->paises_target_blogs->ViewAttributes() ?>>
<?php echo $feedback->paises_target_blogs->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($feedback->paises_target_fbg->Visible) { // paises_target_fbg ?>
		<td<?php echo $feedback->paises_target_fbg->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_paises_target_fbg" class="feedback_paises_target_fbg">
<span<?php echo $feedback->paises_target_fbg->ViewAttributes() ?>>
<?php echo $feedback->paises_target_fbg->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($feedback->fecha->Visible) { // fecha ?>
		<td<?php echo $feedback->fecha->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_fecha" class="feedback_fecha">
<span<?php echo $feedback->fecha->ViewAttributes() ?>>
<?php echo $feedback->fecha->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($feedback->ejecutado->Visible) { // ejecutado ?>
		<td<?php echo $feedback->ejecutado->CellAttributes() ?>><span id="el<?php echo $feedback_list->RowCnt ?>_feedback_ejecutado" class="feedback_ejecutado">
<span<?php echo $feedback->ejecutado->ViewAttributes() ?>>
<?php echo $feedback->ejecutado->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$feedback_list->ListOptions->Render("body", "right", $feedback_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($feedback->CurrentAction <> "gridadd")
		$feedback_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($feedback->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($feedback_list->Recordset)
	$feedback_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($feedback->CurrentAction <> "gridadd" && $feedback->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($feedback_list->Pager)) $feedback_list->Pager = new cPrevNextPager($feedback_list->StartRec, $feedback_list->DisplayRecs, $feedback_list->TotalRecs) ?>
<?php if ($feedback_list->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($feedback_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $feedback_list->PageUrl() ?>start=<?php echo $feedback_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;" /></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($feedback_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $feedback_list->PageUrl() ?>start=<?php echo $feedback_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;" /></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $feedback_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($feedback_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $feedback_list->PageUrl() ?>start=<?php echo $feedback_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;" /></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
<!--last page button-->
	<?php if ($feedback_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $feedback_list->PageUrl() ?>start=<?php echo $feedback_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;" /></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
	<td><span class="phpmaker"><?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $feedback_list->Pager->PageCount ?></span></td>
	</tr></tbody></table>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $feedback_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $feedback_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $feedback_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($feedback_list->SearchWhere == "0=101") { ?>
	<span class="alert"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="alert"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
</form>
<div>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($feedback_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $feedback_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php } ?>
</div>
<script type="text/javascript">
ffeedbacklistsrch.Init();
ffeedbacklist.Init();
</script>
<?php
$feedback_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$feedback_list->Page_Terminate();
?>
