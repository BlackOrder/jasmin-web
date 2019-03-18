<?php

namespace JasminWeb\Test\Command\SmppConnector;

use JasminWeb\Jasmin\Command\SmppConnector\Connector;
use JasminWeb\Test\Command\BaseCommandTest;

class SmppConnectorCommandTest extends BaseCommandTest
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var string
     */
    protected $cid = 'jTestSmppC1';

    protected function initCommand(): void
    {
        $this->connector = new Connector($this->session);
    }

    public function testEmptyList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Connector id                        Service Session          Starts Stops
Total connectors: 0
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->connector->all();
        $this->assertEmpty($list);
    }

    /**
     * @depends testEmptyList
     *
     */
    public function testNotEmptyListWithFakeData(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Connector id                        Service Session          Starts Stops
#888                                 stopped None             0      0
#Demo                                started some-session             1      0
#Demo1                                started UNBOUND             1      1
Total connectors: 3
STR;
            $this->session->method('runCommand')->willReturn($listStr);

            $list = $this->connector->all();
            $this->assertCount(3, $list);
            foreach ($list as $row) {
                $this->assertArrayHasKey('cid', $row);
                $this->assertInternalType('string', $row['cid']);
                $this->assertArrayHasKey('service', $row);
                $this->assertInternalType('string', $row['service']);
                $this->assertArrayHasKey('session', $row);
                $this->assertInternalType('string', $row['session']);
                $this->assertArrayHasKey('starts', $row);
                $this->assertInternalType('int', $row['starts']);
                $this->assertArrayHasKey('stops', $row);
                $this->assertInternalType('int', $row['stops']);
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @depends testNotEmptyListWithFakeData
     *
     */
    public function testAddConnector(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errstr = '';
        $this->assertTrue($this->connector->add(['cid' => $this->cid], $errstr), $errstr);
    }

    /**
     * @depends testAddConnector
     *
     */
    public function testConnectorsList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Connector id                        Service Session          Starts Stops
#$this->cid                                 stopped None             0      0
Total connectors: 1
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->connector->all();
        $this->assertCount(1, $list);
        $row = array_shift($list);
        $this->assertArrayHasKey('cid', $row);
        $this->assertInternalType('string', $row['cid']);
        $this->assertArrayHasKey('service', $row);
        $this->assertInternalType('string', $row['service']);
        $this->assertArrayHasKey('session', $row);
        $this->assertInternalType('string', $row['session']);
        $this->assertArrayHasKey('starts', $row);
        $this->assertInternalType('int', $row['starts']);
        $this->assertArrayHasKey('stops', $row);
        $this->assertInternalType('int', $row['stops']);
    }

    /**
     * @depends testAddConnector
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testStartConnector(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully');
        }

        $this->assertTrue($this->connector->enable($this->cid));
    }

    /**
     * @depends testStartConnector
     *
     */
    public function testIsConnectorStarted(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Connector id                        Service Session          Starts Stops
#$this->cid                                 started None             1      0
Total connectors: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $connectors = $this->connector->all();
        $row = array_shift($connectors);
        $this->assertEquals('started', $row['service']);
        $this->assertEquals(1, $row['starts']);
        $this->assertEquals(0, $row['stops']);
    }

    /**
     * @depends testIsConnectorStarted
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testStopConnector(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully');
        }

        $this->assertTrue($this->connector->disable($this->cid));
    }

    /**
     * @depends testStopConnector
     *
     */
    public function testIsConnectorStopped(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Connector id                        Service Session          Starts Stops
#$this->cid                                 stopped None             1      1
Total connectors: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $connectors = $this->connector->all();
        $row = array_shift($connectors);
        $this->assertEquals('stopped', $row['service']);
        $this->assertEquals(1, $row['starts']);
        $this->assertEquals(1, $row['stops']);
    }

    /**
     * @depends testIsConnectorStopped
     */
    public function testRemoveConnector(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully');
        }

        $this->assertTrue($this->connector->remove($this->cid));
        $this->testEmptyList();
    }
}
