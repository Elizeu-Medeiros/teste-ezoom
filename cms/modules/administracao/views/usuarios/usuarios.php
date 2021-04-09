<div class="listing-header">

<?php  if (in_array('cadastrar', $session_permissions[$current_module->id])){ ?>
    <div class="no-padleft col-xs-5 col-sm-4 col-md-2 col-lg-2">
        <a href="<?php echo site_url('administracao/usuarios/cadastrar'); ?>">
            <button class="btn btn-primary btn-cadastro"><i class="fa fa-plus"></i> <?php echo T_('Cadastrar'); ?></button>
        </a>
    </div>
<?php } ?>
    <form action="<?php echo site_url('administracao/usuarios'); ?>" class="filter" method="POST">
        <div class="col-xs-7 col-sm-8 col-md-3 col-lg-2">
            <select id="filter-show" name="show" class="selectpicker vivisualizeItens"  data-style="btn-primary" data-width="100%">
                <option title="<?php echo T_('Visualizar: 10'); ?>" value="10"<?php echo ($show == 10) ? ' selected="selected"' : ''; ?>>10</option>
                <option title="<?php echo T_('Visualizar: 25'); ?>" value="25"<?php echo ($show == 25) ? ' selected="selected"' : ''; ?>>25</option>
                <option title="<?php echo T_('Visualizar: 50'); ?>" value="50"<?php echo ($show == 50) ? ' selected="selected"' : ''; ?>>50</option>
                <option title="<?php echo T_('Visualizar: 100'); ?>" value="100"<?php echo ($show == 100) ? ' selected="selected"' : ''; ?>>100</option>
            </select>
        </div>
        <div class="no-padleft col-xs-12 col-sm-12 col-md-7 col-lg-8">
            <div class="input-group col-sm-12">
                <input name="search" type="text" class="form-control" placeholder="<?php echo T_('Buscar por...'); ?>" value="<?php echo ($search) ? $search : ''; ?>" id="filter-search">
                <div class="input-group-btn">
                    <button class="btn btn-default rounded-right" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php if ($search){ ?>
        <div class="col-xs-12">
            <div class="text-right innerT">
                <a href="<?php echo site_url('comum/limpar-busca'); ?>"><i class="fa fa-times"></i> <?php echo T_('Limpar Busca'); ?></a>
            </div>
        </div>
        <?php } ?>
    </form>
</div>
<?php if ($users){ ?>
    <div class="no-padleft table-list col-sm-12">
        <div class="separator"></div>
        <table class="table checkboxs">
            <thead class="bg-gray">
                <tr>
                    <?php if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
                    <th class="text-center">
                        <div class="checkbox checkbox-single">
                            <label class="checkbox-custom">
                                <i class="far fa-fw fa-square"></i>
                                <input type="checkbox">
                            </label>
                        </div>
                    </th>
                    <?php } ?>
                    <th><?php echo T_('Usuário'); ?>
                    <th class="hidden-xs"><?php echo T_('Grupo'); ?>
                    <th><?php echo T_('Ativo'); ?></th>
                    <th class="text-center action-column"><?php echo T_('Ações'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $key => $user){ ?>
            <tr data-id="<?php echo $user->id; ?>">
                <?php if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
                <td class="text-center text-middle" width="70px">
                    <?php if ($this->auth->data('id') != $user->id ){ ?>
                    <div class="checkbox checkbox-single">
                        <label class="checkbox-custom">
                            <i class="far fa-fw fa-square"></i>
                            <input type="checkbox">
                        </label>
                    </div>
                    <?php } ?>
                </td>
                <?php } ?>
                <td class="text-middle"><?php echo $user->name; ?></td>
                <td class="text-middle hidden-xs"><?php echo $user->groupName; ?></td>
                <td>
                    <?php if (in_array('editar', $session_permissions[$current_module->id]) && $this->auth->data('id') != $user->id){ ?>
                        <div class="form-group">
                            <div class="make-switch">
                                <?php if ($user->status == '1'){ ?>
                                    <div class="button-switch button-on"><?php echo T_('Sim'); ?></div>
                                    <input type="checkbox" name="status" checked="checked" id="inputStatus">
                                <?php } else { ?>
                                    <div class="button-switch button-off"><?php echo T_('Não'); ?></div>
                                    <input type="checkbox" name="status" id="inputStatus">

                                <?php } ?>
                            </div>
                        </div>
                    <?php }else{
                        echo $user->status ? T_('Sim') : T_('Não');
                    } ?>
                </td>
                <td class="text-right">
                    <?php if ($this->auth->data('id') == $user->id || $this->auth->data('admin')){ ?>
                        <a href="<?php echo site_url('administracao/usuarios/modal/'.$user->id); ?>" class="change-password" title="<?php echo T_('Alterar Senha'); ?>"><button class="btn btn-default"><i class="fa fa-key"></i> </button></a>
                    <?php }
                    if (in_array('editar', $session_permissions[$current_module->id])){ ?>
                        <a href="<?php echo site_url('administracao/usuarios/editar/'.$user->id); ?>" title="<?php echo T_('Editar'); ?>"><button class="btn btn-default"><i class="fa fa-edit"></i> </button></a>
                    <?php }
                    if (in_array('excluir', $session_permissions[$current_module->id]) && $this->auth->data('id') != $user->id){ ?>
                        <a href="<?php echo site_url('administracao/usuarios/delete/'.$user->id); ?>" class="delete-button" title="<?php echo T_('Excluir'); ?>"><button class="btn btn-default"><i class="fa fa-trash-alt"></i> </button></a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }else{?>
    <div class="no-padleft col-sm-12 text-center">
        <div class="separator-horizontal col-sm-12"></div>
        <?php echo T_('Nenhum usuário cadastrado.'); ?>
    </div>
<?php }?>
<div class="col-sm-3">
    <form class="hide delete-all-form" action="<?php echo site_url('administracao/usuarios/delete-multiple'); ?>" method="POST">
        <input type="hidden" name="id" value="">
        <button class="btn btn-primary btn-stroke"><i class="fa fa-trash-alt"></i> <?php echo T_('Excluir selecionados'); ?></button>
    </form>
</div>
<div class="col-sm-9 text-right pagination-wrapper">
    <?php echo $paginacao; ?>
</div>