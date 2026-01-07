<?php
// Determine the current tab for navigation highlighting
$current_tab = isset($tab) ? $tab : (isset($_GET['tab']) ? $_GET['tab'] : 'discover');
?>
<!-- Premium Header Navigation - Responsive -->
<nav class="navbar navbar-expand-lg landing-header">
    <div class="container-fluid px-3 px-lg-5">
        <!-- Logo -->
        <a class="navbar-brand landing-logo me-0" href="index.php">
            <i class="fa fa-tasks"></i> EWU Events
        </a>
        
        <!-- Mobile Toggle (Three Dots) -->
        <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-ellipsis-v text-primary fs-4"></i>
        </button>

        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Centered Nav Links -->
            <ul class="navbar-nav mx-auto mb-3 mb-lg-0 nav-pills-premium text-center">
                <li class="nav-item">
                    <a href="index.php?tab=discover" class="nav-link <?=($current_tab=='discover')?'active':''?>">Discover</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?tab=notices" class="nav-link <?=($current_tab=='notices')?'active':''?>">Notices</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?tab=events" class="nav-link <?=($current_tab=='events')?'active':''?>">Events</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?tab=clubs" class="nav-link <?=($current_tab=='clubs')?'active':''?>">Clubs</a>
                </li>
            </ul>

            <!-- Action Button -->
            <div class="d-flex justify-content-center">
                <?php if(isset($_SESSION['role'])): ?>
                    <a href="dashboard.php" class="btn btn-primary fw-bold rounded-pill px-4 w-100 w-lg-auto">
                        <i class="fa fa-th-large"></i> Dashboard
                    </a>
                <?php else: ?>
                    <button class="btn btn-primary fw-bold rounded-pill px-4 w-100 w-lg-auto" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fa fa-sign-in"></i> Login
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var collapseEl = document.getElementById('navbarContent');
    if (!collapseEl) return;

    var toggler = document.querySelector('[data-bs-target="#navbarContent"]');
    var hasBootstrap = (typeof bootstrap !== 'undefined' && bootstrap.Collapse);

    var hideMenu = function () {
        if (hasBootstrap) {
            var inst = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
            inst.hide();
            return;
        }
        collapseEl.classList.remove('show');
        if (toggler) toggler.setAttribute('aria-expanded', 'false');
    };

    var toggleMenu = function () {
        if (hasBootstrap) {
            var inst = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
            if (collapseEl.classList.contains('show')) inst.hide();
            else inst.show();
            return;
        }
        var isOpen = collapseEl.classList.contains('show');
        if (isOpen) hideMenu();
        else {
            collapseEl.classList.add('show');
            if (toggler) toggler.setAttribute('aria-expanded', 'true');
        }
    };

    if (toggler && !hasBootstrap) {
        toggler.addEventListener('click', function (e) {
            e.preventDefault();
            toggleMenu();
        });
    }

    document.addEventListener('click', function (e) {
        if (!collapseEl.classList.contains('show')) return;

        if (collapseEl.contains(e.target)) return;
        if (toggler && toggler.contains(e.target)) return;

        hideMenu();
    });

    var links = collapseEl.querySelectorAll('a.nav-link');
    links.forEach(function (link) {
        link.addEventListener('click', function () {
            hideMenu();
        });
    });
});
</script>
