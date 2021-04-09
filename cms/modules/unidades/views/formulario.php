<form action="<?php echo site_url($current_module->slug .'/'. (isset($item) ? 'edit/'.$id : 'add'))?>" id="validateSubmitForm" class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
    <?php if (isset($item)){ ?>
    <input type="hidden" name="id" value="<?php echo $id; ?>" id="inputId" />
    <?php } ?>
    <ul class="nav nav-tabs col-sm-12">
        <li class="active"><a href="#tab1" class="glyphicons notes" data-toggle="tab"><i></i> Dados Gerais</a></li>
        <li><a href="#tab2" data-toggle="tab"><i class="fa fa-images"></i> Imagens</a></li>
        <li><a href="#tab5" data-toggle="tab"><i class="fa fa-map"></i> <?php echo T_('Localização'); ?></a></li>
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
                                        <textarea class="form-control title-height inputWithCK" name="value[<?php echo $language->id; ?>][title]" id="inputTitle<?php echo $key; ?>" <?php echo ($language->id == 1) ? ' required' : '';?>><?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->title : ''; ?></textarea>
                                    </div>
                                    <div class="col-xs-6 form-group">
                                        <label for="inputEmail<?php echo $key; ?>" class="control-label">E-mail: </label>
                                        <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][email]" id="inputSlug<?php echo $key; ?>" placeholder="E-mail" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->email : ''; ?>">
                                    </div>
                                    <div class="col-xs-6 form-group">
                                        <label for="inputPhone<?php echo $key; ?>" class="control-label">Telefone: </label>
                                        <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][phone]" id="inputPhone<?php echo $key; ?>" placeholder="Telefone" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->phone : ''; ?>">
                                    </div>
                                    <div class="col-xs-6 form-group">
                                        <label for="inputInstagram<?php echo $key; ?>" class="control-label">Instagram: </label>
                                        <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][instagram]" id="inputInstagram<?php echo $key; ?>" placeholder="Instagram" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->instagram : ''; ?>">
                                    </div>
                                    <div class="col-xs-6 form-group">
                                        <label for="inputFacebook<?php echo $key; ?>" class="control-label">Facebook: </label>
                                        <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][facebook]" id="inputFacebook<?php echo $key; ?>" placeholder="Facebook" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->facebook : ''; ?>">
                                    </div>
                                    <div class="col-xs-12 col-sm-6 form-group">
                                        <label for="inputVideo<?php echo $key; ?>" class="control-label">Vídeo: </label>
                                        <input type="url" class="form-control" name="value[<?php echo $language->id; ?>][video]" id="inputVideo<?php echo $key; ?>" placeholder="Vídeo" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->video : ''; ?>">
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
                    Logo
                    </div>
                    <div class="panel-body">
                    <?php
                            $this->load->view('gallery/single-file', array(
                                'label' => 'Logo',
                                'typeupload' => null,
                                'module' => $current_module->slug,
                                'file' => (isset($item->image) && $item->image) ? $item->image : null,
                                'resize' => FALSE,
                                'dimensions' => array(
                                    'w' => 197,
                                    'h' => 36
                                ),
                                'id' => 'fileuploadImage-primary',
                                'name' => 'image',
                                'key' => 1,
                                'upload' => site_url('gallery/upload/image'),
                                'ext' => 'jpg|png'
                            ));
                        ?>

                        <?php
                            $this->load->view('gallery/single-file', array(
                                'label' => 'Imagem LandingPage',
                                'typeupload' => null,
                                'module' => $current_module->slug,
                                'file' => (isset($item->landing) && $item->landing) ? $item->landing : null,
                                'resize' => FALSE,
                                'dimensions' => array(
                                    'w' => 500,
                                    'h' => 1000
                                ),
                                'id' => 'fileuploadlanding-primary',
                                'name' => 'landing',
                                'key' => 1,
                                'upload' => site_url('gallery/upload/image'),
                                'ext' => 'jpg|png'
                            ));
                        ?>

                        <?php
                            $this->load->view('gallery/single-file', array(
                                'label' => 'Imagem LandingPage Mobile',
                                'typeupload' => null,
                                'module' => $current_module->slug,
                                'file' => (isset($item->landing_mobile) && $item->landing_mobile) ? $item->landing_mobile : null,
                                'resize' => FALSE,
                                'dimensions' => array(
                                    'w' => 414,
                                    'h' => 178
                                ),
                                'id' => 'fileuploadlanding_mobile-primary',
                                'name' => 'landing_mobile',
                                'key' => 1,
                                'upload' => site_url('gallery/upload/image'),
                                'ext' => 'jpg|png'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tab2">
            <div class="col-xs-12">
            <?php
                $this->load->view('gallery/images',
                    array(
                        'label'         => 'Galeria de Imagens',
                        'images'        => isset($item) ? $item->images : array(),
                        'gallerytable'  => 'site_common_content_gallery',
                        'path'          => 'userfiles/'.$current_module->slug.'/',
                        'resize'        => 'true',
                        'width'         => '2000',
                        'height'        => '1000',
                        'fit'           => 'inside',
                        'typeGallery'   => null,
                        //'multilang'     => true
                    )
                );
            ?>
            </div>
        </div>
        <!-- Tab Localização -->
        <div class="tab-pane fade" id="tab5">
            <?php
                $location_config = array();
                if (isset($countries))
                    $location_config['countries'] = $countries;
                if (isset($states))
                    $location_config['estados'] = $states;
                if (isset($cities))
                    $location_config['cidades'] = $cities;
                if (isset($item)){
                    $location_config['data'] = new stdClass();
                    $location_config['data']->lat = isset($item->lat) ? $item->lat : '';
                    $location_config['data']->lng = isset($item->lng) ? $item->lng : '';
                    $location_config['data']->state = isset($item->state) ? $item->state : '';
                    $location_config['data']->id_country = isset($item->id_country) ? $item->id_country : '';
                    $location_config['data']->city = isset($item->city) ? $item->city : '';
                    $location_config['data']->suburb = isset($item->district) ? $item->district : '';
                    $location_config['data']->zip_code = isset($item->zipcode) ? $item->zipcode : '';
                    $location_config['data']->street = isset($item->address) ? $item->address : '';
                    $location_config['data']->number = isset($item->number) ? $item->number : '';
                    $location_config['data']->additional_info = isset($item->complement) ? $item->complement : '';

                }
                $this->load->view('endereco/endereco', $location_config);
            ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="col-sm-12 text-center">
        <a href="<?php echo site_url($current_module->slug); ?>" class="btn btn-default">Cancelar</a>
        <button type="submit" class="btn btn-primary"><?php echo isset($item) ? 'Salvar' : 'Cadastrar'; ?></button>
    </div>
</form>
