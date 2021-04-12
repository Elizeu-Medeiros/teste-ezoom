<header id="header-landing">
    <a href="#" title="<?php echo T_('Colégio Murialdo'); ?>" class="logo">
        <img src="<?php echo base_img('logo.png'); ?>" alt="<?php echo T_('Colégio Murialdo'); ?>">
    </a>

    <div class="select-unity"><?php echo T_('Selecione uma unidade para acessar:'); ?></div>
</header>

<section class="unities">
    <?php foreach ($units as $key => $unit) { ?>
        <div class="unity">
            <?php
                if($isMobile && !empty($unit->landing_mobile)){
                    echo lazyload(array(
                        'src' => site_url('image/resize?w=414&h=178&src=userfiles/unidades/'.$unit->landing_mobile),
                        'class' => 'unity-photo lazyload',
                        'data-background' => TRUE
                    ));
                }else{
                    echo lazyload(array(
                        'src' => site_url('image/resize?w=480&h=936&src=userfiles/unidades/'.$unit->landing),
                        'class' => 'unity-photo lazyload',
                        'data-background' => TRUE
                    ));
                }
            ?>
            <a href="<?php echo site_url('unidade/'.$unit->slug); ?>">
                <div class="content">
                    <div class="title"><?php echo $unit->title; ?></div>
                </div>
            </a>

            <div class="info">
                <a href="tel:+55<?php echo preg_replace('/\D/', '', $unit->phone); ?>" title="<?php echo $unit->phone; ?>"><?php echo $unit->phone; ?></a>
                <a href="mailto:<?php echo $unit->email; ?>" title="<?php echo $unit->email; ?>"><?php echo $unit->email; ?></a>
                <a href="<?php echo site_url('unidade/'.$unit->slug); ?>">
                    <button type="button" class="common-button"><?php echo T_('Acessar site'); ?></button>
                </a>
            </div>
        </div>
    <?php } ?>
</section>