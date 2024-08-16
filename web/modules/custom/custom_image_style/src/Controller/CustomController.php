<?php

namespace Drupal\custom_image_style\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\custom_image_style\Service\ImageStyleService;
use Drupal\media\Entity\Media;

class CustomController extends ControllerBase {

  protected $imageStyleService;

  public function __construct(ImageStyleService $imageStyleService) {
    $this->imageStyleService = $imageStyleService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('custom_image_style.image_style_service')
    );
  }

  public function generateImageStyleCss($media_id, $image_style, $selector) {
    // Load the media entity for demonstration purposes.
    /** @var \Drupal\media\MediaInterface $media */
    $media = Media::load($media_id);

    if (!$media) {
      return [
        '#markup' => 'Media entity not found.',
      ];
    }

    $file_path = $this->imageStyleService->getFilePath($media, $image_style);
    $css = $this->imageStyleService->generateStyles($selector, $file_path);
    // dump($css);

    return [
      '#markup' => '<style>' . $css . '</style>',
        // '#markup' => '<div style="'. $css . '" height=400>test here with background <p>text sarted</p></div>',
    ];
  }

}
