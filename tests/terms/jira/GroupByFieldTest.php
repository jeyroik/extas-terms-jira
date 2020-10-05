<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\extensions\Extension;
use extas\components\extensions\jira\fields\ExtensionNativeFields;
use extas\components\plugins\Plugin;
use extas\components\plugins\terms\jira\groups\GroupIssuesCount;
use extas\components\plugins\terms\jira\groups\GroupMathOperations;
use extas\components\plugins\terms\jira\operations\OperationSum;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\terms\jira\GroupByField;
use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\strategies\MathOperationTotal;
use extas\components\terms\Term;
use extas\components\THasMagicClass;
use extas\interfaces\extensions\jira\fields\IExtensionNativeFields;
use extas\interfaces\jira\issues\fields\IField;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\stages\IStageTermJiraGroupBy;
use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use PHPUnit\Framework\TestCase;
use tests\terms\jira\misc\THasCalculatorArgs;

/**
 * Class GroupByFieldTest
 *
 * @package tests\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class GroupByFieldTest extends TestCase
{
    use THasCalculatorArgs;
    use TSnuffRepositoryDynamic;
    use TSnuffPlugins;

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
        $this->deleteSnuffDynamicRepositories();
    }

    public function testIssuesCount()
    {
        $this->createSnuffPlugin(GroupIssuesCount::class, [IStageTermJiraGroupBy::NAME . '.creator']);
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            [
                GroupIssuesCount::FIELD__SELF_MARKER . '.unknown' => [
                    'jeyroik' => 1
                ]
            ],
            $result,
            'Incorrect result: ' . print_r($result, true)
        );
    }

    public function testMathOperations()
    {
        $this->createSnuffPlugin(GroupMathOperations::class, [IStageTermJiraGroupBy::NAME . '.creator']);
        $this->createSnuffPlugin(
            OperationSum::class,
            [IStageTermJiraMathOperation::NAME . '.' . OperationSum::OPERATION__NAME]
        );
        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => GroupMathOperations::class,
            Plugin::FIELD__STAGE => [IStageTermJiraGroupBy::NAME . '.creator'],
            Plugin::FIELD__PARAMETERS => [
                MathOperations::TERM_PARAM__FIELDS => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__FIELDS,
                    ISampleParameter::FIELD__VALUE => ['timespent']
                ],
                'test' => [
                    ISampleParameter::FIELD__NAME => 'test',
                    ISampleParameter::FIELD__VALUE => ['timespent']
                ]
            ]
        ]));
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            [
                GroupMathOperations::FIELD__SELF_MARKER . '.unknown' => [
                    'jeyroik' => 12900
                ]
            ],
            $result,
            'Incorrect result: ' . print_r($result, true)
        );
    }

    protected function getTerm(): ITerm
    {
        return new Term([
            Term::FIELD__PARAMETERS => [
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

    protected function getCalculator(): ITermCalculator
    {
        return new GroupByField();
    }
}
