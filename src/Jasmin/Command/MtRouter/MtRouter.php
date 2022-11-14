<?php

namespace JasminWeb\Jasmin\Command\MtRouter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class MtRouter extends BaseCommand {
  public const STATIC  = 'StaticMTRoute';
  public const DEFAULT = 'DefaultRoute';
  public const RANDOM = 'RandomRoundrobinMTRoute';
  public const FAILOVER = 'FailoverMTRoute';

  /**
   * @return AddValidator
   */
  protected function getAddValidator(): AddValidator {
    return new MtRouterBaseValidator();
  }

  protected function getName(): string {
    return 'mtrouter';
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

      $ff = strstr($expl, 'Total MT Routes:', true);
      if (!empty($ff)) {
        $router = trim($ff);
      }

      $router = preg_replace(['/\s{2,}/', '/(<\w)(\s)?/'], [' ', '$1'], $router);

      $fixed_routers = explode(' ', $router);

      $row = (object) [
        'order' => (int) array_shift($fixed_routers),
        'type' => array_shift($fixed_routers),
        'rate' => (float) array_shift($fixed_routers),
        'connectors' => [],
        'filters' => [],
      ];

      //Get all connectors
      preg_match_all('~smppc\((.*?)\)~', $router, $MTconnectors);
      $row->connectors = $MTconnectors[0];

      //Get all filters
      preg_match_all('~<(.*?)>~', $router, $MTfilters);
      $row->filters = $MTfilters[0];

      $routers[] = $row;
    }

    return $routers;
  }

  /**
   * {@inheritdoc}
   */
  protected function isHeavy(): bool {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  protected function isNeedPersist(): bool {
    return true;
  }

  /**
   * {@inheritdoc}
   */
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