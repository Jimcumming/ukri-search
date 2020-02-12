<?php

describe(\UKRI\Search\Facets::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->facets = new \UKRI\Search\Facets();
    });

    it('is registerable', function () {
        expect($this->facets)->to->be->an->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the filter', function () {
            \WP_Mock::expectFilterAdded('ep_feature_active', [$this->facets, 'deactivate'], 10, 3);
            $this->facets->register();
        });
    });

    describe('->deactivate()', function () {
        context('the feature is not facets', function () {
            it('returns the active status it is given', function () {
                $feature = (object) [
                    'slug' => 'notFacets'
                ];
                $featureSettings = [
                    'some',
                    'settings'
                ];
                $result = $this->facets->deactivate(true, $featureSettings, $feature);
                expect($result)->to->equal(true);
                $result = $this->facets->deactivate(false, $featureSettings, $feature);
                expect($result)->to->equal(false);
            });
        });
        context('the feature is not facets', function () {
            it('returns false', function () {
                $feature = (object) [
                    'slug' => 'facets'
                ];
                $featureSettings = [
                    'some',
                    'settings'
                ];
                $result = $this->facets->deactivate(true, $featureSettings, $feature);
                expect($result)->to->equal(false);
            });
        });
    });
});
