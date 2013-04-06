<?php

return array(
    'view_helpers' => array(
        'invokables' => array(
            'form' => 'InfinityBase\Form\View\Helper\Form',
        ),
    ),
    'view_manager'    => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
