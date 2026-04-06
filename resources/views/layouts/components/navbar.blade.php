<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">

        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

            <!-- Messages -->
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fa fa-envelope"></i>
                </a>

                <ul class="dropdown-menu messages-notif-box animated fadeIn">
                    <li>
                        <div class="dropdown-title d-flex justify-content-between align-items-center">
                            Messages
                            <a href="#" class="small">Mark all as read</a>
                        </div>
                    </li>

                    <li>
                        <div class="message-notif-scroll scrollbar-outer" style="max-height:250px;">
                            <div class="notif-center" id="messages-list">
                                <p class="text-muted p-2 mb-0">Belum ada pesan</p>
                            </div>
                        </div>
                    </li>

                    <li>
                        <a class="see-all" href="#">See all messages <i class="fa fa-angle-right"></i></a>
                    </li>
                </ul>
            </li>


            <!-- Follow Up Notification -->
            <li class="nav-item topbar-icon dropdown hidden-caret">
                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" data-bs-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                    <span id="followup-count" class="notification">0</span>
                </a>

                <ul class="dropdown-menu notif-box animated fadeIn">
                    <li>
                        <div class="dropdown-title">
                            Follow-Up Hari Ini
                        </div>
                    </li>

                    <li>
                        <div class="notif-scroll scrollbar-outer" style="max-height:250px;">
                            <div class="notif-center" id="followup-list">

                                <p class="text-muted p-2 mb-0">
                                    Tidak ada follow-up hari ini
                                </p>

                            </div>
                        </div>
                    </li>

                    <li>
                        <a class="see-all" href="{{ route('followups.index') }}">
                            Lihat Semua Follow-Up
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
            </li>


            <!-- Quick Actions -->
            <li class="nav-item topbar-icon dropdown hidden-caret">

                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="fas fa-layer-group"></i>
                </a>

                <div class="dropdown-menu quick-actions animated fadeIn">

                    <div class="quick-actions-header">
                        <span class="title mb-1">Quick Actions</span>
                        <span class="subtitle op-7">Shortcuts</span>
                    </div>

                    <div class="quick-actions-scroll scrollbar-outer">

                        <div class="quick-actions-items">
                            <div class="row m-0">

                                <a class="col-6 col-md-4 p-0" href="#">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-danger rounded-circle">
                                            <i class="far fa-calendar-alt"></i>
                                        </div>
                                        <span class="text">Calendar</span>
                                    </div>
                                </a>

                                <a class="col-6 col-md-4 p-0" href="#">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-warning rounded-circle">
                                            <i class="fas fa-map"></i>
                                        </div>
                                        <span class="text">Maps</span>
                                    </div>
                                </a>

                                <a class="col-6 col-md-4 p-0" href="{{ route('reports.index') }}">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-info rounded-circle">
                                            <i class="fas fa-file-excel"></i>
                                        </div>
                                        <span class="text">Reports</span>
                                    </div>
                                </a>

                                <a class="col-6 col-md-4 p-0" href="#">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-success rounded-circle">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <span class="text">Emails</span>
                                    </div>
                                </a>

                                <a class="col-6 col-md-4 p-0" href="#">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-primary rounded-circle">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <span class="text">Invoice</span>
                                    </div>
                                </a>

                                <a class="col-6 col-md-4 p-0" href="#">
                                    <div class="quick-actions-item">
                                        <div class="avatar-item bg-secondary rounded-circle">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <span class="text">Payments</span>
                                    </div>
                                </a>

                            </div>
                        </div>

                    </div>

                </div>

            </li>


            <!-- User Profile -->
            <li class="nav-item topbar-user dropdown hidden-caret">

                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown">

                    <div class="avatar-sm">
                        <img src="{{ asset('assets/img/profile.jpg') }}"
                             class="avatar-img rounded-circle">
                    </div>

                    <span class="profile-username">
                        <span class="op-7">Hi,</span>
                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                    </span>

                </a>

                <ul class="dropdown-menu dropdown-user animated fadeIn">

                    <li>

                        <div class="user-box">

                            <div class="avatar-lg">
                                <img src="{{ asset('assets/img/profile.jpg') }}"
                                     class="avatar-img rounded">
                            </div>

                            <div class="u-text">
                                <h4>{{ Auth::user()->name }}</h4>
                                <p class="text-muted">{{ Auth::user()->email }}</p>
                                <a href="#" class="btn btn-xs btn-secondary btn-sm">
                                    View Profile
                                </a>
                            </div>

                        </div>

                    </li>

                    <li>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="#">My Profile</a>
                        <a class="dropdown-item" href="#">Inbox</a>
                        <a class="dropdown-item" href="#">Settings</a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item"
                           href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">

                           Logout
                        </a>

                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              method="POST"
                              style="display:none;">
                              @csrf
                        </form>

                    </li>

                </ul>

            </li>

        </ul>

    </div>
</nav>


<!-- FOLLOWUP SCRIPT -->
<script>

document.addEventListener("DOMContentLoaded", function () {

    async function fetchFollowupsToday() {

        try {

            const res = await fetch("{{ route('marketing.followups.today') }}");

            if(!res.ok) return;

            const data = await res.json();

            const count = document.getElementById('followup-count');
            const list  = document.getElementById('followup-list');

            if(!count || !list) return;

            if(data.length === 0){

                count.innerText = 0;

                list.innerHTML =
                '<p class="text-muted p-2 mb-0">Tidak ada follow-up hari ini</p>';

                return;
            }

            count.innerText = data.length;

            let html = '';

            data.forEach(function(f){

                html += `
                <a href="{{ route('followups.index') }}">
                    <div class="notif-icon notif-warning">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="notif-content">
                        <span class="block">${f.konsumen ? f.konsumen.nama : '-'}</span>
                        <span class="time">${f.follow_up_date}</span>
                    </div>
                </a>
                `;

            });

            list.innerHTML = html;

        }
        catch(e){
            console.log("Followup error",e);
        }

    }

    fetchFollowupsToday();

    setInterval(fetchFollowupsToday,30000);

});

</script>
