<?php

namespace Drupal\json_placeholder\Controller;

use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;
use Drupal\json_placeholder\JsonPlaceholderService;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Class SpotifyPagesController.
 */
class JsonPlaceholderPagesController extends ControllerBase {

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The Spotify service.
   *
   * @var \Drupal\json_placeholder\JsonPlaceholderService
   */
  protected $jsonPlaceholderService;

  /**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client, JsonPlaceholderService $json_placeholder) {
    $this->httpClient = $http_client;
    $this->jsonPlaceholderService = $json_placeholder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('json_placeholder.json_placeholder_service')
    );
  }

  /**
   * Get links to call ajax content.
   *
   * @return mixed
   *   The build content.
   */
  public function getLinks() {
    $build['ajax_link'] = [
      '#type' => 'container',
      '#open' => TRUE,
    ];
    $build['#attached'] = ['library' => ['core/drupal.ajax',],];
    $build['ajax_link']['posts'] = [
      '#type' => 'link',
      '#title' => $this->t('Get posts'),
      '#attributes' => ['class' => ['use-ajax']],
      '#url' => Url::fromRoute('json_placeholder.json_placeholder_pages_controller_getPosts', ['nojs' => 'ajax']),
    ];
    $build['ajax_link']['photos'] = [
      '#type' => 'link',
      '#title' => $this->t('Get photos'),
      '#attributes' => ['class' => ['use-ajax',],],
      '#url' => Url::fromRoute('json_placeholder.json_placeholder_pages_controller_getPhotos', ['nojs' => 'ajax']),
      '#prefix' => '<br />',
    ];
    $build['ajax_link']['destination'] = [
      '#type' => 'container',
      '#attributes' => ['id' => ['ajax-links-destination-div']],
    ];
    return $build;
  }

  /**
   * Get posts.
   *
   * @param string $nojs
   *   Either 'ajax' or 'nojs.
   * @return array|\Drupal\Core\Ajax\AjaxResponse
   *   Return build content or Ajax replace command with content.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getPosts($nojs = 'ajax') {
    $rows = [];
    foreach ($this->jsonPlaceholderService->getPosts() as $item) {
      $rows[] = [
        'data' => [
          $item['userId'],
          $item['id'],
          $item['title'],
          $item['body'],
        ],
      ];
    }
    $header = [
      'userId' => t('User id'),
      'id' => t('Id'),
      'title' => t('Title'),
      'body' => t('Body'),
    ];
    $build = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => ['id' => ['ajax-links-destination-div']],
    ];
    if ($nojs == 'ajax') {
      $response = new AjaxResponse();
      return $response->addCommand(new ReplaceCommand('#ajax-links-destination-div', $build));
    }
    return $build;
  }

  /**
   * Get photos.
   *
   * @param string $nojs
   *   Either 'ajax' or 'nojs.
   *
   * @return array|\Drupal\Core\Ajax\AjaxResponse
   *   Return build content or Ajax replace command with content.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getPhotos($nojs = 'ajax') {
    $items_list = [];
    foreach ($this->jsonPlaceholderService->getPhotos() as $item) {
      $items_list[] = [
        '#theme' => 'image',
        '#uri' => $item['thumbnailUrl'],
        '#alt' => $item['title'],
      ];
    }
    $build = [
      '#theme' => 'jcarousel',
      '#options' => [
        'skin' => 'tango',
      ],
      '#items' => $items_list,
      '#prefix' => '<div id ="ajax-links-destination-div">',
      '#suffix' => '</div>',
    ];
    if ($nojs == 'ajax') {
      $response = new AjaxResponse();
      return $response->addCommand(new ReplaceCommand('#ajax-links-destination-div', $build));
    }
    return $build;
  }
}
