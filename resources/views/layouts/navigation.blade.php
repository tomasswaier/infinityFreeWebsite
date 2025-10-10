<nav x-data="{ open: false }" class="bg-project-light-blue text-project-super-blue">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left Side -->
            <div class="hidden sm:flex sm:gap-7 ">
                <!-- Admin Page -->
                <div class="shrink-0 flex items-center">
                @if(Auth::user() && Auth::user()->authorization != 'user')
                    <x-nav-link :href="route('adminPage')" :active="request()->routeIs('adminPage')">
                        {{ __('Admin Page') }}
                    </x-nav-link>
                @endif
                </div>

                <!-- Main Page -->

                <x-nav-link :href="route('mainPage')" :active="request()->routeIs('mainPage')">
                    {{ __('Main Page') }}
                </x-nav-link>

                <!-- Conditional School Links -->
                @isset($school_id)
                    <x-nav-link :href="url('school/'.$school_id)">
                        {{ __('School Page') }}
                    </x-nav-link>
                    <x-nav-link :href="url('test/'.$school_id)">
                        {{ __('Test Page') }}
                    </x-nav-link>
                    <x-nav-link :href="url('subjects/'.$school_id)">
                        {{ __('Subjects Page') }}
                    </x-nav-link>
                @endisset

                <!-- Login (if guest) -->
                @guest
                    <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Log In') }}
                    </x-nav-link>
                @endguest
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Dropdown -->
                    <div class="ml-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-project-super-blue bg-white hover:text-project-dark-blue focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.info')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-project-super-blue hover:text-project-dark-blue hover:bg-project-white focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-project-light-blue">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('mainPage')" :active="request()->routeIs('mainPage')">
                {{ __('Main Page') }}
            </x-responsive-nav-link>

            @isset($school_id)
                <x-responsive-nav-link :href="url('school/'.$school_id)">
                    {{ __('School Page') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="url('test/'.$school_id)">
                    {{ __('Test Page') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="url('subjects/'.$school_id)">
                    {{ __('Subjects Page') }}
                </x-responsive-nav-link>
            @endisset

            @auth
                <x-responsive-nav-link :href="route('profile.info')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            @else
                <x-responsive-nav-link :href="route('login')">
                    {{ __('Log In') }}
                </x-responsive-nav-link>
            @endauth
        </div>
    </div>
</nav>

