<?php

require __DIR__ . '/vendor/autoload.php';

use UserMeta\Html\Form;

/**
 * Create a text field with default value
 */
echo Form::text('Some text');

/**
 * Create a text field with default value, name, id and class attributes
 */
echo Form::text('Some text', ['name' => 'Name', 'id' => 'ID', 'class' => 'Class']);

/**
 * Create an email input field
 */
echo Form::input('email', 'noreply@gmail.com');

/**
 * Create a checkbox with default checked and with name and id attributes
 */
echo Form::checkbox(true, ['name' => 'Name', 'id' => 'ID']);

/**
 * Create a list of checkboxes with default values
 */
echo Form::checkboxList(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Create a select with default value, name and id attributes
 */
echo Form::select(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Alies select
 */
echo Form::dropdown(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Create a list of radio
 */
echo Form::radio(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Create a lebel with label text, id, class and for attributes
 */
echo Form::label('Some text', ['id' => 'ID', 'class' => 'Class', 'for' => 'for']);
