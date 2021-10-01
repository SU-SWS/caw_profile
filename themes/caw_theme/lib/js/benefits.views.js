(function ($, Drupal) {
  Drupal.behaviors.cawBenefits = {
    attach: function attach(context) {
      $('.caw-benefits.list').once('comparison').find('ul').each((i, list) => {
        const $list = $(list);
        $list.children('li').each((j, item) => {
          const $item = $(item);
          const $title = $('h3', $item).uniqueId()

          const $checkbox = $('<input>')
            .attr('type', 'checkbox')
            .attr('aria-labelledby', $title.attr('id'))
            .attr('value', $item.attr('data-nid'))
            .on('change', () => {
              $item.toggleClass('selected', $checkbox.is(':checked'));
            });

          const $selectElement = $('<div>')
            .addClass('compare-selector')
            .addClass('clearfix')
            .append($('<label>').append($checkbox));
          $item.prepend($selectElement);
        })

        const $submit = $('<input>').attr('type', 'submit').attr('value', 'Compare').click(() => {
          const selectedItems = $.map($('input:checked', $list), checkbox => checkbox.value)

          Object.keys(drupalSettings.views.ajaxViews).forEach(domId => {
            const view = drupalSettings.views.ajaxViews[domId];
            if (view.view_display_id === 'comparison') {
              view.view_args = selectedItems.join('+');
              $(`.js-view-dom-id-${view.view_dom_id}`).triggerHandler('RefreshView');
            }
          })
        });
        $list.append($submit);
      })
    }
  };
})(jQuery, Drupal);
