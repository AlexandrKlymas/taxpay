@extends('layout.base_en')

@section('base_content')
    <div id="wrapper">
        <header id="main-header" class="main-header">
            <div class="container header-container">
                <a href="/" class="logo">
                    <img src="/theme/images/logo.svg" alt="" title="">
                </a>
                <div class="search-wrap">
                    <button id="search-trigger" class="search-trigger" aria-label="search" aria-labelledby="search"></button>
                    <div class="search" id="searchBlock">
                        <form class="form">
                            <input name="search" v-model="inputValue" type="text" autocomplete="off" placeholder="Search">
                            <input type="submit" class="search-submit" value=" ">
                        </form>
                        <ul class="search-dropdown" :class="{active: dropdownState}">
                            <template v-if="searchArray.length">
                                <li v-for="searchItem in searchArray">
                                    <a :href="searchItem.link">@{{searchItem.title}}</a>
                                </li>
                            </template>
                            <template v-else>
                                <li class="search-empty">
                                    Your search did not match any pages
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
                <div class="header-right">
                    <nav class="main-nav">
                        <ul>

                            @foreach($menu as $item)
                                @if(isset($item['children']))
                                    <li class="menu-item-has-children">
                                        <a href="{{ UrlProcessor::makeUrl( $item['id'] ) }}">{{ $item['pagetitle'] }}</a>
                                        <ul>
                                            @foreach($item['children'] as $child)
                                                <li class="{{ $child['id'] == $documentObject['id'] ?'current-menu-item':''}}">
                                                    <a href="{{ UrlProcessor::makeUrl( $child['id'] ) }}">{{ $child['pagetitle'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li><a href="{{ UrlProcessor::makeUrl( $item['id'] ) }}">{{ $item['pagetitle'] }}</a></li>
                                @endif

                            @endforeach

                        </ul>
                    </nav>
                    <a href="#feedbackModal" class="btn-callback" data-toggle="modal">Feedback</a>
                </div>
                <button id="nav-trigger" class="nav-trigger" aria-label="Menu" aria-labelledby="main-header">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </header>
        <main role="main" class="main @stack('addClass')">
            <div class="container">
                @yield('content')
            </div>

        </main>

        <footer class="main-footer">
            <div class="container footer-container">
                <div class="image-row">
				<span class="img">
					<span><img src="/theme/images/visa.png" alt=""></span>
				</span>
                <span class="img">
					<span><img src="/theme/images/master-card.png" alt=""></span>
				</span>
                <span class="img">
					<span><img src="/theme/images/prostir.png" alt=""></span>
				</span>
                <span class="img">
					<span><img src="/theme/images/g-pay.png" alt=""></span>
				</span>
                <span class="img">
					<span><img src="/theme/images/apple-pay.png" alt=""></span>
				</span>
                <span class="img">
					<span><img src="/theme/images/liqpay.png" alt=""></span>
				</span>
                <span class="img">
					<span><img src="/theme/images/privatbank.png" alt=""></span>
				</span>

                </div>
                <div class="copyright">
                    <p>© 2014-{{ date('Y') }}. Government Payments LLC</p>
                    <p>All rights are protected by the legislation of Ukraine on copyright.</p>
                </div>
            </div>
        </footer>
    </div>
@endsection