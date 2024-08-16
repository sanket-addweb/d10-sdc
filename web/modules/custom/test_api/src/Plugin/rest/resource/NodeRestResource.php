<?php

namespace Drupal\my_custom_api\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

// /**
//  * Provides a resource to create and update nodes.
//  *
//  * @RestResource(
//  *   id = "node_custom_rest_resource",
//  *   label = @Translation("Node REST Resource"),
//  *   uri_paths = {
//  *     "canonical" = "/api/custom_node"
//  *   }
//  * )
//  */
class NodeRestResource extends ResourceBase implements ContainerFactoryPluginInterface {

  protected $entityTypeManager;
  protected $currentUser;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  public function post(Request $request) {
    $data = json_decode($request->getContent(), TRUE);
    if (empty($data['type']) || empty($data['title'])) {
      return new ResourceResponse('Invalid input', 400);
    }

    $node = $this->entityTypeManager->getStorage('node')->create([
      'type' => $data['type'],
      'title' => $data['title'],
      'body' => [
        'value' => $data['body'] ?? '',
        'format' => 'basic_html',
      ],
      'uid' => $this->currentUser->id(),
    ]);
    $node->save();

    return new ResourceResponse($node, 200);
  }

  public function patch(Request $request, $nid) {
    $data = json_decode($request->getContent(), TRUE);
    $node = $this->entityTypeManager->getStorage('node')->load($nid);
    if (!$node) {
      return new ResourceResponse('Node not found', 404);
    }

    if (isset($data['title'])) {
      $node->setTitle($data['title']);
    }
    if (isset($data['body'])) {
      $node->set('body', [
        'value' => $data['body'],
        'format' => 'basic_html',
      ]);
    }
    $node->save();

    return new ResourceResponse($node, 200);
  }
}
