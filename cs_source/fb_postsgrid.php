<?php include_once "ownersinfo.php" ?>
<?php

// Create page object
if (!isset($fb_posts_grid)) $fb_posts_grid = new cfb_posts_grid();

// Page init
$fb_posts_grid->Page_Init();

// Page main
$fb_posts_grid->Page_Main();
?>
<?php if ($fb_posts->Export == "") { ?>
<script type="text/javascript">

// Page object
var fb_posts_grid = new ew_Page("fb_posts_grid");
fb_posts_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = fb_posts_grid.PageID; // For backward compatibility

// Form object
var ffb_postsgrid = new ew_Form("ffb_postsgrid");

// Validate form
ffb_postsgrid.Validate = function(fobj) {
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
ffb_postsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "created_time", false)) return false;
	if (ew_ValueChanged(fobj, infix, "message", false)) return false;
	if (ew_ValueChanged(fobj, infix, "link", false)) return false;
	if (ew_ValueChanged(fobj, infix, "type", false)) return false;
	if (ew_ValueChanged(fobj, infix, "caption", false)) return false;
	if (ew_ValueChanged(fobj, infix, "description", false)) return false;
	if (ew_ValueChanged(fobj, infix, "name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "source", false)) return false;
	if (ew_ValueChanged(fobj, infix, "from", false)) return false;
	return true;
}

// Form_CustomValidate event
ffb_postsgrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_postsgrid.ValidateRequired = true;
<?php } else { ?>
ffb_postsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($fb_posts->CurrentAction == "gridadd") {
	if ($fb_posts->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$fb_posts_grid->TotalRecs = $fb_posts->SelectRecordCount();
			$fb_posts_grid->Recordset = $fb_posts_grid->LoadRecordset($fb_posts_grid->StartRec-1, $fb_posts_grid->DisplayRecs);
		} else {
			if ($fb_posts_grid->Recordset = $fb_posts_grid->LoadRecordset())
				$fb_posts_grid->TotalRecs = $fb_posts_grid->Recordset->RecordCount();
		}
		$fb_posts_grid->StartRec = 1;
		$fb_posts_grid->DisplayRecs = $fb_posts_grid->TotalRecs;
	} else {
		$fb_posts->CurrentFilter = "0=1";
		$fb_posts_grid->StartRec = 1;
		$fb_posts_grid->DisplayRecs = $fb_posts->GridAddRowCount;
	}
	$fb_posts_grid->TotalRecs = $fb_posts_grid->DisplayRecs;
	$fb_posts_grid->StopRec = $fb_posts_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$fb_posts_grid->TotalRecs = $fb_posts->SelectRecordCount();
	} else {
		if ($fb_posts_grid->Recordset = $fb_posts_grid->LoadRecordset())
			$fb_posts_grid->TotalRecs = $fb_posts_grid->Recordset->RecordCount();
	}
	$fb_posts_grid->StartRec = 1;
	$fb_posts_grid->DisplayRecs = $fb_posts_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$fb_posts_grid->Recordset = $fb_posts_grid->LoadRecordset($fb_posts_grid->StartRec-1, $fb_posts_grid->DisplayRecs);
}
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php if ($fb_posts->CurrentMode == "add" || $fb_posts->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($fb_posts->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_posts->TableCaption() ?></h4></p>
</p>
<?php $fb_posts_grid->ShowPageHeader(); ?>
<?php
$fb_posts_grid->ShowMessage();
?>
<div id="ffb_postsgrid" class="ewForm">
<?php if (($fb_posts->CurrentMode == "add" || $fb_posts->CurrentMode == "copy" || $fb_posts->CurrentMode == "edit") && $fb_posts->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridUpperPanel">
</div>
<?php } ?>
<div id="gmp_fb_posts" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<table id="tbl_fb_postsgrid" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $fb_posts->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$fb_posts_grid->RenderListOptions();

// Render list options (header, left)
$fb_posts_grid->ListOptions->Render("header", "left");
?>
<?php if ($fb_posts->created_time->Visible) { // created_time ?>
	<?php if ($fb_posts->SortUrl($fb_posts->created_time) == "") { ?>
		<th><span id="elh_fb_posts_created_time" class="fb_posts_created_time">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->created_time->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_created_time" class="fb_posts_created_time">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->created_time->FldCaption() ?>
			<?php if ($fb_posts->created_time->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->created_time->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->message->Visible) { // message ?>
	<?php if ($fb_posts->SortUrl($fb_posts->message) == "") { ?>
		<th><span id="elh_fb_posts_message" class="fb_posts_message">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->message->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_message" class="fb_posts_message">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->message->FldCaption() ?>
			<?php if ($fb_posts->message->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->message->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->link->Visible) { // link ?>
	<?php if ($fb_posts->SortUrl($fb_posts->link) == "") { ?>
		<th><span id="elh_fb_posts_link" class="fb_posts_link">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->link->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_link" class="fb_posts_link">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->link->FldCaption() ?>
			<?php if ($fb_posts->link->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->link->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->type->Visible) { // type ?>
	<?php if ($fb_posts->SortUrl($fb_posts->type) == "") { ?>
		<th><span id="elh_fb_posts_type" class="fb_posts_type">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->type->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_type" class="fb_posts_type">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->type->FldCaption() ?>
			<?php if ($fb_posts->type->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->type->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->caption->Visible) { // caption ?>
	<?php if ($fb_posts->SortUrl($fb_posts->caption) == "") { ?>
		<th><span id="elh_fb_posts_caption" class="fb_posts_caption">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->caption->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_caption" class="fb_posts_caption">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->caption->FldCaption() ?>
			<?php if ($fb_posts->caption->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->caption->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->description->Visible) { // description ?>
	<?php if ($fb_posts->SortUrl($fb_posts->description) == "") { ?>
		<th><span id="elh_fb_posts_description" class="fb_posts_description">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->description->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_description" class="fb_posts_description">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->description->FldCaption() ?>
			<?php if ($fb_posts->description->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->description->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->name->Visible) { // name ?>
	<?php if ($fb_posts->SortUrl($fb_posts->name) == "") { ?>
		<th><span id="elh_fb_posts_name" class="fb_posts_name">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->name->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_name" class="fb_posts_name">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->name->FldCaption() ?>
			<?php if ($fb_posts->name->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->name->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->source->Visible) { // source ?>
	<?php if ($fb_posts->SortUrl($fb_posts->source) == "") { ?>
		<th><span id="elh_fb_posts_source" class="fb_posts_source">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->source->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_source" class="fb_posts_source">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->source->FldCaption() ?>
			<?php if ($fb_posts->source->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->source->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_posts->from->Visible) { // from ?>
	<?php if ($fb_posts->SortUrl($fb_posts->from) == "") { ?>
		<th><span id="elh_fb_posts_from" class="fb_posts_from">
		<div class="ewTableHeaderBtn"><?php echo $fb_posts->from->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_fb_posts_from" class="fb_posts_from">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_posts->from->FldCaption() ?>
			<?php if ($fb_posts->from->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_posts->from->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$fb_posts_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$fb_posts_grid->StartRec = 1;
$fb_posts_grid->StopRec = $fb_posts_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($fb_posts->CurrentAction == "gridadd" || $fb_posts->CurrentAction == "gridedit" || $fb_posts->CurrentAction == "F")) {
		$fb_posts_grid->KeyCount = $objForm->GetValue("key_count");
		$fb_posts_grid->StopRec = $fb_posts_grid->KeyCount;
	}
}
$fb_posts_grid->RecCnt = $fb_posts_grid->StartRec - 1;
if ($fb_posts_grid->Recordset && !$fb_posts_grid->Recordset->EOF) {
	$fb_posts_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $fb_posts_grid->StartRec > 1)
		$fb_posts_grid->Recordset->Move($fb_posts_grid->StartRec - 1);
} elseif (!$fb_posts->AllowAddDeleteRow && $fb_posts_grid->StopRec == 0) {
	$fb_posts_grid->StopRec = $fb_posts->GridAddRowCount;
}

// Initialize aggregate
$fb_posts->RowType = EW_ROWTYPE_AGGREGATEINIT;
$fb_posts->ResetAttrs();
$fb_posts_grid->RenderRow();
if ($fb_posts->CurrentAction == "gridadd")
	$fb_posts_grid->RowIndex = 0;
if ($fb_posts->CurrentAction == "gridedit")
	$fb_posts_grid->RowIndex = 0;
while ($fb_posts_grid->RecCnt < $fb_posts_grid->StopRec) {
	$fb_posts_grid->RecCnt++;
	if (intval($fb_posts_grid->RecCnt) >= intval($fb_posts_grid->StartRec)) {
		$fb_posts_grid->RowCnt++;
		if ($fb_posts->CurrentAction == "gridadd" || $fb_posts->CurrentAction == "gridedit" || $fb_posts->CurrentAction == "F") {
			$fb_posts_grid->RowIndex++;
			$objForm->Index = $fb_posts_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$fb_posts_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($fb_posts->CurrentAction == "gridadd")
				$fb_posts_grid->RowAction = "insert";
			else
				$fb_posts_grid->RowAction = "";
		}

		// Set up key count
		$fb_posts_grid->KeyCount = $fb_posts_grid->RowIndex;

		// Init row class and style
		$fb_posts->ResetAttrs();
		$fb_posts->CssClass = "";
		if ($fb_posts->CurrentAction == "gridadd") {
			if ($fb_posts->CurrentMode == "copy") {
				$fb_posts_grid->LoadRowValues($fb_posts_grid->Recordset); // Load row values
				$fb_posts_grid->SetRecordKey($fb_posts_grid->RowOldKey, $fb_posts_grid->Recordset); // Set old record key
			} else {
				$fb_posts_grid->LoadDefaultValues(); // Load default values
				$fb_posts_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($fb_posts->CurrentAction == "gridedit") {
			$fb_posts_grid->LoadRowValues($fb_posts_grid->Recordset); // Load row values
		}
		$fb_posts->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($fb_posts->CurrentAction == "gridadd") // Grid add
			$fb_posts->RowType = EW_ROWTYPE_ADD; // Render add
		if ($fb_posts->CurrentAction == "gridadd" && $fb_posts->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$fb_posts_grid->RestoreCurrentRowFormValues($fb_posts_grid->RowIndex); // Restore form values
		if ($fb_posts->CurrentAction == "gridedit") { // Grid edit
			if ($fb_posts->EventCancelled) {
				$fb_posts_grid->RestoreCurrentRowFormValues($fb_posts_grid->RowIndex); // Restore form values
			}
			if ($fb_posts_grid->RowAction == "insert")
				$fb_posts->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$fb_posts->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($fb_posts->CurrentAction == "gridedit" && ($fb_posts->RowType == EW_ROWTYPE_EDIT || $fb_posts->RowType == EW_ROWTYPE_ADD) && $fb_posts->EventCancelled) // Update failed
			$fb_posts_grid->RestoreCurrentRowFormValues($fb_posts_grid->RowIndex); // Restore form values
		if ($fb_posts->RowType == EW_ROWTYPE_EDIT) // Edit row
			$fb_posts_grid->EditRowCnt++;
		if ($fb_posts->CurrentAction == "F") // Confirm row
			$fb_posts_grid->RestoreCurrentRowFormValues($fb_posts_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$fb_posts->RowAttrs = array_merge($fb_posts->RowAttrs, array('data-rowindex'=>$fb_posts_grid->RowCnt, 'id'=>'r' . $fb_posts_grid->RowCnt . '_fb_posts', 'data-rowtype'=>$fb_posts->RowType));

		// Render row
		$fb_posts_grid->RenderRow();

		// Render list options
		$fb_posts_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($fb_posts_grid->RowAction <> "delete" && $fb_posts_grid->RowAction <> "insertdelete" && !($fb_posts_grid->RowAction == "insert" && $fb_posts->CurrentAction == "F" && $fb_posts_grid->EmptyRow())) {
?>
	<tr<?php echo $fb_posts->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fb_posts_grid->ListOptions->Render("body", "left", $fb_posts_grid->RowCnt);
?>
	<?php if ($fb_posts->created_time->Visible) { // created_time ?>
		<td<?php echo $fb_posts->created_time->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_created_time" class="fb_posts_created_time">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" size="30" maxlength="245" value="<?php echo $fb_posts->created_time->EditValue ?>"<?php echo $fb_posts->created_time->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="o<?php echo $fb_posts_grid->RowIndex ?>_created_time" value="<?php echo ew_HtmlEncode($fb_posts->created_time->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" size="30" maxlength="245" value="<?php echo $fb_posts->created_time->EditValue ?>"<?php echo $fb_posts->created_time->EditAttributes() ?> />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->created_time->ViewAttributes() ?>>
<?php echo $fb_posts->created_time->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" value="<?php echo ew_HtmlEncode($fb_posts->created_time->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="o<?php echo $fb_posts_grid->RowIndex ?>_created_time" value="<?php echo ew_HtmlEncode($fb_posts->created_time->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $fb_posts_grid->PageObjName . "_row_" . $fb_posts_grid->RowCnt ?>"></a>
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_idfb_posts" id="x<?php echo $fb_posts_grid->RowIndex ?>_idfb_posts" value="<?php echo ew_HtmlEncode($fb_posts->idfb_posts->CurrentValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_idfb_posts" id="o<?php echo $fb_posts_grid->RowIndex ?>_idfb_posts" value="<?php echo ew_HtmlEncode($fb_posts->idfb_posts->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { ?>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_idfb_posts" id="x<?php echo $fb_posts_grid->RowIndex ?>_idfb_posts" value="<?php echo ew_HtmlEncode($fb_posts->idfb_posts->CurrentValue) ?>" />
<?php } ?>
	<?php if ($fb_posts->message->Visible) { // message ?>
		<td<?php echo $fb_posts->message->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_message" class="fb_posts_message">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_message" id="x<?php echo $fb_posts_grid->RowIndex ?>_message" cols="35" rows="4"<?php echo $fb_posts->message->EditAttributes() ?>><?php echo $fb_posts->message->EditValue ?></textarea>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_message" id="o<?php echo $fb_posts_grid->RowIndex ?>_message" value="<?php echo ew_HtmlEncode($fb_posts->message->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_message" id="x<?php echo $fb_posts_grid->RowIndex ?>_message" cols="35" rows="4"<?php echo $fb_posts->message->EditAttributes() ?>><?php echo $fb_posts->message->EditValue ?></textarea>
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->message->ViewAttributes() ?>>
<?php echo $fb_posts->message->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_message" id="x<?php echo $fb_posts_grid->RowIndex ?>_message" value="<?php echo ew_HtmlEncode($fb_posts->message->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_message" id="o<?php echo $fb_posts_grid->RowIndex ?>_message" value="<?php echo ew_HtmlEncode($fb_posts->message->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->link->Visible) { // link ?>
		<td<?php echo $fb_posts->link->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_link" class="fb_posts_link">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_link" id="x<?php echo $fb_posts_grid->RowIndex ?>_link" size="30" maxlength="245" value="<?php echo $fb_posts->link->EditValue ?>"<?php echo $fb_posts->link->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_link" id="o<?php echo $fb_posts_grid->RowIndex ?>_link" value="<?php echo ew_HtmlEncode($fb_posts->link->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_link" id="x<?php echo $fb_posts_grid->RowIndex ?>_link" size="30" maxlength="245" value="<?php echo $fb_posts->link->EditValue ?>"<?php echo $fb_posts->link->EditAttributes() ?> />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->link->ViewAttributes() ?>>
<?php echo $fb_posts->link->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_link" id="x<?php echo $fb_posts_grid->RowIndex ?>_link" value="<?php echo ew_HtmlEncode($fb_posts->link->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_link" id="o<?php echo $fb_posts_grid->RowIndex ?>_link" value="<?php echo ew_HtmlEncode($fb_posts->link->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->type->Visible) { // type ?>
		<td<?php echo $fb_posts->type->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_type" class="fb_posts_type">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_type" id="x<?php echo $fb_posts_grid->RowIndex ?>_type" size="30" maxlength="245" value="<?php echo $fb_posts->type->EditValue ?>"<?php echo $fb_posts->type->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_type" id="o<?php echo $fb_posts_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($fb_posts->type->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_type" id="x<?php echo $fb_posts_grid->RowIndex ?>_type" size="30" maxlength="245" value="<?php echo $fb_posts->type->EditValue ?>"<?php echo $fb_posts->type->EditAttributes() ?> />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->type->ViewAttributes() ?>>
<?php echo $fb_posts->type->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_type" id="x<?php echo $fb_posts_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($fb_posts->type->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_type" id="o<?php echo $fb_posts_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($fb_posts->type->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->caption->Visible) { // caption ?>
		<td<?php echo $fb_posts->caption->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_caption" class="fb_posts_caption">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_caption" id="x<?php echo $fb_posts_grid->RowIndex ?>_caption" cols="35" rows="4"<?php echo $fb_posts->caption->EditAttributes() ?>><?php echo $fb_posts->caption->EditValue ?></textarea>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_caption" id="o<?php echo $fb_posts_grid->RowIndex ?>_caption" value="<?php echo ew_HtmlEncode($fb_posts->caption->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_caption" id="x<?php echo $fb_posts_grid->RowIndex ?>_caption" cols="35" rows="4"<?php echo $fb_posts->caption->EditAttributes() ?>><?php echo $fb_posts->caption->EditValue ?></textarea>
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->caption->ViewAttributes() ?>>
<?php echo $fb_posts->caption->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_caption" id="x<?php echo $fb_posts_grid->RowIndex ?>_caption" value="<?php echo ew_HtmlEncode($fb_posts->caption->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_caption" id="o<?php echo $fb_posts_grid->RowIndex ?>_caption" value="<?php echo ew_HtmlEncode($fb_posts->caption->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->description->Visible) { // description ?>
		<td<?php echo $fb_posts->description->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_description" class="fb_posts_description">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_description" id="x<?php echo $fb_posts_grid->RowIndex ?>_description" cols="35" rows="4"<?php echo $fb_posts->description->EditAttributes() ?>><?php echo $fb_posts->description->EditValue ?></textarea>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_description" id="o<?php echo $fb_posts_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($fb_posts->description->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_description" id="x<?php echo $fb_posts_grid->RowIndex ?>_description" cols="35" rows="4"<?php echo $fb_posts->description->EditAttributes() ?>><?php echo $fb_posts->description->EditValue ?></textarea>
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->description->ViewAttributes() ?>>
<?php echo $fb_posts->description->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_description" id="x<?php echo $fb_posts_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($fb_posts->description->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_description" id="o<?php echo $fb_posts_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($fb_posts->description->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->name->Visible) { // name ?>
		<td<?php echo $fb_posts->name->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_name" class="fb_posts_name">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_name" id="x<?php echo $fb_posts_grid->RowIndex ?>_name" cols="35" rows="4"<?php echo $fb_posts->name->EditAttributes() ?>><?php echo $fb_posts->name->EditValue ?></textarea>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_name" id="o<?php echo $fb_posts_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($fb_posts->name->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_name" id="x<?php echo $fb_posts_grid->RowIndex ?>_name" cols="35" rows="4"<?php echo $fb_posts->name->EditAttributes() ?>><?php echo $fb_posts->name->EditValue ?></textarea>
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->name->ViewAttributes() ?>>
<?php echo $fb_posts->name->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_name" id="x<?php echo $fb_posts_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($fb_posts->name->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_name" id="o<?php echo $fb_posts_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($fb_posts->name->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->source->Visible) { // source ?>
		<td<?php echo $fb_posts->source->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_source" class="fb_posts_source">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_source" id="x<?php echo $fb_posts_grid->RowIndex ?>_source" cols="35" rows="4"<?php echo $fb_posts->source->EditAttributes() ?>><?php echo $fb_posts->source->EditValue ?></textarea>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_source" id="o<?php echo $fb_posts_grid->RowIndex ?>_source" value="<?php echo ew_HtmlEncode($fb_posts->source->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_source" id="x<?php echo $fb_posts_grid->RowIndex ?>_source" cols="35" rows="4"<?php echo $fb_posts->source->EditAttributes() ?>><?php echo $fb_posts->source->EditValue ?></textarea>
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->source->ViewAttributes() ?>>
<?php echo $fb_posts->source->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_source" id="x<?php echo $fb_posts_grid->RowIndex ?>_source" value="<?php echo ew_HtmlEncode($fb_posts->source->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_source" id="o<?php echo $fb_posts_grid->RowIndex ?>_source" value="<?php echo ew_HtmlEncode($fb_posts->source->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($fb_posts->from->Visible) { // from ?>
		<td<?php echo $fb_posts->from->CellAttributes() ?>><span id="el<?php echo $fb_posts_grid->RowCnt ?>_fb_posts_from" class="fb_posts_from">
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_from" id="x<?php echo $fb_posts_grid->RowIndex ?>_from" cols="35" rows="4"<?php echo $fb_posts->from->EditAttributes() ?>><?php echo $fb_posts->from->EditValue ?></textarea>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_from" id="o<?php echo $fb_posts_grid->RowIndex ?>_from" value="<?php echo ew_HtmlEncode($fb_posts->from->OldValue) ?>" />
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_from" id="x<?php echo $fb_posts_grid->RowIndex ?>_from" cols="35" rows="4"<?php echo $fb_posts->from->EditAttributes() ?>><?php echo $fb_posts->from->EditValue ?></textarea>
<?php } ?>
<?php if ($fb_posts->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fb_posts->from->ViewAttributes() ?>>
<?php echo $fb_posts->from->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_from" id="x<?php echo $fb_posts_grid->RowIndex ?>_from" value="<?php echo ew_HtmlEncode($fb_posts->from->FormValue) ?>" />
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_from" id="o<?php echo $fb_posts_grid->RowIndex ?>_from" value="<?php echo ew_HtmlEncode($fb_posts->from->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fb_posts_grid->ListOptions->Render("body", "right", $fb_posts_grid->RowCnt);
?>
	</tr>
<?php if ($fb_posts->RowType == EW_ROWTYPE_ADD || $fb_posts->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ffb_postsgrid.UpdateOpts(<?php echo $fb_posts_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($fb_posts->CurrentAction <> "gridadd" || $fb_posts->CurrentMode == "copy")
		if (!$fb_posts_grid->Recordset->EOF) $fb_posts_grid->Recordset->MoveNext();
}
?>
<?php
	if ($fb_posts->CurrentMode == "add" || $fb_posts->CurrentMode == "copy" || $fb_posts->CurrentMode == "edit") {
		$fb_posts_grid->RowIndex = '$rowindex$';
		$fb_posts_grid->LoadDefaultValues();

		// Set row properties
		$fb_posts->ResetAttrs();
		$fb_posts->RowAttrs = array_merge($fb_posts->RowAttrs, array('data-rowindex'=>$fb_posts_grid->RowIndex, 'id'=>'r0_fb_posts', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($fb_posts->RowAttrs["class"], "ewTemplate");
		$fb_posts->RowType = EW_ROWTYPE_ADD;

		// Render row
		$fb_posts_grid->RenderRow();

		// Render list options
		$fb_posts_grid->RenderListOptions();
		$fb_posts_grid->StartRowCnt = 0;
?>
	<tr<?php echo $fb_posts->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fb_posts_grid->ListOptions->Render("body", "left", $fb_posts_grid->RowIndex);
?>
	<?php if ($fb_posts->created_time->Visible) { // created_time ?>
		<td><span2 id="el$rowindex$_fb_posts_created_time" class="fb_posts_created_time">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" size="30" maxlength="245" value="<?php echo $fb_posts->created_time->EditValue ?>"<?php echo $fb_posts->created_time->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $fb_posts->created_time->ViewAttributes() ?>>
<?php echo $fb_posts->created_time->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="x<?php echo $fb_posts_grid->RowIndex ?>_created_time" value="<?php echo ew_HtmlEncode($fb_posts->created_time->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_created_time" id="o<?php echo $fb_posts_grid->RowIndex ?>_created_time" value="<?php echo ew_HtmlEncode($fb_posts->created_time->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->message->Visible) { // message ?>
		<td><span2 id="el$rowindex$_fb_posts_message" class="fb_posts_message">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_message" id="x<?php echo $fb_posts_grid->RowIndex ?>_message" cols="35" rows="4"<?php echo $fb_posts->message->EditAttributes() ?>><?php echo $fb_posts->message->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $fb_posts->message->ViewAttributes() ?>>
<?php echo $fb_posts->message->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_message" id="x<?php echo $fb_posts_grid->RowIndex ?>_message" value="<?php echo ew_HtmlEncode($fb_posts->message->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_message" id="o<?php echo $fb_posts_grid->RowIndex ?>_message" value="<?php echo ew_HtmlEncode($fb_posts->message->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->link->Visible) { // link ?>
		<td><span2 id="el$rowindex$_fb_posts_link" class="fb_posts_link">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_link" id="x<?php echo $fb_posts_grid->RowIndex ?>_link" size="30" maxlength="245" value="<?php echo $fb_posts->link->EditValue ?>"<?php echo $fb_posts->link->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $fb_posts->link->ViewAttributes() ?>>
<?php echo $fb_posts->link->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_link" id="x<?php echo $fb_posts_grid->RowIndex ?>_link" value="<?php echo ew_HtmlEncode($fb_posts->link->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_link" id="o<?php echo $fb_posts_grid->RowIndex ?>_link" value="<?php echo ew_HtmlEncode($fb_posts->link->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->type->Visible) { // type ?>
		<td><span2 id="el$rowindex$_fb_posts_type" class="fb_posts_type">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $fb_posts_grid->RowIndex ?>_type" id="x<?php echo $fb_posts_grid->RowIndex ?>_type" size="30" maxlength="245" value="<?php echo $fb_posts->type->EditValue ?>"<?php echo $fb_posts->type->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $fb_posts->type->ViewAttributes() ?>>
<?php echo $fb_posts->type->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_type" id="x<?php echo $fb_posts_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($fb_posts->type->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_type" id="o<?php echo $fb_posts_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($fb_posts->type->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->caption->Visible) { // caption ?>
		<td><span2 id="el$rowindex$_fb_posts_caption" class="fb_posts_caption">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_caption" id="x<?php echo $fb_posts_grid->RowIndex ?>_caption" cols="35" rows="4"<?php echo $fb_posts->caption->EditAttributes() ?>><?php echo $fb_posts->caption->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $fb_posts->caption->ViewAttributes() ?>>
<?php echo $fb_posts->caption->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_caption" id="x<?php echo $fb_posts_grid->RowIndex ?>_caption" value="<?php echo ew_HtmlEncode($fb_posts->caption->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_caption" id="o<?php echo $fb_posts_grid->RowIndex ?>_caption" value="<?php echo ew_HtmlEncode($fb_posts->caption->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->description->Visible) { // description ?>
		<td><span2 id="el$rowindex$_fb_posts_description" class="fb_posts_description">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_description" id="x<?php echo $fb_posts_grid->RowIndex ?>_description" cols="35" rows="4"<?php echo $fb_posts->description->EditAttributes() ?>><?php echo $fb_posts->description->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $fb_posts->description->ViewAttributes() ?>>
<?php echo $fb_posts->description->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_description" id="x<?php echo $fb_posts_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($fb_posts->description->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_description" id="o<?php echo $fb_posts_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($fb_posts->description->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->name->Visible) { // name ?>
		<td><span2 id="el$rowindex$_fb_posts_name" class="fb_posts_name">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_name" id="x<?php echo $fb_posts_grid->RowIndex ?>_name" cols="35" rows="4"<?php echo $fb_posts->name->EditAttributes() ?>><?php echo $fb_posts->name->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $fb_posts->name->ViewAttributes() ?>>
<?php echo $fb_posts->name->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_name" id="x<?php echo $fb_posts_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($fb_posts->name->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_name" id="o<?php echo $fb_posts_grid->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($fb_posts->name->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->source->Visible) { // source ?>
		<td><span2 id="el$rowindex$_fb_posts_source" class="fb_posts_source">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_source" id="x<?php echo $fb_posts_grid->RowIndex ?>_source" cols="35" rows="4"<?php echo $fb_posts->source->EditAttributes() ?>><?php echo $fb_posts->source->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $fb_posts->source->ViewAttributes() ?>>
<?php echo $fb_posts->source->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_source" id="x<?php echo $fb_posts_grid->RowIndex ?>_source" value="<?php echo ew_HtmlEncode($fb_posts->source->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_source" id="o<?php echo $fb_posts_grid->RowIndex ?>_source" value="<?php echo ew_HtmlEncode($fb_posts->source->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($fb_posts->from->Visible) { // from ?>
		<td><span2 id="el$rowindex$_fb_posts_from" class="fb_posts_from">
<?php if ($fb_posts->CurrentAction <> "F") { ?>
<textarea name="x<?php echo $fb_posts_grid->RowIndex ?>_from" id="x<?php echo $fb_posts_grid->RowIndex ?>_from" cols="35" rows="4"<?php echo $fb_posts->from->EditAttributes() ?>><?php echo $fb_posts->from->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $fb_posts->from->ViewAttributes() ?>>
<?php echo $fb_posts->from->ViewValue ?></span>
<input type="hidden" name="x<?php echo $fb_posts_grid->RowIndex ?>_from" id="x<?php echo $fb_posts_grid->RowIndex ?>_from" value="<?php echo ew_HtmlEncode($fb_posts->from->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $fb_posts_grid->RowIndex ?>_from" id="o<?php echo $fb_posts_grid->RowIndex ?>_from" value="<?php echo ew_HtmlEncode($fb_posts->from->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fb_posts_grid->ListOptions->Render("body", "right", $fb_posts_grid->RowCnt);
?>
<script type="text/javascript">
ffb_postsgrid.UpdateOpts(<?php echo $fb_posts_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
<!--</table>-->
<?php if ($fb_posts->CurrentMode == "add" || $fb_posts->CurrentMode == "copy") { ?>
<input class="btn btn-large btn-success" type="submit" />
<input type="hidden" name="a_list" id="a_list" value="gridinsert" />
<input type="hidden" name="key_count" id="key_count" value="<?php echo $fb_posts_grid->KeyCount ?>" />
<?php echo $fb_posts_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($fb_posts->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $fb_posts_grid->KeyCount ?>" />
<?php echo $fb_posts_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($fb_posts->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="ffb_postsgrid">
<?php

// Close recordset
if ($fb_posts_grid->Recordset)
	$fb_posts_grid->Recordset->Close();
?>
</div>
<?php if ($fb_posts->Export == "") { ?>
<script type="text/javascript">
ffb_postsgrid.Init();
</script>
<?php } ?>
<?php
$fb_posts_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$fb_posts_grid->Page_Terminate();
$Page = &$MasterPage;
?>
