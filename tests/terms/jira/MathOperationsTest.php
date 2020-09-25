<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\extensions\Extension;
use extas\components\extensions\jira\fields\ExtensionNativeFields;
use extas\components\plugins\Plugin;
use extas\components\plugins\terms\jira\operations\OperationAverage;
use extas\components\plugins\terms\jira\operations\OperationDivide;
use extas\components\plugins\terms\jira\operations\OperationMedian;
use extas\components\plugins\terms\jira\operations\OperationMultiplication;
use extas\components\plugins\terms\jira\operations\OperationNamedAverage;
use extas\components\plugins\terms\jira\operations\OperationRoundResult;
use extas\components\plugins\terms\jira\operations\OperationSubtraction;
use extas\components\plugins\terms\jira\operations\OperationSum;
use extas\components\plugins\TSnuffPlugins;
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\terms\jira\MathOperations;
use extas\components\terms\jira\strategies\MathOperationCross;
use extas\components\terms\jira\strategies\MathOperationTotal;
use extas\components\terms\jira\THasIssuesSearchResult;
use extas\components\terms\Term;
use extas\interfaces\extensions\jira\fields\IExtensionNativeFields;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\jira\issues\fields\IField;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\stages\IStageTermJiraMathOperation;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use extas\interfaces\terms\jira\IHasIssuesSearchResult;
use PHPUnit\Framework\TestCase;
use tests\terms\jira\misc\THasCalculatorArgs;

/**
 * Class MathOperationsTest
 *
 * @package tests\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class MathOperationsTest extends TestCase
{
    use TSnuffRepositoryDynamic;
    use TSnuffPlugins;
    use THasCalculatorArgs;
    use THasIssuesSearchResult;

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
                'getFieldId', 'getFieldValue',
            ]
        ]));
        $this->createSnuffPlugin(
            OperationAverage::class,
            [IStageTermJiraMathOperation::NAME . '.' . OperationAverage::OPERATION__NAME]
        );
        $this->createSnuffPlugin(
            OperationDivide::class,
            [IStageTermJiraMathOperation::NAME . '.' . OperationDivide::OPERATION__NAME]
        );
        $this->createSnuffPlugin(
            OperationMedian::class,
            [IStageTermJiraMathOperation::NAME . '.' . OperationMedian::OPERATION__NAME]
        );
        $this->createSnuffPlugin(
            OperationMultiplication::class,
            [IStageTermJiraMathOperation::NAME . '.' . OperationMultiplication::OPERATION__NAME]
        );

        $this->createSnuffPlugin(
            OperationSubtraction::class,
            [IStageTermJiraMathOperation::NAME . '.' . OperationSubtraction::OPERATION__NAME]
        );
        $this->createSnuffPlugin(
            OperationSum::class,
            [IStageTermJiraMathOperation::NAME . '.' . OperationSum::OPERATION__NAME]
        );
        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => OperationNamedAverage::class,
            Plugin::FIELD__STAGE => IStageTermJiraMathOperation::NAME . '.' . OperationNamedAverage::OPERATION__NAME,
            Plugin::FIELD__PRIORITY => 0
        ]));
        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => OperationRoundResult::class,
            Plugin::FIELD__STAGE => IStageTermJiraMathOperation::NAME . '.' . OperationNamedAverage::OPERATION__NAME,
            Plugin::FIELD__PRIORITY => -1
        ]));
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
    }

    public function testCrossStrategy()
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
            6752.5,
            $result,
            'Incorrect result: ' . print_r($result, true)
        );

        $term->setParameterValue(
            MathOperations::TERM_PARAM__OPERATION,
            OperationSubtraction::OPERATION__NAME
        );
        $term->setParameterValue(
            MathOperationCross::TERM_PARAM__CROSS_OPERATION,
            OperationMedian::OPERATION__NAME
        );

        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            6747.5,
            $result,
            'Incorrect result: ' . print_r($result, true)
        );

        $term->setParameterValue(
            MathOperations::TERM_PARAM__OPERATION,
            OperationDivide::OPERATION__NAME
        );
        $term->setParameterValue(
            MathOperationCross::TERM_PARAM__CROSS_OPERATION,
            OperationMultiplication::OPERATION__NAME
        );

        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            1290000,
            $result,
            'Incorrect result: ' . print_r($result, true)
        );

        $term->setParameterValue(
            MathOperations::TERM_PARAM__OPERATION,
            OperationDivide::OPERATION__NAME
        );
        $term->setParameterValue(
            MathOperationCross::TERM_PARAM__CROSS_OPERATION,
            OperationNamedAverage::OPERATION__NAME
        );
        $term->addParameterByValue(OperationNamedAverage::TERM_PARAM__FUNCTION_NAME, 'powerMean');
        $term->addParameterByValue(OperationNamedAverage::TERM_PARAM__FUNCTION_ARGS, [2]);
        $term->addParameterByValue(OperationRoundResult::TERM_PARAM__ROUND_PRECISION, 2);

        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            3047.95,
            $result,
            'Incorrect result: ' . print_r($result, true)
        );

        $term->setParameterValue(OperationNamedAverage::TERM_PARAM__FUNCTION_NAME, 'unknown');
        $term->setParameterValue(OperationNamedAverage::TERM_PARAM__FUNCTION_ARGS, [2]);

        $result = $calculator->calculateTerm($term, $args);
        $this->assertEmpty($result, 'There is result: ' . print_r($result, true));

        $term->setParameterValue(OperationNamedAverage::TERM_PARAM__FUNCTION_NAME, 'powerMean');
        $term->setParameterValue(MathOperations::TERM_PARAM__STRATEGY, '');

        $result = $calculator->calculateTerm($term, $args);
        $this->assertEmpty($result, 'There is result: ' . print_r($result, true));
    }

    public function testTotalStrategy()
    {
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();
        $term->setParameterValue(MathOperations::TERM_PARAM__STRATEGY, MathOperationTotal::class);
        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            13505,
            $result,
            'Incorrect result: ' . $result
        );
    }

    public function testFailOperations()
    {
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();
        $result = $this->getIssuesSearchResult($args);
        $result[$result::FIELD__ISSUES] = [];
        $args[IHasHttpIO::FIELD__ARGUMENTS][IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT] = $result;

        $result = $calculator->calculateTerm($term, $args);
        $this->assertEmpty($result, 'There is result: ' . print_r($result, true));
    }

    protected function getTerm(): ITerm
    {
        return new Term([
            Term::FIELD__NAME => 'test',
            Term::FIELD__PARAMETERS => [
                MathOperations::TERM_PARAM__MARKER => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__MARKER,
                    ISampleParameter::FIELD__VALUE => true
                ],
                MathOperations::TERM_PARAM__STRATEGY => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__STRATEGY,
                    ISampleParameter::FIELD__VALUE => MathOperationCross::class
                ],
                MathOperations::TERM_PARAM__FIELDS => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__FIELDS,
                    ISampleParameter::FIELD__VALUE => ['timespent', 'priority', 'unknown']
                ],
                MathOperations::TERM_PARAM__SUBFIELDS => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__SUBFIELDS,
                    ISampleParameter::FIELD__VALUE => ['priority' => 'id']
                ],
                MathOperations::TERM_PARAM__OPERATION => [
                    ISampleParameter::FIELD__NAME => MathOperations::TERM_PARAM__OPERATION,
                    ISampleParameter::FIELD__VALUE => OperationSum::OPERATION__NAME
                ],
                MathOperationCross::TERM_PARAM__CROSS_OPERATION => [
                    ISampleParameter::FIELD__NAME => MathOperationCross::TERM_PARAM__CROSS_OPERATION,
                    ISampleParameter::FIELD__VALUE => OperationAverage::OPERATION__NAME
                ]
            ]
        ]);
    }

    protected function getCalculator(): ITermCalculator
    {
        return new MathOperations();
    }
}
