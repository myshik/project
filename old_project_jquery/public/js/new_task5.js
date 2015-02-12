/**
 * Created by user on 12/4/14.
 */
$(document).ready(function() {
    $("#input").on("click", function(event) {
        event.preventDefault();
        $(this).attr("disabled", "disabled");

        var state = $(".nav-tabs li.active a").attr("href").slice(1);
        var selectedPage = $(".pagination li.active").text();

        var link = $('#name').val();
        $.ajax({
            url:"http://api.bit.ly/v3/shorten",
            data:{
                longUrl: link,
                apiKey:'R_a504087bf6004b44a4c9026f77e65aa5',
                login:'reexar'
            },
            dataType:"jsonp",
            success: function(res) {
                // отправить пару ссылок на сервер
                $.ajax({
                    url: "/links/",
                    method: "POST",
                    data: {
                        longUrl: link,
                        shortUrl: res.data.url,
                        state: state,
                        page: selectedPage
                    },
                    success: function(data) {
                        $("#tabs-row").replaceWith(data);
                    },
                    error: function(data, error, errorMessage) {
                        console.error(arguments);
                        alert("Неудалось сохранить ссылки в БД");
                        $("html").html(data.responseText);
                    }
                });
            },
            error: function() {
                alert("Не получилос создать короткую ссылку");
            },
            complete: function() {
                $("#input").removeAttr("disabled");
            }
        })
    });

    $("body").on("change", "input:checkbox", function(event) {
        var id = $(this).data("id");
        var page = $('.pagin_goes_here li.active').children().data("page");
        console.log(page);
        $.ajax({
            url: "/links/",
            type: "PUT",
            data: {id: id, page: page},
            success: function(data) {
                $("#tabs-row").replaceWith(data);
            },
            error: function(data, error, errorMessage) {
                console.error(arguments);
                alert("Неудалось сохранить ссылки в БД");
                $("html").html(data.responseText);
            }
        });
    });

    $("body").on("click", ".nav-tabs a", function(event) {
        var state = $(this).attr("href").slice(1);

        $.ajax({
            url: "/links/"+state,
            type: "GET",
            success: function(data) {
                $("#tabs-row").replaceWith(data);
            },
            error: function(data, error, errorMessage) {
                console.error(arguments);
                alert("Не удалось переключить вкладки");
                $("html").html(data.responseText);
            }
        })
    });
    //обработчик события удаления удаляем все ссылки
    $("body").on("click", ".but_rem_all",  function(){
        $('.list_goes_here li').each(function(i,elem) {
            var id = $(elem).children().data('id');
            $.ajax({
                url: "/links/",
                type: "DELETE",
                data: {id: id},
                success: function(data) {
                    $("#tabs-row").replaceWith(data);
                },
                error: function(data, error, errorMessage) {
                    console.error(arguments);
                    alert("Неудалось удалить ссылки в БД");
                    $("html").html(data.responseText);
                }
            });
        });
    });
    //удаляем ссылки где стоит аттрибут checked
    $("body").on("click", ".but_rem_sel",  function(){
        $('.list_goes_here li').each(function(i,elem) {
            if($(elem).children().is(':checked')){
                var id = $(elem).children().data('id');
                $.ajax({
                    url: "/links/",
                    type: "DELETE",
                    data: {id: id},
                    success: function(data) {
                        $("#tabs-row").replaceWith(data);
                    },
                    error: function(data, error, errorMessage) {
                        console.error(arguments);
                        alert("Неудалось удалить ссылки в БД");
                        $("html").html(data.responseText);
                    }
                });
            }
        });
    });
});