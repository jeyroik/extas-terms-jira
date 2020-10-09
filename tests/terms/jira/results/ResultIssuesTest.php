<?php
namespace tests\terms\jira\results;

use Dotenv\Dotenv;
use extas\components\terms\jira\results\ResultIssues;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultIssuesTest
 *
 * @package tests\terms\jira\results
 * @author jeyroik <jeyroik@gmail.com>
 */
class ResultIssuesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testBasicMethods()
    {
        $result = new ResultIssues();
        $result->setIssues([1,2,3]);
        $this->assertEquals([1,2,3], $result->getIssues());
        $this->assertEquals([1,2,3], $result->export());
    }
}
