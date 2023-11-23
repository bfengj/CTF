/**
 * Created by Admin on 2018/1/10.
 */
$(document).ready(function () {
     var lastTimeStamp = 0;

    $('.form-checkbox').prev('label').css('block', 'inline-block').css('margin-right', '10px').addClass('pull-left').parent().addClass('clearfix');

    function menuToggle() {
        if ($('.toggle-menu').hasClass('hidden')) {
            $('.user-action>.toggle-menu').removeClass('hidden')
        } else {
            $('.user-action>.toggle-menu').addClass('hidden')
        }
    }

    function stopPropagation(e) {
        if (e.stopPropagation)
            e.stopPropagation();
        else
            e.cancelBubble = true;
    }

    $(document).bind('click', function () {
        var $menu = $('.user-action .toggle-menu');
        if (!$menu.hasClass('hidden')) {
            $menu.addClass('hidden');
        }
    });

    $(function () {
        $.each($('.info-menu>li'), function () {
            if ($(this).hasClass('active')) {
                $('.info-more span').html($(this).children("a:first").html());
                return
            }
        })
    });

    $('.user-action > .avatar').bind('click', function (e) {
        menuToggle();
        stopPropagation(e);
    });

    (function () {
        function getCookie(name) {
            var cookieValue = null;
            if (document.cookie && document.cookie != '') {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = jQuery.trim(cookies[i]);
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) == (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        }

        var csrftoken = getCookie('csrftoken');

        function csrfSafeMethod(method) {
            // these HTTP methods do not require CSRF protection
            return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
        }

        $.ajaxSetup({
            crossDomain: false, // obviates need for sameOrigin test
            beforeSend: function (xhr, settings) {
                if (!csrfSafeMethod(settings.type)) {
                    xhr.setRequestHeader("X-CSRFToken", csrftoken);
                }
            }
        });
    })();

    function thumb_change(ele) {
        var count = ele.children('span').html();
        if (ele.children('i').hasClass('fa-thumbs-o-up')) {
            ele.children('span').html(Number(count) + 1);
            ele.children('i').removeClass('fa-thumbs-o-up').addClass('fa-thumbs-up')
        } else {
            ele.children('span').html(Number(count) - 1);
            ele.children('i').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up')
        }
    }

    function thumb() {
        var action = $(this).attr('data-action');
        var pk = $(this).attr('data-pk');
        var topic = $(this).attr('data-topic');
        var ele = $(this);
        $.ajax({
            url: "/forum/approval",
            dataType: 'json',
            method: 'post',
            data: {'action': action, 'pk': pk, 'csrfmiddlewaretoken': $('input[name="csrfmiddlewaretoken"]').val()},
            success: function (data) {
                if (data.not_authenticated) {
                    window.location.href = data.login_url
                }
                if (data.status) {
                    thumb_change(ele);
                } else {
                    return null
                }
            }
        });

    }

    function FollowTopic() {
        var ele = $(this);
        var pk = $(this).attr('data-pk');
        $.ajax({
            url: "/forum/topic/follow",
            method: 'post',
            dataType: 'json',
            data: {'pk': pk},
            success: function (data) {
                if (data.not_authenticated) {
                    window.location.href = data.login_url
                } else {
                    if (data.status === 1) {
                        alert(data.errorMsg)
                    } else {
                        if (data.actionCode === 1) {
                            ele.html('已关注' + '<span class="i-seprator"> | </span>' + data.count)
                        } else {
                            ele.html('关注' + '<span class="i-seprator"> | </span>' + data.count)
                        }
                    }
                }

            }
        })
    }

    function markTopic() {
        var markText = $(this).children('#mark-text');
        var markCount = $(this).children('#mark-count');
        var pk = $(this).attr('data-pk');
        var tip;
        $.ajax({
                url: "/forum/bookmarked",
                method: 'post',
                dataType: 'json',
                data: {'pk': pk},
                success: function (data) {
                    if (data.not_authenticated) {
                        window.location.href = data.login_url
                    } else {
                        if (data.error === 0) {
                            if (data.actionCode === 1) {
                                tip = '已收藏'
                            } else if (data.actionCode === 0) {
                                tip = '点击收藏'
                            } else {
                                alert('请求出错，请刷新重试');
                                return
                            }
                            markText.html(tip);
                            markCount.html(data.count)
                        } else {
                            console.log(data.msg)
                        }
                    }
                }
            }
        )
    }

    function jumpTo() {
        $("html,body").animate({scrollTop: $("#reply-box,.cont-box").offset().top}, 500)
    }

    function voteUp(topicPk) {
        if (topicPk) {
            $.ajax({
                url: '/forum/topic/up/',
                data: {'pk': topicPk},
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        if (data.up) {
                            $('.t-vote #up-vote').html(data.html);
                        }

                    }
                }
            });
        }
    }

    var voteDown = function (topicPk) {
        if (topicPk) {
            $.ajax({
                url: '/forum/topic/down/',
                data: {'pk': topicPk},
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    if (data.not_authenticated) {
                        window.location.href = data.login_url
                    } else {
                        if (data.success) {
                            $('.t-vote > .vote-down').html(data.html);
                        }
                    }

                }
            });
        }
    };

    function FollowUser() {
        var info = $(this);
        var pk = $(this).attr('data-pk');
        var fan = $(".fans");
        var fanCount;
        $.ajax({
                url: "/forum/user/follow",
                method: "post",
                data: {'pk': pk},
                dataType: 'json',
                success: function (data) {
                    if (data.not_authenticated) {
                        window.location.href = data.login_url
                    } else {
                        if (data.error === 0) {
                            if (data.actionCode === 0) {
                                info.html('关注Ta').removeClass('disabled');
                                fanCount = fan.text();
                                fanCount --;
                                fan.val(fanCount)
                            } else if (data.actionCode === 1) {

                                info.html('已关注').addClass('disabled');
                                fanCount = fan.text();
                                fanCount ++;
                                if (fanCount < 1){
                                    fanCount = 0
                                }
                                fan.val(fanCount)
                            }
                        } else {
                            console.log(data.msg)
                        }
                    }
                },
                error: function () {
                    alert("请求出错，请刷新后重试")
                }
            }
        )
    }

    function changeActive() {
        $(this).addClass('active');
        $(this).parent().siblings().children('a:first-child').removeClass('active')
    }

    function showHistory() {
        var $box = $('.notify-box > .topic-list > tbody');
        var empty = $box.find('.empty-text').clone();
        $box.find('.empty-text').remove();
        if ($box.children().html() === undefined) {
            empty.find('.empty-box').html('暂无历史消息');
            $box.append(empty)
        } else {
            $box.children().removeClass('hidden')
        }
        $(this).remove();
    }

    function draftSave(e) {
        var objData = {
            "title": $('#title').val(),
            "node": $('#node').val(),
            "content": $('#editor_id').val(),
            'attach': $('#attachment').val()
        };
        if ($('#tags').val() === null) {
            objData['tags'] = ''
        } else {
            objData['tags'] = JSON.stringify($('#tags').val())
        }
        lastTimeStamp = e.timeStamp;
        $('#save-info').html('草稿保存中...');
        if ($('#draftId').length > 0) {
            objData["draftId"] = $('#draftId').val()
        }
        setTimeout(function () {
            if (lastTimeStamp === e.timeStamp) {
                $.ajax({
                    url: "/forum/topic/api/draft/save",
                    data: objData,
                    dataType: 'json',
                    method: 'post',
                    success: function (data) {
                        if (data.status === 1) {
                            if ($('#draftId').length === 0) {
                                var $draft = $('<input type="hidden" id="draftId" name="draftId" >');
                                $draft.prop('value', data.data);
                                $('input[name="csrfmiddlewaretoken"]').after($draft);
                            }
                            $('#save-info').html('保存成功');
                        }else {
                            $('#save-info').html('保存失败，请即时发表内容');
                        }

                    }
                });
            }
        }, 1500);
    }

    $('#postForm').bind('keyup', draftSave).bind('paste', draftSave);
    $('#node,#tags').bind('change', draftSave);
    $('#attachment').bind('change', draftSave);
    $('#history').bind('click', showHistory);

    var a_wrap = $('<a></a>');
    var imgs = $('.topic-content img,.post-content img, .appendix-list .markdown-body img');
    imgs.wrap('<a></a>');
    $.each(imgs, function (i, j) {
        $(j).parent().attr('id', 'img' + i).attr('href', $(this).attr('src'));
        $("#img" + i).fancybox({
            'zoomSpeedIn': 300,
            'zoomSpeedOut': 300,
            'overlayShow': false,
            'overlayOpacity': 0.3
        })
    });

    $('.reward-btn').click(function () {
        $('.modals').css('display', 'block');
    });

    $('.reward-close').click(function () {
        $('.modals').css('display', 'none');
    });

    $('.reward-body > table td').click(function (e) {
        var _this = $(this);
        _this.children('a').addClass('active');
        _this.siblings().children('a').removeClass('active');
        _this.parent().siblings('tr').find('a').removeClass('active');
    });

    $('.reward-body > table tr:nth-child(2) > td:nth-child(3) a').click(function () {
        $(this).css('display', 'none').siblings('input').css('display', '').focus();
    });

    $('.reward-body > table tr:nth-child(2) > td:nth-child(3) input').blur(function () {
        var _this = $(this);
        _this.css('display', 'none').siblings('a').css('display', '');
        _this.prev().removeClass('active');
    });

    $('.pay-choice').click(function () {
        $(this).addClass('active').siblings().removeClass('active')
    });

    $('#mark').bind('click', markTopic);

    $('#follow_topic').bind('click', FollowTopic);

    $('.thumbs,.collection').bind('click', thumb);

    $('.reply-jump').on('click', '', jumpTo);

    $('#follow_user').bind('click', FollowUser);

    $('.info-menu a').bind('click', changeActive);

});