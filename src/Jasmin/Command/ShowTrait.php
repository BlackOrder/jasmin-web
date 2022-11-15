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
  protected function parseShow(array $exploded): object {
    $options = [];
    foreach ($exploded as $row) {
      $option = trim($row);

      if (false !== strpos($option, 'jcli :')) {
        continue;
      }

      $option = explode(' ', $option);
      $value = array_pop($option);
      $key = implode(' ', $option);

      if (empty($key)) {
        continue;
      }

      $key = explode(' ', $key);

      while (count($key) > 1) {
        $tmpKey = array_pop($key);
        $value = [$tmpKey => $value];
      }

      $key = $key[0];

      $options = array_merge_recursive($options, [$key => $value]);
    }

    return json_decode(json_encode($options));
  }
}