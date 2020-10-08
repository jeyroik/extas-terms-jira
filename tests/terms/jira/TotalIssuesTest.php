<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\plugins\TSnuffPlugins;
use extas\components\terms\jira\TotalIssues;
use extas\components\terms\Term;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\stages\IStageTermJiraAfterCalculate;
use extas\interfaces\stages\IStageTermJiraBeforeCalculate;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use PHPUnit\Framework\TestCase;
use tests\terms\jira\misc\PluginAfterCalculate;
use tests\terms\jira\misc\PluginBeforeCalculate;
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

    public function testBeforeAndAfterCalculate()
    {
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $this->createSnuffPlugin(
            PluginBeforeCalculate::class,
            [IStageTermJiraBeforeCalculate::NAME . '.' . TotalIssues::TERM_PARAM__MARKER]
        );
        $this->createSnuffPlugin(
            PluginAfterCalculate::class,
            [IStageTermJiraAfterCalculate::NAME . '.' . TotalIssues::TERM_PARAM__MARKER]
        );

        $issues = [1,2,3];
        $result = $calculator->calculateTerm($term, $issues);

        $this->assertArrayHasKey(
            'before.worked',
            $term->getParametersValues(),
            'Incorrect before calculate'
        );

        $this->assertEquals(
            13,
            $result,
            'Incorrect calculating: ' . $result
        );
    }

    public function testCalculatorAsPlugin()
    {
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $this->createSnuffPlugin(
            TotalIssues::class,
            [IStageTermJiraAfterCalculate::NAME . '.' . TotalIssues::TERM_PARAM__MARKER]
        );

        $issues = [1,2,3];
        $result = $calculator->calculateTerm($term, $issues);

        $this->assertEquals(
            ['source' => 3, 'jira__total_issues' => 3],
            $result,
            'Incorrect calculating: ' . print_r($result, true)
        );
    }

    /**
     * @return ITerm
     */
    protected function getTerm(): ITerm
    {
        return new Term([
            Term::FIELD__NAME => 'test',
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
