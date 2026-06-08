<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoGovernmentContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Ẩn các danh mục test cũ
        |--------------------------------------------------------------------------
        | Không xóa dữ liệu cũ, chỉ chuyển status = 0 để menu/trang chủ không hiện.
        */

        DB::table('categories')->update([
            'status' => 0,
            'updated_at' => $now,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tạo danh mục chuẩn cho website phòng ban
        |--------------------------------------------------------------------------
        */

        $categories = [
            ['name' => 'Tin tức - Sự kiện', 'slug' => 'tin-tuc-su-kien', 'sort_order' => 1],
            ['name' => 'Hoạt động HĐND - UBND', 'slug' => 'hoat-dong-hdnd-ubnd', 'sort_order' => 2],
            ['name' => 'Kinh tế', 'slug' => 'kinh-te', 'sort_order' => 3],
            ['name' => 'Văn hóa - Xã hội', 'slug' => 'van-hoa-xa-hoi', 'sort_order' => 4],
            ['name' => 'Giáo dục', 'slug' => 'giao-duc', 'sort_order' => 5],
            ['name' => 'Y tế', 'slug' => 'y-te', 'sort_order' => 6],
            ['name' => 'Chuyển đổi số', 'slug' => 'chuyen-doi-so', 'sort_order' => 7],
            ['name' => 'Cải cách hành chính', 'slug' => 'cai-cach-hanh-chinh', 'sort_order' => 8],
            ['name' => 'Thông báo', 'slug' => 'thong-bao', 'sort_order' => 9],
            ['name' => 'Văn bản chỉ đạo điều hành', 'slug' => 'van-ban-chi-dao-dieu-hanh', 'sort_order' => 10],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'parent_id' => null,
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'sort_order' => $category['sort_order'],
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $categoryIds = DB::table('categories')
            ->whereIn('slug', collect($categories)->pluck('slug'))
            ->pluck('id', 'slug')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Xác định tác giả
        |--------------------------------------------------------------------------
        */

        $authorId = DB::table('users')->where('email', 'admin@gmail.com')->value('id')
            ?? DB::table('users')->value('id')
            ?? 1;

        /*
        |--------------------------------------------------------------------------
        | Bài viết mẫu
        |--------------------------------------------------------------------------
        */

        $posts = [
            [
                'category' => 'tin-tuc-su-kien',
                'title' => 'Xã Vĩnh Bình triển khai nhiệm vụ phát triển kinh tế, văn hóa và xã hội năm 2026',
                'summary' => 'Phòng Kinh tế, Văn hóa và Xã hội xã Vĩnh Bình tập trung tham mưu thực hiện các nhiệm vụ trọng tâm về phát triển kinh tế, giáo dục, y tế, văn hóa và an sinh xã hội.',
                'featured' => true,
            ],
            [
                'category' => 'hoat-dong-hdnd-ubnd',
                'title' => 'Lãnh đạo xã Vĩnh Bình kiểm tra công tác phục vụ người dân tại bộ phận một cửa',
                'summary' => 'Hoạt động kiểm tra nhằm nâng cao chất lượng giải quyết thủ tục hành chính, tăng cường tinh thần trách nhiệm của cán bộ, công chức trong phục vụ người dân.',
                'featured' => true,
            ],
            [
                'category' => 'kinh-te',
                'title' => 'Đẩy mạnh phát triển kinh tế hộ gia đình gắn với chuyển đổi cơ cấu cây trồng, vật nuôi',
                'summary' => 'Địa phương khuyến khích người dân phát triển các mô hình sản xuất phù hợp, nâng cao thu nhập và góp phần xây dựng nông thôn mới bền vững.',
                'featured' => true,
            ],
            [
                'category' => 'van-hoa-xa-hoi',
                'title' => 'Tổ chức các hoạt động văn hóa, văn nghệ chào mừng các ngày lễ lớn trong năm',
                'summary' => 'Các hoạt động văn hóa, văn nghệ được tổ chức nhằm tạo không khí vui tươi, phấn khởi, nâng cao đời sống tinh thần cho Nhân dân.',
                'featured' => true,
            ],
            [
                'category' => 'giao-duc',
                'title' => 'Các trường học trên địa bàn xã chuẩn bị điều kiện cho năm học mới',
                'summary' => 'Công tác rà soát cơ sở vật chất, trang thiết bị dạy học và vệ sinh trường lớp được các đơn vị trường học chủ động triển khai.',
                'featured' => false,
            ],
            [
                'category' => 'y-te',
                'title' => 'Tăng cường tuyên truyền phòng, chống dịch bệnh trong mùa mưa',
                'summary' => 'Ngành y tế phối hợp các ấp tuyên truyền người dân chủ động vệ sinh môi trường, diệt lăng quăng và thực hiện các biện pháp phòng bệnh.',
                'featured' => false,
            ],
            [
                'category' => 'chuyen-doi-so',
                'title' => 'Hướng dẫn người dân sử dụng dịch vụ công trực tuyến và thanh toán không dùng tiền mặt',
                'summary' => 'Xã Vĩnh Bình tiếp tục hỗ trợ người dân tiếp cận các tiện ích số, góp phần nâng cao hiệu quả giải quyết thủ tục hành chính.',
                'featured' => false,
            ],
            [
                'category' => 'cai-cach-hanh-chinh',
                'title' => 'Nâng cao chất lượng giải quyết thủ tục hành chính cho tổ chức và cá nhân',
                'summary' => 'Công tác cải cách hành chính được xác định là nhiệm vụ thường xuyên, góp phần xây dựng nền hành chính công khai, minh bạch, hiệu quả.',
                'featured' => false,
            ],
            [
                'category' => 'thong-bao',
                'title' => 'Thông báo lịch tiếp công dân định kỳ của lãnh đạo UBND xã Vĩnh Bình',
                'summary' => 'UBND xã thông báo lịch tiếp công dân định kỳ để tổ chức, cá nhân biết và liên hệ khi có nhu cầu phản ánh, kiến nghị.',
                'featured' => false,
            ],
            [
                'category' => 'van-ban-chi-dao-dieu-hanh',
                'title' => 'Kế hoạch triển khai công tác tuyên truyền cải cách hành chính năm 2026',
                'summary' => 'Kế hoạch nhằm đẩy mạnh thông tin, tuyên truyền về mục tiêu, nhiệm vụ và kết quả thực hiện cải cách hành chính trên địa bàn xã.',
                'featured' => false,
            ],
            [
                'category' => 'kinh-te',
                'title' => 'Khuyến khích phát triển mô hình sản xuất nông nghiệp an toàn, hiệu quả',
                'summary' => 'Các mô hình sản xuất theo hướng an toàn, tiết kiệm chi phí và gắn với nhu cầu thị trường đang được địa phương quan tâm hỗ trợ.',
                'featured' => false,
            ],
            [
                'category' => 'van-hoa-xa-hoi',
                'title' => 'Đẩy mạnh phong trào toàn dân đoàn kết xây dựng đời sống văn hóa',
                'summary' => 'Phong trào được triển khai rộng khắp tại các ấp, góp phần xây dựng nếp sống văn minh, giữ gìn bản sắc văn hóa địa phương.',
                'featured' => false,
            ],
            [
                'category' => 'giao-duc',
                'title' => 'Tăng cường phối hợp giữa gia đình, nhà trường và xã hội trong chăm lo học sinh',
                'summary' => 'Sự phối hợp chặt chẽ giữa các lực lượng góp phần nâng cao chất lượng giáo dục và hạn chế tình trạng học sinh bỏ học.',
                'featured' => false,
            ],
            [
                'category' => 'y-te',
                'title' => 'Trạm Y tế xã triển khai khám sức khỏe định kỳ cho người cao tuổi',
                'summary' => 'Hoạt động khám sức khỏe giúp phát hiện sớm bệnh lý thường gặp, tư vấn chăm sóc sức khỏe và nâng cao chất lượng cuộc sống cho người dân.',
                'featured' => false,
            ],
            [
                'category' => 'chuyen-doi-so',
                'title' => 'Tổ công nghệ số cộng đồng hỗ trợ người dân cài đặt và sử dụng ứng dụng số',
                'summary' => 'Tổ công nghệ số cộng đồng tiếp tục phát huy vai trò cầu nối, hướng dẫn người dân tiếp cận các nền tảng số thiết yếu.',
                'featured' => false,
            ],
            [
                'category' => 'cai-cach-hanh-chinh',
                'title' => 'Công khai quy trình, thời gian giải quyết thủ tục hành chính tại bộ phận một cửa',
                'summary' => 'Việc công khai, minh bạch thủ tục hành chính giúp người dân thuận tiện theo dõi, thực hiện và giám sát quá trình giải quyết hồ sơ.',
                'featured' => false,
            ],
            [
                'category' => 'thong-bao',
                'title' => 'Thông báo về việc đăng ký nhu cầu học nghề, giới thiệu việc làm cho lao động nông thôn',
                'summary' => 'Người dân có nhu cầu học nghề, tìm kiếm việc làm có thể liên hệ bộ phận chuyên môn để được hướng dẫn đăng ký.',
                'featured' => false,
            ],
            [
                'category' => 'van-ban-chi-dao-dieu-hanh',
                'title' => 'Công văn về việc tăng cường công tác phòng, chống dịch bệnh và vệ sinh môi trường',
                'summary' => 'UBND xã yêu cầu các ngành, đoàn thể và các ấp chủ động triển khai các biện pháp phòng, chống dịch bệnh theo quy định.',
                'featured' => false,
            ],
            [
                'category' => 'tin-tuc-su-kien',
                'title' => 'Ra quân hưởng ứng phong trào chung tay bảo vệ môi trường trên địa bàn xã',
                'summary' => 'Hoạt động ra quân thu hút cán bộ, đoàn viên, hội viên và người dân tham gia vệ sinh đường làng, ngõ xóm, tạo cảnh quan xanh - sạch - đẹp.',
                'featured' => false,
            ],
            [
                'category' => 'hoat-dong-hdnd-ubnd',
                'title' => 'UBND xã họp đánh giá tiến độ thực hiện các chỉ tiêu phát triển kinh tế - xã hội',
                'summary' => 'Cuộc họp tập trung đánh giá kết quả thực hiện nhiệm vụ, chỉ ra khó khăn, hạn chế và đề ra giải pháp trong thời gian tới.',
                'featured' => false,
            ],
        ];

        foreach ($posts as $index => $item) {
            $slug = Str::slug($item['title']);

            $content = $this->makeContent($item['title'], $item['summary']);

            DB::table('posts')->updateOrInsert(
                ['slug' => $slug],
                [
                    'author_id' => $authorId,
                    'title' => $item['title'],
                    'slug' => $slug,
                    'summary' => $item['summary'],
                    'content' => $content,
                    'featured_image' => null,
                    'status' => 'published',
                    'published_at' => Carbon::now()->subDays($index)->setTime(8 + ($index % 8), 30),
                    'views' => rand(25, 350),
                    'is_featured' => $item['featured'] ? 1 : 0,
                    'featured_order' => $item['featured'] ? $index + 1 : 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $postId = DB::table('posts')->where('slug', $slug)->value('id');
            $categoryId = $categoryIds[$item['category']] ?? null;

            if ($postId && $categoryId) {
                DB::table('post_categories')->updateOrInsert(
                    [
                        'post_id' => $postId,
                        'category_id' => $categoryId,
                    ],
                    []
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Liên kết website mẫu
        |--------------------------------------------------------------------------
        */

        $links = [
            ['title' => 'Cổng thông tin điện tử tỉnh An Giang', 'url' => 'https://angiang.gov.vn', 'sort_order' => 1],
            ['title' => 'Dịch vụ công quốc gia', 'url' => 'https://dichvucong.gov.vn', 'sort_order' => 2],
            ['title' => 'Cơ sở dữ liệu quốc gia về văn bản pháp luật', 'url' => 'https://vbpl.vn', 'sort_order' => 3],
        ];

        foreach ($links as $link) {
            DB::table('website_links')->updateOrInsert(
                ['url' => $link['url']],
                [
                    'title' => $link['title'],
                    'url' => $link['url'],
                    'sort_order' => $link['sort_order'],
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    private function makeContent(string $title, string $summary): string
    {
        return '
            <p><strong>' . e($summary) . '</strong></p>

            <p>Thực hiện chương trình công tác năm 2026, Phòng Kinh tế, Văn hóa và Xã hội xã Vĩnh Bình tiếp tục tham mưu UBND xã triển khai các nhiệm vụ trọng tâm trên các lĩnh vực kinh tế, văn hóa, giáo dục, y tế, lao động, an sinh xã hội và chuyển đổi số.</p>

            <p>Trong quá trình thực hiện, địa phương chú trọng công tác phối hợp giữa các ngành, đoàn thể và các ấp nhằm kịp thời nắm bắt tình hình thực tế, tháo gỡ khó khăn phát sinh và nâng cao hiệu quả phục vụ Nhân dân.</p>

            <p>Các nội dung triển khai tập trung vào việc nâng cao chất lượng hoạt động quản lý nhà nước, đẩy mạnh tuyên truyền chủ trương, chính sách của Đảng và pháp luật của Nhà nước, đồng thời phát huy vai trò chủ động của người dân trong tham gia phát triển kinh tế - xã hội tại địa phương.</p>

            <p>Thời gian tới, Phòng Kinh tế, Văn hóa và Xã hội xã Vĩnh Bình tiếp tục rà soát các nhiệm vụ được giao, tăng cường ứng dụng công nghệ thông tin, nâng cao chất lượng tham mưu và phối hợp thực hiện tốt các chỉ tiêu phát triển kinh tế - xã hội trên địa bàn xã.</p>
        ';
    }
}