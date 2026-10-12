@extends('layouts.admin')

@section('title', ($mode === 'create' ? 'افزودن دسته' : 'ویرایش دسته').' | مدیریت سایت')
@section('tab', $mode === 'create' ? 'درج دسته جدید' : 'ویرایش دسته')

@section('content')
    <h1 class="page-title">{{ $mode === 'create' ? 'افزودن دسته‌بندی' : 'ویرایش دسته‌بندی' }}</h1>

    <div class="panel">
        <div class="panel-head">
            <h2>اطلاعات دسته</h2>
            <a class="btn btn-default btn-sm" href="{{ route('admin.categories.index') }}">بازگشت</a>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ $mode === 'create' ? route('admin.categories.store') : route('admin.categories.update', $category) }}">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label>نام</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')<div class="error-text">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>دسته والد</label>
                    <select class="shop-select" name="parent_id">
                        <option value="">— ریشه —</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">ذخیره</button>
            </form>
        </div>
    </div>
@endsection
