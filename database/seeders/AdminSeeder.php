<?php

namespace Database\Seeders;

use App\Domains\Identity\Models\AdminAccessLevel;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Real admins scraped from panel123456.germanymode.com/page/admin/admin.aspx
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $allPermissionKeys = collect(config('admin_permissions', []))
            ->flatMap(fn ($group) => collect($group['permissions'] ?? [])->pluck('key'))
            ->unique()
            ->values()
            ->all();

        $shopPermissionKeys = collect(config('admin_permissions', []))
            ->filter(fn ($group) => in_array($group['title'] ?? '', [
                'ایجاد کالا', 'سفارشات انجام شده', 'آخرین سفارشات',
                'مرتب سازی بخشهای  برگزیده', 'ویرایش سریع کالا ها', 'آماده سازی فروشگاه',
            ], true))
            ->flatMap(fn ($group) => collect($group['permissions'] ?? [])->pluck('key'))
            ->unique()
            ->values()
            ->all();

        $levels = [
            ['name' => 'مدیر 2', 'is_blocked' => false, 'sort_order' => 1, 'created_at' => '2020-04-13 22:51:00', 'permissions' => $allPermissionKeys],
            ['name' => 'مدیر 3', 'is_blocked' => false, 'sort_order' => 2, 'created_at' => '2020-04-20 01:08:00', 'permissions' => $shopPermissionKeys],
            ['name' => 'مدیر 5', 'is_blocked' => false, 'sort_order' => 3, 'created_at' => '2021-02-02 22:05:00', 'permissions' => $shopPermissionKeys],
            ['name' => 'مدیر 4', 'is_blocked' => true, 'sort_order' => 4, 'created_at' => '2021-01-31 18:57:00', 'permissions' => []],
            ['name' => 'مدیر 6', 'is_blocked' => true, 'sort_order' => 5, 'created_at' => '2021-04-12 23:37:00', 'permissions' => []],
            ['name' => 'مدیر 7', 'is_blocked' => true, 'sort_order' => 6, 'created_at' => '2021-07-01 12:35:00', 'permissions' => []],
            ['name' => 'مدیر 8', 'is_blocked' => true, 'sort_order' => 7, 'created_at' => '2022-03-02 20:06:00', 'permissions' => []],
            ['name' => 'مدیر 9', 'is_blocked' => true, 'sort_order' => 8, 'created_at' => '2022-12-22 19:08:00', 'permissions' => []],
            ['name' => 'مدیر 10', 'is_blocked' => true, 'sort_order' => 9, 'created_at' => '2023-07-03 01:14:00', 'permissions' => []],
        ];

        $levelIds = [];
        foreach ($levels as $level) {
            $row = AdminAccessLevel::query()->updateOrCreate(
                ['name' => $level['name']],
                [
                    'is_blocked' => $level['is_blocked'],
                    'sort_order' => $level['sort_order'],
                    'permissions' => $level['permissions'],
                    'created_at' => $level['created_at'],
                    'updated_at' => $level['created_at'],
                ]
            );
            $levelIds[$level['name']] = $row->id;
        }

        // Super admins (site owners) — hidden from managers list
        User::query()->updateOrCreate(
            ['email' => 'h_joneidi1354@yahoo.com'],
            [
                'portal_id' => 1,
                'name' => 'حمیدرضا',
                'last_name' => 'جنیدی جعفری',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'role' => 'owner',
                'access_level_id' => $levelIds['مدیر 2'],
                'admin_rank' => 5,
                'is_blocked' => false,
                'created_at' => '2020-01-01 10:00:00',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@germanymode.com'],
            [
                'portal_id' => 2,
                'name' => 'مدیر',
                'last_name' => 'سایت',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'role' => 'owner',
                'access_level_id' => $levelIds['مدیر 2'],
                'admin_rank' => 5,
                'is_blocked' => false,
            ]
        );

        $admins = [
            [
                'portal_id' => 95121,
                'name' => 'مریم',
                'last_name' => 'جنیدی',
                'email' => 'mjoneidi57@yahoo.com',
                'level' => 'مدیر 2',
                'created_at' => '2020-04-13 22:56:00',
            ],
            [
                'portal_id' => 96270,
                'name' => 'حامد',
                'last_name' => 'جنیدی',
                'email' => 'joneidi_hamed@yahoo.com',
                'level' => 'مدیر 3',
                'created_at' => '2020-04-20 01:11:00',
            ],
            [
                'portal_id' => 151565,
                'name' => 'لیلا',
                'last_name' => 'رسولی',
                'email' => 'leyla_rasolo@yahoo.com',
                'level' => 'مدیر 4',
                'created_at' => '2021-01-31 18:37:00',
            ],
            [
                'portal_id' => 151955,
                'name' => 'رویا',
                'last_name' => 'خزایی',
                'email' => 'sheidaf1112@gmail.com',
                'level' => 'مدیر 5',
                'created_at' => '2021-02-02 21:57:00',
            ],
            [
                'portal_id' => 163667,
                'name' => 'دنیا',
                'last_name' => 'میر خسروی',
                'email' => 'aynodmir@gmail.com',
                'level' => 'مدیر 6',
                'created_at' => '2021-04-12 23:22:00',
            ],
            [
                'portal_id' => 177157,
                'name' => 'آناهیتا',
                'last_name' => 'میران',
                'email' => 'anahitamiran89@gmail.com',
                'level' => 'مدیر 7',
                'created_at' => '2021-07-01 12:28:00',
            ],
            [
                'portal_id' => 221968,
                'name' => 'حسن',
                'last_name' => 'جنیدی',
                'email' => 'sooriakbari@gmail.com',
                'level' => 'مدیر 8',
                'created_at' => '2022-03-02 20:03:00',
            ],
            [
                'portal_id' => 282048,
                'name' => 'سونیا',
                'last_name' => 'تاری',
                'email' => 'soniyatari66@gmail.com',
                'level' => 'مدیر 9',
                'created_at' => '2022-12-22 18:54:00',
            ],
            [
                'portal_id' => 348096,
                'name' => 'مهدی',
                'last_name' => 'فتح الهی',
                'email' => 'mahdigame81@gmail.com',
                'level' => 'مدیر 10',
                'created_at' => '2023-08-02 20:21:00',
            ],
        ];

        foreach ($admins as $admin) {
            User::query()->updateOrCreate(
                ['email' => $admin['email']],
                [
                    'portal_id' => $admin['portal_id'],
                    'name' => $admin['name'],
                    'last_name' => $admin['last_name'],
                    'password' => Hash::make('123456'),
                    'is_admin' => true,
                    'role' => 'staff',
                    'access_level_id' => $levelIds[$admin['level']],
                    'admin_rank' => 0,
                    'is_blocked' => false,
                    'created_at' => $admin['created_at'],
                    'updated_at' => $admin['created_at'],
                ]
            );
        }

        $this->command?->info('Imported '.count($admins).' real panel admins + access levels.');
    }
}
