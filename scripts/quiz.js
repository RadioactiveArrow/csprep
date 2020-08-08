getConfig();
var qID;
var answers;
var submitted = false;
$('.explain').css('visibility', 'hidden');

function select(letter) {
    document.getElementById("ans").setAttribute("value", letter);
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function getConfig(reload = true) {
    var urlData = {
        'testID': getUrlVars()['id']
    };
    $.ajax({
            type: 'POST',
            url: './includes/loadquiz.inc.php',
            data: urlData,
            dataType: 'json',
            encode: true
        })
        .done(function (data) {
            console.log(data);
            console.log(submitted);

            if (data.complete) {
                window.location.href = "scores.php";
            } else {

                $('code').html(data.qCode);
                try { //Prism fails to reload on occassion due to timing issue, this tries to reload prism twice and reloads page on repeated failure. Sue me.
                    Prism.highlightElement($('.line-numbers')[0]);
                } catch (error) {
                    try {
                        Prism.highlightElement($('.line-numbers')[0]);
                    } catch (ignored) {
                        if (reload) {
                            location.reload(false);
                        }
                    }
                }

                $('.qtext').html("<em>" + data.qNum + ") </em>" + data.qText);
                $('label.a').text(data.answers[0].text);
                $('label.b').text(data.answers[1].text);
                $('label.c').text(data.answers[2].text);
                $('label.d').text(data.answers[3].text);
                $('label.e').text(data.answers[4].text);

                qID = data.qID;
                answers = data.answers;
            }
            return data;
        });
}

function checkAns() {
    var data = {
        'ans': $('input[name=choice]').val(),
        'qID': qID,
        'options': answers
    };
    $.ajax({
            type: 'POST',
            url: './includes/checkans.inc.php',
            data: data,
            dataType: 'json',
            encode: true
        })
        .done(function (data) {
            console.log(data);
            if(data.existingEntry == true && data.complete == true) {
                window.location.href = "scores.php";
            }
            if (data.correct == true) {
                $("#" + data.answer).toggleClass("correct", true);

                $("input[type=radio]").prop("disabled", true);
                $('.explain').css('visibility', 'visible');
                $('#explain > p').text(data.explain);
                submitted = true;
            } else if (data.correct == false) {
                $("#" + data.answer).attr('name', 'radio_ans');
                $("#" + data.answer).click();
                $("#" + data.answer).toggleClass("correct", true);
                $("#" + data.choice).toggleClass("incorrect", true);

                $("input[type=radio]").prop("disabled", true);
                $('.explain').css('visibility', 'visible');
                $('#explain > p').text(data.explain);
                submitted = true;
            }
            console.log(submitted);
        });
}

function next() {
    var data = {
        'answered': submitted
    };
    $.ajax({
            type: 'POST',
            url: './includes/nextq.inc.php',
            data: data,
            dataType: 'json',
            encode: true
        })
        .done(function (data) {
            if (submitted) {
                location.reload(false);
                submitted = false;
            }
        });
}

$(document).ready(function () {
    $('.explain').css('visibility', 'hidden');
    $('input[name=sub]').click(function (event) {
        event.preventDefault();
        checkAns();
    });
    $('input[name=next]').click(function (event) {
        event.preventDefault();
        next();
    });
});