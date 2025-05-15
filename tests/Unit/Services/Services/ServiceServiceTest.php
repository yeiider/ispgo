<?php

namespace Tests\Unit\Services\Services;

use App\Repositories\App\Models\Services\ServiceRepository;
use App\Services\Services\ServiceService;
use Exception;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class ServiceServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that getAll returns all services from the repository
     */
    public function test_get_all_returns_all_services(): void
    {
        // Arrange
        $expectedServices = ['service1', 'service2'];
        $repository = Mockery::mock(ServiceRepository::class);
        $repository->shouldReceive('all')->once()->andReturn($expectedServices);

        $service = new ServiceService($repository);

        // Act
        $result = $service->getAll();

        // Assert
        $this->assertEquals($expectedServices, $result);
    }

    /**
     * Test that getById returns a service by ID from the repository
     */
    public function test_get_by_id_returns_service_by_id(): void
    {
        // Arrange
        $serviceId = 1;
        $expectedService = ['id' => $serviceId, 'name' => 'Internet 100Mbps'];
        $repository = Mockery::mock(ServiceRepository::class);
        $repository->shouldReceive('getById')->with($serviceId)->once()->andReturn($expectedService);

        $service = new ServiceService($repository);

        // Act
        $result = $service->getById($serviceId);

        // Assert
        $this->assertEquals($expectedService, $result);
    }

    /**
     * Test that save passes data to the repository and returns the result
     */
    public function test_save_passes_data_to_repository(): void
    {
        // Arrange
        $serviceData = ['customer_id' => 1, 'plan_id' => 2, 'status' => 'active'];
        $expectedResult = ['id' => 1, 'customer_id' => 1, 'plan_id' => 2, 'status' => 'active'];
        $repository = Mockery::mock(ServiceRepository::class);
        $repository->shouldReceive('save')->with($serviceData)->once()->andReturn($expectedResult);

        $service = new ServiceService($repository);

        // Act
        $result = $service->save($serviceData);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that update passes data to the repository and returns the result
     */
    public function test_update_passes_data_to_repository(): void
    {
        // Arrange
        $serviceId = 1;
        $serviceData = ['status' => 'suspended'];
        $expectedResult = ['id' => $serviceId, 'status' => 'suspended'];
        $repository = Mockery::mock(ServiceRepository::class);
        $repository->shouldReceive('update')->with($serviceData, $serviceId)->once()->andReturn($expectedResult);

        $service = new ServiceService($repository);

        // Act
        $result = $service->update($serviceData, $serviceId);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that update throws an exception when the repository throws an exception
     */
    public function test_update_throws_exception_when_repository_fails(): void
    {
        // Arrange
        $serviceId = 1;
        $serviceData = ['status' => 'suspended'];
        $repository = Mockery::mock(ServiceRepository::class);
        $repository->shouldReceive('update')->with($serviceData, $serviceId)->once()->andThrow(new Exception('Repository error'));

        $service = new ServiceService($repository);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to update post data');

        // Act
        $service->update($serviceData, $serviceId);
    }

    /**
     * Test that deleteById passes ID to the repository and returns the result
     */
    public function test_delete_by_id_passes_id_to_repository(): void
    {
        // Arrange
        $serviceId = 1;
        $expectedResult = true;
        $repository = Mockery::mock(ServiceRepository::class);
        $repository->shouldReceive('delete')->with($serviceId)->once()->andReturn($expectedResult);

        $service = new ServiceService($repository);

        // Act
        $result = $service->deleteById($serviceId);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that deleteById throws an exception when the repository throws an exception
     */
    public function test_delete_by_id_throws_exception_when_repository_fails(): void
    {
        // Arrange
        $serviceId = 1;
        $repository = Mockery::mock(ServiceRepository::class);
        $repository->shouldReceive('delete')->with($serviceId)->once()->andThrow(new Exception('Repository error'));

        $service = new ServiceService($repository);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to delete post data');

        // Act
        $service->deleteById($serviceId);
    }
}
