<?php
namespace tests\terms\jira;

use Dotenv\Dotenv;
use extas\components\extensions\Extension;
use extas\components\extensions\jira\fields\ExtensionNativeFields;
use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\terms\jira\THasIssuesSearchResult;
use extas\components\terms\jira\TotalByField;
use extas\components\terms\Term;
use extas\interfaces\extensions\jira\fields\IExtensionNativeFields;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\jira\issues\fields\IField;
use extas\interfaces\jira\results\issues\ISearchResult;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\interfaces\terms\ITerm;
use extas\interfaces\terms\ITermCalculator;
use extas\interfaces\terms\jira\IHasIssuesSearchResult;
use PHPUnit\Framework\TestCase;
use tests\terms\jira\misc\THasCalculatorArgs;

/**
 * Class TotalByFieldTest
 *
 * @package tests\terms\jira
 * @author jeyroik <jeyroik@gmail.com>
 */
class TotalByFieldTest extends TestCase
{
    use THasCalculatorArgs;
    use THasIssuesSearchResult;
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
                'getFieldId', 'getFieldValue',
            ]
        ]));
    }

    protected function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
    }

    public function testHasIssueSearchResult()
    {
        $args = $this->setIssuesSearchResult([]);
        $this->assertNotEmpty($args, 'Incorrect arguments');
        $this->assertArrayHasKey(IHasHttpIO::FIELD__ARGUMENTS, $args, 'Missed arguments key');
        $this->assertArrayHasKey(
            IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT,
            $args[IHasHttpIO::FIELD__ARGUMENTS],
            'Incorrect arguments'
        );
        $this->assertInstanceOf(
            ISearchResult::class,
            $args[IHasHttpIO::FIELD__ARGUMENTS][IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT],
            'Incorrect search result'
        );
    }

    public function testCountByField()
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
            13500,
            $result,
            'Incorrect by timespent result: ' . $result
        );
    }

    public function testCountBySubfield()
    {
        $term = $this->getTerm();
        $term->setParameterValue(TotalByField::TERM_PARAM__FIELD_NAME, 'priority');
        $term->addParameterByValue(TotalByField::TERM_PARAM__SUB_FIELD_NAME, 'id');
        $calculator = $this->getCalculator();
        $args = $this->getArgs();

        $this->assertTrue(
            $calculator->canCalculate($term, $args),
            'Incorrect calculate possibility'
        );

        $result = $calculator->calculateTerm($term, $args);

        $this->assertEquals(
            5,
            $result,
            'Incorrect by priority.id result: ' . $result
        );
    }

    protected function getTerm(): ITerm
    {
        return new Term([
            Term::FIELD__PARAMETERS => [
                TotalByField::TERM_PARAM__MARKER => [
                    ISampleParameter::FIELD__NAME => TotalByField::TERM_PARAM__MARKER,
                    ISampleParameter::FIELD__VALUE => true
                ],
                TotalByField::TERM_PARAM__FIELD_NAME => [
                    ISampleParameter::FIELD__NAME => TotalByField::TERM_PARAM__FIELD_NAME,
                    ISampleParameter::FIELD__VALUE => 'timespent'
                ]
            ]
        ]);
    }

    protected function getCalculator(): ITermCalculator
    {
        return new TotalByField();
    }
}
