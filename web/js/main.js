/**
 * Created by Jacik on 2018-10-13.
 */
$( document ).ready(function() {

    $(".show").fadeIn(800);
    $(".logo-symf").animate({right: "30px"}, 800);

});

function AjaxItemEdit(u, id)
{

    //$("#button.ajax").click( function(){
    $.ajax({
        url: u,
        type: "POST",
        dataType: "html",
        data: {
            "id": "id"
        },
        async: true,
        success: function (data)
        {
            console.log(data)
            $('div#item'+id).html(data);

        }
    });
    //return false;

    //});

}
