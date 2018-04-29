$(document).ready(function() {
	$('#main').fullpage({
        menu: '#menu',
        anchors:['project', 'founder', 'signup', 'faq'],
        //paddingTop: '2rem',
        //paddingBottom: '2rem',
    });
    $(".navbar .nav-link").on("click", function(){
        $(".navbar").find(".active").removeClass("active");
        $(this).parent().addClass("active");
     });
});