$(function()
{
	$(".images").sortable({
		cursor: 'move',
		stop: function(event, ui) {
			var order = '';

			$('.ui-sortable div').each(function()
			{
				order = order + ',' + $(this).attr('id');
			});

			order = order.substring(1);

			$.ajax(
			{
				type: "POST",
				url: BASE_URL + 'admin/slider/reorder',
				data: { 'order': order }
			});
		}
	});
	
	$(".images").disableSelection();
});