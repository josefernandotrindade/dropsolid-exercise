<?php

namespace Drupal\dependency_injection_exercise\Plugin\Block;

use Drupal\dependency_injection_exercise\ImageService;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'RestOutputBlock' block.
 *
 * @Block(
 *  id = "rest_output_block",
 *  admin_label = @Translation("Rest output block"),
 * )
 */
class RestOutputBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The service to provide us with the image data.
   *
   * @var \Drupal\dependency_injection_exercise\ImageService
   */
  protected ImageService $imageService;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration, $plugin_id, $plugin_definition, ImageService $imageService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->imageService = $imageService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dependency_injection_exercise.image_service')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function build(): array {
    // Setup build caching.
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => [
          'url',
        ],
      ],
    ];

    $data = $this->imageService->getImages(random_int(1, 20));

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
