<?php    defined('C5_EXECUTE') or die('Access Denied.');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Force SSL Settings'), false, 'span6 offset3', false) ?>

<form method="post" class="form-inline" id="jbx-force-ssl" action="<?php   echo $this->url('/dashboard/system/environment/jbx_force_ssl', 'save')?>">
	
	<div class="ccm-pane-body">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="base_url_ssl"><?php   echo t('Base URL for your secure pages')?></label>
				
				<div class="controls input-prepend">
					<span class="add-on">https://</span>
					<?php   echo $form->text('base_url_ssl', $base_url_ssl, array('class' => 'span4')) ?>
				</div>
			</div>
		</fieldset>
	</div>
	
	<div class="ccm-pane-footer">
		<?php  
			$b1 = $concrete_interface->submit(t('Save'), 'base-url-ssl', 'right', 'primary');
			print $b1;
		?>
	</div>
	
</form>

<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false)?>