<?php
if(!empty($model)) {
	$data = (empty($data[$model])) ? $data : $data[$model];
}
if(!isset($fields_prefix)) $fields_prefix = '';
if(!isset($show_thumb)) $show_thumb = true;

// $edit_mode = (!empty($data[$field_name]) && !@$data[$field_name]['error'] );
// $edit_mode = (!empty($data[$field_name]) && @$data[$field_name]['error'] != 4 );
$edit_mode = (!empty($data[$field_name]) && !is_array(@$data[$field_name]));
/*

@name: imagefield.ctp
@version: 1.0

------Como utilizarlo------

Reemplazar el campo original por el siguiente bloque de codigo

<?php echo $this->element('Ui.imagefield',
	array(
		'data'       => $this->request->data, 
		'model'      => 'News', 
		'field_name' => 'file_image', 
		'title'      => 'Imagen de Presentación'
		// optionals:
 		// 'fields' => array('dir' => 'file_image_dir', 'mimetype' => 'file_image_mimetype', 'filesize' => 'file_image_size')
 		// 'help_block' => 'Lorem ipsum dolor sit amet',
	)); 
?>
*/

// funcion para mostrar el limite de file uploads del servidor en megabytes
if (!function_exists("let_to_num2")){ 
	function let_to_num2($v){ //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
		$l = substr($v, -1);
		$ret = substr($v, 0, -1);
		switch(strtoupper($l)){
		case 'P': $ret *= 1024;
		case 'T': $ret *= 1024;
		case 'G': $ret *= 1024;
		case 'M': $ret *= 1024;
		case 'K': $ret *= 1024;
			break;
		}
		return $ret;
	}
}
$max_upload_size = min(let_to_num2(ini_get('post_max_size')), let_to_num2(ini_get('upload_max_filesize')));
$max_upload_size_in_mb = ($max_upload_size/(1024*1024))."MB.";
//$max_upload_legend = 'La aplicación ajustará el tamaño y resolución automaticamente.';
$max_upload_legend = 'Nota: El servidor no permite subir archivos que superen los '. $max_upload_size_in_mb;


if(empty($field_name)) $field_name = 'filename';

$prefix = substr($field_name, 0, strpos($field_name, '_'));

// file input options
$options['type'] = 'file';
$options['label'] = false;
$options['div'] = false;

if(!empty($title)){
	$label = $title;
}
if(!empty($help_block)){
	$options['help_block'] = $help_block . '<br><span class="muted">' . $max_upload_legend . '</span>';
}else{
	$options['help_block'] = '<span class="muted">' . $max_upload_legend . '</span>';
}

if(empty($fields)) {
	$fields = array(
		'dir' 		=> $prefix.'_dir',
		'filesize'	=> $prefix.'_filesize',
		'mimetype' 	=> $prefix.'_mimetype',
	);
}

?>
<style type="text/css">
	.form-image .controls{
		 margin-left:0;
	}			
</style>

<?php if($edit_mode): /* EDIT ATTACHMENT  */ ?>
<?php 
// $filename = ( !empty($data[$field_name]['name']) ) ? $data[$field_name]['name'] : $data[$field_name];
$filename = $data[$field_name];
?>
<?php $this->Helpers->load('Number'); ?>
<div class="control-group">
	<label for="<?php echo Inflector::camelize($field_name); ?>" class="control-label"><?php echo (empty($label)) ? __(Inflector::humanize($field_name)) : $label; ?></label>
	<div class="controls">
		
		<div class="row-fluid">
			<?php if($show_thumb): ?>
			<div class="span4 img-thumb">
				<?php echo $this->Html->link(
								$this->Html->image(
									'/'.$data[$fields['dir']].'/'. $filename,
									array('class'=>'img-polaroid')
								), 
								'/'.$data[$fields['dir']].'/'. $filename,
								array('escape' => false)
							);
				?>
			</div>
			<?php endif; ?>
			<div class="span8">
				<p class="file-name">
					<?php
					echo $filename;
					?><br>
					<span class="muted">(<?php echo $data[$fields['mimetype']];?> - <?php echo $this->Number->toReadableSize($data[$fields['filesize']]);?>)</span>
				</p>
			</div>
		</div>
		<br>

		<div class="form-image">
				<?php
				echo $this->Form->input($fields_prefix.$field_name, $options);
				echo $this->Form->hidden($fields_prefix.$fields['dir']);
				echo $this->Form->hidden($fields_prefix.$fields['filesize']);
				echo $this->Form->hidden($fields_prefix.$fields['mimetype']);
				?>
		</div>
	</div>
</div>

<?php else: /* ADD ATTACHMENT  */	?>

<div class="control-group">
	<label for="<?php echo Inflector::camelize($field_name); ?>" class="control-label"><?php echo (empty($label)) ? __(Inflector::humanize($field_name)) : $label; ?></label>
	<div class="controls">
		<div class="form-image">
				<?php
				echo $this->Form->input($fields_prefix.$field_name, $options);
				echo $this->Form->hidden($fields_prefix.$fields['dir']);
				echo $this->Form->hidden($fields_prefix.$fields['filesize']);
				echo $this->Form->hidden($fields_prefix.$fields['mimetype']);
				?>
		</div>
	</div>
</div>

<?php endif; ?>
<!-- end image element-->