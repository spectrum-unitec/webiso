<div id="header" class="app-header">
    <!-- BEGIN mobile-toggler -->
    <div class="mobile-toggler">
        <button type="button" class="menu-toggler" data-toggle="sidebar-mobile">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END mobile-toggler -->

    <!-- BEGIN brand -->
    <div class="brand">
        <div class="desktop-toggler">
            <button type="button" class="menu-toggler" data-toggle="sidebar-minify">
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>

        <a href="index.html" class="brand-logo sp-2">
           <h3>WebISO</h3>
        </a>
    </div>
    <!-- END brand -->

    <!-- BEGIN menu -->
    <div class="menu justify-content-end">
        <div class="menu-item dropdown">
            <a href="#" data-bs-toggle="dropdown" data-display="static" class="menu-link">
                <div class="menu-img online">
                    <img src="<?= base_url(); ?>assets/img/user.png" alt="" class="ms-100 mh-100 rounded-circle">
                </div>
                <div class="menu-text"><?= auth()->user()->email; ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-end me-lg-3">
                <a class="dropdown-item d-flex align-items-center" href="profile.html">Edit Profile <i class="fa fa-user-circle fa-fw ms-auto text-body text-opacity-50"></i></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="page_login.html">Log Out <i class="fa fa-toggle-off fa-fw ms-auto text-body text-opacity-50"></i></a>
            </div>
        </div>
    </div>
    <!-- END menu -->
</div>