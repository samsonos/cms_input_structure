<?php
/**
 * Created by Maxim Omelchenko <omelchenko@samsonos.com>
 * on 31.03.2015 at 19:19
 */

namespace samsoncms\input\structure;

use samson\activerecord\materialfield;

/**
 * SamsonCMS select input module
 * @author Vitaly Iegorov <egorov@samsonos.com>
 * @author Ruslan Molodyko <molodyko@samsonos.com>
 */
class Application extends \samsoncms\input\Application
{
    /** @var int Field type number */
    public static $type = 12;

    /** @var string SamsonCMS field class */
    protected $fieldClass = '\samsoncms\input\structure\Select';

    /**
     * Add value with id of structure to material field
     * @param null $materialId
     * @param null $fieldId
     * @param null $structureId
     * @return array
     */
    public function __async_add($materialId = null, $fieldId = null, $structureId = null)
    {
        // If passed structure don't exists then create it
        if (dbQuery('materialfield')
                ->cond('MaterialID', $materialId)
                ->cond('FieldID', $fieldId)
                ->cond('Value', $structureId)
                ->cond('Active', 1)
                ->count() == 0
        ) {

            $value = (int)$structureId;

            // If structure is valid
            if (!empty($value)) {

                $mf = new materialfield(false);
                $mf->Active = 1;
                $mf->Value = $structureId;
                $mf->MaterialID = $materialId;
                $mf->FieldID = $fieldId;
                $mf->save();

                return array('status' => 1);
            }
        };

        return array('status' => 0);
    }

    /**
     * Remove value with id of structure from material field
     * @param null $materialId
     * @param null $fieldId
     * @param null $structureId
     * @return array
     */
    public function __async_remove($materialId = null, $fieldId = null, $structureId = null)
    {
        // If passed structure don't exists then create it
        $mf = null;
        if (dbQuery('materialfield')
                ->cond('MaterialID', $materialId)
                ->cond('FieldID', $fieldId)
                ->cond('Value', $structureId)
                ->cond('Active', 1)
                ->first($mf) > 0
        ) {

            $mf->delete();
        };

        return array('status' => 1);
    }

    public function build($string = '', $groupSeparator = ',', $viewSeparator = ':')
    {
        $this->field->build($string, $groupSeparator, $viewSeparator);

        return $this;
    }
}
