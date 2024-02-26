<?php
get_header();

$var      = variables();
$set      = $var['setting_home'];
$assets   = $var['assets'];
$url      = $var['url'];
$url_home = $var['url_home'];
?>
<div class="main-bg" style="background:url('/wp-content/uploads/2024/01/404-svg.webp') no-repeat top center/cover"></div>
<div class="container pad_section">
    <div class="error-content dark_section">
        <div class="error-content__main">4<span>0</span>4</div>
        <div class="title-sm">Ой! <br> Сторінку не знайдено</div>
        <div class="error-content__text">Можливо, ви ввели неправильну адресу або сторінку перемістили.</div>
        <a class="btn_st btn_yellow" href="<?php echo $url; ?>"><span> На головну сторінку </span></a>
    </div>
</div>
<?php get_footer(); ?>
