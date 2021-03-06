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
        .append($('<a>', {
          text: 'Clear All',
          href: '#'
        }).click(() => location.reload()))
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
            const $availableSelect = $('select[name="available"]', $view);
            const $available = $(`option[value="${$availableSelect.val()}"]`, $availableSelect).text();
            const $type = $('h2', $list.closest('.rows')).text() + ' ' + Drupal.t('Plans');

            $headerSummary.html(`${$available}, ${$type}:` +'<br/>' + $summary.html());

            $('.attachment-before', $view).addClass('header-info-wrap');

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
            const $label = $('<span>', {class: 'label', text: 'Compare'});
            $label.uniqueId();

            const $checkbox = $('<input>')
              .attr('type', 'checkbox')
              .attr('aria-labelledby', $label.attr('id') + ' ' +  $title.attr('id'))
              .attr('value', $item.attr('data-nid'))
              .on('change', () => {
                $item.toggleClass('selected', $checkbox.is(':checked'));
                $checkbox.parent().find('.label').text($checkbox.is(':checked') ? 'Selected' : 'Compare')

                const selectedItems = $.map($('input:checked', $list), a => $('h3', $(a).closest('li')).text())
                $info.find('.num-selected').text(selectedItems.length);

                const $ul = $('<ul>', { class: 'selected-items su-list-unstyled' }).append(
                  selectedItems.map(item => $("<li>").text(item))
                );
                $summary.html($ul);

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
              .append($('<label>').append($label).append($checkbox));
            $item.prepend($selectElement);
          })

          const $submitWrapper = $('<div>').addClass('submit-wrapper').append($info).append($submit);
          $list.after($submitWrapper);
        })


      $('table', context).find('.group-header').each((i, tableCell) => {
        const headerText = $(tableCell).text().trim();
        const colspan = $(tableCell).closest('tr').find('td, th').length;
        const $groupHeader = $('<th>', {
          colspan: colspan,
          text: headerText,
          scope: 'colgroup'
        });
        $groupHeader.uniqueId();
        $(tableCell).closest('tr').addClass('group-header').empty().append($groupHeader);
      })

      $('table', context).once('header-ids').each((i, table) => {
        const $topHeaders = $('thead th', table)
        $topHeaders.uniqueId();
        $('tbody td', table).each((j, tableCell) => {
          const $tableCell = $(tableCell);
          const position = $(tableCell).prevAll().length;
          $tableCell.attr('headers', $tableCell.attr('headers') + ' ' + $($topHeaders[position]).attr('id'));
        })

        $('tr.group-header', table).each((j, headerRow) => {
          const $cell = $(headerRow).nextUntil('.group-header').find('td');
          $cell.attr('headers', $cell.attr('headers') + ' ' + $(headerRow).find('th').attr('id'));
        });
      })

      // Uncheck all options if the users hits the back button or refreshes.
      $(window).bind("pageshow", function() {
        $('input[type="checkbox"]:checked').click();
      });
    }
  };
})(jQuery, Drupal, drupalSettings);

