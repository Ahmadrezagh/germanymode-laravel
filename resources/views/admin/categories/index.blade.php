@extends('layouts.admin')

@section('title', 'دسته‌بندی‌ها | مدیریت سایت')
@section('tab', 'دسته بندی کالاها')

@section('content')
    <h1 class="page-title">
        چیدمان طبقات فروشگاهی (دسته بندی کالاها)
        <a class="btn btn-green btn-sm" href="{{ route('admin.products.create') }}" style="margin-right:auto">+ ایجاد کالا</a>
    </h1>

    <div class="panel">
        <div class="panel-head">
            <h2>لیست دسته‌ها</h2>
            <a class="btn btn-success btn-sm" href="{{ route('admin.categories.create') }}">دسته جدید</a>
        </div>
        <div class="panel-body table-wrap">
            <table class="data">
                <thead>
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>والد</th>
                    <th>تعداد محصول</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->parent?->name ?? '—' }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td style="white-space:nowrap">
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.categories.edit', $category) }}">ویرایش</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post" style="display:inline" onsubmit="return confirm('حذف شود؟')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-wrap">
                {{ $categories->links('pagination.simple') }}
            </div>
        </div>
    </div>
@endsection
