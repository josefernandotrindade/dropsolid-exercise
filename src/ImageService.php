<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class ImageService {

  const IMAGE_URL = 'https://jsonplaceholder.typicode.com/albums/%s/photos';

  /**
   * The HTTP client to fetch the feed data with.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected ClientInterface $httpClient;

  /**
   * ImageService constructor.
   *
   * @param use GuzzleHttp\ClientInterface $httpClient
   */
  public function __construct(ClientInterface $httpClient) {
    $this->httpClient = $httpClient;
  }

  /**
   * Get $limit placeholder images from the API.
   */
  public function getImages(int $limit = 5, bool $theme = TRUE) {
    $data = [];

    // Try to obtain the photo data via the external API.
    try {
      $response = $this->httpClient->request('GET', sprintf(self::IMAGE_URL, $limit));
      $raw_data = $response->getBody()->getContents();
      $data = Json::decode($raw_data);
    }
    catch (GuzzleException $e) {
      // TODO: log the exception?
    }

    if ($theme) {
      // Build a listing of photos from the photo data.
      $data = array_map(static function ($item) {
        return [
          '#theme' => 'image',
          '#uri' => $item['thumbnailUrl'],
          '#alt' => $item['title'],
          '#title' => $item['title'],
        ];
      }, $data);
    }

    return $data;
  }
}
