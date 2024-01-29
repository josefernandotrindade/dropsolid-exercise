<?php

namespace Drupal\dependency_injection_exercise\Breadcrumb;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;

class MyBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return $route_match->getRouteName() === 'dependency_injection_exercise.rest_output_controller_photos';
 }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();

    // On the path /dropsolid/example/photos the breadcrumb should be Home > Dropsolid > Example > Photos
    $breadcrumb->setLinks([
      Link::createFromRoute('Home', '<front>'),
      Link::createFromRoute('Dropsolid', '<none>'),
      Link::createFromRoute('Example', '<none>'),
      Link::createFromRoute('Photos', 'dependency_injection_exercise.rest_output_controller_photos'),
    ]);

    $breadcrumb->addCacheContexts(['route']);

    return $breadcrumb;
  }
}
