!function (n) {
  var o = {};

  function a(t) {
    if (o[t]) {
      return o[t].exports;
    }
    var e = o[t] = {i: t, l: !1, exports: {}};
    return n[t].call(e.exports, e, e.exports, a), e.l = !0, e.exports
  }

  a.m = n, a.c = o, a.d = function (t, e, n) {
    a.o(t, e) || Object.defineProperty(t, e, {enumerable: !0, get: n})
  }, a.r = function (t) {
    "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(t, "__esModule", {value: !0})
  }, a.t = function (e, t) {
    if (1 & t && (e = a(e)), 8 & t) {
      return e;
    }
    if (4 & t && "object" == typeof e && e && e.__esModule) {
      return e;
    }
    var n = Object.create(null);
    if (a.r(n), Object.defineProperty(n, "default", {
      enumerable: !0,
      value: e
    }), 2 & t && "string" != typeof e) {
      for (var o in e) {
        a.d(n, o, function (t) {
          return e[t]
        }.bind(null, o));
      }
    }
    return n
  }, a.n = function (t) {
    var e = t && t.__esModule ? function () {
      return t.default
    } : function () {
      return t
    };
    return a.d(e, "a", e), e
  }, a.o = function (t, e) {
    return Object.prototype.hasOwnProperty.call(t, e)
  }, a.p = "", a(a.s = 329)
}({
  318: function (t, e) {
    var n, o = document.getElementById("block-stanford-basic-local-tasks");
    o && (n = o.getBoundingClientRect().top, window.onscroll = function () {
      var t = 0;
      t = !0 === document.body.classList.contains("toolbar-tray-open") ? 79 : 39, window.pageYOffset >= n - t ? (o.classList.add("sticky"), o.style.marginTop = t + "px") : (o.classList.remove("sticky"), o.style.marginTop = "0px")
    })
  }, 319: function (t, e) {
    window.Drupal.behaviors.stanford_basic = {
      attach: function (t, e) {
        var o = jQuery, n = once;
        o("#main-content", t).length || o(".su-skipnav--content", t).attr("href", "#page-content"), o("#secondary-navigation", t).length || o(".su-skipnav--secondary", t).remove();
        var a, r = o(".su-masthead .su-site-search", t);

        function i() {
          o(window).scrollTop() >= 3 * o(window).height() ? o("#back-to-top").fadeIn() : o("#back-to-top").fadeOut()
        }

        r.length && ((a = r.clone()).addClass("search-block-form"), a.attr("id", "block-stanford-basic-search-mobile"), a.find("[id]").each(function (t, e) {
          var n = o(e).attr("id");
          a.find('[for="'.concat(n, '"]')).attr("for", "".concat(n, "-mobile")), o(e).attr("id", "".concat(n, "-mobile"))
        }), a.prependTo(".su-masthead .su-multi-menu > ul", t).wrap('<li class="su-mobile-site-search"></li>')), o("#block-stanford-basic-local-tasks", t).length && o(".page-content", t).addClass("stanford-basic--outline"), o(".page-user-login", t) && o(".su-back-to-site", t).removeClass("hidden"), i(), o(window).scroll(i), o(n("back-to-top", "#back-to-top", t)).click(function (t) {
          t.preventDefault(), o("html, body").animate({scrollTop: 0}, "slow"), o("#page-content").attr("tabIndex", "-1").focus()
        }), o(".topics__collapsable-menu", t).click(function () {
          o(this).toggleClass("show"), "none" != o(this).siblings(".menu").css("display") ? o(this).attr("aria-expanded", "true") : o(this).attr("aria-expanded", "false")
        })
      }, detach: function () {
      }
    }
  }, 329: function (t, e, n) {
    "use strict";
    n.r(e);
    n(318), n(319)
  }
});
//# sourceMappingURL=behaviors.js.map
