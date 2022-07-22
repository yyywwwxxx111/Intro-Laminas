<?php
namespace Application\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class EmailTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    //select all rows
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }
    //get the emails by uid
    public function getEmails($uid)
    {
        $uid = (int) $uid;
        $rowset = $this->tableGateway->select(['uid' => $uid]);
        //$row = $rowset->current();
        if (! $rowset) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $uid
            ));
        }
        return $rowset;
    }
    //get one of the emails by uid
    public function getEmail($uid)
    {
        $uid = (int) $uid;
        $rowset = $this->tableGateway->select(['uid' => $uid]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $uid
            ));
        }
        return $row;
    }
    //get name of user by uid
    public function getName($uid)
    {
        $uid = (int) $uid;
        $rowset = $this->tableGateway->select(['uid' => $uid]);
        $row = $rowset->current();
        if ($row) {
            return $row->name;
        }
    }
    //find if the email exits
    public function findEmail($email_string)
    {
        $rowset = $this->tableGateway->select(['email_string' => $email_string]);
        $row = $rowset->current();
        if ($row) {
            return true;
        }
    }
    //save the email to database
    public function saveEmail(Email $email)
    {
        $data = [
            'uid' => $email->uid,
            'name' => $email->name,
            'email_string'  => $email->email_string,
        ];

        $id = (int) $email->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getEmail($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update email with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }
}
