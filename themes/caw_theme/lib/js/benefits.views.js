(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.cawBenefits = {
    attach: function attach(context) {

      const disableSelectOptions = available => {
        const $available = $(available);
        const $type = $('.view.caw-benefits.filtering-list select[name="type"]');

        const availableIds = drupalSettings.cawBenefits.available[$available.val()];
        $('option', $type).each((j, option) => {
          const typeIds = drupalSettings.cawBenefits.type[$(option).attr('value')];
          const intersectionIds = availableIds.filter(value => typeIds.includes(value));

          if (intersectionIds.length === 0 && $(option).attr('value') !== 'All') {
            $(option).attr('disabled', true);
          }
          else {
            $(option).attr('disabled', false);
          }
        })
      };
      const $view = $('.view.caw-benefits.filtering-list');

      // Add change listener to disable options that don't have any results.
      $('select[name="available"]', $view)
        .once('select-benefits')
        .on('change', e => disableSelectOptions(e.currentTarget))
        .each((i, select) => disableSelectOptions(select));

      // The header is the comparison summary, but the actual text inside the
      // $headerSummary is updated on click, so set this variable for easier
      // display manipulation in the click handler.
      const $headerSummary = $('<div>', {class: 'summary'});
      const $headerInfo = $('<div>', {
        class: 'comparison-table-plan-names',
        html: '<h2>Comparing Results For</h2>'
      }).hide()
        .append($('<a>', {text: 'Clear All'}).click(() => location.reload()))
        .append($headerSummary);

      $('.attachment-before', $view).once('header-info').prepend($headerInfo);

      // Go through each list and add necessary markup and click handlers.
      $view.once('comparison-list')
        .find('.rows ul')
        .each((i, list) => {

          const $list = $(list);

          // A button to uncheck all the inputs.
          const $clearAll = $('<a>').attr('href', '#')
            .text('Clear All')
            .click(e => {
              e.preventDefault();
              $('input:checked', $list).each((i, input) => {
                $(input).click();
              })
            });

          const $summary = $('<div>', {class: 'summary'})

          const $info = $('<div>')
            .html('<strong><span class="num-selected">0</span> plans selected</strong>')
            .append($clearAll)
            .append($summary);

          // The Compare submit button that triggers the views ajax to fire.
          const $submit = $('<input>', {
            type: 'submit',
            disabled: true,
            value: Drupal.t('Compare Selected Plans')
          }).click(() => {
            // Get the id values from the checked input boxes.
            const selectedItems = $.map($('input:checked', $list), checkbox => checkbox.value)
            $headerInfo.show();
            $headerSummary.text($summary.text());
            $('.attachment-before', $view).addClass('header-info-wrap');
            // $('.comparison-table-plan-names, .comparison-table--wrapper').once('comparison-wrapper').wrapAll('<div class="header-info-wrap"></div>');

            Object.keys(drupalSettings.views.ajaxViews).forEach(domId => {
              const view = drupalSettings.views.ajaxViews[domId];
              if (view.view_display_id === 'comparison') {
                view.view_args = selectedItems.join('+');
                $(`.js-view-dom-id-${view.view_dom_id}`).triggerHandler('RefreshView');
              }
            })
          });

          // Add the input checkboxes to each view result.
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

                $submit.attr('disabled', null);

                if ($('input:checked', $list).length === 0) {
                  $view.find('input[type="checkbox"]').each((i, input) => {
                    $(input).attr('disabled', null);
                    $(input).closest('li').removeClass('disabled');
                  });
                }
                else {
                  $view.find('input[type="checkbox"]').each((i, input) => {
                    $(input).attr('disabled', 'true')
                    $(input).closest('li').addClass('disabled');
                  });
                  $list.find('input[type="checkbox"]').each((i, input) => {
                    $(input).attr('disabled', null)
                    $(input).closest('li').removeClass('disabled');
                  });
                }
              });

            const $selectElement = $('<div>')
              .addClass('compare-selector')
              .addClass('clearfix')
              .append($('<label>').html('<span class="label">Compare</span>').append($checkbox));
            $item.prepend($selectElement);
          })

          const $submitWrapper = $('<div>').addClass('submit-wrapper').append($info).append($submit);
          $list.after($submitWrapper);
        })
    }
  };
})(jQuery, Drupal, drupalSettings);
