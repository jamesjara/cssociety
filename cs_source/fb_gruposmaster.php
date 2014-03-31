<?php

// nombre
// pais
// url

?>
<?php if ($fb_grupos->Visible) { ?>
<small>
<table id="tbl_fb_gruposmaster" class="ewTable ewTableSeparate table table-striped">
	<thead>
		<tr>
<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
			<th class="ewTableHeader">
			<b><?php echo $fb_grupos->nombre->FldCaption() ?></b>
			</th>
<?php } ?>
<?php if ($fb_grupos->pais->Visible) { // pais ?>
			<th class="ewTableHeader">
			<b><?php echo $fb_grupos->pais->FldCaption() ?></b>
			</th>
<?php } ?>
<?php if ($fb_grupos->url->Visible) { // url ?>
			<th class="ewTableHeader">
			<b><?php echo $fb_grupos->url->FldCaption() ?></b>
			</th>
<?php } ?>
		</tr>
	</thead>
	<tbody>
		<tr>
<?php if ($fb_grupos->nombre->Visible) { // nombre ?>
			<td<?php echo $fb_grupos->nombre->CellAttributes() ?>><span id="el_fb_grupos_nombre">
<span<?php echo $fb_grupos->nombre->ViewAttributes() ?>>
<?php echo $fb_grupos->nombre->ListViewValue() ?></span>
</span></td>
<?php } ?>
<?php if ($fb_grupos->pais->Visible) { // pais ?>
			<td<?php echo $fb_grupos->pais->CellAttributes() ?>><span id="el_fb_grupos_pais">
<span<?php echo $fb_grupos->pais->ViewAttributes() ?>>
<?php echo $fb_grupos->pais->ListViewValue() ?></span>
</span></td>
<?php } ?>
<?php if ($fb_grupos->url->Visible) { // url ?>
			<td<?php echo $fb_grupos->url->CellAttributes() ?>><span id="el_fb_grupos_url">
<span<?php echo $fb_grupos->url->ViewAttributes() ?>>
<?php echo $fb_grupos->url->ListViewValue() ?></span>
</span></td>
<?php } ?>
		</tr>
	</tbody>
</table></small>
<?php } ?>
