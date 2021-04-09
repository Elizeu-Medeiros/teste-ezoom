<form action="<?php echo site_url($current_module->slug . ( isset($item) ? '/edit/'.$id : '/add' ) ); ?>" id="validateSubmitForm" class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
    <?php   if (isset($item)){ ?>
    <input type="hidden" name="id" value="<?php echo $id; ?>" id="inputId" />
    <?php   } ?>
    <ul class="nav nav-tabs col-sm-12">
        <li class="active"><a href="#tab1" data-toggle="tab"><i></i> <?php echo T_('Dados'); ?></a></li>
        <li><a href="#tab2" data-toggle="tab"><i></i> <?php echo T_('Localização'); ?></a></li>
        <li><a href="#tab3" data-toggle="tab"><i></i> <?php echo T_('Redes Sociais'); ?></a></li>
        <li><a href="#tab4" data-toggle="tab"><i></i> <?php echo T_('SEO'); ?></a></li>
        <?php   if($this->config->item('multi_company')){ ?>
        <li><a href="#tab5" data-toggle="tab"><i></i> <?php echo T_('Site'); ?></a></li>
        <?php   } ?>
    </ul>
    <div class="tab-content col-sm-12">
        <!-- Primeira Tab (Empresa) -->
        <div class="tab-pane fade active in" id="tab1">
            <div class="tab-content">
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputTitulo" class="control-label"><?php echo T_('Razão Social:'); ?> </label>
                    <input type="text" class="form-control" name="company_name" id="inputTitulo" placeholder="<?php echo T_('Razão Social'); ?>" value="<?php echo (isset($item->company_name)) ?  $item->company_name : '';?>" required>
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputTitulo" class="control-label"><?php echo T_('Nome Fantasia:'); ?> </label>
                    <input type="text" class="form-control" name="fantasy_name" id="inputTitulo" placeholder="<?php echo T_('Nome Fantasia'); ?>" value="<?php echo (isset($item->fantasy_name)) ?  $item->fantasy_name : '';?>" required>
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputPhone" class="control-label"><?php echo T_('Telefone:'); ?> </label>
                    <input type="text" class="form-control" name="phone" id="inputPhone" placeholder="<?php echo T_('Telefone'); ?>" value="<?php echo (isset($item->phone)) ?  $item->phone : '';?>" required>
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputSac" class="control-label"><?php echo T_('Sac:'); ?> </label>
                    <input type="text" class="form-control" name="sac" id="inputSac" placeholder="<?php echo T_('Sac'); ?>" value="<?php echo (isset($item->sac)) ?  $item->sac : '';?>">
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputEmail" class="control-label"><?php echo T_('E-mail:'); ?> </label>
                    <input type="text" class="form-control" name="email" id="inputEmail" placeholder="<?php echo T_('E-mail'); ?>" value="<?php echo (isset($item->email)) ?  $item->email : '';?>" required>
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputGoogleTagManager" class="control-label"><?php echo T_('Google Tag Manager:'); ?> </label>
                    <input type="text" class="form-control" name="google_tag_manager" id="inputGoogleTagManager" placeholder="<?php echo T_('Google Tag Manager'); ?>" value="<?php echo isset($item) ? $item->google_tag_manager : ''; ?>">
                </div>

                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputLanguage" class="control-label"><?php echo T_('Idioma Principal:'); ?> </label>
                    <select class="form-control select2" name="language_main" id="inputLanguage" required>
                        <?php foreach ($languages as $key => $value) { ?>
                        <option <?php echo ( isset($item) && $value->id == $item->language_main ) ? ' selected ' : ''; ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputLanguages" class="control-label"><?php echo T_('Idiomas Disponíveis:'); ?> </label>
                    <select class="form-control select2" name="languages_site[]" id="inputLanguages" data-placeholder="<?php echo T_('Nenhum idioma selecionado'); ?>" multiple required >
                        <option value=""></option>
                        <?php foreach ($languages as $key => $value) { ?>
                        <option <?php echo ( isset($item) && in_array($value->id, explode(',', $item->languages_site) ) ) ? ' selected ' : ''; ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php           
                    $this->load->view('gallery/single-file', array(
                        'label' => 'Logo',
                        'module' => 'empresas',
                        'file' => (isset($item->image) && $item->image) ? $item->image : null,
                        'dimensions' => array(
                            'w' => '197',
                            'h' => '36'
                        ),
                        'id' => 'fileuploadImage-primary',
                        'key' => 1,
                        'name' => 'image',
                        'upload' => site_url('gallery/upload/image')
                    ));
                ?>
                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputStatus" class="col-xs-12 control-label"><?php echo T_('Ativo:'); ?> </label>
                    <div class="make-switch">
                        <?php if (!isset($item) || $item->status == '1'){ ?>
                            <div class="button-switch button-on"><?php echo T_('Sim'); ?></div>
                            <input type="checkbox" name="status" checked="checked" id="inputStatus">
                        <?php } else { ?>
                            <div class="button-switch button-off"><?php echo T_('Não'); ?></div>
                            <input type="checkbox" name="status" id="inputStatus">
                        <?php } ?>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 form-group">
                    <label for="inputSite" class="col-xs-12 control-label"><?php echo T_('Site Ativo:'); ?> </label>
                    <div class="make-switch">
                        <?php if (!isset($item) || $item->active_site == '1'){ ?>
                            <div class="button-switch button-on"><?php echo T_('Sim'); ?></div>
                            <input type="checkbox" name="active_site" checked="checked" id="inputSite">
                        <?php } else { ?>
                            <div class="button-switch button-off"><?php echo T_('Não'); ?></div>
                            <input type="checkbox" name="active_site" id="inputSite">
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Segunda Tab (Localização) -->
        <div class="tab-pane fade" id="tab2">
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
                    $location_config['data']->suburb = isset($item->suburb) ? $item->suburb : '';
                    $location_config['data']->zip_code = isset($item->zipcode) ? $item->zipcode : '';
                    $location_config['data']->street = isset($item->address) ? $item->address : '';
                    $location_config['data']->number = isset($item->number) ? $item->number : '';
                    $location_config['data']->additional_info = isset($item->complement) ? $item->complement : '';
                }
                $this->load->view('endereco/endereco', $location_config);
            ?>
        </div>

        <!-- Terceira Tab (Redes Sociais) -->
        <div class="tab-pane fade" id="tab3">

            <?php echo $this->load->view('comum/nav-lang', array('tabname' => 'tablang', 'languages' => $languages_by_company)); ?>
            <div class="tab-content">
                <!-- Body da tab linguagem -->
                <?php foreach($languages_by_company as $key => $language){ ?>
                    <div class="tab-pane<?php echo ($key == 0) ? ' active in ' : ''; ?> fade" id="tablang<?php echo $key; ?>">
                        <div class="col-xs-12 col-sm-6 form-group">
                            <label for="inputFacebook<?php echo $key; ?>" class="control-label"><?php echo T_('Facebook:'); ?> </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][facebook]" id="inputFacebook<?php echo $key; ?>" placeholder="Facebook" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->facebook : ''; ?>">
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group">
                            <label for="inputInstagram<?php echo $key; ?>" class="control-label"><?php echo T_('Instagram:'); ?> </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][instagram]" id="inputInstagram<?php echo $key; ?>" placeholder="Instagram" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->instagram : ''; ?>">
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group">
                            <label for="inputYoutube<?php echo $key; ?>" class="control-label"><?php echo T_('Youtube:'); ?> </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][youtube]" id="inputYoutube<?php echo $key; ?>" placeholder="Youtube" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->youtube : ''; ?>">
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group">
                            <label for="inputTwitter<?php echo $key; ?>" class="control-label"><?php echo T_('Twitter:'); ?> </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][twitter]" id="inputTwitter<?php echo $key; ?>" placeholder="Twitter" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->twitter : ''; ?>">
                        </div>
                        <div class="col-xs-12 col-sm-6 form-group">
                            <label for="inputSoundcloud<?php echo $key; ?>" class="control-label"><?php echo T_('Soundcloud:'); ?> </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][soundcloud]" id="inputSoundcloud<?php echo $key; ?>" placeholder="Soundcloud" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->soundcloud : ''; ?>">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Quarta Tab (SEO) -->
        <div class="tab-pane fade" id="tab4">
            <?php echo $this->load->view('comum/nav-lang', array('tabname' => 'seotablang', 'languages' => $languages_by_company)); ?>
            <div class="tab-content">
                <!-- Body da tab linguagem -->
                <?php foreach($languages_by_company as $key => $language){ ?>
                    <div class="tab-pane<?php echo ($key == 0) ? ' active in ' : ''; ?> fade" id="seotablang<?php echo $key; ?>">
                        <div class="col-sm-12 form-group">
                            <label for="inputWebmaster<?php echo $key; ?>" class="control-label">Meta Webmaster: </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][meta_webmaster]" id="inputWebmaster<?php echo $key; ?>" placeholder="Meta Webmaster" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->meta_webmaster : ''; ?>">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="inputTitle<?php echo $key; ?>" class="control-label">Meta Title: </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][meta_title]" id="inputTitle<?php echo $key; ?>" placeholder="Meta Title" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->meta_title : ''; ?>">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="inputDescription<?php echo $key; ?>" class="control-label">Meta Description: </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][meta_description]" id="inputDescription<?php echo $key; ?>" placeholder="Meta Description" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->meta_description : ''; ?>">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label for="inputKeywords<?php echo $key; ?>" class="control-label">Meta Keywords: </label>
                            <input type="text" class="form-control" name="value[<?php echo $language->id; ?>][meta_keywords]" id="inputKeywords<?php echo $key; ?>" placeholder="Meta Keywords" value="<?php echo (isset($item->languages[$language->id])) ? $item->languages[$language->id]->meta_keywords : ''; ?>">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <?php   if($this->config->item('multi_company')){ ?>
        <!-- Quinta Tab (Site) -->
        <div class="tab-pane fade" id="tab5">

            <div class="col-sm-12 col-sm-6 form-group">
                <label for="inputSlug" class="control-label"><?php echo T_('Slug:'); ?> </label>
                <input type="text" class="form-control" name="slug" id="inputSlug" placeholder="<?php echo T_('Slug'); ?>" value="<?php echo isset($item) ? $item->slug : ''; ?>">
            </div>

            <div class="col-xs-12 col-sm-6 form-group">
                <label for="inputDomain" class="control-label"><?php echo T_('Domínio:'); ?> </label>
                <input type="text" class="form-control" name="domain" id="inputDomain" placeholder="<?php echo T_('URL'); ?>" value="<?php echo isset($item) ? $item->domain : ''; ?>">
            </div>
            <?php if($this->config->item('multi_company_colors')) { ?>
            <div class="col-sm-12 col-sm-6 form-group">
                <label for="inputColor1" class="control-label"><?php echo T_('Cor Principal:'); ?> </label>
                <div class="input-group input-append color colorpicker2" data-color="<?php echo isset($item->colors) ? $item->colors->primary : $default_colors['primary']; ?>">
                    <span class="input-group-addon add-on"><i></i></span>
                    <input id="inputColor1" type="text" class="form-control" placeholder="<?php echo T_('Cor Principal'); ?>" name="colors[primary]" value="<?php echo isset($item->colors) ? $item->colors->primary : $default_colors['primary']; ?>">
                </div>
            </div>

            <div class="col-sm-12 col-sm-6 form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="inputColor2" class="control-label"><?php echo T_('Cor Degradê de:'); ?> </label>
                        <div class="input-group input-append color colorpicker2" data-color="<?php echo isset($item->colors) ? $item->colors->gradient->from : $default_colors['gradient_from']; ?>">
                            <span class="input-group-addon add-on"><i></i></span>
                            <input id="inputColor2" type="text" class="form-control" placeholder="<?php echo T_('Cor Degradê de'); ?>" name="colors[gradient][from]" value="<?php echo isset($item->colors) ? $item->colors->gradient->from : $default_colors['gradient_from']; ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="inputColor3" class="control-label"><?php echo T_('Cor Degradê para:'); ?> </label>
                        <div class="input-group input-append color colorpicker2" data-color="<?php echo isset($item->colors) ? $item->colors->gradient->to : $default_colors['gradient_to']; ?>">
                            <span class="input-group-addon add-on"><i></i></span>
                            <input id="inputColor3" type="text" class="form-control" placeholder="<?php echo T_('Cor Degradê para'); ?>" name="colors[gradient][to]" value="<?php echo isset($item->colors) ? $item->colors->gradient->to : $default_colors['gradient_to']; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }

                $this->load->view('gallery/single-file', array(
                    'label' => 'Favicon',
                    'module' => 'empresas',
                    'file' => (isset($item->favicon) && $item->favicon) ? $item->favicon : null,
                    'dimensions' => array(
                        'w' => 16,
                        'h' => 16
                    ),
                    'key' => 1,
                    'id' => 'fileuploadImage-favicon',
                    'name' => 'favicon',
                    'upload' => site_url('gallery/upload/image')
                ));

                $this->load->view('gallery/single-file', array(
                    'label' => 'Logo',
                    'module' => 'empresas',
                    'file' => (isset($item->image) && $item->image) ? $item->image : null,
                    'dimensions' => array(
                        'w' => '197',
                        'h' => '36'
                    ),
                    'id' => 'fileuploadImage-primary',
                    'key' => 1,
                    'name' => 'image',
                    'upload' => site_url('gallery/upload/image')
                ));
            ?>
        </div>
        <?php   } ?>
    </div>

    <div class="clearfix"></div>

    <div class="separator col-sm-12 text-center">
        <a href="<?php echo site_url($current_module->slug); ?>" class="btn btn-default"><?php echo T_('Cancelar'); ?></a>
        <button type="submit" class="btn btn-primary"><?php echo isset($item) ? T_('Salvar') : T_('Cadastrar'); ?></button>
    </div>
</form>
