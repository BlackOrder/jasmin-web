<?php

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\InternalAddValidator;

class FilterAddValidator extends InternalAddValidator {
  /**
   * @return array
   */
  public function getRequiredAttributes(): array
  {
    return ['fid', 'type'];
  }

  /**
   * {@inheritdoc}
   */
  protected function resolveValidator(array $data): ?AddValidator{
    $validator = null;
    switch ($data['type']) {
    case Filter::CONNECTOR:
      $validator = new ConnectorFilterValidator();
      break;
    case Filter::USER:
      $validator = new UserFilterAddValidator();
      break;
    case Filter::GROUP:
      $validator = new GroupFilterValidator();
      break;
    case Filter::SOURCEADD:
      $validator = new SourceAddrFilterValidator();
      break;
    case Filter::DESTINATIONADD:
      $validator = new DestinationAddrFilterValidator();
      break;
    case Filter::SHORTMESS:
      $validator = new ShortMessageFilterValidator();
      break;
    case Filter::DATEINTERV:
      $validator = new DateIntervalFilterValidator();
      break;
    case Filter::TIMEINTERV:
      $validator = new TimeIntervalFilterValidator();
      break;
    case Filter::TAG:
      $validator = new TagFilterValidator();
      break;
    case Filter::EVAL:
      $validator = new EvalPyFilterValidator();
      break;
    case Filter::TRANSPARENT:
      $validator = new class extends AddValidator {
        /**
         * @return array
         */
        public function getRequiredAttributes(): array
        {
          return [];
        }
      };
      break;
    }

    return $validator;
  }

  /**
   * {@inheritdoc}
   */
  protected function addResolveError(array $data): void {
    $this->errors['type'] = 'Unknown type of filter ' . $data['type'];
  }
}