<form action="{{ route('page.search.do') }}" method="POST">
    @csrf
    <div class="row align-items-center">
        <div class="col-lg-12 mb-4 mb-xl-0 col-xl-3">
            <div class="select-wrap">
                <span class="icon"><span class="icon-keyboard_arrow_down"></span></span>
                <select class="form-control select rounded @error('categories') is-invalid @enderror" name="categories">
                    <option value="0">{{ __('frontend.search.all-categories') }}</option>
                    @foreach($search_all_categories as $category)
                    <option
                        {{ $category->id == (old('categories') ? old('categories') : (isset($last_search_category) ? $last_search_category : 0)) ? 'selected' : '' }}
                        value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
                @error('categories')
                <div class="invalid-tooltip">
                    {{ $message }}
                </div>
                @enderror
            </div>


        </div>
        <div class="col-lg-12 mb-4 mb-xl-0 col-xl-2">
            <div class="select-wrap">
                <span class="icon"><span class="icon-keyboard_arrow_down"></span></span>
                <select class="form-control select  rounded @error('categories') is-invalid @enderror ras"
                    id="select_state_id" name="city_state[]">
                    <option value="0"> جميع المحافظات</option>
                  
                    @foreach(DB::table('states')->orderBy('id', 'desc')->get() as $category)
                    <option   {{ ( $category->id == Session::get('governorates_id')) ? 'selected' : '' }}  value="{{ $category->id }}">{{ $category->state_name }}</option>
                    @endforeach
                </select>
                @error('categories')
                <div class="invalid-tooltip">
                    {{ $message }}
                </div>
                @enderror
            </div>


        </div>
        <div class="col-lg-12 mb-4 mb-xl-0 col-xl-2">
            <div class="select-wrap">
                <span class="icon"><span class="icon-keyboard_arrow_down"></span></span>
                <select class="form-control osd rounded select @error('categories') is-invalid @enderror"
                    id="select_city_id" name="city_state[]">
                    <option value="0">جميع المدن </option>

                </select>
                @error('categories')
                <div class="invalid-tooltip">
                    {{ $message }}
                </div>
                @enderror
            </div>


        </div>
        <div class="col-lg-12 mb-4 mb-xl-0 col-xl-3">
            <input name="search_query" type="text"
                class="form-control rounded @error('search_query') is-invalid @enderror"
                value="{{ old('search_query') ? old('search_query') : (isset($last_search_query) ? $last_search_query : '') }}"
                placeholder="{{ __('frontend.search.what-are-you-looking-for') }}">
            @error('search_query')
            <div class="invalid-tooltip">
                {{ $message }}
            </div>
            @enderror
        </div>


        <div class="col-lg-12 col-xl-2 ml-auto text-right">
            <input type="submit" class="btn btn-primary btn-block rounded text-white"
                value="{{ __('frontend.search.search') }}">
        </div>

    </div>
</form>