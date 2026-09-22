<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Services\ServiceImporterService;
use App\Models\Services\Service;
use App\Models\Services\AdditionalPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;

class ServiceImporterServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ServiceImporterService $importer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importer = new ServiceImporterService();
    }

    protected function callParseAdditionalPlanIds($value): array
    {
        $reflection = new ReflectionClass(ServiceImporterService::class);
        $method = $reflection->getMethod('parseAdditionalPlanIds');
        $method->setAccessible(true);
        return $method->invoke($this->importer, $value);
    }

    public function test_parses_single_id_correctly()
    {
        $result = $this->callParseAdditionalPlanIds('1');
        $this->assertEquals([1], $result);

        $result2 = $this->callParseAdditionalPlanIds(5);
        $this->assertEquals([5], $result2);
    }

    public function test_parses_bracket_array_correctly()
    {
        $result = $this->callParseAdditionalPlanIds('[1, 2]');
        $this->assertEquals([1, 2], $result);

        $result2 = $this->callParseAdditionalPlanIds('[10,11, 12]');
        $this->assertEquals([10, 11, 12], $result2);
    }

    public function test_parses_empty_values_to_empty_array()
    {
        $this->assertEquals([], $this->callParseAdditionalPlanIds(null));
        $this->assertEquals([], $this->callParseAdditionalPlanIds(''));
        $this->assertEquals([], $this->callParseAdditionalPlanIds('[]'));
    }
}
