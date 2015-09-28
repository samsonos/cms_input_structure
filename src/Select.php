<?php
namespace samsoncms\input\structure;

use samsoncms\input\Field;

/**
 * Select SamsonCMS input field
 * @author Vitaly Iegorov <egorov@samsonos.com>
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class Select extends Field
{
    /** Special CSS classname for nested field objects to bind JS and CSS */
    protected $cssClass = '__select';

    /** @var array Select options */
    protected $navs;

    /** @var string Select options HTML */
    protected $material;

    /** {@inheritdoc} */
    public function viewField($renderer)
    {
        // Iterate all loaded CMSNavs
        $parentSelect = '';

        $this->navs = dbQuery('structure')->exec();

        $this->material = dbQuery('materialfield')
            ->cond('MaterialFieldID', $this->dbObject->id)
            ->first();

        // Iterate all structures of this material
        foreach ($this->navs as $db_structure) {
            $selected = '';
            $sm = dbQuery('materialfield')
                ->cond('FieldID', $this->material->FieldID)
                ->cond('Value', $db_structure->StructureID)
                ->cond('MaterialID', $this->material->MaterialID);


            // If material is related to current CMSNav
            if ($sm->count() > 0) {
                $selected = 'selected';
            }

            // Generate CMSNav option
            $parentSelect .= '<option ' . $selected . ' value="' .
                $db_structure->id . '">' . $db_structure->Name . '</option>';
        }

        $nameSelectStructureField = t('Теги структуры', true);

        // Render tab content
        return $renderer
            ->view($this->fieldView)
            ->nameSelectStructureField($nameSelectStructureField)
            ->parentSelect($parentSelect)
            ->matId($this->material->MaterialID)
            ->fieldId($this->dbObject->FieldID)
            ->output();
    }
}
