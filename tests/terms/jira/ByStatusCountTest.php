<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\extensions\Extension;
use extas\components\extensions\jira\fields\ExtensionNativeFields;
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\terms\jira\ByStatusIssuesCount;
use extas\components\terms\Term;
use extas\interfaces\extensions\jira\fields\IExtensionNativeFields;
use extas\interfaces\jira\issues\fields\IField;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use PHPUnit\Framework\TestCase;
use tests\terms\jira\misc\THasCalculatorArgs;

/**
 * Class ByStatusCountTest
 *
 * @package tests\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class ByStatusCountTest extends TestCase
{
    use TSnuffRepositoryDynamic;
    use THasCalculatorArgs;

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
                'getFieldId', 'getFieldName', 'getFieldStatusCategory',
            ]
        ]));
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
    }

    public function testCountByStatusCategory()
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
            'Incorrect by status category count: ' . $result
        );

        /**
         * Check calculator idempotency
         */
        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            2,
            $result,
            'Incorrect by status category count: ' . $result
        );
    }

    public function testCountByStatusSimpleField()
    {
        $term = $this->getTerm();
        $calculator = $this->getCalculator();
        $args = $this->getArgs();
        $term->setParameterValue(ByStatusIssuesCount::TERM_PARAM__STATUS_VARIANTS, ['Готово']);
        $calculator->setParameterValue(ByStatusIssuesCount::PARAM__STATUS_FIELD_NAME, 'name');

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $result = $calculator->calculateTerm($term, $args);
        $this->assertEquals(
            1,
            $result,
            'Incorrect by status name count: ' . $result
        );
    }

    /**
     * @return ITerm
     */
    protected function getTerm(): ITerm
    {
        return new Term([
            Term::FIELD__PARAMETERS => [
                ByStatusIssuesCount::TERM_NAME__COUNT_BY_STATUS => [
                    ISampleParameter::FIELD__NAME => ByStatusIssuesCount::TERM_NAME__COUNT_BY_STATUS,
                    ISampleParameter::FIELD__VALUE => true
                ],
                ByStatusIssuesCount::TERM_PARAM__STATUS_VARIANTS => [
                    ISampleParameter::FIELD__NAME => ByStatusIssuesCount::TERM_PARAM__STATUS_VARIANTS,
                    ISampleParameter::FIELD__VALUE => [3,4]
                ]
            ]
        ]);
    }

    /**
     * @return ITermCalculator
     */
    protected function getCalculator(): ITermCalculator
    {
        return new ByStatusIssuesCount([
            ByStatusIssuesCount::FIELD__PARAMETERS => [
                ByStatusIssuesCount::PARAM__STATUS_FIELD_NAME => [
                    ISampleParameter::FIELD__NAME => ByStatusIssuesCount::PARAM__STATUS_FIELD_NAME,
                    ISampleParameter::FIELD__VALUE => 'statusCategory'
                ],
                ByStatusIssuesCount::PARAM__CATEGORY_FIELD_NAME => [
                    ISampleParameter::FIELD__NAME => ByStatusIssuesCount::PARAM__CATEGORY_FIELD_NAME,
                    ISampleParameter::FIELD__VALUE => 'id'
                ]
            ]
        ]);
    }
}
