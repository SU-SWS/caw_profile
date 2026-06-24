(function ($, Drupal, once) {
  Drupal.behaviors.cawBenefitsNode = {
    attach: function attach(context, settings) {
      const $expandAllButton = $('<button type="button" class="expand-all su-button--secondary">').html('Expand All<span class="visually-hidden"> details below</span><i class="fa-solid fa-plus"></i>');
      $expandAllButton.click(function () {
        if ($expandAllButton.text().includes('Expand')) {
          $('button[aria-expanded="false"]').click();
          $expandAllButton.html('Collapse All<span class="visually-hidden"> details below</span><i class="fa-solid fa-minus"></i>');
        } else {
          $('button[aria-expanded="true"]').click();
          $expandAllButton.html('Expand All<span class="visually-hidden"> details below</span><i class="fa-solid fa-plus"></i>');
        }
      });
      $(once('benefits-plan-expand-all', '.caw-benefits.plan-details h2', context)).after($expandAllButton);

      $(once('benefits-plan-accordions', '.views-element-container', context)).each(function () {
        $(this).closest('.views-block').addClass('accordion-block');
        const $heading = $(this).find('h3').wrapInner('<span class="button-text"></span>');
        const $accordionContent = $(this).find('.view.benefit-accordion');
        if (!$accordionContent.length) {
          return;
        }
        let id = $heading.text().toLowerCase().replaceAll(/[^a-z0-9-]/gi, '-');
        while ($(`#${id}`).length) {
          id += '-' + Date.now();
        }
        $accordionContent.attr('id', id)
          .attr('aria-labelledby', `${id}-heading`)
          .attr('role', 'region')
          .addClass('hidden');

        $heading.attr('id', `${id}-heading`);
        $heading.wrapInner('<button type="button" class="accordion-button"></button>');

        const $button = $heading.find('button');
        $button.append('<i class="fa-solid fa-plus"></i>');
        $button.attr('aria-expanded', 'false')
          .attr('aria-controls', id);

        $button.on('click', function () {
          $button.find('.fa-solid').toggleClass('fa-plus').toggleClass('fa-minus');
          $accordionContent.toggleClass('hidden');

          if ($button.attr('aria-expanded') === 'true') {
            $button.attr('aria-expanded', 'false');
          } else {
            $button.attr('aria-expanded', 'true');
          }
        });

      });
    },
  };
})(jQuery, Drupal, once);

