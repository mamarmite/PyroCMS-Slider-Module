$(function()
{
	$(".images").sortable({
		cursor: 'move',
		stop: function(event, ui) {
			var order = '';

			$('.ui-sortable div.slider_image').each(function()
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

	//set all heights to be equal to the largest height (re-usable)
	$.fn.setAllToMaxHeight = function(){
		return this.height( Math.max.apply(this, $.map( this , function(e){ return $(e).height() }) ) );
	}
	// usage: $(‘div.unevenheights’).setAllToMaxHeight()
	$('.slider_image').setAllToMaxHeight();

});