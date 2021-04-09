  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">E-mail recebido em: <?php echo $item->date.' às '.$item->hour; ?></h4>
      </div>
      <div class="modal-body">
        <dl class="list-contact">

            <?php if (isset($item->name) &&  $item->name) { ?>
            <dt>Nome</dt> <dd><?php echo $item->name ?></dd>

            <?php } if (isset($item->email) &&  $item->email) { ?>
            <dt>E-mail</dt> <dd><?php echo $item->email ?></dd>

            <?php } if (isset($item->phone) &&  $item->phone) { ?>
            <dt>Telefone</dt> <dd><?php echo $item->phone ?></dd>

            <?php } if (isset($item->unit) &&  $item->unit) { ?>
            <dt>Unidade</dt> <dd><?php echo $item->unit ?></dd>
            <?php } ?>
        </dl>
      <h4 class="text-center">Alunos</h4>
            <?php foreach($item->students as $student){ ?>
              <dl class="list-contact">
                  <dt>Nome</dt> <dd><?php echo $student->name ?></dd>
                  <dt>Nascimento</dt> <dd><?php echo $student->birth ?></dd>
                  <dt>Ano desejado</dt> <dd><?php echo $student->level ?></dd>
              </dl>
            <?php } ?>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
