<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>Infores Product Locator</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
				
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>Main Content Header</span></h3>
						<div class="inside">
							<form name="wpinfores_settings_form" method="post" action="">

								<input type="hidden" name="wpinfores_form_submitted" value="Y">

								<table class="form-table">
									<tr>
										<td>
											<label for="wpinfores_clientid">Enter Your Client ID</label>
										</td>
										<td>
											<input name="wpinfores_clientid" id="wpinfores_clientid" type="text" value="<?php echo $wpinfores_clientid; ?>" class="regular-text" />
										</td>
									</tr>
									<tr>
										<td>
											<label for="wpinfores_productfamilyid">Enter Your Product Family ID</label>
										</td>
										<td>
											<input name="wpinfores_productfamilyid" id="wpinfores_productfamilyid" type="text" value="<?php echo $wpinfores_productfamilyid; ?>" class="regular-text" />
										</td>
									</tr>
								</table>
								<p><input class="button-primary" type="submit" name="wpinfores_submit" value="Save" /></p>
							</form>
						</div> <!-- .inside -->
					
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- post-body-content -->
			
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				
				<div class="meta-box-sortables">
					
					<div class="postbox">
					
						<h3><span>Sidebar Content Header</span></h3>
						<div class="inside">
							Content space
						</div> <!-- .inside -->
						
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables -->
				
			</div> <!-- #postbox-container-1 .postbox-container -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->