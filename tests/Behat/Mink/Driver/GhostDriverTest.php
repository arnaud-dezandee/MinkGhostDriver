<?php
namespace Tests\Behat\Mink\Driver;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\GhostDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;

/**
 * @author Arnaud Dezandee <dezandee.arnaud@gmail.com>
 */
class GhostDriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Mink session manager.
     *
     * @var Mink
     */
    private static $mink;

    /**
     * Initializes mink instance.
     */
    public static function setUpBeforeClass()
    {
        self::$mink = new Mink(array('sess' => new Session(static::getDriver())));
    }

    /**
     * Creates driver instance.
     *
     * @return DriverInterface
     * @throws \RuntimeException
     */
    protected static function getDriver()
    {
        return new GhostDriver();
    }

    /**
     * Returns session.
     *
     * @return Session
     */
    public function getSession()
    {
        return self::$mink->getSession('sess');
    }

    protected function tearDown()
    {
        self::$mink->resetSessions();
    }

    public function testGetWebDriverSessionId()
    {
        /** @var GhostDriver $driver */
        $driver = $this->getSession()->getDriver();
        $this->assertNotEmpty($driver->getWebDriverSessionId(), 'Started session has an ID');

        $driver = new GhostDriver();
        $this->assertNull($driver->getWebDriverSessionId(), 'Not started session don\'t have an ID');
    }
}
