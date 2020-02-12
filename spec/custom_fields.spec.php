<?php

describe(\UKRI\Search\CustomFields::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->fieldMapping = \Mockery::mock(\UKRI\Search\FieldMapping::class);
        $this->customFields = new \UKRI\Search\CustomFields($this->fieldMapping);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registrable', function () {
        expect($this->customFields)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });
    
    describe('->register()', function () {
        it('adds the filter', function () {
            \WP_Mock::expectFilterAdded('ep_post_sync_args_post_prepare_meta', [$this->customFields, 'addCustomFieldsToContent'], 10, 1);
            $this->customFields->register();
        });
    });
    
    describe('->addCustomFieldsToContent()', function () {
        context('there is no field mapping for this post type', function () {
            it('returns the document unchanged', function () {
                $document = [
                    'post_id' => 123,
                    'post_type' => 'blog',
                    'post_content' => 'foo bar'
                ];
                $this->fieldMapping->shouldReceive('get')
                    ->once()
                    ->andReturn([
                        'news' => [
                            'aTemplate' => [
                                'some',
                                'fields'
                            ]
                        ]
                    ]);
                $result = $this->customFields->addCustomFieldsToContent($document);
                expect($result)->to->equal($document);
            });
        });
        context('there is a field mapping for this post type', function () {
            context('but not for the template this post uses', function () {
                it('returns the document unchanged', function () {
                    $document = [
                        'post_id' => 123,
                        'post_type' => 'blog',
                        'post_content' => 'foo bar'
                    ];
                    $this->fieldMapping->shouldReceive('get')
                        ->once()
                        ->andReturn([
                            'news' => [
                                'aTemplate' => [
                                    'some',
                                    'fields'
                                ]
                            ],
                            'blog' => [
                                'templateNew' => [
                                    'other',
                                    'fields'
                                ]
                            ]
                        ]);
                    \WP_Mock::wpFunction('get_page_template_slug', [
                        'times' => 1,
                        'args' => 123,
                        'return' => 'otherTemplate.php'
                    ]);
                    $result = $this->customFields->addCustomFieldsToContent($document);
                    expect($result)->to->equal($document);
                });
            });
            context('and for the template this post uses', function () {
                it('appends the content of those fields to the document content', function () {
                    $document = [
                        'post_id' => 123,
                        'post_type' => 'blog',
                        'post_content' => 'foo bar'
                    ];
                    $this->fieldMapping->shouldReceive('get')
                        ->once()
                        ->andReturn([
                            'news' => [
                                'aTemplate' => [
                                    'some',
                                    'fields'
                                ]
                            ],
                            'blog' => [
                                'templateNew' => [
                                    'other',
                                    'fields'
                                ],
                                'otherTemplate' => [
                                    'my',
                                    'custom',
                                    'acf_fields'
                                ]
                            ]
                        ]);
                    \WP_Mock::wpFunction('get_page_template_slug', [
                        'times' => 1,
                        'args' => 123,
                        'return' => 'otherTemplate.php'
                    ]);
                    \WP_Mock::wpFunction('get_field', [
                        'times' => 1,
                        'args' => ['my', 123],
                        'return' => 'alias grace'
                    ]);
                    \WP_Mock::wpFunction('get_field', [
                        'times' => 1,
                        'args' => ['custom', 123],
                        'return' => 'frankie and benny'
                    ]);
                    \WP_Mock::wpFunction('get_field', [
                        'times' => 1,
                        'args' => ['acf_fields', 123],
                        'return' => 'I am the Walrus'
                    ]);
                    $result = $this->customFields->addCustomFieldsToContent($document);
                    expect($result)->to->equal($document = [
                        'post_id' => 123,
                        'post_type' => 'blog',
                        'post_content' => 'foo bar alias grace frankie and benny I am the Walrus'
                    ]);
                });
            });
        });
    });
});
