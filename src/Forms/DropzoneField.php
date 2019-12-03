<?php

namespace XD\DropzoneField\Forms;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Core\Convert;

/**
 * Class DropzoneField
 *
 * @author Bram de Leeuw
 */
class DropzoneField extends UploadField
{
    private static $include_scripts_in_template = true;

    protected $dropzoneConfig = [
        'paramName' => 'Upload'
    ];

    public function Type()
    {
        return 'dropzone-field';
    }

    public function getDropzoneConfig()
    {
        $token = $this->getForm()->getSecurityToken();
        return Convert::array2json(array_merge_recursive($this->dropzoneConfig, [
            'url' => $this->Link('upload'),
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