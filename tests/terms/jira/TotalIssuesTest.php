<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\plugins\TSnuffPlugins;
use extas\components\terms\jira\TotalIssues;
use extas\components\terms\Term;
use extas\interfaces\samples\parameters\ISampleParameter;
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
    use TSnuffPlugins;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffPlugins();
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

        $issues = [1,2,3];
        $result = $calculator->calculateTerm($term, $issues);

        $this->assertEquals(
            3,
            $result,
            'Incorrect calculating: ' . $result
        );
    }

    /**
     * @return ITerm
     */
    protected function getTerm(): ITerm
    {
        return new Term([
            Term::FIELD__PARAMETERS => [
                TotalIssues::TERM_PARAM__MARKER => [
                    ISampleParameter::FIELD__NAME => TotalIssues::TERM_PARAM__MARKER,
                    ISampleParameter::FIELD__VALUE => true
                ]
            ]
        ]);
    }

    /**
     * @return ITermCalculator
     */
    protected function getCalculator(): ITermCalculator
    {
        return new TotalIssues();
    }
}
