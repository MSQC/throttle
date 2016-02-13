<?php
namespace sideshow_bob\throttle;

use sideshow_bob\throttle\Storage\ArrayStorage;

/**
 * Class ThrottleTest
 */
class ThrottleTest extends \PHPUnit_Framework_TestCase
{
    const IDENTIFIER = "identifier";
    const BAN = 10;
    const LOG = 20;
    const TIME_SPAN = 10;

    /**
     * @var Throttle
     */
    private $throttle;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->throttle = new Throttle(
            new ArrayStorage(),
            [
                "ban" => static::BAN,
                "log" => static::LOG,
                "timespan" => static::TIME_SPAN,
            ]
        );
    }

    public function testSuccessfulValidate()
    {
        $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
    }

    public function testBan()
    {
        for ($i = 0; $i < 9; $i++) {
            $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
        }
        $this->assertFalse($this->throttle->validate(static::IDENTIFIER));
    }

    public function testLog()
    {
        for ($i = 0; $i < static::BAN - 1; $i++) {
            $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
        }
        for ($i = static::BAN; $i < static::LOG - 1; $i++) {
            $this->assertFalse($this->throttle->validate(static::IDENTIFIER));
        }
        $this->assertFalse($this->throttle->validate(static::IDENTIFIER));
    }

    public function testBanExpiration()
    {
        for ($i = 0; $i < static::BAN - 1; $i++) {
            $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
        }
        $this->assertFalse($this->throttle->validate(static::IDENTIFIER));
        sleep(10);
        $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
    }

    public function testReset()
    {
        for ($i = 0; $i < static::BAN - 1; $i++) {
            $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
        }
        $this->assertFalse($this->throttle->validate(static::IDENTIFIER));
        $this->throttle->reset(static::IDENTIFIER);
        $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
    }

    public function testRemaining()
    {
        $this->assertTrue($this->throttle->validate(static::IDENTIFIER));
        $this->assertEquals(static::BAN - 1, $this->throttle->remaining(static::IDENTIFIER));
    }
}
