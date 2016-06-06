<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementCategory\Communication\Form\DataProvider;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\ProductManagementCategory\Communication\Form\CategoryFormAdd;

class CategoryFormAddDataProvider extends AbstractCategoryFormDataProvider
{

    /**
     * @param int $idParentNode
     * @param int|null $idCategory
     *
     * @return array
     */
    public function getData($idParentNode, $idCategory = null)
    {
        $formData = $this->getDefaultFormFields($idParentNode);

        if ($idCategory !== null) {
            /** @var \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity */
            $categoryEntity = $this->categoryQueryContainer
                ->queryCategoryById($idCategory)
                ->innerJoinAttribute()
                ->addAnd(SpyCategoryAttributeTableMap::COL_FK_LOCALE, $this->locale->getIdLocale())
                ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, CategoryFormAdd::FIELD_NAME)
                ->innerJoinNode()
                ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE)
                ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, CategoryFormAdd::FIELD_PK_CATEGORY_NODE)
                ->findOne();

            if ($categoryEntity) {
                $categoryEntity = $categoryEntity->toArray();

                $formData = [
                    self::PK_CATEGORY => $categoryEntity[self::PK_CATEGORY],
                    CategoryFormAdd::FIELD_PK_CATEGORY_NODE => $categoryEntity[CategoryFormAdd::FIELD_PK_CATEGORY_NODE],
                    CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE => $categoryEntity[CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE],
                    CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE => $categoryEntity[CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE],
                    CategoryFormAdd::FIELD_NAME => $categoryEntity[CategoryFormAdd::FIELD_NAME],
                ];
            }
        }

        return $formData;
    }

    /**
     * @param int|null $idParentNode
     *
     * @return array
     */
    protected function getDefaultFormFields($idParentNode = null)
    {
        return [
            self::PK_CATEGORY => null,
            CategoryFormAdd::FIELD_PK_CATEGORY_NODE => null,
            CategoryFormAdd::FIELD_FK_PARENT_CATEGORY_NODE => $idParentNode,
            CategoryFormAdd::FIELD_NAME => '',
        ];
    }

}
