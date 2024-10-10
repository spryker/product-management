<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Controller\EditController;
use Spryker\Zed\ProductManagement\ProductManagementConfig;

class VariantTable extends AbstractProductTable
{
    /**
     * @var string
     */
    public const TABLE_IDENTIFIER = 'product-variant-table';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT = 'id_product';

    /**
     * @var string
     */
    public const COL_SKU = 'sku';

    /**
     * @var string
     */
    public const COL_NAME = 'name';

    /**
     * @var string
     */
    public const COL_STATUS = 'status';

    /**
     * @var string
     */
    public const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_BUNDLE = 'idProductBundle';

    /**
     * @var string
     */
    public const COL_IS_BUNDLE = 'is_bundle';

    /**
     * @var string
     */
    public const COL_VALID_FROM = 'valid_from';

    /**
     * @var string
     */
    public const COL_VALID_TO = 'valid_to';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryQueryContainer;

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductVariantTableActionExpanderPluginInterface>
     */
    protected array $productVariantTableActionExpanderPlugins;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $type
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductVariantTableActionExpanderPluginInterface> $productVariantTableActionExpanderPlugins
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        $idProductAbstract,
        LocaleTransfer $localeTransfer,
        $type,
        array $productVariantTableActionExpanderPlugins
    ) {
        $this->productQueryQueryContainer = $productQueryContainer;
        $this->idProductAbstract = $idProductAbstract;
        $this->localeTransfer = $localeTransfer;
        $this->defaultUrl = sprintf(
            'variant-table?%s=%d&type=%s',
            EditController::PARAM_ID_PRODUCT_ABSTRACT,
            $idProductAbstract,
            $type,
        );
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);
        $this->type = $type;
        $this->productVariantTableActionExpanderPlugins = $productVariantTableActionExpanderPlugins;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_PRODUCT => 'Product ID',
            static::COL_SKU => 'Sku',
            static::COL_NAME => 'Name',
            static::COL_STATUS => 'Status',
            static::COL_IS_BUNDLE => 'Is bundle',
            static::COL_ACTIONS => 'Actions',
            static::COL_VALID_FROM => 'Valid From (Time in UTC)',
            static::COL_VALID_TO => 'Valid To (Time in UTC)',
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            static::COL_STATUS,
        ]);

        $config->setSearchable([
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductValidityTableMap::COL_VALID_FROM,
            SpyProductValidityTableMap::COL_VALID_TO,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT,
            static::COL_SKU,
            static::COL_NAME,
            static::COL_VALID_FROM,
            static::COL_VALID_TO,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this
            ->productQueryQueryContainer
            ->queryProduct()
            ->innerJoinSpyProductAbstract()
            ->leftJoinSpyProductValidity()
            ->filterByFkProductAbstract($this->idProductAbstract)
            ->useSpyProductLocalizedAttributesQuery()
                ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, static::COL_ID_PRODUCT_ABSTRACT)
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_FROM, static::COL_VALID_FROM)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_TO, static::COL_VALID_TO);

        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productEntity) {
            $productAbstractCollection[] = $this->generateItem($productEntity);
        }

        return $productAbstractCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array
     */
    protected function generateItem(SpyProduct $productEntity)
    {
        return [
            static::COL_ID_PRODUCT => $this->formatInt($productEntity->getIdProduct()),
            static::COL_SKU => $productEntity->getSku(),
            static::COL_NAME => $productEntity->getVirtualColumn(static::COL_NAME),
            static::COL_STATUS => $this->getStatusLabel($productEntity->getIsActive()),
            static::COL_IS_BUNDLE => $this->getIsBundleProduct($productEntity),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($productEntity)),
            static::COL_VALID_FROM => $productEntity->getVirtualColumn(static::COL_VALID_FROM),
            static::COL_VALID_TO => $productEntity->getVirtualColumn(static::COL_VALID_TO),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return string
     */
    protected function getIsBundleProduct(SpyProduct $productEntity)
    {
        if (
            $productEntity->getSpyProductBundlesRelatedByFkProduct()->count() > 0 ||
            $this->type == ProductManagementConfig::PRODUCT_TYPE_BUNDLE
        ) {
            return 'Yes';
        }

        return 'No';
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array
     */
    protected function createActionColumn(SpyProduct $productEntity)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            sprintf(
                '/product-management/view/variant?%s=%d&%s=%d&type=%s',
                EditController::PARAM_ID_PRODUCT,
                $productEntity->getIdProduct(),
                EditController::PARAM_ID_PRODUCT_ABSTRACT,
                $productEntity->getFkProductAbstract(),
                $this->type,
            ),
            'View',
        );

        $urls[] = $this->generateEditButton(
            sprintf(
                '/product-management/edit/variant?%s=%d&%s=%d&type=%s',
                EditController::PARAM_ID_PRODUCT,
                $productEntity->getIdProduct(),
                EditController::PARAM_ID_PRODUCT_ABSTRACT,
                $productEntity->getFkProductAbstract(),
                $this->type,
            ),
            'Edit',
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-attribute-gui/view/product', [
                EditController::PARAM_ID_PRODUCT => $productEntity->getIdProduct(),
            ]),
            'Manage Attributes',
        );

        return $this->expandActionUrls($urls, $productEntity->toArray());
    }

    /**
     * @param array<string> $urls
     * @param array<mixed> $productData
     *
     * @return array<string>
     */
    protected function expandActionUrls(array $urls, array $productData): array
    {
        $buttonCollectionTransfer = $this->executeProductVariantTableActionExpanderPlugins(
            new ButtonCollectionTransfer(),
            $productData,
        );

        foreach ($buttonCollectionTransfer->getButtons() as $button) {
            $urls[] = $this->generateButton(
                $button->getUrl(),
                $button->getTitle(),
                $button->getDefaultOptions(),
                $button->getCustomOptions(),
            );
        }

        return $urls;
    }

    /**
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     * @param array<mixed> $productData
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    protected function executeProductVariantTableActionExpanderPlugins(
        ButtonCollectionTransfer $buttonCollectionTransfer,
        array $productData
    ): ButtonCollectionTransfer {
        foreach ($this->productVariantTableActionExpanderPlugins as $productVariantTableActionExpanderPlugin) {
            $buttonCollectionTransfer = $productVariantTableActionExpanderPlugin->execute(
                $productData,
                $buttonCollectionTransfer,
            );
        }

        return $buttonCollectionTransfer;
    }
}
