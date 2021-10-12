<?php

namespace Drupal\node_edit_link\Services;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
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
   * Current user account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Email validation service.
   *
   * @var \Drupal\Component\Utility\EmailValidatorInterface
   */
  protected $emailValidator;

  /**
   * {@inheritDoc}
   */
  public function __construct(RequestStack $request_stack, CsrfTokenGenerator $csrf_token, CacheBackendInterface $cache, MailManagerInterface $mail_manager, AccountProxyInterface $current_user, EmailValidatorInterface $mail_validator) {
    $this->currentRequest = $request_stack->getCurrentRequest();
    $this->csrfToken = $csrf_token;
    $this->cache = $cache;
    $this->mailManager = $mail_manager;
    $this->currentUser = $current_user;
    $this->emailValidator = $mail_validator;
  }

  /**
   * {@inheritDoc}
   */
  public function checkAccess(NodeInterface $node): bool {
    $current_request_query = $this->currentRequest->query;
    if (!$current_request_query->has('mail')) {
      return FALSE;
    }

    $mail_hash = $current_request_query->get('mail');
    if ($cache = $this->cache->get($this->getCid($node, $mail_hash))) {
      return $cache->data['csrf'] == $current_request_query->get('edit-token');
    }
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function createCsrfToken(NodeInterface $node, $mail, $expires = 604800): string {
    $token = $this->csrfToken->get(time() . $mail);
    $this->cache->set($this->getCid($node, $mail), ['csrf' => $token], time() + $expires, ['node_edit_link:' . $node->id()]);
    return $token;
  }

  /**
   * {@inheritDoc}
   */
  public function clearCsrfToken(NodeInterface $node, $mail): void {
    $this->cache->delete($this->getCid($node, $mail));
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
      '#access' => $this->currentUser->hasPermission('send one time edit link'),
    ];
    $form['node_edit_link']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Address'),
      '#description' => $this->t('Send a one time edit link to the provided email address. Valid for 7 days'),
    ];
    $form['actions']['submit']['#submit'][] = [$this, 'submitNodeForm'];
  }

  /**
   * Node form submit handler to send email with one time login link.
   *
   * @param array $form
   *   Complete form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Submitted form state.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  public function submitNodeForm(array $form, FormStateInterface $form_state) {
    $email = $form_state->getValue(['node_edit_link', 'email']);
    if (empty($email)) {
      return;
    }

    /** @var \Drupal\node\NodeForm $node_form */
    $node_form = $form_state->getBuildInfo()['callback_object'];
    /** @var \Drupal\node\NodeInterface $node */
    $node = $node_form->getEntity();
    $url = $node_form->getEntity()->toUrl('edit-form', [
      'query' => [
        'edit-token' => $this->createCsrfToken($node, $email),
        'mail' => self::getEmailHash($email),
      ],
      'absolute' => TRUE,
    ])->toString();

    $params = [
      'message' => 'This is a test attempt: ' . $url,
      'subject' => $node->label(),
    ];

    \Drupal::messenger()->addStatus($url);
    $this->mailManager->mail('php', $node->id(), $email, 'en', $params, NULL, TRUE);
  }

  protected function getCid(NodeInterface $node, $mail) {
    $cid = 'node_edit_link:' . $node->id();
    return $this->emailValidator->isValid($mail) ? $cid . ':' . self::getEmailHash($mail) : "$cid:$mail";
  }

  protected static function getEmailHash($mail) {
    return substr(md5($mail), 0, 5);
  }

}
