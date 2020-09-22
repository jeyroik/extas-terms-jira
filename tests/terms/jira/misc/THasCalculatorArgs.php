<?php
namespace tests\terms\jira\misc;

use extas\components\jira\results\issues\SearchResult;
use extas\interfaces\http\IHasHttpIO;
use extas\interfaces\terms\jira\IHasIssuesSearchResult;

/**
 * Trait THasCalculatorArgs
 *
 * @package tests\terms\jira\misc
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasCalculatorArgs
{
    /**
     * @return array
     */
    protected function getArgs(): array
    {
        return [
            IHasHttpIO::FIELD__ARGUMENTS => [
                IHasIssuesSearchResult::FIELD__ISSUES_SEARCH_RESULT => new SearchResult([
                    SearchResult::FIELD__ISSUES => [
                        [
                            'id' => 1,
                            'key' => 'JRK-1',
                            'fields' => [
                                'status' => [
                                    "name" => "Готово",
                                    "id" => "10001",
                                    "statusCategory" => [
                                        "id" => 3,
                                        "key" => "done",
                                        "colorName" => "green",
                                        "name" => "Done"
                                    ]
                                ]
                            ]
                        ],
                        [
                            'id' => 2,
                            'key' => 'JRK-2',
                            'fields' => [
                                'status' => [
                                    "name" => "In progress",
                                    "id" => "10002",
                                    "statusCategory" => [
                                        "id" => 4,
                                        "key" => "in progress",
                                        "colorName" => "blue",
                                        "name" => "In progress"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    SearchResult::FIELD__NAMES => [
                        'project' => 'Project',
                        'status' => 'Status'
                    ],
                    SearchResult::FIELD__SCHEMA => [
                        'project' => [
                            "type" => "project",
                            "system" => "project"
                        ],
                        'status' => [
                            "type" => "status",
                            "system" => "status"
                        ]
                    ]
                ])
            ]
        ];
    }
}
