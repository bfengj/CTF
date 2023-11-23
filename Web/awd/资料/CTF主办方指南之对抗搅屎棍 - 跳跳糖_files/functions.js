"use strict";

function submitPost(action_url) {
    var f = $('<form method="post"></form>');
    var xsrf = $("meta[name=csrfmiddlewaretoken]").attr('content');
    f.append($('<input type="hidden" name="csrfmiddlewaretoken" value="' + xsrf + '">'));
    f.prop('action', action_url);
    f.appendTo('body').submit();
}

function escapeHtml(text) {
    const map = {
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

(function ($) {
    const $body = $('body');
    const csrftoken = document.querySelector('[name=csrfmiddlewaretoken]').value;

    //popover
    $('[data-toggle="popover"]').popover();

    //tooltip
    $('[data-toggle="tooltip"]').tooltip();

    /* ============================================================================================
     Theme Settings
     ==============================================================================================*/
    const current_theme = $body.data('theme');
    $body.addClass(current_theme);

    // Add slideUp animation to Bootstrap dropdown when collapsing.
    $('.dropdown, .split-dropdown').on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(200);
    });

    $('.dropdown, .split-dropdown').on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });

    $("#unbind-wx").on('click', function () {
        return submitPost('/auth/wx/callback/');
    });

    $("#unbind-github").on('click', function () {
        return submitPost('/auth/github/callback/');
    });

    if ($.magnificPopup) {
        $(".articles img").each(function (i, e) {
              if (e.parentNode.tagName.toUpperCase() !== 'A') {
                  $(e).wrap('<a href="' + escapeHtml(e.src) + '" class="image"></a>');
              } else {
                  $(e.parentNode).addClass('image');
              }
          });

        $(".articles").magnificPopup({
            type:'image',
            delegate: 'a.image',
        });
    }
})(jQuery);
