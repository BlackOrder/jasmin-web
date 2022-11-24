<?php

namespace JasminWeb\Jasmin\Command\MtInterceptor;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;
use JasminWeb\Jasmin\Command\FlushTrait;

class MtInterceptor extends BaseCommand {
  use FlushTrait;

  public const STATIC  = 'StaticMTInterceptor';
  public const DEFAULT = 'DefaultInterceptor';

  /**
   * @return AddValidator
   */
  protected function getAddValidator(): AddValidator {
    return new MtInterceptorBaseValidator();
  }

  protected function getName(): string {
    return 'mtinterceptor';
  }

  /**
   * @param array $exploded
   * @return array
   */
  protected function parseList(array $exploded): array
  {
    $interceptors = [];
    foreach ($exploded as $expl) {
      $interceptor = trim($expl);

      $ff = strstr($expl, 'Total MT Interceptors:', true);
      if (!empty($ff)) {
        $interceptor = trim($ff);
      }

      $interceptor = preg_replace(['/\s{2,}/', '/(<\w)(\s)?/'], [' ', '$1'], $interceptor);

      $fixed_interceptors = explode(' ', $interceptor);

      $row = (object) [
        'order' => (int) array_shift($fixed_interceptors),
        'type' => array_shift($fixed_interceptors),
        'script' => null,
        'filters' => [],
      ];

      //Get script
      preg_match('~<MTIS(.*?)>~', $interceptor, $MtInterceptorScript);
      $row->script = $MtInterceptorScript[0];

      //Get all filters
      preg_match_all('~<((?!MTIS).*?)>~', $interceptor, $MtInterceptorFilters);
      $row->filters = $MtInterceptorFilters[0];

      $interceptors[] = $row;
    }

    return $interceptors;
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

    return $data;
  }
}