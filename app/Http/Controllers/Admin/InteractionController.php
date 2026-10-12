<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAccessLevel;
use App\Models\Comment;
use App\Models\Poll;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InteractionController extends Controller
{
    public function admins(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $editId = (int) $request->get('edit', 0);

        $admins = User::query()
            ->with('accessLevel')
            ->where(function ($query) {
                $query->where('is_admin', true)->orWhereIn('role', ['admin', 'staff']);
            })
            ->whereNotIn('email', $this->superAdminEmails())
            ->where(function ($query) {
                $query->whereNull('role')->orWhere('role', '!=', 'owner');
            })
            ->when($q !== '', fn ($query) => $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('mobile', 'like', "%{$q}%")
                    ->orWhere('portal_id', 'like', "%{$q}%");
            }))
            ->orderByRaw('portal_id is null')
            ->orderBy('portal_id')
            ->paginate(50)
            ->appends($request->query());

        $levels = AdminAccessLevel::query()->orderBy('sort_order')->get();
        $editing = $editId > 0
            ? User::query()->with('accessLevel')->find($editId)
            : null;

        return view('admin.interactions.admins', compact('admins', 'q', 'levels', 'editing'));
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $data = $this->validatedAdmin($request);
        $password = $data['password'] ?: '123456';
        unset($data['password']);

        User::query()->create([
            ...$data,
            'password' => Hash::make($password),
            'is_admin' => true,
            'role' => 'staff',
            'portal_id' => $this->nextPortalId(),
        ]);

        return redirect()->route('admin.interactions.admins')->with('ok', 'مدیر جدید ثبت شد.');
    }

    public function updateAdmin(Request $request, User $user): RedirectResponse
    {
        if ($this->isSuperAdmin($user)) {
            return back()->with('error', 'سوپر ادمین از این بخش قابل ویرایش نیست.');
        }

        if (! $user->is_admin && ! in_array($user->role, ['admin', 'staff'], true)) {
            abort(404);
        }

        $data = $this->validatedAdmin($request, $user->id);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update([
            ...$data,
            'is_admin' => true,
        ]);

        return redirect()->route('admin.interactions.admins')->with('ok', 'مدیر به‌روزرسانی شد.');
    }

    public function toggleBlockAdmin(User $user): RedirectResponse
    {
        if ($this->isSuperAdmin($user)) {
            return back()->with('error', 'سوپر ادمین قابل مسدودسازی نیست.');
        }

        $user->update(['is_blocked' => ! $user->is_blocked]);

        return back()->with('ok', $user->is_blocked ? 'مدیر مسدود شد.' : 'مسدودسازی برداشته شد.');
    }

    public function destroyAdmin(User $user): RedirectResponse
    {
        if ($this->isSuperAdmin($user)) {
            return back()->with('error', 'سوپر ادمین قابل حذف نیست.');
        }

        $user->delete();

        return back()->with('ok', 'مدیر حذف شد.');
    }

    /** @return list<string> */
    private function superAdminEmails(): array
    {
        return [
            'admin@germanymode.com',
            'h_joneidi1354@yahoo.com',
        ];
    }

    private function isSuperAdmin(User $user): bool
    {
        return $user->role === 'owner'
            || in_array($user->email, $this->superAdminEmails(), true);
    }

    private function validatedAdmin(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email,'.($ignoreId ?: 'NULL'),
            'password' => 'nullable|string|min:6',
            'access_level_id' => 'required|exists:admin_access_levels,id',
            'admin_rank' => 'nullable|integer|min:0|max:5',
            'name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'page_link' => 'nullable|string|max:500',
            'avatar' => 'nullable|string|max:500',
            'address' => 'nullable|string',
            'is_blocked' => 'nullable|boolean',
        ]);

        $data['admin_rank'] = (int) ($data['admin_rank'] ?? 0);
        $data['is_blocked'] = $request->boolean('is_blocked');

        return $data;
    }

    private function nextPortalId(): int
    {
        return max(400000, (int) User::query()->max('portal_id') + 1);
    }

    public function accessLevels(Request $request): View
    {
        $editId = (int) $request->get('edit', 0);
        $levels = AdminAccessLevel::query()
            ->withCount(['users' => function ($query) {
                $query->whereNotIn('email', $this->superAdminEmails())
                    ->where(function ($inner) {
                        $inner->whereNull('role')->orWhere('role', '!=', 'owner');
                    });
            }])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $permissionGroups = config('admin_permissions', []);
        $editing = $editId > 0 ? AdminAccessLevel::query()->find($editId) : null;

        return view('admin.interactions.access-levels', compact('levels', 'permissionGroups', 'editing'));
    }

    public function storeAccessLevel(Request $request): RedirectResponse
    {
        $data = $this->validatedAccessLevel($request);

        AdminAccessLevel::query()->create([
            ...$data,
            'sort_order' => ((int) AdminAccessLevel::query()->max('sort_order')) + 1,
        ]);

        return redirect()->route('admin.interactions.access-levels')
            ->with('ok', 'سطح دسترسی جدید ثبت شد.');
    }

    public function updateAccessLevel(Request $request, AdminAccessLevel $level): RedirectResponse
    {
        $level->update($this->validatedAccessLevel($request));

        return redirect()->route('admin.interactions.access-levels')
            ->with('ok', 'سطح دسترسی به‌روزرسانی شد. دسترسی مدیران مرتبط نیز بر اساس همین سطح اعمال می‌شود.');
    }

    public function toggleBlockAccessLevel(AdminAccessLevel $level): RedirectResponse
    {
        $level->update(['is_blocked' => ! $level->is_blocked]);

        return back()->with('ok', $level->is_blocked ? 'دسترسی مسدود شد.' : 'مسدودسازی دسترسی برداشته شد.');
    }

    public function destroyAccessLevel(AdminAccessLevel $level): RedirectResponse
    {
        if ($level->users()->exists()) {
            return back()->with('error', 'این سطح به مدیرانی اختصاص دارد؛ ابتدا مدیران را جابه‌جا کنید.');
        }

        $level->delete();

        return back()->with('ok', 'سطح دسترسی حذف شد.');
    }

    private function validatedAccessLevel(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:120',
            'is_blocked' => 'nullable|boolean',
        ]);

        $data['is_blocked'] = $request->boolean('is_blocked');
        $data['permissions'] = array_values(array_unique($data['permissions'] ?? []));

        return $data;
    }

    public function comments(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $comments = Comment::query()
            ->with('user')
            ->when($q !== '', fn ($query) => $query->where('body', 'like', "%{$q}%")
                ->orWhere('author_name', 'like', "%{$q}%"))
            ->latest('id')
            ->paginate(25)
            ->appends(['q' => $q]);

        return view('admin.interactions.comments', compact('comments', 'q'));
    }

    public function approveComment(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);

        return back()->with('ok', 'نظر تایید شد.');
    }

    public function destroyComment(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('ok', 'نظر حذف شد.');
    }

    public function visits(): View
    {
        $days = Visit::query()->orderByDesc('visited_on')->limit(30)->get();
        $totals = [
            'hits' => (int) Visit::query()->sum('hits'),
            'unique' => (int) Visit::query()->sum('unique_hits'),
            'today' => (int) Visit::query()->whereDate('visited_on', now()->toDateString())->sum('hits'),
        ];

        return view('admin.interactions.visits', compact('days', 'totals'));
    }

    public function polls(): View
    {
        $polls = Poll::query()->with('options')->latest('id')->get();

        return view('admin.interactions.polls', compact('polls'));
    }

    public function storePoll(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'options' => 'required|string',
        ]);

        $poll = Poll::query()->create([
            'title' => $data['title'],
            'is_active' => true,
        ]);

        foreach (preg_split('/\r\n|\r|\n/', $data['options']) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $poll->options()->create(['title' => $line, 'votes' => 0]);
        }

        return back()->with('ok', 'نظرسنجی ایجاد شد.');
    }

    public function destroyPoll(Poll $poll): RedirectResponse
    {
        $poll->delete();

        return back()->with('ok', 'نظرسنجی حذف شد.');
    }

    public function tickets(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $tickets = Ticket::query()
            ->with(['user', 'messages'])
            ->when($q !== '', fn ($query) => $query->where('subject', 'like', "%{$q}%"))
            ->latest('id')
            ->paginate(20)
            ->appends(['q' => $q]);

        return view('admin.interactions.tickets', compact('tickets', 'q'));
    }

    public function replyTicket(Request $request, Ticket $ticket): RedirectResponse
    {
        $data = $request->validate(['body' => 'required|string']);

        TicketMessage::query()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
            'is_staff' => true,
        ]);

        $ticket->update(['status' => 'answered']);

        return back()->with('ok', 'پاسخ ثبت شد.');
    }

    public function closeTicket(Ticket $ticket): RedirectResponse
    {
        $ticket->update(['status' => 'closed']);

        return back()->with('ok', 'تیکت بسته شد.');
    }

    public function editProfile(Request $request): View
    {
        return view('admin.interactions.edit-profile', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('ok', 'پروفایل به‌روزرسانی شد.');
    }
}
