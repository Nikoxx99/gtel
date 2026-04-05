jQuery(document).ready(function($){
    $(".offcanvas-body li > a").click(function () {
            var addressValue = $(this).attr("href");
            location.href=addressValue;
        });
});