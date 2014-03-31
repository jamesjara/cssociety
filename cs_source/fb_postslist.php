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

$fb_posts_list = NULL; // Initialize page object first

class cfb_posts_list extends cfb_posts {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'fb_posts';

	// Page object name
	var $PageObjName = 'fb_posts_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this->james_url("fb_postsadd.php");
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "fb_postsdelete.php";
		$this->MultiUpdateUrl = "fb_postsupdate.php";

		// Table object (fb_grupos)
		if (!isset($GLOBALS['fb_grupos'])) $GLOBALS['fb_grupos'] = new cfb_grupos();

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_posts', TRUE);

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
	var $DisplayRecs = 50;
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

			// Set up master detail parameters
			$this->SetUpMasterParms();

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
			$this->DisplayRecs = 50; // Load default
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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "fb_grupos") {
			global $fb_grupos;
			$rsmaster = $fb_grupos->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("fb_gruposlist.php"); // Return to master page
			} else {
				$fb_grupos->LoadListRowValues($rsmaster);
				$fb_grupos->RowType = EW_ROWTYPE_MASTER; // Master row
				$fb_grupos->RenderListRow();
				$rsmaster->Close();
			}
		}

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
			$this->idfb_posts->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idfb_posts->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->id, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->created_time, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->actions, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->icon, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->is_published, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->message, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->link, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->object_id, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->picture, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->privacy, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->promotion_status, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->timeline_visibility, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->type, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->updated_time, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->caption, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->source, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->from, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->to, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->comments, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->id_grupo, $Keyword);
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
			$this->UpdateSort($this->created_time); // created_time
			$this->UpdateSort($this->message); // message
			$this->UpdateSort($this->link); // link
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->caption); // caption
			$this->UpdateSort($this->description); // description
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->source); // source
			$this->UpdateSort($this->from); // from
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
				$this->created_time->setSort("DESC");
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->id_grupo->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->created_time->setSort("");
				$this->message->setSort("");
				$this->link->setSort("");
				$this->type->setSort("");
				$this->caption->setSort("");
				$this->description->setSort("");
				$this->name->setSort("");
				$this->source->setSort("");
				$this->from->setSort("");
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idfb_posts")) <> "")
			$this->idfb_posts->CurrentValue = $this->getKey("idfb_posts"); // idfb_posts
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

			// id_grupo
			$this->id_grupo->ViewValue = $this->id_grupo->CurrentValue;
			$this->id_grupo->ViewCustomAttributes = "";

			// created_time
			$this->created_time->LinkCustomAttributes = "";
			$this->created_time->HrefValue = "";
			$this->created_time->TooltipValue = "";

			// message
			$this->message->LinkCustomAttributes = "";
			$this->message->HrefValue = "";
			$this->message->TooltipValue = "";

			// link
			$this->link->LinkCustomAttributes = "";
			$this->link->HrefValue = "";
			$this->link->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "fb_grupos") {
				$bValidMaster = TRUE;
				if (@$_GET["super_id"] <> "") {
					$GLOBALS["fb_grupos"]->super_id->setQueryStringValue($_GET["super_id"]);
					$this->id_grupo->setQueryStringValue($GLOBALS["fb_grupos"]->super_id->QueryStringValue);
					$this->id_grupo->setSessionValue($this->id_grupo->QueryStringValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "fb_grupos") {
				if ($this->id_grupo->QueryStringValue == "") $this->id_grupo->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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

		$header  = '<a class="operacion btn btn-success" name="RefreshGroupFromFb"  id="'.preg_replace('/\D/', '', $_GET['super_id'] ).'" href="#">Refresh From FB</a> ';  
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
		$opt = &$this->ListOptions->Add("delete_fb");
		$opt->Header = "Action";
		$opt->OnLeft = TRUE; // Link on left
		$opt->MoveTo(0); // Move to first column
	}

		// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:      
		$id =  $this->id->ViewValue;               
		$this->ListOptions->Items["delete_fb"]->Body = '<a class="operacion btn btn-success" name="DeletePostFromFb"  id="'. $id.'" href="#">Delete</a> ';
	}                                                                                                                                                        
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($fb_posts_list)) $fb_posts_list = new cfb_posts_list();

// Page init
$fb_posts_list->Page_Init();

// Page main
$fb_posts_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_posts_list = new ew_Page("fb_posts_list");
fb_posts_list.PageID = "list"; // Page ID
var EW_PAGE_ID = fb_posts_list.PageID; // For backward compatibility

// Form object
var ffb_postslist = new ew_Form("ffb_postslist");

// Form_CustomValidate event
ffb_postslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_postslist.ValidateRequired = true;
<?php } else { ?>
ffb_postslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var ffb_postslistsrch = new ew_Form("ffb_postslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (($fb_posts->Export == "") || (EW_EXPORT_MASTER_RECORD && $fb_posts->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "fb_gruposlist.php";
if ($fb_posts_list->DbMasterFilter <> "" && $fb_posts->getCurrentMasterTable() == "fb_grupos") {
	if ($fb_posts_list->MasterRecordExists) {
		if ($fb_posts->getCurrentMasterTable() == $fb_posts->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<span class="ewTitle ewMasterTableTitle"><i class="icon-resize-small"></i> <?php echo $Language->Phrase("MasterRecord") ?><?php echo $fb_grupos->TableCaption() ?>&nbsp;&nbsp;</span>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar2<span class="caret"></span></button><ul class="dropdown-menu">';
$fb_posts_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';*/
?>
<a href="<?php echo $gsMasterReturnUrl ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("BackToMasterRecordPage") ?></a>
<?php include_once "fb_gruposmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$fb_posts_list->TotalRecs = $fb_posts->SelectRecordCount();
	} else {
		if ($fb_posts_list->Recordset = $fb_posts_list->LoadRecordset())
			$fb_posts_list->TotalRecs = $fb_posts_list->Recordset->RecordCount();
	}
	$fb_posts_list->StartRec = 1;
	if ($fb_posts_list->DisplayRecs <= 0 || ($fb_posts->Export <> "" && $fb_posts->ExportAll)) // Display all records
		$fb_posts_list->DisplayRecs = $fb_posts_list->TotalRecs;
	if (!($fb_posts->Export <> "" && $fb_posts->ExportAll))
		$fb_posts_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$fb_posts_list->Recordset = $fb_posts_list->LoadRecordset($fb_posts_list->StartRec-1, $fb_posts_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_posts->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php if ($fb_posts->getCurrentMasterTable() == "") {  ?>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar1<span class="caret"></span></button><ul class="dropdown-menu">';
$fb_posts_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
<?php } ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($fb_posts->Export == "" && $fb_posts->CurrentAction == "") { ?>
<div class="accordion" id="accordion2">
<div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        <?php echo $Language->Phrase("Search") ?>
      </a>
    </div>
<div id="collapseOne" class="accordion-body collapse">
<div class="accordion-inner">
<form onsubmit="return ewForms[this.id].Submit();" name="ffb_postslistsrch" id="ffb_postslistsrch" class="ewForm navbar-form pull-left" action="<?php echo ew_CurrentPage() ?>">
<!--
<a href="javascript:ffb_postslistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="ffb_postslistsrch_SearchImage" src="http://cdn.registrodemascotas.co.cr/collapse.gif" alt="" width="9" height="9" style="border: 0;" /></a><span class="phpmaker"><?php echo $Language->Phrase("Search") ?></span><br />
-->
<div id="ffb_postslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search" />
<input type="hidden" name="t" value="fb_posts" />
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>"  class="span2"  value="<?php echo ew_HtmlEncode($fb_posts_list->BasicSearch->getKeyword()) ?>" />
	<input type="submit" class="btn" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>" />&nbsp;
	<a class="btn btn-warning" href="<?php echo $fb_posts_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($fb_posts_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($fb_posts_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($fb_posts_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $fb_posts_list->ShowPageHeader(); ?>
<?php
$fb_posts_list->ShowMessage();
?>
<div class="ewGridUpperPanel">
<?php if ($fb_posts->CurrentAction <> "gridadd" && $fb_posts->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($fb_posts_list->Pager)) $fb_posts_list->Pager = new cNumericPager($fb_posts_list->StartRec, $fb_posts_list->DisplayRecs, $fb_posts_list->TotalRecs, $fb_posts_list->RecRange) ?>
<?php if ($fb_posts_list->Pager->RecordCount > 0) { ?>
	<ul><?php if ($fb_posts_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_list->PageUrl() ?>start=<?php echo $fb_posts_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_posts_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_list->PageUrl() ?>start=<?php echo $fb_posts_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($fb_posts_list->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $fb_posts_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($fb_posts_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_list->PageUrl() ?>start=<?php echo $fb_posts_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_posts_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $fb_posts_list->PageUrl() ?>start=<?php echo $fb_posts_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
	<?php if ($fb_posts_list->Pager->ButtonCount > 0) { ?><?php } ?>
	<div style=" margin-top: 1px; " class="pull-right"><span class="label label-info"><i class="icon-ok-sign icon-white"></i> <?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $fb_posts_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $fb_posts_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $fb_posts_list->Pager->RecordCount ?></span></div>
<?php } else { ?>	
	<?php if ($fb_posts_list->SearchWhere == "0=101") { ?>
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
if ( count( $fb_posts_list->ExportOptions->Items) > 0 ) {
	if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
	$fb_posts_list->ExportOptions->Render("body"); 
	if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '</ul></div> ';
}
?>
</div>
<form name="ffb_postslist" id="ffb_postslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="fb_posts" />
<div id="gmp_fb_posts" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($fb_posts_list->TotalRecs > 0) { ?>
<table id="tbl_fb_postslist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $fb_posts->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$fb_posts_list->RenderListOptions();

// Render list options (header, left)
$fb_posts_list->ListOptions->Render("header", "left");
?>
<?php if ($fb_posts->created_time->Visible) { // created_time ?>
	<?php if ($fb_posts->SortUrl($fb_posts->created_time) == "") { ?>
		<th><span id="elh_fb_posts_created_time" class="fb_posts_created_time">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->created_time->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->created_time) ?>',1);"><span id="elh_fb_posts_created_time" class="fb_posts_created_time">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->created_time->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->created_time->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->created_time->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->message->Visible) { // message ?>
	<?php if ($fb_posts->SortUrl($fb_posts->message) == "") { ?>
		<th><span id="elh_fb_posts_message" class="fb_posts_message">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->message->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->message) ?>',1);"><span id="elh_fb_posts_message" class="fb_posts_message">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->message->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->message->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->message->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->link->Visible) { // link ?>
	<?php if ($fb_posts->SortUrl($fb_posts->link) == "") { ?>
		<th><span id="elh_fb_posts_link" class="fb_posts_link">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->link->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->link) ?>',1);"><span id="elh_fb_posts_link" class="fb_posts_link">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->link->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->link->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->link->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->type->Visible) { // type ?>
	<?php if ($fb_posts->SortUrl($fb_posts->type) == "") { ?>
		<th><span id="elh_fb_posts_type" class="fb_posts_type">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->type->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->type) ?>',1);"><span id="elh_fb_posts_type" class="fb_posts_type">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->type->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->type->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->caption->Visible) { // caption ?>
	<?php if ($fb_posts->SortUrl($fb_posts->caption) == "") { ?>
		<th><span id="elh_fb_posts_caption" class="fb_posts_caption">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->caption->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->caption) ?>',1);"><span id="elh_fb_posts_caption" class="fb_posts_caption">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->caption->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->caption->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->caption->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->description->Visible) { // description ?>
	<?php if ($fb_posts->SortUrl($fb_posts->description) == "") { ?>
		<th><span id="elh_fb_posts_description" class="fb_posts_description">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->description->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->description) ?>',1);"><span id="elh_fb_posts_description" class="fb_posts_description">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->description->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->description->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->description->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->name->Visible) { // name ?>
	<?php if ($fb_posts->SortUrl($fb_posts->name) == "") { ?>
		<th><span id="elh_fb_posts_name" class="fb_posts_name">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->name->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->name) ?>',1);"><span id="elh_fb_posts_name" class="fb_posts_name">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->name->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->name->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->source->Visible) { // source ?>
	<?php if ($fb_posts->SortUrl($fb_posts->source) == "") { ?>
		<th><span id="elh_fb_posts_source" class="fb_posts_source">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->source->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->source) ?>',1);"><span id="elh_fb_posts_source" class="fb_posts_source">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->source->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->source->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->source->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->from->Visible) { // from ?>
	<?php if ($fb_posts->SortUrl($fb_posts->from) == "") { ?>
		<th><span id="elh_fb_posts_from" class="fb_posts_from">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->from->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_posts->SortUrl($fb_posts->from) ?>',1);"><span id="elh_fb_posts_from" class="fb_posts_from">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->from->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_posts->from->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->from->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$fb_posts_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($fb_posts->ExportAll && $fb_posts->Export <> "") {
	$fb_posts_list->StopRec = $fb_posts_list->TotalRecs;
} else {

	// Set the last record to display
	if ($fb_posts_list->TotalRecs > $fb_posts_list->StartRec + $fb_posts_list->DisplayRecs - 1)
		$fb_posts_list->StopRec = $fb_posts_list->StartRec + $fb_posts_list->DisplayRecs - 1;
	else
		$fb_posts_list->StopRec = $fb_posts_list->TotalRecs;
}
$fb_posts_list->RecCnt = $fb_posts_list->StartRec - 1;
if ($fb_posts_list->Recordset && !$fb_posts_list->Recordset->EOF) {
	$fb_posts_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $fb_posts_list->StartRec > 1)
		$fb_posts_list->Recordset->Move($fb_posts_list->StartRec - 1);
} elseif (!$fb_posts->AllowAddDeleteRow && $fb_posts_list->StopRec == 0) {
	$fb_posts_list->StopRec = $fb_posts->GridAddRowCount;
}

// Initialize aggregate
$fb_posts->RowType = EW_ROWTYPE_AGGREGATEINIT;
$fb_posts->ResetAttrs();
$fb_posts_list->RenderRow();
while ($fb_posts_list->RecCnt < $fb_posts_list->StopRec) {
	$fb_posts_list->RecCnt++;
	if (intval($fb_posts_list->RecCnt) >= intval($fb_posts_list->StartRec)) {
		$fb_posts_list->RowCnt++;

		// Set up key count
		$fb_posts_list->KeyCount = $fb_posts_list->RowIndex;

		// Init row class and style
		$fb_posts->ResetAttrs();
		$fb_posts->CssClass = "";
		if ($fb_posts->CurrentAction == "gridadd") {
		} else {
			$fb_posts_list->LoadRowValues($fb_posts_list->Recordset); // Load row values
		}
		$fb_posts->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$fb_posts->RowAttrs = array_merge($fb_posts->RowAttrs, array('data-rowindex'=>$fb_posts_list->RowCnt, 'id'=>'r' . $fb_posts_list->RowCnt . '_fb_posts', 'data-rowtype'=>$fb_posts->RowType));

		// Render row
		$fb_posts_list->RenderRow();

		// Render list options
		$fb_posts_list->RenderListOptions();
?>
	<tr<?php echo $fb_posts->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fb_posts_list->ListOptions->Render("body", "left", $fb_posts_list->RowCnt);
?>
	<?php if ($fb_posts->created_time->Visible) { // created_time ?>
		<td<?php echo $fb_posts->created_time->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_created_time" class="fb_posts_created_time">
<span<?php echo $fb_posts->created_time->ViewAttributes() ?>>
<?php echo $fb_posts->created_time->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $fb_posts_list->PageObjName . "_row_" . $fb_posts_list->RowCnt ?>"></a>
	<?php if ($fb_posts->message->Visible) { // message ?>
		<td<?php echo $fb_posts->message->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_message" class="fb_posts_message">
<span<?php echo $fb_posts->message->ViewAttributes() ?>>
<?php echo $fb_posts->message->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->link->Visible) { // link ?>
		<td<?php echo $fb_posts->link->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_link" class="fb_posts_link">
<span<?php echo $fb_posts->link->ViewAttributes() ?>>
<?php echo $fb_posts->link->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->type->Visible) { // type ?>
		<td<?php echo $fb_posts->type->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_type" class="fb_posts_type">
<span<?php echo $fb_posts->type->ViewAttributes() ?>>
<?php echo $fb_posts->type->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->caption->Visible) { // caption ?>
		<td<?php echo $fb_posts->caption->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_caption" class="fb_posts_caption">
<span<?php echo $fb_posts->caption->ViewAttributes() ?>>
<?php echo $fb_posts->caption->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->description->Visible) { // description ?>
		<td<?php echo $fb_posts->description->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_description" class="fb_posts_description">
<span<?php echo $fb_posts->description->ViewAttributes() ?>>
<?php echo $fb_posts->description->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->name->Visible) { // name ?>
		<td<?php echo $fb_posts->name->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_name" class="fb_posts_name">
<span<?php echo $fb_posts->name->ViewAttributes() ?>>
<?php echo $fb_posts->name->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->source->Visible) { // source ?>
		<td<?php echo $fb_posts->source->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_source" class="fb_posts_source">
<span<?php echo $fb_posts->source->ViewAttributes() ?>>
<?php echo $fb_posts->source->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->from->Visible) { // from ?>
		<td<?php echo $fb_posts->from->CellAttributes() ?>><span id="el<?php echo $fb_posts_list->RowCnt ?>_fb_posts_from" class="fb_posts_from">
<span<?php echo $fb_posts->from->ViewAttributes() ?>>
<?php echo $fb_posts->from->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fb_posts_list->ListOptions->Render("body", "right", $fb_posts_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($fb_posts->CurrentAction <> "gridadd")
		$fb_posts_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($fb_posts->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($fb_posts_list->Recordset)
	$fb_posts_list->Recordset->Close();
?>
<script type="text/javascript">
ffb_postslistsrch.Init();
ffb_postslist.Init();
</script>
<?php
$fb_posts_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_posts_list->Page_Terminate();
?>
