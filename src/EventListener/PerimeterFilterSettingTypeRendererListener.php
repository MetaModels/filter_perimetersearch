<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2022 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/filter_perimetersearch
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2022 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener;

use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface;
use ContaoCommunityAlliance\DcGeneral\EnvironmentInterface;
use MetaModels\CoreBundle\EventListener\DcGeneral\Table\FilterSetting\AbstractFilterSettingTypeRenderer;

/**
 * Handles rendering of model from tl_metamodel_filtersetting.
 *
 * @SuppressWarnings(PHPMD.LongClassName)
 */
class PerimeterFilterSettingTypeRendererListener extends AbstractFilterSettingTypeRenderer
{
    /**
     * {@inheritdoc}
     */
    protected function getTypes()
    {
        return ['perimetersearch'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getLabelParameters(EnvironmentInterface $environment, ModelInterface $model)
    {
        return $this->getLabelParametersWithAttributeAndUrlParam($environment, $model);
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function getLabelParametersWithAttributeAndUrlParam(
        EnvironmentInterface $environment,
        ModelInterface $model
    ) {
        $translator = $environment->getTranslator();
        $metamodel  = $this->getMetaModel($model);

        if ('single' === $model->getProperty('datamode')) {
            $attribute = $metamodel->getAttribute($model->getProperty('single_attr_id'));

            if ($attribute) {
                $attributeColumnName = $attribute->getColName();
                $attributeName       = $attribute->getName();
                $urlParam            = $attribute->getColName();
            } else {
                $attributeColumnName = '-';
                $attributeName       = '-';
                $urlParam            = 'filter_attr_' . $model->getProperty('id');
            }
        } elseif ('multi' === $model->getProperty('datamode')) {
            $attribute1 = $metamodel->getAttribute($model->getProperty('first_attr_id'));
            $attribute2 = $metamodel->getAttribute($model->getProperty('second_attr_id'));

            $attributeColumnName = \sprintf(
                $translator->translate('typedesc._multicolumn_', 'tl_metamodel_filtersetting'),
                ($attribute1 ? $attribute1->getColName() : '-'),
                ($attribute2 ? $attribute2->getColName() : '-')
            );

            $attributeName = \sprintf(
                $translator->translate('typedesc._multiname_', 'tl_metamodel_filtersetting'),
                ($attribute1 ? $attribute1->getName() : '-'),
                ($attribute2 ? $attribute2->getName() : '-')
            );

            $urlParam = $attribute1 ? $attribute1->getColName() : 'filter_attr_' . $model->getProperty('id');
        } else {
            $attribute = $metamodel->getAttributeById((int) $model->getProperty('attr_id'));

            if ($attribute) {
                $attributeColumnName = $attribute->getColName();
                $attributeName       = $attribute->getName();
                $urlParam            = $attribute->getColName();
            } else {
                $attributeColumnName = '-';
                $attributeName       = '-';
                $urlParam            = 'filter_attr_' . $model->getProperty('id');
            }
        }

        return [
            $this->getLabelImage($model),
            $this->getLabelText($translator, $model),
            \sprintf(
                $translator->translate('typedesc._attribute_', 'tl_metamodel_filtersetting'),
                $attributeColumnName,
                $attributeName
            ),
            $this->getLabelComment($model, $translator),
            \sprintf(
                $translator->translate('typedesc._url_', 'tl_metamodel_filtersetting'),
                ($model->getProperty('urlparam') ? $model->getProperty('urlparam') : $urlParam)
            )
        ];
    }
}
