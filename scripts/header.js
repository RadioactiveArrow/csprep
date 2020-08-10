function onScroll() {
    var scroll = window.scrollY < window.innerHeight-60;
    if (!$("body").hasClass("log") && !$("body").hasClass("quizgen")) {
        $('.header').toggleClass('transparent', scroll);
    } else {
        $('.header').toggleClass('transparent', false);
    }
    if($("body").hasClass("review")) {
        $('.header').toggleClass('transparent', true);
    }
}

$(document).ready(function () {
    if($("body").hasClass("review")) {
        $('.header').css("position","absolute");
    }
    if (!$("body").hasClass("log") && !$("body").hasClass("quizgen") || $("body").hasClass("review")) {
        $('.header').toggleClass('transparent', true);
    } else {
        $('.header').toggleClass('transparent', false);
    }
    $("#logout").on("click", function (e) {
        $.post("includes/logout.inc.php");
    });    

    var windowHeight = $(window).innerHeight();
    $('body').css({'height':windowHeight});
});

$(window).on('scroll', function () {
    var scroll = window.scrollY < 30;
    if ($("body").hasClass("parallax")) {
        if (window.screenY < 800) {
            $('.parallax').css('margin-top', $(window).scrollTop() * -.3);
            $('.header').css('margin-top', $(window).scrollTop() * .3);
        }
    }
});

document.addEventListener('scroll', function(event)
{
    var element = event.target;
});