<?php 

$blocks = (empty($this->viewVars['blocks'])) ? array( ) : $this->viewVars['blocks'];

if(!isset($alias)) $alias = 'default';
if(!isset($edit_link)) $edit_link = true;
if(!isset($escape)) $escape = true;

?>

<?php
if(!empty($blocks[$alias])) : 
	$role = $this->Session->read('Auth.User.role');
	if($edit_link && ($this->Session->read('Auth.User.is_admin') || $role == 'editor')): // TODO: parametrizar condiciones para mostrar el botoncito de edit (authorizedRoles o algo así) 
		echo $this->Html->link('<i class="icon-pencil"></i> '. __d('ui','Edit'), array('controller' => 'blocks', 'action' => 'edit_by_alias', $alias, $role => true), array('class' => 'btn btn-mini btn-edit-block pull-right', 'escape' => false)); 
	endif;
	$block = ($escape) ? h($blocks[$alias]) : $blocks[$alias];
	echo $block;
endif;
?>

