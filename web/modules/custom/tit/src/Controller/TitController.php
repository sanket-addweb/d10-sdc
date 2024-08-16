<?php

declare(strict_types=1);

namespace Drupal\tit\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Returns responses for Test Interface transaltion routes.
 */
class TitController extends ControllerBase {
  use StringTranslationTrait;

  /**
   * Builds the response.
   */
  public function build(): array {

    $var = "Passing var in translating string";
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Welcome! @var'),
    ];

    // return [
    //   '#theme' => 'tit_page',
    //   '#attached' => [
    //     'library' => [
    //       'tit/global-scripts',
    //     ],
    //   ],
    // ];

    return $build;
  }

}
