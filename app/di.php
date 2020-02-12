<?php

$registrar->addInstance(new \UKRI\Search\FieldMapping());
$registrar->addInstance(new \UKRI\Search\CustomFields(
    $registrar->getInstance(\UKRI\Search\FieldMapping::class)
));
$registrar->addInstance(new \UKRI\Search\Facets());
