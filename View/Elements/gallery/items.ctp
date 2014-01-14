<?php echo $this->Html->css(array('/ui/css/gallery.css'), null, array('inline' => false)); ?>
<?php $this->append('scripts'); ?>
<?php echo $this->Html->script(array('/ui/js/jquery-ui.js', '/ui/js/gallery.js?sbjff6'), array('inline' => true)); ?>
<?php $this->end(); ?>
<?php

$gallery_id = uniqid();

$is_edit = strpos($this->request->params['action'], 'edit') !== false;
$is_add  = strpos($this->request->params['action'], 'add')  !== false;

// esto es para que el array merge no arroje warnings si alguna variable is not an array
if(empty($options))     $options = array();
if(empty($itemModel))   $itemModel = array();
if(empty($parentModel)) $parentModel = array();
$defaults = array(
	'itemModel' => array(
		'name' => 'Image',
		'file_field' => 'image_filename', 
		'meta_fields' => array(
			'dir'      => 'image_dir',
			'mimetype' => 'image_mimetype',
			'filesize' => 'image_filesize'
		),
		'order_field' => 'order', 
		'edit_fields' => array(
			// 'title',
		),
		'create_fields' => array(
		),
	),
	'parentModel' => array(
		'id' => null,
		'foreign_key' => null,
		'name' => null,
	),
	'options' => array(
		'gallery' => $is_edit,
		'add' => $is_add,
		'legend' => __d('ui', 'Gallery'),
		'image_span' => 3,
	),
);

if(!empty($itemModel['file_field']) && empty($itemModel['meta_fields'])) {
	$prefix = substr($itemModel['file_field'], 0, strpos($itemModel['file_field'], '_'));
	$defaults['itemModel']['meta_fields'] = array(
		'dir' 		=> $prefix.'_dir',
		'size' 		=> $prefix.'_filesize',
		'mimetype' 	=> $prefix.'_mimetype',
	); 
}
if(!empty($parentModel['name']) && empty($parentModel['foreign_key'])) {
	$defaults['parentModel']['foreign_key'] = Inflector::variable($parentModel['name']) . '_id';
	if ( $is_edit && !empty($this->request->data[ $parentModel['name'] ]['id'] ) ) {
		$defaults['parentModel']['id'] = $this->request->data[   $parentModel['name']    ]['id'];
	}
}

$itemModel = array_merge($defaults['itemModel'], @$itemModel);
$parentModel = array_merge($defaults['parentModel'], @$parentModel);
$options = array_merge($defaults['options'], @$options);
?>

<fieldset class="images image-gallery">
	<?php if($options['legend']): ?>
	<legend><?php echo $options['legend'] ?></legend>
	<?php endif; ?>
<?php $j = 0; ?>
<?php if($options['gallery'] && !empty($this->request->data[ $itemModel['name'] ])): ?>
	<div style="display:none;" class="alert alert-info" id="reordering-alert"><?php echo __d('ui','Note: to make reordering effective, you must save changes'); ?></div>
	<ul class="thumbnails">
	<?php foreach($this->request->data[ $itemModel['name'] ] as $i => $item): ?>
	
	<?php $skip = (empty($item[ 'id' ]) /* || @$item[ $itemModel['file_field'] ]['error'] */ ); ?>
	<?php if($skip) continue; ?>
	
	<?php 
	$_item_classes = array('span' . $options['image_span'], 'block-image', 'block-edit-image');
	$item_classes = implode(' ', $_item_classes);
	?>
	
	<li class="<?php echo $item_classes; ?>" id="block-image-<?php echo $i; ?>">
		<div class="handle-wrapper">
			<div class="handle">::::::::</div>
		</div>
		<div class="thumbnail">
			<?php 
			$field_dir  = $itemModel['meta_fields']['dir'];
			$field_file = $itemModel[ 'file_field' ];
			$dir        = $item[ $field_dir ];
			$file       = $item[ $field_file ];
			$original_path = $dir .             '/' . $file;
			$large_path    = $dir . '/thumb/large/' . $file;
			$small_path    = $dir . '/thumb/small/' . $file;
			
			$img_opts = array();
			
			// si hay thumb muestro el thumb, sino la imagen original
			$img  = ( is_file(WWW_ROOT . $small_path) ) ? '/' . $small_path : '/' . $original_path ;
			if($img == '/'.$original_path) {
				$img_opts['style'] = 'width: 100%;'; 
			}
			// si hay large linkeo a ese, sino a la imagen original
			$link = ( is_file(WWW_ROOT . $large_path) ) ? '/' . $large_path : '/' . $original_path ;
			$link_opts = array('escape'=>false, 'rel'=>'gallery', 'class'=>'fancybox image');
			?>
			<div class="btn-group btn-image-control pull-right">
				<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i> <span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><?php echo $this->Html->link('<i class="icon-eye-open"></i> ' . __d('ui', 'View original size'), '/' . $original_path, $link_opts);?></li>
					<li><a href="#image-edit-<?php echo   $i; ?>" data-toggle="modal"><i class="icon-pencil"></i> <?php echo __d('ui','Edit') ?></a></li>
					<li><a href="#image-delete-<?php echo $i; ?>" data-toggle="modal"><i class="icon-trash" ></i> <?php echo __d('ui','Mark for deletion'); ?></a></li> 
				</ul>
			</div>			
			<?php
			
			echo $this->Html->link($this->Html->image($img, $img_opts), $link, $link_opts);
			
			//echo $this->Form->hidden($itemModel['name'].".$i.$field_file");  
			//echo $this->Form->hidden($itemModel['name'].".$i.$field_dir"); // estos los pone el element imagefield asique ni los pongo
			echo $this->Form->hidden($itemModel['name'].".$i.id"); // en cambio este no!
			echo $this->Form->hidden($itemModel['name'].".$i." . $itemModel['order_field'], array('class'=>'input-mini order', 'data-image-id'=> $item['id' ]));
			?>

			<div class="image-delete-mark hidden"><a href="#image-delete-<?php echo $i; ?>" data-toggle="modal"><i class="icon-trash"></i> <?php echo __d('ui','Marked for deletion'); ?></a></div>
		</div>
		
		
		<!-- modal para editar imagen -->
		<div class="modal hide fade" id="image-edit-<?php echo $i; ?>">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3><?php echo __d('ui','Edit Image'); ?></h3>
			</div>
			<div class="modal-body">
				<fieldset>
					<p><?php echo __d('ui','You must save changes'); ?></p>
					<?php if(!empty($itemModel['edit_fields'])): ?>
					<?php 	foreach($itemModel['edit_fields'] as $field) : ?>
					<?php 		echo $this->Form->input($itemModel['name'] . ".$i.$field"); ?>
					<?php 	endforeach; ?>
					<?php endif; ?>
					<?php echo $this->element('Ui.imagefield', array(
									'show_thumb'    => true,
									'data'		    => $item,
									'model'		    => $itemModel['name'],
									'field_name'    => $field_file,
									'fields'        => $itemModel['meta_fields'],
									'fields_prefix' => $itemModel['name'].".$i.",
									//'fields_prefix' => $i.'.',
									'label'    	    => Inflector::humanize($field_file),
					)); ?>
				</fieldset>
			</div>
			<div class="modal-footer">
				<a href="#" data-dismiss="modal" class="btn"><?php echo __d('ui','Close'); ?></a>
			</div>
		</div>


		<!-- modal para eliminar imagen -->
		<div class="modal hide fade" id="image-delete-<?php echo $i; ?>">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3><?php echo __d('ui', 'Delete Image'); ?></h3>
			</div>
			<div class="modal-body">
				<p><?php echo __d('ui','The image will be marked for deletion. This will be done only if changes are saved.'); ?></p>
				<?php 
				echo $this->Form->input($itemModel['name'].".$i.".$itemModel['file_field'].".remove", array('type'=>'checkbox','value'=>false, 'checked'=>false,'label'=>__d('ui','Mark for deletion'), 'class'=>'delete-check', 'data-image-index'=>$i)); ?>
			</div>
			<div class="modal-footer">
				<a href="#" data-dismiss="modal" class="btn"><?php echo __d('ui','Close'); ?></a>
			</div>
		</div> <!-- -->

	</li>
	<?php 
	$j = $i+1;
	endforeach; 
	?>
	</ul>
<?php endif; ?>


<!-- TODO: ver si esta parte de "agregar" no debería ser otro element aparte -->
<?php if($options['add']): ?>
	<div class="well">
		<a href="javascript:void();" class="btn" data-toggle="collapse" data-target="#gallery-add-images-<?php echo $gallery_id; ?>"><?php echo __d('ui','Add Images'); ?>…</a>
	
		<div id="gallery-add-images-<?php echo $gallery_id; ?>" class="collapse<?php if(empty($this->request->data[ $itemModel['name'] ] )){echo ' in';} ?>">
	
			<?php unset($this->request->data[ $itemModel['name'] ]); // esto es importante! sino, el template podría quizás prepopulado! ?>
	
			<!--<div class="alert alert-info">Puede agregar hasta 10 imagenes por vez. Los formatos admitidos son: jpg, png y gif, de un peso máximo de 2MB.</div>-->
			<table cellpadding="0" cellspacing="0" class="table table-bordere table-striped" id="table-add-images">
				<thead>
					<tr>
						<th>Archivo</th>
						<?php foreach($itemModel['create_fields'] as $field): ?>
						<th><?php echo __(Inflector::humanize($field)); ?></th>
						<?php endforeach; ?>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$j; $i < $j+3; $i++) { ?> 
					<tr class="block-image block-add-image">
						<?php
						echo $this->element('Ui.gallery/item-row', array(
							'itemModel' => $itemModel,
							'parentModel' => $parentModel,
							'index'=>$i,'start'=>$j,
							'gallery_id' => $gallery_id
						));
						?>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<a class="btn btn-add-image" id="btn-add-image-<?php echo $gallery_id; ?>" href="javascript:void();" data-gallery-id="<?php echo $gallery_id; ?>"><i class="icon-plus"></i> <?php echo __d('ui', 'Add another image'); ?></a>

			<table id="template-add-image-<?php echo $gallery_id; ?>" class="invisible hide">
				<tbody>
					<tr class="block-image block-add-image">
						<?php
						echo $this->element('Ui.gallery/item-row', array(
							'itemModel' => $itemModel,  
							'parentModel' => $parentModel,
							'display'=>'template',
							'gallery_id' => $gallery_id
						));
						?>
					</tr>
				</tbody>
			</table>
		</div>	
	</div>
<?php endif; ?>
</fieldset>

