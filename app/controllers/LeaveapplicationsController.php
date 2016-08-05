<?php

class LeaveapplicationsController extends \BaseController {

	/**
	 * Display a listing of leaveapplications
	 *
	 * @return Response
	 */
	public function index()
	{
		$leaveapplications = Leaveapplication::all();

		return Redirect::to('leavemgmt');
	}

	/**
	 * Show the form for creating a new leaveapplication
	 *
	 * @return Response
	 */
	public function create()
	{
		$employees = Employee::all();

		$leavetypes = Leavetype::all();

		return View::make('leaveapplications.create', compact('employees', 'leavetypes'));
	}

	/**
	 * Store a newly created leaveapplication in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Leaveapplication::$rules,Leaveapplication::$messages);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$employee = Employee::find(array_get($data, 'employee_id'));

		$leavetype = Leavetype::find(array_get($data, 'leavetype_id'));

		$start_date = array_get($data, 'applied_start_date');
		$end_date = array_get($data, 'applied_end_date');

		$days_applied = Leaveapplication::getLeaveDays($start_date, $end_date);

		$balance_days = Leaveapplication::getBalanceDays($employee, $leavetype);


		if($days_applied > $balance_days){

			return Redirect::back()->with('info', 'The days you have applied are more than your balance. You have '.$balance_days.' days left');
		}


		if(Mailsender::checkConnection() == false){

				return Redirect::back()->with('notice', 'Employee has not been activated. Internet connection could not be established. kindly check your mail settings');
			}


		Leaveapplication::createLeaveApplication($data);

		if(Confide::user()->user_type == 'member'){

			return Redirect::to('css/leave');
		} else {
			return Redirect::to('leavemgmt');
		}
		
	}

	/**
	 * Display the specified leaveapplication.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$leaveapplication = Leaveapplication::findOrFail($id);

		return View::make('leaveapplications.show', compact('leaveapplication'));
	}

	/**
	 * Show the form for editing the specified leaveapplication.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$leaveapplication = Leaveapplication::find($id);

		$employees = Employee::all();

		$leavetypes = Leavetype::all();

		return View::make('leaveapplications.edit', compact('leaveapplication', 'employees', 'leavetypes'));
	}

	/**
	 * Update the specified leaveapplication in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$leaveapplication = Leaveapplication::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Leaveapplication::$rules,Leaveapplication::$messages);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Leaveapplication::amendLeaveApplication($data, $id);

		return Redirect::to('leavemgmt');
	}

	/**
	 * Remove the specified leaveapplication from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Leaveapplication::destroy($id);

		return Redirect::to('leavemgmt');
	}


	public function approve($id){

		$leaveapplication = Leaveapplication::find($id);

		

		return View::make('leaveapplications.approve', compact('leaveapplication'));



	}

	public function cssleaveapprove($id){

		$leaveapplication = Leaveapplication::find($id);

		

		return View::make('css.employeeleave', compact('leaveapplication'));



	}


	public function doApprove($id){



		$data = Input::all();

		/*if(Mailsender::checkConnection() == false){

				return Redirect::back()->with('notice', 'Leave could not be approved. Internet connection could not be established. kindly check your mail settings');
			} else {*/


				Leaveapplication::approveLeaveApplication($data, $id);

		return Redirect::route('leaveapplications.index');


			//}


		

	}

	public function supervisorapprove($id){

	    $leaveapplication = Leaveapplication::findOrFail($id);

	    $leaveapplication->is_supervisor_approved = 1;

	    $leaveapplication->update();

		return Redirect::to('css/subordinateleave')->withFlashMessage('Successfully Approved subordinate leave!');


	}

	public function supervisorreject($id){

	    $leaveapplication = Leaveapplication::findOrFail($id);

	    $leaveapplication->is_supervisor_approved = 0;

	    $leaveapplication->update();

		return Redirect::to('css/subordinateleave')->withFlashMessage('Successfully rejected subordinate leave!');


	}


	public function reject($id){

		Leaveapplication::rejectLeaveApplication($id);
		return Redirect::route('leaveapplications.index');

	}

	public function cancel($id){

		Leaveapplication::cancelLeaveApplication($id);
		return Redirect::route('leaveapplications.index');

	}

	public function redeem(){

		$employee = Employee::find(Input::get('employee_id'));
		$leeavetype = Leavetype::find(Input::get('leavetype_id'));

		Leaveapplication::RedeemLeaveDays($employee, $leavetype);

		return Redirect::route('leaveapplications.index');

	}


	public function approvals()
	{
		$leaveapplications = Leaveapplication::all();

		return View::make('leaveapplications.approved', compact('leaveapplications'));
	}


	public function amended()
	{
		$leaveapplications = Leaveapplication::all();

		return View::make('leaveapplications.amended', compact('leaveapplications'));
	}

	public function rejects()
	{
		$leaveapplications = Leaveapplication::all();

		return View::make('leaveapplications.rejected', compact('leaveapplications'));
	}

	public function cancellations()
	{
		$leaveapplications = Leaveapplication::all();

		return View::make('leaveapplications.cancelled', compact('leaveapplications'));
	}

}
