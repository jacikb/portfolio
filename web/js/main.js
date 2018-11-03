/**
 * Created by Jacik on 2018-10-13.
 */
$( document ).ready(function() {

    $(".show").fadeIn(800);
    $(".logo-symf").animate({right: "30px"}, 800);
});


function ajaxItemEdit(u, id)
{
    $("#bt-" + id).replaceWith("<img id='bt-" + id+ "' src='/img/wait.gif' class='pull-right' width='24px' height='24px'></img>");
    $.ajax({
        url: u,
        type: "GET",
        dataType: "json",
        async: true,
        success: function (data)
        {
            $('.modal-title').html("Edycja");
            $('.modal-body').html(data.form);

            dialog.dialog( "open" );

            $("#bt-" + id).replaceWith('<small class="pull-right">( edited... )</small>');

            //console.log('msg:' + data.message);
        }
    });
}
function ajaxItemShow(u, id)
{

    $.ajax({
        url: u,
        type: "GET",
        dataType: "json",
        async: true,
        success: function (data)
        {
            dialog.dialog( "close" );

            $('#item'+id).html(data.form);
            $('#message').html(data.message);

            //console.log('msg:' + data.message);
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
                dialog.dialog( "close" );
            },
        failure: function(errMsg) {
            alert(errMsg);
        }
    });
}

//form utility function
function getFormData(data) {

    var unindexed_array = data;
    var indexed_array = {};

    $.map(unindexed_array, function(n, i) {

        //name[key] > key
        str_array = n['name'];
        var name = str_array.substring(str_array.indexOf('[')+1,str_array.indexOf(']'));

        indexed_array[name] = n['value'];
    });

    return indexed_array;
}


