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

      // Add change listener to disable options that don't have any results.
      $('.view.caw-benefits.filtering-list select[name="available"]')
        .once('select-benefits')
        .on('change', e => disableSelectOptions(e.currentTarget))
        .each((i, select) => disableSelectOptions(select));
      const $view = $('.view.caw-benefits.filtering-list');

      $view.once('here')
        .find('.rows ul')
        .each((i, list) => {
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

          const $submit = $('<input>')
            .attr('type', 'submit')
            .attr('disabled', 'true')
            .attr('value', 'Compare Selected Plans')
            .click(() => {
              const selectedItems = $.map($('input:checked', $list), checkbox => checkbox.value)

              // Cloning and adding to the head of the table when plans
              // selected.
              const $headerInfoClear = $($clearAll).clone();
              $headerInfoClear.click(() => location.reload())

              const $headerInfoSummary = $($summary).clone();
              const $headerInfo = $('<div class="comparison-table-plan-names"></div>')
                .html('<h2>Comparing Results For</h2>')
                .append($headerInfoClear)
                .append($headerInfoSummary);

              $($headerInfo).insertBefore('.comparison-table--wrapper');
              $('.comparison-table-plan-names,.comparison-table--wrapper').wrapAll('<div class="header-info-wrap"></div>');

              Object.keys(drupalSettings.views.ajaxViews).forEach(domId => {
                const view = drupalSettings.views.ajaxViews[domId];
                if (view.view_display_id === 'comparison') {
                  view.view_args = selectedItems.join('+');
                  $(`.js-view-dom-id-${view.view_dom_id}`).triggerHandler('RefreshView');
                }
              })
            });

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
