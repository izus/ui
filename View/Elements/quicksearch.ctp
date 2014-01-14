<?php
$has_advanced = ($this->fetch('advanced_search'));

$controller = (empty($controller)) ? $this->params->controller : $controller;
$action     = (empty($action))     ? 'index'                   : $action;
$plugin     = (empty($plugin))     ? null                      : $plugin;
// $admin      = (empty($admin))      ? false                     : $admin;

$tooltip = isset($tooltip) ? $tooltip : __d('ui','Search');

?>
	<div class="row grid-tools-wrapper">
		<?php echo $this->Form->create($modelName, array('class'=>'form-horizontal form-ui-search', 'url' => array_merge(array('controller' => $controller, 'action' => $action, 'plugin' => $plugin, /*'admin' => $admin */), $this->params['pass']) )); ?>
		<div class="span8">
			<div class="search-toolbar">			
				<div class="input-append">
					<?php
					echo $this->Form->basic_input('search',array(
						'type' => 'text',
						'data-original-title' => $tooltip,
						'class' => 'span3',
						'rel' => 'tooltip',
						'placeholder' => __d('ui','Search'),
					)); 
					if($has_advanced): 
					?>
					<button class="btn advanced-search-toggle <?php if(@$is_advanced_filtered){ echo 'btn-warning'; }; ?>" 
							rel="tooltip" 
							title="<?php echo __d('ui','Advanced search'); ?>" 
							type="button" 
							data-toggle="button"><i class="icon-cog <?php if(@$is_advanced_filtered){ echo 'icon-white'; }; ?>"></i>
					</button>
					<?php endif; ?>
					<button class="btn" rel="tooltip" title="<?php echo __d('ui','Search'); ?>" type="submit"><i class="icon-search"></i></button>
				</div>
				<span class="help-inline"><?php 
					if(!$is_filtered){
						//echo $this->Paginator->counter('{:count}') . ' ' . __d('ui','records found') . '.';
					}else{
						echo $this->Paginator->counter('{:count}') . ' ' . __dn('ui','record found', 'records found', $this->Paginator->counter('{:count}')) . '. '. $this->Html->link(__d('ui','Remove filters'), array('action' => $action), array('class'=>'')); 
					};
				?></span>
			</div>
		</div>
		<div class="span4">
			<?php echo $this->fetch('toolbar-second'); ?>
		</div>
		<?php if($has_advanced): ?>
		<div class="span12">
			<div id="advanced-search" class="collapse">
				<div class="well">
					<legend><?php echo __d('ui','Advanced search'); ?> <a class="close" href="#">&times;</a></legend>
					<?php echo $this->fetch('advanced_search'); ?>
					<hr />
					<div class="controls">
						<button class="btn btn-large" type="submit"><i class="icon-search"></i> <?php echo __d('ui','Search'); ?></button>
						 &nbsp; <?php echo $this->Html->link(__d('ui','Remove filters'), array('action' => $action), array('class'=>'btn btn-link')); ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php echo $this->Form->end(); ?>
	</div>


