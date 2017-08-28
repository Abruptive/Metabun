(function ($) {
	'use strict';

	/**
	 * Create the repeater setting functionality.
	 */

	if ($('.repeater').length) {

		// Bind the add and remove events to repeater buttons.
		$(document).on('click', '[data-repeater]', function (event) {

			var repeater = $(this).closest('.repeater').find('tbody');
			var item = $(this).closest('.repeater-item');
			var template = repeater.find('.repeater-template').html().replace('data-name', 'name');

			switch ($(this).data('repeater')) {
				case 'add':
					repeater.append(
						'<tr class="repeater-item">' + template + '</tr>'
					);
					break;
				case 'remove':
					item.remove();
					break;
			}

			event.preventDefault();

		});

		// Populate the repeaters with the current values.
		$('.repeater').each(function () {

			var repeater = $(this).find('tbody');
			var template = repeater.find('.repeater-template').html().replace('data-name', 'name');
			var data = JSON.parse($(this).find('.repeater-data').val().replace('"",', ''));

			for (var index in data) {
				repeater.append(
					'<tr class="repeater-item">' + template.replace('value=""', 'value="' + data[index] + '"') + '</tr>'
				);
			}

		});

	}

	/**
	 * Create the image setting functionality.
	 */

	if ($('.image').length) {

		// Bind the upload button event.
		$(document).on('click', '.image', function (event) {

			var button = $(this);
			var media = wp.media({
				title: button.data('title'),
				library: {
					type: 'image'
				},
			}).on('select', function () {

				var size = button.data( 'size' );
				var attachment = media.state().get('selection').first().toJSON();

				if( typeof attachment.sizes[size] !== 'undefined' ) {
					attachment.sizes[size].id = attachment.id;
					attachment = attachment.sizes[size];
				}

				button.removeClass('button').html( '<img src="' + attachment.url + '" width="' + attachment.width + '" height="' + attachment.height +  '" alt="' + attachment.alt + '">' );
				button.prev().show();
				button.next().val( attachment.id );

			}).open();

			return false;

		});

		// Bind the remove button event.
		$(document).on('click', '.remove', function () {

			$(this).hide().next().next().val('');
			$(this).next().addClass('button').html( $(this).next().data('title') );

			return false;

		});

	}

})(jQuery);