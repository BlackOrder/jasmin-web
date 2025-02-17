<?php

namespace JasminWeb\Jasmin\Command;

trait AddTrait {
  /**
   * @param array $data
   *
   * @param string $errorStr
   *
   * @return bool
   */
  public function add(array $data, string&$errorStr = ''): bool {
    $validator = $this->getAddValidator();
    if (!$validator->checkRequiredAttributes($data)) {
      $errorStr = json_encode($validator->getErrors());
      return false;
    }
    
    // get direct validator's attributes as priority attributes.
    $priority_options = $validator->getRequiredAttributes();

    $data = $this->prepareAttributes($data);

    $command = $this->getName() . ' -a';
    $command .= PHP_EOL;

    // collect priorities first
    foreach ($data as $property_key => $property_value) {
      if(false === array_search($property_key, $priority_options, false)){
        continue;
      }
      $command .= $property_key . ' ' . $property_value;
      $command .= PHP_EOL;
    }

    // rest of options
    foreach ($data as $property_key => $property_value) {
      if(false !== array_search($property_key, $priority_options, false)){
        continue;
      }
      $command .= $property_key . ' ' . $property_value;
      $command .= PHP_EOL;
    }

    $command .= 'ok' . PHP_EOL;

    $result = $this->session->runCommand($command, $this->isHeavy());
    if (false !== stripos($result, 'successfully')) {
      if ($this->isNeedPersist()) {
        $this->session->persist();
      }

      return true;
    }

    // close session on failure
    $command = 'ko' . PHP_EOL;
    $this->session->runCommand($command, $this->isHeavy());

    $errorStr = strtolower($result);
    return false;
  }

  /**
   * @return AddValidator
   */
  abstract protected function getAddValidator(): AddValidator;

  /**
   * Return mutable version of data
   *
   * @param array $data
   *
   * @return array
   */
  protected function prepareAttributes(array $data): array
  {
    return $data;
  }
}