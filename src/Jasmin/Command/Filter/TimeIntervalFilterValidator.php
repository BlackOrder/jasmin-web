<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;

class TimeIntervalFilterValidator extends AddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['timeInterval'];
  }
}