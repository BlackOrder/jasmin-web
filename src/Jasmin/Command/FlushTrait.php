<?php

namespace JasminWeb\Jasmin\Command;

trait FlushTrait {
  /**
   * @return bool
   */
  public function flush(): bool {
    $result = $this->session->runCommand($this->getName() . ' -f');

    if (false !== stripos($result, 'successfully')) {
      $this->session->persist();
      return true;
    }

    return false;
  }
}