<?php
use Monolog\Logger;
use sideshow_bob\throttle\Storage\Memcached;
use sideshow_bob\throttle\Throttle;

/**
 * Class ThrottleTest
 */
class ThrottleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * $reflection
     * @var object
     */
    protected $reflection = null;

    public function setUp()
    {
        $options = array(
            'banned' => 10, // Ban identifier after 10 attempts. (default 5)
            'logged' => 20, // Log identifier after 20 attempts. (default 10)
            'timespan' => 10 // The timespan for the duration of the ban. (default 86400)
            );
        $this->throttle = new Throttle(new Logger('throttle'), new Memcached(),$options);
    }

    public function testInstantiateAsObjectSucceeds()
    {
        $this->assertInstanceOf('Websoftwares\Throttle', $this->throttle);
    }

    public function testPropertyValuesSucceeds()
    {
        $throttle = new Throttle(new Logger('throttle'), new Memcached());
        $this->reflection = new \ReflectionClass($throttle);

        $options = $this->getProperty('options',$throttle);
        $expected = array('banned' => 5, 'logged' => 10, 'timespan' => 86400);

        $this->assertInternalType('array', $options);
        $this->assertEquals($expected, $options);

        $this->assertInstanceOf('\\Websoftwares\Storage\Memcached', $this->getProperty('storage',$throttle));
        $this->assertInstanceOf('\\Monolog\Logger', $this->getProperty('logger',$throttle));
    }

    public function testValidateSucceeds()
    {
        // 10 rounds
        for ($i=0; $i < 9; $i++) {
            $this->assertTrue($this->throttle->validate('127.0.0.11'));
        }
        // Failed
        $this->assertFalse($this->throttle->validate('127.0.0.11'));
        // Expire the ban
        sleep(10);
        // Valid again
        $this->assertTrue($this->throttle->validate('127.0.0.11'));
    }

    public function testResetSucceeds()
    {
        $identifier = "Lijpehackertje@169.168.0.1";
        $this->assertTrue($this->throttle->validate($identifier));
        $this->assertTrue($this->throttle->reset($identifier));
        $this->assertFalse($this->throttle->reset($identifier));
    }

    public function testRemainingSucceeds()
    {
        $identifier = "Lijpehackertje@169.168.0.1";
        $this->assertTrue($this->throttle->validate($identifier));
        $actual = $this->throttle->remaining($identifier);
        $this->assertEquals(9, $actual);
        $this->assertInternalType('int', $actual);
        $this->assertInternalType('int',  $this->throttle->remaining('NotFound'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateFails()
    {
        $this->throttle->validate();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testResetFails()
    {
        $this->throttle->reset();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRemainingFails()
    {
        $this->throttle->remaining();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInstantiateAsObjectFails()
    {
        new Throttle;
    }

    /**
     * @expectedException Exception
     */
    public function testInstantiateAsObjectArgumentsFails()
    {
        new Throttle(new \stdClass, new \stdClass, 'lorum');
    }

    public function getProperty($property, $object = null)
    {
        $property = $this->reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object ? $object : $this->throttle);
    }
}
