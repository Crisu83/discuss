<?php
// Message command configuration.
return array(
    'translator' => 't',
    'sourcePath' => __DIR__ . '/..',
    'messagePath' => __DIR__ . '/../messages',
    'languages' => array('fi'),
    'fileTypes' => array('php'),
    'overwrite' => true,
    'exclude' => array(
        '.git',
        '/gii',
        '/i18n',
        '/messages',
        '/vendor',
    ),
);