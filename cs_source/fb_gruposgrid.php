<?php include_once "ownersinfo.php" ?>
<?php

// Create page object
if (!isset($fb_grupos_grid)) $fb_grupos_grid = new cfb_grupos_grid();

// Page init
$fb_grupos_grid->Page_Init();

// Page main
$fb_grupos_grid->Page_Main();
?>
<?php if ($fb_grupos->Export == "") { ?>
<script type="text/javascript">

// Page object
var fb_grupos_grid = new ew_Page("fb_grupos_grid");
fb_grupos_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = fb_grupos_grid.PageID; // For backward compatibility

// Form object
var ffb_gruposgrid = new ew_Form("ffb_gruposgrid");

// Validate form
ffb_gruposgrid.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	var addcnt = 0;
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = (fobj.key_count) ? String(i) : "";
		var checkrow = (fobj.a_list && fobj.a_list.value == "gridinsert") ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ffb_gruposgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pais", false)) return false;
	if (ew_ValueChanged(fobj, infix, "url", false)) return false;
	return true;
}

// Form_CustomValidate event
ffb_gruposgrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_gruposgrid.ValidateRequired = true;
<?php } else { ?>
ffb_gruposgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffb_gruposgrid.Lists["x_pais"] = {"LinkField":"x_idpaises","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_admin","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($fb_grupos->CurrentAction == "gridadd") {
	if ($fb_grupos->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$fb_grupos_grid->TotalRecs = $fb_grupos->SelectRecordCount();
			$fb_grupos_grid->Recordset = $fb_grupos_grid->LoadRecordset($fb_grupos_grid->StartRec-1, $fb_grupos_grid->DisplayRecs);
		} else {
			if ($fb_grupos_grid->Recordset = $fb_grupos_grid->LoadRecordset())
				$fb_grupos_grid->TotalRecs = $fb_grupos_grid->Recordset->RecordCount();
		}
		$fb_grupos_grid->StartRec = 1;
		$fb_grupos_grid->DisplayRecs = $fb_grupos_grid->TotalRecs;
	} else {
		$fb_grupos->CurrentFilter = "0=1";
		$fb_grupos_grid->StartRec = 1;
		$fb_grupos_grid->DisplayRecs = $fb_grupos->GridAddRowCount;
	}
	$fb_grupos_grid->TotalRecs = $fb_grupos_grid->DisplayRecs;
	$fb_grupos_grid->StopRec = $fb_grupos_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$fb_grupos_grid->TotalRecs = $fb_grupos->SelectRecordCount();
	} else {
		if ($fb_grupos_grid->Recordset = $fb_grupos_grid->LoadRecordset())
			$fb_grupos_grid->TotalRecs = $fb_grupos_grid->Recordset->RecordCount();
	}
	$fb_grupos_grid->StartRec = 1;
	$fb_grupos_grid->DisplayRecs = $fb_grupos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$fb_grupos_grid->Recordset = $fb_grupos_grid->LoadRecordset($fb_grupos_grid->StartRec-1, $fb_grupos_grid->DisplayRecs);
}
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php if ($fb_grupos->CurrentMode == "add" || $fb_grupos->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($fb_grupos->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_grupos->TableCaption() ?></h4></p>
</p>
<?php $fb_grupos_grid->ShowPageHeader(); ?>
<?php
$fb_grupos_grid->ShowMessage();
?>
<div id="ffb_gruposgrid" class="ewForm">
<?php if (($fb_grupos->CurrentMode == "add" || $fb_grupos->CurrentMode == "copy" || $fb_grupos->CurrentMode == "edit") && $fb_grupos->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridUpperPanel">
<?php if ($fb_grupos->AllowAddDeleteRow) { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<span class="phpmaker">
<a href="javascript:void(0);" onclick="ew_AddGridRow(this);"><?php echo $Language->Phrase("AddBlankRow") ?></a>
</span>
<?php } ?>
<?php } ?>
</div>
<?php } ?>
<div id="gmp_fb_grupos" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<table id="tbl_fb_gruposgrid" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $fb_grupos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$fb_grupos_grid->RenderListOptions();

// Render list options (header, left)
$fb_grupos_grid->ListOptions->Render("header", "left");
?>
<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
	<?php if ($fb_grupos->SortUrl($fb_grupos->nombre) == "") { ?>
		<th><span id="elh_fb_grupos_nombre" class="fb_grupos_nombre">
		<div class="ewTableHeaderBtn"><?php echo $fb_grupos->nombre->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_grupos_nombre" class="fb_grupos_nombre">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_grupos->nombre->FldCaption() ?>
			<?php if ($fb_grupos->nombre->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_grupos->nombre->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_grupos->pais->Visible) { // pais ?>
	<?php if ($fb_grupos->SortUrl($fb_grupos->pais) == "") { ?>
		<th><span id="elh_fb_grupos_pais" class="fb_grupos_pais">
		<div class="ewTableHeaderBtn"><?php echo $fb_grupos->pais->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_grupos_pais" class="fb_grupos_pais">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_grupos->pais->FldCaption() ?>
			<?php if ($fb_grupos->pais->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_grupos->pais->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_grupos->url->Visible) { // url ?>
	<?php if ($fb_grupos->SortUrl($fb_grupos->url) == "") { ?>
		<th><span id="elh_fb_grupos_url" class="fb_grupos_url">
		<div class="ewTableHeaderBtn"><?php echo $fb_grupos->url->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_grupos_url" class="fb_grupos_url">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_grupos->url->FldCaption() ?>
			<?php if ($fb_grupos->url->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_grupos->url->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$fb_grupos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$fb_grupos_grid->StartRec = 1;
$fb_grupos_grid->StopRec = $fb_grupos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($fb_grupos->CurrentAction == "gridadd" || $fb_grupos->CurrentAction == "gridedit" || $fb_grupos->CurrentAction == "F")) {
		$fb_grupos_grid->KeyCount = $objForm->GetValue("key_count");
		$fb_grupos_grid->StopRec = $fb_grupos_grid->KeyCount;
	}
}
$fb_grupos_grid->RecCnt = $fb_grupos_grid->StartRec - 1;
if ($fb_grupos_grid->Recordset && !$fb_grupos_grid->Recordset->EOF) {
	$fb_grupos_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $fb_grupos_grid->StartRec > 1)
		$fb_grupos_grid->Recordset->Move($fb_grupos_grid->StartRec - 1);
} elseif (!$fb_grupos->AllowAddDeleteRow && $fb_grupos_grid->StopRec == 0) {
	$fb_grupos_grid->StopRec = $fb_grupos->GridAddRowCount;
}

// Initialize aggregate
$fb_grupos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$fb_grupos->ResetAttrs();
$fb_grupos_grid->RenderRow();
if ($fb_grupos->CurrentAction == "gridadd")
	$fb_grupos_grid->RowIndex = 0;
if ($fb_grupos->CurrentAction == "gridedit")
	$fb_grupos_grid->RowIndex = 0;
while ($fb_grupos_grid->RecCnt < $fb_grupos_grid->StopRec) {
	$fb_grupos_grid->RecCnt++;
	if (intval($fb_grupos_grid->RecCnt) >= intval($fb_grupos_grid->StartRec)) {
		$fb_grupos_grid->RowCnt++;
		if ($fb_grupos->CurrentAction == "gridadd" || $fb_grupos->CurrentAction == "gridedit" || $fb_grupos->CurrentAction == "F") {
			$fb_grupos_grid->RowIndex++;
			$objForm->Index = $fb_grupos_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$fb_grupos_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($fb_grupos->CurrentAction == "gridadd")
				$fb_grupos_grid->RowAction = "insert";
			else
				$fb_grupos_grid->RowAction = "";
		}

		// Set up key count
		$fb_grupos_grid->KeyCount = $fb_grupos_grid->RowIndex;

		// Init row class and style
		$fb_grupos->ResetAttrs();
		$fb_grupos->CssClass = "";
		if ($fb_grupos->CurrentAction == "gridadd") {
			if ($fb_grupos->CurrentMode == "copy") {
				$fb_grupos_grid->LoadRowValues($fb_grupos_grid->Recordset); // Load row values
				$fb_grupos_grid->SetRecordKey($fb_grupos_grid->RowOldKey, $fb_grupos_grid->Recordset); // Set old record key
			} else {
				$fb_grupos_grid->LoadDefaultValues(); // Load default values
				$fb_grupos_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($fb_grupos->CurrentAction == "gridedit") {
			$fb_grupos_grid->LoadRowValues($fb_grupos_grid->Recordset); // Load row values
		}
		$fb_grupos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($fb_grupos->CurrentAction == "gridadd") // Grid add
			$fb_grupos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($fb_grupos->CurrentAction == "gridadd" && $fb_grupos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$fb_grupos_grid->RestoreCurrentRowFormValues($fb_grupos_grid->RowIndex); // Restore form values
		if ($fb_grupos->CurrentAction == "gridedit") { // Grid edit
			if ($fb_grupos->EventCancelled) {
				$fb_grupos_grid->RestoreCurrentRowFormValues($fb_grupos_grid->RowIndex); // Restore form values
			}
			if ($fb_grupos_grid->RowAction == "insert")
				$fb_grupos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$fb_grupos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($fb_grupos->CurrentAction == "gridedit" && ($fb_grupos->RowType == EW_ROWTYPE_EDIT || $fb_grupos->RowType == EW_ROWTYPE_ADD) && $fb_grupos->EventCancelled) // Update failed
			$fb_grupos_grid->RestoreCurrentRowFormValues($fb_grupos_grid->RowIndex); // Restore form values
		if ($fb_grupos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$fb_grupos_grid->EditRowCnt++;
		if ($fb_grupos->CurrentAction == "F") // Confirm row
			$fb_grupos_grid->RestoreCurrentRowFormValues($fb_grupos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$fb_grupos->RowAttrs = array_merge($fb_grupos->RowAttrs, array('data-rowindex'=>$fb_grupos_grid->RowCnt, 'id'=>'r' . $fb_grupos_grid->RowCnt . '_fb_grupos', 'data-rowtype'=>$fb_grupos->RowType));

		// Render row
		$fb_grupos_grid->RenderRow();

		// Render list options
		$fb_grupos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($fb_grupos_grid->RowAction <> "delete" && $fb_grupos_grid->RowAction <> "insertdelete" && !($fb_grupos_grid->RowAction == "insert" && $fb_grupos->CurrentAction == "F" && $fb_grupos_grid->EmptyRow())) {
?>
	<tr<?php echo $fb_grupos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fb_grupos_grid->ListOptions->Render("body", "left", $fb_grupos_grid->RowCnt);
?>
	<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
		<td<?php echo $fb_grupos->nombre->CellAttributes() ?>><span id="el<?php echo $fb_grupos_grid->RowCnt ?>_fb_grupos_nombre" class="fb_grupos_nombre">
<?php if ($fb_grupos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" size="30" maxlength="45" value="<?php echo $fb_grupos->nombre->EditValue ?>"<?php echo $fb_grupos->nombre->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="o<?php echo $fb_grupos_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($fb_grupos->nombre->OldValue) ?>" />
<?php } ?>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" size="30" maxlength="45" value="<?php echo $fb_grupos->nombre->EditValue ?>"<?php echo $fb_grupos->nombre->EditAttributes() ?> />
<?php } ?>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_grupos->nombre->ViewAttributes() ?>>
<?php echo $fb_grupos->nombre->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($fb_grupos->nombre->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="o<?php echo $fb_grupos_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($fb_grupos->nombre->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $fb_grupos_grid->PageObjName . "_row_" . $fb_grupos_grid->RowCnt ?>"></a>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_idfb_grupos" id="x<?php echo $fb_grupos_grid->RowIndex ?>_idfb_grupos" value="<?php echo ew_HtmlEncode($fb_grupos->idfb_grupos->CurrentValue) ?>" />
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_idfb_grupos" id="o<?php echo $fb_grupos_grid->RowIndex ?>_idfb_grupos" value="<?php echo ew_HtmlEncode($fb_grupos->idfb_grupos->OldValue) ?>" />
<?php } ?>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_EDIT) { ?>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_idfb_grupos" id="x<?php echo $fb_grupos_grid->RowIndex ?>_idfb_grupos" value="<?php echo ew_HtmlEncode($fb_grupos->idfb_grupos->CurrentValue) ?>" />
<?php } ?>
	<?php if ($fb_grupos->pais->Visible) { // pais ?>
		<td<?php echo $fb_grupos->pais->CellAttributes() ?>><span id="el<?php echo $fb_grupos_grid->RowCnt ?>_fb_grupos_pais" class="fb_grupos_pais">
<?php if ($fb_grupos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($fb_grupos->pais->getSessionValue() <> "") { ?>
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->CurrentValue) ?>">
<?php } else { ?>
<select id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais"<?php echo $fb_grupos->pais->EditAttributes() ?>>
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
if (@$emptywrk) $fb_grupos->pais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaises`, `nombre` AS `DispFld`, `admin` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="s_x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($fb_grupos->pais->LookupFn) ?>&f0=<?php echo TEAencrypt("`idpaises` = {filter_value}"); ?>&t0=3" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="o<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->OldValue) ?>" />
<?php } ?>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($fb_grupos->pais->getSessionValue() <> "") { ?>
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->CurrentValue) ?>">
<?php } else { ?>
<select id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais"<?php echo $fb_grupos->pais->EditAttributes() ?>>
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
if (@$emptywrk) $fb_grupos->pais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaises`, `nombre` AS `DispFld`, `admin` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="s_x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($fb_grupos->pais->LookupFn) ?>&f0=<?php echo TEAencrypt("`idpaises` = {filter_value}"); ?>&t0=3" />
<?php } ?>
<?php } ?>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="o<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_grupos->url->Visible) { // url ?>
		<td<?php echo $fb_grupos->url->CellAttributes() ?>><span id="el<?php echo $fb_grupos_grid->RowCnt ?>_fb_grupos_url" class="fb_grupos_url">
<?php if ($fb_grupos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $fb_grupos_grid->RowIndex ?>_url" id="x<?php echo $fb_grupos_grid->RowIndex ?>_url" size="30" maxlength="245" value="<?php echo $fb_grupos->url->EditValue ?>"<?php echo $fb_grupos->url->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_url" id="o<?php echo $fb_grupos_grid->RowIndex ?>_url" value="<?php echo ew_HtmlEncode($fb_grupos->url->OldValue) ?>" />
<?php } ?>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $fb_grupos_grid->RowIndex ?>_url" id="x<?php echo $fb_grupos_grid->RowIndex ?>_url" size="30" maxlength="245" value="<?php echo $fb_grupos->url->EditValue ?>"<?php echo $fb_grupos->url->EditAttributes() ?> />
<?php } ?>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_grupos->url->ViewAttributes() ?>>
<?php echo $fb_grupos->url->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_url" id="x<?php echo $fb_grupos_grid->RowIndex ?>_url" value="<?php echo ew_HtmlEncode($fb_grupos->url->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_url" id="o<?php echo $fb_grupos_grid->RowIndex ?>_url" value="<?php echo ew_HtmlEncode($fb_grupos->url->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fb_grupos_grid->ListOptions->Render("body", "right", $fb_grupos_grid->RowCnt);
?>
	</tr>
<?php if ($fb_grupos->RowType == EW_ROWTYPE_ADD || $fb_grupos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ffb_gruposgrid.UpdateOpts(<?php echo $fb_grupos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($fb_grupos->CurrentAction <> "gridadd" || $fb_grupos->CurrentMode == "copy")
		if (!$fb_grupos_grid->Recordset->EOF) $fb_grupos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($fb_grupos->CurrentMode == "add" || $fb_grupos->CurrentMode == "copy" || $fb_grupos->CurrentMode == "edit") {
		$fb_grupos_grid->RowIndex = '$rowindex$';
		$fb_grupos_grid->LoadDefaultValues();

		// Set row properties
		$fb_grupos->ResetAttrs();
		$fb_grupos->RowAttrs = array_merge($fb_grupos->RowAttrs, array('data-rowindex'=>$fb_grupos_grid->RowIndex, 'id'=>'r0_fb_grupos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($fb_grupos->RowAttrs["class"], "ewTemplate");
		$fb_grupos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$fb_grupos_grid->RenderRow();

		// Render list options
		$fb_grupos_grid->RenderListOptions();
		$fb_grupos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $fb_grupos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fb_grupos_grid->ListOptions->Render("body", "left", $fb_grupos_grid->RowIndex);
?>
	<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
		<td><span2 id="el$rowindex$_fb_grupos_nombre" class="fb_grupos_nombre">
<?php if ($fb_grupos->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" size="30" maxlength="45" value="<?php echo $fb_grupos->nombre->EditValue ?>"<?php echo $fb_grupos->nombre->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $fb_grupos->nombre->ViewAttributes() ?>>
<?php echo $fb_grupos->nombre->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="x<?php echo $fb_grupos_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($fb_grupos->nombre->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_nombre" id="o<?php echo $fb_grupos_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($fb_grupos->nombre->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_grupos->pais->Visible) { // pais ?>
		<td><span2 id="el$rowindex$_fb_grupos_pais" class="fb_grupos_pais">
<?php if ($fb_grupos->CurrentAction <> "F") { ?>
<?php if ($fb_grupos->pais->getSessionValue() <> "") { ?>
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->CurrentValue) ?>">
<?php } else { ?>
<select id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais"<?php echo $fb_grupos->pais->EditAttributes() ?>>
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
if (@$emptywrk) $fb_grupos->pais->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `idpaises`, `nombre` AS `DispFld`, `admin` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `paises`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="s_x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($fb_grupos->pais->LookupFn) ?>&f0=<?php echo TEAencrypt("`idpaises` = {filter_value}"); ?>&t0=3" />
<?php } ?>
<?php } else { ?>
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="x<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_pais" id="o<?php echo $fb_grupos_grid->RowIndex ?>_pais" value="<?php echo ew_HtmlEncode($fb_grupos->pais->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_grupos->url->Visible) { // url ?>
		<td><span2 id="el$rowindex$_fb_grupos_url" class="fb_grupos_url">
<?php if ($fb_grupos->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $fb_grupos_grid->RowIndex ?>_url" id="x<?php echo $fb_grupos_grid->RowIndex ?>_url" size="30" maxlength="245" value="<?php echo $fb_grupos->url->EditValue ?>"<?php echo $fb_grupos->url->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $fb_grupos->url->ViewAttributes() ?>>
<?php echo $fb_grupos->url->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_grupos_grid->RowIndex ?>_url" id="x<?php echo $fb_grupos_grid->RowIndex ?>_url" value="<?php echo ew_HtmlEncode($fb_grupos->url->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_grupos_grid->RowIndex ?>_url" id="o<?php echo $fb_grupos_grid->RowIndex ?>_url" value="<?php echo ew_HtmlEncode($fb_grupos->url->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fb_grupos_grid->ListOptions->Render("body", "right", $fb_grupos_grid->RowCnt);
?>
<script type="text/javascript">
ffb_gruposgrid.UpdateOpts(<?php echo $fb_grupos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
<!--</table>-->
<?php if ($fb_grupos->CurrentMode == "add" || $fb_grupos->CurrentMode == "copy") { ?>
<input class="btn btn-large btn-success" type="submit" />
<input type="hidden" name="a_list" id="a_list" value="gridinsert" />
<input type="hidden" name="key_count" id="key_count" value="<?php echo $fb_grupos_grid->KeyCount ?>" />
<?php echo $fb_grupos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($fb_grupos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $fb_grupos_grid->KeyCount ?>" />
<?php echo $fb_grupos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($fb_grupos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="ffb_gruposgrid">
<?php

// Close recordset
if ($fb_grupos_grid->Recordset)
	$fb_grupos_grid->Recordset->Close();
?>
</div>
<?php if ($fb_grupos->Export == "") { ?>
<script type="text/javascript">
ffb_gruposgrid.Init();
</script>
<?php } ?>
<?php
$fb_grupos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$fb_grupos_grid->Page_Terminate();
$Page = &$MasterPage;
?>
