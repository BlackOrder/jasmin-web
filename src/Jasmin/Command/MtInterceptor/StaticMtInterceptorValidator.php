<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\MtInterceptor;

use JasminWeb\Jasmin\Command\AddValidator;

class StaticMtInterceptorValidator extends AddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['filters', 'script', 'order'];
  }
}