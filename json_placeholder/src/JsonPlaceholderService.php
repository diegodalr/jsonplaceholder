<?php

namespace Drupal\json_placeholder;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Class JsonPlaceholderService.
 */
class JsonPlaceholderService {

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructs a new JsonPlaceholderService object.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * Get posts.
   *
   * @return array|mixed
   *   The result
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getPosts() {
    try {
      $request = $this->httpClient->request('get', 'https://jsonplaceholder.typicode.com/posts');
      if ($response = json_decode($request->getBody(), TRUE)) {
        return $response;
      }
    } catch (RequestException $requestException) {
      if (function_exists('dpm')) {
        dpm($requestException->getMessage());
      }
    }
    return [];
  }

  /**
   * Get photos.
   *
   * @return array|mixed
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getPhotos() {
    try {
      $request = $this->httpClient->request('get', 'https://jsonplaceholder.typicode.com/photos?_limit=10');
      if ($response = json_decode($request->getBody(), TRUE)) {
        return $response;
      }
    } catch (RequestException $requestException) {
      if (function_exists('dpm')) {
        dpm($requestException->getMessage());
      }
    }
    return [];
  }
}
