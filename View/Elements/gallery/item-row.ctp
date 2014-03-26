<?php
//if(empty($model)  ) $model   = 'Image'; 
if(empty($display)) $display = 'default';
if(empty($index)  ) $index   = '0';
if(empty($gallery_id)  ) $gallery_id   = '';
$i = $index;
if($display == 'template') $i = '#'; // si es template pongo un numeral para que se reemplace por javaescríp.

?>

	<td>
		<?php echo $this->Form->input($itemModel['name'] . ".$i.". $itemModel['file_field'], array('type'=>'file', 'plugin' => false, 'label'=>false,'wrapInput' => false, 'div' => false, 'class'=>'upload-field')); ?>
		
	</td>
	<?php foreach($itemModel['create_fields'] as $field): ?>
	<td>
		<?php echo $this->Form->input($itemModel['name'] . ".$i.$field", array('wrapInput' => false,'plugin' => false, 'label' => false, 'div' => false, 'class' => $itemModel['name'].ucfirst($field) )); ?>
	</td>
	<?php endforeach; ?>
	<td>
	<?php if(!empty($parentModel['id'])): ?>
	<?php 	echo $this->Form->hidden($itemModel['name'] . ".$i." . $parentModel['foreign_key'] , array('value'=> $parentModel['id'], 'class'=>'keep-value'));
	?>
	<?php endif; ?>
	<?php echo $this->Form->hidden($itemModel['name'] . ".$i." . $itemModel['meta_fields']['dir']    /* , array('default' => $options['default_dir']) */ ); ?>
	<?php echo $this->Form->hidden($itemModel['name'] . ".$i." . $itemModel['meta_fields']['mimetype']); ?>
	<?php echo $this->Form->hidden($itemModel['name'] . ".$i." . $itemModel['meta_fields']['filesize']); ?>

	<?php if($display == 'template' || $i > $start): ?>
		<a class="btn btn-danger btn-cancel-image" href="#" data-gallery-id="<?php echo $gallery_id; ?>"><i class="icon-trash icon-white"></i></a>
	<?php endif; ?>
</td>
