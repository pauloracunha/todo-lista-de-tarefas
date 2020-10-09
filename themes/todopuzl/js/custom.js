var load;
var flashClass = "ajax_response";
var flash;
var content;
var fullscreen;
$(function(){
    load = $(".ajax_load");
    flash = $("." + flashClass);
    content = $(".container-fluid");
    fullscreen = $(".app_fullscreen");

    $(document).on('submit', 'form:not(".ajax_off")', function (e) {
        e.preventDefault();
        ajaxLoad($(this));
    });

    $(document).on('click', 'a:not(".ajax_off")', function(e){
        e.preventDefault();
        let data = $(this).data();
        if(data.confirm){
            let conf = confirm(data.confirm);
            if(!conf){
                return;
            }
        }
        if(data.post){
            $.post($(this).attr("href"), data, function (response) {
                updateState(response);
                if(data.toggle === "modal"){
                    $(data.target).find('[name="action"]').val("update");
                    $.each(response, (key, element) => {
                        if(key === "datetime"){
                            element = element.replace(/\s/i, "T");
                        } else if(key === "completed") {
                            $(data.target).find('[name="'+key+'"]').prop("checked", element === "yes");
                            return;
                        } else if(key === "id"){
                            let action = $(data.target).find("form").attr("action").replace("nova", element);
                            $(data.target).find("form").attr("action", action);
                        }
                        $(data.target).find('[name="'+key+'"]').val(element);
                    });
                }
            }, "json");
        } else {
            $.get($(this).attr("href"), function (response) {
                updateState(response);
                if(data.toggle === "modal"){
                    $(data.target).find('[name="action"]').val("update");
                    $.each(response, (key, element) => {
                        if(key === "datetime"){
                            element = element.replace(/\s/i, "T");
                        } else if(key === "completed") {
                            $(data.target).find('[name="'+key+'"]').prop("checked", element === "yes");
                            return;
                        } else if(key === "id"){
                            let action = $(data.target).find("form").attr("action").replace("nova", element);
                            $(data.target).find("form").attr("action", action);
                        }
                        $(data.target).find('[name="'+key+'"]').val(element);
                    });
                }
            }, "json");
        }
    });

    $("."+flashClass).on("click", ".message", function (e) {
        $(this).effect("bounce").fadeOut(1);
    });
});
function ajaxMessage(message, time) {
    var ajaxMessage = $(message);
    ajaxMessage.append("<div class='message_time'></div>");
    ajaxMessage.find(".message_time").animate({"width": "100%"}, time * 1000, function () {
        $(this).parents(".message").fadeOut(200);
    });

    $("."+flashClass).append(ajaxMessage);
    ajaxMessage.effect("bounce");
}

function updateState(response, callback = null){
    //reload
    if (response.reload) {
        window.location.reload();
    } else {
        load.fadeOut(200);
    }

    //modal
    if(response.modal){
        let call = response.modal_callback;
        window[call](response.modal_data);
    }

    //content
    if(response.content){
        $(content).html(response.content);
        $(fullscreen).fadeOut(300, () => {
            $(fullscreen).html("")
        });
    }

    //fullscreen
    if(response.fullscreen){
        $(fullscreen).html(response.fullscreen);
        $(fullscreen).fadeIn(300);
    }

    //message
    if (response.message) {
        ajaxMessage(response.message, 5);
    } else {
        flash.fadeOut(100);
    }

    if (response.data && callback) {
        callback(response.data);
    }
}

function ajaxLoad(form, callback = null, type = "POST") {
    form.ajaxSubmit({
        url: form.attr("action"),
        type,
        dataType: "json",
        beforeSend: function () {
            load.fadeIn(200).css("display", "flex");
        },
        uploadProgress: function (event, position, total, completed) {
            var loaded = completed;
            var load_title = $(".ajax_load_box_title");
            load_title.text("Enviando (" + loaded + "%)");

            form.find("input[type='file']").val(null);
            if (completed >= 100) {
                load_title.text("Aguarde, carregando...");
            }
        },
        success: function (response) {
            updateState(response, callback);
            if(form.attr("id") === "formRegisterTask") {
                form.find('[name="action"]').val("create");
                form.attr("action", "/task/nova");
                form.closest(".modal").modal('hide');
            }
        },
        complete: function () {
            if (form.data("reset") === true) {
                form.trigger("reset");
            }
        },
        error: function () {
            var message = "<div class='message error icon-warning'>Desculpe mas não foi possível processar a requisição. Favor tente novamente!</div>";

            if (flash.length) {
                flash.html(message).fadeIn(100).effect("bounce", 300);
            } else {
                form.prepend("<div class='" + flashClass + "'>" + message + "</div>")
                    .find("." + flashClass).effect("bounce", 300);
            }

            load.fadeOut();
        }
    });
}