<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\MoInterceptor;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\InternalAddValidator;

class MoInterceptorBaseValidator extends InternalAddValidator {
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
    case MoInterceptor::STATIC :
      return new StaticMoInterceptorValidator();
    case MoInterceptor::DEFAULT:
      return new DefaultMoInterceptorValidator();
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
    $this->errors['type'] = 'Unknown MoInterceptor type ' . $data['type'];
  }
}