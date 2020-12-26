<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-9 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="footer-heading mb-4">{{ __('frontend.footer.about') }}</h2>
                        <p>{{ $site_global_settings->setting_site_about }}</p>
                    </div>

                    <div class="col-md-3">
                        <h2 class="footer-heading mb-4">{{ __('frontend.footer.navigations') }}</h2>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('page.about') }}">{{ __('frontend.footer.about-us') }}</a></li>
                            <li><a href="{{ route('page.contact') }}">{{ __('frontend.footer.contact-us') }}</a></li>
                            @if($site_global_settings->setting_page_terms_of_service_enable == \App\Setting::TERM_PAGE_ENABLED)
                                <li><a href="{{ route('page.terms-of-service') }}">{{ __('frontend.footer.terms-of-service') }}</a></li>
                            @endif
                            @if($site_global_settings->setting_page_privacy_policy_enable == \App\Setting::PRIVACY_PAGE_ENABLED)
                                <li><a href="{{ route('page.privacy-policy') }}">{{ __('frontend.footer.privacy-policy') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h2 class="footer-heading mb-4">{{ __('frontend.footer.follow-us') }}</h2>
                        @foreach(\App\SocialMedia::orderBy('social_media_order')->get() as $key => $social_media)
                            <a href="{{ $social_media->social_media_link }}" class="pl-0 pr-3">
                                <i class="{{ $social_media->social_media_icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <h2 class="footer-heading mb-4">{{ __('frontend.footer.posts') }}</h2>
                <ul class="list-unstyled">
                    @foreach(\Canvas\Post::published()->orderByDesc('published_at')->take(5)->get() as $key => $post)
                        <li><a href="{{ route('page.blog.show', $post->slug) }}">{{ $post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>


        <div class="row pt-2 mt-5 pb-2 text-right" style="
    display: none;
">
            <div class="col-md-12">
                <span class="text-white pr-2">
                    {{ __('backend.setting.language.language') }}:
                </span>
                <a class="btn btn-sm btn-outline-secondary text-white rounded" id="btn-language-selector">
                    @if(app()->getLocale() == \App\Setting::LANGUAGE_AR)
                        {{ __('backend.setting.language.arabic') }}
                   
                    @elseif(app()->getLocale() == \App\Setting::LANGUAGE_EN)
                        {{ __('backend.setting.language.english') }}
       
                  
                    @endif
                </a>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-md-12">
                <div class="border-top pt-5">
                    <p>
                        {{ __('frontend.footer.copyright') }} &copy; {{ empty($site_global_settings->setting_site_name) ? config('app.name', 'Laravel') : $site_global_settings->setting_site_name }} <script>document.write(new Date().getFullYear());</script> {{ __('frontend.footer.rights-reserved') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</footer>

<!-- Modal -->
<div class="modal fade" id="language-selector-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.setting.language.select-language') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row ml-1 mr-1">
                    <div class="col-md-auto p-1">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_AR }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.arabic') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
              
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_DE }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.german') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_EN }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.english') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_ES }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.spanish') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_FA }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.persian-farsi') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_FR }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.french') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_HI }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.hindi') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_NL }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.dutch') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_PT_BR }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.portuguese-brazil') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_RU }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.russian') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_TR }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.turkish') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-auto p-1" style="
    display: none;
">
                        <form action="{{ route('page.locale.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="text" hidden name="user_prefer_language" value="{{ \App\Setting::LANGUAGE_ZH_CN }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                {{ __('backend.setting.language.chinese') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
