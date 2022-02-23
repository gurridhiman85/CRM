@php
    $segment = Request::segment(1);
    $segment2 = Request::segment(2);
@endphp


<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="{!! ($segment == "taxonomy") ? 'active' : '' !!}">
                    <a
                        href="taxonomy"
                        class="waves-effect waves-dark {!! ($segment == "taxonomy") ? 'active' : '' !!}"
                        aria-expanded="false">
                        <i class="ti-text"></i>
                        <span class="hide-menu">Taxonomy</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

