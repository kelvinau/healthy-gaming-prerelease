var MOVE_SECTION_TIME = 1000;
$(document).ready(function() {
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
    var timer;

    $('.joystick-container').hover(onMouseEnter, onMouseLeave);
    $('.joystick-container .top').mousedown(function() {
        hideAllJ();
        showJ($up_2);
        joystick_state = 3;
        
        $.fn.fullpage.moveSectionUp();
        timer = setInterval($.fn.fullpage.moveSectionUp, MOVE_SECTION_TIME);
    }).mouseup(onMouseUp); 
    $('.joystick-container .bottom').mousedown(function() {
        hideAllJ();
        showJ($down_2);
        joystick_state = 5;

        $.fn.fullpage.moveSectionDown();
        timer = setInterval($.fn.fullpage.moveSectionDown, MOVE_SECTION_TIME);
    }).mouseup(onMouseUp); 


	$('#main').fullpage({
        menu: '#menu',
        anchors:['project', 'founder', 'signup', 'faq'],
        paddingTop: '66px', // same as navbar-height
        //paddingBottom: '2rem',
        onLeave: onSectionleave,
        afterLoad: afterSectionLoad,
        afterRender: function(e) {
            $('.navbar a.nav-link[href="' + getCurrentAnchor() +'"]').parent().addClass('active');
        },
    });
    
    $(".navbar .nav-link").on("click", function(){
        $(".navbar").find(".active").removeClass("active");
        $(this).parent().addClass("active");
        
        $('.navbar-collapse').collapse('hide');
     });

    function onMouseEnter() {
        if (joystick_state === 0) {
            hideAllJ();
            showJ($idle_2);
            joystick_state = 1;
        }
    }

    function onMouseLeave() {
        //if (joystick_state === 1) {
            hideAllJ();
            showJ($idle_1);
            joystick_state = 0;
        //}
    }

    function onSectionleave(index, nextIndex, direction) {
        if (joystick_state !== 3 && joystick_state !== 5) {
            hideAllJ();
            if (direction === 'up') {
                showJ($up_1);
            }
            else if (direction === 'down') {
                showJ($down_1);
            }
        }
    }

    function afterSectionLoad() {
        if (joystick_state !== 3 && joystick_state !== 5) {
            hideAllJ();
            showJ($idle_1);
            joystick_state = 0;
        }
    }

    function onMouseUp() {
        clearInterval(timer);
        timer = null;
        hideAllJ();
        if (joystick_state === 0) {
            showJ($idle_1);
        }
        else {
            showJ($idle_2);
        }
    }

    function hideAllJ() {
       $('.joystick-container').find('.background-img').css('visibility', 'hidden');
    }

    function showJ(elem) {
        elem.css('visibility', 'visible');
    }

});

function getCurrentAnchor() {
    var arr = window.location.href.split('/');
    var anchor = arr[arr.length - 1];
    return anchor[0] !== '#' ? '#project' : anchor;
}

function signup() {
    
    $.post('ajax.php', {
        csrf_token: $('#csrf_token').val(),
        name: $('#name').val(),
        birth_year: $('#birth_year').val(),
        gender: $('#gender').val(),
        gender_others: $('#gender_others').val(),
        country: $('#country').val(),
        city: $('#city').val(),
        email: $('#email').val(),
    })
    .then(function(res) {
        var json = JSON.parse(res);
        if (json.status === 1) {
            $('.signup_btn').html('<p>' + json.msg + '</p>');
        }
        else {
            $('.error-text').text(json.msg.join(', '));
        }
    })
    return false;
}