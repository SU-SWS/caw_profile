(function ($, Drupal, once) {
  Drupal.behaviors.cawBenefits = {
    attach: function attach(context, settings) {

      const $table = $('table', context)
      if ($(context).hasClass('comparison-table--wrapper') && $table.length > 0) {
        $(context).attr('role', 'region')
          .attr('aria-label', 'Comparison Chart')
          .attr('tabindex', '0')
          .focus();
      }

      const disableSelectOptions = available => {
        const $available = $(available);
        const $type = $('.view.caw-benefits.filtering-list select[name="type"]');

        const availableIds = settings.cawBenefits.available[$available.val()];
        $('option', $type).each((j, option) => {
          const typeIds = settings.cawBenefits.type[$(option).attr('value')];
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
      $(once('select-benefits', 'select[name="available"]', $view.get(0)))
        .on('change', e => disableSelectOptions(e.currentTarget))
        .each((i, select) => disableSelectOptions(select));

      // The header is the comparison summary, but the actual text inside the
      // $headerSummary is updated on click, so set this variable for easier
      // display manipulation in the click handler.
      const $headerSummary = $('<div>', {class: 'summary'});
      const $headerInfo = $('<div>', {
        class: 'comparison-table-plan-names',
        'aria-live': 'polite',
        html: '<h2>Comparing Results For</h2>'
      }).hide()
        .append($('<a>', {
          text: 'Clear All',
          href: '#'
        }).click(() => location.reload()))
        .append($headerSummary);

      $(once('header-info', '.attachment-before', $view.get(0))).prepend($headerInfo);

      // Go through each list and add necessary markup and click handlers.
      $(once('comparison-list', $view.get(0))).find('.rows ul')
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

          const $summary = $('<div>', {class: 'summary', 'aria-live': 'polite', 'aria-atomic': 'true'});

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

            Object.keys(settings.views.ajaxViews).forEach(domId => {
              const view = settings.views.ajaxViews[domId];
              if (view.view_display_id === 'comparison') {
                view.view_args = selectedItems.join('+');
                $(`.js-view-dom-id-${view.view_dom_id}`).triggerHandler('RefreshView');
              }
            })
          });

          // Add the input checkboxes to each view result.
          $list.children('li').each((j, item) => {
            const $item = $(item);

            const $title = $('h3', $item).each((i, elem) => {
              $(elem).attr('id', 'comparison-item-title-' + Math.random().toString(36).substr(2, 9));
            });


            const $label = $('<span>', {class: 'label', text: 'Compare'});
            $label.each((i, elem) => {
              $(elem).attr('id', 'comparison-item-label-' + Math.random().toString(36).substr(2, 9));
            });


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

                $checkbox.focus();
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
        $groupHeader.each((i, elem) => {
          $(elem).attr('id', 'group-header-' + Math.random().toString(36).substr(2, 9));
        });

        $(tableCell).closest('tr').addClass('group-header').empty().append($groupHeader);
      })

      $(once('header-ids', 'table', context)).each((i, table) => {
        const $topHeaders = $('thead th', table)

        $topHeaders.each((i, elem) => {
          $(elem).attr('id', 'top-header-' + Math.random().toString(36).substr(2, 9));
        });

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
})(jQuery, Drupal, once);

