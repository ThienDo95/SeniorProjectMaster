<nav class="navbar navbar-default ">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="/">
                        PI Face Validation
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->



                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">


                        <!-- Authentication Links -->
                       
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                   

               

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false"><i class="fa fa-user"></i> ME <i
                                        class="fa fa-caret-down"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                          
                              <!--<li><a href="{{ url('admin/product/create') }}"> Admin Create Product</a></li>
                                <li><a href="{{ url('admin/product') }}"> Admin View Products</a></li>
                                  <li><a href="{{ url('admin/orders') }}"> Admin Orders</a></li>
                                  <li><a href="{{ url('admin/category') }}"> Admin Categories</a></li>-->

                
                        <li> <a href="/profile.php">
                                    My Profile
                                </a></li>
                          <li role="presentation" class="divider"></li>
                      
                            <li>
                                <a href="/logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                                </ul>
                            </li>
                       
                    </ul>
                </div>
            </div>
        </nav>
