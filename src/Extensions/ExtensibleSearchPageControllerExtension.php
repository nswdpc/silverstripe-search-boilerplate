<?php

namespace NSWDPC\Search;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormField;

/**
 * Update search form based on configuration
 * Extension applied to nglasl\extensible\ExtensibleSearchPageController
 * @author James
 */
class ExtensibleSearchPageControllerExtension extends Extension
{

    /**
     * Return the sort by fields configured as an array
     * The key is the field name, the value is a configured label, applied as the default in an i18n string value
     */
    public function getDisplayedSortFields() : array {
        $sortFields = [];
        $page = $this->owner->data();
        if($displayedSortByFields = $page->DisplayedSortFields) {
            $selectableSortFields = $page->getSelectableFields();
            $displayedFields = $displayedSortByFields->getValue();
            foreach($displayedFields as $fieldName) {
                $fieldLabel = isset($selectableSortFields[ $fieldName ]) ? FormField::name_to_label($selectableSortFields[ $fieldName ]) : '';
                if(!$fieldLabel) {
                    $fieldLabel = FormField::name_to_label($fieldName);
                }
                $sortFields[ $fieldName ] = _t(
                    'nswdpc_searchboilerplate.SORT_FIELD_' . strtoupper($fieldName),
                    $fieldLabel
                );
            }
        }
        return $sortFields;
    }

    /**
     * Apply configured fields for sorting to the form
     * @param Form $form
     */
    public function applySortByFields(Form $form) {
        $sortField = $form->Fields()->dataFieldByName('SortBy');
        if($sortField) {
            $fields = $this->getDisplayedSortFields();
            if(count($fields) == 0) {
                // remove fields as there is no displayed sort
                $form->Fields()->removeByName(['SortBy','SortDirection']);
            } else {
                $sortField->setSource($fields);
            }
        }
    }

    /**
     * Update the form
     * @param Form $form
     */
    public function updateExtensibleSearchForm(Form $form)
    {
        $this->applySortByFields($form);
    }

    /**
     * Update the search form
     * @param Form|null $form
     */
    public function updateExtensibleSearchSearchForm(?Form $form) {
        if($form) {
            $this->applySortByFields($form);
        }
    }
}
