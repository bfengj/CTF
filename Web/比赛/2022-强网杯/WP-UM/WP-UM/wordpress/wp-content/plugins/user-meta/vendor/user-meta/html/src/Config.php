<?php
namespace UserMeta\Html;

/*
 * Default config for the package.
 *
 * @author Khaled Hossain
 * @since 1.1
 */
trait Config
{

    /**
     * Accepted config as attributes.
     *
     * @var array
     */
    protected $config = [
        'LABEL' => 'label', // hard coded on OptionsElement
        'BEFORE' => '_before',
        'AFTER' => '_after',
        'ENCLOSE' => '_enclose',
        'OPTION_BEFORE' => '_option_before', // hard coded on OptionsElement
        'OPTION_AFTER' => '_option_after', // hard coded on OptionsElement
        'DISABLE_ESCAPE' => '_disable_escape'
    ];

    /**
     * Valid html5 input type.
     * Anything other than that is considered as tag.
     *
     * @var array
     */
    protected $inputTypes = [
        'button',
        'checkbox',
        'color',
        'date',
        'datetime',
        'datetime-local',
        'email',
        'file',
        'hidden',
        'image',
        'month',
        'number',
        'password',
        'radio',
        'range',
        'reset',
        'search',
        'submit',
        'tel',
        'text',
        'time',
        'url',
        'week'
    ];

    /**
     * Attributes configurations.
     *
     * @var array
     */
    protected $attributesConfig = [
        'value' => [
            '_escape_function' => 'esc_attr'
        ],
        'id' => [
            '_escape_function' => 'esc_attr'
        ],
        'class' => [
            '_escape_function' => 'esc_attr'
        ],
        'src' => [
            '_escape_function' => 'esc_url'
        ],
        'href' => [
            '_escape_function' => 'esc_url'
        ]
    ];
}