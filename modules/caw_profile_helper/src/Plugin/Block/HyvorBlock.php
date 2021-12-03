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
   * Current user account object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Entity Type Manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

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
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritDoc}
   */
  public function build() {
    /** @var \Drupal\node\NodeInterface $node */
    $node = $this->routeMatch->getParameter('node');
    if (!($node instanceof NodeInterface)) {
      return [];
    }

    // Encrypt the user's data.
    // @link https://talk.hyvor.com/docs/sso-stateless
    $hyvor_key = Settings::get('hyvor_talk_private_key');
    $user_data = $this->getUserData();
    $encodedUserData = base64_encode(json_encode($user_data));
    $hash = hash_hmac('sha1', $encodedUserData, $hyvor_key);

    $current_url = Url::fromRoute($this->routeMatch->getRouteName(), $this->routeMatch->getRawParameters()
      ->all(), ['absolute' => TRUE])->toString();

    $login_url = Url::fromRoute('simplesamlphp_auth.saml_login', [], [
      'absolute' => TRUE,
      'query' => ['ReturnTo' => $current_url],
    ])->toString();

    $build = [
      'div' => [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' => '',
        '#attributes' => [
          'id' => 'hyvor-talk-view',
        ],
        '#attached' => [
          'library' => 'caw_profile_helper/hyvor',
          'drupalSettings' => [
            'caw_profile_helper' => [
              'hyvor' => [
                'id' => Settings::get('hyvor_talk_id'),
                'config' => [
                  'url' => FALSE,
                  'id' => $node->id(),
                  'sso' => [
                    'hash' => $hash,
                    'userData' => $encodedUserData,
                    'loginURL' => $login_url,
                    'signupURL' => $login_url,
                  ],
                ],
              ],
            ],
          ],
        ],
      ],
    ];
    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['user']);
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
      'id' => $this->currentUser->getAccountName(),
      'name' => $user->getDisplayName(),
      'email' => $user->getEmail(),
    ];
  }

}
