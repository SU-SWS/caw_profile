var HYVOR_TALK_WEBSITE = 0;
var HYVOR_TALK_CONFIG = {url: false, id: false, sso: {}};

(($, Drupal, drupalSettings) => {
  Drupal.behaviors.cawProfileHelperHyvor = {
    attach: function attach(context, settings) {
      HYVOR_TALK_CONFIG = drupalSettings.caw_profile_helper.hyvor.config;
      HYVOR_TALK_WEBSITE = drupalSettings.caw_profile_helper.hyvor.id;

      $.ajax({
        type: "GET",
        url: "//talk.hyvor.com/web-api/embed.js",
        dataType: "script"
      });
    },
  };
})(jQuery, Drupal, drupalSettings);
