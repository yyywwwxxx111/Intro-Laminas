<?php
namespace Application\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

//we creat a form
class EmailForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('email');
        //id,created by database
        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        //uid of user which is the searching key
        $this->add([
            'name' => 'uid',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'Uid',
            ],
            'attributes' => [
                'required' => true,
                'min'  => '1',
                'step' => '1',
                'autocomplete'=>'off',
            ],
        ]);
        //name of user
        $this->add([
            'name' => 'name',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'Name',
            ],
            'attributes' => [
                'required' => true,
                'pattern'=>'^[a-zA-Z]+$',
                'minlength' => 2,
                'maxlength'=> 100,
                'autocomplete'=>'off',
            ],
        ]);
        //email of user
        $this->add([
            'type'=>Element\Email::class,
            'name' => 'email_string',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'required' => true,
                'maxlength'=> 100,
                'pattern' => '^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$',
                'title' => 'Please input valid address'
            ],
        ]);
        //submit
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
