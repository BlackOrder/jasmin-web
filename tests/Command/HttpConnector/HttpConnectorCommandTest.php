<?php

namespace JasminWeb\Test\Command\HttpConnector;

use JasminWeb\Jasmin\Command\HttpConnector\Connector;
use JasminWeb\Test\Command\BaseCommandTest;

class HttpConnectorCommandTest extends BaseCommandTest
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var string
     */
    protected $cid = 'jTestHttpC1';

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
#Httpcc id        Type                   Method URL
#HTTP-01          HttpConnector          GET    http://10.10.20.125/receive-sms/mo.php
#HTTP-02          HttpConnector          POST    http://10.10.20.125/receive-sms/mo.php
Total Httpccs: 2
STR;
            $this->session->method('runCommand')->willReturn($listStr);

            $list = $this->connector->all();
            $this->assertCount(2, $list);
            foreach ($list as $row) {
                $this->assertArrayHasKey('cid', $row);
                $this->assertInternalType('string', $row['cid']);
                $this->assertArrayHasKey('type', $row);
                $this->assertInternalType('string', $row['type']);
                $this->assertArrayHasKey('method', $row);
                $this->assertInternalType('string', $row['method']);
                $this->assertArrayHasKey('url', $row);
                $this->assertInternalType('string', $row['url']);
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @depends testNotEmptyListWithFakeData
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testAddConnector(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errstr = '';

        $data = [
            'cid' => $this->cid,
            'url' => 'http://10.10.20.125/receive-sms/mo.php',
            'method' => 'GET'
        ];

        $this->assertTrue($this->connector->add($data, $errstr), $errstr);
        $this->session->persist();
    }

    /**
     * @depends testAddConnector
     *
     */
    public function testConnectorsList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Httpcc id        Type                   Method URL
#$this->cid          HttpConnector          POST    http://10.10.20.125/receive-sms/mo.php
Total Httpccs: 1
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->connector->all();
        $this->assertCount(1, $list);
        $row = array_shift($list);
        $this->assertArrayHasKey('cid', $row);
        $this->assertInternalType('string', $row['cid']);
        $this->assertArrayHasKey('type', $row);
        $this->assertInternalType('string', $row['type']);
        $this->assertArrayHasKey('method', $row);
        $this->assertInternalType('string', $row['method']);
        $this->assertArrayHasKey('url', $row);
        $this->assertInternalType('string', $row['url']);
    }

    /**
     * @depends testConnectorsList
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testRemoveConnector(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully');
        }

        $this->assertTrue($this->connector->remove($this->cid));
        $this->session->persist();
        $this->testEmptyList();
    }
}
