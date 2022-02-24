<?php

namespace Helper;

use Canvas\Http\Request;
use Canvas\Models\Apps;
use Canvas\Models\Users;
use Codeception\Module;
use Codeception\TestInterface;
use Faker\Factory;
use Faker\Generator;
use Kanvas\Inventory\Tests\Support\Models\Users as ModelsUsers;
use Kanvas\Packages\Test\Support\Helper\Phinx;
use Phalcon\Di;
use Phalcon\DI\FactoryDefault as PhDI;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Metadata\Memory;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
class Integration extends Module
{
    /**
     * @var null|PhDI
     */
    protected $diContainer = null;
    protected $savedModels = [];
    protected $savedRecords = [];
    protected $config = [
        'rollback' => false
    ];

    /**
     * Test initializer.
     */
    public function _before(TestInterface $test)
    {
        PhDI::reset();
        $this->diContainer = new Di();
        $this->setDi($this->diContainer);

        $this->diContainer->setShared('userData', new ModelsUsers());
        $this->diContainer->setShared('userProvider', new Users());
        $this->diContainer->setShared('app', new Apps());
        $this->diContainer->setShared('request', new Request());

        $this->savedModels = [];
        $this->savedRecords = [];
    }

    public function _after(TestInterface $test)
    {
    }

    /**
     * After all is done.
     *
     * @return void
     */
    public function _afterSuite()
    {
        //Phinx::dropTables();
    }

    /**
     * @return mixed
     */
    public function grabDi()
    {
        return $this->diContainer;
    }

    /**
     * Faker data.
     *
     * @return void
     */
    public function faker() : Generator
    {
        return Factory::create();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function grabFromDi(string $name)
    {
        return $this->diContainer->get($name);
    }

    /**
     * @param string $name
     * @param mixed  $service
     */
    public function haveService(string $name, $service)
    {
        $this->diContainer->set($name, $service);
    }

    /**
     * @param string $name
     */
    public function removeService(string $name)
    {
        if ($this->diContainer->has($name)) {
            $this->diContainer->remove($name);
        }
    }

    protected function setDi()
    {
        $this->diContainer->setShared(
            'modelsManager',
            function () {
                return new ModelsManager();
            }
        );

        $this->diContainer->setShared(
            'modelsMetadata',
            function () {
                return new Memory();
            }
        );
        $providers = include __DIR__ . '/../../providers.php';
        foreach ($providers as $provider) {
            (new $provider())->register($this->diContainer);
        }
    }
}
