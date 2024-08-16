<?php

namespace Drupal\custom_image_style\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\media\MediaInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;

class ImageStyleService {

  protected $entityTypeManager;
  protected $fileUrlGenerator;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, FileUrlGeneratorInterface $fileUrlGenerator) {
    $this->entityTypeManager = $entityTypeManager;
    $this->fileUrlGenerator = $fileUrlGenerator;
  }

  /**
   * Code to get file path.
   */
  public function getFilePath(MediaInterface $entity, string $image_style) {
    $fid = $entity->getSource()->getSourceFieldValue($entity);
    /** @var \Drupal\file\FileInterface $file */
    $file = $this->entityTypeManager->getStorage('file')->load($fid);

    //Add crop type and crop api logic
    $focal_point_manager = \Drupal::service('focal_point.manager');
    $crop = $focal_point_manager->getCropEntity($file, 'focal_point');

    $width = 300;
    $height = 200;
    $focal_point = "9,20";
    if ($focal_point && $width && $height) {
        [$x, $y] = explode(',', $focal_point);
        $focal_point_manager->saveCropEntity($x, $y, $width, $height, $crop);
    }

    /** @var \Drupal\image\ImageStyleInterface $style */
    $style = $this->entityTypeManager->getStorage('image_style')->load($image_style);
    $file_path = $style->buildUrl($file->getFileUri());


    return $file_path;
  }

  /**
   * Code to create CSS.
   */
  public function generateStyles(string $selector, string $file_path) {
    // Use file_url_generator to transform the file URL to relative.
    $relative_url = $this->fileUrlGenerator->transformRelative($file_path);

    // $css = sprintf('%s {', $selector);
    // $css .= sprintf('background-image: url(\'%s\');', $relative_url);
    // $css .= '}';
    $css = sprintf('background-image: url(\'%s\');', $relative_url);

    return $css;
  }

}
