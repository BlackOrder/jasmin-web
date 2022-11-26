<?php

namespace JasminWeb\Jasmin\Command;

trait ListTrait {
  /**
   * @return array
   */
  public function all(): array
  {
    $response = $this->session->runCommand($this->getName() . ' -l');

    $exploded = explode(PHP_EOL, $response);
    foreach ($exploded as $key => $value) {
      if (substr($value, 0, 1) !== '#') {
        unset($exploded[$key]);
      } else {
        $exploded[$key] = ltrim($value, '#');
      }
    }
    array_shift($exploded);


    return $this->parseList($exploded);
  }

  /**
   * @param array $exploded
   *
   * @return array
   */
  abstract protected function parseList(array $exploded): array;
}