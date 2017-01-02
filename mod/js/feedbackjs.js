$(document).ready(function() {
    var form = document.querySelector('form');
    var request = new XMLHttpRequest();

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        request.open("POST", "mod/php/mail.php");
        request.send(formData);

    });
    request.onreadystatechange = function() {
        $('#submit').attr('disabled', 'true');
        if (this.readyState == 4) {
            if (this.status == 200) {
                $("#content").fadeOut("slow", function() {
                    $('#sendform')[0].reset();
                    $('.md-title').html(window.location.hostname);
                    $('.md-headline').html('Ваше сообщение было успешно отправленно ;)');
                    $("#after").fadeIn("slow", function() {
                        setTimeout(function() {
                            $("#after").fadeOut("slow", function() {
                                $('#submit').removeAttr('disabled');
                                $("#content").fadeIn("slow", function() {

                                });
                            });
                        }, 3000);
                    });
                });
            } else
                $("#content").fadeOut("slow", function() {
                    $('#sendform')[0].reset();
                    $('.md-title').html(window.location.hostname);
                    $('.md-headline').html('Что-то пошло не так ;(');
                    $("#after").fadeIn("slow", function() {
                        setTimeout(function() {
                            $("#after").fadeOut("slow", function() {
                                $("#content").fadeIn("slow", function() {

                                });
                            });
                        }, 3000);
                    });
                });
        }
    };




});
