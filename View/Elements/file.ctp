<?php
// if(empty($options))

if(empty($field_name)) $field_name = 'filename';

$name = substr($field_name, 0, strpos($field_name, '_'));

if(!empty($help_block)) $options['help_block'] = $help_block;
if(!empty($label))      $options['label']      = $label;


if(empty($fields)) {
	$fields = array(
		'dir' => $name.'_dir',
		'size' => $name.'_filesize',
		'mimetype' => $name.'_mimetype',
	);	
}

$options['type'] = 'file';
?>
<div class="well">

<?php 

$file_path = '/'.$fields['dir'].'/'.$field_name;
$opts = array('alt' => '','class'=>'img-polaroid', 'style'=>'width:60px');

if(!is_file(APP.'webroot'.$file_path)) {
	echo $this->Html->image($file_path, $opts);	
}

?>

<?php
echo $this->Form->input($field_name, $options);
echo $this->Form->hidden($fields['dir']);
echo $this->Form->hidden($fields['size']);
echo $this->Form->hidden($fields['mimetype']);
?>
</div>