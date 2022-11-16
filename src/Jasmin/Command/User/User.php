<?php

namespace JasminWeb\Jasmin\Command\User;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;
use JasminWeb\Jasmin\Command\ChangeStateTrait;
use JasminWeb\Jasmin\Command\ShowTrait;

class User extends BaseCommand {
  use ChangeStateTrait, ShowTrait;

  /**
   * @return AddValidator
   */
  protected function getAddValidator(): AddValidator {
    return new UserAddValidator();
  }

  protected function getName(): string {
    return 'user';
  }

  /**
   * @param array $exploded
   * @return array
   */
  protected function parseList(array $exploded): array
  {
    $users = [];
    foreach ($exploded as $item) {
      $user = trim($item);

      $ff = strstr($item, 'Total Users:', true);
      if (!empty($ff)) {
        $user = trim($ff);
      }

      $temp_user = explode(' ', $user);
      $temp_user = array_filter($temp_user);

      $fixed_connector = [];
      foreach ($temp_user as $temp) {
        $fixed_connector[] = $temp;
      }

      // get throughput parts
      $throughput = explode('/', $fixed_connector[5]);

      //catch if row contains data or is it Header
      if (count($throughput) < 2) {
        continue;
      }

      $uid = ltrim($fixed_connector[0], '!');
      $gid = ltrim($fixed_connector[1], '!');
      $active = $uid === $fixed_connector[0] && $gid === $fixed_connector[1];

      $users[] = (object) [
        'uid' => $uid,
        'gid' => $gid,
        'active' => (bool) $active,
        'username' => $fixed_connector[2],
        'balance' => $fixed_connector[3],
        'sms' => $fixed_connector[4],
        'throughput' => (object) [
          'http' => $throughput[0],
          'smpps' => $throughput[1],
        ],
      ];
    }

    return $users;
  }

  /**
   * {@inheritdoc}
   */
  protected function isNeedPersist(): bool {
    return true;
  }
}