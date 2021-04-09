<div id="login">
    <h4 class="text-center"><i class="fa fa-lock"></i> CMS</h4>
    <div class="separator col-xs-12"></div>
    <div id="login-lock"><img src="<?php echo base_img('logo-color.png'); ?>" /></div>
    <div class="col-sm-6 col-sm-offset-3" id="login-form">
        <form role="form" id="validateSubmitForm1" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo T_('Usuário/E-mail'); ?></label>
                <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="<?php echo T_('Digite seu E-mail ou seu nome de Usuário'); ?>" autofocus>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1"><?php echo T_('Senha'); ?></label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="<?php echo T_('Digite sua Senha'); ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-block"><?php echo T_('Entrar'); ?></button>
            <div id="login-forgot">
                <a href="<?php echo site_url('esqueci-minha-senha'); ?>"><?php echo T_('Esqueci minha senha'); ?></a>
            </div>
        </form>
    </div>
</div>