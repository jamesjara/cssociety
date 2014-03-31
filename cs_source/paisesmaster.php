<?php

// nombre
// admin

?>
<?php if ($paises->Visible) { ?>
<small>
<table id="tbl_paisesmaster" class="ewTable ewTableSeparate table table-striped">
	<thead>
		<tr>
<?php if ($paises->nombre->Visible) { // nombre ?>
			<th class="ewTableHeader">
			<b><?php echo $paises->nombre->FldCaption() ?></b>
			</th>
<?php } ?>
<?php if ($paises->admin->Visible) { // admin ?>
			<th class="ewTableHeader">
			<b><?php echo $paises->admin->FldCaption() ?></b>
			</th>
<?php } ?>
		</tr>
	</thead>
	<tbody>
		<tr>
<?php if ($paises->nombre->Visible) { // nombre ?>
			<td<?php echo $paises->nombre->CellAttributes() ?>><span id="el_paises_nombre">
<span<?php echo $paises->nombre->ViewAttributes() ?>>
<?php echo $paises->nombre->ListViewValue() ?></span>
</span></td>
<?php } ?>
<?php if ($paises->admin->Visible) { // admin ?>
			<td<?php echo $paises->admin->CellAttributes() ?>><span id="el_paises_admin">
<span<?php echo $paises->admin->ViewAttributes() ?>>
<?php echo $paises->admin->ListViewValue() ?></span>
</span></td>
<?php } ?>
		</tr>
	</tbody>
</table></small>
<?php } ?>
