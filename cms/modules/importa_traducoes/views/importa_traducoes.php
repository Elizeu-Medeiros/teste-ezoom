<div class="tab-content col-sm-12">
    <br>
    <div class="col-sm-12 upload-box">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-check">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    <?php echo T_('Importar Traduções'); ?>
                </div>
            </div>
            <div class="panel-body" data-upload="importa_traducoes/import">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-upload">
                            <span class="fa fa-file"></span>&nbsp; <?php echo T_('Escolher arquivo'); ?>
                        </button>
                    </span>
                    <input type="file" class="hidden" name="file_series">
                    <input type="text" class="form-control" readonly>
                    <span class="input-group-btn">
                        <button class="btn btn-primary btn-send"><?php echo T_('Importar'); ?> &nbsp;<span class="fa fa-arrow-right"></span></button>
                    </span>
                </div>
                <br>
                <small><?php echo T_('O arquivo a ser importado deve seguir o mesmo padrão do exportado no módulo de exportação das traduções.'); ?></small><br>
                <small>Formatos aceitos: xls, xlsx ou zip.</small>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-check">
                    <i class="fa fa-table" aria-hidden="true"></i>
                    <?php echo T_('Resultado'); ?>
                </div>
            </div>
            <div class="panel-body" id="importResult">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo T_('Arquivo'); ?></th>
                            <th><?php echo T_('Tabela'); ?></th>
                            <th><?php echo T_('Registros'); ?></th>
                            <th><?php echo T_('Status'); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    small{
        color:#888;
    }
    .upload-box{
        max-width:800px;
    }
    .btn.loading{
        position:relative;
        background-image: url('modules/comum/assets/img/loading-white.gif');
        background-size: auto 70%;
        background-repeat:no-repeat;
        background-position: center center;
        color: transparent;
        transition:0s;
    }
</style>