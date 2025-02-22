
<?php
namespace App\Repositories\Product;
use App\Repositories\BaseInterface;

interface ProductInterface extends BaseInterface
{
    public function findBySeller(int $sellerId);
    public function findByCategory(int $categoryId);
}
