<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\MoInterceptor;

use JasminWeb\Jasmin\Command\AddValidator;

class DefaultMoInterceptorValidator extends AddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['script'];
  }
}