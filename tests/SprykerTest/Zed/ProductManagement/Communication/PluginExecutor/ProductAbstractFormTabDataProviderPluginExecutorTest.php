<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication\PluginExecutor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductManagement\Communication\PluginExecutor\ProductAbstractFormTabDataProviderPluginExecutor;
use Spryker\Zed\ProductManagement\Communication\TabContentProvider\ProductAbstractFormTabContentProviderInterface;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormTabContentProviderPluginInterface;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractFormTabContentProviderWithPriorityPluginInterface;
use SprykerTest\Zed\ProductManagement\ProductManagementCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Communication
 * @group PluginExecutor
 * @group ProductAbstractFormTabDataProviderPluginExecutorTest
 * Add your own group annotations below this line
 */
class ProductAbstractFormTabDataProviderPluginExecutorTest extends Unit
{
    protected ProductManagementCommunicationTester $tester;

    public function testProvideTabContentsOrdersContentProvidersByPriority(): void
    {
        // Arrange
        $executor = new ProductAbstractFormTabDataProviderPluginExecutor(
            [
                $this->createContentProvider('general', 20, ['template-b.twig']),
                $this->createContentProvider('general', 10, ['template-a.twig']),
            ],
            [],
            [],
            true,
        );

        // Act
        $tabContents = $executor->provideTabContents();

        // Assert
        $this->assertSame(['template-a.twig', 'template-b.twig'], $tabContents['general']);
    }

    public function testProvideTabContentsOrdersPriorityPluginsAfterContentProviders(): void
    {
        // Arrange
        $executor = new ProductAbstractFormTabDataProviderPluginExecutor(
            [$this->createContentProvider('general', 10, ['provider.twig'])],
            [$this->createPriorityPlugin('general', 5, ['plugin-high.twig'])],
            [],
            true,
        );

        // Act
        $tabContents = $executor->provideTabContents();

        // Assert
        $this->assertSame(['plugin-high.twig', 'provider.twig'], $tabContents['general']);
    }

    public function testProvideTabContentsWhenDisabledSkipsContentProvidersAndPriorityPlugins(): void
    {
        // Arrange
        $executor = new ProductAbstractFormTabDataProviderPluginExecutor(
            [$this->createContentProvider('general', 10, ['provider.twig'])],
            [$this->createPriorityPlugin('general', 5, ['plugin.twig'])],
            [],
            false,
        );

        // Act
        $tabContents = $executor->provideTabContents();

        // Assert
        $this->assertEmpty($tabContents);
    }

    public function testProvideTabContentsDeprecatedPluginsAreAlwaysRenderedEvenWhenDisabled(): void
    {
        // Arrange
        $executor = new ProductAbstractFormTabDataProviderPluginExecutor(
            [],
            [],
            [$this->createDeprecatedPlugin('general', ['deprecated.twig'])],
            false,
        );

        // Act
        $tabContents = $executor->provideTabContents();

        // Assert
        $this->assertSame(['deprecated.twig'], $tabContents['general']);
    }

    public function testProvideTabContentsDeprecatedPluginsAreIgnoredWhenEnabled(): void
    {
        // Arrange
        $executor = new ProductAbstractFormTabDataProviderPluginExecutor(
            [],
            [$this->createPriorityPlugin('general', 1, ['priority.twig'])],
            [$this->createDeprecatedPlugin('general', ['deprecated.twig'])],
            true,
        );

        // Act
        $tabContents = $executor->provideTabContents();

        // Assert
        $this->assertSame(['priority.twig'], $tabContents['general']);
    }

    public function testProvideTabContentsPassesProductAbstractTransferToProviders(): void
    {
        // Arrange
        $productAbstractTransfer = (new ProductAbstractTransfer())->setIdProductAbstract(42);
        $receivedTransfer = null;

        $provider = $this->createMock(ProductAbstractFormTabContentProviderInterface::class);
        $provider->method('getTabName')->willReturn('general');
        $provider->method('getPriority')->willReturn(10);
        $provider->method('provideTabContent')->willReturnCallback(
            static function (?ProductAbstractTransfer $transfer) use (&$receivedTransfer): array {
                $receivedTransfer = $transfer;

                return ['template.twig'];
            },
        );

        $executor = new ProductAbstractFormTabDataProviderPluginExecutor([$provider], [], [], true);

        // Act
        $executor->provideTabContents($productAbstractTransfer);

        // Assert
        $this->assertSame(42, $receivedTransfer->getIdProductAbstract());
    }

    protected function createContentProvider(string $tabName, int $priority, array $templates): ProductAbstractFormTabContentProviderInterface
    {
        $provider = $this->createMock(ProductAbstractFormTabContentProviderInterface::class);
        $provider->method('getTabName')->willReturn($tabName);
        $provider->method('getPriority')->willReturn($priority);
        $provider->method('provideTabContent')->willReturn($templates);

        return $provider;
    }

    protected function createPriorityPlugin(string $tabName, int $priority, array $templates): ProductAbstractFormTabContentProviderWithPriorityPluginInterface
    {
        $plugin = $this->createMock(ProductAbstractFormTabContentProviderWithPriorityPluginInterface::class);
        $plugin->method('getTabName')->willReturn($tabName);
        $plugin->method('getPriority')->willReturn($priority);
        $plugin->method('provideTabContent')->willReturn($templates);

        return $plugin;
    }

    protected function createDeprecatedPlugin(string $tabName, array $templates): ProductAbstractFormTabContentProviderPluginInterface
    {
        $plugin = $this->createMock(ProductAbstractFormTabContentProviderPluginInterface::class);
        $plugin->method('getTabName')->willReturn($tabName);
        $plugin->method('provideTabContent')->willReturn($templates);

        return $plugin;
    }
}
