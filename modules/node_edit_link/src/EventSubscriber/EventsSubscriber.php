<?php

namespace Drupal\node_edit_link\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class EventsSubscriber.
 */
class EventsSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => 'onKernelRequest',
    ];
  }

  /**
   * On a kernel request, check if the user is on the expected edit page.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   Triggered event.
   */
  public function onKernelRequest(RequestEvent $event) {
    if ($session = $event->getRequest()->getSession()) {
      $node_edit_link = $session->get('node_edit_link');

      if ($node_edit_link) {
        $uri = $event->getRequest()->getRequestUri();

        // If the user had ventured off the node edit form, clear out the
        // session that they started when loading the form.
        if (strpos($uri, "/node/$node_edit_link/edit") === FALSE) {
          $session->clear();
        }
      }
    }
  }

}
