<?php

/**
 * adminlog.class.php 管理员日志类
 *
 * @version		$Id$
 * @createtime		2018/03/01
 * @updatetime		2018/03/01
 * @author         空竹
 * @copyright		Copyright (c) 芝麻开发 (http://www.zhimawork.com)
 */

class Adminlog {

	public function __construct() {
	}

     /**
      * Adminlog::add() 记录管理员日志
      * 
	  * @param string $adminid   管理员ID
      * @param string $log       日志内容
      * 
      * @return
      */
     
    static public function add($log){
		if(empty($log)) throw new Exception('日志内容不能为空', 102);

		$adminid = Admin::getSession();

        return Table_adminlog::add($adminid, $log);
    }
    
	/** 
	 * Adminlog::logList()    管理员日志记录列表
	 * 
	 * @param integer $page        当前页
	 * @param integer $pagesize    每页大小
	 * 
	 * @return
	 */
	static public function logList($page = 1, $pagesize = 20){
	    return Table_adminlog::getList($page, $pagesize);
	}
    
    /**
     * Adminlog::logCount()  管理员日志记录总数
     * 
     * @return
     */
    static public function logCount(){
	    return Table_adminlog::getCount();
	}

	
}
?>