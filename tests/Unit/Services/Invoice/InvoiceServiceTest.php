<?php

namespace Tests\Unit\Services\Invoice;

use App\Repositories\App\Models\Invoice\InvoiceRepository;
use App\Services\Invoice\InvoiceService;
use Exception;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test that getAll returns all invoices from the repository
     */
    public function test_get_all_returns_all_invoices(): void
    {
        // Arrange
        $expectedInvoices = ['invoice1', 'invoice2'];
        $repository = Mockery::mock(InvoiceRepository::class);
        $repository->shouldReceive('all')->once()->andReturn($expectedInvoices);

        $service = new InvoiceService($repository);

        // Act
        $result = $service->getAll();

        // Assert
        $this->assertEquals($expectedInvoices, $result);
    }

    /**
     * Test that getById returns an invoice by ID from the repository
     */
    public function test_get_by_id_returns_invoice_by_id(): void
    {
        // Arrange
        $invoiceId = 1;
        $expectedInvoice = ['id' => $invoiceId, 'number' => 'INV-001'];
        $repository = Mockery::mock(InvoiceRepository::class);
        $repository->shouldReceive('getById')->with($invoiceId)->once()->andReturn($expectedInvoice);

        $service = new InvoiceService($repository);

        // Act
        $result = $service->getById($invoiceId);

        // Assert
        $this->assertEquals($expectedInvoice, $result);
    }

    /**
     * Test that save passes data to the repository and returns the result
     */
    public function test_save_passes_data_to_repository(): void
    {
        // Arrange
        $invoiceData = ['customer_id' => 1, 'amount' => 100.00];
        $expectedResult = ['id' => 1, 'customer_id' => 1, 'amount' => 100.00];
        $repository = Mockery::mock(InvoiceRepository::class);
        $repository->shouldReceive('save')->with($invoiceData)->once()->andReturn($expectedResult);

        $service = new InvoiceService($repository);

        // Act
        $result = $service->save($invoiceData);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that update passes data to the repository and returns the result
     */
    public function test_update_passes_data_to_repository(): void
    {
        // Arrange
        $invoiceId = 1;
        $invoiceData = ['amount' => 150.00];
        $expectedResult = ['id' => $invoiceId, 'amount' => 150.00];
        $repository = Mockery::mock(InvoiceRepository::class);
        $repository->shouldReceive('update')->with($invoiceData, $invoiceId)->once()->andReturn($expectedResult);

        $service = new InvoiceService($repository);

        // Act
        $result = $service->update($invoiceData, $invoiceId);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that update throws an exception when the repository throws an exception
     */
    public function test_update_throws_exception_when_repository_fails(): void
    {
        // Arrange
        $invoiceId = 1;
        $invoiceData = ['amount' => 150.00];
        $repository = Mockery::mock(InvoiceRepository::class);
        $repository->shouldReceive('update')->with($invoiceData, $invoiceId)->once()->andThrow(new Exception('Repository error'));

        $service = new InvoiceService($repository);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to update post data');

        // Act
        $service->update($invoiceData, $invoiceId);
    }

    /**
     * Test that deleteById passes ID to the repository and returns the result
     */
    public function test_delete_by_id_passes_id_to_repository(): void
    {
        // Arrange
        $invoiceId = 1;
        $expectedResult = true;
        $repository = Mockery::mock(InvoiceRepository::class);
        $repository->shouldReceive('delete')->with($invoiceId)->once()->andReturn($expectedResult);

        $service = new InvoiceService($repository);

        // Act
        $result = $service->deleteById($invoiceId);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that deleteById throws an exception when the repository throws an exception
     */
    public function test_delete_by_id_throws_exception_when_repository_fails(): void
    {
        // Arrange
        $invoiceId = 1;
        $repository = Mockery::mock(InvoiceRepository::class);
        $repository->shouldReceive('delete')->with($invoiceId)->once()->andThrow(new Exception('Repository error'));

        $service = new InvoiceService($repository);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to delete post data');

        // Act
        $service->deleteById($invoiceId);
    }
}
