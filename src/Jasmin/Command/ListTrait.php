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
      if ($value[0] !== '#') {
        unset($exploded[$key]);
      } else {
        ltrim($value[0], '#');
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