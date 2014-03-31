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

$blogs_add = NULL; // Initialize page object first

class cblogs_add extends cblogs {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'blogs';

	// Page object name
	var $PageObjName = 'blogs_add';

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

		// Table object (blogs)
		if (!isset($GLOBALS["blogs"])) {
			$GLOBALS["blogs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["blogs"];
		}

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'blogs', TRUE);

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
			if (@$_GET["idforos_fb"] != "") {
				$this->idforos_fb->setQueryStringValue($_GET["idforos_fb"]);
				$this->setKey("idforos_fb", $this->idforos_fb->CurrentValue); // Set up key
			} else {
				$this->setKey("idforos_fb", ""); // Clear key
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
					$this->Page_Terminate($this->james_url( "blogslist.php" )); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "blogsview.php")
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
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->pais->CurrentValue = NULL;
		$this->pais->OldValue = $this->pais->CurrentValue;
		$this->tipo->CurrentValue = NULL;
		$this->tipo->OldValue = $this->tipo->CurrentValue;
		$this->user->CurrentValue = NULL;
		$this->user->OldValue = $this->user->CurrentValue;
		$this->pass->CurrentValue = NULL;
		$this->pass->OldValue = $this->pass->CurrentValue;
		$this->owner->CurrentValue = NULL;
		$this->owner->OldValue = $this->owner->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->pais->FldIsDetailKey) {
			$this->pais->setFormValue($objForm->GetValue("x_pais"));
		}
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->user->FldIsDetailKey) {
			$this->user->setFormValue($objForm->GetValue("x_user"));
		}
		if (!$this->pass->FldIsDetailKey) {
			$this->pass->setFormValue($objForm->GetValue("x_pass"));
		}
		if (!$this->owner->FldIsDetailKey) {
			$this->owner->setFormValue($objForm->GetValue("x_owner"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->pais->CurrentValue = $this->pais->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
		$this->user->CurrentValue = $this->user->FormValue;
		$this->pass->CurrentValue = $this->pass->FormValue;
		$this->owner->CurrentValue = $this->owner->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nombre
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);

			// pais
			$this->pais->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `idpaises`, `nombre` AS `DispFld`, `admin` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `paises`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->pais->EditValue = $arwrk;

			// tipo
			$this->tipo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->tipo->FldTagValue(1), $this->tipo->FldTagCaption(1) <> "" ? $this->tipo->FldTagCaption(1) : $this->tipo->FldTagValue(1));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->tipo->EditValue = $arwrk;

			// user
			$this->user->EditCustomAttributes = "";
			$this->user->EditValue = ew_HtmlEncode($this->user->CurrentValue);

			// pass
			$this->pass->EditCustomAttributes = "";
			$this->pass->EditValue = ew_HtmlEncode($this->pass->CurrentValue);

			// owner
			// Edit refer script
			// nombre

			$this->nombre->HrefValue = "";

			// pais
			$this->pais->HrefValue = "";

			// tipo
			$this->tipo->HrefValue = "";

			// user
			$this->user->HrefValue = "";

			// pass
			$this->pass->HrefValue = "";

			// owner
			$this->owner->HrefValue = "";
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
		$rsnew = array();

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// pais
		$this->pais->SetDbValueDef($rsnew, $this->pais->CurrentValue, NULL, FALSE);

		// tipo
		$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, NULL, FALSE);

		// user
		$this->user->SetDbValueDef($rsnew, $this->user->CurrentValue, NULL, FALSE);

		// pass
		$this->pass->SetDbValueDef($rsnew, $this->pass->CurrentValue, NULL, FALSE);

		// owner
		$this->owner->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['owner'] = &$this->owner->DbValue;

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
			$this->idforos_fb->setDbValue($conn->Insert_ID());
			$rsnew['idforos_fb'] = $this->idforos_fb->DbValue;
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
if (!isset($blogs_add)) $blogs_add = new cblogs_add();

// Page init
$blogs_add->Page_Init();

// Page main
$blogs_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var blogs_add = new ew_Page("blogs_add");
blogs_add.PageID = "add"; // Page ID
var EW_PAGE_ID = blogs_add.PageID; // For backward compatibility

// Form object
var fblogsadd = new ew_Form("fblogsadd");

// Validate form
fblogsadd.Validate = function(fobj) {
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
fblogsadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fblogsadd.ValidateRequired = true;
<?php } else { ?>
fblogsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fblogsadd.Lists["x_pais"] = {"LinkField":"x_idpaises","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","x_admin","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $blogs->TableCaption() ?></h4>
<a href="<?php echo $blogs->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $blogs_add->ShowPageHeader(); ?>
<?php
$blogs_add->ShowMessage();
?>
<form name="fblogsadd" id="fblogsadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="blogs" />
<input type="hidden" name="a_add" id="a_add" value="A" />
<table id="tbl_blogsadd" class="ewTable ewTableSeparate table table-striped ">
<?php if ($blogs->nombre->Visible) { // nombre ?>
	<tr id="r_nombre"<?php echo $blogs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_blogs_nombre">
		<b><?php echo $blogs->nombre->FldCaption() ?></b>
		</span></td>
		<td<?php echo $blogs->nombre->CellAttributes() ?>><span id="el_blogs_nombre">
<input type="text" name="x_nombre" id="x_nombre" size="30" maxlength="45" value="<?php echo $blogs->nombre->EditValue ?>"<?php echo $blogs->nombre->EditAttributes() ?> />
</span><?php echo $blogs->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($blogs->pais->Visible) { // pais ?>
	<tr id="r_pais"<?php echo $blogs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_blogs_pais">
		<b><?php echo $blogs->pais->FldCaption() ?></b>
		</span></td>
		<td<?php echo $blogs->pais->CellAttributes() ?>><span id="el_blogs_pais">
<select id="x_pais" name="x_pais"<?php echo $blogs->pais->EditAttributes() ?>>
<?php
if (is_array($blogs->pais->EditValue)) {
	$arwrk = $blogs->pais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($blogs->pais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$blogs->pais) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fblogsadd.Lists["x_pais"].Options = <?php echo (is_array($blogs->pais->EditValue)) ? ew_ArrayToJson($blogs->pais->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $blogs->pais->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($blogs->tipo->Visible) { // tipo ?>
	<tr id="r_tipo"<?php echo $blogs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_blogs_tipo">
		<b><?php echo $blogs->tipo->FldCaption() ?></b>
		</span></td>
		<td<?php echo $blogs->tipo->CellAttributes() ?>><span id="el_blogs_tipo">
<select id="x_tipo" name="x_tipo"<?php echo $blogs->tipo->EditAttributes() ?>>
<?php
if (is_array($blogs->tipo->EditValue)) {
	$arwrk = $blogs->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($blogs->tipo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span><?php echo $blogs->tipo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($blogs->user->Visible) { // user ?>
	<tr id="r_user"<?php echo $blogs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_blogs_user">
		<b><?php echo $blogs->user->FldCaption() ?></b>
		</span></td>
		<td<?php echo $blogs->user->CellAttributes() ?>><span id="el_blogs_user">
<input type="text" name="x_user" id="x_user" size="30" maxlength="245" value="<?php echo $blogs->user->EditValue ?>"<?php echo $blogs->user->EditAttributes() ?> />
</span><?php echo $blogs->user->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($blogs->pass->Visible) { // pass ?>
	<tr id="r_pass"<?php echo $blogs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_blogs_pass">
		<b><?php echo $blogs->pass->FldCaption() ?></b>
		</span></td>
		<td<?php echo $blogs->pass->CellAttributes() ?>><span id="el_blogs_pass">
<input type="text" name="x_pass" id="x_pass" size="30" maxlength="245" value="<?php echo $blogs->pass->EditValue ?>"<?php echo $blogs->pass->EditAttributes() ?> />
</span><?php echo $blogs->pass->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit"  class="btn btn-large btn-success"  name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>" />
</form>
<script type="text/javascript">
fblogsadd.Init();
</script>
<?php
$blogs_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$blogs_add->Page_Terminate();
?>
