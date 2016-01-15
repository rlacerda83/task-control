$(document).ready(function() {

	$(document).on('click', 'a[data-confirm]', function(ev) {
        href = $(this).attr('href');
        bootbox.dialog({
            title: '<strong>'+ $(this).attr('data-title') +'</strong>',
            message: $(this).attr('data-confirm'),
            buttons: {
                success: {
                    label: "Cancel",
                    className: "btn-default"
                },
                danger: {
                    label: "Confirm",
                    className: "btn-danger",
                    callback: function() {
                        window.location.href = href;
                    }
                }
            }
        });
        return false;
    });
});

function block(message) {
	$.isLoading({
		text: (message) ? message : 'Loading...'
	});
}

function unblock() {
    $.isLoading('hide');
}