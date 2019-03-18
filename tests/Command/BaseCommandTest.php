<?php declare(strict_types=1);

namespace JasminWeb\Test\Command;

use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

abstract class BaseCommandTest extends BaseTest
{
    /**
     * @var Session|MockObject
     */
    protected $session;

    protected function setUp()
    {
        if (!$this->isRealJasminServer()) {
            $this->session = $this->getSessionMock();
        }

        if (!$this->session) {
            $this->session = $this->getSession();
        }

        $this->initCommand();
    }

    abstract protected function initCommand(): void;
}