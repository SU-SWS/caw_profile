<?php

namespace Drupal\node_edit_link\Services;

use Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use Drupal\node_edit_link\NodeCsrfTokenInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class NodeCsrfToken Service.
 */
class NodeCsrfToken implements NodeCsrfTokenInterface {

  use StringTranslationTrait;

  /**
   * Current request stack service.
   *
   * @var \Symfony\Component\HttpFoundation\Request|null
   */
  protected $currentRequest;

  /**
   * CSRF Token service.
   *
   * @var \Drupal\Core\Access\CsrfTokenGenerator
   */
  protected $csrfToken;

  /**
   * Default cache service.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * Mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * {@inheritDoc}
   */
  public function __construct(RequestStack $request_stack, CsrfTokenGenerator $csrf_token, CacheBackendInterface $cache, MailManagerInterface $mail_manager) {
    $this->currentRequest = $request_stack->getCurrentRequest();
    $this->csrfToken = $csrf_token;
    $this->cache = $cache;
    $this->mailManager = $mail_manager;
  }

  /**
   * {@inheritDoc}
   */
  public function checkAccess(NodeInterface $node): bool {
    if ($cache = $this->cache->get('node_edit_link:' . $node->id())) {
      return $cache->data['csrf'] == $this->currentRequest->query->get('edit-token');
    }
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function createCsrfToken(NodeInterface $node, $expires = 604800): string {
    $token = $this->csrfToken->get(time());
    $this->cache->set('node_edit_link:' . $node->id(), ['csrf' => $token], time() + $expires);
    return $token;
  }

  /**
   * {@inheritDoc}
   */
  public function clearCsrfToken(NodeInterface $node): void {
    $this->cache->delete('node_edit_link:' . $node->id());
  }

  /**
   * {@inheritDoc}
   */
  public function addFormElements(array &$form, FormStateInterface $form_state): void {
    $form['node_edit_link'] = [
      '#type' => 'details',
      '#title' => $this->t('One-Time Edit Link'),
      '#group' => 'advanced',
      '#tree' => TRUE,
    ];
    $form['node_edit_link']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Address'),
      '#description' => $this->t('Send a one time edit link to the provided email address. Valid for 7 days'),
    ];
    $form['actions']['submit']['#submit'][] = [$this, 'submitNodeForm'];
  }

  public function submitNodeForm($form, FormStateInterface $form_state) {
    //    dpm($form_state->getValues());

  }

}
