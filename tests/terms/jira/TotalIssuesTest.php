<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\terms\jira\TotalIssues;
use extas\components\terms\Term;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use PHPUnit\Framework\TestCase;
use tests\terms\jira\misc\THasCalculatorArgs;

/**
 * Class TotalIssuesTest
 *
 * @package tests\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class TotalIssuesTest extends TestCase
{
    use THasCalculatorArgs;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testCount()
    {
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            2,
            $result,
            'Incorrect calculating: ' . $result
        );
    }

    protected function getTerm(): ITerm
    {
        return new Term([
            Term::FIELD__NAME => TotalIssues::TERM__TOTAL_ISSUES
        ]);
    }

    protected function getCalculator(): ITermCalculator
    {
        return new TotalIssues();
    }
}
