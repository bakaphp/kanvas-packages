<?php

namespace Helper;

include __DIR__ . '/../../providers.php';

// use Canvas\Bootstrap\IntegrationTests;

use Codeception\Module;
use Codeception\TestInterface;
use Kanvas\Packages\Social\Providers\DatabaseProvider;
use Kanvas\Packages\Social\Providers\QueueProvider;
use Kanvas\Packages\Social\Providers\RedisProvider;
use Kanvas\Packages\Test\Support\Helper\Phinx;
use Kanvas\Packages\Test\Support\Models\App;
use Kanvas\Packages\Test\Support\Models\Users;
use Phalcon\Config as PhConfig;
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
    protected $config = ['rollback' => false];

    /**
     * Test initializer.
     */
    public function _before(TestInterface $test)
    {
        PhDI::reset();
        $this->diContainer = new Di();
        $this->setDi($this->diContainer);

        $this->diContainer->setShared('userData', new Users());
        $this->diContainer->setShared('userProvider', new Users());
        $this->diContainer->setShared('app', new App());

        $this->savedModels = [];
        $this->savedRecords = [];
    }

    public function _after(TestInterface $test)
    {
    }

    /**
     * Run migration.
     *
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        //Phinx::migrate();
        //Phinx::seed();
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
     * @param string $name
     *
     * @return mixed
     */
    public function grabFromDi(string $name)
    {
        return $this->diContainer->get($name);
    }

    /**
     * @param array $configData
     */
    public function haveConfig(array $configData)
    {
        $config = new PhConfig($configData);
        $this->diContainer->set('config', $config);
    }

    /**
     * Create a record for $modelName with fields provided.
     *
     * @param string $modelName
     * @param array  $fields
     *
     * @return mixed
     */
    public function haveRecordWithFields(string $modelName, array $fields = [])
    {
        $record = new $modelName;
        foreach ($fields as $key => $val) {
            $record->set($key, $val);
        }
        $this->savedModels[$modelName] = $fields;
        $result = $record->save();
        $this->assertNotSame(false, $result);
        $this->savedRecords[] = $record;
        return $record;
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

    /**
     * Checks that record exists and has provided fields.
     *
     * @param $model
     * @param $by
     * @param $fields
     */
    public function seeRecordSaved($model, $by, $fields)
    {
        $this->savedModels[$model] = array_merge($by, $fields);
        $record = $this->seeRecordFieldsValid(
            $model,
            array_keys($by),
            array_keys($by)
        );
        $this->savedRecords[] = $record;
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

        $db = new DatabaseProvider();
        $db->register($this->diContainer);

        $queue = new QueueProvider();
        $queue->register($this->diContainer);

        $redis = new RedisProvider();
        $redis->register($this->diContainer);
        $providers = include __DIR__ . '/../../providers.php';
        foreach ($providers as $provider) {
            (new $provider())->register($this->diContainer);
        }
    }
}
