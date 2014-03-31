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

$fb_grupos_list = NULL; // Initialize page object first

class cfb_grupos_list extends cfb_grupos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'fb_grupos';

	// Page object name
	var $PageObjName = 'fb_grupos_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this-> james_url("fb_gruposadd.php") . '?'.EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "fb_gruposdelete.php";
		$this->MultiUpdateUrl = "fb_gruposupdate.php";

		// Table object (paises)
		if (!isset($GLOBALS['paises'])) $GLOBALS['paises'] = new cpaises();

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_grupos', TRUE);

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "paises") {
			global $paises;
			$rsmaster = $paises->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("paiseslist.php"); // Return to master page
			} else {
				$paises->LoadListRowValues($rsmaster);
				$paises->RowType = EW_ROWTYPE_MASTER; // Master row
				$paises->RenderListRow();
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
			$this->idfb_grupos->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idfb_grupos->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->nombre, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->url, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->super_id, $Keyword);
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
			$this->UpdateSort($this->pais); // pais
			$this->UpdateSort($this->url); // url
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->pais->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nombre->setSort("");
				$this->pais->setSort("");
				$this->url->setSort("");
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

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "detail_fb_posts"
		$item = &$this->ListOptions->Add("detail_fb_posts");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;
		if (!isset($GLOBALS["fb_posts_grid"])) $GLOBALS["fb_posts_grid"] = new cfb_posts_grid;

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

		// "detail_fb_posts"
		$oListOpt = &$this->ListOptions->Items["detail_fb_posts"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = $Language->Phrase("DetailLink") . $Language->TablePhrase("fb_posts", "TblCaption");
			$oListOpt->Body = "<a class=\"ewRowLink label label-warning\" href=\"fb_postslist.php?" . EW_TABLE_SHOW_MASTER . "=fb_grupos&super_id=" . urlencode(strval($this->super_id->CurrentValue)) . "\"><i class='icon-list-alt icon-white'></i> " . $oListOpt->Body . "</a>";
			$links = "";
			if ($GLOBALS["fb_posts_grid"]->DetailEdit && $Security->IsLoggedIn() && $Security->IsLoggedIn())
				$links .= "<a class=\"ewRowLink label label-warning \" href=\"" . $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=fb_posts") . "\"><i class='icon-list-alt icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
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
		$this->idfb_grupos->setDbValue($rs->fields('idfb_grupos'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->pais->setDbValue($rs->fields('pais'));
		$this->url->setDbValue($rs->fields('url'));
		$this->super_id->setDbValue($rs->fields('super_id'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idfb_grupos")) <> "")
			$this->idfb_grupos->CurrentValue = $this->getKey("idfb_grupos"); // idfb_grupos
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
			if ($sMasterTblVar == "paises") {
				$bValidMaster = TRUE;
				if (@$_GET["idpaises"] <> "") {
					$GLOBALS["paises"]->idpaises->setQueryStringValue($_GET["idpaises"]);
					$this->pais->setQueryStringValue($GLOBALS["paises"]->idpaises->QueryStringValue);
					$this->pais->setSessionValue($this->pais->QueryStringValue);
					if (!is_numeric($GLOBALS["paises"]->idpaises->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "paises") {
				if ($this->pais->QueryStringValue == "") $this->pais->setSessionValue("");
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
		//$header = '<a class="ewGridLink btn btn-success" href="fb_posts-add">Refresh</a>';

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
		//$opt->Header = "Refresh";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

		// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = '<a class="ewGridLink btn btn-success" href="fb_posts-add">Refresh</a>';

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($fb_grupos_list)) $fb_grupos_list = new cfb_grupos_list();

// Page init
$fb_grupos_list->Page_Init();

// Page main
$fb_grupos_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_grupos_list = new ew_Page("fb_grupos_list");
fb_grupos_list.PageID = "list"; // Page ID
var EW_PAGE_ID = fb_grupos_list.PageID; // For backward compatibility

// Form object
var ffb_gruposlist = new ew_Form("ffb_gruposlist");

// Form_CustomValidate event
ffb_gruposlist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_gruposlist.ValidateRequired = true;
<?php } else { ?>
ffb_gruposlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffb_gruposlist.Lists["x_pais"] = {"LinkField":"x_idpaises","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_admin","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var ffb_gruposlistsrch = new ew_Form("ffb_gruposlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (($fb_grupos->Export == "") || (EW_EXPORT_MASTER_RECORD && $fb_grupos->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "paiseslist.php";
if ($fb_grupos_list->DbMasterFilter <> "" && $fb_grupos->getCurrentMasterTable() == "paises") {
	if ($fb_grupos_list->MasterRecordExists) {
		if ($fb_grupos->getCurrentMasterTable() == $fb_grupos->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<span class="ewTitle ewMasterTableTitle"><i class="icon-resize-small"></i> <?php echo $Language->Phrase("MasterRecord") ?><?php echo $paises->TableCaption() ?>&nbsp;&nbsp;</span>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar2<span class="caret"></span></button><ul class="dropdown-menu">';
$fb_grupos_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';*/
?>
<a href="<?php echo $gsMasterReturnUrl ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("BackToMasterRecordPage") ?></a>
<?php include_once "paisesmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$fb_grupos_list->TotalRecs = $fb_grupos->SelectRecordCount();
	} else {
		if ($fb_grupos_list->Recordset = $fb_grupos_list->LoadRecordset())
			$fb_grupos_list->TotalRecs = $fb_grupos_list->Recordset->RecordCount();
	}
	$fb_grupos_list->StartRec = 1;
	if ($fb_grupos_list->DisplayRecs <= 0 || ($fb_grupos->Export <> "" && $fb_grupos->ExportAll)) // Display all records
		$fb_grupos_list->DisplayRecs = $fb_grupos_list->TotalRecs;
	if (!($fb_grupos->Export <> "" && $fb_grupos->ExportAll))
		$fb_grupos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$fb_grupos_list->Recordset = $fb_grupos_list->LoadRecordset($fb_grupos_list->StartRec-1, $fb_grupos_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_grupos->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php if ($fb_grupos->getCurrentMasterTable() == "") {  ?>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar1<span class="caret"></span></button><ul class="dropdown-menu">';
$fb_grupos_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
<?php } ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($fb_grupos->Export == "" && $fb_grupos->CurrentAction == "") { ?>
<div class="accordion" id="accordion2">
<div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        <?php echo $Language->Phrase("Search") ?>
      </a>
    </div>
<div id="collapseOne" class="accordion-body collapse">
<div class="accordion-inner">
<form onsubmit="return ewForms[this.id].Submit();" name="ffb_gruposlistsrch" id="ffb_gruposlistsrch" class="ewForm navbar-form pull-left" action="<?php echo ew_CurrentPage() ?>">
<!--
<a href="javascript:ffb_gruposlistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="ffb_gruposlistsrch_SearchImage" src="http://cdn.registrodemascotas.co.cr/collapse.gif" alt="" width="9" height="9" style="border: 0;" /></a><span class="phpmaker"><?php echo $Language->Phrase("Search") ?></span><br />
-->
<div id="ffb_gruposlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search" />
<input type="hidden" name="t" value="fb_grupos" />
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>"  class="span2"  value="<?php echo ew_HtmlEncode($fb_grupos_list->BasicSearch->getKeyword()) ?>" />
	<input type="submit" class="btn" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>" />&nbsp;
	<a class="btn btn-warning" href="<?php echo $fb_grupos_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($fb_grupos_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($fb_grupos_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($fb_grupos_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $fb_grupos_list->ShowPageHeader(); ?>
<?php
$fb_grupos_list->ShowMessage();
?>
<div class="ewGridUpperPanel">
<?php if ($fb_grupos->CurrentAction <> "gridadd" && $fb_grupos->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($fb_grupos_list->Pager)) $fb_grupos_list->Pager = new cNumericPager($fb_grupos_list->StartRec, $fb_grupos_list->DisplayRecs, $fb_grupos_list->TotalRecs, $fb_grupos_list->RecRange) ?>
<?php if ($fb_grupos_list->Pager->RecordCount > 0) { ?>
	<ul><?php if ($fb_grupos_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $fb_grupos_list->PageUrl() ?>start=<?php echo $fb_grupos_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_grupos_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $fb_grupos_list->PageUrl() ?>start=<?php echo $fb_grupos_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($fb_grupos_list->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $fb_grupos_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($fb_grupos_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $fb_grupos_list->PageUrl() ?>start=<?php echo $fb_grupos_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_grupos_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $fb_grupos_list->PageUrl() ?>start=<?php echo $fb_grupos_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
	<?php if ($fb_grupos_list->Pager->ButtonCount > 0) { ?><?php } ?>
	<div style=" margin-top: 1px; " class="pull-right"><span class="label label-info"><i class="icon-ok-sign icon-white"></i> <?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $fb_grupos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $fb_grupos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $fb_grupos_list->Pager->RecordCount ?></span></div>
<?php } else { ?>	
	<?php if ($fb_grupos_list->SearchWhere == "0=101") { ?>
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
if ( count( $fb_grupos_list->ExportOptions->Items) > 0 ) {
	if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
	$fb_grupos_list->ExportOptions->Render("body"); 
	if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '</ul></div> ';
}
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($fb_grupos_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $fb_grupos_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php if ($fb_posts_grid->DetailAdd && $Security->IsLoggedIn()) { ?>
<a class="ewGridLink  btn btn-success" href="<?php echo $fb_grupos->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=fb_posts" ?>"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $fb_grupos->TableCaption() ?>/<?php echo $fb_posts->TableCaption() ?></a>
<?php } ?>
<?php } ?>
</div>
<form name="ffb_gruposlist" id="ffb_gruposlist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="fb_grupos" />
<div id="gmp_fb_grupos" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($fb_grupos_list->TotalRecs > 0) { ?>
<table id="tbl_fb_gruposlist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $fb_grupos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$fb_grupos_list->RenderListOptions();

// Render list options (header, left)
$fb_grupos_list->ListOptions->Render("header", "left");
?>
<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
	<?php if ($fb_grupos->SortUrl($fb_grupos->nombre) == "") { ?>
		<th><span id="elh_fb_grupos_nombre" class="fb_grupos_nombre">
		<div class="ewTableHeaderBtn"><?php echo $fb_grupos->nombre->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_grupos->SortUrl($fb_grupos->nombre) ?>',1);"><span id="elh_fb_grupos_nombre" class="fb_grupos_nombre">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_grupos->nombre->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_grupos->nombre->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_grupos->nombre->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_grupos->pais->Visible) { // pais ?>
	<?php if ($fb_grupos->SortUrl($fb_grupos->pais) == "") { ?>
		<th><span id="elh_fb_grupos_pais" class="fb_grupos_pais">
		<div class="ewTableHeaderBtn"><?php echo $fb_grupos->pais->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_grupos->SortUrl($fb_grupos->pais) ?>',1);"><span id="elh_fb_grupos_pais" class="fb_grupos_pais">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_grupos->pais->FldCaption() ?>
			<?php if ($fb_grupos->pais->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_grupos->pais->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_grupos->url->Visible) { // url ?>
	<?php if ($fb_grupos->SortUrl($fb_grupos->url) == "") { ?>
		<th><span id="elh_fb_grupos_url" class="fb_grupos_url">
		<div class="ewTableHeaderBtn"><?php echo $fb_grupos->url->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_grupos->SortUrl($fb_grupos->url) ?>',1);"><span id="elh_fb_grupos_url" class="fb_grupos_url">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_grupos->url->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_grupos->url->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_grupos->url->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$fb_grupos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($fb_grupos->ExportAll && $fb_grupos->Export <> "") {
	$fb_grupos_list->StopRec = $fb_grupos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($fb_grupos_list->TotalRecs > $fb_grupos_list->StartRec + $fb_grupos_list->DisplayRecs - 1)
		$fb_grupos_list->StopRec = $fb_grupos_list->StartRec + $fb_grupos_list->DisplayRecs - 1;
	else
		$fb_grupos_list->StopRec = $fb_grupos_list->TotalRecs;
}
$fb_grupos_list->RecCnt = $fb_grupos_list->StartRec - 1;
if ($fb_grupos_list->Recordset && !$fb_grupos_list->Recordset->EOF) {
	$fb_grupos_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $fb_grupos_list->StartRec > 1)
		$fb_grupos_list->Recordset->Move($fb_grupos_list->StartRec - 1);
} elseif (!$fb_grupos->AllowAddDeleteRow && $fb_grupos_list->StopRec == 0) {
	$fb_grupos_list->StopRec = $fb_grupos->GridAddRowCount;
}

// Initialize aggregate
$fb_grupos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$fb_grupos->ResetAttrs();
$fb_grupos_list->RenderRow();
while ($fb_grupos_list->RecCnt < $fb_grupos_list->StopRec) {
	$fb_grupos_list->RecCnt++;
	if (intval($fb_grupos_list->RecCnt) >= intval($fb_grupos_list->StartRec)) {
		$fb_grupos_list->RowCnt++;

		// Set up key count
		$fb_grupos_list->KeyCount = $fb_grupos_list->RowIndex;

		// Init row class and style
		$fb_grupos->ResetAttrs();
		$fb_grupos->CssClass = "";
		if ($fb_grupos->CurrentAction == "gridadd") {
		} else {
			$fb_grupos_list->LoadRowValues($fb_grupos_list->Recordset); // Load row values
		}
		$fb_grupos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$fb_grupos->RowAttrs = array_merge($fb_grupos->RowAttrs, array('data-rowindex'=>$fb_grupos_list->RowCnt, 'id'=>'r' . $fb_grupos_list->RowCnt . '_fb_grupos', 'data-rowtype'=>$fb_grupos->RowType));

		// Render row
		$fb_grupos_list->RenderRow();

		// Render list options
		$fb_grupos_list->RenderListOptions();
?>
	<tr<?php echo $fb_grupos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fb_grupos_list->ListOptions->Render("body", "left", $fb_grupos_list->RowCnt);
?>
	<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
		<td<?php echo $fb_grupos->nombre->CellAttributes() ?>><span id="el<?php echo $fb_grupos_list->RowCnt ?>_fb_grupos_nombre" class="fb_grupos_nombre">
<span<?php echo $fb_grupos->nombre->ViewAttributes() ?>>
<?php echo $fb_grupos->nombre->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $fb_grupos_list->PageObjName . "_row_" . $fb_grupos_list->RowCnt ?>"></a>
	<?php if ($fb_grupos->pais->Visible) { // pais ?>
		<td<?php echo $fb_grupos->pais->CellAttributes() ?>><span id="el<?php echo $fb_grupos_list->RowCnt ?>_fb_grupos_pais" class="fb_grupos_pais">
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_grupos->url->Visible) { // url ?>
		<td<?php echo $fb_grupos->url->CellAttributes() ?>><span id="el<?php echo $fb_grupos_list->RowCnt ?>_fb_grupos_url" class="fb_grupos_url">
<span<?php echo $fb_grupos->url->ViewAttributes() ?>>
<?php echo $fb_grupos->url->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fb_grupos_list->ListOptions->Render("body", "right", $fb_grupos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($fb_grupos->CurrentAction <> "gridadd")
		$fb_grupos_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($fb_grupos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($fb_grupos_list->Recordset)
	$fb_grupos_list->Recordset->Close();
?>
<script type="text/javascript">
ffb_gruposlistsrch.Init();
ffb_gruposlist.Init();
</script>
<?php
$fb_grupos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_grupos_list->Page_Terminate();
?>
