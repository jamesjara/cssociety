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

$fb_grupos_edit = NULL; // Initialize page object first

class cfb_grupos_edit extends cfb_grupos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'fb_grupos';

	// Page object name
	var $PageObjName = 'fb_grupos_edit';

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

		// Table object (fb_grupos)
		if (!isset($GLOBALS["fb_grupos"])) {
			$GLOBALS["fb_grupos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fb_grupos"];
		}

		// Table object (paises)
		if (!isset($GLOBALS['paises'])) $GLOBALS['paises'] = new cpaises();

		// Table object (owners)
		if (!isset($GLOBALS['owners'])) $GLOBALS['owners'] = new cowners();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_grupos', TRUE);

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
		$this->idfb_grupos->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["idfb_grupos"] <> "")
			$this->idfb_grupos->setQueryStringValue($_GET["idfb_grupos"]);

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->idfb_grupos->CurrentValue == "")
			$this->Page_Terminate($this->james_url( "fb_gruposlist.php" )); // Invalid key, return to list

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate($this->james_url( "fb_gruposlist.php" )); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idfb_grupos->FldIsDetailKey)
			$this->idfb_grupos->setFormValue($objForm->GetValue("x_idfb_grupos"));
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->pais->FldIsDetailKey) {
			$this->pais->setFormValue($objForm->GetValue("x_pais"));
		}
		if (!$this->url->FldIsDetailKey) {
			$this->url->setFormValue($objForm->GetValue("x_url"));
		}
		if (!$this->super_id->FldIsDetailKey) {
			$this->super_id->setFormValue($objForm->GetValue("x_super_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idfb_grupos->CurrentValue = $this->idfb_grupos->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->pais->CurrentValue = $this->pais->FormValue;
		$this->url->CurrentValue = $this->url->FormValue;
		$this->super_id->CurrentValue = $this->super_id->FormValue;
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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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

			// idfb_grupos
			$this->idfb_grupos->LinkCustomAttributes = "";
			$this->idfb_grupos->HrefValue = "";
			$this->idfb_grupos->TooltipValue = "";

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

			// super_id
			$this->super_id->LinkCustomAttributes = "";
			$this->super_id->HrefValue = "";
			$this->super_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// idfb_grupos
			$this->idfb_grupos->EditCustomAttributes = "";
			$this->idfb_grupos->EditValue = $this->idfb_grupos->CurrentValue;
			$this->idfb_grupos->ViewCustomAttributes = "";

			// nombre
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);

			// pais
			$this->pais->EditCustomAttributes = "";
			if ($this->pais->getSessionValue() <> "") {
				$this->pais->CurrentValue = $this->pais->getSessionValue();
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
			} else {
			if (trim(strval($this->pais->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`idpaises`" . ew_SearchString("=", $this->pais->CurrentValue, EW_DATATYPE_NUMBER);
			}
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
			}

			// url
			$this->url->EditCustomAttributes = "";
			$this->url->EditValue = ew_HtmlEncode($this->url->CurrentValue);

			// super_id
			$this->super_id->EditCustomAttributes = "";
			$this->super_id->EditValue = ew_HtmlEncode($this->super_id->CurrentValue);

			// Edit refer script
			// idfb_grupos

			$this->idfb_grupos->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// pais
			$this->pais->HrefValue = "";

			// url
			$this->url->HrefValue = "";

			// super_id
			$this->super_id->HrefValue = "";
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

		// Validate detail grid
		if ($this->getCurrentDetailTable() == "fb_posts" && $GLOBALS["fb_posts"]->DetailEdit) {
			if (!isset($GLOBALS["fb_posts_grid"])) $GLOBALS["fb_posts_grid"] = new cfb_posts_grid(); // get detail page object
			$GLOBALS["fb_posts_grid"]->ValidateGridForm();
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$rsnew = array();

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, $this->nombre->ReadOnly);

			// pais
			$this->pais->SetDbValueDef($rsnew, $this->pais->CurrentValue, NULL, $this->pais->ReadOnly);

			// url
			$this->url->SetDbValueDef($rsnew, $this->url->CurrentValue, NULL, $this->url->ReadOnly);

			// super_id
			$this->super_id->SetDbValueDef($rsnew, $this->super_id->CurrentValue, NULL, $this->super_id->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';

				// Update detail records
				if ($EditRow) {
					if ($this->getCurrentDetailTable() == "fb_posts" && $GLOBALS["fb_posts"]->DetailEdit) {
						if (!isset($GLOBALS["fb_posts_grid"])) $GLOBALS["fb_posts_grid"] = new cfb_posts_grid(); // get detail page object
						$EditRow = $GLOBALS["fb_posts_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			if ($sDetailTblVar == "fb_posts") {
				if (!isset($GLOBALS["fb_posts_grid"]))
					$GLOBALS["fb_posts_grid"] = new cfb_posts_grid;
				if ($GLOBALS["fb_posts_grid"]->DetailEdit) {
					$GLOBALS["fb_posts_grid"]->CurrentMode = "edit";
					$GLOBALS["fb_posts_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["fb_posts_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["fb_posts_grid"]->setStartRecordNumber(1);
					$GLOBALS["fb_posts_grid"]->id_grupo->FldIsDetailKey = TRUE;
					$GLOBALS["fb_posts_grid"]->id_grupo->CurrentValue = $this->super_id->CurrentValue;
					$GLOBALS["fb_posts_grid"]->id_grupo->setSessionValue($GLOBALS["fb_posts_grid"]->id_grupo->CurrentValue);
				}
			}
		}
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
if (!isset($fb_grupos_edit)) $fb_grupos_edit = new cfb_grupos_edit();

// Page init
$fb_grupos_edit->Page_Init();

// Page main
$fb_grupos_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_grupos_edit = new ew_Page("fb_grupos_edit");
fb_grupos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = fb_grupos_edit.PageID; // For backward compatibility

// Form object
var ffb_gruposedit = new ew_Form("ffb_gruposedit");

// Validate form
ffb_gruposedit.Validate = function(fobj) {
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
ffb_gruposedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_gruposedit.ValidateRequired = true;
<?php } else { ?>
ffb_gruposedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffb_gruposedit.Lists["x_pais"] = {"LinkField":"x_idpaises","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_admin","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_grupos->TableCaption() ?></h4>
<a href="<?php echo $fb_grupos->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $fb_grupos_edit->ShowPageHeader(); ?>
<?php
$fb_grupos_edit->ShowMessage();
?>
<form name="ffb_gruposedit" id="ffb_gruposedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="fb_grupos" />
<input type="hidden" name="a_edit" id="a_edit" value="U" />
<table id="tbl_fb_gruposedit" class="ewTable ewTableSeparate table table-striped ">
<?php if ($fb_grupos->idfb_grupos->Visible) { // idfb_grupos ?>
	<tr id="r_idfb_grupos"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_idfb_grupos">
		<b><?php echo $fb_grupos->idfb_grupos->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_grupos->idfb_grupos->CellAttributes() ?>><span id="el_fb_grupos_idfb_grupos">
<span<?php echo $fb_grupos->idfb_grupos->ViewAttributes() ?>>
<?php echo $fb_grupos->idfb_grupos->EditValue ?></span>
<input type="hidden" name="x_idfb_grupos" id="x_idfb_grupos" value="<?php echo ew_HtmlEncode($fb_grupos->idfb_grupos->CurrentValue) ?>" />
</span><?php echo $fb_grupos->idfb_grupos->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
	<tr id="r_nombre"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_nombre">
		<b><?php echo $fb_grupos->nombre->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_grupos->nombre->CellAttributes() ?>><span id="el_fb_grupos_nombre">
<input type="text" name="x_nombre" id="x_nombre" size="30" maxlength="45" value="<?php echo $fb_grupos->nombre->EditValue ?>"<?php echo $fb_grupos->nombre->EditAttributes() ?> />
</span><?php echo $fb_grupos->nombre->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->pais->Visible) { // pais ?>
	<tr id="r_pais"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_pais">
		<b><?php echo $fb_grupos->pais->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_grupos->pais->CellAttributes() ?>><span id="el_fb_grupos_pais">
<?php if ($fb_grupos->pais->getSessionValue() <> "") { ?>
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ViewValue ?></span>
<input type="hidden" id="x_pais" name="x_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->CurrentValue) ?>">
<?php } else { ?>
<select id="x_pais" name="x_pais"<?php echo $fb_grupos->pais->EditAttributes() ?>>
<?php
if (is_array($fb_grupos->pais->EditValue)) {
	$arwrk = $fb_grupos->pais->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fb_grupos->pais->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$fb_grupos->pais) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT `idpaises`, `nombre` AS `DispFld`, `admin` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
$sWhereWrk = "";
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_pais" id="s_x_pais" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($fb_grupos->pais->LookupFn) ?>&f0=<?php echo TEAencrypt("`idpaises` = {filter_value}"); ?>&t0=3" />
<?php } ?>
</span><?php echo $fb_grupos->pais->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->url->Visible) { // url ?>
	<tr id="r_url"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_url">
		<b><?php echo $fb_grupos->url->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_grupos->url->CellAttributes() ?>><span id="el_fb_grupos_url">
<input type="text" name="x_url" id="x_url" size="30" maxlength="245" value="<?php echo $fb_grupos->url->EditValue ?>"<?php echo $fb_grupos->url->EditAttributes() ?> />
</span><?php echo $fb_grupos->url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_grupos->super_id->Visible) { // super_id ?>
	<tr id="r_super_id"<?php echo $fb_grupos->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_grupos_super_id">
		<b><?php echo $fb_grupos->super_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_grupos->super_id->CellAttributes() ?>><span id="el_fb_grupos_super_id">
<input type="text" name="x_super_id" id="x_super_id" size="30" maxlength="245" value="<?php echo $fb_grupos->super_id->EditValue ?>"<?php echo $fb_grupos->super_id->EditAttributes() ?> />
</span><?php echo $fb_grupos->super_id->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<?php if ($fb_grupos->getCurrentDetailTable() == "fb_posts" && $fb_posts->DetailEdit) { ?>
<br />
<?php include_once "fb_postsgrid.php" ?>
<br />
<?php } ?>
<input type="submit" class="btn btn-large btn-success" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>" />
</form>
<script type="text/javascript">
ffb_gruposedit.Init();
</script>
<?php
$fb_grupos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_grupos_edit->Page_Terminate();
?>
