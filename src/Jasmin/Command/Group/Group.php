<?php

namespace JasminWeb\Jasmin\Command\Group;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;
use JasminWeb\Jasmin\Command\ChangeStateTrait;

class Group extends BaseCommand {
  use ChangeStateTrait;

  protected function getName(): string {
    return 'group';
  }

  /**
   * @param array $exploded
   * @return array
   */
  protected function parseList(array $exploded): array
  {
    $groups = [];
    foreach ($exploded as $item) {
      $group = trim($item);

      $ff = strstr($item, 'Total Groups:', true);
      if (!empty($ff)) {
        $group = trim($ff);
      }

      
      $gid = ltrim($group, '!');
      $active = $gid === $group;


      $groups[] = (object) [
        'gid' => $gid,
        'active' => (bool) $active,
      ];
    }

    return $groups;
  }

  /**
   * @return AddValidator
   */
  protected function getAddValidator(): AddValidator {
    return new GroupAddValidator();
  }

  /**
   * {@inheritdoc}
   */
  protected function isNeedPersist(): bool {
    return true;
  }
}