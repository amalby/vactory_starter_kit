<?php

namespace Drupal\vactory_decoupled\Plugin\Field;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\node\Entity\Node;
use Drupal\vactory_decoupled\BlocksManager;

/**
 * Comment per node.
 */
class InternalNodeEntityCommentFieldItemList extends FieldItemList
{

  use ComputedItemListTrait;

  /**
   * {@inheritdoc}
   */
  protected function computeValue()
  {
    /** @var Node $entity */
    $entity = $this->getEntity();
    $entity_type = $entity->getEntityTypeId();

    if (!in_array($entity_type, ['node'])) {
      return;
    }

    if ($entity->hasField('comment')) {
      $comments = !empty($entity->get('comment')->getValue()) ? $entity->get('comment')->getValue()[0] : [];
      $contributions = isset($comments['comment_count']) ? $comments['comment_count'] : 0;
      $last_contribution = $contributions > 0 ? $comments['last_comment_timestamp'] : null;

      $value = [
        'contributions' => $contributions,
        'last_contribution' => $last_contribution
      ];
    }
    else {
      $value = [
        'error' => 'Entity should have a field named comment'
      ];
    }

    $this->list[0] = $this->createItem(0, $value);
  }
}
