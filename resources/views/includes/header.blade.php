<!DOCTYPE html>

<html lang="en">



<body>



    <!-- ======= Header ======= -->

    <header id="header" class="header fixed-top d-flex align-items-center">



        <div class="d-flex align-items-center justify-content-between">

            <a href="{{ url('/dashboard') }}" class="logo d-flex align-items-center">

                <img src="{{ asset('assets/img/code4each_logo.png') }}" alt="Code4Each">

                <!-- <span class="d-none d-lg-block">Management</span> -->

            </a>

            <i class="bi bi-list toggle-sidebar-btn"></i>    

        </div><!-- End Logo -->







        <nav class="header-nav ms-auto">

            <ul class="d-flex align-items-center">



                <li class="nav-item d-block d-lg-none">

                    <a class="nav-link nav-icon search-bar-toggle " href="#">

                        <i class="bi bi-search"></i>

                    </a>

                </li>



                <!-- </li>End Messages Nav -->

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">

                    @if (auth()->user()->profile_picture)

                    <img src="{{asset('assets/img/').'/'.auth()->user()->profile_picture}}" id="profile_picture" alt="Profile" height="50px" width="50px" class="rounded-circle picture js-profile-picture">

                    @else

                    <img src="{{asset('assets/img/blankImage.jpg')}}" id="profile_picture" alt="Profile" height="50px" width="50px" class="rounded-circle picture js-profile-picture">

                    @endif



                </a>

                <li class="nav-item dropdown pe-3">



                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                        <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->

                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->first_name ?? " " }}</span>

                    </a><!-- End Profile Iamge Icon -->



                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                        <li class="dropdown-header">

                            <div class="row">

                                <div class="col-md-4">

                                    @if (auth()->user()->profile_picture)

                                    <img src="{{asset('assets/img/').'/'.auth()->user()->profile_picture}}" id="profile_picture" alt="Profile" height="50px" width="50px" class="rounded-circle picture js-profile-picture">

                                    @else

                                    <img src="{{asset('assets/img/blankImage.jpg')}}" id="profile_picture" alt="Profile" height="50px" width="50px" class="rounded-circle picture js-profile-picture">

                                    @endif

                                </div>

                                <div class="col-md-5">

                                    <h6>{{ auth()->user()->first_name ?? " " }}</h6>

                                    <span>{{ auth()->user()->role->name ?? " " }}</span>

                                </div>

                            </div>

                        </li>

                        <li>

                            <hr class="dropdown-divider">

                        </li>

                        <li>

                        <li>

                            <a class="dropdown-item d-flex align-items-center" href="{{route('profile')}}">

                                <i class="bi bi-person"></i>

                                <span>My Profile</span>

                            </a>

                        </li>

                        <hr class="dropdown-divider">



                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout')}}">

                            <i class="bi bi-box-arrow-right"></i>

                            <span>Log Out</span>

                        </a>

                </li>



            </ul><!-- End Profile Dropdown Items -->

            </li><!-- End Profile Nav -->



            </ul>

        </nav><!-- End Icons Navigation -->



        @if(session()->has('message'))

        <div class="alert alert-success header-alert fade show" role="alert" id="header-alert">

            <i class="bi bi-check-circle me-1"></i>

            {{ session()->get('message') }}

        </div>

        @endif



        @if(session()->has('error'))



        <div class="alert alert-danger header-alert fade show" role="alert" id="header-alert">

            <i class="bi bi-exclamation-octagon me-1"></i>

            {{ session()->get('error') }}

        </div>

        @endif



    </header><!-- End Header -->



    <!-- ======= Sidebar ======= -->

    <aside id="sidebar" class="sidebar">



        <ul class="sidebar-nav" id="sidebar-nav">



            <li class="nav-item">

                <a class="nav-link {{ request()->is('dashboard') ? '' : 'collapsed' }}" href="{{ url('/dashboard') }}">

                    <i class="bi bi-grid"></i>

                    <span>Dashboard</span>

                </a>

            </li><!-- End Dashboard Nav -->

            @if(auth()->user()->role->name == 'Super Admin')

            <li class="nav-item">

                <a class="nav-link {{ request()->is('pages') ? '' : 'collapsed' }}" href="{{ route('pages.index') }}">

                    <!-- <i class="bi bi-buildings"></i> -->

                    <i class="bi bi-file-earmark-fill"></i>

                    <span>Pages</span>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link {{ request()->is('modules') ? '' : 'collapsed' }}" href="{{ route('modules.index') }}">

                    <!-- <i class="bi bi-buildings"></i> -->

                    <i class="bi bi-file-earmark-fill"></i>

                    <span>Modules</span>

                </a>

            </li>



            <!-- <li class="nav-item">

                <a class="nav-link {{ request()->is('clients') ? '' : 'collapsed' }}" href="{{ route('clients.index') }}">

                    <i class="bi bi-person-square"></i>

                    <span>Clients</span>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link {{ request()->is('departments') ? '' : 'collapsed' }}" href="{{ route('departments.index') }}">

                    <i class="bi bi-buildings"></i>

                    <span>Departments</span>

                </a>

            </li> -->

            <li class="nav-item">

                <a class="nav-link {{ request()->is('role') ? '' : 'collapsed' }}" href="{{ route('roles.index') }}">

                    <i class="bi bi-people"></i>

                    <span>Roles</span>

                </a>

            </li>

            @endif

            @if (auth()->user()->role->name == 'HR Manager')

            <li class="nav-item">

                <a class="nav-link {{ request()->is('departments') ? '' : 'collapsed' }}" href="{{ route('departments.index') }}">

                    <i class="bi bi-buildings"></i>

                    <span>Departments</span>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link {{ request()->is('role') ? '' : 'collapsed' }}" href="{{ route('roles.index') }}">

                    <i class="bi bi-people"></i>

                    <span>Roles</span>

                </a>

            </li>





            @endif

            @if (auth()->user()->role->name !== 'Employee')
                <li class="nav-item">

                    <a class="nav-link {{ request()->is('users') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">

                        <i class="bi bi-person-square"></i>

                        <span>Users

                        </span>

                    </a>

                </li>
            @endif
            
            @if(auth()->user()->role->name !== 'Super Admin' && auth()->user()->role->name != 'Employee')

            <!-- <li class="nav-item">

                <a class="nav-link {{ request()->is('attendance/team') ? '' : 'collapsed' }} show" href="{{ route('attendance.team.index') }}">

                    <i class="bi bi-person-vcard-fill"></i>

                    <span>Attendance</span>

                </a>

            </li> -->

            <li class="nav-item">

                <a class="nav-link {{ request()->is('attendance') ? '' : 'collapsed' }}" data-bs-target="#attendance-nav" data-bs-toggle="collapse" href="#">

                    <i class="bi bi-person-vcard-fill"></i></i><span>Attendance</span><i class="bi bi-chevron-down ms-auto"></i>

                </a>

                <ul id="attendance-nav" class="nav-content collapse {{ request()->is('attendance') || request()->is('attendance/team') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

                    <li>

                        <a class="{{ request()->is('attendance') ? 'active' : 'collapsed' }}" href="{{ route('attendance.index') }}" href="">

                            <i class="bi bi-circle "></i><span>My Attendance</span>

                        </a>

                    </li>

                    <li>

                        <a class="{{ request()->is('attendance/team') ? 'active' : 'collapsed ' }}" href="{{ route('attendance.team.index')}}">

                            <i class="bi bi-circle"></i><span>Team Attendance</span>

                        </a>

                    </li>

                </ul>

            </li>

            @endif

            @if(auth()->user()->role->name == 'Super Admin')

            <li class="nav-item">

                <a class="nav-link {{ request()->is('leaves/team') ? '' : 'collapsed' }}" href=" {{ route('leaves.team.index')}}">

                    <i class="bi bi-menu-button-wide"></i>

                    <span>Leaves</span>

                </a>

            </li>

            @else

            <li class="nav-item">

                <a class="nav-link {{ request()->is('leaves') ? '' : 'collapsed' }}" data-bs-target="#leaves-nav" data-bs-toggle="collapse" href="#">

                    <i class="bi bi-layout-text-window-reverse"></i><span>Leaves</span><i class="bi bi-chevron-down ms-auto"></i>

                </a>

                <ul id="leaves-nav" class="nav-content collapse {{ request()->is('leaves') || request()->is('leaves/team') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

                    <li>

                        <a class=" {{ request()->is('leaves') ? 'active' : 'collapsed' }} " href=" {{ route('leaves.index') }}">

                            <i class="bi bi-circle "></i><span>My Leaves</span>

                        </a>

                    </li>

                    <li>

                        <a class=" {{ request()->is('leaves/team') ? 'active' : 'collapsed' }} " href=" {{ route('leaves.team.index')}}">

                            <i class="bi bi-circle"></i><span>Team Leaves</span>

                        </a>

                    </li>

                </ul>

            </li>

            @endif

            @if (auth()->user()->role->name == 'HR Manager' || auth()->user()->role->name == 'Super Admin')

            <li class="nav-item">

                <a class="nav-link {{ request()->is('leaves/team1') ? '' : 'collapsed' }}" href="{{ url('all_leave_type') }}">

                    <i class="bi bi-menu-button-wide"></i>

                    <span>Leaves Types</span>

                </a>

            </li>

            @endif



            <!-- <li class="nav-item">

                <a class="nav-link {{ request()->is('projects') ? '' : 'collapsed' }}" href="{{ route('projects.index') }}">

                    <i class="bi bi-list-task"></i> <span>Projects</span>

                </a>

            </li> -->



            <!-- <li class="nav-item">

                <a class="nav-link {{ request()->is('todo_list') ? '' : 'collapsed' }}" href="{{ route('todo_list.index') }}">

                    <i class="bi bi-journal-code"></i> <span>ToDo</span>

                </a>

            </li>



            <li class="nav-item">

                <a class="nav-link {{ request()->is('tickets') ? '' : 'collapsed' }}" href="{{ route('tickets.index') }}">

                    <i class="bi bi-journal-code"></i> <span>Tickets</span>

                </a>

            </li> -->


            @if(auth()->user()->role->name !== 'Super Admin')
            <li class="nav-item">

                <a class="nav-link {{ request()->is('holidays') ? '' : 'collapsed' }}" href="{{ route('holidays.index') }}">

                    <i class="bi bi-calendar-check"></i>

                    <span>Holidays</span>

                </a>

            </li>
            @endif

            @if (auth()->user()->role->name == 'HR Manager' || auth()->user()->role->name == 'Super Admin')

            <li class="nav-item">

                <a class="nav-link {{ request()->is('holidays') ? '' : 'collapsed' }}" href="{{ route('calander.index') }}">

                    <!-- <i class="bi bi-buildings"></i> -->

                    <i class="bi bi-calendar-check"></i>

                    <span>Calendar</span>

                </a>

            </li>

            @endif

            @if (auth()->user()->role->name == 'HR Manager' || auth()->user()->role->name == 'Super Admin')

            <!-- <li class="nav-item">

                <a class="nav-link {{ request()->is('devices') ? '' : 'collapsed' }}" data-bs-target="#devices-nav" data-bs-toggle="collapse" href="#">

                    <i class="bi bi-person-vcard-fill"></i></i><span>Devices</span><i class="bi bi-chevron-down ms-auto"></i>

                </a>

                <ul id="devices-nav" class="nav-content collapse {{ request()->is('devices') || request()->is('assigned-devices') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

                    <li>

                        <a class="{{ request()->is('devices') ? 'active' : 'collapsed' }}" href="{{ route('devices.index') }}" href="">

                            <i class="bi bi-circle "></i><span>All Devices</span>

                        </a>

                    </li>

                    <li>

                        <a class="{{ request()->is('assigned-devices') ? 'active' : 'collapsed ' }}" href="{{ route('devices.assigned.index')}}">

                            <i class="bi bi-circle"></i><span>Assigned Devices</span>

                        </a>

                    </li>

                </ul>

            </li> -->



            <!-- <li class="nav-item">

                <a class="nav-link {{ request()->is('policies') ? '' : 'collapsed' }}" href="{{ route('policies.index') }}">

                    <i class="bi bi-files"></i> <span>Policies</span>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link {{ request()->is('hireus') ? '' : 'collapsed' }}" href="{{ route('hireus.index') }}">

                    <i class="bi bi-person-square"></i> <span>Hire Us</span>

                </a>

            </li> -->



            <!-- <li class="nav-item">

                <a class="nav-link {{ request()->is('jobs') ? '' : 'collapsed' }}" data-bs-target="#job-cat-nav" data-bs-toggle="collapse" href="#">

                    <i class="bi bi-layout-text-window-reverse"></i><span>Jobs</span><i class="bi bi-chevron-down ms-auto"></i>

                </a>

                <ul id="job-cat-nav" class="nav-content collapse {{ request()->is('jobs') || request()->is('job-categories') ||  request()->is('applicants') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

                    <li>

                        <a class=" {{ request()->is('jobs') ? 'active' : 'collapsed' }} " href=" {{ route('jobs.index') }}">

                            <i class="bi bi-circle "></i><span>Jobs</span>

                        </a>

                    </li>

                    <li>

                        <a class=" {{ request()->is('job-categories') ? 'active' : 'collapsed' }} " href=" {{ route('job_categories.index')}}">

                            <i class="bi bi-circle"></i><span>Job Categories</span>

                        </a>

                    </li>



                    <li>

                        <a class=" {{ request()->is('applicants') ? 'active' : 'collapsed' }} " href=" {{ route('applicants.index')}}">

                            <i class="bi bi-circle"></i><span>Applicants</span>

                        </a>

                    </li>

                </ul>

            </li> -->



            @endif





        </ul>



    </aside><!-- End Sidebar-->









</body>



</html>