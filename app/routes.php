<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('/', function()
{
    $organization = DB::table('organizations')->where('id', '=', 1)->first();
    $count = count(User::all());

    if($count <= 1 ){

        return View::make('login', compact('organization'));
    }


	if (Confide::user()) {

   

        return Redirect::to('/dashboard');
        } else {
            return View::make('login',compact('organization'));
        }
});

Route::get('roles/show/{id}', 'RolesController@show');

Route::get('mail', function(){
	$mail = Mailsender::find(1);	
	return View::make('system.mail', compact('mail'));

});

Route::get('templates', function(){

	return View::make('organization.template');
});

Route::get('/dashboard', function()
{
	if (Confide::user()) {


        if(Confide::user()->user_type == 'admin'){

             
          $employees = Employee::getActiveEmployee();
           return View::make('dashboard', compact('employees'));



        } 


        if(Confide::user()->user_type == 'member'){

          $employee_id = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');
          
          $c = Supervisor::where('employee_id', $employee_id)->count();
             
          $employee = Employee::findorfail($employee_id);
           return View::make('empdash', compact('employee','c'));



        } 

        

      
        } else {
            return View::make('login');
        }
});
//

Route::get('fpassword', function(){

  return View::make(Config::get('confide::forgot_password_form'));

});
// Confide routes
Route::resource('users', 'UsersController');
Route::get('users/create', 'UsersController@create');
Route::get('users/edit/{user}', 'UsersController@edit');
Route::post('users/update/{user}', 'UsersController@update');
Route::post('users', 'UsersController@store');
Route::get('users/add', 'UsersController@add');
Route::post('users/newuser', 'UsersController@newuser');
Route::get('users/login', 'UsersController@login');
Route::post('users/login', 'UsersController@doLogin');
Route::get('users/confirm/{code}', 'UsersController@confirm');
Route::get('users/forgot_password', 'UsersController@forgotPassword');
Route::post('users/forgot_password', 'UsersController@doForgotPassword');
Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
Route::post('users/reset_password', 'UsersController@doResetPassword');
Route::get('users/logout', 'UsersController@logout');
Route::get('users/activate/{user}', 'UsersController@activate');
Route::get('users/deactivate/{user}', 'UsersController@deactivate');
Route::get('users/destroy/{user}', 'UsersController@destroy');
Route::get('users/password/{user}', 'UsersController@Password');
Route::post('users/password/{user}', 'UsersController@changePassword');
Route::get('users/profile/{user}', 'UsersController@profile');
Route::get('users/show/{user}', 'UsersController@show');


Route::post('users/changepassword/{user}', 'UsersController@updateCreds');
Route::get('users/client/{user}', 'UsersController@client');
Route::get('users/change/{user}', 'UsersController@change');



Route::post('users/pass', 'UsersController@changePassword2');

Route::group(['before' => 'manage_roles'], function() {

Route::resource('roles', 'RolesController');
Route::get('roles/create', 'RolesController@create');
Route::get('roles/edit/{id}', 'RolesController@edit');
Route::post('roles/update/{id}', 'RolesController@update');
Route::get('roles/destroy/{id}', 'RolesController@destroy');

});

Route::get('import', function(){

    return View::make('import');
});


Route::group(['before' => 'manage_system'], function() {

Route::get('system', function(){


    $organization = Organization::find(1);

    return View::make('system.index', compact('organization'));
});

});



Route::get('license', function(){


    $organization = Organization::find(1);

    return View::make('system.license', compact('organization'));
});




/**
* Organization routes
*/

Route::group(['before' => 'manage_organization'], function() {

Route::resource('organizations', 'OrganizationsController');

Route::post('organizations/update/{id}', 'OrganizationsController@update');
Route::post('organizations/logo/{id}', 'OrganizationsController@logo');

});

Route::get('language/{lang}', 
           array(
                  'as' => 'language.select', 
                  'uses' => 'OrganizationsController@language'
                 )
          );



Route::resource('currencies', 'CurrenciesController');
Route::get('currencies/edit/{id}', 'CurrenciesController@edit');
Route::post('currencies/update/{id}', 'CurrenciesController@update');
Route::get('currencies/delete/{id}', 'CurrenciesController@destroy');
Route::get('currencies/create', 'CurrenciesController@create');



/*
* branches routes
*/

Route::group(['before' => 'manage_branches'], function() {

Route::resource('branches', 'BranchesController');
Route::post('branches/update/{id}', 'BranchesController@update');
Route::get('branches/delete/{id}', 'BranchesController@destroy');
Route::get('branches/edit/{id}', 'BranchesController@edit');
});
/*
* groups routes
*/
Route::group(['before' => 'manage_groups'], function() {

Route::resource('groups', 'GroupsController');
Route::post('groups/update/{id}', 'GroupsController@update');
Route::get('groups/delete/{id}', 'GroupsController@destroy');
Route::get('groups/edit/{id}', 'GroupsController@edit');
});

/*
* accounts routes
*/

Route::group(['before' => 'process_payroll'], function() {

Route::resource('accounts', 'AccountsController');
Route::post('accounts/update/{id}', 'AccountsController@update');
Route::get('accounts/delete/{id}', 'AccountsController@destroy');
Route::get('accounts/edit/{id}', 'AccountsController@edit');
Route::get('accounts/show/{id}', 'AccountsController@show');
Route::get('accounts/create/{id}', 'AccountsController@create');

});


/*
* journals routes
*/
Route::resource('journals', 'JournalsController');
Route::post('journals/update/{id}', 'JournalsController@update');
Route::get('journals/delete/{id}', 'JournalsController@destroy');
Route::get('journals/edit/{id}', 'JournalsController@edit');
Route::get('journals/show/{id}', 'JournalsController@show');


Route::resource('expenses', 'ExpensesController');
Route::get('expenses/edit/{id}', 'ExpensesController@edit');
Route::post('expenses/update/{id}', 'ExpensesController@update');
Route::get('expenses/delete/{id}', 'ExpensesController@destroy');
Route::get('expenses/pay/{id}', 'ExpensesController@payBillView');
Route::post('expenses/payBill', 'ExpensesController@payBill');



/**
 * Bank Account Routes &
 * Bank Reconciliation Routes
 */
Route::resource('bankAccounts', 'BankAccountController');
Route::get('bankAccounts/reconcile/{id}', 'BankAccountController@showReconcile');
Route::post('bankAccounts/uploadStatement', 'BankAccountController@uploadBankStatement');
Route::post('bankAccount/reconcile', 'BankAccountController@reconcileStatement');

Route::get('bankAccount/reconcile/add/{id}/{id2}/{id3}', 'BankAccountController@addStatementTransaction');
Route::post('bankAccount/reconcile/add', 'BankAccountController@saveStatementTransaction');

Route::get('bankReconciliation/report', 'ErpReportsController@displayRecOptions');
Route::post('bankReconciliartion/generateReport', 'ErpReportsController@showRecReport');


/* PETTY CASH ROUTES */
Route::resource('petty_cash', 'PettyCashController');
Route::post('petty_cash/addMoney', 'PettyCashController@addMoney');
Route::post('petty_cash/addContribution', 'PettyCashController@addContribution');
Route::post('petty_cash/newTransaction', 'PettyCashController@newTransaction');
Route::post('petty_cash/commitTransaction', 'PettyCashController@commitTransaction');
Route::get('petty_cash/transaction/{id}', 'PettyCashController@receiptTransactions');

// Edit and delete petty cash items
Route::get('petty_cash/newTransaction/remove/{count}', 'PettyCashController@removeTransactionItem');


/* EXPENSE CLAIMS ROUTES */
Route::resource('expense_claims', 'ExpenseClaimController');
Route::get('expense_claims/newReceipt', 'ExpenseClaimController@show');
Route::get('expense_claims/editReceipt/{id}', 'ExpenseClaimController@edit');
Route::post('expense_claims/newItem', 'ExpenseClaimController@addReceiptItem');
Route::get('expense_claims/newReceipt/remove/{count}', 'ExpenseClaimController@removeItem');
Route::post('expense_claims/commitTransaction', 'ExpenseClaimController@commitTransaction');
Route::post('expense_claims/submitClaim', 'ExpenseClaimController@submitClaim');
Route::get('expense_claims/approveClaim/{id}', 'ExpenseClaimController@approveClaimView');
Route::get('expense_claims/approve/{id}', 'ExpenseClaimController@approveClaim');
Route::get('expense_claims/decline/{id}', 'ExpenseClaimController@declineClaim');
Route::get('expense_claims/payClaim/{id}', 'ExpenseClaimController@payClaimView');
Route::post('expense_claims/payClaim', 'ExpenseClaimController@payClaim');



/*
* license routes
*/

Route::post('license/key', 'OrganizationsController@generate_license_key');
Route::post('license/activate', 'OrganizationsController@activate_license');
Route::get('license/activate/{id}', 'OrganizationsController@activate_license_form');

/*
* Audits routes
*/
Route::group(['before' => 'manage_audits'], function() {

Route::resource('audits', 'AuditsController');

});

/*
* backups routes
*/

Route::get('backups', function(){

   
    //$backups = Backup::getRestorationFiles('../app/storage/backup/');

    return View::make('backup');

});


Route::get('backups/create', function(){

    echo '<pre>';

    $instance = Backup::getBackupEngineInstance();

    print_r($instance);

    //Backup::setPath(public_path().'/backups/');

   //Backup::export();
    //$backups = Backup::getRestorationFiles('../app/storage/backup/');

    //return View::make('backup');

});







/*
* #####################################################################################################################
*/
Route::group(['before' => 'manage_holiday'], function() {

Route::resource('holidays', 'HolidaysController');
Route::get('holidays/edit/{id}', 'HolidaysController@edit');
Route::get('holidays/delete/{id}', 'HolidaysController@destroy');
Route::post('holidays/update/{id}', 'HolidaysController@update');

});

Route::group(['before' => 'manage_leavetype'], function() {

Route::resource('leavetypes', 'LeavetypesController');
Route::get('leavetypes/edit/{id}', 'LeavetypesController@edit');
Route::get('leavetypes/delete/{id}', 'LeavetypesController@destroy');
Route::post('leavetypes/update/{id}', 'LeavetypesController@update');

});


Route::resource('leaveapplications', 'LeaveapplicationsController');
Route::get('leaveapplications/edit/{url}/{id}', 'LeaveapplicationsController@edit');
Route::get('leaveapplications/delete/{id}', 'LeaveapplicationsController@destroy');
Route::post('leaveapplications/update/{id}', 'LeaveapplicationsController@update');
Route::get('leaveapplications/approve/{url}/{id}', 'LeaveapplicationsController@approve');
Route::get('employeeleave/view/{id}', 'LeaveapplicationsController@cssleaveapprove');

Route::post('leaveapplications/approve/{id}', 'LeaveapplicationsController@doapprove');
Route::get('supervisorapproval/{id}', 'LeaveapplicationsController@supervisorapprove');
Route::get('supervisorreject/{id}', 'LeaveapplicationsController@supervisorreject');
Route::get('leaveapplications/cancel/{url}/{id}', 'LeaveapplicationsController@cancel');
Route::get('leaveapplications/reject/{url}/{id}', 'LeaveapplicationsController@reject');
Route::get('leaveapplications/show/{id}', 'LeaveapplicationsController@show');

Route::get('leaveapplications/approvals', 'LeaveapplicationsController@approvals');
Route::get('leaveapplications/rejects', 'LeaveapplicationsController@rejects');
Route::get('leaveapplications/cancellations', 'LeaveapplicationsController@cancellations');
Route::get('leaveapplications/amends', 'LeaveapplicationsController@amended');


Route::get('leaveapplicationenddate', function(){

   

    $enddate = date('Y-m-d');

    return json_encode($enddate);

});


Route::get('leaveapprovals', function(){

  $leaveapplications = Leaveapplication::where('status','approved')->orderBy('date_approved', 'desc')->get();

  return View::make('leaveapplications.approved', compact('leaveapplications'));

} );

Route::group(['before' => 'amend_application'], function() {

Route::get('leaveamends', function(){

  $leaveapplications = Leaveapplication::orderBy('date_amended', 'desc')->get();

  return View::make('leaveapplications.amended', compact('leaveapplications'));

} );

});

Route::group(['before' => 'reject_application'], function() {

Route::get('leaverejects', function(){

  $leaveapplications = Leaveapplication::orderBy('date_rejected', 'desc')->get();

  return View::make('leaveapplications.rejected', compact('leaveapplications'));

} );

});

Route::group(['before' => 'manage_settings'], function() {

Route::get('migrate', function(){

    return View::make('migration');

});

});


/*
* Template routes and generators 
*/


Route::get('template/employees', function(){

  $bank_data = Bank::all();

  $data = Employee::all();

  $employees = Employee::all();

  $bankbranch_data = BBranch::all();
 
  $branch_data = Branch::all();

  $department_data = Department::all();

  $employeetype_data = EType::all();

  $jobgroup_data = Jobgroup::all();

  Excel::create('Employees', function($excel) use($bank_data, $bankbranch_data, $branch_data, $department_data, $employeetype_data, $jobgroup_data,$employees, $data) {


    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

    

    $excel->sheet('employees', function($sheet) use($bank_data, $bankbranch_data, $branch_data, $department_data, $employeetype_data, $jobgroup_data, $data, $employees){


              $sheet->row(1, array(
     'EMPLOYMENT NUMBER','FIRST NAME', 'SURNAME', 'OTHER NAMES', 'ID NUMBER', 'KRA PIN', 'NSSF NUMBER', 'NHIF NUMBER','EMAIL ADDRESS','BASIC PAY'
));

             
                $empdata = array();

                foreach($employees as $d){

                  $empdata[] = $d->personal_file_number.':'.$d->first_name.' '.$d->last_name.' '.$d->middle_name;
                }

                $emplist = implode(", ", $empdata);

                

                $listdata = array();

                foreach($data as $d){

                  $listdata[] = $d->allowance_name;
                }

                $list = implode(", ", $listdata);

    });

  })->export('xls');
});

Route::get('template/items', function(){

  $locations = Location::all();

  $categories = Itemcategory::all();

  Excel::create('Items', function($excel) use($locations,$categories) {


    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

    

    $excel->sheet('items', function($sheet) use($locations,$categories){


              $sheet->row(1, array(
     'NAME','DESCRIPTION', 'PURCHASE PRICE', 'SELLING PRICE', 'LOCATION','DURATION','CATEGORY','STORE KEEPING UNIT', 'TAG ID', 'REORDER LEVEL'
));

               $sheet->row(1, function($cell) {

               // manipulate the cell
                $cell->setFontWeight('bold');

              });

               $sheet->setWidth(array(
                    'A'     =>  30,
                    'B'     =>  30,
                    'C'     =>  30,
                    'D'     =>  30,
                    'E'     =>  30,
                    'F'     =>  30,
                    'G'     =>  30,
                    'H'     =>  30,
                    'I'     =>  30,
                    'J'     =>  30,
              ));
             
                $locdata = array();

                foreach($locations as $d){

                  $locdata[] = $d->name;
                }

                $loclist = implode(", ", $locdata);

               $catdata = array();

                foreach($categories as $d){

                  $catdata[] = $d->name;
                }

                $catlist = implode(", ", $catdata);


                $row = 2;
                $r = 2;
            
            for($i = 0; $i<count($locations); $i++){
            
             $sheet->SetCellValue("YY".$row, $locations[$i]->name);
             $row++;
            }  

                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange(
                        'names', $sheet, 'YY2:YY'.(count($locations)+1)
                        )
                );

                for($i = 0; $i<count($categories); $i++){
            
             $sheet->SetCellValue("YZ".$r, $categories[$i]->name);
             $r++;
            }  

                $sheet->_parent->addNamedRange(
                        new \PHPExcel_NamedRange(
                        'categories', $sheet, 'YZ2:YZ'.(count($categories)+1)
                        )
                );
   

    for($i=2; $i <= 1000; $i++){
                $objValidation = $sheet->getCell('E'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('names'); //note this!

                $objValidation = $sheet->getCell('G'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('categories'); //note this!
                }

    });

  })->export('xlsx');

});

Route::get('template/assetregister', function(){

  Excel::create('Asset Register', function($excel) {


    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

    

    $excel->sheet('Assets', function($sheet){


              $sheet->row(1, array(
     'DATE','QUANTITY', 'ASSET SERIAL NUMBER', 'DESCRIPTION', 'UNIT PRICE','AMOUNT','ASSET TYPE'
));

              $sheet->row(1, function($cell) {

               // manipulate the cell
                $cell->setFontWeight('bold');

              });


               $sheet->setWidth(array(
                    'A'     =>  30,
                    'B'     =>  30,
                    'C'     =>  30,
                    'D'     =>  30,
                    'E'     =>  30,
                    'F'     =>  30,
                    'G'     =>  30,
              ));
             

    });

  })->export('xlsx');

});

/*
*allowance template
*
*/

Route::get('template/allowances', function(){

  $data = Allowance::all();
  $employees = Employee::all();


  Excel::create('Allowances', function($excel) use($data, $employees) {

    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

    

    $excel->sheet('allowances', function($sheet) use($data, $employees){


              $sheet->row(1, array(
     'EMPLOYEE', 'ALLOWANCE TYPE', 'AMOUNT'
));

             
                $empdata = array();

                foreach($employees as $d){

                  $empdata[] = $d->personal_file_number.':'.$d->first_name.' '.$d->last_name.' '.$d->middle_name;
                }

                $emplist = implode(", ", $empdata);

                

                $listdata = array();

                foreach($data as $d){

                  $listdata[] = $d->allowance_name;
                }

                $list = implode(", ", $listdata);
   

    for($i=2; $i <= 250; $i++){

                $objValidation = $sheet->getCell('B'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.$list.'"'); //note this!



                $objValidation = $sheet->getCell('A'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.$emplist.'"'); //note this!

    }

                

                
        

    });

  })->export('xls');



});

/*
*earning template
*
*/

Route::get('template/earnings', function(){

  $employees = Employee::all();


  Excel::create('Earnings', function($excel) use($employees) {

    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

    

    $excel->sheet('earnings', function($sheet) use($employees){


              $sheet->row(1, array(
     'EMPLOYEE', 'EARNING TYPE','NARRATIVE', 'AMOUNT'
));

             
                $empdata = array();

                foreach($employees as $d){

                  $empdata[] = $d->personal_file_number.':'.$d->first_name.' '.$d->last_name.' '.$d->middle_name;
                }

                $emplist = implode(", ", $empdata);

                
   

    for($i=2; $i <= 250; $i++){

                $objValidation = $sheet->getCell('B'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"Bonus, Commission, Others"'); //note this!
                
                

                $objValidation = $sheet->getCell('A'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.$emplist.'"'); //note this!

    }

                

                
        

    });

  })->export('xls');



});

/*
*Relief template
*
*/

Route::get('template/reliefs', function(){

  $employees = Employee::all();
  
  $data = Relief::all();

  Excel::create('Reliefs', function($excel) use($employees, $data) {

    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

    

    $excel->sheet('reliefs', function($sheet) use($employees, $data){


              $sheet->row(1, array(
     'EMPLOYEE', 'RELIEF TYPE', 'AMOUNT'
));

             
                $empdata = array();

                foreach($employees as $d){

                  $empdata[] = $d->personal_file_number.':'.$d->first_name.' '.$d->last_name.' '.$d->middle_name;
                }

                $emplist = implode(", ", $empdata);

                
                $listdata = array();

                foreach($data as $d){

                  $listdata[] = $d->relief_name;
                }

                $list = implode(", ", $listdata);
   

    for($i=2; $i <= 250; $i++){

                $objValidation = $sheet->getCell('B'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.$list.'"'); //note this!



                $objValidation = $sheet->getCell('A'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.$emplist.'"'); //note this!

    }

                

                
        

    });

  })->export('xls');



});



/*
*deduction template
*
*/

Route::get('template/deductions', function(){

  $data = Deduction::all();
  $employees = Employee::all();


  Excel::create('Deductions', function($excel) use($data, $employees) {

    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
    require_once(base_path()."/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

    

    $excel->sheet('deductions', function($sheet) use($data, $employees){


              $sheet->row(1, array(
     'EMPLOYEE', 'DEDUCTION TYPE', 'AMOUNT','Date'
));

             
                $empdata = array();

                foreach($employees as $d){

                  $empdata[] = $d->personal_file_number.':'.$d->first_name.' '.$d->last_name.' '.$d->middle_name;
                }

                $emplist = implode(", ", $empdata);

                

                $listdata = array();

                foreach($data as $d){

                  $listdata[] = $d->deduction_name;
                }

                $list = implode(", ", $listdata);
   

    for($i=2; $i <= 250; $i++){

                $objValidation = $sheet->getCell('B'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.$list.'"'); //note this!



                $objValidation = $sheet->getCell('A'.$i)->getDataValidation();
                $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pick from list');
                $objValidation->setPrompt('Please pick a value from the drop-down list.');
                $objValidation->setFormula1('"'.$emplist.'"'); //note this!

    }

                

                
        

    });

  })->export('xls');



});



/* #################### IMPORT EMPLOYEES ################################## */

Route::post('import/employees', function(){

  
  if(Input::hasFile('employees')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('employees')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;      
     
      Input::file('employees')->move($destination, $file);


  


    Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

          $results = $reader->get();    
  
    foreach ($results as $result) {



      $employee = new Employee;

      $employee->personal_file_number = $result->employment_number;
      
      $employee->first_name = $result->first_name;
      $employee->last_name = $result->surname;
      $employee->middle_name = $result->other_names;
      $employee->identity_number = $result->id_number;
      $employee->pin = $result->kra_pin;
      $employee->social_security_number = $result->nssf_number;
      $employee->hospital_insurance_number = $result->nhif_number;
      $employee->email_office = $result->email_address;
      $employee->basic_pay = str_replace( ',', '', $result->basic_pay);
      $employee->save();
      
    }
    

    

  });



      
    }



  return Redirect::back()->with('notice', 'Employees have been succeffully imported');



  

});

Route::post('import/items', function(){

  
  if(Input::hasFile('items')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('items')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;      
     
      Input::file('items')->move($destination, $file);


    Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

          $results = $reader->get();   

      foreach ($results as $result) {
      if($result->name != ''){
      
      $location = DB::table('locations')->where('name', '=', $result->location)->pluck('id');
      $item = new Item;
      $item->name = $result->name;
      $item->description = $result->description;
      $item->purchase_price = $result->purchase_price;
      $item->selling_price = $result->selling_price;
      $item->sku = $result->store_keeping_unit;
      $item->tag_id = $result->tag_id;
      $item->category = $result->category;
      $item->location_id = $location;
      $item->duration = $result->duration;
      $item->reorder_level = $result->reorder_level;
      $item->save();
    }
      
    }

  });
  
  }



  return Redirect::back()->with('notice', 'Items have been succefully imported');



  

});

Route::post('import/assetregister', function(){

  
  if(Input::hasFile('asset')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('asset')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;      
     
      Input::file('asset')->move($destination, $file);


    Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

          $results = $reader->get(); 

      foreach ($results as $result) {
      $asset = new Asset;
      $asset->name = $result->description;
      $asset->purchase_date = $result->date;
      $asset->serial_number = $result->asset_serial_number;
      $asset->quantity = $result->quantity;
      if($result->unit_price == ''){
      $asset->cost = $result->amount/$result->quantity;
      }else{
      $asset->cost = $result->unit_price;
      }
      $asset->asset_type = $result->asset_type;
      $asset->save();
      
    }

  });
  
  }

  return Redirect::back()->with('notice', 'Assets have been succefully imported');

});


/* #################### IMPORT EARNINGS ################################## */

Route::post('import/earnings', function(){

  
  if(Input::hasFile('earnings')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('earnings')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;

     
      Input::file('earnings')->move($destination, $file);


    Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

          $results = $reader->get();    
  
    foreach ($results as $result) {

    $name = explode(':', $result->employee);

    
    $employeeid = DB::table('employee')->where('personal_file_number', '=', $name[0])->pluck('id');

    $earning = new Earnings;

    $earning->employee_id = $employeeid;

    $earning->earnings_name = $result->earning_type;

    $earning->narrative = $result->narrative;

    $earning->earnings_amount = $result->amount;

    $earning->save();
      
    }
    

    

  });



      
    }



  return Redirect::back()->with('notice', 'earnings have been succeffully imported');



  

});


/* #################### IMPORT RELIEFS ################################## */

Route::post('import/reliefs', function(){

  
  if(Input::hasFile('reliefs')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('reliefs')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;

     
      Input::file('reliefs')->move($destination, $file);


    Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

          $results = $reader->get();    
  
    foreach ($results as $result) {

    $name = explode(':', $result->employee);

    
    $employeeid = DB::table('employee')->where('personal_file_number', '=', $name[0])->pluck('id');

    $reliefid = DB::table('relief')->where('relief_name', '=', $result->relief_type)->pluck('id');

    $relief = new ERelief;

    $relief->employee_id = $employeeid;

    $relief->relief_id = $reliefid;

    $relief->relief_amount = $result->amount;

    $relief->save();
      
    }
    

    

  });



      
    }



  return Redirect::back()->with('notice', 'reliefs have been succeffully imported');



  

});



/* #################### IMPORT ALLOWANCES ################################## */

Route::post('import/allowances', function(){

  
  if(Input::hasFile('allowances')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('allowances')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;



      
      
     
      Input::file('allowances')->move($destination, $file);


  


  Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

    $results = $reader->get();    
  
    foreach ($results as $result) {

    $name = explode(':', $result->employee);

    

    
    $employeeid = DB::table('employee')->where('personal_file_number', '=', $name[0])->pluck('id');

    $allowanceid = DB::table('allowances')->where('allowance_name', '=', $result->allowance_type)->pluck('id');

    $allowance = new EAllowances;

    $allowance->employee_id = $employeeid;

    $allowance->allowance_id = $allowanceid;

    $allowance->allowance_amount = $result->amount;

    $allowance->save();

    
      
    }
    

    

  });



      
    }



  return Redirect::back()->with('notice', 'allowances have been succefully imported');



  

});


/* #################### IMPORT DEDUCTIONS ################################## */

Route::post('import/deductions', function(){

  
  if(Input::hasFile('deductions')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('deductions')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;



      
      
     
      Input::file('deductions')->move($destination, $file);


  


  Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

    $results = $reader->get();    
  
    foreach ($results as $result) {

    $name = explode(':', $result->employee);

    

    
    $employeeid = DB::table('employee')->where('personal_file_number', '=', $name[0])->pluck('id');

    $deductionid = DB::table('deductions')->where('deduction_name', '=', $result->deduction_type)->pluck('id');

    $deduction = new EDeduction;

    $deduction->employee_id = $employeeid;

    $deduction->deduction_id = $deductionid;

    $deduction->deduction_amount = $result->amount;

    $deduction->deduction_date = $result->date;

    $deduction->save();

    
      
    }
    

    

  });



      
    }



  return Redirect::back()->with('notice', 'deductions have been succefully imported');



  

});



/* #################### IMPORT BANK BRANCHES ################################## */

Route::post('import/bankBranches', function(){

  
  if(Input::hasFile('bbranches')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('bbranches')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;



      
      
     
      Input::file('bbranches')->move($destination, $file);


  


  Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

    $results = $reader->get();    
  
    foreach ($results as $result) {
  

    $bbranch = new BBranch;

    $bbranch->branch_code = $result->branch_code;

    $bbranch->bank_branch_name = $result->branch_name;

    $bbranch->bank_id = $result->bank_id;

    $bbranch->organization_id = $result->organization_id;

    $bbranch->save();
      
    }   

  });
      
    }


  return Redirect::back()->with('notice', 'bank branches have been succefully imported');



  

});

/* #################### IMPORT BANKS ################################## */

Route::post('import/banks', function(){

  
  if(Input::hasFile('banks')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('banks')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;



      
      
     
      Input::file('banks')->move($destination, $file);


  


  Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

    $results = $reader->get();    
  
    foreach ($results as $result) {
  

    $bank = new Bank;

    $bank->bank_name = $result->bank_name;

    $bank->bank_code = $result->bank_code;

    $bank->organization_id = $result->organization_id;

    $bank->save();
      
    }   

  });
      
    }


  return Redirect::back()->with('notice', 'banks have been succefully imported');



  

});



/*
* #####################################################################################################################
*/
/*
* banks routes
*/

Route::resource('banks', 'BanksController');
Route::post('banks/update/{id}', 'BanksController@update');
Route::get('banks/delete/{id}', 'BanksController@destroy');
Route::get('banks/edit/{id}', 'BanksController@edit');

/*
* departments routes
*/

Route::resource('departments', 'DepartmentsController');
Route::post('departments/update/{id}', 'DepartmentsController@update');
Route::get('departments/delete/{id}', 'DepartmentsController@destroy');
Route::get('departments/edit/{id}', 'DepartmentsController@edit');


/*
* bank branch routes
*/

Route::resource('bank_branch', 'BankBranchController');
Route::post('bank_branch/update/{id}', 'BankBranchController@update');
Route::get('bank_branch/delete/{id}', 'BankBranchController@destroy');
Route::get('bank_branch/edit/{id}', 'BankBranchController@edit');

/*
* allowances routes
*/

Route::resource('allowances', 'AllowancesController');
Route::post('allowances/update/{id}', 'AllowancesController@update');
Route::get('allowances/delete/{id}', 'AllowancesController@destroy');
Route::get('allowances/edit/{id}', 'AllowancesController@edit');

/*
* occurence settings routes
*/

Route::resource('occurencesettings', 'OccurencesettingsController');
Route::post('occurencesettings/update/{id}', 'OccurencesettingsController@update');
Route::get('occurencesettings/delete/{id}', 'OccurencesettingsController@destroy');
Route::get('occurencesettings/edit/{id}', 'OccurencesettingsController@edit');

/*
* benefits setting routes
*/

Route::resource('benefitsettings', 'BenefitSettingsController');
Route::post('benefitsettings/update/{id}', 'BenefitSettingsController@update');
Route::get('benefitsettings/delete/{id}', 'BenefitSettingsController@destroy');
Route::get('benefitsettings/edit/{id}', 'BenefitSettingsController@edit');

/*
* reliefs routes
*/

Route::resource('reliefs', 'ReliefsController');
Route::post('reliefs/update/{id}', 'ReliefsController@update');
Route::get('reliefs/delete/{id}', 'ReliefsController@destroy');
Route::get('reliefs/edit/{id}', 'ReliefsController@edit');

/*
* deductions routes
*/

Route::resource('deductions', 'DeductionsController');
Route::post('deductions/update/{id}', 'DeductionsController@update');
Route::get('deductions/delete/{id}', 'DeductionsController@destroy');
Route::get('deductions/edit/{id}', 'DeductionsController@edit');

/*
* nssf routes
*/

Route::resource('nssf', 'NssfController');
Route::post('nssf/update/{id}', 'NssfController@update');
Route::get('nssf/delete/{id}', 'NssfController@destroy');
Route::get('nssf/edit/{id}', 'NssfController@edit');

/*
* nhif routes
*/

Route::resource('nhif', 'NhifController');
Route::post('nhif/update/{id}', 'NhifController@update');
Route::get('nhif/delete/{id}', 'NhifController@destroy');
Route::get('nhif/edit/{id}', 'NhifController@edit');

/*
* job group routes
*/

Route::resource('job_group', 'JobGroupController');
Route::post('job_group/update/{id}', 'JobGroupController@update');
Route::get('job_group/delete/{id}', 'JobGroupController@destroy');
Route::get('job_group/edit/{id}', 'JobGroupController@edit');
Route::get('job_group/show/{id}', 'JobGroupController@show');

/*
* employee type routes
*/

Route::resource('employee_type', 'EmployeeTypeController');
Route::post('employee_type/update/{id}', 'EmployeeTypeController@update');
Route::get('employee_type/delete/{id}', 'EmployeeTypeController@destroy');
Route::get('employee_type/edit/{id}', 'EmployeeTypeController@edit');

/*
* employees routes
*/

Route::get('deactives', function(){

  $employees = Employee::getDeactiveEmployee();

  return View::make('employees.activate', compact('employees'));

} );


Route::resource('employees', 'EmployeesController');
Route::post('employees/update/{id}', 'EmployeesController@update');
Route::get('employees/deactivate/{id}', 'EmployeesController@deactivate');
Route::get('employees/activate/{id}', 'EmployeesController@activate');
Route::get('employees/edit/{id}', 'EmployeesController@edit');
Route::get('employees/view/{id}', 'EmployeesController@view');
Route::get('employees/viewdeactive/{id}', 'EmployeesController@viewdeactive');

/*
* occurences routes
*/

Route::resource('occurences', 'OccurencesController');
Route::post('occurences/update/{id}', 'OccurencesController@update');
Route::get('occurences/delete/{id}', 'OccurencesController@destroy');
Route::get('occurences/edit/{id}', 'OccurencesController@edit');
Route::get('occurences/view/{id}', 'OccurencesController@view');
Route::get('occurences/download/{id}', 'OccurencesController@getDownload');
Route::get('occurences/create/{id}', 'OccurencesController@create');

/*
* occurences routes
*/

Route::resource('EmergencyContacts', 'EmergencyContactsController');
Route::post('EmergencyContacts/update/{id}', 'EmergencyContactsController@update');
Route::get('EmergencyContacts/delete/{id}', 'EmergencyContactsController@destroy');
Route::get('EmergencyContacts/edit/{id}', 'EmergencyContactsController@edit');
Route::get('EmergencyContacts/view/{id}', 'EmergencyContactsController@view');
Route::get('EmergencyContacts/create/{id}', 'EmergencyContactsController@create');


/*
* employee earnings routes
*/

Route::resource('other_earnings', 'EarningsController');
Route::post('other_earnings/update/{id}', 'EarningsController@update');
Route::get('other_earnings/delete/{id}', 'EarningsController@destroy');
Route::get('other_earnings/edit/{id}', 'EarningsController@edit');
Route::get('other_earnings/view/{id}', 'EarningsController@view');

/*
* employee nontaxables routes
*/

Route::resource('employeenontaxables', 'EmployeeNonTaxableController');
Route::post('employeenontaxables/update/{id}', 'EmployeeNonTaxableController@update');
Route::get('employeenontaxables/delete/{id}', 'EmployeeNonTaxableController@destroy');
Route::get('employeenontaxables/edit/{id}', 'EmployeeNonTaxableController@edit');
Route::get('employeenontaxables/view/{id}', 'EmployeeNonTaxableController@view');
Route::post('createNontaxable', 'EmployeeNonTaxableController@createnontaxable');

/*
* nontaxables routes
*/

Route::resource('nontaxables', 'NonTaxablesController');
Route::post('nontaxables/update/{id}', 'NonTaxablesController@update');
Route::get('nontaxables/delete/{id}', 'NonTaxablesController@destroy');
Route::get('nontaxables/edit/{id}', 'NonTaxablesController@edit');

/*
* employee reliefs routes
*/

Route::resource('employee_relief', 'EmployeeReliefController');
Route::post('employee_relief/update/{id}', 'EmployeeReliefController@update');
Route::get('employee_relief/delete/{id}', 'EmployeeReliefController@destroy');
Route::get('employee_relief/edit/{id}', 'EmployeeReliefController@edit');
Route::get('employee_relief/view/{id}', 'EmployeeReliefController@view');

/*
* employee allowances routes
*/

Route::resource('employee_allowances', 'EmployeeAllowancesController');
Route::post('employee_allowances/update/{id}', 'EmployeeAllowancesController@update');
Route::get('employee_allowances/delete/{id}', 'EmployeeAllowancesController@destroy');
Route::get('employee_allowances/edit/{id}', 'EmployeeAllowancesController@edit');
Route::get('employee_allowances/view/{id}', 'EmployeeAllowancesController@view');

/*
* employee deductions routes
*/

Route::resource('employee_deductions', 'EmployeeDeductionsController');
Route::post('employee_deductions/update/{id}', 'EmployeeDeductionsController@update');
Route::get('employee_deductions/delete/{id}', 'EmployeeDeductionsController@destroy');
Route::get('employee_deductions/edit/{id}', 'EmployeeDeductionsController@edit');
Route::get('employee_deductions/view/{id}', 'EmployeeDeductionsController@view');

/*
* payroll routes
*/

Route::get('payrollcalculator', function(){
  $currency = Currency::find(1);
  return View::make('payroll.payroll_calculator',compact('currency'));

});
Route::post('shownet', 'PayrollController@disp');
Route::post('showgross', 'PayrollController@dispgross');


Route::resource('payroll', 'PayrollController');
Route::post('deleterow', 'PayrollController@del_exist');
Route::post('showrecord', 'PayrollController@display');
Route::post('payroll/preview', 'PayrollController@create');


/*
* advance routes
*/


Route::resource('advance', 'AdvanceController');
Route::post('deleteadvance', 'AdvanceController@del_exist');
Route::post('showadvance', 'AdvanceController@display');
Route::post('advance/preview', 'AdvanceController@create');


/*
* employees routes
*/
Route::resource('employees', 'EmployeesController');
Route::get('employees/show/{id}', 'EmployeesController@show');
Route::group(['before' => 'create_employee'], function() {
Route::get('employees/create', 'EmployeesController@create');
});
Route::get('employees/edit/{id}', 'EmployeesController@edit');
Route::post('employees/update/{id}', 'EmployeesController@update');
Route::get('employees/delete/{id}', 'EmployeesController@destroy');


Route::get('advanceReports', function(){

    return View::make('employees.advancereports');
});


Route::get('payrollReports', function(){

    return View::make('employees.payrollreports');
});

Route::get('statutoryReports', function(){

    return View::make('employees.statutoryreports');
});

Route::get('email/payslip', 'payslipEmailController@index');
Route::post('email/payslip/employees', 'payslipEmailController@sendEmail');

Route::get('reports/employees', function(){

    return View::make('employees.reports');
});

Route::resource('earningsettings', 'EarningsettingsController');
Route::post('earningsettings/update/{id}', 'EarningsettingsController@update');
Route::get('earningsettings/delete/{id}', 'EarningsettingsController@destroy');
Route::get('earningsettings/edit/{id}', 'EarningsettingsController@edit');


Route::get('reports/selectEmployeeStatus', 'ReportsController@selstate');
Route::post('reports/employeelist', 'ReportsController@employees');
Route::get('employee/select', 'ReportsController@emp_id');
Route::post('reports/employee', 'ReportsController@individual');
Route::get('payrollReports/selectPeriod', 'ReportsController@period_payslip');
Route::post('payrollReports/payslip', 'ReportsController@payslip');
Route::get('payrollReports/selectAllowance', 'ReportsController@employee_allowances');
Route::post('payrollReports/allowances', 'ReportsController@allowances');
Route::get('payrollReports/selectDeduction', 'ReportsController@employee_deductions');
Route::post('payrollReports/deductions', 'ReportsController@deductions');
Route::get('payrollReports/selectPayePeriod', 'ReportsController@period_paye');
Route::get('payrollReports/selectnontaxableincome', 'ReportsController@employeenontaxableselect');
Route::post('payrollReports/nontaxables', 'ReportsController@employeenontaxables');
Route::post('payrollReports/payeReturns', 'ReportsController@payeReturns');
Route::get('payrollReports/selectRemittancePeriod', 'ReportsController@period_rem');
Route::post('payrollReports/payRemittances', 'ReportsController@payeRems');
Route::get('payrollReports/selectSummaryPeriod', 'ReportsController@period_summary');
Route::post('payrollReports/payrollSummary', 'ReportsController@paySummary');
Route::get('payrollReports/selectNssfPeriod', 'ReportsController@period_nssf');
Route::post('payrollReports/nssfReturns', 'ReportsController@nssfReturns');
Route::get('payrollReports/selectNhifPeriod', 'ReportsController@period_nhif');
Route::post('payrollReports/nhifReturns', 'ReportsController@nhifReturns');
Route::get('payrollReports/selectNssfExcelPeriod', 'ReportsController@period_excel');
Route::post('payrollReports/nssfExcel', 'ReportsController@export');
Route::get('reports/selectEmployeeOccurence', 'ReportsController@selEmp');
Route::post('reports/occurence', 'ReportsController@occurence');
Route::get('reports/CompanyProperty/selectPeriod', 'ReportsController@propertyperiod');
Route::post('reports/companyproperty', 'ReportsController@property');
Route::get('reports/Appraisals/selectPeriod', 'ReportsController@appraisalperiod');
Route::post('reports/appraisal', 'ReportsController@appraisal');
Route::get('reports/nextofkin/selectEmployee', 'ReportsController@selempkin');
Route::post('reports/EmployeeKin', 'ReportsController@kin');

Route::get('advanceReports/selectRemittancePeriod', 'ReportsController@period_advrem');
Route::post('advanceReports/advanceRemittances', 'ReportsController@payeAdvRems');
Route::get('advanceReports/selectSummaryPeriod', 'ReportsController@period_advsummary');
Route::post('advanceReports/advanceSummary', 'ReportsController@payAdvSummary');

/*
*#################################################################
*/
Route::group(['before' => 'process_payroll'], function() {

    


Route::get('payrollmgmt', function(){

     $employees = Employee::getActiveEmployee();

  return View::make('payrollmgmt', compact('employees'));

});

});

Route::group(['before' => 'leave_mgmt'], function() {

Route::get('leavemgmt', function(){

  $leaveapplications = Leaveapplication::orderBy('application_date', 'desc')->get();

  return View::make('leavemgmt', compact('leaveapplications'));

});

});


Route::get('erpmgmt', function(){

  return View::make('erpmgmt');

});



Route::get('cbsmgmt', function(){


      if(Confide::user()->user_type == 'admin'){

            $members = Member::all();

            //print_r($members);

            return View::make('cbsmgmt', compact('members'));

        } 

        if(Confide::user()->user_type == 'teller'){

            $members = Member::all();

            return View::make('tellers.dashboard', compact('members'));

        } 


        if(Confide::user()->user_type == 'member'){

            $loans = Loanproduct::all();
            $products = Product::all();

            $rproducts = Product::getRemoteProducts();

            
            return View::make('shop.index', compact('loans', 'products', 'rproducts'));

        } 


  



});





/*
* #####################################################################################################################
*/









Route::get('import', function(){

    return View::make('import');
});

Route::get('items/import', function(){

    return View::make('items.import');
});

Route::get('asset/import', function(){

    return View::make('assets.import');
});


Route::get('automated/loans', function(){

    
    $loanproducts = Loanproduct::all();

    return View::make('autoloans', compact('loanproducts'));
});

Route::get('automated/savings', function(){

    
   $savingproducts = Savingproduct::all();

    return View::make('automated', compact('savingproducts'));
});



Route::post('automated', function(){

    $members = DB::table('members')->where('is_active', '=', true)->get();


    $category = Input::get('category');


    
    
    if($category == 'savings'){

        $savingproduct_id = Input::get('savingproduct');

        $savingproduct = Savingproduct::findOrFail($savingproduct_id);

        

            foreach($savingproduct->savingaccounts as $savingaccount){

                if(($savingaccount->member->is_active) && (Savingaccount::getLastAmount($savingaccount) > 0)){

                    
                    $data = array(
                        'account_id' => $savingaccount->id,
                        'amount' => Savingaccount::getLastAmount($savingaccount), 
                        'date' => date('Y-m-d'),
                        'type'=>'credit'
                        );

                    Savingtransaction::creditAccounts($data);
                    

                    

                }
 
                

            

    }

       Autoprocess::record(date('Y-m-d'), 'saving', $savingproduct); 
      

        

    } else {

        $loanproduct_id = Input::get('loanproduct');

        $loanproduct = Loanproduct::findOrFail($loanproduct_id);


        

        

            foreach($loanproduct->loanaccounts as $loanaccount){

                if(($loanaccount->member->is_active) && (Loanaccount::getEMP($loanaccount) > 0)){

                    
                    
                    $data = array(
                        'loanaccount_id' => $loanaccount->id,
                        'amount' => Loanaccount::getEMP($loanaccount), 
                        'date' => date('Y-m-d')
                        
                        );


                    Loanrepayment::repayLoan($data);
                    

                    
                   

                    

                }
            }


             Autoprocess::record(date('Y-m-d'), 'loan', $loanproduct);
            

    }


    

    return Redirect::back()->with('notice', 'successfully processed');
    

    
});






Route::get('loanrepayments/offprint/{id}', 'LoanrepaymentsController@offprint');



Route::resource('members', 'MembersController');
Route::post('members/update/{id}', 'MembersController@update');
Route::get('members/delete/{id}', 'MembersController@destroy');
Route::get('members/edit/{id}', 'MembersController@edit');

Route::get('members/show/{id}', 'MembersController@show');
Route::get('members/loanaccounts/{id}', 'MembersController@loanaccounts');
Route::get('memberloans', 'MembersController@loanaccounts2');
Route::group(['before' => 'limit'], function() {

    Route::get('members/create', 'MembersController@create');
});

Route::resource('kins', 'KinsController');
Route::post('kins/update/{id}', 'KinsController@update');
Route::get('kins/delete/{id}', 'KinsController@destroy');
Route::get('kins/edit/{id}', 'KinsController@edit');
Route::get('kins/show/{id}', 'KinsController@show');
Route::get('kins/create/{id}', 'KinsController@create');





Route::resource('charges', 'ChargesController');
Route::post('charges/update/{id}', 'ChargesController@update');
Route::get('charges/delete/{id}', 'ChargesController@destroy');
Route::get('charges/edit/{id}', 'ChargesController@edit');
Route::get('charges/show/{id}', 'ChargesController@show');
Route::get('charges/disable/{id}', 'ChargesController@disable');
Route::get('charges/enable/{id}', 'ChargesController@enable');

Route::resource('savingproducts', 'SavingproductsController');
Route::post('savingproducts/update/{id}', 'SavingproductsController@update');
Route::get('savingproducts/delete/{id}', 'SavingproductsController@destroy');
Route::get('savingproducts/edit/{id}', 'SavingproductsController@edit');
Route::get('savingproducts/show/{id}', 'SavingproductsController@show');




Route::resource('savingaccounts', 'SavingaccountsController');
Route::get('savingaccounts/create/{id}', 'SavingaccountsController@create');
Route::get('member/savingaccounts/{id}', 'SavingaccountsController@memberaccounts');



Route::get('savingtransactions/show/{id}', 'SavingtransactionsController@show');
Route::resource('savingtransactions', 'SavingtransactionsController');
Route::get('savingtransactions/create/{id}', 'SavingtransactionsController@create');
Route::get('savingtransactions/receipt/{id}', 'SavingtransactionsController@receipt');
Route::get('savingtransactions/statement/{id}', 'SavingtransactionsController@statement');

Route::post('savingtransactions/import', 'SavingtransactionsController@import');

//Route::resource('savingpostings', 'SavingpostingsController');



Route::resource('shares', 'SharesController');
Route::post('shares/update/{id}', 'SharesController@update');
Route::get('shares/delete/{id}', 'SharesController@destroy');
Route::get('shares/edit/{id}', 'SharesController@edit');
Route::get('shares/show/{id}', 'SharesController@show');



Route::get('sharetransactions/show/{id}', 'SharetransactionsController@show');
Route::resource('sharetransactions', 'SharetransactionsController');
Route::get('sharetransactions/create/{id}', 'SharetransactionsController@create');





Route::post('license/key', 'OrganizationsController@generate_license_key');
Route::post('license/activate', 'OrganizationsController@activate_license');
Route::get('license/activate/{id}', 'OrganizationsController@activate_license_form');



Route::resource('loanproducts', 'LoanproductsController');
Route::post('loanproducts/update/{id}', 'LoanproductsController@update');
Route::get('loanproducts/delete/{id}', 'LoanproductsController@destroy');
Route::get('loanproducts/edit/{id}', 'LoanproductsController@edit');
Route::get('loanproducts/show/{id}', 'LoanproductsController@show');



Route::resource('loanguarantors', 'LoanguarantorsController');
Route::post('loanguarantors/update/{id}', 'LoanguarantorsController@update');
Route::get('loanguarantors/delete/{id}', 'LoanguarantorsController@destroy');
Route::get('loanguarantors/edit/{id}', 'LoanguarantorsController@edit');
Route::get('loanguarantors/create/{id}', 'LoanguarantorsController@create');
Route::get('loanguarantors/css/{id}', 'LoanguarantorsController@csscreate');

Route::post('loanguarantors/cssupdate/{id}', 'LoanguarantorsController@cssupdate');
Route::get('loanguarantors/cssdelete/{id}', 'LoanguarantorsController@cssdestroy');
Route::get('loanguarantors/cssedit/{id}', 'LoanguarantorsController@cssedit');



Route::resource('loans', 'LoanaccountsController');
Route::get('loans/apply/{id}', 'LoanaccountsController@apply');
Route::post('loans/apply', 'LoanaccountsController@doapply');
Route::post('loans/application', 'LoanaccountsController@doapply2');


Route::get('loantransactions/statement/{id}', 'LoantransactionsController@statement');
Route::get('loantransactions/receipt/{id}', 'LoantransactionsController@receipt');

Route::get('loans/application/{id}', 'LoanaccountsController@apply2');
Route::post('shopapplication', 'LoanaccountsController@shopapplication');

Route::get('loans/edit/{id}', 'LoanaccountsController@edit');
Route::post('loans/update/{id}', 'LoanaccountsController@update');

Route::get('loans/approve/{id}', 'LoanaccountsController@approve');
Route::post('loans/approve/{id}', 'LoanaccountsController@doapprove');


Route::get('loans/reject/{id}', 'LoanaccountsController@reject');
Route::post('loans/reject/{id}', 'LoanaccountsController@doreject');

Route::get('loans/disburse/{id}', 'LoanaccountsController@disburse');
Route::post('loans/disburse/{id}', 'LoanaccountsController@dodisburse');

Route::get('loans/show/{id}', 'LoanaccountsController@show');

Route::post('loans/amend/{id}', 'LoanaccountsController@amend');

Route::get('loans/reject/{id}', 'LoanaccountsController@reject');
Route::post('loans/reject/{id}', 'LoanaccountsController@rejectapplication');


Route::get('loanaccounts/topup/{id}', 'LoanaccountsController@gettopup');
Route::post('loanaccounts/topup/{id}', 'LoanaccountsController@topup');

Route::get('memloans/{id}', 'LoanaccountsController@show2');

Route::resource('loanrepayments', 'LoanrepaymentsController');

Route::get('loanrepayments/create/{id}', 'LoanrepaymentsController@create');
Route::get('loanrepayments/offset/{id}', 'LoanrepaymentsController@offset');
Route::post('loanrepayments/offsetloan', 'LoanrepaymentsController@offsetloan');





Route::get('reports', function(){

    return View::make('members.reports');
});

Route::get('reports/combined', function(){

    $members = Member::all();

    return View::make('members.combined', compact('members'));
});


Route::get('loanreports', function(){

    $loanproducts = Loanproduct::all();

    return View::make('loanaccounts.reports', compact('loanproducts'));
});


Route::get('savingreports', function(){

    $savingproducts = Savingproduct::all();

    return View::make('savingaccounts.reports', compact('savingproducts'));
});


Route::get('financialreports', function(){

    

    return View::make('pdf.financials.reports');
});



Route::get('reports/listing', 'ReportsController@members');
Route::get('reports/remittance', 'ReportsController@remittance');
Route::get('reports/blank', 'ReportsController@template');
Route::get('reports/loanlisting', 'ReportsController@loanlisting');

Route::get('reports/loanproduct/{id}', 'ReportsController@loanproduct');

Route::get('reports/savinglisting', 'ReportsController@savinglisting');

Route::get('reports/savingproduct/{id}', 'ReportsController@savingproduct');

Route::post('reports/financials', 'ReportsController@financials');



Route::get('portal', function(){

    //$members = DB::table('members')->where('is_active', '=', TRUE)->get();

  $employees = Employee::all();

    return View::make('css.employees', compact('employees'));
});

Route::get('portal/activate/{id}', 'MembersController@activateportal');
Route::get('portal/deactivate/{id}', 'MembersController@deactivateportal');
Route::get('css/reset/{id}', 'MembersController@reset');








/*
* Vendor controllers
*/
Route::resource('vendors', 'VendorsController');
Route::get('vendors/create', 'VendorsController@create');
Route::post('vendors/update/{id}', 'VendorsController@update');
Route::get('vendors/edit/{id}', 'VendorsController@edit');
Route::get('vendors/delete/{id}', 'VendorsController@destroy');
Route::get('vendors/products/{id}', 'VendorsController@products');
Route::get('vendors/orders/{id}', 'VendorsController@orders');

/*
* products controllers
*/
Route::resource('products', 'ProductsController');
Route::post('products/update/{id}', 'ProductsController@update');
Route::get('products/edit/{id}', 'ProductsController@edit');
Route::get('products/create', 'ProductsController@create');
Route::get('products/delete/{id}', 'ProductsController@destroy');
Route::get('products/orders/{id}', 'ProductsController@orders');
Route::get('shop', 'ProductsController@shop');

/*
* orders controllers
*/
Route::resource('orders', 'OrdersController');
Route::post('orders/update/{id}', 'OrdersControler@update');
Route::get('orders/edit/{id}', 'OrdersControler@edit');
Route::get('orders/delete/{id}', 'OrdersControler@destroy');




Route::get('savings', function(){

    $mem = Confide::user()->username;

   

    $memb = DB::table('members')->where('membership_no', '=', $mem)->pluck('id');

    $member = Member::find($memb);

    
    

    return View::make('css.savingaccounts', compact('member'));
});


Route::post('loanguarantors', function(){

    
    $mem_id = Input::get('member_id');

        $member = Member::findOrFail($mem_id);

        $loanaccount = Loanaccount::findOrFail(Input::get('loanaccount_id'));


        $guarantor = new Loanguarantor;

        $guarantor->member()->associate($member);
        $guarantor->loanaccount()->associate($loanaccount);
        $guarantor->amount = Input::get('amount');
        $guarantor->save();
        


        return Redirect::to('memloans/'.$loanaccount->id);

});




Route::get('backups', function(){

   
    //$backups = Backup::getRestorationFiles('../app/storage/backup/');

    return View::make('backup');

});


Route::get('backups/create', function(){

    echo '<pre>';

    $instance = Backup::getBackupEngineInstance();

    print_r($instance);

    //Backup::setPath(public_path().'/backups/');

   //Backup::export();
    //$backups = Backup::getRestorationFiles('../app/storage/backup/');

    //return View::make('backup');

});


Route::get('memtransactions/{id}', 'MembersController@savingtransactions');


/*
* This route is for testing how license conversion works. its purely for testing purposes
*/
Route::get('convert', function(){




// get the name of the organization from the database
//$org_id = Confide::user()->organization_id;

$organization = Organization::findorfail(1);



$string =  $organization->name;

echo "Organization: ". $string."<br>";


$organization = new Organization;






$license_code = $organization->encode($string);

echo "License Code: ".$license_code."<br>";


$name2 = $organization->decode($license_code, 7);

echo "Decoded L code: ".$name2."<br>";





$license_key = $organization->license_key_generator($license_code);

echo "License Key: ".$license_key."<br>";

echo "__________________________________________________<br>";

$name4 = $organization->license_key_validator($license_key,$license_code,$string);

echo "Decoded L code: ".$name4."<br>";



});




/* ########################  ERP ROUTES ################################ */

Route::group(['before' => 'manage_clients'], function() {

  Route::resource('clients', 'ClientsController');
Route::get('clients/edit/{id}', 'ClientsController@edit');
Route::post('clients/update/{id}', 'ClientsController@update');
Route::get('clients/delete/{id}', 'ClientsController@destroy');

    
});


Route::group(['before' => 'manage_items'], function() {


    Route::resource('items', 'ItemsController');
Route::get('items/edit/{id}', 'ItemsController@edit');
Route::post('items/update/{id}', 'ItemsController@update');
Route::get('items/delete/{id}', 'ItemsController@destroy');

Route::get('items/show/{id}', 'ItemsController@show');

    
});


Route::group(['before' => 'manage_bookings'], function() {


    Route::resource('bookings', 'BookingsController');
Route::get('bookings/edit/{id}', 'BookingsController@edit');
Route::post('bookings/update/{id}', 'BookingsController@update');
Route::get('bookings/delete/{id}', 'BookingsController@destroy');
Route::get('bookings/show/{id}', 'BookingsController@show');
Route::post('bookings/add', 'BookingsController@add');
Route::post('bookings/additems', 'BookingsController@additems');
Route::get('bookingscommit', function(){

    $bookingitems =Session::get('bookingitems');
      $bking =Session::get('booking');

      $client = Client::findOrFail($bking['client_id']);

     

      
      $booking = new Booking;
      $booking->client()->associate($client);
      $booking->event = $bking['event'];
      $booking->start_date = $bking['start_date'];
      $booking->end_date = $bking['end_date'];
      $booking->venue = $bking['venue'];
      $booking->lead = $bking['lead'];
      $booking->save();

      foreach($bookingitems as $bookingitem){
        
          $item = Item::findOrFail($bookingitem['item']);
          $bookingitem = new Bookingitem;
          $bookingitem->item()->associate($item);
          $bookingitem->booking()->associate($booking);
          $bookingitem->save();
      }
      

  return Redirect::to('bookings');

  

});

    
});




Route::group(['before' => 'manage_stores'], function() {


   Route::resource('locations', 'LocationsController');
Route::get('locations/edit/{id}', 'LocationsController@edit');
Route::get('locations/delete/{id}', 'LocationsController@destroy');
Route::post('locations/update/{id}', 'LocationsController@update');


    
});


Route::group(['before' => 'manage_checkin'], function() {



Route::get('checks/checkin/{id}', 'ChecksController@checkin');
Route::post('checks/checkin/{id}', 'ChecksController@docheckin');

    
});


Route::group(['before' => 'manage_checkout'], function() {



 Route::resource('checks', 'ChecksController');
Route::get('checks/edit/{id}', 'ChecksController@edit');
Route::get('checks/delete/{id}', 'ChecksController@destroy');
Route::post('checks/update/{id}', 'ChecksController@update');
Route::get('checks/show/{id}', 'ChecksController@show');

Route::get('checks/checkout', 'ChecksController@checkout');

    
});




  



Route::resource('paymentmethods', 'PaymentmethodsController');




Route::resource('payments', 'PaymentsController');





/*
Leave Reports
*/

Route::get('leaveReports', function(){

    return View::make('leavereports.leavereports');
});

Route::get('leaveReports/selectApplicationPeriod', 'ReportsController@appperiod');
Route::post('leaveReports/leaveapplications', 'ReportsController@leaveapplications');

Route::get('leaveReports/selectApprovedPeriod', 'ReportsController@approvedperiod');
Route::post('leaveReports/approvedleaves', 'ReportsController@approvedleaves');

Route::get('leaveReports/selectRejectedPeriod', 'ReportsController@rejectedperiod');
Route::post('leaveReports/rejectedleaves', 'ReportsController@rejectedleaves');

Route::get('leaveReports/selectLeave', 'ReportsController@balanceselect');
Route::post('leaveReports/leaveBalances', 'ReportsController@leavebalances');

Route::get('leaveReports/selectLeaveType', 'ReportsController@leaveselect');
Route::post('leaveReports/Employeesonleave', 'ReportsController@employeesleave');

Route::get('leaveReports/selectEmployee', 'ReportsController@employeeselect');
Route::post('leaveReports/IndividualEmployeeLeave', 'ReportsController@individualleave');

Route::get('api/dropdown', function(){
    $id = Input::get('option');
    $bbranch = Bank::find($id)->bankbranch;
    return $bbranch->lists('bank_branch_name', 'id');
});

Route::get('api/getDays', function(){
    $id = Input::get('employee');
    $lid = Input::get('leave');
    $d = Input::get('option');
    $sdate = Input::get('sdate');
    $weekends = Input::get('weekends');
    $holidays = Input::get('holidays');
    
    Leaveapplication::checkBalance($id, $lid,$d);
    if(Leaveapplication::checkBalance($id, $lid,$d)<0){
     return Leaveapplication::checkBalance($id, $lid,$d);
    }else{

    $enddate = Leaveapplication::getEndDate($sdate,$d,$weekends,$holidays);

    return $enddate;
    //Leaveapplication::checkHoliday($sdate);
    }
    
    //return Leaveapplication::checkBalance($id, $lid,$d);
});



Route::get('api/score', function(){
    $id = Input::get('option');
    $rate = Appraisalquestion::find($id);
    return $rate->rate;
});

Route::get('empedit/{id}', function($id){

  

  $employee = Employee::find($id);
    $branches = Branch::all();
    $departments = Department::all();
    $jgroups = Jobgroup::all();
    $etypes = EType::all();
    $banks = Bank::all();
    $bbranches = BBranch::all();
    return View::make('employees.cssedit', compact('branches','departments','etypes','jgroups','banks','bbranches','employee'));
  
});



Route::get('css/payslips', function(){

  $employeeid = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');

  $employee = Employee::findorfail($employeeid);

  return View::make('css.payslip', compact('employee'));
});

Route::resource('css/advances', 'MemberAdvancesController');
Route::get('css/advances/edit/{id}', 'MemberAdvancesController@edit');
Route::post('css/advances/update/{id}', 'MemberAdvancesController@update');
Route::get('css/advances/delete/{id}', 'MemberAdvancesController@destroy');
Route::get('css/advances/view/{id}', 'MemberAdvancesController@view');
Route::get('css/advances/create', 'MemberAdvancesController@create');

Route::resource('memberadvances', 'MemAdvancesController');
Route::get('memberadvances/approve/{id}', 'MemAdvancesController@approve');
Route::get('memberadvances/memapprove/{id}', 'MemAdvancesController@memapprove');
Route::get('memberadvances/reject/{id}', 'MemAdvancesController@reject');
Route::get('memberadvances/memrej/{id}', 'MemAdvancesController@memrej');
Route::get('memberadvances/view/{id}', 'MemAdvancesController@view');


Route::get('css/leave', function(){

  $employeeid = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');


  $employee = Employee::findorfail($employeeid);

   $leaveapplications = DB::table('leaveapplications')->where('employee_id', '=', $employee->id)->orderBy('application_date', 'desc')->get();

  return View::make('css.leave', compact('employee', 'leaveapplications'));
});

Route::get('css/subordinateleave', function(){

  $employeeid = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');
   $c = Supervisor::where('supervisor_id', $employeeid)->count();
    
  $employee = Employee::findorfail($employeeid);

   //$leaveapplications = DB::table('leaveapplications')->where('employee_id', '=', $employee->id)->get();

  return View::make('css.approveleave', compact('c','leaveapplications'));
});


Route::get('css/leaveapply', function(){

  $employeeid = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');

  $employee = Employee::findorfail($employeeid);
  $leavetypes = Leavetype::all();

  return View::make('css.leaveapply', compact('employee', 'leavetypes'));
});


Route::get('css/balances', function(){

  $employeeid = DB::table('employee')->where('personal_file_number', '=', Confide::user()->username)->pluck('id');

  $employee = Employee::findorfail($employeeid);
  $leavetypes = Leavetype::all();

  return View::make('css.balances', compact('employee', 'leavetypes'));
});



/*
*##########################ERP REPORTS#######################################
*/

Route::get('erpReports', function(){

    return View::make('erpreports.erpReports');
});

Route::post('erpReports/clients', 'ErpReportsController@clients');
Route::get('erpReports/selectClientsPeriod', 'ErpReportsController@selectClientsPeriod');



Route::post('erpReports/items', 'ErpReportsController@items');
Route::get('erpReports/selectItemsPeriod', 'ErpReportsController@selectItemsPeriod');

Route::post('erpReports/expenses', 'ErpReportsController@expenses');
Route::get('erpReports/selectExpensesPeriod', 'ErpReportsController@selectExpensesPeriod');


Route::get('erpReports/paymentmethods', 'ErpReportsController@paymentmethods');

Route::post('erpReports/payments', 'ErpReportsController@payments');
Route::get('erpReports/selectPaymentsPeriod', 'ErpReportsController@selectPaymentsPeriod');

Route::get('erpReports/invoice/{id}', 'ErpReportsController@invoice');


Route::post('erpReports/sales', 'ErpReportsController@sales');
Route::get('erpReports/sales_summary', 'ErpReportsController@sales_Summary');
Route::get('erpReports/selectSalesPeriod', 'ErpReportsController@selectSalesPeriod');


Route::post('erpReports/purchases', 'ErpReportsController@purchases');
Route::get('erpReports/selectPurchasesPeriod', 'ErpReportsController@selectPurchasesPeriod');



Route::get('erpReports/quotation/{id}', 'ErpReportsController@quotation');
Route::get('erpReports/pricelist', 'ErpReportsController@pricelist');
Route::get('erpReports/receipt/{id}', 'ErpReportsController@receipt');
Route::get('erpReports/PurchaseOrder/{id}', 'ErpReportsController@PurchaseOrder');

Route::get('erpReports/locations', 'ErpReportsController@locations');

Route::get('erpReports/stocks', 'ErpReportsController@stock');
Route::get('erpReports/selectStockPeriod', 'ErpReportsController@selectStockPeriod');


Route::get('erpReports/accounts', 'ErpReportsController@accounts');



Route::resource('taxes', 'TaxController');
Route::post('taxes/update/{id}', 'TaxController@update');
Route::get('taxes/delete/{id}', 'TaxController@destroy');
Route::get('taxes/edit/{id}', 'TaxController@edit');





/**
 * BEGINNING OF ORDERS(SALES & PURCHASES) & QUOTATIONS
 */

Route::get('salesorders', function(){
  $orders = Erporder::all();
  $items = Item::all();
  $locations = Location::all();

  return View::make('erporders.index', compact('items', 'locations', 'orders'));
});



Route::get('purchaseorders', function(){

  $purchases = Erporder::all();
  $items = Item::all();
  $locations = Location::all();


  return View::make('erppurchases.index', compact('items', 'locations', 'purchases'));
});



Route::get('quotationorders', function(){

  $quotations = Erporder::all();
  $items = Item::all();
  $locations = Location::all();
  $items = Item::all();
  $locations = Location::all();

  return View::make('erpquotations.index', compact('items', 'locations', 'quotations'));
});


Route::get('salesorders/create', function(){

  $count = DB::table('erporders')->count();
  $order_number = date("Y/m/d/").str_pad($count+1, 4, "0", STR_PAD_LEFT);
  $items = Item::all();
  $locations = Location::all();
  $accounts = Account::all();

  $clients = Client::all();

  return View::make('erporders.create', compact('items', 'locations', 'order_number', 'clients', 'accounts'));
});


Route::get('purchaseorders/create', function(){

  $count = DB::table('erporders')->count();
  $order_number = date("Y/m/d/").str_pad($count+1, 4, "0", STR_PAD_LEFT);
  $items = Item::all();
  $locations = Location::all();
  $accounts = Account::all();

  $clients = Client::all();

  return View::make('erppurchases.create', compact('items', 'locations', 'order_number', 'clients', 'accounts'));
});


Route::get('quotationorders/create', function(){

  $count = DB::table('erporders')->count();
  $order_number = date("Y/m/d/").str_pad($count+1, 4, "0", STR_PAD_LEFT);;
  $items = Item::all();
  $locations = Location::all();

  $clients = Client::all();

  return View::make('erpquotations.create', compact('items', 'locations', 'order_number', 'clients'));
});




Route::post('erporders/create', function(){

  $data = Input::all();

  $client = Client::findOrFail(array_get($data, 'client'));

/*
  $erporder = array(
    'order_number' => array_get($data, 'order_number'), 
    'client' => $client,
    'date' => array_get($data, 'date')

    );
  */

  Session::put( 'erporder', array(
      'order_number' => array_get($data, 'order_number'), 
      'client' => $client,
      'date' => array_get($data, 'date'),
      'credit_ac' => array_get($data, 'credit_ac'),
      'debit_ac' => array_get($data, 'debit_ac'),
      'transaction_desc' => array_get($data, 'transaction_desc')
    )
  );
  Session::put('orderitems', []);

  $orderitems =Session::get('orderitems');

 /*
  $erporder = new Erporder;

  $erporder->date = date('Y-m-d', strtotime(array_get($data, 'date')));
  $erporder->order_number = array_get($data, 'order_number');
  $erporder->client()->associate($client);
  $erporder->payment_type = array_get($data, 'payment_type');
  $erporder->type = 'sales';
  $erporder->save();

  */

  $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erporders.orderitems', compact('erporder', 'items', 'locations', 'taxes','orderitems'));

});



Route::post('erppurchases/create', function(){

  $data = Input::all();

  $client = Client::findOrFail(array_get($data, 'client'));

/*
  $erporder = array(
    'order_number' => array_get($data, 'order_number'), 
    'client' => $client,
    'date' => array_get($data, 'date')

    );
  */

  Session::put( 'erporder', array(
      'order_number' => array_get($data, 'order_number'), 
      'client' => $client,
      'date' => array_get($data, 'date'),
      'credit_ac' => array_get($data, 'credit_ac'),
      'debit_ac' => array_get($data, 'debit_ac'),
      'transaction_desc' => array_get($data, 'transaction_desc')
    )
  );
  Session::put('purchaseitems', []);

  $orderitems =Session::get('purchaseitems');

 /*
  $erporder = new Erporder;

  $erporder->date = date('Y-m-d', strtotime(array_get($data, 'date')));
  $erporder->order_number = array_get($data, 'order_number');
  $erporder->client()->associate($client);
  $erporder->payment_type = array_get($data, 'payment_type');
  $erporder->type = 'sales';
  $erporder->save();

  */

  $items = Item::where('type', '!=', 'service')->get();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erppurchases.purchaseitems', compact('items', 'locations','taxes','orderitems'));

});





Route::post('erpquotations/create', function(){

  $data = Input::all();

  $client = Client::findOrFail(array_get($data, 'client'));

/*
  $erporder = array(
    'order_number' => array_get($data, 'order_number'), 
    'client' => $client,
    'date' => array_get($data, 'date')

    );
  */

  Session::put( 'erporder', array(
    'order_number' => array_get($data, 'order_number'), 
    'client' => $client,
    'date' => array_get($data, 'date')

    )
    );
  Session::put('quotationitems', []);

  $orderitems =Session::get('quotationitems');

 /*
  $erporder = new Erporder;

  $erporder->date = date('Y-m-d', strtotime(array_get($data, 'date')));
  $erporder->order_number = array_get($data, 'order_number');
  $erporder->client()->associate($client);
  $erporder->payment_type = array_get($data, 'payment_type');
  $erporder->type = 'sales';
  $erporder->save();

  */

  $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erpquotations.quotationitems', compact('items', 'locations','taxes','orderitems'));

});


/**
 * =====================================
 * ORDERITEMS {SALES ORDER}
 */
Route::post('orderitems/create', function(){

  $data = Input::all();

  $item = Item::findOrFail(array_get($data, 'item'));

  $item_name = $item->name;
  $price = $item->selling_price;
  $quantity = Input::get('quantity');
  $duration = Input::get('duration');
  $item_id = $item->id;
  $location = Input::get('location');

   Session::push('orderitems', [
      'itemid' => $item_id,
      'item' => $item_name,
      'price' => $price,
      'quantity' => $quantity,
      'duration' => $duration,
      'location' =>$location
    ]);



  $orderitems = Session::get('orderitems');

   $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erporders.orderitems', compact('items', 'locations', 'taxes','orderitems'));

});


/**
 * =================================================================
 * ORDERITEMS EDITING
 * Editing order item session
 */
Route::get('orderitems/edit/{count}', function($count){
  $editItem = Session::get('orderitems')[$count];

  return View::make('erporders.edit', compact('editItem', 'count'));
});

Route::post('orderitems/edit/{count}', function($sesItemID){
  $quantity = Input::get('qty');
  $price = (float) Input::get('price');
  //return $data['qty'].' - '.$data['price'];

  $ses = Session::get('orderitems');
  //unset($ses);
  $ses[$sesItemID]['quantity']=$quantity;
  $ses[$sesItemID]['price']=$price;
  Session::put('orderitems', $ses);

  $orderitems = Session::get('orderitems');
  $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erporders.orderitems', compact('items', 'locations', 'taxes','orderitems'));
  
});


/**
 * =====================================
 * Deleting an order item session item
 */
Route::get('orderitems/remove/{count}', function($count){
  $item = Session::get('orderitems');
  unset($item[$count]);
  $newItems = array_values($item);
  Session::put('orderitems', $newItems);


  $orderitems = Session::get('orderitems');
  $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erporders.orderitems', compact('items', 'locations', 'taxes','orderitems'));
});



/**
 * =========================
 * PURCHASES
 */
Route::post('purchaseitems/create', function(){

  $data = Input::all();

  $item = Item::findOrFail(array_get($data, 'item'));

  $item_name = $item->name;
  $price = $item->purchase_price;
  $quantity = Input::get('quantity');
  $duration = Input::get('duration');
  $item_id = $item->id;

   Session::push('purchaseitems', [
      'itemid' => $item_id,
      'item' => $item_name,
      'price' => $price,
      'quantity' => $quantity,
      'duration' => $duration
    ]);



  $orderitems = Session::get('purchaseitems');

  $items = Item::where('type', 'product')->get();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erppurchases.purchaseitems', compact('items', 'locations', 'taxes','orderitems'));

});



/**
 * ==========================================================================
 * EDITING PURCHASE ORDER SESSION
 * Editing a purchase order session item
 */
Route::get('purchaseitems/edit/{count}', function($count){
  $editItem = Session::get('purchaseitems')[$count];

  return View::make('erppurchases.edit', compact('editItem', 'count'));
});

Route::post('erppurchases/edit/{count}', function($sesItemID){
  $quantity = Input::get('qty');
  $price = (float) Input::get('price');
  //return $data['qty'].' - '.$data['price'];

  $ses = Session::get('purchaseitems');
  //unset($ses);
  $ses[$sesItemID]['quantity']=$quantity;
  $ses[$sesItemID]['price']=$price;
  Session::put('purchaseitems', $ses);

  $orderitems = Session::get('purchaseitems');
  $items = Item::where('type', 'product')->get();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erppurchases.purchaseitems', compact('items', 'locations', 'taxes','orderitems'));
  
});


/**
 * =========================================================================
 * Deleting a purchase order session
 */
Route::get('purchaseitems/remove/{count}', function($count){
  $items = Session::get('purchaseitems');
  unset($items[$count]);
  $newItems = array_values($items);
  Session::put('purchaseitems', $newItems);


  $orderitems = Session::get('purchaseitems');
  $items = Item::where('type', 'product')->get();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erppurchases.purchaseitems', compact('items', 'locations', 'taxes','orderitems'));
});


/**
 * ===================
 * QUOTATION
 */
Route::post('quotationitems/create', function(){

  $data = Input::all();

  $item = Item::findOrFail(array_get($data, 'item'));

  $item_name = $item->name;
  $price = $item->selling_price;
  $quantity = Input::get('quantity');
  $duration = Input::get('duration');
  $item_id = $item->id;

   Session::push('quotationitems', [
      'itemid' => $item_id,
      'item' => $item_name,
      'price' => $price,
      'quantity' => $quantity,
      'duration' => $duration
    ]);



  $orderitems = Session::get('quotationitems');

   $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erpquotations.quotationitems', compact('items', 'locations', 'taxes','orderitems'));

});


/**
 * ==============================================================================
 * EDITING QUOTATION ITEMS VIA SESSION
 * Deleting a session item
 */
Route::get('quotationitems/remove/{count}', function($count){
  $items = Session::get('quotationitems');
  unset($items[$count]);
  $newItems = array_values($items);
  Session::put('quotationitems', $newItems);


  $orderitems = Session::get('quotationitems');
  $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erpquotations.quotationitems', compact('items', 'locations', 'taxes','orderitems'));
  //return Session::get('quotationitems')[$count];
});


/**
 * EDITING A QUOTATION ITEM
 */
Route::get('quotationitems/edit/{count}', function($count){
  $editItem = Session::get('quotationitems')[$count];

  return View::make('erpquotations.sessionedit', compact('editItem', 'count'));
});


Route::post('erpquotations/sessionedit/{count}', function($sesItemID){
  $quantity = Input::get('qty');
  $price = (float) Input::get('price');

  $ses = Session::get('quotationitems');
  
  $ses[$sesItemID]['quantity']=$quantity;
  $ses[$sesItemID]['price']=$price;
  Session::put('quotationitems', $ses);

  $orderitems = Session::get('quotationitems');
  $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erpquotations.quotationitems', compact('items', 'locations', 'taxes','orderitems'));
});

/*=================================================================================*/


Route::get('purchaseitems/remove/{id}', function($id){

  Session::forget('orderitems', $id);

  $orderitems = Session::get('orderitems');

  $items = Item::all();
  $locations = Location::all();
  $taxes = Tax::all();

  return View::make('erporders.orderitems', compact('items', 'locations', 'taxes', 'orderitems'));

});



Route::resource('stocks', 'StocksController');
Route::resource('erporders', 'ErporderssController');


Route::resource('mails', 'MailsController');
Route::get('mailtest', 'MailsController@test');




Route::post('erporder/commit', function(){

  $erporder = Session::get('erporder');

  $erporderitems = Session::get('orderitems');
  
  $total = Input::all();

  // $client = Client: :findorfail(array_get($erporder, 'client'));

  // print_r($total);
  
  // Create a session to hold journal entry data
  Session::put('sales_journal', [
    'credit_account' => $erporder['credit_ac'],
    'debit_account' => $erporder['debit_ac'],
    'date' => date('Y-m-d', strtotime(array_get($erporder, 'date'))),
    'amount' => $total['grand'],
    'description' => $erporder['transaction_desc'],
    'initiated_by' => Confide::user()->username
  ]);

  $data = Session::get('sales_journal');

  // Create a new sales order
  $order = new Erporder;
  $order->order_number = array_get($erporder, 'order_number');
  $order->client()->associate(array_get($erporder, 'client'));
  $order->date = date('Y-m-d', strtotime(array_get($erporder, 'date')));
  $order->status = 'new';
  $order->discount_amount = array_get($total, 'discount');
  $order->type = 'sales';  
  $order->save();

  // Create a new Journal Entry
  $jEntry = new Journal;
  $jEntry->journal_entry($data);

  // Create a new Account Transaction
  $acTransaction = new AccountTransaction;
  $acTransaction->createTransaction($data);

  Session::forget('sales_journal');


  // Insert data into Erporderitem table
  foreach($erporderitems as $item){

    $itm = Item::findOrFail($item['itemid']);

    $ord = Erporder::findOrFail($order->id);


    
    $location_id = $item['location'];

     $location = Location::find($location_id);    
    
    $date = date('Y-m-d', strtotime(array_get($erporder, 'date')));

    $orderitem = new Erporderitem;
    $orderitem->erporder()->associate($ord);
    $orderitem->item()->associate($itm);
    $orderitem->price = $item['price'];
    $orderitem->quantity = $item['quantity'];
    $orderitem->duration = $item['duration'];
    $orderitem->save();


    if($itm->type=='product')
    {

   Stock::removeStock($itm, $location, $item['quantity'], $date);

   }

  }
 

  $tax = Input::get('tax');
  $rate = Input::get('rate');





  for($i=0; $i < count($rate);  $i++){

    $txOrder = new TaxOrder;

    $txOrder->tax_id = $rate[$i];
    $txOrder->order_number = array_get($erporder, 'order_number');
    $txOrder->amount = $tax[$i];
    $txOrder->save();
    
  }
  
 
//Session::flush('orderitems');
//Session::flush('erporder');  
 
    

return Redirect::to('salesorders')->withFlashMessage('Order Successfully Placed!');



});







Route::post('erppurchase/commit', function(){

  //$orderitems = Session::get('erppurchase');

  $erporder = Session::get('erporder');

  $orderitems = Session::get('purchaseitems');
  
  $total = Input::all();
  // $client = Client: :findorfail(array_get($erporder, 'client'));

  // print_r($total);
  
  // Create a session to hold journal entry data
  Session::put('purchase_journal', [
    'credit_account' => $erporder['credit_ac'],
    'debit_account' => $erporder['debit_ac'],
    'date' => date('Y-m-d', strtotime(array_get($erporder, 'date'))),
    'amount' => $total['grand'],
    'description' => $erporder['transaction_desc'],
    'initiated_by' => Confide::user()->username
  ]);

  $data = Session::get('purchase_journal');


  $order = new Erporder;
  $order->order_number = array_get($erporder, 'order_number');
  $order->client()->associate(array_get($erporder, 'client'));
  $order->date = date('Y-m-d', strtotime(array_get($erporder, 'date')));
  $order->status = 'new';
  //$order->discount_amount = array_get($total, 'discount');
  $order->type = 'purchases';
  $order->save();

  // Create a new Journal Entry
  $jEntry = new Journal;
  $jEntry->journal_entry($data);

  // Create a new Account Transaction
  $acTransaction = new AccountTransaction;
  $acTransaction->createTransaction($data);

  Session::forget('purchase_journal');


  // Insert data into Erporderitem table
  foreach($orderitems as $item){


    $itm = Item::findOrFail($item['itemid']);

    $ord = Erporder::findOrFail($order->id);

    $orderitem = new Erporderitem;
    $orderitem->erporder()->associate($ord);
    $orderitem->item()->associate($itm);
    $orderitem->price = $item['price'];
    $orderitem->quantity = $item['quantity'];
    //s$orderitem->duration = $item['duration'];
    $orderitem->save();
  }
  
 
//Session::flush('orderitems');
//Session::flush('erporder');
return Redirect::to('purchaseorders')->withFlashMessage('Order Successfully Placed!');;



});


Route::post('erpquotation/commit', function(){

  $erporder = Session::get('erporder');

  $erporderitems = Session::get('quotationitems');
  
  $total = Input::all();

  // $client = Client: :findorfail(array_get($erporder, 'client'));

  // print_r($total);


  $order = new Erporder;
  $order->order_number = array_get($erporder, 'order_number');
  $order->client()->associate(array_get($erporder, 'client'));
  $order->date = date('Y-m-d', strtotime(array_get($erporder, 'date')));
  $order->status = 'new';
  $order->discount_amount = array_get($total, 'discount');
  $order->type = 'quotations';  
  $order->save();
  

  foreach($erporderitems as $item){


    $itm = Item::findOrFail($item['itemid']);

    $ord = Erporder::findOrFail($order->id);


    
    //$location_id = $item['location'];

     //$location = Location::find($location_id);    
    
    $date = date('Y-m-d', strtotime(array_get($erporder, 'date')));

    $orderitem = new Erporderitem;
    $orderitem->erporder()->associate($ord);
    $orderitem->item()->associate($itm);
    $orderitem->price = $item['price'];
    $orderitem->quantity = $item['quantity'];
    $orderitem->duration = $item['duration'];
    $orderitem->save();
  }

  $tax = Input::get('tax');
  $rate = Input::get('rate');

  for($i=0; $i < count($rate);  $i++){

    $txOrder = new TaxOrder;

    $txOrder->tax_id = $rate[$i];
    $txOrder->order_number = array_get($erporder, 'order_number');
    $txOrder->amount = $tax[$i];
    $txOrder->save();
    
  }
  
  //Session::flush('orderitems');
  //Session::flush('erporder');    
  return Redirect::to('quotationorders');

});




Route::get('erporders/cancel/{id}', function($id){

  $order = Erporder::findorfail($id);



  $order->status = 'cancelled';
  $order->update();

  return Redirect::to('salesorders');
  
});


Route::get('erporders/delivered/{id}', function($id){

  $order = Erporder::findorfail($id);



  $order->status = 'delivered';
  $order->update();

  return Redirect::to('salesorders');
  
});




Route::get('erppurchases/cancel/{id}', function($id){

  $order = Erporder::findorfail($id);



  $order->status = 'cancelled';
  $order->update();

  return Redirect::to('purchaseorders');
  
});



Route::get('erppurchases/delivered/{id}', function($id){

  $order = Erporder::findorfail($id);

  $order->status = 'delivered';
  $order->update();

  return Redirect::to('purchaseorders');
  
});




Route::get('erpquotations/cancel/{id}', function($id){

  $order = Erporder::findorfail($id);



  $order->status = 'cancelled';
  $order->update();

  return Redirect::to('quotationorders');
  
});




Route::get('erporders/show/{id}', function($id){

  $order = Erporder::findorfail($id);

  return View::make('erporders.show', compact('order'));
  
});



Route::get('erppurchases/show/{id}', function($id){

  $order = Erporder::findorfail($id);

  return View::make('erppurchases.show', compact('order'));
  
});


Route::get('erppurchases/payment/{id}', function($id){

  $payments = Payment::all();

  $purchase = Erporder::findorfail($id);    

  $account = Accounts::all();

  return View::make('erppurchases.payment', compact('payments', 'purchase', 'account'));
  
});



/**
 * SHOW ERP QUOTATION 'X'
 */
Route::get('erpquotations/show/{id}', function($id){


  $order = Erporder::findorfail($id);

  return View::make('erpquotations.show', compact('order'));
  
});


/**
 * APPROVE QUOTATION 'X'
 */
Route::post('/erpquotations/approve', function(){
  $id = Input::get('order_id');
  $comment = Input::get('comment');

  $order = Erporder::findorfail($id);

  $order->status = 'APPROVED';
  if($comment === ''){
    $order->comment = 'No comment.';
  } else{
    $order->comment = $comment;
  }

  $order->update();

  return Redirect::to('erpquotations/show/'.$id);
});


/**
 * REJECT QUOTATION 'X'
 */
Route::post('/erpquotations/reject', function(){
  $id = Input::get('order_id');
  $comment = Input::get('comment');

  $order = Erporder::findorfail($id);

  $order->status = 'REJECTED';
  if($comment === ''){
    $order->comment = 'No comment.';
  } else{
    $order->comment = $comment;
  }

  $order->update();

  return Redirect::to('erpquotations/show/'.$id);
});


/**
 * MAIL ERP QUOTATION TO CLIENT 'X'
 */
Route::post('erpquotations/mail', 'ErpReportsController@sendMail_quotation');


/**
 * EDIT QUOTATION 'X'
 */
Route::get('erpquotations/edit/{id}', function($id){
  $order = Erporder::findorfail($id);
  $items = Item::all();
  $taxes = Tax::all();
  $tax_orders = TaxOrder::where('order_number', $order->order_number)->orderBy('tax_id', 'ASC')->get();

  //return $tax_orders;
  return View::make('erpquotations.editquotation', compact('order', 'items', 'taxes', 'tax_orders'));

});


/**
 * ADD ITEMS TO EXISTING ORDER
 */
Route::post('erpquotations/edit/add', function(){
    $order_id = Input::get('order_id');
    $item_id = Input::get('item_id');
    $quantity = Input::get('quantity');
    
    $item = Item::findorfail($item_id);
    $item_price = $item->selling_price;

    $itemId = Erporderitem::where('erporder_id', $order_id)->where('item_id', $item_id)->get();
    
    if(count($itemId) > 0){
        return Redirect::back()->with('error', "Item already exists! You can edit the existing item.");
    } else{
        $order_item = new Erporderitem;
        
        $order_item->item_id = $item_id;
        $order_item->quantity = $quantity;
        $order_item->erporder_id = $order_id;   
        $order_item->price = $item_price;
        $order_item->save();

        return Redirect::back(); 
    }
});


/**
 * COMMIT CHANGES
 */
Route::post('erpquotations/edit/{id}', function($id){
    $order = Erporder::findOrFail($id);

    foreach($order->erporderitems as $orderitem){
        $val = Input::get('newQty'.$orderitem->item_id);
        $price = Input::get('newPrice'.$orderitem->item_id);
        
        $orderitem->price = $price;
        $orderitem->quantity = $val;
        $orderitem->save();
    }  

    $discount = Input::get('discount');
    $order->discount_amount = $discount;

    $tax = Input::get('tax');
    $rate = Input::get('rate');

    for($i=0; $i < count($rate);  $i++){
        if(count(TaxOrder::getAmount($rate[$i],$order->order_number)) > 0){
            $txOrder = TaxOrder::findOrfail($rate[$i]);
            $txOrder->amount = $tax[$i];
            $txOrder->update();
        } else{
            $txOrder = new TaxOrder;
            $txOrder->tax_id = $rate[$i];
            $txOrder->order_number = array_get($order, 'order_number');
            $txOrder->amount = $tax[$i];
            $txOrder->save();
        }
    }

    $order->status = 'EDITED';
    $order->update();
    return View::make('erpquotations.show', compact('order'));
});



/**
 * ==========================================================================================
 * EDITING QUOTATION ITEM FROM DB
 */
/*Route::get('erpquotations/edit/{id}', function($id){
  //$order = Erporder::findorfail($id);

  $quote = DB::table('erporderitems')
              ->join('erporders', 'erporders.id', '=', 'erporderitems.erporder_id')
              ->join('items', 'items.id', '=', 'erporderitems.item_id')
              ->select('erporders.id as erporders_id', 'erporders.client_id', 'erporders.type', 
                      'erporderitems.id as erporderitems_id', 'item_id', 'quantity', 'price', 
                      'erporderitems.erporder_id as quote_id',
                      'items.id as items_id', 'name')
              ->where('erporderitems.id', '=', $id)
              ->first();

            //return var_dump($quote);

  return View::make('erpquotations.edit', compact('quote'));

});


Route::post('erpquotations/edit', function(){
  $quote_id = Input::get('quote_id');
  $id = Input::get('quote_item_id');
  $qty = Input::get('qty');
  $price = Input::get('price');

  //return $id.' - '.$qty.' - '.$price.' - '.$quote_id;

  DB::table('erporderitems')
        ->where('id', $id)
        ->update(array('quantity'=>$qty, 'price'=>$price));

  return Redirect::to('erpquotations/show/'.$quote_id);

});


/*= ADD ITEMS TO QUOTATION =*/


/*= DELETE QUOTE ITEM =*/
Route::get('erpquotations/delete/{quote_id}/{id}', function($quote_id, $id){
  
  DB::table('erporderitems')
        ->where('id', $id)
        ->delete();
  DB::update('ALTER TABLE `erporderitems` AUTO_INCREMENT=1');

  return Redirect::to('erpquotations/edit/'.$quote_id);
});


/*========================================================================*/


Route::get('api/getrate', function(){
    $id = Input::get('option');
    $tax = Tax::find($id);
    return $tax->rate;
});

Route::get('api/getmax', function(){
    $id = Input::get('option');
    $item  = Item::find($id);
    if($item->type == 'product'){
    $stock_in = DB::table('stocks')
         ->join('items', 'stocks.item_id', '=', 'items.id')
         ->where('item_id',$id)
         ->sum('quantity_in');

    $stock_out = DB::table('stocks')
         ->join('items', 'stocks.item_id', '=', 'items.id')
         ->where('item_id',$id)
         ->sum('quantity_out');
    return $stock_in-$stock_out;
  }
});


Route::get('api/total', function(){
    $id = Input::get('option');
    
    $client = Client::find($id);
    $order = 0;
    

          if($client->type == 'Customer'){
             $order = DB::table('erporders')
               ->join('erporderitems','erporders.id','=','erporderitems.erporder_id')
               ->join('clients','erporders.client_id','=','clients.id')
               ->where('clients.id',$id)
               ->where('erporders.type', '!=', 'quotations')
               ->where('erporders.status', '!=', 'cancelled') ->selectRaw('SUM((price * quantity)) as total')
               ->pluck('total');
               //->where('status', '<>', 'cancelled')

             $tax = DB::table('erporders')
               ->join('clients','erporders.client_id','=','clients.id')
               ->join('tax_orders','erporders.order_number','=','tax_orders.order_number')
               ->where('clients.id',$id)
               ->where('erporders.type', '!=', 'quotations')
               ->where('erporders.status', '!=', 'cancelled') ->selectRaw('SUM(COALESCE(amount,0))as total')
               ->pluck('total');

             $order = $order + $tax;
          }
          else{
             $order = DB::table('erporders')
               ->join('erporderitems','erporders.id','=','erporderitems.erporder_id')
               ->join('clients','erporders.client_id','=','clients.id')           
               ->where('clients.id',$id)
               ->where('erporders.status', '!=', 'cancelled') ->selectRaw('SUM((price * quantity))as total')
               ->pluck('total');
          }

    $paid = DB::table('clients')
           ->join('payments','clients.id','=','payments.client_id')
           ->where('clients.id',$id) ->selectRaw('COALESCE(SUM(amount_paid),0) as due')
           ->pluck('due');
    
    return number_format($order-$paid, 2);
});

Route::resource('mails', 'MailsController');
Route::get('mailtest', 'MailsController@test');


Route::get('seedmail', function(){

  $mail = new Mailsender;

  $mail->driver = 'smtp';
  $mail->save();
});

Route::get('mail', function(){
  $mail = Mailsender::find(1);  
  return View::make('system.mail', compact('mail'));

});


/**
 * ==== END OF ORDERS(SALES & PURCHASES) & QUOTATIONS ====
 */


Route::get('salesinvoice/{id}', function($id){

    $order = Erporder::find($id);

    $organization = Organization::find(1);

    $pdf = PDF::loadView('erpreports.salesinvoice', compact('order', 'organization'))->setPaper('a4')->setOrientation('potrait');
  
    return $pdf->stream('Sales Invoice.pdf');
});



/*
*overtimes
*/

Route::resource('overtimes', 'OvertimesController');
Route::get('overtimes/edit/{id}', 'OvertimesController@edit');
Route::post('overtimes/update/{id}', 'OvertimesController@update');
Route::get('overtimes/delete/{id}', 'OvertimesController@destroy');
Route::get('overtimes/view/{id}', 'OvertimesController@view');

/*
* employee documents routes
*/

Route::resource('documents', 'DocumentsController');
Route::post('documents/update/{id}', 'DocumentsController@update');
Route::get('documents/delete/{id}', 'DocumentsController@destroy');
Route::get('documents/edit/{id}', 'DocumentsController@edit');
Route::get('documents/download/{id}', 'DocumentsController@getDownload');
Route::get('documents/create/{id}', 'DocumentsController@create');

Route::resource('NextOfKins', 'NextOfKinsController');
Route::post('NextOfKins/update/{id}', 'NextOfKinsController@update');
Route::get('NextOfKins/delete/{id}', 'NextOfKinsController@destroy');
Route::get('NextOfKins/edit/{id}', 'NextOfKinsController@edit');
Route::get('NextOfKins/view/{id}', 'NextOfKinsController@view');
Route::get('NextOfKins/create/{id}', 'NextOfKinsController@create');

Route::resource('Appraisals', 'AppraisalsController');
Route::post('Appraisals/update/{id}', 'AppraisalsController@update');
Route::get('Appraisals/delete/{id}', 'AppraisalsController@destroy');
Route::get('Appraisals/edit/{id}', 'AppraisalsController@edit');
Route::get('Appraisals/create/{id}', 'AppraisalsController@create');
Route::get('Appraisals/view/{id}', 'AppraisalsController@view');

Route::resource('Properties', 'PropertiesController');
Route::post('Properties/update/{id}', 'PropertiesController@update');
Route::get('Properties/delete/{id}', 'PropertiesController@destroy');
Route::get('Properties/edit/{id}', 'PropertiesController@edit');
Route::get('Properties/view/{id}', 'PropertiesController@view');
Route::get('Properties/create/{id}', 'PropertiesController@create');

Route::resource('AppraisalSettings', 'AppraisalSettingsController');
Route::post('AppraisalSettings/update/{id}', 'AppraisalSettingsController@update');
Route::get('AppraisalSettings/delete/{id}', 'AppraisalSettingsController@destroy');
Route::get('AppraisalSettings/edit/{id}', 'AppraisalSettingsController@edit');



Route::group(['before' => 'manage_items'], function() {

Route::resource('itemcategories', 'ItemcategoriesController');
Route::get('itemcategories/edit/{id}', 'ItemcategoriesController@edit');
Route::get('itemcategories/delete/{id}', 'ItemcategoriesController@destroy');
Route::post('itemcategories/update/{id}', 'ItemcategoriesController@update');

    
});




Route::group(['before' => 'manage_maintenance'], function() {


  Route::resource('tests', 'TestsController');
Route::get('tests/edit/{id}', 'TestsController@edit');
Route::get('tests/delete/{id}', 'TestsController@destroy');
Route::post('tests/update/{id}', 'TestsController@update');


Route::resource('maintenances', 'MaintenancesController');
Route::get('maintenances/edit/{id}', 'MaintenancesController@edit');
Route::get('maintenances/delete/{id}', 'MaintenancesController@destroy');
Route::post('maintenances/update/{id}', 'MaintenancesController@update');


Route::resource('assets', 'AssetsController');
Route::get('assets/edit/{id}', 'AssetsController@edit');
Route::get('assets/delete/{id}', 'AssetsController@destroy');
Route::post('assets/update/{id}', 'AssetsController@update');
Route::get('assets/dispose/{id}', 'AssetsController@dispose');
Route::post('assets/dispose/{id}', 'AssetsController@submitdispose');
Route::get('assets/show/{id}', 'AssetsController@show');


    
});




Route::group(['before' => 'manage_inventory_reports'], function() {

    Route::get('invreports', function(){
 
      $clients = Client::all();
      $bookings = Booking::all();
      $items = Item::all();
      $stores = Location::all();
      $tests = Test::all();

      return View::make('invreports', compact('items', 'stores','bookings','clients','tests'));

    });

     Route::post('invreports', 'ReportsController@inventoryreports');
  

     /*Route::post('invreports', function(){


     $data = Input::all();

     return Redirect::back()->with('notice', 'You do not have sufficient information to generate this report');

    });*/


});





Route::get('erpmigrate', function(){

  return View::make('erpmigrate');

});


Route::post('import/categories', function(){

  
  if(Input::hasFile('category')){

      $destination = public_path().'/migrations/';

      $filename = str_random(12);

      $ext = Input::file('category')->getClientOriginalExtension();
      $file = $filename.'.'.$ext;



      
      
     
      Input::file('category')->move($destination, $file);


  


    Excel::selectSheetsByIndex(0)->load(public_path().'/migrations/'.$file, function($reader){

          $results = $reader->get();    
  
    foreach ($results as $result) {



      $category = new Itemcategory;

      $employee->personal_file_number = $result->employment_number;
      
      $employee->first_name = $result->first_name;
      $employee->last_name = $result->surname;
      $employee->middle_name = $result->other_names;
      $employee->identity_number = $result->id_number;
      $employee->pin = $result->kra_pin;
      $employee->social_security_number = $result->nssf_number;
      $employee->hospital_insurance_number = $result->nhif_number;
      $employee->email_office = $result->email_address;
      $employee->basic_pay = $result->basic_pay;
      $employee->save();
      
    }
    

    

  });



      
    }



  return Redirect::back()->with('notice', 'Employees have been succeffully imported');
  

});

Route::get('reports/AllowanceExcel', 'ReportsController@excelAll');

Route::get('itax/download', 'ReportsController@getDownload');



Route::get('errorboard', function(){

  return View::make('errorboard');

});




Route::resource('mails', 'MailsController');
Route::get('mailtest', 'MailsController@test');


Route::get('seedmail', function(){

  $mail = new Mailsender;

  $mail->driver = 'smtp';
  $mail->save();
});


Route::get('userview/{user}', function($id){

    $user = User::findorfail($id);

    return View::make('users.show', compact('user'));
});

Route::get('perms', function(){

    $perm = new Permission;

    $perm->name = 'manage_checkout';
    $perm->display_name = 'Checkout Items';
    $perm->category = 'Inventory';
    $perm->save();


    $perm = new Permission;

    $perm->name = 'manage_checkin';
    $perm->display_name = 'Checkin Items';
    $perm->category = 'Inventory';
    $perm->save();

    $perm = new Permission;

    $perm->name = 'manage_bookings';
    $perm->display_name = 'Manage Bookings';
    $perm->category = 'Inventory';
    $perm->save();

    $perm = new Permission;

    $perm->name = 'manage_maintenance';
    $perm->display_name = 'Manage Maintenance';
    $perm->category = 'Inventory';
    $perm->save();

    $perm = new Permission;

    $perm->name = 'manage_items';
    $perm->display_name = 'Manage Items';
    $perm->category = 'Inventory';
    $perm->save();

    $perm = new Permission;

    $perm->name = 'manage_clients';
    $perm->display_name = 'Manage Clients';
    $perm->category = 'Inventory';
    $perm->save();

    $perm = new Permission;

    $perm->name = 'manage_stores';
    $perm->display_name = 'Manage Stores';
    $perm->category = 'Inventory';
    $perm->save();

    $perm = new Permission;

    $perm->name = 'manage_inventory_reports';
    $perm->display_name = 'View Reports';
    $perm->category = 'Inventory';
    $perm->save();

   

});




Route::get('movements/{tag}', function($tag){

  $item_id = DB::table('items')->where('tag_id', '=', $tag)->pluck('id');
  $item = Item::findorfail($item_id);


  return View::make('movement', compact('item', 'tag'));

});


Route::get('movements/checkout/{id}', function($id){

  
  $item = Item::findorfail($id);

  return View::make('move', compact('item'));

});


Route::get('enddate', function(){

  $enddate = Leaveapplication::getEndDate();

  return $enddate;
});

Route::get('api/pay', function(){
    $id = Input::get('option');
    $employee = Employee::find($id);
    return number_format($employee->basic_pay,2);
});

Route::get('payrollReports/selectYear', function(){
    $employees = Employee::where('organization_id',Confide::user()->organization_id)->get();
    return View::make('pdf.p9Select',compact('employees'));
});

Route::post('payrollReports/p9form', 'ReportsController@p9form');
