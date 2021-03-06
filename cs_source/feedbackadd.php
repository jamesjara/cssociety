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

$feedback_add = NULL; // Initialize page object first

class cfeedback_add extends cfeedback {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'feedback';

	// Page object name
	var $PageObjName = 'feedback_add';

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

		// Table object (feedback)
		if (!isset($GLOBALS["feedback"])) {
			$GLOBALS["feedback"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["feedback"];
		}

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'feedback', TRUE);

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
			if (@$_GET["idfeedback"] != "") {
				$this->idfeedback->setQueryStringValue($_GET["idfeedback"]);
				$this->setKey("idfeedback", $this->idfeedback->CurrentValue); // Set up key
			} else {
				$this->setKey("idfeedback", ""); // Clear key
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
					$this->Page_Terminate($this->james_url( "feedbacklist.php" )); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "feedbackview.php")
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
		$this->Titulo->CurrentValue = NULL;
		$this->Titulo->OldValue = $this->Titulo->CurrentValue;
		$this->Descripcion->CurrentValue = NULL;
		$this->Descripcion->OldValue = $this->Descripcion->CurrentValue;
		$this->Url->CurrentValue = NULL;
		$this->Url->OldValue = $this->Url->CurrentValue;
		$this->autor->CurrentValue = NULL;
		$this->autor->OldValue = $this->autor->CurrentValue;
		$this->paises_target_blogs->CurrentValue = NULL;
		$this->paises_target_blogs->OldValue = $this->paises_target_blogs->CurrentValue;
		$this->paises_target_fbg->CurrentValue = NULL;
		$this->paises_target_fbg->OldValue = $this->paises_target_fbg->CurrentValue;
		$this->fecha->CurrentValue = NULL;
		$this->fecha->OldValue = $this->fecha->CurrentValue;
		$this->ejecutado->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Titulo->FldIsDetailKey) {
			$this->Titulo->setFormValue($objForm->GetValue("x_Titulo"));
		}
		if (!$this->Descripcion->FldIsDetailKey) {
			$this->Descripcion->setFormValue($objForm->GetValue("x_Descripcion"));
		}
		if (!$this->Url->FldIsDetailKey) {
			$this->Url->setFormValue($objForm->GetValue("x_Url"));
		}
		if (!$this->autor->FldIsDetailKey) {
			$this->autor->setFormValue($objForm->GetValue("x_autor"));
		}
		if (!$this->paises_target_blogs->FldIsDetailKey) {
			$this->paises_target_blogs->setFormValue($objForm->GetValue("x_paises_target_blogs"));
		}
		if (!$this->paises_target_fbg->FldIsDetailKey) {
			$this->paises_target_fbg->setFormValue($objForm->GetValue("x_paises_target_fbg"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 5);
		}
		if (!$this->ejecutado->FldIsDetailKey) {
			$this->ejecutado->setFormValue($objForm->GetValue("x_ejecutado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Titulo->CurrentValue = $this->Titulo->FormValue;
		$this->Descripcion->CurrentValue = $this->Descripcion->FormValue;
		$this->Url->CurrentValue = $this->Url->FormValue;
		$this->autor->CurrentValue = $this->autor->FormValue;
		$this->paises_target_blogs->CurrentValue = $this->paises_target_blogs->FormValue;
		$this->paises_target_fbg->CurrentValue = $this->paises_target_fbg->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 5);
		$this->ejecutado->CurrentValue = $this->ejecutado->FormValue;
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

			// Descripcion
			$this->Descripcion->ViewValue = $this->Descripcion->CurrentValue;
			$this->Descripcion->ViewCustomAttributes = "";

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

			// Titulo
			$this->Titulo->LinkCustomAttributes = "";
			$this->Titulo->HrefValue = "";
			$this->Titulo->TooltipValue = "";

			// Descripcion
			$this->Descripcion->LinkCustomAttributes = "";
			$this->Descripcion->HrefValue = "";
			$this->Descripcion->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Titulo
			$this->Titulo->EditCustomAttributes = "";
			$this->Titulo->EditValue = ew_HtmlEncode($this->Titulo->CurrentValue);

			// Descripcion
			$this->Descripcion->EditCustomAttributes = "";
			$this->Descripcion->EditValue = ew_HtmlEncode($this->Descripcion->CurrentValue);

			// Url
			$this->Url->EditCustomAttributes = "";
			$this->Url->EditValue = ew_HtmlEncode($this->Url->CurrentValue);

			// autor
			$this->autor->EditCustomAttributes = "";
			$this->autor->EditValue = ew_HtmlEncode($this->autor->CurrentValue);

			// paises_target_blogs
			$this->paises_target_blogs->EditCustomAttributes = "";
			$this->paises_target_blogs->EditValue = ew_HtmlEncode($this->paises_target_blogs->CurrentValue);

			// paises_target_fbg
			$this->paises_target_fbg->EditCustomAttributes = "";
			$this->paises_target_fbg->EditValue = ew_HtmlEncode($this->paises_target_fbg->CurrentValue);

			// fecha
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 5));

			// ejecutado
			$this->ejecutado->EditCustomAttributes = "";
			$this->ejecutado->EditValue = ew_HtmlEncode($this->ejecutado->CurrentValue);

			// Edit refer script
			// Titulo

			$this->Titulo->HrefValue = "";

			// Descripcion
			$this->Descripcion->HrefValue = "";

			// Url
			$this->Url->HrefValue = "";

			// autor
			$this->autor->HrefValue = "";

			// paises_target_blogs
			$this->paises_target_blogs->HrefValue = "";

			// paises_target_fbg
			$this->paises_target_fbg->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// ejecutado
			$this->ejecutado->HrefValue = "";
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
		if (!ew_CheckInteger($this->autor->FormValue)) {
			ew_AddMessage($gsFormError, $this->autor->FldErrMsg());
		}
		if (!ew_CheckDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ejecutado->FormValue)) {
			ew_AddMessage($gsFormError, $this->ejecutado->FldErrMsg());
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

		// Titulo
		$this->Titulo->SetDbValueDef($rsnew, $this->Titulo->CurrentValue, NULL, FALSE);

		// Descripcion
		$this->Descripcion->SetDbValueDef($rsnew, $this->Descripcion->CurrentValue, NULL, FALSE);

		// Url
		$this->Url->SetDbValueDef($rsnew, $this->Url->CurrentValue, NULL, FALSE);

		// autor
		$this->autor->SetDbValueDef($rsnew, $this->autor->CurrentValue, NULL, FALSE);

		// paises_target_blogs
		$this->paises_target_blogs->SetDbValueDef($rsnew, $this->paises_target_blogs->CurrentValue, NULL, FALSE);

		// paises_target_fbg
		$this->paises_target_fbg->SetDbValueDef($rsnew, $this->paises_target_fbg->CurrentValue, NULL, FALSE);

		// fecha
		$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 5), NULL, FALSE);

		// ejecutado
		$this->ejecutado->SetDbValueDef($rsnew, $this->ejecutado->CurrentValue, NULL, strval($this->ejecutado->CurrentValue) == "");

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
			$this->idfeedback->setDbValue($conn->Insert_ID());
			$rsnew['idfeedback'] = $this->idfeedback->DbValue;
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
if (!isset($feedback_add)) $feedback_add = new cfeedback_add();

// Page init
$feedback_add->Page_Init();

// Page main
$feedback_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var feedback_add = new ew_Page("feedback_add");
feedback_add.PageID = "add"; // Page ID
var EW_PAGE_ID = feedback_add.PageID; // For backward compatibility

// Form object
var ffeedbackadd = new ew_Form("ffeedbackadd");

// Validate form
ffeedbackadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_autor"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($feedback->autor->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_fecha"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($feedback->fecha->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_ejecutado"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($feedback->ejecutado->FldErrMsg()) ?>");

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
ffeedbackadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffeedbackadd.ValidateRequired = true;
<?php } else { ?>
ffeedbackadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $feedback->TableCaption() ?></h4>
<a href="<?php echo $feedback->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $feedback_add->ShowPageHeader(); ?>
<?php
$feedback_add->ShowMessage();
?>
<form name="ffeedbackadd" id="ffeedbackadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="feedback" />
<input type="hidden" name="a_add" id="a_add" value="A" />
<table id="tbl_feedbackadd" class="ewTable ewTableSeparate table table-striped ">
<?php if ($feedback->Titulo->Visible) { // Titulo ?>
	<tr id="r_Titulo"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_Titulo">
		<b><?php echo $feedback->Titulo->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->Titulo->CellAttributes() ?>><span id="el_feedback_Titulo">
<input type="text" name="x_Titulo" id="x_Titulo" size="30" maxlength="245" value="<?php echo $feedback->Titulo->EditValue ?>"<?php echo $feedback->Titulo->EditAttributes() ?> />
</span><?php echo $feedback->Titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($feedback->Descripcion->Visible) { // Descripcion ?>
	<tr id="r_Descripcion"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_Descripcion">
		<b><?php echo $feedback->Descripcion->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->Descripcion->CellAttributes() ?>><span id="el_feedback_Descripcion">
<textarea name="x_Descripcion" id="x_Descripcion" cols="35" rows="4"<?php echo $feedback->Descripcion->EditAttributes() ?>><?php echo $feedback->Descripcion->EditValue ?></textarea>
</span><?php echo $feedback->Descripcion->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($feedback->Url->Visible) { // Url ?>
	<tr id="r_Url"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_Url">
		<b><?php echo $feedback->Url->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->Url->CellAttributes() ?>><span id="el_feedback_Url">
<input type="text" name="x_Url" id="x_Url" size="30" maxlength="245" value="<?php echo $feedback->Url->EditValue ?>"<?php echo $feedback->Url->EditAttributes() ?> />
</span><?php echo $feedback->Url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($feedback->autor->Visible) { // autor ?>
	<tr id="r_autor"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_autor">
		<b><?php echo $feedback->autor->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->autor->CellAttributes() ?>><span id="el_feedback_autor">
<input type="text" name="x_autor" id="x_autor" size="30" value="<?php echo $feedback->autor->EditValue ?>"<?php echo $feedback->autor->EditAttributes() ?> />
</span><?php echo $feedback->autor->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($feedback->paises_target_blogs->Visible) { // paises_target_blogs ?>
	<tr id="r_paises_target_blogs"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_paises_target_blogs">
		<b><?php echo $feedback->paises_target_blogs->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->paises_target_blogs->CellAttributes() ?>><span id="el_feedback_paises_target_blogs">
<input type="text" name="x_paises_target_blogs" id="x_paises_target_blogs" size="30" maxlength="245" value="<?php echo $feedback->paises_target_blogs->EditValue ?>"<?php echo $feedback->paises_target_blogs->EditAttributes() ?> />
</span><?php echo $feedback->paises_target_blogs->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($feedback->paises_target_fbg->Visible) { // paises_target_fbg ?>
	<tr id="r_paises_target_fbg"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_paises_target_fbg">
		<b><?php echo $feedback->paises_target_fbg->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->paises_target_fbg->CellAttributes() ?>><span id="el_feedback_paises_target_fbg">
<input type="text" name="x_paises_target_fbg" id="x_paises_target_fbg" size="30" maxlength="245" value="<?php echo $feedback->paises_target_fbg->EditValue ?>"<?php echo $feedback->paises_target_fbg->EditAttributes() ?> />
</span><?php echo $feedback->paises_target_fbg->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($feedback->fecha->Visible) { // fecha ?>
	<tr id="r_fecha"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_fecha">
		<b><?php echo $feedback->fecha->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->fecha->CellAttributes() ?>><span id="el_feedback_fecha">
<input type="text" name="x_fecha" id="x_fecha" value="<?php echo $feedback->fecha->EditValue ?>"<?php echo $feedback->fecha->EditAttributes() ?> />
</span><?php echo $feedback->fecha->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($feedback->ejecutado->Visible) { // ejecutado ?>
	<tr id="r_ejecutado"<?php echo $feedback->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_feedback_ejecutado">
		<b><?php echo $feedback->ejecutado->FldCaption() ?></b>
		</span></td>
		<td<?php echo $feedback->ejecutado->CellAttributes() ?>><span id="el_feedback_ejecutado">
<input type="text" name="x_ejecutado" id="x_ejecutado" size="30" value="<?php echo $feedback->ejecutado->EditValue ?>"<?php echo $feedback->ejecutado->EditAttributes() ?> />
</span><?php echo $feedback->ejecutado->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit"  class="btn btn-large btn-success"  name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>" />
</form>
<script type="text/javascript">
ffeedbackadd.Init();
</script>
<?php
$feedback_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$feedback_add->Page_Terminate();
?>
