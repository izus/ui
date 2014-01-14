
function sanitize_currency(i) {
	o = i.replace(/[\,]/g,'.'); // comas a puntos
	o = o.replace(/[^0-9\.]/g,''); // saco todo lo que no es números ni puntos
	return o;
}


$(document).ready(function() {

	if($.fn.wysihtml5) {
		
		var myCustomTemplates = {
	        "emphasis": function(locale, options) {
	        	alert('aaaa');
	            var size = (options && options.size) ? ' btn-'+options.size : '';
	            return "<li>" +
	              "<div class='btn-group'>" +
	                "<a class='btn" + size + "' data-wysihtml5-command='bold' title='CTRL+B' tabindex='-1'>" + locale.emphasis.bold + "</a>" +
	                "<a class='btn" + size + "' data-wysihtml5-command='italic' title='CTRL+I' tabindex='-1'>" + locale.emphasis.italic + "</a>" +
	                "<a class='btn" + size + "' data-wysihtml5-command='underline' title='CTRL+U' tabindex='-1'>" + locale.emphasis.underline + "</a>" +
	              "</div>" +
	            "</li>";
	        }
		}

		$('textarea.rte').each(function(i, elem) {
			$(elem).wysihtml5({
				'customTemplates': 'myCustomTemplates',
				//'emphasis': true,
				'html': true,
				'color': false,
				'stylesheets': [webroot + 'ui/js/bootstrap-wysihtml5' + '/lib/css/wysiwyg-color.css'],
				'locale': 'es-AR',
			    'useLineBreaks': false,
			});
		});
	}
	
	$("*[rel=tooltip]").tooltip( {delay: { show: 500, hide: 100 }} );
	
	$("*[rel=popover]").popover( {trigger:'hover'} );

	// ui - quicksearch ----------------------------------------------
	
	$(".advanced-search-toggle").live('click', function(event) {
		var status = $(this).hasClass('active');
		if(status){
			$('#advanced-search').collapse('show');	
		}else{
			$('#advanced-search').collapse('hide');	
		}
	});
	
	$("#advanced-search .close").live('click', function(event) {
		$('#advanced-search').collapse('hide');
		$(".advanced-search-toggle").removeClass('active');
	});
		
	// dirty hack para que me muestre el chosen desplegable 
	$('#advanced-search').on('hide', function () {
		$('#advanced-search').css('overflow','hidden');
	});
	$('#advanced-search').on('shown', function () {
		$('#advanced-search').css('overflow','visible');
	});

	// Jquery chosen
	$('.chzn-select').attr('data-placeholder', 'Seleccione uno o varios items');
	$(".chzn-select").chosen(); 
	$(".chzn-select-deselect").chosen({allow_single_deselect:true}); 
	
	// Cancel buttons
	
	$('.btn-cancel-form').click(function(e) {
		e.preventDefault();
		history.back();
	});


});