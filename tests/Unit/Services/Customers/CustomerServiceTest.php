<?php

namespace Tests\Unit\Services\Customers;

use App\Repositories\App\Models\Customers\CustomerRepository;
use App\Services\Customers\CustomerService;
use Exception;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class CustomerServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that getAll returns all customers from the repository
     */
    public function test_get_all_returns_all_customers(): void
    {
        // Arrange
        $expectedCustomers = ['customer1', 'customer2'];
        $repository = Mockery::mock(CustomerRepository::class);
        $repository->shouldReceive('all')->once()->andReturn($expectedCustomers);

        $service = new CustomerService($repository);

        // Act
        $result = $service->getAll();

        // Assert
        $this->assertEquals($expectedCustomers, $result);
    }

    /**
     * Test that getById returns a customer by ID from the repository
     */
    public function test_get_by_id_returns_customer_by_id(): void
    {
        // Arrange
        $customerId = 1;
        $expectedCustomer = ['id' => $customerId, 'name' => 'Test Customer'];
        $repository = Mockery::mock(CustomerRepository::class);
        $repository->shouldReceive('getById')->with($customerId)->once()->andReturn($expectedCustomer);

        $service = new CustomerService($repository);

        // Act
        $result = $service->getById($customerId);

        // Assert
        $this->assertEquals($expectedCustomer, $result);
    }

    /**
     * Test that save passes data to the repository and returns the result
     */
    public function test_save_passes_data_to_repository(): void
    {
        // Arrange
        $customerData = ['name' => 'New Customer', 'email' => 'test@example.com'];
        $expectedResult = ['id' => 1, 'name' => 'New Customer', 'email' => 'test@example.com'];
        $repository = Mockery::mock(CustomerRepository::class);
        $repository->shouldReceive('save')->with($customerData)->once()->andReturn($expectedResult);

        $service = new CustomerService($repository);

        // Act
        $result = $service->save($customerData);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that update passes data to the repository and returns the result
     */
    public function test_update_passes_data_to_repository(): void
    {
        // Arrange
        $customerId = 1;
        $customerData = ['name' => 'Updated Customer'];
        $expectedResult = ['id' => $customerId, 'name' => 'Updated Customer'];
        $repository = Mockery::mock(CustomerRepository::class);
        $repository->shouldReceive('update')->with($customerData, $customerId)->once()->andReturn($expectedResult);

        $service = new CustomerService($repository);

        // Act
        $result = $service->update($customerData, $customerId);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that update throws an exception when the repository throws an exception
     */
    public function test_update_throws_exception_when_repository_fails(): void
    {
        // Arrange
        $customerId = 1;
        $customerData = ['name' => 'Updated Customer'];
        $repository = Mockery::mock(CustomerRepository::class);
        $repository->shouldReceive('update')->with($customerData, $customerId)->once()->andThrow(new Exception('Repository error'));

        $service = new CustomerService($repository);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to update post data');

        // Act
        $service->update($customerData, $customerId);
    }

    /**
     * Test that deleteById passes ID to the repository and returns the result
     */
    public function test_delete_by_id_passes_id_to_repository(): void
    {
        // Arrange
        $customerId = 1;
        $expectedResult = true;
        $repository = Mockery::mock(CustomerRepository::class);
        $repository->shouldReceive('delete')->with($customerId)->once()->andReturn($expectedResult);

        $service = new CustomerService($repository);

        // Act
        $result = $service->deleteById($customerId);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that deleteById throws an exception when the repository throws an exception
     */
    public function test_delete_by_id_throws_exception_when_repository_fails(): void
    {
        // Arrange
        $customerId = 1;
        $repository = Mockery::mock(CustomerRepository::class);
        $repository->shouldReceive('delete')->with($customerId)->once()->andThrow(new Exception('Repository error'));

        $service = new CustomerService($repository);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to delete post data');

        // Act
        $service->deleteById($customerId);
    }
}
