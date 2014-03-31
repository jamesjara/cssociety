<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
define("EW_DEFAULT_LOCALE", "Es_es", TRUE);
@setlocale(LC_ALL, EW_DEFAULT_LOCALE);
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "ownersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$owners_add = NULL; // Initialize page object first

class cowners_add extends cowners {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'owners';

	// Page object name
	var $PageObjName = 'owners_add';

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

		// Table object (owners)
		if (!isset($GLOBALS["owners"])) {
			$GLOBALS["owners"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["owners"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'owners', TRUE);

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_owners"] != "") {
				$this->id_owners->setQueryStringValue($_GET["id_owners"]);
				$this->setKey("id_owners", $this->id_owners->CurrentValue); // Set up key
			} else {
				$this->setKey("id_owners", ""); // Clear key
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
					$this->Page_Terminate($this->james_url( "ownerslist.php" )); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "ownersview.php")
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
		$this->Correo_Electronico->CurrentValue = NULL;
		$this->Correo_Electronico->OldValue = $this->Correo_Electronico->CurrentValue;
		$this->Password->CurrentValue = NULL;
		$this->Password->OldValue = $this->Password->CurrentValue;
		$this->activated->CurrentValue = NULL;
		$this->activated->OldValue = $this->activated->CurrentValue;
		$this->profile->CurrentValue = NULL;
		$this->profile->OldValue = $this->profile->CurrentValue;
		$this->role->CurrentValue = NULL;
		$this->role->OldValue = $this->role->CurrentValue;
		$this->tipo->CurrentValue = NULL;
		$this->tipo->OldValue = $this->tipo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Correo_Electronico->FldIsDetailKey) {
			$this->Correo_Electronico->setFormValue($objForm->GetValue("x_Correo_Electronico"));
		}
		if (!$this->Password->FldIsDetailKey) {
			$this->Password->setFormValue($objForm->GetValue("x_Password"));
		}
		if (!$this->activated->FldIsDetailKey) {
			$this->activated->setFormValue($objForm->GetValue("x_activated"));
		}
		if (!$this->profile->FldIsDetailKey) {
			$this->profile->setFormValue($objForm->GetValue("x_profile"));
		}
		if (!$this->role->FldIsDetailKey) {
			$this->role->setFormValue($objForm->GetValue("x_role"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Correo_Electronico->CurrentValue = $this->Correo_Electronico->FormValue;
		$this->Password->CurrentValue = $this->Password->FormValue;
		$this->activated->CurrentValue = $this->activated->FormValue;
		$this->profile->CurrentValue = $this->profile->FormValue;
		$this->role->CurrentValue = $this->role->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
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
		$this->id_owners->setDbValue($rs->fields('id_owners'));
		$this->Correo_Electronico->setDbValue($rs->fields('Correo Electronico'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->activated->setDbValue($rs->fields('activated'));
		$this->profile->setDbValue($rs->fields('profile'));
		$this->role->setDbValue($rs->fields('role'));
		$this->tipo->setDbValue($rs->fields('tipo'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_owners")) <> "")
			$this->id_owners->CurrentValue = $this->getKey("id_owners"); // id_owners
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
		// id_owners
		// Correo Electronico
		// Password
		// activated
		// profile
		// role
		// tipo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_owners
			$this->id_owners->ViewValue = $this->id_owners->CurrentValue;
			$this->id_owners->ViewCustomAttributes = "";

			// Correo Electronico
			$this->Correo_Electronico->ViewValue = $this->Correo_Electronico->CurrentValue;
			$this->Correo_Electronico->ViewCustomAttributes = "";

			// Password
			$this->Password->ViewValue = $this->Password->CurrentValue;
			$this->Password->ViewCustomAttributes = "";

			// activated
			$this->activated->ViewValue = $this->activated->CurrentValue;
			$this->activated->ViewCustomAttributes = "";

			// profile
			$this->profile->ViewValue = $this->profile->CurrentValue;
			$this->profile->ViewCustomAttributes = "";

			// role
			$this->role->ViewValue = $this->role->CurrentValue;
			$this->role->ViewCustomAttributes = "";

			// tipo
			$this->tipo->ViewValue = $this->tipo->CurrentValue;
			$this->tipo->ViewCustomAttributes = "";

			// Correo Electronico
			$this->Correo_Electronico->LinkCustomAttributes = "";
			$this->Correo_Electronico->HrefValue = "";
			$this->Correo_Electronico->TooltipValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";
			$this->Password->TooltipValue = "";

			// activated
			$this->activated->LinkCustomAttributes = "";
			$this->activated->HrefValue = "";
			$this->activated->TooltipValue = "";

			// profile
			$this->profile->LinkCustomAttributes = "";
			$this->profile->HrefValue = "";
			$this->profile->TooltipValue = "";

			// role
			$this->role->LinkCustomAttributes = "";
			$this->role->HrefValue = "";
			$this->role->TooltipValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Correo Electronico
			$this->Correo_Electronico->EditCustomAttributes = "";
			$this->Correo_Electronico->EditValue = ew_HtmlEncode($this->Correo_Electronico->CurrentValue);

			// Password
			$this->Password->EditCustomAttributes = "";
			$this->Password->EditValue = ew_HtmlEncode($this->Password->CurrentValue);

			// activated
			$this->activated->EditCustomAttributes = "";
			$this->activated->EditValue = ew_HtmlEncode($this->activated->CurrentValue);

			// profile
			$this->profile->EditCustomAttributes = "";
			$this->profile->EditValue = ew_HtmlEncode($this->profile->CurrentValue);

			// role
			$this->role->EditCustomAttributes = "";
			$this->role->EditValue = ew_HtmlEncode($this->role->CurrentValue);

			// tipo
			$this->tipo->EditCustomAttributes = "";
			$this->tipo->EditValue = ew_HtmlEncode($this->tipo->CurrentValue);

			// Edit refer script
			// Correo Electronico

			$this->Correo_Electronico->HrefValue = "";

			// Password
			$this->Password->HrefValue = "";

			// activated
			$this->activated->HrefValue = "";

			// profile
			$this->profile->HrefValue = "";

			// role
			$this->role->HrefValue = "";

			// tipo
			$this->tipo->HrefValue = "";
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
		if (!is_null($this->Correo_Electronico->FormValue) && $this->Correo_Electronico->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Correo_Electronico->FldCaption());
		}
		if (!is_null($this->Password->FormValue) && $this->Password->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->Password->FldCaption());
		}
		if (!ew_CheckInteger($this->role->FormValue)) {
			ew_AddMessage($gsFormError, $this->role->FldErrMsg());
		}
		if (!ew_CheckInteger($this->tipo->FormValue)) {
			ew_AddMessage($gsFormError, $this->tipo->FldErrMsg());
		}

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
		$rsnew = array();

		// Correo Electronico
		$this->Correo_Electronico->SetDbValueDef($rsnew, $this->Correo_Electronico->CurrentValue, "", FALSE);

		// Password
		$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, "", FALSE);

		// activated
		$this->activated->SetDbValueDef($rsnew, $this->activated->CurrentValue, NULL, FALSE);

		// profile
		$this->profile->SetDbValueDef($rsnew, $this->profile->CurrentValue, NULL, FALSE);

		// role
		$this->role->SetDbValueDef($rsnew, $this->role->CurrentValue, NULL, FALSE);

		// tipo
		$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, NULL, FALSE);

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
			$this->id_owners->setDbValue($conn->Insert_ID());
			$rsnew['id_owners'] = $this->id_owners->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
if (!isset($owners_add)) $owners_add = new cowners_add();

// Page init
$owners_add->Page_Init();

// Page main
$owners_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var owners_add = new ew_Page("owners_add");
owners_add.PageID = "add"; // Page ID
var EW_PAGE_ID = owners_add.PageID; // For backward compatibility

// Form object
var fownersadd = new ew_Form("fownersadd");

// Validate form
fownersadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_Correo_Electronico"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($owners->Correo_Electronico->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_Password"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($owners->Password->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_role"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($owners->role->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_tipo"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($owners->tipo->FldErrMsg()) ?>");

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
fownersadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fownersadd.ValidateRequired = true;
<?php } else { ?>
fownersadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $owners->TableCaption() ?></h4>
<a href="<?php echo $owners->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $owners_add->ShowPageHeader(); ?>
<?php
$owners_add->ShowMessage();
?>
<form name="fownersadd" id="fownersadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="owners" />
<input type="hidden" name="a_add" id="a_add" value="A" />
<table id="tbl_ownersadd" class="ewTable ewTableSeparate table table-striped ">
<?php if ($owners->Correo_Electronico->Visible) { // Correo Electronico ?>
	<tr id="r_Correo_Electronico"<?php echo $owners->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_owners_Correo_Electronico">
		<b><?php echo $owners->Correo_Electronico->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></b>
		</span></td>
		<td<?php echo $owners->Correo_Electronico->CellAttributes() ?>><span id="el_owners_Correo_Electronico">
<input type="text" name="x_Correo_Electronico" id="x_Correo_Electronico" size="30" maxlength="245" value="<?php echo $owners->Correo_Electronico->EditValue ?>"<?php echo $owners->Correo_Electronico->EditAttributes() ?> />
</span><?php echo $owners->Correo_Electronico->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($owners->Password->Visible) { // Password ?>
	<tr id="r_Password"<?php echo $owners->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_owners_Password">
		<b><?php echo $owners->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></b>
		</span></td>
		<td<?php echo $owners->Password->CellAttributes() ?>><span id="el_owners_Password">
<input type="text" name="x_Password" id="x_Password" size="30" maxlength="45" value="<?php echo $owners->Password->EditValue ?>"<?php echo $owners->Password->EditAttributes() ?> />
</span><?php echo $owners->Password->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($owners->activated->Visible) { // activated ?>
	<tr id="r_activated"<?php echo $owners->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_owners_activated">
		<b><?php echo $owners->activated->FldCaption() ?></b>
		</span></td>
		<td<?php echo $owners->activated->CellAttributes() ?>><span id="el_owners_activated">
<input type="text" name="x_activated" id="x_activated" size="30" maxlength="245" value="<?php echo $owners->activated->EditValue ?>"<?php echo $owners->activated->EditAttributes() ?> />
</span><?php echo $owners->activated->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($owners->profile->Visible) { // profile ?>
	<tr id="r_profile"<?php echo $owners->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_owners_profile">
		<b><?php echo $owners->profile->FldCaption() ?></b>
		</span></td>
		<td<?php echo $owners->profile->CellAttributes() ?>><span id="el_owners_profile">
<textarea name="x_profile" id="x_profile" cols="35" rows="4"<?php echo $owners->profile->EditAttributes() ?>><?php echo $owners->profile->EditValue ?></textarea>
</span><?php echo $owners->profile->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($owners->role->Visible) { // role ?>
	<tr id="r_role"<?php echo $owners->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_owners_role">
		<b><?php echo $owners->role->FldCaption() ?></b>
		</span></td>
		<td<?php echo $owners->role->CellAttributes() ?>><span id="el_owners_role">
<input type="text" name="x_role" id="x_role" size="30" value="<?php echo $owners->role->EditValue ?>"<?php echo $owners->role->EditAttributes() ?> />
</span><?php echo $owners->role->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($owners->tipo->Visible) { // tipo ?>
	<tr id="r_tipo"<?php echo $owners->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_owners_tipo">
		<b><?php echo $owners->tipo->FldCaption() ?></b>
		</span></td>
		<td<?php echo $owners->tipo->CellAttributes() ?>><span id="el_owners_tipo">
<input type="text" name="x_tipo" id="x_tipo" size="30" value="<?php echo $owners->tipo->EditValue ?>"<?php echo $owners->tipo->EditAttributes() ?> />
</span><?php echo $owners->tipo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit"  class="btn btn-large btn-success"  name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>" />
</form>
<script type="text/javascript">
fownersadd.Init();
</script>
<?php
$owners_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$owners_add->Page_Terminate();
?>
