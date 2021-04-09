<header id="header">
    <div class="wrap">
        <div class="common-limiter">
            <button type="button" class="open-menu item-mobile"></button>

            <a href="<?php echo site_url('home'); ?>" title="<?php echo $contentPage->current_unit->title; ?>" class="logo">
                <img src="<?php echo base_url('userfiles/unidades/'.$contentPage->current_unit->image); ?>" alt="<?php echo $contentPage->current_unit->title; ?>">
            </a>

            <div class="menu-content">

                <div class="content">
                    <nav>
                        <a href="javascript:;" title="<?php echo T_("Estrutura"); ?>" data-target="estrutura" class="nav-a menu-target">
                            <?php echo T_("Estrutura"); ?>
                        </a> 
                        <a href="javascript:;" title="<?php echo T_("Diferenciais"); ?>" data-target="diferenciais" class="nav-a menu-target">
                            <?php echo T_("Diferenciais"); ?>
                        </a> 
                        <a href="javascript:;" title="<?php echo T_("Níveis de ensino"); ?>" data-target="niveis" class="nav-a menu-target">
                            <?php echo T_("Níveis de ensino"); ?>
                        </a> 
                        <a href="javascript:;" title="<?php echo T_("Programa Bilíngue"); ?>" data-target="banner-bilingue" class="nav-a menu-target">
                            <?php echo T_("Programa Bilíngue"); ?>
                        </a> 
                        <a href="javascript:;" title="<?php echo T_("Atividades Extras"); ?>" data-target="banner-extras" class="nav-a menu-target">
                            <?php echo T_("Atividades Extras"); ?>
                        </a>                                                   
                    </nav>
                </div>
            </div>
            <div class="btn-right">
                <i class="fas fa-calendar-alt"></i>
                <a href="javascript:;" title="<?php echo T_("Agende sua visita"); ?>" data-target="contato" class="btn-schedule menu-target">
                    <?php echo T_("Agende sua visita"); ?>
                </a> 
            </div>
        </div>
    </div>
</header>

<div class="modal-ez">
    <div class="modal-wrapper">
        <div class="content">

        </div>

        <button type="button" class="close-modal"></button>
    </div>
</div>