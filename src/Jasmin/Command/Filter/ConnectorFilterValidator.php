<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;

class ConnectorFilterValidator extends AddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['cid'];
    }
}