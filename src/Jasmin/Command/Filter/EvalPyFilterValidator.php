<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;

class EvalPyFilterValidator extends AddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['pyCode'];
  }
}