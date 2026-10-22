<?php

namespace Drupal\node_edit_link\Services;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\KeyValueStore\KeyValueExpirableFactoryInterface;
use Drupal\Core\KeyValueStore\KeyValueStoreExpirableInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use Drupal\node_edit_link\NodeCsrfTokenInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class NodeCsrfToken Service.
 */
class NodeCsrfToken implements NodeCsrfTokenInterface {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * Core session manager service.
   *
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  protected $sessionManager;

  /**
   * Request stack service.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Expirable key value storage for the edit tokens.
   *
   * @var \Drupal\Core\KeyValueStore\KeyValueStoreExpirableInterface
   */
  protected KeyValueStoreExpirableInterface $tokenStorage;

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
  public function __construct(SessionManagerInterface $session_manager, RequestStack $request_stack, KeyValueExpirableFactoryInterface $key_value_expirable, MailManagerInterface $mail_manager, AccountProxyInterface $current_user, EmailValidatorInterface $mail_validator) {
    $this->sessionManager = $session_manager;
    $this->requestStack = $request_stack;
    $this->tokenStorage = $key_value_expirable->get('node_edit_link');
    $this->mailManager = $mail_manager;
    $this->currentUser = $current_user;
    $this->emailValidator = $mail_validator;
  }

  /**
   * {@inheritDoc}
   */
  public function checkAccess(NodeInterface $node): bool {
    $request = $this->requestStack->getCurrentRequest();
    // Edit links only ever grant access to the node's edit form, never to any
    // other route that checks for update access, such as REST.
    if ($node->isNew() || !$request || !self::isNodeEditRoute($request, $node->id()) || !$request->hasSession()) {
      return FALSE;
    }

    $session = $request->getSession();
    // A session can't be started once the response headers are sent, such as
    // when nodes are loaded during kernel termination.
    if (!$session->isStarted() && headers_sent()) {
      return FALSE;
    }

    // If the user has already started the session for the current node, they
    // should have access to it, until they submit the form.
    if ($session->get('node_edit_link') == $node->id()) {
      return TRUE;
    }

    // The mail query parameter is used in conjunction with the node id to load
    // the cache. Then the cached CSRF token must match the token in the query
    // to be allowed access to the node. If all is satisfied, initiate a session
    // for the user. This is necessary so that they will be able to successfully
    // submit the node form.
    $mail_hash = $request->query->get('mail');
    $token = $request->query->get('edit-token');
    if (!is_string($mail_hash) || !is_string($token) || $mail_hash === '' || $token === '') {
      return FALSE;
    }

    $stored_token = $this->tokenStorage->get($this->getCid($node, $mail_hash));
    if (is_string($stored_token) && hash_equals($stored_token, $token)) {
      $this->sessionManager->start();
      $session->set('node_edit_link', $node->id());
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Check if the request is for the given node's edit form.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request object.
   * @param int|string $nid
   *   Node ID.
   *
   * @return bool
   *   If the request is for the node edit form.
   */
  public static function isNodeEditRoute(Request $request, int|string $nid): bool {
    if ($request->attributes->get('_route') !== 'entity.node.edit_form') {
      return FALSE;
    }
    $raw_variables = $request->attributes->get('_raw_variables');
    return $raw_variables instanceof ParameterBag && (string) $raw_variables->get('node') === (string) $nid;
  }

  /**
   * {@inheritDoc}
   */
  public function createCsrfToken(NodeInterface $node, string $mail, int $expires = 604800): string {
    $token = Crypt::randomBytesBase64(32);
    // Save the token in the expirable storage for reference later.
    $this->tokenStorage->setWithExpire($this->getCid($node, $mail), $token, $expires);
    return $token;
  }

  /**
   * {@inheritDoc}
   */
  public function clearCsrfToken(NodeInterface $node, string $mail): void {
    $this->tokenStorage->delete($this->getCid($node, $mail));
  }

  /**
   * {@inheritDoc}
   */
  public function sendEmail(NodeInterface $node, string $token, string $mail, ?string $message = NULL) {
    // Construct an absolute url to email to the user with the appropriate query
    // parameters.
    $url = $node->toUrl('edit-form', [
      'query' => ['edit-token' => $token, 'mail' => self::getEmailHash($mail)],
      'absolute' => TRUE,
    ])->toString();

    $params = [
      'context' => ['message' => $message, 'subject' => $node->label()],
    ];

    // Display a message to the current admin and send out the email to the
    // provided email address.
    self::messenger()
      ->addStatus($this->t('One time edit link: %link', ['%link' => $url]));

    try {
      $this->mailManager->mail('system', $node->id(), $mail, 'en', $params, NULL, TRUE);
    }
    catch (\Exception $e) {
      // Do nothing. If the email fails to send, a message is already provided
      // to the user.
    }
  }

  /**
   * Get a storage key for the given node and email address.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity object.
   * @param string $mail
   *   The email address or the hashed email address.
   *
   * @return string
   *   Storage key string.
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
