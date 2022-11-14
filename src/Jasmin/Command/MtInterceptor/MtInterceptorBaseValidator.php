<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\MtInterceptor;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\InternalAddValidator;

class MtInterceptorBaseValidator extends InternalAddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['type'];
  }

  /**
   * Find validator by data
   *
   * @param array $data
   *
   * @return AddValidator|null
   */
  protected function resolveValidator(array $data): ?AddValidator {
    switch ($data['type']) {
    case MtInterceptor::STATIC :
      return new StaticMtInterceptorValidator();
    case MtInterceptor::DEFAULT:
      return new DefaultMtInterceptorValidator();
    default:
      return null;
    }
  }

  /**
   * Add specific error if validator isn't found
   *
   * @param array $data
   */
  protected function addResolveError(array $data): void {
    $this->errors['type'] = 'Unknown MtInterceptor type ' . $data['type'];
  }
}