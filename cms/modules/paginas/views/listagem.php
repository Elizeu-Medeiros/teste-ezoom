<?php   $this->load->view('comum/busca', array('view_search' => 'filtros' ));
    if ($items) { ?>
    <div class="no-padleft table-list col-sm-12">
        <div class="separator"></div>
        <table class="table checkboxs">
            <thead class="bg-gray">
                <tr>
                    <?php if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
                    <th data-field="id" class="text-center">
                        <div class="checkbox checkbox-single">
                            <label class="checkbox-custom">
                                <i class="far fa-fw fa-square"></i>
                                <input type="checkbox">
                            </label>
                        </div>
                    </th>
                    <?php } ?>
                    <th>
                        <a href="<?php echo site_url($this->uri->uri_string()).'?order=area'; ?>">
                            Área <i class="fa<?php echo $order_by && $order_by['column'] == 'area' ? ' fa-caret-'.($order_by['order']) : ''; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?php echo site_url($this->uri->uri_string()).'?order=subarea'; ?>">
                            Subárea <i class="fa<?php echo $order_by && $order_by['column'] == 'subarea' ? ' fa-caret-'.($order_by['order']) : ''; ?>"></i>
                        </a>
                    </th>
                    <th class="title-column">
                        <a href="<?php echo site_url($this->uri->uri_string()).'?order=title'; ?>">
                            Título <i class="fa<?php echo $order_by && $order_by['column'] == 'title' ? ' fa-caret-'.($order_by['order']) : ''; ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="<?php echo site_url($this->uri->uri_string()).'?order=slug'; ?>">
                            Slug <i class="fa<?php echo $order_by && $order_by['column'] == 'slug' ? ' fa-caret-'.($order_by['order']) : ''; ?>"></i>
                        </a>
                    </th>
                    <th class="text-center action-column">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($items as $key => $item){ ?>
                <tr data-id="<?php echo $item->id_common_content; ?>" id="item-<?php echo $item->id_common_content; ?>" >
                    <?php if (in_array('excluir', $session_permissions[$current_module->id])){ ?>
                    <td class="text-center text-middle" width="70px">
                        <div class="checkbox checkbox-single">
                            <label class="checkbox-custom">
                                <i class="far fa-fw fa-square"></i>
                                <input type="checkbox">
                            </label>
                        </div>
                    </td>
                    <?php } ?>
                    <td class="text-middle"><?php echo $item->area; ?></td>
                    <td class="text-middle"><?php echo $item->subarea; ?></td>
                    <td class="text-middle">
                        <?php if($item->enable_gallery=='enabled'){ ?>
                            <i class="glyphicon glyphicon-picture"></i>
                        <?php } if($item->enable_videos=='enabled'){ ?>
                            <i class="glyphicon glyphicon-facetime-video"></i>
                        <?php }
                        echo $item->title.($item->subtitle ? ' '.$item->subtitle : ''); ?>
                    </td>
                    <td class="text-middle"><?php echo $item->slug; ?></td>
                    <td class="text-right nowrap">
                        <?php if($this->session->userdata('user_data')->id == 1) { ?>
                        <a href="<?php echo site_url('paginas/permissoes/'.$item->id_common_content); ?>"><button class="btn btn-default"><i class="glyphicon glyphicon-cog"></i> Permissões</button></a>
                        <?php }
                        if (in_array('editar', $session_permissions[$current_module->id]) ){ ?>
                            <a href="<?php echo site_url('paginas/editar/'.$item->id_common_content); ?>">
                                <button class="btn <?php echo ($item->enable_edit=='enabled' ) ? ' btn-default ' : 'btn-secondary'; ?>">
                                    <i class="fa fa-edit"></i> Editar
                                </button>
                            </a>
                        <?php }
                        if (in_array('excluir', $session_permissions[$current_module->id]) ){ ?>
                            <a href="<?php echo site_url('paginas/delete/'.$item->id_common_content); ?>" class="delete-button">
                                <button class="btn <?php echo ($item->enable_delete=='enabled') ? ' btn-default ' : ' btn-secondary'; ?>">
                                    <i class="fa fa-trash-alt"></i> Excluir
                                </button>
                            </a>
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
        Nenhum dado cadastrado.
    </div>
<?php }?>
<div class="col-sm-3">
    <form class="hide delete-all-form" action="<?php echo site_url('paginas/delete-multiple'); ?>" method="POST">
        <input type="hidden" name="id" value="">
        <button class="btn btn-primary btn-stroke"><i class="fa fa-trash-alt"></i> Excluir selecionados</button>
    </form>
</div>
<div class="col-sm-9 text-right pagination-wrapper">
    <?php echo $paginacao; ?>
</div>
