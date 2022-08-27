<?php
use Ilex\Validation\HkidValidation\HkidDigitCheck;

require_once 'vendor/autoload.php';

$p1 = 'CA';
$p2 = '182361';
$p3 = '1';
$s = 'CA182361(1)';

$c = new HkidDigitCheck();
$hkid = $c->checkParts($p1,$p2,$p3);
if ($hkid->isValid()) {
    echo ('correct');
    echo $hkid->format();
} else {
    echo ('wrong');
    if ($hkid->isPattenError()) {
        echo('Patten not match');
    }
    if ($hkid->isDigitError()) {
        echo('Digit not match');
    }
}

$hkid = $c->checkString($s);
if ($hkid->isValid()) {
    echo ('correct');
    echo $hkid->format();
} else {
    echo ('wrong');
}
