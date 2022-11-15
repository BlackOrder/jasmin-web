<?php

namespace JasminWeb\Jasmin\Command;

trait ShowTrait {
  /**
   * @param string $key
   * @return object
   */
  public function show(string $key): object {
    $response = $this->session->runCommand($this->getName() . ' -s ' . $key);

    $exploded = explode("\n", $response);
    unset($exploded[0]);

    return $this->parseShow($exploded);
  }

  /**
   * @param array $exploded
   * @return object
   */
  abstract protected function parseShow(array $exploded): object;
}