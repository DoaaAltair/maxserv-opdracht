<?php

declare(strict_types=1);

namespace MaxServ\App\Controller;

use MaxServ\Core\Render\TemplateRenderer;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class IndexController
{
    public function __construct(
        private TemplateRenderer $templateRenderer,
        private \MaxServ\App\Repository\ProductRepository $productRepository,
        private \MaxServ\App\Service\ProductPriceCalculator $priceCalculator
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function index(): void
    {
        $category = $_GET['category'] ?? null;
        $brand = $_GET['brand'] ?? null;
        $sort = $_GET['sort'] ?? 'title';
        $direction = $_GET['direction'] ?? 'asc';

        $products = $this->productRepository->findByFilters(
            $category,
            $brand,
            $sort,
            $direction
        );

        $categories = $this->productRepository->findCategories();

        $brands = $this->productRepository->findBrands($category);

        foreach ($products as &$product) {
            $product['discount_price'] = $this->priceCalculator->calculateDiscountPrice(
                (float) $product['price'],
                (float) $product['discount_percentage']
            );
        }
        unset($product);

        echo $this->templateRenderer->render('index.html.twig', [
            'products' => $products,
            'category' => $category,
            'brand' => $brand,
            'categories' => $categories,
            'brands' => $brands,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }
}
