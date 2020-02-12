<?php

describe(\UKRI\Search\FieldMapping::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->fieldMapping = new \UKRI\Search\FieldMapping();
    });
    
    describe('->get()', function () {
        it('returns an array', function () {
            /* This is essentially config,
            * so it doesn't make sense to test
            * the exact contents
            */
            $result = $this->fieldMapping->get();
            expect(is_array($result))->to->equal(true);
        });
    });
});
