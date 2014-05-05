<?php
/**
 * This file is loaded automatically by /app/config/bootstrap.php
 *
 * This file should load/create any custom application wide configuration settings
 */

$config = array();

$versionFile = file(APP . 'VERSION.txt');
/**
 * Application version
 */
$config['display.Version'] = trim(array_pop($versionFile));

/**
 * Application title
 */
$config['display.Short'] = 'RR';
$config['display.Name'] = 'RaumReservierung';

/**
 * Application author
 */
$config['display.Author'] = 'Sebastian Klatte';
$config['display.Support'] = 'wi1shk@wiwi.uni-paderborn.de';

/**
 * Application owner
 */
$config['display.Orga'] = 'Wirtschaftsinformatik 1';
$config['display.Owner'] = 'Prof. Dr. Joachim Fischer';
$config['display.Street'] = 'Warburger Straße 100';
$config['display.Location'] = '33098 Paderborn';
$config['display.Phone'] = '(0 52 51) 60 32 56';
$config['display.EMail'] = 'bpetermeier@wiwi.upb.de';
$config['display.Website'] = 'http://wiwi.uni-paderborn.de/dep3/betriebswirtschaftliche-informationssysteme-prof-joachim-fischer/';

/**
 * Application department
 */
$config['display.Department.Name'] = 'Department Wirtschaftsinformatik';
$config['display.Department.Website'] = 'http://wiwi.uni-paderborn.de/';

/**
 * Application university
 */
$config['display.University.Name'] = 'Universität Paderborn';
$config['display.University.Website'] = 'http://uni-paderborn.de/';