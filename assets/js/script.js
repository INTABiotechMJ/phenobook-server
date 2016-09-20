$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})



// Only enable if the document has a long scroll bar
// Note the window height + offset
if ( ($(window).height() + 100) < $(document).height() ) {
	$('#top-link-block').removeClass('hidden').affix({
        // how far to scroll down before link "slides" into view
        offset: {top:100}
    });
}