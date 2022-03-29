<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet"/>
        <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <title>COMPANIES</title>
        <style>
            .side-navbar {
                width: 180px;
                height: 100%;
                position: fixed;
                margin-left: -300px;
                background-color: #100901;
                transition: 0.5s;
            }
            .nav-link:active,
            .nav-link:focus,
            .nav-link:hover {
                background-color: #ffffff26;
            }
            .my-container {
                transition: 0.4s;
            }
            .active-nav {
                margin-left: 0;
            }
            /* for main section */
            .active-cont {
                margin-left: 180px;
            }
            #menu-btn {
                background-color: #100901;
                color: #fff;
                margin-left: -62px;
            }
            .content {
                background-color: white;
                padding: 10px;
            }
            .error {
                color: red;
                padding: 10px;
            }
            .modal { overflow: auto !important; }
        </style>
    </head>
    <body>
    <!-- Side-Nav -->
        <div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar">
            <ul class="nav flex-column text-white w-100">
                <a href="#" class="nav-link h3 text-white">COMPANY </a>
                <a href="{{ route('employees') }}" class="nav-link text-white">
                    <li href="#" class="nav-link">
                        <i class="bx bx-user-check"></i> <span class="">EMPLOYEES</span>
                    </li>
                </a>
                <a href="{{ route('companies') }}" class="nav-link text-white">
                    <li href="#" class="nav-link">
                        <i class="bx bxs-dashboard"></i> <span class="">COMPANIES</span>
                    </li>
                </a>
                <a href="{{ route('logout') }}" class="nav-link text-white"> 
                    <li href="#" class="nav-link">
                        <i class="bx bx-left-arrow-circle"></i> <span class="">LOGOUT</span>
                    </li>
                </a>
            </ul>
        </div>
        @yield('content')
        <script src="	https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="	https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        @yield('scripts')
    </body>
</html>