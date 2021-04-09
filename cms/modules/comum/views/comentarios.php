<form action="<?php echo site_url($module.'/comentarios/'.$id_item); ?>" class="filter" method="POST">
    <div class="col-xs-7 col-sm-8 col-md-3 col-lg-2">
        <select id="filter-show" name="show" class="selectpicker visualizeItens"  data-style="btn-primary" data-width="100%">
            <option title="<?php echo T_('Visualizar: 10'); ?>" value="10"<?php echo ($show == 10) ? ' selected="selected"' : ''; ?>>10</option>
            <option title="<?php echo T_('Visualizar: 25'); ?>" value="25"<?php echo ($show == 25) ? ' selected="selected"' : ''; ?>>25</option>
            <option title="<?php echo T_('Visualizar: 50'); ?>" value="50"<?php echo ($show == 50) ? ' selected="selected"' : ''; ?>>50</option>
            <option title="<?php echo T_('Visualizar: 100'); ?>" value="100"<?php echo ($show == 100) ? ' selected="selected"' : ''; ?>>100</option>
        </select>
    </div>
    <div class="no-padleft col-xs-12 col-sm-12 col-md-7 col-lg-8">
        <div class="input-group col-sm-12">
            <input id="filter-search" name="search[title]" type="text" class="form-control" placeholder="<?php echo T_('Buscar por...'); ?>" value="<?php echo ($search) ? $search['title'] : ''; ?>">
            <div class="input-group-btn">
                <button class="btn btn-default rounded-right" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
        <?php if ($search){ ?>
        <div class="col-xs-12">
            <div class="text-right innerT">
                <a href="<?php echo site_url('comum/limpar-busca'); ?>"><i class="fa fa-times"></i> <?php echo T_('Limpar Busca'); ?></a>
            </div>
        </div>
        <?php } ?>
    </div>
</form>
<?php if ($items) { ?>
    <div class="no-padleft col-sm-12">
        <div class="separator"></div>
        <table class="table checkboxs">
            <thead class="bg-gray"><thead class="bg-gray">
                <tr>
                    <th class="text-center" width="50">
                        <?php if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
                        <div class="checkbox checkbox-single margin-none">
                            <label class="checkbox-custom">
                                <i class="far fa-fw fa-square"></i>
                                <input type="checkbox">
                            </label>
                        </div>
                        <?php } ?>
                    </th>
                    <th><?php echo T_('Data'); ?>
                    <th><?php echo T_('Nome'); ?>
                    <th><?php echo T_('Aprovado'); ?></th>
                    <th width="250" class="text-center" data-hide="phone"><?php echo T_('Ações'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $key => $item) { ?>
                <tr data-id="<?php echo $item->id; ?>" class="<?php echo $item->approved ? 'approved' : ($item->approved !== null ? 'disapproved' : ''); ?>">
                    <td class="text-center text-top">
                        <?php if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
                        <div class="checkbox checkbox-single margin-none">
                            <label class="checkbox-custom">
                                <i class="far fa-fw fa-square"></i>
                                <input type="checkbox">
                            </label>
                        </div>
                        <?php } ?>
                    </td>
                    <td><?php echo $item->date.' '.$item->hour; ?></td>
                    <td><?php echo $item->name; ?></td>
                    <td>
                        <?php if (in_array('aprovar', $session_permissions[$current_module->id]) ){ ?>
                            <div class="make-switch">
                                <?php if ($item->approved == '1') { ?>
                                    <div class="button-switch button-on"><?php echo T_('Sim'); ?></div>
                                    <input type="checkbox" name="approved" checked="checked" id="inputApproved">
                                <?php } else { ?>
                                    <div class="button-switch button-off"><?php echo T_('Não'); ?></div>
                                    <input type="checkbox" name="approved" id="inputApproved">
                                <?php } ?>
                            </div>
                        <?php } else {
                            echo $item->approved ? T_('Sim') : T_('Não');
                        } ?>
                    </td>
                    <td class="text-center">
                        <?php if (in_array('modal', $session_permissions[$current_module->id]) ){ ?>
                        <span href="#responsive" data-toggle="modal" class="chamaModal btn btn-default"><i class="fa fa-eye"></i> <?php echo T_('Visualizar'); ?></span>
                        <?php } if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
                        <a href="<?php echo site_url('comum/comentarios/delete/'.$item->id); ?>" class="delete-button no-ajax"><button class="btn btn-default"><i class="fa fa-trash-alt"></i> <?php echo T_('Excluir'); ?></button></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else {?>
    <div class="no-padleft col-sm-12 text-center">
        <div class="separator-horizontal col-sm-12"></div>
        <?php echo T_('Nenhum comentário').' '.($search ? T_('encontrado') : T_('enviado') ); ?>.
    </div>
<?php }
if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
<div class="col-sm-3">
    <form class="hide delete-all-form" action="<?php echo site_url($module . '/delete-multiple'); ?>" method="POST">
        <input type="hidden" name="id" value="">
        <button class="btn btn-primary btn-stroke"><i class="fa fa-trash-alt"></i> <?php echo T_('Excluir selecionados'); ?></button>
    </form>
</div>
<?php } ?>
<div class="col-sm-9 text-right pagination-wrapper">
    <?php echo $paginacao; ?>
</div>
