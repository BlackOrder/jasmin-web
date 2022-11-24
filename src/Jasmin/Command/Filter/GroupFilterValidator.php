<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;

class GroupFilterValidator extends AddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['fid', 'gid'];
  }
}