<?php

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class Filter extends BaseCommand {
  public const TRANSPARENT = 'TransparentFilter';
  public const CONNECTOR = 'ConnectorFilter';
  public const USER = 'UserFilter';
  public const GROUP = 'GroupFilter';
  public const SOURCEADD = 'SourceAddrFilter';
  public const DESTINATIONADD = 'DestinationAddrFilter';
  public const SHORTMESS = 'ShortMessageFilter';
  public const DATEINTERV = 'DateIntervalFilter';
  public const TIMEINTERV = 'TimeIntervalFilter';
  public const TAG = 'TagFilter';
  public const EVAL = 'EvalPyFilter';

  /**
   * @return AddValidator
   */
  protected function getAddValidator(): AddValidator {
    return new FilterAddValidator();
  }

  /**
   * {@inheritdoc}
   */
  protected function getName(): string {
    return 'filter';
  }

  /**
   * @param array $exploded
   * @return array
   */
  protected function parseList(array $exploded): array
  {
    $filters = [];
    foreach ($exploded as $expl) {
      $filter = trim($expl);

      $ff = strstr($expl, 'Total Filters:', true);
      if (!empty($ff)) {
        $filter = trim($ff);
      }

      $temp_filter = explode(' ', $filter);
      $temp_filter = array_filter($temp_filter);

      $fixed_connector = [];
      foreach ($temp_filter as $temp) {
        $fixed_connector[] = $temp;
      }

      $row = (object) [
        'fid' => $fixed_connector[0],
        'type' => $fixed_connector[1],
        'description' => substr($filter, strpos($filter, '<'), strpos($filter, '>')),
        'routes' => [],
      ];

      if (false !== strpos($filter, 'MT')) {
        $row->routes[] = 'MT';
      }

      if (false !== strpos($filter, 'MO')) {
        $row->routes[] = 'MO';
      }

      $filters[] = $row;
    }

    return $filters;
  }

  /**
   * {@inheritdoc}
   */
  protected function isNeedPersist(): bool {
    return true;
  }
}