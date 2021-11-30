<?php

namespace Drupal\caw_profile_helper\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Class JobDescriptionRedirectController.
 *
 * @codeCoverageIgnore
 */
class JobDescriptionRedirectController extends ControllerBase {

  /**
   * Redirect after authentication.
   *
   * @return string
   *   Return Hello string.
   */
  public function view() {
    return new TrustedRedirectResponse('https://stanford.taleo.net/careersection/jdl/moresearch.ftl');
  }

}
