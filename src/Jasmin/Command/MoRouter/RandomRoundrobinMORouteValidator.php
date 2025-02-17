<?php declare (strict_types = 1);

namespace JasminWeb\Jasmin\Command\MoRouter;

use JasminWeb\Jasmin\Command\AddValidator;

class RandomRoundrobinMORouteValidator extends AddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['filters', 'connectors', 'order'];
  }
}