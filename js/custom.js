$(document).ready(function () {

$(document).on('click' ,'.closeFeed',
function()
{
	$('.feed').css('display', 'none');
}
);

$(document).on('click', '#unbooked',
function()
{
	var book = $(this);
	var court = book.attr('c');
	var time = book.attr('t');
	
	var setDate = $('#calender').val();
	
	book.removeAttr('id');
	$.post('placebook.php', { setDate:setDate, court:court, time:time },
		function(data)
		{
			switch(data){
				case 'success':
					book.attr('id', 'bookedbyyou');
					break;
				case 'session':
					window.location.href = "login.php";
					break;
				case 'error':
					book.attr('id', 'unbooked');
					alert(data);
					break;
				case 'rating':
					book.attr('id', 'unbooked');
					alert('Your rating is low. Please consult Office');
					break;
				case 'limit':
					book.attr('id', 'unbooked');
					alert('Limit for peak time reached 17:00 - 20:00');
					break;
				case 'outofdate':
					book.attr('id', 'unbooked');
					alert('Book within 2 weeks');
					break;
				default:
					book.attr('id', 'unbooked');
					break;
			}
		}
	);
}
);

$(document).on('click', '#bookedbyyou',
function()
{
	var book = $(this);
	var court = book.attr('c');
	var time = book.attr('t');
	var setDate = $('#calender').val();
	book.removeAttr('id');
	
	$.post('removebook.php', { setDate:setDate, court:court, time:time },
		function(data)
		{
			switch(data){
				case 'success':
					book.attr('id', 'unbooked');
					break;
				default:
					book.attr('id', 'bookedbyyou');
					break;
			}
		}
	);
	
}
);

$('#calender').change(
function()
{
	$('#time').slideUp(250);
	$('body').append("<div class='feed err fixed-bottom'><span>Press Submit</span><span class='closeFeed' style='float: right;margin-right: 20px;cursor: pointer;'>X</span></div>");
}
);

});