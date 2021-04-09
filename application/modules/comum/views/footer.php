<footer id="footer"> 
    <div class="bottom">
        <div class="common-limiter">
            <div class="logo">
                <img src="<?php echo base_url('userfiles/unidades/'.$contentPage->current_unit->image); ?>" alt="<?php echo $contentPage->current_unit->title; ?>">
            </div>
            <div class="box">
                <?php echo $contentPage->current_unit->address.', '.$contentPage->current_unit->number; ?><br>
                <?php echo $contentPage->current_unit->city.' - '.$contentPage->current_unit->state; ?>
            </div>
            <div class="box">
                <a href="tel:<?php echo preg_replace('/\D/', '', $contentPage->current_unit->phone); ?>" title="<?php echo $contentPage->current_unit->phone; ?>" class="link"><?php echo $contentPage->current_unit->phone; ?></a>
            </div>
            <div class="box">
                <b>Colégio Murialdo:</b> entidade beneficente de assistência social - CEBAS - Educação
            </div>
            <div class="box">
                <?php $this->load->view('comum/socials'); ?>
            </div>
            <div class="box">
                <a href="http://www.ezoom.com.br" title="<?php echo T_('Desenvolvido por Agência Ezoom'); ?>" class="ezoom" target="_blank">
                    <?php echo load_svg('ezoom.svg'); ?>
                </a>
            </div>
        </div>
    </div>
</footer>