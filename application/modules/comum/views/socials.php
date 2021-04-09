<div class="socials <?php echo isset($mobile) ? 'item-mobile' : ''; ?>">
    <?php if(!empty($contentPage->current_unit->facebook)){ ?>
        <a href="<?php echo $contentPage->current_unit->facebook; ?>" target="_blank" title="<?php echo T_('Curta nossa página no Facebook'); ?>" class="socials-a">
            <i class="fab fa-facebook-f"></i>
        </a>
    <?php } if(!empty($contentPage->current_unit->instagram)){ ?>
        <a href="<?php echo $contentPage->current_unit->instagram; ?>" target="_blank" title="<?php echo T_('Nos siga no Instagram'); ?>" class="socials-a">
            <i class="fab fa-instagram"></i>
        </a>
    <?php } ?>
</div>