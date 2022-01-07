<?php

namespace XD\DropzoneField\Forms;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\SS_List;

/**
 * Class DropzoneField
 *
 * @author Bram de Leeuw
 */
class DropzoneField extends UploadField
{
    private static $include_scripts_in_template = true;

    public $addRemoveLinks = false;

    protected $dropzoneConfig = [
        'paramName' => 'Upload'
    ];

    public function __construct($name, $title = null, $items = null)
    {
        if ($items instanceof SS_List) {
            // SS_List input
        } elseif (is_array($items)) {
            // array input
            $items = ArrayList::create($items);
        } elseif( !empty($items) ) {
            // dataobject
            $items = ArrayList::create([$items]);
        }
        parent::__construct($name, $title, $items);
    }

    public function setAddRemoveLinks(bool $bool)
    {
        $this->addRemoveLinks = $bool;
        return $this;
    }

    public function Type()
    {
        return 'dropzone-field';
    }

    public function getDropzoneConfig()
    {
        $token = $this->getForm()->getSecurityToken();
        return Convert::array2json(array_merge_recursive($this->dropzoneConfig, [
            'url' => $this->Link('upload'),
            'addRemoveLinks' => $this->addRemoveLinks,
            'dictRemoveFile' => _t(__CLASS__ . '.RemoveFile', 'Remove file'),
            'headers' => [
                'X-' . $token->getName() => $token->getValue()
            ],
        ]));
    }

    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $attributes['dropzone-config'] = $this->getDropzoneConfig();
        return $attributes;
    }

    public function getIncludeScripts()
    {
        return self::config()->get('include_scripts_in_template');
    }
}