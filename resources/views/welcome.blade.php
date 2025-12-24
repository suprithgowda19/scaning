<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="Zeta admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
  <meta name="keywords"
    content="admin template, Zeta admin template, dashboard template, flat admin template, responsive admin template, web app">
  <meta name="author" content="pixelstrap">
  <link rel="icon" href="{{url('/')}}/public/admin/assets/images/biffeslogo.jpeg" type="image/x-icon">
  <link rel="shortcut icon" href="{{url('/')}}/public/admin/assets/images/biffeslogo.jpeg" type="image/x-icon">
  <title>BIFFES dashboard </title>
  <!-- Google font-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
  <link
    href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
    rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
    rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/font-awesome.css">
  <!-- ico-font-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/icofont.css">
  <!-- Themify icon-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/themify.css">
  <!-- Flag icon-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/flag-icon.css">
  <!-- Feather icon-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/feather-icon.css">
  <!-- Plugins css start-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/scrollbar.css">
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/datatables.css">
  <!-- Plugins css Ends-->
  <!-- Bootstrap css-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/vendors/bootstrap.css">
  <!-- App css-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/style.css">
  <link id="color" rel="stylesheet" href="{{url('/')}}/public/admin/assets/css/color-1.css" media="screen">
  <!-- Responsive css-->
  <link rel="stylesheet" type="text/css" href="{{url('/')}}/public/admin/assets/css/responsive.css">
  <link rel="stylesheet" type="text/css" href="{{url('/').'/public/admin/assets/css/vendors/datatable-extension.css'}}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .simplebar-offset {
      height: auto !important;
    }
    .static-widget {
    margin-bottom: 0px;
}

    .footer {
      margin-left: 0px !important;
    }

    .card {
      transition: border 0.3s ease, box-shadow 0.3s ease;
      border: 2px solid transparent;

    }

    .card:hover {
      border: 2px solid #2196f3;
      box-shadow: 0 0 12px rgba(33, 150, 243, 0.4);
    }

    .small-card h6 {
      color: #464c51;
    }

    .small-card h4 {
      color: #1a73e8;
    }


    .right-details-card {
      margin-top: 0 !important;
    }

    .align-top {
      align-items: flex-start !important;
    }
    .bi-none{
      background-image:none !important;
      color:white !important;
    }
  </style>
</head>

<body>
  <!-- tap on top starts-->
  <div class="tap-top"><i data-feather="chevrons-up"></i></div>
  <!-- tap on tap ends-->
  <!-- page-wrapper Start-->
  <div class="page-wrapper compact-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    <div class="page-header">
      <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
          <div class="logo-wrapper">
            <a href="index.html">
              <img class="img-fluid" src="{{url('/')}}/public/admin/assets/images/biffeslogo.jpeg" alt="">
            </a>
          </div>
          <div class="toggle-sidebar">
            <div class="status_toggle sidebar-toggle d-flex">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g>
                  <g>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M21.0003 6.6738C21.0003 8.7024 19.3551 10.3476 17.3265 10.3476C15.2979 10.3476 13.6536 8.7024 13.6536 6.6738C13.6536 4.6452 15.2979 3 17.3265 3C19.3551 3 21.0003 4.6452 21.0003 6.6738Z"
                      stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M10.3467 6.6738C10.3467 8.7024 8.7024 10.3476 6.6729 10.3476C4.6452 10.3476 3 8.7024 3 6.6738C3 4.6452 4.6452 3 6.6729 3C8.7024 3 10.3467 4.6452 10.3467 6.6738Z"
                      stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M21.0003 17.2619C21.0003 19.2905 19.3551 20.9348 17.3265 20.9348C15.2979 20.9348 13.6536 19.2905 13.6536 17.2619C13.6536 15.2333 15.2979 13.5881 17.3265 13.5881C19.3551 13.5881 21.0003 15.2333 21.0003 17.2619Z"
                      stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M10.3467 17.2619C10.3467 19.2905 8.7024 20.9348 6.6729 20.9348C4.6452 20.9348 3 19.2905 3 17.2619C3 15.2333 4.6452 13.5881 6.6729 13.5881C8.7024 13.5881 10.3467 15.2333 10.3467 17.2619Z"
                      stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  </g>
                </g>
              </svg>
            </div>
          </div>
        </div>
        <div class="left-side-header col ps-0 d-none d-md-block"></div>
        <div class="nav-right col-10 col-sm-6 pull-right right-header p-0">
          <ul class="nav-menus">
            <li class="d-md-none resp-serch-input">
              <div class="resp-serch-box">
                <i data-feather="search"></i>
              </div>
              <div class="form-group search-form">
                <input type="text" placeholder="Search here...">
              </div>
            </li>
            <li class="maximize">
              <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g>
                    <g>
                      <path d="M2.99609 8.71995C3.56609 5.23995 5.28609 3.51995 8.76609 2.94995" stroke="#130F26"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path
                        d="M8.76616 20.99C5.28616 20.41 3.56616 18.7 2.99616 15.22L2.99516 15.224C2.87416 14.504 2.80516 13.694 2.78516 12.804"
                        stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path
                        d="M21.2446 12.804C21.2246 13.694 21.1546 14.504 21.0346 15.224L21.0366 15.22C20.4656 18.7 18.7456 20.41 15.2656 20.99"
                        stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M15.2661 2.94995C18.7461 3.51995 20.4661 5.23995 21.0361 8.71995" stroke="#130F26"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                  </g>
                </svg>
              </a>
            </li>
            <li class="profile-nav onhover-dropdown pe-0 py-0 me-0">
              <div class="media profile-media">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g>
                    <g>
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.55851 21.4562C5.88651 21.4562 2.74951 20.9012 2.74951 18.6772C2.74951 16.4532 5.86651 14.4492 9.55851 14.4492C13.2305 14.4492 16.3665 16.4342 16.3665 18.6572C16.3665 20.8802 13.2505 21.4562 9.55851 21.4562Z"
                        stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.55849 11.2776C11.9685 11.2776 13.9225 9.32356 13.9225 6.91356C13.9225 4.50356 11.9685 2.54956 9.55849 2.54956C7.14849 2.54956 5.19449 4.50356 5.19449 6.91356C5.18549 9.31556 7.12649 11.2696 9.52749 11.2776H9.55849Z"
                        stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path
                        d="M16.8013 10.0789C18.2043 9.70388 19.2383 8.42488 19.2383 6.90288C19.2393 5.31488 18.1123 3.98888 16.6143 3.68188"
                        stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path
                        d="M17.4608 13.6536C19.4488 13.6536 21.1468 15.0016 21.1468 16.2046C21.1468 16.9136 20.5618 17.6416 19.6718 17.8506"
                        stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                  </g>
                </svg>
              </div>
              <ul class="profile-dropdown onhover-show-div">
                <li>
                  <a href="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i data-feather="log-in"></i>
                    <span>Log Out</span>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
        <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
            <div class="ProfileCard-details">
            <div class="ProfileCard-realName">name</div>
            </div>
            </div>
                    </script>
        <script class="empty-template"
          type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
      </div>
    </div>
    <!-- Page Header Ends-->
    <!-- Page Body Start-->
    <div class="page-body-wrapper">
      <!-- Page Sidebar Start-->
      <div class="sidebar-wrapper">
        <div>
          <div class="logo-wrapper d-flex align-items-center gap-2">
            <a href="index.html" class="d-flex align-items-center">
              <img class="img-fluid for-light" src="{{url('/')}}/public/admin/assets/images/biffeslogo.jpeg" alt="">
              <img class="img-fluid for-dark" src="{{url('/')}}/public/admin/assets/images/biffeslogo.jpeg" alt="">
            </a>
            <h3 class="mb-0 ms-1 fw-bold">BIFFES</h3>
            <div class="back-btn ms-3">
              <i class="fa fa-angle-left"></i>
            </div>
          </div>
          <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow">
              <i data-feather="arrow-left"></i>
            </div>
            <div id="sidebar-menu">
              <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn">
                  <a href="index.html">
                    <img class="img-fluid" src="{{url('/')}}/public/admin/assets/images/logo-icon.png" alt="">
                  </a>
                  <div class="mobile-back text-end">
                    <span>Back</span>
                  </div>
                </li>
                <li class="sidebar-list">
                  <a class="sidebar-link sidebar-title link-nav" href="#">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g>
                        <g>
                          <path d="M9.07861 16.1355H14.8936" stroke="#130F26" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                          <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.3999 13.713C2.3999 8.082 3.0139 8.475 6.3189 5.41C7.7649 4.246 10.0149 2 11.9579 2C13.8999 2 16.1949 4.235 17.6539 5.41C20.9589 8.475 21.5719 8.082 21.5719 13.713C21.5719 22 19.6129 22 11.9859 22C4.3589 22 2.3999 22 2.3999 13.713Z"
                            stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                      </g>
                    </svg>
                    <span>Dashboard</span>
                  </a>
                </li>
            </div>
            <div class="right-arrow" id="right-arrow">
              <i data-feather="arrow-right"></i>
            </div>
          </nav>
        </div>
      </div>
      <!-- Page Sidebar Ends-->
      <div class="page-body">
        <div class="container-fluid">
          <div class="page-title">
            <div class="row">
              <div class="col-12 col-sm-6">
                <h3>Dashboard</h3>
              </div>
              <div class="col-12 col-sm-6">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#"> <i data-feather="home"></i></a>
                  </li>
                  <li class="breadcrumb-item active">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
          <div class="row">
            <!-- Zero Configuration  Starts-->
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header pb-0">
                  <div class="row g-5 align-items-center">

                    <div class="col-12 col-md-4">
                      <div class="form-control d-flex align-items-center justify-content-center fw-bold"
                        style="height: 38px;">
                        {{ date('d/m/Y')}}
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="form-control d-flex align-items-center justify-content-center fw-bold"
                        style="height: 38px;">
                        Screen : {{$screen->name}}
                      </div>
                    </div>
                    <div class="col-12 col-md-4">
                      <select class="form-select fw-bold" size="1" id="slot" onchange="callApi('counts')">
                        <option value="" disabled>Select Slot</option>
                        @foreach($slots as $slot)
                          <option value="{{$slot->id}}">{{$slot->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row p-3 justify-content-center">
                  <div class="col-12 col-md-6 col-xl-4 mt-3">
                    <div class="card shadow o-hidden myHoverCard">
                      <div class="card-body">

                        <!-- Total Capacity -->
                        <div class="media static-widget mb-3">
                          <div class="media-body">
                            <h6 class="font-roboto">Total Capacity</h6>
                            <h4 class="mb-0 counter">{{$screen->capacity}}</h4>
                          </div>
                          <i data-feather="film" class="feather-icon"></i>
                        </div>

                        <!-- Colored Line -->
                        <div style="height: 4px; width: 100%; background: #4caf50; border-radius: 10px;"></div>

                        <!-- Total Scanning -->
                        <div class="media static-widget mt-3 mb-3">
                          <div class="media-body">
                            <h6 class="font-roboto">Total Scanning</h6>
                            <h4 class="mb-0 counter" id="scanned">{{$scanned}}</h4>
                          </div>
                          <i data-feather="users" class="feather-icon"></i>
                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-6 col-xl-4 mt-3">
                    <div class="card shadow o-hidden myHoverCard">
                      <div class="card-body">

                        <!-- Total Capacity -->
                        <div class="media static-widget mb-3">
                          <div class="media-body">
                            <h6 class="font-roboto">Movie Title</h6>
                            <h4 class="mb-0 counter">Text</h4>
                          </div>
                          <i data-feather="film" class="feather-icon"></i>
                        </div>

                        <!-- Colored Line -->
                        <div style="height: 4px; width: 100%; background: #4caf50; border-radius: 10px;"></div>

                        <!-- Total Scanning -->
                        <div class="media static-widget mt-3 mb-3">
                          <div class="media-body">
                            <h6 class="font-roboto">Duration</h6>
                            <h4 class="mb-0 counter" id="scanned">70 Mins</h4>
                          </div>
                          <i data-feather="clock" class="feather-icon"></i>
                        </div>
                        <!-- Colored Line -->
                        <div style="height: 4px; width: 100%; background: #4caf50; border-radius: 10px;"></div>

                        <!-- Total Scanning -->
                        <div class="media static-widget mt-3 mb-3">
                          <div class="media-body">
                            <h6 class="font-roboto">Language</h6>
                            <h4 class="mb-0 counter" id="scanned">Kannada</h4>
                          </div>
                          <i data-feather="globe" class="feather-icon"></i>
                        </div>

                      </div>
                    </div>
                  </div>


                  <div class="row p-3">

                    <!-- LEFT -->
                    <div class="col-lg-7">
                      <div class="card shadow">
                        <div class="card-body">
                          <h5 class="fw-bold text-white p-2 mb-3 text-center"
                            style="background:#1a237e; border-radius:6px;">
                            Scan QR
                          </h5>

                          <div class="border rounded p-3 text-center mb-4" style="height: 80px;">
                            <span class="fw-bold" id="scanned_id">QR CODE HERE</span><br>
                            <span class="fw-bold text-danger" id="scanned_err"></span>
                          </div>

                          <!-- Small Cards -->
                          <div class="row g-4">

                            <div class="col-12 col-md-6">
                              <div class="card small-card shadow text-center p-3">
                                <h6 class="mb-1">Delegates</h6>
                                <h4 class="fw-bold mb-0" id="delegates">0</h4>
                              </div>
                            </div>

                            <div class="col-12 col-md-6">
                              <div class="card small-card shadow text-center p-3">
                                <h6 class="mb-1">Students</h6>
                                <h4 class="fw-bold mb-0" id="students">0</h4>
                              </div>
                            </div>

                            <div class="col-12 col-md-6">
                              <div class="card small-card shadow text-center p-3">
                                <h6 class="mb-1">Senior Citizens</h6>
                                <h4 class="fw-bold mb-0" id="senior">0</h4>
                              </div>
                            </div>

                            <div class="col-12 col-md-6">
                              <div class="card small-card shadow text-center p-3">
                                <h6 class="mb-1">Film Fraternity</h6>
                                <h4 class="fw-bold mb-0" id="film">0</h4>
                              </div>
                            </div>

                            <div class="col-12 col-md-6">
                              <div class="card small-card shadow text-center p-3">
                                <h6 class="mb-1">Complimentary</h6>
                                <h4 class="fw-bold mb-0" id="compl">0</h4>
                              </div>
                            </div>

                          </div><!-- small cards end -->
                        </div>
                      </div>
                    </div>

                    <!-- RIGHT: Details card -->
                    <div class="col-lg-5 mt-4 mt-lg-0">
                      <div class="card shadow right-details-card">
                        <div class="card-body">
                            <h5 class="fw-bold text-white p-2 mb-3 text-center"
                              style="background:#1a237e; border-radius:6px;">
                              Ticket Details
                            </h5>
                            <div class="loader-box" id="tkt_loader" style="display:none;">
                              <div class="loader-19"></div>
                            </div>
                            <div id="ticket_details">
                              <h5 class="fw-bold text-center mb-3">Please Scan to get details.</h5>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">

                <div class="card-header pb-0">
                  <div class="row g-5 align-items-center">

                    <div class="col-12 col-md-4">
                      <select class="form-select" size="1" id="day2" onchange="getCardCounts()">
                        <option value="" disabled>Select Day</option>
                        <option value="1" selected>Day 1</option>
                        <option value="2">Day 2</option>
                        <option value="3">Day 3</option>
                        <option value="4">Day 4</option>
                        <option value="5">Day 5</option>
                        <option value="6">Day 6</option>
                        <option value="7">Day 7</option>
                      </select>
                    </div>
                    <div class="col-12 col-md-4">
                      <div class="form-control d-flex align-items-center justify-content-center fw-bold"
                        style="height: 38px;">
                        Screen : {{$screen->name}}
                      </div>
                    </div>


                    <div class="col-12 col-md-4">
                      <select class="form-select" size="1" id="slot2" onchange="getCardCounts()">
                        <option>Select Slot</option>
                        @foreach($slots as $slot)
                          <option value="{{$slot->id}}" {{$loop->iteration == 1 ? 'selected' : ''}}>{{$slot->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row g-4 align-items-center mt-1">

                    <div class="col-sm-6  col-lg-4">
                      <div class="card o-hidden shadow">
                        <div class="card-body">
                          <div class="media static-widget mb-1">
                            <div class="media-body">
                              <h6 class="font-roboto">Total Capacity</h6>
                              <h4 class="mb-0 counter">{{$screen->capacity}}</h4>
                            </div>
                            <i data-feather="film" class="feather-icon"></i>
                          </div>

                          <div style="height: 4px; width: 100%; background: #6271e6; border-radius: 10px;"></div>

                          <div class="media static-widget mt-1 mb-1">
                            <div class="media-body">
                              <h6 class="font-roboto">Total Scanning</h6>
                              <h4 class="mb-0 counter" id="scanned_count"></h4>
                            </div>
                            <i data-feather="users" class="feather-icon"></i>
                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                      <div class="card o-hidden shadow">
                        <div class="card-body">
                          <div class="media static-widget">
                            <div class="media-body">
                              <h6 class="font-roboto">Delegates</h6>
                              <h4 class="mb-0 counter" id="delegates_count"></h4>
                            </div>
                            <i data-feather="user" class="feather-icon"></i>
                          </div>
                          <div class="progress-widget">
                            <div class="progress sm-progress-bar progress-animate">
                              <div class="progress-gradient-warning" role="progressbar" style="width: 80%">
                                <span class="animate-circle"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6  col-lg-4">
                      <div class="card o-hidden shadow">
                        <div class="card-body">
                          <div class="media static-widget">
                            <div class="media-body">
                              <h6 class="font-roboto">Students</h6>
                              <h4 class="mb-0 counter" id="students_count"></h4>
                            </div>
                            <i data-feather="book-open" class="feather-icon"></i>
                          </div>
                          <div class="progress-widget">
                            <div class="progress sm-progress-bar progress-animate">
                              <div class="progress-gradient-danger" role="progressbar" style="width: 35%">
                                <span class="animate-circle"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6  col-lg-4">
                      <div class="card o-hidden shadow">
                        <div class="card-body">
                          <div class="media static-widget">
                            <div class="media-body">
                              <h6 class="font-roboto">Senior Citizens</h6>
                              <h4 class="mb-0 counter" id="senior_count"></h4>
                            </div>
                            <i data-feather="camera" class="feather-icon"></i>
                          </div>
                          <div class="progress-widget">
                            <div class="progress sm-progress-bar progress-animate">
                              <div class="progress-gradient-primary" role="progressbar" style="width: 40%">
                                <span class="animate-circle"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                      <div class="card o-hidden shadow">
                        <div class="card-body">
                          <div class="media static-widget">
                            <div class="media-body">
                              <h6 class="font-roboto">Film Fraternity</h6>
                              <h4 class="mb-0 counter" id="film_count"></h4>
                            </div>
                            <i data-feather="video" class="feather-icon"></i>
                          </div>
                          <div class="progress-widget">
                            <div class="progress sm-progress-bar progress-animate">
                              <div class="progress-gradient-secondary" role="progressbar" style="width: 40%">
                                <span class="animate-circle"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-6  col-lg-4">
                      <div class="card o-hidden shadow">
                        <div class="card-body">
                          <div class="media static-widget">
                            <div class="media-body">
                              <h6 class="font-roboto">Complementary</h6>
                              <h4 class="mb-0 counter" id="compl_count"></h4>
                            </div>
                            <i data-feather="users" class="feather-icon"></i>
                          </div>
                          <div class="progress-widget">
                            <div class="progress sm-progress-bar progress-animate">
                              <div class="progress-gradient-success" role="progressbar" style="width: 60%"
                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"><span
                                  class="animate-circle"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="row align-items-center mt-2">
                    <div class="col-12 col-md-4 mb-2 mb-md-0">
                       <button class="btn btn-primary export-btn" type="button"><i class="fa-solid fa-arrows-rotate"></i> Refresh Table</button>
                    </div>
                    <div
                      class="col-12 col-md-8 d-flex justify-content-md-end justify-content-start gap-2 flex-md-row flex-column flex-lg-row mt-2 mt-md-0">
                      <button class="btn btn-primary export-btn" type="button">Excel
                        Export</button>
                      <button class="btn btn-primary export-btn" type="button">PDF Export</button>
                    </div>

                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="display" id="list-table">
                      <thead>
                        <tr>
                          <th>Sl.No</th>
                          <th>Name</th>
                          <th>Phone No</th>
                          <th>Gender</th>
                          <th>Category</th>
                          <th>Email</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- Zero Configuration  Ends-->

            <!-- Container-fluid Ends-->
          </div>
          <!-- footer start-->
          <footer class="footer">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12 footer-copyright text-center">
                  <p class="mb-0">Copyright {{date('Y')}} © Mcware Technologies</p>
                </div>
              </div>
            </div>
          </footer>
        </div>
      </div>

      <script>
        const today = new Date().toISOString().split("T")[0];
        document.getElementById("daypicker").value = today;
      </script>

      <!-- latest jquery-->
      <script src="{{url('/')}}/public/admin/assets/js/jquery-3.5.1.min.js"></script>
      <!-- Bootstrap js-->
      <script src="{{url('/')}}/public/admin/assets/js/bootstrap/bootstrap.bundle.min.js"></script>
      <!-- feather icon js-->
      <script src="{{url('/')}}/public/admin/assets/js/icons/feather-icon/feather.min.js"></script>
      <script src="{{url('/')}}/public/admin/assets/js/icons/feather-icon/feather-icon.js"></script>
      <!-- scrollbar js-->
      <script src="{{url('/')}}/public/admin/assets/js/scrollbar/simplebar.js"></script>
      <script src="{{url('/')}}/public/admin/assets/js/scrollbar/custom.js"></script>
      <!-- Sidebar jquery-->
      <script src="{{url('/')}}/public/admin/assets/js/config.js"></script>
      <!-- Plugins JS start-->
      <script src="{{url('/')}}/public/admin/assets/js/sidebar-menu.js"></script>
      <script src="{{url('/')}}/public/admin/assets/js/datatable/datatables/jquery.dataTables.min.js"></script>
      <script src="{{url('/')}}/public/admin/assets/js/datatable/datatables/datatable.custom.js"></script>
      <script src="{{url('/').'/public/admin/assets/js/datatable/datatable-extension/dataTables.buttons.min.js'}}"></script>
      <script src="{{url('/').'/public/admin/assets/js/datatable/datatable-extension/buttons.colVis.min.js'}}"></script>
      <!-- Plugins JS Ends-->
      <!-- Theme js-->
      <script src="{{url('/')}}/public/admin/assets/js/script.js"></script>
      <script src="{{url('/')}}/public/admin/assets/js/theme-customizer/customizer.js"></script>
      <!-- login js-->
      <!-- Plugin used-->

      <script>
        let buffer = "";
        let lastKeyTime = Date.now();

        document.addEventListener("keydown", function (e) {
          let now = Date.now();
          let diff = now - lastKeyTime;

          // Scanner types fast (<40ms)
          if (diff < 40) buffer += e.key;
          else buffer = e.key;  // new scan

          lastKeyTime = now;

          if (e.key === "Enter") {
            let code = buffer.replace("Enter", "").trim();
            buffer = "";
            if (code.length > 0) handleScan(code);
          }
        });

        function handleScan(rawCode) {
          document.getElementById("scanned_id").textContent = "";
          document.getElementById("scanned_err").textContent = ""
          // Extract UUID from "UUID: <uuid>"
          let uuid = extractUUID(rawCode);
          console.log(uuid);

          if (!uuid) {
            document.getElementById("scanned_err").textContent = "Invalid ID! Please Scan Again.";
            return;
          }
          document.getElementById("scanned_id").textContent = uuid;

          callApi(uuid);
        }

        function extractUUID(text) {
            // Accepts UUIDs with - or /
            const regex = /[0-9a-fA-F]{8}[-\/][0-9a-fA-F]{4}[-\/][0-9a-fA-F]{4}[-\/][0-9a-fA-F]{4}[-\/][0-9a-fA-F]{12}/;
            const match = text.match(regex);
            
            // Convert / back to - for API use
            return match ? match[0].replace(/\//g, "-") : null;
        }

        function callApi(uuid) {
          $('#tkt_loader').show();
          let slot =  $('#slot').val();
          fetch("{{ route('screen.ticket', ['slot' => 'slot_id', 'uuid' => 'uid']) }}".replace('slot_id', slot).replace('uid', uuid))
            .then(res => res.json())
            .then(data => {
              $('#tkt_loader').hide();
              if(uuid != 'counts')
              document.getElementById("ticket_details").innerHTML = data.html;
              $('#scanned').text(data.scanned);
              $("#delegates").text(data.delegates);
              $('#students').text(data.students);
              $('#senior').text(data.senior);
              $('#film').text(data.film);
              $('#compl').text(data.compl);
            })
            .catch(err => {
              console.log(err);
              
            });
        }
        
        callApi('counts');

      </script>

      <script>
        function getCardCounts(){
          let slot = $('#slot2').val();
          let day = $('#day2').val();
          fetch("{{ route('screen.card_counts', ['slot' => 'slot_id', 'day' => 'day_no']) }}".replace('slot_id', slot).replace('day_no', day))
            .then(res => res.json())
            .then(data => {
              $('#scanned_count').text(data.scanned);
              $('#delegates_count').text(data.delegates);
              $('#students_count').text(data.students);
              $('#senior_count').text(data.senior);
              $('#film_count').text(data.film);
              $('#compl_count').text(data.compl);
            })
            .catch(err => {
              console.log(err);
              
            });
        }
        getCardCounts();

        $(document).ready(function() {
          $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          var table = $('#list-table').DataTable({
            dom: '<"d-flex justify-content-between"l<B>f>tip',
            buttons: [
                {
                    extend: 'colvis',
                    className: 'btn btn-info bi-none',
                    text: 'Show/Hide Columns'
                }
            ], 
            language: {
                'buttons': {
                    'colvis': 'Show/Hide Columns'
                }
            },
            processing: false,
            serverSide: true,
            ajax: {
              url: "{{route('screen.table_data')}}",
                data: function(data) {
                  data.day = $("#day2").val();
                  data.slot = $("#slot2").val();
                }
            },
            order: [[0, 'desc']],
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'phone' },
                { data: 'gender' },
                { data: 'category' },
                { data: 'email' },
            ]
          });
          $('#day2').bind("change", function(){
              table.draw();
          });
          $('#slot2').bind("change", function(){
              table.draw();
          });
        });
      </script>
</body>

</html>