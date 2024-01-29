<?php 

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Mail\MailManager as CoreMailManager;

class MyMailManager extends CoreMailManager implements MailManagerInterface {

  public function mail($module, $key, $to, $langcode, $params = [], $reply = NULL, $send = TRUE) {
    // Take over the MailManager service and ensure all mails are redirected to "nope@doesntexist.com"
    $to = 'nope@doesntexist.com';

    return parent::mail($module, $key, $to, $langcode, $params, $reply, $send);
  }
}