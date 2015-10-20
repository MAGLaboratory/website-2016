<?php
namespace Maglab\Members;
class Procurement extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->get('/members/users', $admin_mw, [$this, 'index']);
  }
}
