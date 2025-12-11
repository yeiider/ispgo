<?php

namespace App\GraphQL\Queries;

use App\Models\Inventory\Product;
use App\Models\Inventory\ProductStock;
use App\Models\Inventory\Warehouse;

class InventoryQuery
{
    /**
     * Obtiene todos los productos con paginación.
     */
    public function products($root, array $args)
    {
        $query = Product::query()->with(['category', 'warehouse', 'stocks.warehouse']);

        if (isset($args['name'])) {
            $query->where('name', 'like', '%' . $args['name'] . '%');
        }

        if (isset($args['sku'])) {
            $query->where('sku', 'like', '%' . $args['sku'] . '%');
        }

        if (isset($args['category_id'])) {
            $query->where('category_id', $args['category_id']);
        }

        if (isset($args['warehouse_id'])) {
            $query->where('warehouse_id', $args['warehouse_id']);
        }

        if (isset($args['status'])) {
            $query->where('status', $args['status']);
        }

        $first = $args['first'] ?? 15;
        $page = $args['page'] ?? 1;
        $paginator = $query->paginate($first, ['*'], 'page', $page);

        return [
            'data' => $paginator->items(),
            'paginatorInfo' => [
                'count' => $paginator->count(),
                'currentPage' => $paginator->currentPage(),
                'firstItem' => $paginator->firstItem(),
                'hasMorePages' => $paginator->hasMorePages(),
                'lastItem' => $paginator->lastItem(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ];
    }

    /**
     * Obtiene un producto por ID.
     */
    public function product($root, array $args)
    {
        return Product::with(['category', 'warehouse', 'stocks.warehouse'])->find($args['id']);
    }

    /**
     * Obtiene todas las bodegas con paginación.
     */
    public function warehouses($root, array $args)
    {
        $query = Warehouse::query()->with(['stocks.product']);

        if (isset($args['name'])) {
            $query->where('name', 'like', '%' . $args['name'] . '%');
        }

        if (isset($args['code'])) {
            $query->where('code', 'like', '%' . $args['code'] . '%');
        }

        $first = $args['first'] ?? 15;
        $page = $args['page'] ?? 1;
        $paginator = $query->paginate($first, ['*'], 'page', $page);

        return [
            'data' => $paginator->items(),
            'paginatorInfo' => [
                'count' => $paginator->count(),
                'currentPage' => $paginator->currentPage(),
                'firstItem' => $paginator->firstItem(),
                'hasMorePages' => $paginator->hasMorePages(),
                'lastItem' => $paginator->lastItem(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ];
    }

    /**
     * Obtiene una bodega por ID.
     */
    public function warehouse($root, array $args)
    {
        return Warehouse::with(['stocks.product'])->find($args['id']);
    }

    /**
     * Obtiene todos los registros de stock con paginación.
     */
    public function productStocks($root, array $args)
    {
        $query = ProductStock::query()->with(['product', 'warehouse']);

        if (isset($args['product_id'])) {
            $query->where('product_id', $args['product_id']);
        }

        if (isset($args['warehouse_id'])) {
            $query->where('warehouse_id', $args['warehouse_id']);
        }

        if (isset($args['low_stock']) && $args['low_stock']) {
            $query->whereNotNull('min_stock')
                ->whereRaw('quantity < min_stock');
        }

        $first = $args['first'] ?? 15;
        $page = $args['page'] ?? 1;
        $paginator = $query->paginate($first, ['*'], 'page', $page);

        return [
            'data' => $paginator->items(),
            'paginatorInfo' => [
                'count' => $paginator->count(),
                'currentPage' => $paginator->currentPage(),
                'firstItem' => $paginator->firstItem(),
                'hasMorePages' => $paginator->hasMorePages(),
                'lastItem' => $paginator->lastItem(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        ];
    }

    /**
     * Obtiene un registro de stock por ID.
     */
    public function productStock($root, array $args)
    {
        return ProductStock::with(['product', 'warehouse'])->find($args['id']);
    }

    /**
     * Obtiene el stock de un producto en todas las bodegas.
     */
    public function productStockByProduct($root, array $args)
    {
        return ProductStock::with(['warehouse'])
            ->where('product_id', $args['product_id'])
            ->get();
    }

    /**
     * Obtiene el stock de todos los productos en una bodega.
     */
    public function productStockByWarehouse($root, array $args)
    {
        return ProductStock::with(['product'])
            ->where('warehouse_id', $args['warehouse_id'])
            ->get();
    }

    /**
     * Obtiene el total de stock de un producto en todas las bodegas.
     */
    public function productTotalStock($root, array $args)
    {
        $product = Product::find($args['product_id']);
        
        if (!$product) {
            return null;
        }

        return [
            'product' => $product,
            'total_quantity' => $product->total_stock,
            'warehouses_count' => $product->stocks()->count(),
        ];
    }

    /**
     * Field resolver para el campo total_stock en InventoryProduct.
     */
    public function productTotalStockField($root, array $args)
    {
        if ($root instanceof Product) {
            return $root->total_stock;
        }
        return 0;
    }
}
