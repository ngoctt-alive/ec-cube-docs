<?php


$lines = file('_data/navigation copy.yml', FILE_IGNORE_NEW_LINES);

$content = file_get_contents('_data/navigation copy.yml'); 

//var_dump($content);
preg_match_all('/- title: (.*)/', $content, $matches);
//var_dump($matches);
//preg_replace()

// $marks = array();
// $indexs = array();
// foreach ($lines as $key =>  $line) {
//   if(strstr($line, '- title:')){
//     $text = str_replace('- title:', '', $line);
//     var_dump($line);
//     $marks[] = trim($text);
//     $indexs[] = true;
//   }
//   else{
//     $indexs[] = false;
//   }
// }

//echo implode('__',$marks);

// echo count($marks) ;

$trans = explode('__', '"Về EC-CUBE"__"Cộng đồng phát triển"__ec-cube.co__Về ec-cube.co__Về các biện pháp bảo mật__Hướng dẫn cập nhật__Console__Quy tắc đặt tên__Tính năng quản lý Git__Cách sử dụng thư mục tùy chỉnh__Xây dựng môi trường thử nghiệm__Lấy log__Câu hỏi thường gặp__Bắt đầu__Phiên bản mới nhất__Yêu cầu hệ thống__Những lưu ý khi sử dụng môi trường sản xuất__Thông tin cho người mới bắt đầu__Các bước học tập__Cài đặt__Các phương pháp cài đặt__Giao diện dòng lệnh__Cập nhật phiên bản__Cập nhật phiên bản chính từ 4.2 lên 4.3__Di chuyển từ 4.2 lên 4.3__Di chuyển từ 4.1 lên 4.2__Cập nhật phiên bản chính từ 4.0 lên 4.1__Di chuyển từ 4.0 lên 4.1__Cập nhật phiên bản chính 4.2__Cập nhật phiên bản chính 4.1__Cập nhật phiên bản chính 4.0__Lưu ý với 4.0.3__Hỗ trợ cookie SameSite__Thông số kỹ thuật tính năng__Danh sách tính năng__Liên quan đến đơn hàng__Cài đặt tỷ lệ thuế__Cài đặt phương thức thanh toán__Tùy chỉnh chính__Cấu trúc thư mục__Tùy chỉnh Controller__Tùy chỉnh Entity__Tùy chỉnh Repository__Tùy chỉnh FormType__Tùy chỉnh quy trình mua hàng__Tùy chỉnh trạng thái đơn hàng__Tùy chỉnh template__Tùy chỉnh tính năng hạn chế tốc độ__Kiểm soát danh sách trắng bằng Twig Sandbox__Mở rộng với tính năng Symfony__Phát triển Command__Tùy chỉnh thiết kế__Cơ sở template thiết kế__Sử dụng block__Sử dụng quản lý layout__Quản lý hình ảnh__Sử dụng CSS__Sử dụng Sass (scss)__Thay đổi layout form__Tùy chỉnh thông điệp__Tài liệu tham khảo thiết kế giao diện người dùng (style guide)__Tài liệu tham khảo thiết kế giao diện quản trị (design guide)__Template giao diện người dùng for Adobe XD__Template giao diện quản trị for Adobe XD__Phát triển plugin__Thông số kỹ thuật plugin__Cài đặt plugin__Cách xử lý sự cố plugin__Mẫu plugin__Phát triển plugin__Kiểm tra cài đặt qua Owners Store__Quy tắc đặt tên plugin khuyến nghị__Mở rộng điều hướng giao diện quản trị__Thay đổi cài đặt__Đa ngôn ngữ__Tiền tệ__Múi giờ__Cài đặt môi trường__Chế độ gỡ lỗi__Cách lưu phiên__Công cụ phát triển__MailCatcher__"Thực hiện kiểm tra bảo mật"__"Bắt đầu"__"Quick Start"__"Giới thiệu"__"Về OWASP ZAP"__"Kế hoạch kiểm tra"__"Thực hiện kiểm tra"__"Đánh giá kiểm tra"__"Cải thiện bảo mật"__Hướng dẫn bảo mật__Lập trình__Tăng cường bảo mật trong phát triển plugin__Tham chiếu ngược__Mẹo__Bộ sưu tập mẫu tùy chỉnh__Tham gia phát triển__Làm thế nào để tham gia phát triển EC-CUBE? (Trang chính thức)__Tổng quan phát triển__Cách tạo pull request__Khi không tìm thấy tài liệu__Yêu cầu tài liệu__Thêm và viết tài liệu__Đăng tải tài liệu"');

foreach($trans as $i => $tran){
  $content = str_replace($matches[0][$i], '- title: '.$tran, $content);
  //var_dump($matches[0][$i]);
  //preg_replace('/'.$matches[0][$i].'/', '- title: '.$tran.'', $content);
}

var_dump($content);

// var_dump($trans);


// $handle = fopen(__DIR__.'/_data/navigation.yml', 'r+');
// if ($handle) {
//     $i = 0;
//     while (($line = fgets($handle)) !== false) {
//         if($indexs[$i] == true){
//           $line = str_replace($marks[$i], $trans[$i], $line);
//           fwrite($handle, $line);
//         }
//         $i++;
//     }
//     fclose($handle);
// } else {
//     echo 'Error opening file.';
// }