<?php

class FormBuild {

	public static function text($field, $label, $required=false, $type='text', $max=false, $min=false, $value=null, $noLabel=false) {
		$field = brick('input', false, [
			'type' => $type,
			'name' => $field,
			'id' => $field,
			'value' => $value,
			'aria-required' => r($required, 'true'),
			'min' => $min,
			'max' => $max,
			'placeholder' => r($noLabel, $label)
		]);

		if($noLabel)
			return $field;

		$label = $label ? brick('label', $label, ['for'=>$field, 'class'=>r($required, 'required')]) : '';

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

	public static function yesno($field, $text, $required=false, $value=null) {
		$label = brick('label', $text, ['for'=>$field]);
		$checkbox = brick('input', false, [
			'type' => 'checkbox',
			'name' => $field,
			'id' => $field,
			'value' => 'Yes',
			'aria-required' => r($required, 'true'),
			'checked' => r($value == 'Yes', true)
		]);

		return brick('div', $checkbox.$label, ['class'=>'yesno-wrapper']);
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