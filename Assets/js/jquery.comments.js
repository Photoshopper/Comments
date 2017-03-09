// Ajax submitting comments
$(document).on('click', '.comment-form .comment-submit', function(e){
    var $form = $(e.target).parent();
    var $comment_group = $form.find($('.comment-group'));

    $.ajax({
        type: "POST",
        url: route,
        data: {
            _token: $('meta[name="token"]').attr('value'),
            parent_id: $form.find($('.parent_id')).val(),
            comment: $form.find($('.comment')).val(),
            commentable_id: $form.find($('.commentable_id')).val(),
            commentable_type: $form.find($('.commentable_type')).val()
        },
        success: function (data) {
            var reply_user = '';

            if(data['comment']['parent_id'] !== null) {
                reply_user = ' <span class="glyphicon glyphicon-share-alt"></span>' + ' ' + $form.closest('li').find($('.author')).first().text();
            }

            var list = '<li>' +
                '<div class="comment-content clearfix"><div class="avatar"><img src="'+ data['comment']['avatar'] +'" alt=""></div>' +
                '<div class="comment-body">' +
                '<header>' +
                '<span class="author">' + data['comment']['username'] + '</span>' +
                reply_user +
                '<span class="time-ago">' + data['comment']['time_ago'] + '</span>' +
                '</header>' +
                '<div class="comment-message">' + data['comment']['comment'] + '</div>' +
                '<div class="reply"><a href="#" data-id="' + data['comment']['id'] + '">'+ reply_text +'</a></div>' +
                '</div>' +
                '</div>' +
                '</li>';

            if (typeof data['message'] !== "undefined") {
                var message = '<div class="alert alert-success fade in alert-dismissable">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                    data['message'] +
                    '</div>';
            }

            if(data['comment']['parent_id']) {
                if (typeof message !== "undefined") {
                    $form.closest('li').append('<ul class="comment-list children" style="display: none">' + message + list + '</ul>');
                } else {
                    $form.closest('li').append('<ul class="comment-list children" style="display: none">' + list + '</ul>');
                }

                $form.remove();
            } else {
                $('.comments-wrapper').prepend('<ul class="comment-list" style="display: none">' + list + '</ul>');

                if($('.no-comments').length > 0) {
                    ('.no-comments').remove();
                }

                if (typeof message !== "undefined") {
                    $form.prepend(message);
                }

                $form.find($('.comment')).val('');
                $comment_group.removeClass('has-error');
                $comment_group.children(".help-block").remove();
            }

            $('.comment-list').fadeIn(600);

            grecaptcha.reset(widgetId1);
        },
        error: function (data) {
            var jsonResponse = JSON.parse(data.responseText);

            if (!$comment_group.children(".help-block").length) {
                $comment_group.addClass('has-error');
                $comment_group.append('<span class="help-block">' + jsonResponse["comment"][0] + '</span>');
            }
        }
    });

    e.preventDefault();
});

// Show/Hide a reply form on click
$(document).on('click', '.reply a', function (e) {
    if($(e.target).parent().parent().find('form').length > 0) {
        $(e.target).parent().parent().find('form').remove();
    } else {
        $(e.target).parent().after(
            '<form method="POST" action="/comments" accept-charset="UTF-8" class="comment-form">' +
            '<input name="_token" type="hidden" value="' + $('meta[name="token"]').attr('value') + '">' +
            '<div class="form-group comment-group">' +
            '<textarea class="form-control comment reply-textarea" rows="3" maxlength="500" name="comment" cols="50" id="comment"></textarea>' +
            '<div class="textarea-counter" id="reply-textarea-counter"></div>' +
            '</div>' +
            '<input class="parent_id" name="parent_id" type="hidden" value="' + $(this).attr('data-id') + '">' +
            '<input class="commentable_id" name="commentable_id" type="hidden" value="' + $('.commentable_id').val() + '">' +
            '<input class="commentable_type" name="commentable_type" type="hidden" value="' + $('.commentable_type').val() + '">' +
            '<input class="btn btn-success btn-sm comment-submit" type="submit" value="'+ submit_text +'">' +
            '</form>'
        );
        textareaCounter($('.reply-textarea'), $('#reply-textarea-counter'))
    }

    e.preventDefault();
});

function textareaCounter(textarea, counter) {
    var maxlength = 500;
    counter.html(maxlength);

    textarea.keyup(function() {
        var text_length = textarea.val().length;
        var characters_remaining = maxlength - text_length;

        counter.html(characters_remaining);
    });
}

textareaCounter($('#comment'), $('#textarea-counter'));