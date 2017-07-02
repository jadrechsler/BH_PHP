<?php

// Sample Test List of children and their information
$children = array(
	array(
		'name' => 'Jamie Rozanne',
		'present' => 1
		// There will also be img link
		// and any other information
	),
	array(
		'name' => 'Casey Louie',
		'present' => 0
	),
	array(
		'name' => 'Gillian Elfreda',
		'present' => 1
	),
	array(
		'name' => 'Kira Edgar',
		'present' => 0
	),
	array(
		'name' => 'Heath Louis',
		'present' => 0
	),
	array(
		'name' => 'Emery Katherina',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Ethel Zackery',
		'present' => 1
	),
	array(
		'name' => 'Alec Brandie',
		'present' => 0
	)
);

function getChildrenArray() {
	global $children;

	return json_encode($children);
}

function makeRowItem($child) {
	$name = $child["name"];

	$html = '<div class="col-md-3 row-item">';

	$html .= '<img src="img/face.jpg" onclick="overlay_show(1, \''.$name.'\')">';

	$html .= '<figcaption>'.$name.'</figcaption>';

	$html .= '</div>';

	return $html;
}

function makeRow($list) {
	$html = '<div class="container-fluid row">';

	foreach ($list as $child) {
		$html .= makeRowItem($child);
	}

	$html .= '</div>';

	return $html;
}

function makeRowSet($list) {
	$html = '';

	foreach (array_chunk($list, 5) as $row) {
		$html .= makeRow($row);
	}

	return $html;
}

?>