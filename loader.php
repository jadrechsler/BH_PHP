<?php


// Sample Test List of children and their information
$children = array(
	array(
		'name' => 'Jamie Rozanne',
		'example' => 'nothing'
		// There will also be img link
		// and any other information
	),
	array(
		'name' => 'Casey Louie',
		'example' => 'nothing'
	),
	array(
		'name' => 'Gillian Elfreda',
		'example' => 'nothing'
	),
	array(
		'name' => 'Kira Edgar',
		'example' => 'nothing'
	),
	array(
		'name' => 'Heath Louis',
		'example' => 'nothing'
	),
	array(
		'name' => 'Emery Katherina',
		'example' => 'nothing'
	),
	array(
		'name' => 'Ethel Zackery',
		'example' => 'nothing'
	),
	array(
		'name' => 'Alec Brandie',
		'example' => 'nothing'
	)
);

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

	foreach (array_chunk($list, 4) as $row) {
		$html .= makeRow($row);
	}

	return $html;
}
?>