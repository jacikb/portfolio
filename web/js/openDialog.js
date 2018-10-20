                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               /**
 * Created by Jacik on 2018-10-20.
 */

$(document).ready(function() {
    dialog = $("#dialog-form").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: "66%",
        modal: true,
        closeOnEscape: true,
        buttons: {},
        open: function(event, ui) {
            $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
        }
/*
        buttons: {
            //"Create an account": addUser,
            Cancel: function () {
                ajaxItemShow('http://127.0.0.1:8000/article/item/edit/1', 1);
                this.hide();

            }
        },

        close: function () {

            //form[0].reset();
            //allFields.removeClass("ui-state-error");
        }
 */
    });

});
