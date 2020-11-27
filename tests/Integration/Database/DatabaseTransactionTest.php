<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;

class DatabaseTransactionTest extends TestCase
{
    // use DatabaseTransactions;

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', 'true');
        $app['config']->set('database.default', 'mysql');
    }

    protected function setUp(): void
    {
        if (! isset($_SERVER['CI']) || windows_os()) {
            $this->markTestSkipped('This test is only executed on CI in Linux.');
        }

        parent::setUp();
    }

    /**
     * @link https://dev.mysql.com/doc/refman/8.0/en/implicit-commit.html
     */
    public function test_it_can_handle_implicit_commits()
    {
        $database = $this->app->make('db');

        $database->connection('mysql')->beginTransaction();

        DB::unprepared('CREATE TABLE a (col varchar(1) null)');

        $connection = $database->connection('mysql');

        $connection->rollBack();
        $connection->disconnect();

        $this->assertTrue(true);
    }
}
