<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;
use Drupal\language\LanguageServiceProvider;

/**
 * Modifies the mail provider service.
 */
class DependencyInjectionExerciseServiceProvider extends LanguageServiceProvider {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Take over the MailManager service and ensure all mails are redirected to "nope@doesntexist.com"
    if ($container->hasDefinition('plugin.manager.mail')) {
      $mailDefinition = $container->getDefinition('plugin.manager.mail');

      $mailDefinition->setClass('Drupal\dependency_injection_exercise\MyMailManager');
    }

    // Take over the LanguageManager service in a way that doesn't preclude others from also taking over the LanguageManager service
    //   Or perhaps you want a decorator service, I don't know
    if ($container->hasDefinition('language_manager')) {
      $languageDefinition = $container->getDefinition('language_manager');

      $languageDefinition->setClass('Drupal\dependency_injection_exercise\MyLanguageManager')
      ->addArgument(new Reference('config.factory'))
      ->addArgument(new Reference('module_handler'))
      ->addArgument(new Reference('language.config_factory_override'))
      ->addArgument(new Reference('request_stack'));

      if ($default_language_values = $this->getDefaultLanguageValues()) {
        $container->setParameter('language.default_values', $default_language_values);
      }
    }
  }
}
