$(document).ready(function() {
	$('#main').fullpage({
        menu: '#menu',
        anchors:['project', 'founder', 'signup', 'faq'],
        paddingTop: '66px', // same as navbar-height
        //paddingBottom: '2rem',
        onLeave: function(e) {
            console.log(e);
        },
        afterRender: function(e) {
            $('.navbar a.nav-link[href="' + getCurrentAnchor() +'"]').parent().addClass('active');
        },
    });
    
    $(".navbar .nav-link").on("click", function(){
        $(".navbar").find(".active").removeClass("active");
        $(this).parent().addClass("active");
        
        $('.navbar-collapse').collapse('hide');
     });

    // 0 - idle_1 - idle
    // 1 - idle_2 - mouse hover
    // 2 - up_1 - wheel Up
    // 3 - up_2 - click and hold top half
    // 4 - down_1 - wheel down
    // 5 - down_2 - click and hold bottom half
    var joystick_state = 0;
    $idle_1 = $('.idle_1');
    $idle_2 = $('.idle_2');
    $up_1 = $('.up_1');
    $up_2 = $('.up_2');
    $down_1 = $('.down_1');
    $down_2 = $('.down_2');

    $('.joystick-container').hover(function() {
        if (joystick_state === 0) {
            hideAllJ();
            showJ($idle_2);
            joystick_state = 1;
        }
    }, function() {
        if (joystick_state === 1) {
            hideAllJ();
            showJ($idle_1);
            joystick_state = 0;
        }
    })
    $('body').scroll(function() {
        console.log('aa');
    });

    function hideAllJ() {
       $('.joystick-container').find('.background-img').css('visibility', 'hidden');
    }

    function showJ(elem) {
        elem.css('visibility', 'visible');
    }

    function signup() {
        console.log('aaa');
    }
});

function getCurrentAnchor() {
    var arr = window.location.href.split('/');
    var anchor = arr[arr.length - 1];
    return anchor[0] !== '#' ? '#project' : anchor;
}