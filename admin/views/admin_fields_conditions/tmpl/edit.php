<?php
/*--------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
    __      __       _     _____                 _                                  _     __  __      _   _               _
    \ \    / /      | |   |  __ \               | |                                | |   |  \/  |    | | | |             | |
     \ \  / /_ _ ___| |_  | |  | | _____   _____| | ___  _ __  _ __ ___   ___ _ __ | |_  | \  / | ___| |_| |__   ___   __| |
      \ \/ / _` / __| __| | |  | |/ _ \ \ / / _ \ |/ _ \| '_ \| '_ ` _ \ / _ \ '_ \| __| | |\/| |/ _ \ __| '_ \ / _ \ / _` |
       \  / (_| \__ \ |_  | |__| |  __/\ V /  __/ | (_) | |_) | | | | | |  __/ | | | |_  | |  | |  __/ |_| | | | (_) | (_| |
        \/ \__,_|___/\__| |_____/ \___| \_/ \___|_|\___/| .__/|_| |_| |_|\___|_| |_|\__| |_|  |_|\___|\__|_| |_|\___/ \__,_|
                                                        | |                                                                 
                                                        |_| 				
/-------------------------------------------------------------------------------------------------------------------------------/

	@version		@update number 7 of this MVC
	@build			12th October, 2017
	@created		12th October, 2017
	@package		Component Builder
	@subpackage		edit.php
	@author			Llewellyn van der Merwe <http://vdm.bz/component-builder>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	Builds Complex Joomla Components 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$componentParams = JComponentHelper::getParams('com_componentbuilder');
?>
<script type="text/javascript">
	// waiting spinner
	var outerDiv = jQuery('body');
	jQuery('<div id="loading"></div>')
		.css("background", "rgba(255, 255, 255, .8) url('components/com_componentbuilder/assets/images/import.gif') 50% 15% no-repeat")
		.css("top", outerDiv.position().top - jQuery(window).scrollTop())
		.css("left", outerDiv.position().left - jQuery(window).scrollLeft())
		.css("width", outerDiv.width())
		.css("height", outerDiv.height())
		.css("position", "fixed")
		.css("opacity", "0.80")
		.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
		.css("filter", "alpha(opacity = 80)")
		.css("display", "none")
		.appendTo(outerDiv);
	jQuery('#loading').show();
	// when page is ready remove and show
	jQuery(window).load(function() {
		jQuery('#componentbuilder_loader').fadeIn('fast');
		jQuery('#loading').hide();
	});
</script>
<div id="componentbuilder_loader" style="display: none;">
<form action="<?php echo JRoute::_('index.php?option=com_componentbuilder&layout=edit&id='.(int) $this->item->id.$this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

	<?php echo JLayoutHelper::render('admin_fields_conditions.conditions_above', $this); ?>
<div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'admin_fields_conditionsTab', array('active' => 'conditions')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'admin_fields_conditionsTab', 'conditions', JText::_('COM_COMPONENTBUILDER_ADMIN_FIELDS_CONDITIONS_CONDITIONS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('admin_fields_conditions.conditions_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('core.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('core.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'admin_fields_conditionsTab', 'publishing', JText::_('COM_COMPONENTBUILDER_ADMIN_FIELDS_CONDITIONS_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('admin_fields_conditions.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('admin_fields_conditions.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'admin_fields_conditionsTab', 'permissions', JText::_('COM_COMPONENTBUILDER_ADMIN_FIELDS_CONDITIONS_PERMISSION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="adminform">
					<div class="adminformlist">
					<?php foreach ($this->form->getFieldset('accesscontrol') as $field): ?>
						<div>
							<?php echo $field->label; echo $field->input;?>
						</div>
						<div class="clearfix"></div>
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="admin_fields_conditions.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>
</div>
</form>
</div>

<script type="text/javascript">


<?php $numberAddconditions = range(0, count($this->item->addconditions) + 3, 1);?>

// for the values already set
jQuery(document).ready(function(){
<?php foreach($numberAddconditions as $fieldNr): ?>
	jQuery('#adminForm').on('change', '#jform_addconditions__addconditions<?php echo $fieldNr ?>__match_field',function (e) {
		e.preventDefault();
		getFieldSelectOptions(<?php echo $fieldNr ?>, "_", "_");
	});
<?php endforeach; ?>
	jQuery(document).on('subform-row-add', function(event, row){
		var groupName = jQuery(row).data('group');
		var fieldName = groupName.replace(/([0-9])/g, '');
		var fieldNr = groupName.replace(/([A-z_])/g, '');
		if ('addconditions' === fieldName) {
			jQuery('#adminForm').on('change', '#jform_addconditions_addconditions'+fieldNr+'_match_field',function (e) {
				e.preventDefault();
				getFieldSelectOptions(fieldNr, "", "");
			});
		}
	});
});
</script>
