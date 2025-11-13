(function (a) {
    function h() {
        var i = jQuery("body").innerWidth();
        i += c();
        if (i > 991) {
            a(".azeroth-menu-wapper").each(function () {
                if (a(this).length > 0) {
                    var j = a(this);
                    if (j != "undefined") {
                        var l = 0, k = j.offset();
                        l = j.innerWidth();
                        setTimeout(function () {
                            a(".main-menu .item-megamenu").each(function (p, o) {
                                a(o).children(".megamenu").css({"max-width": l + "px"});
                                var v = a(o).children(".megamenu").outerWidth(), r = a(o).outerWidth(), m = k.left,
                                    n = (m + l), q = a(o).offset().left, t = (v / 2 > (q - m)), u = ((v / 2 + q) > n);
                                a(o).children(".megamenu").css({left: "-" + (v / 2 - r / 2) + "px"});
                                if (t) {
                                    var s = (q - m);
                                    a(o).children(".megamenu").css({left: -s + "px"})
                                }
                                if (u && !t) {
                                    var s = (q - m);
                                    s = s - (l - v);
                                    a(o).children(".megamenu").css({left: -s + "px"})
                                }
                            })
                        }, 100)
                    }
                }
            })
        }
    }

    function b() {
        var i = parseInt(a(".container").innerWidth()) - 30;
        a(".azeroth-menu-wapper.vertical.support-mega-menu").each(function () {
            var j = parseInt(a(this).actual("width")), k = (i - j);
            if (k > 0) {
                a(this).find(".megamenu").each(function () {
                    var l = a(this).attr("style");
                    l = (l == undefined) ? "" : l;
                    l = l + " max-width:" + k + "px;";
                    a(this).attr("style", l)
                })
            }
        })
    }

    function c() {
        var i = jQuery('<div style="width:100%;height:200px;">test</div>'),
            j = jQuery('<div style="width:200px;height:150px;position:absolute;top:0;left:0;visibility:hidden;overflow:hidden;"></div>').append(i),
            k = i[0], l = j[0];
        jQuery("body").append(l);
        var m = k.offsetWidth;
        j.css("overflow", "scroll");
        var n = l.clientWidth;
        j.remove();
        return (m - n)
    }

    function f() {
        if (!a(".azeroth-menu-clone-wrap").length && a(".azeroth-clone-mobile-menu").length > 0) {
            a("body").prepend('<div class="azeroth-menu-clone-wrap"><div class="azeroth-menu-panels-actions-wrap"><a class="azeroth-menu-close-btn azeroth-menu-close-panels" href="#">x</a></div><div class="azeroth-menu-panels"></div></div>')
        }
        var j = 0, k = Array();
        if (!a(".azeroth-menu-clone-wrap .azeroth-menu-panels #azeroth-menu-panel-main").length) {
            a(".azeroth-menu-clone-wrap .azeroth-menu-panels").append('<div id="azeroth-menu-panel-main" class="azeroth-menu-panel azeroth-menu-panel-main"><ul class="depth-01"></ul></div>')
        }
        a(".azeroth-clone-mobile-menu").each(function () {
            var i = a(this), p = i, m = p.attr("id"), l = "azeroth-menu-clone-" + m;
            if (!a("#" + l).length) {
                var n = i.clone(true);
                n.find(".menu-item").addClass("clone-menu-item");
                n.find("[id]").each(function () {
                    n.find('.vc_tta-panel-heading a[href="#' + a(this).attr("id") + '"]').attr("href", "#" + e(a(this).attr("id"), "azeroth-menu-clone-"));
                    n.find('.azeroth-menu-tabs .tabs-link a[href="#' + a(this).attr("id") + '"]').attr("href", "#" + e(a(this).attr("id"), "azeroth-menu-clone-"));
                    a(this).attr("id", e(a(this).attr("id"), "azeroth-menu-clone-"))
                });
                n.find(".azeroth-menu-menu").addClass("azeroth-menu-menu-clone");
                var o = a(".azeroth-menu-clone-wrap .azeroth-menu-panels #azeroth-menu-panel-main ul");
                o.append(n.html());
                d(o, j)
            }
        })
    }

    function d(j, k) {
        if (j.find(".menu-item-has-children").length) {
            j.find(".menu-item-has-children").each(function () {
                var m = a(this);
                d(m, k);
                var i = "azeroth-menu-panel-" + k;
                while (a("#" + i).length) {
                    k++;
                    i = "azeroth-menu-panel-" + k
                }
                m.prepend('<a class="azeroth-menu-next-panel" href="#' + i + '" data-target="#' + i + '"></a>');
                var l = a("<div>").append(m.find("> .submenu").clone()).html();
                m.find("> .submenu").remove();
                a(".azeroth-menu-clone-wrap .azeroth-menu-panels").append('<div id="' + i + '" class="azeroth-menu-panel azeroth-menu-sub-panel azeroth-menu-hidden">' + l + "</div>")
            })
        }
    }

    function e(j, i) {
        return i + j
    }

    function g(i, k) {
        var j = new RegExp(i + "=([^&]*)", "i").exec(k);
        return j && j[1] || ""
    }

    a(document).ready(function () {
        h();
        b();
        a(document).on("click", ".menu-toggle", function () {
            a(".azeroth-menu-clone-wrap").addClass("open");
            return false
        });
        a(document).on("click", ".azeroth-menu-clone-wrap .azeroth-menu-close-panels", function () {
            a(".azeroth-menu-clone-wrap").removeClass("open");
            return false
        });
        a(document).on("click", function (i) {
            if (i.offsetX > a(".azeroth-menu-clone-wrap").width()) {
                a(".azeroth-menu-clone-wrap").removeClass("open")
            }
        });
        a(document).on("click", ".azeroth-menu-next-panel", function (j) {
            var i = a(this), n = i.closest(".menu-item"), o = i.closest(".azeroth-menu-panel"), m = i.attr("href");
            if (a(m).length) {
                o.addClass("azeroth-menu-sub-opened");
                a(m).addClass("azeroth-menu-panel-opened").removeClass("azeroth-menu-hidden").attr("data-parent-panel", o.attr("id"));
                var l = n.find(".azeroth-menu-item-title").attr("title"), k = "";
                if (a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").length > 0) {
                    k = a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").html()
                }
                if (typeof l != "undefined" && typeof l != false) {
                    if (!a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").length) {
                        a(".azeroth-menu-panels-actions-wrap").prepend('<span class="azeroth-menu-current-panel-title"></span>')
                    }
                    a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").html(l)
                } else {
                    a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").remove()
                }
                a(".azeroth-menu-panels-actions-wrap .azeroth-menu-prev-panel").remove();
                a(".azeroth-menu-panels-actions-wrap").prepend('<a data-prenttitle="' + k + '" class="azeroth-menu-prev-panel" href="#' + o.attr("id") + '" data-cur-panel="' + m + '" data-target="#' + o.attr("id") + '"></a>')
            }
            j.preventDefault()
        });
        a(document).on("click", ".azeroth-menu-prev-panel", function (k) {
            var i = a(this), j = i.attr("data-cur-panel"), n = i.attr("href");
            a(j).removeClass("azeroth-menu-panel-opened").addClass("azeroth-menu-hidden");
            a(n).addClass("azeroth-menu-panel-opened").removeClass("azeroth-menu-sub-opened");
            var m = a(n).attr("data-parent-panel");
            if (typeof m == "undefined" || typeof m == false) {
                a(".azeroth-menu-panels-actions-wrap .azeroth-menu-prev-panel").remove();
                a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").remove()
            } else {
                a(".azeroth-menu-panels-actions-wrap .azeroth-menu-prev-panel").attr("href", "#" + m).attr("data-cur-panel", n).attr("data-target", "#" + m);
                var l = a("#" + m).find('.azeroth-menu-next-panel[data-target="' + n + '"]').closest(".menu-item").find(".azeroth-menu-item-title").attr("data-title");
                l = a(this).data("prenttitle");
                if (typeof l != "undefined" && typeof l != false) {
                    if (!a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").length) {
                        a(".azeroth-menu-panels-actions-wrap").prepend('<span class="azeroth-menu-current-panel-title"></span>')
                    }
                    a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").html(l)
                } else {
                    a(".azeroth-menu-panels-actions-wrap .azeroth-menu-current-panel-title").remove()
                }
            }
            k.preventDefault()
        })
    });
    a(window).on("resize", function () {
        h();
        b()
    });
    a(window).load(function () {
        f()
    })
})(jQuery);