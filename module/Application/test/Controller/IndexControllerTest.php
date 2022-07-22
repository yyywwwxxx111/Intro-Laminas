<?php

//declare(strict_types=1);

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Application\Model\EmailTable;
use Laminas\ServiceManager\ServiceManager;
use Application\Model\Email;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;
use Prophecy\Argument;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    protected $emailTable;

    protected $traceError = true;

    public function setUp(): void
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
//        $services = $this->getApplicationServiceLocator();
//        $config = $services->get('config');
//        unset($config['db']);
//        $services->setAllowOverride(true);
//        $services->setService('config', $config);
//        $services->setAllowOverride(false);
        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    public function testIndexActionCanBeAccessed(): void
    {
        $this->emailTable->fetchAll()->willReturn([]);

        $this->dispatch('/email');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('email');
    }
    public function testAddActionRedirectsAfterValidPost()
    {
        $this->emailTable
            ->getName(1)
            ->shouldBeCalled();
        $this->emailTable
            ->findEmail("3317@nyu.edu")
            ->shouldBeCalled();
        $this->emailTable
            ->saveEmail(Argument::type(Email::class))
            ->shouldBeCalled();

        $postData = [
            'uid' => '1',
            'name'  => 'LedIadadad',
            'email_string' => "3317@nyu.edu",
            'id'     => '',
        ];
        $this->dispatch('/email/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/email');
    }

    public function testSearchAction()
    {
        $this->emailTable->getEmails(1)->willReturn([]);
        $this->emailTable
            ->getEmail(1)
            ->shouldBeCalled();
        $this->emailTable
            ->getEmails(1)
            ->shouldBeCalled();
        $this->dispatch('/email/search/1');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName(IndexController::class); // as specified in router's controller name alias
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('email');
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(EmailTable::class, $this->mockEmailTable()->reveal());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];
        return $config;
    }
    protected function mockEmailTable()
    {
        $this->emailTable = $this->prophesize(EmailTable::class);
        return $this->emailTable;
    }

}
