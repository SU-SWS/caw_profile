
(function ($, Drupal) {
  Drupal.behaviors.bookDetailsSummaries = {
    attach: function attach(context) {
      $(context).find('.book-outline-form').drupalSetSummary(function (context) {
        var $select = $(context).find('.book-title-select');
        var val = $select.val();

        if (val === '0') {
          return Drupal.t('Not in Subsite');
        }

        if (val === 'new') {
          return Drupal.t('New Subsite');
        }
        return Drupal.checkPlain($select.find(':selected').text());
      });
    }
  };
})(jQuery, Drupal);
