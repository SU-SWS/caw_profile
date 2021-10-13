<?php

namespace Drupal\node_edit_link\Services;

use Drupal\Component\Utility\EmailValidatorInterface;
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
    $mail_hash = $current_request_query->get('mail', FALSE);
    // The mail query parameter is used in conjunction with the node id to load
    // the cache. Then the cached csrf token must match the token in the query
    // to be allowed access to the node.
    if ($mail_hash && $cache = $this->cache->get($this->getCid($node, $mail_hash))) {
      return $cache->data['csrf'] == $current_request_query->get('edit-token');
    }
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function createCsrfToken(NodeInterface $node, $mail, $expires = 604800): string {
    $token = $this->csrfToken->get(time() . $mail);
    // Create a token and save it in cache for reference later.
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
    $form['#attributes']['class'][] = 'centered-container';
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

    if ($mail = $this->currentRequest->query->get('mail')) {
      // Store the mail hash query into storage so that we can clear the cached
      // token upon submitting.
      $form_state->set('node_edit_link', $mail);
      $form['revision_information']['#access'] = FALSE;
    }
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
    /** @var \Drupal\node\NodeForm $node_form */
    $node_form = $form_state->getBuildInfo()['callback_object'];
    /** @var \Drupal\node\NodeInterface $node */
    $node = $node_form->getEntity();
    $email = $form_state->getValue(['node_edit_link', 'email']);

    // First clear the current token that the user used.
    if ($mail = $form_state->get('node_edit_link')) {
      $this->clearCsrfToken($node, $mail);
    }

    if (empty($email)) {
      return;
    }

    // Construct an absolute url to email to the user with the appropriate query
    // parameters.
    $url = $node_form->getEntity()->toUrl('edit-form', [
      'query' => [
        'edit-token' => $this->createCsrfToken($node, $email),
        'mail' => self::getEmailHash($email),
      ],
      'absolute' => TRUE,
    ])->toString();

    $params = [
      'context' => [
        'message' => 'This is a test attempt: ' . $url,
        'subject' => $node->label(),
      ],
    ];

    // Display a message to the current admin and send out the email to the
    // provided email address.
    \Drupal::messenger()->addStatus($this->t('One time edit link: %link', ['%link' => $url]));
    $this->mailManager->mail('system', $node->id(), $email, 'en', $params, NULL, TRUE);
  }

  /**
   * Get a cache id string for the given node and email address.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity object.
   * @param string $mail
   *   The email address or the hashed email address.
   *
   * @return string
   *   Cache ID string.
   */
  protected function getCid(NodeInterface $node, string $mail): string {
    $cid = 'node_edit_link:' . $node->id();
    return $this->emailValidator->isValid($mail) ? $cid . ':' . self::getEmailHash($mail) : "$cid:$mail";
  }

  /**
   * Hash an email address and return a substring of it.
   *
   * @param string $mail
   *   Email address.
   *
   * @return string
   *   Shortened hash of the email.
   */
  protected static function getEmailHash(string $mail): string {
    return substr(md5($mail), 0, 5);
  }

}
