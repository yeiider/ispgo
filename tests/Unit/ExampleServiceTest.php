<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleServiceTest extends TestCase
{
    /**
     * A basic test example for a service.
     */
    public function test_example_service_returns_expected_value(): void
    {
        // Arrange
        $expectedValue = 'expected result';

        // Act
        $actualValue = $this->getExampleServiceResult();

        // Assert
        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * Helper method that simulates a service call.
     */
    private function getExampleServiceResult(): string
    {
        // In a real test, you would instantiate and call an actual service
        return 'expected result';
    }
}
