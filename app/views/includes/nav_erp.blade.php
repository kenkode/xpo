 <nav class="navbar-default navbar-static-side" role="navigation">
    
           


            <div class="sidebar-collapse">

                <ul class="nav" id="side-menu">

                

                   <li>
                    <a href="{{ URL::to('checks') }}"><i class="fa fa-random fa-fw"></i>  Check Out / Check in</a>
                  </li>

                   <li>
                    <a href="{{ URL::to('bookings') }}"><i class="glyphicon glyphicon-th fa-fw"></i> Bookings</a>
                  </li>


                  <li  >
                    <a href="#"  >
                        <i class="fa fa-th-large fa-fw"></i>  Maintenance <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="nav ">
                        <li>
                            <a href="{{ URL::to('maintenances') }}"><i class="fa fa-th fa-fw"></i>  Maintenance</a>
                        </li>
                        
                        
                        <li>
                            <a href="{{ URL::to('tests') }}"><i class="fa fa-list fa-fw"></i> Tests</a>
                        </li>
                        
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                  
                  <li><a href="#"><i class="fa fa-th-large fa-fw"></i>Orders<i class="fa fa-caret-down fa-fw"></i></a>
                    <ul class="nav">
                      <li><a href="{{ URL::to('salesorders') }}"><i class="fa fa-list fa-fw"></i>Sales Orders</a></li>
                      <li><a href="{{ URL::to('purchaseorders') }}"><i class="fa fa-list fa-fw"></i>Purchase Orders</a></li>
                    </ul>
                  </li>

                  <li>
                    <a href="{{ URL::to('quotationorders') }}"><i class="fa fa-list fa-fw"></i>Quotation</a>
                  </li>

                  
                  <li>
                    <a href="{{ URL::to('items') }}"><i class="glyphicon glyphicon-barcode fa-fw"></i> Items</a>
                  </li>


                  <li>
                    <a href="{{ URL::to('itemcategories') }}"><i class="glyphicon glyphicon-tasks fa-fw"></i> Item Categories</a>
                  </li>

                  <li>
                    <a href="{{ URL::to('clients') }}"><i class="fa fa-group fa-fw"></i> Clients</a>
                  </li>


                   

                   <!--
               
                  <li>
                    <a href="{{ URL::to('salesorders') }}"><i class="glyphicon glyphicon-list fa-fw"></i> Quotations</a>
                  </li>
                     -->   
                  
                 


                  
                
                <!--
                  <li>
                    <a href="{{ URL::to('stocks') }}"><i class="glyphicon glyphicon-random fa-fw"></i>  Stock</a>
                  </li>
                  -->

                  <li>
                    <a href="{{ URL::to('locations') }}"><i class="glyphicon glyphicon-home fa-fw"></i>  Stores</a>
                  </li>   

<!--
                  <li>

                    <a href="#"><i class="glyphicon glyphicon-folder-open fa-fw"></i>  Reports</a>
                  </li> 


                  <li>
                    <a href="#"><i class="glyphicon glyphicon-upload fa-fw"></i>  Migrate</a>
                  </li>    -->
@if(Confide::user()->hasRole('SUPERADMIN') || Confide::user()->hasRole('ACCOUNTS MANAGER'))
                  <li>

                    <a href="{{ URL::to('assets') }}"><i class="glyphicon glyphicon-list fa-fw"></i>  Asset Register</a>
                  </li> 
@endif

                  <li>

                    <a href="{{ URL::to('invreports') }}"><i class="glyphicon glyphicon-folder-open fa-fw"></i>  Reports</a>
                  </li> 


                  


                    
                    
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </nav>
        <!-- /.navbar-static-side -->
