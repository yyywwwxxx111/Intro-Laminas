<?php
namespace ApplicationTest\Model;

use Application\Model\EmailTable;
use Application\Model\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use RuntimeException;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\TableGateway\TableGatewayInterface;

class EmailTableTest extends TestCase
{
    protected function setUp() : void
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->prophet = new Prophet;
        $this->emailTable = new EmailTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllEmails()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->emailTable->fetchAll());
    }
    public function testSaveEmailWillInsertNewEmailsIfTheyDontAlreadyHaveAnId()
    {
        $emailData = [
            'uid' => 1,
            'name' => 'aaa',
            'email_string'  => 'ada@qq.com'
        ];
        $email = new Email();
        $email->exchangeArray($emailData);

        $this->tableGateway->insert($emailData)->shouldBeCalled();
        $this->emailTable->saveEmail($email);
    }
    public function testExceptionIsThrownWhenGettingNonExistentEmail()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn(null);

        $this->tableGateway
            ->select(['uid' => 123])
            ->willReturn($resultSet->reveal());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not find row with identifier 123');
        $this->emailTable->getEmail(123);
    }

}