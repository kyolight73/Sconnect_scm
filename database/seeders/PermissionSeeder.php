<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'Hệ thống',
                'display_name'=> 'Hệ thống',
                'parent_id'=>0,
                'key_code'=>'',
                'created_at'=>now(),
                'updated_at'=>now(),

            ],
            [
                'name' => 'Trang chủ',
                'display_name'=> 'Trang chủ',
                'parent_id'=>1,
                'key_code'=>'trang-chu',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Công ty',
                'display_name'=> 'Công ty',
                'parent_id'=>1,
                'key_code'=>'cong-ty',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Phòng ban/Chức danh',
                'display_name'=> 'Phòng ban/Chức danh',
                'parent_id'=>1,
                'key_code'=>'phong-banchuc-danh',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Nhân viên',
                'display_name'=> 'Nhân viên',
                'parent_id'=>1,
                'key_code'=>'nhan-vien',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Phân Quyền',
                'display_name'=> 'Phân Quyền',
                'parent_id'=>1,
                'key_code'=>'phan-quyen',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],

            //Media
            [
                'name' => 'Media',
                'display_name'=> 'Media',
                'parent_id'=>0,
                'key_code'=>'',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Sản phẩm gốc',
                'display_name'=> 'Sản phẩm gốc',
                'parent_id'=>7,
                'key_code'=>'san-pham-goc',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Nền tảng chia sẻ video',
                'display_name'=> 'Nền tảng chia sẻ video',
                'parent_id'=>7,
                'key_code'=>'nen-tang-chia-se-video',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Chủ đề',
                'display_name'=> 'Chủ đề',
                'parent_id'=>7,
                'key_code'=>'chu-de',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Loại kênh',
                'display_name'=> 'Loại kênh',
                'parent_id'=>7,
                'key_code'=>'loai-kenh',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Kênh video',
                'display_name'=> 'Kênh video',
                'parent_id'=>7,
                'key_code'=>'kenh-video',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Video',
                'display_name'=> 'Video',
                'parent_id'=>7,
                'key_code'=>'video',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],


            //Marketing
            [
                'name' => 'Marketing',
                'display_name'=> 'Marketing',
                'parent_id'=>0,
                'key_code'=>'',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Shortlink',
                'display_name'=> 'Shortlink',
                'parent_id'=>14,
                'key_code'=>'shortlink',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Quảng cáo',
                'display_name'=> 'Quảng cáo',
                'parent_id'=>14,
                'key_code'=>'quang-cao',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'Bình Luận',
                'display_name'=> 'Bình Luận',
                'parent_id'=>14,
                'key_code'=>'binh-luan',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
