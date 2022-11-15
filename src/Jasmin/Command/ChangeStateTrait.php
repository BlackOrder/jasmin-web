<?php

namespace JasminWeb\Jasmin\Command;

trait ChangeStateTrait {
  /**
   * @param string $key
   * @return bool
   */
  public function enable(string $key): bool {
    $r = $this->session->runCommand($this->getName() . ' -e ' . $key);
    
    if ($this->isNeedPersist()) {
      $this->session->persist();
    }

    return $this->parseResult($r);
  }

  /**
   * @param string $key
   * @return bool
   */
  public function disable(string $key): bool {
    $r = $this->session->runCommand($this->getName() . ' -d ' . $key);
    
    if ($this->isNeedPersist()) {
      $this->session->persist();
    }
    
    return $this->parseResult($r);
  }

  /**
   * @param string $result
   * @return bool
   */
  private function parseResult(string $result): bool {
    return false !== strpos($result, 'Successfully');
  }
}