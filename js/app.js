var MOVE_SECTION_TIME = 1000;

$(document).ready(function() {
    // 0 - idle_1 - idle
    // 1 - idle_2 - mouse hover
    // 2 - up_1 - wheel Up
    // 3 - up_2 - click and hold top half
    // 4 - down_1 - wheel down
    // 5 - down_2 - click and hold bottom half
    var joystick_state = 0;
    //$idle_1 = $('.idle_1');
    $idle_2 = $('.idle_2');
    //$up_1 = $('.up_1');
    $up_2 = $('.up_2');
    //$down_1 = $('.down_1');
    $down_2 = $('.down_2');
    var anchors = ['project', 'founder', 'signup', 'faq'];
    var timer;
    var mouseDown = false;
    var clickHandled = false;

    $('.joystick-container').hover(onMouseEnter, onMouseLeave);
    $('.joystick-container .top').on('touchstart mousedown', function(e) {
        e.stopImmediatePropagation();

        if(e.type == "touchstart") {
            clickHandled = true;
            onMouseDown(true);
        }
        else if(e.type == "mousedown" && !clickHandled) {
            onMouseDown(true);
        }
        else {
            clickHandled = false;
        }
    }).on('touchend mouseup', onMouseUp); 
    $('.joystick-container .bottom').on('touchstart mousedown', function(e) {
        e.stopImmediatePropagation();

        if(e.type == "touchstart") {
            clickHandled = true;
            onMouseDown();
        }
        else if(e.type == "mousedown" && !clickHandled) {
            onMouseDown();
        }
        else {
            clickHandled = false;
        }
    }).on('touchend mouseup', onMouseUp); 

	$('#main').fullpage({
        menu: '#menu',
        anchors: anchors,
        paddingTop: screen.width > 992 ? '66px' : '120px', // same as navbar-height
        //paddingBottom: '2rem',
        onLeave: onSectionleave,
        afterLoad: afterSectionLoad,
        responsiveWidth: 500,
        afterRender: function(e) {
            $('.navbar a.nav-link[href="' + getCurrentAnchor() +'"]').parent().addClass('active');
        },
    });
    
    $(".navbar .nav-link").on("click", function(){
       // $(".navbar").find(".active").removeClass("active");
        //$(this).parent().addClass("active");
        
        $('.navbar-collapse').collapse('hide');
    });

    setTimeout(function() {
        $('.alert-success').fadeIn(1000);
    }, 500);

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
            showJ($idle_2);
            joystick_state = 0;
        //}
    }

    function onMouseDown(isTop) {
        mouseDown = true;
        if (timer) {
            clickHandled = false;
            clearInterval(timer);
            timer = null;
        }
        if (mouseDown) {
            hideAllJ();
            if (isTop) {
                showJ($up_2);
                joystick_state = 3;
                
                $.fn.fullpage.moveSectionUp();
                timer = setInterval($.fn.fullpage.moveSectionUp, MOVE_SECTION_TIME);
            }
            else {
                showJ($down_2);
                joystick_state = 5;
        
                $.fn.fullpage.moveSectionDown();
                timer = setInterval($.fn.fullpage.moveSectionDown, MOVE_SECTION_TIME);
            }
        }
    }

    function onSectionleave(index, nextIndex, direction) {
        $(".navbar").find(".active").removeClass("active");
        $('.navbar a.nav-link[href="#' + anchors[nextIndex - 1] +'"]').parent().addClass('active');
        if (joystick_state !== 3 && joystick_state !== 5) {
            hideAllJ();
            if (direction === 'up') {
                showJ($up_2);
            }
            else if (direction === 'down') {
                showJ($down_2);
            }
        }
    }

    function afterSectionLoad() {
        if (joystick_state !== 3 && joystick_state !== 5) {
            hideAllJ();
            showJ($idle_2);
            joystick_state = 0;
        }
    }

    function onMouseUp() {
        mouseDown = false;
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
        hideAllJ();
        showJ($idle_2);
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

function gotoTop() {
    $.fn.fullpage.moveTo('project');
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
            $('.submit-container').html('<p>' + json.msg + '</p>');
        }
        else {
            var msg = $.isArray(json.msg) ? json.msg.join(', ') : json.msg;
            $('.error-msg').text(msg);
        }
    })
    return false;
}