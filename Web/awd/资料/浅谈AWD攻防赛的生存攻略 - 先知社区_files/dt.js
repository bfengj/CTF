/**
 * Created by Admin on 2018/2/8.
 */
$(document).ready(function () {
    let toc = $('#toc-container');
    let tocList = $('#toc');
    let content = $("#topic_content");
    let selectorArray = [];
    let selector = initialSelector();

    tocList.initTOC({
        selector: selector,
        scope: '#topic_content',
        overwrite: false,
        prefix: 'toc',
    });

    let top1 = toc.offset().top;
    toc.css("width", $(".sidebar").css("width"));

    $(window).scroll(function () {
        let win_top = $(this).scrollTop();
        let top = toc.offset().top;
        if (win_top >= top) {
            toc.addClass("sfixed");
        }
        if (win_top < top1) {
            toc.removeClass("sfixed");
        }
        scrollActive(win_top)
    });

    function tocCheck() {
        if ($('#toc').children('ol').children().length === 0) {
            toc.remove()
        }
    }

    let H2 = content.find(selector);
    let wintTop = $(window).scrollTop();
    scrollActive(wintTop)
    function scrollActive(top) {
        let currentId = "";
        let stepLimit = 18;
        let bg = $(".high-light");
        let list = $("#toc > ol");
        let topHeight = 50;
        H2.each(function () {
            let item = $(this);
            if (top > item.offset().top - 80) {
                currentId = "#" + item.attr("id")
            } else {
                return false
            }
        });

        if (currentId && toc.find(`a[href='${currentId}']`).offset().top !== bg.offset().top) {
            let step = parseInt(currentId.split("-")[1]);
            let last = toc.find("[href=" + "#toc-" + (step - 1) + "]");
            // console.log(last.height())
            let current = toc.find("[href=" + currentId + "]");
            bg.css("height", current.height());
            // console.log(current.height())
            if (step > stepLimit) {
                if (current.height() === 26) {
                    bg.css("top", 50 + 26 * stepLimit);
                    list.css("top", (50 - 26 * (step - stepLimit)) + "px")
                } else {
                    bg.css("top", 50 + 26 * (stepLimit - 1) + current.height());
                    list.css("top", (50 - (26 * (step - stepLimit + 1)) + current.height()) + "px")
                }

            } else {
                if (step === 0) {
                    bg.css("top", 50 + "px")
                } else {
                    if (current.height() === 26) {

                        bg.css("top", 50 + 26 * step);
                    } else {

                        bg.css("top", 50 + 26 * (step - 2));
                    }
                    list.css("top", 50)
                }
            }
        }
    }

    function initialSelector() {
        if (content.children("h1").length !== 0) {
            selectorArray.push("h1")
        }
        if (content.children("h2").length !== 0) {
            selectorArray.push("h2")
        }
        if (content.children("h3").length !== 0) {
            selectorArray.push("h3")
        }
        let part;
        if (selectorArray.length < 3) {
            part = selectorArray.join(", ")
        } else {
            let part = selectorArray.slice(1, 3).sort();
            part = part.join(", ")
        }
        return part || "h2, h3"

    }

    tocCheck()
});