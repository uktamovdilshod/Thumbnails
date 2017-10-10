This Thumbnail plugin for <a href="https://github.com/Studio-42/elFinder">https://github.com/Studio-42/elFinder</a>

**Installation**

1. You need install <a href="https://github.com/yiisoft/yii2-imagine">yii2-imagine </a>

2. Download this repo and extract zip file, then put "Thumbnails" folder to put-to/elfinder/php/plugins

3. Connect plugin:

            connectOptions' => [
                'bind' => [
                    'rm.pre' => [
                        ...,
                        'Plugin.Thumbnails.removeThumbs'
                        ...,
                    ],
                    'upload.presave' => [
                        ...,
                        'Plugin.Thumbnails.generateThumbs'
                        ...,
                    ],
                ],
               'plugin' => [
                    ...,
                    'Thumbnails' => [
                        'enable' => true,
                        'thumb_path' => $_SERVER['DOCUMENT_ROOT'].'/upload/',  // your elfinder upload folder. Plugin create new .thumb folder In this folder
                        'thumb' =>  [
                            'small' =>  [200, 150],
                            'medium' => [400, 250],
                            'large' =>  null,
                        ],
                    ],
                    ...,
                ],
            ]

