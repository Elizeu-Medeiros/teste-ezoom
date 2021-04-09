<section id="banners" class="<?php echo isset($internal) ? 'internal': ''; ?>">
    <div id="banner-images">
        <?php 
        if(!empty($banner)) {
                    echo lazyload(array(
                        'src' => base_url('image/resize?w=1920&h=984&src=userfiles/paginas/'.$banner->image),
                        'title' => $banner->title,
                        'class' => 'banner lazyload',
                        'data-background' => 0,
                    ));
            }
         ?>

    </div>

    <?php if (isset($internal)) { ?>
        <div class="banner-text">
            <h1 class="title"><?php echo $title; ?></h1>
            <div class="common-text">
                <p><?php echo $text; ?></p>
            </div>
        </div>
    <?php } ?>

    <div class="common-navigation common-limiter">
        <div class="common-dots" id="banner-dots"></div>
        <div class="common-arrows" id="banner-arrows"></div>
    </div>

</section>