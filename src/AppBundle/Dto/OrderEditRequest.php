<?php

namespace AppBundle\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Closure;
use SplFileInfo;

class OrderEditRequest extends OrderEdit
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            $request->get('key'),
            $request->get('work'),
            $request->get('number'),
            $request->get('brand'),
            $request->get('model'),
            $request->get('name'),
            $request->get('phone'),
            $request->get('email'),
            $request->files->get('photo'),
            $request->get('note')
        );
    }

    /**
     * @param Closure(SplFileInfo, string, string) $callback
     *
     * @return array
     */
    public function mapFiles(Closure $callback)
    {
        return array_map(function(UploadedFile $uploadedFile) use ($callback) {
            return call_user_func($callback,
                $uploadedFile->getFileInfo(),
                $uploadedFile->getMimeType(),
                $uploadedFile->getClientOriginalName() ?: uniqid('autoname-')
            );
        },
            $this->getFiles()
        );
    }

    /**
     * @return SplFileInfo[]
     */
    public function getFiles()
    {
        return array_filter((array) $this->files, function($file) {
            return (true
                and ($file instanceof UploadedFile)
                and ($file->isValid())
            );
        });
    }
}
