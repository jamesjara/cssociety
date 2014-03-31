<?php

// Global variable for table object
$fb_posts = NULL;

//
// Table class for fb_posts
//
class cfb_posts extends cTable {
	var $idfb_posts;
	var $id;
	var $created_time;
	var $actions;
	var $icon;
	var $is_published;
	var $message;
	var $link;
	var $object_id;
	var $picture;
	var $privacy;
	var $promotion_status;
	var $timeline_visibility;
	var $type;
	var $updated_time;
	var $caption;
	var $description;
	var $name;
	var $source;
	var $from;
	var $to;
	var $comments;
	var $id_grupo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'fb_posts';
		$this->TableName = 'fb_posts';
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

		// idfb_posts
		$this->idfb_posts = new cField('fb_posts', 'fb_posts', 'x_idfb_posts', 'idfb_posts', '`idfb_posts`', '`idfb_posts`', 3, -1, FALSE, '`idfb_posts`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idfb_posts->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idfb_posts'] = &$this->idfb_posts;

		// id
		$this->id = new cField('fb_posts', 'fb_posts', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id'] = &$this->id;

		// created_time
		$this->created_time = new cField('fb_posts', 'fb_posts', 'x_created_time', 'created_time', '`created_time`', '`created_time`', 200, -1, FALSE, '`created_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['created_time'] = &$this->created_time;

		// actions
		$this->actions = new cField('fb_posts', 'fb_posts', 'x_actions', 'actions', '`actions`', '`actions`', 201, -1, FALSE, '`actions`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['actions'] = &$this->actions;

		// icon
		$this->icon = new cField('fb_posts', 'fb_posts', 'x_icon', 'icon', '`icon`', '`icon`', 200, -1, FALSE, '`icon`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['icon'] = &$this->icon;

		// is_published
		$this->is_published = new cField('fb_posts', 'fb_posts', 'x_is_published', 'is_published', '`is_published`', '`is_published`', 200, -1, FALSE, '`is_published`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['is_published'] = &$this->is_published;

		// message
		$this->message = new cField('fb_posts', 'fb_posts', 'x_message', 'message', '`message`', '`message`', 201, -1, FALSE, '`message`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['message'] = &$this->message;

		// link
		$this->link = new cField('fb_posts', 'fb_posts', 'x_link', 'link', '`link`', '`link`', 200, -1, FALSE, '`link`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['link'] = &$this->link;

		// object_id
		$this->object_id = new cField('fb_posts', 'fb_posts', 'x_object_id', 'object_id', '`object_id`', '`object_id`', 200, -1, FALSE, '`object_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['object_id'] = &$this->object_id;

		// picture
		$this->picture = new cField('fb_posts', 'fb_posts', 'x_picture', 'picture', '`picture`', '`picture`', 200, -1, FALSE, '`picture`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['picture'] = &$this->picture;

		// privacy
		$this->privacy = new cField('fb_posts', 'fb_posts', 'x_privacy', 'privacy', '`privacy`', '`privacy`', 201, -1, FALSE, '`privacy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['privacy'] = &$this->privacy;

		// promotion_status
		$this->promotion_status = new cField('fb_posts', 'fb_posts', 'x_promotion_status', 'promotion_status', '`promotion_status`', '`promotion_status`', 200, -1, FALSE, '`promotion_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['promotion_status'] = &$this->promotion_status;

		// timeline_visibility
		$this->timeline_visibility = new cField('fb_posts', 'fb_posts', 'x_timeline_visibility', 'timeline_visibility', '`timeline_visibility`', '`timeline_visibility`', 200, -1, FALSE, '`timeline_visibility`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['timeline_visibility'] = &$this->timeline_visibility;

		// type
		$this->type = new cField('fb_posts', 'fb_posts', 'x_type', 'type', '`type`', '`type`', 200, -1, FALSE, '`type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['type'] = &$this->type;

		// updated_time
		$this->updated_time = new cField('fb_posts', 'fb_posts', 'x_updated_time', 'updated_time', '`updated_time`', '`updated_time`', 200, -1, FALSE, '`updated_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['updated_time'] = &$this->updated_time;

		// caption
		$this->caption = new cField('fb_posts', 'fb_posts', 'x_caption', 'caption', '`caption`', '`caption`', 201, -1, FALSE, '`caption`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['caption'] = &$this->caption;

		// description
		$this->description = new cField('fb_posts', 'fb_posts', 'x_description', 'description', '`description`', '`description`', 201, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['description'] = &$this->description;

		// name
		$this->name = new cField('fb_posts', 'fb_posts', 'x_name', 'name', '`name`', '`name`', 201, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name'] = &$this->name;

		// source
		$this->source = new cField('fb_posts', 'fb_posts', 'x_source', 'source', '`source`', '`source`', 201, -1, FALSE, '`source`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['source'] = &$this->source;

		// from
		$this->from = new cField('fb_posts', 'fb_posts', 'x_from', 'from', '`from`', '`from`', 201, -1, FALSE, '`from`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['from'] = &$this->from;

		// to
		$this->to = new cField('fb_posts', 'fb_posts', 'x_to', 'to', '`to`', '`to`', 201, -1, FALSE, '`to`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['to'] = &$this->to;

		// comments
		$this->comments = new cField('fb_posts', 'fb_posts', 'x_comments', 'comments', '`comments`', '`comments`', 201, -1, FALSE, '`comments`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comments'] = &$this->comments;

		// id_grupo
		$this->id_grupo = new cField('fb_posts', 'fb_posts', 'x_id_grupo', 'id_grupo', '`id_grupo`', '`id_grupo`', 200, -1, FALSE, '`id_grupo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_grupo'] = &$this->id_grupo;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "fb_grupos") {
			if ($this->id_grupo->getSessionValue() <> "")
				$sMasterFilter .= "`super_id`=" . ew_QuotedValue($this->id_grupo->getSessionValue(), EW_DATATYPE_STRING);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "fb_grupos") {
			if ($this->id_grupo->getSessionValue() <> "")
				$sDetailFilter .= "`id_grupo`=" . ew_QuotedValue($this->id_grupo->getSessionValue(), EW_DATATYPE_STRING);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_fb_grupos() {
		return "`super_id`='@super_id@'";
	}

	// Detail filter
	function SqlDetailFilter_fb_grupos() {
		return "`id_grupo`='@id_grupo@'";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`fb_posts`";
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
		return "`created_time` DESC";
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
	var $UpdateTable = "`fb_posts`";

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
			$sql .= ew_QuotedName('idfb_posts') . '=' . ew_QuotedValue($rs['idfb_posts'], $this->idfb_posts->FldDataType) . ' AND ';
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
		return "`idfb_posts` = @idfb_posts@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idfb_posts->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idfb_posts@", ew_AdjustSql($this->idfb_posts->CurrentValue), $sKeyFilter); // Replace key value
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
			return $this->james_url( "fb_postslist.php" );
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
		return $this->james_url( "fb_postslist.php" );
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl($this->james_url("fb_postsview.php"), $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return $this->james_url("fb_postsadd.php");
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl($this->james_url("fb_postsedit.php"), $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl($this->james_url(ew_CurrentPage()), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl($this->james_url("fb_postsadd.php"), $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl( $this->james_url( ew_CurrentPage() ), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl($this->james_url("fb_postsdelete.php"), $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idfb_posts->CurrentValue)) {
			$sUrl .= "idfb_posts=" . urlencode($this->idfb_posts->CurrentValue);
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
			$arKeys[] = @$_GET["idfb_posts"]; // idfb_posts

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
			$this->idfb_posts->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// idfb_posts
		$this->idfb_posts->LinkCustomAttributes = "";
		$this->idfb_posts->HrefValue = "";
		$this->idfb_posts->TooltipValue = "";

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
				if ($this->idfb_posts->Exportable) $Doc->ExportCaption($this->idfb_posts);
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->created_time->Exportable) $Doc->ExportCaption($this->created_time);
				if ($this->actions->Exportable) $Doc->ExportCaption($this->actions);
				if ($this->icon->Exportable) $Doc->ExportCaption($this->icon);
				if ($this->is_published->Exportable) $Doc->ExportCaption($this->is_published);
				if ($this->message->Exportable) $Doc->ExportCaption($this->message);
				if ($this->link->Exportable) $Doc->ExportCaption($this->link);
				if ($this->object_id->Exportable) $Doc->ExportCaption($this->object_id);
				if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
				if ($this->privacy->Exportable) $Doc->ExportCaption($this->privacy);
				if ($this->promotion_status->Exportable) $Doc->ExportCaption($this->promotion_status);
				if ($this->timeline_visibility->Exportable) $Doc->ExportCaption($this->timeline_visibility);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->updated_time->Exportable) $Doc->ExportCaption($this->updated_time);
				if ($this->caption->Exportable) $Doc->ExportCaption($this->caption);
				if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->source->Exportable) $Doc->ExportCaption($this->source);
				if ($this->from->Exportable) $Doc->ExportCaption($this->from);
				if ($this->to->Exportable) $Doc->ExportCaption($this->to);
				if ($this->comments->Exportable) $Doc->ExportCaption($this->comments);
				if ($this->id_grupo->Exportable) $Doc->ExportCaption($this->id_grupo);
			} else {
				if ($this->idfb_posts->Exportable) $Doc->ExportCaption($this->idfb_posts);
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->created_time->Exportable) $Doc->ExportCaption($this->created_time);
				if ($this->actions->Exportable) $Doc->ExportCaption($this->actions);
				if ($this->icon->Exportable) $Doc->ExportCaption($this->icon);
				if ($this->is_published->Exportable) $Doc->ExportCaption($this->is_published);
				if ($this->message->Exportable) $Doc->ExportCaption($this->message);
				if ($this->link->Exportable) $Doc->ExportCaption($this->link);
				if ($this->object_id->Exportable) $Doc->ExportCaption($this->object_id);
				if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
				if ($this->privacy->Exportable) $Doc->ExportCaption($this->privacy);
				if ($this->promotion_status->Exportable) $Doc->ExportCaption($this->promotion_status);
				if ($this->timeline_visibility->Exportable) $Doc->ExportCaption($this->timeline_visibility);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->updated_time->Exportable) $Doc->ExportCaption($this->updated_time);
				if ($this->caption->Exportable) $Doc->ExportCaption($this->caption);
				if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->source->Exportable) $Doc->ExportCaption($this->source);
				if ($this->from->Exportable) $Doc->ExportCaption($this->from);
				if ($this->to->Exportable) $Doc->ExportCaption($this->to);
				if ($this->id_grupo->Exportable) $Doc->ExportCaption($this->id_grupo);
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
					if ($this->idfb_posts->Exportable) $Doc->ExportField($this->idfb_posts);
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->created_time->Exportable) $Doc->ExportField($this->created_time);
					if ($this->actions->Exportable) $Doc->ExportField($this->actions);
					if ($this->icon->Exportable) $Doc->ExportField($this->icon);
					if ($this->is_published->Exportable) $Doc->ExportField($this->is_published);
					if ($this->message->Exportable) $Doc->ExportField($this->message);
					if ($this->link->Exportable) $Doc->ExportField($this->link);
					if ($this->object_id->Exportable) $Doc->ExportField($this->object_id);
					if ($this->picture->Exportable) $Doc->ExportField($this->picture);
					if ($this->privacy->Exportable) $Doc->ExportField($this->privacy);
					if ($this->promotion_status->Exportable) $Doc->ExportField($this->promotion_status);
					if ($this->timeline_visibility->Exportable) $Doc->ExportField($this->timeline_visibility);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->updated_time->Exportable) $Doc->ExportField($this->updated_time);
					if ($this->caption->Exportable) $Doc->ExportField($this->caption);
					if ($this->description->Exportable) $Doc->ExportField($this->description);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->source->Exportable) $Doc->ExportField($this->source);
					if ($this->from->Exportable) $Doc->ExportField($this->from);
					if ($this->to->Exportable) $Doc->ExportField($this->to);
					if ($this->comments->Exportable) $Doc->ExportField($this->comments);
					if ($this->id_grupo->Exportable) $Doc->ExportField($this->id_grupo);
				} else {
					if ($this->idfb_posts->Exportable) $Doc->ExportField($this->idfb_posts);
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->created_time->Exportable) $Doc->ExportField($this->created_time);
					if ($this->actions->Exportable) $Doc->ExportField($this->actions);
					if ($this->icon->Exportable) $Doc->ExportField($this->icon);
					if ($this->is_published->Exportable) $Doc->ExportField($this->is_published);
					if ($this->message->Exportable) $Doc->ExportField($this->message);
					if ($this->link->Exportable) $Doc->ExportField($this->link);
					if ($this->object_id->Exportable) $Doc->ExportField($this->object_id);
					if ($this->picture->Exportable) $Doc->ExportField($this->picture);
					if ($this->privacy->Exportable) $Doc->ExportField($this->privacy);
					if ($this->promotion_status->Exportable) $Doc->ExportField($this->promotion_status);
					if ($this->timeline_visibility->Exportable) $Doc->ExportField($this->timeline_visibility);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->updated_time->Exportable) $Doc->ExportField($this->updated_time);
					if ($this->caption->Exportable) $Doc->ExportField($this->caption);
					if ($this->description->Exportable) $Doc->ExportField($this->description);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->source->Exportable) $Doc->ExportField($this->source);
					if ($this->from->Exportable) $Doc->ExportField($this->from);
					if ($this->to->Exportable) $Doc->ExportField($this->to);
					if ($this->id_grupo->Exportable) $Doc->ExportField($this->id_grupo);
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
