<?php
namespace ApplicationTest\Model;

use Application\Model\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testInitialEmailValuesAreNull()
    {
        $email = new Email();

        $this->assertNull($email->uid, '"uid" should be null by default');
        $this->assertNull($email->name, '"name" should be null by default');
        $this->assertNull($email->id, '"id" should be null by default');
        $this->assertNull($email->email_string, '"email_string" should be null by default');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $email = new Email();
        $data  = [
            'uid' => '123',
            'name' => 'aa',
            'id'     => '123',
            'email_string'  => 'da11@nyu.edu',
        ];

        $email->exchangeArray($data);

        $this->assertSame(
            $data['uid'],
            $email->uid,
            '"uid" was not set correctly'
        );
        $this->assertSame(
            $data['name'],
            $email->name,
            '"name" was not set correctly'
        );

        $this->assertSame(
            $data['id'],
            $email->id,
            '"id" was not set correctly'
        );

        $this->assertSame(
            $data['email_string'],
            $email->email_string,
            '"email_string" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $email = new Email();

        $email->exchangeArray([
            'uid' => '123',
            'name' => 'some artist',
            'id'     => '123',
            'email_string'  => 'da11@qq.com',
        ]);
        $email->exchangeArray([]);

        $this->assertNull($email->uid, '"uid" should default to null');
        $this->assertNull($email->name, '"artist" should default to null');
        $this->assertNull($email->id, '"id" should default to null');
        $this->assertNull($email->email_string, '"title" should default to null');
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $email = new Email();
        $data  = [
            'uid' => '123',
            'name' => 'a',
            'id'     => '123',
            'email_string'  => 'da11@nyu.edu',
        ];

        $email->exchangeArray($data);
        $copyArray = $email->getArrayCopy();

        $this->assertSame($data['uid'], $copyArray['uid'], '"uid" was not set correctly');
        $this->assertSame($data['name'], $copyArray['name'], '"name" was not set correctly');
        $this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
        $this->assertSame($data['email_string'], $copyArray['email_string'], '"email_string" was not set correctly');
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $email = new Email();

        $inputFilter = $email->getInputFilter();

        $this->assertSame(4, $inputFilter->count());
        $this->assertTrue($inputFilter->has('uid'));
        $this->assertTrue($inputFilter->has('name'));
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('email_string'));
    }
}
