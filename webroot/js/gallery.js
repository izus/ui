
var max_images     =  10;
var max_images_msg = 'Límite de imágenes permitidas alcanzado';
var debug          = false;

function block_count(gallery_id) {
	gallery_id = (typeof gallery_id === "undefined") ? "" : '#gallery-add-images-'+gallery_id;

	return $(gallery_id+' .block-add-image').length -1;
}

function _field_template_fill(newElement, labelScope, newIndex) {
	// cambio name's e ID's de los elementos y sus labels para que sigan la numeración y no mantengan los del "template", o sea el bloque que se clonó
	
	var element  = $(newElement);
	
	var templateId    = element.attr('id');
	var templateName  = element.attr('name');

	var label = labelScope.find('label[for="' + templateId + '"]');
	var id    = templateId.replace  (/(\w+)(\d+|#)/,"$1" + newIndex); // Model#Field -> Model3Field
	var name  = templateName.replace(/data\[(\w+)\]\[(\d+|#)/, "data[$1][" + newIndex); // data[Model][#][field] -> data[Model][3][field]

	label.attr   ('for',  id);
	element.attr ('id',   id);
	element.attr ('name', name);
	return true;
}

function add_image_block(template, afterWhat /* TODO: parametrizar si uso insertAfter u otra cosa! */) {

	var newblock = $(template).clone();

	//var block_index = $('.block-image:visible').length;

	var formElements = 'input, select, textarea';
	newblock.find(formElements).each(function(i, element) { // recorro uno por uno los elementos clonados
		_field_template_fill(element, newblock, window.block_index);
	});

	window.block_index += 1;
	newblock.insertAfter(afterWhat);
	return true;
}

jQuery(document).ready(function() {

	window.block_index  =  $('.block-image:visible').length;
	
	////////////////////////////////////
	// Agregar imágenes
	////////////////////////////////////

	// agregar una nueva a esa lista
	$('.btn-add-image').click(function(e) {
		
		e.preventDefault();

		var gallery_id = $(this).attr('data-gallery-id');

		if(block_count(gallery_id) >= max_images) {
			alert(max_images_msg);
			// $('#btn-add-image').attr('disabled', true); // esto es molesto porque lo tengo que volver a habilitar si sacan 
			$(this).attr('disabled', true); // esto es molesto porque lo tengo que volver a habilitar si sacan 
			return false;
		}
		return add_image_block('#template-add-image-'+gallery_id+' .block-add-image', '#gallery-add-images-'+gallery_id+' .block-add-image:visible:last');
	});

	// remover imágenes de la lista de las que se van a agregar
	$('.btn-cancel-image').live('click',function(e) {
		e.preventDefault();

		$(this).closest('.block-add-image').remove(); // block_index-- ? 

		var gallery_id = $(this).attr('data-gallery-id');
		
		// reordenar todos los index: sería algo así (NO PROBADO!)
		/*$('.block-add-image').each(function(i, block){
			console.log($(block));
			$(block).find('input, select, textarea').each(function(j, element) {
				_field_template_fill(element, $(block), i);
			});
		});*/
		
		if(block_count(gallery_id) < max_images ) {
			$('#btn-add-image-' + gallery_id).attr('disabled', false);
		}
	});


	//////////////////////////////////
	// Galería
	//////////////////////////////////

	// reordenar las imágenes de la galería
	$('.image-gallery ul').sortable({
		placeholder: 'ui-selected',
		revert:      true,
		cursor:      'move',
		handle:      '.handle',
		stop:
			function(e,ui) 
			{
				$('#reordering-alert').show();
				$('.image-gallery ul li input.order').each( function(index, element) {
					$(element).val(index+1);
				});
			}
	});

	// Al cambiar el check que marca para eliminar realizo unos cambios cosméticos
	$('input.delete-check').change(function(e) {
		var image_i = $(this).data('image-index');
		var iblock = $('#block-image-'+image_i);
		var image = $('#block-image-'+image_i+' a.image img');
		var imark = $('#block-image-'+image_i+' .image-delete-mark');
		if ($(this).is(':checked')) { 
			image.fadeTo('slow',0.33);
			iblock.addClass('delete');
			imark.removeClass('hidden');
		} else {
			image.fadeTo('slow', 1);
			iblock.removeClass('delete');
			imark.addClass('hidden');
		}
	});
	
});
