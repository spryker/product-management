<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\PluginExecutor;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class ProductAbstractFormTabDataProviderPluginExecutor
{
    /**
     * @var array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormTabContentProviderPluginInterface>
     */
    protected array $productAbstractFormTabContentProviderPlugins;

    /**
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormTabContentProviderPluginInterface> $productAbstractFormTabContentProviderPlugins
     */
    public function __construct(array $productAbstractFormTabContentProviderPlugins)
    {
        $this->productAbstractFormTabContentProviderPlugins = $productAbstractFormTabContentProviderPlugins;
    }

    /**
     * @return array<string, array<string>>
     */
    public function provideTabContents(?ProductAbstractTransfer $productAbstractTransfer = null): array
    {
        $tabContents = [];

        foreach ($this->productAbstractFormTabContentProviderPlugins as $plugin) {
            $tabName = $plugin->getTabName();
            $templates = $plugin->provideTabContent($productAbstractTransfer);
            $tabContents[$tabName] ??= [];

            $tabContents[$tabName] = array_merge($tabContents[$tabName], $templates);
        }

        return $tabContents;
    }
}
