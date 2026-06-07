<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Quản lý người dùng', 'code' => 'user.manage'],

            ['name' => 'Quản lý danh mục', 'code' => 'category.manage'],

            ['name' => 'Tạo bài viết', 'code' => 'post.create'],
            ['name' => 'Sửa bài viết', 'code' => 'post.edit'],
            ['name' => 'Xóa bài viết', 'code' => 'post.delete'],
            ['name' => 'Xuất bản bài viết', 'code' => 'post.publish'],

            ['name' => 'Quản lý banner', 'code' => 'banner.manage'],
            ['name' => 'Quản lý media', 'code' => 'media.manage'],
            ['name' => 'Quản lý menu', 'code' => 'menu.manage'],
            ['name' => 'Quản lý trang nội dung', 'code' => 'page.manage'],
            ['name' => 'Cài đặt website', 'code' => 'setting.manage'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['code' => $permission['code']],
                $permission
            );
        }

        $admin = Role::where('code', 'admin')->first();
        $editor = Role::where('code', 'editor')->first();
        $reporter = Role::where('code', 'reporter')->first();

        if ($admin) {
            $admin->permissions()->sync(
                Permission::pluck('id')->toArray()
            );
        }

        if ($editor) {
            $editor->permissions()->sync(
                Permission::whereIn('code', [
                    'category.manage',
                    'post.create',
                    'post.edit',
                    'post.delete',
                    'post.publish',
                    'media.manage',
                    'page.manage',
                ])->pluck('id')->toArray()
            );
        }

        if ($reporter) {
            $reporter->permissions()->sync(
                Permission::whereIn('code', [
                    'post.create',
                    'post.edit',
                    'media.manage',
                ])->pluck('id')->toArray()
            );
        }
    }
}