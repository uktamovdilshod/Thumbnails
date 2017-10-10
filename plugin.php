<?php
/***************************************************
 *  Author: O'ktamov Dilshod
 *  Description: Used to create custom .
 *  © Copyright 2017 http://uktamov.uz
 ***************************************************/


use yii\imagine\Image;

class elFinderPluginThumbnails {

    private $options = [];

    private $defaultOptions = [
        'enable' => true,
        'thumb_path' => '',
        'thumb' =>  [
            'small'  => null,
            'medium' => null,
            'large'  => null,
        ],
    ];

    public function __construct($options)
    {
        $this->options = array_merge( $this->defaultOptions, $options );
    }

    public function generateThumbs(&$path, &$name, $src, $elfinder, $volume)
    {

        $options = $this->options;

        if($options == 'false' or $options['thumb_path']=='')
        {
            return false;
        }

        $imgTypes = $this->mimeType($options, $src);

        if($imgTypes == 'false')
        {
            return false;
        }

        $thumbPath = $options['thumb_path'];

        $this->createFolders($thumbPath);

        $this->resize($src, $thumbPath, $name);

    }

    public function removeThumbs($cmd, $targets)
    {

        $thumbs = $this->options['thumb'];

        foreach( $thumbs as $key => $value)
        {
            if(is_dir( $this->options['thumb_path'] . '.thumb/' . $key )) {
                foreach ($targets['targets'] as $target) {
                    $h = explode('_',$target);
                    $fileName = explode('/',base64_decode(strtr(end($h), '-_.', '+/=')));
                    if (file_exists($this->options['thumb_path'] . '.thumb/' . $key .'/'.end($fileName))) {
                        unlink($this->options['thumb_path'] . '.thumb/' . $key .'/'.end($fileName));
                    }
                }
            }
        }
    }

    protected function mimeType($opts, $src)
    {
        $srcImgInfo = @getimagesize( $src );

        if ( $srcImgInfo === false ) {
            return 'false';
        }

        switch ( $srcImgInfo[ 'mime' ] ) {
            case 'image/gif':
                break;
            case 'image/jpeg':
                break;
            case 'image/png':
                break;
            default:
                return 'false';
        }
    }

    private function createFolders($thumbPath)
    {
        $thumbs = $this->options['thumb'];

        foreach( $thumbs as $key => $value)
        {
            if($value != '')
            {
                if( ! is_dir( $thumbPath . '.thumb/' . $key ))
                {
                    mkdir($thumbPath . '.thumb/' . $key, 0777, true);
                }
            }
        }
    }

    private function resize($src, $thumbPath, $name)
    {
        $thumbs = $this->options['thumb'];

        foreach( $thumbs as $key => $value)
        {
            if(is_array($value) and count($value)==2)
            {
                $fakeName = $this->getName($thumbPath . '.thumb/' . $key . '/',$name);

                Image::thumbnail($src,$value[0],$value[1])
                    ->save($thumbPath . '.thumb/' . $key . '/' . $fakeName);
            }
        }
    }

    private function getName($thumbPath,$name)
    {
        $fileName = explode('.',str_replace('_','',$name));
        $count = count($fileName);
        $name = '';
        foreach ($fileName as $a=>$item) {
            if ($a==($count-2)) {
                if (!file_exists($thumbPath. $name.$item.'.'.end($fileName))) {
                    $name .= $item.'.'.end($fileName);
                    break;
                }else {
                    for ($i = 1; $i <= 100; $i++) {
                        if (file_exists($thumbPath . $name.$item . '-' . $i . '.' . end($fileName))) {
                            $this->getName($thumbPath, $name.$item . '-' . $i . '.' . end($fileName));
                        } else {
                            $name .= $item . '-' . $i . '.';
                            break;
                        }
                    }
                }
            }else{
                $name .=$item;
            }
        }
        return $name;
    }
}
