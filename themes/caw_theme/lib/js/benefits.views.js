(function ($, Drupal) {
  Drupal.behaviors.cawBenefits = {
    attach: function attach(context) {
      $('.caw-benefits.filtering-list').once('comparison').find('ul').each((i, list) => {

        const $list = $(list);
        const $clearAll = $('<a>').attr('href', '#')
          .text('Clear All')
          .click(e => {
            e.preventDefault();
            $('input:checked', $list).each((i, input) => {
              $(input).click();
            })
          });
        const $summary = $('<div>').addClass('summary');
        const $info = $('<div>')
          .html('<strong><span class="num-selected">0</span> plans selected</strong>')
          .append($clearAll)
          .append($summary);

        $list.children('li').each((j, item) => {
          const $item = $(item);
          const $title = $('h3', $item).uniqueId()

          const $checkbox = $('<input>')
            .attr('type', 'checkbox')
            .attr('aria-labelledby', $title.attr('id'))
            .attr('value', $item.attr('data-nid'))
            .on('change', () => {
              $item.toggleClass('selected', $checkbox.is(':checked'));
              $checkbox.parent().find('.label').text($checkbox.is(':checked') ? 'Selected' : 'Compare')

              const selectedItems = $.map($('input:checked', $list), () => $('h3', $item).text())
              $info.find('.num-selected').text(selectedItems.length);
              $summary.text(selectedItems.join(', '));
            });

          const $selectElement = $('<div>')
            .addClass('compare-selector')
            .addClass('clearfix')
            .append($('<label>').html('<span class="label">Compare</span>').append($checkbox));
          $item.prepend($selectElement);
        })

        const $submit = $('<input>').attr('type', 'submit').attr('value', 'Compare Selected Plans').click(() => {
          const selectedItems = $.map($('input:checked', $list), checkbox => checkbox.value)

          Object.keys(drupalSettings.views.ajaxViews).forEach(domId => {
            const view = drupalSettings.views.ajaxViews[domId];
            if (view.view_display_id === 'comparison') {
              view.view_args = selectedItems.join('+');
              $(`.js-view-dom-id-${view.view_dom_id}`).triggerHandler('RefreshView');
            }
          })
        });

        const $submitWrapper = $('<div>').addClass('submit-wrapper').append($info).append($submit);
        $list.after($submitWrapper);
      })
    }
  };
})(jQuery, Drupal);
