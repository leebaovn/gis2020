1. Sử dụng WAMP
2. Đăng nhập vào phpmyadmin(localhost:<port>/phpmyadmin)
3. Sử dụng file deviation_plus.sql để import vào phpmyadmin
4. Di chuyển thư mục chứa source vào thư mục www của wamp (Bình thường sẽ là C:/wamp64/www)
5. Mở trình duyệt và truy cập vào đường dẫn localhost:<port>/deviation để mở trang web

#Github: https://github.com/leebaovn/gis2020
#Source code gồm

- index.php -> file chính bao gồm xử lí arcgis và giao diện web
- db.php -> xử lí các hàm đọc ghi vào cơ sở dữ liệu
- vehicle.php -> xử lí form đăng ký phương tiện
- style.css -> style cho giao diện
