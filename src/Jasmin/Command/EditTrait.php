<?php

namespace JasminWeb\Jasmin\Command;

trait EditTrait {

  /**
   * @param string $key
   *
   * @param array $data
   *
   * @param string $errorStr
   *
   * @return bool
   */
  public function edit(string $key, array $data, string&$errorStr = ''): bool {
    $data = $this->prepareAttributes($data);

    $command = $this->getName() . ' -u ' . $key;
    $command .= PHP_EOL;

    foreach ($data as $property_key => $property_value) {
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