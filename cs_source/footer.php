<?php if (@!$gbSkipHeaderFooter) { ?>	
			<!-- right column (end) -->
			<?php if (isset($gTimer)) $gTimer->Stop() ?>
	  <!--   </td>	
		</tr>
	</table>
	content (end) -->
<?php if (!ew_IsMobile()) { ?>
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
	<div class="ewFooterRow">	
		<div class="ewFooterText"><?php echo $Language->ProjectPhrase("FooterText") ?></div>
		<!-- Place other links, for example, disclaimer, here -->		
	</div>
	<!-- footer (end) -->	
<?php } ?>
</div>
<?php } ?>
<?php if (ew_IsMobile()) { ?>
	</div>
</div>
<?php } ?>
<div class="yui-tt" id="ewTooltipDiv" style="visibility: hidden; border: 0px;"></div>
<script type="text/javascript">
ew_Select("table." + EW_TABLE_CLASSNAME, document, ew_SetupTable); // Init tables
ew_Select("table." + EW_GRID_CLASSNAME, document, ew_SetupGrid); // Init grids
ew_InitTooltipDiv(); // init tooltip div
</script>
<script type="text/javascript">

// Write your global startup script here
// document.write("page loaded");    

 $('a.operacion').live('click', function() {
	var tipo    =  this.name    ;    
	var id      =  this.id      ;                    
	var dialog  =  $("#dialog");          
	if ($("#dialog").length == 0) {                                         
		dialog = $('<div id="dialog" style="background-color: red;position: fixed;top: 0%;width: 100%;height: 100%;text-align: center;padding-top: 10%;">123</div>').appendTo('body');
	}                                                                                                                         
	$.ajax({                                                                                                            
			url: 'operaciones.php',                                   
			data: { tipo : tipo , id: id },            
			success: function(msg) {          
			   dialog.html( msg );    

			   //Unblock all           
			},                                        
			failure: function() {
			   dialog.html(" Error ...");   

			   //Unblock all   
			},  
			beforeSend: function (thisXHR) {
			   dialog.html(" ... Espere, mientras se realiza la operacion ...");     

			   //Block everything page
			}                                                              
	});                                   
	return false;  
}); 
</script>
	<!-- footer (begin) --><!-- *** Note: Only licensed users are allowed to remove or change the following copyright statement. *** -->
<!-- *** Remove comment lines to show footer for mobile
	<div data-role="footer">
		<h4>&nbsp;<?php echo $Language->ProjectPhrase("FooterText") ?></h4>
	</div>
*** -->
	<!-- footer (end) -->	
</body>
</html>
