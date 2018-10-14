<?php

class FormBuild {

	public static function text($field, $label, $required=false, $type='text', $max=false, $min=false, $value=null) {
		$label = $label ? brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]) : '';
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

	public static function select($field, $label, $options, $required=false, $value=null) {
		$label = $label ? brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]) : '';
		$choices = '';
		foreach($options as $val => $text)
			$choices.= brick('option', $text, ['value'=>($text ? $text : $val)]);

		$field = brick('select', $choices, [
			'name' => $field,
			'id' => $field,
			'value' => $value,
			'aria-required' => r($required, 'true')
		]);

		$content = $label.brick('div', $field, ['class'=>'select-container']);

		return brick('div', $content, ['class'=>'row']);
	}

	public static function radio($field, $label, $options, $required=false, $value=null) {
		$label = $label ? brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]) : '';
		$choices = '';
		foreach($options as $val => $text)
			$choices.= brick('div', '<input type="radio" name="'.$field.'" value="'.$val.'" id="'.$val.'" aria-required="'.r($required, 'true').'">'.brick('label', $text, ['for'=>$val]));

		$field = brick('div', $choices, [
			'id' => $field,
			'class' => 'radio-container',
		]);

		return brick('div', $label.$field, ['class'=>'row']);
	}

	public static function textarea($field, $label, $required=false, $rows=4, $value=null) {
		$label = $label ? brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]) : '';
		$field = brick('textarea', $value, [
			'name' => $field,
			'id' => $field,
			'aria-required' => r($required, 'true'),
			'rows' => $rows
		]);

		return brick('div', $label.$field, ['class'=>'row']);
	}

}