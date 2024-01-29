<?php

namespace Drupal\dependency_injection_exercise\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dependency_injection_exercise\ImageService;

/**
 * Provides the rest output.
 */
class RestOutputController extends ControllerBase {

  /**
   * The service to provide us with the image data.
   *
   * @var \Drupal\dependency_injection_exercise\ImageService
   */
  protected ImageService $imageService;

  /**
   * RestOutputController constructor.
   *
   * @param use Drupal\dependency_injection_exercise\ImageService $imageService
   */
  public function __construct(ImageService $imageService) {
    $this->imageService = $imageService;
  }

  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dependency_injection_exercise.image_service')
    );
  }

  /**
   * Displays the photos.
   *
   * @return array[]
   *   A renderable array representing the photos.
   */
  public function showPhotos(): array {
    // Setup build caching.
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => [
          'url',
        ],
      ],
    ];

    $data = $this->imageService->getImages(5);

    if (!$data) {
      $build['error'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No photos available.'),
      ];

      return $build;
    }

    $build['photos'] = $data;

    return $build;
  }

}
