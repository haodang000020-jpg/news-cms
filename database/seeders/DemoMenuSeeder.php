<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoMenuSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Xóa menu mẫu cũ
        |--------------------------------------------------------------------------
        | Chỉ xóa bảng menus, không ảnh hưởng danh mục và bài viết.
        */

        DB::table('menus')->delete();

        /*
        |--------------------------------------------------------------------------
        | Helper lấy category_id theo slug
        |--------------------------------------------------------------------------
        */

        $categoryIds = DB::table('categories')
            ->pluck('id', 'slug')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Menu cấp 1 + cấp 2
        |--------------------------------------------------------------------------
        */

        $menus = [
            [
                'title' => 'Trang chủ',
                'url' => '/',
                'category_slug' => null,
                'children' => [],
            ],

            [
                'title' => 'Giới thiệu',
                'url' => '#',
                'category_slug' => null,
                'children' => [
                    ['title' => 'Giới thiệu chung', 'url' => '/gioi-thieu-chung'],
                    ['title' => 'Chức năng, nhiệm vụ', 'url' => '/chuc-nang-nhiem-vu'],
                    ['title' => 'Cơ cấu tổ chức', 'url' => '/co-cau-to-chuc'],
                ],
            ],

            [
                'title' => 'Tin tức - Sự kiện',
                'url' => null,
                'category_slug' => 'tin-tuc-su-kien',
                'children' => [
                    ['title' => 'Hoạt động của xã', 'category_slug' => 'hoat-dong-hdnd-ubnd'],
                    ['title' => 'Thông tin tuyên truyền', 'category_slug' => 'tin-tuc-su-kien'],
                    ['title' => 'Chuyển đổi số', 'category_slug' => 'chuyen-doi-so'],
                ],
            ],

            [
                'title' => 'Kinh tế',
                'url' => null,
                'category_slug' => 'kinh-te',
                'children' => [
                    ['title' => 'Nông nghiệp', 'url' => '#'],
                    ['title' => 'Thương mại - Dịch vụ', 'url' => '#'],
                    ['title' => 'Chương trình OCOP', 'url' => '#'],
                    ['title' => 'Kinh tế hộ gia đình', 'category_slug' => 'kinh-te'],
                ],
            ],

            [
                'title' => 'Văn hóa - Xã hội',
                'url' => null,
                'category_slug' => 'van-hoa-xa-hoi',
                'children' => [
                    ['title' => 'Văn hóa', 'category_slug' => 'van-hoa-xa-hoi'],
                    ['title' => 'Gia đình', 'url' => '#'],
                    ['title' => 'Lao động - Việc làm', 'url' => '#'],
                    ['title' => 'Chính sách xã hội', 'url' => '#'],
                ],
            ],

            [
                'title' => 'Giáo dục',
                'url' => null,
                'category_slug' => 'giao-duc',
                'children' => [
                    ['title' => 'Mầm non', 'url' => '#'],
                    ['title' => 'Tiểu học', 'url' => '#'],
                    ['title' => 'Trung học cơ sở', 'url' => '#'],
                    ['title' => 'Khuyến học', 'url' => '#'],
                ],
            ],

            [
                'title' => 'Y tế',
                'url' => null,
                'category_slug' => 'y-te',
                'children' => [
                    ['title' => 'Chăm sóc sức khỏe', 'category_slug' => 'y-te'],
                    ['title' => 'Phòng chống dịch bệnh', 'url' => '#'],
                    ['title' => 'An toàn thực phẩm', 'url' => '#'],
                ],
            ],

            [
                'title' => 'Cải cách hành chính',
                'url' => null,
                'category_slug' => 'cai-cach-hanh-chinh',
                'children' => [
                    ['title' => 'Thủ tục hành chính', 'category_slug' => 'cai-cach-hanh-chinh'],
                    ['title' => 'Dịch vụ công trực tuyến', 'url' => '#'],
                    ['title' => 'Tiếp nhận phản ánh kiến nghị', 'url' => '#'],
                ],
            ],

            [
                'title' => 'Thông báo - Văn bản',
                'url' => '#',
                'category_slug' => null,
                'children' => [
                    ['title' => 'Thông báo', 'category_slug' => 'thong-bao'],
                    ['title' => 'Văn bản chỉ đạo điều hành', 'category_slug' => 'van-ban-chi-dao-dieu-hanh'],
                    ['title' => 'Lịch tiếp công dân', 'url' => '#'],
                ],
            ],
        ];

        $sortOrder = 1;

        foreach ($menus as $menu) {
            $parentId = DB::table('menus')->insertGetId([
                'parent_id' => null,
                'category_id' => $this->getCategoryId($categoryIds, $menu['category_slug'] ?? null),
                'title' => $menu['title'],
                'url' => $this->getUrl($menu, $categoryIds),
                'target' => '_self',
                'sort_order' => $sortOrder,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $childOrder = 1;

            foreach ($menu['children'] as $child) {
                DB::table('menus')->insert([
                    'parent_id' => $parentId,
                    'category_id' => $this->getCategoryId($categoryIds, $child['category_slug'] ?? null),
                    'title' => $child['title'],
                    'url' => $this->getUrl($child, $categoryIds),
                    'target' => '_self',
                    'sort_order' => $childOrder,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $childOrder++;
            }

            $sortOrder++;
        }
    }

    private function getCategoryId(array $categoryIds, ?string $slug): ?int
    {
        if (!$slug) {
            return null;
        }

        return $categoryIds[$slug] ?? null;
    }

    private function getUrl(array $item, array $categoryIds): ?string
    {
        if (!empty($item['url'])) {
            return $item['url'];
        }

        if (!empty($item['category_slug']) && isset($categoryIds[$item['category_slug']])) {
            return null;
        }

        return '#';
    }
}