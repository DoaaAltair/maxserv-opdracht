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
        // Your logic here
        $products = $this->productRepository->findAll();

        foreach ($products as &$product) {
            $product['discount_price'] = $this->priceCalculator->calculateDiscountPrice(
                (float) $product['price'],
                (float) $product['discount_percentage']
            );
        }

        unset($product);

        echo $this->templateRenderer->render('index.html.twig', [
            'products' => $products,
        ]);
    }
}
