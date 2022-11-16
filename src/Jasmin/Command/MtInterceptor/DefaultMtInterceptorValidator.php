<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\MtInterceptor;

use JasminWeb\Jasmin\Command\AddValidator;

class DefaultMtInterceptorValidator extends AddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['script'];
  }
}