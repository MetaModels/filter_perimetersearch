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
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/filter_perimetersearch/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\FilterPerimetersearchBundle\EventListener\DcGeneral\Table\FilterSetting;

use MenAtWork\MultiColumnWizardBundle\Event\GetOptionsEvent;
use MetaModels\Filter\Setting\IFilterSettingFactory;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * This class provides the attribute options and encodes and decodes the attribute id.
 */
class LookupServiceListener extends Base
{
    /**
     * The translator.
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * The constructor.
     *
     * @param IFilterSettingFactory $filterFactory The filter setting factory.
     * @param TranslatorInterface   $translator    The translator.
     */
    public function __construct(IFilterSettingFactory $filterFactory, TranslatorInterface $translator)
    {
        parent::__construct($filterFactory);

        $this->translator = $translator;
    }

    /**
     * Provide options for default selection.
     *
     * @param GetOptionsEvent $event The event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getOptions(GetOptionsEvent $event)
    {
        // Check the context.
        $allowedProperties = array('lookupservice', 'second_attr_id', 'single_attr_id');
        if (!$this->isAllowedProperty($event, 'tl_metamodel_filtersetting', $allowedProperties)
            || 'lookupservice' !== $event->getSubPropertyName()
        ) {
            return;
        }

        $resolveClass = (array) $GLOBALS['METAMODELS']['filters']['perimetersearch']['resolve_class'];

        $domain  = 'tl_metamodel_filtersetting';
        $options = [];
        foreach (\array_keys($resolveClass) as $name) {
            $options[$name] = $this->translator->trans($domain . '.perimetersearch.' . $name, [], 'contao_' . $domain);
        }

        $event->setOptions($options);
    }
}
