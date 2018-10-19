/**
 * Created by Jacik on 2018-10-13.
 */
$( document ).ready(function() {

    $(".show").fadeIn(800);
    $(".logo-symf").animate({right: "30px"}, 800);

});


function AjaxItem(u, id)
{
    $("#bt-" + id).replaceWith("<img src='/img/wait.gif' class='pull-right' width='24px' height='24px'></img>");
    $.ajax({
        url: u,
        type: "GET",
        dataType: "json",
        async: true,
        success: function (data)
        {
            //$('#item'+id).html(data);
            $('#message').fadeOut(100, function(){
                $('#message').html(data.message);
                $('#message').width("50%");
                $('#message').height("50%");
            });
            $('#message').fadeIn(400).delay(1000).fadeOut(300);
            $('#item'+id).html(data.form);
            //$('.modal-body').html(data.form);
            //$('.modal-body').modal('show');

            console.log('msg:' + data.message);
        }
    });
}


function ajaxFormSubmit(id)
{
    var form = $("#form-" + id);
    var data = getFormData(form.serializeArray());

    var editor_id = $('#form-' + id + ' textarea').attr("id");
    var value = CKEDITOR.instances[editor_id].getData();

    data['content'] = value;

    $.ajax({
        type: 'post',
        url: form.attr('action'),
        data: JSON.stringify(data),
        contentType: "application/json",
        dataType: "json",
        success: function(data)
            {
                $('#item'+id).html(data.form);
                $('#message').fadeOut(100, function(){
                    $('#message').html(data.message);
                });
                $('#message').fadeIn(400).delay(1000).fadeOut(300);

            },
        failure: function(errMsg) {
            alert(errMsg);
        }
    });

}

//utility function
function getFormData(data) {

    var unindexed_array = data;
    var indexed_array = {};

    $.map(unindexed_array, function(n, i) {

        //form_name[title] > title
        str_array = n['name'];
        var name = str_array.substring(str_array.indexOf('[')+1,str_array.indexOf(']'));

        indexed_array[name] = n['value'];
    });

    return indexed_array;
}
