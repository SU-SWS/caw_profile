(function ($, Drupal, once) {
  Drupal.behaviors.cawFaq = {
    attach: function attach(context) {
      $(once('faq-expand-all', '.ptype-stanford-faq', context)).each((index, faq) => {
        const $details = $('details', faq);
        if ($details.length < 2) {
          return;
        }

        const $button = $(
          '<button class="expand-collapse-button expand-all su-button--secondary">' +
          '<span class="expand-collapse">Expand</span> All' +
          '<span class="visually-hidden"> Items below.</span>' +
          '</button>'
        );
        $button.click(function () {
          $button.toggleClass('expand-all').toggleClass('collapse-all');
          const expanded = !$button.hasClass('expand-all')

          $('span', $button).text(expanded ? 'Collapse' : 'Expand');
          $details.each((i, detail) => {
            $(detail).attr('open', expanded);
            $('summary', detail).attr('aria-expanded', expanded)
              .attr('aria-pressed', expanded);
          })
        })

        const $headline = $('.su-faq-headline', faq);
        if ($headline.length) {
          $headline.append($('<div class="button-wrapper">').append($button));
        }
        else {
          $(faq).prepend($('<div class="button-wrapper clearfix">').append($button));
        }
      })
    }
  };
})(jQuery, Drupal, once);
