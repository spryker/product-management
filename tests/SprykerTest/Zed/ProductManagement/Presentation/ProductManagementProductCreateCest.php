<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Presentation;

use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementProductCreatePage;
use SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Presentation
 * @group ProductManagementProductCreateCest
 * Add your own group annotations below this line
 */
class ProductManagementProductCreateCest
{
    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function _before(ProductManagementPresentationTester $i): void
    {
        $i->amZed();
        $i->amLoggedInUser();
    }

    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductManagementPresentationTester $i): void
    {
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->registerMoneyCollectionFormTypePlugin();

        $i->amOnPage(ProductManagementProductCreatePage::URL);
        $i->seeBreadcrumbNavigation('Catalog / Products / Create a Product');
    }
}
