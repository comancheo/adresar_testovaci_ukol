$(document).ready(function () {
    /*trigger edit*/
    $(".edit").click(function (e) {
        e.preventDefault();
        edit_vars($(this));
    });
    /*trigger cancle editing*/
    $(".cancle").click(function (e) {
        e.preventDefault();
        cancle_edit($(this));
    });
    /*adding new line for add new item to table*/
    $(".add_new").click(function (e) {
        var new_id = highest_new();
        $("tbody").append("<tr class='item-new item-new-" + new_id + "' data-id='" + new_id + "'>\n\
<td class='name'><input type='text' name='adresar[new" + new_id + "][name]'></td>\n\
<td class='surname'><input type='text' name='adresar[new" + new_id + "][surname]'></td>\n\
<td class='phone'><input type='text' name='adresar[new" + new_id + "][phone]'></td>\n\
<td class='email'><input type='text' name='adresar[new" + new_id + "][email]'></td>\n\
<td class='note'><textarea name='adresar[new" + new_id + "][note]'></textarea></td>\n\
<td class=''><a href='#' class='new_cancle' data-id='" + new_id + "' onclick='cancle_new($(this));'>Zrušit</a></td>\n\
</tr>");
        if ($("input").length > 0) {
            $(".send_changes").show();
        }
    });
    /*trigger delete*/
    $(".delete").click(function () {
        delete_handle($(this));
    });
    /*trigger save changes*/
    $(".send_changes").click(function (e) {
        e.preventDefault();
        send_changes($(this));
    });
});
/*Just ask for highest index of new (not saved) items*/
function highest_new() {
    var highest = -1;
    $(".item-new").each(function () {
        if (parseInt($(this).attr("data-id")) >= highest) {
            console.log(parseInt($(this).attr("data-id")));
            highest = parseInt($(this).attr("data-id"));
        }
    });
    return highest + 1;
}
/*Push info which item we want delete via AJAX, then refresh table data*/
function delete_handle(el) {
    var id = el.attr("data-id");
    if (confirm("Opravdu chcete dané položky vymazat?")) {
        var data = new FormData();
        data.append('adresar[' + id + '][delete]', id);
        $.ajax({
            url: '/ajax_delete_item',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            beforeSend: function (xhr) {
                console.log("Mažu z DB záznam číslo " + id);
            },
            success: function (data) {
                refresh_table();
            },
            error: function (xhr, status, error) {
                console.log("Nepovedlo se smazat záznam číslo " + id);
            }
        });
    } else {

    }
}
/*Change inputs back to text*/
function cancle_edit(el) {
    var tr = el.parent("td").parent("tr");
    tr.children(".editable").each(function () {
        var original = $(this).attr("data-original");
        var what = $(this).attr("class").replace("editable ", "");
        $(this).html("");
        if (what == "note") {
            $(this).html(original);
        } else if (what == "email") {
            $(this).append("<a href='mailto:" + original + "'>" + original + "</a>");
        } else {
            $(this).html(original);
        }
    });
    if ($("input").length < 1) {
        $(".send_changes").hide();
    }
}
/*Unset row of new item*/
function cancle_new(el) {
    var id = el.attr("data-id");
    console.log(id);
    $("tr.item-new-" + id).remove();
    if ($("input").length < 1) {
        $(".send_changes").hide();
    }

}
/*Push changes (new items and edited items) via AJAX to server, then refresh data in table*/
function send_changes() {
    if (confirm("Opravdu chcete dané položky uložit?")) {
        clear_empty_rows(); //first remove empty rows
        var data = new FormData();
        $("input").each(function () {
            data.append($(this).attr("name"), $(this).val());
        });
        $("textarea").each(function () {
            data.append($(this).attr("name"), $(this).val());
        });
        console.log(data);
        $.ajax({
            url: '/ajax_update_new',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            beforeSend: function (xhr) {
            },
            success: function (data) {
                refresh_table();
                $(".send_changes").hide();
            },
            error: function (xhr, status, error) {
            }
        });
    } else {

    }
}
/*Enable editing values -> change text to inputs and textarea*/
function edit_vars(el) {
    var tr = el.parent("td").parent("tr");
    tr.children(".editable").each(function () {
        var id = $(this).attr("data-id");
        var original = $(this).attr("data-original");
        var what = $(this).attr("class").replace("editable ", "");
        if (what == "note") {
            $(this).html("");
            $(this).append("<textarea name='adresar[" + id + "][" + what + "]'>" + original + "</textarea>");
        } else {
            $(this).html("");
            var type = "text";
            if (what == "email") {
                type = "email";
            }
            $(this).append("<input type='text' name='adresar[" + id + "][" + what + "]' value='" + original + "'>");
        }
    });
    if ($("input").length > 0) {
        $(".send_changes").show();
    }
}
/*Download via AJAX JSON items and push them to fill_table()*/
function refresh_table() {
    $.ajax({
        url: '/ajax_refresh_items',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function (xhr) {
        },
        success: function (data) {
            fill_table($.parseJSON(data));
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}
/*Function which fill table with data from refresh_table()*/
function fill_table(data) {
    $("tbody").html("");
    $.each(data, function (index, value) {
//        console.log(index+":"+value);
        $("tbody").append("<tr>\n\
<td class='editable name' data-id='" + value.id + "' data-original='" + htmlspecialchars(value.name) + "'>" + value.name + "</td>\n\
<td class='editable surname' data-id='" + value.id + "' data-original='" + htmlspecialchars(value.surname) + "'>" + value.surname + "</td>\n\
<td class='editable phone' data-id='" + value.id + "' data-original='" + htmlspecialchars(value.phone) + "'>" + value.phone + "</td>\n\
<td class='editable email' data-id='" + value.id + "' data-original='" + htmlspecialchars(value.email) + "'><a href='mailto:" + value.email + "'>" + value.email + "</a></td>\n\
<td class='editable note' data-id='" + value.id + "' data-original='" + htmlspecialchars(value.note) + "'>" + value.note + "</td>\n\
<td>\n\
<a href='#' data-id='" + value.id + "' onclick='delete_handle($(this));' class='delete'>X</a> /\n\
<a href='#' data-id='" + value.id + "' onclick='edit_vars($(this));' class='edit'>E</a> /\n\
<a href='#' data-id='" + value.id + "' onclick='cancle_edit($(this));' class='cancle'>Zrušit</a>\n\
</td>\n\
</tr>");
    });
}
/*Remove empty rows before save changes*/
function clear_empty_rows() {
    $("tr.item-new").each(function () {
        var name = $(this).children("td.name").children("input").val();
        var surname = $(this).children("td.surname").children("input").val();
        var phone = $(this).children("td.phone").children("input").val();
        var email = $(this).children("td.email").children("input").val();
        var note = $(this).children("td.note").children("textarea").val();
        if (name == "" && surname == "" && phone == "" && email == "" && note == "") {
            $(this).remove();
        }
    });
}
/*JS equivalent for PHP function htmlspecialchars();*/
function htmlspecialchars(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function (m) {
        return map[m];
    });
}
