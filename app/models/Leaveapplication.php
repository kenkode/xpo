<?php

class Leaveapplication extends \Eloquent {

	// Add your validation rules here
	public static $rules = [
		'applied_start_date' => 'required',
		'applied_end_date' => 'required|after:applied_start_date'
	];

    public static $messages = array(
        'applied_start_date.required'=>'Please select start date!',
        'applied_end_date.required'=>'Please select end date!',
        'applied_start_date.after'=>'End date cannot be before start date!',
    );

	// Don't forget to fill this array
	protected $fillable = [];

	public function organization(){
		
		return $this->belongsTo('Organization');
	}


	public function employee(){
		
		return $this->belongsTo('Employee');
	}


	public function leavetype(){

		return $this->belongsTo('Leavetype');
	}


	public static function createLeaveApplication($data){

		$organization = Organization::getUserOrganization();

		$employee = Employee::find(array_get($data, 'employee_id'));

		$leavetype = Leavetype::find(array_get($data, 'leavetype_id'));

		$application = new Leaveapplication;

		$application->applied_start_date = array_get($data, 'applied_start_date');
		$application->applied_end_date = array_get($data, 'applied_end_date');
		$application->status = 'applied';
		$application->application_date = date('Y-m-d');
		$application->employee()->associate($employee);
		$application->leavetype()->associate($leavetype);
		$application->organization()->associate($organization);
		$application->is_supervisor_approved = 0;
		$application->save();






        /*

		$name = $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;


		Mail::send( 'emails.leavecreate', array('application'=>$application, 'name'=>$name), function( $message ) use ($organization)
		{
    		
    		$message->to($organization->email )->subject( 'Leave Application' );
		});

               */

     


	}




	public static function amendLeaveApplication($data, $id){

		

		$leavetype = Leavetype::find(array_get($data, 'leavetype_id'));

		$application = Leaveapplication::find($id);

		$application->applied_start_date = array_get($data, 'applied_start_date');
		$application->applied_end_date = array_get($data, 'applied_end_date');
		$application->status = 'amended';
		$application->date_amended = date('Y-m-d');
		$application->leavetype()->associate($leavetype);
		
		$application->update();

	}


	public static function approveLeaveApplication($data, $id){

		

		$application = Leaveapplication::find($id);

		$application->approved_start_date = array_get($data, 'approved_start_date');
		$application->approved_end_date = array_get($data, 'approved_end_date');
		$application->status = 'approved';
		$application->date_approved = date('Y-m-d');
		
		$application->update();


		$employeeid = DB::table('leaveapplications')->where('id', '=', $application->id)->pluck('employee_id');

  		$employee = Employee::findorfail($employeeid);

		$name = $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;


		/*Mail::send( 'emails.leaveapprove', array('application'=>$application, 'name'=>$name), function( $message ) use ($employee)
		{
    		
    		$message->to($employee->email_office )->subject( 'Leave Approval' );
		});*/

	}


	public static function cancelLeaveApplication($id){

		

		$application = Leaveapplication::find($id);

	
		$application->status = 'cancelled';
		$application->date_cancelled = date('Y-m-d');
		
		$application->update();

	}


	public static function rejectLeaveApplication($id){

		

		$application = Leaveapplication::find($id);

	
		$application->status = 'rejected';
		$application->date_rejected = date('Y-m-d');
		
		$application->update();

	}


	public static function getLeaveDays($start_date, $end_date){

		$start = new DateTime($start_date);
		$end = new DateTime($end_date);

		//$start = strototime($start_date);
		//$end = strototime($end_date);

		//$diff =$end - $start;

		//$diff=date_diff($end, $start);


        $interval = $end->diff($start);

         $interval->format('%m');
return $interval->days;

		//return strtotime($diff);

		
	}


	public static function checkWeekend($date){

    		return (date('N', strtotime($date)) >= 6);
		

	}



	public static function checkHoliday($date){


    		$holiday = DB::table('holidays')->where('date', '=', $date)->count();

    		if($holiday >= 1){

    			return true;
    		} else {

    			return false;
    		}
		

	}


	public static function getDaysTaken($employee, $leavetype){



		$leavestaken = DB::table('leaveapplications')->where('employee_id', '=', $employee->id)->where('leavetype_id', '=', $leavetype->id)->where('status', '=', 'approved')->get();
		
		$daystaken = 0;
		foreach ($leavestaken as $leavetaken) {
			
			

				$taken = Leaveapplication::getLeaveDays($leavetaken->approved_start_date, $leavetaken->approved_end_date);

				$daystaken = $daystaken + $taken;

			
			
			

		}

		return $daystaken;

	}


	public static function getBalanceDays($employee, $leavetype){

		$currentyear = date('Y');

		$joined_year = date('Y', strtotime($employee->date_joined));

		if($currentyear == $joined_year){
			$years = 1;
		} else {

			$years = $currentyear - $joined_year;

		}

		
		$entitled = ($years * $leavetype->days);

		$daystaken = Leaveapplication::getDaysTaken($employee, $leavetype);

		$balance = $entitled - $daystaken;

		return $balance;
		
	}


	public static function getRedeemLeaveDays($employee, $leavetype){

		$payrate = $employee->basic_pay/ 30;

		$balancedays = Leaveapplication::getBalanceDays($employee, $leavetype);

		$amount = $balancedays * $payrate;

		return $amount;
	}


	public static function RedeemLeaveDays($employee, $leavetype){

		$payrate = $employee->basic_pay/ 30;

		$balancedays = Leaveapplication::getBalanceDays($employee, $leavetype);

		$amount = $balancedays * $payrate;

		Earning::insert($employee->id, 'Leave earning', 'redeemed leave days', $amount);
	}








}
