<?php

class Expense extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		'name' => 'required',
		'type' => 'required',
		'from_account' => 'required',
		'to_account' => 'required',
	];

	public static $messages = array(
    	'name.required'=>'Please insert expense name!',
        'type.required'=>'Please select expense type!',
        'from_account.required'=>'Please select a from account!',
        'to_account.required'=>'Please select a to account!',
        'amount.required'=>'Please insert amount name!',
    );

	// Don't forget to fill this array
	protected $fillable = [];


	public function account(){
		return $this->belongsTo('Account');
	}

}