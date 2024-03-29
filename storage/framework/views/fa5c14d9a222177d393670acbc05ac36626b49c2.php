<div class="sidebar_wrapper">
    <h5 class="sidebar-header title m-3 pb-3 border-bottom">
        <img class="profile_picture img-fluid" src="<?php echo e(url('images/profile.jpg')); ?>" alt="Profile Picture">
        <span class="ml-3 sidebar_small-hide"><?php echo e(auth()->user()->name); ?></span>
    </h5>
    <div class="sidebar-contents">
        <ul>
            <li><a class="d-flex align-items-center" href="<?php echo e(route('app.dashboard')); ?>"><i class="mx-3 fas fa-home"></i>
                    <span class="sidebar_small-hide"> Dashboard</span></a></li>
            <?php if(auth()->user()->role_id == 1): ?>
                <li><a class="d-flex align-items-center" href="<?php echo e(route('app.users')); ?>"><i class="mx-3 fas fa-user"></i>
                        <span class="sidebar_small-hide"> User</span> </a></li>
                <li><a class="d-flex align-items-center" href="<?php echo e(route('app.quiz')); ?>"><i
                                class="mx-3 fas fa-newspaper"></i> <span class="sidebar_small-hide"> Quiz</span> </a>
                </li>
                <li><a class="d-flex align-items-center" href="<?php echo e(route('app.departments')); ?>"><i
                                class="mx-3 fas fa-th"></i> <span class="sidebar_small-hide"> Departments</span> </a>
                </li>
                <li><a class="d-flex align-items-center" href="<?php echo e(route('app.reminders')); ?>"><i
                                class="mx-3 fas fa-bell"></i> <span class="sidebar_small-hide"> Reminders </span> </a>
                </li>
                <li><a class="d-flex align-items-center" href="<?php echo e(route('app.reports')); ?>"><i
                                class="mx-3 fas fa-chart-bar"></i> <span class="sidebar_small-hide"> Quiz Reports </span>
                    </a></li>
            <?php endif; ?>
            <?php if(auth()->user()->role_id == 2): ?>
                <li><a class="d-flex align-items-center" href="<?php echo e(route('app.quiz.staff')); ?>"><i
                                class="mx-3 fas fa-newspaper"></i> <span class="sidebar_small-hide"> My Quizzes</span>
                    </a></li>
            <?php endif; ?>
            <li><a class="d-flex align-items-center" href="<?php echo e(route('app.settings')); ?>"><i class="mx-3 fas fa-cogs"></i>
                    <span class="sidebar_small-hide"> Setting</span> </a></li>

        </ul>
    </div>
    <div class="sidebar-footer js_height-footer border-top">
        <a href="<?php echo e(route('app.logout')); ?>"><i class="fas fa-sign-out-alt mx-3"></i><span class="sidebar_small-hide"> Log Out</span></a>
    </div>
</div>