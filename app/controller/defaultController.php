<?php
/**
 * 默认控制器类
 * 用于测试
 * @category app/controller
 * @author luis <john.doe@example.com>
 */
class defaultController extends Controller
{
    /**
     * 默认Action
     */
    public function indexAction()
    {
        $users_model = new model_users();
        print_r($users_model->getAllRecords());

//        $data = array(
//            'name' => '什么问题',
//            'age' => '55',
//            'addr' => '八嘎',
//        );
//        $bool = $users_model->addRecord($data);
//        echo $bool;
        $redis = lib_redis::getInstance();
        $redis->set('num', 12);
        echo $redis->get('num').'   ';
        echo $redis->incr('num').'   ';
        echo $redis->decr('num');
    }
    
    public function testAction()
    {
        echo '哈哈哈';

        $this->assign('cc', '测试短标签!');
        $this->assign('str', 'Hello World!');
        $this->display('default');
    }
    
    private function Fibonacci()
    {
        $sum = 0;
        for($i = 0; ;$i++)
        {
            if($i == 0 || $i == 1)
            {
                $fibonacci[] = $i;
            }
            if($i >= 2)
            {
                $sum = $fibonacci[$i - 2] + $fibonacci[$i - 1];
                if($sum < 100)
                {
                    $fibonacci[] = $sum;
                }
                else
                {
                    break;
                }
            }
        }
        
        print_r($fibonacci);
    }
}
