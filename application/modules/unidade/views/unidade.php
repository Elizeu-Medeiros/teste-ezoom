<?php $this->load->view('comum/banners', array('banner' => $contentPage->banner_header, 'path' => 'banners', 'home' => TRUE)); ?>

<section class="default-content estrutura">
    <div class="smaller-limiter">
        <h2><?php echo T_('Estrutura da unidade'); ?></h2>
        <h1><?php echo $contentPage->current_unit->title;?></h1>

        <?php 
        if($contentPage->current_unit->video){ 
            $youtube_id = get_youtube_id($contentPage->current_unit->video);   
        ?>
        <div class="content box-player">
           <iframe width="80%" src="https://www.youtube.com/embed/<?php echo $youtube_id ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <?php } ?>
    </div>
</section>

<section class="default-content estrutura fadeOnScroll">
    <h2><?php echo T_('Galeria de'); ?></h2>
    <h1><?php echo T_('Fotos'); ?></h1>

    <div class="content carrousel slide-images" >
        <div class="slider multiple-items">
            <?php 
            foreach ($contentPage->current_unit->images as $image) {   
            ?>
                <a href="<?php echo site_url('userfiles/unidades/'.$image->file); ?>">
                    <div class="slide-item item-image">

                        <?php
                            echo lazyload(array(
                                'src' 		=> site_url('userfiles/unidades/'.$image->file),
                                'alt' 		=> $image->subtitle,
                                'class' 	=> 'lazyload desktop',
                                'data-background' => 0
                            ));
                        ?>
                        <span class="legend"><?php echo $image->subtitle; ?></span>
                    </div>
                </a>
            <?php } ?>
            
        </div>
        <div class="navigator">
            <button type="button" class="slick-prev prev"><i class="fas fa-chevron-left"></i></button>     
            <button type="button" class="slick-next next"><i class="fas fa-chevron-right"></i></button>
        </div>
        
    </div>
</section>

<section class="default-content diferenciais fadeOnScroll desktop">
    <h2><?php echo T_('Nossos'); ?></h2>
    <h1><?php echo T_('Diferenciais'); ?></h1>

    <div class="content container-boxes desktop">
        <?php foreach($contentPage->differentials as $diff){ ?>
        <div class="box">
            <?php
                echo lazyload(array(
                    'src' 		=> site_url('userfiles/diferenciais/'.$diff->image),
                    'alt' 		=> $diff->title,
                    'class' 	=> 'lazyload desktop',
                    'data-background' => 0
                ));
            ?>
            <h3><?php echo $diff->title; ?></h3>
            <div class="text">
                <p><?php echo word_limiter( strip_tags($diff->text, '<strong>'), 15); ?></p>
            </div>
            <a class="btn-more open-modal" data-type="diferenciais" href="javascript:;"><?php echo T_('Saiba mais'); ?></a>

            <div class="modal-content modal-diferenciais">
                <div class="left">
                        <?php
                        echo lazyload(array(
                            'src' 		=> site_url('userfiles/diferenciais/'.$diff->image),
                            'alt' 		=> $diff->title,
                            'class' 	=> 'lazyload desktop',
                            'data-background' => 0
                        ));
                    ?>
                    <h3><?php echo $diff->title; ?></h3>
                </div>
                <div class="right">
                    <p><?php echo $diff->text; ?></p>
                </div>
            </div>
        </div>
        <?php } ?>
        
    </div>
</section>

<section class="default-content diferenciais fadeOnScroll mobile">
    <h2><?php echo T_('Nossos'); ?></h2>
    <h1><?php echo T_('Diferenciais'); ?></h1>

    <div class="content carrousel slide-diferenciais container-boxes" >
        <div class="slider multiple-items">
            <?php 
            foreach ($contentPage->worth as $w) {   
            ?>
                <div class="slide-item item-image box" >
                <?php
                    echo lazyload(array(
                        'src' 		=> site_url('userfiles/valores/'.$w->image),
                        'alt' 		=> $w->title,
                        'class' 	=> 'lazyload desktop',
                        'data-background' => 1
                    ));
                ?>
                        
                    <h3><?php echo $w->title; ?></h3>
                    <p><?php echo $w->text; ?></p>
                    
                </div>
            <?php } ?>
            
        </div>
        <div class="navigator">
            <button type="button" class="slick-prev prev"><i class="fas fa-chevron-left"></i></button>     
            <button type="button" class="slick-next next"><i class="fas fa-chevron-right"></i></button>
        </div>
        
    </div>
</section>

<section class="default-content valores fadeOnScroll">
    <h2><?php echo T_('Princípios e'); ?></h2>
    <h1><?php echo T_('Valores'); ?></h1>

    <div class="content carrousel slide-valores container-boxes" >
        <div class="slider multiple-items">
            <?php 
            foreach ($contentPage->worth as $w) {   
            ?>
                <div class="slide-item item-image box" >
                <?php
                    echo lazyload(array(
                        'src' 		=> site_url('userfiles/valores/'.$w->image),
                        'alt' 		=> $w->title,
                        'class' 	=> 'lazyload desktop',
                        'data-background' => 1
                    ));
                ?>
                        
                    <h3><?php echo $w->title; ?></h3>
                    <p><?php echo $w->text; ?></p>
                    
                </div>
            <?php } ?>
            
        </div>
        <div class="navigator">
            <button type="button" class="slick-prev prev"><i class="fas fa-chevron-left"></i></button>     
            <button type="button" class="slick-next next"><i class="fas fa-chevron-right"></i></button>
        </div>
        
    </div>
</section>


<section class="default-content niveis fadeOnScroll">
    <h2><?php echo T_('Níveis de'); ?></h2>
    <h1><?php echo T_('Ensino'); ?></h1>

    <div class="content container-boxes">
        <?php foreach($contentPage->levels as $level){ ?>
        <div class="box">
            <?php
                echo lazyload(array(
                    'src' 		=> site_url('userfiles/niveis-de-ensino/'.$level->image),
                    'alt' 		=> $level->title,
                    'class' 	=> 'lazyload desktop',
                    'data-background' => 0
                ));
            ?>
            <h3><?php echo $level->title; ?></h3>
            <p><?php echo word_limiter( strip_tags($level->text, '<strong>'), 20); ?></p>
            <a class="btn-more open-modal" data-type="niveis"  href="javascript:;"><?php echo T_('Saiba mais'); ?></a>
            <div class="modal-content modal-niveis">
                <div class="left">
                        <?php
                        echo lazyload(array(
                            'src' 		=> site_url('userfiles/niveis-de-ensino/'.$level->image),
                            'alt' 		=> $level->title,
                            'class' 	=> 'lazyload desktop',
                            'data-background' => 0
                        ));
                    ?>
                    <h3><?php echo $level->title; ?></h3>
                </div>
                <div class="right">
                    <p><?php echo $level->text; ?></p>
                </div>
            </div>
        </div>
        <?php } ?>
        
    </div>
</section>

<section class="default-content banners banner-bilingue fadeOnScroll">
    <img src="<?php echo base_img('mundi.png'); ?>" class="mundi">
    <div class="common-limiter">
        <div class="left">
            <h1><?php echo $contentPage->content_bilingue->title; ?></h1>
            <p><?php echo $contentPage->content_bilingue->text; ?></p>
            <a class="btn-more" href="<?php echo $contentPage->content_bilingue->link; ?>" target="<?php echo $contentPage->content_bilingue->target; ?>"><?php echo $contentPage->content_bilingue->link_label; ?></a>
        </div>
        <div class="right">
            <?php
                echo lazyload(array(
                    'src' 		=> site_url('userfiles/paginas/'.$contentPage->content_bilingue->image),
                    'alt' 		=> $contentPage->content_bilingue->title,
                    'class' 	=> 'lazyload desktop',
                    'data-background' => 0
                ));
            ?>
        </div>
    </div>
</section>

<section class="default-content banners banner-extras fadeOnScroll">
    <div class="common-limiter">
        <div class="left">
        <?php
                echo lazyload(array(
                    'src' 		=> site_url('userfiles/paginas/'.$contentPage->content_extras->image),
                    'alt' 		=> $contentPage->content_extras->title,
                    'class' 	=> 'lazyload desktop',
                    'data-background' => 0
                ));
            ?>
        </div>
        <div class="right">
            <h1><?php echo $contentPage->content_extras->title; ?></h1>
            <p><?php echo $contentPage->content_extras->text; ?></p>
            <a class="btn-more" href="<?php echo $contentPage->content_extras->link; ?>" target="<?php echo $contentPage->content_extras->target; ?>"><?php echo $contentPage->content_extras->link_label; ?></a>
        </div>
    </div>
</section>


<section class="default-content depoimentos fadeOnScroll">
    <h1><?php echo $contentPage->content_testimonial->title; ?></h1>
    <p class="subtitle"><?php echo $contentPage->content_testimonial->text; ?></p>

    <div class="content carrousel slide-depoimentos container-boxes" >
        <div class="slider multiple-items">
            <?php 
            foreach ($contentPage->testimonial as $t) {   
                $youtube_id = get_youtube_id($t->video);   
            ?>
                <div class="slide-item item-image box" >
                    <div class="play-box">
                        <a href="https://www.youtube.com/watch?v=<?php echo $youtube_id; ?>" rel="vid" class="video mfp-iframe">
                        <?php
                                echo lazyload(array(
                                    'src' 		=> get_youtube_img($youtube_id),
                                    'alt' 		=> $t->title,
                                    'class' 	=> 'lazyload desktop',
                                    'data-background' => 0
                                ));
                            ?>
                            <span class="video mfp-iframe play-video"><?php echo load_svg('play-video.svg'); ?><span>
                        </a>
                    </div>   
                    <h6><strong><?php echo $t->title; ?></strong></h6>
                    <p><?php echo $t->text; ?></p>
                    
                </div>
            <?php } ?>
            
        </div>
        <div class="navigator">
            <button type="button" class="slick-prev prev"><i class="fas fa-chevron-left"></i></button>     
            <button type="button" class="slick-next next"><i class="fas fa-chevron-right"></i></button>
        </div>
        
    </div>
</section>


<section class="default-content contato fadeOnScroll">
    
    <h2><?php echo T_('Entre em'); ?></h2>
    <h1><?php echo T_('Contato conosco'); ?></h1>
    <p class="subtitle"><?php echo T_('Preencha os campos abaixo para que possamos responder você o mais rápido possível.'); ?></p>

    <div class="smaller-limiter">
        <div class="content" >
            <form id="form-subscribe" action="<?php echo site_url('unidade/send'); ?>" method="POST" class="common-form ajax-form" novalidate>
                <input type="hidden" name="id_unit" value="<?php echo $contentPage->current_unit->id; ?>">
                <div class="field field-normal">
                    <input type="text" name="name" class="input" placeholder="<?php echo T_('Nome'); ?>" required>
                </div>
                <div class="field field-normal">
                    <input type="tel" name="phone" class="input" placeholder="<?php echo T_('Telefone'); ?>">
                </div>
                <div class="field field-normal">
                    <input type="email" name="email" class="input" placeholder="<?php echo T_('E-mail'); ?>" required>
                </div>
                <div class="field field-normal">
                    <select name="unit"  class="select2 no-search" data-placeholder="<?php echo T_('Selecione a unidade'); ?>" required>
                    <?php foreach ($contentPage->units as $unit) { ?>
                        <option value="<?php echo $unit->id; ?>" <?php echo $unit->id == $contentPage->current_unit->id ? 'selected' : ''; ?>><?php echo $unit->title; ?></option>
                    <?php } ?>
                    </select>
                </div>
               
                <h3><?php echo T_('Dados do aluno'); ?></h3>
                <div class="container-fields template">
                    <div class="field field-normal name">
                        <input type="text" name="subscription[name][]" class="input" placeholder="<?php echo T_('Nome'); ?>" required>
                    </div>
                    <div class="field field-normal birth">
                        <input type="text" name="subscription[birth][]" class="input date-mask" placeholder="<?php echo T_('Data de nascimento'); ?>" required>
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="field field-normal year">
                        <select name="subscription[level][]"  class="select2 no-search select-level" data-placeholder="<?php echo T_('Ano desejado'); ?>" required>
                        <?php foreach ($contentPage->levels as $level) { ?>
                            <option value="<?php echo $level->id; ?>"><?php echo $level->title; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="field field-normal more">
                        <a class="btn-more"><i class="fas fa-plus"></i></a>
                    </div>
                </div>

                <div class="field field-normal field-submit">
                    <button class="btn-more form-submit"><?php echo T_('Enviar'); ?></button>
                </div>
            </form>
        </div>
    </div>
</section>

