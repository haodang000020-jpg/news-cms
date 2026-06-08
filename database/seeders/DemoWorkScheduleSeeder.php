<?php

namespace Database\Seeders;

use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoWorkScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $startOfWeek = Carbon::now()->startOfWeek();

        $items = [
            [
                'day' => 0,
                'start_time' => '08:00',
                'end_time' => '09:30',
                'title' => 'Họp giao ban UBND xã đầu tuần',
                'location' => 'Phòng họp UBND xã',
                'chairperson' => 'Chủ tịch UBND xã',
                'participants' => 'Lãnh đạo UBND xã, công chức chuyên môn',
            ],
            [
                'day' => 1,
                'start_time' => '14:00',
                'end_time' => '16:00',
                'title' => 'Kiểm tra công tác cải cách hành chính tại bộ phận một cửa',
                'location' => 'Bộ phận Một cửa xã',
                'chairperson' => 'Phó Chủ tịch UBND xã',
                'participants' => 'Công chức văn phòng, công chức tư pháp - hộ tịch',
            ],
            [
                'day' => 2,
                'start_time' => '08:00',
                'end_time' => '10:30',
                'title' => 'Làm việc với các trường học về công tác chuẩn bị năm học mới',
                'location' => 'Trường Tiểu học Vĩnh Bình',
                'chairperson' => 'Lãnh đạo Phòng Kinh tế, Văn hóa và Xã hội',
                'participants' => 'Ban giám hiệu các trường, đại diện các đoàn thể',
            ],
            [
                'day' => 3,
                'start_time' => '07:30',
                'end_time' => '09:30',
                'title' => 'Ra quân tuyên truyền bảo vệ môi trường và chỉnh trang cảnh quan',
                'location' => 'Các tuyến đường trung tâm xã',
                'chairperson' => 'Đoàn Thanh niên xã',
                'participants' => 'Đoàn viên, hội viên và người dân',
            ],
            [
                'day' => 4,
                'start_time' => '13:30',
                'end_time' => '15:30',
                'title' => 'Họp triển khai kế hoạch chuyển đổi số cộng đồng',
                'location' => 'Hội trường UBND xã',
                'chairperson' => 'Tổ công nghệ số cộng đồng',
                'participants' => 'Trưởng các ấp, thành viên tổ công nghệ số',
            ],
        ];

        foreach ($items as $index => $item) {
            WorkSchedule::updateOrCreate(
                [
                    'work_date' => $startOfWeek->copy()->addDays($item['day'])->toDateString(),
                    'title' => $item['title'],
                ],
                [
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'location' => $item['location'],
                    'chairperson' => $item['chairperson'],
                    'participants' => $item['participants'],
                    'status' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}