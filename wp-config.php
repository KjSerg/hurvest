<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'ds526906_mosaica' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'ds526906_mosaica' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', '(5be9C9#Jp' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'ds526906.mysql.tools' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Tg,`-]ei_|8[7rNC8Bu=JZ*f72)CHkP<4?U$nC@q_%YNX5=QP $Xz1P&V$/{wH=f' );
define( 'SECURE_AUTH_KEY',  '81`(]w55~*3]~2/P bi1otn2^EjF]mFC|feinfv{ab0`g$%9%KHM_oT)#Bii O?c' );
define( 'LOGGED_IN_KEY',    '_|GSVOvd~GqI&JR7^?qE!gixdAEmoQZDfUR@HXB{V7Oge}+@r wC8SU9)&l5;Jl9' );
define( 'NONCE_KEY',        '_+&%4?e/1}2KXTT&Hwwvv`$ OR#bRXDE~XAYCC`|/8KV-3tM8Dt_aH*}rlbwgn95' );
define( 'AUTH_SALT',        'tHfP-#xZwA&T`i8^F0#nrKjKR!t*C*e.lf!jTDu8O#{2:SWEA0Jmsp&pkO|uMW`:' );
define( 'SECURE_AUTH_SALT', ']Vaf|kryu,hDu_((t/>I!v@eCJfg#xs^tLZ;iy!iSZY,[cVKNSjrCTPG0(5fY[p0' );
define( 'LOGGED_IN_SALT',   '|6,1bnG f04.TgCp@W]/k#fg^i_ea;0Ym9JW.u[MxRY R+:$B!RO$J1F?/w_Sdp8' );
define( 'NONCE_SALT',       '<YM xFq Us),I*1i5 /SRbdL&JB&GA0AOL?mluQ$0IQRO`!BL{>G*F)tV61Ij)FQ' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', 0 );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
