<?php

function asMoney($value) {
  return number_format($value, 2);
}

?>

@extends('layouts.accounting')
@section('content')

<div class="row">
	<div class="col-lg-12">
		<h3>Pay Bill</h3>
		<hr>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<form action="{{ URL::to('expenses/payBill') }}" method="POST">
			<fieldset>
				<input type="hidden" name="bill_id" value="{{ $bill->id }}">
				<div class="form-group">
				   <label for="username">Expense Name <span style="color:red">*</span> :</label>
				   <input class="form-control" placeholder="" type="text" name="name" id="name" value="{{ $bill->name }}" required>
				</div>

				<div class="form-group">
				   <label for="username">Type</label><span style="color:red">*</span> :
				   <input class="form-control" placeholder="" type="text" name="type" value="{{$bill->type}}" required>
				</div>

				<div class="form-group">
				   <label for="username">From Account</label><span style="color:red">*</span> :
				   <select name="from_account" class="form-control" required>
				     <option></option>
				     @foreach($accounts as $account)
				       <option value="{{$account->id }}"<?= ($bill->account_from==$account->id)?'selected="selected"':''; ?>> {{ $account->name }}</option>
				     @endforeach
				   </select>
				</div>

				<div class="form-group">
				   <label for="username">To Account</label><span style="color:red">*</span> :
				   <select name="to_account" class="form-control" required>
				     <option></option>
				     @foreach($accounts as $account)
				       <option value="{{$account->id }}"<?= ($bill->account_to==$account->id)?'selected="selected"':''; ?>> {{ $account->name }}</option>
				     @endforeach
				   </select>
				</div>

				<div class="form-group">
				   <label for="username">Amount <span style="color:red">*</span> :</label>
				   <div class="input-group">
						<span class="input-group-addon">KES</span>
				   	<input class="form-control" placeholder="" type="text" name="amount" id="amount" value="{{$bill->amount}}" required>
					</div>
				</div>

				<!-- <div class="form-group">
				   <label for="username">Date</label><span style="color:red">*</span> :
				   <div class="right-inner-addon ">
				     <i class="glyphicon glyphicon-calendar"></i>
				     <input class="form-control datepicker"  readonly="readonly" placeholder="" type="text" name="date" id="date" value="{{$bill->date}}" required>
				   </div>
				</div> -->

				<div class="form-actions form-group">
				 	<button type="submit" class="btn btn-primary btn-sm">Pay Bill</button>
				</div>
			</fieldset>
		</form>
	</div>
</div>

@stop