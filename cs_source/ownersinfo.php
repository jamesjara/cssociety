<?php

// Global variable for table object
$owners = NULL;

//
// Table class for owners
//
class cowners extends cTable {
	var $id_owners;
	var $Correo_Electronico;
	var $Password;
	var $activated;
	var $profile;
	var $role;
	var $tipo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'owners';
		$this->TableName = 'owners';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_owners
		$this->id_owners = new cField('owners', 'owners', 'x_id_owners', 'id_owners', '`id_owners`', '`id_owners`', 3, -1, FALSE, '`id_owners`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_owners->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_owners'] = &$this->id_owners;

		// Correo Electronico
		$this->Correo_Electronico = new cField('owners', 'owners', 'x_Correo_Electronico', 'Correo Electronico', '`Correo Electronico`', '`Correo Electronico`', 200, -1, FALSE, '`Correo Electronico`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Correo Electronico'] = &$this->Correo_Electronico;

		// Password
		$this->Password = new cField('owners', 'owners', 'x_Password', 'Password', '`Password`', '`Password`', 200, -1, FALSE, '`Password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Password'] = &$this->Password;

		// activated
		$this->activated = new cField('owners', 'owners', 'x_activated', 'activated', '`activated`', '`activated`', 200, -1, FALSE, '`activated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['activated'] = &$this->activated;

		// profile
		$this->profile = new cField('owners', 'owners', 'x_profile', 'profile', '`profile`', '`profile`', 201, -1, FALSE, '`profile`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['profile'] = &$this->profile;

		// role
		$this->role = new cField('owners', 'owners', 'x_role', 'role', '`role`', '`role`', 3, -1, FALSE, '`role`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->role->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['role'] = &$this->role;

		// tipo
		$this->tipo = new cField('owners', 'owners', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 3, -1, FALSE, '`tipo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tipo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tipo'] = &$this->tipo;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`owners`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		return TRUE;
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`owners`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			$sql .= ew_QuotedName('id_owners') . '=' . ew_QuotedValue($rs['id_owners'], $this->id_owners->FldDataType) . ' AND ';
		}
		if (substr($sql, -5) == " AND ") $sql = substr($sql, 0, -5);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " AND " . $filter;
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_owners` = @id_owners@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_owners->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_owners@", ew_AdjustSql($this->id_owners->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return $this->james_url( "ownerslist.php" );
		}
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

	// List URL
	function GetListUrl() {
		return $this->james_url( "ownerslist.php" );
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl($this->james_url("ownersview.php"), $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return $this->james_url("ownersadd.php");
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl($this->james_url("ownersedit.php"), $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl($this->james_url(ew_CurrentPage()), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl($this->james_url("ownersadd.php"), $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl( $this->james_url( ew_CurrentPage() ), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl($this->james_url("ownersdelete.php"), $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_owners->CurrentValue)) {
			$sUrl .= "id_owners=" . urlencode($this->id_owners->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id_owners"]; // id_owners

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_owners->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id_owners->setDbValue($rs->fields('id_owners'));
		$this->Correo_Electronico->setDbValue($rs->fields('Correo Electronico'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->activated->setDbValue($rs->fields('activated'));
		$this->profile->setDbValue($rs->fields('profile'));
		$this->role->setDbValue($rs->fields('role'));
		$this->tipo->setDbValue($rs->fields('tipo'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_owners
		// Correo Electronico
		// Password
		// activated
		// profile
		// role
		// tipo
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

		// id_owners
		$this->id_owners->LinkCustomAttributes = "";
		$this->id_owners->HrefValue = "";
		$this->id_owners->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->id_owners->Exportable) $Doc->ExportCaption($this->id_owners);
				if ($this->Correo_Electronico->Exportable) $Doc->ExportCaption($this->Correo_Electronico);
				if ($this->Password->Exportable) $Doc->ExportCaption($this->Password);
				if ($this->activated->Exportable) $Doc->ExportCaption($this->activated);
				if ($this->profile->Exportable) $Doc->ExportCaption($this->profile);
				if ($this->role->Exportable) $Doc->ExportCaption($this->role);
				if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
			} else {
				if ($this->id_owners->Exportable) $Doc->ExportCaption($this->id_owners);
				if ($this->Correo_Electronico->Exportable) $Doc->ExportCaption($this->Correo_Electronico);
				if ($this->Password->Exportable) $Doc->ExportCaption($this->Password);
				if ($this->activated->Exportable) $Doc->ExportCaption($this->activated);
				if ($this->role->Exportable) $Doc->ExportCaption($this->role);
				if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->id_owners->Exportable) $Doc->ExportField($this->id_owners);
					if ($this->Correo_Electronico->Exportable) $Doc->ExportField($this->Correo_Electronico);
					if ($this->Password->Exportable) $Doc->ExportField($this->Password);
					if ($this->activated->Exportable) $Doc->ExportField($this->activated);
					if ($this->profile->Exportable) $Doc->ExportField($this->profile);
					if ($this->role->Exportable) $Doc->ExportField($this->role);
					if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
				} else {
					if ($this->id_owners->Exportable) $Doc->ExportField($this->id_owners);
					if ($this->Correo_Electronico->Exportable) $Doc->ExportField($this->Correo_Electronico);
					if ($this->Password->Exportable) $Doc->ExportField($this->Password);
					if ($this->activated->Exportable) $Doc->ExportField($this->activated);
					if ($this->role->Exportable) $Doc->ExportField($this->role);
					if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
