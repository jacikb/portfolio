/**
 * Created by Jacik on 2018-10-13.
 */
$( document ).ready(function() {

    $(".show").fadeIn(800);
    $(".logo-symf").animate({right: "30px"}, 800);

});


function AjaxItem(u, id)
{
    $.ajax({
        url: u,
        type: "GET",
        dataType: "json",
        //data: {
          //  "id": id
        //},
        async: true,
        success: function (data)
        {
            //$('#item'+id).html(data);

            //$('#msgm'+id).html(data.message);
            $('#item'+id).html(data.form);
            console.log(data.message);
        }
    });
}


function ajaxFormSubmit(id)
{
    var form = $("#form" + id);

    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data)
            {
                $('#item'+id).html(data.form);
                $('#message').html(data.message);
                $('#message').fadeIn(300).delay(1000).fadeOut(100);
                console.log(data.message);

            },
        failure: function(errMsg) {
            alert(errMsg);
        }
    });

}

