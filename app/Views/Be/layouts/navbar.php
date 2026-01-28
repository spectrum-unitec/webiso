<!-- BEGIN #sidebar -->
<div id="sidebar" class="app-sidebar">
    <!-- BEGIN scrollbar -->
    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
        <!-- BEGIN menu -->
        <div class="menu">
            <div class="menu-header">Navigation</div>
            <div class="menu-item <?= nav_active_route('admin.masterdata'); ?>">
                <a href="<?= base_url(route_to('admin.masterdata')); ?>" class="menu-link">
                    <span class="menu-icon"><i class="fa fa-database"></i></span>
                    <span class="menu-text">Master Data</span>
                </a>
            </div>
            <div class="menu-item <?= nav_active_route('admin.mydoc'); ?>">
                <a href="<?= base_url(route_to('admin.mydoc')); ?>" class="menu-link">
                    <span class="menu-icon"><i class="far fa-file-pdf"></i></span>
                    <span class="menu-text">My Document</span>
                </a>
            </div>
            <div class="menu-item">
                <a href="<?= base_url(route_to('logout')); ?>" class="menu-link">
                    <span class="menu-icon"><i class="fa fa-sign-out-alt"></i></span>
                    <span class="menu-text">Log Out</span>
                </a>
            </div>
        </div>
        <!-- END menu -->
    </div>
    <!-- END scrollbar -->

    <!-- BEGIN mobile-sidebar-backdrop -->
    <button class="app-sidebar-mobile-backdrop" data-dismiss="sidebar-mobile"></button>
    <!-- END mobile-sidebar-backdrop -->
</div>
<!-- END #sidebar -->