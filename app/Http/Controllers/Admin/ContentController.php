<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\QuickLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function blog(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $posts = Post::query()
            ->where('type', 'blog')
            ->when($q !== '', fn ($query) => $query->where('title', 'like', "%{$q}%"))
            ->latest('published_at')
            ->paginate(25)
            ->appends(['q' => $q]);

        return view('admin.content.blog', compact('posts', 'q'));
    }

    public function gallery(Request $request): View
    {
        $items = Post::query()
            ->where('type', 'gallery')
            ->latest('id')
            ->paginate(25);

        return view('admin.content.gallery', compact('items'));
    }

    public function about(): View
    {
        $page = Post::query()->firstOrCreate(
            ['type' => 'page', 'slug' => 'about'],
            [
                'title' => 'درباره ما',
                'body' => '',
                'author' => 'مدیر سایت',
                'is_hidden' => false,
                'published_at' => now(),
            ]
        );

        return view('admin.content.about', compact('page'));
    }

    public function saveAbout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'is_hidden' => 'nullable|boolean',
        ]);

        Post::query()->updateOrCreate(
            ['type' => 'page', 'slug' => 'about'],
            [
                'title' => $data['title'],
                'body' => $data['body'] ?? '',
                'is_hidden' => (bool) ($data['is_hidden'] ?? false),
                'published_at' => now(),
            ]
        );

        return back()->with('ok', 'صفحه درباره ما ذخیره شد.');
    }

    public function contact(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $messages = ContactMessage::query()
            ->when($q !== '', fn ($query) => $query->where('name', 'like', "%{$q}%")
                ->orWhere('body', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%"))
            ->latest('id')
            ->paginate(20)
            ->appends(['q' => $q]);

        return view('admin.content.contact', compact('messages', 'q'));
    }

    public function markContactSeen(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_seen' => true]);

        return back()->with('ok', 'پیام به وضعیت دیده‌شده منتقل شد.');
    }

    public function destroyContact(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('ok', 'پیام حذف شد.');
    }

    public function quickLinks(): View
    {
        $links = QuickLink::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.content.quick-links', compact('links'));
    }

    public function storeQuickLink(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
        ]);

        QuickLink::query()->create([
            ...$data,
            'sort_order' => (int) QuickLink::query()->max('sort_order') + 1,
        ]);

        return back()->with('ok', 'لینک اضافه شد.');
    }

    public function destroyQuickLink(QuickLink $link): RedirectResponse
    {
        $link->delete();

        return back()->with('ok', 'لینک حذف شد.');
    }

    public function storePost(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => 'required|in:blog,gallery',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'author' => 'nullable|string|max:120',
        ]);

        Post::query()->create([
            ...$data,
            'slug' => str()->slug($data['title']).'-'.substr(uniqid(), -4),
            'views' => 0,
            'is_hidden' => false,
            'published_at' => now(),
            'author' => $data['author'] ?: 'مدیر سایت',
        ]);

        return back()->with('ok', 'مورد جدید ثبت شد.');
    }

    public function destroyPost(Post $post): RedirectResponse
    {
        $post->delete();

        return back()->with('ok', 'حذف شد.');
    }

    public function togglePost(Post $post): RedirectResponse
    {
        $post->update(['is_hidden' => ! $post->is_hidden]);

        return back()->with('ok', 'وضعیت نمایش تغییر کرد.');
    }
}
