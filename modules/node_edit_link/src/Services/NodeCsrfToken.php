<?php

namespace Drupal\node_edit_link\Services;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;
use Drupal\node_edit_link\NodeCsrfTokenInterface;
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
  public function __construct(SessionManagerInterface $session_manager, RequestStack $request_stack, CsrfTokenGenerator $csrf_token, CacheBackendInterface $cache, MailManagerInterface $mail_manager, AccountProxyInterface $current_user, EmailValidatorInterface $mail_validator) {
    $this->sessionManager = $session_manager;
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
    $session = $this->currentRequest->getSession();
    // If the user has already started the session for the current node, they
    // should have access to it, until they submit the form.
    if (!$node->isNew() && $session->get('node_edit_link') == $node->id()) {
      return TRUE;
    }

    // The mail query parameter is used in conjunction with the node id to load
    // the cache. Then the cached CSRF token must match the token in the query
    // to be allowed access to the node. If all is satisfied, initiate a session
    // for the user. This is necessary so that they will be able to successfully
    // submit the node form.
    if ($mail_hash && $cache = $this->cache->get($this->getCid($node, $mail_hash))) {
      if ($cache->data['csrf'] == $current_request_query->get('edit-token')) {
        $this->sessionManager->start();
        $this->currentRequest->getSession()->set('node_edit_link', $node->id());
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritDoc}
   */
  public function createCsrfToken(NodeInterface $node, string $mail, int $expires = 604800): string {
    $token = $this->csrfToken->get(time() . $mail);
    // Create a token and save it in cache for reference later.
    $this->cache->set($this->getCid($node, $mail), ['csrf' => $token], time() + $expires, ['node_edit_link:' . $node->id()]);
    return $token;
  }

  /**
   * {@inheritDoc}
   */
  public function clearCsrfToken(NodeInterface $node, string $mail): void {
    $this->cache->delete($this->getCid($node, $mail));
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
