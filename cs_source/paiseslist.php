<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
define("EW_DEFAULT_LOCALE", "Es_es", TRUE);
@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "paisesinfo.php" ?>
<?php include_once "ownersinfo.php" ?>
<?php include_once "fb_gruposgridcls.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$paises_list = NULL; // Initialize page object first

class cpaises_list extends cpaises {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'paises';

	// Page object name
	var $PageObjName = 'paises_list';

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

		// Table object (paises)
		if (!isset($GLOBALS["paises"])) {
			$GLOBALS["paises"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["paises"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this-> james_url("paisesadd.php") . '?'.EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "paisesdelete.php";
		$this->MultiUpdateUrl = "paisesupdate.php";

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'paises', TRUE);

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
			$this->idpaises->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idpaises->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $Keyword);
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
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->admin); // admin
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
				$this->nombre->setSort("");
				$this->admin->setSort("");
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
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "detail_fb_grupos"
		$item = &$this->ListOptions->Add("detail_fb_grupos");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;
		if (!isset($GLOBALS["fb_grupos_grid"])) $GLOBALS["fb_grupos_grid"] = new cfb_grupos_grid;

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

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink  label label-important\"" . "" . " href=\"" . $this->DeleteUrl . "\">" . $Language->Phrase("DeleteLink") . "</a>";

		// "detail_fb_grupos"
		$oListOpt = &$this->ListOptions->Items["detail_fb_grupos"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = $Language->Phrase("DetailLink") . $Language->TablePhrase("fb_grupos", "TblCaption");
			$oListOpt->Body = "<a class=\"ewRowLink label label-warning\" href=\"fb_gruposlist.php?" . EW_TABLE_SHOW_MASTER . "=paises&idpaises=" . urlencode(strval($this->idpaises->CurrentValue)) . "\"><i class='icon-list-alt icon-white'></i> " . $oListOpt->Body . "</a>";
			$links = "";
			if ($GLOBALS["fb_grupos_grid"]->DetailEdit && $Security->IsLoggedIn() && $Security->IsLoggedIn())
				$links .= "<a class=\"ewRowLink label label-warning \" href=\"" . $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=fb_grupos") . "\"><i class='icon-list-alt icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
			if ($links <> "") $oListOpt->Body .= "<br />" . $links;
		}
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
		$this->idpaises->setDbValue($rs->fields('idpaises'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->admin->setDbValue($rs->fields('admin'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idpaises")) <> "")
			$this->idpaises->CurrentValue = $this->getKey("idpaises"); // idpaises
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
		// idpaises
		// nombre
		// admin

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idpaises
			$this->idpaises->ViewValue = $this->idpaises->CurrentValue;
			$this->idpaises->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// admin
			if (strval($this->admin->CurrentValue) <> "") {
				$sFilterWrk = "`id_owners`" . ew_SearchString("=", $this->admin->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_owners`, `Correo Electronico` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `owners`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->admin->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->admin->ViewValue = $this->admin->CurrentValue;
				}
			} else {
				$this->admin->ViewValue = NULL;
			}
			$this->admin->ViewCustomAttributes = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// admin
			$this->admin->LinkCustomAttributes = "";
			$this->admin->HrefValue = "";
			$this->admin->TooltipValue = "";
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
if (!isset($paises_list)) $paises_list = new cpaises_list();

// Page init
$paises_list->Page_Init();

// Page main
$paises_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var paises_list = new ew_Page("paises_list");
paises_list.PageID = "list"; // Page ID
var EW_PAGE_ID = paises_list.PageID; // For backward compatibility

// Form object
var fpaiseslist = new ew_Form("fpaiseslist");

// Form_CustomValidate event
fpaiseslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpaiseslist.ValidateRequired = true;
<?php } else { ?>
fpaiseslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpaiseslist.Lists["x_admin"] = {"LinkField":"x_id_owners","Ajax":true,"AutoFill":false,"DisplayFields":["x_Correo_Electronico","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fpaiseslistsrch = new ew_Form("fpaiseslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$paises_list->TotalRecs = $paises->SelectRecordCount();
	} else {
		if ($paises_list->Recordset = $paises_list->LoadRecordset())
			$paises_list->TotalRecs = $paises_list->Recordset->RecordCount();
	}
	$paises_list->StartRec = 1;
	if ($paises_list->DisplayRecs <= 0 || ($paises->Export <> "" && $paises->ExportAll)) // Display all records
		$paises_list->DisplayRecs = $paises_list->TotalRecs;
	if (!($paises->Export <> "" && $paises->ExportAll))
		$paises_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$paises_list->Recordset = $paises_list->LoadRecordset($paises_list->StartRec-1, $paises_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $paises->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php /*jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar3<span class="caret"></span></button><ul class="dropdown-menu">';
$paises_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($paises->Export == "" && $paises->CurrentAction == "") { ?>
<div class="accordion" id="accordion2">
<div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        <?php echo $Language->Phrase("Search") ?>
      </a>
    </div>
<div id="collapseOne" class="accordion-body collapse">
<div class="accordion-inner">
<form onsubmit="return ewForms[this.id].Submit();" name="fpaiseslistsrch" id="fpaiseslistsrch" class="ewForm navbar-form pull-left" action="<?php echo ew_CurrentPage() ?>">
<!--
<a href="javascript:fpaiseslistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fpaiseslistsrch_SearchImage" src="http://cdn.registrodemascotas.co.cr/collapse.gif" alt="" width="9" height="9" style="border: 0;" /></a><span class="phpmaker"><?php echo $Language->Phrase("Search") ?></span><br />
-->
<div id="fpaiseslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search" />
<input type="hidden" name="t" value="paises" />
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>"  class="span2"  value="<?php echo ew_HtmlEncode($paises_list->BasicSearch->getKeyword()) ?>" />
	<input type="submit" class="btn" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>" />&nbsp;
	<a class="btn btn-warning" href="<?php echo $paises_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($paises_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($paises_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($paises_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $paises_list->ShowPageHeader(); ?>
<?php
$paises_list->ShowMessage();
?>
<div class="ewGridUpperPanel">
<?php if ($paises->CurrentAction <> "gridadd" && $paises->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($paises_list->Pager)) $paises_list->Pager = new cNumericPager($paises_list->StartRec, $paises_list->DisplayRecs, $paises_list->TotalRecs, $paises_list->RecRange) ?>
<?php if ($paises_list->Pager->RecordCount > 0) { ?>
	<ul><?php if ($paises_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $paises_list->PageUrl() ?>start=<?php echo $paises_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($paises_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $paises_list->PageUrl() ?>start=<?php echo $paises_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($paises_list->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $paises_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($paises_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $paises_list->PageUrl() ?>start=<?php echo $paises_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($paises_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $paises_list->PageUrl() ?>start=<?php echo $paises_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
	<?php if ($paises_list->Pager->ButtonCount > 0) { ?><?php } ?>
	<div style=" margin-top: 1px; " class="pull-right"><span class="label label-info"><i class="icon-ok-sign icon-white"></i> <?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $paises_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $paises_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $paises_list->Pager->RecordCount ?></span></div>
<?php } else { ?>	
	<?php if ($paises_list->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</div>
</form>
<div>
<?php } ?>
<?php //jamesjara
if ( count( $paises_list->ExportOptions->Items) > 0 ) {
	if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
	$paises_list->ExportOptions->Render("body"); 
	if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '</ul></div> ';
}
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($paises_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $paises_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php if ($fb_grupos_grid->DetailAdd && $Security->IsLoggedIn()) { ?>
<a class="ewGridLink  btn btn-success" href="<?php echo $paises->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=fb_grupos" ?>"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $paises->TableCaption() ?>/<?php echo $fb_grupos->TableCaption() ?></a>
<?php } ?>
<?php } ?>
</div>
<form name="fpaiseslist" id="fpaiseslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="paises" />
<div id="gmp_paises" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($paises_list->TotalRecs > 0) { ?>
<table id="tbl_paiseslist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $paises->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$paises_list->RenderListOptions();

// Render list options (header, left)
$paises_list->ListOptions->Render("header", "left");
?>
<?php if ($paises->nombre->Visible) { // nombre ?>
	<?php if ($paises->SortUrl($paises->nombre) == "") { ?>
		<th><span id="elh_paises_nombre" class="paises_nombre">
		<div class="ewTableHeaderBtn"><?php echo $paises->nombre->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $paises->SortUrl($paises->nombre) ?>',1);"><span id="elh_paises_nombre" class="paises_nombre">
			<div class="ewTableHeaderBtn">			
			<?php echo $paises->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($paises->nombre->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($paises->nombre->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($paises->admin->Visible) { // admin ?>
	<?php if ($paises->SortUrl($paises->admin) == "") { ?>
		<th><span id="elh_paises_admin" class="paises_admin">
		<div class="ewTableHeaderBtn"><?php echo $paises->admin->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $paises->SortUrl($paises->admin) ?>',1);"><span id="elh_paises_admin" class="paises_admin">
			<div class="ewTableHeaderBtn">			
			<?php echo $paises->admin->FldCaption() ?>
			<?php if ($paises->admin->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($paises->admin->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$paises_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($paises->ExportAll && $paises->Export <> "") {
	$paises_list->StopRec = $paises_list->TotalRecs;
} else {

	// Set the last record to display
	if ($paises_list->TotalRecs > $paises_list->StartRec + $paises_list->DisplayRecs - 1)
		$paises_list->StopRec = $paises_list->StartRec + $paises_list->DisplayRecs - 1;
	else
		$paises_list->StopRec = $paises_list->TotalRecs;
}
$paises_list->RecCnt = $paises_list->StartRec - 1;
if ($paises_list->Recordset && !$paises_list->Recordset->EOF) {
	$paises_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $paises_list->StartRec > 1)
		$paises_list->Recordset->Move($paises_list->StartRec - 1);
} elseif (!$paises->AllowAddDeleteRow && $paises_list->StopRec == 0) {
	$paises_list->StopRec = $paises->GridAddRowCount;
}

// Initialize aggregate
$paises->RowType = EW_ROWTYPE_AGGREGATEINIT;
$paises->ResetAttrs();
$paises_list->RenderRow();
while ($paises_list->RecCnt < $paises_list->StopRec) {
	$paises_list->RecCnt++;
	if (intval($paises_list->RecCnt) >= intval($paises_list->StartRec)) {
		$paises_list->RowCnt++;

		// Set up key count
		$paises_list->KeyCount = $paises_list->RowIndex;

		// Init row class and style
		$paises->ResetAttrs();
		$paises->CssClass = "";
		if ($paises->CurrentAction == "gridadd") {
		} else {
			$paises_list->LoadRowValues($paises_list->Recordset); // Load row values
		}
		$paises->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$paises->RowAttrs = array_merge($paises->RowAttrs, array('data-rowindex'=>$paises_list->RowCnt, 'id'=>'r' . $paises_list->RowCnt . '_paises', 'data-rowtype'=>$paises->RowType));

		// Render row
		$paises_list->RenderRow();

		// Render list options
		$paises_list->RenderListOptions();
?>
	<tr<?php echo $paises->RowAttributes() ?>>
<?php

// Render list options (body, left)
$paises_list->ListOptions->Render("body", "left", $paises_list->RowCnt);
?>
	<?php if ($paises->nombre->Visible) { // nombre ?>
		<td<?php echo $paises->nombre->CellAttributes() ?>><span id="el<?php echo $paises_list->RowCnt ?>_paises_nombre" class="paises_nombre">
<span<?php echo $paises->nombre->ViewAttributes() ?>>
<?php echo $paises->nombre->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $paises_list->PageObjName . "_row_" . $paises_list->RowCnt ?>"></a>
	<?php if ($paises->admin->Visible) { // admin ?>
		<td<?php echo $paises->admin->CellAttributes() ?>><span id="el<?php echo $paises_list->RowCnt ?>_paises_admin" class="paises_admin">
<span<?php echo $paises->admin->ViewAttributes() ?>>
<?php echo $paises->admin->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$paises_list->ListOptions->Render("body", "right", $paises_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($paises->CurrentAction <> "gridadd")
		$paises_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($paises->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($paises_list->Recordset)
	$paises_list->Recordset->Close();
?>
<script type="text/javascript">
fpaiseslistsrch.Init();
fpaiseslist.Init();
</script>
<?php
$paises_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$paises_list->Page_Terminate();
?>
