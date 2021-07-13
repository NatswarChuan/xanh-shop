<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'xanh_shop' );

/** Username của database */
define( 'DB_USER', 'root' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', '' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'vh[NcP&p$j$}${-(+5m~i&A*;0G^6UDFoTd>mlx^}4w,5%<8PsoW?JzGa~]p1wt6' );
define( 'SECURE_AUTH_KEY',  '6V&Wf6jhm+a0[W-ja}:}G#,EM|$*Q>8>1#H>f<]F9QQC*=eVq5$yCCB55.9g;G5[' );
define( 'LOGGED_IN_KEY',    'D`Vntd.A5$y<ccx ^hfd}#=RvN9{7>Rd{9&!0I!k-49Veir]Am>sr_Ro2KyeN|%~' );
define( 'NONCE_KEY',        'A7Gdmr:)mLx[p41OPAX10UPOPw}9Xjx^M9P }OLhcYQ@|zKG5h:5n[TGZhQy|WoU' );
define( 'AUTH_SALT',        'C,=gPhb{A#^Vnu|[on#/k(}ZC_g-/3~+sC!e;NpKui8p6+[.Y!^Iez-H,y1K+U&6' );
define( 'SECURE_AUTH_SALT', 'NAU^($aJaLfan~W_/-CVA,5|CuS%G,osEq#2yzk J=J[3HPR|O43g[B6H&5<I_yz' );
define( 'LOGGED_IN_SALT',   'Jf?~u!*veQ#9B9G9{}guJOYXg1O|W9Riw9GX2OaZ,i=U_Lc@S8#(hd;cVl]w0M~)' );
define( 'NONCE_SALT',       'MQ`<&YUCY)Ct%<#T>sMCG:&Qm>(G|%RRgFN40k2qoW,wcvy_6z`X;`X3RpKl]Rpy' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix = 'xanh_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
