<?php

namespace JasminWeb\Jasmin\Command\MoRouter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class MoRouter extends BaseCommand {
  public const STATIC  = 'StaticMORoute';
  public const DEFAULT = 'DefaultRoute';
  public const RANDOM = 'RandomRoundrobinMORoute';
  public const FAILOVER = 'FailoverMORoute';

  /**
   * @return AddValidator
   */
  protected function getAddValidator(): AddValidator {
    return new MoRouterBaseAddValidator();
  }

  protected function getName(): string {
    return 'morouter';
  }

  /**
   * @param array $exploded
   * @return array
   */
  protected function parseList(array $exploded): array
  {
    $routers = [];
    foreach ($exploded as $expl) {
      $router = trim($expl);

      $ff = strstr($expl, 'Total MO Routes:', true);
      if (!empty($ff)) {
        $router = trim($ff);
      }

      $router = preg_replace(['/\s{2,}/', '/(<\w)(\s)?/'], [' ', '$1'], $router);
      $fixed_routers = explode(' ', $router);

      $row = (object) [
        'order' => (int) array_shift($fixed_routers),
        'type' => array_shift($fixed_routers),
        'connectors' => [],
        'filters' => [],
      ];

      //Get all http connectors
      preg_match_all('~http\((.*?)\)~', $router, $MOhttpConnectors);

      //Get all smpps connectors
      preg_match_all('~smpps\((.*?)\)~', $router, $MOsmppsConnectors);
      $row->connectors = array_merge($MOhttpConnectors[0], $MOsmppsConnectors[0]);

      //Get all filters
      preg_match_all('~<(.*?)>~', $router, $MTfilters);
      $row->filters = $MTfilters[0];

      $routers[] = $row;
    }

    return $routers;
  }

  protected function isHeavy(): bool {
    return true;
  }

  public function prepareAttributes(array $data): array
  {
    if (isset($data['filters']) && !empty($data['filters'])) {
      $data['filters'] = implode(';', $data['filters']);
    }

    if (isset($data['connectors']) && !empty($data['connectors'])) {
      $data['connectors'] = implode(';', $data['connectors']);
    }

    return $data;
  }
}