<?php

declare(strict_types=1);

namespace GenderApiIntegration;

/**
 * Integration tests for multiple names batch lookup endpoints
 *
 * @group integration
 */
class MultipleNamesTest extends IntegrationTestCase
{
    public function testGetByMultipleNames(): void
    {
        $names = ['Markus', 'Sandra', 'Andrea'];
        $result = $this->client->getByMultipleNames($names);

        $this->assertCount(3, $result);
        $this->assertEquals(3, $result->count());

        $resultsMap = [];
        foreach ($result as $nameResult) {
            $resultsMap[$nameResult->getFirstName()] = $nameResult->getGender();
            $this->assertNotNull($nameResult->getAccuracy());
        }

        $this->assertEquals('male', $resultsMap['Markus']);
        $this->assertEquals('female', $resultsMap['Sandra']);

        // Andrea without country is usually female global average/or male depending on samples but mostly known as female/male ambiguity
        $this->assertArrayHasKey('Andrea', $resultsMap);
    }

    public function testGetByMultipleNamesAndCountry(): void
    {
        $names = ['Andrea', 'Luca'];

        // In Italy, both are male
        $result = $this->client->getByMultipleNamesAndCountry($names, 'IT');

        $this->assertCount(2, $result);

        foreach ($result as $nameResult) {
            $this->assertEquals('male', $nameResult->getGender());
            $this->assertEquals('IT', $nameResult->getCountry());
        }
    }
}
