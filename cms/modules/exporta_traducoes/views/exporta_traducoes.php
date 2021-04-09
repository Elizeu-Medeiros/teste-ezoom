<form action="<?php echo site_url($current_module->slug .'/export') ?>" id="validateSubmitForm" class="form-horizontal" role="form" enctype="multipart/form-data" method="post">

    <div class="tab-content col-sm-12">
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="form-check">
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        <?php echo T_('Configurações'); ?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-xs-12 form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input check-column" id="clearTags" name="config[cleartags]" value="1">
                            <label class="form-check-label" for="clearTags"><?php echo T_('Remover tags HTML'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="form-check">
                        <i class="fa fa-language" aria-hidden="true"></i>
                        <?php echo T_('Linguagens para tradução'); ?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-xs-12 form-group">
                        <?php foreach ($this->lang->supported_lang as $key => $value) { if($key == 'pt') continue; ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input check-column" id="lang<?php echo $key; ?>" name="config[langs][]" value="<?php echo $value; ?>">
                                <label class="form-check-label" for="lang<?php echo $key; ?>"><?php echo ucfirst($value); ?></label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content col-sm-12">
        <div class="organize col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="form-check">
                        <i class="fa fa-table" aria-hidden="true"></i>
                        <?php echo T_('Tabelas'); ?>
                    </div>
                </div>
                <div class="panel-body" style="padding-left:0px; padding-right:0px;">
                    <?php foreach ($tables as $table_key => $table) { ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="panel panel-default disabled">
                                <div class="panel-heading">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input check-table" id="<?php echo 'table'.$table_key; ?>" name="tables[<?php echo $table_key ?>][table_name]" value="<?php echo $table['table_name']; ?>">
                                        <label class="form-check-label" for="<?php echo 'table'.$table_key; ?>"><?php echo $table['table_name']; ?></label>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-xs-12 form-group">
                                        <?php foreach ($table['table_columns'] as $column_key => $column) {
                                            if(strpos($column, 'id_') === false){ ?>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input check-column" id="<?php echo 'column'.$table_key.$column_key; ?>" name="tables[<?php echo $table_key; ?>][columns][<?php echo $column_key ?>]" value="<?php echo $column; ?>" disabled>
                                                    <label class="form-check-label" for="<?php echo 'column'.$table_key.$column_key; ?>"><?php echo $column; ?></label>
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" class="id-column" name="tables[<?php echo $table_key; ?>][columns][<?php echo $column_key ?>]" value="<?php echo $column; ?>" disabled>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 text-center">
        <a href="<?php echo site_url($current_module->slug); ?>" class="btn btn-default"><?php echo T_('Cancelar'); ?></a>
        <button type="submit" class="btn btn-primary"><?php echo T_('Exportar'); ?></button>
    </div>
</form>

<style>
    .panel.disabled .panel-body{
        background:#f5f5f5;
        color:#b9b9b9;
    }
    .organize .panel-body {
        display: flex;
        flex-wrap: wrap;
    }
    .organize .panel-body > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }
    .form-check .form-check-input{
        margin-right:2px;
    }
    .form-check .form-check-label{
        position:relative;
        top:-2px;
    }
</style>