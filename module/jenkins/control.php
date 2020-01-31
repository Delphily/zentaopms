<?php

/**
 * The control file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class jenkins extends control
{
    /**
     * jenkins constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $repoID = $this->session->repoID;
        foreach($this->lang->repo->menu as $key => $menu)
        {
            common::setMenuVars($this->lang->ci->menu, $key, $repoID);
        }

        if(common::hasPriv('jenkins', 'create') and strpos(',browsejob,', $this->methodName) > -1) {
            $this->lang->modulePageActions = html::a(helper::createLink('jenkins', 'create'), "<i class='icon icon-plus text-muted'></i> " . $this->lang->jenkins->create, '', "class='btn'");
        }
    }

    /**
     * Browse jenkinss.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->jenkinsList   = $this->jenkins->listAll($orderBy, $pager);

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->browse;
        $this->view->position[] = $this->lang->jenkins->common;
        $this->view->position[] = $this->lang->jenkins->browse;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $this->jenkins->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->create;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->jenkins->common);
        $this->view->position[] = $this->lang->jenkins->create;

        $this->display();
    }

    /**
     * Edit a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $jenkins = $this->jenkins->getByID($id);
        if($_POST)
        {
            $this->jenkins->update($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->edit;
        $this->view->position[] = html::a(inlink('browse'), $this->lang->jenkins->common);
        $this->view->position[] = $this->lang->jenkins->edit;

        $this->view->jenkins    = $jenkins;

        $this->display();
    }

    /**
     * Delete a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->jenkins->delete(TABLE_JENKINS, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }
}