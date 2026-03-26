<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\PluginExecutor;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class ProductAbstractFormTabDataProviderPluginExecutor
{
    // Deprecated plugins have no declared priority; render them last.
    protected const int DEPRECATED_PLUGIN_DEFAULT_PRIORITY = 100;

    protected const string KEY_PRIORITY = 'priority';

    protected const string KEY_TEMPLATES = 'templates';

    /**
     * @param array<\Spryker\Zed\ProductManagement\Communication\TabContentProvider\ProductAbstractFormTabContentProviderInterface> $productAbstractFormTabContentProviders
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormTabContentProviderWithPriorityPluginInterface> $productAbstractFormTabContentProviderWithPriorityPlugins
     * @param array<\Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormTabContentProviderPluginInterface> $productAbstractFormTabContentProviderPlugins
     */
    public function __construct(
        protected array $productAbstractFormTabContentProviders,
        protected array $productAbstractFormTabContentProviderWithPriorityPlugins,
        protected array $productAbstractFormTabContentProviderPlugins,
        protected bool $isTabContentProviderEnabled,
    ) {
    }

    /**
     * @return array<string, array<string>>
     */
    public function provideTabContents(?ProductAbstractTransfer $productAbstractTransfer = null): array
    {
        $entriesByTab = [];

        if ($this->isTabContentProviderEnabled) {
            foreach ($this->productAbstractFormTabContentProviders as $provider) {
                $entriesByTab[$provider->getTabName()][] = [
                    static::KEY_PRIORITY => $provider->getPriority(),
                    static::KEY_TEMPLATES => $provider->provideTabContent($productAbstractTransfer),
                ];
            }

            foreach ($this->productAbstractFormTabContentProviderWithPriorityPlugins as $plugin) {
                $entriesByTab[$plugin->getTabName()][] = [
                    static::KEY_PRIORITY => $plugin->getPriority(),
                    static::KEY_TEMPLATES => $plugin->provideTabContent($productAbstractTransfer),
                ];
            }

            return $this->sortAndFlattenByPriority($entriesByTab);
        }

        $tabContents = [];
        foreach ($this->productAbstractFormTabContentProviderPlugins as $plugin) {
            $tabName = $plugin->getTabName();
            $templates = $plugin->provideTabContent($productAbstractTransfer);
            $tabContents[$tabName] ??= [];

            $tabContents[$tabName] = array_merge($tabContents[$tabName], $templates);
        }

        return $tabContents;
    }

    /**
     * @param array<string, array<array<string, array<string>|int>>> $entriesByTab
     *
     * @return array<string, array<string>>
     */
    protected function sortAndFlattenByPriority(array $entriesByTab): array
    {
        $tabContents = [];

        foreach ($entriesByTab as $tabName => $entries) {
            usort($entries, fn ($a, $b) => $a[static::KEY_PRIORITY] <=> $b[static::KEY_PRIORITY]);

            /** @var array<array<string>> $templateGroups */
            $templateGroups = array_column($entries, static::KEY_TEMPLATES);
            $tabContents[$tabName] = array_merge(...$templateGroups);
        }

        return $tabContents;
    }
}
