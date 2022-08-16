<?php

namespace NSWDPC\Search;

use SilverStripe\ORM\DataExtension;
use Symbiote\MultiValueField\Fields\MultiValueDropdownField;

/**
 * Update search form based on configuration
 * Extension applied to nglasl\extensible\ExtensibleSearchPageController
 * @author James
 */
class ExtensibleSearchPageExtension extends DataExtension
{

    /**
     * @var array
     */
    private static $db = [
        'DisplayedSortFields' => 'MultiValueField'
    ];

    /**
     * Apply selectable sort fields to the displayed sort fields
     */
    public function updateExtensibleSearchPageCMSFields(&$fields) {
        $sortByField = $fields->dataFieldByName('SortBy');
        if($sortByField) {
            $displayedSortByField = MultiValueDropdownField::create(
                'DisplayedSortFields',
                _t(
                    'nswdpc_searchboilerplate.DISPLAYED_SORT_FIELDS',
                    'Displayed sort fields'
                ),
                $sortByField->getSource()
            );
            $fields->insertAfter('SortBy', $displayedSortByField);
        } else {
            $fields->removeByName('DisplayedSortFields');
        }
    }

}
