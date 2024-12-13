<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\DecimalObject\Decimal;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface;

class BundledProductTable extends AbstractTable
{
    /**
     * @var string
     */
    public const COL_SELECT = 'select';

    /**
     * @var string
     */
    public const COL_AVAILABILITY = 'availability';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_CONCRETE = 'id_product_concrete';

    /**
     * @var string
     */
    public const SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME = 'Name';

    /**
     * @var string
     */
    public const SPY_STOCK_PRODUCT_ALIAS_QUANTITY = 'stockQuantity';

    /**
     * @var string
     */
    public const IS_NEVER_OUT_OF_STOCK = 'isNeverOutOfStock';

    /**
     * @var string
     */
    protected const ALIAS_IN_THIS_BUNDLE = 'inThisBundle';

    /**
     * @var string
     */
    protected const ALIAS_HAS_ANOTHER_BUNDLE = 'hasAnotherBundle';

    /**
     * @var string
     */
    protected const COL_ID_PRODUCT_BUNDLE = 'id_product_bundle';

    /**
     * @var string
     */
    protected const VIRTUAL_COL_BUNDLE_ID = 'bundleId';

    /**
     * @var string
     */
    protected const COL_FK_PRODUCT = 'fk_product';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var int
     */
    protected $idProductConcrete;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface $availabilityFacade
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface $priceFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToStoreFacadeInterface $storeFacade
     * @param int|null $idProductConcrete
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementToUtilEncodingInterface $utilEncodingService,
        ProductManagementToPriceProductInterface $priceProductFacade,
        ProductManagementToMoneyInterface $moneyFacade,
        ProductManagementToAvailabilityInterface $availabilityFacade,
        LocaleTransfer $localeTransfer,
        ProductManagementToPriceInterface $priceFacade,
        ProductManagementToStoreFacadeInterface $storeFacade,
        $idProductConcrete = null
    ) {
        $this->setTableIdentifier('bundled-product-table');
        $this->productQueryContainer = $productQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->priceProductFacade = $priceProductFacade;
        $this->moneyFacade = $moneyFacade;
        $this->availabilityFacade = $availabilityFacade;
        $this->idProductConcrete = $idProductConcrete;
        $this->localeTransfer = $localeTransfer;
        $this->priceFacade = $priceFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl(
            sprintf(
                'bundled-product-table?id-product-concrete=%d',
                $this->idProductConcrete,
            ),
        );

        $defaultPriceMode = $this->priceFacade->getDefaultPriceMode();

        $header = [
            static::COL_SELECT => 'Select',
            static::COL_ID_PRODUCT_CONCRETE => 'id product',
            SpyProductLocalizedAttributesTableMap::COL_NAME => 'Product name',
            SpyProductTableMap::COL_SKU => 'SKU',
            static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY => 'Stock',
            static::COL_AVAILABILITY => 'Availability',
            SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK => 'Is never out of stock',

        ];

        $config->setHeader($header);

        $config->setRawColumns([
            static::COL_SELECT,
            static::COL_AVAILABILITY,
            SpyProductTableMap::COL_SKU,
        ]);

        $config->setSearchable([
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductTableMap::COL_SKU,
        ]);

        $config->setSortable([
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductTableMap::COL_SKU,
            static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY,
            SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this
            ->productQueryContainer
            ->queryProduct()
            ->leftJoinSpyProductBundleRelatedByFkProduct(static::ALIAS_HAS_ANOTHER_BUNDLE)
            ->joinSpyProductLocalizedAttributes()
            ->joinStockProduct()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME)
            ->withColumn(sprintf('SUM(%s)', SpyStockProductTableMap::COL_QUANTITY), static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY)
            ->withColumn(SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK, static::IS_NEVER_OUT_OF_STOCK)
            ->where(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE . ' = ?', $this->localeTransfer->getIdLocale())
            ->add(
                static::ALIAS_HAS_ANOTHER_BUNDLE . '.' . static::COL_ID_PRODUCT_BUNDLE,
                null,
                Criteria::ISNULL,
            )
            ->groupBy(SpyProductTableMap::COL_ID_PRODUCT);

        if ($this->idProductConcrete) {
            $query->leftJoinSpyProductBundleRelatedByFkBundledProduct(static::ALIAS_IN_THIS_BUNDLE)
                ->addJoinCondition(
                    static::ALIAS_IN_THIS_BUNDLE,
                    static::ALIAS_IN_THIS_BUNDLE . '.' . static::COL_FK_PRODUCT . ' = ?',
                    $this->idProductConcrete,
                )
                ->withColumn(static::ALIAS_IN_THIS_BUNDLE . '.' . static::COL_ID_PRODUCT_BUNDLE, static::VIRTUAL_COL_BUNDLE_ID);
        }

        /** @var array<\Orm\Zed\Product\Persistence\SpyProduct> $queryResults */
        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productEntity) {
            $productAbstractCollection[] = [
                static::COL_SELECT => $this->addCheckBox($productEntity),
                static::COL_ID_PRODUCT_CONCRETE => $this->formatInt($productEntity->getIdProduct()),
                static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY => (new Decimal(
                    $productEntity->getVirtualColumn(static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY) ?? 0,
                ))->trim(),
                SpyProductLocalizedAttributesTableMap::COL_NAME => $productEntity->getVirtualColumn(
                    static::SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME,
                ),
                SpyProductTableMap::COL_SKU => $this->getProductEditPageLink(
                    $productEntity->getSku(),
                    $productEntity->getFkProductAbstract(),
                    $productEntity->getIdProduct(),
                ),
                SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK => $productEntity->getIsNeverOutOfStock(),
                static::COL_AVAILABILITY => $this->formatFloat(
                    $this->getAvailability($productEntity)
                                                ->trim()
                                                ->toFloat(),
                ),
            ];
        }

        return $productAbstractCollection;
    }

    /**
     * @param string $sku
     * @param int $idProductAbstract
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function getProductEditPageLink($sku, $idProductAbstract, $idProductConcrete)
    {
        $pageEditUrl = Url::generate('/product-management/edit/variant', [
            'id-product-abstract' => $idProductAbstract,
            'id-product' => $idProductConcrete,
        ])->build();

        $pageEditLink = '<a target="_blank" href="' . $pageEditUrl . '">' . $sku . '</a>';

        return $pageEditLink;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     *
     * @return string
     */
    protected function addCheckBox(SpyProduct $productConcreteEntity)
    {
        $checked = '';

        if (
            $this->idProductConcrete
            && $productConcreteEntity->getVirtualColumn(static::VIRTUAL_COL_BUNDLE_ID) !== null
        ) {
                $checked = 'checked="checked"';
        }

        return sprintf(
            "<input id='product_assign_checkbox_%d' class='product_assign_checkbox' type='checkbox' data-info='%s' %s >",
            $productConcreteEntity->getIdProduct(),
            $this->utilEncodingService->encodeJson($productConcreteEntity->toArray()),
            $checked,
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getAvailability(SpyProduct $productConcreteEntity): Decimal
    {
        if (!$productConcreteEntity->getIsNeverOutOfStock()) {
            return $this->availabilityFacade
                ->calculateAvailabilityForProductWithStore(
                    $productConcreteEntity->getSku(),
                    $this->storeFacade->getCurrentStore(true),
                );
        }

        return new Decimal(0);
    }
}
