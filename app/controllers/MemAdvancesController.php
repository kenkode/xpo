<?php

class MemAdvancesController extends \BaseController {

	/**
	 * Display a listing of accounts
	 *
	 * @return Response
	 */
	public function index()
	{
		$advances = Memberadvance::orWhere('is_admin_visible',1)->orderBy('date','desc')->get();

        return View::make('memberadvances.index', compact('advances'));
	}

	/**
	 * Show the form for creating a new account
	 *
	 * @return Response
	 */
	public function approve($id)
	{
        $advance = Memberadvance::findOrFail($id);
        
        $employee = Employee::findOrFail($advance->employee_id);

        return View::make('memberadvances.approve',compact('advance','employee'));
	}

	public function memapprove($id)
	{
		
        $memberadvance = Memberadvance::findOrFail($id);
        
        $employee = Employee::findOrFail($memberadvance->employee_id);

		$memberadvance->status = 'Approved';
		$memberadvance->is_admin_visible = 1;
		
		$memberadvance->update();

		$ded = new EDeduction;

		$ded->employee_id = $memberadvance->employee_id;

		$ded->deduction_id = 1;

		$ded->formular = 'One Time';

	    $ded->instalments = '1';
       
        $ded->deduction_amount = $memberadvance->amount;

        $ded->deduction_date = date("Y-m-d");

        $First  = date('Y-m-01', strtotime(date("Y-m-d")));
        
        $Last   = date('Y-m-t', strtotime(date("Y-m-d")));
        
        $ded->first_day_month = $First;

        $ded->last_day_month = $Last;

		$ded->save();

		$name = $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;


		Mail::send( 'emails.approval', array('advance'=>$memberadvance, 'name'=>$name, 'employee'=>$employee), function( $message ) use ($employee)
		{
    		
    		$message->to($employee->email_office)->subject( 'Salary Advance Approval' );
		});


		Audit::logaudit('Memberadvance', 'approve', 'Approved: '.Confide::user()->username.' approved advance');

		return Redirect::to('memberadvances')->withFlashMessage('Advance successfully approved!');
	}
    
    public function reject($id)
	{
        $advance = Memberadvance::findOrFail($id);
        
        $employee = Employee::findOrFail($advance->employee_id);

        return View::make('memberadvances.reject',compact('advance','employee'));
	}

	public function memrej($id)
	{
		
        $memberadvance = Memberadvance::findOrFail($id);
        
        $employee = Employee::findOrFail($memberadvance->employee_id);

		$memberadvance->status = 'Rejected';
		$memberadvance->is_admin_visible = 1;
		
		$memberadvance->update();

		$name = $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;


		Mail::send( 'emails.rejection', array('advance'=>$memberadvance, 'name'=>$name, 'employee'=>$employee), function( $message ) use ($employee)
		{
    		
    		$message->to($employee->email_office)->subject( 'Salary Advance Rejection' );
		});

		
		Audit::logaudit('Memberadvance', 'reject', 'Rejected: '.Confide::user()->username.' rejected advance');

		return Redirect::to('memberadvances')->withFlashMessage('Advance successfully rejected!');
	}


	/**
	 * Store a newly created account in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Memberadvance::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

        $employeeid = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');

        $employee = Employee::find($employeeid);

		$memberadvance = new Memberadvance;

		$memberadvance->employee_id = $employeeid;
		$memberadvance->amount = str_replace(',', '', Input::get('amount'));
		$memberadvance->date = Input::get('date');
		$memberadvance->type = Input::get('type');
		$memberadvance->status = 'Pending';
		$memberadvance->fiscal_year = date('Y');
		
		$memberadvance->save();

		Audit::logaudit('Memberadvance', 'applied', 'Applied: '.$employee->personal_file_number.' : '.$employee->last_name.' '.$employee->first_name.' applied advance of KES '.Input::get('amount').' type '.Input::get('type'));

		return Redirect::to('css/advances')->withFlashMessage('Advance successfully created!');
	}

	/**
	 * Display the specified account.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$account = Account::findOrFail($id);

		return View::make('accounts.show', compact('account'));
	}

	/**
	 * Show the form for editing the specified account.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$advance = Memberadvance::find($id);

		return View::make('css.advanceupdate', compact('advance'));
	}

	/**
	 * Update the specified account in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validator = Validator::make($data = Input::all(), Memberadvance::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

        $employeeid = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');

        $employee = Employee::find($employeeid);

		$memberadvance = Memberadvance::findOrFail($id);

		$memberadvance->employee_id = $employeeid;
		$memberadvance->amount = str_replace(',', '', Input::get('amount'));
		$memberadvance->date = Input::get('date');
		$memberadvance->type = Input::get('type');
		$memberadvance->status = 'Pending';
		$memberadvance->fiscal_year = date('Y');
		
		$memberadvance->update();

		Audit::logaudit('Memberadvance', 'update', 'Updated: '.$employee->personal_file_number.' : '.$employee->last_name.' '.$employee->first_name.' updated advance');

		return Redirect::to('css/advances')->withFlashMessage('Advance successfully updated!');
	}

	/**
	 * Remove the specified account from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		$advance = Memberadvance::findOrFail($id);

		Memberadvance::destroy($id);


		Audit::logaudit('Memberadvance', 'delete', 'deleted: advance');


		return Redirect::to('css/advances')->withDeleteMessage('Advance successfully deleted!');
	}

	public function view($id){

		$advance = Memberadvance::find($id);

		$organization = Organization::find(1);

		$employee = Employee::find($advance->employee_id);

		$pdf = PDF::loadView('pdf.advanceform', compact('advance','organization','employee'))->setPaper('a4')->setOrientation('potrait');
  
        return $pdf->stream($employee->personal_file_number.'_'.$employee->last_name.'_'.$employee->first_name.'_member_advance_form.pdf');
		
	}

}
