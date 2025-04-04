<nav class="nav flex-column">
    <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>
    <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ url('/admin/categories') }}">
        <i class="fas fa-folder"></i> Categories
    </a>
    <a class="nav-link {{ request()->is('admin/exams*') ? 'active' : '' }}" href="{{ url('/admin/exams') }}">
        <i class="fas fa-file-alt"></i> Exams
    </a>
    <a class="nav-link {{ request()->is('admin/topics*') ? 'active' : '' }}" href="{{ url('/admin/topics') }}">
        <i class="fas fa-book"></i> Topics
    </a>
    <a class="nav-link {{ request()->is('admin/questions*') ? 'active' : '' }}" href="{{ url('/admin/questions') }}">
        <i class="fas fa-question-circle"></i> Questions
    </a>
    <a class="nav-link {{ request()->is('admin/question-papers*') ? 'active' : '' }}" href="{{ url('/admin/question-papers') }}">
        <i class="fas fa-file-invoice"></i> Question Papers
    </a>
    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ url('/admin/users') }}">
        <i class="fas fa-users"></i> Users
    </a>
    <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ url('/admin/settings') }}">
        <i class="fas fa-cog"></i> Settings
    </a>
</nav>
