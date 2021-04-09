<form action="<?php echo site_url($current_module->slug .'/'. (isset($item) ? 'edit/'.$id : 'add'))?>" id="validateSubmitForm" class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
    <?php if (isset($item)){ ?>
    <input type="hidden" name="id" value="<?php echo $id; ?>" id="inputId" />
    <?php } ?>
    <ul class="nav nav-tabs col-sm-12">
        <li class="active"><a href="#tab1" class="glyphicons notes" data-toggle="tab"><i></i> Dados Gerais</a></li>
    </ul>
    <div class="tab-content col-sm-12">

        <!-- Tab (Dados Gerais) -->
        <div class="tab-pane fade active in" id="tab1">
            <div class="form-group col-xs-12">
                <label class="col-xs-12 control-label">Ativo: </label>
                <div class="make-switch">
                    <?php if (!isset($item) || $item->status == '1') { ?>
                        <div class="button-switch button-on">Sim</div>
                        <input type="checkbox" name="status" checked="checked" id="inputStatus">
                    <?php } else { ?>
                        <div class="button-switch button-off">Não</div>
                        <input type="checkbox" name="status" id="inputStatus">
                    <?php } ?>
                </div>
            </div>
            <div class="">
                <div class="form-group col-xs-12">
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Dados Gerais
                    </div>
                    <div class="panel-body">

                        <?php echo $this->load->view('comum/nav-lang'); ?>

                        <div class="tab-content">
                            <?php foreach($languages as $key => $language){ ?>
                                <div class="tab-pane<?php echo ($key == 0) ? ' active in ' : ''; ?> fade" id="tablang<?php echo $key; ?>">
                                    <div class="col-xs-12 form-group">
                                        <label for="inputTitle<?php echo $key; ?>" class="control-label">Título: </label>
                                        <textarea type="text" class="form-control title-height inputWithCK" name="value[<?php echo $language->id; ?>][title]" id="inputTitle<?php echo $key; ?>" <?php echo ($language->id == 1) ? ' required' : '';?>><?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->title : ''; ?></textarea>
                                    </div>
                                    <div class="col-xs-12 col-md-12 col-sm-12 form-group">
                                        <label for="inputText<?php echo $key; ?>" class="control-label"><?php echo T_('Descrição'); ?>: </label>
                                        <textarea id="inputText<?php echo $key; ?>" name="value[<?php echo $language->id; ?>][text]" class="form-control ckeditor" style="height: 320px;" rows="5"><?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->text : ''; ?></textarea>
                                    </div>
                                    <div class="form-group col-xs-12 col-lg-12">
                                        <label class="control-label" for="units"><?php echo T_('Selecione as unidades:'); ?> </label>
                                        <select id="units" name="unit[]" class="form-control placeholder select2" tabindex="-1" multiple>
                                            <?php foreach($units as $key=>$value){ ?>
                                                <option <?php echo isset($item) && in_array($value->id, array_map(function($enf) { 
                                                    return $enf->id_unit; 
                                                    }, $item->units)) ? ' selected ' : ''; ?> value="<?php echo $value->id?>">
                                                    <?php echo $value->title; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Imagem
                    </div>
                    <div class="panel-body">
                    <?php
                            $this->load->view('gallery/single-file', array(
                                'label' => 'Imagem',
                                'typeupload' => null,
                                'module' => $current_module->slug,
                                'file' => (isset($item->image) && $item->image) ? $item->image : null,
                                'resize' => FALSE,
                                'dimensions' => array(
                                    'w' => 500,
                                    'h' => 500
                                ),
                                'id' => 'fileuploadImage-primary',
                                'name' => 'image',
                                'key' => 1,
                                'upload' => site_url('gallery/upload/image'),
                                'ext' => 'jpg|png'
                            ));
                        ?>

                        
                    </div>
                </div>
            </div>
        </div>
       
        <div class="clearfix"></div>
    </div>

    <div class="col-sm-12 text-center">
        <a href="<?php echo site_url($current_module->slug); ?>" class="btn btn-default">Cancelar</a>
        <button type="submit" class="btn btn-primary"><?php echo isset($item) ? 'Salvar' : 'Cadastrar'; ?></button>
    </div>
</form>
