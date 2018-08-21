<?php

// kirby()->routes([
// 	[
// 		'pattern' => 'form-process',
// 		'method'  => 'GET',
// 		'action'  => function() {
			
// 		}
// 	]
// ]);

class FormBuild {

	public static function text($field, $label, $value=null, $required=false, $type='text', $max=false, $min=false) {
		$label = brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]);
		$field = brick('input', false, [
			'type' => $type,
			'name' => $field,
			'id' => $field,
			'value' => $value,
			'aria-required' => r($required, 'true'),
			'min' => $min,
			'max' => $max
		]);

		return brick('div', $label.$field, ['class'=>'row']);
	}

	public static function select($field, $label, $value=null, $required=false, $options) {
		$label = brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]);
		$choices = '';
		foreach($options as $val => $text) {
			$choices.= '<option value="'.$val.'">'.$text.'</option>';
		}
		$field = brick('select', $choices, [
			'name' => $field,
			'id' => $field,
			'value' => $value,
			'aria-required' => r($required, 'true')
		]);

		$content = $label.brick('div', $field, ['class'=>'select-wrapper']);

		return brick('div', $content, ['class'=>'row']);
	}

	public static function textarea($field, $label, $value=null, $required=false, $rows=4) {
		$label = brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]);
		$field = brick('textarea', $value, [
			'name' => $field,
			'id' => $field,
			'aria-required' => r($required, 'true'),
			'rows' => $rows
		]);
		return brick('div', $label.$field, ['class'=>'row']);
	}

}