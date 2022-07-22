<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Model\EmailTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Stdlib\ResponseInterface as Response;
use Laminas\View\Model\ViewModel;
use Application\Form\EmailForm;
use Application\Model\Email;

class IndexController extends AbstractActionController
{
    private $table;

    // Add this constructor:
    public function __construct(EmailTable $table)
    {
        $this->table = $table;
    }
    //to render the home page which have all the emails
    public function indexAction()
    {
        return new ViewModel([
            'emails' => $this->table->fetchAll(),
        ]);
    }
    //realize the add function
    public function addAction()
    {
        $form = new EmailForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }
        $email = new Email();
        $form->setInputFilter($email->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }
        $email->exchangeArray($form->getData());

        //if name is no match we redirect to a page which indicates error
        $name = $this->table->getName($email->uid);
        if ($email->name != $name
            && $name != null
        ) {
            return $this->redirect()->toRoute('email', ['action' => 'repeat']);
        }

        //if email has existed we redirect to a page which indicates error
        if ($this->table->findEmail($email->email_string)) {
            return $this->redirect()->toRoute('email', ['action' => 'exist']);
        }

        $this->table->saveEmail($email);
        return $this->redirect()->toRoute('email');
    }
    //search the data we want according to the number we input
    public function searchAction()
    {
        $uid = (int) $this->params()->fromRoute('uid', 0);
        //if we can not get this row from database, we redirect to a page which indicates error
        try {
            $email = $this->table->getEmail($uid);
        } catch (\Exception $e) {
            //if we can not find this id we render no.phtml
            return $this->redirect()->toRoute('email', ['action' => 'no']);
        }
        //render the data we want, it can be a row or many rows
        return new ViewModel([
            'emails' => $this->table->getEmails($uid),
        ]);
    }

    public function noAction()
    {
        //do nothing, just render alert
    }
    public function repeatAction()
    {
        //do nothing, just render alert
    }
    public function existAction()
    {
        //do nothing, just render alert
    }
}
