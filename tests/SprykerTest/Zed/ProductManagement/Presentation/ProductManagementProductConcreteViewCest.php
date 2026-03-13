<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Presentation;

use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementProductListPage;
use SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Presentation
 * @group ProductManagementProductConcreteViewCest
 * Add your own group annotations below this line
 */
class ProductManagementProductConcreteViewCest
{
    public function _before(ProductManagementPresentationTester $i): void
    {
        $i->amZed();
        $i->amLoggedInUser();
    }

    public function breadcrumbIsVisible(ProductManagementPresentationTester $i): void
    {
        $i->amOnPage(ProductManagementProductListPage::URL);
        $i->clickDataTableViewButton();
        $i->waitForElement('#product-variant-table_wrapper .column-actions', 30);
        $i->clickDataTableViewButton(1, 'product-variant-table_wrapper');

        $i->seeBreadcrumbNavigation('Catalog / Products / View Concrete Product');
    }
}
