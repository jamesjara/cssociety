<?php

// Global variable for table object
$feedback = NULL;

//
// Table class for feedback
//
class cfeedback extends cTable {
	var $idfeedback;
	var $Titulo;
	var $Descripcion;
	var $Url;
	var $autor;
	var $paises_target_blogs;
	var $paises_target_fbg;
	var $fecha;
	var $ejecutado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'feedback';
		$this->TableName = 'feedback';
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

		// idfeedback
		$this->idfeedback = new cField('feedback', 'feedback', 'x_idfeedback', 'idfeedback', '`idfeedback`', '`idfeedback`', 3, -1, FALSE, '`idfeedback`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idfeedback->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idfeedback'] = &$this->idfeedback;

		// Titulo
		$this->Titulo = new cField('feedback', 'feedback', 'x_Titulo', 'Titulo', '`Titulo`', '`Titulo`', 200, -1, FALSE, '`Titulo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Titulo'] = &$this->Titulo;

		// Descripcion
		$this->Descripcion = new cField('feedback', 'feedback', 'x_Descripcion', 'Descripcion', '`Descripcion`', '`Descripcion`', 201, -1, FALSE, '`Descripcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Descripcion'] = &$this->Descripcion;

		// Url
		$this->Url = new cField('feedback', 'feedback', 'x_Url', 'Url', '`Url`', '`Url`', 200, -1, FALSE, '`Url`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['Url'] = &$this->Url;

		// autor
		$this->autor = new cField('feedback', 'feedback', 'x_autor', 'autor', '`autor`', '`autor`', 3, -1, FALSE, '`autor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->autor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor'] = &$this->autor;

		// paises_target_blogs
		$this->paises_target_blogs = new cField('feedback', 'feedback', 'x_paises_target_blogs', 'paises_target_blogs', '`paises_target_blogs`', '`paises_target_blogs`', 200, -1, FALSE, '`paises_target_blogs`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['paises_target_blogs'] = &$this->paises_target_blogs;

		// paises_target_fbg
		$this->paises_target_fbg = new cField('feedback', 'feedback', 'x_paises_target_fbg', 'paises_target_fbg', '`paises_target_fbg`', '`paises_target_fbg`', 200, -1, FALSE, '`paises_target_fbg`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['paises_target_fbg'] = &$this->paises_target_fbg;

		// fecha
		$this->fecha = new cField('feedback', 'feedback', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['fecha'] = &$this->fecha;

		// ejecutado
		$this->ejecutado = new cField('feedback', 'feedback', 'x_ejecutado', 'ejecutado', '`ejecutado`', '`ejecutado`', 3, -1, FALSE, '`ejecutado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->ejecutado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ejecutado'] = &$this->ejecutado;
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
		return "`feedback`";
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
	var $UpdateTable = "`feedback`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
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
			$sql .= ew_QuotedName('idfeedback') . '=' . ew_QuotedValue($rs['idfeedback'], $this->idfeedback->FldDataType) . ' AND ';
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
		return "`idfeedback` = @idfeedback@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idfeedback->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idfeedback@", ew_AdjustSql($this->idfeedback->CurrentValue), $sKeyFilter); // Replace key value
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
			return $this->james_url( "feedbacklist.php" );
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
		return $this->james_url( "feedbacklist.php" );
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl($this->james_url("feedbackview.php"), $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return $this->james_url("feedbackadd.php");
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl($this->james_url("feedbackedit.php"), $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl($this->james_url(ew_CurrentPage()), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl($this->james_url("feedbackadd.php"), $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl( $this->james_url( ew_CurrentPage() ), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl($this->james_url("feedbackdelete.php"), $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idfeedback->CurrentValue)) {
			$sUrl .= "idfeedback=" . urlencode($this->idfeedback->CurrentValue);
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
			$arKeys[] = @$_GET["idfeedback"]; // idfeedback

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
			$this->idfeedback->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// idfeedback
		// Titulo
		// Descripcion
		// Url
		// autor
		// paises_target_blogs
		// paises_target_fbg
		// fecha
		// ejecutado
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

		// idfeedback
		$this->idfeedback->LinkCustomAttributes = "";
		$this->idfeedback->HrefValue = "";
		$this->idfeedback->TooltipValue = "";

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
				if ($this->idfeedback->Exportable) $Doc->ExportCaption($this->idfeedback);
				if ($this->Titulo->Exportable) $Doc->ExportCaption($this->Titulo);
				if ($this->Descripcion->Exportable) $Doc->ExportCaption($this->Descripcion);
				if ($this->Url->Exportable) $Doc->ExportCaption($this->Url);
				if ($this->autor->Exportable) $Doc->ExportCaption($this->autor);
				if ($this->paises_target_blogs->Exportable) $Doc->ExportCaption($this->paises_target_blogs);
				if ($this->paises_target_fbg->Exportable) $Doc->ExportCaption($this->paises_target_fbg);
				if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
				if ($this->ejecutado->Exportable) $Doc->ExportCaption($this->ejecutado);
			} else {
				if ($this->idfeedback->Exportable) $Doc->ExportCaption($this->idfeedback);
				if ($this->Titulo->Exportable) $Doc->ExportCaption($this->Titulo);
				if ($this->Url->Exportable) $Doc->ExportCaption($this->Url);
				if ($this->autor->Exportable) $Doc->ExportCaption($this->autor);
				if ($this->paises_target_blogs->Exportable) $Doc->ExportCaption($this->paises_target_blogs);
				if ($this->paises_target_fbg->Exportable) $Doc->ExportCaption($this->paises_target_fbg);
				if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
				if ($this->ejecutado->Exportable) $Doc->ExportCaption($this->ejecutado);
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
					if ($this->idfeedback->Exportable) $Doc->ExportField($this->idfeedback);
					if ($this->Titulo->Exportable) $Doc->ExportField($this->Titulo);
					if ($this->Descripcion->Exportable) $Doc->ExportField($this->Descripcion);
					if ($this->Url->Exportable) $Doc->ExportField($this->Url);
					if ($this->autor->Exportable) $Doc->ExportField($this->autor);
					if ($this->paises_target_blogs->Exportable) $Doc->ExportField($this->paises_target_blogs);
					if ($this->paises_target_fbg->Exportable) $Doc->ExportField($this->paises_target_fbg);
					if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
					if ($this->ejecutado->Exportable) $Doc->ExportField($this->ejecutado);
				} else {
					if ($this->idfeedback->Exportable) $Doc->ExportField($this->idfeedback);
					if ($this->Titulo->Exportable) $Doc->ExportField($this->Titulo);
					if ($this->Url->Exportable) $Doc->ExportField($this->Url);
					if ($this->autor->Exportable) $Doc->ExportField($this->autor);
					if ($this->paises_target_blogs->Exportable) $Doc->ExportField($this->paises_target_blogs);
					if ($this->paises_target_fbg->Exportable) $Doc->ExportField($this->paises_target_fbg);
					if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
					if ($this->ejecutado->Exportable) $Doc->ExportField($this->ejecutado);
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
