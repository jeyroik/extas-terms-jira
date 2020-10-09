<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\extensions\Extension;
use extas\components\extensions\jira\fields\ExtensionNativeFields;
use extas\components\plugins\terms\jira\operations\OperationSum;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\terms\jira\GroupByField;
use extas\components\terms\jira\JiraTermCalculator;
use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\strategies\MathOperationTotal;
use extas\components\terms\jira\TotalIssues;
use extas\components\terms\Term;
use extas\interfaces\extensions\jira\fields\IExtensionNativeFields;
use extas\interfaces\jira\issues\fields\IField;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\stages\IStageTermJiraAfterCalculate;
use extas\interfaces\stages\IStageTermJiraBeforeCalculate;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use PHPUnit\Framework\TestCase;
use tests\terms\jira\misc\PluginAfterCalculate;
use tests\terms\jira\misc\PluginBeforeCalculate;
use tests\terms\jira\misc\THasCalculatorArgs;

class JiraTermCalculatorTest extends TestCase
{
    use THasCalculatorArgs;
    use TSnuffPlugins;
    use TSnuffRepositoryDynamic;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->createSnuffDynamicRepositories([]);
        $this->createWithSnuffRepo('extensionRepository', new Extension([
            Extension::FIELD__CLASS => ExtensionNativeFields::class,
            Extension::FIELD__INTERFACE => IExtensionNativeFields::class,
            Extension::FIELD__SUBJECT => IField::SUBJECT,
            Extension::FIELD__METHODS => [
                'getFieldName', 'getFieldValue'
            ]
        ]));
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffPlugins();
    }

    public function testBeforeAndAfterCalculate()
    {
        $term = $this->getTerm();
        $calculator = new TotalIssues();
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

        $result = $calculator->calculateTerm($term, $args);

        $this->assertArrayHasKey(
            'before.worked',
            $term->getParametersValues(),
            'Incorrect before calculate'
        );

        $this->assertEquals(
            12,
            $result,
            'Incorrect calculating: ' . print_r($result, true)
        );
    }

    public function testCalculatorAsPlugin()
    {
        $term = $this->getTerm();
        $calculator = new GroupByField();
        $args = $this->getArgs();

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $this->createSnuffPlugin(
            TotalIssues::class,
            [IStageTermJiraAfterCalculate::NAME . '.' . GroupByField::TERM_PARAM__MARKER]
        );

        $result = $calculator->calculateTerm($term, $args);

        $this->assertArrayHasKey(
            JiraTermCalculator::RESULT__SOURCE,
            $result,
            'Missed "' . JiraTermCalculator::RESULT__SOURCE . '": ' . print_r($result, true)
        );

        $this->assertArrayHasKey(
            TotalIssues::TERM_PARAM__MARKER,
            $result,
            'Missed "' . TotalIssues::TERM_PARAM__MARKER . '": ' . print_r($result, true)
        );

        $this->assertEquals(1, $result[TotalIssues::TERM_PARAM__MARKER], 'Incorrect total issues count');
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
                ],
                GroupByField::TERM_PARAM__MARKER => [
                    ISampleParameter::FIELD__NAME => GroupByField::TERM_PARAM__MARKER,
                    ISampleParameter::FIELD__VALUE => true
                ],
                GroupByField::TERM_PARAM__FIELD_NAME => [
                    ISampleParameter::FIELD__NAME => GroupByField::TERM_PARAM__FIELD_NAME,
                    ISampleParameter::FIELD__VALUE => 'creator'
                ],
                GroupByField::TERM_PARAM__SUBFIELD_NAME => [
                    ISampleParameter::FIELD__NAME => GroupByField::TERM_PARAM__SUBFIELD_NAME,
                    ISampleParameter::FIELD__VALUE => 'name'
                ],
                MathOperations::TERM_PARAM__STRATEGY => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__STRATEGY,
                    ISampleParameter::FIELD__VALUE => MathOperationTotal::class
                ],
                GroupByField::TERM_PARAM__DO_RUN_STAGE => [
                    ISampleParameter::FIELD__NAME => GroupByField::TERM_PARAM__DO_RUN_STAGE,
                    ISampleParameter::FIELD__VALUE => false
                ],
                MathOperations::TERM_PARAM__OPERATION => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__OPERATION,
                    ISampleParameter::FIELD__VALUE => OperationSum::OPERATION__NAME
                ],
                MathOperations::TERM_PARAM__FIELDS => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__FIELDS,
                    ISampleParameter::FIELD__VALUE => ['timespent']
                ]
            ]
        ]);
    }
}
