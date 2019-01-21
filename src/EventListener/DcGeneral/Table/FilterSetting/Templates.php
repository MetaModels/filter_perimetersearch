<?php

/**
 * This file is part of MetaModels/filter_perimetersearch.
 *
 * (c) 2012-2019 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/filter_perimetersearch
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener\DcGeneral\Table\FilterSetting;

use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use MetaModels\BackendIntegration\TemplateList;
use MetaModels\Filter\Setting\IFilterSettingFactory;

/**
 * This fetches the template list.
 */
class Templates extends Base
{
    /**
     * The template list to use.
     *
     * @var TemplateList
     */
    private $templateList;

    /**
     * Templates constructor.
     *
     * @param TemplateList          $templateList  The template list to use.
     * @param IFilterSettingFactory $filterFactory The filter factory to use.
     */
    public function __construct($templateList, IFilterSettingFactory $filterFactory)
    {
        $this->templateList = $templateList;
        parent::__construct($filterFactory);
    }

    /**
     * Provide options for default selection.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public function getOptions(GetPropertyOptionsEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_filtersetting')
            || ($event->getPropertyName() !== 'range_template')
        ) {
            return;
        }

        $event->setOptions($this->templateList->getTemplatesForBase('mm_filteritem_'));
    }
}
