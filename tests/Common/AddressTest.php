<?php namespace ArgentumTest\Common;

use Argentum\Common\Address;

class AddressTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Address
     */
    protected $address;

    protected function setUp()
    {
        $this->address = new Address([
            'address_1'    => '123 Fake Street',
            'address_2'    => 'Tower 4',
            'address_3'    => '5-D',
            'neighborhood' => 'Kilcaragh',
            'postcode'     => '12345',
            'locality'     => 'Lixnaw',
            'state'        => 'Kerry',
            'country'      => 'IE',
        ]);
    }

    protected function tearDown() {
        unset($this->address);
    }

    public function testGetAddress_1()
    {
        $this->assertEquals($this->address->getAddress_1(), '123 Fake Street');
    }

    public function testGetAddress_2()
    {
        $this->assertEquals($this->address->getAddress_2(), 'Tower 4');
    }

    public function testGetAddress_3()
    {
        $this->assertEquals($this->address->getAddress_3(), '5-D');
    }

    public function testGetNeighborhood()
    {
        $this->assertEquals($this->address->getNeighborhood(), 'Kilcaragh');
    }

    public function testGetPostcode()
    {
        $this->assertEquals($this->address->getPostcode(), '12345');
    }

    public function testGetLocality()
    {
        $this->assertEquals($this->address->getLocality(), 'Lixnaw');
    }

    public function testGetState()
    {
        $this->assertEquals($this->address->getState(), 'Kerry');
    }

    public function testGetCountry()
    {
        $this->assertEquals($this->address->getCountry(), 'IE');
    }

}