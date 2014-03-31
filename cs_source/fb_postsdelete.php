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

$fb_posts_delete = NULL; // Initialize page object first

class cfb_posts_delete extends cfb_posts {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B9BF3307-7A7A-4ACA-81A3-DC180D98192D}";

	// Table name
	var $TableName = 'fb_posts';

	// Page object name
	var $PageObjName = 'fb_posts_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate($this->james_url( "fb_postslist.php" )); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in fb_posts class, fb_postsinfo.php

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

			// id_grupo
			$this->id_grupo->ViewValue = $this->id_grupo->CurrentValue;
			$this->id_grupo->ViewCustomAttributes = "";

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
				$sThisKey .= $row['idfb_posts'];
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
if (!isset($fb_posts_delete)) $fb_posts_delete = new cfb_posts_delete();

// Page init
$fb_posts_delete->Page_Init();

// Page main
$fb_posts_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_posts_delete = new ew_Page("fb_posts_delete");
fb_posts_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = fb_posts_delete.PageID; // For backward compatibility

// Form object
var ffb_postsdelete = new ew_Form("ffb_postsdelete");

// Form_CustomValidate event
ffb_postsdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_postsdelete.ValidateRequired = true;
<?php } else { ?>
ffb_postsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($fb_posts_delete->Recordset = $fb_posts_delete->LoadRecordset())
	$fb_posts_deleteTotalRecs = $fb_posts_delete->Recordset->RecordCount(); // Get record count
if ($fb_posts_deleteTotalRecs <= 0) { // No record found, exit
	if ($fb_posts_delete->Recordset)
		$fb_posts_delete->Recordset->Close();
	$fb_posts_delete->Page_Terminate($this->james_url( "fb_postslist.php" )); // Return to list
}
?>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_posts->TableCaption() ?></h4>
<a href="<?php echo $fb_posts->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("GoBack") ?></a>
<?php $fb_posts_delete->ShowPageHeader(); ?>
<?php
$fb_posts_delete->ShowMessage();
?>
<form name="ffb_postsdelete" id="ffb_postsdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="fb_posts" />
<input type="hidden" name="a_delete" id="a_delete" value="D" />
<?php foreach ($fb_posts_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>" />
<?php } ?>
<br />
<table id="tbl_fb_postsdelete" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $fb_posts->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<th><span id="elh_fb_posts_message" class="fb_posts_message">
		<?php echo $fb_posts->message->FldCaption() ?></span></th>
		<th><span id="elh_fb_posts_link" class="fb_posts_link">
		<?php echo $fb_posts->link->FldCaption() ?></span></th>
		<th><span id="elh_fb_posts_type" class="fb_posts_type">
		<?php echo $fb_posts->type->FldCaption() ?></span></th>
		<th><span id="elh_fb_posts_caption" class="fb_posts_caption">
		<?php echo $fb_posts->caption->FldCaption() ?></span></th>
		<th><span id="elh_fb_posts_description" class="fb_posts_description">
		<?php echo $fb_posts->description->FldCaption() ?></span></th>
		<th><span id="elh_fb_posts_name" class="fb_posts_name">
		<?php echo $fb_posts->name->FldCaption() ?></span></th>
		<th><span id="elh_fb_posts_source" class="fb_posts_source">
		<?php echo $fb_posts->source->FldCaption() ?></span></th>
		<th><span id="elh_fb_posts_from" class="fb_posts_from">
		<?php echo $fb_posts->from->FldCaption() ?></span></th>
	</tr>
	</thead>
	<tbody>
<?php
$fb_posts_delete->RecCnt = 0;
$i = 0;
while (!$fb_posts_delete->Recordset->EOF) {
	$fb_posts_delete->RecCnt++;
	$fb_posts_delete->RowCnt++;

	// Set row properties
	$fb_posts->ResetAttrs();
	$fb_posts->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$fb_posts_delete->LoadRowValues($fb_posts_delete->Recordset);

	// Render row
	$fb_posts_delete->RenderRow();
?>
	<tr<?php echo $fb_posts->RowAttributes() ?>>
		<td<?php echo $fb_posts->message->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_message" class="fb_posts_message">
<span<?php echo $fb_posts->message->ViewAttributes() ?>>
<?php echo $fb_posts->message->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_posts->link->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_link" class="fb_posts_link">
<span<?php echo $fb_posts->link->ViewAttributes() ?>>
<?php echo $fb_posts->link->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_posts->type->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_type" class="fb_posts_type">
<span<?php echo $fb_posts->type->ViewAttributes() ?>>
<?php echo $fb_posts->type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_posts->caption->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_caption" class="fb_posts_caption">
<span<?php echo $fb_posts->caption->ViewAttributes() ?>>
<?php echo $fb_posts->caption->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_posts->description->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_description" class="fb_posts_description">
<span<?php echo $fb_posts->description->ViewAttributes() ?>>
<?php echo $fb_posts->description->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_posts->name->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_name" class="fb_posts_name">
<span<?php echo $fb_posts->name->ViewAttributes() ?>>
<?php echo $fb_posts->name->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_posts->source->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_source" class="fb_posts_source">
<span<?php echo $fb_posts->source->ViewAttributes() ?>>
<?php echo $fb_posts->source->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_posts->from->CellAttributes() ?>><span id="el<?php echo $fb_posts_delete->RowCnt ?>_fb_posts_from" class="fb_posts_from">
<span<?php echo $fb_posts->from->ViewAttributes() ?>>
<?php echo $fb_posts->from->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$fb_posts_delete->Recordset->MoveNext();
}
$fb_posts_delete->Recordset->Close();
?>
</tbody>
</table>
<input class="ewLink btn btn-danger" type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>" />
</form>
<script type="text/javascript">
ffb_postsdelete.Init();
</script>
<?php
$fb_posts_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_posts_delete->Page_Terminate();
?>
