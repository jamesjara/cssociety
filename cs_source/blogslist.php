<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
define("EW_DEFAULT_LOCALE", "Es_es", TRUE);
@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "blogsinfo.php" ?>
<?php include_once "ownersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$blogs_list = NULL; // Initialize page object first

class cblogs_list extends cblogs {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'blogs';

	// Page object name
	var $PageObjName = 'blogs_list';

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

		// Table object (blogs)
		if (!isset($GLOBALS["blogs"])) {
			$GLOBALS["blogs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["blogs"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this->james_url("blogsadd.php");
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "blogsdelete.php";
		$this->MultiUpdateUrl = "blogsupdate.php";

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'blogs', TRUE);

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
		$this->idforos_fb->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->owner->Visible = !$this->IsAddOrEdit();

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
			$this->idforos_fb->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idforos_fb->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->user, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->pass, $Keyword);
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
			$this->UpdateSort($this->idforos_fb); // idforos_fb
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->pais); // pais
			$this->UpdateSort($this->tipo); // tipo
			$this->UpdateSort($this->user); // user
			$this->UpdateSort($this->pass); // pass
			$this->UpdateSort($this->owner); // owner
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
				$this->idforos_fb->setSort("");
				$this->nombre->setSort("");
				$this->pais->setSort("");
				$this->tipo->setSort("");
				$this->user->setSort("");
				$this->pass->setSort("");
				$this->owner->setSort("");
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
		$this->idforos_fb->setDbValue($rs->fields('idforos_fb'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->pais->setDbValue($rs->fields('pais'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->user->setDbValue($rs->fields('user'));
		$this->pass->setDbValue($rs->fields('pass'));
		$this->owner->setDbValue($rs->fields('owner'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idforos_fb")) <> "")
			$this->idforos_fb->CurrentValue = $this->getKey("idforos_fb"); // idforos_fb
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
		// idforos_fb
		// nombre
		// pais
		// tipo
		// user
		// pass
		// owner

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idforos_fb
			$this->idforos_fb->ViewValue = $this->idforos_fb->CurrentValue;
			$this->idforos_fb->ViewCustomAttributes = "";

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
					$this->pais->ViewValue .= ew_ValueSeparator(1,$blogs->pais) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->pais->ViewValue = $this->pais->CurrentValue;
				}
			} else {
				$this->pais->ViewValue = NULL;
			}
			$this->pais->ViewCustomAttributes = "";

			// tipo
			if (strval($this->tipo->CurrentValue) <> "") {
				switch ($this->tipo->CurrentValue) {
					case $this->tipo->FldTagValue(1):
						$this->tipo->ViewValue = $this->tipo->FldTagCaption(1) <> "" ? $this->tipo->FldTagCaption(1) : $this->tipo->CurrentValue;
						break;
					default:
						$this->tipo->ViewValue = $this->tipo->CurrentValue;
				}
			} else {
				$this->tipo->ViewValue = NULL;
			}
			$this->tipo->ViewCustomAttributes = "";

			// user
			$this->user->ViewValue = $this->user->CurrentValue;
			$this->user->ViewCustomAttributes = "";

			// pass
			$this->pass->ViewValue = $this->pass->CurrentValue;
			$this->pass->ViewCustomAttributes = "";

			// owner
			$this->owner->ViewValue = $this->owner->CurrentValue;
			$this->owner->ViewCustomAttributes = "";

			// idforos_fb
			$this->idforos_fb->LinkCustomAttributes = "";
			$this->idforos_fb->HrefValue = "";
			$this->idforos_fb->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// pais
			$this->pais->LinkCustomAttributes = "";
			$this->pais->HrefValue = "";
			$this->pais->TooltipValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";

			// pass
			$this->pass->LinkCustomAttributes = "";
			$this->pass->HrefValue = "";
			$this->pass->TooltipValue = "";

			// owner
			$this->owner->LinkCustomAttributes = "";
			$this->owner->HrefValue = "";
			$this->owner->TooltipValue = "";
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
if (!isset($blogs_list)) $blogs_list = new cblogs_list();

// Page init
$blogs_list->Page_Init();

// Page main
$blogs_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var blogs_list = new ew_Page("blogs_list");
blogs_list.PageID = "list"; // Page ID
var EW_PAGE_ID = blogs_list.PageID; // For backward compatibility

// Form object
var fblogslist = new ew_Form("fblogslist");

// Form_CustomValidate event
fblogslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fblogslist.ValidateRequired = true;
<?php } else { ?>
fblogslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fblogslist.Lists["x_pais"] = {"LinkField":"x_idpaises","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","x_admin","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fblogslistsrch = new ew_Form("fblogslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$blogs_list->TotalRecs = $blogs->SelectRecordCount();
	} else {
		if ($blogs_list->Recordset = $blogs_list->LoadRecordset())
			$blogs_list->TotalRecs = $blogs_list->Recordset->RecordCount();
	}
	$blogs_list->StartRec = 1;
	if ($blogs_list->DisplayRecs <= 0 || ($blogs->Export <> "" && $blogs->ExportAll)) // Display all records
		$blogs_list->DisplayRecs = $blogs_list->TotalRecs;
	if (!($blogs->Export <> "" && $blogs->ExportAll))
		$blogs_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$blogs_list->Recordset = $blogs_list->LoadRecordset($blogs_list->StartRec-1, $blogs_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $blogs->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php /*jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar3<span class="caret"></span></button><ul class="dropdown-menu">';
$blogs_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($blogs->Export == "" && $blogs->CurrentAction == "") { ?>
<div class="accordion" id="accordion2">
<div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        <?php echo $Language->Phrase("Search") ?>
      </a>
    </div>
<div id="collapseOne" class="accordion-body collapse">
<div class="accordion-inner">
<form onsubmit="return ewForms[this.id].Submit();" name="fblogslistsrch" id="fblogslistsrch" class="ewForm navbar-form pull-left" action="<?php echo ew_CurrentPage() ?>">
<!--
<a href="javascript:fblogslistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fblogslistsrch_SearchImage" src="http://cdn.registrodemascotas.co.cr/collapse.gif" alt="" width="9" height="9" style="border: 0;" /></a><span class="phpmaker"><?php echo $Language->Phrase("Search") ?></span><br />
-->
<div id="fblogslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search" />
<input type="hidden" name="t" value="blogs" />
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>"  class="span2"  value="<?php echo ew_HtmlEncode($blogs_list->BasicSearch->getKeyword()) ?>" />
	<input type="submit" class="btn" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>" />&nbsp;
	<a class="btn btn-warning" href="<?php echo $blogs_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($blogs_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($blogs_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($blogs_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $blogs_list->ShowPageHeader(); ?>
<?php
$blogs_list->ShowMessage();
?>
<form name="fblogslist" id="fblogslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="blogs" />
<div id="gmp_blogs" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($blogs_list->TotalRecs > 0) { ?>
<table id="tbl_blogslist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $blogs->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$blogs_list->RenderListOptions();

// Render list options (header, left)
$blogs_list->ListOptions->Render("header", "left");
?>
<?php if ($blogs->idforos_fb->Visible) { // idforos_fb ?>
	<?php if ($blogs->SortUrl($blogs->idforos_fb) == "") { ?>
		<th><span id="elh_blogs_idforos_fb" class="blogs_idforos_fb">
		<div class="ewTableHeaderBtn"><?php echo $blogs->idforos_fb->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $blogs->SortUrl($blogs->idforos_fb) ?>',1);"><span id="elh_blogs_idforos_fb" class="blogs_idforos_fb">
			<div class="ewTableHeaderBtn">			
			<?php echo $blogs->idforos_fb->FldCaption() ?>
			<?php if ($blogs->idforos_fb->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($blogs->idforos_fb->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($blogs->nombre->Visible) { // nombre ?>
	<?php if ($blogs->SortUrl($blogs->nombre) == "") { ?>
		<th><span id="elh_blogs_nombre" class="blogs_nombre">
		<div class="ewTableHeaderBtn"><?php echo $blogs->nombre->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $blogs->SortUrl($blogs->nombre) ?>',1);"><span id="elh_blogs_nombre" class="blogs_nombre">
			<div class="ewTableHeaderBtn">			
			<?php echo $blogs->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($blogs->nombre->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($blogs->nombre->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($blogs->pais->Visible) { // pais ?>
	<?php if ($blogs->SortUrl($blogs->pais) == "") { ?>
		<th><span id="elh_blogs_pais" class="blogs_pais">
		<div class="ewTableHeaderBtn"><?php echo $blogs->pais->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $blogs->SortUrl($blogs->pais) ?>',1);"><span id="elh_blogs_pais" class="blogs_pais">
			<div class="ewTableHeaderBtn">			
			<?php echo $blogs->pais->FldCaption() ?>
			<?php if ($blogs->pais->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($blogs->pais->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($blogs->tipo->Visible) { // tipo ?>
	<?php if ($blogs->SortUrl($blogs->tipo) == "") { ?>
		<th><span id="elh_blogs_tipo" class="blogs_tipo">
		<div class="ewTableHeaderBtn"><?php echo $blogs->tipo->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $blogs->SortUrl($blogs->tipo) ?>',1);"><span id="elh_blogs_tipo" class="blogs_tipo">
			<div class="ewTableHeaderBtn">			
			<?php echo $blogs->tipo->FldCaption() ?>
			<?php if ($blogs->tipo->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($blogs->tipo->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($blogs->user->Visible) { // user ?>
	<?php if ($blogs->SortUrl($blogs->user) == "") { ?>
		<th><span id="elh_blogs_user" class="blogs_user">
		<div class="ewTableHeaderBtn"><?php echo $blogs->user->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $blogs->SortUrl($blogs->user) ?>',1);"><span id="elh_blogs_user" class="blogs_user">
			<div class="ewTableHeaderBtn">			
			<?php echo $blogs->user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($blogs->user->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($blogs->user->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($blogs->pass->Visible) { // pass ?>
	<?php if ($blogs->SortUrl($blogs->pass) == "") { ?>
		<th><span id="elh_blogs_pass" class="blogs_pass">
		<div class="ewTableHeaderBtn"><?php echo $blogs->pass->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $blogs->SortUrl($blogs->pass) ?>',1);"><span id="elh_blogs_pass" class="blogs_pass">
			<div class="ewTableHeaderBtn">			
			<?php echo $blogs->pass->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($blogs->pass->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($blogs->pass->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($blogs->owner->Visible) { // owner ?>
	<?php if ($blogs->SortUrl($blogs->owner) == "") { ?>
		<th><span id="elh_blogs_owner" class="blogs_owner">
		<div class="ewTableHeaderBtn"><?php echo $blogs->owner->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $blogs->SortUrl($blogs->owner) ?>',1);"><span id="elh_blogs_owner" class="blogs_owner">
			<div class="ewTableHeaderBtn">			
			<?php echo $blogs->owner->FldCaption() ?>
			<?php if ($blogs->owner->getSort() == "ASC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($blogs->owner->getSort() == "DESC") { ?><img src="http://cdn.registrodemascotas.co.cr/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$blogs_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($blogs->ExportAll && $blogs->Export <> "") {
	$blogs_list->StopRec = $blogs_list->TotalRecs;
} else {

	// Set the last record to display
	if ($blogs_list->TotalRecs > $blogs_list->StartRec + $blogs_list->DisplayRecs - 1)
		$blogs_list->StopRec = $blogs_list->StartRec + $blogs_list->DisplayRecs - 1;
	else
		$blogs_list->StopRec = $blogs_list->TotalRecs;
}
$blogs_list->RecCnt = $blogs_list->StartRec - 1;
if ($blogs_list->Recordset && !$blogs_list->Recordset->EOF) {
	$blogs_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $blogs_list->StartRec > 1)
		$blogs_list->Recordset->Move($blogs_list->StartRec - 1);
} elseif (!$blogs->AllowAddDeleteRow && $blogs_list->StopRec == 0) {
	$blogs_list->StopRec = $blogs->GridAddRowCount;
}

// Initialize aggregate
$blogs->RowType = EW_ROWTYPE_AGGREGATEINIT;
$blogs->ResetAttrs();
$blogs_list->RenderRow();
while ($blogs_list->RecCnt < $blogs_list->StopRec) {
	$blogs_list->RecCnt++;
	if (intval($blogs_list->RecCnt) >= intval($blogs_list->StartRec)) {
		$blogs_list->RowCnt++;

		// Set up key count
		$blogs_list->KeyCount = $blogs_list->RowIndex;

		// Init row class and style
		$blogs->ResetAttrs();
		$blogs->CssClass = "";
		if ($blogs->CurrentAction == "gridadd") {
		} else {
			$blogs_list->LoadRowValues($blogs_list->Recordset); // Load row values
		}
		$blogs->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$blogs->RowAttrs = array_merge($blogs->RowAttrs, array('data-rowindex'=>$blogs_list->RowCnt, 'id'=>'r' . $blogs_list->RowCnt . '_blogs', 'data-rowtype'=>$blogs->RowType));

		// Render row
		$blogs_list->RenderRow();

		// Render list options
		$blogs_list->RenderListOptions();
?>
	<tr<?php echo $blogs->RowAttributes() ?>>
<?php

// Render list options (body, left)
$blogs_list->ListOptions->Render("body", "left", $blogs_list->RowCnt);
?>
	<?php if ($blogs->idforos_fb->Visible) { // idforos_fb ?>
		<td<?php echo $blogs->idforos_fb->CellAttributes() ?>><span id="el<?php echo $blogs_list->RowCnt ?>_blogs_idforos_fb" class="blogs_idforos_fb">
<span<?php echo $blogs->idforos_fb->ViewAttributes() ?>>
<?php echo $blogs->idforos_fb->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $blogs_list->PageObjName . "_row_" . $blogs_list->RowCnt ?>"></a>
	<?php if ($blogs->nombre->Visible) { // nombre ?>
		<td<?php echo $blogs->nombre->CellAttributes() ?>><span id="el<?php echo $blogs_list->RowCnt ?>_blogs_nombre" class="blogs_nombre">
<span<?php echo $blogs->nombre->ViewAttributes() ?>>
<?php echo $blogs->nombre->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($blogs->pais->Visible) { // pais ?>
		<td<?php echo $blogs->pais->CellAttributes() ?>><span id="el<?php echo $blogs_list->RowCnt ?>_blogs_pais" class="blogs_pais">
<span<?php echo $blogs->pais->ViewAttributes() ?>>
<?php echo $blogs->pais->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($blogs->tipo->Visible) { // tipo ?>
		<td<?php echo $blogs->tipo->CellAttributes() ?>><span id="el<?php echo $blogs_list->RowCnt ?>_blogs_tipo" class="blogs_tipo">
<span<?php echo $blogs->tipo->ViewAttributes() ?>>
<?php echo $blogs->tipo->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($blogs->user->Visible) { // user ?>
		<td<?php echo $blogs->user->CellAttributes() ?>><span id="el<?php echo $blogs_list->RowCnt ?>_blogs_user" class="blogs_user">
<span<?php echo $blogs->user->ViewAttributes() ?>>
<?php echo $blogs->user->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($blogs->pass->Visible) { // pass ?>
		<td<?php echo $blogs->pass->CellAttributes() ?>><span id="el<?php echo $blogs_list->RowCnt ?>_blogs_pass" class="blogs_pass">
<span<?php echo $blogs->pass->ViewAttributes() ?>>
<?php echo $blogs->pass->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($blogs->owner->Visible) { // owner ?>
		<td<?php echo $blogs->owner->CellAttributes() ?>><span id="el<?php echo $blogs_list->RowCnt ?>_blogs_owner" class="blogs_owner">
<span<?php echo $blogs->owner->ViewAttributes() ?>>
<?php echo $blogs->owner->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$blogs_list->ListOptions->Render("body", "right", $blogs_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($blogs->CurrentAction <> "gridadd")
		$blogs_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($blogs->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($blogs_list->Recordset)
	$blogs_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($blogs->CurrentAction <> "gridadd" && $blogs->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($blogs_list->Pager)) $blogs_list->Pager = new cPrevNextPager($blogs_list->StartRec, $blogs_list->DisplayRecs, $blogs_list->TotalRecs) ?>
<?php if ($blogs_list->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($blogs_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $blogs_list->PageUrl() ?>start=<?php echo $blogs_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;" /></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($blogs_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $blogs_list->PageUrl() ?>start=<?php echo $blogs_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;" /></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $blogs_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($blogs_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $blogs_list->PageUrl() ?>start=<?php echo $blogs_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;" /></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
<!--last page button-->
	<?php if ($blogs_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $blogs_list->PageUrl() ?>start=<?php echo $blogs_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;" /></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;" /></td>
	<?php } ?>
	<td><span class="phpmaker"><?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $blogs_list->Pager->PageCount ?></span></td>
	</tr></tbody></table>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $blogs_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $blogs_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $blogs_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($blogs_list->SearchWhere == "0=101") { ?>
	<span class="alert"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="alert"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
</form>
<div>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($blogs_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $blogs_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php } ?>
</div>
<script type="text/javascript">
fblogslistsrch.Init();
fblogslist.Init();
</script>
<?php
$blogs_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$blogs_list->Page_Terminate();
?>
