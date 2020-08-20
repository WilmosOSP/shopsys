<?php

declare(strict_types=1);

namespace Tests\FrontendApiBundle\Functional\Article;

use Tests\FrontendApiBundle\Test\GraphQlTestCase;

class GetArticlesTest extends GraphQlTestCase
{
    /**
     * @param string $query
     * @param array $expectedArticlesData
     *
     * @dataProvider getArticlesDataProvider
     */
    public function testGetArticles(string $query, array $expectedArticlesData): void
    {
        $graphQlType = 'articles';
        $response = $this->getResponseContentForQuery($query);
        $this->assertResponseContainsArrayOfDataForGraphQlType($response, $graphQlType);
        $responseData = $this->getResponseDataForGraphQlType($response, $graphQlType);

        $this->assertArrayHasKey('edges', $responseData);
        $this->assertCount(count($expectedArticlesData), $responseData['edges']);

        foreach ($responseData['edges'] as $edge) {
            $this->assertArrayHasKey('node', $edge);

            $expectedArticleData = array_shift($expectedArticlesData);
            $this->assertArrayHasKey('name', $edge['node']);
            $this->assertSame($expectedArticleData['name'], $edge['node']['name']);

            $this->assertArrayHasKey('placement', $edge['node']);
            $this->assertSame($expectedArticleData['placement'], $edge['node']['placement']);
        }
    }

    /**
     * @return array
     */
    public function getArticlesDataProvider(): array
    {
        return [
            [
                $this->getAllArticlesQuery(),
                $this->getExpectedArticles(),
            ],
            [
                $this->getFirstArticlesQuery(2),
                array_slice($this->getExpectedArticles(), 0, 2),
            ],
            [
                $this->getFirstArticlesQuery(1),
                array_slice($this->getExpectedArticles(), 0, 1),
            ],
            [
                $this->getLastArticlesQuery(1),
                array_slice($this->getExpectedArticles(), 4, 1),
            ],
            [
                $this->getLastArticlesQuery(2),
                array_slice($this->getExpectedArticles(), 3, 2),
            ],
            [
                $this->getFirstArticlesQuery(1, 'topMenu'),
                array_slice($this->getExpectedArticles(), 3, 1),
            ],
            [
                $this->getLastArticlesQuery(1, 'topMenu'),
                array_slice($this->getExpectedArticles(), 4, 1),
            ],
            [
                $this->getAllArticlesQuery('topMenu'),
                array_slice($this->getExpectedArticles(), 3, 2),
            ],
            [
                $this->getAllArticlesQuery('non-existing-placement'),
                [],
            ],
        ];
    }

    /**
     * @param string|null $placement
     * @return string
     */
    private function getAllArticlesQuery(?string $placement = null): string
    {
        if ($placement !== null) {
            $graphQlTypeWithFilters = 'articles (placement:"' . $placement . '")';
        } else {
            $graphQlTypeWithFilters = 'articles';
        }
        return '
            {
                ' . $graphQlTypeWithFilters . ' {
                    edges {
                        node {
                            name
                            placement
                        }
                    }
                }
            }
        ';
    }

    /**
     * @param int $numberOfArticles
     * @param string|null $placement
     * @return string
     */
    private function getFirstArticlesQuery(int $numberOfArticles, ?string $placement = null): string
    {
        if ($placement !== null) {
            $graphQlTypeWithFilters = 'articles (first:' . $numberOfArticles . ', placement: "' . $placement . '")';
        } else {
            $graphQlTypeWithFilters = 'articles (first:' . $numberOfArticles . ')';
        }

        return '
            {
                ' . $graphQlTypeWithFilters . ' {
                    edges {
                        node {
                            name
                            placement
                        }
                    }
                }
            }
        ';
    }

    /**
     * @param int $numberOfArticles
     * @param string|null $placement
     * @return string
     */
    private function getLastArticlesQuery(int $numberOfArticles, ?string $placement = null): string
    {
        if ($placement !== null) {
            $graphQlTypeWithFilters = 'articles (last:' . $numberOfArticles . ', placement: "' . $placement . '")';
        } else {
            $graphQlTypeWithFilters = 'articles (last:' . $numberOfArticles . ')';
        }

        return '
            {
                ' . $graphQlTypeWithFilters . ' {
                    edges {
                        node {
                            name
                            placement
                        }
                    }
                }
            }
        ';
    }

    /**
     * @return array
     */
    private function getExpectedArticles(): array
    {
        return [
            [
                'name' => 'Terms and conditions',
                'placement' => 'footer',
            ],
            [
                'name' => 'Privacy policy',
                'placement' => 'none',
            ],
            [
                'name' => 'Information about cookies',
                'placement' => 'none',
            ],
            [
                'name' => 'News',
                'placement' => 'topMenu',
            ],
            [
                'name' => 'Shopping guide',
                'placement' => 'topMenu',
            ],
        ];
    }
}
