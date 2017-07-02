<?php

$fetchChildren = file_get_contents('http://localhost:/query.php?action=get_children&data={}');

$children = json_decode($fetchChildren)->data->children;

function getChildrenArray() {
	global $children;

	return json_encode($children);
}

function makeRowItem($child) {
	$name = $child->name;

	$html = '<div class="col-md-3 row-item">';

	$html .= '<img src="img/face.jpg" onclick="overlay_show('.$child->id.', \''.$name.'\')">';

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