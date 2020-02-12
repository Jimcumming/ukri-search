<?php

namespace UKRI\Search;

class FieldMapping
{
    public function get()
    {
        /*
        * An associative array of post types,
        * their associated templates which
        * use custom fields, and the custom
        * field names that should have their
        * content indexed for that template.
        *
        * Use the template filename, without
        * .php extension. Use "default" for
        * default template.
        */
        return [
            'opportunity' => [
                'default' => [
                    'summary',
                    'description',
                    'eligibility',
                    'scope',
                    'how_to_apply',
                    'how_to_assess',
                    'additional_info',
                    'cofunders'
                ],
                'template-opportunity-simple' => [
                    'description_simple'
                ]
            ],
            'council' => [
                'default' => [
                    'council_description',
                    'address'
                ]
            ],
            'page' => [
                'template-chapter-nav-parent' => [
                    'chapter_one_title',
                    'content'
                ]
            ]
        ];
    }
}
