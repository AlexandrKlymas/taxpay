<div class="sidebar">
    <div class="position-sticky">
        <h1 class="main-title">Оплата державних та муніципальних послуг</h1>
        <div class="department-info">
            <div class="image">
                <img src="{{ $currentDepartmentForSidebar->image }}" alt="{{ $currentDepartmentForSidebar->pagetitle }}">
            </div>
            <p class="department-name">{{ $currentDepartmentForSidebar->pagetitle }}</p>
            <div class="description">
                <p>{{ $currentDepartmentForSidebar->description }}</p>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul>
                @foreach($departmentsForSidebar as $department)
                    <li><a href="{{ UrlProcessor::makeUrl($department->id) }}">{{ $department->pagetitle }}</a></li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>