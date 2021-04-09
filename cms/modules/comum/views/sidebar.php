<div id="menu-left" class="hidden-print hidden-xs">
    <div id="profile-avatar">
        <div class="img-avatar">
            <img src="<?php echo site_url('image/resize_crop?src='.$this->auth->data('avatar').'&w=48&h=48&q=85'); ?>" width="48" class="img-circle thumb" />
        </div>
        <div id="infos-profile">
            <h4 class="info-name"><?php echo $this->auth->data('name'); ?></h4>
            <p class="info-group"><?php echo $this->auth->data('group'); ?></p>
        </div>
    </div>
    <form id="sidebar-search" autocomplete="off">
        <input type="search" name="search" placeholder="<?php echo T_('Buscar módulo'); ?>" autocomplete="off">
        <i class="fa fas fa-search"></i>
    </form>
    <ul id="menu-list">
        <?php echo $sidebar_menu; ?>
        <div id="menu-no-modules" class="hidden"><?php echo T_('Nenhum módulo encontrado'); ?></div>
    </ul>
</div>
