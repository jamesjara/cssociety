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

$fb_posts_add = NULL; // Initialize page object first

class cfb_posts_add extends cfb_posts {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'fb_posts';

	// Page object name
	var $PageObjName = 'fb_posts_add';

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

		// Table object (fb_grupos)
		if (!isset($GLOBALS['fb_grupos'])) $GLOBALS['fb_grupos'] = new cfb_grupos();

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_posts', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["idfb_posts"] != "") {
				$this->idfb_posts->setQueryStringValue($_GET["idfb_posts"]);
				$this->setKey("idfb_posts", $this->idfb_posts->CurrentValue); // Set up key
			} else {
				$this->setKey("idfb_posts", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate($this->james_url( "fb_postslist.php" )); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "fb_postsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->created_time->CurrentValue = NULL;
		$this->created_time->OldValue = $this->created_time->CurrentValue;
		$this->actions->CurrentValue = NULL;
		$this->actions->OldValue = $this->actions->CurrentValue;
		$this->icon->CurrentValue = NULL;
		$this->icon->OldValue = $this->icon->CurrentValue;
		$this->is_published->CurrentValue = NULL;
		$this->is_published->OldValue = $this->is_published->CurrentValue;
		$this->message->CurrentValue = NULL;
		$this->message->OldValue = $this->message->CurrentValue;
		$this->link->CurrentValue = NULL;
		$this->link->OldValue = $this->link->CurrentValue;
		$this->object_id->CurrentValue = NULL;
		$this->object_id->OldValue = $this->object_id->CurrentValue;
		$this->picture->CurrentValue = NULL;
		$this->picture->OldValue = $this->picture->CurrentValue;
		$this->privacy->CurrentValue = NULL;
		$this->privacy->OldValue = $this->privacy->CurrentValue;
		$this->promotion_status->CurrentValue = NULL;
		$this->promotion_status->OldValue = $this->promotion_status->CurrentValue;
		$this->timeline_visibility->CurrentValue = NULL;
		$this->timeline_visibility->OldValue = $this->timeline_visibility->CurrentValue;
		$this->type->CurrentValue = NULL;
		$this->type->OldValue = $this->type->CurrentValue;
		$this->updated_time->CurrentValue = NULL;
		$this->updated_time->OldValue = $this->updated_time->CurrentValue;
		$this->caption->CurrentValue = NULL;
		$this->caption->OldValue = $this->caption->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->source->CurrentValue = NULL;
		$this->source->OldValue = $this->source->CurrentValue;
		$this->from->CurrentValue = NULL;
		$this->from->OldValue = $this->from->CurrentValue;
		$this->to->CurrentValue = NULL;
		$this->to->OldValue = $this->to->CurrentValue;
		$this->comments->CurrentValue = NULL;
		$this->comments->OldValue = $this->comments->CurrentValue;
		$this->id_grupo->CurrentValue = NULL;
		$this->id_grupo->OldValue = $this->id_grupo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->created_time->FldIsDetailKey) {
			$this->created_time->setFormValue($objForm->GetValue("x_created_time"));
		}
		if (!$this->actions->FldIsDetailKey) {
			$this->actions->setFormValue($objForm->GetValue("x_actions"));
		}
		if (!$this->icon->FldIsDetailKey) {
			$this->icon->setFormValue($objForm->GetValue("x_icon"));
		}
		if (!$this->is_published->FldIsDetailKey) {
			$this->is_published->setFormValue($objForm->GetValue("x_is_published"));
		}
		if (!$this->message->FldIsDetailKey) {
			$this->message->setFormValue($objForm->GetValue("x_message"));
		}
		if (!$this->link->FldIsDetailKey) {
			$this->link->setFormValue($objForm->GetValue("x_link"));
		}
		if (!$this->object_id->FldIsDetailKey) {
			$this->object_id->setFormValue($objForm->GetValue("x_object_id"));
		}
		if (!$this->picture->FldIsDetailKey) {
			$this->picture->setFormValue($objForm->GetValue("x_picture"));
		}
		if (!$this->privacy->FldIsDetailKey) {
			$this->privacy->setFormValue($objForm->GetValue("x_privacy"));
		}
		if (!$this->promotion_status->FldIsDetailKey) {
			$this->promotion_status->setFormValue($objForm->GetValue("x_promotion_status"));
		}
		if (!$this->timeline_visibility->FldIsDetailKey) {
			$this->timeline_visibility->setFormValue($objForm->GetValue("x_timeline_visibility"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->updated_time->FldIsDetailKey) {
			$this->updated_time->setFormValue($objForm->GetValue("x_updated_time"));
		}
		if (!$this->caption->FldIsDetailKey) {
			$this->caption->setFormValue($objForm->GetValue("x_caption"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->source->FldIsDetailKey) {
			$this->source->setFormValue($objForm->GetValue("x_source"));
		}
		if (!$this->from->FldIsDetailKey) {
			$this->from->setFormValue($objForm->GetValue("x_from"));
		}
		if (!$this->to->FldIsDetailKey) {
			$this->to->setFormValue($objForm->GetValue("x_to"));
		}
		if (!$this->comments->FldIsDetailKey) {
			$this->comments->setFormValue($objForm->GetValue("x_comments"));
		}
		if (!$this->id_grupo->FldIsDetailKey) {
			$this->id_grupo->setFormValue($objForm->GetValue("x_id_grupo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->created_time->CurrentValue = $this->created_time->FormValue;
		$this->actions->CurrentValue = $this->actions->FormValue;
		$this->icon->CurrentValue = $this->icon->FormValue;
		$this->is_published->CurrentValue = $this->is_published->FormValue;
		$this->message->CurrentValue = $this->message->FormValue;
		$this->link->CurrentValue = $this->link->FormValue;
		$this->object_id->CurrentValue = $this->object_id->FormValue;
		$this->picture->CurrentValue = $this->picture->FormValue;
		$this->privacy->CurrentValue = $this->privacy->FormValue;
		$this->promotion_status->CurrentValue = $this->promotion_status->FormValue;
		$this->timeline_visibility->CurrentValue = $this->timeline_visibility->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->updated_time->CurrentValue = $this->updated_time->FormValue;
		$this->caption->CurrentValue = $this->caption->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->source->CurrentValue = $this->source->FormValue;
		$this->from->CurrentValue = $this->from->FormValue;
		$this->to->CurrentValue = $this->to->FormValue;
		$this->comments->CurrentValue = $this->comments->FormValue;
		$this->id_grupo->CurrentValue = $this->id_grupo->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);

			// created_time
			$this->created_time->EditCustomAttributes = "";
			$this->created_time->EditValue = ew_HtmlEncode($this->created_time->CurrentValue);

			// actions
			$this->actions->EditCustomAttributes = "";
			$this->actions->EditValue = ew_HtmlEncode($this->actions->CurrentValue);

			// icon
			$this->icon->EditCustomAttributes = "";
			$this->icon->EditValue = ew_HtmlEncode($this->icon->CurrentValue);

			// is_published
			$this->is_published->EditCustomAttributes = "";
			$this->is_published->EditValue = ew_HtmlEncode($this->is_published->CurrentValue);

			// message
			$this->message->EditCustomAttributes = "";
			$this->message->EditValue = ew_HtmlEncode($this->message->CurrentValue);

			// link
			$this->link->EditCustomAttributes = "";
			$this->link->EditValue = ew_HtmlEncode($this->link->CurrentValue);

			// object_id
			$this->object_id->EditCustomAttributes = "";
			$this->object_id->EditValue = ew_HtmlEncode($this->object_id->CurrentValue);

			// picture
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = ew_HtmlEncode($this->picture->CurrentValue);

			// privacy
			$this->privacy->EditCustomAttributes = "";
			$this->privacy->EditValue = ew_HtmlEncode($this->privacy->CurrentValue);

			// promotion_status
			$this->promotion_status->EditCustomAttributes = "";
			$this->promotion_status->EditValue = ew_HtmlEncode($this->promotion_status->CurrentValue);

			// timeline_visibility
			$this->timeline_visibility->EditCustomAttributes = "";
			$this->timeline_visibility->EditValue = ew_HtmlEncode($this->timeline_visibility->CurrentValue);

			// type
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);

			// updated_time
			$this->updated_time->EditCustomAttributes = "";
			$this->updated_time->EditValue = ew_HtmlEncode($this->updated_time->CurrentValue);

			// caption
			$this->caption->EditCustomAttributes = "";
			$this->caption->EditValue = ew_HtmlEncode($this->caption->CurrentValue);

			// description
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);

			// source
			$this->source->EditCustomAttributes = "";
			$this->source->EditValue = ew_HtmlEncode($this->source->CurrentValue);

			// from
			$this->from->EditCustomAttributes = "";
			$this->from->EditValue = ew_HtmlEncode($this->from->CurrentValue);

			// to
			$this->to->EditCustomAttributes = "";
			$this->to->EditValue = ew_HtmlEncode($this->to->CurrentValue);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);

			// id_grupo
			$this->id_grupo->EditCustomAttributes = "";
			if ($this->id_grupo->getSessionValue() <> "") {
				$this->id_grupo->CurrentValue = $this->id_grupo->getSessionValue();
			$this->id_grupo->ViewValue = $this->id_grupo->CurrentValue;
			$this->id_grupo->ViewCustomAttributes = "";
			} else {
			$this->id_grupo->EditValue = ew_HtmlEncode($this->id_grupo->CurrentValue);
			}

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// created_time
			$this->created_time->HrefValue = "";

			// actions
			$this->actions->HrefValue = "";

			// icon
			$this->icon->HrefValue = "";

			// is_published
			$this->is_published->HrefValue = "";

			// message
			$this->message->HrefValue = "";

			// link
			$this->link->HrefValue = "";

			// object_id
			$this->object_id->HrefValue = "";

			// picture
			$this->picture->HrefValue = "";

			// privacy
			$this->privacy->HrefValue = "";

			// promotion_status
			$this->promotion_status->HrefValue = "";

			// timeline_visibility
			$this->timeline_visibility->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// updated_time
			$this->updated_time->HrefValue = "";

			// caption
			$this->caption->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// source
			$this->source->HrefValue = "";

			// from
			$this->from->HrefValue = "";

			// to
			$this->to->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// id_grupo
			$this->id_grupo->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		if ($this->id->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(id = '" . ew_AdjustSql($this->id->CurrentValue) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->id->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->id->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$rsnew = array();

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, NULL, FALSE);

		// created_time
		$this->created_time->SetDbValueDef($rsnew, $this->created_time->CurrentValue, NULL, FALSE);

		// actions
		$this->actions->SetDbValueDef($rsnew, $this->actions->CurrentValue, NULL, FALSE);

		// icon
		$this->icon->SetDbValueDef($rsnew, $this->icon->CurrentValue, NULL, FALSE);

		// is_published
		$this->is_published->SetDbValueDef($rsnew, $this->is_published->CurrentValue, NULL, FALSE);

		// message
		$this->message->SetDbValueDef($rsnew, $this->message->CurrentValue, NULL, FALSE);

		// link
		$this->link->SetDbValueDef($rsnew, $this->link->CurrentValue, NULL, FALSE);

		// object_id
		$this->object_id->SetDbValueDef($rsnew, $this->object_id->CurrentValue, NULL, FALSE);

		// picture
		$this->picture->SetDbValueDef($rsnew, $this->picture->CurrentValue, NULL, FALSE);

		// privacy
		$this->privacy->SetDbValueDef($rsnew, $this->privacy->CurrentValue, NULL, FALSE);

		// promotion_status
		$this->promotion_status->SetDbValueDef($rsnew, $this->promotion_status->CurrentValue, NULL, FALSE);

		// timeline_visibility
		$this->timeline_visibility->SetDbValueDef($rsnew, $this->timeline_visibility->CurrentValue, NULL, FALSE);

		// type
		$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, FALSE);

		// updated_time
		$this->updated_time->SetDbValueDef($rsnew, $this->updated_time->CurrentValue, NULL, FALSE);

		// caption
		$this->caption->SetDbValueDef($rsnew, $this->caption->CurrentValue, NULL, FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// source
		$this->source->SetDbValueDef($rsnew, $this->source->CurrentValue, NULL, FALSE);

		// from
		$this->from->SetDbValueDef($rsnew, $this->from->CurrentValue, NULL, FALSE);

		// to
		$this->to->SetDbValueDef($rsnew, $this->to->CurrentValue, NULL, FALSE);

		// comments
		$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, FALSE);

		// id_grupo
		$this->id_grupo->SetDbValueDef($rsnew, $this->id_grupo->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->idfb_posts->setDbValue($conn->Insert_ID());
			$rsnew['idfb_posts'] = $this->idfb_posts->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($fb_posts_add)) $fb_posts_add = new cfb_posts_add();

// Page init
$fb_posts_add->Page_Init();

// Page main
$fb_posts_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_posts_add = new ew_Page("fb_posts_add");
fb_posts_add.PageID = "add"; // Page ID
var EW_PAGE_ID = fb_posts_add.PageID; // For backward compatibility

// Form object
var ffb_postsadd = new ew_Form("ffb_postsadd");

// Validate form
ffb_postsadd.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = "";

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}

	// Process detail page
	if (fobj.detailpage && fobj.detailpage.value && ewForms[fobj.detailpage.value])
		return ewForms[fobj.detailpage.value].Validate(fobj);
	return true;
}

// Form_CustomValidate event
ffb_postsadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_postsadd.ValidateRequired = true;
<?php } else { ?>
ffb_postsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_posts->TableCaption() ?></h4>
<a href="<?php echo $fb_posts->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $fb_posts_add->ShowPageHeader(); ?>
<?php
$fb_posts_add->ShowMessage();
?>
<form name="ffb_postsadd" id="ffb_postsadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="fb_posts" />
<input type="hidden" name="a_add" id="a_add" value="A" />
<table id="tbl_fb_postsadd" class="ewTable ewTableSeparate table table-striped ">
<?php if ($fb_posts->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_id">
		<b><?php echo $fb_posts->id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->id->CellAttributes() ?>><span id="el_fb_posts_id">
<input type="text" name="x_id" id="x_id" size="30" maxlength="245" value="<?php echo $fb_posts->id->EditValue ?>"<?php echo $fb_posts->id->EditAttributes() ?> />
</span><?php echo $fb_posts->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->created_time->Visible) { // created_time ?>
	<tr id="r_created_time"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_created_time">
		<b><?php echo $fb_posts->created_time->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->created_time->CellAttributes() ?>><span id="el_fb_posts_created_time">
<input type="text" name="x_created_time" id="x_created_time" size="30" maxlength="245" value="<?php echo $fb_posts->created_time->EditValue ?>"<?php echo $fb_posts->created_time->EditAttributes() ?> />
</span><?php echo $fb_posts->created_time->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->actions->Visible) { // actions ?>
	<tr id="r_actions"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_actions">
		<b><?php echo $fb_posts->actions->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->actions->CellAttributes() ?>><span id="el_fb_posts_actions">
<textarea name="x_actions" id="x_actions" cols="35" rows="4"<?php echo $fb_posts->actions->EditAttributes() ?>><?php echo $fb_posts->actions->EditValue ?></textarea>
</span><?php echo $fb_posts->actions->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->icon->Visible) { // icon ?>
	<tr id="r_icon"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_icon">
		<b><?php echo $fb_posts->icon->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->icon->CellAttributes() ?>><span id="el_fb_posts_icon">
<input type="text" name="x_icon" id="x_icon" size="30" maxlength="245" value="<?php echo $fb_posts->icon->EditValue ?>"<?php echo $fb_posts->icon->EditAttributes() ?> />
</span><?php echo $fb_posts->icon->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->is_published->Visible) { // is_published ?>
	<tr id="r_is_published"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_is_published">
		<b><?php echo $fb_posts->is_published->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->is_published->CellAttributes() ?>><span id="el_fb_posts_is_published">
<input type="text" name="x_is_published" id="x_is_published" size="30" maxlength="245" value="<?php echo $fb_posts->is_published->EditValue ?>"<?php echo $fb_posts->is_published->EditAttributes() ?> />
</span><?php echo $fb_posts->is_published->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->message->Visible) { // message ?>
	<tr id="r_message"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_message">
		<b><?php echo $fb_posts->message->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->message->CellAttributes() ?>><span id="el_fb_posts_message">
<textarea name="x_message" id="x_message" cols="35" rows="4"<?php echo $fb_posts->message->EditAttributes() ?>><?php echo $fb_posts->message->EditValue ?></textarea>
</span><?php echo $fb_posts->message->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->link->Visible) { // link ?>
	<tr id="r_link"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_link">
		<b><?php echo $fb_posts->link->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->link->CellAttributes() ?>><span id="el_fb_posts_link">
<input type="text" name="x_link" id="x_link" size="30" maxlength="245" value="<?php echo $fb_posts->link->EditValue ?>"<?php echo $fb_posts->link->EditAttributes() ?> />
</span><?php echo $fb_posts->link->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->object_id->Visible) { // object_id ?>
	<tr id="r_object_id"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_object_id">
		<b><?php echo $fb_posts->object_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->object_id->CellAttributes() ?>><span id="el_fb_posts_object_id">
<input type="text" name="x_object_id" id="x_object_id" size="30" maxlength="245" value="<?php echo $fb_posts->object_id->EditValue ?>"<?php echo $fb_posts->object_id->EditAttributes() ?> />
</span><?php echo $fb_posts->object_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->picture->Visible) { // picture ?>
	<tr id="r_picture"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_picture">
		<b><?php echo $fb_posts->picture->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->picture->CellAttributes() ?>><span id="el_fb_posts_picture">
<input type="text" name="x_picture" id="x_picture" size="30" maxlength="245" value="<?php echo $fb_posts->picture->EditValue ?>"<?php echo $fb_posts->picture->EditAttributes() ?> />
</span><?php echo $fb_posts->picture->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->privacy->Visible) { // privacy ?>
	<tr id="r_privacy"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_privacy">
		<b><?php echo $fb_posts->privacy->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->privacy->CellAttributes() ?>><span id="el_fb_posts_privacy">
<textarea name="x_privacy" id="x_privacy" cols="35" rows="4"<?php echo $fb_posts->privacy->EditAttributes() ?>><?php echo $fb_posts->privacy->EditValue ?></textarea>
</span><?php echo $fb_posts->privacy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->promotion_status->Visible) { // promotion_status ?>
	<tr id="r_promotion_status"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_promotion_status">
		<b><?php echo $fb_posts->promotion_status->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->promotion_status->CellAttributes() ?>><span id="el_fb_posts_promotion_status">
<input type="text" name="x_promotion_status" id="x_promotion_status" size="30" maxlength="245" value="<?php echo $fb_posts->promotion_status->EditValue ?>"<?php echo $fb_posts->promotion_status->EditAttributes() ?> />
</span><?php echo $fb_posts->promotion_status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->timeline_visibility->Visible) { // timeline_visibility ?>
	<tr id="r_timeline_visibility"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_timeline_visibility">
		<b><?php echo $fb_posts->timeline_visibility->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->timeline_visibility->CellAttributes() ?>><span id="el_fb_posts_timeline_visibility">
<input type="text" name="x_timeline_visibility" id="x_timeline_visibility" size="30" maxlength="245" value="<?php echo $fb_posts->timeline_visibility->EditValue ?>"<?php echo $fb_posts->timeline_visibility->EditAttributes() ?> />
</span><?php echo $fb_posts->timeline_visibility->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_type">
		<b><?php echo $fb_posts->type->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->type->CellAttributes() ?>><span id="el_fb_posts_type">
<input type="text" name="x_type" id="x_type" size="30" maxlength="245" value="<?php echo $fb_posts->type->EditValue ?>"<?php echo $fb_posts->type->EditAttributes() ?> />
</span><?php echo $fb_posts->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->updated_time->Visible) { // updated_time ?>
	<tr id="r_updated_time"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_updated_time">
		<b><?php echo $fb_posts->updated_time->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->updated_time->CellAttributes() ?>><span id="el_fb_posts_updated_time">
<input type="text" name="x_updated_time" id="x_updated_time" size="30" maxlength="245" value="<?php echo $fb_posts->updated_time->EditValue ?>"<?php echo $fb_posts->updated_time->EditAttributes() ?> />
</span><?php echo $fb_posts->updated_time->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->caption->Visible) { // caption ?>
	<tr id="r_caption"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_caption">
		<b><?php echo $fb_posts->caption->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->caption->CellAttributes() ?>><span id="el_fb_posts_caption">
<textarea name="x_caption" id="x_caption" cols="35" rows="4"<?php echo $fb_posts->caption->EditAttributes() ?>><?php echo $fb_posts->caption->EditValue ?></textarea>
</span><?php echo $fb_posts->caption->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->description->Visible) { // description ?>
	<tr id="r_description"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_description">
		<b><?php echo $fb_posts->description->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->description->CellAttributes() ?>><span id="el_fb_posts_description">
<textarea name="x_description" id="x_description" cols="35" rows="4"<?php echo $fb_posts->description->EditAttributes() ?>><?php echo $fb_posts->description->EditValue ?></textarea>
</span><?php echo $fb_posts->description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_name">
		<b><?php echo $fb_posts->name->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->name->CellAttributes() ?>><span id="el_fb_posts_name">
<textarea name="x_name" id="x_name" cols="35" rows="4"<?php echo $fb_posts->name->EditAttributes() ?>><?php echo $fb_posts->name->EditValue ?></textarea>
</span><?php echo $fb_posts->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->source->Visible) { // source ?>
	<tr id="r_source"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_source">
		<b><?php echo $fb_posts->source->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->source->CellAttributes() ?>><span id="el_fb_posts_source">
<textarea name="x_source" id="x_source" cols="35" rows="4"<?php echo $fb_posts->source->EditAttributes() ?>><?php echo $fb_posts->source->EditValue ?></textarea>
</span><?php echo $fb_posts->source->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->from->Visible) { // from ?>
	<tr id="r_from"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_from">
		<b><?php echo $fb_posts->from->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->from->CellAttributes() ?>><span id="el_fb_posts_from">
<textarea name="x_from" id="x_from" cols="35" rows="4"<?php echo $fb_posts->from->EditAttributes() ?>><?php echo $fb_posts->from->EditValue ?></textarea>
</span><?php echo $fb_posts->from->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->to->Visible) { // to ?>
	<tr id="r_to"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_to">
		<b><?php echo $fb_posts->to->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->to->CellAttributes() ?>><span id="el_fb_posts_to">
<textarea name="x_to" id="x_to" cols="35" rows="4"<?php echo $fb_posts->to->EditAttributes() ?>><?php echo $fb_posts->to->EditValue ?></textarea>
</span><?php echo $fb_posts->to->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->comments->Visible) { // comments ?>
	<tr id="r_comments"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_comments">
		<b><?php echo $fb_posts->comments->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->comments->CellAttributes() ?>><span id="el_fb_posts_comments">
<textarea name="x_comments" id="x_comments" cols="35" rows="4"<?php echo $fb_posts->comments->EditAttributes() ?>><?php echo $fb_posts->comments->EditValue ?></textarea>
</span><?php echo $fb_posts->comments->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_posts->id_grupo->Visible) { // id_grupo ?>
	<tr id="r_id_grupo"<?php echo $fb_posts->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_posts_id_grupo">
		<b><?php echo $fb_posts->id_grupo->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_posts->id_grupo->CellAttributes() ?>><span id="el_fb_posts_id_grupo">
<?php if ($fb_posts->id_grupo->getSessionValue() <> "") { ?>
<span<?php echo $fb_posts->id_grupo->ViewAttributes() ?>>
<?php echo $fb_posts->id_grupo->ViewValue ?></span>
<input type="hidden" id="x_id_grupo" name="x_id_grupo" value="<?php echo ew_HtmlEncode($fb_posts->id_grupo->CurrentValue) ?>">
<?php } else { ?>
<input type="text" name="x_id_grupo" id="x_id_grupo" size="30" maxlength="245" value="<?php echo $fb_posts->id_grupo->EditValue ?>"<?php echo $fb_posts->id_grupo->EditAttributes() ?> />
<?php } ?>
</span><?php echo $fb_posts->id_grupo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit"  class="btn btn-large btn-success"  name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>" />
</form>
<script type="text/javascript">
ffb_postsadd.Init();
</script>
<?php
$fb_posts_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_posts_add->Page_Terminate();
?>
