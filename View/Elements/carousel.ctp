<?php

if(!empty($alias)) {
	if(!empty($this->viewVars['slideshows'][$alias])) {
		$slides   = $this->viewVars['slideshows'][$alias]['Slide'];
		$options  = $this->viewVars['slideshows'][$alias]['Slideshow'];
	}
}
if(empty($slides))   $slides = array();
if(empty($options)) $options = array();


$field_filename = 'image_filename';
$field_dir      = 'image_dir';

$user_role = $this->Session->read('Auth.User.role');
$edit_link = $this->Html->link('<i class="icon-pencil"></i> '. __('Edit'), array('controller' => 'slideshows', 'action' => 'edit', @$options['id'], $user_role => true), array('escape' => false , 'class' => 'btn btn-mini btn-edit-block', 'title' => __('Edit Slideshow'), 'style' => 'position:absolute;top:4px;right:4px;'));

$carousel_opts = array(
	'id' => 'carousel-' . @$options['alias'],
	'data-interval' => @$options['transition_time'],
);
$carousel_classes = array('carousel', 'slide', @$options['transition_type'] );

$carousel_classes = implode(' ', $carousel_classes);

$carousel_div = $this->Html->div($carousel_classes, null, $carousel_opts);

$first = true;
?>
		<?php echo $carousel_div; ?>
			<div class="carousel-inner">
	<?php foreach($slides as $slide): ?>
				<div class="item <?php if($first) { echo 'active'; $first = false; } ?>">
					<?php
					$slide_img = '/' . $slide[ $field_dir ] . '/' . $slide[ $field_filename ];
					$item = $this->Html->image($slide_img);
					if(!empty($slide[ 'link' ])) {
						$item = $this->Html->link($item, '/'.$slide['link'], array('escape' => false,)); 
					}
					echo $item;
					?>
		<?php if(!empty($slide[ 'caption' ]) || !empty($slide[ 'title' ])) : ?>
					<div class="carousel-caption">
						<?php if(!empty($slide['title'])): ?><h4><?php echo h($slide['title']); ?></h4><?php endif; ?>
						<?php if(!empty($slide['caption'])): ?><p><?php echo h($slide['caption']); ?></p><?php endif; ?>
					</div>
		<?php endif; ?>
				</div>
	<?php endforeach; ?>
			</div>
	<?php if(@$options['controls']): ?> 
			<a class="carousel-control left"  href="#<?php echo $carousel_opts['id']; ?>" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#<?php echo $carousel_opts['id']; ?>" data-slide="next">&rsaquo;</a>
	<?php endif; ?>
			<?php
			if($this->Session->read('Auth.User.is_admin') || $user_role == 'editor'):
				echo $edit_link;
			endif; 
			?>
		</div>

