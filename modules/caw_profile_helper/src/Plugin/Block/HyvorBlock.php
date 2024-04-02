<?php

namespace Drupal\caw_profile_helper\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\Url;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an subsite secondary navigation menu.
 *
 * @Block(
 *   id = "hyvor",
 *   admin_label = @Translation("Hyvor Commenting"),
 *   category = @Translation("CAW")
 * )
 */
class HyvorBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * Block constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   Current active user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager service
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   Route match service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, protected AccountInterface $currentUser, protected EntityTypeManagerInterface $entityTypeManager,protected  RouteMatchInterface $routeMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritDoc}
   */
  public function build() {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->routeMatch->getParameter('node');
    $hyvor_key = Settings::get('hyvor_talk_private_key');

    if (!($node instanceof NodeInterface && $hyvor_key)) {
      return [];
    }

    // Encrypt the user's data.
    // @link https://talk.hyvor.com/docs/sso-stateless
    $userData = $this->getUserData();
    $encodedUserData = $userData ? base64_encode(json_encode($userData)) : "";
    $hash = $userData ? hash_hmac('sha256', $encodedUserData, $hyvor_key) : "";

    $build = [
      'div' => [
        '#type' => 'html_tag',
        '#tag' => 'hyvor-talk-comments',
        '#value' => '',
        '#attributes' => [
          'website-id' => Settings::get('hyvor_talk_id'),
          'page-id' => $node->id(),
          'login-url' => self::getLoginUrl($node->toUrl()->toString()),
          'sso-user' => $encodedUserData,
          'sso-hash' => $hash,
        ],
        '#attached' => ['library' => 'caw_profile_helper/hyvor'],
      ],
    ];
    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['user', 'url.path', 'url.query_args']);
  }

  /**
   * Return the currently logged in user's information.
   *
   * @return array
   *   Keyed array of user data if authenticated.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getUserData() {
    if (!$this->currentUser->isAuthenticated()) {
      return [];
    }
    /** @var \Drupal\user\UserInterface $user */
    $user = $this->entityTypeManager->getStorage('user')
      ->load($this->currentUser->id());
    return [
      'timestamp' => time(),
      'id' => $this->currentUser->getAccountName(),
      'name' => $user->getDisplayName(),
      'email' => $user->getEmail(),
    ];
  }

  /**
   * Get the url for log in depending on SAML.
   *
   * @param string $current_url
   *   Current path the user is on.
   *
   * @return \Drupal\Core\GeneratedUrl|string
   */
  protected static function getLoginUrl($current_url) {
    $url = Url::fromRoute('user.login', [], [
      'absolute' => TRUE,
      'query' => ['destination' => $current_url],
    ]);

    if (\Drupal::moduleHandler()->moduleExists('samlauth')) {
      $url = Url::fromRoute('samlauth.saml_controller_login', [], [
        'absolute' => TRUE,
        'query' => ['destination' => $current_url],
      ]);
    }

    return $url->toString();
  }

}
