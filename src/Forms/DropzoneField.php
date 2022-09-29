<?php

namespace XD\DropzoneField\Forms;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\SS_List;
use SilverStripe\Versioned\Versioned;

/**
 * Class DropzoneField
 *
 * @author Bram de Leeuw
 */
class DropzoneField extends UploadField
{
    private static $include_scripts_in_template = true;

    private static $allowed_actions = [
        'remove'
    ];

    protected $dropzoneConfig = [
        'paramName' => 'Upload'
    ];

    public function __construct($name, $title = null, $items = null)
    {
        if ($items && !$items instanceof SS_List) {
            if (!is_array($items)) {
                $items = [$items];
            }

            $items = ArrayList::create($items);            
        }

        parent::__construct($name, $title, $items);
    }

    public function remove(HTTPRequest $request)
    {
        if ($this->isDisabled() || $this->isReadonly()) {
            return $this->httpError(403);
        }

        // CSRF check
        $token = $this->getForm()->getSecurityToken();
        if (!$token->checkRequest($request)) {
            return $this->httpError(400);
        }

        if (!$body = $request->getBody()) {
            return $this->httpError(400);
        }

        $body = json_decode($body, true);
        if (!isset($body['fileId'])) {
            return $this->httpError(400);
        }

        $fileId = $body['fileId'];
        /** @var Image|Versioned $image */
        $image = Image::get_by_id($fileId);
        if (!$image || !$image->exists()) {
            return $this->httpError(400);
        }

        // remove the file
        $image->doArchive();        
        return (new HTTPResponse(json_encode(['removedFile' => $fileId])))
            ->addHeader('Content-Type', 'application/json');
    }

    public function setAddRemoveLinks(bool $bool)
    {
        $this->addDropzoneConfig('addRemoveLinks', $bool);
        return $this;
    }

    public function Type()
    {
        return 'dropzone-field';
    }

    public function getDropzoneConfig()
    {
        $config = $this->dropzoneConfig;
        
        // setup uploadfield uptions to dropzone options
        $config['maxFilesize'] = $this->getAllowedMaxFileSize() / 1024 / 1024;
        $config['maxFiles'] = !$this->getIsMultiUpload() ? 1 : $this->getAllowedMaxFileNumber();;

        $token = $this->getForm()->getSecurityToken();
        return json_encode(array_merge_recursive($config, [
            'url' => $this->Link('upload'),
            'removeUrl' => $this->Link('remove'),
            'headers' => [
                'X-' . $token->getName() => $token->getValue()
            ],
        ]));
    }

    public function addDropzoneConfig($key, $value) 
    {
        $this->dropzoneConfig[$key] = $value;
        return $this;
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
