/**
 * Created by Jacik on 2018-10-13.
 */
$( document ).ready(function() {

    $(".show").fadeIn(800);
    $(".logo-symf").animate({right: "30px"}, 800);

});

function AjaxItemEdit(u, id)
{
    $.ajax({
        url: u,
        type: "GET",
        dataType: "html",
        data: {
            "id": "id"
        },
        async: true,
        success: function (data)
        {
            $('#item'+id).html(data);
        }
    });
}
function AjaxItemShow(u, id)
{
    $.ajax({
        url: u,
        type: "GET",
        dataType: "html",
        data: {
            "id": "id"
        },
        async: true,
        success: function (data)
        {
            $('#item'+id).html(data);
        }
    });
}
