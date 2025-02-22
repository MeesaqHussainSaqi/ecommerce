
<?php
namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Models\Product;

class ProductRepository extends BaseRepository implements ProductInterface
{
    protected function model()
    {
        return new Product();
    }

    public function findBySeller(int $sellerId)
    {
        return $this->model->where('seller_id', $sellerId)->get();
    }

    public function findByCategory(int $categoryId)
    {
        return $this->model->where('category_id', $categoryId)->get();
    }
}
