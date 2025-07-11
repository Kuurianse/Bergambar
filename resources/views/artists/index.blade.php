@extends('layouts.app')

@section('title', 'Browse Artists')

@section('css_tambahan')
    <link rel="stylesheet" href="{{ asset('css/artist-index.css') }}" />
@endsection

@section('content')
    <section class="heading">
        <h3>Browse Artists</h3>
        <p>Discover talented artists and their amazing work</p>
    </section>

    <section class="artist">
        <div class="artist-container">
            @if($artists->isEmpty())
                <p>No artist profiles available at the moment.</p>
            @else
            <div class="cards-grid-container">
                @foreach($artists as $artistProfile)
                <div class="card">
                    <div class="ichi">
                        <div class="photo-wrapper">
                            <div class="photo">
                                <img src="{{ $artistProfile->user->profile_picture ? asset('storage/' . $artistProfile->user->profile_picture) : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80' }}" alt="Photo Profile" style="width: 100%; height: 100%; object-fit: cover" />
                            </div>
                            @if($artistProfile->is_verified)
                            <div class="verified-badge">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M19.2565 7.58082C20.3446 8.36457 21.0834 9.57916 21.0834 11C21.0834 12.4208 20.3446 13.6363 19.2565 14.4192C19.4765 15.741 19.1337 17.1307 18.1317 18.1317C17.128 19.1354 15.7392 19.4691 14.4229 19.2546C13.6428 20.3463 12.4172 21.0833 11.0001 21.0833C9.57925 21.0833 8.36191 20.3436 7.58 19.2537C6.26183 19.4691 4.87216 19.1363 3.8675 18.1317C2.86375 17.1279 2.53008 15.7382 2.75191 14.4192C1.66475 13.6372 0.916748 12.4217 0.916748 11C0.916748 9.57824 1.66475 8.36182 2.75191 7.58082C2.53008 6.26174 2.86375 4.87207 3.86841 3.86832C4.87033 2.86549 6.26091 2.52357 7.58733 2.74357C8.36466 1.65366 9.582 0.916656 11.0001 0.916656C12.4163 0.916656 13.6401 1.65274 14.4211 2.74357C15.7429 2.52357 17.1307 2.86732 18.1317 3.86832C19.1337 4.86932 19.4774 6.25899 19.2565 7.58082ZM15.1993 7.50382C15.2973 7.57376 15.3806 7.66231 15.4444 7.76442C15.5081 7.86654 15.5512 7.98021 15.571 8.09896C15.5908 8.2177 15.5871 8.33919 15.5599 8.45649C15.5328 8.57378 15.4829 8.68459 15.4129 8.78257L10.8296 15.1992C10.7522 15.3076 10.6522 15.3978 10.5364 15.4636C10.4206 15.5294 10.2919 15.5691 10.1592 15.5801C10.0265 15.5911 9.89303 15.5731 9.76802 15.5272C9.64301 15.4814 9.52948 15.4089 9.43533 15.3147L6.68533 12.5647C6.51835 12.3919 6.42596 12.1603 6.42805 11.92C6.43013 11.6796 6.52654 11.4497 6.6965 11.2797C6.86645 11.1098 7.09637 11.0134 7.33671 11.0113C7.57706 11.0092 7.80861 11.1016 7.9815 11.2686L9.96608 13.2532L13.9206 7.71649C14.062 7.51872 14.2761 7.38521 14.5159 7.34533C14.7557 7.30545 15.0016 7.36246 15.1993 7.50382Z" fill="#3AA0FF" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="profile-info">
                            <div class="username">{{ $artistProfile->user->username ?? $artistProfile->user->name }}</div>
                            <div class="name">{{ $artistProfile->user->name }}</div>
                            @if(!is_null($artistProfile->rating) && $artistProfile->rating > 0)
                            <div class="rating">
                                <span><svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.43754 0H6.56254L5.16839 4.29076L0.6568 4.29077L0.0773926 6.074L3.72735 8.72584L2.33321 13.0165L3.85013 14.1187L7.50005 11.4668L11.15 14.1187L12.6669 13.0165L11.2727 8.72582L14.9227 6.07399L14.3432 4.29076H9.83169L8.43754 0Z" fill="#FFC107" />
                                    </svg>
                                </span>
                                {{ number_format($artistProfile->rating, 1) }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="ni">
                        <div class="commissions">
                            <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.88889 4.11111V3.48889C4.88889 2.6177 4.88889 2.1821 5.05844 1.84935C5.20757 1.55665 5.44554 1.31868 5.73824 1.16955C6.07099 1 6.50659 1 7.37778 1H8.62222C9.49341 1 9.92904 1 10.2618 1.16955C10.5545 1.31868 10.7925 1.55665 10.9416 1.84935C11.1111 2.1821 11.1111 2.6177 11.1111 3.48889V4.11111M11.1111 4.11111H4.73333C4.50723 4.11111 4.30068 4.11111 4.11111 4.11243M11.1111 4.11111H11.8889M4.11111 15V4.11243M4.11111 4.11243C3.20505 4.11873 2.68679 4.15512 2.27402 4.36543C1.83498 4.58913 1.47802 4.94609 1.25432 5.38513C1 5.88426 1 6.53762 1 7.84444V11.2667C1 12.5735 1 13.2268 1.25432 13.726C1.47802 14.1651 1.83498 14.522 2.27402 14.7457C2.77315 15 3.42654 15 4.73333 15H11.2667C12.5735 15 13.2268 15 13.726 14.7457C14.1651 14.522 14.522 14.1651 14.7457 13.726C15 13.2268 15 12.5735 15 11.2667V7.84444C15 6.53762 15 5.88426 14.7457 5.38513C14.522 4.94609 14.1651 4.58913 13.726 4.36543C13.2268 4.11111 12.5735 4.11111 11.2667 4.11111H11.8889M11.8889 15V4.11111" stroke="#57606E" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            {{ $artistProfile->user->commissions_count ?? 0 }} Commission
                        </div>
                        {{-- <div class="services">
                            <span><svg width="11" height="15" viewBox="0 0 11 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.55001 10.1C8.06291 10.1 10.1 8.06291 10.1 5.55001C10.1 3.03711 8.06291 1 5.55001 1C3.03711 1 1 3.03711 1 5.55001C1 8.06291 3.03711 10.1 5.55001 10.1Z" stroke="#57606E" />
                                    <path opacity="0.5" d="M2.52812 9.45001L2.11416 10.96C1.70574 12.4496 1.50153 13.1945 1.77403 13.6023C1.86954 13.7452 1.99765 13.8599 2.14632 13.9355C2.57053 14.1515 3.22551 13.8103 4.53545 13.1277C4.97134 12.9006 5.18929 12.7871 5.42082 12.7624C5.50668 12.7532 5.59313 12.7532 5.679 12.7624C5.91053 12.7871 6.12847 12.9006 6.56436 13.1277C7.87431 13.8103 8.52932 14.1515 8.95351 13.9355C9.10216 13.8599 9.23028 13.7452 9.32576 13.6023C9.59831 13.1945 9.39408 12.4496 8.98568 10.96L8.5717 9.45001" stroke="#57606E" stroke-linecap="round" />
                                </svg> </span>
                            {{ $artistProfile->services_count ?? 0 }} Services
                        </div> --}}
                    </div>
                    @if($artistProfile->portfolio_link)
                        <a href="{{ $artistProfile->portfolio_link }}" target="_blank" class="link-portfolio">View Portfolio →</a>
                    @endif
                    <a href="{{ route('artists.show', $artistProfile->id) }}" class="btn-view-profile">View Profile</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination-buttons-wrapper">
            {{ $artists->links() }}
        </div>
    </div>
@endsection
