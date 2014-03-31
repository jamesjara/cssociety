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

$feedback_delete = NULL; // Initialize page object first

class cfeedback_delete extends cfeedback {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'feedback';

	// Page object name
	var $PageObjName = 'feedback_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate($this->james_url( "feedbacklist.php" )); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in feedback class, feedbackinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		} else {
			$this->LoadRowValues($rs); // Load row values
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['idfeedback'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($feedback_delete)) $feedback_delete = new cfeedback_delete();

// Page init
$feedback_delete->Page_Init();

// Page main
$feedback_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var feedback_delete = new ew_Page("feedback_delete");
feedback_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = feedback_delete.PageID; // For backward compatibility

// Form object
var ffeedbackdelete = new ew_Form("ffeedbackdelete");

// Form_CustomValidate event
ffeedbackdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffeedbackdelete.ValidateRequired = true;
<?php } else { ?>
ffeedbackdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($feedback_delete->Recordset = $feedback_delete->LoadRecordset())
	$feedback_deleteTotalRecs = $feedback_delete->Recordset->RecordCount(); // Get record count
if ($feedback_deleteTotalRecs <= 0) { // No record found, exit
	if ($feedback_delete->Recordset)
		$feedback_delete->Recordset->Close();
	$feedback_delete->Page_Terminate($this->james_url( "feedbacklist.php" )); // Return to list
}
?>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $feedback->TableCaption() ?></h4>
<a href="<?php echo $feedback->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("GoBack") ?></a>
<?php $feedback_delete->ShowPageHeader(); ?>
<?php
$feedback_delete->ShowMessage();
?>
<form name="ffeedbackdelete" id="ffeedbackdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="feedback" />
<input type="hidden" name="a_delete" id="a_delete" value="D" />
<?php foreach ($feedback_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>" />
<?php } ?>
<br />
<table id="tbl_feedbackdelete" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $feedback->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<th><span id="elh_feedback_idfeedback" class="feedback_idfeedback">
		<?php echo $feedback->idfeedback->FldCaption() ?></span></th>
		<th><span id="elh_feedback_Titulo" class="feedback_Titulo">
		<?php echo $feedback->Titulo->FldCaption() ?></span></th>
		<th><span id="elh_feedback_Url" class="feedback_Url">
		<?php echo $feedback->Url->FldCaption() ?></span></th>
		<th><span id="elh_feedback_autor" class="feedback_autor">
		<?php echo $feedback->autor->FldCaption() ?></span></th>
		<th><span id="elh_feedback_paises_target_blogs" class="feedback_paises_target_blogs">
		<?php echo $feedback->paises_target_blogs->FldCaption() ?></span></th>
		<th><span id="elh_feedback_paises_target_fbg" class="feedback_paises_target_fbg">
		<?php echo $feedback->paises_target_fbg->FldCaption() ?></span></th>
		<th><span id="elh_feedback_fecha" class="feedback_fecha">
		<?php echo $feedback->fecha->FldCaption() ?></span></th>
		<th><span id="elh_feedback_ejecutado" class="feedback_ejecutado">
		<?php echo $feedback->ejecutado->FldCaption() ?></span></th>
	</tr>
	</thead>
	<tbody>
<?php
$feedback_delete->RecCnt = 0;
$i = 0;
while (!$feedback_delete->Recordset->EOF) {
	$feedback_delete->RecCnt++;
	$feedback_delete->RowCnt++;

	// Set row properties
	$feedback->ResetAttrs();
	$feedback->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$feedback_delete->LoadRowValues($feedback_delete->Recordset);

	// Render row
	$feedback_delete->RenderRow();
?>
	<tr<?php echo $feedback->RowAttributes() ?>>
		<td<?php echo $feedback->idfeedback->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_idfeedback" class="feedback_idfeedback">
<span<?php echo $feedback->idfeedback->ViewAttributes() ?>>
<?php echo $feedback->idfeedback->ListViewValue() ?></span>
</span></td>
		<td<?php echo $feedback->Titulo->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_Titulo" class="feedback_Titulo">
<span<?php echo $feedback->Titulo->ViewAttributes() ?>>
<?php echo $feedback->Titulo->ListViewValue() ?></span>
</span></td>
		<td<?php echo $feedback->Url->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_Url" class="feedback_Url">
<span<?php echo $feedback->Url->ViewAttributes() ?>>
<?php echo $feedback->Url->ListViewValue() ?></span>
</span></td>
		<td<?php echo $feedback->autor->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_autor" class="feedback_autor">
<span<?php echo $feedback->autor->ViewAttributes() ?>>
<?php echo $feedback->autor->ListViewValue() ?></span>
</span></td>
		<td<?php echo $feedback->paises_target_blogs->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_paises_target_blogs" class="feedback_paises_target_blogs">
<span<?php echo $feedback->paises_target_blogs->ViewAttributes() ?>>
<?php echo $feedback->paises_target_blogs->ListViewValue() ?></span>
</span></td>
		<td<?php echo $feedback->paises_target_fbg->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_paises_target_fbg" class="feedback_paises_target_fbg">
<span<?php echo $feedback->paises_target_fbg->ViewAttributes() ?>>
<?php echo $feedback->paises_target_fbg->ListViewValue() ?></span>
</span></td>
		<td<?php echo $feedback->fecha->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_fecha" class="feedback_fecha">
<span<?php echo $feedback->fecha->ViewAttributes() ?>>
<?php echo $feedback->fecha->ListViewValue() ?></span>
</span></td>
		<td<?php echo $feedback->ejecutado->CellAttributes() ?>><span id="el<?php echo $feedback_delete->RowCnt ?>_feedback_ejecutado" class="feedback_ejecutado">
<span<?php echo $feedback->ejecutado->ViewAttributes() ?>>
<?php echo $feedback->ejecutado->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$feedback_delete->Recordset->MoveNext();
}
$feedback_delete->Recordset->Close();
?>
</tbody>
</table>
<input class="ewLink btn btn-danger" type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>" />
</form>
<script type="text/javascript">
ffeedbackdelete.Init();
</script>
<?php
$feedback_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$feedback_delete->Page_Terminate();
?>
