<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="/dashboard">SIMEVAL</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="/dashboard">SMV</a>
        </div>
        <ul class="sidebar-menu">
          <?php $user = Auth::user();?>
            <li class="menu-header">Dashboard</li>
            <li class="{{$pageName=='Dashboard Simonev'?'active':''}}"><a class="nav-link active"
                    href="{{url('/')}}"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
           
            <li class="menu-header">Master Data</li>

            <li class="{{request()->segment(1)=='officer'?'active':''}}"><a class="nav-link"
                    href="{{url('officer/')}}"><i class="far fa-user"></i><span>Kasat</span></a></li>
            <li class="{{request()->segment(1)=='period'?'active':''}}"><a class="nav-link" href="{{url('/period')}}"><i
                        class="far fa-calendar"></i><span>Periode Penilaian</span></a></li>
            <li class="{{request()->segment(1)=='rank_category'?'active':''}}"><a class="nav-link" href="{{url('/rank_category')}}"><i
                          class="far fa-chart-bar"></i><span>Kategori Kinerja</span></a></li>
            <li class="{{request()->segment(1)=='criteria'?'active':''}}"><a class="nav-link"
                    href="{{url('/criteria')}}"><i class="far fa-check-circle"></i><span>Kriteria Performance</span></a></li>
            <li class="{{request()->segment(1)=='monitoring_criteria'?'active':''}}"><a class="nav-link"
                    href="{{url('/monitoring_criteria')}}"><i class="far fa-hourglass"></i><span>Kriteria
                        Monitoring</span></a></li>

   

            <li class="menu-header">Performance & Monitoring</li>
            <li class="{{request()->segment(1)=='performance_report'||request()->segment(1)=="performance_report_form"?'active':''}}"><a class="nav-link active"
                    href="{{url('/performance_report')}}"><i class="fas fa-envelope"></i><span>Laporan Performance</span></a></li>
            <li class="{{request()->segment(1)=='monitoring_report'||request()->segment(1)=="monitoring_report_form"?'active':''}}"><a class="nav-link active"
                    href="{{url('/monitoring_report')}}"><i class="fas fa-file"></i><span>Laporan Monitoring</span></a></li>
           

          
            <li class="menu-header">Laporan</li>
            <li class="{{request()->segment(1)=='performance_rank'?'active':''}}"><a class="nav-link active"
                    href="{{url('/performance_rank')}}"><i class="fas fa-signal"></i><span>Performance Ranking</span></a></li>
            <li class="{{request()->segment(1)=='monitoring_progress'?'active':''}}"><a class="nav-link active"
                    href="{{url('/monitoring_progress/progress_form')}}"><i class="fas fa-hourglass"></i><span>Monitoring Ranking</span></a></li>
            <li class="menu-header">Pengguna</li>
            @if($user->level=="Admin" || $user->level=="Unsur Penilai")
            <li class="{{request()->segment(1)=='user' && request()->segment(2)==""?'active':''}}"><a class="nav-link active"
                    href="{{url('/user')}}"><i class="fas fa-users"></i><span>Daftar Pengguna</span></a></li>
            @endif
            <li class="@if(request()->segment(1)=='user' && request()->segment(2)=="profile") active @endif}}"><a class="nav-link active"
                      href="{{url('/user/profile')}}"><i class="fas fa-user"></i><span>Profil</span></a></li>


            <!-- <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-file-alt"></i> <span>Forms</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="forms-advanced-form.html">Advanced Form</a></li>
                  <li><a class="nav-link" href="forms-editor.html">Editor</a></li>
                  <li><a class="nav-link" href="forms-validation.html">Validation</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-map-marker-alt"></i> <span>Google Maps</span></a>
                <ul class="dropdown-menu">
                  <li><a href="gmaps-advanced-route.html">Advanced Route</a></li>
                  <li><a href="gmaps-draggable-marker.html">Draggable Marker</a></li>
                  <li><a href="gmaps-geocoding.html">Geocoding</a></li>
                  <li><a href="gmaps-geolocation.html">Geolocation</a></li>
                  <li><a href="gmaps-marker.html">Marker</a></li>
                  <li><a href="gmaps-multiple-marker.html">Multiple Marker</a></li>
                  <li><a href="gmaps-route.html">Route</a></li>
                  <li><a href="gmaps-simple.html">Simple</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-plug"></i> <span>Modules</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="modules-calendar.html">Calendar</a></li>
                  <li><a class="nav-link" href="modules-chartjs.html">ChartJS</a></li>
                  <li><a class="nav-link" href="modules-datatables.html">DataTables</a></li>
                  <li><a class="nav-link" href="modules-flag.html">Flag</a></li>
                  <li><a class="nav-link" href="modules-font-awesome.html">Font Awesome</a></li>
                  <li><a class="nav-link" href="modules-ion-icons.html">Ion Icons</a></li>
                  <li><a class="nav-link" href="modules-owl-carousel.html">Owl Carousel</a></li>
                  <li><a class="nav-link" href="modules-sparkline.html">Sparkline</a></li>
                  <li><a class="nav-link" href="modules-sweet-alert.html">Sweet Alert</a></li>
                  <li><a class="nav-link" href="modules-toastr.html">Toastr</a></li>
                  <li><a class="nav-link" href="modules-vector-map.html">Vector Map</a></li>
                  <li><a class="nav-link" href="modules-weather-icon.html">Weather Icon</a></li>
                </ul>
              </li> 
              <li class="menu-header">Pages</li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Auth</span></a>
                <ul class="dropdown-menu">
                  <li><a href="auth-forgot-password.html">Forgot Password</a></li>
                  <li><a href="auth-login.html">Login</a></li>
                  <li><a class="beep beep-sidebar" href="auth-login-2.html">Login 2</a></li>
                  <li><a href="auth-register.html">Register</a></li>
                  <li><a href="auth-reset-password.html">Reset Password</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-exclamation"></i> <span>Errors</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="errors-503.html">503</a></li>
                  <li><a class="nav-link" href="errors-403.html">403</a></li>
                  <li><a class="nav-link" href="errors-404.html">404</a></li>
                  <li><a class="nav-link" href="errors-500.html">500</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-bicycle"></i> <span>Features</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="features-activities.html">Activities</a></li>
                  <li><a class="nav-link" href="features-post-create.html">Post Create</a></li>
                  <li><a class="nav-link" href="features-posts.html">Posts</a></li>
                  <li><a class="nav-link" href="features-profile.html">Profile</a></li>
                  <li><a class="nav-link" href="features-settings.html">Settings</a></li>
                  <li><a class="nav-link" href="features-setting-detail.html">Setting Detail</a></li>
                  <li><a class="nav-link" href="features-tickets.html">Tickets</a></li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-ellipsis-h"></i> <span>Utilities</span></a>
                <ul class="dropdown-menu">
                  <li><a href="utilities-contact.html">Contact</a></li>
                  <li><a class="nav-link" href="utilities-invoice.html">Invoice</a></li>
                  <li><a href="utilities-subscribe.html">Subscribe</a></li>
                </ul>
              </li>
              <li><a class="nav-link" href="credits.html"><i class="fas fa-pencil-ruler"></i> <span>Credits</span></a></li>-->
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{route('logout.post')}}" class="btn btn-griff btn-lg btn-block btn-icon-split">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>
</div>
