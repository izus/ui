<?php
$_defaults = array(
	'view' => true,
	'edit' => true,
	'delete' => true,
	'order' => false,
);
if(!isset($options)) $options = array();
if(!isset($id)) $id = '#';

if(isset($options['crud'])) {
	$_defaults['view'] = $options['crud'];
	$_defaults['edit'] = $options['crud'];
	$_defaults['delete'] = $options['crud'];
}
$options = array_merge($_defaults, $options); 

?>

					<div class="btn-group pull-right actions-dropdown">
						<button class="btn btn-mini btn-link dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i><span class="caret"></span></button>
						<ul class="dropdown-menu">
							<?php if($options['view']): ?>
							<li><?php echo $this->Html->link(__d('cake','View'), array('action' => 'view', $id)); ?></li>
							<?php endif; ?>
							<?php if($options['edit']): ?>
							<li><?php echo $this->Html->link(__d('cake','Edit'), array('action' => 'edit', $id)); ?></li>
							<?php endif; ?>
							<?php if($options['delete']): ?>
							<li class="divider"></li>
							<li><?php echo $this->Form->postLink(__d('cake','Delete'), array('action' => 'delete', $id), null, __('Are you sure you want to delete # %s?', $id)); ?></li>
							<?php endif; ?>
							<?php if($options['order']): ?>
							<li class="divider"></li>
							<li><?php echo $this->Html->link(__d('ui','Move Up'), array('action' => 'moveUp', $id)); ?></li>
							<li><?php echo $this->Html->link(__d('ui','Move Down'), array('action' => 'moveDown', $id)); ?></li>
							<?php endif; ?>
						</ul>
					</div>
