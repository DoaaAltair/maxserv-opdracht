<?php

declare(strict_types=1);

namespace MaxServ\App\Controller;

use MaxServ\App\Repository\ProductRepository;

final readonly class ProductController
{
    public function __construct(
        private ProductRepository $productRepository,
        private \MaxServ\Core\Render\TemplateRenderer $templateRenderer,
        private \MaxServ\App\Service\ProductPriceCalculator $priceCalculator
    ) {
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $product = $this->productRepository->findById($id);

        if ($product === null) {
            http_response_code(404);
            echo 'Product niet gevonden.';
            return;
        }

        $product['discount_price'] = $this->priceCalculator->calculateDiscountPrice(
            (float) $product['price'],
            (float) $product['discount_percentage']
        );

        echo $this->templateRenderer->render('product.html.twig', [
            'product' => $product,
        ]);
    }
}