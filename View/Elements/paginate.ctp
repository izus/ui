<?php

/*
$_defaults = array(
    "align" => "", // 'center', 'right', or empty for left alignment
    "size" => "", // 'mini', 'small', 'large' or empty for medium size
)
*/

//$mode = 'standard', 'compact', ;

$class = array('pagination');

if(!empty($align)){
	switch ($align) {
		case 'center':
			$class[] = "pagination-centered";
			break;
		case 'right':
			$class[] = "pagination-right";
			break;
	}
}
if(!empty($size)){
	switch ($size) {
		case 'mini':
			$class[] = "pagination-mini";
			break;
		case 'small':
			$class[] = "pagination-small";
			break;
		case 'large':
			$class[] = "pagination-large";
			break;
	}
}
$classes = implode(' ', $class);

$numbers = $this->Paginator->numbers(array('tag' => 'li', 'separator' => "\n\t\t"));
$numbers = str_replace('<li class="current">'.$this->Paginator->current().'</li>', '<li class="active"><a href="#">'.$this->Paginator->current().'</a></li>', $numbers);

?>

<div class="<?php echo $classes ;?>">
	<ul>
<?php 
	if($this->Paginator->hasPrev()) {
		echo "\t\t" . $this->Paginator->prev(' « ', array('tag'=>'li', 'class'=>'prev'), null, array('tag'=>'li')) . "\n";
	}else{
		echo "\t\t".'<li class="prev disabled"><a href="#"> « </a></li>'."\n";
	}

	echo "\t\t".$numbers."\n";

	if($this->Paginator->hasNext()) {
		echo "\t\t" . $this->Paginator->next(' » ', array('tag'=>'li', 'class'=>'next'), null, array('tag'=>'li')) . "\n";
	}else{
		echo "\t\t".'<li class="next disabled"><a href="#"> » </a></li>'."\n";
	}
?>
	</ul>	
	
</div>


<?php 

$mini = false;
if($mini): // TODO: implementar detección de mini-paginador 

?>

<div class="mini-pagination">
<?php 
	if($this->Paginator->hasPrev()) {
		echo "\t\t" . $this->Paginator->prev('<i class="icon-chevron-left"></i>', array('tag'=>'a', 'class'=>'btn', 'escape'=>false), null, array('tag'=>'span')) . "\n";
	}
?>
	<div class="btn-group">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
			Página <?php echo $this->Paginator->current(); ?>
			<span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<?php echo $numbers; ?>
		</ul>
	</div>
<?php

	// TODO: mejorar estilo (sacar btn dropdown?) de los numbers

	if($this->Paginator->hasNext()) {
		echo "\t\t" . $this->Paginator->next('<i class="icon-chevron-right"></i>', array('tag'=>'a', 'class'=>'btn', 'escape'=>false), null, array('tag'=>'span')) . "\n";
	}
?>	
</div>

<?php endif; ?>
