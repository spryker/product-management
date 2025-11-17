<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class ProductReadinessController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @var string
     */
    public const PARAM_ID_PRODUCT = 'id-product';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT,
        ));

        $productAbstractTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            $this->addErrorMessage('The product [%s] you are trying to view, does not exist.', [
                '%s' => $idProductAbstract,
            ]);

            return $this->redirectResponse('/product-management');
        }

        $concreteProductCollection = $this->getFactory()
            ->getProductFacade()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);

        $abstractProductRedinesses = $this->getFactory()->createProductAbstractReadinessReader()->read(
            (new ProductAbstractReadinessRequestTransfer())
                ->setProductAbstract($productAbstractTransfer)
                ->setProductConcretes(new ArrayObject($concreteProductCollection)),
        );

        $concreteProductReadinesses = $this->getFactory()
            ->createProductConcreteReadinessReader()
            ->readBulk(new ArrayObject($concreteProductCollection));

        return $this->viewResponse([
            'productAbstract' => $productAbstractTransfer,
            'concreteProductCollection' => $concreteProductCollection,
            'abstractProductReadiness' => $abstractProductRedinesses,
            'concreteProductReadinesses' => $concreteProductReadinesses,
        ]);
    }

    /**
     * Displays readiness for an abstract and a single concrete product.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function variantAction(Request $request): array|RedirectResponse
    {
        $idProduct = $this->castId($request->get(static::PARAM_ID_PRODUCT));

        $productConcreteTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductConcreteById($idProduct);

        if (!$productConcreteTransfer) {
            $this->addErrorMessage('The product [%s] you are trying to view, does not exist.', [
                '%s' => $idProduct,
            ]);

            return $this->redirectResponse('/product-management');
        }

        $concreteProductReadinessTransfers =
            $this->getFactory()->createProductConcreteReadinessReader()->read(
                (new ProductConcreteReadinessRequestTransfer())
                    ->setProductConcrete($productConcreteTransfer),
            );

        return $this->viewResponse([
            'product' => $productConcreteTransfer,
            'concreteProductReadinesses' => $concreteProductReadinessTransfers,
        ]);
    }
}
